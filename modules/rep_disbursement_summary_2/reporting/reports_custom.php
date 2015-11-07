<?php

global $reports, $dim;
			
$reports->addReport(RC_GL,"_disbursement_summary",_('&Cash Disbursement Summary Report'),
	array(_('Start Date') => 'DATEBEGINM',
		_('End Date') => 'DATEENDM',
			_('Destination') => 'DESTINATION'));		
?>
