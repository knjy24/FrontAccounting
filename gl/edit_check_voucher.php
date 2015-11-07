<?php
$page_security = 'SA_JOURNALENTRY';
$path_to_root = "..";
include_once($path_to_root . "/includes/ui/items_cart.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/gl/includes/ui/disbursement_ui.inc");
include_once($path_to_root . "/gl/includes/gl_db_ml.inc");
include_once($path_to_root . "/gl/includes/gl_ui.inc");

/*===============MOODLEARNING====================*/
include_once($path_to_root . "/gl/ml/db/ml_gl_trans.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
/*========================================*/


$js = '';
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

 if(isset($_GET['ModifyDisbursement'])) {

	$_SESSION['page_title'] = _($help_context = "Edit Check Voucher");
	create_cart(ST_DISBURSEMENT, $_GET['trans_no']);
	
} 
page($_SESSION['page_title'], false, false, '', $js);

if (isset($_POST['Process']))
{

	$input_error = 0;


	if (!is_date($_POST['date_'])) 
	{
		display_error(_("The entered date is invalid."));
		set_focus('date_');
		$input_error = 1;
	} 
	elseif (!is_date_in_fiscalyear($_POST['date_'])) 
	{
		display_error(_("The entered date is not in fiscal year."));
		set_focus('date_');
		$input_error = 1;
	} 

	if ($input_error == 1)
		unset($_POST['Process']);
}

if (isset($_POST['Process']))
{
	$cart = &$_SESSION['disbursement_items'];
	$new = $cart->order_id == 0;

	$cart->reference = $_POST['ref'];
	$cart->memo_ = $_POST['memo_'];
	$cart->tran_date = $_POST['date_'];
	$cart->custom_no = $_POST['cv_no']; /*=======MOODLEARNING==============*/
	$cart->address = $_POST['address'];
	$cart->check_num = $_POST['check_num'];
	$cart->person_id = $_POST['PayType'];
	$cart->person_detail_id = $_POST['person_id'];
	$cart->settled_amount = input_num('settled_amount', null);



	$trans_no = update_disbursement_entries($cart, $_POST['tno']);

	$cart->clear_items();
	new_doc_date($_POST['date_']);
	unset($_SESSION['disbursement_items']);

		meta_forward("inquiry/custom_inquiry.php", "UpdatedID=$cart->custom_no");
}


function create_cart($type=0, $trans_no=0)
{
	global $Refs;

	if (isset($_SESSION['disbursement_items']))
	{
		unset ($_SESSION['disbursement_items']);
	}

	$cart = new items_cart($type);
    $cart->order_id = $trans_no;

	if ($trans_no) {
		
		$bank_trans = db_fetch(get_bank_trans($type, $trans_no));
		//$cart->address = $bank_trans['person_id'];
		$cart->person_id = $bank_trans["person_type_id"];
		$_POST['PayType'] = $cart->person_id;
		
		if ($bank_trans["person_type_id"] == PT_CUSTOMER)
		{
			//$trans = get_customer_trans($trans_no, $type);	
			//$_POST['person_id'] = $trans["debtor_no"];
			$cart->person_detail_id = $bank_trans["person_id"];
		}
		elseif ($bank_trans["person_type_id"] == PT_SUPPLIER)
		{
			$trans = get_supp_trans($trans_no, $type);
			$cart->person_detail_id = $trans["supplier_id"];
		}
		elseif ($bank_trans["person_type_id"] == PT_MISC)
		{
			$cart->person_detail_id = $bank_trans["person_id"];
			$cart->address = get_address($type, $trans_no);
		}
		elseif ($bank_trans["person_type_id"] == PT_IMC)
		{
			$cart->person_detail_id = $bank_trans["person_id"];
		}
		elseif ($bank_trans["person_type_id"] == PT_AUTHOR)
		{
			$cart->person_detail_id = $bank_trans["person_id"];
		}
		elseif ($bank_trans["person_type_id"] == PT_EMPLOYEE)
		{
			$cart->person_detail_id = $bank_trans["person_id"];
		}
		elseif ($bank_trans["person_type_id"] == PT_QUICKENTRY)
			$cart->person_detail_id = $bank_trans["person_id"];
		else 
			$cart->person_detail_id = $bank_trans["person_id"];

		$result = get_gl_trans($type, $trans_no);

		if ($result) {
			while ($row = db_fetch($result)) {
				if ($row['amount'] == 0) continue;
				$date = $row['tran_date'];
				$cart->add_gl_item($row['account'], $row['dimension_id'], 
					$row['dimension2_id'], $row['amount'], $row['memo_']);
			}
		}
		$cart->memo_ = get_comments_string($type, $trans_no);
		$cart->tran_date = sql2date($date);
		$cart->custom_no = get_jv_no($type, $trans_no);
		$cart->check_num = get_check_num($type, $trans_no);
		if ($type == ST_DISBURSEMENT)
		{
			$voucher_type = 'Check Voucher';
			$cart->reference = $Refs->get(ST_DISBURSEMENT, $trans_no);

		}
			
		//$_POST['ref_original'] = $cart->reference; // Store for comparison when updating
	} else {
		$cart->reference = $Refs->get_next(ST_DISBURSEMENT);
		$cart->tran_date = new_doc_date();
		if (!is_date_in_fiscalyear($cart->tran_date))
			$cart->tran_date = end_fiscalyear();
		//$_POST['ref_original'] = -1;
	}


	$_POST['memo_'] = $cart->memo_;
	$_POST['ref'] = $cart->reference;
	$_POST['date_'] = $cart->tran_date;
	$_POST['cv_no'] = $cart->custom_no; /**========MOODLEARNING=======*/
	$_POST['address'] = $cart->address; 
	$_POST['check_num'] = $cart->check_num;
	$_POST['PayType'] = $cart->person_id;
	$_POST['person_id'] = $cart->person_detail_id;
	$_POST['settled_amount'] = $cart->settled_amount;


	$_SESSION['disbursement_items'] = &$cart;
}


start_form();
global $Ajax, $Refs;
	$payment = $order->trans_type == ST_DISBURSEMENT;
	$voucher_type = "Check Voucher";

	$result = get_gl_trans($type, $trans_no);

	if ($result) {
			while ($row = db_fetch($result)) {
				if ($row['amount'] == 0) continue;
				$date = $row['tran_date'];
				$cart->add_gl_item($row['account'], $row['dimension_id'], 
					$row['dimension2_id'], $row['amount'], $row['memo_']);
			}
		}
	$customer_error = false;
	div_start('pmt_header');

	start_outer_table(TABLESTYLE2, "width=80%"); // outer table

	table_section(1);
	
    date_row(_("Date:"), 'date_', '', true, 0, 0, 0, null, true);

	ref_row(_("Reference:"), 'ref', '');

/*===============================MOODLEARNING===============================*/

		
		text_row(_("Check Voucher No."), 'cv_no');
		text_row(_("Check No."), 'check_num');
		hidden('tno', $_GET['trans_no']);

	
/*======================================================================================================*/
		
	
	table_section(2, "50%");

	if (!isset($_POST['PayType']))
	{
		if (isset($_GET['PayType']))
			$_POST['PayType'] = $_GET['PayType'];
		else
			$_POST['PayType'] = "";
	}
	if (!isset($_POST['person_id']))
	{
		if (isset($_GET['PayPerson']))
			$_POST['person_id'] = $_GET['PayPerson'];
		else
			$_POST['person_id'] = "";
	}
	if (isset($_POST['_PayType_update'])) {
		$_POST['person_id'] = '';
		$Ajax->activate('pmt_header');
		$Ajax->activate('code_id');
		$Ajax->activate('pagehelp');
		$Ajax->activate('editors');
		$Ajax->activate('footer');
	}
    payment_person_types_list_row( $payment ? _("Pay To:"):_("From:"),
		 'PayType', $_POST['PayType'], true);
    switch ($_POST['PayType'])
    {

		case PT_MISC :
    		text_row_ex($payment ?_("To the Order of:"):_("Name:"),
				 'person_id', 40, 50);
    		textarea_row(_("Address:"), 'address', '', 40,3); //moodlearning
    		break;
		//case PT_WORKORDER :
    	//	workorders_list_row(_("Work Order:"), 'person_id', null);
    	//	break;
		case PT_SUPPLIER :
    		supplier_list_row(_("Supplier:"), 'person_id', null, false, true, false, true);
    		break;
		case PT_CUSTOMER :
    		customer_list_row(_("Customer:"), 'person_id', null, false, true, false, true);
    	
        	if (db_customer_has_branches($_POST['person_id']))
        	{
        		customer_branches_list_row(_("Branch:"), $_POST['person_id'], 
					'PersonDetailID', null, false, true, true, true);
        	}
        	else
        	{
				$_POST['PersonDetailID'] = ANY_NUMERIC;
        		hidden('PersonDetailID');
        	}
        	$trans = get_customer_habit($_POST['person_id']); // take care of customers on hold
        	if ($trans['dissallow_invoices'] != 0)
        	{
        		if ($payment)
        		{
        			$customer_error = true;
					display_error(_("This customer account is on hold."));
        		}
        		else			
					display_warning(_("This customer account is on hold."));
        	}		
    		break;
		case PT_QUICKENTRY :
			quick_entries_list_row(_("Type").":", 'person_id', null, ($payment ? QE_PAYMENT : QE_DEPOSIT), true);
			$qid = get_quick_entry(get_post('person_id'));
			if (list_updated('person_id')) {
				unset($_POST['totamount']); // enable default
				$Ajax->activate('footer');
				$Ajax->activate('totamount');
			}
			amount_row($qid['base_desc'].":", 'totamount', price_format($qid['base_amount']),
				 null, "&nbsp;&nbsp;".submit('go', _("Go"), false, false, true));
			break;	

			case PT_EMPLOYEE :
    		employee_list_row(_("Employee:"), 'person_id', null, false, true, false, true);
    		break;
    		
			case PT_IMC :
    		//text_row("Sample", '');
    		sales_persons_list_row(_("IMC:"), 'person_id', null, false, true, false, true);
    		break;

    		case PT_AUTHOR :
    		author_list_row(_("Author:"), 'person_id', null, false, true, false, true);
    		break;

    		
		//case payment_person_types::Project() :
    	//	dimensions_list_row(_("Dimension:"), 'person_id', $_POST['person_id'], false, null, true);
    	//	break;
    }

	end_outer_table(1);

	div_end();
	if ($customer_error)
	{
		end_form();
		end_page();
		exit;
	}

br();

$result2 = get_gl_trans($_GET['type_id'], $_GET['trans_no']);

if (db_num_rows($result2) == 0)
{
    echo "<p><center>" . _("No general ledger transactions have been created for") . " " .$systypes_array[$_GET['type_id']]." " . _("number") . " " . $_GET['trans_no'] . "</center></p><br><br>";
	end_page(true);
	exit;
}

$th = array(_("Account Code"), _("Account Name"),
		_("Debit"), _("Credit"), _("Memo"));
$k = 0; //row colour counter
$heading_shown = false;

$credit = $debit = 0;


while($myrow=db_fetch($result2))
{
	if ($myrow['amount'] == 0) continue;
	if (!$heading_shown)
	{
	
		start_table(TABLESTYLE, "width=95%");
		table_header($th);
		$heading_shown = true;
	}	

	alt_table_row_color($k);
	
    label_cell($myrow['account']);
	label_cell($myrow['account_name']);
	if ($dim >= 1)
		label_cell(get_dimension_string($myrow['dimension_id'], true));
	if ($dim > 1)
		label_cell(get_dimension_string($myrow['dimension2_id'], true));

	display_debit_or_credit_cells($myrow['amount']);
	label_cell($myrow['memo_']);
	end_row();
    if ($myrow['amount'] > 0 ) 
    	$debit += $myrow['amount'];
    else 
    	$credit += $myrow['amount'];
}

if ($heading_shown)
{
    start_row("class='inquirybg' style='font-weight:bold'");
    label_cell(_("Total"), "colspan=2");
    if ($dim >= 1)
        label_cell('');
    if ($dim > 1)
        label_cell('');
    amount_cell($debit);
    amount_cell(-$credit);
    label_cell('');
    end_row();
	end_table(1);
}

//end of while loop

is_voided_display($_GET['type_id'], $_GET['trans_no'], _("This transaction has been voided."));


echo "<br><table align='center'>";

	  textarea_row(_("Memo"), 'memo_', null, 50, 3);

	  echo "</table>";
	  
br();
submit_center('Process', _("Process Check Voucher Entry"), true , 
	_('Process journal entry only if debits equal to credits'), 'default');

 // outer table
end_form();
//------------------------------------------------------------------------------------------------

end_page();

?>