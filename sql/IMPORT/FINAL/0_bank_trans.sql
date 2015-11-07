-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2015 at 04:26 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_trans`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `0_bank_trans`
--

INSERT INTO `0_bank_trans` (`id`, `type`, `trans_no`, `bank_act`, `ref`, `trans_date`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `reconciled`) VALUES
(1, 0, 1, '10', '1', '2014-12-31', 107.25, 0, 0, 0, '', NULL),
(2, 0, 1, '1', '1', '2014-12-31', 4960346.43, 0, 0, 0, '', NULL),
(3, 0, 1, '2', '1', '2014-12-31', 4999931.63, 0, 0, 0, '', NULL),
(4, 0, 1, '3', '1', '2014-12-31', 759570.97, 0, 0, 0, '', NULL),
(5, 0, 1, '4', '1', '2014-12-31', 245548.24, 0, 0, 0, '', NULL),
(6, 0, 1, '5', '1', '2014-12-31', 215482.16, 0, 0, 0, '', NULL),
(7, 0, 1, '7', '1', '2014-12-31', 290910.05, 0, 0, 0, '', NULL),
(8, 0, 1, '8', '1', '2014-12-31', 1046121.53, 0, 0, 0, '', NULL),
(9, 0, 1, '9', '1', '2014-12-31', 17235119.27, 0, 0, 0, '', NULL),
(10, 0, 1, '10', '1', '2014-12-31', -42688406.35, 0, 0, 0, '', NULL);
