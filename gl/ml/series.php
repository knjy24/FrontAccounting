<?php 
$page_security = 'SA_GLACCOUNT';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Booklet"));

include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");
include_once($path_to_root . "/gl/ml/ui/gl_ui.inc");

simple_page_mode(true);

if ($Mode=='ADD_ITEM') 
{
	$checkIfExists = check_booklet($_POST['no'], $_POST['type'], $_POST['year']);
	$input_error = 0;

	if (strlen($_POST['no']) == 0) 
	{
		$input_error = 1;
		display_error(_("The booklet no. cannot be empty."));
		set_focus('no');
	}

	if ($checkIfExists > 0)
	{
		$input_error = 1;
		display_error(_("The booklet you have entered already exists."));
		set_focus('no');
	}

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		update_booklet($selected_id, $_POST['no'], $_POST['type'], $_POST['year']);
			$note = _('Selected booklet has been updated');
    	} 
    	else 
    	{
    		add_booklet($_POST['no'], $_POST['type'], $_POST['year']);
			$note = _('New booklet has been added');
    	}
    
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

 if ($Mode=='UPDATE_ITEM'){

	$input_error = 0;

	if (strlen($_POST['no']) == 0) 
	{
		$input_error = 1;
		display_error(_("The booklet no. cannot be empty."));
		set_focus('no');
	}


	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		update_booklet($selected_id, $_POST['no'], $_POST['type'], $_POST['year']);
			$note = _('Selected booklet has been updated');
    	} 
    	else 
    	{
    		add_booklet($_POST['no'], $_POST['type'], $_POST['year']);
			$note = _('New booklet has been added');
    	}
    
		display_notification($note);    	
		$Mode = 'RESET';
	}
 }

if ($Mode == 'Delete')
{

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN ''

	/*if (key_in_foreign_table($selected_id, 'cust_branch', 'group_no'))
	{
		$cancel_delete = 1;
		display_error(_("Cannot delete this group because customers have been created using this group."));
	} */
	if ($cancel_delete == 0) 
	{
		delete_booklet($selected_id);
		display_notification(_('Selected book level has been deleted'));
	} //end if Delete group
	$Mode = 'RESET';
} 

if ($Mode == 'RESET')
{
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	if ($sav) $_POST['show_inactive'] = 1;
}


$result = get_booklet(check_value('show_inactive'));

start_form();
start_table(TABLESTYLE, "width=50%");

$th = array (_('ID'), _('No'), _('Booklet Type'), _('Year'),'','');
inactive_control_column($th);

table_header($th);

$k=0;
while ($myrow = db_fetch($result)) {
    alt_table_row_color($k);
    label_cell($myrow['id']);
    label_cell($myrow['no']);
    label_cell($myrow['type']);
    label_cell($myrow['year']);
    inactive_control_cell($myrow["id"], $myrow["inactive"], 'booklet', 'id');
	
    edit_button_cell("Edit".$myrow['id'], _("Edit"));
    delete_button_cell("Delete".$myrow['id'], _("Delete"));
    end_row();
}

inactive_control_row($th);
end_table();


start_table(TABLESTYLE2);

if ($selected_id != -1) {
    if ($Mode == 'Edit') {
	$myrow = get_selected_booklet($selected_id);
		$_POST['no']  = $myrow['no'];
		$_POST['type']  = $myrow['type'];
		$_POST['year']  = $myrow['year'];
	}
	hidden('selected_id', $selected_id);
	label_row(_("ID"), $myrow["id"]);
}
text_row_ex(_("No:"), 'no', 25);
booklet_type_row_list(_("Type:"), 'type', $_POST['type']);
text_row_ex(_("Year:"), 'year', 30);



end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>