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

function getTransactions($from, $to, $sman, $stat)
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

function get_all_salesman()
{
	$sql = "SELECT salesman_code, imc_code from ".TB_PREF."salesman ORDER BY salesman_code";
	return db_query($sql, "could not retrieve imc list.");
}


//----------------------------------------------------------------------------------------------------

function print_summary()
{
    global $path_to_root;

	$sman = $_POST['PARAM_0'];
	$from = $_POST['PARAM_1'];
	$to = $_POST['PARAM_2'];
	$stat = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");
	$orientation = ($orientation ? 'L' : 'P');

	$dec = user_price_dec();

	$cols = array(0, 50, 100, 150, 200,	250, 300, 350, 400, 450, 520, 550);

	$headers = array(_('IMC'), _('Gross'), _('Returns'), _('% To Sales'),
		_('Net Sales'),	_('Full Payment'),	_('% To Sales'), _('Partial Payments'), _('% To Sales'), _('Accounts Receivables'), _('% To Sales'));

	$aligns = array('left',	'right','right', 'right', 'right', 'right',	'right', 'right', 'right', 'right', 'right');

	$aligns2 = $aligns;

	$summary = 1;

	$rep = new FrontReport(_('Sales Status'), "Sales Status", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);
	$cols2 = $cols;
	$rep->Font();
	$rep->Info(null, $cols, $headers, $aligns);

	$rep->NewPage();

	$total = $grandtotal = 0.0;
	$total1 = $grandtotal1 = 0.0;
	$total2 = $grandtotal2 = 0.0;
	$catt = '';
	$slist = array();
	if ($sman == "")
	{
		$row = get_all_salesman();
		while($r=db_fetch($row))
		{
			array_push($slist, $r['salesman_code']);
		}
		foreach($slist as $slists)
		{
			$res = getTransactions($from, $to, $slists, $stat);
		}
	}
	else
	{
		$res = getTransactions($from, $to, $sman, $stat);
	}
	$prev = '';
	while ($myrow=db_fetch($res))
	{
		$current = $myrow['salesman_code'];
		if ($myrow['salesman_code'] != "")
		{
			if ($current == $prev)
			{

			}
			else
			{

			}
			$prev = $current;
		}
		$company_data = get_company_prefs();
		$branch = get_branch($myrow["branch_code"]);
		$branch_data = get_branch_accounts($myrow['branch_code']);

		$dt = get_discount($branch_data['sales_discount_account'], $myrow['type'], $myrow['trans_no']);

		$salesman = get_imc_code($myrow['branch_code']);
		$res2 = get_return_details($myrow['order_']);
		$returns = 0;
		$rtn_dt = 0;
		$credit_num = "";
		$cm_date = "";
		$num = db_num_rows($res2);
		$var = array();
		while ($myrow2 = (db_fetch($res2)))
		{
			$returns += $myrow2['ov_amount'];
			if ($num > 1){
				$credit_num .= $myrow2['customized_no']."/";
				$cm_date = $myrow2['tran_date']."/";
			} else {
				$credit_num .= $myrow2['customized_no'];
				$cm_date = $myrow2['tran_date'];
			}
			
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

		$invoicetot = $myrow['ov_amount'] + $dt;
		$invoice_discount = ($dt / $invoicetot) * 100;
		$sales_discount = ($invoicetot - $total_returns) * ($invoice_discount/100);
		$net_sales = $invoicetot - $total_returns - $sales_discount;
		$gross_commission = ($invoicetot - $total_returns) * ($myrow['commission']/100);
		$tot_invoice = $invoicetot - ($invoicetot * ($invoice_discount/100));


		if ($myrow['IsVoid'] == '')
		{

			$myrow4 = db_fetch(get_commission_details($myrow['type'], $myrow['trans_no']));
			$comm = ($invoicetot - $total_returns) * ($myrow4['commission']/100);
			
		$partial_payment = 0;
		$zero;
		$or = get_pr_details($myrow['type'], $myrow['trans_no']);
		$ref = '';
		$num2 = db_num_rows($or);
		$date_alloc = '';
		$ctr = 0;
		$bal = 0;
		$bal1 = 0;
		$bal2 = 0;
		$ppayment = 0;
		$fpayment = 0;
		$all_bal = 0;
		
		$payment = get_total_payment($myrow['type'], $myrow['trans_no']);
		while($pr = db_fetch($or)){
			$partial_payment += $pr['amt'];
			$date_alloc = $pr['prDate'] ." ";
			$pr_number = $pr['customized_no'] ." ";

			$ref = $pr['reference'];

			$net_remittance = $net_sales - $partial_payment;

			$ctr++;
			
			if ($num2 > 1)
			{
				$bal = $net_sales - $partial_payment;
				if (number_format($payment, 2) == number_format($net_sales, 2))
				{
					$fpayment += $pr['amt'];
				}
				else
				{
					$ppayment += $pr['amt'];
					$all_bal += $bal;
				}
			}
			else
			{
				$bal = $net_sales - $partial_payment;
				if ($bal == 0)
				{   $bal = number_format($net_sales, 2) - number_format($partial_payment, 2);
					$fpayment += $partial_payment;
					$bal1 += $bal;
				}
				else
				{
					$bal = $net_sales - $pr['amt'];
					$ppayment += $partial_payment;
					$bal2 += $bal;
				}
			}
			
			if ($num2 > 1)
			$rep->NewLine();
			}
		
			$net_invoice += $invoicetot;
			$net_discount += $sales_discount;
			$net_returns += $total_returns;
			$sale_amount += $net_sales;
			$net_partial += $ppayment;
			$net_rem += $net_remittance;
			$net_bal += $bal;
			$net_balance = $net_rem + $net_bal;
			$net_comm += $comm;
			$net_tax += $myrow4['with_tax'];
			$net_net_comm += $myrow4['net_commission'];
			$net_full_payment += $fpayment;
		}
	}

	$Percentage1 = ($net_returns/$net_invoice) * 100;
	$NetSales = $net_invoice - $net_returns;
	$Percentage2 = ($net_full_payment/$NetSales) * 100;
	$Percentage3 = ($net_partial/$NetSales) * 100;
	$ReceivablesTotal = $NetSales-$net_full_payment-$net_partial;
	$Percentage4 = ($ReceivablesTotal/$NetSales) * 100;

	$rep->Font('bold');
	$rep->NewLine();
	$rep->TextCol(0,1, $salesman);
	$rep->AmountCol(1,2, $net_invoice, 2);
	$rep->AmountCol(2,3, $net_returns, 2);
	$rep->AmountCol(3,4, $Percentage1, 2);
	$rep->AmountCol(4,5, $NetSales, 2);
	$rep->AmountCol(5,6, $net_full_payment, 2);
	$rep->AmountCol(6,7, $Percentage2, 2);
	$rep->AmountCol(7,8, $net_partial, 2);
	$rep->AmountCol(8,9, $Percentage3, 2);
	$rep->AmountCol(9,10, $ReceivablesTotal, 2);
	$rep->AmountCol(10,11, $Percentage4, 2);
	
	$rep->NewLine();
	
    $rep->End();
}

?>