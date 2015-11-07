<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL,
	as published by the Free Software Foundation, either version 3
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_CUSTSTATREP';
// ----------------------------------------------------------------
// $ Revision:	2.9 $
// Creator:	Maxime Bourget
// date_:	2013-09-06
// Title:	Customer Account Statement
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/includes/db/crm_contacts_db.inc");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");


//----------------------------------------------------------------------------------------------------

print_statements();

//----------------------------------------------------------------------------------------------------
function amountSQL() {
		return "(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
				".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)*IF(".TB_PREF."debtor_trans.type in (".ST_SALESINVOICE.", ".ST_BANKPAYMENT."), 1, -1)"; 
}

function findLatestNullDate($debtorno, $default) {
	db_query("SELECT @balance:=0");
	$sql = "SELECT MAX(date)+ INTERVAL 1 DAY  AS date FROM ( SELECT @balance := @balance+".amountSQL()." AS balance,
					GREATEST(tran_date, due_date) AS date
					FROM   ".TB_PREF."debtor_trans WHERE ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
    				AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
					ORDER BY GREATEST(tran_date, due_date)
					) b WHERE ABS(balance) < 1e-6
		";

		$result = db_query($sql);
		$row = db_fetch($result);
		return $row ? $row['date'] : $default;
	
	
}

