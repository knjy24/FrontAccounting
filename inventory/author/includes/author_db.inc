<?php
	function fetchAuthors($id = NULL){
		if ($id)
			$sql = "SELECT * FROM ".TB_PREF."authors WHERE id = ".db_escape($id)."";
		else if (!$id)
			$sql = "SELECT id, CONCAT(author_fname, ' ', author_lname) as names, author_address, author_contact_number, author_email from ".TB_PREF."authors";

		return db_query($sql, 'Error');
	}

	function submitAuthor($fname, $mname = NULL, $lname, $bdate, $address, $contact_number, $email_address, $id = NULL){
		if ($id)
			$sql = "UPDATE ".TB_PREF."authors SET author_fname = ".db_escape($fname).", author_mname = ".db_escape($mname).", author_lname = ".db_escape($lname).", author_birthdate = '".date2sql($bdate)."', author_address = ".db_escape($address).", author_contact_number = ".db_escape($contact_number).", author_email = ".db_escape($email_address)." WHERE id = ".db_escape($id)." ";	
		else
		$sql = "INSERT INTO ".TB_PREF."authors(author_fname, author_mname, author_lname, author_birthdate, author_address, author_contact_number, author_email) VALUES (".db_escape($fname).", ".db_escape($mname).", ".db_escape($lname).", '".date2sql($bdate)."', ".db_escape($address).", ".db_escape($contact_number).", ".db_escape($email_address).") ";

		return db_query($sql, 'Error');
	}

	function deleteAuthor($id){
		$sql = "DELETE FROM ".TB_PREF."authors WHERE id = ".db_escape($id)."";
		return db_query($sql, 'Error');
	}

	//Royalty Part
	function fetchRoyalty($item, $id = NULL){
		if ($id)
			$sql = "SELECT a.id, b.id, a.percentage FROM ".TB_PREF."royalty_authors as a INNER JOIN ".TB_PREF."authors as b ON a.author_id = b.id WHERE a.item_id = ".db_escape($item)." AND a.id = ".db_escape($id)." ";
		else
			$sql = "SELECT a.id, CONCAT (b.author_fname, ' ', b.author_lname), a.percentage FROM ".TB_PREF."royalty_authors as a INNER JOIN ".TB_PREF."authors as b ON a.author_id = b.id WHERE a.item_id = ".db_escape($item)."";
		return db_query($sql, 'Error');
	}

	function submitRoyalty($item, $author_id, $percentage, $id = NULL){
		if ($id)
			$sql = "UPDATE ".TB_PREF."royalty_authors SET author_id = ".db_escape($author_id).", percentage = ".db_escape($percentage)." WHERE id = ".db_escape($id)."";
		else 
			$sql = "INSERT INTO ".TB_PREF."royalty_authors(item_id, author_id, percentage) VALUES(".db_escape($item).", ".db_escape($author_id).", ".db_escape($percentage).")";

		return db_query($sql, 'Error');
	}

	function deleteRoyalty($id){
		$sql = "DELETE FROM ".TB_PREF."royalty_authors WHERE id = ".db_escape($id)."";	
		return db_query($sql, 'Error');
	}

	function fetchRoyaltyReport($id = NULL){
		if ($id)
			$sql = "SELECT a.id, CONCAT(d.author_fname, ' ', d.author_lname) as names, c.description, a.percentage, a.id, a.item_id FROM ".TB_PREF."royalty_authors as a INNER JOIN ".TB_PREF."item_codes as c ON a.item_id = c.item_code INNER JOIN ".TB_PREF."authors as d ON a.author_id = d.id WHERE a.author_id = ".db_escape($id);
		else
			$sql = "SELECT id, CONCAT(author_fname, ' ', author_lname) as names FROM ".TB_PREF."authors";
		
		return db_query($sql, 'Error');

	}
?>