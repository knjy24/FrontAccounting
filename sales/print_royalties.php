<?php
$page_security = 'SA_PAYROLL';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");
add_access_extensions();

page(_($help_context = "Royalty Summary"));

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/inventory/author/includes/author_ui.inc");
include_once($path_to_root . "/inventory/author/includes/author_db.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

simple_page_mode(true);
$id = NULL;
if (isset($_GET['id']))
	$id = $_GET['id'];
$total = 0;
start_form();
start_table(TABLESTYLE, "width=50%");
$result = fetchRoyaltyReport($id);

$name = "";

while ($author = db_fetch($result)){
	$name = $author[1];
}

echo "<b>".$name."</b>";
br(); 
br();

$th = array (_('Book Title'), _('Percentage'), _('Total Sales'), _(''));
table_header($th);

$result = fetchRoyaltyReport($id);
while ($myrow = db_fetch($result)) {
    start_row();
    label_cell($myrow[2]);
    label_cell($myrow[3]);
    label_cell($myrow[5]);
    echo "<td>";
	submenu_print(_("&Print This"), ST_ROYALTY, $myrow[6], null, 1);
	echo "</td>";
    $total += $myrow[5];
    end_row();
}

end_table();
br();

end_form();

end_page();

?>
