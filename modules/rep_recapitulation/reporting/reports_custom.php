<?php

global $reports, $dim;
			
$reports->addReport(RC_GL,"_recapitulation",_('&Cash Disbursement Recapitulation'),
	array(_('Start Date') => 'DATEBEGINM',
		_('End Date') => 'DATEENDM',
			_('Destination') => 'DESTINATION'));		
?>
