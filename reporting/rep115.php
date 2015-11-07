<?php

$page_security = 'SA_SALESTRANSVIEW';
// ----------------------------------------------------------------
// 
// Creator:	Karen 
// date_:	
// Title:	Subsidiary Ledger
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/ml/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

//----------------------------------------------------------------------------------------------------

print_voucher();

function get_transaction($voucher_no){
    $sql = "SELECT a.imc, a.invoice_no, a.client, a.gross, a.with_tax, a.commission, a.net_commission, (DATE_FORMAT(a.date, '%b. %d')) as Dated, YEAR(a.date) as dateYear from ".TB_PREF."comm_voucher a where invoice_no =".db_escape($voucher_no)."";
    return db_query($sql, "Cannot retrieve commission voucher transaction");

}
//----------------------------------------------------------------------------------------------------

function print_voucher()
{
    global $path_to_root, $systypes_array;

    $dim = get_company_pref('use_dimension');
    $dimension = $dimension2 = 0;

    $voucher_no = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $destination = $_POST['PARAM_2'];
    

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report2.inc");
    $orientation = ($orientation ? 'L' : 'P');

    $rep = new FrontReport(_('COMMISSION VOUCHER'), "CommissionVoucherReport", user_pagesize(), 9, $orientation);
    $dec = user_price_dec();

   
    $cols = array(-15, 20, 40, 60, 90, 150, 200, 225, 220, 465, 525);
    //------------0--1---2---3----4----5----6----7----8----9----10-------
    //-----------------------dim1-dim2-----------------------------------
    //-----------------------dim1----------------------------------------
    //-------------------------------------------------------------------
    $aligns = array('left', 'left', 'left', 'left', 'left', 'right', 'right', 'right', 'right', 'right', 'right');

    
        $headers = array('', _('Commission Voucher'), '', '', '', '', '');

    
    if ($orientation == 'L')
        recalculate_cols($cols);
    $rep->SetHeaderType(0);
    $rep->Font();
    $rep->Info(null, $cols, null, $aligns);
    $rep->NewPage();

    $res = get_transaction($voucher_no);
    $myrow = db_fetch($res);
    $salesman = get_salesman_name($myrow['imc']);
    $client = get_customer_name($myrow['client']);

    $rep->Font('bold');
   // $rep->TextCol(4,8, _("ST. MATTHEW'S PUBLISHING CORPORATION"));
    $rep->Font();
    $rep->NewLine();
   // $rep->TextCol(4,8, _("Tel. Nos. 426-5611 * 433-5385 Telefax: 426-1274"));
    $rep->NewLine(5);
    $rep->Font('bold');
    $rep->SetFontSize(14);
    //$rep->TextCol(0,3, _("COMMISSION VOUCHER"));
    $rep->Font();
    $rep->SetFontSize(10);
    $rep->NewLine(4);
    $netgross = ($myrow['gross'] * ($myrow['commission']/100));
    //$rep->TextCol(8,9, _("Date:"));
    $rep->TextCol(5,6, $myrow['Dated']);
    $year = substr($myrow['dateYear'],2);
    $rep->TextCol(6,7, $year);
    $rep->NewLine(1);
    $rep->TextCol(1,4, $salesman);
    $rep->NewLine(3);
    //$rep->Line($rep->row  - -10);
    //$rep->TextCol(2,5, _("EXPLANATION"));
    //$rep->TextCol(8,9, _("AMOUNT"));
   // $rep->Line($rep->row  - 4);
    $rep->NewLine(2);
    $rep->SetFontSize('8');
    $oldrow = $rep->row;
    $rep->TextColLines(2,5, $client);
    $newrow = $rep->row;
    $rep->row = $oldrow;
    $rep->NewLine(3);
    $rep->SetFontSize('10');
    //$rep->TextCol(8,9, _("Gross: "));
    $rep->AmountCol(5,7,  $netgross, 2);
    $rep->NewLine(1);
    $rep->TextCol(2,4, $myrow['commission']);
    $rep->AmountCol(5,7, $myrow['with_tax'], 2);
    $rep->NewLine(2);
    $rep->TextCol(2,4, $myrow['invoice_no']);
    $rep->NewLine(4);
    $rep->Font('bold');
    $rep->AmountCol(5,7, $myrow['net_commission'], 2);
    $rep->NewLine();
   // $rep->Line($rep->row  - 4);
   // $rep->Line($rep->row  - 8);
    $rep->NewLine(3);
    $rep->Font();
    $rep->SetFontSize('9');
    //$rep->TextCol(1,4, _("Approved by:"));
   // $rep->TextCol(5,8, _("Received payment by:"));
    $rep->NewLine(2);
    $rep->TextCol(2, 4, _("Checked by:"));
  
    

   
    $rep->End();
}
