-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 02:08 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `0_authors`
--

INSERT INTO `0_authors` (`id`, `author_fname`, `author_mname`, `author_lname`, `author_birthdate`, `author_address`, `author_contact_number`, `author_email`) VALUES
(1, 'Hazel', 'Domingo', 'Babiano', '1960-09-06', '25 Cavalier Street East F', '092113777903', 'babiano@yahoo'),
(2, 'Rowena', 'Venegas', 'Dagdag', '1970-01-23', '430 D. Reyes St., Brgy.10', '09238165478', 'hannahyohann@yahoo.com'),
(3, 'Dr. Felicidad', 'N.', 'Remo', '1939-05-02', '3817 Cuenca Street 1200 M', '8312106', ''),
(4, 'Avelina', 'S.', 'Espelita', '1929-04-29', '32 Matimtiman St., Teache', '3829773', ''),
(5, 'Dr. Luzviminda', 'L.', 'Ona', '1947-07-06', '24 Gaspar St., Pacheco Vi', '2913302', ''),
(6, 'Marie Tess', 'P.', 'Faluta', '1974-08-27', '1741 Up Bliss ', '4330544', ''),
(7, 'Valerie ', 'Cruz', 'Tapalla', '2015-01-28', 'San Juan City', '7234118', ''),
(8, 'Proserfina', 'T.', 'Santos', '1946-05-06', '28 Bonanza St., Marikina', '9426848', ''),
(9, 'Fidela', 'M.', 'Santos', '1949-04-29', '323 Morales St., Lolomboy', '6924542', ''),
(10, 'Aileen Ascencion', 'O.', 'Ruivivar', '2015-01-28', '13 St., Joseph St. Q.C.', '4546998', ''),
(11, 'Dr. Federico', 'C.', 'Castillo', '1944-03-09', '19 Italy St., Adelina 2A ', '5290726', ''),
(12, 'Ofelia', 'G.', 'Chingcuangco', '2015-01-28', '1409 A Sulu St. Sta Cruz ', '09285038874', ''),
(13, 'Jovylennie', 'V.', 'Nardo', '2015-01-28', 'Blk.37 B. Lot 15 Caloocan', '09485961215', ''),
(14, 'Nely', 'D.', 'Baylon', '2015-01-28', 'U503 MB17 BCA Taguig', '09289066375', ''),
(15, 'Dr. Isabelita', 'M. Santos', 'No.3 Domingo St., Q.C.', '2015-01-28', '#3 Domingo St.S.F.D.M. Q.', '3367261', ''),
(16, 'Dr. Menelea', 'M. ', 'Chiu', '1962-09-30', '161 Judge Juan Luna Q.C.', '3764245', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
