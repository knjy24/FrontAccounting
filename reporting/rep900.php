<?php

$page_security = 'SA_SALESALLOC';
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

//----------------------------------------------------------------------------------------------------
print_provisional_receipt();

function print_provisional_receipt(){
    $array = array();
    $catch = "";
    $catch = $_POST['PARAM_0'];
    $array = explode("/", $catch);

    global $path_to_root, $systypes_array;

    if ($destination)
        include_once($path_to_root . "/reporting/includes/excel_report.inc");
    else
        include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $orientation = ($orientation ? 'L' : 'P');
    $dec = user_price_dec();
    $cols = array();

    for ($i = 0; $i <= 400; $i++){
       array_push($cols, $i);
    }
    $headers = array(_('Date'), _('Time'), _('User'), _('Trans Date'),
        _('Type'), _('#'), _('Action'), _('Amount'));

    $aligns = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'right');

    $usr = get_user($user);
    $user_id = $usr['user_id'];
    
    $rep = new FrontReport(_('Provisional Receipt'), "ProvisionalReceipt", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
        recalculate_cols($cols);

    $rep->SetHeaderType('Header0');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();
    $res = get_fields($array[0], $array[1], $array[2]);
    while($myrow = db_fetch($res)){
        $rep->NewLine();
        $rep->NewLine();
        $rep->NewLine();
        $rep->NewLine();
        $rep->NewLine();
        $rep->TextCol(399,0, "No: ".$myrow[6]);
        $rep->NewLine();
        $rep->TextCol(399,1, $myrow[2]);
        $rep->NewLine();
        $rep->NewLine();
        $rep->NewLine();

        $rep->TextCol(50,200, "Received from " . $myrow[1]);
        for ($loop = 110; $loop <= 399; $loop++){
            $rep->UnderlineCell($loop,0);
        }

        $rep->NewLine();
        
        $rep->TextCol(50,160, "with TIN " );
        for ($loop = 85; $loop <= 160; $loop++){
            $rep->UnderlineCell($loop,0);
        }

        $rep->TextCol(161,300, "And address at " . $myrow[3]);
        for ($loop = 222; $loop <= 399; $loop++){
            $rep->UnderlineCell($loop,0);
        }

        $rep->NewLine();
        
        $rep->TextCol(50,300, "Engaged in the business style of ");
        for ($loop = 180; $loop <= 399; $loop++){
            $rep->UnderlineCell($loop,0);
        }

        $rep->NewLine(1);
        $word = price_in_words($myrow[4],ST_CUSTPAYMENT);
        $rep->TextCol(50, 350, "the sum of " . $word);
        for ($loop = 95; $loop <= 369; $loop++){
            $rep->UnderlineCell($loop,0);
        }
        $rep->TextCol(375, 400, "Pesos ");
        
        $rep->NewLine();
        
        $rep->TextCol(50,120, "(P ". $myrow[4] . "                  )");
        for ($loop = 57; $loop <= 115; $loop++){
            $rep->UnderlineCell($loop,0);
        }

        $word2 = price_in_words($myrow[5],ST_CUSTPAYMENT);
        

        $rep->TextCol(121,350, "in partial/full payment of " . $word2);
        for ($loop = 217; $loop <= 399; $loop++){
            $rep->UnderlineCell($loop,0);
        }
        
        $rep->NewLine();
        $rep->NewLine();
       
        $rep->NewLine();
        if (array_pop($array) == 'collection'){
            $rep->TextCol(265, 399, "By:");
            $rep->NewLine();
            
            for ($loop = 275; $loop <= 399; $loop++){
                $rep->UnderlineCell($loop,0);
            }
            $rep->NewLine();
            $rep->TextCol(275, 399, "Cashier/Authorized Representative");
        }
        else {
            for ($loop = 300; $loop <= 399; $loop++){
                $rep->UnderlineCell($loop,0);
            }
            $rep->NewLine();
        
            $rep->TextCol(320, 399, "IMC/Collector");
            $rep->NewLine();
            $rep->TextCol(300, 399, "Cashier's Name and Code");
        }
        $rep->NewLine();
        $rep->NewLine();
        }   
   
    $rep->End();
    }

function get_fields($id, $type, $no = NULL){
    $sql = "SELECT b.debtor_no, b.name, DATE_FORMAT(NOW(), '%m/%d/%y'), b.address, a.alloc, a.ov_amount, c.no FROM ".TB_PREF."debtors_master as b INNER JOIN ".TB_PREF."debtor_trans as a ON b.debtor_no = a.debtor_no INNER JOIN ".TB_PREF."cust_alloc_details as c ON a.trans_no = c.details_id WHERE a.trans_no = ".db_escape($id)." and a.type = ".db_escape($type)." AND c.no = ".db_escape($no)." ";
    return db_query($sql, 'Error');
    }
?>