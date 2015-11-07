<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
    Released under the terms of the GNU General Public License, GPL, 
    as published by the Free Software Foundation, either version 3 
    of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_CUSTPAYMREP';

// ----------------------------------------------------------------
// $ Revision:  2.0 $
// Creator: Joe Hunt
// date_:   2005-05-19
// Title:   Customer Balances
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/modules/payroll/includes/payroll_db.inc");
include_once($path_to_root . "/modules/payroll/includes/payroll_ui.inc");

//----------------------------------------------------------------------------------------------------

// trial_inquiry_controls();
print_employee_payslip();


//----------------------------------------------------------------------------------------------------

function print_employee_payslip()
{
        global $path_to_root, $SysPrefs, $dflt_lang;

    include_once($path_to_root . "/reporting/includes/pdf_report2.inc");

    $from = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $email = $_POST['PARAM_2'];
    $comments = $_POST['PARAM_3'];
    $orientation = $_POST['PARAM_4'];


    if (!$from || !$to) return;

    $orientation = ($orientation ? 'L' : 'P');
   // $fno = explode("-", $from);
   // $tno = explode("-", $to);
   // $from = min($fno[0], $tno[0]);
   // $to = max($fno[0], $tno[0]);

   // $cols = array(4, 60, 190, 200);

   $cols = array(4, 50,50,50,50);

    // $headers in doctext.inc
    $aligns = array('left', 'left', 'left', 'left', 'right');

    $params = array('comments' => $comments);

    if ($email == 0)
        $rep = new FrontReport(_('PAYSLIP'), "PayslipBulk", user_pagesize(), 9, $orientation);
        $rep = new FrontReport("Payroll", "", user_pagesize(), 9, $orientation);
            $rep->title = _('PAYSLIP');
            $rep->filename = "Payslip" . $myrow['payslip_id'] . ".pdf";
    if ($orientation == 'L')
        recalculate_cols($cols);

    for ($i = $from; $i <= $to; $i++)
    {
        $myrow = get_payslip($i);
        if ($myrow === false)
            continue;
        $date_ = sql2date($myrow["date_"]);
        if ($email == 1)
        {
            $rep = new FrontReport("Payroll", "", user_pagesize(), 9, $orientation);
            $rep->title = _('PAYSLIP');
            $rep->filename = "Payslip" . $myrow['payslip_id'] . ".pdf";
        }
        $rep->SetHeaderType('Header_custom'); //display company logo and info on header
       
        //$rep->currency = $cur;
        $rep->Font();

        $rep->Info($params, $cols, null, $aligns);


       $rep->SetCommonData($myrow, null, null, '', 100, $contact);
        $rep->NewPage();

        $result = get_payslip($i);
        //$rep->TextCol(0, 5,_("Breakdown"), -2);
       // $rep->NewLine(2);
        $rep->TextCol(0, 5, _("Payslip No:").$myrow['payslip_id'], -2);
        $rep->TextCol(2, 6, _("Employee: ").$myrow['EmpNames'], -2);
        $rep->TextCol(3, 6, _("Role Pay: ").$myrow['role_pay'], -2);
        $rep->NewLine(2);
        $rep->NewLine(2);

    }
    if ($email == 0)
        $rep->End();
}
    

?>
