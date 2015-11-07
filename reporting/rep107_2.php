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

	$cols = array(4, 40, 60, 100, 200, 250, 300, 320, 400, 450);

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
			$rep->Info($null, $cols, null, $aligns);

			//$contacts = get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no'], true);
			//$baccount['payment_service'] = $pay_service;
			$salesman = get_imc_code($branch['branch_code']);
			$pay_term = get_payment_terms($myrow['payment_terms']);
			$dt = get_discount($branch_data['sales_discount_account'], $myrow['type'], $myrow['trans_no']);
			$branch_data = get_branch_accounts($myrow['branch_code']);

			$rep->NewPage();
   			$result = get_customer_trans_details(ST_SALESINVOICE, $i);
			$SubTotal = 0;
			$rep->NewLine(12);
			$rep->TextCol(2,7, _("Customer Name : ". $branch['br_name']));
			$rep->TextCol(7,9, _("Discount : " . $dt));
			$rep->NewLine();
			$rep->TextCol(2,5, _("Customer Code : ". $branch['branch_ref']));
			$rep->TextCol(7,9, _("IMC Code : ". $salesman));
			$rep->NewLine();
			$rep->TextCol(2,5, _("Contact Person : "));
			$rep->TextCol(7,9, _("Terms : ". $pay_term['terms']));
			$rep->NewLine();
			$rep->TextCol(2,5, _("Address : ". $branch['br_address']));
			$rep->NewLine(5);
			$rep->Font('bold');
			$rep->Line($rep->row  + 10);
			$rep->TextCol(2,5, _("Item Description"));
			$rep->TextCol(5,6, _("Quantity"));
			$rep->TextCol(7,8, _("Unit Price"));
			$rep->TextCol(9,10, _("Total Amount"));
			$rep->Line($rep->row  - 4);
			$rep->NewLine(2);
			$rep->Font();
			while ($myrow2=db_fetch($result))
			{
				if ($myrow2["quantity"] == 0)
					continue;

				$Net = round2($sign * ((1 - $myrow2["discount_percent"]) * $myrow2["unit_price"] * $myrow2["quantity"]), user_price_dec());
				$Net2 = round2($sign * ($myrow2["unit_price"] * $myrow2["quantity"]), user_price_dec());
				$SubTotal += $Net;
	    		$DisplayPrice = number_format2($myrow2["unit_price"],$dec);
	    		$DisplayQty = number_format2($sign*$myrow2["quantity"],get_qty_dec($myrow2['stock_id']));
	    		$DisplayNet = number_format2($Net,$dec);
	    		$dNet = number_format2($Net2,$dec);
	    		/*if ($myrow2["discount_percent"]==0)
		  			$DisplayDiscount ="";
	    		else
		  			$DisplayDiscount = number_format2($myrow2["discount_percent"]*100,user_percent_dec()) . "%";*/

		  		//if ($myrow["ov_discount"]==0)
		  		//	$DisplayDiscount ="";
	    		//else
		  			//$DisplayDiscount = number_format2($myrow["ov_discount"]/$myrow["ov_amount"] * 100,user_percent_dec()) . "%";


				$rep->TextCol(0, 2,	$myrow2['stock_id'], -2);
				$oldrow = $rep->row;
				$rep->TextColLines(2, 4, $myrow2['StockDescription'], -2);
				$newrow = $rep->row;
				$rep->row = $oldrow;
				if ($Net != 0.0 || !is_service($myrow2['mb_flag']) || !isset($no_zero_lines_amount) || $no_zero_lines_amount == 0)
				{
					$rep->TextCol(5, 6,	$DisplayQty. " ". $myrow2['units']);
					//$rep->TextCol(5, 6,	$myrow2['units'], -2);
					$rep->TextCol(7, 8,	$DisplayPrice, -2);
					//$rep->TextCol(5, 6,	$DisplayDiscount, -2);
					$rep->TextCol(8, 10,	$dNet, -2);
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

			$DisplayNet = number_format2($SubTotal, $dec);
   			$DisplaySubTot = number_format2($SubTotal,$dec);
   			$DisplayFreight = number_format2($sign*$myrow["ov_freight"],$dec);
   			$DisplayTots = number_format2($myrow['Total'], $dec);
   			$DisplayDiscount = number_format2($DisplaySubTot - $myrow['Total'], $dec);

    		$rep->row = $rep->bottomMargin + (15 * $rep->lineHeight);
			$doctype = ST_SALESINVOICE;

			$rep->NewLine();
			$rep->Font('bold');
			$rep->TextCol(8, 9, _("Shipping : "), -2);
			$rep->TextCol(9, 10, $DisplayFreight, -2);
			$rep->NewLine();
			$rep->TextCol(8, 9, _("Less : "));
			$rep->TextCol(9, 10,	$DisplayDiscount, -2);
			$rep->NewLine();
			$rep->TextCol(8, 9, _("Net Amount : "), -2);
			$rep->TextCol(9, 10,	$DisplayTots, -2);
			$rep->NewLine();
			/*$tax_items = get_trans_tax_details(ST_SALESINVOICE, $i);
			$first = true;
    		while ($tax_item = db_fetch($tax_items))
    		{
    			if ($tax_item['amount'] == 0)
    				continue;
    			$DisplayTax = number_format2($sign*$tax_item['amount'], $dec);
    			
    			if (isset($suppress_tax_rates) && $suppress_tax_rates == 1)
    				$tax_type_name = $tax_item['tax_type_name'];
    			else
    				$tax_type_name = $tax_item['tax_type_name']." (".$tax_item['rate']."%) ";

    			if ($tax_item['included_in_price'])
    			{
    				if (isset($alternative_tax_include_on_docs) && $alternative_tax_include_on_docs == 1)
    				{
    					if ($first)
    					{
							$rep->TextCol(3, 6, _("Total Tax Excluded"), -2);
							$rep->TextCol(6, 7,	number_format2($sign*$tax_item['net_amount'], $dec), -2);
							$rep->NewLine();
    					}
						$rep->TextCol(3, 6, $tax_type_name, -2);
						$rep->TextCol(6, 7,	$DisplayTax, -2);
						$first = false;
    				}
    				else
						$rep->TextCol(3, 7, _("Included") . " " . $tax_type_name . _("Amount") . ": " . $DisplayTax, -2);
				}
    			else
    			{
					$rep->TextCol(3, 6, $tax_type_name, -2);
					$rep->TextCol(6, 7,	$DisplayTax, -2);
				}
				$rep->NewLine();
    		}

    		$rep->NewLine();
			$DisplayTotal = number_format2($sign*($myrow["ov_freight"] + $myrow["ov_gst"] +
				$myrow["ov_amount"]+$myrow["ov_freight_tax"]),$dec);*/
			//$rep->Font('bold');
			//$rep->TextCol(3, 6, _("TOTAL INVOICE"), - 2);
			//$rep->TextCol(6, 7, $DisplayTotal, -2);
			//$words = price_in_words($myrow['Total'], ST_SALESINVOICE);
			//if ($words != "")
			//{
			//	$rep->NewLine(1);
			//	$rep->TextCol(1, 7, $myrow['curr_code'] . ": " . $words, - 2);
			//}
			//$rep->Font();
			if ($email == 1)
			{
				$rep->End($email);
			}
	}
	if ($email == 0)
		$rep->End();
}

?>
