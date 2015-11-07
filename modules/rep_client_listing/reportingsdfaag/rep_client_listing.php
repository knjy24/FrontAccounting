<?php

$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------

// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");

//----------------------------------------------------------------------------------------------------

print_summary();

function getList($imc)
{
	$sql = "SELECT a.* from ".TB_PREF."cust_branch a";

	if ($imc != 0)
			$sql .= " where salesman =".db_escape($imc);

	$sql .= " ORDER BY a.salesman";

	return db_query($sql, "Error getting order details");

}

//----------------------------------------------------------------------------------------------------

function print_summary()
{
    global $path_to_root;

	$imc = $_POST['PARAM_0'];
	$comments = $_POST['PARAM_1'];
	$orientation = $_POST['PARAM_2'];
	$destination = $_POST['PARAM_3'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");
	$orientation = ($orientation ? 'L' : 'P');

	$dec = user_price_dec();

	$cols = array(0, 10, 100, 150, 200,	250, 300, 350, 400, 450, 520);

	//$headers = array(_('IMC'));

	$aligns = array('left',	'left',	'left', 'left', 'left', 'left',	'left', 'left', 'left');

    //$params =   array( 	0 => $comments,	1 => array(  'text' => _('Period'), 'from' => $from));

	$aligns2 = $aligns;

	$summary = 1;

	$rep = new FrontReport(_('Client List'), "ClientList", user_pagesize(), 9, $orientation);
    if ($orientation == 'L')
    	recalculate_cols($cols);
	$cols2 = $cols;
	$rep->Font();
	$rep->Info(null, $cols, null, $aligns);

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
				$rep->TextCol(1,10, $myrow['br_name']);
				$rep->NewLine();
			} else {
				$rep->Font('bold');
				$rep->NewLine(2);
				$rep->Line($rep->row  + 10);
				$rep->TextCol(0,2, $salesman);
				$rep->Line($rep->row  - 4);
				$rep->NewLine(2);
				$rep->Font();
				$rep->TextCol(1,10, $myrow['br_name']);
				$rep->NewLine();
			}

			$previous = $current;
		}
		
	}

	$rep->NewLine();
	$rep->End();
}

?>