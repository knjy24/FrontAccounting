<?php

$page_security = 'SA_CUSTSTATREP';

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
	$sql = "SELECT ".TB_PREF."debtor_trans.*, v.type as IsVoid,	
		".amountSQL()."
		AS TotalAmount, ".TB_PREF."debtor_trans.alloc AS Allocated,
		((".TB_PREF."debtor_trans.type != ".ST_SALESINVOICE.")
		OR ".TB_PREF."debtor_trans.due_date <= '$date') AS OverDue,
		IF(due_date = '0000-00-00' , ".TB_PREF."debtor_trans.tran_date, due_date) AS EffectiveDate
		FROM ".TB_PREF."debtor_trans
		LEFT JOIN ".TB_PREF."voided v  ON v.type = ".TB_PREF."debtor_trans.type and v.id=".TB_PREF."debtor_trans.trans_no
		where GREATEST(".TB_PREF."debtor_trans.tran_date, ".TB_PREF."debtor_trans.due_date) >= '$from' AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
		AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
		AND ABS(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight +
		".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount) > 1e-6";
	$sql .= " ORDER BY ".TB_PREF."debtor_trans.type";
//and ".TB_PREF."debtor_trans.payment_terms != 4
	return db_query($sql,"No transactions were returned");
}

//----------------------------------------------------------------------------------------------------

