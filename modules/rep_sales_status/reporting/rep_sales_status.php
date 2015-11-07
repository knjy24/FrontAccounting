<?php

$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------

// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");
include_once($path_to_root . "/sales/ml/voucher_search.php");


//----------------------------------------------------------------------------------------------------

print_summary();

function getTransactions($imc, $from, $to, $type, $status)
{
	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT imc_code, salesman_name, salesman_code, ".TB_PREF."debtor_trans.branch_code, SUM(ov_amount + ov_discount) AS InvoiceTotal
		FROM ".TB_PREF."debtor_trans LEFT JOIN ".TB_PREF."voided v  ON v.type = ".TB_PREF."debtor_trans.type and v.id=".TB_PREF."debtor_trans.trans_no,
		".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		    AND (".TB_PREF."debtor_trans.type=".db_escape($type).") and ".TB_PREF."debtor_trans.payment_terms!=4
		    ";
    if ($fromdate != '')
    	$sql .= " AND ".TB_PREF."debtor_trans.tran_date >= '$fromdate'";
    if ($todate != '')
    	$sql .= " AND ".TB_PREF."debtor_trans.tran_date <= '$todate'";
    if ($imc != 0)
		$sql .= " AND ".TB_PREF."salesman.salesman_code =".db_escape($imc);
	if ($status == 1)
		$sql .= " AND ov_amount = alloc";
	if ($status == 2)
		$sql .= " AND ov_amount > alloc";
	if ($imc == 0 || $imc == '')
		$sql .= " GROUP BY ".TB_PREF."salesman.salesman_code";
	
    return db_query($sql,"No transactions were returned");
    

}

function getAlloc($imc, $type, $full_alloc=false, $partial_alloc=false)
{
	$sql = "SELECT SUM(ov_amount+ov_discount) AS InvoiceTotal
		FROM ".TB_PREF."debtor_trans LEFT JOIN ".TB_PREF."voided v  ON v.type = ".TB_PREF."debtor_trans.type 
		and v.id=".TB_PREF."debtor_trans.trans_no,
		".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		    AND (".TB_PREF."debtor_trans.type=".db_escape($type).") and ".TB_PREF."debtor_trans.payment_terms!=4
		    and ".TB_PREF."salesman.salesman_code=".db_escape($imc)." ";

	if ($full_alloc)
		$sql .= " AND ov_amount = alloc";
	if ($partial_alloc)
		$sql .= " AND ov_amount < alloc";

	$result = db_query($sql, "NO transactions returned.");
	$row = db_fetch_row($result);
	return $row[0];
		    

}
function getDiscountTotal($imc, $from, $to, $type, $account, $status)
{

	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT SUM(gl.amount) as Amount from ".TB_PREF."gl_trans gl
			INNER JOIN ".TB_PREF."debtor_trans debt on gl.type=debt.type AND gl.type_no=debt.trans_no
			INNER JOIN ".TB_PREF."cust_branch cust on debt.debtor_no=cust.debtor_no 
			where account=".db_escape($account)." and debt.type=".db_escape($type)." and cust.salesman=".db_escape($imc)." and debt.payment_terms!=4";
			//and gl.account='4-1015'";

	if ($fromdate != '')
		$sql .= " and debt.tran_date >='$fromdate'";
	if ($todate != '')
		$sql .= " and debt.tran_date <='$todate'";
	if ($status == 1)
		$sql .= " AND debt.ov_amount = alloc";
	if ($status == 2)
		$sql .= " AND debt.ov_amount < alloc";

	$sql .= " group by cust.salesman";

	$result = db_query($sql, "NO transactions returned.");
	$row = db_fetch_row($result);
	return $row[0];

}

function getReturnsTotal($imc, $from, $to, $type, $status)
{
	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT SUM(ov_amount+ov_discount) AS InvoiceTotal
		FROM ".TB_PREF."debtor_trans LEFT JOIN ".TB_PREF."voided v  ON v.type = ".TB_PREF."debtor_trans.type and v.id=".TB_PREF."debtor_trans.trans_no,
		".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		    AND (".TB_PREF."debtor_trans.type=".db_escape($type).") 
		    and ".TB_PREF."debtor_trans.payment_terms!=4
		    and ".TB_PREF."salesman.salesman_code=".db_escape($imc)."";
    if ($fromdate != '')
    	$sql .= " AND ".TB_PREF."debtor_trans.tran_date >= '$fromdate'";
    if ($todate != '')
    	$sql .= " AND ".TB_PREF."debtor_trans.tran_date <= '$todate'";
    if ($status == 1)
		$sql .= " AND ".TB_PREF."debtor_trans.ov_amount = alloc";
	if ($status == 2)
		$sql .= " AND ".TB_PREF."debtor_trans.ov_amount < alloc";


	$result = db_query($sql, "NO transactions returned.");
	$row = db_fetch_row($result);
	return $row[0];
}

