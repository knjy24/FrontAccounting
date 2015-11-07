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

function getTransactions($from, $to)
{
    $fromdate = date2sql($from);
    $todate = date2sql($to);
    $sql = "SELECT DISTINCT a.*, b.customized_no, b.check_num, v.type as IsVoid, DATE_FORMAT(a.tran_date, '%d-%b') as tranDate from ".TB_PREF."gl_trans a inner join ".TB_PREF."customized b on a.type=b.type AND a.type_no=b.type_no
            LEFT JOIN ".TB_PREF."voided v  ON v.type = a.type and v.id=a.type_no where a.type=".ST_DISBURSEMENT." AND a.tran_date >='$fromdate' AND a.tran_date <='$todate' ORDER BY b.customized_no ASC";
    //if ($imc != 0)
    //  $sql .= " and ".TB_PREF."salesman.salesman_code =".db_escape($imc); 
    //display_notification($sql);
    
    return db_query($sql,"No transactions were returned");

}

function get_sundry($customized)
{
    $sql = "SELECT DISTINCT a.*, v.type as IsVoid from ".TB_PREF."gl_trans a inner join ".TB_PREF."customized b on a.type=b.type AND a.type_no=b.type_no
        LEFT JOIN ".TB_PREF."voided v ON v.type = a.type and v.id=a.type_no
            where a.type=".ST_DISBURSEMENT." AND b.customized_no = ".db_escape($customized)."";
    //if ($imc != 0)
    //  $sql .= " and ".TB_PREF."salesman.salesman_code =".db_escape($imc); 
    //display_notification($sql);
    
    return db_query($sql,"No transactions were returned");
}

function getTotal($account)
{

}

