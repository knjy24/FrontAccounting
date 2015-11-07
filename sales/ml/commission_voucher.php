<?php

$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/ml/voucher_search.php");
include_once($path_to_root . "/sales/ml/ui/voucher_ui.php");
include_once($path_to_root . "/sales/ml/db/voucher_db.php");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");
include_once($path_to_root . "/reporting/includes/reporting.inc");


$js = '';
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

/*========================================START OF PAGE=========================================*/
page(_($help_context = "Manage Commission"), @$_REQUEST['popup'], false, "", $js); 

global $imc;


if (isset($_GET['AddedID']))
{
	$id = $_GET['AddedID'];
	$trans_type = ST_VOUCHER;
	display_notification_centered(sprintf( _("Commission Voucher for Invoice # %d has been entered."),$id));

	submenu_option(_("Create New Commission Voucher"),	"/sales/ml/commission_voucher.php");

	display_note(print_document_link($id, _("&Print Voucher"), true, $trans_type));

	display_footer_exit();
}

function clear_data()
{
	unset($_POST['commission']);
}

function computation_1($gross, $tax, $commission){


	$sub1 = $gross * ($commission / 100);
	$sub2 = $sub1 * ($tax / 100);
	$totalcommission = $sub1 - $sub2;

	$_POST['total'] = $totalcommission;
	$_POST['tax'] = $sub2;
	

}


function can_process(){

	if (strlen($_POST['invoice_no']) == 0){
		display_error("Select below to make a commission voucher");
		return false;

	}

	if (strlen($_POST['invoice_no'] == 0)  || strlen($_POST['provision']) == 0 || strlen($_POST['client']) == 0 || strlen($_POST['total']) == 0 || strlen($_POST['gross']) == 0 || strlen($_POST['net_commission'])){
		display_error("Required fields should not be empty.");
		return false;
	}

	if (checkvouchexists($_POST['invoice_no']) != 0) 
	{
		display_error("Invoice Number already exists. Please check again.");
		return false;
	}

	return true;
}


function voucher_details($id){

	div_start('voucher_table');
	br();	

	if (!isset($_POST['imc']) || list_updated($_POST['imc'])) {
		$imc = get_post('imc'); 
		$row = get_salesman_trans($imc);
		$_POST['sales'] = $row['salesman_name'];
		$_POST['invoice_no'] = $row['customized_no'];
		$_POST['client'] = $row['br_name'];
		$_POST['provision'] = $row['provision'];
		$_POST['total'] = $row['InvoiceTotal'];
	}


	div_start('detail_table');
	start_outer_table(TABLESTYLE2);
	table_section(1);

	date_row(_("Date:"), 'date');
	text_row(_("IMC: "), 'sales');
	text_row(_("Invoice No."), 'invoice_no');
	text_row(_("Client: "), 'client');
	hidden('gross', $_POST['gross']);

		table_section(2);
		amount_row(_("Commission %: "), 'provision');
		label_row(_("Gross Commission: "), price_format($_POST['netsales']));
		label_row(_("W/Tax: "), price_format($_POST['tax']));
		label_row(_("Net Commission: "), price_format($_POST['total']));


		hidden('netsales', $_POST['netsales']);
		hidden('tax', $_POST['tax']);
		hidden('total', $_POST['total']);

		submit_row('Compute', _("Compute"), '', 'default');

		hidden('br_id', $_POST['br_id']);
		hidden('discount', $_POST['discount']);
		hidden('returns', $_POST['returns']);

	end_outer_table(1);

	div_start('controls');
	if (isset($_POST['Compute']))
		submit_center_first('Submit', _("Create Commission Voucher"), '', 'default');
	div_end();
	br();


	start_table(TABLESTYLE, "width=90%");
	$th = array(_('IMC'), _('Invoice #'), _('Date'), _('Client'), '');
	table_header($th);
	$k=0;
	$res = get_salesman_trans($_POST['imc'], $_POST['customNum']);
	while ($myrow = db_fetch($res)) {
		   alt_table_row_color($k);

		   if (checkvouchexists($myrow['customized_no']) == 0) {
		   		if ($myrow['Voided'] == '')
		   		{
		   			label_cell($myrow['salesman_name']);
			label_cell($myrow['customized_no']);
			label_cell($myrow['tran_date']);
			label_cell($myrow['br_name']);
			edit_button_cell("Edit".$myrow['customized_no'], _("Select"));
		   		}
		   		
		   }
		
		end_row();
	}

	end_table(1);
	div_end();
	div_end();
	
	br();

}



