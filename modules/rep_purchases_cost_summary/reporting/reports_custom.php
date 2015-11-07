<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_purchases_cost_summary",_('&Summary of Purchases at Cost Report'),
		array(	_('Start Date') => 'DATEBEGINM',
			_('Orientation') => 'ORIENTATION',
			_('Destination') => 'DESTINATION'));				
?>