function print_statements()
{
	global $path_to_root, $systypes_array;

	include_once($path_to_root . "/reporting/includes/pdf_report2.inc");

	$from = date2sql($_POST['PARAM_0']);
	$to = date2sql($_POST['PARAM_1']);
	$customer = $_POST['PARAM_2'];
	$currency = $_POST['PARAM_3'];
	$email = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$orientation = $_POST['PARAM_6'];

	$orientation = ($orientation ? 'L' : 'P');
	$dec = 2;

	$cols = array(4, 64, 180, 250, 300, 350, 400, 480);

	//$headers in doctext.inc

	$aligns = array('left',	'left',	'left',	'right',	'right', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_pref('curr_default');
	$PastDueDays1 = get_company_pref('past_due_days');
	$PastDueDays2 = 2 * $PastDueDays1;

	if ($email == 0)
		$rep = new FrontReport(_('CUSTOMER ACCOUNT STATEMENT'), "StatementBulk", user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);

	$sql = "SELECT b.debtor_no, b.name AS DebtorName, b.address, b.tax_id, b.curr_code, cust.salesman, 
	curdate() AS tran_date, CONCAT (d.name, d.name2) AS contactPerson, d.phone, d.phone2  
	FROM ".TB_PREF."debtors_master b INNER JOIN
".TB_PREF."crm_contacts c on b.debtor_no=c.entity_id INNER JOIN ".TB_PREF."crm_persons d on c.person_id=d.id
INNER JOIN ".TB_PREF."cust_branch cust on b.debtor_no=cust.debtor_no";
	if ($customer != ALL_TEXT)
		$sql .= " WHERE c.type='customer' and cust.salesman = ".db_escape($customer);
	else
		$sql .= " where c.type='customer' and cust.salesman = ".db_escape($customer)." ORDER by b.name";
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
			$rep->title = _('STATEMENT OF ACCOUNT');
			$rep->filename = "Statement" . $debtor_row['debtor_no'] . ".pdf";
			$rep->Info($params, $cols, null, $aligns);
		}

		$rep->filename = "ST-" . strtr($debtor_row['DebtorName'], " '", "__") ."--" . strtr(Today(), "/", "-") . ".pdf";
		$contacts = get_customer_contacts($debtor_row['debtor_no'], 'invoice');
		$rep->SetHeaderType('customheader');
		$rep->currency = $cur;
		$rep->Font();
		$rep->Info(null, $cols, null, $aligns);

		$rep->SetCommonData($debtor_row, null, null, $baccount, ST_STATEMENT, $contacts);
		$rep->NewPage();
		$doctype = ST_STATEMENT;

		//$rep->TextCol(0,4,"yeah");
		$current = false;
		$balance = getInitialBalance($debtor_row['debtor_no'], $start);
		if(true || Abs($balance) > 1e-6) {
				if(Abs($balance) < 1e-6) $rep->SetTextColor(190, 190, 190);
				else if($balance > 0) $rep->SetTextColor(190, 0, 0);
			$rep->SetTextColor(0, 0, 0);
		}
		$overdue = 0;
		$prev = '';

		$gross_amount = 0;
		$gross_amount2 = 0;
		$payment_tot = 0;
		$tots = 0;
		$discount_amount = 0;
		$percent = 0;
		while ($transaction_row=db_fetch($TransResult))
		{
			if ($myrow['IsVoid'] == '')
			{
			$company_data = get_company_prefs();
			$branch = get_branch($transaction_row["branch_code"]);
			$branch_data = get_branch_accounts($transaction_row['branch_code']);

			$dt = get_discount($branch_data['sales_discount_account'], $transaction_row['type'], $transaction_row['trans_no']);

			$DisplayTotal = number_format2(Abs($transaction_row["TotalAmount"] + $dt),$dec);
			$DisplayAlloc = number_format2($transaction_row["Allocated"],$dec);
			$DisplayNet = number_format2($transaction_row["TotalAmount"] - $transaction_row["Allocated"],$dec);
			
			/*if ($dt != 0 && $transaction_row['type'] == ST_SALESINVOICE || $transaction_row['type'] == ST_CUSTCREDIT)
				{
					$discount_amount += $dt;
						$ctr = $transaction_row['bulk_discount'];
				}*/
			
			$amount = ($transaction_row["TotalAmount"] + $dt);

			$balance +=  $transaction_row["TotalAmount"];
			$invoice_no = get_custom_no($transaction_row['trans_no'], $transaction_row['type']);

			$enter1 = 0;
			
			if ($systypes_array[$transaction_row['type']] =="Customer Payment")
			{
				$open_pay = get_payment_invoice_details($transaction_row["trans_no"], ST_CUSTPAYMENT);
				if ($open_pay)
				{
					$stat2 = false;
				}
				else
				{
					$stat2 = true;
					$payment_tot += $amount;
					$text = "pr#";
				}
			}
				
			if ($systypes_array[$transaction_row['type']] =="Sales Invoice")
			{
				if ($transaction_row['ov_amount'] > $transaction_row['alloc'] || $transaction_row['alloc'] == 0)
				{
					$discount_amount += $dt;
						$ctr = $transaction_row['bulk_discount'];
					$gross_amount += $amount;
					$text = '';
					$stat3 = true;
				}
				else
					$stat3 = false;
			}
			
			if ($systypes_array[$transaction_row['type']] == "Customer Credit Note")
			{
				$open = get_sales_invoice_details($transaction_row['order_'], ST_SALESINVOICE);
				if ($open)
				{
					$stat = false;
				}
				else
				{
					$discount_amount += $dt;
					$ctr = $transaction_row['bulk_discount'];
					$gross_amount2 += $amount;
					$stat = true;
				}
				
				$text = "cm#";
			}

			$current = $text;

			$tot = ($gross_amount + $gross_amount2) - $discount_amount;
			if ($current != '')
			{
				if ($prev == $current)
				{

				} else
				{

					if ($prev == "" && $text == "cm#"  && $stat)
					{
						$rep->NewLine();
						$rep->TextCol(1,2, "Less Returns");
						$rep->NewLine();
					}

					
					if ($text == 'pr#' && $prev == "cm#" && $stat2)
					{
						
						$rep->AmountCol(6,7, $tot,2);	
						$rep->NewLine(2);
						$rep->TextCol(1,2, "Less Payments:");
						$rep->NewLine();
					}



					if ($prev == '' && $text =='pr#' && $stat2)
					{
						
						$rep->TextCol(1,2, "Less Payments:");
						$rep->NewLine();
					}
				}
				$prev = $current;
			}
			
			if ($transaction_row['type'] == ST_SALESINVOICE && $stat3)
			{
				$rep->TextCol(1, 2, $text.$invoice_no, -2);
				$rep->TextCol(0, 3,	sql2date($transaction_row['tran_date']), -2);
				$rep->TextCol(3, 4,	$DisplayTotal, -2);
				$rep->NewLine();
			}
			if ($transaction_row['type'] == ST_CUSTCREDIT && $stat)
			{
				$rep->TextCol(1, 2, $text.$invoice_no, -2);
				$rep->TextCol(0, 3,	sql2date($transaction_row['tran_date']), -2);
				$rep->TextCol(3, 4,	$DisplayTotal, -2);
				$rep->NewLine();
			}
			if ($transaction_row['type'] == ST_CUSTPAYMENT && $stat2)
			{
				if ($invoice_no == "")
					$rep->TextCol(1, 2, $text.$transaction_row['reference'], -2);
				else
				$rep->TextCol(1, 2, $text.$invoice_no, -2);
				$rep->TextCol(0, 3,	sql2date($transaction_row['tran_date']), -2);
				$rep->TextCol(5, 6,	$DisplayTotal, -2);
				$rep->NewLine();
			}
			
			$rep->SetTextColor(0, 0, 0);

			//$rep->NewLine();
			if ($rep->row < $rep->bottomMargin + (10 * $rep->lineHeight))
				$rep->NewPage();
		}
		}

		if(!$current)  {
				$overdue = $balance;
				$balance = 0;
		}
		$rep->NewLine();
		$net = $gross_amount - abs($gross_amount2);
		$percent = ($ctr / $net) * 100;
		$per = number_format2($percent, 2);
		if ($per != 0)
		{
			$rep->TextCol(1,2, "Less  ".$ctr."% discount");
		$rep->AmountCol(5, 6, $discount_amount, 2);
		}
		
		$rep->SetTextColor(0, 0, 0);
		$rep->fontSize += 2;	
		$rep->NewLine(5);
		$rep->TextCol(1,2,'Amount Due');
		//$rep->TextCol(6,7, "     	____________", -2);	
		if ($payment_tot != 0)
		$rep->TextCol(6,7, number_format2($tot - abs($payment_tot), $dec));
		else
			$rep->AmountCol(6,7, $tot, 2);
		$rep->NewLine(5);
		$rep->TextCol(2,4, "Verified & Checked by:");
		$rep->TextCol(4,6, "___________________");
		$rep->NewLine();
		$rep->TextCol(4,6, "Credit & Collection");
		$rep->fontSize -= 2;
	
	}
	//$rep->NewPage();

	if ($email == 0)
		$rep->End();
}

?>
