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

function getTransactions($from, $to, $year, $account)
{
  $month_sum = array();
  foreach($account as $accounts)
  {
    $sql = "SELECT SUM(CASE WHEN MONTH(tran_date) = 1 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS JanDR,
       SUM(CASE WHEN MONTH(tran_date) = 1 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS JanCR,
       SUM(CASE WHEN MONTH(tran_date) = 2 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS FebDR,
       SUM(CASE WHEN MONTH(tran_date) = 2 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS FebCR,
       SUM(CASE WHEN MONTH(tran_date) = 3 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS MarDR,
       SUM(CASE WHEN MONTH(tran_date) = 3 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS MarCR,
       SUM(CASE WHEN MONTH(tran_date) = 4 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS AprDr,
       SUM(CASE WHEN MONTH(tran_date) = 4 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS AprCR,
       SUM(CASE WHEN MONTH(tran_date) = 5 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS MayDR,
       SUM(CASE WHEN MONTH(tran_date) = 5 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS MayCR,
       SUM(CASE WHEN MONTH(tran_date) = 6 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS JunDR,
       SUM(CASE WHEN MONTH(tran_date) = 6 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS JunCR,
       SUM(CASE WHEN MONTH(tran_date) = 7 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS JulDR,
       SUM(CASE WHEN MONTH(tran_date) = 7 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS JulCR,
       SUM(CASE WHEN MONTH(tran_date) = 8 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS AugDR,
       SUM(CASE WHEN MONTH(tran_date) = 8 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS AugCR,
       SUM(CASE WHEN MONTH(tran_date) = 9 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS SepDR,
       SUM(CASE WHEN MONTH(tran_date) = 9 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS SepCR,
       SUM(CASE WHEN MONTH(tran_date) = 10 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS OctDR,
       SUM(CASE WHEN MONTH(tran_date) = 10 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS OctCR,
       SUM(CASE WHEN MONTH(tran_date) = 11 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS NovDR,
       SUM(CASE WHEN MONTH(tran_date) = 11 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS NovCR,
       SUM(CASE WHEN MONTH(tran_date) = 12 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount > 0 THEN amount ELSE 0 END) AS DecDR,
       SUM(CASE WHEN MONTH(tran_date) = 12 AND MONTH(tran_date) >= '$from' AND MONTH(tran_date) <='$to' AND amount < 0 THEN amount ELSE 0 END) AS DecCR

        from ".TB_PREF."gl_trans 
        where type = ".ST_DISBURSEMENT." and YEAR(tran_date) = '$year' and account=".db_escape($accounts)." 
        OR type = ".ST_SUPPAYMENT." and YEAR(tran_date) = '$year' and account=".db_escape($accounts);
        $result = db_query($sql, "Could not retrieve transaction.");
        $row = db_fetch_row($result);

        array_push($month_sum, array($accounts, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13],
          $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23]));
  }
	
	//if ($imc != 0)
	//	$sql .= " and ".TB_PREF."salesman.salesman_code =".db_escape($imc);	
	//display_notification($sql);
	
    return $month_sum;

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

	$cols = array(0, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000, 1050, 1100, 1150, 1200, 1250, 1300, 1350, 1400, 1450, 1500, 1550, 1600, 1650, 1700,1750, 1800);


	$headers = array(_('Account'), _('JANUARY'), _(''), _('FEBRUARY'),  _(''), _('MARCH'), _(''),   _('APRIL'), _(''),  _('MAY'), _(''),  _('JUNE'), _(''), _('JULY'), _(''), _('AUGUST'), _(''), _('SEPTEMBER'), _(''), _('OCTOBER'), _(''), _('NOVEMBER'), _(''), _('DECEMBER'));	
	$headers2 = array(_(''),       _('DR'),  _('CR'),    _('DR'), _('CR'),    _('DR'),  _('CR'),    _('DR'),  _('CR'),  _('DR'), _('CR'), _('DR'), _('CR'), _('DR'), _('CR'), _('DR'), _('CR'),   _('DR'), _('CR'),      _('DR'), _('CR'),    _('DR'), _('CR'), _('DR'), _('CR'));

	$aligns = array('left',	'right',	'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right');
    

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''));

    $rep = new FrontReport(_('Recapitulation'), "CashDisbursementSummary", user_pagesize(), 8, 'L');

    $rep->Font();
    $rep->Info($params, $cols, $headers2, $aligns, $cols, $headers, $aligns);
    $rep->NewPage();

    $acc = getExpenseAccount();
    $account = array();
    while($accs=db_fetch($acc))
    {
      array_push($account, $accs['account_code']);
    }

    $year = substr(date2sql($from), 0, 4);
    //$res = getTransactions($from, $to);
    $previous = '';
    $dr = 0;
        $cr = 0;

       // $monthfr = date("n",strtotime($from));
       // $monthto = date("n",strtotime($to));

$date_from = date2sql($from);
$date_to = date2sql($to);

 $monthfr = date("n",strtotime($date_from));
 $monthto = date("n",strtotime($date_to));

//$myrow = 0;
//$row = 0;

  $row = getTransactions($monthfr, $monthto, $year, $account);
 // $myrow = db_fetch($row);
foreach($row as $myrow)
{
   
    $acc_name = get_gl_account_name($myrow[0]);
    $rep->TextCol(0,1, $acc_name);
    $rep->AmountCol(1,2, $myrow[1], 2);
    $rep->AmountCol(2,3, abs($myrow[2]), 2);
    $rep->AmountCol(3,4, $myrow[3], 2);
    $rep->AmountCol(4,5, abs($myrow[4]), 2);
    $rep->AmountCol(5,6, $myrow[5], 2);
    $rep->AmountCol(6,7, abs($myrow[6]), 2);
    $rep->AmountCol(7,8, $myrow[7], 2);
    $rep->AmountCol(8,9, abs($myrow[8]), 2);
    $rep->AmountCol(9,10, $myrow[9], 2);
    $rep->AmountCol(10,11, abs($myrow[10]), 2);
    $rep->AmountCol(11,12, $myrow[11], 2);
    $rep->AmountCol(12,13, abs($myrow[12]), 2);
    $rep->AmountCol(13,14, $myrow[13], 2);
    $rep->AmountCol(14,15, abs($myrow[14]), 2);
    $rep->AmountCol(15,16, $myrow[15], 2);
    $rep->AmountCol(16,17, abs($myrow[16]), 2);
    $rep->AmountCol(17,18, $myrow[17], 2);
    $rep->AmountCol(18,19, abs($myrow[18]), 2);
    $rep->AmountCol(19,20, $myrow[19], 2);
    $rep->AmountCol(20,21, abs($myrow[20]), 2);
    $rep->AmountCol(21,22, $myrow[21], 2);
    $rep->AmountCol(22,23, abs($myrow[22]), 2);
    $rep->AmountCol(23,24, $myrow[23], 2);
    $rep->AmountCol(24,25, abs($myrow[24]), 2);
    $rep->NewLine();
}
    	 
    $rep->End();
}

?>