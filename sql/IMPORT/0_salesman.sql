-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 02:13 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `samp`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_salesman`
--

CREATE TABLE IF NOT EXISTS `0_salesman` (
  `imc_code` varchar(20) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `0_salesman`
--

INSERT INTO `0_salesman` (`imc_code`, `salesman_code`, `salesman_name`, `salesman_phone`, `salesman_fax`, `salesman_email`, `provision`, `break_pt`, `provision2`, `inactive`) VALUES
('ARG', 1, 'Adrian R. Gomez', '', '', '', 0, 0, 0, 0),
('AM', 2, 'Amelia Mariano', '', '', '', 0, 0, 0, 0),
('CC', 3, 'Cesar Castillo', '', '', '', 0, 0, 0, 0),
('CSB', 4, 'Consuelo S. Bernabe', '', '', '', 0, 0, 0, 0),
('DA', 5, 'Darren Arias', '', '', '', 0, 0, 0, 0),
('DM', 6, 'Dina Mactal', '', '', '', 0, 0, 0, 0),
('DOM', 7, 'District Of Manila', '', '', '', 0, 0, 0, 0),
('EDH', 8, 'Edemar Henerale', '', '', '', 0, 0, 0, 0),
('EC', 9, 'Eleonor Canapi', '', '', '', 0, 0, 0, 0),
('ERH', 10, 'Erlinda Huvalla', '', '', '', 0, 0, 0, 0),
('EFC', 11, 'Evelyn F. Celis', '', '', '', 0, 0, 0, 0),
('FC', 12, 'Fe Calderon', '', '', '', 0, 0, 0, 0),
('GV', 13, 'Gido Violago', '', '', '', 0, 0, 0, 0),
('HB', 14, 'Hazel Babiano', '', '', '', 0, 0, 0, 0),
('JI', 15, 'Jeffrey Illustrisimo', '', '', '', 0, 0, 0, 0),
('JCR', 16, 'June Corazon Reyno', '', '', '', 0, 0, 0, 0),
('LV', 17, 'Lorena Violago', '', '', '', 0, 0, 0, 0),
('LM', 18, 'Lucia Mara', '', '', '', 0, 0, 0, 0),
('LP', 19, 'Luke Perea', '', '', '', 0, 0, 0, 0),
('MDG', 20, 'Marcelino De Guzman', '', '', '', 0, 0, 0, 0),
('MDC', 21, 'Marlyn Dela Cruz', '', '', '', 0, 0, 0, 0),
('NS', 22, 'Nanette Saquing', '', '', '', 0, 0, 0, 0),
('NB', 23, 'Nida Buenaventura', '', '', '', 0, 0, 0, 0),
('NSM', 24, 'Noel Sta. Maria', '', '', '', 0, 0, 0, 0),
('NR', 25, 'Norma Rosales', '', '', '', 0, 0, 0, 0),
('RH', 26, 'Rodel Huvalla', '', '', '', 0, 0, 0, 0),
('RR', 27, 'Rommel Ronquillo', '', '', '', 0, 0, 0, 0),
('RF', 28, 'Ronnie Fedelino', '', '', '', 0, 0, 0, 0),
('RP', 29, 'Roy Polana', '', '', '', 0, 0, 0, 0),
('TM', 30, 'Tina Mercado', '', '', '', 0, 0, 0, 0),
('VCD', 31, 'Villamor C. Donato', '', '', '', 0, 0, 0, 0),
('WR', 32, 'William Romana', '', '', '', 0, 0, 0, 0),
('ANSEL/DIRECT', 33, 'Ansel/Direct', '', '', '', 0, 0, 0, 0),
('DIRECT', 34, 'Direct', '', '', '', 0, 0, 0, 0),
('EGV/REL', 35, 'Enrico G. Vasquez/Relyn', '', '', '', 0, 0, 0, 0),
('ERH/ARG', 36, 'Erlinda/Adrian', '', '', '', 0, 0, 0, 0),
('ERH/AMOR', 37, 'Erlinda/Amor', '', '', '', 0, 0, 0, 0),
('ERH/JCR', 38, 'Erlinda/June', '', '', '', 0, 0, 0, 0),
('ERH/WR', 39, 'Erlinda/William', '', '', '', 0, 0, 0, 0),
('FC/BLOCK', 40, 'Fc-Block Listed', '', '', '', 0, 0, 0, 0),
('FC/LP', 41, 'Fe/Luke', '', '', '', 0, 0, 0, 0),
('IDC', 42, 'Idc', '', '', '', 0, 0, 0, 0),
('IDC-MARIKINA', 43, 'Idc-Marikina', '', '', '', 0, 0, 0, 0),
('JCR/DA', 44, 'June/Darren', '', '', '', 0, 0, 0, 0),
('JCR/FC', 45, 'June/Fe', '', '', '', 0, 0, 0, 0),
('JCR/GV', 46, 'June/Gido', '', '', '', 0, 0, 0, 0),
('JCR/JI', 47, 'June/Jeff', '', '', '', 0, 0, 0, 0),
('JCR/LP', 48, 'June/Luke', '', '', '', 0, 0, 0, 0),
('JCR/NR', 49, 'June/Norma', '', '', '', 0, 0, 0, 0),
('NS/LV', 50, 'Nanette/Lorena', '', '', '', 0, 0, 0, 0),
('NB/JCR', 51, 'Nida/June', '', '', '', 0, 0, 0, 0),
('NB/WR', 52, 'Nida/William', '', '', '', 0, 0, 0, 0),
('RR/DA', 53, 'Rommel/Darren', '', '', '', 0, 0, 0, 0),
('RR/FC', 54, 'Rommel/Fe', '', '', '', 0, 0, 0, 0),
('RR/JI', 55, 'Rommel/Jeff', '', '', '', 0, 0, 0, 0),
('RR/LP', 56, 'Rommel/Luke', '', '', '', 0, 0, 0, 0),
('RR/MDG', 57, 'Rommel/Marcelino', '', '', '', 0, 0, 0, 0),
('WR/LP', 58, 'Wr/Lp', '', '', '', 0, 0, 0, 0),
('DEFAULT', 59, 'Sales Person Default', '', '', '', 0, 0, 0, 0);
