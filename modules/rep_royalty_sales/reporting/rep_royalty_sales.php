<?php
$page_security = 'SA_SALESALLOC';
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");


print_royalty_sales($array);

function get_code($id)
{
    $sql = "SELECT imc_code from ".TB_PREF."salesman where salesman_code =".db_escape($id)."";
    $result = db_query($sql, "Could not retrieve salesman code");
    $row = db_fetch($result);
    return $row[0];
}
function fetchItemCode()
{
    $sql = "SELECT a.stock_id from ".TB_PREF."stock_master a INNER JOIN ".TB_PREF."stock_category b
            on a.category_id=b.category_id where a.mb_flag='M' order by b.category_id, a.stock_id";
    return db_query($sql, "");
}
function print_royalty_sales()
{
    $from = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $item = $_POST['PARAM_2'];
    $status = $_POST['PARAM_3'];
    $destination = $_POST['PARAM_4'];
    $orientation = $_POST['PARAM_5'];

    
    global $path_to_root, $systypes_array;

    if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $result = fetchRoyalty($item);

    $name = "";

    while ($book = db_fetch($result)){
        $name = $book[1];
    }

    $params =   array(  0 => $comments,
                        1 => array('text' => _('Item'), 'from' => $name));

	$orientation = ($orientation ? 'L' : 'P');
    $dec = user_price_dec();
    $cols = array(0, 200, 210, 300, 310, 360, 380, 430, 440, 490, 500, 550);

    $headers = array(_('Client'), '', _('IMC'), '', _('Invoice/CM #'), '', _('Date'), '', _('Quantity'), '', _('Status'));

    $aligns = array('left', 'left', 'left', 'left', 'right', 'right');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
    
    $rep = new FrontReport(_('Royalty Sales'), "RoyaltySales", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);

    $rep->SetHeaderType('Header');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();

   if ($item == ''){
        $no_title_print = false;
        $enter = true;
   }
   else
   {
        $no_title_print = true;
        $enter = false;
   }

    if ($no_title_print){
        $rep->Font('bold');
        $rep->TextCol(0, 1, $item);
        $rep->TextCol(2, 10, fetchTitle($item));
        $rep->Font();
    }
    $total = 0;
    $total_invoice_qty = 0;
    $total_credit_qty = 0;

    $rep->NewLine();
    if ($item == "")
    {
        $code = array();
        $ItemCode = fetchItemCode();
        while($r=db_fetch($ItemCode))
        {
            array_push($code, $r[0]);
        }
        $total  = 0;
        $total_invoice_qty = 0;
        $total_credit_qty = 0;
        foreach($code as $codes)
        {
            $result = fetchRoyalty($codes, $status, $from, $to); 
            $bookname = fetchTitle($codes);
            $rep->Font('bold');
            $rep->TextCol(0,5, $codes."-".$bookname);
            $rep->Font();
            $rep->NewLine();
            while ($myrow = db_fetch($result)) 
            {
                if ($myrow['alloc'] == $myrow['ov_amount'])
                    $stat = 'Closed';
                 if ($myrow['alloc'] < $myrow['ov_amount']) 
                    $stat = 'Open';

                $scode = get_code($myrow['salesman']);    
                $salesman = get_salesman_name($myrow['salesman']);
                $total_invoice_qty += $myrow[3];
                $clientName = get_customer_name($myrow[0]);
                $rep->TextCol(0, 1, $clientName);
                $rep->TextCol(2, 3, $scode);
                $rep->TextCol(4, 5, '  '.$myrow['customized_no']);
                $rep->TextCol(6, 7, $myrow['tranDate']);
                $rep->TextCol(8, 9, $myrow['quantity']);
                $rep->TextCol(10, 11, $stat);
                 $rep->NewLine();
            }

            $result1 = fetchCreditMemo($codes, $status, $from, $to);
            while ($myrow1 = db_fetch($result1)) 
            {
                if ($myrow1[3] > 0) 
                {
                     if ($myrow1['alloc'] == $myrow1['ov_amount'])
                        $stat = 'Closed';
                     if ($myrow1['alloc'] < $myrow1['ov_amount']) 
                        $stat = 'Open';
                }

                $qty = -$myrow1['quantity'];
                $total_credit_qty += $qty;
                $scode = get_code($myrow1['salesman']);    
                $clientName2 = get_customer_name($myrow1[0]);
                $rep->TextCol(0, 1, $clientName2);
                $rep->TextCol(2,3, $scode);
                $rep->TextCol(4, 5, '  '.$myrow1['customized_no']);
                $rep->TextCol(6, 7, $myrow1['tranDate']);
                $rep->TextCol(8, 9, $qty);
                $rep->TextCol(10, 11, $stat);
                $rep->NewLine();
            }

            $rep->NewLine(1);
            $total = $total_invoice_qty + $total_credit_qty;
            $rep->Font('bold');
            $rep->TextCol(6,7, _("Total"));
            $rep->TextCol(8,9, $total);
            $rep->Line($rep->row + 10);
            $rep->Font();
            $rep->NewLine();
            $total = 0;
        $total_invoice_qty = 0;
        $total_credit_qty = 0;
        }
    }
    else 
    {
        $result = fetchRoyalty($item, $status, $from, $to); 

        while ($myrow = db_fetch($result)) 
        {
            if ($myrow['alloc'] == $myrow['ov_amount'])
                $stat = 'Closed';
             if ($myrow['alloc'] < $myrow['ov_amount']) 
                $stat = 'Open';

            $scode = get_code($myrow['salesman']);    
            $salesman = get_salesman_name($myrow['salesman']);
            $total_invoice_qty += $myrow[3];
            $clientName = get_customer_name($myrow[0]);
            $rep->TextCol(0, 1, $clientName);
            $rep->TextCol(2, 3, $scode);
            $rep->TextCol(4, 5, '  '.$myrow['customized_no']);
            $rep->TextCol(6, 7, $myrow['tranDate']);
            $rep->TextCol(8, 9, $myrow['quantity']);
            $rep->TextCol(10, 11, $stat);
             $rep->NewLine();
        }

        $result1 = fetchCreditMemo($item, $status, $from, $to);
        while ($myrow1 = db_fetch($result1)) 
        {
            if ($myrow1[3] > 0) 
            {
                if ($myrow1['alloc'] == $myrow1['ov_amount'])
                    $stat = 'Closed';
                if ($myrow1['alloc'] < $myrow1['ov_amount']) 
                    $stat = 'Open';

                $qty = -$myrow1['quantity'];
                $total_credit_qty += $qty;
                $scode = get_code($myrow1['salesman']);    
                $clientName2 = get_customer_name($myrow1[0]);
                $rep->TextCol(0, 1, $clientName2);
                $rep->TextCol(2,3, $scode);
                $rep->TextCol(4, 5, '  '.$myrow1['customized_no']);
                $rep->TextCol(6, 7, $myrow1['tranDate']);
                $rep->TextCol(8, 9, $qty);
                $rep->TextCol(10, 11, $stat);
                $rep->NewLine();
            }
        }
        $rep->NewLine(1);
        $total = $total_invoice_qty + $total_credit_qty;
        $rep->Font('bold');
        $rep->TextCol(6,7, _("Total"));
        $rep->TextCol(8,9, $total);
        $rep->Line($rep->row + 10);
        $rep->NewLine();
        $total = 0;
        $total_invoice_qty = 0;
        $total_credit_qty = 0;
    } 
    $rep->End();    
}


