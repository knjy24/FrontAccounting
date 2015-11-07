<?php

$page_security = 'SA_CUSTSTATREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Print Statements
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/includes/db/crm_contacts_db.inc");

//----------------------------------------------------------------------------------------------------

print_statements();

//----------------------------------------------------------------------------------------------------

function getTransactions($debtorno, $date, $show_also_allocated)
{
    $sql = "SELECT ".TB_PREF."debtor_trans.*, ".TB_PREF."customized.customized_no,
				(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
				".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)
				AS TotalAmount, ".TB_PREF."debtor_trans.alloc AS Allocated,
				((".TB_PREF."debtor_trans.type = ".ST_SALESINVOICE.")
				AND ".TB_PREF."debtor_trans.due_date < '$date') AS OverDue
				FROM ".TB_PREF."debtor_trans INNER JOIN ".TB_PREF."customized on ".TB_PREF."debtor_trans.type=".TB_PREF."customized.type AND ".TB_PREF."debtor_trans.trans_no=".TB_PREF."customized.type_no
				INNER JOIN ".TB_PREF."cust_branch on ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."cust_branch.debtor_no
				WHERE ".TB_PREF."debtor_trans.tran_date <= '$date' AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
    				AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
					AND ABS(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
				".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount) > 1e-6";
	if (!$show_also_allocated)
		$sql .= " AND ABS(ABS(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
				".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount) - alloc) > 1e-6";
	$sql .= " ORDER BY ".TB_PREF."debtor_trans.tran_date";

    return db_query($sql,"No transactions were returned");
}

//----------------------------------------------------------------------------------------------------

function print_statements()
{
	global $path_to_root, $systypes_array;

	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$customer = $_POST['PARAM_0'];
	$orientation = $_POST['PARAM_1'];

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$cols = array(4, 80, 150, 250, 320, 385, 450, 515);

	//$headers in doctext.inc

	$aligns = array('left',	'left',	'left',	'left',	'right', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_pref('curr_default');
	$PastDueDays1 = get_company_pref('past_due_days');
	$PastDueDays2 = 2 * $PastDueDays1;

	if ($email == 0)
		$rep = new FrontReport(_('STATEMENT'), "StatementBulk", user_pagesize(), 9, $orientation);
   if ($orientation == 'L')
    	recalculate_cols($cols);
 
	$sql = "SELECT ".TB_PREF."debtors_master.debtor_no, name AS DebtorName, address, tax_id, curr_code, curdate() AS tran_date, cust.salesman 
	FROM ".TB_PREF."debtors_master INNER JOIN ".TB_PREF."cust_branch cust on ".TB_PREF."debtors_master.debtor_no=cust.debtor_no";
	if ($customer != ALL_TEXT)
		$sql .= " WHERE b.debtor_no = ".db_escape($customer);
	else
		$sql .= " ORDER by name";
	$result = db_query($sql, "The customers could not be retrieved");
	$arr = array();
	while ($myrow=db_fetch($result))
	{
		
			$date = date('Y-m-d');

		$myrow['order_'] = "";

		$TransResult = getTransactions($myrow['debtor_no'], $date, $show_also_allocated);
		$baccount = get_default_bank_account($myrow['curr_code']);
		$params['bankaccount'] = $baccount['id'];
		if (db_num_rows($TransResult) == 0)
			continue;
		if ($email == 1)
		{
			$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
			$rep->title = _('STATEMENT');
			$rep->filename = "Statement" . $myrow['debtor_no'] . ".pdf";
			$rep->Info($params, $cols, null, $aligns);
		}

		$contacts = get_customer_contacts($myrow['debtor_no'], 'invoice');
		$rep->SetHeaderType('0');
		$rep->currency = $cur;
		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);

		//= get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no']);
		$rep->SetCommonData($myrow, null, null, $baccount, ST_STATEMENT, $contacts);
		$rep->NewPage();
		$rep->NewLine();
		$doctype = ST_STATEMENT;
		$rep->fontSize += 2;

		$rep->TextCol(2, 9, _("St. Matthew's Publishing Corporation"));
		$rep->NewLine();
		$rep->TextCol(2,9, _("744 Baltazar St., Cristi Compound, Guitnang Bayan 1, San Mateo Rizal"));
		$rep->NewLine();
		$rep->TextCol(2,9, _("Tel Nos.: 426-5611 * 433-5385 Telefax No: 426-1274"));
		$rep->NewLine();
		$rep->TextCol(0,9, _("Name:"));
		$rep->NewLine();
		$rep->TextCol(0,9, _("Contact:"));
		$rep->NewLine();
		$rep->TextCol(0,9, _("Address: "));
		$rep->NewLine();
		$rep->TextCol(0,9, _("Tel Nos."));
		$rep->NewLine();

		$rep->TextCol(3, 8, _("STATEMENT OF ACCOUNTS"));
		$rep->NewLine();

		$rep->TextCol(0,1, _("Date"));
		$rep->TextCol(1,2, _("Invoice #"));
		$rep->TextCol(2,3, _("Charge"));
		$rep->TextCol(3,4, _("Credit Balance"));
		$rep->fontSize -= 2;
		$rep->NewLine(2);
		while ($myrow2=db_fetch($TransResult))
		{
			$DisplayTotal = number_format2(Abs($myrow2["TotalAmount"]),$dec);
			$DisplayAlloc = number_format2($myrow2["Allocated"],$dec);
			$DisplayNet = number_format2($myrow2["TotalAmount"] - $myrow2["Allocated"],$dec);

			$rep->TextCol(2, 3, $systypes_array[$myrow2['type']], -2);
			$rep->TextCol(1, 2,	$myrow2['customized_no'], -2);
			$rep->TextCol(0, 1,	sql2date($myrow2['tran_date']), -2);
			if ($myrow2['type'] == ST_SALESINVOICE)
				$rep->TextCol(3, 4,	sql2date($myrow2['due_date']), -2);
			if ($myrow2['type'] == ST_SALESINVOICE || $myrow2['type'] == ST_BANKPAYMENT)
				$rep->TextCol(4, 5,	$DisplayTotal, -2);
			else
				$rep->TextCol(5, 6,	$DisplayTotal, -2);
			$rep->TextCol(6, 7,	$DisplayAlloc, -2);
			$rep->TextCol(6, 7,	$DisplayNet, -2);
			$rep->NewLine();
			if ($rep->row < $rep->bottomMargin + (10 * $rep->lineHeight))
				$rep->NewPage();
		}
		$nowdue = "1-" . $PastDueDays1 . " " . _("Days");
		$pastdue1 = $PastDueDays1 + 1 . "-" . $PastDueDays2 . " " . _("Days");
		$pastdue2 = _("Over") . " " . $PastDueDays2 . " " . _("Days");
		$CustomerRecord = get_customer_details($myrow['debtor_no'], null, $show_also_allocated);
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
			$rep->TextWrap($col[$i], $rep->row, $col[$i + 1] - $col[$i], $str2[$i], 'right');*/
		//if ($email == 1)
		//	$rep->End($email, _("Statement") . " " . _("as of") . " " . sql2date($date));

		

	}
	$rep->NewPage();
	if ($email == 0)
		$rep->End();
}

?>
