<?php


function imc_list($name, $selected_id=null, $new_item=false, $submit_on_change=false, $show_inactive = false)
{
	$sql = "SELECT salesman_code, salesman_name, inactive FROM ".TB_PREF."salesman";
	
		$options = array(
		'spec_option'=>$new_item ? _("Select IMC") : false,
		'spec_id' => '',
		'select_submit'=> $submit_on_change,
		'show_inactive' => $show_inactive
		);

	return combo_input($name, $selected_id, $sql, 'salesman_code', 'salesman_name', $options);
}

function imc_list_cells($label, $name, $selected_id=null, $new_item=false, $submit_on_change=false, $show_inactive = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td>\n";
	echo imc_list($name, $selected_id, $new_item=false, $submit_on_change, $show_inactive);
	echo "</td>\n";
}

function imc_list_row($label, $name, $selected_id=null, $submit_on_change=false) {
		
	echo "<tr><td class='label'>$label</td><td>";
	
	$options = array(
	    'select_submit'=> $submit_on_change
	);
	
	echo array_selector($name, $selected_id, get_imc(), $options );
	echo "</td></tr>\n";
}

?>