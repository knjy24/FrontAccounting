<?php

// Link to printing single document with bulk report template file.
// Ex. label_cell(print_document_link($myrow['order_no'], _("Print")), $type);
// or display_note(print_document_link($order_no, _("Print this order")));
// You only need full parameter list for invoices/credit notes

function print_document_link($doc_no, $link_text, $link=true, $type_no, 
	$icon=false, $class='printlink', $id='', $email=0, $extra=0)
{
	global $path_to_root, $def_print_orientation;
	include_once($path_to_root . "/../includes/types.inc");

	$url = $path_to_root.'/../reporting/prn_redirect.php?';
	$def_orientation = (isset($def_print_orientation) && $def_print_orientation == 1 ? 1 : 0);

	switch ($type_no)
	{
		/*case ST_JOURNAL :
			$rep = 711;
				$ar = array(
				'PARAM_0' => $doc_no[0],
				'PARAM_1' => $doc_no[1],
				'PARAM_2' => $doc_no[2],
				'PARAM_3' => $doc_no[3],
				'PARAM_4' => $def_orientation);
			
			break;
		case ST_PAYROLL :
			$rep = 801;
			// from, to, comments, orientation
			$ar = array(
				'PARAM_0' => $doc_no,
				'PARAM_1' => $doc_no,
				'PARAM_2' => $def_orientation); 
			break;*/

		case ST_ROYALTIES :
			$rep = 115;

			$ar = array(
				'PARAM_0' => $doc_no,
				'PARAM_1' => $doc_no,
				'PARAM_2' => $def_orientation);
			break;
			
//		default: $ar = array();
	}
	
	return print_link($link_text, $rep, $ar, "", $icon, $class, $id);
}
//
//	Universal link to any kind of report.
//
function print_link($link_text, $rep, $pars = array(), $dir = '', 
	$icon=false, $class='printlink', $id='')
{
	global $path_to_root, $pdf_debug;

	$url = $dir == '' ?  $path_to_root.'/reporting/prn_redirect.php?' : $dir;

	$id = default_focus($id);
	foreach($pars as $par => $val) {
		$pars[$par] = "$par=".urlencode($val);
	}
	$pars[] = 'REP_ID='.urlencode($rep);
	$url .= implode ('&', $pars);

	if ($class != '')
		$class = $pdf_debug ? '' : " class='$class'";
	if ($id != '')
		$id = " id='$id'";
	$pars = access_string($link_text);
	if (user_graphic_links() && $icon)
		$pars[0] = set_icon($icon, $pars[0]);
	return "<a target='_blank' href='$url'$id$class $pars[1]>$pars[0]</a>";
}

?>