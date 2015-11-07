<?php

function checkvouchexists($no)
{
	$sql = "SELECT * from ".TB_PREF."comm_voucher where invoice_no = ".db_escape($no)."";
	$res = db_query($sql, "Cannot check if transaction already exists.");
	$ret = db_fetch_row($res);
	return $ret[0];
}

function get_salesman_trans($imc, $custom)
{
	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT DISTINCT ".TB_PREF."debtor_trans.*,
		ov_amount+ov_discount AS InvoiceTotal, ".TB_PREF."voided.type as Voided,
		".TB_PREF."debtors_master.name AS DebtorName, ".TB_PREF."debtors_master.curr_code, ".TB_PREF."cust_branch.br_name,
		".TB_PREF."cust_branch.contact_name, ".TB_PREF."salesman.*, ".TB_PREF."customized.customized_no
		FROM ".TB_PREF."debtor_trans inner join ".TB_PREF."customized 
		on ".TB_PREF."debtor_trans.type = ".TB_PREF."customized.type
		LEFT JOIN ".TB_PREF."voided on ".TB_PREF."voided.type=".TB_PREF."debtor_trans.type
		and ".TB_PREF."voided.id=".TB_PREF."debtor_trans.trans_no,
		".TB_PREF."debtors_master,
		 ".TB_PREF."sales_orders,".TB_PREF."cust_branch,
			".TB_PREF."salesman 
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		    AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
		    AND ".TB_PREF."debtor_trans.ov_amount = ".TB_PREF."debtor_trans.alloc
		    AND (".TB_PREF."debtor_trans.type=".ST_SALESINVOICE.")
		    AND ".TB_PREF."salesman.salesman_code = ".db_escape($imc)."";
	
		if ($custom!= "")
			$sql .= " AND ".TB_PREF."customized.customized_no=".db_escape($custom);

		$sql .= " ORDER BY ".TB_PREF."customized.customized_no ASC";

	return db_query($sql, "Error getting order details");
}

function get_sql_view_voucher($imc)
{
	$sql = "SELECT DISTINCT ".TB_PREF."debtor_trans.*,
		ov_amount+ov_discount AS InvoiceTotal,
		".TB_PREF."debtors_master.name AS DebtorName, ".TB_PREF."debtors_master.curr_code, ".TB_PREF."cust_branch.br_name,
		".TB_PREF."cust_branch.contact_name, ".TB_PREF."salesman.*, ".TB_PREF."customized.customized_no
		FROM ".TB_PREF."debtor_trans inner join ".TB_PREF."customized on ".TB_PREF."debtor_trans.type = ".TB_PREF."customized.type, ".TB_PREF."debtors_master,
		 ".TB_PREF."sales_orders,".TB_PREF."cust_branch,
			".TB_PREF."salesman 
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		    AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
		    AND (".TB_PREF."debtor_trans.type=".ST_SALESINVOICE.")
		    AND ".TB_PREF."salesman.salesman_code = ".db_escape($imc)."
		ORDER BY ".TB_PREF."customized.customized_no ASC";

	return $sql;
}

function get_selected_salesman($trans_no){
	$sql = "SELECT DISTINCT ".TB_PREF."debtor_trans.*,
		ov_amount+ov_discount AS InvoiceTotal,
		".TB_PREF."debtors_master.name AS DebtorName, ".TB_PREF."debtors_master.curr_code, ".TB_PREF."cust_branch.br_name,
		".TB_PREF."cust_branch.contact_name, ".TB_PREF."salesman.*, ".TB_PREF."customized.customized_no
		FROM ".TB_PREF."debtor_trans inner join ".TB_PREF."customized on ".TB_PREF."debtor_trans.type=".TB_PREF."customized.type, ".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		     AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
		    AND (".TB_PREF."debtor_trans.type=".ST_SALESINVOICE.")
			AND ".TB_PREF."customized.customized_no = '$trans_no'";

	return db_query($sql, "Error getting order details");
}

