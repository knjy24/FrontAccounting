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
include_once($path_to_root . "/includes/ui/ui_view.inc");
include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");

//----------------------------------------------------------------------------------------------------

print_check_voucher();


//----------------------------------------------------------------------------------------------------

function get_transaction($trans_no){


    $sql = "SELECT a.*, b.*, DATE_FORMAT(a.tran_date, '%m-%d-%Y') as tranDate FROM ".TB_PREF."gl_trans a inner join ".TB_PREF."customized b on a.type=b.type where a.type = 0 and a.type_no = ".db_escape($trans_no)." and b.type_no = ".db_escape($trans_no)."";

    return db_query($sql,"Could not get check voucher transaction");
}

function print_check_voucher()
{
    global $path_to_root, $systypes_array;


    $trans_no = $_POST['PARAM_0'];
    $trans_num = $_POST['PARAM_1'];
    $orientation = $_POST['PARAM_2'];
    $destination = $_POST['PARAM_3'];
    

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report2.inc");
    $orientation = ($orientation ? 'L' : 'P');

    $rep = new FrontReport(_('JOURNAL VOUCHER'), "JournalVoucher", user_pagesize(), 9, $orientation);
    $dec = user_price_dec();
   
    $cols = array(0, 65, 105, 125, 175, 210, 250, 300, 360, 370);

    $aligns = array('left', 'left', 'left', 'center', 'right', 'right', 'right', 'right', 'right');
   
    $rep->SetHeaderType(0);
    $rep->Font();
    $rep->Info(null, $cols, null, $aligns);
    $rep->NewPage();
    $res = get_transaction($trans_no);
    $myrow = db_fetch($res);

    $credit = $debit = 0;
    $res2 = get_gl_trans(0, $trans_num);
    $res3 = get_gl_trans(0, $trans_num);

    $fiscal = get_year(); //to be use
    $year_no = substr(sql2date($fiscal['begin']), 8); //to be use

    $transtype = ST_JOURNAL;
    $ser = db_fetch(get_used_series($year_no, $transtype));
    $num = substr($ser['year'], 2);

    $rep->Font('bold');
    //$rep->TextCol(7,11, _("ST. MATTHEW'S PUBLISHING CORPORATION"));
    $rep->Font();
    $rep->NewLine();
   // $rep->TextCol(7,11, _("Tel. Nos. 426-5611 * 433-5385 Telefax: 426-1274"));
    $rep->NewLine(3);
    //$rep->Font('bold');
    //$rep->SetFontSize(14);
    //$rep->TextCol(3,8, _("JOURNAL VOUCHER"));
    $rep->NewLine(5);
    $rep->SetFontSize(10);
    $rep->TextCol(0,2, $myrow['tranDate']);
    $rep->SetFontSize(12);
    $rep->TextCol(6, 8, $num ."-". str_pad($myrow['customized_no'], 4, 0, STR_PAD_LEFT));

    $rep->NewLine(2);

    //$rep->Line($rep->row  - -10);
   // $rep->TextCol(7,8, _("DEBIT"));
    //$rep->TextCol(9,10, _("CREDIT"));
    //$rep->TextCol(5,7, _("ACCOUNT CODE"));
       $rep->SetFontSize(10);
    $rep->Font();
    //$rep->Line($rep->row  - 4);
    $rep->NewLine(2);
   
    while($myrow3 = db_fetch($res3)){
        $accounts = get_gl_accounts($myrow3['account']);
        $account = db_fetch($accounts);
      

       if ($myrow3['amount'] > 0){
      
            $rep->TextCol(0,3, $account['account_name']);
            $rep->TextCol(4,5, $account['account_code']);
            $rep->AmountCol(5,7, $myrow3['amount'], 2);
            $rep->NewLine();
      
            $debit += $myrow3['amount'];
        } else {
      
            $rep->TextCol(0,3, $account['account_name']);
            $rep->TextCol(4,5, $account['account_code']);
            $rep->AmountCol(7,9, abs($myrow3['amount']), 2);
            $rep->NewLine();
            $credit += abs($myrow3['amount']);
           
        }
    }
   // $rep->Line($rep->row  - -4);
    $rep->NewLine(17);
    $rep->Font('bold');
    //$rep->TextCol(0,4, _("Total"));
    $rep->AmountCol(5,7, $debit, 2);
    $rep->AmountCol(7,9, $credit, 2);
    $rep->NewLine(2);
    $rep->Font();
    $comment = get_comments_string($transtype, $trans_num);
    $rep->TextCol(0, 9, $comment);

   // $rep->Line($rep->row  - 4);

   
    $rep->End();
}
