<?php
$page_security = 'SA_ITEM';
$path_to_root = "..";

include_once($path_to_root . "/includes/session.inc");
add_access_extensions();

page(_($help_context = "Author List"));

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/inventory/author/includes/author_ui.inc");
include_once($path_to_root . "/inventory/author/includes/author_db.inc");

simple_page_mode(true);
//----------------------------------------------------------------------------------------------------

start_form();
start_table(TABLESTYLE, "width=30%");

$th = array (_('Author'),  '');
table_header($th);
$result = fetchRoyaltyReport();
while ($myrow = db_fetch($result)) {
    start_row();
    label_cell($myrow[1]);
    viewprinterRoyalty($myrow[0]);
    end_row();
}


end_table();
end_form();

end_page();

?>
