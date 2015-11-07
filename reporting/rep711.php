<?php

$page_security = 'SA_GLANALYTIC';

$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");

//----------------------------------------------------------------------------------------------------

print_subsidiary_ledger();


//----------------------------------------------------------------------------------------------------

function getTransaction($from, $to, $cat, $account, $account2)
{
    $fromdate = date2sql($from);
    $todate = date2sql($to);
    $sql = "SELECT DISTINCT a.type, a.type_no, a.account, a.amount, 
            DATE_FORMAT(a.tran_date, '%m/%d/%Y') as tranDate, a.person_type_id, a.person_id, v.type as Voided
            FROM ".TB_PREF."gl_trans a";

    $sql .=" LEFT JOIN ".TB_PREF."voided v on a.type=v.type AND a.type_no=v.id";

    if ($cat == 1)
    $sql .=" INNER JOIN ".TB_PREF."supp_trans supp on a.type=supp.type and a.type_no=supp.trans_no";

    $sql .= " WHERE a.tran_date >='$fromdate' AND a.tran_date <='$todate'";

    if ($cat == 1)
    $sql .= " AND a.person_id = ".db_escape($account)." and a.account= '$account2'";

    if ($cat == 3)
    $sql .= " AND a.person_id =".db_escape($account)." and a.person_type_id=6";

    $sql .= " ORDER BY a.type, a.type_no ASC";

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
    $account2 = $_POST['PARAM_4'];
    

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report2.inc");
    $orientation = ($orientation ? 'L' : 'P');

    $rep = new FrontReport(_('Subsidiary Ledger Report'), "SubsidiaryLedger", user_pagesize(), 9, L);
    $dec = user_price_dec();

    //$headers = array(_('Type'),   _('Ref'), _('#'),   _('Date'), _('Dimension')." 1", _('Dimension')." 2",
    //      _('Person/Item'), _('Debit'),   _('Credit'), _('Balance'));

  //$cols = array(0, 80, 100, 150, 210, 280, 340, 400, 450, 510, 570);
    $cols = array(0, 50, 140, 200, 210, 400, 450, 550, 600, 650);
    //------------0--1---2-----3----4----5----6----7----8----9----10-------
    //-----------------------dim1-dim2-----------------------------------
    //-----------------------dim1----------------------------------------
    //-------------------------------------------------------------------
    $aligns = array('left', 'left', 'left', 'left', 'left', 'right', 'right', 'right', 'right', 'right');

    
        //$headers = array(_('ID'), '', '', '', '', '', '', _('Debit'), _('Credit'), _('Balance'));

    
        $params =   array(  0 => $comments,
                        1 => array('text' => _('Period'), 'from' => $from, 'to' => $to),
                        2 => array('text' => _('Accounts'),'from' => $fromacc,'to' => $fromacc));
    
    if ($orientation == 'L')
        recalculate_cols($cols);
    $rep->SetHeaderType('header3');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();

    if ($cat == 3)
        $person = get_salesman_name($account);
    if ($cat ==1)
        $person = get_supplier_name($account);
    /*$rep->TextCol(0,2, $cat);
    $rep->NewLine();*/
    $rep->Font('bold');
    $rep->TextCol(0,2, "Name:".$person);
    $rep->NewLine();
    $rep->TextCol(0,1, "ID:");
    $rep->TextCol(1,2, "Src:");
    $rep->TextCol(2,3, "Date:");
    $rep->TextCol(4,5, "Memo:");
    $rep->TextCol(5,6, "Account:");
    $rep->TextCol(6,7, "Debit:");
    $rep->TextCol(8,9, "Credit:");
    $rep->Font();


    $rep->NewLine(2);

   $result = getTransaction($from, $to, $cat, $account, $account2);
   $type = '';
    while ($myrow=db_fetch($result))
    {
            if ($myrow['Voided'] == '' && $myrow['amount'] > 0)
            {
            $comments = get_comments_string($myrow['type'], $myrow['type_no']);
            $custom = get_custom_no($myrow['type_no'], $myrow['type']);
            if ($myrow['type'] == ST_DISBURSEMENT)
                $type = "CD";
            if ($myrow['type'] == ST_PURCHASEORDER)
                $type = "P.O.";
            if ($myrow['type'] == ST_SUPPAYMENT)
                $type = "CD";
            //else
            //    $type = $systypes_array[$myrow["type"]];
            $rep->TextCol(0,1, "#".$custom);
            $rep->TextCol(1,2, $type);
            $rep->TextCol(2,3, $myrow['tranDate']);
            $rep->TextCol(4,5, $comments);
            $rep->TextCol(5,6, $myrow['account']);
            

                $dr += $myrow['amount'];
                $rep->AmountCol(6,7, $myrow['amount'], 2);
                
            $rep->NewLine();
            }
            else if ($myrow['Voided'] == '' && $myrow['amount'] < 0)
            {
                $cr += $myrow['amount'];
                //$rep->AmountCol(8,9, $myrow['amount'], 2);
            }
    }
    $rep->NewLine(2);
    $rep->Font('bold');
    $rep->AmountCol(6,7, $dr, 2);
    $rep->NewLine(2);
    $rep->TextCol(1,2, "Net Activity: ");
    $rep->AmountCol(2,4, $cr, 2);
    $rep->End();
}
