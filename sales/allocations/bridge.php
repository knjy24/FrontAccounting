
<?php
$path_to_root = "../..";
$page_security = 'SA_SALESALLOC';

include_once($path_to_root . "/includes/session.inc");
add_access_extensions();

page(_($help_context = "Allocate Customer Payment or Credit Note"));

include_once($path_to_root . "/sales/includes/cart_class.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/sales/includes/ui/sales_order_ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/includes/db/sales_types_db.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
include($path_to_root . "/includes/ui/allocation_cart.inc");
simple_page_mode(true);
//----------------------------------------------------------------------------------------------------
$id = $_GET['id'];
$type =  $_GET['type'];
$no = $_GET['no'];


function checker($id, $type){
	$sql = "SELECT b.debtor_no, b.name, DATE_FORMAT(NOW(), '%m/%d/%y'), b.address, a.alloc, a.ov_amount FROM ".TB_PREF."debtors_master as b INNER JOIN ".TB_PREF."debtor_trans as a ON b.debtor_no = a.debtor_no WHERE a.trans_no = ".db_escape($id)." and a.type = ".db_escape($type)." ";
    return db_query($sql, 'Error');
}

function init($id, $type, $no){
	$amount = NULL;
	$alloc = NULL;

	$result = checker($id, $type);
	while ($myrow = db_fetch($result)){
		$amount = $myrow[5];
		$alloc = $myrow[4];
	}
	
	$sql = "INSERT INTO ".TB_PREF."cust_alloc_details(details_id, no, alloc, total_amount, trans_date) VALUES(".db_escape($id).", ".db_escape($no).", ".db_escape($alloc).", ".db_escape($amount).", NOW())";
	return db_query($sql, 'Error');
}

start_form();
if (init($id, $type, $no)){
start_table(TABLESTYLE2);

br();br();
display_notification(_('Allocation Successful'));

submenu_print(_("&Print Collection Receipt"), ST_ALLOC, $_GET['id'].'/'.$_GET['type'].'/'.$_GET['no'].'/collection', null, 1);

end_table(1);
}

end_form();

end_page();

?>