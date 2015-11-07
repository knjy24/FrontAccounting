<?php
function get_jv($type, $trans_no){
	$sql = "SELECT * FROM ".TB_PREF."series WHERE ref = ".db_escape($trans_no)." AND type = ".db_escape($type)."";
	return db_query($sql, "Could not retrieve Journal Voucher No.");
}

function get_jv_no(){
	$sql = "SELECT max(series_no) FROM ".TB_PREF."series where type = 5";
	$result = db_query($sql, "could not get booklet");

	$row = db_fetch_row($result);
	return $row[0];
}

function check_booklet_use(){
	$sql = "SELECT min(no), begin, end, id FROM ".TB_PREF."booklet where type = 'Journal Voucher' AND stat = 0 AND inactive = 0 and year = year(now())";
	return db_query($sql, "could not retrieve booklet");
}

function update_booklet_use($id){
	$sql = "UPDATE ".TB_PREF."booklet SET stat = 1 where id = ".db_escape($id)."";
	return db_query($sql, "could not update booklet");
}

?>