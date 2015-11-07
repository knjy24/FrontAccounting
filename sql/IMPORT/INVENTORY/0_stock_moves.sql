-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2015 at 03:42 AM
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
-- Table structure for table `0_stock_moves`
--

CREATE TABLE IF NOT EXISTS `0_stock_moves` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(25) NOT NULL DEFAULT '',
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

--
-- Dumping data for table `0_stock_moves`
--

INSERT INTO `0_stock_moves` (`trans_id`, `trans_no`, `stock_id`, `type`, `loc_code`, `tran_date`, `person_id`, `price`, `reference`, `qty`, `discount_percent`, `standard_cost`, `visible`) VALUES
(1, 1, '(NP)971-625-129-7', 17, 'QC', '2014-12-31', 0, 0, '1', 158, 0, 21.16, 1),
(2, 1, '(NP)978-971-625-180-7', 17, 'QC', '2014-12-31', 0, 0, '1', 227, 0, 26.57, 1),
(3, 1, '(NP)971-625-083-5', 17, 'QC', '2014-12-31', 0, 0, '1', 381, 0, 21.91, 1),
(4, 1, '(NP)971-625-084-3', 17, 'QC', '2014-12-31', 0, 0, '1', 1709, 0, 26.07, 1),
(5, 1, '(NP)971-625-133-5', 17, 'QC', '2014-12-31', 0, 0, '1', 1714, 0, 22.45, 1),
(6, 1, '(NP)978-971-625-219-4', 17, 'QC', '2014-12-31', 0, 0, '1', 368, 0, 22.98, 1),
(7, 1, '(NP)978-971-625-220-0', 17, 'QC', '2014-12-31', 0, 0, '1', 7, 0, 24.08, 1),
(8, 1, '(NP)978-971-625-218-7', 17, 'QC', '2014-12-31', 0, 0, '1', 1170, 0, 24.88, 1),
(9, 1, '(NP)978-971-625-243-9', 17, 'QC', '2014-12-31', 0, 0, '1', 969, 0, 24.47, 1),
(10, 1, '(NP)978-971-625-244-6', 17, 'QC', '2014-12-31', 0, 0, '1', 348, 0, 31.86, 1),
(11, 1, '(NP)978-971-625-245-3', 17, 'QC', '2014-12-31', 0, 0, '1', 2581, 0, 33.07, 1),
(12, 1, '(NP)971-625-169-6', 17, 'QC', '2014-12-31', 0, 0, '1', 306, 0, 28.61, 1),
(13, 1, '(NP)971-625-170X', 17, 'QC', '2014-12-31', 0, 0, '1', 668, 0, 30.8, 1),
(14, 1, '(NP)978-971-625-232-3', 17, 'QC', '2014-12-31', 0, 0, '1', 1780, 0, 32.43, 1),
(15, 1, '(NP)978-971-625-233-0', 17, 'QC', '2014-12-31', 0, 0, '1', 1932, 0, 36.81, 1),
(16, 1, '(NP)978-971-625-234-7', 17, 'QC', '2014-12-31', 0, 0, '1', 4720, 0, 34.29, 1),
(17, 1, '(NP)978-971-625-177-7', 17, 'QC', '2014-12-31', 0, 0, '1', 105, 0, 35.19, 1),
(18, 1, '(NP)978-971-625-179-1', 17, 'QC', '2014-12-31', 0, 0, '1', 1590, 0, 32.27, 1),
(19, 1, '(NP)978-971-625-226-2', 17, 'QC', '2014-12-31', 0, 0, '1', 1331, 0, 25.59, 1),
(20, 1, '(NP)978-971-625-227-9', 17, 'QC', '2014-12-31', 0, 0, '1', 518, 0, 33.75, 1),
(21, 1, '(NP)978-971-625-228-6', 17, 'QC', '2014-12-31', 0, 0, '1', 2155, 0, 33.75, 1),
(22, 1, '(NP)978-971-625-214-9', 17, 'QC', '2014-12-31', 0, 0, '1', 501, 0, 41.98, 1),
(23, 1, '(NP)978-971-625-182-1', 17, 'QC', '2014-12-31', 0, 0, '1', 28, 0, 29.34, 1),
(24, 1, '(NP)978-971-625-216-3', 17, 'QC', '2014-12-31', 0, 0, '1', 1251, 0, 33.42, 1),
(25, 1, '(NP)978-971-625-217-0', 17, 'QC', '2014-12-31', 0, 0, '1', 1630, 0, 36.11, 1),
(26, 1, '(NP)978-971-625-194-4', 17, 'QC', '2014-12-31', 0, 0, '1', 45, 0, 30.12, 1),
(27, 1, '(NP)978-971-625-195-1', 17, 'QC', '2014-12-31', 0, 0, '1', 932, 0, 32.8, 1),
(28, 1, '978-971-625-223-1', 17, 'QC', '2014-12-31', 0, 0, '1', 2599, 0, 24.45, 1),
(29, 1, '978-971-625-224-8', 17, 'QC', '2014-12-31', 0, 0, '1', 1548, 0, 30.37, 1),
(30, 1, '978-971-625-225-5', 17, 'QC', '2014-12-31', 0, 0, '1', 1592, 0, 36.04, 1),
(31, 1, '(NP)978-971-625-171-5', 17, 'QC', '2014-12-31', 0, 0, '1', 421, 0, 30.84, 1),
(32, 1, '(NP)978-971-625-172-2', 17, 'QC', '2014-12-31', 0, 0, '1', 43, 0, 35.49, 1),
(33, 1, '(NP)978-971-625-173-9', 17, 'QC', '2014-12-31', 0, 0, '1', 1440, 0, 42.43, 1),
(34, 1, '(NP)978-971-625-229-3', 17, 'QC', '2014-12-31', 0, 0, '1', 436, 0, 22.82, 1),
(35, 1, '(NP)978-971-625-230-9', 17, 'QC', '2014-12-31', 0, 0, '1', 166, 0, 29.63, 1),
(36, 1, '(NP)978-971-625-231-6', 17, 'QC', '2014-12-31', 0, 0, '1', 1210, 0, 39.62, 1),
(37, 1, '(NP)971-625-165-3', 17, 'QC', '2014-12-31', 0, 0, '1', 225, 0, 30.05, 1),
(38, 1, '(NP)971-625-166-1', 17, 'QC', '2014-12-31', 0, 0, '1', 1816, 0, 38.14, 1),
(39, 1, '(NP)978-971-625-181-4', 17, 'QC', '2014-12-31', 0, 0, '1', 693, 0, 28.32, 1),
(40, 1, '(NP)978-971-625-183-8', 17, 'QC', '2014-12-31', 0, 0, '1', 141, 0, 34.83, 1),
(41, 1, '(NP)978-971-625-196-8', 17, 'QC', '2014-12-31', 0, 0, '1', 1879, 0, 36.49, 1),
(42, 1, '(NP)978-971-625-197-5', 17, 'QC', '2014-12-31', 0, 0, '1', 1602, 0, 34.09, 1),
(43, 1, '(NP)978-971-625-198-2', 17, 'QC', '2014-12-31', 0, 0, '1', 1695, 0, 39.19, 1),
(44, 1, '(NP)978-971-625-190-6', 17, 'QC', '2014-12-31', 0, 0, '1', 889, 0, 41.45, 1),
(45, 1, '(NP)978-971-625-205-7', 17, 'QC', '2014-12-31', 0, 0, '1', 592, 0, 40.95, 1),
(46, 1, '(NP)978-971-625-192-0', 17, 'QC', '2014-12-31', 0, 0, '1', 626, 0, 28.34, 1),
(47, 1, '(NP)978-971-625-191-3', 17, 'QC', '2014-12-31', 0, 0, '1', 487, 0, 32.35, 1),
(48, 1, '(NP)978-971-625-204-0', 17, 'QC', '2014-12-31', 0, 0, '1', 696, 0, 31.13, 1),
(49, 1, '(NP)978-971-625-247-7', 17, 'QC', '2014-12-31', 0, 0, '1', 432, 0, 30.43, 1),
(50, 1, '(NP)978-971-625-248-4', 17, 'QC', '2014-12-31', 0, 0, '1', 510, 0, 29.82, 1),
(51, 1, '(NP)978-971-625-249-1', 17, 'QC', '2014-12-31', 0, 0, '1', 2044, 0, 33.53, 1),
(52, 1, '(NP)978-971-625-254-5', 17, 'QC', '2014-12-31', 0, 0, '1', 3090, 0, 37.05, 1),
(53, 1, '(NP)978-971-625-255-2', 17, 'QC', '2014-12-31', 0, 0, '1', 590, 0, 38.56, 1),
(54, 1, '(NP)978-971-625-256-9', 17, 'QC', '2014-12-31', 0, 0, '1', 239, 0, 33.67, 1),
(55, 1, '(NP)978-971-625-257-6', 17, 'QC', '2014-12-31', 0, 0, '1', 364, 0, 32.71, 1),
(56, 1, '(NP)978-971-625-258-3', 17, 'QC', '2014-12-31', 0, 0, '1', 950, 0, 29.89, 1),
(57, 1, '(NP)978-971-625-259-0', 17, 'QC', '2014-12-31', 0, 0, '1', 2018, 0, 35.4, 1),
(58, 1, '(NP)978-971-625-260-6', 17, 'QC', '2014-12-31', 0, 0, '1', 3439, 0, 35.3, 1),
(59, 1, '(NP))971-625-113-0', 17, 'QC', '2014-12-31', 0, 0, '1', 50, 0, 40.37, 1),
(60, 1, '(NP))971-625-114-9', 17, 'QC', '2014-12-31', 0, 0, '1', 398, 0, 43.84, 1),
(61, 1, '(NP))971-625-115-7', 17, 'QC', '2014-12-31', 0, 0, '1', 489, 0, 33.88, 1),
(62, 1, '(NP))971-625-117-3', 17, 'QC', '2014-12-31', 0, 0, '1', 203, 0, 29.37, 1),
(63, 1, '(NP))971-625-118-1', 17, 'QC', '2014-12-31', 0, 0, '1', 321, 0, 31.15, 1),
(64, 1, '(NP))971-625-107-6', 17, 'QC', '2014-12-31', 0, 0, '1', 4, 0, 40.72, 1),
(65, 1, '(NP))971-625-108-4', 17, 'QC', '2014-12-31', 0, 0, '1', 1049, 0, 30.9, 1),
(66, 1, '(NP))971-625-109-2', 17, 'QC', '2014-12-31', 0, 0, '1', 1335, 0, 33.8, 1),
(67, 1, '(NP))971-625-110-6', 17, 'QC', '2014-12-31', 0, 0, '1', 111, 0, 34.32, 1),
(68, 1, '(NP))971-625-111-4', 17, 'QC', '2014-12-31', 0, 0, '1', 131, 0, 30.26, 1),
(69, 1, '(NP))971-625-112-2', 17, 'QC', '2014-12-31', 0, 0, '1', 42, 0, 46.07, 1),
(70, 1, '(NP))971-625-123-8', 17, 'QC', '2014-12-31', 0, 0, '1', 299, 0, 49.87, 1),
(71, 1, '(NP))971-625-124-6', 17, 'QC', '2014-12-31', 0, 0, '1', 351, 0, 47.54, 1),
(72, 1, '(NP))971-625-125-4', 17, 'QC', '2014-12-31', 0, 0, '1', 403, 0, 47.54, 1),
(73, 1, '(NP))971-625-126-2', 17, 'QC', '2014-12-31', 0, 0, '1', 18, 0, 41.55, 1),
(74, 1, '(NP))971-625-127-3', 17, 'QC', '2014-12-31', 0, 0, '1', 28, 0, 53.68, 1),
(75, 1, '(NP))971-625-128-9', 17, 'QC', '2014-12-31', 0, 0, '1', 377, 0, 44.75, 1),
(76, 1, '(NP))971-625-101-7', 17, 'QC', '2014-12-31', 0, 0, '1', 50, 0, 43.18, 1),
(77, 1, '(NP))971-625-102-5', 17, 'QC', '2014-12-31', 0, 0, '1', 24, 0, 32.58, 1),
(78, 1, '(NP))971-625-103-3', 17, 'QC', '2014-12-31', 0, 0, '1', 91, 0, 41.15, 1),
(79, 1, '(NP))971-625-104-1', 17, 'QC', '2014-12-31', 0, 0, '1', 285, 0, 40.25, 1),
(80, 1, '(NP))971-625-105-X', 17, 'QC', '2014-12-31', 0, 0, '1', 36, 0, 24.4, 1),
(81, 1, '(NP))971-625-106-8', 17, 'QC', '2014-12-31', 0, 0, '1', 70, 0, 24.4, 1),
(82, 1, '(NP))971-625-095-9', 17, 'QC', '2014-12-31', 0, 0, '1', 6, 0, 30.43, 1),
(83, 1, '(NP))971-625-096-7', 17, 'QC', '2014-12-31', 0, 0, '1', 775, 0, 31.79, 1),
(84, 1, '(NP))971-625-098-3', 17, 'QC', '2014-12-31', 0, 0, '1', 7, 0, 23.5, 1),
(85, 1, '(NP))971-625-099-1', 17, 'QC', '2014-12-31', 0, 0, '1', 14, 0, 24.85, 1),
(86, 1, '(NP))971-625-100-9', 17, 'QC', '2014-12-31', 0, 0, '1', 266, 0, 24.85, 1),
(87, 1, '978-971-625-219-4', 17, 'QC', '2014-12-31', 0, 0, '1', 3180, 0, 25.88, 1),
(88, 1, '978-971-625-243-9', 17, 'QC', '2014-12-31', 0, 0, '1', 1335, 0, 32.36, 1),
(89, 1, '978-971-625-244-6', 17, 'QC', '2014-12-31', 0, 0, '1', 2250, 0, 39.61, 1),
(90, 1, '978-971-625-211-8', 17, 'QC', '2014-12-31', 0, 0, '1', 1731, 0, 37.04, 1),
(91, 1, '978-971-625-212-5', 17, 'QC', '2014-12-31', 0, 0, '1', 2111, 0, 41.55, 1),
(92, 1, '978-971-625-226-2', 17, 'QC', '2014-12-31', 0, 0, '1', 2857, 0, 33.5, 1),
(93, 1, '978-971-625-227-9', 17, 'QC', '2014-12-31', 0, 0, '1', 2653, 0, 41.58, 1),
(94, 1, '978-971-625-228-6', 17, 'QC', '2014-12-31', 0, 0, '1', 2949, 0, 41.58, 1),
(95, 1, '978-971-625-217-0', 17, 'QC', '2014-12-31', 0, 0, '1', 887, 0, 42.25, 1),
(96, 1, '978-971-625-194-4', 17, 'QC', '2014-12-31', 0, 0, '1', 1575, 0, 42.81, 1),
(97, 1, '978-971-625-247-7', 17, 'QC', '2014-12-31', 0, 0, '1', 2894, 0, 33.69, 1),
(98, 1, '(TB)978-971-625-241-5', 17, 'QC', '2014-12-31', 0, 0, '1', 2840, 0, 13.72, 1),
(99, 1, '(NP)978-971-625-269-9', 17, 'QC', '2014-12-31', 0, 0, '1', 749, 0, 35.94, 1),
(100, 1, '(NP)978-971-625-270-5', 17, 'QC', '2014-12-31', 0, 0, '1', 631, 0, 39.28, 1),
(101, 1, '(NP)978-971-625-273-6', 17, 'QC', '2014-12-31', 0, 0, '1', 8204, 0, 37.81, 1),
(102, 1, 'S-978-971-625-243-9', 17, 'QC', '2014-12-31', 0, 0, '1', 423, 0, 30.92, 1),
(103, 1, 'S-978-971-625-244-6', 17, 'QC', '2014-12-31', 0, 0, '1', 117, 0, 42.35, 1),
(104, 1, 'S-978-971-625-245-3', 17, 'QC', '2014-12-31', 0, 0, '1', 400, 0, 34.37, 1),
(105, 1, 'S-978-971-625-232-3', 17, 'QC', '2014-12-31', 0, 0, '1', 276, 0, 30.82, 1),
(106, 1, 'S-978-971-625-234-7', 17, 'QC', '2014-12-31', 0, 0, '1', 543, 0, 41.64, 1),
(107, 1, 'S-978-971-625-247-7', 17, 'QC', '2014-12-31', 0, 0, '1', 108, 0, 30.32, 1),
(108, 1, 'S-978-971-625-248-4', 17, 'QC', '2014-12-31', 0, 0, '1', 12, 0, 37.34, 1),
(109, 1, 'S-978-971-625-249-1', 17, 'QC', '2014-12-31', 0, 0, '1', 343, 0, 40.3, 1),
(110, 1, 'S-978-971-625-263-7', 17, 'QC', '2014-12-31', 0, 0, '1', 546, 0, 37.86, 1),
(111, 1, 'S-978-971-625-264-4', 17, 'QC', '2014-12-31', 0, 0, '1', 462, 0, 42.23, 1),
(112, 1, 'S-978-971-625-265-1', 17, 'QC', '2014-12-31', 0, 0, '1', 578, 0, 46.42, 1),
(113, 1, 'S-978-971-625-268-2', 17, 'QC', '2014-12-31', 0, 0, '1', 511, 0, 48.46, 1),
(114, 1, 'S-978-971-625-267-5', 17, 'QC', '2014-12-31', 0, 0, '1', 325, 0, 56.09, 1),
(115, 1, 'S-978-971-625-266-8', 17, 'QC', '2014-12-31', 0, 0, '1', 532, 0, 53, 1),
(116, 1, 'S-978-971-625-269-9', 17, 'QC', '2014-12-31', 0, 0, '1', 2111, 0, 55.13, 1),
(117, 1, 'S-978-971-625-270-5', 17, 'QC', '2014-12-31', 0, 0, '1', 2119, 0, 49.18, 1),
(118, 1, 'S-978-971-625-271-2', 17, 'QC', '2014-12-31', 0, 0, '1', 2118, 0, 46.53, 1),
(119, 1, 'S-978-971-625-272-9', 17, 'QC', '2014-12-31', 0, 0, '1', 2187, 0, 45.49, 1),
(120, 1, 'S-978-971-625-273-6', 17, 'QC', '2014-12-31', 0, 0, '1', 2191, 0, 47.7, 1),
(121, 1, 'S-978-971-625-303-0', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 66.44, 1),
(122, 1, 'S-978-971-625-304-7', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 94.65, 1),
(123, 1, 'S-978-971-625-300-9', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 50.9, 1),
(124, 1, 'S-978-971-625-301-6', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 51.81, 1),
(125, 1, 'S-978-971-625-302-3', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 54.22, 1),
(126, 1, 'S-978-971-625-297-2', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 63.54, 1),
(127, 1, 'S-978-971-625-298-9', 17, 'QC', '2014-12-31', 0, 0, '1', 3000, 0, 61.68, 1),
(128, 2, '(NP)971-625-168-8', 17, 'QC', '2014-12-31', 0, 0, '2', 645, 0, 26.63, 1),
(129, 2, '(NP)978-971-625-213-2', 17, 'QC', '2014-12-31', 0, 0, '2', 82, 0, 34.72, 1),
(130, 2, '(NP)971-625-167-X', 17, 'QC', '2014-12-31', 0, 0, '2', 1975, 0, 32.73, 1),
(131, 2, '(NP)978-971-625-253-8', 17, 'QC', '2014-12-31', 0, 0, '2', 154, 0, 39.68, 1),
(132, 2, '(NP))971-625-116-5', 17, 'QC', '2014-12-31', 0, 0, '2', 496, 0, 29.53, 1),
(133, 2, '978-971-625-220-0', 17, 'QC', '2014-12-31', 0, 0, '2', 1237, 0, 31.1, 1),
(134, 2, '978-971-625-218-7', 17, 'QC', '2014-12-31', 0, 0, '2', 2307, 0, 28, 1),
(135, 2, '978-971-625-245-3', 17, 'QC', '2014-12-31', 0, 0, '2', 620, 0, 35.54, 1),
(136, 2, '978-971-625-232-3', 17, 'QC', '2014-12-31', 0, 0, '2', 3691, 0, 33.08, 1),
(137, 2, '978-971-625-233-0', 17, 'QC', '2014-12-31', 0, 0, '2', 1319, 0, 40.8, 1),
(138, 2, '978-971-625-234-7', 17, 'QC', '2014-12-31', 0, 0, '2', 882, 0, 45.87, 1),
(139, 2, '978-971-625-213-2', 17, 'QC', '2014-12-31', 0, 0, '2', 349, 0, 45.36, 1),
(140, 2, '978-971-625-216-3', 17, 'QC', '2014-12-31', 0, 0, '2', 171, 0, 43.9, 1),
(141, 2, '978-971-625-193-7', 17, 'QC', '2014-12-31', 0, 0, '2', 2753, 0, 33.2, 1),
(142, 2, '978-971-625-195-1', 17, 'QC', '2014-12-31', 0, 0, '2', 914, 0, 44.7, 1),
(143, 2, '978-971-625-248-4', 17, 'QC', '2014-12-31', 0, 0, '2', 1561, 0, 43.1, 1),
(144, 2, '978-971-625-249-1', 17, 'QC', '2014-12-31', 0, 0, '2', 1655, 0, 48.88, 1),
(145, 2, '(NP)978-971-625-268-2', 17, 'QC', '2014-12-31', 0, 0, '2', 1866, 0, 44.44, 1),
(146, 2, '(NP)978-971-625-267-5', 17, 'QC', '2014-12-31', 0, 0, '2', 1704, 0, 39.15, 1),
(147, 2, '(NP)978-971-625-266-8', 17, 'QC', '2014-12-31', 0, 0, '2', 1861, 0, 37.35, 1),
(148, 2, '(NP)978-971-625-263-7', 17, 'QC', '2014-12-31', 0, 0, '2', 2398, 0, 29.74, 1),
(149, 2, '(NP)978-971-625-264-4', 17, 'QC', '2014-12-31', 0, 0, '2', 3075, 0, 33.74, 1),
(150, 2, '(NP)978-971-625-265-1', 17, 'QC', '2014-12-31', 0, 0, '2', 399, 0, 34.26, 1),
(151, 3, '(NP)971-625-168-8', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 26.03, 1),
(152, 3, '(NP)978-971-625-213-2', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 36.9, 1),
(153, 3, '(NP)971-625-167-X', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 41.06, 1),
(154, 3, '(NP)978-971-625-253-8', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 60.45, 1),
(155, 3, '(NP))971-625-116-5', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 32.65, 1),
(156, 3, '978-971-625-220-0', 17, 'QC', '2014-12-31', 0, 0, '3', 4000, 0, 30.06, 1),
(157, 3, '978-971-625-218-7', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 28.01, 1),
(158, 3, '978-971-625-245-3', 17, 'QC', '2014-12-31', 0, 0, '3', 4000, 0, 39.01, 1),
(159, 3, '978-971-625-232-3', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 36.34, 1),
(160, 3, '978-971-625-233-0', 17, 'QC', '2014-12-31', 0, 0, '3', 4000, 0, 45.64, 1),
(161, 3, '978-971-625-234-7', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 46.18, 1),
(162, 3, '978-971-625-213-2', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 45.67, 1),
(163, 3, '978-971-625-216-3', 17, 'QC', '2014-12-31', 0, 0, '3', 5000, 0, 47.18, 1),
(164, 3, '978-971-625-193-7', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 33.41, 1),
(165, 3, '978-971-625-195-1', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 45, 1),
(166, 3, '978-971-625-248-4', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 40.68, 1),
(167, 3, '978-971-625-249-1', 17, 'QC', '2014-12-31', 0, 0, '3', 3000, 0, 46.81, 1),
(168, 3, '(NP)978-971-625-268-2', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 38.66, 1),
(169, 3, '(NP)978-971-625-267-5', 17, 'QC', '2014-12-31', 0, 0, '3', 2000, 0, 45.01, 1),
(170, 3, '(NP)978-971-625-266-8', 17, 'QC', '2014-12-31', 0, 0, '3', 4000, 0, 42.75, 1),
(171, 3, '(NP)978-971-625-265-1', 17, 'QC', '2014-12-31', 0, 0, '3', 4000, 0, 35.48, 1),
(172, 4, '978-971-625-234-7', 17, 'QC', '2014-12-31', 0, 0, '4', 2000, 0, 49.18, 1),
(173, 5, '978-971-625-223-1', 17, 'QC', '2014-12-31', 0, 0, '5', 2399, 0, 32.75, 1),
(174, 5, '978-971-625-224-8', 17, 'QC', '2014-12-31', 0, 0, '5', 2976, 0, 38.5, 1),
(175, 5, '978-971-625-225-5', 17, 'QC', '2014-12-31', 0, 0, '5', 1901, 0, 44, 1),
(176, 5, '(NP)978-971-625-271-2', 17, 'QC', '2014-12-31', 0, 0, '5', 246, 0, 35.49, 1),
(177, 5, '(NP)978-971-625-272-9', 17, 'QC', '2014-12-31', 0, 0, '5', 5182, 0, 34.7, 1);