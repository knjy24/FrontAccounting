<?php

$page_security = $_POST['PARAM_0'] == $_POST['PARAM_1'] ?
	'SA_SALESTRANSVIEW' : 'SA_SALESBULKREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Print Invoices
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/ml/db/customer_trans.php");

//----------------------------------------------------------------------------------------------------

print_invoices();


//----------------------------------------------------------------------------------------------------

function print_invoices()
{
	global $path_to_root, $alternative_tax_include_on_docs, $suppress_tax_rates, $no_zero_lines_amount;
	
	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$currency = $_POST['PARAM_2'];
	$email = $_POST['PARAM_3'];
	$pay_service = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$customer = $_POST['PARAM_6'];
	$orientation = $_POST['PARAM_7'];


	if (!$from || !$to) return;
	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

 	$fno = explode("-", $from);
	$tno = explode("-", $to);
	$from = min($fno[0], $tno[0]);
	$to = max($fno[0], $tno[0]);

	

	$cols = array(-18, 40, 60, 100, 160, 200, 260, 360, 420, 450, 500);

	// $headers in doctext.inc
	$aligns = array('left',	'left',	'left', 'left', 'right', 'right', 'right', 'right', 'right', 'right', 'right');

	//$params = array('comments' => $comments);

	$cur = get_company_Pref('curr_default');
	$company_data = get_company_prefs();
	

	if ($email == 0)
		$rep = new FrontReport(_('INVOICE'), "InvoiceBulk", user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);
	for ($i = $from; $i <= $to; $i++)
	{
			if (!exists_customer_trans(ST_SALESINVOICE, $i))
				continue;
			$sign = 1;
			$myrow = get_customer_trans($i, ST_SALESINVOICE);

			if($customer && $myrow['debtor_no'] != $customer) {
				continue;
			}
			$baccount = get_default_bank_account($myrow['curr_code']);
			$params['bankaccount'] = $baccount['id'];

			$branch = get_branch($myrow["branch_code"]);
			$sales_order = get_sales_order_header($myrow["order_"], ST_SALESORDER);
			if ($email == 1)
			{
				$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
				//$rep->title = _('INVOICE');
				$rep->filename = "Invoice" . $myrow['reference'] . ".pdf";
			}	
			$rep->SetHeaderType(0);
			$rep->currency = $cur;
			$rep->Font();
			$rep->Info(null, $cols, null, $aligns);

			$salesman = get_imc_code($branch['branch_code']);
			$pay_term = get_payment_terms($myrow['payment_terms']);
			$branch_data = get_branch_accounts($myrow['branch_code']);
			
			$invoice_no = get_custom_no($myrow['trans_no'], $myrow['type']);
			$contact = getContact($myrow['salesman'], $myrow['debtor_no'], $branch['branch_code']);
			$cnumber = getContactNumber($myrow['salesman'], $myrow['debtor_no'], $branch['branch_code']);

			$rep->NewPage();
   			$result = get_customer_trans_details(ST_SALESINVOICE, $i);
			$SubTotal = 0;
			$rep->NewLine(6);
			$rep->TextCol(2,7, $invoice_no);
			$rep->TextCol(6,8, $myrow['TranDate']);
			$rep->NewLine();
			$rep->TextCol(2,7, $branch['br_name']);
			$rep->TextCol(7,8, $myrow['bulk_discount'] ."%");	
			$rep->NewLine();
			$rep->TextCol(2,7, $branch['branch_ref']);
			$rep->TextCol(6,8, $salesman);
			$rep->NewLine();
			$rep->TextCol(2,6, $contact." - ". $cnumber);
			if ($pay_term['terms'] == 'C.O.D.' || $pay_term['Cash Only'])
			{
				$rep->TextCol(7,8, $pay_term['terms'], -2);
			} else {
				$oldrow = $rep->row;
				$newrow = $rep->row;
				$rep->TextColLines(7,9, $pay_term['terms'], -2);
				$newrow = $rep->row;
				$rep->row = $oldrow;
			}
				

			$rep->NewLine();
			$oldrow = $rep->row;
			$newrow = $rep->row;
			$rep->TextColLines(2,6, $branch['br_address'], -2);
			$rep->row = $oldrow;
			$rep->NewLine(4);
			$rep->Font();
			while ($myrow2=db_fetch($result))
			{
				if ($myrow2["quantity"] == 0)
					continue;

				$Net = round2($sign * ((1 - $myrow2["discount_percent"]) * $myrow2["unit_price"] * $myrow2["quantity"]), user_price_dec());
				$Net2 = round2($sign * ($myrow2["unit_price"] * $myrow2["quantity"]), user_price_dec());
				$SubTotal += $Net;
	    		$DisplayPrice = number_format2($myrow2["unit_price"],2);
	    		$DisplayQty = number_format2($sign*$myrow2["quantity"],get_qty_dec($myrow2['stock_id']));
	    		$DisplayNet = number_format2($Net,2);
	    		$dNet = number_format2($Net2,2);
	    		
				$oldrow = $rep->row;
				$rep->TextColLines(-0.75, 4, $myrow2['StockDescription'], -2);
				$newrow = $rep->row;
				$rep->row = $oldrow;
				if ($Net != 0.0 || !is_service($myrow2['mb_flag']) || !isset($no_zero_lines_amount) || $no_zero_lines_amount == 0)
				{
					$rep->TextCol(4, 5,	$DisplayQty. " ". $myrow2['units']);
					$rep->TextCol(5, 6,	$DisplayPrice, -2);
					$rep->TextCol(6, 8,	$dNet, -2);
				}	
				$rep->row = $newrow;
				if ($rep->row < $rep->bottomMargin + (15 * $rep->lineHeight))
					$rep->NewPage();
			}

			$memo = get_comments_string(ST_SALESINVOICE, $i);
			if ($memo != "")
			{
				$rep->NewLine();
				$rep->TextColLines(1, 5, $memo, -2);
			}

			$DisplayNet = number_format2($SubTotal, 2);
   			$DisplaySubTot = number_format2($SubTotal,2);
   			$DisplayTots = number_format2($myrow['Total'], 2);
   			$DisplayDiscount = number_format2($SubTotal * ($myrow['bulk_discount']/100), 2);

    		$rep->NewLine(3);
			$doctype = ST_SALESINVOICE;

			$rep->NewLine();
			$rep->Font('bold');
			$rep->TextCol(6,7, "TOTAL AMOUNT :");
			$rep->TextCol(6,8, $DisplayNet, -2);
			$rep->NewLine();
			$rep->TextCol(6,7, "VOLUME DISCOUNT :");
			$rep->TextCol(5, 8,	$DisplayDiscount, -2);
			$rep->NewLine();
			$rep->TextCol(6, 7, _("NET AMOUNT : "), -2);
			$rep->TextCol(5, 8,	$DisplayTots, -2);
			$rep->NewLine();
			
			if ($email == 1)
			{
				$rep->End($email);
			}
	}
	if ($email == 0)
		$rep->End();
}

?>
