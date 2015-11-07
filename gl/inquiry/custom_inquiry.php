<?php

$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../..";
include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");


include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");
include_once($path_to_root . "/gl/ml/ui/gl_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

$js = "";

if ($use_date_picker)
	$js .= get_js_date_picker();

if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = "Disbursement/Journal Voucher Inquiry"), false, false, "", $js);

if (isset($_GET['UpdatedID']))
{
	$trans_no = $_GET['AddedID'];
	display_notification_centered( _("Check voucher entry has been edited") . " #$trans_no");
}

start_form();


	start_table(TABLESTYLE_NOBORDER);
	start_row();
		booklet_type_row_list(_("Select type: "), 'type');
		text_cells(_("Voucher #"), 'voucher_no');
		date_cells(_("From:"), 'FromDate', '', null, 0, -1, 0);

	submit_cells('RefreshInquiry', _("Search"),'',_('Refresh Inquiry'), 'default');
	end_row();
	end_table();
	br(3);


//------------------------------------------------------------------------------------------------

function type_name($dummy, $type)
{
	global $systypes_array;
	
	return $systypes_array[$type];
}

function voucher($row)
{
	$vouch = substr($row['YearDate'], 2). "-". str_pad($row['customized_no'], 4, 0, STR_PAD_LEFT);
	return $vouch;
}
//moodlearning
function date_view($row)
{

	return $row['tranDate'];
}

function prt_link($row)
{
 		if ($row['type'] == ST_JOURNAL)
 			$trans_type = ST_JOURNAL;
 		if ($row['type'] == ST_DISBURSEMENT)
 			$trans_type = ST_DISBURSEMENT;


 		return print_document_link($row['type_no'], _("&Print"), true, $trans_type);

}

$editors = array(
	ST_JOURNAL => "/gl/gl_journal.php?ModifyGL=Yes&trans_no=%d&trans_type=%d",
	ST_DISBURSEMENT => "/gl/edit_check_voucher.php?ModifyDisbursement=Yes&trans_no=%d&type_id=%d",
);

function edit_link($row)
{
	global $editors;

	$ok = true;
	if ($row['type'] == ST_SALESINVOICE)
	{
		$myrow = get_customer_trans($row["type_no"], $row["type"]);
		if ($myrow['alloc'] != 0 || get_voided_entry(ST_SALESINVOICE, $row["type_no"]) !== false)
			$ok = false;
	}	

	if ($row['person_type_id'] != PT_SUPPLIER || $row['person_type_id'] != PT_CUSTOMER)	
	return isset($editors[$row["type"]]) && !is_closed_trans($row["type"], $row["type_no"]) && $ok ? 
		pager_link(_("Edit"), 
			sprintf($editors[$row["type"]], $row["type_no"], $row["type"]),
			ICON_EDIT) : '';
}

//------------------------------------------------------------------------------------------------
$sql = get_sql_for_custom_inquiry($_POST['type'], $_POST['voucher_no'], $_POST['TransFromDate']);
echo $_POST['voucher_no'];
//------------------------------------------------------------------------------------------------
//db_query("set @bal:=0");
//moodlearning custom = invoice #
$cols = array(
	_("Date") => array('fun'=>'date_view'), 
	_("Type") => array('fun'=>'type_name', 'ord'=>''),
	_("Voucher #") => array('fun'=>'voucher', 'ord'=>''),
		array('insert'=>true, 'fun'=>'prt_link'),
		array('insert'=>true, 'fun'=>'edit_link')
	);

if (isset($_POST['customer_id'])) {
	$cols[_("Customer")] = 'skip';
	$cols[_("Currency")] = 'skip';
}

$table =& new_db_pager('trans_tbl', $sql, $cols);

$table->width = "85%";

display_db_pager($table);

if (!@$_GET['popup'])
{
	end_form();
	end_page(@$_GET['popup'], false, false);
}
?>
