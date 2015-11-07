<?php

$page_security = 'SA_GLANALYTIC';
// ----------------------------------------------------------------
// 
// Creator: Karen 
// date_:   
// Title:   Subsidiary Ledger
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");

//----------------------------------------------------------------------------------------------------

print_check_voucher();


//----------------------------------------------------------------------------------------------------

function get_transaction($trans_no, $trans_type){


    $sql = "SELECT DISTINCT (DATE_FORMAT(a.tran_date, '%b. %d, %Y')) as sqldate, YEAR(a.tran_date) as yearDate,
    a.person_id, a.person_type_id,a.memo_, a.amount,a.account 
    FROM ".TB_PREF."gl_trans a where a.type=".db_escape($trans_type)." and a.type_no = ".db_escape($trans_no)."";

    return db_query($sql,"Could not get check voucher transaction");
}

function get_heading($trans_no, $trans_type){
    $sql = "SELECT a.* from ".TB_PREF."customized a where a.type=".db_escape($trans_type)." and a.type_no = ".db_escape($trans_no)."";
    return db_query($sql,"Could not get check voucher transaction");
}

function print_check_voucher()
{
    global $path_to_root, $systypes_array;


    $trans_no = $_POST['PARAM_0'];
    $trans_num = $_POST['PARAM_1'];
    $cv_no = $_POST['PARAM_2'];
    $account = $_POST['PARAM_3'];
    $orientation = $_POST['PARAM_4'];
    $destination = $_POST['PARAM_5'];
    

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report2.inc");
    $orientation = ($orientation ? 'L' : 'P');

    $rep = new FrontReport(_('CHECK VOUCHER'), "CheckVoucher", user_pagesize(), '', $orientation);
    $dec = user_price_dec();
   
    $cols = array(0, 30, 50, 125, 175, 200, 265, 285, 360, 425, 460, 542, 550);

    $aligns = array('left', 'left', 'left', 'left', 'right', 'right', 'left', 'left', 'right', 'right', 'right');
   
    $rep->SetHeaderType(0);
    $rep->Font();
    $rep->Info(null, $cols, null, $aligns);
    $rep->NewPage();
    $ty = explode("-", $trans_no);
    $t_type = $ty[1];

    if ($t_type != 0)
    {
      $res = get_transaction($trans_no, $t_type);
      $res1 = get_heading($trans_no, $t_type);
    }
    else
    {
       $res = get_transaction($trans_no, ST_DISBURSEMENT);
      $res1 = get_heading($trans_no, ST_DISBURSEMENT);
    }
    
    $myrow = db_fetch($res);
    $myrow1 = db_fetch($res1);

    $credit = $debit = 0;
    //$transtype = ST_DISBURSEMENT;
    if ($t_type != 0 || $t_type!= '')
    {
      $res2 = get_gl_trans($t_type, $trans_num);
    $res3 = get_gl_trans($t_type, $trans_num);
    }
    else
    {
      $res2 = get_gl_trans(ST_DISBURSEMENT, $trans_num);
    $res3 = get_gl_trans(ST_DISBURSEMENT, $trans_num);
    }
    
    $new_ret = 0;
    $return = 0;

    //$fiscal = get_year(); //to be use
   // $year_no = substr(sql2date($fiscal['begin']), 8); //to be use

    
   // $ser = db_fetch(get_used_series($year_no, $transtype));
   // $num = substr($ser['year'], 2);

   /* $rep->Font('bold');
    $rep->TextCol(7,11, _("ST. MATTHEW'S PUBLISHING CORPORATION"));
    $rep->Font();
    $rep->NewLine();
    $rep->TextCol(7,11, _("Tel. Nos. 426-5611 * 433-5385 Telefax: 426-1274"));
    $rep->NewLine(3);
    $rep->Font('bold');
    $rep->SetFontSize(14);
    $rep->TextCol(5,8, _("CHECK VOUCHER"));*/
   $rep->NewLine(7);
    $rep->SetFontSize(12);
    $num = substr($myrow['yearDate'], 2);
    $name = payment_person_name($myrow["person_type_id"],$myrow["person_id"]);
    $rep->TextCol(2,6, $name);
    $rep->TextCol(9, 11, $num. "-". str_pad($myrow1['customized_no'], 4, 0, STR_PAD_LEFT));
    $rep->NewLine();
    if ($myrow1['customized_field'] != 0 || $myrow1['customized_field'] != '')
         $rep->TextCol(0,4, $myrow1['customized_field']);
     else
        $rep->TextCol(0,4, _(""));
    $rep->NewLine(1);
    $rep->TextCol(8, 10, $myrow['sqldate']);
    $rep->NewLine(5);
   // $rep->Line($rep->row  - -10);
  //  $rep->TextCol(4,6, _("PARTICULARS"));
   // $rep->TextCol(9,10, _("AMOUNT"));
  //  $rep->Line($rep->row  - 4);

    $var = array();
    $pos = array();
    $neg = array();
    $pos_amt = array();
    $neg_amt = array();
    $tots = 0;
     $comment = get_comments_string($cv_no, $trans_num);
    while($myrow2 = db_fetch($res2)){
   
    $tots = 0;
    if (is_bank_account($myrow2['account']))
    {   $var = array($myrow2['account']);
        $rep->TextCol(1,8, $comment);
        $tots += abs($myrow2['amount']);
    } 
    }
    $rep->AmountCol(9,11, $tots, 2);
    $rep->NewLine(2);
   // $rep->Line($rep->row  - -10);
   // $rep->TextCol(1,3, _("DEBIT"));
   // $rep->TextCol(7,8, _("CREDIT"));
   // $rep->Line($rep->row  - 4);
    $rep->NewLine(5);
      $rep->SetFontSize(12);
    while($myrow3 = db_fetch($res3)){
        $accounts = get_gl_accounts($myrow3['account']);
        $account = db_fetch($accounts);
      

       if ($myrow3['amount'] > 0){
       // $rep->Line($rep->row  - -10);
            //$rep->TextCol(0,4, $account['account_name']);
          //  $rep->AmountCol(5,6, $myrow3['amount'],2);
          //  $rep->NewLine();
          //  $rep->Line($rep->row  + 8);

        array_push($pos, $account['account_name']);
        array_push($pos_amt, $myrow3['amount']);
            $debit += $myrow3['amount'];
        } else {

        array_push($neg,$account['account_name']);
        array_push($neg_amt, $myrow3['amount']);
          //  $rep->Line($rep->row  - 4);
          //  $rep->TextCol(6,8, $account['account_name']);
          //  $rep->AmountCol(9,11, abs($myrow3['amount']), 2);
          //  $rep->NewLine();
           // $rep->Line($rep->row  - 4);
        }
    }


$result = array_merge($pos, $neg);
$new = array();
for ($i=0; $i<count($result); $i++) {
   $new[] = $pos[$i];
   $new[] = $neg[$i];
}

$result2 = array_merge($pos_amt, $neg_amt);
$new2 = array();
for ($j=0; $j<count($result2); $j++) {
   $new2[] = $pos_amt[$j];
   $new2[] = $neg_amt[$j];
}

$final_res = array_merge($new, $new2);
$final_array = array();
$final = array();
for ($k=0; $k<count($final_res); $k++) {
   $final[] = $new[$k];
   $final[] = $new2[$k];
}

$enter = 0;
for($i=0; $i<=count($final_res); $i++)
{
  //if ($final[$i] != 0)
  //{
    $rep->TextCol(0,4, $pos[$i]);
    if ($pos_amt[$i] != 0)
    $rep->AmountCol(4,6, $pos_amt[$i], 2);
    $rep->TextCol(7,9, $neg[$i]);
    if ($neg_amt[$i] != 0)
    $rep->AmountCol(9,11, abs($neg_amt[$i]), 2);
  if ($pos[$i] != "")
  {
    $rep->NewLine();
    $enter++;
  }
  

 // }
}

$return = 11 - $enter;

    //$rep->Line($rep->row  - 4);
  $rep->NewLine($return);
//$rep->TextCol(0,2, count($final_res));
//$rep->TextCol(4,5, $enter);
//else
  //  $rep->NewLine($return);
    $rep->SetFontSize(10);
   // $rep->TextCol(0,2, _("Cash"));
    $words = price_in_words($tots, ST_CHEQUE);
    $_word = strlen($words);
    $first_word = substr($words, 0,34);
    $second_word = substr($words, 34);
           // if ($words != "")
           // {
                //$rep->TextCol(5,7, _("Received the sum of :"));
               // $oldrow = $rep->row;
               $rep->TextColLines(8, 11, $first_word."-", - 2);
                //$newrow = $rep->row;
               // $rep->row = $oldrow;

                
           // }   

   $rep->SetFontSize(10);
      $rep->TextCol(1,3, $myrow1['check_num']);
      $tots_dec = price_format($tots);
      $rep->TextCol(3,7, "                       ".$tots_dec);
       $rep->SetFontSize(10);
      $rep->TextCol(7,11, $second_word);
    //$rep->TextCol(7,10, _("as payment of the above particulars."));
    $rep->NewLine();

    $rep->SetFontSize(10);
   //$rep->TextCol(0,10, _("Bank/Branch: "));
    foreach($var as $vars)
    {
       // $bank_name = get_bank_name($vars);
        $rep->TextCol(2,10, get_bank_name($vars). " ");
    }
  //  $rep->TextCol(1,5, $myrow['bank_address']);
    $rep->TextCol(7,9, "     ".$tots_dec);
    $rep->End();
}
