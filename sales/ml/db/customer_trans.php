<?php
function checkIfInvoiceExists($invoice_num)
{
	
	$sql = "SELECT a.customized_no from ".TB_PREF."debtor_trans trans INNER JOIN ".TB_PREF."customized a
			on trans.type=a.type AND trans.trans_no=a.type_no where a.customized_no = ".db_escape($invoice_num)."";
	$result = db_query($sql, "could not get transaction");
	$row = db_fetch($result);
	if ($db_fetch_row($row) > 0)
		return true;
	else
		return false;
}

function checkIfCreditNoExists($credit_num){

	$sql = "SELECT count(trans_no) from ".TB_PREF."debtor_trans where customized_no = ".db_escape($credit_num)."";
	$result = db_query($sql, "could not get transaction");

	$row = db_fetch_row($result);
	return $row[0];
}

function get_invoice_trans($from, $to, $invoice, $client)
{
	$from = date2sql($from);
	$to = date2sql($to);

	$sql = "SELECT a.trans_no, a.type, a.tran_date, a.ov_discount, b.name, c.customized_no from ".TB_PREF."debtor_trans a inner join ".TB_PREF."debtors_master b on a.debtor_no=b.debtor_no inner join ".TB_PREF."gl_trans c on a.type = c.type where a.type = 10";

	if ($from != null)
		$sql .= " AND a.tran_date >=".db_escape($from); 
	if ($to != null)
		$sql .= " AND a.tran_date <=".db_escape($to); 
	if ($client != null)
		$sql .= " AND a.debtor_no =".db_escape($client); 
	if ($invoice != null)
		$sql .= " AND c.customized_no =".db_escape($invoice); 

	$sql .= "ORDER BY c.customized_no, a.tran_date "; 
	
	return db_query($sql, "could not retrieve invoices");
}

function get_custom_no($trans_id, $trans_type)
{
	$sql = "SELECT customized_no from ".TB_PREF."customized where type = ".db_escape($trans_type)." AND type_no = ".db_escape($trans_id)."";

	$result = db_query($sql, "could not retrieve Number.");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_custom_date($trans_id, $trans_type)
{
	$sql = "SELECT date from ".TB_PREF."customized where type = ".db_escape($trans_type)." AND type_no = ".db_escape($trans_id)."";

	$result = db_query($sql, "could not retrieve date.");
	$row = db_fetch_row($result);
	return $row[0];
}



function get_imc_name($branch)
{
	$sql = "SELECT b.salesman_name from ".TB_PREF."cust_branch a INNER JOIN ".TB_PREF."salesman b on a.salesman = b.salesman_code where a.branch_code = ".db_escape($branch)."";
	$result = db_query($sql, "Could not retrieve IMC");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_imc_code($branch)
{
	$sql = "SELECT b.imc_code from ".TB_PREF."cust_branch a INNER JOIN ".TB_PREF."salesman b on a.salesman = b.salesman_code where a.branch_code = ".db_escape($branch)."";
	$result = db_query($sql, "Could not retrieve IMC");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_salesman_code($branch)
{
	$sql = "SELECT b.salesman_code from ".TB_PREF."cust_branch a INNER JOIN ".TB_PREF."salesman b on a.salesman = b.salesman_code where a.branch_code = ".db_escape($branch)."";
	$result = db_query($sql, "Could not retrieve IMC");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_discount($account, $type, $trans_no)
{
	$sql = "SELECT sum(amount) from ".TB_PREF."gl_trans where type = ".db_escape($type)." AND type_no = ".db_escape($trans_no)." AND account= ".db_escape($account)."";
	$result = db_query($sql, "Could not retrieve IMC");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_return_discount($account, $type, $trans_no)
{
	$sql = "SELECT amount from ".TB_PREF."gl_trans where type = ".db_escape($type)." AND type_no = ".db_escape($trans_no)." AND account= ".db_escape($account)."";
	return db_query($sql, "Could not retrieve IMC");

}

function get_sales_invoice_no($order, $type)
{
	$sql = "SELECT a.customized_no from ".TB_PREF."customized a INNER JOIN ".TB_PREF."debtor_trans b 
			on a.type=b.type AND a.type_no=b.trans_no where b.order_ = ".db_escape($order)." and b.type = ".db_escape($type)."";
	$result = db_query($sql, "Could not get Sales Invoice No.");
	$row = db_fetch_row($result);
	return $row[0];
}

//Karen 07/22/2015
function get_sales_invoice_details($order, $type)
{
	$sql = "SELECT b.ov_amount, b.alloc from ".TB_PREF."customized a INNER JOIN ".TB_PREF."debtor_trans b 
			on a.type=b.type AND a.type_no=b.trans_no where b.order_ = ".db_escape($order)." and b.type = ".db_escape($type)."";
	$result = db_query($sql, "Could not get Sales Invoice No.");
	$row = db_fetch_row($result);
	if (number_format($row[0], 2) == number_format($row[1], 2))
		return true;
	else
		return false;
}

function get_payment_invoice_details($no, $type)
{
	$sql = "SELECT b.ov_amount, b.alloc from ".TB_PREF."cust_allocations a INNER JOIN ".TB_PREF."debtor_trans b 
			on a.trans_type_to=b.type AND a.trans_no_to=b.trans_no where a.trans_type_from = ".db_escape($type)." 
			and a.trans_no_from = ".db_escape($no)." and a.trans_type_to = ".ST_SALESINVOICE."";
	$result = db_query($sql, "Could not get Sales Invoice No.");
	$row = db_fetch_row($result);
	if ($row[0] == $row[1])
		return true;
	else
		return false;
}

function getContact($imc, $debtor_no, $branch_code)
{
	$sql = "SELECT DISTINCT CONCAT(d.name, ' ',d.name2) AS contactName, d.name FROM ".TB_PREF."cust_branch b  INNER JOIN
".TB_PREF."crm_contacts c on b.debtor_no=c.entity_id INNER JOIN ".TB_PREF."crm_persons d on c.person_id=d.id where b.salesman = ".db_escape($imc)."
	AND b.debtor_no = ".db_escape($debtor_no)." AND b.branch_code = ".db_escape($branch_code)." and c.type='customer'";
	$result = db_query($sql, "could not get IMC");
	$row = db_fetch_row($result);

	if ($row[0] != '' && $row[1] != '')
		return $row[0];
	else
		return $row[1];
}

function getContactNumber($imc, $debtor_no, $branch_code)
{
	$sql = "SELECT DISTINCT CONCAT (d.phone, '/', d.phone2) AS contactNumber, d.phone, d.phone2 FROM ".TB_PREF."cust_branch b  INNER JOIN
".TB_PREF."crm_contacts c on b.debtor_no=c.entity_id INNER JOIN ".TB_PREF."crm_persons d on c.person_id=d.id where c.type='customer' and b.salesman = ".db_escape($imc)."
	AND b.debtor_no = ".db_escape($debtor_no)." AND b.branch_code = ".db_escape($branch_code)."";
	$result = db_query($sql, "could not get IMC");

	$row = db_fetch_row($result);
	
	if ($row[0] != '' && $row[1] != '')
		return $row[0];
	if ($row[0] == '' && $row[1] != '')
		return $row[1];
	if ($row[1] == '' && $row[0] != '')
		return $row[0];
}

function getUnitPrice($stock_id)
{
	$sql = "SELECT price from ".TB_PREF."purch_data where stock_id=".db_escape($stock_id)."";
	$result = db_query($sql, "Could not get price");
	$row = db_fetch_row($result);
	return $row[0];
}


?>