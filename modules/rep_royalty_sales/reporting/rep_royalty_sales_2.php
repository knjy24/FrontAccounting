<?php
$page_security = 'SA_SALESALLOC';
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

print_royalty_sales($array);


function print_royalty_sales()
{
    $from = $_POST['PARAM_0'];
    $item = $_POST['PARAM_1'];
    $status = $_POST['PARAM_2'];
    $destination = $_POST['PARAM_3'];
    $orientation = $_POST['PARAM_4'];

    
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
    $cols = array(0, 150, 250, 350, 450);

    $headers = array(_('Name'), _('ID #'), _('Date'), _('Quantity'), _('Status'));

    $aligns = array('left', 'left', 'left', 'left', 'left');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
    
    $rep = new FrontReport(_('Royalty Sales'), "RoyaltySales", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);

    $rep->SetHeaderType('Header');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();
    $rep->TextCol(0, 1, $item);
    $rep->TextCol(2, 4, fetchTitle($item));
    $rep->NewLine();
    $rep->NewLine();
    $result = fetchRoyalty($item);
    while ($myrow = db_fetch($result)) {
        $rep->TextCol(0, 1, $myrow[0]);
        $rep->TextCol(1, 2, '  '.$myrow[1]);
        $rep->TextCol(2, 3, $myrow[2]);
        $rep->TextCol(3, 4, $myrow[3]);
        $rep->TextCol(4, 5, 'Closed');
        $rep->NewLine();
    }

    $result1 = fetchCreditMemo($item);
    while ($myrow1 = db_fetch($result1)) {
    	if ($myrow1[3] > 0) {
    		$rep->TextCol(0, 1, $myrow1[0]);
	        $rep->TextCol(1, 2, '  '.$myrow1[1]);
	        $rep->TextCol(2, 3, $myrow1[2]);
	        $rep->TextCol(3, 4, '- '. $myrow1[3]);
	        $rep->TextCol(4, 5, 'Closed');
	        $rep->NewLine();
    	}
        
    }
    $rep->Line($rep->row  + 4);
    $rep->End();
}


function fetchRoyalty($id){

    $sql = "SELECT d.br_name, a.customized_no, b.tran_date, c.quantity, c.stock_id FROM ".TB_PREF."customized as a 
    		INNER JOIN ".TB_PREF."debtor_trans as b ON a.type_no = b.trans_no 
    		INNER JOIN ".TB_PREF."debtor_trans_details as c ON b.trans_no = c.debtor_trans_no 
    		INNER JOIN ".TB_PREF."cust_branch as d ON b.debtor_no = d.branch_code 
    			WHERE a.type = 10 AND b.type = 10 AND c.debtor_trans_type = 10 AND b.ov_amount = b.alloc";
    if ($id != '')
    	$sql .= " AND c.stock_id =".db_escape($id);
    return db_query($sql, 'error');
}

function fetchCreditMemo($id){
    $sql = "SELECT d.br_name, a.customized_no, b.tran_date, c.quantity, c.stock_id FROM ".TB_PREF."customized as a 
    		INNER JOIN ".TB_PREF."debtor_trans as b ON a.type_no = b.trans_no 
    		INNER JOIN ".TB_PREF."debtor_trans_details as c ON b.trans_no = c.debtor_trans_no 
    		INNER JOIN ".TB_PREF."cust_branch as d ON b.debtor_no = d.branch_code WHERE a.type = 11 AND b.type = 11 AND c.debtor_trans_type = 11 AND b.ov_amount = b.alloc";

    if ($id != '')
    	$sql .= " AND c.stock_id =".db_escape($id);
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