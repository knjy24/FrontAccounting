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

//----------------------------------------------------------------------------------------------------

print_purchases_cost();

function getTransactions($from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT purch.order_no,
		supp.supp_name, purch_det.description,
		purch_det.quantity_ordered, purch_det.unit_price

		FROM ".TB_PREF."purch_orders purch,
		".TB_PREF."purch_order_details purch_det,
		".TB_PREF."suppliers supp
		WHERE supp.supplier_id = purch.supplier_id
		AND purch.order_no = purch_det.order_no
		AND purch.ord_date>='$from'
		AND purch.ord_date<='$to'

		";
		$sql .= " GROUP BY purch.order_no,
		supp.supp_name, purch_det.description
		ORDER BY supp.supp_name";
			
	//display_notification($sql);
	
    return db_query($sql,"No transactions were returned");

}

//----------------------------------------------------------------------------------------------------

function print_purchases_cost()
{
    global $path_to_root;

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$destination = $_POST['PARAM_2'];
	$orientation = $_POST['PARAM_3'];
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

	$cols = array(0,20,55,85,125,200,230,260,290,325,350,375,425,475);
	//14 headers + 1 lagi dapat for cols
    //todo: format date paid to 2 digits representation only i.e. 12/12/12
	$headers = array(_('CV#'), _('Date Paid'), _('OR #'), _('Supplier'), _('Title of Book'), _('Quantity'),
		_('Unit Cost'),_('Amount') , _('With Tax'), _('Net') , _('PO #'), _('Stock Supplied'), _('Stock Amount') , _('Total Amount'));
	//todo: Date Paid, OR(official receipt number) Amount, w/tax, net, Stock supplied, stock amount and total amount
	// Possible tables to read on:  grn_batch and grn_items
	$aligns = array('left',	'left',	'left',	'left','left','left','left','left', 'left', 'left', 'left','left','left','left');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''));

    $rep = new FrontReport(_('Summary of Purchases at Cost Report'), "SummaryPurchasesCostReport", user_pagesize(), 8, 'L');

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();

	$res = getTransactions($from, $to);
	$total = $grandtotal = 0.0;
	$total1 = $grandtotal1 = 0.0;
	$total2 = $grandtotal2 = 0.0;
	$catt = '';
	while ($trans=db_fetch($res))
	{
		
		$rep->NewLine();
		$rep->fontSize -= 2;
		$rep->TextCol(3, 4, $trans['supp_name']);
		$rep->TextCol(4, 5, $trans['description']);
		$rep->AmountCol(5, 6, $trans['quantity_ordered']);
		$rep->AmountCol(6, 7, $trans['unit_price']);
		$rep->AmountCol(10,11, $trans['order_no']);

	}
	$rep->NewLine(2, 3);
	$rep->TextCol(0, 4, _('Total'));
	$rep->AmountCol(4, 5, $total, $dec);
	$rep->Line($rep->row - 2);
	$rep->NewLine();
	$rep->NewLine(2, 1);
	$rep->TextCol(0, 4, _('Grand Total'));
	$rep->AmountCol(4, 5, $grandtotal, $dec);

	$rep->Line($rep->row  - 4);
	$rep->NewLine();
    $rep->End();
}

?>