function getInitialBalance($debtorno, $date) {
    $sql = "SELECT SUM(".amountSQL().") AS balance
				FROM ".TB_PREF."debtor_trans
				WHERE GREATEST(".TB_PREF."debtor_trans.tran_date, ".TB_PREF."debtor_trans.due_date) < '$date' AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
    				AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY;
		$result = db_query($sql);
		$row = db_fetch($result);
		return $row ? $row['balance'] : 0;



}
function getTransactions($debtorno, $from,  $date)
{
	$sql = "SELECT ".TB_PREF."debtor_trans.*,
		".amountSQL()."
		AS TotalAmount, ".TB_PREF."debtor_trans.alloc AS Allocated,
		((".TB_PREF."debtor_trans.type != ".ST_SALESINVOICE.")
		OR ".TB_PREF."debtor_trans.due_date <= '$date') AS OverDue,
		IF(due_date = '0000-00-00' , tran_date, due_date) AS EffectiveDate
		FROM ".TB_PREF."debtor_trans
		WHERE GREATEST(".TB_PREF."debtor_trans.tran_date, ".TB_PREF."debtor_trans.due_date) >= '$from' AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
		AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
		AND ABS(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
		".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount) > 1e-6";
	$sql .= " ORDER BY IF(due_date = '0000-00-00' , tran_date, due_date)";

	return db_query($sql,"No transactions were returned");
}

//----------------------------------------------------------------------------------------------------

function print_statements()
{
	global $path_to_root, $systypes_array;

	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$from = date2sql($_POST['PARAM_0']);
	$to = date2sql($_POST['PARAM_1']);
	$customer = $_POST['PARAM_2'];
	$currency = $_POST['PARAM_3'];
	$email = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$orientation = $_POST['PARAM_6'];

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$cols = array(4, 64, 180,	250, 320, 385, 450, 515);

	//$headers in doctext.inc

	$aligns = array('left',	'left',	'left',	'left',	'right', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_pref('curr_default');
	$PastDueDays1 = get_company_pref('past_due_days');
	$PastDueDays2 = 2 * $PastDueDays1;

	if ($email == 0)
		$rep = new FrontReport(_('CUSTOMER ACCOUNT STATEMENT'), "StatementBulk", user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$sql = "SELECT b.debtor_no, b.name AS DebtorName, b.address, b.tax_id, b.curr_code, curdate() AS tran_date, CONCAT (d.name, d.name2) AS contactPerson, d.phone, d.phone2  FROM ".TB_PREF."debtors_master b INNER JOIN
".TB_PREF."crm_contacts c on b.debtor_no=c.entity_id INNER JOIN ".TB_PREF."crm_persons d on c.person_id=d.id";
	if ($customer != ALL_TEXT)
		$sql .= " WHERE b.debtor_no = ".db_escape($customer);
	else
		$sql .= " ORDER by b.name";
	$result = db_query($sql, "The customers could not be retrieved");

	while ($debtor_row=db_fetch($result))
	{
		$date = date('Y-m-d');

		if($from != $to) {
			// find the latest point where the balance was null
			$start = findLatestNullDate($debtor_row['debtor_no'], $from);
			// but not earlier than the $to date.
			if(date1_greater_date2(sql2date($start), sql2date($to))) {
				$start = $to;
			}
			if(date1_greater_date2(sql2date($from), sql2date($start))) {
				$start = $from;
			}
		}
		else {
			$start = $from;
		}

		$debtor_row['order_'] = "";

		$TransResult = getTransactions($debtor_row['debtor_no'], $start,  $date);
		$baccount = get_default_bank_account($debtor_row['curr_code']);
		$params['bankaccount'] = $baccount['id'];
		if (db_num_rows($TransResult) == 0)
			continue;
		if ($email == 1)
		{
			$rep = new FrontReport("CUSTOMER ACCOUNT STATEMENT", "", user_pagesize(), 9, $orientation);
			$rep->title = _('CUSTOMER ACCOUNT STATEMENT');
			$rep->filename = "Statement" . $debtor_row['debtor_no'] . ".pdf";
			$rep->Info($params, $cols, null, $aligns);
		}

		$rep->filename = "ST-" . strtr($debtor_row['DebtorName'], " '", "__") ."--" . strtr(Today(), "/", "-") . ".pdf";
		$contacts = get_customer_contacts($debtor_row['debtor_no'], 'invoice');
		$rep->SetHeaderType(0);
		$rep->currency = $cur;
		$rep->Font();
		$rep->Info(null, $cols, null, $aligns);

		//= get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no']);
		$rep->SetCommonData($debtor_row, null, null, $baccount, ST_STATEMENT, $contacts);
		$rep->NewPage();
		$doctype = ST_STATEMENT;
/*
		$rep->NewLine();
		$rep->fontSize += 2;
		$rep->TextCol(0, 7, _("Overdue"));
		$rep->fontSize -= 2;
		$rep->NewLine(2);
 */
		$rep->NewLine(10);
		$rep->TextCol(0, 5, $debtor_row['DebtorName']);
		$rep->NewLine();
		$rep->TextCol(0,5, $debtor_row['contactPerson']);
		$rep->NewLine();
		$rep->TextCol(0,5, $debtor_row['address']);
		$rep->NewLine();
		$rep->TextCol(0,5, $debtor_row['phone'] . " ". $debtor_row['phone2']);
		$rep->NewLine(5);
		$current = false;
		$balance = getInitialBalance($debtor_row['debtor_no'], $start);
		if(true || Abs($balance) > 1e-6) {
			// Display initial balance
			//$rep->TextCol(1, 4, 'Balance Brought Forward');
				if(Abs($balance) < 1e-6) $rep->SetTextColor(190, 190, 190);
				else if($balance > 0) $rep->SetTextColor(190, 0, 0);
			//$rep->TextCol(6, 7,	number_format2(-$balance, $dec), -2);
			$rep->SetTextColor(0, 0, 0);
			//$rep->NewLine();
		}
		$overdue = 0;
		while ($transaction_row=db_fetch($TransResult))
		{
			if(!$current && !$transaction_row['OverDue']==true) {
				$rep->fontSize += 2;
				//$rep->NewLine(2);
				//$rep->TextCol(0, 7, _("Due Soon"));
				$rep->fontSize -= 2;
				$current = true;
				$overdue = $balance;
				/* Reset the balance. so we have a separate balance for overdue
				 * and current. However if the customer is in credit
				 * don't reset the balance.
				 * Example : A Customer has made a payment before the invoice
				 * is overdue. The total balance after the invoice should be 0.
				 */
				if($balance >0) {
						$balance = 0;
				}
				else {
					$overdue = 0;
				}
				$rep->NewLine(2);
			}

			if($current)
				$rep->SetTextColor(0, 0, 190);


			$DisplayTotal = number_format2(Abs($transaction_row["TotalAmount"]),$dec);
			$DisplayAlloc = number_format2($transaction_row["Allocated"],$dec);
			$DisplayNet = number_format2($transaction_row["TotalAmount"] - $transaction_row["Allocated"],$dec);

			$balance +=  $transaction_row["TotalAmount"];

			if ($systypes_array[$transaction_row['type']] == "Customer Payment")
				$invoice_no = get_custom_no($transaction_row['trans_no'], 53);
			else
				$invoice_no = get_custom_no($transaction_row['trans_no'], $transaction_row['type']);
			if ($systypes_array[$transaction_row['type']] =="Sales Invoice")
				$typename = "Charge Invoice";
			else
				$typename = $systypes_array[$transaction_row['type']];
			$rep->TextCol(1, 1, $typename, -2);
			$rep->TextCol(2, 2,	$invoice_no, -2);
			$rep->TextCol(0, 3,	sql2date($transaction_row['EffectiveDate']), -2);
			if ($transaction_row['type'] == ST_SALESINVOICE)
				$rep->TextCol(3, 4,	sql2date($transaction_row['tran_date']), -2);
			if ($transaction_row['type'] == ST_SALESINVOICE || $transaction_row['type'] == ST_BANKPAYMENT)
				$rep->TextCol(4, 5,	$DisplayTotal, -2);
			else
				$rep->TextCol(5, 6,	$DisplayTotal, -2);
			if(!$current) {
				if(Abs($balance) < 1e-6) $rep->SetTextColor(190, 190, 190);
				else if($balance > 0) $rep->SetTextColor(190, 0, 0);
			}
			$rep->TextCol(6, 7,	number_format2(-$balance, $dec), -2);
			$rep->SetTextColor(0, 0, 0);

			$rep->NewLine();
			if ($rep->row < $rep->bottomMargin + (10 * $rep->lineHeight))
				$rep->NewPage();
		}

		if(!$current)  {
				$overdue = $balance;
				$balance = 0;
		}

		// Total
		$rep->NewLine();
		$rep->SetTextColor(0, 0, 0);
		$rep->fontSize += 2;
		$rep->NewLine(18);
		$rep->TextCol(1,2,'Total Balance');
		$rep->TextCol(2,3, number_format2(-($balance+$overdue), $dec));

		if ($overdue > 1e-6) {
		 // $rep->fontSize += 2;
			$rep->NewLine(2);
			$rep->SetTextColor(190, 0, 0);
			$rep->TextCol(5,6,'Overdue');
			$rep->TextCol(6,7,number_format2($overdue, $dec));
			$rep->TextCol(2,5, 'PLEASE PAY NOW');
		//	$rep->fontSize -= 2;
			$rep->SetTextColor(0, 0, 0);
			$rep->NewLine();
		}
		$rep->fontSize -= 2;


/*
		$nowdue = "1-" . $PastDueDays1 . " " . _("Days");
		$pastdue1 = $PastDueDays1 + 1 . "-" . $PastDueDays2 . " " . _("Days");
		$pastdue2 = _("Over") . " " . $PastDueDays2 . " " . _("Days");
		$CustomerRecord = get_customer_details($debtor_row['debtor_no'], null, $show_also_allocated);
		$str = array(_("Current"), $nowdue, $pastdue1, $pastdue2, _("Total Balance"));
		$str2 = array(number_format2(($CustomerRecord["Balance"] - $CustomerRecord["Due"]),$dec),
			number_format2(($CustomerRecord["Due"]-$CustomerRecord["Overdue1"]),$dec),
			number_format2(($CustomerRecord["Overdue1"]-$CustomerRecord["Overdue2"]) ,$dec),
			number_format2($CustomerRecord["Overdue2"],$dec),
			number_format2($CustomerRecord["Balance"],$dec));
		$col = array($rep->cols[0], $rep->cols[0] + 110, $rep->cols[0] + 210, $rep->cols[0] + 310,
			$rep->cols[0] + 410, $rep->cols[0] + 510);
		$rep->row = $rep->bottomMargin + (10 * $rep->lineHeight - 6);
		for ($i = 0; $i < 5; $i++)
			$rep->TextWrap($col[$i], $rep->row, $col[$i + 1] - $col[$i], $str[$i], 'right');
		$rep->NewLine();
		for ($i = 0; $i < 5; $i++)
			$rep->TextWrap($col[$i], $rep->row, $col[$i + 1] - $col[$i], $str2[$i], 'right');
 */
		if ($email == 1)
			$rep->End($email, _("Statement") . " " . _("as of") . " " . sql2date($date));

	}
	if ($email == 0)
		$rep->End();
}

?>
