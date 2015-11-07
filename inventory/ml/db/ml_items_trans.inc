<?php

function add_item_level($description)
{
	$sql = "INSERT INTO ".TB_PREF."levels (description) VALUES (".db_escape($description) . ")";
   	db_query($sql,"The book level could not be added");
}

function check_item_level($description){
	$sql = "SELECT COUNT(id) FROM ".TB_PREF."levels where description LIKE ".db_escape($description)."";
	//db_query($sql);

	$result = db_query($sql, "could not get book levels");

	$row = db_fetch_row($result);
	return $row[0];

}

function get_item_level($show_inactive)
{
	$sql = "SELECT * FROM ".TB_PREF."levels";
	if (!$show_inactive) $sql .= " WHERE !inactive";
	return db_query($sql,"could not get book levels");
}

function get_selected_item_level($selected_id)
{
	$sql = "SELECT * FROM ".TB_PREF."levels WHERE id=".db_escape($selected_id)."";

	$result = db_query($sql,"could not get book levels");
	return db_fetch($result);
}

function update_item_level($selected_id, $description)
{
	$sql = "UPDATE ".TB_PREF."levels SET description=".db_escape($description)." WHERE id = ".db_escape($selected_id);
   	db_query($sql,"The selected book level could not be updated");
}

function delete_item_level($selected_id)
{
	$sql="DELETE FROM ".TB_PREF."levels WHERE id=".db_escape($selected_id)."";
	db_query($sql,"could not delete book level");
}

/*================================================================================*/


function add_item_subject($description)
{
	$sql = "INSERT INTO ".TB_PREF."subjects (description) VALUES (".db_escape($description) . ")";
   	db_query($sql,"The book subject could not be added");
}

function check_item_subject($description){
	$sql = "SELECT COUNT(id) FROM ".TB_PREF."subjects where description LIKE ".db_escape($description)."";
	//db_query($sql);

	$result = db_query($sql, "could not get book subject");

	$row = db_fetch_row($result);
	return $row[0];

}

function get_item_subject($show_inactive)
{
	$sql = "SELECT * FROM ".TB_PREF."subjects";
	if (!$show_inactive) $sql .= " WHERE !inactive";
	return db_query($sql,"could not get book subjects");
}

function get_selected_item_subject($selected_id)
{
	$sql = "SELECT * FROM ".TB_PREF."subjects WHERE id=".db_escape($selected_id);

	$result = db_query($sql,"could not get book subject");
	return db_fetch($result);
}

function update_item_subject($selected_id, $description)
{
	$sql = "UPDATE ".TB_PREF."subjects SET description=".db_escape($description)." WHERE id = ".db_escape($selected_id);
   	db_query($sql,"The selected book subject could not be updated");
}

function delete_item_subject($selected_id)
{
	$sql="DELETE FROM ".TB_PREF."subjects WHERE id=".db_escape($selected_id);
	db_query($sql,"could not delete book subject");
}


?>