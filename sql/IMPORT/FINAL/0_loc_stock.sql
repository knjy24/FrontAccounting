-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2015 at 04:25 PM
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
-- Table structure for table `0_loc_stock`
--

CREATE TABLE IF NOT EXISTS `0_loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(25) NOT NULL DEFAULT '',
  `reorder_level` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_loc_stock`
--

INSERT INTO `0_loc_stock` (`loc_code`, `stock_id`, `reorder_level`) VALUES
('QC', '(NP)971-625-083-5', 0),
('QC', '(NP)971-625-084-3', 0),
('QC', '(NP)971-625-095-9', 0),
('QC', '(NP)971-625-096-7', 0),
('QC', '(NP)971-625-098-3', 0),
('QC', '(NP)971-625-099-1', 0),
('QC', '(NP)971-625-100-9', 0),
('QC', '(NP)971-625-101-7', 0),
('QC', '(NP)971-625-102-5', 0),
('QC', '(NP)971-625-103-3', 0),
('QC', '(NP)971-625-104-1', 0),
('QC', '(NP)971-625-105-X', 0),
('QC', '(NP)971-625-106-8', 0),
('QC', '(NP)971-625-107-6', 0),
('QC', '(NP)971-625-108-4', 0),
('QC', '(NP)971-625-109-2', 0),
('QC', '(NP)971-625-110-6', 0),
('QC', '(NP)971-625-111-4', 0),
('QC', '(NP)971-625-112-2', 0),
('QC', '(NP)971-625-113-0', 0),
('QC', '(NP)971-625-114-9', 0),
('QC', '(NP)971-625-115-7', 0),
('QC', '(NP)971-625-116-5', 0),
('QC', '(NP)971-625-117-3', 0),
('QC', '(NP)971-625-118-1', 0),
('QC', '(NP)971-625-123-8', 0),
('QC', '(NP)971-625-124-6', 0),
('QC', '(NP)971-625-125-4', 0),
('QC', '(NP)971-625-126-2', 0),
('QC', '(NP)971-625-127-3', 0),
('QC', '(NP)971-625-128-9', 0),
('QC', '(NP)971-625-129-7', 0),
('QC', '(NP)971-625-133-5', 0),
('QC', '(NP)971-625-165-3', 0),
('QC', '(NP)971-625-166-1', 0),
('QC', '(NP)971-625-167-X', 0),
('QC', '(NP)971-625-168-8', 0),
('QC', '(NP)971-625-169-6', 0),
('QC', '(NP)971-625-170X', 0),
('QC', '(NP)978-971-625-171-5', 0),
('QC', '(NP)978-971-625-172-2', 0),
('QC', '(NP)978-971-625-173-9', 0),
('QC', '(NP)978-971-625-177-7', 0),
('QC', '(NP)978-971-625-179-1', 0),
('QC', '(NP)978-971-625-180-7', 0),
('QC', '(NP)978-971-625-181-4', 0),
('QC', '(NP)978-971-625-182-1', 0),
('QC', '(NP)978-971-625-183-8', 0),
('QC', '(NP)978-971-625-190-6', 0),
('QC', '(NP)978-971-625-191-3', 0),
('QC', '(NP)978-971-625-192-0', 0),
('QC', '(NP)978-971-625-194-4', 0),
('QC', '(NP)978-971-625-195-1', 0),
('QC', '(NP)978-971-625-196-8', 0),
('QC', '(NP)978-971-625-197-5', 0),
('QC', '(NP)978-971-625-198-2', 0),
('QC', '(NP)978-971-625-204-0', 0),
('QC', '(NP)978-971-625-205-7', 0),
('QC', '(NP)978-971-625-213-2', 0),
('QC', '(NP)978-971-625-214-9', 0),
('QC', '(NP)978-971-625-216-3', 0),
('QC', '(NP)978-971-625-217-0', 0),
('QC', '(NP)978-971-625-218-7', 0),
('QC', '(NP)978-971-625-219-4', 0),
('QC', '(NP)978-971-625-220-0', 0),
('QC', '(NP)978-971-625-223-1', 0),
('QC', '(NP)978-971-625-224-8', 0),
('QC', '(NP)978-971-625-225-5', 0),
('QC', '(NP)978-971-625-226-2', 0),
('QC', '(NP)978-971-625-227-9', 0),
('QC', '(NP)978-971-625-228-6', 0),
('QC', '(NP)978-971-625-229-3', 0),
('QC', '(NP)978-971-625-230-9', 0),
('QC', '(NP)978-971-625-231-6', 0),
('QC', '(NP)978-971-625-232-3', 0),
('QC', '(NP)978-971-625-233-0', 0),
('QC', '(NP)978-971-625-234-7', 0),
('QC', '(NP)978-971-625-241-5', 0),
('QC', '(NP)978-971-625-243-9', 0),
('QC', '(NP)978-971-625-244-6', 0),
('QC', '(NP)978-971-625-245-3', 0),
('QC', '(NP)978-971-625-247-7', 0),
('QC', '(NP)978-971-625-248-4', 0),
('QC', '(NP)978-971-625-249-1', 0),
('QC', '(NP)978-971-625-253-8', 0),
('QC', '(NP)978-971-625-254-5', 0),
('QC', '(NP)978-971-625-255-2', 0),
('QC', '(NP)978-971-625-256-9', 0),
('QC', '(NP)978-971-625-257-6', 0),
('QC', '(NP)978-971-625-258-3', 0),
('QC', '(NP)978-971-625-259-0', 0),
('QC', '(NP)978-971-625-260-6', 0),
('QC', '(NP)978-971-625-269-9', 0),
('QC', '(NP)978-971-625-270-5', 0),
('QC', '(NP)978-971-625-271-2', 0),
('QC', '(NP)978-971-625-272.9', 0),
('QC', '(NP)978-971-625-273-6', 0),
('QC', '978-971-625-193-7', 0),
('QC', '978-971-625-194-4', 0),
('QC', '978-971-625-195-1', 0),
('QC', '978-971-625-211-8', 0),
('QC', '978-971-625-212-5', 0),
('QC', '978-971-625-213-2', 0),
('QC', '978-971-625-216-3', 0),
('QC', '978-971-625-217-0', 0),
('QC', '978-971-625-218-7', 0),
('QC', '978-971-625-219-4', 0),
('QC', '978-971-625-220-0', 0),
('QC', '978-971-625-223-1', 0),
('QC', '978-971-625-224-8', 0),
('QC', '978-971-625-225-5', 0),
('QC', '978-971-625-226-2', 0),
('QC', '978-971-625-227-9', 0),
('QC', '978-971-625-228-6', 0),
('QC', '978-971-625-232-3', 0),
('QC', '978-971-625-233-0', 0),
('QC', '978-971-625-234-7', 0),
('QC', '978-971-625-243-9', 0),
('QC', '978-971-625-244-6', 0),
('QC', '978-971-625-245-3', 0),
('QC', '978-971-625-247-7', 0),
('QC', '978-971-625-248-4', 0),
('QC', '978-971-625-249-1', 0),
('QC', '978-971-625-263-7', 0),
('QC', '978-971-625-264-4', 0),
('QC', '978-971-625-265-1', 0),
('QC', '978-971-625-266-8', 0),
('QC', '978-971-625-267-5', 0),
('QC', '978-971-625-268-2', 0),
('QC', 'S-978-971-625-234-7', 0),
('QC', 'S-978-971-625-243-9', 0),
('QC', 'S-978-971-625-247-7', 0),
('QC', 'S-978-971-625-248-4', 0),
('QC', 'S-978-971-625-249-1', 0),
('QC', 'S-978-971-625-263-7', 0),
('QC', 'S-978-971-625-264-4', 0),
('QC', 'S-978-971-625-265-1', 0),
('QC', 'S-978-971-625-266-8', 0),
('QC', 'S-978-971-625-267-5', 0),
('QC', 'S-978-971-625-268-2', 0),
('QC', 'S-978-971-625-269-9', 0),
('QC', 'S-978-971-625-270-5', 0),
('QC', 'S-978-971-625-271-2', 0),
('QC', 'S-978-971-625-272-9', 0),
('QC', 'S-978-971-625-273-6', 0),
('QC', 'S-978-971-625-297-2', 0),
('QC', 'S-978-971-625-298-9', 0),
('QC', 'S-978-971-625-300-9', 0),
('QC', 'S-978-971-625-301-6', 0),
('QC', 'S-978-971-625-302-3', 0),
('QC', 'S-978-971-625-303-0', 0),
('QC', 'S-978-971-625-304-7', 0);
