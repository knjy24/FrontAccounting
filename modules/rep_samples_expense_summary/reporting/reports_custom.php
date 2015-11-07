<?php

global $reports, $dim;
			
$reports->addReport(RC_INVENTORY,"_samples_expense_summary",_('&Samples Expense Summary Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Inventory Category') => 'CATEGORIES',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));				
?>
