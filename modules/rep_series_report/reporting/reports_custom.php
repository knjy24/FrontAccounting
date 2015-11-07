<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_series_report",_('&Series Report'),
	array(	_('Salesman/IMC') => 'SALESMAN',
			_('Start Date') => 'DATEBEGINM',
			 _('End Date') => 'DATEENDM',
			 _('Status') => 'STATUS',
			_('Destination') => 'DESTINATION'));		
?>	
