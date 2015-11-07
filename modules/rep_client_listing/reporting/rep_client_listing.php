<?php
$page_security = 'SA_SALESANALYTIC';
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");


print_royalty_sales($array);

function getList($imc)
{
	$sql = "Select b.*, c.*, d.* FROM ".TB_PREF."cust_branch b  INNER JOIN
".TB_PREF."crm_contacts c on b.debtor_no=c.entity_id INNER JOIN ".TB_PREF."crm_persons d on c.person_id=d.id where c.type='customer'";

	if ($imc != 0)
			$sql .= " AND b.salesman =".db_escape($imc);

	$sql .= " ORDER BY b.salesman";

	return db_query($sql, "Error getting order details");

}

function print_royalty_sales()
{
    $imc = $_POST['PARAM_0'];
    $destination = $_POST['PARAM_2'];
    $orientation = $_POST['PARAM_1'];

    
    global $path_to_root, $systypes_array;

    if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");


    $params =   array(  0 => $comments,
                        1 => array('text' => _('Item'), 'from' => $name));

	$orientation = ($orientation ? 'L' : 'P');
    $dec = user_price_dec();
    $cols = array(0, 100, 150,	250, 300, 350, 400, 450, 520);

    $headers = array(_('IMC/Client'),  _('Address'), '', _('Contact Person'), '', _('Contact No.'));

    $aligns = array('left',	'left',	'left', 'left', 'left', 'left',	'left', 'left', 'left');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
    
    $rep = new FrontReport(_('Client Listing'), "Client Listing", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);

    $rep->SetHeaderType('Header');
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->NewPage();

 
    
   $salesman = 0;

	$result = getList($imc);

	while ($myrow=db_fetch($result))
	{
		$previous == '';
		$salesman = get_salesman_name($myrow['salesman']);
		$current = $salesman;

		if ($salesman != "")
		{
			if ($previous == $current) {
				$salesman = '';
				$name = $myrow['name']." ".$myrow['name2'];
				$rep->TextCol(0,2, $myrow['br_name']);
				$rep->NewLine();
				$rep->TextCol(1,7, "Address: ".$myrow['br_address']);
				$rep->TextCol(7,15, "Contact Person: ".$name);
				$rep->NewLine();
				if ($myrow['phone'] != NULL && $myrow['phone2'] != NULL)
					$rep->TextCol(1,5, "Contact Nos. ". $myrow['phone']. "/" .$myrow['phone2']);
				if ($myrow['phone2'] == NULL )
					$rep->TextCol(1,5, "Contact Nos. ". $myrow['phone']);
				if ($myrow['fax'] != NULL)
					$rep->TextCol(6, 8, "Fax".$myrow['fax']);
				if ($myrow['email'] != NULL)
					$rep->TextCol(9, 15, "Email".$myrow['email']);
				$rep->NewLine();
			} else {
				$name = $myrow['name']." ".$myrow['name2'];
				$rep->Font('bold');
				$rep->NewLine(2);
				$rep->Line($rep->row  + 10);
				$rep->TextCol(0,10, $salesman);
				$rep->Line($rep->row  - 4);
				$rep->NewLine(2);
				$rep->Font();
				$rep->TextCol(0,2, $myrow['br_name']);
				$rep->NewLine();
				$rep->TextCol(1,7, "Address: ".$myrow['br_address']);
				$rep->TextCol(7,15, "Contact Person: ".$name);
				$rep->NewLine();
				if ($myrow['phone'] != NULL && $myrow['phone2'] != NULL)
					$rep->TextCol(1,5, "Contact Nos. ". $myrow['phone']. "/" .$myrow['phone2']);
				if ($myrow['phone2'] == NULL )
					$rep->TextCol(1,5, "Contact Nos. ". $myrow['phone']);
				if ($myrow['fax'] != NULL)
					$rep->TextCol(6, 8, "Fax".$myrow['fax']);
				if ($myrow['email'] != NULL)
					$rep->TextCol(9, 15, "Email".$myrow['email']);
				$rep->NewLine();
			}

			$previous = $current;
		}
		
	}
    $rep->NewLine();

  

    
    $rep->End();
}




?>