<?php

/*
	Class for supplier/customer payment/credit allocations edition
	and related helpers.
*/
//-----------------------------------------------------------------------------------

class allocation 
{

	var $trans_no; 
	var $type;
	var $person_id = '';
	var $person_name = '';
	var $person_type;	// PT_SUPPLIER/PT_CUSTOMER
	var $person_curr;
	var $date_;
	var $payment = 0; /*Total amount of the transaction in FX */
	var $currency;
	var $gl_codes; //added by karen

	var $allocs; /*array of transactions allocated to */
	var $gl_codes_counter=0;


	function allocation($type, $trans_no, $person_id = null, $person_type_id=null)
	{

		$this->gl_codes = array();
		$this->allocs = array();

		$this->trans_no = $trans_no;
		$this->type = $type;
		if ($person_id)
			$this->set_person($person_id, $person_type_id);

		$this->read($type, $trans_no, $person_id, $person_type_id); // read payment or credit
	}

	function add_gl_codes_to_trans($gl_code, $gl_act_name, $gl_dim, $gl_dim2, $amount, $memo_)
	{
		$this->gl_codes[$this->gl_codes_counter] = new gl_codes($this->gl_codes_counter, 
			$gl_code, $gl_act_name, $gl_dim, $gl_dim2, $amount, $memo_);
		$this->gl_codes_counter++;
		//unset($this->tax_overrides); // cancel tax overrides after cart change
		return 1;
	}

	function remove_gl_codes_from_trans($gl_code_counter)
	{
		//unset($this->tax_overrides); // cancel tax overrides after cart change
		unset($this->gl_codes[$gl_code_counter]);
		//echo $gl_code_counter;
	}

	function get_items_total()
	{
		$total = 0;

		foreach ($this->gl_codes as $gl_line)
		{
				$total += $gl_line->amount;
		}
		return $total;
	}

	function clear_items()
	{
		
		unset($this->gl_codes);
		$this->payment =  $this->supplier_id = 0;

		$this->gl_codes = array();
	}

	function set_person($person_id, $person_type)
	{
		$this->person_id = $person_id;
		$this->person_type = $person_type;
		$this->person_curr = $person_type == PT_SUPPLIER ?
			get_supplier_currency($person_id) : get_customer_currency($person_id);
		return $this->person_curr;
	}

