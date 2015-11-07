<?php
$page_security = 'SA_ITEMSSTATVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/includes/session.inc");

if (!@$_GET['popup'])
{
	if (isset($_GET['stock_id'])){
		page(_($help_context = "Authors & Payments"), true);
	} else {
		page(_($help_context = "Authors & Payments"));
	}
}
if (isset($_GET['stock_id']))
	$_POST['stock_id'] = $_GET['stock_id'];

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
if (!@$_GET['popup'])
	page(_($help_context = "Authors & Payments"));
	
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/includes/db/sales_types_db.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/author/includes/author_db.inc");
include_once($path_to_root . "/inventory/author/includes/author_ui.inc");

simple_page_mode(true);

$input_error = 0;

if (isset($_GET['stock_id']))
{
	$_POST['stock_id'] = $_GET['stock_id'];
}
if (isset($_GET['Item']))
{
	$_POST['stock_id'] = $_GET['Item'];
}

if (!isset($_POST['curr_abrev']))
{
	$_POST['curr_abrev'] = get_company_currency();
}

//---------------------------------------------------------------------------------------------------
if (!@$_GET['popup'])
	start_form();

if (!isset($_POST['stock_id']))
	$_POST['stock_id'] = get_global_stock_item();

if (!@$_GET['popup'])
{
	echo "<center>" . _("Item:"). "&nbsp;";
	echo sales_items_list('stock_id', $_POST['stock_id'], false, true, '', array('editable' => false));
	echo "<hr></center>";
}
else
	br(2);
set_global_stock_item($_POST['stock_id']);

//----------------------------------------------------------------------------------------------------
function can_process() {
    if (strlen($_POST['royalty_percentage']) == 0) {
	display_error(_("Percentage cannot be empty."));
	set_focus('royalty_percentage');
	return false;
    }

    return true;
}

//----------------------------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' && can_process()) {
	if (submitRoyalty($stock_id, $_POST['author_id'], $_POST['royalty_percentage'])){
	display_notification(_('New author and royalty for the book has been added '));
	$Mode = 'RESET';}
}

//----------------------------------------------------------------------------------------------------

if ($Mode=='UPDATE_ITEM' && can_process()) {
	submitRoyalty($stock_id, $_POST['author_id'], $_POST['royalty_percentage'], $selected_id);
	display_notification(_('Selected author and royalty for the book has been updated'));
	$Mode = 'RESET';
}

//----------------------------------------------------------------------------------------------------

if ($Mode == 'Delete') {
    deleteRoyalty($selected_id);
    display_notification(_('Selected author and royalty for the book has been deleted'));
    $Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	unset($_POST);
}
//----------------------------------------------------------------------------------------------------

$result = fetchRoyalty($stock_id);

start_form();
start_table(TABLESTYLE, "width=40%");

$th = array (_('Author'), _('Royalty Percentage'), '','');
table_header($th);

$k=0;
while ($myrow = db_fetch($result)) {
    alt_table_row_color($k);
    label_cell($myrow[1]);
    label_cell($myrow[2] . ' %');
    
    edit_button_cell("Edit".$myrow[0], _("Edit"));
    delete_button_cell("Delete".$myrow[0], _("Delete"));
    end_row();
}
end_table(1);

start_table(TABLESTYLE2);
if ($selected_id != -1) {
    if ($Mode == 'Edit') {
	$result = fetchRoyalty($stock_id, $selected_id);
	while ($myrow = db_fetch($result)){
		$_POST['author_id']  = $myrow[1];
		$_POST['royalty_percentage'] = $myrow[2];
		$_POST['selected_id']  = $myrow[0];
		}
	}
	hidden('selected_id', $selected_id);
}

authors_list_row(_("Authors:"), 'author_id', $_POST['author_id']);
text_row_ex(_("Royalty Percentage:"), 'royalty_percentage', 10);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

?>
