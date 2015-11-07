<?php

global $reports, $dim;
			
$reports->addReport(RC_INVENTORY,"_purchases_cost_summary",_('&Summary of Purchases at Cost Report'),
		array(	_('Start Date') => 'DATEBEGINM',
				_('End Date') => 'DATEENDM',			
				_('Destination') => 'DESTINATION',
				_('Orientation') => 'ORIENTATION'));				
	?>
