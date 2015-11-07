<?php

function db_has_salesman(){
    return check_empty_result("SELECT COUNT(*) FROM ".TB_PREF."salesman");
}

function get_imc(){
    $sql = "SELECT salesman_code, salesman_name FROM ".TB_PREF."salesman";
    $result = db_query($sql, "could not get pay rates");
    $type_array = array();
    while($myrow = db_fetch($result)){
	$type_array[$myrow['salesman_code']] = $myrow['salesman_name'];
    }
    return $type_array;
}

function get_sql_for_commission_voucher($imc, $invoice_no)
{
	$sql = "SELECT a.imc, a.invoice_no, a.client, DATE_FORMAT(a.date, '%m-%d-%Y') as tranDate from ".TB_PREF."comm_voucher a where a.imc= ".db_escape($imc)."";

	if ($invoice_no != 0)
		$sql .= " and a.invoice_no =".db_escape($invoice_no);

	$sql .= " ORDER BY a.invoice_no";
	return $sql;

}

?>