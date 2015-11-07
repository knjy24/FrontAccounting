<?php

global $reports, $dim;
			
$reports->addReport(RC_INVENTORY,"_royalty_sales",_('&Royalty Sales Report'),
	array(_('Start Date') => 'DATEBEGINM',
		_('End Date') => 'DATEENDM',
		_('Book') => 'ITEMS_COST',
		_('Status') => 'STATUS',
		_('Destination') => 'DESTINATION',
		_('Orientation') => 'ORIENTATION'));		
?>
