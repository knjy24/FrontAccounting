-- phpMyAdmin SQL Dump
-- version 3.3.7deb3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2011 at 03:17 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7

-- --------------------------------------------------------

--
-- Table structure for table `0_areas`
--

DROP TABLE IF EXISTS `0_areas`;
CREATE TABLE IF NOT EXISTS `0_areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_areas`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_attachments`
--

DROP TABLE IF EXISTS `0_attachments`;
CREATE TABLE IF NOT EXISTS `0_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `unique_name` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `filename` varchar(60) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `filetype` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_no` (`type_no`,`trans_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_attachments`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_audit_trail`
--

DROP TABLE IF EXISTS `0_audit_trail`;
CREATE TABLE IF NOT EXISTS `0_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `user` smallint(6) unsigned NOT NULL DEFAULT '0',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` varchar(60) DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL,
  `gl_date` date NOT NULL DEFAULT '0000-00-00',
  `gl_seq` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Seq` (`fiscal_year`,`gl_date`,`gl_seq`),
  KEY `Type_and_Number` (`type`,`trans_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_audit_trail`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_accounts`
--

DROP TABLE IF EXISTS `0_bank_accounts`;
CREATE TABLE IF NOT EXISTS `0_bank_accounts` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_type` smallint(6) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(60) NOT NULL DEFAULT '',
  `bank_account_number` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(60) NOT NULL DEFAULT '',
  `bank_address` tinytext,
  `bank_curr_code` char(3) NOT NULL DEFAULT '',
  `dflt_curr_act` tinyint(1) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `last_reconciled_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending_reconcile_balance` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bank_account_name` (`bank_account_name`),
  KEY `bank_account_number` (`bank_account_number`),
  KEY `account_code` (`account_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_bank_accounts`


-- --------------------------------------------------------

--
-- Table structure for table `0_bank_trans`
--

DROP TABLE IF EXISTS `0_bank_trans`;
CREATE TABLE IF NOT EXISTS `0_bank_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `bank_act` varchar(15) NOT NULL DEFAULT '',
  `ref` varchar(40) DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) NOT NULL DEFAULT '0',
  `person_id` tinyblob,
  `reconciled` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_act` (`bank_act`,`ref`),
  KEY `type` (`type`,`trans_no`),
  KEY `bank_act_2` (`bank_act`,`reconciled`),
  KEY `bank_act_3` (`bank_act`,`trans_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_bank_trans`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_bom`
--

DROP TABLE IF EXISTS `0_bom`;
CREATE TABLE IF NOT EXISTS `0_bom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` char(20) NOT NULL DEFAULT '',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentre_added` int(11) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`parent`,`component`,`workcentre_added`,`loc_code`),
  KEY `component` (`component`),
  KEY `id` (`id`),
  KEY `loc_code` (`loc_code`),
  KEY `parent` (`parent`,`loc_code`),
  KEY `workcentre_added` (`workcentre_added`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_bom`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_budget_trans`
--

DROP TABLE IF EXISTS `0_budget_trans`;
CREATE TABLE IF NOT EXISTS `0_budget_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`),
  KEY `Account` (`account`,`tran_date`,`dimension_id`,`dimension2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_budget_trans`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_chart_class`
--

DROP TABLE IF EXISTS `0_chart_class`;
CREATE TABLE IF NOT EXISTS `0_chart_class` (
  `cid` varchar(3) NOT NULL,
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_chart_class`
--

INSERT INTO `0_chart_class` VALUES('1', 'Assets', 1, 0);
INSERT INTO `0_chart_class` VALUES('2', 'Liabilities', 2, 0);
INSERT INTO `0_chart_class` VALUES('3', 'Shareholders Equity', 3, 0);
INSERT INTO `0_chart_class` VALUES('4', 'Revenue/Gross Profit', 4, 0);
INSERT INTO `0_chart_class` VALUES('5', 'Operating Expenses', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_master`
--

DROP TABLE IF EXISTS `0_chart_master`;
CREATE TABLE IF NOT EXISTS `0_chart_master` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_code2` varchar(15) NOT NULL DEFAULT '',
  `account_name` varchar(60) NOT NULL DEFAULT '',
  `account_type` varchar(10) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_code`),
  KEY `account_name` (`account_name`),
  KEY `accounts_by_type` (`account_type`,`account_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_chart_master`
--

INSERT INTO `0_chart_master` VALUES('1-1010', '', 'Petty Cash Fund', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1015', '', 'Revolving Fund', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1020', '', 'CIB-Time Deposit (MBTC)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1025', '', 'CIB-Time Deposit (BPIFSB-67)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1030', '', 'CIB-MBTC CA (662-5)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1035', '', 'CIB-MBTC CA (982-5)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1040', '', 'CIB-BPI Family Savings Bank(67)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1045', '', 'CIB-BPIFSB (KN)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1050', '', 'CIB-BPI (53)', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1055', '', 'CIB-LAND BANK', '1', 0);
INSERT INTO `0_chart_master` VALUES('1-1060', '', 'CIB-BPIFSB Maxi Saver', '1', 0);

INSERT INTO `0_chart_master` VALUES('1-2010', '', 'Accounts Receivable-Trade (2010)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2015', '', 'Accounts Receivable-Trade (2011)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2020', '', 'Accounts Receivable-Trade (2012)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2025', '', 'Accounts Receivable-Trade (2013)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2030', '', 'Accounts Receivable-Trade (2014)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2031', '', 'Accounts Receivable-Trade (2015)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2035', '', 'Advances To Customers', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2040', '', 'Advances To Supplier', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2045', '', 'Advances To Officers & Employee', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2050', '', 'Cash Advance', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2055', '', 'Other Advances and Receivables', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2060', '', 'Cash Advance - OCDA', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2065', '', 'Other Receivables(Supplier)', '2', 0);
INSERT INTO `0_chart_master` VALUES('1-2070', '', 'Advances to Belray', '2', 0);

INSERT INTO `0_chart_master` VALUES('1-3010', '', 'Prepaid Car Insurance', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3015', '', 'Prepaid Building Insurance', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3020', '', 'Prepaid Health Insurance', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3025', '', 'Prepaid Rent', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3030', '', 'Creditable Withholding Taxes', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3035', '', 'City Tax', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3040', '', 'Deferred Tax Asset - MCIT', '3', 0);
INSERT INTO `0_chart_master` VALUES('1-3045', '', 'Creditable Withholding Tax Receivable', '3', 0);

INSERT INTO `0_chart_master` VALUES('1-4010', '', 'Merchandise Inventory', '4', 0);

INSERT INTO `0_chart_master` VALUES('1-5010', '', 'Refundable Deposit', '5', 0);

INSERT INTO `0_chart_master` VALUES('1-6010', '', 'Transportation Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6015', '', 'Furnitures Fixtures and OE', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6020', '', 'EDP Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6025', '', 'EDP Software', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6030', '', 'Telecommunications Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6035', '', 'Land', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6040', '', 'Land Improvement', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6045', '', 'Accumulated Depreciation-Transportation Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6050', '', 'Accumulated Depreciation-Furniture Fixtures and OE', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6055', '', 'Accumulated Depreciation-EDP Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6060', '', 'Accumulated Depreciation-EDP Software', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6065', '', 'Accumulated Depreciation-Telecommunications Equipment', '6', 0);
INSERT INTO `0_chart_master` VALUES('1-6070', '', 'Land Improvement', '6', 0);

INSERT INTO `0_chart_master` VALUES('2-1010', '', 'Income Tax Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1015', '', 'W/Tax Payable - Compensation', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1020', '', 'W/Tax Payable - Expanded', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1025', '', 'W/Tax Payable - Final', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1030', '', 'SSS and ECC Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1035', '', 'PHIC Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1040', '', 'HDMF Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1045', '', 'SSS Loan Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1050', '', 'HDMF Loan Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1055', '', 'Bank Revolving Line Pay', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1060', '', 'Deposit from Supplier', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1065', '', 'Accounts Payable', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1070', '', 'Advances from Customers', '7', 0);
INSERT INTO `0_chart_master` VALUES('2-1075', '', 'Other Accounts Payable', '7', 0);

INSERT INTO `0_chart_master` VALUES('2-2010', '', 'Notes Payable (Bank Loan - Veh)', '8', 0);
INSERT INTO `0_chart_master` VALUES('2-2015', '', 'Discount on Notes Payable', '8', 0);
INSERT INTO `0_chart_master` VALUES('2-3010', '', 'Advances from Officers', '8', 0);
INSERT INTO `0_chart_master` VALUES('2-3015', '', 'Advances from Belray', '8', 0);

INSERT INTO `0_chart_master` VALUES('3-1010', '', 'Capital Stock', '9', 0);
INSERT INTO `0_chart_master` VALUES('3-1015', '', 'Subscribed Capital Stock', '9', 0);
INSERT INTO `0_chart_master` VALUES('3-1020', '', 'Subscription Receivable', '9', 0);
INSERT INTO `0_chart_master` VALUES('3-1025', '', 'Additional Paid-in Capital', '9', 0);

INSERT INTO `0_chart_master` VALUES('3-1030', '', 'Retained Earnings', '10', 0);
INSERT INTO `0_chart_master` VALUES('3-1035', '', 'Prior Period Adjustments', '10', 0);

INSERT INTO `0_chart_master` VALUES('4-1010', '', 'Sales', '11', 0);
INSERT INTO `0_chart_master` VALUES('4-1015', '', 'Sales Discounts', '11', 0);
INSERT INTO `0_chart_master` VALUES('4-1020', '', 'Sales Returns and Allowances', '11', 0);

INSERT INTO `0_chart_master` VALUES('5-1010', '', 'COS - Purchases of Books', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1015', '', 'COS - Commission', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1020', '', 'COS - Salaries and Wages', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1025', '', 'COS - Royalty', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1030', '', 'COS - Freight', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1035', '', 'COS - Typesetting and Illus.', '12', 0);
INSERT INTO `0_chart_master` VALUES('5-1040', '', 'COS - Copyright', '12', 0);

INSERT INTO `0_chart_master` VALUES('6-1010', '', 'Salaries and Wages', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1015', '', 'Cost of Living Allowance', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1020', '', '13th Month Pay', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1025', '', 'Diminimis', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1030', '', 'Retirement Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1035', '', 'Directors Fee', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1040', '', 'SSS and ECC Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1045', '', 'PHIC Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1050', '', 'HDMF Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1055', '', 'Representation Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1060', '', 'Research and Development', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1065', '', 'Personnel Development', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1070', '', 'Transportation and Travel', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1075', '', 'Light and Water', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1080', '', 'Telecommunications', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1085', '', 'Repairs and Maintenance', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1090', '', 'Rent Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1095', '', 'Security Services-Security Guard Salaries', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1100', '', 'Security Services-Agency Fees', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1105', '', 'Insurance', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1110', '', 'Gasoline and Oil', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1115', '', 'Taxes License and Fees', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1120', '', 'Books and Subscriptions', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1125', '', 'Dep. Exp. - EDP Equipment', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1130', '', 'Dep. Exp. - EDP Software', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1135', '', 'Dep. Exp. - Office Equipment', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1140', '', 'Dep. Exp. - Tel. Equipment', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1145', '', 'Dep. Exp. - Transportation Equipment', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1150', '', 'Dep. Exp. - Land Improvement', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1155', '', 'Postage and Courier', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1160', '', 'Foreign Travel', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1165', '', 'Donations and Contributions', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1170', '', 'Doubtful Account Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1175', '', 'Stationery and Office Supplies', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1180', '', 'Loss on Inventory Writedown', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1185', '', 'Professional Fees', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1190', '', 'Membership and Association Dues', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1195', '', 'Seminars and Trainings', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1200', '', 'Miscellaneous', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1205', '', 'Freight', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1210', '', 'Meetings and Conference', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1215', '', 'Marketing Expense', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1220', '', 'Ads and Promo-Solicitation', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1225', '', 'Ads and Promo-Seminars', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1230', '', 'Ads and Promo', '13', 0);
INSERT INTO `0_chart_master` VALUES('6-1240', '', 'Bank Charges', '13', 0);

INSERT INTO `0_chart_master` VALUES('6-2010', '', 'Interest Expense', '14', 0);

INSERT INTO `0_chart_master` VALUES('6-3010', '', 'Interest Income', '15', 0);

INSERT INTO `0_chart_master` VALUES('6-4010', '', 'Other Income', '16', 0);

INSERT INTO `0_chart_master` VALUES('7-1010', '', 'Provision for Income Tax', '17', 0);

--INSERT INTO `0_chart_master` VALUES('8-1010', '', 'Gain/Loss on Foreign Exchange', '17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_types`
--

DROP TABLE IF EXISTS `0_chart_types`;
CREATE TABLE IF NOT EXISTS `0_chart_types` (
  `id` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `class_id` varchar(3) NOT NULL DEFAULT '',
  `parent` varchar(10) NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_chart_types`
--

INSERT INTO `0_chart_types` VALUES('1', 'Cash', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('2', 'Trade and Other Receivables', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('3', 'Prepayments and Other Current Assets', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('4', 'Merchandise Inventory', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('5', 'Refundable Deposit', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('6', 'Property, Plant and Equipment', '1', '', 0);
INSERT INTO `0_chart_types` VALUES('7', 'Trade and Other Payables', '2', '', 0);
INSERT INTO `0_chart_types` VALUES('8', 'Non-current Liabilities', '2', '', 0);
INSERT INTO `0_chart_types` VALUES('9', 'Share Capital', '3', '', 0);
INSERT INTO `0_chart_types` VALUES('10', 'Retained Earnings', '3', '', 0);
INSERT INTO `0_chart_types` VALUES('11', 'Revenues', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('12', 'Cost of Sales', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('13', 'Operating Expenses', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('14', 'Interest Expense', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('15', 'Interest Income', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('16', 'Other Income', '4', '', 0);
INSERT INTO `0_chart_types` VALUES('17', 'Income Tax Expense', '4', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_comments`
--

DROP TABLE IF EXISTS `0_comments`;
CREATE TABLE IF NOT EXISTS `0_comments` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext,
  KEY `type_and_id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_comments`
--

--INSERT INTO `0_comments` VALUES(0, 1, '2011-06-16', 'To record initial capital');

-- --------------------------------------------------------

--
-- Table structure for table `0_credit_status`
--

DROP TABLE IF EXISTS `0_credit_status`;
CREATE TABLE IF NOT EXISTS `0_credit_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_description` char(100) NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_description` (`reason_description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_credit_status`
--

INSERT INTO `0_credit_status` VALUES(1, 'Good History', 0, 0);
INSERT INTO `0_credit_status` VALUES(3, 'No more work until payment received', 1, 0);
INSERT INTO `0_credit_status` VALUES(4, 'In liquidation', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_categories`
--

DROP TABLE IF EXISTS `0_crm_categories`;
CREATE TABLE IF NOT EXISTS `0_crm_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pure technical key',
  `type` varchar(20) NOT NULL COMMENT 'contact type e.g. customer',
  `action` varchar(20) NOT NULL COMMENT 'detailed usage e.g. department',
  `name` varchar(30) NOT NULL COMMENT 'for category selector',
  `description` tinytext NOT NULL COMMENT 'usage description',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'nonzero for core system usage',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`action`),
  UNIQUE KEY `type_2` (`type`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_crm_categories`
--

INSERT INTO `0_crm_categories` VALUES(1, 'cust_branch', 'general', 'General', 'General contact data for customer branch (overrides company setting)', 1, 0);
INSERT INTO `0_crm_categories` VALUES(2, 'cust_branch', 'invoice', 'Invoices', 'Invoice posting (overrides company setting)', 1, 0);
INSERT INTO `0_crm_categories` VALUES(3, 'cust_branch', 'order', 'Orders', 'Order confirmation (overrides company setting)', 1, 0);
INSERT INTO `0_crm_categories` VALUES(4, 'cust_branch', 'delivery', 'Deliveries', 'Delivery coordination (overrides company setting)', 1, 0);
INSERT INTO `0_crm_categories` VALUES(5, 'customer', 'general', 'General', 'General contact data for customer', 1, 0);
INSERT INTO `0_crm_categories` VALUES(6, 'customer', 'order', 'Orders', 'Order confirmation', 1, 0);
INSERT INTO `0_crm_categories` VALUES(7, 'customer', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0);
INSERT INTO `0_crm_categories` VALUES(8, 'customer', 'invoice', 'Invoices', 'Invoice posting', 1, 0);
INSERT INTO `0_crm_categories` VALUES(9, 'supplier', 'general', 'General', 'General contact data for supplier', 1, 0);
INSERT INTO `0_crm_categories` VALUES(10, 'supplier', 'order', 'Orders', 'Order confirmation', 1, 0);
INSERT INTO `0_crm_categories` VALUES(11, 'supplier', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0);
INSERT INTO `0_crm_categories` VALUES(12, 'supplier', 'invoice', 'Invoices', 'Invoice posting', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_contacts`
--

DROP TABLE IF EXISTS `0_crm_contacts`;
CREATE TABLE IF NOT EXISTS `0_crm_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL DEFAULT '0' COMMENT 'foreign key to crm_contacts',
  `type` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `action` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `entity_id` varchar(11) DEFAULT NULL COMMENT 'entity id in related class table',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_crm_contacts`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_persons`
--

DROP TABLE IF EXISTS `0_crm_persons`;
CREATE TABLE IF NOT EXISTS `0_crm_persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(30) NOT NULL,
  `name` varchar(60) NOT NULL,
  `name2` varchar(60) DEFAULT NULL,
  `address` tinytext,
  `phone` varchar(30) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lang` char(5) DEFAULT NULL,
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_crm_persons`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_currencies`
--

DROP TABLE IF EXISTS `0_currencies`;
CREATE TABLE IF NOT EXISTS `0_currencies` (
  `currency` varchar(60) NOT NULL DEFAULT '',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_abrev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_currencies`
--

INSERT INTO `0_currencies` VALUES('US Dollars', 'USD', '$', 'United States', 'Cents', 1, 0);
INSERT INTO `0_currencies` VALUES('Philippine Peso', 'PHP', 'P', 'Philippines', 'Centavo', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_cust_allocations`
--

DROP TABLE IF EXISTS `0_cust_allocations`;
CREATE TABLE IF NOT EXISTS `0_cust_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_cust_allocations`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_cust_branch`
--

DROP TABLE IF EXISTS `0_cust_branch`;
CREATE TABLE IF NOT EXISTS `0_cust_branch` (
  `branch_code` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `br_name` varchar(60) NOT NULL DEFAULT '',
  `branch_ref` varchar(30) NOT NULL DEFAULT '',
  `br_address` tinytext NOT NULL,
  `area` int(11) DEFAULT NULL,
  `salesman` int(11) NOT NULL DEFAULT '0',
  `contact_name` varchar(60) NOT NULL DEFAULT '',
  `default_location` varchar(5) NOT NULL DEFAULT '',
  `tax_group_id` int(11) DEFAULT NULL,
  `sales_account` varchar(15) NOT NULL DEFAULT '4-1010',
  `sales_discount_account` varchar(15) NOT NULL DEFAULT '4-1015',
  `receivables_account` varchar(15) NOT NULL DEFAULT '1-2031',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '4-1015',
  `default_ship_via` int(11) NOT NULL DEFAULT '1',
  `disable_trans` tinyint(4) NOT NULL DEFAULT '0',
  `br_post_address` tinytext NOT NULL,
  `group_no` int(11) NOT NULL DEFAULT '0',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`branch_code`,`debtor_no`),
  KEY `branch_code` (`branch_code`),
  KEY `branch_ref` (`branch_ref`),
  KEY `group_no` (`group_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_cust_branch`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_debtors_master`
--

DROP TABLE IF EXISTS `0_debtors_master`;
CREATE TABLE IF NOT EXISTS `0_debtors_master` (
  `debtor_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) NOT NULL,
  `address` tinytext,
  `tax_id` varchar(55) NOT NULL DEFAULT '',
  `curr_code` char(3) NOT NULL DEFAULT '',
  `sales_type` int(11) NOT NULL DEFAULT '1',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `credit_status` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pymt_discount` double NOT NULL DEFAULT '0',
  `credit_limit` float NOT NULL DEFAULT '1000',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtor_no`),
  UNIQUE KEY `debtor_ref` (`debtor_ref`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_debtors_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans`
--
--moodlearning edit: bulk_discount

DROP TABLE IF EXISTS `0_debtor_trans`;
CREATE TABLE IF NOT EXISTS `0_debtor_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `branch_code` int(11) NOT NULL DEFAULT '-1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` varchar(60) NOT NULL DEFAULT '',
  `tpe` int(11) NOT NULL DEFAULT '0',
  `order_` int(11) NOT NULL DEFAULT '0',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `ov_freight` double NOT NULL DEFAULT '0',
  `ov_freight_tax` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `bulk_discount` double DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ship_via` int(11) DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  PRIMARY KEY (`type`,`trans_no`),
  KEY `debtor_no` (`debtor_no`,`branch_code`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_debtor_trans`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans_details`
--

DROP TABLE IF EXISTS `0_debtor_trans_details`;
CREATE TABLE IF NOT EXISTS `0_debtor_trans_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_trans_no` int(11) DEFAULT NULL,
  `debtor_trans_type` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `qty_done` double NOT NULL DEFAULT '0',
  `src_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Transaction` (`debtor_trans_type`,`debtor_trans_no`),
  KEY `src_id` (`src_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_debtor_trans_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_dimensions`
--

DROP TABLE IF EXISTS `0_dimensions`;
CREATE TABLE IF NOT EXISTS `0_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `date_` (`date_`),
  KEY `due_date` (`due_date`),
  KEY `type_` (`type_`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_dimensions`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_exchange_rates`
--

DROP TABLE IF EXISTS `0_exchange_rates`;
CREATE TABLE IF NOT EXISTS `0_exchange_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curr_code` char(3) NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT '0',
  `rate_sell` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `curr_code` (`curr_code`,`date_`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_exchange_rates`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_fiscal_year`
--

DROP TABLE IF EXISTS `0_fiscal_year`;
CREATE TABLE IF NOT EXISTS `0_fiscal_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `begin` (`begin`),
  UNIQUE KEY `end` (`end`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_fiscal_year`
--

INSERT INTO `0_fiscal_year` VALUES(4, '2011-01-01', '2011-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_gl_trans`
--
--moodlearning edit: customized_no and customized_field

DROP TABLE IF EXISTS `0_gl_trans`;
CREATE TABLE IF NOT EXISTS `0_gl_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`),
  KEY `dimension_id` (`dimension_id`),
  KEY `dimension2_id` (`dimension2_id`),
  KEY `tran_date` (`tran_date`),
  KEY `account_and_tran_date` (`account`,`tran_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_gl_trans`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_grn_batch`
--

DROP TABLE IF EXISTS `0_grn_batch`;
CREATE TABLE IF NOT EXISTS `0_grn_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_date` (`delivery_date`),
  KEY `purch_order_no` (`purch_order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_grn_batch`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_grn_items`
--

DROP TABLE IF EXISTS `0_grn_items`;
CREATE TABLE IF NOT EXISTS `0_grn_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_recd` double NOT NULL DEFAULT '0',
  `quantity_inv` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `grn_batch_id` (`grn_batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_grn_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_groups`
--

DROP TABLE IF EXISTS `0_groups`;
CREATE TABLE IF NOT EXISTS `0_groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_groups`
--

INSERT INTO `0_groups` VALUES(1, 'Small', 0);
INSERT INTO `0_groups` VALUES(2, 'Medium', 0);
INSERT INTO `0_groups` VALUES(3, 'Large', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_codes`
--

DROP TABLE IF EXISTS `0_item_codes`;
CREATE TABLE IF NOT EXISTS `0_item_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(20) NOT NULL,
  `stock_id` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `category_id` smallint(6) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `is_foreign` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_id` (`stock_id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_codes`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_types`
--

DROP TABLE IF EXISTS `0_item_tax_types`;
CREATE TABLE IF NOT EXISTS `0_item_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_tax_types`
--

INSERT INTO `0_item_tax_types` VALUES(1, 'Non Vat', 1, 0);
INSERT INTO `0_item_tax_types` VALUES(2, 'Regular', 0, 0);


-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_type_exemptions`
--

DROP TABLE IF EXISTS `0_item_tax_type_exemptions`;
CREATE TABLE IF NOT EXISTS `0_item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_tax_type_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_tax_type_exemptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_item_units`
--

DROP TABLE IF EXISTS `0_item_units`;
CREATE TABLE IF NOT EXISTS `0_item_units` (
  `abbr` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `decimals` tinyint(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abbr`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_units`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_locations`
--

DROP TABLE IF EXISTS `0_locations`;
CREATE TABLE IF NOT EXISTS `0_locations` (
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `location_name` varchar(60) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_locations`
--

INSERT INTO `0_locations` VALUES('QC', 'Quezon City', 'N/A', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_loc_stock`
--

DROP TABLE IF EXISTS `0_loc_stock`;
CREATE TABLE IF NOT EXISTS `0_loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `reorder_level` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_loc_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_movement_types`
--

DROP TABLE IF EXISTS `0_movement_types`;
CREATE TABLE IF NOT EXISTS `0_movement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_movement_types`
--

INSERT INTO `0_movement_types` VALUES(1, 'Adjustment', 0);
INSERT INTO `0_movement_types` VALUES(2, 'Supplier Delivery', 0);
INSERT INTO `0_movement_types` VALUES(3, 'Store Delivery', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_payment_terms`
--

DROP TABLE IF EXISTS `0_payment_terms`;
CREATE TABLE IF NOT EXISTS `0_payment_terms` (
  `terms_indicator` int(11) NOT NULL AUTO_INCREMENT,
  `terms` char(80) NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT '0',
  `day_in_following_month` smallint(6) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`terms_indicator`),
  UNIQUE KEY `terms` (`terms`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_payment_terms`
--

INSERT INTO `0_payment_terms` VALUES(1, 'Due 15th Of the Following Month', 0, 17, 0);
INSERT INTO `0_payment_terms` VALUES(2, 'Due By End Of The Following Month', 0, 30, 0);
INSERT INTO `0_payment_terms` VALUES(3, 'Payment due within 10 days', 10, 0, 0);
INSERT INTO `0_payment_terms` VALUES(4, 'Cash Only', 0, 0, 0);
INSERT INTO `0_payment_terms` VALUES(4, 'C.O.D.', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_prices`
--

DROP TABLE IF EXISTS `0_prices`;
CREATE TABLE IF NOT EXISTS `0_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT '0',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_prices`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_printers`
--

DROP TABLE IF EXISTS `0_printers`;
CREATE TABLE IF NOT EXISTS `0_printers` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(60) NOT NULL,
  `queue` varchar(20) NOT NULL,
  `host` varchar(40) NOT NULL,
  `port` smallint(11) unsigned NOT NULL,
  `timeout` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_printers`
--

INSERT INTO `0_printers` VALUES(1, 'QL500', 'Label printer', 'QL500', 'server', 127, 20);
INSERT INTO `0_printers` VALUES(2, 'Samsung', 'Main network printer', 'scx4521F', 'server', 515, 5);
INSERT INTO `0_printers` VALUES(3, 'Local', 'Local print server at user IP', 'lp', '', 515, 10);

-- --------------------------------------------------------

--
-- Table structure for table `0_print_profiles`
--

DROP TABLE IF EXISTS `0_print_profiles`;
CREATE TABLE IF NOT EXISTS `0_print_profiles` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(30) NOT NULL,
  `report` varchar(5) DEFAULT NULL,
  `printer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile` (`profile`,`report`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_print_profiles`
--

INSERT INTO `0_print_profiles` VALUES(1, 'Out of office', '', 0);
INSERT INTO `0_print_profiles` VALUES(2, 'Sales Department', '', 0);
INSERT INTO `0_print_profiles` VALUES(3, 'Central', '', 2);
INSERT INTO `0_print_profiles` VALUES(4, 'Sales Department', '104', 2);
INSERT INTO `0_print_profiles` VALUES(5, 'Sales Department', '105', 2);
INSERT INTO `0_print_profiles` VALUES(6, 'Sales Department', '107', 2);
INSERT INTO `0_print_profiles` VALUES(7, 'Sales Department', '109', 2);
INSERT INTO `0_print_profiles` VALUES(8, 'Sales Department', '110', 2);
INSERT INTO `0_print_profiles` VALUES(9, 'Sales Department', '201', 2);

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_data`
--

DROP TABLE IF EXISTS `0_purch_data`;
CREATE TABLE IF NOT EXISTS `0_purch_data` (
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(50) NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT '1',
  `supplier_description` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_purch_data`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_purch_orders`
--

DROP TABLE IF EXISTS `0_purch_orders`;
CREATE TABLE IF NOT EXISTS `0_purch_orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_no`),
  KEY `ord_date` (`ord_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_purch_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_purch_order_details`
--

DROP TABLE IF EXISTS `0_purch_order_details`;
CREATE TABLE IF NOT EXISTS `0_purch_order_details` (
  `po_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_detail_item`),
  KEY `order` (`order_no`,`po_detail_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_purch_order_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entries`
--

DROP TABLE IF EXISTS `0_quick_entries`;
CREATE TABLE IF NOT EXISTS `0_quick_entries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(60) NOT NULL,
  `base_amount` double NOT NULL DEFAULT '0',
  `base_desc` varchar(60) DEFAULT NULL,
  `bal_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_quick_entries`
--

INSERT INTO `0_quick_entries` VALUES(1, 1, 'Maintenance', 0, 'Amount', 0);
INSERT INTO `0_quick_entries` VALUES(2, 4, 'Phone', 0, 'Amount', 0);
INSERT INTO `0_quick_entries` VALUES(3, 2, 'Cash Sales', 0, 'Amount', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entry_lines`
--

DROP TABLE IF EXISTS `0_quick_entry_lines`;
CREATE TABLE IF NOT EXISTS `0_quick_entry_lines` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `qid` smallint(6) unsigned NOT NULL,
  `amount` double DEFAULT '0',
  `action` varchar(2) NOT NULL,
  `dest_id` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` smallint(6) unsigned DEFAULT NULL,
  `dimension2_id` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_quick_entry_lines`
--

INSERT INTO `0_quick_entry_lines` VALUES(1, 1, 0, 't-', '1', 0, 0);
INSERT INTO `0_quick_entry_lines` VALUES(2, 2, 0, 't-', '1', 0, 0);
INSERT INTO `0_quick_entry_lines` VALUES(3, 3, 0, 't-', '1', 0, 0);
INSERT INTO `0_quick_entry_lines` VALUES(4, 3, 0, '=', '4-1000', 0, 0);
INSERT INTO `0_quick_entry_lines` VALUES(5, 1, 0, '=', '6-4130', 0, 0);
INSERT INTO `0_quick_entry_lines` VALUES(6, 2, 0, '=', '6-4310', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_recurrent_invoices`
--

DROP TABLE IF EXISTS `0_recurrent_invoices`;
CREATE TABLE IF NOT EXISTS `0_recurrent_invoices` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `order_no` int(11) unsigned NOT NULL,
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `group_no` smallint(6) unsigned DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `monthly` int(11) NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_recurrent_invoices`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_refs`
--

DROP TABLE IF EXISTS `0_refs`;
CREATE TABLE IF NOT EXISTS `0_refs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`),
  KEY `Type_and_Reference` (`type`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_refs`
--

--INSERT INTO `0_refs` VALUES(1, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `0_salesman`
--

DROP TABLE IF EXISTS `0_salesman`;
CREATE TABLE IF NOT EXISTS `0_salesman` (
  `imc_code` varchar(10) NOT NULL DEFAULT '',
  `salesman_code` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_name` char(60) NOT NULL DEFAULT '',
  `salesman_phone` char(30) NOT NULL DEFAULT '',
  `salesman_fax` char(30) NOT NULL DEFAULT '',
  `salesman_email` varchar(100) NOT NULL DEFAULT '',
  `provision` double NOT NULL DEFAULT '0',
  `break_pt` double NOT NULL DEFAULT '0',
  `provision2` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesman_code`),
  UNIQUE KEY `salesman_name` (`salesman_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_salesman`
--

--INSERT INTO `0_salesman` VALUES(1, 'Sales Person', '', '', '', 0, 1000, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_orders`
--

DROP TABLE IF EXISTS `0_sales_orders`;
CREATE TABLE IF NOT EXISTS `0_sales_orders` (
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `branch_code` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `customer_ref` tinytext NOT NULL,
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `order_type` int(11) NOT NULL DEFAULT '0',
  `ship_via` int(11) NOT NULL DEFAULT '0',
  `delivery_address` tinytext NOT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `deliver_to` tinytext NOT NULL,
  `freight_cost` double NOT NULL DEFAULT '0',
  `from_stk_loc` varchar(5) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `payment_terms` int(11) DEFAULT NULL,
  `total` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_type`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sales_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_sales_order_details`
--

DROP TABLE IF EXISTS `0_sales_order_details`;
CREATE TABLE IF NOT EXISTS `0_sales_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `stk_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_sent` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sorder` (`trans_type`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sales_order_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_sales_pos`
--

DROP TABLE IF EXISTS `0_sales_pos`;
CREATE TABLE IF NOT EXISTS `0_sales_pos` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(30) NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) NOT NULL,
  `pos_account` smallint(6) unsigned NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_name` (`pos_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sales_pos`
--

--INSERT INTO `0_sales_pos` VALUES(1, 'Default', 1, 1, 'QC', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_types`
--

DROP TABLE IF EXISTS `0_sales_types`;
CREATE TABLE IF NOT EXISTS `0_sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` char(50) NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_type` (`sales_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sales_types`
--

--INSERT INTO `0_sales_types` VALUES(1, 'Retail', 1, 1, 0);
--INSERT INTO `0_sales_types` VALUES(2, 'Wholesale', 0, 0.7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_security_roles`
--

DROP TABLE IF EXISTS `0_security_roles`;
CREATE TABLE IF NOT EXISTS `0_security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `sections` text,
  `areas` text,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_security_roles`
--

INSERT INTO `0_security_roles` VALUES(1, 'Inquiries', 'Inquiries', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(2, 'System Administrator', 'System Administrator', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;10496;10752;11008;13056;13312;15616;15872;16128', '257;258;259;260;513;514;515;517;518;519;520;521;522;523;524;525;526;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11011;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15628;15625;15626;15627;15629;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;15884;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(3, 'Salesman', 'Salesman', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873', 0);
INSERT INTO `0_security_roles` VALUES(4, 'Stock Manager', 'Stock Manager', '2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(5, 'Production Manager', 'Production Manager', '512;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(6, 'Purchase Officer', 'Purchase Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(7, 'AR Officer', 'AR Officer', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(8, 'AP Officer', 'AP Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(9, 'Accountant', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0);
INSERT INTO `0_security_roles` VALUES(10, 'Sub Admin', 'Sub Admin', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_shippers`
--

DROP TABLE IF EXISTS `0_shippers`;
CREATE TABLE IF NOT EXISTS `0_shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_name` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `contact` tinytext NOT NULL,
  `address` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`),
  UNIQUE KEY `name` (`shipper_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_shippers`
--

INSERT INTO `0_shippers` VALUES(1, 'Default', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sql_trail`
--

DROP TABLE IF EXISTS `0_sql_trail`;
CREATE TABLE IF NOT EXISTS `0_sql_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sql_trail`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_stock_category`
--

DROP TABLE IF EXISTS `0_stock_category`;
CREATE TABLE IF NOT EXISTS `0_stock_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `dflt_tax_type` int(11) NOT NULL DEFAULT '1',
  `dflt_units` varchar(20) NOT NULL DEFAULT 'each',
  `dflt_mb_flag` char(1) NOT NULL DEFAULT 'B',
  `dflt_sales_act` varchar(15) NOT NULL DEFAULT '4-1010',
  `dflt_cogs_act` varchar(15) NOT NULL DEFAULT '5-1010',
  `dflt_inventory_act` varchar(15) NOT NULL DEFAULT '1-4010',
  `dflt_adjustment_act` varchar(15) NOT NULL DEFAULT '1-4010',
  `dflt_assembly_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_dim1` int(11) DEFAULT NULL,
  `dflt_dim2` int(11) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `dflt_no_sale` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_stock_category`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_master`
--

DROP TABLE IF EXISTS `0_stock_master`;
CREATE TABLE IF NOT EXISTS `0_stock_master` (
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `level_id` int(11) NOT NULL DEFAULT '0',
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mb_flag` char(1) NOT NULL DEFAULT 'B',
  `sales_account` varchar(15) NOT NULL DEFAULT '4-1010',
  `cogs_account` varchar(15) NOT NULL DEFAULT '5-1010',
  `inventory_account` varchar(15) NOT NULL DEFAULT '1-4010',
  `adjustment_account` varchar(15) NOT NULL DEFAULT '1-4010',
  `assembly_account` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `actual_cost` double NOT NULL DEFAULT '0',
  `last_cost` double NOT NULL DEFAULT '0',
  `material_cost` double NOT NULL DEFAULT '0',
  `labour_cost` double NOT NULL DEFAULT '0',
  `overhead_cost` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `no_sale` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_stock_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_moves`
--

DROP TABLE IF EXISTS `0_stock_moves`;
CREATE TABLE IF NOT EXISTS `0_stock_moves` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `person_id` int(11) DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `reference` char(40) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`trans_id`),
  KEY `type` (`type`,`trans_no`),
  KEY `Move` (`stock_id`,`loc_code`,`tran_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_stock_moves`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_suppliers`
--

DROP TABLE IF EXISTS `0_suppliers`;
CREATE TABLE IF NOT EXISTS `0_suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(60) NOT NULL DEFAULT '',
  `supp_ref` varchar(30) NOT NULL DEFAULT '',
  `address` tinytext NOT NULL,
  `supp_address` tinytext NOT NULL,
  `gst_no` varchar(25) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `supp_account_no` varchar(40) NOT NULL DEFAULT '',
  `website` varchar(100) NOT NULL DEFAULT '',
  `bank_account` varchar(60) NOT NULL DEFAULT '',
  `curr_code` char(3) DEFAULT NULL,
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `tax_group_id` int(11) DEFAULT NULL,
  `credit_limit` double NOT NULL DEFAULT '0',
  `purchase_account` varchar(15) NOT NULL DEFAULT '5-1010',
  `payable_account` varchar(15) NOT NULL DEFAULT '2-1065',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '6-1170',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplier_id`),
  KEY `supp_ref` (`supp_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_suppliers`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_allocations`
--

DROP TABLE IF EXISTS `0_supp_allocations`;
CREATE TABLE IF NOT EXISTS `0_supp_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_supp_allocations`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_supp_invoice_items`
--

DROP TABLE IF EXISTS `0_supp_invoice_items`;
CREATE TABLE IF NOT EXISTS `0_supp_invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_trans_no` int(11) DEFAULT NULL,
  `supp_trans_type` int(11) DEFAULT NULL,
  `gl_code` varchar(15) NOT NULL DEFAULT '',
  `grn_item_id` int(11) DEFAULT NULL,
  `po_detail_item_id` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `quantity` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `memo_` tinytext,
  PRIMARY KEY (`id`),
  KEY `Transaction` (`supp_trans_type`,`supp_trans_no`,`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_supp_invoice_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_supp_trans`
--

DROP TABLE IF EXISTS `0_supp_trans`;
CREATE TABLE IF NOT EXISTS `0_supp_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `reference` tinytext NOT NULL,
  `supp_reference` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `alloc` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`trans_no`),
  KEY `supplier_id` (`supplier_id`),
  KEY `SupplierID_2` (`supplier_id`,`supp_reference`),
  KEY `type` (`type`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_supp_trans`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_sys_prefs`
--

DROP TABLE IF EXISTS `0_sys_prefs`;
CREATE TABLE IF NOT EXISTS `0_sys_prefs` (
  `name` varchar(35) NOT NULL DEFAULT '',
  `category` varchar(30) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `length` smallint(6) DEFAULT NULL,
  `value` tinytext,
  PRIMARY KEY (`name`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sys_prefs`
--

INSERT INTO `0_sys_prefs` VALUES('coy_name', 'setup.company', 'varchar', 60, 'Marinny Corporation');
INSERT INTO `0_sys_prefs` VALUES('gst_no', 'setup.company', 'varchar', 25, '');
INSERT INTO `0_sys_prefs` VALUES('coy_no', 'setup.company', 'varchar', 25, '');
INSERT INTO `0_sys_prefs` VALUES('tax_prd', 'setup.company', 'int', 11, '1');
INSERT INTO `0_sys_prefs` VALUES('tax_last', 'setup.company', 'int', 11, '1');
INSERT INTO `0_sys_prefs` VALUES('postal_address', 'setup.company', 'tinytext', 0, 'N/A');
INSERT INTO `0_sys_prefs` VALUES('phone', 'setup.company', 'varchar', 30, '');
INSERT INTO `0_sys_prefs` VALUES('fax', 'setup.company', 'varchar', 30, '');
INSERT INTO `0_sys_prefs` VALUES('email', 'setup.company', 'varchar', 100, '');
INSERT INTO `0_sys_prefs` VALUES('coy_logo', 'setup.company', 'varchar', 100, '');
INSERT INTO `0_sys_prefs` VALUES('domicile', 'setup.company', 'varchar', 55, '');
INSERT INTO `0_sys_prefs` VALUES('curr_default', 'setup.company', 'char', 3, 'USD');
INSERT INTO `0_sys_prefs` VALUES('use_dimension', 'setup.company', 'tinyint', 1, '1');
INSERT INTO `0_sys_prefs` VALUES('f_year', 'setup.company', 'int', 11, '4');
INSERT INTO `0_sys_prefs` VALUES('no_item_list', 'setup.company', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('no_customer_list', 'setup.company', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('no_supplier_list', 'setup.company', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('base_sales', 'setup.company', 'int', 11, '1');
INSERT INTO `0_sys_prefs` VALUES('time_zone', 'setup.company', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('add_pct', 'setup.company', 'int', 5, '-1');
INSERT INTO `0_sys_prefs` VALUES('round_to', 'setup.company', 'int', 5, '1');
INSERT INTO `0_sys_prefs` VALUES('login_tout', 'setup.company', 'smallint', 6, '600');
INSERT INTO `0_sys_prefs` VALUES('past_due_days', 'glsetup.general', 'int', 11, '30');
INSERT INTO `0_sys_prefs` VALUES('profit_loss_year_act', 'glsetup.general', 'varchar', 15, '6-1170');
INSERT INTO `0_sys_prefs` VALUES('retained_earnings_act', 'glsetup.general', 'varchar', 15, '3-1030');
INSERT INTO `0_sys_prefs` VALUES('bank_charge_act', 'glsetup.general', 'varchar', 15, '1-1010');
INSERT INTO `0_sys_prefs` VALUES('exchange_diff_act', 'glsetup.general', 'varchar', 15, '6-1170');
INSERT INTO `0_sys_prefs` VALUES('default_credit_limit', 'glsetup.customer', 'int', 11, '100000');
INSERT INTO `0_sys_prefs` VALUES('accumulate_shipping', 'glsetup.customer', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('legal_text', 'glsetup.customer', 'tinytext', 0, '');
INSERT INTO `0_sys_prefs` VALUES('freight_act', 'glsetup.customer', 'varchar', 15, '5-1030');
INSERT INTO `0_sys_prefs` VALUES('debtors_act', 'glsetup.sales', 'varchar', 15, '1-2031');
INSERT INTO `0_sys_prefs` VALUES('default_sales_act', 'glsetup.sales', 'varchar', 15, '4-1010');
INSERT INTO `0_sys_prefs` VALUES('default_sales_discount_act', 'glsetup.sales', 'varchar', 15, '4-1015');
INSERT INTO `0_sys_prefs` VALUES('default_prompt_payment_act', 'glsetup.sales', 'varchar', 15, '4-1015');
INSERT INTO `0_sys_prefs` VALUES('default_delivery_required', 'glsetup.sales', 'smallint', 6, '1');
INSERT INTO `0_sys_prefs` VALUES('default_dim_required', 'glsetup.dims', 'int', 11, '20');
INSERT INTO `0_sys_prefs` VALUES('pyt_discount_act', 'glsetup.purchase', 'varchar', 15, '6-1170');
INSERT INTO `0_sys_prefs` VALUES('creditors_act', 'glsetup.purchase', 'varchar', 15, '2-1065');
INSERT INTO `0_sys_prefs` VALUES('po_over_receive', 'glsetup.purchase', 'int', 11, '10');
INSERT INTO `0_sys_prefs` VALUES('po_over_charge', 'glsetup.purchase', 'int', 11, '10');
INSERT INTO `0_sys_prefs` VALUES('allow_negative_stock', 'glsetup.inventory', 'tinyint', 1, '0');
INSERT INTO `0_sys_prefs` VALUES('default_inventory_act', 'glsetup.items', 'varchar', 15, '1-4010');
INSERT INTO `0_sys_prefs` VALUES('default_cogs_act', 'glsetup.items', 'varchar', 15, '5-1010');
INSERT INTO `0_sys_prefs` VALUES('default_adj_act', 'glsetup.items', 'varchar', 15, '1-4010');
INSERT INTO `0_sys_prefs` VALUES('default_inv_sales_act', 'glsetup.items', 'varchar', 15, '4-1010');
INSERT INTO `0_sys_prefs` VALUES('default_assembly_act', 'glsetup.items', 'varchar', 15, '1-4010');
INSERT INTO `0_sys_prefs` VALUES('default_workorder_required', 'glsetup.manuf', 'int', 11, '20');
INSERT INTO `0_sys_prefs` VALUES('version_id', 'system', 'varchar', 11, '2.3rc');
INSERT INTO `0_sys_prefs` VALUES('auto_curr_reval', 'setup.company', 'smallint', 6, '');
INSERT INTO `0_sys_prefs` VALUES('grn_clearing_act', 'glsetup.purchase', 'varchar', 15, '1-4010');

-- --------------------------------------------------------

--
-- Table structure for table `0_sys_types`
--

DROP TABLE IF EXISTS `0_sys_types`;
CREATE TABLE IF NOT EXISTS `0_sys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sys_types`
--

INSERT INTO `0_sys_types` VALUES(0, 17, '1');
INSERT INTO `0_sys_types` VALUES(1, 7, '1');
INSERT INTO `0_sys_types` VALUES(2, 4, '1');
INSERT INTO `0_sys_types` VALUES(4, 3, '1');
INSERT INTO `0_sys_types` VALUES(10, 16, '1');
INSERT INTO `0_sys_types` VALUES(11, 2, '1');
INSERT INTO `0_sys_types` VALUES(12, 6, '1');
INSERT INTO `0_sys_types` VALUES(13, 1, '1');
INSERT INTO `0_sys_types` VALUES(16, 2, '1');
INSERT INTO `0_sys_types` VALUES(17, 2, '1');
INSERT INTO `0_sys_types` VALUES(18, 1, '1');
INSERT INTO `0_sys_types` VALUES(20, 6, '1');
INSERT INTO `0_sys_types` VALUES(21, 1, '1');
INSERT INTO `0_sys_types` VALUES(22, 3, '1');
INSERT INTO `0_sys_types` VALUES(25, 1, '1');
INSERT INTO `0_sys_types` VALUES(26, 1, '1');
INSERT INTO `0_sys_types` VALUES(28, 1, '1');
INSERT INTO `0_sys_types` VALUES(29, 1, '1');
INSERT INTO `0_sys_types` VALUES(30, 0, '1');
INSERT INTO `0_sys_types` VALUES(32, 0, '1');
INSERT INTO `0_sys_types` VALUES(35, 1, '1');
INSERT INTO `0_sys_types` VALUES(40, 1, '1');
INSERT INTO `0_sys_types` VALUES(55, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `0_tags`
--

DROP TABLE IF EXISTS `0_tags`;
CREATE TABLE IF NOT EXISTS `0_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tags`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_tag_associations`
--

DROP TABLE IF EXISTS `0_tag_associations`;
CREATE TABLE IF NOT EXISTS `0_tag_associations` (
  `record_id` varchar(15) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `record_id` (`record_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tag_associations`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_tax_groups`
--

DROP TABLE IF EXISTS `0_tax_groups`;
CREATE TABLE IF NOT EXISTS `0_tax_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `tax_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tax_groups`
--

INSERT INTO `0_tax_groups` VALUES(1, 'Tax', 0, 0);
INSERT INTO `0_tax_groups` VALUES(2, 'Tax Exempt', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_group_items`
--

DROP TABLE IF EXISTS `0_tax_group_items`;
CREATE TABLE IF NOT EXISTS `0_tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_group_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tax_group_items`
--

INSERT INTO `0_tax_group_items` VALUES(1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_types`
--

DROP TABLE IF EXISTS `0_tax_types`;
CREATE TABLE IF NOT EXISTS `0_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` double NOT NULL DEFAULT '0',
  `sales_gl_code` varchar(15) NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tax_types`
--

INSERT INTO `0_tax_types` VALUES(1, 12, '5080', '3200', 'VAT', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_trans_tax_details`
--

DROP TABLE IF EXISTS `0_trans_tax_details`;
CREATE TABLE IF NOT EXISTS `0_trans_tax_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `tran_date` date NOT NULL,
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ex_rate` double NOT NULL DEFAULT '1',
  `included_in_price` tinyint(1) NOT NULL DEFAULT '0',
  `net_amount` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `memo` tinytext,
  PRIMARY KEY (`id`),
  KEY `Type_and_Number` (`trans_type`,`trans_no`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_trans_tax_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_useronline`
--

DROP TABLE IF EXISTS `0_useronline`;
CREATE TABLE IF NOT EXISTS `0_useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_useronline`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_users`
--

DROP TABLE IF EXISTS `0_users`;
CREATE TABLE IF NOT EXISTS `0_users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `real_name` varchar(100) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `date_format` tinyint(1) NOT NULL DEFAULT '0',
  `date_sep` tinyint(1) NOT NULL DEFAULT '0',
  `tho_sep` tinyint(1) NOT NULL DEFAULT '0',
  `dec_sep` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) NOT NULL DEFAULT 'default',
  `page_size` varchar(20) NOT NULL DEFAULT 'A4',
  `prices_dec` smallint(6) NOT NULL DEFAULT '2',
  `qty_dec` smallint(6) NOT NULL DEFAULT '2',
  `rates_dec` smallint(6) NOT NULL DEFAULT '4',
  `percent_dec` smallint(6) NOT NULL DEFAULT '1',
  `show_gl` tinyint(1) NOT NULL DEFAULT '1',
  `show_codes` tinyint(1) NOT NULL DEFAULT '0',
  `show_hints` tinyint(1) NOT NULL DEFAULT '0',
  `last_visit_date` datetime DEFAULT NULL,
  `query_size` tinyint(1) DEFAULT '10',
  `graphic_links` tinyint(1) DEFAULT '1',
  `pos` smallint(6) DEFAULT '1',
  `print_profile` varchar(30) NOT NULL DEFAULT '1',
  `rep_popup` tinyint(1) DEFAULT '1',
  `sticky_doc_date` tinyint(1) DEFAULT '0',
  `startup_tab` varchar(20) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_users`
--

INSERT INTO `0_users` VALUES ('1', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', '2', '', 'admin@domain.com', 'en_GB', '1', '2', '0', '0', 'default', 'A4', '2', '2', '4', '1', '1', '1', '1', '2010-02-20 21:49:38', '10', '1', '1', '', '1', '0', 'orders', '0');

-- --------------------------------------------------------

--
-- Table structure for table `0_voided`
--

DROP TABLE IF EXISTS `0_voided`;
CREATE TABLE IF NOT EXISTS `0_voided` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext NOT NULL,
  UNIQUE KEY `id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_voided`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_workcentres`
--

DROP TABLE IF EXISTS `0_workcentres`;
CREATE TABLE IF NOT EXISTS `0_workcentres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `description` char(50) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_workcentres`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_workorders`
--

DROP TABLE IF EXISTS `0_workorders`;
CREATE TABLE IF NOT EXISTS `0_workorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_ref` varchar(60) NOT NULL DEFAULT '',
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `units_reqd` double NOT NULL DEFAULT '1',
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required_by` date NOT NULL DEFAULT '0000-00-00',
  `released_date` date NOT NULL DEFAULT '0000-00-00',
  `units_issued` double NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `additional_costs` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wo_ref` (`wo_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_workorders`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issues`
--

DROP TABLE IF EXISTS `0_wo_issues`;
CREATE TABLE IF NOT EXISTS `0_wo_issues` (
  `issue_no` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`issue_no`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_wo_issues`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issue_items`
--

DROP TABLE IF EXISTS `0_wo_issue_items`;
CREATE TABLE IF NOT EXISTS `0_wo_issue_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(40) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_wo_issue_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_manufacture`
--

DROP TABLE IF EXISTS `0_wo_manufacture`;
CREATE TABLE IF NOT EXISTS `0_wo_manufacture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_wo_manufacture`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_requirements`
--

DROP TABLE IF EXISTS `0_wo_requirements`;
CREATE TABLE IF NOT EXISTS `0_wo_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `workcentre` int(11) NOT NULL DEFAULT '0',
  `units_req` double NOT NULL DEFAULT '1',
  `std_cost` double NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `units_issued` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- ---------------------MOODLEARNING----------------------------- --

-- HARELL --
CREATE TABLE IF NOT EXISTS `0_royalty_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `percentage` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `0_royalty_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `percentage` decimal(11,2) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;


CREATE TABLE IF NOT EXISTS `0_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_fname` varchar(50) NOT NULL,
  `author_mname` varchar(50) DEFAULT NULL,
  `author_lname` varchar(50) NOT NULL,
  `author_birthdate` date DEFAULT NULL,
  `author_address` varchar(800) NOT NULL,
  `author_contact_number` varchar(15) NOT NULL,
  `author_email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `0_cust_alloc_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `details_id` int(11) NOT NULL,
  `no` varchar(10) NOT NULL,
  `alloc` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
 trans_date date NOT NULL, 
 PRIMARY KEY (`id`),
  UNIQUE KEY `no` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- KAREN --
CREATE TABLE IF NOT EXISTS `0_booklet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `inactive` int(11) NOT NULL,
  `date` date NOT NULL,
  `year` year(4) NOT NULL,
  `begin` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `0_booklet`
--

INSERT INTO `0_booklet` (`id`, `type`, `inactive`, `date`, `year`, `begin`, `end`, `stat`) VALUES
(1, 'Journal Voucher', 0, '0000-00-00', 2014, 5, 10, 0),
(2, 'Check Voucher', 0, '0000-00-00', 2014, 1, 1000, 0);


CREATE TABLE IF NOT EXISTS `0_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `inactive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `0_levels`
--


CREATE TABLE IF NOT EXISTS `0_comm_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imc` varchar(10) NOT NULL,
  `invoice_no` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `gross` float NOT NULL,
  `with_tax` float NOT NULL,
  `commission` float NOT NULL,
  `returns` float NOT NULL,
  `discount` float NOT NULL,
  `net_commission` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

CREATE TABLE IF NOT EXISTS `0_customized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `customized_no` int(11) NOT NULL DEFAULT '0',
  `customized_field` varchar(200) NOT NULL DEFAULT '',
  `check_num` varchar(20) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

