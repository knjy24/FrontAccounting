<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_cash_sales",_('&Cash Sales Report'),
	array( _('Start Date') => 'DATEBEGINM',
		_('End Date') => 'DATEENDM',
		_('Destination') => 'DESTINATION'));				
?>
