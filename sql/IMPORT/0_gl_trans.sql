-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 04:30 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `final`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_gl_trans`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `0_gl_trans`
--

INSERT INTO `0_gl_trans` (`counter`, `type`, `type_no`, `tran_date`, `account`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`) VALUES
(1, 0, 1, '2014-12-31', '1-1010', '', 107.25, 0, 0, NULL, NULL),
(2, 0, 1, '2014-12-31', '1-1015', '', 3200, 0, 0, NULL, NULL),
(3, 0, 1, '2014-12-31', '1-1020', '', 4960346.43, 0, 0, NULL, NULL),
(4, 0, 1, '2014-12-31', '1-1025', '', 4999931.63, 0, 0, NULL, NULL),
(5, 0, 1, '2014-12-31', '1-1030', '', 759570.97, 0, 0, NULL, NULL),
(6, 0, 1, '2014-12-31', '1-1035', '', 245548.24, 0, 0, NULL, NULL),
(7, 0, 1, '2014-12-31', '1-1040', '', 215482.16, 0, 0, NULL, NULL),
(8, 0, 1, '2014-12-31', '1-1050', '', 290910.05, 0, 0, NULL, NULL),
(9, 0, 1, '2014-12-31', '1-1055', '', 1046121.53, 0, 0, NULL, NULL),
(10, 0, 1, '2014-12-31', '1-1060', '', 17235119.27, 0, 0, NULL, NULL),
(11, 0, 1, '2014-12-31', '1-2010', '', 1198267.43, 0, 0, NULL, NULL),
(12, 0, 1, '2014-12-31', '1-2015', '', 1418795.01, 0, 0, NULL, NULL),
(13, 0, 1, '2014-12-31', '1-2020', '', 1373925.98, 0, 0, NULL, NULL),
(14, 0, 1, '2014-12-31', '1-2025', '', 6509777.4, 0, 0, NULL, NULL),
(15, 0, 1, '2014-12-31', '1-2040', '', 48725, 0, 0, NULL, NULL),
(16, 0, 1, '2014-12-31', '1-2045', '', 25000, 0, 0, NULL, NULL),
(17, 0, 1, '2014-12-31', '1-2050', '', 194366, 0, 0, NULL, NULL),
(18, 0, 1, '2014-12-31', '1-2055', '', 51339.49, 0, 0, NULL, NULL),
(19, 0, 1, '2014-12-31', '1-2065', '', 1304.2, 0, 0, NULL, NULL),
(20, 0, 1, '2014-12-31', '1-2070', '', 13673, 0, 0, NULL, NULL),
(21, 0, 1, '2014-12-31', '1-3010', '', 81131.91, 0, 0, NULL, NULL),
(22, 0, 1, '2014-12-31', '1-3015', '', 6490.6, 0, 0, NULL, NULL),
(23, 0, 1, '2014-12-31', '1-3020', '', 213557.85, 0, 0, NULL, NULL),
(24, 0, 1, '2014-12-31', '1-3025', '', 182624.1, 0, 0, NULL, NULL),
(25, 0, 1, '2014-12-31', '1-3035', '', 9035.21, 0, 0, NULL, NULL),
(26, 0, 1, '2014-12-31', '1-3045', '', 32.55, 0, 0, NULL, NULL),
(27, 0, 1, '2014-12-31', '1-4010', '', 6214914.82, 0, 0, NULL, NULL),
(28, 0, 1, '2014-12-31', '1-5010', '', 241352.01, 0, 0, NULL, NULL),
(29, 0, 1, '2014-12-31', '1-6010', '', 14575000, 0, 0, NULL, NULL),
(30, 0, 1, '2014-12-31', '1-6015', '', 1284147.17, 0, 0, NULL, NULL),
(31, 0, 1, '2014-12-31', '1-6020', '', 1098665.28, 0, 0, NULL, NULL),
(32, 0, 1, '2014-12-31', '1-6025', '', 655425.83, 0, 0, NULL, NULL),
(33, 0, 1, '2014-12-31', '1-6030', '', 100379.54, 0, 0, NULL, NULL),
(34, 0, 1, '2014-12-31', '1-6035', '', 9084810, 0, 0, NULL, NULL),
(35, 0, 1, '2014-12-31', '1-6040', '', 1928625, 0, 0, NULL, NULL),
(36, 0, 1, '2014-12-31', '1-6010', '', -5755849.04, 0, 0, NULL, NULL),
(37, 0, 1, '2014-12-31', '1-6015', '', -932761.24, 0, 0, NULL, NULL),
(38, 0, 1, '2014-12-31', '1-6020', '', -442388.33, 0, 0, NULL, NULL),
(39, 0, 1, '2014-12-31', '1-6025', '', -392127.97, 0, 0, NULL, NULL),
(40, 0, 1, '2014-12-31', '1-6030', '', -48297.44, 0, 0, NULL, NULL),
(41, 0, 1, '2014-12-31', '1-6040', '', -45258.63, 0, 0, NULL, NULL),
(42, 0, 1, '2014-12-31', '2-1010', '', -1334960.83, 0, 0, NULL, NULL),
(43, 0, 1, '2014-12-31', '2-1015', '', -279838.75, 0, 0, NULL, NULL),
(44, 0, 1, '2014-12-31', '2-1020', '', -91858.05, 0, 0, NULL, NULL),
(45, 0, 1, '2014-12-31', '2-1030', '', -31552, 0, 0, NULL, NULL),
(46, 0, 1, '2014-12-31', '2-1035', '', -9600, 0, 0, NULL, NULL),
(47, 0, 1, '2014-12-31', '2-1040', '', -10982, 0, 0, NULL, NULL),
(48, 0, 1, '2014-12-31', '2-1045', '', -11871.17, 0, 0, NULL, NULL),
(49, 0, 1, '2014-12-31', '2-1050', '', -14887.27, 0, 0, NULL, NULL),
(50, 0, 1, '2014-12-31', '2-1060', '', -182190.24, 0, 0, NULL, NULL),
(51, 0, 1, '2014-12-31', '2-1075', '', -123779.49, 0, 0, NULL, NULL),
(52, 0, 1, '2014-12-31', '2-1070', '', -413998.45, 0, 0, NULL, NULL),
(53, 0, 1, '2014-12-31', '2-2010', '', -1648658, 0, 0, NULL, NULL),
(54, 0, 1, '2014-12-31', '2-2015', '', 137246.96, 0, 0, NULL, NULL),
(55, 0, 1, '2014-12-31', '2-3010', '', -1384.62, 0, 0, NULL, NULL),
(56, 0, 1, '2014-12-31', '3-1010', '', -21944300, 0, 0, NULL, NULL),
(57, 0, 1, '2014-12-31', '3-1030', '', -42688406.35, 0, 0, NULL, NULL);
