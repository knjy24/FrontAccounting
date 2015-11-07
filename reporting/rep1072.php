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

	$cols = array(-15, 10, 30, 50, 80, 240, 300, 340, 360, 400);

	// $headers in doctext.inc
	$aligns = array('right',	'left',	'left', 'left', 'left', 'left', 'right', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_Pref('curr_default');

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
			$salesm = get_salesman_name($branch['salesman']);
			$sales_order = get_sales_order_header($myrow["order_"], ST_SALESORDER);
			if ($email == 1)
			{
				$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
				$rep->title = _('INVOICE');
				$rep->filename = "Invoice" . $myrow['reference'] . ".pdf";
			}	
			$rep->SetHeaderType(0);
			$rep->currency = $cur;
			$rep->Font();
			$rep->Info(null, $cols, null, $aligns);

			$contacts = get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no'], true);
			$baccount['payment_service'] = $pay_service;
			$rep->SetCommonData($myrow, $branch, $sales_order, $baccount, ST_SALESINVOICE, $contacts);
			$rep->NewPage();
   			$result = get_customer_trans_details(ST_SALESINVOICE, $i);
			$SubTotal = 0;
			$invoice_no = get_custom_no($myrow['trans_no'], ST_SALESINVOICE);
			$rep->NewLine(5);
			$rep->TextCol(2,5, $branch['br_name']);
			$rep->TextCol(7,9, $myrow['TranDate']);
			$rep->NewLine();
			$rep->TextCol(2,10, $branch['br_address']);
			$rep->TextCol(7,9, "SI - ".$invoice_no);
			$rep->NewLine();
			$rep->TextCol(0,2, "Contact: ");
			$rep->TextCol(2,5, $contacts["name"].$contacts["name2"]);
			$rep->TextCol(6,9, "IMC: ".$salesm);
			$rep->NewLine();
			$rep->TextCol(2,5, "");
			$rep->NewLine();
			$rep->TextCol(2,5, "");

			$rep->NewLine(3);
			//$rep->NewLine(2);

			$ent = 0;
			while ($myrow2=db_fetch($result))
			{
				if ($myrow2["quantity"] == 0)
					continue;

				$Net = round2($sign * ((1 - $myrow2["discount_percent"]) * $myrow2["unit_price"] * $myrow2["quantity"]),
				   user_price_dec());
				$SubTotal += $Net;
	    		$DisplayPrice = number_format2($myrow2["unit_price"],2);
	    		$DisplayQty = number_format2($sign*$myrow2["quantity"],get_qty_dec($myrow2['stock_id']));
	    		$DisplayNet = number_format2($Net,2);
	    		if ($myrow2["discount_percent"]==0)
		  			$DisplayDiscount ="";
	    		else
		  			$DisplayDiscount = number_format2($myrow2["discount_percent"]*100, 2) . "%";
				//$rep->TextCol(3, 4,	$myrow2['stock_id'], -2);
				$oldrow = $rep->row;
				$rep->TextColLines(4, 6, $myrow2['StockDescription'], -2);
				$newrow = $rep->row;
				$rep->row = $oldrow;

				$ent++;
				if ($Net != 0.0 || !is_service($myrow2['mb_flag']) || !isset($no_zero_lines_amount) || $no_zero_lines_amount == 0)
				{
					$rep->TextCol(2, 3,	$DisplayQty, -2);
					$rep->TextCol(3, 4,	$myrow2['units'], -2);
					$rep->TextCol(6, 7,	$DisplayPrice, -2);
					$rep->TextCol(7, 9,	$DisplayNet, -2);
				}	
				$rep->row = $newrow;
				//$rep->NewLine(1);
				if ($rep->row < $rep->bottomMargin + (15 * $rep->lineHeight))
					$rep->NewPage();
			}

			$memo = get_comments_string(ST_SALESINVOICE, $i);
			if ($memo != "")
			{
				$rep->NewLine();
				$rep->TextColLines(1, 5, $memo, -2);
			}

   			$DisplaySubTot = $SubTotal;

   			$rep->NewLine(8-$ent);
			$doctype = ST_SALESINVOICE;
			$tot = $sign*($myrow["ov_freight"] + $myrow["ov_gst"] + $myrow["ov_amount"]+$myrow["ov_freight_tax"]);
			if ($tot != $SubTotal)
				$discount = ($SubTotal * ($myrow['bulk_discount'] / 100));

	
			$rep->AmountCol(7, 9,	$DisplaySubTot, 2);
			$rep->NewLine();
			if ($myrow['bulk_discount'] != 0)
				$rep->TextCol(5,7, "Less Discount: ".$myrow['bulk_discount']."%");
			$rep->AmountCol(7, 9,	$discount, 2);
			$rep->NewLine();
			
			$DisplayTotal = $sign*($myrow["ov_freight"] + $myrow["ov_gst"] +
				$myrow["ov_amount"]+$myrow["ov_freight_tax"]);
			$rep->Font('bold');
			$rep->AmountCol(7, 9, $DisplayTotal, 2);
			
	}
	if ($email == 0)
		$rep->End();
}

?>