function getAllocTransact($from, $to, $sman, $stat)
{
	$fromdate = date2sql($from);
	$todate = date2sql($to);

	$sql = "SELECT ".TB_PREF."debtor_trans.*, DATE_FORMAT(".TB_PREF."debtor_trans.tran_date, '%m-%d-%Y') as tranDate,
		ov_amount+ov_discount AS InvoiceTotal,
		".TB_PREF."debtors_master.name AS DebtorName, ".TB_PREF."debtors_master.curr_code, ".TB_PREF."cust_branch.br_name,
		".TB_PREF."cust_branch.contact_name, ".TB_PREF."salesman.*, ".TB_PREF."customized.customized_no, v.type as IsVoid
		FROM ".TB_PREF."debtor_trans 
			INNER JOIN ".TB_PREF."customized on ".TB_PREF."debtor_trans.type=".TB_PREF."customized.type 
			LEFT JOIN ".TB_PREF."voided v  ON v.type = ".TB_PREF."debtor_trans.type and v.id=".TB_PREF."debtor_trans.trans_no,
		".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		     AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
		    AND (".TB_PREF."debtor_trans.type=".ST_SALESINVOICE.") and ".TB_PREF."debtor_trans.payment_terms!=4";
	
    if ($fromdate != '')
		$sql .= " AND ".TB_PREF."debtor_trans.tran_date >='$fromdate'";
	if ($todate != '')
		$sql .= " AND ".TB_PREF."debtor_trans.tran_date <='$todate'";

	if ($sman != 0)
		$sql .= " AND ".TB_PREF."salesman.salesman_code=".db_escape($sman);

	if ($stat == 1)
		$sql .= " AND ".TB_PREF."debtor_trans.ov_amount = ".TB_PREF."debtor_trans.alloc";

	if ($stat == 2)
		$sql .= " AND ".TB_PREF."debtor_trans.ov_amount > ".TB_PREF."debtor_trans.alloc";


	$sql .= " ORDER BY ".TB_PREF."salesman.salesman_code";
    return db_query($sql,"No transactions were returned");

}

//----------------------------------------------------------------------------------------------------

