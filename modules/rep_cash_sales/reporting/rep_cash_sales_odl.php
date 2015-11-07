<?php

$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Chaitanya
// date_:	2005-05-19
// Title:	Sales Summary Report
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

print_cash_sale();

function getTransactions($from)
{
	$fromdate = date2sql($from);

	$sql = "SELECT a.*, DATE_FORMAT(a.tran_date, '%m-%d-%Y') as tranDate, c.*, d.*, v.type as IsVoid from ".TB_PREF."debtor_trans a 
			LEFT JOIN ".TB_PREF."voided v  ON v.type = a.type and v.id=a.trans_no
			INNER JOIN ".TB_PREF."cust_branch c on a.branch_code=c.branch_code AND a.debtor_no=c.debtor_no 
			INNER JOIN ".TB_PREF."customized d on a.type=d.type AND a.trans_no=d.type_no
			where a.tran_date >= '$fromdate' AND a.payment_terms = 4 AND a.type=".ST_SALESINVOICE."";
	if ($imc != 0)
		$sql .= " and ".TB_PREF."salesman.salesman_code =".db_escape($imc);	
	//display_notification($sql);
	
    return db_query($sql,"No transactions were returned");

}


//----------------------------------------------------------------------------------------------------

function print_cash_sale()
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

	$cols = array(0, 50, 250, 350, 400, 450, 500, 550, 600, 700, 750, 800, 850, 900, 950, 1000, 1050, 1100, 1150, 1200);

	$headers = array(_('DATE'), _('CLIENT NAME'), _('IMC'), _('CASH SALES'), _('INVOICE'), _('CASH'), _('SALES'), _('RETURNS'), _('SALES DISCOUNT'), _('SUNDRIES'), _('DR'), _('CR'));	
	$header2 = array(_(''), '', _('CODE'), _('INVOICE'), _('AMOUNT'), _('DR'), _('CR'), _(' DR'), _('DR'), _(''), _(''));

	$aligns = array('left',	'left',	'center', 'left', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''));

    $rep = new FrontReport(_('Cash Sales Report'), "CashSalesReport", user_pagesize(), 8, 'L');

    $rep->Font();
    $rep->Info($params, $cols, $header2, $aligns, $cols, $headers, $aligns);
    $rep->NewPage();

	$res = getTransactions($from);
	while ($myrow=db_fetch($res))
	{
		$company_data = get_company_prefs();
		$branch = get_branch($myrow["branch_code"]);
		$branch_data = get_branch_accounts($myrow['branch_code']);

		$dt = get_discount($branch_data['sales_discount_account'], $myrow['type'], $myrow['trans_no']);
		$sales_acct = get_discount($branch_data['sales_account'], $myrow['type'], $myrow['trans_no']);
		$salesman = get_imc_code($myrow['branch_code']);
		  
		
		$invoicetot = $myrow['ov_amount'] + $dt;
		$invoice_discount = ($dt / $invoicetot) * 100;

		$rep->TextCol(0,1, $myrow['tranDate']);
		$rep->TextCol(1,2, $myrow['br_name']);
		$rep->TextCol(2,3, $salesman);
		$rep->TextCol(3,4, $myrow['customized_no']);
		if ($myrow['IsVoid'] == '')
		{
		
		$rep->AmountCol(4,5, $invoicetot, 2);
		$rep->AmountCol(5,6, $myrow['ov_amount'], 2);
			
		$rtn_dt = 0;
		$res2 = get_return_details($myrow['order_']);
		while ($myrow2 = (db_fetch($res2)))
		{
			$returns += $myrow2['ov_amount'];

			$res2 = get_return_discount($branch_data['sales_discount_account'], $myrow2['type'], $myrow2['trans_no']);
				while ($myrow3 = db_fetch($res2)){
					$rtn_dt += abs($myrow3['amount']);
				}
		}
			$net_sales = abs($sales_acct) - $dt - $rtn_dt;
			$rep->AmountCol(6,7, $net_sales, 2);
			if ($rtn_dt != 0)
			$rep->AmountCol(7,8, $rtn_dt, 2);
			if ($dt != 0)
				$rep->AmountCol(8,9, $dt, 2);
			$rep->NewLine();
		}
		
		else {
			$rep->TextCol(4,5, "Voided");

		}

		$net_invoice += $invoicetot;
		$net_dr += $myrow['ov_amount'];
		$net_cr += $net_sales;
		$net_return += $rtn_dt;
		$net_ret_dt += $dt;

	}
	
	$rep->NewLine();
	$rep->Font('bold');
	$rep->TextCol(1,2, "TOTAL");
	$rep->AmountCol(4,5, $net_invoice, 2);
	$rep->AmountCol(5,6, $net_dr, 2);
	$rep->AmountCol(6,7, $net_cr, 2);
	$rep->AmountCol(7,8, $net_return, 2);
	$rep->AmountCol(8,9, $net_ret_dt, 2);
    $rep->End();
}

?>