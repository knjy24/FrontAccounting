<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_sales_status",_('&Sales Status Report'),
	array( 	_('Salesman/IMC') => 'SALESMAN',	
			_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Status') => 'STATUS',
			_('Orientation') => 'ORIENTATION',
			_('Destination') => 'DESTINATION'));				
?>
