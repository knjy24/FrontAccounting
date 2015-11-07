<?php

global $reports;

$reports->addReport(RC_CUSTOMER,"_customer_account_statement",_('Customer Account Statement'),
	array(_('Start Date') => 'DATEBEGIN',
				('End Date') => 'DATE',
				_('Salesman/IMC') => 'SALESMAN',
			//_('Customer') => 'CUSTOMERS_NO_FILTER',
			_('Currency Filter') => 'CURRENCY',
			_('Email Customers') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Orientation') => 'ORIENTATION'));
