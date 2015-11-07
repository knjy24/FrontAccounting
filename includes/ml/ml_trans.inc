<?php 

function get_gl_accounts($from=null, $to=null, $type=null)
{
	$sql = "SELECT ".TB_PREF."chart_master.*,".TB_PREF."chart_types.name AS AccountTypeName
		FROM ".TB_PREF."chart_master,".TB_PREF."chart_types
		WHERE ".TB_PREF."chart_master.account_type=".TB_PREF."chart_types.id";
	if ($from != null)
		$sql .= " AND ".TB_PREF."chart_master.account_code >= ".db_escape($from);
	if ($to != null)
		$sql .= " AND ".TB_PREF."chart_master.account_code <= ".db_escape($to);
	if ($type != null)
		$sql .= " AND account_type=".db_escape($type);
	$sql .= " ORDER BY account_code";

	return db_query($sql, "could not get gl accounts");
}

function is_account_balancesheet($code)
{
	$sql = "SELECT ".TB_PREF."chart_class.ctype FROM ".TB_PREF."chart_class, "
		.TB_PREF."chart_types, ".TB_PREF."chart_master
		WHERE ".TB_PREF."chart_master.account_type=".TB_PREF."chart_types.id AND
		".TB_PREF."chart_types.class_id=".TB_PREF."chart_class.cid
		AND ".TB_PREF."chart_master.account_code=".db_escape($code);

	$result = db_query($sql,"could not retreive the account class for $code");
	$row = db_fetch_row($result);
	return $row[0] > 0 && $row[0] < CL_INCOME;
}

function get_fiscalyear_begin_for_date($date)
{
 	$date = date2sql($date);
	$sql = "SELECT begin FROM ".TB_PREF."fiscal_year WHERE '$date' >= begin AND '$date' <= end";
	$result = db_query($sql, "could not get begin date of the fiscal year");
	$row = db_fetch_row($result);
	if ($row != false)
		return sql2date($row[0]);
	else
		return begin_fiscalyear();
}

function get_gl_balance_from_to($from_date, $to_date, $account, $dimension=0, $dimension2=0)
{
	$from = date2sql($from_date);
	$to = date2sql($to_date);

    $sql = "SELECT SUM(amount) FROM ".TB_PREF."gl_trans
		WHERE account='$account'";
	if ($from_date != "")
		$sql .= "  AND tran_date > '$from'";
	if ($to_date != "")
		$sql .= "  AND tran_date < '$to'";
	if ($dimension != 0)
  		$sql .= " AND dimension_id = ".($dimension<0?0:db_escape($dimension));
	if ($dimension2 != 0)
  		$sql .= " AND dimension2_id = ".($dimension2<0?0:db_escape($dimension2));

	$result = db_query($sql, "The starting balance for account $account could not be calculated");

	$row = db_fetch_row($result);
	return $row[0];
}

function get_gl_transactions($from_date, $to_date, $trans_no=0,
	$account=null, $dimension=0, $dimension2=0, $filter_type=null,
	$amount_min=null, $amount_max=null)
{
	global $show_voided_gl_trans;
	
	$from = date2sql($from_date);
	$to = date2sql($to_date);

	$sql = "SELECT ".TB_PREF."gl_trans.*, "
		.TB_PREF."chart_master.account_name FROM "
		.TB_PREF."gl_trans
			LEFT JOIN ".TB_PREF."voided v ON "
			.TB_PREF."gl_trans.type_no=v.id AND v.type=".TB_PREF."gl_trans.type,"
			.TB_PREF."chart_master"
		." WHERE ".TB_PREF."chart_master.account_code=".TB_PREF."gl_trans.account
		AND ISNULL(v.date_)
		AND tran_date >= '$from'
		AND tran_date <= '$to'";
	if (isset($show_voided_gl_trans) && $show_voided_gl_trans == 0)
		$sql .= " AND ".TB_PREF."gl_trans.amount <> 0"; 
	if ($trans_no > 0)
		$sql .= " AND ".TB_PREF."gl_trans.type_no LIKE ".db_escape('%'.$trans_no);

	if ($account != null)
		$sql .= " AND ".TB_PREF."gl_trans.account = ".db_escape($account);

	if ($dimension != 0)
  		$sql .= " AND ".TB_PREF."gl_trans.dimension_id = ".($dimension<0?0:db_escape($dimension));

	if ($dimension2 != 0)
  		$sql .= " AND ".TB_PREF."gl_trans.dimension2_id = ".($dimension2<0?0:db_escape($dimension2));

	if ($filter_type != null AND is_numeric($filter_type))
		$sql .= " AND ".TB_PREF."gl_trans.type= ".db_escape($filter_type);
		
	if ($amount_min != null)
		$sql .= " AND ABS(".TB_PREF."gl_trans.amount) >= ABS(".db_escape($amount_min).")";
	
	if ($amount_max != null)
		$sql .= " AND ABS(".TB_PREF."gl_trans.amount) <= ABS(".db_escape($amount_max).")";

	$sql .= " ORDER BY tran_date, counter";

	return db_query($sql, "The transactions for could not be retrieved");
}

?>