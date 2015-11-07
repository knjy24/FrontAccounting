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
include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");

//----------------------------------------------------------------------------------------------------

print_inventory_sales();

function getTransactions($imc)
{
	
	$sql = "SELECT DISTINCT a.*, b.customized_no, b.check_num, DATE_FORMAT(a.tran_date, '%d-%b') as tranDate from ".TB_PREF."gl_trans a inner join ".TB_PREF."customized b on a.type=b.type AND a.type_no=b.type_no
			where a.type=".ST_DISBURSEMENT." ORDER BY b.customized_no ASC";
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
	$to = $_POST['PARAM_1'];
	$destination = $_POST['PARAM_2'];
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

	$headers = array(_('DATE'), _('PAYEES'), _('PARTICULARS'), _('GV NO'), _('CHECK NO'), _('CASH IN'), _('PURCHASES'), _('SALARIES &'), _('SUPPLIES'), _('GASOLINE &'), _('LIGHT &'), _('TELECOMS'), _('REPAIRS'),  _('REPRESENTATION'), _('TRANSPORTATION'), _('POSTAGE'),    _('AD &'), _('PROF.'), _('INSURANCE'), _('CASH'),    _('SUNDRY'), _('DEBIT'), _('CREDIT'));	
	$header2 = array(_(''),          '',            '',         '',          _(''),       _('BANK'),       _(''),       _('WAGES'),       _(''),        _('OIL'),         _('WATER'),  _(''),         _('& MAINT.'),  _('EXPENSE'),       _('EXPENSE'),         _('& COURIER'), _('PROMO'), _('FEES'), _(''),          _('ADVANCE'), _('ACCOUNTS'));

	$aligns = array('left',	'center',	'center', 'center', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''));

    $rep = new FrontReport(_('Cash Disbursement Summary'), "CashDisbursementSummary", user_pagesize(), 8, 'L');

    $rep->Font();
    $rep->Info($params, $cols, $header2, $aligns, $cols, $headers, $aligns);
    $rep->NewPage();

    $salary = '6-1100';
    $purchase = '5-1000';
    $supplies = '6-4360';
    $gas_oil = '6-4170';
    $light_water = '6-4120';
    $tel = '6-4310';
    $repair = '6-4130';
    $representation = '6-4100';
    $transport = '6-4110';
    $postage = '6-4300';
    $ad_promo = '6-5310';
    $prof_fee = '6-4380';
    $insurance = '6-4160';
    $cash_advance = '1-3500';
    $res = getTransactions($from, $to);
    $previous = '';
    $var = array();
    while($myrow=db_fetch($res))
    {
    	$check = $myrow['customized_no'];
    	$current = $check;
    	$name = payment_person_name($myrow["person_type_id"],$myrow["person_id"]);
    	$comment = get_comments_string($myrow['type'], $myrow['type_no']);
        $account_name = get_gl_account_name($myrow['account']);
        
    	//$rep->NewLine();
    	if ($current != '') 
    	{
    		if ($previous == $current)
    		{
    			
    			if (is_bank_account($myrow['account']))
    				$rep->AmountCol(5,6, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $purchase)
    				$rep->AmountCol(6,7, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $salary)
    				$rep->AmountCol(7,8, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $supplies)
    				$rep->AmountCol(8,9, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $gas_oil)
    				$rep->AmountCol(9,10, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $light_water)
    				$rep->AmountCol(10,11, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $tel)
    				$rep->AmountCol(11,12, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $repair)
    				$rep->AmountCol(12,13, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $representation)
    				$rep->AmountCol(13,14, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $transport)
    				$rep->AmountCol(14,15, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $postage)
    				$rep->AmountCol(15,16, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $ad_promo)
    				$rep->AmountCol(16,17, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $prof_fee)
    				$rep->AmountCol(17,18, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $insurance)
    				$rep->AmountCol(18,19, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $cash_advance)
    				$rep->AmountCol(19,20, abs($myrow['amount']), 2);
                else 
                {   
                    if (!is_bank_account($myrow['account']))
                        {   
                            $var += array($account_name, $myrow['amount']);
                        }
                
                }
    			
    		} 
    		else
    		{
    			$rep->NewLine();

               foreach($var as $vars)
                {

                    $rep->TextCol(20,21, $vars[0]);
                    if ($vars[1] > 0)
                        $rep->AmountCol(21,22, $vars[1]);
                    else
                        $rep->AmountCol(22,23, $vars[1]);
                    $rep->NewLine();
                }

    			$rep->TextCol(0,1, $myrow['tranDate']);
    			$rep->TextCol(1,2, $name);
    			$rep->TextCol(2,3, $comment);
    			$rep->TextCol(3,4, $myrow['customized_no']);
    			$rep->TextCol(4,5, $myrow['check_num']);
    			if (is_bank_account($myrow['account']))
    				$rep->AmountCol(5,6, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $salary)
    				$rep->AmountCol(7,8, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $supplies)
    				$rep->AmountCol(8,9, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $gas_oil)
    				$rep->AmountCol(9,10, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $light_water)
    				$rep->AmountCol(10,11, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $tel)
    				$rep->AmountCol(11,12, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $repair)
    				$rep->AmountCol(12,13, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $representation)
    				$rep->AmountCol(13,14, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $transport)
    				$rep->AmountCol(14,15, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $postage)
    				$rep->AmountCol(15,16, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $ad_promo)
    				$rep->AmountCol(16,17, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $prof_fee)
    				$rep->AmountCol(17,18, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $insurance)
    				$rep->AmountCol(18,19, abs($myrow['amount']), 2);
    			if ($myrow['account'] == $cash_advance)
    				$rep->AmountCol(19,20, abs($myrow['amount']), 2);

    			
    		}
    		$previous = $current;

    	}
    	
    }
    $rep->End();
}

?>