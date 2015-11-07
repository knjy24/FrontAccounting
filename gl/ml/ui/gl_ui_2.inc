<?php

$page_security = 'SA_GLANALYTIC';
$path_to_root="../..";

//include_once($path_to_root . "/includes/ml/ml_ui_lists.inc");

$type = array(
    1 => _('Supplier'),
    2 => _('Distributor'),
    3 => _('IMC'),
    4 => _('Author'),
    5 => _('Employee'),
    6 => _('Book Title')
);

$booklet_type = array(
	'Journal Voucher' => _('Journal Voucher'),
	'Check Voucher' => _('Check Voucher')
	);

$status = array(
	'1' => _('Close'),
	'0' => _('Open')
	
	);

function booklet_list($name, $selected_id=null, $submit_on_change=false, $show_inactive=false, $editkey = false){
	global $booklet_type;
	$ret = array_selector($name, $selected_id, $type, //sql fields
	array(
	    //'format' => '_format_add_curr',
	    'order' => array('emp_first_name'),//order by sql field 
		'search_box' => $mode!=0,
		'type' => 1,
		'size' => 20,
		'spec_option' => $spec_option === true ? _("All Employees") : $spec_option,
		'spec_id' => $all_items,//what is this?
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('') :
		_('Select Type'),
		'show_inactive' => $show_inactive
	) );
	return $ret;
}

function booklet_list_cells($label, $name, $selected_id=null, $submit_on_change=false, $show_inactive=false, $editkey = false){
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo booklet_list($name, $selected_id, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}


function chose_list_cells($label, $name, $selected_id=null){
	$array = array();
	if ($selected_id == "Check Voucher")
		//$array = array('Solicitations', 'Promo Ads Sales');
		gl_all_accounts_list_cells(_("Account"), 'accountId', null, true);

	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo chose_list($name, $selected_id, $array);
	echo "</td>\n";
}

function booklet_type_row_list($label, $name, $selected_id=null, $submit_on_change=false) {
	global $booklet_type;
		
	echo "<tr><td class='label'>$label</td><td>";
	$options = array(
	    'select_submit'=> $submit_on_change
	);
	echo array_selector($name, $selected_id, $booklet_type, $options );
	echo "</td></tr>\n";
}

function booklet_type_list_cells($label, $name, $selected_id=null, $submit_on_change=false, $show_inactive=false, $editkey = false){
	global $booklet_type;
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo $booklet_type($name, $selected_id, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function status_row_list($label, $name, $selected_id=null, $submit_on_change=false) {
	global $status;
		
	echo "<tr><td class='label'>$label</td><td>";
	$options = array(
	    'select_submit'=> $submit_on_change
	);
	echo array_selector($name, $selected_id, $status, $options );
	echo "</td></tr>\n";
}


function type_list($name, $selected_id=null, $submit_on_change=false, $show_inactive=false, $editkey = false){
	global $type;
	$ret = array_selector($name, $selected_id, $type, //sql fields
	array(
	    //'format' => '_format_add_curr',
	    'order' => array('emp_first_name'),//order by sql field 
		'search_box' => $mode!=0,
		'type' => 1,
		'size' => 20,
		'spec_option' => $spec_option === true ? _("All Employees") : $spec_option,
		'spec_id' => $all_items,//what is this?
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('') :
		_('Select Type'),
		'show_inactive' => $show_inactive
	) );
	return $ret;
}

function type_list_cells($label, $name, $selected_id=null, $submit_on_change=false, $show_inactive=false, $editkey = false){
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo type_list($name, $selected_id, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function account_list($name, $selected_id, $array){
	
}

function account_list_cells($label, $name, $selected_id=null){
	$array = array();
	if ($selected_id == 1)
	{
		supplier_list_cells(_("Supplier"), 'accountId', null, true);
		gl_all_accounts_list_cells(_("Account"), 'accountId_2', null, true);
	}
	else if ($selected_id == 2)
		//$array = array('Solicitations', 'Promo Ads Sales');
		gl_all_accounts_list_cells(_("Account"), 'accountId', null, true);
	else if ($selected_id == 3)
		//$array = array('OCDA', 'Solicitations', 'Promo Ads Sales');
		sales_persons_list_cells(_("Account"), 'accountId', null);
	else if ($selected_id == 4)
		//$array = array('Advances', 'Prof. Fee');
		author_list_cells(_("Name"), 'accountId', null, true);
	else if ($selected_id == 5)
		//$array = array('Cash Advance', 'Expense Advance & Medical Reimbursements');
		employee_list_cells(_("Employee"), 'EmpId', null, true);
	else if ($selected_id == 6)
		get_items_list_cells(_("Item"), 'accountId', null, true);
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo account_list($name, $selected_id, $array);
	echo "</td>\n";
}
?>