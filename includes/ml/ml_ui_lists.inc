<?php

function all_items_list($name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $opts=array(), $editkey = false)
{
	global $all_items;

	$sql = "SELECT stock_id, s.description, c.description, s.inactive, s.editable
			FROM ".TB_PREF."stock_master s,".TB_PREF."stock_category c WHERE s.category_id=c.category_id";

	if ($editkey)
		set_editor('item', $name, $editkey);

	$ret = combo_input($name, $selected_id, $sql, 'stock_id', 's.description',
	array_merge(
	  array(
		'format' => '_format_stock_items',
		'spec_option' => $all_option===true ?  _("All Items") : $all_option,
		'spec_id' => $all_items,
		'search_box' => true,
		'search' => array("stock_id", "c.description","s.description"),
		'search_submit' => get_company_pref('no_item_list')!=0,
		'size'=>10,
		'select_submit'=> $submit_on_change,
		'category' => 2,
		'order' => array('c.description','stock_id')
	  ), $opts) );
	if ($editkey)
		$ret .= add_edit_combo('item');		
	return $ret;
	  
}

function get_items_list($name, $selected_id=null,
	$all_option=false, $submit_on_change=false)
{
	return all_items_list($name, $selected_id, $all_option, $submit_on_change,
		array('where'=>array("mb_flag!='D'")));
}

function get_items_list_cells($label, $name, $selected_id=null, 
	$all_option=false, $submit_on_change=false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo all_items_list($name, $selected_id, $all_option, $submit_on_change,
		array('where'=>array("mb_flag!='D'"), 'cells'=>true));
}

function employee_list($name, $selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;

	$sql = "SELECT emp_id, CONCAT_WS(' ', emp_first_name, emp_last_name), inactive FROM ".TB_PREF."employees ";

	$mode = 0;//We don't have many employees, hardcoding to no search

	if ($editkey)
		set_editor('employee', $name, $editkey);//what is this? //set_editor defined in ui_controls.inc

	$ret = combo_input($name, $selected_id, $sql, 'emp_id', 'emp_first_name', //sql fields
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
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment; F2 - entry new employee') :
		_('Select employee'),
		'show_inactive' => $show_inactive
	) );
	if ($editkey)
		$ret .= add_edit_combo('employee');
	return $ret;
}

//--------------------------------------------------------------------------------------------
function employee_list_cells($label, $name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo employee_list($name, $selected_id, $all_option, $submit_on_change);
	echo "</td>\n";
}

function author_list($name, $selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;

	$sql = "SELECT id, CONCAT_WS(' ', author_fname, author_lname) FROM ".TB_PREF."authors ";

	$mode = 0;//We don't have many employees, hardcoding to no search

	if ($editkey)
		set_editor('author', $name, $editkey);//what is this? //set_editor defined in ui_controls.inc

	$ret = combo_input($name, $selected_id, $sql, 'id', 'author_fname', //sql fields
	array(
	    //'format' => '_format_add_curr',
	    'order' => array('author_fname'),//order by sql field 
		'search_box' => $mode!=0,
		'type' => 1,
		'size' => 20,
		'spec_option' => $spec_option === true ? _("All Authors") : $spec_option,
		'spec_id' => $all_items,//what is this?
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment; F2 - enter new author') :
		_('Select author'),
		'show_inactive' => $show_inactive
	) );
	if ($editkey)
		$ret .= add_edit_combo('author');
	return $ret;
}

//--------------------------------------------------------------------------------------------
function author_list_cells($label, $name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo author_list($name, $selected_id, $all_option, $submit_on_change);
	echo "</td>\n";
}

?>