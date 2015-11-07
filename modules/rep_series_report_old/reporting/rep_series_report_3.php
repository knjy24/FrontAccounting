<?php

$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------

// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");
include_once($path_to_root . "/sales/ml/voucher_search.php");

//----------------------------------------------------------------------------------------------------

print_inventory_sales();

function getTransactions($imc)
{
	
	$sql = "SELECT ".TB_PREF."debtor_trans.*, DATE_FORMAT(".TB_PREF."debtor_trans.tran_date, '%m-%d-%Y') as tranDate,
		ov_amount+ov_discount AS InvoiceTotal,
		".TB_PREF."debtors_master.name AS DebtorName, ".TB_PREF."debtors_master.curr_code, ".TB_PREF."cust_branch.br_name,
		".TB_PREF."cust_branch.contact_name, ".TB_PREF."salesman.*, ".TB_PREF."customized.customized_no
		FROM ".TB_PREF."debtor_trans 
			INNER JOIN ".TB_PREF."customized on ".TB_PREF."debtor_trans.type=".TB_PREF."customized.type, 
		".TB_PREF."debtors_master, ".TB_PREF."sales_orders, ".TB_PREF."cust_branch, 
			".TB_PREF."salesman
		WHERE ".TB_PREF."sales_orders.order_no=".TB_PREF."debtor_trans.order_
		    AND ".TB_PREF."sales_orders.branch_code=".TB_PREF."cust_branch.branch_code
		    AND ".TB_PREF."cust_branch.salesman=".TB_PREF."salesman.salesman_code
		    AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		     AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
		    AND (".TB_PREF."debtor_trans.type=".ST_SALESINVOICE.") and ".TB_PREF."debtor_trans.payment_terms!=4 ORDER BY ".TB_PREF."customized.customized_no";
	//if ($imc != 0)
	//	$sql .= " and ".TB_PREF."salesman.salesman_code =".db_escape($imc);	
	//display_notification($sql);
	
    return db_query($sql,"No transactions were returned");

}


//----------------------------------------------------------------------------------------------------

