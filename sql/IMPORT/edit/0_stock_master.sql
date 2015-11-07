-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2015 at 02:13 AM
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
-- Table structure for table `0_stock_master`
--

CREATE TABLE IF NOT EXISTS `0_stock_master` (
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `level_id` int(11) NOT NULL DEFAULT '0',
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mb_flag` char(1) NOT NULL DEFAULT 'B',
  `sales_account` varchar(15) NOT NULL DEFAULT '4-1010',
  `cogs_account` varchar(15) NOT NULL DEFAULT '5-1010',
  `inventory_account` varchar(15) NOT NULL DEFAULT '1-4010',
  `adjustment_account` varchar(15) NOT NULL DEFAULT '1-4010',
  `assembly_account` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `actual_cost` double NOT NULL DEFAULT '0',
  `last_cost` double NOT NULL DEFAULT '0',
  `material_cost` double NOT NULL DEFAULT '0',
  `labour_cost` double NOT NULL DEFAULT '0',
  `overhead_cost` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `no_sale` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_stock_master`
--

INSERT INTO `0_stock_master` (`stock_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `level_id`, `units`, `mb_flag`, `sales_account`, `cogs_account`, `inventory_account`, `adjustment_account`, `assembly_account`, `dimension_id`, `dimension2_id`, `actual_cost`, `last_cost`, `material_cost`, `labour_cost`, `overhead_cost`, `inactive`, `no_sale`, `editable`) VALUES
('078-971-625-166-1', 8, 2, 'Writing Made Fund and Easy - Kinder', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 38.14, 0, 0, 0, 0, 0),
('716243240', 12, 2, 'Integrated Core Curr.ICC - 5 books', 'ICC/ per Set', 2, 'Set', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('971-625-129-7', 9, 2, 'Computer Literacy For Learners', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 21.16, 0, 0, 0, 0, 0),
('971-978-625-297-2', 2, 2, 'On the Road to Reading Success - 1', 'Elementary books', 4, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 36.057875, 0, 0, 0, 0, 0),
('978-971-625--225-5', 2, 2, 'Reading for the Young Minds - Prep', 'rymp', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 36.04, 0, 0, 0, 0, 0),
('978-971-625-165-3', 8, 2, 'Writing Made Fund and Easy - Nursery', '', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 30.05, 0, 0, 0, 0, 0),
('978-971-625-167-X', 8, 2, 'Writing Made Fund and Easy - Prep', '', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 32.73, 0, 0, 0, 0, 0),
('978-971-625-180-7', 4, 2, 'Child Wonders (A Think and dp Preschool Creativity Book)', '', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 26.57, 0, 0, 0, 0, 0),
('978-971-625-181-4', 7, 2, 'Pagpapahalagng Moral par sa Preschool', 'moral', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 28.32, 0, 0, 0, 0, 0),
('978-971-625-183-8', 7, 2, 'Values Education for Preschool', 'values', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 34.83, 0, 0, 0, 0, 0),
('978-971-625-193-7', 2, 2, 'Reading Made Fun and Easy - Nursery', 'rmfn', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 28.39, 0, 0, 0, 0, 0),
('978-971-625-194-4', 2, 2, 'Reading Made Fun and Easy - Kinder', 'rmfk', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 30.12, 0, 0, 0, 0, 0),
('978-971-625-195-1', 2, 2, 'Reading  Made Fun and Easy- Prep', 'Math', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 30.6, 0, 0, 0, 0, 0),
('978-971-625-211-8', 5, 2, 'Mathematics Made Fun  And Easy - Nursery', 'Math', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 28.59, 0, 0, 0, 0, 0),
('978-971-625-212-5', 5, 2, 'Mathematics Made Fun and Easy-Kinder', 'Math', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 31.9, 0, 0, 0, 0, 0),
('978-971-625-213-2', 5, 2, 'Mathematics Made Fund and Easy - Prep', 'mmfp', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 34.72, 0, 0, 0, 0, 0),
('978-971-625-214-9', 6, 2, 'Makabagong Sining ng Komunikasyon (Binagong Edisyon)', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 41.98, 0, 0, 0, 0, 0),
('978-971-625-216-3', 6, 2, 'Makabagong Paraan ng Pagbasa(Binagong Edisyon)', 'mpp', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 33.42, 0, 0, 0, 0, 0),
('978-971-625-217-0', 6, 2, 'Makabayan Tayo sa Preschool(Binagong Edisyon)', 'mpt', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 36.11, 0, 0, 0, 0, 0),
('978-971-625-218-7', 4, 2, 'Creative Arts Experiences in the Preschool(Upraged)-3', '', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 24.88, 0, 0, 0, 0, 0),
('978-971-625-219-4', 4, 2, 'Creative Art Experiences in the Preschool(Upgraded)-1', 'caw-1', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 22.98, 0, 0, 0, 0, 0),
('978-971-625-220-0', 4, 2, 'Creative Arts Experiences in the Preschool(Upraged)-2', 'cae2', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 24.08, 0, 0, 0, 0, 0),
('978-971-625-223-1', 2, 2, 'Reading for the Young Minds - Nursery', 'rym-n', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 24.45, 0, 0, 0, 0, 0),
('978-971-625-224-8', 2, 2, 'Reading for the Young Minds - Kinder', 'rym-k', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 30.37, 0, 0, 0, 0, 0),
('978-971-625-226-2', 5, 2, 'Mathematics for the Young Minds-Nursery', 'mym-n', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 25.59, 0, 0, 0, 0, 0),
('978-971-625-227-9', 5, 2, 'Mathematics for the Young Minds-Kinder', 'mym-k', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 33.75, 0, 0, 0, 0, 0),
('978-971-625-228-6', 5, 2, 'Mathematics for the Young Minds-Prep', 'mym-p', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 33.75, 0, 0, 0, 0, 0),
('978-971-625-229-3', 8, 2, 'Phonemic Awareness and Writing for the Young Minds -Nursery', '', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 22.82, 0, 0, 0, 0, 0),
('978-971-625-231-6', 8, 2, 'Phonemic Awareness and Writing for the Young Minds -Prep', '', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 39.62, 0, 0, 0, 0, 0),
('978-971-625-232-3', 1, 2, 'Language Made Fun and Easy - Nursery', 'lmfn', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 24.43, 0, 0, 0, 0, 0),
('978-971-625-233-0', 1, 2, 'Language Made Fun and Easy -Kinder', 'lmf-k', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 30.12, 0, 0, 0, 0, 0),
('978-971-625-234-7', 1, 2, 'Language Made Fun and Easy - Prep', 'lmfp', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 32.66, 0, 0, 0, 0, 0),
('978-971-625-243-9', 6, 2, 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Nursery', 'ksp-n', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 24.47, 0, 0, 0, 0, 0),
('978-971-625-244-6', 6, 2, 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino - Kinder', 'ksp-k', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 31.86, 0, 0, 0, 0, 0),
('978-971-625-245-3', 6, 2, 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino - Prep', 'ksp-p', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 27.47, 0, 0, 0, 0, 0),
('978-971-625-247-7', 3, 2, 'Wonders of Integrated Science and Health-1', 'won-1', 1, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 36.49, 0, 0, 0, 0, 0),
('978-971-625-248-4', 3, 2, 'Wonders of Integrated Science and Health-2', 'won-2', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 34.09, 0, 0, 0, 0, 0),
('978-971-625-249-1', 3, 2, 'Wonders of Integrated Science and Health-3', 'won-3', 3, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 39.19, 0, 0, 0, 0, 0),
('978-971-625-263-7', 7, 2, 'Preschool Life Skills - Level 1', 'pls-1', 1, 'cpy', 'D', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-264-4', 7, 2, 'Preschool Life Skills - Level 2', 'pls2', 2, 'cpy', 'D', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-265-1', 7, 2, 'Preschool Life Skills - Level 3', 'pls3', 3, 'cpy', 'D', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-266-8', 8, 1, 'The Progressive Approach to To Writing-Prep', 'tpawp', 3, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-267-5', 8, 2, 'The Progressive Approach To Writing-Kinder', 'tpawk', 2, 'bot', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 17.82, 0, 0, 0, 0, 0),
('978-971-625-268-2', 8, 2, 'The Progressive Approach To Writing-Nursery', 'tpaw-n', 1, 'pc.', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-269-9', 12, 2, 'Integrated Core Curriculum - ICC K12 Week 1-7', 'icc 1-7', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 41.45, 0, 0, 0, 0, 0),
('978-971-625-270-5', 12, 2, 'Integrated core Curriculum ICC k12 Week 8-16', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 40.95, 0, 0, 0, 0, 0),
('978-971-625-271-2', 12, 2, 'Integrated core Curriculum ICC k12 Week 17-24', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 28.34, 0, 0, 0, 0, 0),
('978-971-625-272-9', 12, 2, 'Integrated core Curriculum ICC k12 Week 25-32', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 32.35, 0, 0, 0, 0, 0),
('978-971-625-273-6', 12, 2, 'Integrated core Curriculum ICC k12 Week 33-40', '', 2, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 31.13, 0, 0, 0, 0, 0),
('978-971-625-298-9', 2, 2, 'On the Road to Reading Success 2', '', 5, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-1010', 0, 0, 0, 0, 43.358875, 0, 0, 0, 0, 0),
('978-971-625-299-6', 2, 2, 'On the Road to Reading Success 3', 'On the Road to Reading Success 3', 6, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4100', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-300-9', 1, 2, 'On the Road to Language Success 1', 'On the Road to Language Success 1', 4, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-1010', 0, 0, 0, 0, 41.949125, 0, 0, 0, 0, 0),
('978-971-625-301-6', 1, 2, 'On the Road to Language Success 2', 'On the Road to Language Success 2', 5, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4100', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-302-3', 1, 2, 'On the Road to Language Success 3', 'On the Road to Language Success 3', 6, 'cpy', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4100', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('978-971-625-303-0', 5, 2, 'Exploring the World of Mathematics -1', 'Exploring the World of Mathematics 1', 4, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-1010', 0, 0, 0, 0, 68.3426, 0, 0, 0, 0, 0),
('978-971-625-304-7', 5, 2, 'Exploring the World of Mathematics 2', 'Exploring the World of Mathematics 2', 5, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-1010', 0, 0, 0, 0, 79.6264, 0, 0, 0, 0, 0),
('978-971-625-305-4', 5, 2, 'Exploring the World of Mathematics 3', '', 6, 'cpy', 'M', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 70.293875, 0, 0, 0, 0, 0),
('978971-62-5-230-9', 8, 2, 'Phonemic Awareness and Writing for the Young Minds -Kinder', '', 2, 'pc.', 'B', '4-1010', '5-1010', '1-4010', '1-4010', '1-4010', 0, 0, 0, 0, 29.63, 0, 0, 0, 0, 0),
('S-978-971-625-297-2', 1, 2, 'S-On the Road to Rdg.Success-1', 'S-On the Road to Rdg. Success 1', 4, 'cpy', 'B', '6-1230', '5-1010', '1-4010', '1-4010', '1-4100', 0, 0, 0, 0, 15.81, 0, 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
