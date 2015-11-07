<?php
function checkIfInvoiceExists($invoice_num)
{
	
	$sql = "SELECT a.customized_no from ".TB_PREF."debtor_trans trans INNER JOIN ".TB_PREF."customized a
			on trans.type=a.type AND trans.trans_no=a.type_no where a.customized_no = ".db_escape($invoice_num)."";
	$result = db_query($sql, "could not get transaction");
	$row = db_fetch($result);
	if ($db_fetch_row($row) > 0)
		return true;
	else
		return false;
}

?>