function insert_comm_voucher($sm, $invoice, $client, $gross, $commission, $tax, $return, $discount, $net_commission, $date) 
{	
	$SQLDate = date2sql($date);

	$sql = "INSERT INTO ".TB_PREF."comm_voucher (imc, invoice_no, client, gross, commission, with_tax, returns, discount, net_commission, date) VALUES 
			(".db_escape($sm).", ".db_escape($invoice).", ".db_escape($client).", ".db_escape($gross).", ".db_escape($commission).", ".db_escape($tax).", ".db_escape($return).", ".db_escape($discount).", ".db_escape($net_commission).", '$SQLDate')";
   	db_query($sql,"Commission voucher could not be added");

}

function get_returns($type)
{
	$sql = "SELECT * from ".TB_PREF."debtor_trans where order_ = ".db_escape($type)." and type= ".ST_CUSTCREDIT."";
	return db_query($sql, "Error getting returns");
}


function get_return_details($type)
{
	$sql = "SELECT a.*, b.* from ".TB_PREF."debtor_trans a INNER JOIN ".TB_PREF."customized b on a.type=b.type AND a.trans_no=b.type_no where a.order_ = ".db_escape($type)." AND a.type = ".ST_CUSTCREDIT."";
	return db_query($sql, "Error getting returns");
}

function get_voucher_no()
{
	$sql = "SELECT invoice_no from ".TB_PREF."comm_voucher";
	return db_query($sql, "Error getting invoice number");
}

function checkdiscount($type, $trans_no, $account)
{
	$sql = "SELECT amount from ".TB_PREF."gl_trans where type = ".db_escape($type)." AND type_no=".db_escape($trans_no)." and account=".db_escape($account)."";
	$res = db_query($sql, "Cannot check if transaction already exists.");
	$ret = db_fetch_row($res);
	return $ret[0];
}

function get_pr_details($type, $trans_no)
{
	$sql = "SELECT a.*, DATE_FORMAT(c.tran_date, '%m-%d-%Y') as prDate, b.customized_no, c.ov_discount, c.reference, c.ov_amount as InvoiceAmt, DATE_FORMAT(b.date, '%m-%d-%Y') as orDAte from ".TB_PREF."cust_allocations a 
	INNER JOIN ".TB_PREF."customized b on a.trans_type_from=b.type AND a.trans_no_from= b.type_no
	INNER JOIN ".TB_PREF."debtor_trans c on b.type=c.type AND b.type_no=c.trans_no
	where a.trans_type_to = ".db_escape($type)." AND a.trans_no_to = ".db_escape($trans_no)." AND a.trans_type_from = ".ST_CUSTPAYMENT."";

	return db_query($sql, "Error getting PR# details");
}

function get_total_payment($type, $trans_no)
{
	$sql = "SELECT SUM(amt) from ".TB_PREF."cust_allocations a 
	INNER JOIN ".TB_PREF."debtor_trans c on a.trans_type_to=c.type AND a.trans_no_to=c.trans_no
	where a.trans_type_to = ".db_escape($type)." AND a.trans_no_to = ".db_escape($trans_no)." AND a.trans_type_from = ".ST_CUSTPAYMENT."";

	$res = db_query($sql, "Could not retrieve payment at the moment.");
	$ret = db_fetch_row($res);
	return $ret[0];
}

function get_commission_details($type, $trans_no)
{
	$sql = "SELECT DATE_FORMAT(a.date, '%m-%d-%Y') as tranDate, a.commission, a.with_tax, a.net_commission from 
			".TB_PREF."comm_voucher a INNER JOIN ".TB_PREF."customized b on a.invoice_no=b.customized_no INNER JOIN 
			".TB_PREF."debtor_trans c on b.type=c.type AND b.type_no=c.trans_no where c.type=".db_escape($type)." AND c.trans_no=".db_escape($trans_no)."";

	return db_query($sql, "could not retrieve transaction");
}

?>
   