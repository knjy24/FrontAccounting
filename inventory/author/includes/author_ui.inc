<?php
	function authors_list_row($label, $name, $selected_id=null, $submit_on_change=false) {	
	
	echo "<tr><td class='label'>$label</td><td>";
	$options = array();
	echo array_selector($name, $selected_id, getAuthor(), $options );
	echo "</td></tr>\n";
	}

	function getAuthor(){
		$sql = "SELECT id, CONCAT(author_fname, ' ', author_lname) as names FROM ".TB_PREF."authors";
		$result = db_query($sql, "could not get authors");
		$author_array = array();
		while($myrow = db_fetch($result)){
		$author_array[$myrow['id']] = $myrow['names'];
		}
		return $author_array;
	}

	function windowprintRoyalty($name, $id = NULL){
		echo "</br>";
		if ($id == NULL)
		echo "<center><input type='button' id ='w' value=".db_escape($name)." onclick='window.print()' /></center>";
		else
		echo "<center><a href = 'print_royalty.php?id=".$id."'>View</a></center>";
	}

	function viewprinterRoyalty($id){
		echo "<td><center><a href = 'print_royalties.php?id=".$id."'>View</a></center></td>";
	}

	function viewprinterBook($id){
		echo "<td><center><a href = 'print_books.php?id=".$id."'>View</a></center></td>";
	}

	function fetchBook($id){
		$sql = "SELECT id FROM ".TB_PREF."item_codes WHERE id = ".db_escape($id)." ";
		return db_query($sql, 'Error');
	}
?>