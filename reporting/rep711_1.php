<?php

$page_security = 'SA_GLANALYTIC';

$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

//----------------------------------------------------------------------------------------------------

print_subsidiary_ledger();


//----------------------------------------------------------------------------------------------------

function getTransaction($from, $to, $cat, $account)
{
    $fromdate = date2sql($from);
    $todate = date2sql($to);
    $sql = "SELECT DISTINCT (custom.customized_no), a.type, a.type_no, a.account, a.amount, a.tran_date, a.person_type_id, a.person_id";
    if ($cat == 4)
        $sql .= " ,auth.* ";
    if ($cat == 1)
        $sql .= " ,strans.* ";
    if ($cat == 3)
        $sql .= " , cv.* ";


    $sql .= " from ".TB_PREF."gl_trans a";

    if ($cat == 4)
        $sql .= " INNER JOIN ".TB_PREF."royalty_sales auth on a.person_id = auth.author_id";
    if ($cat == 1)
        $sql .= " INNER JOIN ".TB_PREF."supp_trans strans on a.type=strans.type AND a.type_no = strans.trans_no";
    if ($cat == 3)
        $sql .= " ,".TB_PREF."comm_voucher cv";

    $sql .= " ,".TB_PREF."customized custom";

    $sql .= " where a.tran_date >='$fromdate' AND a.tran_date <= '$todate'";
    if ($cat == 4)
    $sql .= " AND auth.author_id =".db_escape($account);
    if ($cat == 1)
    $sql .= " AND strans.supplier_id =".db_escape($account);
    if ($cat == 3)
    $sql .= " AND a.person_id=cv.imc AND cv.imc=".db_escape($account);

    $sql .= " AND a.type=custom.type AND a.type_no=custom.type_no";

    return db_query($sql, "could not fetch transaction.");
    

}

function print_subsidiary_ledger()
{
    global $path_to_root, $systypes_array;

    $dim = get_company_pref('use_dimension');
    $dimension = $dimension2 = 0;

    $from = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $cat = $_POST['PARAM_2'];
    $account = $_POST['PARAM_3'];
    

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report2.inc");
    $orientation = ($orientation ? 'L' : 'P');

    $rep = new FrontReport(_('Subsidiary Ledger Report'), "SubsidiaryLedger", user_pagesize(), 9, $orientation);
    $dec = user_price_dec();

    //$headers = array(_('Type'),   _('Ref'), _('#'),   _('Date'), _('Dimension')." 1", _('Dimension')." 2",
    //      _('Person/Item'), _('Debit'),   _('Credit'), _('Balance'));

  //$cols = array(0, 80, 100, 150, 210, 280, 340, 400, 450, 510, 570);
    $cols = array(0, 65, 105, 125, 175, 230, 290, 345, 405, 465, 525);
    //------------0--1---2---3----4----5----6----7----8----9----10-------
    //-----------------------dim1-dim2-----------------------------------
    //-----------------------dim1----------------------------------------
    //-------------------------------------------------------------------
    $aligns = array('left', 'left', 'left', 'left', 'left', 'right', 'right');

    
        $headers = array(_('ID'), '', '', '', '', '', '', _('Debit'), _('Credit'), _('Balance'));

    
        $params =   array(  0 => $comments,
                        1 => array('text' => _('Period'), 'from' => $from, 'to' => $to),
                        2 => array('text' => _('Accounts'),'from' => $fromacc,'to' => $fromacc));
    
    if ($orientation == 'L')
        recalculate_cols($cols);
    $rep->SetHeaderType('0');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();

    $rep->NewLine(5);
    $rep->TextCol(4,8, "ST. MATTHEWS PUBLISHING CORPORATION");
    $rep->NewLine();
    $rep->TextCol(1,10, "First RVC Building #92 Anonas corner K-6 Streets, East Kamias, Quezon City 1102");
    $rep->NewLine(3);
    $rep->TextCol(4,8, "SUBSIDIARY LEDGER");
    $rep->NewLine(2);
    //date range here

    if ($cat == 3)
        $person = get_salesman_name($account);

    $rep->TextCol(0,2, "Name:".$person);
    $rep->NewLine();
    $rep->TextCol(0,1, "ID:");
    $rep->TextCol(1,2, "Src:");
    $rep->TextCol(2,3, "Date:");
    $rep->TextCol(3,4, "Memo:");
    $rep->TextCol(4,5, "Account:");
    $rep->TextCol(5,6, "Debit:");
    $rep->TextCol(6,7, "Credit:");



    $rep->NewLine(2);
  /*  $rep->TextCol(0,1, $from);
    $rep->TextCol(1,2, $to);
    $rep->TextCol(2,3, $cat);
    $rep->TextCol(3,4, $account);*/

    
    $rep->TextCol(0, 1, $person);
    $rep->NewLine();

    $result = getTransaction($from, $to, $cat, $account);

    while ($myrow=db_fetch($result))
    {

            if ($myrow['type'] == ST_DISBURSEMENT && $myrow['customized_no'] != 0)
                $type = "CV";
            else
                $type = "";
            $rep->TextCol(0,5, $type."#".$myrow['customized_no']);
            $rep->TextCol(2,3, "");
            $rep->TextCol(3,4, $myrow['tran_date']);
            $rep->TextCol(4,5, $myrow['account']);
            $rep->AmountCol(5,6, $myrow['amount'], 2);

            if ($myrow['amount'] < 0)
                $cr += $myrow['amount'];

            if ($myrow['amount'] > 0)
                $dr += $myrow['amount'];
            
            $rep->NewLine();
    }
    $rep->AmountCol(5,6, $dr, 2);
    $rep->NewLine();
    $rep->AmountCol(1,2, abs($cr), 2);
    $rep->End();
}
