<?php

$page_security = 'SA_GLANALYTIC';
$path_to_root="../..";

include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/gl/ml/ui/gl_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc"); // added for calling subsidiary ledger report



$js = "";
if ($use_date_picker)
	$js = get_js_date_picker();

page(_($help_context = "Subsidiary Ledger"), false, false, "", $js);

function inquiry_controls()
{  
	global $Ajax;
	$dim = get_company_pref('use_dimension');
    start_table(TABLESTYLE_NOBORDER);
    
	$date = today();
	if (!isset($_POST['TransFromDate']))
		$_POST['TransFromDate'] = begin_month($date);
	if (!isset($_POST['TransToDate']))
		$_POST['TransToDate'] = end_month($date);
    date_cells(_("From:"), 'TransFromDate');
	date_cells(_("To:"), 'TransToDate');

	type_list_cells(_("Category: "), 'typeId', null, true);

	$r = set();
	account_list_cells(_(""), 'accountId', $r);

	submit_cells('submit_submit', _("Generate Report"), true, '', 'default');
	//submit_cells('RefreshInquiry', _("Search"),'',_('Refresh Inquiry'), 'default');
	
	
    end_table();

}

function set()
{  
	if (isset($_POST['typeId']))
		return $_POST['typeId'];
	
}

function can_process(){
	if ($_POST['typeId'] == ''){
		display_error(_("Please select a category"));
		set_focus('typeId');
		return false;
	}
	return true;
}

function handle_report(){
	global $Ajax;
	if (can_process()){
		$from = $_POST['TransFromDate'];
		$to = $_POST['TransToDate'];
		$typeId = $_POST['typeId'];
		$accountId = $_POST['accountId'];

		
		display_notification(_('Report successfully generated.'));
		$arr = array($from, $to, $typeId, $accountId);
		$trans_type = ST_SUBSIDIARY;
		display_note(print_document_link($arr, _("&Print Report"), true, $trans_type));
	}
	
	else{
		display_notification(_('Report not generated, please contact the administrator.'));
	}
	$Ajax->activate('_page_body');
	
	return;
}

//----------------------------------------------------------------------------------------------------



//----------------------------------------------------------------------------------------------------

start_form();

inquiry_controls();

if (isset($_POST['submit_submit']))
	handle_report();

end_form();

end_page();

?>