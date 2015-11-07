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
	
	$checkIfExists = check_booklet_exist($_POST['type'], $_POST['year'], $_POST['begin'], $_POST['end']);
	$checkOpen = check_series_open($_POST['type'], $_POST['year'], $_POST['status']);
	$input_error = 0;


	if ($checkIfExists > 0)
	{
		$input_error = 1;
		display_error(_("The series you have entered already exists."));
		//set_focus('no');
	}

	if ($checkOpen > 1)
	{
		$input_error = 1;
		display_error(_("Only one booklet type should be open for selected year."));
		set_focus('status');
	}

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		update_booklet($selected_id, $_POST['type'], $_POST['year'], $beg, $end, $_POST['status']);
			$note = _('Selected booklet has been updated');
    	} 
    	else 
    	{
    		add_booklet($_POST['type'], $_POST['year'], $_POST['begin'], $_POST['end'], $_POST['status']);
			$note = _('New booklet has been added');	
    	}
    
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

 if ($Mode=='UPDATE_ITEM'){

	$input_error = 0;

	$sub1 = substr($_POST['year'], 2);
    $beg = $sub1."-".$_POST['begin'];
    $end = $sub1."-".$_POST['end'];

	$input_error = 0;


	if ($checkIfExists > 0)
	{
		$input_error = 1;
		display_error(_("The series you have entered already exists."));
		//set_focus('no');
	}


	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{

    		update_booklet($selected_id, $_POST['type'], $_POST['year'], $_POST['begin'], $_POST['end'], $_POST['status']);
			$note = _('Selected booklet has been updated');
    	} 
    	else 
    	{
    		add_booklet($_POST['type'], $_POST['year'], $_POST['begin'], $_POST['end'], $_POST['status']);
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
start_table(TABLESTYLE, "width=60%");

$th = array (_('ID'), _('Booklet Type'), _('Begin'), _('End'), _('Year'), _('Status'), '','');
inactive_control_column($th);

table_header($th);

$k=0;
while ($myrow = db_fetch($result)) {
	$formatted = substr($myrow['year'], 2)."-";
    alt_table_row_color($k);
    label_cell($myrow['id']);
    //label_cell($myrow['no']);
    label_cell($myrow['type']);
    label_cell($formatted.(str_pad($myrow['begin'], 4,0, STR_PAD_LEFT)));
    label_cell($formatted.(str_pad($myrow['end'], 4,0, STR_PAD_LEFT)));
    label_cell($myrow['year']);
    label_cell($myrow['status1']."/".$myrow['status']);
    inactive_control_cell($myrow["id"], $myrow["inactive"], 'booklet', 'id');
	
    edit_button_cell("Edit".$myrow['id'], _("Edit"));
    delete_button_cell("Delete".$myrow['id'], _("Delete"));
    end_row();
}

inactive_control_row($th);
end_table();

br();
start_table(TABLESTYLE2);

if ($selected_id != -1) {
    if ($Mode == 'Edit') {
	$myrow = get_selected_booklet($selected_id);
		//$_POST['no']  = $myrow['no'];
		$_POST['type']  = $myrow['type'];
		$_POST['begin']  = $myrow['begin'];
		$_POST['end']  = $myrow['end'];
		$_POST['year']  = $myrow['year'];
		$_POST['status'] = $myrow['stat'];
	}
	hidden('selected_id', $selected_id);
	label_row(_("ID"), $myrow["id"]);
}
//text_row_ex(_("No:"), 'no', 25);
booklet_type_row_list(_("Type:"), 'type', $_POST['type']);
text_row_ex(_("Begin:"), 'begin', 25);
text_row_ex(_("End:"), 'end', 25);
text_row_ex(_("Year:"), 'year', 30);
status_row_list(_("Status:"), 'status', $_POST['status']);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>