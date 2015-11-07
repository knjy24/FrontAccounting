-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2014 at 08:50 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ss`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_authors`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_authors`
--

INSERT INTO `0_authors` (`id`, `author_fname`, `author_mname`, `author_lname`, `author_birthdate`, `author_address`, `author_contact_number`, `author_email`) VALUES
(1, 'Karen Joy', '', 'Calado', '1993-07-24', 'Swamp', '12345678910', 'karenpalaka@yahoo.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
