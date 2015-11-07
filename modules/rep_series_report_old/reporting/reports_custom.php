<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_series_report",_('&Series Report'),
	array(_('Start Date') => 'DATEBEGINM',
			_('Destination') => 'DESTINATION'));		
?>
