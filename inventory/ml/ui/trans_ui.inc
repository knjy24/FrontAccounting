<?php

function level_list($name, $selected_id=null)
{
	$sql = "SELECT id, description FROM ".TB_PREF."levels";
	return combo_input($name, $selected_id, $sql, 'id', 'description', array());
}

function level_list_cells($label, $name, $selected_id=null)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td>";
	echo level_list($name, $selected_id);
	echo "</td>\n";
}

function level_list_row($label, $name, $selected_id=null)
{
	echo "<tr><td class='label'>$label</td>";
	level_list_cells(null, $name, $selected_id);
	echo "</tr>\n";
}

?>