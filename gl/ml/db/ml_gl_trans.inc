<?php

function getExpenseAccount()
{
	$sql = "SELECT account_code from ".TB_PREF."chart_master master 
			INNER JOIN ".TB_PREF."chart_types type
			ON master.account_type = type.id INNER JOIN ".TB_PREF."chart_class cl 
			ON type.class_id = cl.ctype where cl.ctype=4";

	return db_query($sql, "could not fetch accounts");
}

function update_bank_trans_date($trans_type, $trans_id, $date_)
{
	$sql = "UPDATE FROM ".TB_PREF."bank_trans where type=";
}

function get_jv_no($type, $trans_no)
{
	$sql = "SELECT customized_no from ".TB_PREF."customized where type=".db_escape($type)." and type_no=".db_escape($trans_no);
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_check_num($type, $trans_no)
{
	$sql = "SELECT check_num from ".TB_PREF."customized where type=".db_escape($type)." and type_no=".db_escape($trans_no);
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_address($type, $trans_no)
{
	$sql = "SELECT customized_field from ".TB_PREF."customized where type=".db_escape($type)." and type_no=".db_escape($trans_no);
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_bank_name($code)
{
	$sql = "SELECT bank_name from ".TB_PREF."bank_accounts where account_code=".db_escape($code);
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}
function get_year()
{
	global $path_to_root;
	include_once($path_to_root . "/admin/db/company_db.inc");
	$year = get_company_pref('f_year');

	$sql = "SELECT * FROM ".TB_PREF."fiscal_year WHERE id=".db_escape($year);

	$result = db_query($sql, "could not get current fiscal year");

	return db_fetch($result);
}

function checkNoIfExists($jv_no, $year_begin, $year_end)
{
	$sql = "SELECT count(a.type) from ".TB_PREF."customized a inner join ".TB_PREF."gl_trans b on a.type=b.type where a.customized_no = ".db_escape($jv_no)." AND 
			a.type = ".ST_JOURNAL." AND b.tran_date BETWEEN ".db_escape($year_begin)." and ".db_escape($year_end)."";
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}

function checkNoIfExists2($jv_no)
{
	$sql = "SELECT count(type) from ".TB_PREF."gl_trans where customized_no = ".db_escape($jv_no)." AND type = ".ST_BANKPAYMENT."";
	$result = db_query($sql, "could not get GL Transaction");
	$row = db_fetch_row($result);
	return $row[0];
}
/*=====================================================BOOKLET========================================================================*/
function add_booklet($type, $year, $begin, $end, $status)
{
	$sql = "INSERT INTO ".TB_PREF."booklet (type, year, begin, end, stat, inactive) VALUES (".db_escape($type).", ".db_escape($year).", ".db_escape($begin).", ".db_escape($end).", ".db_escape($status).", 0)";
   return db_query($sql,"The booklet could not be added");
}


function check_series_open($type, $year, $status){
	$sql = "SELECT COUNT(id) FROM ".TB_PREF."booklet where type = ".db_escape($type)." AND year = ".db_escape($year)." AND stat=0";
	//db_query($sql);

	$result = db_query($sql, "could not get booklet");

	$row = db_fetch_row($result);
	return $row[0];

}

function update_status($type, $year, $beg, $end, $status)
{
	$sql = "UPDATE ".TB_PREF."booklet SET stat=".db_escape($status)."
		 WHERE  type = ".db_escape($type)." AND year = ".db_escape($year)." AND begin = ".db_escape($begin)." AND  end = ".db_escape($end)."";
   	db_query($sql,"The selected booklet could not be updated");
}


function get_booklet($show_inactive)
{
	$sql = "SELECT id, type, year, inactive, begin, end, stat,
		CASE inactive
			WHEN 0 THEN 'Active'
			WHEN 1 THEN 'Inactive'
		END AS status,
		CASE stat
			WHEN 0 THEN 'Open'
			WHEN 1 THEN 'Close'
		END AS status1
		FROM ".TB_PREF."booklet";
	if (!$show_inactive) $sql .= " WHERE !inactive";
	return db_query($sql,"could not get booklet");
}

function get_selected_booklet($selected_id)
{
	$sql = "SELECT * FROM ".TB_PREF."booklet WHERE id=".db_escape($selected_id);

	$result = db_query($sql,"could not get booklet");
	return db_fetch($result);
}

function update_booklet($selected_id, $type, $year, $begin, $end, $status)
{
	$sql = "UPDATE ".TB_PREF."booklet SET type = ".db_escape($type).", year = ".db_escape($year).", begin = ".db_escape($begin).", end = ".db_escape($end).", stat=".db_escape($status)." WHERE id = ".db_escape($selected_id);
   	db_query($sql,"The selected booklet could not be updated");
}

function delete_booklet($selected_id)
{
	$sql="DELETE FROM ".TB_PREF."booklet WHERE id=".db_escape($selected_id);
	db_query($sql,"could not delete booklet");
}


/*===========================================================SERIES======================================================================*/

function add_series($no, $type, $year)
{
	$sql = "INSERT INTO ".TB_PREF."booklet (no, type, year) VALUES (".db_escape($no) . ", ".db_escape($type).", ".db_escape($year).")";
   return db_query($sql,"The booklet could not be added");
}

function check_series($years, $type){
	if ($type = ST_BANKPAYMENT)
		$sql = "SELECT begin from ".TB_PREF."booklet where type = 'Check Voucher' and year = ".db_escape($years)."";
	else if ($type = ST_JOURNAL)
		$sql = "SELECT begin from ".TB_PREF."booklet where type = 'Journal Voucher' and year = ".db_escape($years)."";
	$result = db_query($sql, "Could not get begin series.");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_series($show_inactive)
{
	$sql = "SELECT * FROM ".TB_PREF."booklet";
	if (!$show_inactive) $sql .= " WHERE !inactive";
	return db_query($sql,"could not get booklet");
}

function get_used_series($type)
{
	//if ($type == ST_DISBURSEMENT || $type == 55)
		$sql = "SELECT * from ".TB_PREF."booklet where type = ".db_escape($type)." and stat=0";
	//else if ($type == ST_JOURNAL || $type == 0)
	//	$sql = "SELECT * from ".TB_PREF."booklet where type = 'Journal Voucher' and year = ".db_escape($year)."";

	return db_query($sql, "could not get booklet.");
}

function check_series_exist($num)
{
	$sql = "SELECT count(id) from ".TB_PREF."customized where customized_no=".db_escape($num)."";
	$result = db_query($sql, "Could not check if number exists.");
	$row = db_fetch_row($result);
	return $row[0];
}

function check_booklet_exist($type, $year, $begin, $end)
{
	$sql = "SELECT count(id) from ".TB_PREF."booklet where type=".db_escape($type)." AND year=".db_escape($year)." AND begin = ".db_escape($begin)." AND end = ".db_escape($end)."";
	$result = db_query($sql, "Could not check if number exists.");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_selected_series($selected_id)
{
	$sql = "SELECT * FROM ".TB_PREF."booklet WHERE id=".db_escape($selected_id);

	$result = db_query($sql,"could not get booklet");
	return db_fetch($result);
}

function update_series($selected_id, $no, $type, $year)
{
	$sql = "UPDATE ".TB_PREF."booklet SET no=".db_escape($no).", type = ".db_escape($type).", year = ".db_escape($year)." WHERE id = ".db_escape($selected_id);
   	db_query($sql,"The selected booklet could not be updated");
}

function delete_series($selected_id)
{
	$sql="DELETE FROM ".TB_PREF."booklet WHERE id=".db_escape($selected_id);
	db_query($sql,"could not delete booklet");
}

function get_cv_no($year_begin, $year_end, $type)
{
		$sql = "SELECT max(a.customized_no) from ".TB_PREF."customized a INNER JOIN ".TB_PREF."gl_trans b on a.type = b.type where a.type = ".db_escape($type)." and b.tran_date BETWEEN ".db_escape($year_begin)." and ".db_escape($year_end)."";

	$result = db_query($sql, "could not get check number");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_customized_no($type, $type_no)
{
	$sql = "SELECT customized_no from ".TB_PREF."customized where type = ".db_escape($type)." AND type_no = ".db_escape($type_no)."";
	$result = db_query($sql, "could not retrieve transaction");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_next_series($type, $year)
{	
	//if ($type == ST_BANKPAYMENT)
		$sql = "SELECT max(customized_no) from ".TB_PREF."customized where type = ".db_escape($type)." and YEAR(date) =".db_escape($year);
	//else if ($type == ST_JOURNAL)
	//	$sql = "SELECT begin from ".TB_PREF."booklet where type = 'Journal Voucher' and year = ".db_escape($year)."";

	$result = db_query($sql, "Could not get begin series.");
	$row = db_fetch_row($result);
	return $row[0];
}

function write_trans($type, $trans_id, $jv_no, $address, $check_num)
{
	$sql = "INSERT INTO ".TB_PREF."customized (type, type_no, customized_no, customized_field, check_num) VALUES (".db_escape($type).", ".db_escape($trans_id).",
			".db_escape($jv_no).", ".db_escape($address).", ".db_escape($check_num).")";

	 db_query($sql, "Could not process transaction");

}

function firstSeries($begin, $year)
{
	$sql = "SELECT count(id) from ".TB_PREF."customized where customized_no=".db_escape($begin)." AND YEAR(date) = ".db_escape($year);
	$result = db_query($sql, "Could not get begin series.");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_sql_for_custom_inquiry($type, $voucher_no, $fromdate)
{
	$from = sql2date($fromdate);
	if ($type=="Check Voucher")
		$trans_type = ST_DISBURSEMENT;
	if ($type == "Journal Voucher")
		$trans_type = ST_JOURNAL;

	$sql = "SELECT DISTINCT IF(ISNULL(a.gl_seq),0,a.gl_seq) as gl_seq,
		DATE_FORMAT(gl.tran_date, '%m-%d-%Y') as tranDate,
		gl.type,
		cs.customized_no,
		com.memo_,
		IF(ISNULL(u.user_id),'',u.user_id) as user_id,
		gl.type_no, YEAR(gl.tran_date) as YearDate
		FROM ".TB_PREF."gl_trans as gl
		 LEFT JOIN ".TB_PREF."audit_trail as a ON
			(gl.type=a.type AND gl.type_no=a.trans_no)
		 LEFT JOIN ".TB_PREF."comments as com ON
			(gl.type=com.type AND gl.type_no=com.id)
		 LEFT JOIN ".TB_PREF."refs as refs ON
			(gl.type=refs.type AND gl.type_no=refs.id)
		 LEFT JOIN ".TB_PREF."users as u ON
			a.user=u.id
		INNER JOIN ".TB_PREF."customized cs on gl.type=cs.type AND gl.type_no=cs.type_no
		WHERE gl.tran_date >= '" . date2sql($from) . "'
		AND gl.amount!=0 AND gl.type=".db_escape($trans_type)."";

//	$sql = "SELECT DISTINCT DATE_FORMAT(a.tran_date, '%m-%d-%Y') as tranDate, a.type, b.customized_no, a.type_no, YEAR(a.tran_date) as YearDate, a.person_type_id from ".TB_PREF."gl_trans a 
	//		LEFT JOIN ".TB_PREF."audit_trail as ad ON (a.type=ad.type AND a.type_no=ad.trans_no)
	//	INNER JOIN ".TB_PREF."customized b on a.type=b.type AND a.type_no=b.type_no where a.type=".db_escape($trans_type)." and a.amount !=0";

	if ($voucher_no != 0)
		$sql .= " AND b.customized_no=".db_escape($voucher_no);

	return $sql;
}


?>