	function add_item($type, $type_no, $date_, $due_date, $payment, $amount_allocated, 
		$current_allocated, $ref)
	{
		if (floatcmp($payment, 0))
		{
			$this->allocs[count($this->allocs)] = new allocation_item($type, $type_no, 
				$date_, $due_date, $payment, $amount_allocated, $current_allocated, $ref);
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	function update_item($index, $type, $type_no, $date_, $due_date, 
		$payment, $amount_allocated, $current_allocated, $ref)
	{
		if (floatcmp($payment, 0))
		{
			$this->allocs[$index] = new allocation_item($type, $type_no, 
				$date_, $due_date, $payment, $amount_allocated, $current_allocated, $ref);
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	function add_or_update_item($type, $type_no, $date_, $due_date, 
		$payment, $amount_allocated, $current_allocated, $ref)
	{
		for ($i = 0; $i < count($this->allocs); $i++) 
		{
			$item = $this->allocs[$i];
			if (($item->type == $type) && ($item->type_no == $type_no)) 
			{
				return $this->update_item($i, $type, $type_no, $date_, $due_date, 
					$payment, $amount_allocated, $current_allocated, $ref);
			}
		}
        return $this->add_item($type, $type_no, $date_, $due_date, 
        	$payment, $amount_allocated, $current_allocated, $ref);
	}

	//
	//	Read payment or credit current/available allocations to cart.
	//
	// FIXME - read all transactions below twice seems to be suboptimal
	//
	function read($type = null, $trans_no = 0, $person_id=null, $person_type_id=null)
	{
		if ($type !== null) {	// otherwise re-read
			$type = $this->type;
			$trans_no = $this->trans_no;

			if (isset($person_type_id))
			{
				$this->person_type = $person_type_id;
				$this->person_id = $person_id;
			} else { // guess person_type_id
				if (in_array($type, array(ST_BANKPAYMENT, ST_BANKDEPOSIT)))
				{
					$bank_trans = db_fetch(get_bank_trans($type, $trans_no));
					$this->person_type = $bank_trans['person_type_id'];
				} else
					$this->person_type = in_array($type, array(ST_SUPPCREDIT, ST_SUPPAYMENT)) ? PT_SUPPLIER : PT_CUSTOMER;
			}

			if ($trans_no) {
				$trans = $this->person_type == PT_SUPPLIER ? get_supp_trans($trans_no, $type, $person_id)
					: get_customer_trans($trans_no, $type, $person_id);

				$this->person_id = $trans[$this->person_type == PT_SUPPLIER ? 'supplier_id':'debtor_no'];
				$this->person_name = $trans[$this->person_type == PT_SUPPLIER ? "supplier_name":"DebtorName"];
				$this->date_ = sql2date($trans["tran_date"]);
				$this->person_curr = $trans['curr_code'];
				$this->currency = isset($trans['bank_curr_code']) ? $trans['bank_curr_code'] : $trans['curr_code'];
				$this->bank_amount = @$trans["bank_amount"];
				$this->payment = $trans["Total"];
			} else
				$this->date_ = Today();
		}
	/* Now populate the array of possible (and previous actual) allocations 
		for this customer/supplier. First get the transactions that have 
		outstanding balances ie Total-alloc >0 */

		$this->allocs = array();
		if ($this->person_id)
		{
			if ($this->person_type==PT_SUPPLIER)
				$trans_items = get_allocatable_to_supp_transactions($this->person_id);
			else
		 		$trans_items = get_allocatable_to_cust_transactions($this->person_id);
			while ($myrow = db_fetch($trans_items))
			{
				$this->add_item($myrow["type"], $myrow["trans_no"],
					sql2date($myrow["tran_date"]),
					sql2date($myrow["due_date"]),
					$myrow["Total"], // trans total
					$myrow["alloc"], // trans total allocated
					0,
					$myrow["reference"]); // this allocation
			}
		}
		if ($this->trans_no == 0) return; // this is new payment

	/* Now get trans that might have previously been allocated to by this trans
	NB existing entries where still some of the trans outstanding entered from
	above logic will be overwritten with the prev alloc detail below */

		if ($this->person_type==PT_SUPPLIER)
			$trans_items = get_allocatable_to_supp_transactions($this->person_id, 
				$this->trans_no, $this->type);
		else
		 	$trans_items = get_allocatable_to_cust_transactions($this->person_id, 
				$this->trans_no, $this->type);

		while ($myrow = db_fetch($trans_items))
		{
			$this->add_or_update_item ($myrow["type"], $myrow["trans_no"],
				sql2date($myrow["tran_date"]),
				sql2date($myrow["due_date"]),
				$myrow["Total"],
				$myrow["alloc"] - $myrow["amt"], $myrow["amt"], $myrow["reference"]);
		}
	}
	//
	//	Update allocations in database.
	//
	function write()
	{
		begin_transaction();

		if ($this->person_type == PT_SUPPLIER)
			clear_supp_alloctions($this->type, $this->trans_no, $this->date_);
		else
		 	clear_cust_alloctions($this->type, $this->trans_no, $this->date_);

		// now add the new allocations
		$total_allocated = 0;
		$dec = user_price_dec();
		foreach ($this->allocs as $alloc_item)
		{
			if ($alloc_item->current_allocated > 0)
			{
				$payment = round($alloc_item->current_allocated, $dec);

				if ($this->person_type == PT_SUPPLIER) {
					add_supp_allocation($payment,
						$this->type, $this->trans_no,
			    	 	$alloc_item->type, $alloc_item->type_no, $this->date_);

					update_supp_trans_allocation($alloc_item->type, $alloc_item->type_no);
				} else {
	 				add_cust_allocation($payment,
						$this->type, $this->trans_no,
 			     		$alloc_item->type, $alloc_item->type_no, $this->date_);

	 				update_debtor_trans_allocation($alloc_item->type, $alloc_item->type_no);
				}
				// Exchange Variations Joe Hunt 2008-09-20 ////////////////////
				exchange_variation($this->type, $this->trans_no,
					$alloc_item->type, $alloc_item->type_no, $this->date_,
					$payment, $this->person_type);

				//////////////////////////////////////////////////////////////
				$total_allocated += $alloc_item->current_allocated;
			}

		}  /*end of the loop through the array of allocations made */
		if ($this->person_type == PT_SUPPLIER)
			update_supp_trans_allocation($this->type, $this->trans_no);
		else
		 	update_debtor_trans_allocation($this->type,	$this->trans_no);

		commit_transaction();

	}

} 

//-----------------------------------------------------------------------------------

class allocation_item 
{

	var $type;
	var $type_no;
	
	var $date_;
	var $due_date;
	
	var $amount_allocated;
	var $payment;
	var $ref;
	
	var $current_allocated;
	
	function allocation_item ($type, $type_no, $date_, $due_date, $payment, 
		$amount_allocated, $current_allocated, $ref)
	{

		$this->type = $type;
		$this->type_no = $type_no;

		$this->ref = $ref;

		$this->date_ = $date_;
		$this->due_date = $due_date;
		
		$this->payment = $payment;
		$this->amount_allocated = $amount_allocated;
		$this->current_allocated = $current_allocated;
	}
}

//--------------------------------------------------------------------------------

function show_allocatable($show_totals) {

	global $systypes_array;
	
    $k = $counter = $total_allocated = 0;

	$cart = $_SESSION['alloc'];
	$supp_ref = in_array($cart->type, array(ST_SUPPCREDIT, ST_SUPPAYMENT, ST_BANKPAYMENT));

	if (count($cart->allocs)) 
	{
		if ($cart->currency != $cart->person_curr)
			display_heading(sprintf(_("Allocated amounts in %s:"), $cart->person_curr));
		start_table(TABLESTYLE, "width=60%");
   		$th = array(_("Transaction Type"), _("#"), $supp_ref ? _("Supplier Ref"): _("Ref"), _("Date"), _("Due Date"), _("Amount"),
   			_("Other Allocations"), _("Left to Allocate"), _("This Allocation"),'','');

	   	table_header($th);

		foreach ($cart->allocs as $id => $alloc_item)
		{
		    if (floatcmp(abs($alloc_item->payment), $alloc_item->amount_allocated))
		    {
				alt_table_row_color($k);
    			label_cell($systypes_array[$alloc_item->type]);
	   			label_cell(get_trans_view_str($alloc_item->type, $alloc_item->type_no));
		   		label_cell($alloc_item->ref);
    			label_cell($alloc_item->date_, "align=right");
    			label_cell($alloc_item->due_date, "align=right");
	    		amount_cell(abs($alloc_item->payment));
				amount_cell($alloc_item->amount_allocated);

		    	$_POST['payment' . $id] = price_format($alloc_item->current_allocated);

	    		$un_allocated = round((abs($alloc_item->payment) - $alloc_item->amount_allocated), 6);
	    		amount_cell($un_allocated, false,'', 'maxval'.$id);
    			amount_cells(null, "payment" . $id);//, input_num('amount' . $id));
				label_cell("<a href='#' name=Alloc$id onclick='allocate_all(this.name.substr(5));return true;'>"
					 . _("All") . "</a>");
				label_cell("<a href='#' name=DeAll$id onclick='allocate_none(this.name.substr(5));return true;'>"
					 . _("None") . "</a>".hidden("un_allocated" . $id, 
					 price_format($un_allocated), false));
				end_row();

   	    		$total_allocated += input_num('payment' . $id);
		   	}
		}
		if ($show_totals) {
    	   	label_row(_("Total Allocated"), price_format($total_allocated),
	    		"colspan=8 align=right", "align=right id='total_allocated'", 3);
/*
			$amount = $_SESSION['alloc']->amount;

			if ($_SESSION['alloc']->type == ST_SUPPCREDIT
				|| $_SESSION['alloc']->type == ST_SUPPAYMENT
				||  $_SESSION['alloc']->type == ST_BANKPAYMENT)
				$amount = -$amount;
*/
			$payment = abs($cart->payment);

			if (floatcmp($payment, $total_allocated) < 0)
	        {
        		$font1 = "<font color=red>";
        		$font2 = "</font>";
    	    }
	        else
        		$font1 = $font2 = "";
			$left_to_allocate = price_format($payment - $total_allocated);
	        label_row(_("Left to Allocate"), $font1 . $left_to_allocate . $font2, 
				"colspan=8 align=right", "nowrap align=right id='left_to_allocate'",
				 3);
		}
		end_table(1);
	}
	hidden('TotalNumberOfAllocs', count($cart->allocs));
}
//--------------------------------------------------------------------------------

function check_allocations()
{
	global $SysPrefs;

	$total_allocated = 0;

	for ($counter = 0; $counter < get_post("TotalNumberOfAllocs"); $counter++)
	{
		if (!isset($_POST['payment'.$counter])) continue;
		if (!check_num('payment' . $counter, 0))
		{
			display_error(_("The entry for one or more amounts is invalid or negative."));
			set_focus('payment'.$counter);
			return false;
		 }

		  /* Now check to see that the AllocAmt is no greater than the
		 amount left to be allocated against the transaction under review;
		 skip check if no allocation is set to avoid deadlock on mistakenly overallocated transactions*/
		 $allocated = input_num('payment' . $counter);
		 if ($allocated && ($allocated > input_num('un_allocated' . $counter)))
		 {
			display_error(_("At least one transaction is overallocated."));
			set_focus('payment'.$counter);
			return false;
		 }

		 $_SESSION['alloc']->allocs[$counter]->current_allocated = input_num('payment' . $counter);

		 $total_allocated += input_num('payment' . $counter);
	}
/*
	$amount = $_SESSION['alloc']->amount;

	if (in_array($_SESSION['alloc']->type, array(ST_BANKPAYMENT, ST_SUPPCREDIT, ST_SUPPAYMENT)))
		$amount = -$amount;
*/
	$payment = abs($_SESSION['alloc']->payment);

	if ($total_allocated - ($payment + input_num('discount'))  > $SysPrefs->allocation_settled_allowance())
	{
		display_error(_("These allocations cannot be processed because the amount allocated is more than the total amount left to allocate."));
		return false;
	}

	return true;
}

class gl_codes 
{

	var $Counter;
	var $gl_code;
	var $gl_act_name;
	var $gl_dim;
	var $gl_dim2;
	var $amount;
	var $memo_;

	function gl_codes ($Counter, $gl_code, $gl_act_name, $gl_dim, $gl_dim2, $amount, $memo_)
	{

		$this->Counter = $Counter;
		$this->gl_code = $gl_code;
		$this->gl_act_name = $gl_act_name;
		$this->gl_dim = $gl_dim;
		$this->gl_dim2 = $gl_dim2;
		$this->amount = $amount;
		$this->memo_= $memo_;
	}
}

?>


