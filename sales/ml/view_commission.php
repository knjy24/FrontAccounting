<?php

$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../..";
include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
include_once($path_to_root . "/sales/ml/db/voucher_db.php");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = "Commission Voucher Inquiry"), false, false, "", $js);

start_form();


	start_table(TABLESTYLE_NOBORDER);
	start_row();
		salesman_list_cells(_("Select IMC: "), 'imc');
		text_cells(_("Invoice #"), 'invoice_no');

	submit_cells('RefreshInquiry', _("Search"),'',_('Refresh Inquiry'), 'default');
	end_row();
	end_table();
	br(3);


//------------------------------------------------------------------------------------------------

function imc_name($row, $type) 
{
	return get_salesman_name($row['imc']);
}

function invoice($row)
{
	return $row['invoice_no'];
}
//moodlearning
function date_view($row)
{
	return $row['tranDate'];
}

function client_view($row)
{
	return	get_customer_name($row['client']);
}

function prt_link($row)
{
 		return print_document_link($row['invoice_no'], _("&Print"), true, ST_VOUCHER);

}
//------------------------------------------------------------------------------------------------
$sql = get_sql_for_commission_voucher($_POST['imc'], $_POST['invoice_no']);

//------------------------------------------------------------------------------------------------
//db_query("set @bal:=0");
//moodlearning custom = invoice #
$cols = array(
	_("IMC") => array('fun'=>'imc_name', 'ord'=>''),
	_("Invoice #") => array('fun'=>'invoice', 'ord'=>''),
	_("Date") => array('fun'=>'date_view'), 
	_("Client") => array('fun' => 'client_view'),
		array('insert'=>true, 'fun'=>'prt_link')
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
