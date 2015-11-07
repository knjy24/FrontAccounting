-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 02:11 AM
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
-- Table structure for table `0_item_codes`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_item_codes`
--

INSERT INTO `0_item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(1, '978-971-625-229-3', '978-971-625-229-3', 'Phonemic Awareness and Writing for the Young Minds -Nursery', 8, 1, 0, 0),
(2, '978-971-625-214-9', '978-971-625-214-9', 'Makabagong Sining ng Komunikasyon (Binagong Edisyon)', 6, 1, 0, 0),
(3, '978-971-625-180-7', '978-971-625-180-7', 'Child Wonders (A Think and dp Preschool Creativity Book)', 4, 1, 0, 0),
(4, '971-625-129-7', '971-625-129-7', 'Computer Literacy For Learners', 9, 1, 0, 0),
(5, '978-971-625-211-8', '978-971-625-211-8', 'Mathematics Made Fun  And Easy - Nursery', 5, 1, 0, 0),
(6, '978-971-625-212-5', '978-971-625-212-5', 'Mathematics Made Fun and Easy-Kinder', 5, 1, 0, 0),
(7, '978-971-625-195-1', '978-971-625-195-1', 'Reading  Made Fun and Easy- Prep', 2, 1, 0, 0),
(8, '978-971-625-226-2', '978-971-625-226-2', 'Mathematics for the Young Minds-Nursery', 5, 1, 0, 0),
(9, '978-971-625-227-9', '978-971-625-227-9', 'Mathematics for the Young Minds-Kinder', 5, 1, 0, 0),
(10, '978-971-625-228-6', '978-971-625-228-6', 'Mathematics for the Young Minds-Prep', 5, 1, 0, 0),
(11, '978-971-625-248-4', '978-971-625-248-4', 'Wonders of Integrated Science and Health-2', 3, 1, 0, 0),
(12, '978-971-625-247-7', '978-971-625-247-7', 'Wonders of Integrated Science and Health-1', 3, 1, 0, 0),
(13, '978-971-625-249-1', '978-971-625-249-1', 'Wonders of Integrated Science and Health-3', 3, 1, 0, 0),
(14, '978-971-625-223-1', '978-971-625-223-1', 'Reading for the Young Minds - Nursery', 2, 1, 0, 0),
(15, '978-971-625-224-8', '978-971-625-224-8', 'Reading for the Young Minds - Kinder', 2, 1, 0, 0),
(16, '978-971-625--225-5', '978-971-625--225-5', 'Reading for the Young Minds - Prep', 2, 1, 0, 0),
(17, '978-971-625-193-7', '978-971-625-193-7', 'Reading Made Fun and Easy - Nursery', 2, 1, 0, 0),
(18, '978-971-625-194-4', '978-971-625-194-4', 'Reading Made Fun and Easy - Kinder', 2, 1, 0, 0),
(19, '978-971-625-213-2', '978-971-625-213-2', 'Mathematics Made Fund and Easy - Prep', 5, 1, 0, 0),
(20, '978-971-625-243-9', '978-971-625-243-9', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Nursery', 6, 1, 0, 0),
(21, '978-971-625-244-6', '978-971-625-244-6', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino - Kinder', 6, 1, 0, 0),
(22, '978-971-625-245-3', '978-971-625-245-3', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino - Prep', 6, 1, 0, 0),
(23, '978-971-625-216-3', '978-971-625-216-3', 'Makabagong Paraan ng Pagbasa(Binagong Edisyon)', 6, 1, 0, 0),
(24, '978-971-625-217-0', '978-971-625-217-0', 'Makabayan Tayo sa Preschool(Binagong Edisyon)', 6, 1, 0, 0),
(25, '978-971-625-232-3', '978-971-625-232-3', 'Language Made Fun and Easy - Nursery', 1, 1, 0, 0),
(26, '978-971-625-233-0', '978-971-625-233-0', 'Language Made Fun and Easy -Kinder', 1, 1, 0, 0),
(27, '978-971-625-234-7', '978-971-625-234-7', 'Language Made Fun and Easy - Prep', 1, 1, 0, 0),
(28, '978-971-625-268-2', '978-971-625-268-2', 'The Progressive Approach To Writing-Nursery', 8, 1, 0, 0),
(29, '978-971-625-267-5', '978-971-625-267-5', 'The Progressive Approach To Writing-Kinder', 8, 1, 0, 0),
(30, '978-971-625-266-8', '978-971-625-266-8', 'The Progressive Approach to To Writing-Prep', 8, 1, 0, 0),
(31, '978-971-625-181-4', '978-971-625-181-4', 'Pagpapahalagng Moral par sa Preschool', 7, 1, 0, 0),
(32, '978-971-625-263-7', '978-971-625-263-7', 'Preschool Life Skills - Level 1', 7, 1, 0, 0),
(33, '978-971-625-264-4', '978-971-625-264-4', 'Preschool Life Skills - Level 2', 7, 1, 0, 0),
(34, '978-971-625-265-1', '978-971-625-265-1', 'Preschool Life Skills - Level 3', 7, 1, 0, 0),
(35, '978-971-625-183-8', '978-971-625-183-8', 'Values Education for Preschool', 7, 1, 0, 0),
(36, '978-971-625-269-9', '978-971-625-269-9', 'Integrated Core Curriculum - ICC K12 Week 1-7', 12, 1, 0, 0),
(37, '978-971-625-270-5', '978-971-625-270-5', 'Integrated core Curriculum ICC k12 Week 8-16', 12, 1, 0, 0),
(38, '978-971-625-271-2', '978-971-625-271-2', 'Integrated core Curriculum ICC k12 Week 17-24', 12, 1, 0, 0),
(39, '978-971-625-272-9', '978-971-625-272-9', 'Integrated core Curriculum ICC k12 Week 25-32', 12, 1, 0, 0),
(40, '978-971-625-273-6', '978-971-625-273-6', 'Integrated core Curriculum ICC k12 Week 33-40', 12, 1, 0, 0),
(41, '978-971-625-219-4', '978-971-625-219-4', 'Creative Art Experiences in the Preschool(Upgraded)-1', 4, 1, 0, 0),
(42, '978-971-625-220-0', '978-971-625-220-0', 'Creative Arts Experiences in the Preschool(Upraged)-2', 4, 1, 0, 0),
(43, '978-971-625-218-7', '978-971-625-218-7', 'Creative Arts Experiences in the Preschool(Upraged)-3', 4, 1, 0, 0),
(44, '978971-62-5-230-9', '978971-62-5-230-9', 'Phonemic Awareness and Writing for the Young Minds -Kinder', 8, 1, 0, 0),
(45, '978-971-625-231-6', '978-971-625-231-6', 'Phonemic Awareness and Writing for the Young Minds -Prep', 8, 1, 0, 0),
(46, '978-971-625-165-3', '978-971-625-165-3', 'Writing Made Fund and Easy - Nursery', 8, 1, 0, 0),
(47, '078-971-625-166-1', '078-971-625-166-1', 'Writing Made Fund and Easy - Kinder', 8, 1, 0, 0),
(48, '978-971-625-167-X', '978-971-625-167-X', 'Writing Made Fund and Easy - Prep', 8, 1, 0, 0),
(49, '971-978-625-297-2', '971-978-625-297-2', 'On the Road to Reading Success - 1', 2, 1, 0, 0),
(50, '978-971-625-305-4', '978-971-625-305-4', 'Exploring the World of Mathematics 3', 5, 1, 0, 0),
(51, '978-971-625-303-0', '978-971-625-303-0', 'Exploring the World of Mathematics -1', 5, 1, 0, 0),
(52, '978-971-625-304-7', '978-971-625-304-7', 'Exploring the World of Mathematics 2', 5, 1, 0, 0),
(53, '978-971-625-301-6', '978-971-625-301-6', 'On the Road to Language Success 2', 1, 1, 0, 0),
(54, '978-971-625-302-3', '978-971-625-302-3', 'On the Road to Language Success 3', 1, 1, 0, 0),
(55, '978-971-625-300-9', '978-971-625-300-9', 'On the Road to Language Success 1', 1, 1, 0, 0),
(56, '978-971-625-298-9', '978-971-625-298-9', 'On the Road to Reading Success 2', 2, 1, 0, 0),
(57, '978-971-625-299-6', '978-971-625-299-6', 'On the Road to Reading Success 3', 2, 1, 0, 0),
(58, 'S-978-971-625-297-2', 'S-978-971-625-297-2', 'S-On the Road to Rdg.Success-1', 1, 1, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
