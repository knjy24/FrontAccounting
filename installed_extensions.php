<?php

/* List of installed additional extensions. If extensions are added to the list manually
	make sure they have unique and so far never used extension_ids as a keys,
	and $next_extension_id is also updated. More about format of this file yo will find in 
	FA extension system documentation.
*/

$next_extension_id = 16; // unique id for next installed extension

$installed_extensions = array (
  1 => 
  array (
    'package' => 'rep_client_listing',
    'name' => 'rep_client_listing',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_client_listing',
    'active' => false,
  ),
  2 => 
  array (
    'name' => 'Annual balance breakdown report',
    'package' => 'rep_annual_balance_breakdown',
    'version' => '2.3.0-1',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/rep_annual_balance_breakdown',
  ),
  3 => 
  array (
    'name' => 'Annual expense breakdown report',
    'package' => 'rep_annual_expense_breakdown',
    'version' => '2.3.0-1',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/rep_annual_expense_breakdown',
  ),
  4 => 
  array (
    'name' => 'Cash Flow Statement Report',
    'package' => 'rep_cash_flow_statement',
    'version' => '2.3.0-1',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/rep_cash_flow_statement',
  ),
  5 => 
  array (
    'package' => 'rep_cash_sales',
    'name' => 'rep_cash_sales',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_cash_sales',
    'active' => false,
  ),
  6 => 
  array (
    'package' => 'rep_customer_account_statement',
    'name' => 'rep_customer_account_statement',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_customer_account_statement',
    'active' => false,
  ),
  7 => 
  array (
    'package' => 'rep_disbursement_summary',
    'name' => 'rep_disbursement_summary',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_disbursement_summary',
    'active' => false,
  ),
  8 => 
  array (
    'package' => 'rep_disbursement_summary_sample',
    'name' => 'rep_disbursement_summary_sample',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_disbursement_summary_sample',
    'active' => false,
  ),
  9 => 
  array (
    'package' => 'rep_royalty_sales',
    'name' => 'rep_royalty_sales',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_royalty_sales',
    'active' => false,
  ),
  10 => 
  array (
    'package' => 'rep_sales_remittance',
    'name' => 'rep_sales_remittance',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_sales_remittance',
    'active' => false,
  ),
  11 => 
  array (
    'package' => 'rep_sales_status',
    'name' => 'rep_sales_status',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_sales_status',
    'active' => false,
  ),
  12 => 
  array (
    'package' => 'rep_series_report',
    'name' => 'rep_series_report',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_series_report',
    'active' => false,
  ),
  13 => 
  array (
    'package' => 'payroll',
    'name' => 'payroll',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/payroll',
    'active' => false,
  ),
  14 => 
  array (
    'name' => 'Company Dashboard',
    'package' => 'dashboard',
    'version' => '2.3.15-5',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/dashboard',
  ),
  15 => 
  array (
    'package' => 'rep_recapitulation',
    'name' => 'rep_recapitulation',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/rep_recapitulation',
    'active' => false,
  ),
);
?>