<?php

//--------------------------------------------------------------------------------------------------

function copy_from_trans(&$alloc)
{

	$_POST['tran_date'] = $alloc->tran_date;
}

//--------------------------------------------------------------------------------------------------

function copy_to_trans(&$alloc)
{

	$alloc->payment =  0;
	$alloc->tran_date = $_POST['tran_date'];

	if (count($alloc->gl_codes) > 0)
	{
		foreach ( $alloc->gl_codes as $gl_line)
		{
			////////// 2009-08-18 Joe Hunt
			///if (!is_tax_account($gl_line->gl_code) || $alloc->tax_included)
				$alloc->payment += $gl_line->amount;
		}
	}
}


//--------------------------------------------------------------------------------------------------

function invoice_totals(&$alloc, $pmt)
{
	copy_to_trans($alloc);

	$dim = get_company_pref('use_dimension');
 	$colspan = ($dim == 2 ? 7 : ($dim == 1 ? 6 : 5));
   	start_table(TABLESTYLE, "width=95%");
   	$pay = price_format($pmt);
   	label_row(_("Sub-total:"), price_format($alloc->payment + $pmt), "colspan=$colspan align=right", "align=right");


    $display_total = price_format($alloc->payment + $pmt);

	if ($alloc->type == ST_SUPPAYMENT)
    	label_row(_("Payment Total:"), 
    		$display_total, "colspan=$colspan align=right style='font-weight:bold;'", "align=right style='font-weight:bold;'");
    else
		label_row(_("Credit Note Total"),
			$display_total, "colspan=$colspan align=right style='font-weight:bold;color:red;'", "nowrap align=right style='font-weight:bold;color:red;'");

    end_table(1);
    /*start_table(TABLESTYLE2);
    textarea_row(_("Memo:"), "Comments", null, 50, 3);
    end_table(1);*/
}

//--------------------------------------------------------------------------------------------------
function display_gl_controls(&$alloc, $k)
{
	$accs = get_supplier_accounts($alloc->supplier_id);
	$_POST['gl_code'] = $accs['purchase_account'] ? 
		$accs['purchase_account'] : get_company_pref('default_cogs_act');

	alt_table_row_color($k);
	echo gl_all_accounts_list('gl_code', null, true, true);
	$dim = get_company_pref('use_dimension');
	if ($dim >= 1)
		dimensions_list_cells(null, 'dimension_id', null, true, " ", false, 1);
	if ($dim > 1)
		dimensions_list_cells(null, 'dimension2_id', null, true, " ", false, 2);
	amount_cells(null, 'amount');
	if ($dim < 1)	
		text_cells_ex(null, 'memo_', 35, 50, null, null, null, hidden('dimension_id', 0, false).hidden('dimension2_id', 0, false));
	else if ($dim < 2)	
		text_cells_ex(null, 'memo_', 35, 50, null, null, null, hidden('dimension2_id', 0, false));
	else	
		text_cells_ex(null, 'memo_', 35, 50, null, null, null);
	submit_cells('AddGLCodeToTrans', _("Add"), "",
		    _('Add GL Line'), true);
	submit_cells('ClearFields', _("Reset"), "",
		    _("Clear all GL entry fields"), true);
	end_row();	
}

// $mode = 0 none at the moment
//		 = 1 display on invoice/credit page
//		 = 2 display on view invoice
//		 = 3 display on view credit

function display_gl_items(&$alloc, $mode=0)
{
	global $path_to_root, $Ajax;

    // if displaying in form, and no items, exit
    if (($mode == 2 || $mode == 3) && count($alloc->gl_codes) == 0)
    	return 0;

	if ($alloc->type == ST_SUPPINVOICE)
		$heading = _("GL Items for this Invoice");
	if ($alloc->type == ST_SUPPAYMENT)
		$heading = _("GL Items for this Payment");
	else
		$heading = _("GL Items for this Credit Note");

	start_outer_table(TABLESTYLE, "width=95%");
	
	if ($mode == 1)
	{
		$qes = has_quick_entries(QE_SUPPINV);
		if ($qes !== false)
		{
			echo "<div style='float:right;'>";
			echo _("Quick Entry:")."&nbsp;"; 
			echo quick_entries_list('qid', null, QE_SUPPINV, true);
			$qid = get_quick_entry(get_post('qid'));
			if (list_updated('qid')) {
				unset($_POST['totamount']); // enable default
				$Ajax->activate('totamount');
			}
			echo "&nbsp;".$qid['base_desc'].":"."&nbsp;";

			$amount = input_num('totamount', $qid['base_amount']);
			$dec = user_price_dec();
			echo "<input class='amount' type='text' name='totamount' size='7' maxlength='12' dec='$dec' value='$amount'>&nbsp;";
			submit('go', _("Go"), true, false, true);
			echo "</div>";

		}	
	}
	display_heading($heading);

	end_outer_table(0, false);

	div_start('gl_items');
	start_table(TABLESTYLE, "width=95%");

	$dim = get_company_pref('use_dimension');
	if ($dim == 2)
    	$th = array(_("Account"), _("Name"), _("Dimension")." 1", _("Dimension")." 2", _("Amount"), _("Memo"));
	else if ($dim == 1)
    	$th = array(_("Account"), _("Name"), _("Dimension"), _("Amount"), _("Memo"));
    else
    	$th = array(_("Account"), _("Name"), _("Amount"), _("Memo"));

	if ($mode == 1)
	{
		$th[] = "";
		$th[] = "";
	}	
	table_header($th);
	$total_gl_value=$total = 0;
	$i = $k = 0;

	if (count($alloc->gl_codes) > 0)
	{

		foreach ($alloc->gl_codes as $entered_gl_code)
		{

			alt_table_row_color($k);

			if ($mode == 3)
				$entered_gl_code->amount = -$entered_gl_code->amount;

			label_cell($entered_gl_code->gl_code);
			label_cell($entered_gl_code->gl_act_name);

			if ($dim >= 1)
   				label_cell(get_dimension_string($entered_gl_code->gl_dim, true));
			if ($dim > 1)
   				label_cell(get_dimension_string($entered_gl_code->gl_dim2, true));

			amount_cell($entered_gl_code->amount, true);
			label_cell($entered_gl_code->memo_);

			if ($mode == 1)
			{
				delete_button_cell("Delete2" . $entered_gl_code->Counter, _("Delete"),
					  _('Remove line from document'));
				label_cell("");
			}	
			end_row();
			/////////// 2009-08-18 Joe Hunt
			if ($mode > 1) {
				if ($alloc->tax_included || !is_tax_account($entered_gl_code->gl_code))
					$total_gl_value += $entered_gl_code->amount;
			}		
			else	
				$total_gl_value += $entered_gl_code->amount;
			$total += $entered_gl_code->amount;	
			$i++;
			if ($i > 15)
			{
				$i = 0;
				table_header($th);
			}
		}

	}
	if ($mode == 1)
		display_gl_controls($alloc, $k);
	$colspan = ($dim == 2 ? 4 : ($dim == 1 ? 3 : 2));
	label_row(_("Total"), price_format($total),
		"colspan=".$colspan." align=right", "nowrap align=right", ($mode==1?3:1));

	end_table(1);
	div_end();

	return $total_gl_value;
}

//--------------//-----------------------------------------------------------------------------------------




//--------------------------------------------------------------------------------------------------

?>