function print_summary()
{
    global $path_to_root;

	$imc = $_POST['PARAM_0'];
	$from = $_POST['PARAM_1'];
	$to = $_POST['PARAM_2'];
	$status = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");
	$orientation = ($orientation ? 'L' : 'P');

	$dec = user_price_dec();

	$cols = array(0, 50, 100, 150, 200,	250, 300, 350, 400, 450, 520, 550);

	$headers = array(_('IMC'), _('Gross'), _('Returns'), _('Discount'), _('% To Sales'),
		_('Net Sales'),	_('Full Payment'),	_('% To Sales'), _('Partial Payments'), _('% To Sales'), _('Accounts Receivables'), _('% To Sales'));

	$aligns = array('left',	'right','right', 'right', 'right', 'right',	'right', 'right', 'right', 'right', 'right', 'right');

	$aligns2 = $aligns;

	$summary = 1;

	$rep = new FrontReport(_('Sales Status'), "Sales Status", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);
	$cols2 = $cols;
	$rep->Font();
	$rep->Info(null, $cols, $headers, $aligns);

	$rep->NewPage();

	$result = getTransactions($imc, $from, $to, ST_SALESINVOICE, $status);
	$InvoiceTotal = 0;
	$TotalReturn = 0;
	$DiscountTotal = 0;
	$NetSales = 0;
	$FullAlloc = 0;
	$PartialAlloc = 0;
	$ReceivablesTotal = 0;
	$Percentage1 = 0;
	$Percentage2 = 0;
	$Percentage3 = 0;
	$Percentage4 = 0;

	$TotalInvoice =0;
	$TotalReturn_2 =0;
	$TotalSales = 0;
	$TotalFullAlloc = 0;
	$TotalPartialAlloc = 0;
	$TotalReceivables = 0;
	$TotalDiscount = 0;
	
	
	$previous = '';
	while ($myrow=db_fetch($result))
	{
		$company_data = get_company_prefs();


		$imc = $myrow['imc_code'];
		$imc_code = $myrow['salesman_code'];
		$DiscountTotal = getDiscountTotal($imc_code, $from, $to, ST_SALESINVOICE, get_company_pref('default_sales_discount_act'), $status);
		$ReturnDiscount = getDiscountTotal($imc_code, $from, $to, ST_CUSTCREDIT, get_company_pref('default_sales_discount_act'), $status);
		$InvoiceTotal = $myrow['InvoiceTotal'] + $DiscountTotal;
		$TotalDiscount = $ReturnDiscount + $DiscountTotal;

		
		$net_partial = 0;
		$net_full_payment = 0;
		$TotalReturn = 0;

		$Transact = getAllocTransact($from, $to, $imc_code, $status);
		while($row=db_fetch($Transact))
		{
		$branch = get_branch($row["branch_code"]);
		$branch_data = get_branch_accounts($row['branch_code']);
		$dt = get_discount($branch_data['sales_discount_account'], $row['type'], $row['trans_no']);

		$res2 = get_return_details($row['order_']);
		$returns = 0;
		$rtn_dt = 0;
		$num = db_num_rows($res2);
		$var = array();
		while ($myrow2 = (db_fetch($res2)))
		{
			$returns += $myrow2['ov_amount'];
			$var = array($myrow2['trans_no']);
		}
		foreach ($var as $vars)
		{
			$res2 = get_return_discount($branch_data['sales_discount_account'], ST_CUSTCREDIT, $vars);
				while ($myrow3 = db_fetch($res2)){
					$rtn_dt += abs($myrow3['amount']);
				}
		}

		$total_returns = $returns + $rtn_dt;
		$return_discount = ($rtn_dt / $total_returns) * 100;
		$TotalReturn += $total_returns;

		$invoicetot = $row['ov_amount'] + $dt;
		$invoice_discount = ($dt / $invoicetot) * 100;
		$sales_discount = ($invoicetot - $total_returns) * ($invoice_discount/100);
		$net_sales = $invoicetot - $total_returns - $sales_discount;

			if ($row['IsVoid'] == '')
			{
				$partial_payment = 0;
				$zero;
				$or = get_pr_details($row['type'], $row['trans_no']);
				$num2 = db_num_rows($or);
				$date_alloc = '';
				$ctr = 0;
				$bal = 0;
				$bal1 = 0;
				$bal2 = 0;
				$ppayment = 0;
				$fpayment = 0;
				$all_bal = 0;
		
		$payment = get_total_payment($row['type'], $row['trans_no']);
		while($pr = db_fetch($or)){
			$partial_payment += $pr['amt'];
			$ctr++;

			if ($num2 > 1)
			{
				$bal = $net_sales - $partial_payment;
				if (number_format($payment, 2) == number_format($net_sales, 2))
				{
					if ($status == 1 || $status == "" ||$status == 0)
					$fpayment += $pr['amt'];
				}
				else
				{
					if ($status == 2 || $status == "" || $status == 0)
					{
						$ppayment += $pr['amt'];
						$all_bal += $bal;
					}
				}
			}
			else
			{
				$bal = $net_sales - $partial_payment;
				if ($bal == 0)
				{   $bal = number_format($net_sales, 2) - number_format($partial_payment, 2);
					if ($status == 1 || $status == "" || $status == 0)
					{
						$fpayment += $partial_payment;
						$bal1 += $bal;
					}
				}
				else
				{
					$bal = $net_sales - $pr['amt'];
					if ($status == 2 || $status == "" || $status == 0)
					{
						$ppayment += $partial_payment;
						$bal2 += $bal;
					}
				}
			}
			}

			$net_partial += $ppayment;
			$net_full_payment += $fpayment;
			}
		}

		$Percentage1 = ($TotalReturn/$InvoiceTotal) * 100;
		$NetSales = $InvoiceTotal - $TotalReturn;
		$Percentage2 = ($net_full_payment / $NetSales) * 100;
		$Percentage3 = ($net_partial / $NetSales) * 100;
		$ReceivablesTotal = $NetSales-$fpayment-$ppayment;
		$Percentage4 = ($ReceivablesTotal/$NetSales) * 100;
		
		
		$rep->TextCol(0,1, $myrow['imc_code']);
		$rep->AmountCol(1,2, $InvoiceTotal, 2);
		$rep->AmountCol(2,3, $TotalReturn, 2);
		$rep->AmountCol(3,4, $TotalDiscount, 2);
		$rep->AmountCol(4,5, $Percentage1, 2);
		$rep->AmountCol(5,6, $NetSales, 2);
		$rep->AmountCol(6,7, $net_full_payment, 2);
		$rep->AmountCol(7,8, $Percentage2, 2);
		$rep->AmountCol(8,9, $net_partial, 2);
		$rep->AmountCol(9,10, $Percentage3, 2);
		if ($status != 1)
		$rep->AmountCol(10,11, $ReceivablesTotal, 2);	
		$rep->AmountCol(11,12, $Percentage4, 2);
		$rep->NewLine();	

		$TotalInvoice += $InvoiceTotal;
		$TotalReturn_2 += $TotalReturn;
		$TotalSales += $NetSales;
		$TotalFullAlloc += $net_full_payment;
		$TotalPartialAlloc += $net_partial;
		if ($status != 1)
		$TotalReceivables += $ReceivablesTotal;

	}
		$rep->NewLine();
		$rep->Font('bold');
		$rep->TextCol(0,1, "TOTAL");
		$rep->AmountCol(1,2, $TotalInvoice, 2);
		$rep->AmountCol(2,3, $TotalReturn_2, 2);

		$rep->AmountCol(5,6, $TotalSales, 2);
		$rep->AmountCol(6,7, $TotalFullAlloc, 2);

		$rep->AmountCol(8,9, $TotalPartialAlloc, 2);
		$rep->AmountCol(10,11, $TotalReceivables, 2);


		$rep->NewLine();	

	


	$rep->NewLine();
	$rep->End();
}

?>