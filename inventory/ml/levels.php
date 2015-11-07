<?php 
$page_security = 'SA_INVENTORYLOCATION';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Book Levels"));

include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/inventory/ml/db/ml_items_trans.inc");

simple_page_mode(true);

if ($Mode=='ADD_ITEM') 
{
	$checkIfExists = check_item_level($_POST['description']);
	$input_error = 0;

	if (strlen($_POST['description']) == 0) 
	{
		$input_error = 1;
		display_error(_("The description cannot be empty."));
		set_focus('description');
	}

	if ($checkIfExists > 0)
	{
		$input_error = 1;
		display_error(_("The description you have entered already exists in the database."));
		//set_focus('description');
	}

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		update_item_level($selected_id, $_POST['description']);
			$note = _('Selected book level has been updated');
    	} 
    	else 
    	{
    		add_item_level($_POST['description']);
			$note = _('New book level has been added');
    	}
    
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

if ($Mode=='UPDATE_ITEM'){
	$input_error = 0;

	if (strlen($_POST['description']) == 0) 
	{
		$input_error = 1;
		display_error(_("The description cannot be empty."));
		set_focus('description');
	}

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		update_item_level($selected_id, $_POST['description']);
			$note = _('Selected book level has been updated');
    	} 
    	else 
    	{
    		add_item_level($_POST['description']);
			$note = _('New book level has been added');
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
		delete_item_level($selected_id);
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


$result = get_item_level(check_value('show_inactive'));

start_form();
start_table(TABLESTYLE, "width=30%");

$th = array(_("No"), _("Description"), "", "");
inactive_control_column($th);

table_header($th);
$k = 0; 

while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);
	label_cell($myrow["id"]);	
	label_cell($myrow["description"]);
	inactive_control_cell($myrow["id"], $myrow["inactive"], 'levels', 'id');

 	edit_button_cell("Edit".$myrow["id"], _("Edit"));
 	delete_button_cell("Delete".$myrow["id"], _("Delete"));
	end_row();
}
	
inactive_control_row($th);
end_table();
echo '<br>';

//-------------------------------------------------------------------------------------------------

start_table(TABLESTYLE2);

if ($selected_id != -1) 
{
 	if ($Mode == 'Edit') {
		//editing an existing area
		$myrow = get_selected_item_level($selected_id);

		$_POST['description']  = $myrow["description"];
	}
	hidden("selected_id", $selected_id);
	label_row(_("ID"), $myrow["id"]);
} 

text_row_ex(_("Description:"), 'description', 30); 

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>