function check_account($account, $amount)
{
    $salary = '6-1010';
    $purchase = '5-1010';
    $supplies = '6-1175';
    $gas_oil = '6-1110';
    $light_water = '6-1075';
    $tel = '6-1080';
    $repair = '6-1085';
    $representation = '6-1055';
    $transport = '6-1070';
    $postage = '6-1155';
    $ad_promo = '6-1230';
    $prof_fee = '6-1185';
    $insurance = '6-1105';
    $cash_advance = '1-2045';

    if (is_bank_account($account))
    {
        if ($amount < 0)
            return false;
        else
            return true;
    }
        
    if ($account == $salary)
    {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $purchase)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $supplies)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $gas_oil)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $light_water)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $tel)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $repair)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $representation)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $transport)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $postage)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $ad_promo)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $prof_fee)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    if ($account == $insurance)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
     if ($account == $cash_advance)
     {
        if ($amount > 0)
            return false;
        else
            return true;
    }
    else
        return true;
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

    $salary = '6-1010';
    $purchase = '5-1010';
    $supplies = '6-1175';
    $gas_oil = '6-1110';
    $light_water = '6-1075';
    $tel = '6-1080';
    $repair = '6-1085';
    $representation = '6-1055';
    $transport = '6-1070';
    $postage = '6-1155';
    $ad_promo = '6-1230';
    $prof_fee = '6-1185';
    $insurance = '6-1105';
    $cash_advance = '1-2045';
    $petty_cash = '1-1010';
    $res = getTransactions($from, $to);
    $previous = '';
    $var = array($salary, $purchase, $gas_oil, $light_water, $tel, $repair, $representation, $transport, $postage, $ad_promo, $prof_fee, $insurance, $cash_advance);
    $total = 0;
    $purchase_total = 0;
    $sal_total = 0;
    $sup_total = 0;
    $gas_total = 0;
    $light_total = 0;
    $tel_total = 0;
    $repair_total = 0;
    $rep_total = 0;
    $trans_total = 0;
    $post_total= 0;
    $ad_total = 0;
    $prof_total = 0;
    $ins_total = 0;
    $adv_total = 0;
    $dr = 0;
    $cr = 0;
     $sun_bank_name = '';
    $sun_bank_amount = '';
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
    			if ($myrow['IsVoid'] == '')
                {
                    if (is_bank_account($myrow['account']))
                    {
                        if ($myrow['account'] != $petty_cash)
                        {
                            if ($myrow['amount'] < 0)
                            {
                                $rep->AmountCol(5,6, abs($myrow['amount']), 2);
                                $total += abs($myrow['amount']);
                            }
                        }
                            
                    }
                        
                    if ($myrow['account'] == $purchase)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(6,7, abs($myrow['amount']), 2);
                            $purchase_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $salary)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(7,8, abs($myrow['amount']), 2);
                            $sal_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $supplies)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(8,9, abs($myrow['amount']), 2);
                            $sup_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $gas_oil)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(9,10, abs($myrow['amount']), 2);
                            $gas_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $light_water)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(10,11, abs($myrow['amount']), 2);
                             $light_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $tel)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(11,12, abs($myrow['amount']), 2);
                            $tel_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $repair)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(12,13, abs($myrow['amount']), 2);
                            $repair_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $representation)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(13,14, abs($myrow['amount']), 2);
                            $rep_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $transport)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(14,15, abs($myrow['amount']), 2);
                            $trans_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $postage)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(15,16, abs($myrow['amount']), 2);
                            $post_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $ad_promo)
                    {
                        if ($myrow['amount'] > 0)
                        {
                             $rep->AmountCol(16,17, abs($myrow['amount']), 2);
                            $ad_total += abs($myrow['amount']);
                        }
                       
                    }
                        
                    if ($myrow['account'] == $prof_fee)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(17,18, abs($myrow['amount']), 2);
                            $prof_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $insurance)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(18,19, abs($myrow['amount']), 2);
                            $ins_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                    if ($myrow['account'] == $cash_advance)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(19,20, abs($myrow['amount']), 2);
                            $adv_total += abs($myrow['amount']);
                        }
                        
                    }
                        
                }
    			
    		} 
    		else
    		{
                $result = get_sundry($previous);
                $ctr = 0;
                while($row=db_fetch($result))
                {
                    if ($row['IsVoid'] == "")
                    {
                            $yes = check_account($row['account'], $row['amount']);
                            if ($yes)
                            {  // $rep->TextCol(22, 23, $check_account($row['account']));
                                $ctr++;
                                if ($ctr > 1)
                                    $rep->NewLine();
                                $account_name2 = get_gl_account_name($row['account']);
                                $rep->TextCol(20,21, $account_name2);
                                    if ($row['amount'] > 0)
                                    {
                                        
                                        $rep->AmountCol(21,22, $row['amount'], 2);
                                        $abs = abs($row['amount']);
                                        $dr += $abs;
                                    } 
                                    else
                                    {
                                        if (!is_bank_account($row['account']))
                                        {
                                            $rep->AmountCol(22,23, abs($row['amount']), 2);
                                            $abs2 = abs($row['amount']);
                                            $cr += $abs2;
                                        }
                                    }    
                                }
                    }
                }
                $rep->NewLine();
    			
                if ($myrow['IsVoid'] == '')
                {
                    $rep->TextCol(0,1, $myrow['tranDate']);
                    $rep->TextCol(1,2, $name);
                    $rep->TextCol(2,3, $comment);
                    $rep->TextCol(3,4, $myrow['customized_no']);
                    $rep->TextCol(4,5, $myrow['check_num']);

                    if (is_bank_account($myrow['account']))
                    {
                       if ($myrow['account'] != $petty_cash)
                        {
                                $rep->AmountCol(5,6, abs($myrow['amount']), 2);
                                $total += abs($myrow['amount']);
                            
                        }

                    }
                    if ($myrow['account'] == $purchase)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(6,7, abs($myrow['amount']), 2);
                            $purchase_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $salary)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(7,8, abs($myrow['amount']), 2);
                            $sal_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $supplies)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(8,9, abs($myrow['amount']), 2);
                            $sup_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $gas_oil)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(9,10, abs($myrow['amount']), 2);
                            $gas_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $light_water)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(10,11, abs($myrow['amount']), 2);
                            $light_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $tel)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(11,12, abs($myrow['amount']), 2);
                            $tel_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $repair)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(12,13, abs($myrow['amount']), 2);
                            $repair_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $representation)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(13,14, abs($myrow['amount']), 2);
                            $rep_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $transport)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(14,15, abs($myrow['amount']), 2);
                            $trans_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $postage)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(15,16, abs($myrow['amount']), 2);
                            $post_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $ad_promo)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(16,17, abs($myrow['amount']), 2);
                            $ad_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $prof_fee)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(17,18, abs($myrow['amount']), 2);
                            $prof_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $insurance)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(18,19, abs($myrow['amount']), 2);
                            $ins_total += abs($myrow['amount']);
                        }
                    }
                        
                    if ($myrow['account'] == $cash_advance)
                    {
                        if ($myrow['amount'] > 0)
                        {
                            $rep->AmountCol(19,20, abs($myrow['amount']), 2);
                            $adv_total += abs($myrow['amount']);
                        }
                    }
                }
                else 
                {
                    $rep->TextCol(0,1, $myrow['tranDate']);
                    $rep->TextCol(1,2, "Cancelled");
                    $rep->TextCol(2,3, "Cancelled");
                    $rep->TextCol(3,4, $myrow['customized_no']);
                    $rep->TextCol(4,5, $myrow['check_num']);
                }
    			
    		}
    		$previous = $current;

    	}
    	
    }
    $rep->NewLine();
    $rep->Font('bold');
    $rep->AmountCol(5, 6, $total, 2);
    $rep->AmountCol(6, 7, $purchase_total, 2);
    $rep->AmountCol(7, 8, $sal_total, 2);
    $rep->AmountCol(8, 9, $sup_total, 2);
    $rep->AmountCol(9, 10, $gas_total, 2);
    $rep->AmountCol(10, 11, $light_total, 2);
    $rep->AmountCol(11, 12, $tel_total, 2);
    $rep->AmountCol(12, 13, $repair_total, 2);
    $rep->AmountCol(13, 14, $rep_total, 2);
    $rep->AmountCol(14, 15, $trans_total, 2);
    $rep->AmountCol(15, 16, $post_total, 2);
    $rep->AmountCol(16, 17, $ad_total, 2);
    $rep->AmountCol(17, 18, $prof_total, 2);
    $rep->AmountCol(18, 19, $ins_total, 2);
    $rep->AmountCol(19, 20, $adv_total, 2);
    $rep->AmountCol(21, 22, $dr, 2);
    $rep->AmountCol(22, 23, $cr, 2);
   /* $i = 0;
    $k = 1;
    foreach($var as $vars)
    {
        $tots = getTotal($vars);
        $rep->AmountCol($i, $k, $tots, 2);
        $i++;
        $j++;
    }*/
    
    $rep->End();
}

?>