<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_sales_remittance",_('&Sales Remittance Report'),
	array(  _('Salesman/IMC') => 'SALESMAN',	
		_('Start Date') => 'DATEBEGINM',
		_('Destination') => 'DESTINATION'));				
?>
