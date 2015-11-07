-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 01:32 AM
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
-- Table structure for table `0_areas`
--

CREATE TABLE IF NOT EXISTS `0_areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

--
-- Dumping data for table `0_areas`
--

INSERT INTO `0_areas` (`area_code`, `description`, `inactive`) VALUES
(1, 'Team Manila', 0),
(2, 'Caloocan City', 0),
(3, 'Isabela City', 0),
(4, 'Tarlac City', 0),
(5, 'Nueva Ecija', 0),
(6, 'Pasay City', 0),
(7, 'Malabon City', 0),
(8, 'Quezon City', 0),
(9, 'Mandaluyong City', 0),
(10, 'Marikina City', 0),
(11, 'Makati City', 0),
(12, 'Pasig City', 0),
(13, 'San Juan City', 0),
(14, 'Paranaque City', 0),
(15, 'Las Pinas City', 0),
(16, 'Valenzuela City', 0),
(17, 'Navotas City', 0),
(18, 'Taguig City', 0),
(19, 'Pateros City', 0),
(20, 'Muntinlupa City', 0),
(21, 'Ilocos Norte', 0),
(22, 'Ilocos Sur', 0),
(23, 'La Union', 0),
(24, 'Pangasinan', 0),
(25, 'Dagupan City', 0),
(26, 'Laoag City', 0),
(27, 'San Carlos City', 0),
(28, 'Candon City', 0),
(29, 'Alaminos City', 0),
(30, 'Vigan City', 0),
(31, 'San Fernando City', 0),
(32, 'Cagayan', 0),
(33, 'Cauayan City', 0),
(34, 'Nieva Vizcaya', 0),
(35, 'Qurino', 0),
(36, 'Aurora', 0),
(37, 'Bataan', 0),
(38, 'Bulacan', 0),
(39, 'Pampanga', 0),
(40, 'Zambales', 0),
(41, 'Angeles City', 0),
(42, 'Cabanatuan City', 0),
(43, 'Olongapo City', 0),
(44, 'Gapan City', 0),
(45, 'Munoz City', 0),
(46, 'Balanga City', 0),
(47, 'Batangas', 0),
(48, 'Cavite', 0),
(49, 'laguna', 0),
(50, 'Quezon', 0),
(51, 'Rizal', 0),
(52, 'Batangas City', 0),
(53, 'Lipa City', 0),
(54, 'Lucena City', 0),
(55, 'Calamba laguna', 0),
(56, 'Antipolo City', 0),
(57, 'Tanauan City', 0),
(58, 'Marinduque', 0),
(59, 'Occidental Mindoro', 0),
(60, 'oriental Mindoro', 0),
(61, 'Palawan', 0),
(62, 'Romblon', 0),
(63, 'Puerto Princesa', 0),
(64, 'Calapan City', 0),
(65, 'Aklan', 0),
(66, 'Antique', 0),
(67, 'Capiz', 0),
(68, 'Guimaras', 0),
(69, 'Ilo-ilo', 0),
(70, 'Negros occidental', 0),
(71, 'Bacolod City', 0),
(72, 'Bago City', 0),
(73, 'La Carlota', 0),
(74, 'Roxas City', 0),
(75, 'Sagay City', 0),
(76, 'Kabankalan City', 0),
(77, 'Passi City', 0),
(78, 'Bohol', 0),
(79, 'Cebu City', 0),
(80, 'Dumaguete City', 0),
(81, 'Lapu-lapu City', 0),
(82, 'Mandaue City', 0),
(83, 'Negros Oriental', 0),
(84, 'Siquejor', 0),
(85, 'Toledo City', 0),
(86, 'Bayanwan City', 0),
(87, 'Danao City', 0),
(88, 'Zamboanga Del Norte', 0),
(89, 'Zamboanga del Sur', 0),
(90, 'Zamboanga Sibugay', 0),
(91, 'Dapitan City', 0),
(92, 'Pagadian City', 0),
(93, 'Zamboanga City', 0),
(94, 'Bukidnon', 0),
(95, 'Camiguin', 0),
(96, 'lanao Del Norte', 0),
(97, 'Misamis occidental', 0),
(98, 'Misamis Oriental', 0),
(99, 'Cagayan de oro City', 0),
(100, 'Ginoog City', 0),
(101, 'Iligan City', 0),
(102, 'ozamis City', 0),
(103, 'Oroquita City', 0),
(104, 'Tangub City', 0),
(105, 'Valencia', 0),
(106, 'Compostela Valley', 0),
(107, 'Daval Del Norte', 0),
(108, 'Davao Del Sur', 0),
(109, 'Davao Oriental', 0),
(110, 'Davao City', 0),
(111, 'Digos ', 0),
(112, 'panabo', 0),
(113, 'Tagum', 0),
(114, 'Nort Cotabato', 0),
(115, 'Sarangani', 0),
(116, 'South Cotabato', 0),
(117, 'Sultan Kudarat', 0),
(118, 'Cotabato City', 0),
(119, 'General Santos City', 0),
(120, 'Kadapawan City', 0),
(121, 'Koronadal City', 0),
(122, 'Tacurong City', 0),
(123, 'Abra', 0),
(124, 'Apayao', 0),
(125, 'Benguet', 0),
(126, 'Kalinga', 0),
(127, 'Mt. Province', 0),
(128, 'Baguio City', 0),
(129, 'Binangonan Rizal', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
