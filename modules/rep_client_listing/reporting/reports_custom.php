<?php

global $reports, $dim;
			
$reports->addReport(RC_CUSTOMER,"_client_listing",_('&Client Listing'),
	array(	_('Salesman/IMC') => 'SALESMAN',
			_('Orientation') => 'ORIENTATION',
			_('Destination') => 'DESTINATION'));				
?>