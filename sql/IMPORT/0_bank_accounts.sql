-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 04, 2015 at 07:02 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_accounts`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `0_bank_accounts`
--

INSERT INTO `0_bank_accounts` (`account_code`, `account_type`, `bank_account_name`, `bank_account_number`, `bank_name`, `bank_address`, `bank_curr_code`, `dflt_curr_act`, `id`, `last_reconciled_date`, `ending_reconcile_balance`, `inactive`) VALUES
('1-1020', 0, 'CIB-Time Deposit (MBTC)', '', 'METRO BANK', '', 'PHP', 0, 1, '0000-00-00 00:00:00', 0, 0),
('1-1025', 0, 'CIB-Time Deposit (BPIFSB - 67)', '', 'BANK OF THE PHILIPPINE ISLANDS', '', 'PHP', 0, 2, '0000-00-00 00:00:00', 0, 0),
('1-1030', 0, 'CIB-MBTC CA 662-5', '347-3-34755662-5', 'MBTC CA', '', 'PHP', 0, 3, '0000-00-00 00:00:00', 0, 0),
('1-1035', 0, 'CIB-MBTC 982-5', '347-3-34726982-5', 'MBTC', '', 'PHP', 0, 4, '0000-00-00 00:00:00', 0, 0),
('1-1040', 0, 'CIB-BPI Family Savings Bank (67)', '', 'BPIFSB', '', 'PHP', 0, 5, '0000-00-00 00:00:00', 0, 0),
('1-1045', 0, 'CIB-BPIFSB  (KN)', '5793-0031-39', 'BPIFSB (KN)', '', 'PHP', 0, 6, '0000-00-00 00:00:00', 0, 0),
('1-1050', 0, 'CIB-BPI (53)', '', 'BPI ', '', 'PHP', 0, 7, '0000-00-00 00:00:00', 0, 0),
('1-1055', 0, 'CIB - LAND BANK', '', 'LAND BANK', '', 'PHP', 0, 8, '0000-00-00 00:00:00', 0, 0),
('1-1060', 0, 'CIB - BPIFSB Maxi Saver', '', 'BPIFSB', '', 'PHP', 0, 9, '0000-00-00 00:00:00', 0, 0),
('1-1010', 3, 'Cash Account', '', '', '', 'PHP', 0, 10, '0000-00-00 00:00:00', 0, 0);