start_form();

$selected_id = $_POST['imc'];


if (db_has_salesman()) {
	div_start('sample');
		start_table(TABLESTYLE_NOBORDER);
			start_row();
				if(isset($_POST['imc'])){
					$_POST['sales_id'] = key(get_imc());
   					 }

				imc_list_cells(_("IMC:"), 'imc', null, false, true);
				text_cells(_("#:"), 'customNum');
				submit_cells('Search', _("Search"),'',_('Refresh Inquiry'), 'default');
				hidden('sales_id', $_POST['sales_id']);
			end_row();
		end_table();
	div_end();

	if (get_post('_show_inactive_update')) {
		$Ajax->activate('imc');
		set_focus('imc');
	}

	} else {	
    display_error("Enter some IMC/Salesman.");
    hidden('imc');
	}
  
$edit_id = find_submit("Edit");
if ($edit_id != -1){

	$myrow = db_fetch(get_selected_salesman($edit_id));

	$company_data = get_company_prefs();
	$branch = get_branch($myrow["branch_code"]);
	$branch_data = get_branch_accounts($myrow['branch_code']);

	clear_data();

	$_POST['sales'] = $myrow['salesman_name']; //get imc name
	$_POST['invoice_no'] = $myrow['customized_no']; //get invoice number
	$_POST['client'] = $myrow['br_name']; //get customer name
	$_POST['br_id'] = $myrow['debtor_no'];
	$_POST['order_no'] = $myrow['order_'];

	$dt = get_discount($branch_data['sales_discount_account'], $myrow['type'], $myrow['trans_no']);

	$res = get_returns($_POST['order_no']);
	
	while ($myrow2 = db_fetch($res))
	{

		$rtn += $myrow2['ov_amount'];
		$res2 = get_return_discount($branch_data['sales_discount_account'], $myrow2['type'], $myrow2['trans_no']);
		while ($myrow3 = db_fetch($res2)){
			$rtn_dt += abs($myrow3['amount']);
		}
		
	}

	$total_return = $rtn + $rtn_dt;
	$total_invoice = $myrow['ov_amount'] + $dt;
	$gr = $total_invoice - $total_return;
	
	
	/*---------get discount-------*/
	$dsc = ($dt / $total_invoice) * 100;
	$_POST['discount'] = $dsc;
	/*----------------------------*/
	$_POST['returns'] = $total_return;
	$_POST['gross'] = $_POST['netsales'] = $gr;
	$_POST['invoice_no'] = $edit_id;
	$Ajax->activate('voucher_table');
} 

if (isset($_POST['Compute'])) {

	$gross = $_POST['gross'];
	$return = $_POST['returns'];
	$discount = $_POST['discount'];
	$tax = 10;
	$commission = $_POST['provision'];

		computation_1($gross, $tax, $commission);

}

if (isset($_POST['Submit'])){
	$sm = get_post('imc');
	$invoice = $_POST['invoice_no'];
	$client = $_POST['br_id'];
	$gross = $_POST['gross'];
	$tax = $_POST['tax'];
	$commission = $_POST['provision'];
	$date = $_POST['date'];
	$discount = $_POST['discount'];
	$net_commission = abs($_POST['total']);
	$return = $_POST['returns'];


	if(can_process()){

		insert_comm_voucher($sm, $invoice, $client, $gross, $commission, $tax, $return, $discount, $net_commission, $date);
		meta_forward($_SERVER['PHP_SELF'], "AddedID=$invoice");
		
	}

}

//--------------------------------------------------------------------------------------------

if (!$imc)
  unset($_POST['_tabs_sel']); 
  
tabbed_content_start('tabs', array(
		     'settings' => array(_('&Commission Voucher'), $_POST['imc'])));

  switch (get_post('_tabs_sel')){
    default:
	case 'comp':
      voucher_details();
      break;
  }
br();
tabbed_content_end();
//--------------------------------------------------------------------------------------------
hidden('popup', @$_REQUEST['popup']);//What is this?
end_form();
end_page(@$_REQUEST['popup']);
?>
