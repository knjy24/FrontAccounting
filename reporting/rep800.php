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
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Customer Balances
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/sales/includes/db/customers_db.inc");

//----------------------------------------------------------------------------------------------------

// trial_inquiry_controls();
printYearlyPurch();



//----------------------------------------------------------------------------------------------------

function printYearlyPurch()
{
    	global $path_to_root, $systypes_array;

    	///$from = $_POST['PARAM_0'];
    	$to = $_POST['PARAM_1'];
    	/*$fromcust = $_POST['PARAM_2'];
    	$show_balance = $_POST['PARAM_3'];
    	$currency = $_POST['PARAM_4'];
    	$no_zeros = $_POST['PARAM_5'];
    	$comments = $_POST['PARAM_6'];
	//$orientation = $_POST['PARAM_7'];*/
	$destination = $_POST['PARAM_3'];
	
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$orientation = 'L';
	//incrementing dapat
	$cols = array(0,20,55,85,125,200,230,260,290,325,350,375,425,475);
	//14 headers + 1 lagi dapat for cols
    //todo: format date paid to 2 digits representation only i.e. 12/12/12
	$headers = array(_('CV#'), _('Date Paid'), _('OR #'), _('Supplier'), _('Title of Book'), _('Quantity'),
		_('Unit Cost'),_('Amount') , _('With Tax'), _('Net') , _('PO #'), _('Stock Supplied'), _('Stock Amount') , _('Total Amount'));

	$aligns = array('left',	'left',	'left',	'left','left','left','left','left', 'left', 'left', 'left','left','left','left');

    $params =  null;
    //todo: modify concatenation of $to to include only year, not the whole date
    $rep = new FrontReport(_('Purchases for the year '.$to), "Purchases Year ".$to, user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();
    

	$grandtotal = array(0,0,0,0);

	$rep->NewLine();
    	$rep->End();
}

?>