function print_inventory_sales()
{
    global $path_to_root;

	$from = $_POST['PARAM_0'];
	$destination = $_POST['PARAM_1'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $dec = user_price_dec();

	if ($category == ALL_NUMERIC)
		$category = 0;
	if ($category == 0)
		$cat = _('All');
	else
		$cat = get_category_name($category);

	$cols = array(0, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000, 1050, 1100, 1150, 1200);

	$headers = array(_('Date'), _('Client Name'), _('IMC'), _('Charge'), _('Sales'), _('Date'), _('CM#'), _('Returns'), _(''), _('Discount'), _('Balance'), _('Date'), _('PR/OR#'), _('Date'), _('OR #'), _('Amount'), _('Partial'), _('Balance'), _('Date'), _(''), _('Commission'), _('w/tax'), _('Net Commission'));	
	$header2 = array(_(''), '', '', _('Invoice'), _('Amount'), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), _(''), '', _(''), _(''));

	$aligns = array('left',	'center',	'center', 'center', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''));

    $rep = new FrontReport(_('Series Report'), "SeriesReport", user_pagesize(), 8, 'L');

    $rep->Font();
    $rep->Info($params, $cols, $header2, $aligns, $cols, $headers, $aligns);
    $rep->NewPage();

	
	$total = $grandtotal = 0.0;
	$total1 = $grandtotal1 = 0.0;
	$total2 = $grandtotal2 = 0.0;
	$catt = '';
	$res = getTransactions($imc, $from);
	while ($myrow=db_fetch($res))
	{
		$net_invoice += $invoicetot;
		$net_return += $total_returns;
		$net_invoice_discount += $invoice_discount;
		$net_sales_discount += $sales_discount;
		$net_net_sales += $net_sales;
		$net_partial_payment += $partial_payment;
		$net_net_remittance += $net_remittance;

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

		while ($myrow2 = (db_fetch($res2)))
		{
			$returns += $myrow2['ov_amount'];
			$credit_num .= $myrow2['customized_no'];
			$cm_date .= $myrow2['tran_date'];

			$res2 = get_return_discount($branch_data['sales_discount_account'], $myrow2['type'], $myrow2['trans_no']);
				while ($myrow3 = db_fetch($res2)){
					$rtn_dt += abs($myrow3['amount']);
				}
		}

		$total_returns = $returns + $rtn_dt;
		$return_discount = ($rtn_dt / $total_returns) * 100;
		//$discount = $myrow['discount'];

		$invoicetot = $myrow['ov_amount'] + $dt;
		$invoice_discount = ($dt / $invoicetot) * 100;
		$sales_discount = ($invoicetot - $total_returns) * ($invoice_discount/100);
		$net_sales = $invoicetot - $total_returns - $sales_discount;
		$gross_commission = ($invoicetot - $total_returns) * ($myrow['commission']/100);

		$rep->TextCol(0,1, $myrow['tranDate']);
			$rep->TextCol(1,2, $myrow['br_name']);
			$rep->TextCol(2,3, $salesman);
			$rep->TextCol(3,4, $myrow['customized_no']);
			$rep->AmountCol(4,5, $invoicetot, 2);
			$rep->TextCol(5,6, $cm_date);
			$rep->TextCol(6,7, $credit_num);
			$rep->AmountCol(7,8, $total_returns, 2);
			if  ($invoice_discount < 0)
				$rep->TextCol(8,9, "CLIENT SALES/SALES DISCOUNT NOT DEFINED.");
			else 
				$rep->TextCol(8,9, $invoice_discount."%");

			if ($sales_discount < 0)
				$rep->TextCol(9,10, "ERROR.");
			else 
				$rep->AmountCol(9,10, $sales_discount, 2);

			$rep->AmountCol(10,11, $net_sales,2);
			
		$partial_payment = 0;
		$or = get_pr_details($myrow['type'], $myrow['trans_no']);
		while($pr = db_fetch($or)){
			$partial_payment += $pr['amt'];
			$date_alloc = $pr['prDate'] ." ";
			$pr_number = $pr['customized_no'] ." ";
			
			/*$rep->TextCol(11,12, $pr['date_alloc']);
			$rep->TextCol(12,13, $pr['customized_no']);
			$rep->TextCol(13,14, _("DATE"));
			$rep->TextCol(14,15, _("OR#"));
			$rep->TextCol(15,16, "");
			$rep->AmountCol(17,18, $net_remittance, 2);*/
			}
			$net_remittance = $net_sales - $partial_payment;

			$rep->TextCol(11,12, $date_alloc);
			$rep->TextCol(12,13, $pr_number);
			$rep->TextCol(13,14, _(""));
			$rep->TextCol(14,15, _(""));


			if ($partial_payment == 0)
			{
				$partial_payment = 0;
				$net_remittance = $net_sales;
			} 
			$rep->AmountCol(15,16, $partial_payment, 2);
			$rep->AmountCol(17,18, $net_remittance, 2);

			$myrow4 = db_fetch(get_commission_details($myrow['type'], $myrow['trans_no']));
			$comm = ($invoicetot - $total_returns) * ($myrow4['commission']/100);

			$rep->TextCol(18,19, $myrow4['tranDate']);
			$rep->TextCol(19,20, $myrow4['commission']."%");
			$rep->AmountCol(20,21, $comm, 2);
			$rep->AmountCol(21,22, $myrow4['with_tax'], 2);
			$rep->AmountCol(22,23, $myrow4['net_commission'], 2);
			$rep->NewLine();
	}
	
	$rep->NewLine();
	$rep->Font('bold');
	$rep->TextCol(0,2, _("TOTAL"));
	$rep->AmountCol(4,5, $net_invoice, 2);
	$rep->AmountCol(7,8, $net_return, 2);
	//$rep->AmountCol(8,9, $net_invoice_discount, 2);
	$rep->AmountCol(9,10, $net_sales_discount, 2);
	$rep->AmountCol(10,11, $net_net_sales, 2);
	$rep->AmountCol(15,16, $net_partial_payment, 2);
	$rep->AmountCol(17, 18, $net_net_remittance, 2);

    $rep->End();
}

?>