function fetchRoyalty($id, $status, $from, $to){
    $fromdate = date2sql($from);
    $todate = date2sql($to);

    $sql = "SELECT b.debtor_no, a.customized_no, DATE_FORMAT(b.tran_date, '%m-%d-%Y') as tranDate, c.quantity, c.stock_id, c.description, b.ov_amount, b.alloc, d.salesman
            FROM ".TB_PREF."customized as a 
    		INNER JOIN ".TB_PREF."debtor_trans as b ON a.type_no = b.trans_no 
    		INNER JOIN ".TB_PREF."debtor_trans_details as c ON b.trans_no = c.debtor_trans_no 
    		INNER JOIN ".TB_PREF."cust_branch as d ON b.debtor_no = d.debtor_no 
            INNER JOIN ".TB_PREF."stock_master stock on stock.stock_id=c.stock_id
            INNER JOIN ".TB_PREF."stock_category cat on cat.category_id=stock.category_id

    			WHERE a.type = ".ST_SALESINVOICE." AND b.type = ".ST_SALESINVOICE." AND c.debtor_trans_type = ".ST_SALESINVOICE." and b.tran_date >= '$fromdate' and b.tran_date <='$todate'";
    if ($id != '')
    	$sql .= " AND c.stock_id =".db_escape($id);

    if ($status == 1)
        $sql .= " AND b.ov_amount = b.alloc";

    if ($status == 2)
        $sql .= " AND b.ov_amount > b.alloc";

    $sql .= " ORDER BY d.salesman";

    return db_query($sql, 'error');
}

function fetchCreditMemo($id, $status, $from, $to){
    $fromdate = date2sql($from);
    $todate = date2sql($to);
    $sql = "SELECT b.debtor_no, a.customized_no, DATE_FORMAT(b.tran_date, '%m-%d-%Y') as tranDate, c.quantity, c.stock_id, c.description, b.ov_amount, b.alloc, d.salesman
             FROM ".TB_PREF."customized as a 
    		INNER JOIN ".TB_PREF."debtor_trans as b ON a.type_no = b.trans_no 
    		INNER JOIN ".TB_PREF."debtor_trans_details as c ON b.trans_no = c.debtor_trans_no 
    		INNER JOIN ".TB_PREF."cust_branch as d ON b.debtor_no = d.debtor_no 
             INNER JOIN ".TB_PREF."stock_master stock on stock.stock_id=c.stock_id
            INNER JOIN ".TB_PREF."stock_category cat on cat.category_id=stock.category_id
            WHERE a.type = ".ST_CUSTCREDIT." AND b.type = ".ST_CUSTCREDIT." AND c.debtor_trans_type = ".ST_CUSTCREDIT." and b.tran_date >= '$fromdate' and b.tran_date <='$todate'";

    if ($id != '')
    	$sql .= " AND c.stock_id =".db_escape($id);

    if ($status == 1)
        $sql .= " AND b.ov_amount = b.alloc";

    if ($status == 2)
        $sql .= " AND b.ov_amount > b.alloc";

     $sql .= " ORDER BY d.salesman";

    return db_query($sql, 'error');
}

function fetchTitle($id){
    $catcher = "";
    $sql = "SELECT description from ".TB_PREF."item_codes";

    if ($id != '')
    	$sql .= " WHERE stock_id = ".db_escape($id);
    $result = db_query($sql, 'Error');
    while ($myrow = db_fetch($result)){
        $catcher = $myrow[0];
    }
    return $catcher;
}

?>