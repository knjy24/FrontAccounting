<?php
$page_security = 'SA_PAYROLL';
$path_to_root = "../..";

include_once($path_to_root . "/includes/session.inc");
add_access_extensions();


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
	
page(_($help_context = "Manage Authors"));

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/inventory/author/includes/author_db.inc");

simple_page_mode(true);
function can_process() {
    if (strlen($_POST['fname']) == 0) {
	display_error(_("First name cannot be empty."));
	set_focus('code');
	return false;
    }
    if (strlen($_POST['lname']) == 0) {
	display_error(_("Last name cannot be empty."));
	set_focus('code');
	return false;
    }
    if (strlen($_POST['address']) == 0) {
	display_error(_("Address cannot be empty."));
	set_focus('code');
	return false;
    }
    if (strlen($_POST['contact_number']) == 0) {
	display_error(_("Contact number cannot be empty."));
	set_focus('code');
	return false;
    }
    if (strlen($_POST['email_address']) == 0) {
	display_error(_("Email address cannot be empty."));
	set_focus('code');
	return false;
    }
    return true;
}

if ($Mode=='ADD_ITEM' && can_process()) {
	submitAuthor($_POST['fname'], $_POST['mname'], $_POST['lname'], $_POST['bdate'], $_POST['address'], $_POST['contact_number'], $_POST['email_address']);
	display_notification(_('New author has been added'));
	$Mode = 'RESET';
}

if ($Mode=='UPDATE_ITEM' && can_process()) {
	submitAuthor($_POST['fname'], $_POST['mname'], $_POST['lname'], $_POST['bdate'], $_POST['address'], $_POST['contact_number'], $_POST['email_address'], $_POST['selected_id']);
	display_notification(_('Selected author has been updated'));
	$Mode = 'RESET';
}

if ($Mode == 'Delete') {
    deleteAuthor($selected_id);
    display_notification(_('Selected author has been deleted'));
    $Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	unset($_POST);
}

$result = fetchAuthors();

start_form();
start_table(TABLESTYLE, "width=70%");

$th = array (_('Name'), _('Address'), _('Contact Number'), _('Email Address'), '','');
table_header($th);

$k=0;
while ($myrow = db_fetch($result)) {
    alt_table_row_color($k);
    label_cell($myrow[1]);
    label_cell($myrow[2]);
    label_cell($myrow[3]);
    label_cell($myrow[4]);
    
    edit_button_cell("Edit".$myrow[0], _("Edit"));
    delete_button_cell("Delete".$myrow[0], _("Delete"));
    end_row();
}
end_table(1);

start_table(TABLESTYLE2);

if ($selected_id != -1) {
    if ($Mode == 'Edit') {
	$result = fetchAuthors($selected_id);
	while ($myrow = db_fetch($result)){
		$_POST['fname']  = $myrow['author_fname'];
		$_POST['mname']  = $myrow['author_mname'];
		$_POST['lname']  = $myrow['author_lname'];
		$_POST['bdate']  = sql2date($myrow["author_birthdate"]);
		$_POST['address']  = $myrow['author_address'];
		$_POST['contact_number']  = $myrow['author_contact_number'];
		$_POST['email_address']  = $myrow['author_email'];
		$_POST['selected_id']  = $myrow['id'];
		}
	}

	hidden('selected_id', $selected_id);
}


text_row_ex(_("First Name:"), 'fname', 25);
text_row_ex(_("Middle Name:"), 'mname', 25);
text_row_ex(_("Last Name:"), 'lname', 25);
date_row(_("Birth Date:"), 'bdate');
text_row_ex(_("Address:"), 'address', 25);
text_row_ex(_("Contact Number:"), 'contact_number', 25);
text_row_ex(_("Email Address:"), 'email_address', 25);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();

?>
