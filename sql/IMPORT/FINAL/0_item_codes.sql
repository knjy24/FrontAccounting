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
-- Table structure for table `0_item_codes`
--

CREATE TABLE IF NOT EXISTS `0_item_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(25) NOT NULL,
  `stock_id` varchar(25) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `category_id` smallint(6) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `is_foreign` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_id` (`stock_id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;

--
-- Dumping data for table `0_item_codes`
--

INSERT INTO `0_item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(1, '(NP)971-625-129-7', '(NP)971-625-129-7', 'Computer Literacy for Early Learners', 9, 1, 0, 0),
(2, '(NP)978-971-625-180-7', '(NP)978-971-625-180-7', 'Child Wonders (A Think and Do Preschool Creativity Book)', 4, 1, 0, 0),
(3, '(NP)971-625-083-5', '(NP)971-625-083-5', 'Creative Arts Experiences (Revised Edition)-1', 4, 1, 0, 0),
(4, '(NP)971-625-084-3', '(NP)971-625-084-3', 'Creative Arts Experiences (Revised Edition)-2', 4, 1, 0, 0),
(5, '(NP)971-625-133-5', '(NP)971-625-133-5', 'Creative Arts Experiences-3', 4, 1, 0, 0),
(6, '(NP)978-971-625-219-4', '(NP)978-971-625-219-4', 'Creative Arts Experiences (Upg. Edition)-1', 4, 1, 0, 0),
(7, '(NP)978-971-625-220-0', '(NP)978-971-625-220-0', 'Creative Arts Experiences (Upg. Edition)-2', 4, 1, 0, 0),
(8, '(NP)978-971-625-218-7', '(NP)978-971-625-218-7', 'Creative Arts Experiences (Upg. Edition)-3', 4, 1, 0, 0),
(9, '(NP)978-971-625-243-9', '(NP)978-971-625-243-9', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Nursery', 6, 1, 0, 0),
(10, '(NP)978-971-625-244-6', '(NP)978-971-625-244-6', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Kinder', 6, 1, 0, 0),
(11, '(NP)978-971-625-245-3', '(NP)978-971-625-245-3', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Prep', 6, 1, 0, 0),
(12, '(NP)971-625-168-8', '(NP)971-625-168-8', 'Language Enrichment Program-Nursery', 1, 1, 0, 0),
(13, '(NP)971-625-169-6', '(NP)971-625-169-6', 'Language Enrichment Program-Kinder', 1, 1, 0, 0),
(14, '(NP)971-625-170X', '(NP)971-625-170X', 'Language Enrichment Program-Prep', 1, 1, 0, 0),
(15, '(NP)978-971-625-232-3', '(NP)978-971-625-232-3', 'Language Made Fun and Easy-Nursery', 1, 1, 0, 0),
(16, '(NP)978-971-625-233-0', '(NP)978-971-625-233-0', 'Language Made Fun and Easy-Kinder', 1, 1, 0, 0),
(17, '(NP)978-971-625-234-7', '(NP)978-971-625-234-7', 'Language Made Fun and Easy-Prep', 1, 1, 0, 0),
(18, '(NP)978-971-625-177-7', '(NP)978-971-625-177-7', 'Mathematics in the Early Grades-Nursery', 5, 1, 0, 0),
(19, '(NP)978-971-625-179-1', '(NP)978-971-625-179-1', 'Mathematics in the Early Grades-Prep', 5, 1, 0, 0),
(20, '(NP)978-971-625-213-2', '(NP)978-971-625-213-2', 'Mathematics Made Fun &amp; Easy-Prep', 5, 1, 0, 0),
(21, '(NP)978-971-625-226-2', '(NP)978-971-625-226-2', 'Mathematics for the Young Minds-Nursery', 5, 1, 0, 0),
(22, '(NP)978-971-625-227-9', '(NP)978-971-625-227-9', 'Mathematics for the Young Minds-Kinder', 5, 1, 0, 0),
(23, '(NP)978-971-625-228-6', '(NP)978-971-625-228-6', 'Mathematics for the Young Minds-Prep', 5, 1, 0, 0),
(24, '(NP)978-971-625-214-9', '(NP)978-971-625-214-9', 'Makabagong Sining ng Komunikasyon - Binagong Edisyon', 6, 1, 0, 0),
(25, '(NP)978-971-625-182-1', '(NP)978-971-625-182-1', 'Pinayamang Pamamaraan sa Pagbasa ng Bagong Alpabetong Filipino', 6, 1, 0, 0),
(26, '(NP)978-971-625-216-3', '(NP)978-971-625-216-3', 'Makabagong Paraan ng Pagbasa (Binagong Edisyon)', 6, 1, 0, 0),
(27, '(NP)978-971-625-217-0', '(NP)978-971-625-217-0', 'Makabayan Tayo (Binagong Edisyon)', 6, 1, 0, 0),
(28, '(NP)978-971-625-194-4', '(NP)978-971-625-194-4', 'Reading Made Fun &amp; Easy-Kinder', 2, 1, 0, 0),
(29, '(NP)978-971-625-195-1', '(NP)978-971-625-195-1', 'Reading Made Fun &amp; Easy-Prep', 2, 1, 0, 0),
(30, '(NP)978-971-625-223-1', '(NP)978-971-625-223-1', 'Reading for the Young Minds-Nursery', 2, 1, 0, 0),
(31, '(NP)978-971-625-224-8', '(NP)978-971-625-224-8', 'Reading for the Young Minds-Kinder', 2, 1, 0, 0),
(32, '(NP)978-971-625-225-5', '(NP)978-971-625-225-5', 'Reading for the Young Minds-Prep', 2, 1, 0, 0),
(33, '(NP)978-971-625-171-5', '(NP)978-971-625-171-5', 'Writing in the Early Grades-Nursery', 8, 1, 0, 0),
(34, '(NP)978-971-625-172-2', '(NP)978-971-625-172-2', 'Writing in the Early Grades-Kinder', 8, 1, 0, 0),
(35, '(NP)978-971-625-173-9', '(NP)978-971-625-173-9', 'Writing in the Early Grades-Prep', 8, 1, 0, 0),
(36, '(NP)978-971-625-229-3', '(NP)978-971-625-229-3', 'Phoenemic Awareness &amp; Writing for the Young Minds-Nursery', 8, 1, 0, 0),
(37, '(NP)978-971-625-230-9', '(NP)978-971-625-230-9', 'Phoenemic Awareness &amp; Writing for the Young Minds-Kinder', 8, 1, 0, 0),
(38, '(NP)978-971-625-231-6', '(NP)978-971-625-231-6', 'Phoenemic Awareness &amp; Writing for the Young Minds-Prep', 8, 1, 0, 0),
(39, '(NP)971-625-165-3', '(NP)971-625-165-3', 'Writing Made Fun and Easy-Nursery', 8, 1, 0, 0),
(40, '(NP)971-625-166-1', '(NP)971-625-166-1', 'Writing Made Fun and Easy-Kinder', 8, 1, 0, 0),
(41, '(NP)971-625-167-X', '(NP)971-625-167-X', 'Writing Made Fun and Easy-Prep', 8, 1, 0, 0),
(42, '(NP)978-971-625-181-4', '(NP)978-971-625-181-4', 'Pagpapahalagang Moral', 7, 1, 0, 0),
(43, '(NP)978-971-625-183-8', '(NP)978-971-625-183-8', 'Values Education for Preshool', 7, 1, 0, 0),
(44, '(NP)978-971-625-196-8', '(NP)978-971-625-196-8', 'Wonders of Integrated Science for Preschool-1', 3, 1, 0, 0),
(45, '(NP)978-971-625-197-5', '(NP)978-971-625-197-5', 'Wonders of Integrated Science for Preschool-2', 3, 1, 0, 0),
(46, '(NP)978-971-625-198-2', '(NP)978-971-625-198-2', 'Wonders of Integrated Science for Preschool-3', 3, 1, 0, 0),
(47, '(NP)978-971-625-190-6', '(NP)978-971-625-190-6', 'Integrated Core Curriculum  My Self (revised)', 10, 1, 0, 0),
(48, '(NP)978-971-625-205-7', '(NP)978-971-625-205-7', 'Integrated Core Curriculum My Community ( Revised)', 10, 1, 0, 0),
(49, '(NP)978-971-625-192-0', '(NP)978-971-625-192-0', 'Integrated Core Curriculum My Family (Revised)', 10, 1, 0, 0),
(50, '(NP)978-971-625-191-3', '(NP)978-971-625-191-3', 'Integrated Core Curriculum My School (Revised)', 10, 1, 0, 0),
(51, '(NP)978-971-625-204-0', '(NP)978-971-625-204-0', 'Integrated Core Curriculum Things Around Me (Revised)', 10, 1, 0, 0),
(52, '(NP)978-971-625-247-7', '(NP)978-971-625-247-7', 'Wonders of Integrated Science &amp; Health-1', 3, 1, 0, 0),
(53, '(NP)978-971-625-248-4', '(NP)978-971-625-248-4', 'Wonders of Integrated Science &amp; Health-2', 3, 1, 0, 0),
(54, '(NP)978-971-625-249-1', '(NP)978-971-625-249-1', 'Wonders of Integrated Science &amp; Health-3', 3, 1, 0, 0),
(55, '(NP)978-971-625-253-8', '(NP)978-971-625-253-8', 'Integrated Core Curriculum Week 1-5', 10, 1, 0, 0),
(56, '(NP)978-971-625-254-5', '(NP)978-971-625-254-5', 'Integrated Core Curriculum Week 6-10', 10, 1, 0, 0),
(57, '(NP)978-971-625-255-2', '(NP)978-971-625-255-2', 'Integrated Core Curriculum Week 11-15', 10, 1, 0, 0),
(58, '(NP)978-971-625-256-9', '(NP)978-971-625-256-9', 'Integrated Core Curriculum Week 16-20', 10, 1, 0, 0),
(59, '(NP)978-971-625-257-6', '(NP)978-971-625-257-6', 'Integrated Core Curriculum Week 21-25', 10, 1, 0, 0),
(60, '(NP)978-971-625-258-3', '(NP)978-971-625-258-3', 'Integrated Core Curriculum Week 26-30', 10, 1, 0, 0),
(61, '(NP)978-971-625-259-0', '(NP)978-971-625-259-0', 'Integrated Core Curriculum Week 31-35', 10, 1, 0, 0),
(62, '(NP)978-971-625-260-6', '(NP)978-971-625-260-6', 'Integrated Core Curriculum Week 36-40', 10, 1, 0, 0),
(63, '(NP)971-625-113-0', '(NP)971-625-113-0', 'Enhancing Language Skills (Grade 1)', 1, 1, 0, 0),
(64, '(NP)971-625-114-9', '(NP)971-625-114-9', 'Enhancing Language Skills (Grade 2)', 1, 1, 0, 0),
(65, '(NP)971-625-115-7', '(NP)971-625-115-7', 'Enhancing Language Skills (Grade 3)', 1, 1, 0, 0),
(66, '(NP)971-625-116-5', '(NP)971-625-116-5', 'Enhancing Language Skills (Grade 4)', 1, 1, 0, 0),
(67, '(NP)971-625-117-3', '(NP)971-625-117-3', 'Enhancing Language Skills (Grade 5)', 1, 1, 0, 0),
(68, '(NP)971-625-118-1', '(NP)971-625-118-1', 'Enhancing Language Skills (Grade 6)', 1, 1, 0, 0),
(69, '(NP)971-625-107-6', '(NP)971-625-107-6', 'Enhancing Reading Skills (Grade 1)', 2, 1, 0, 0),
(70, '(NP)971-625-108-4', '(NP)971-625-108-4', 'Enhancing Reading Skills (Grade 2)', 2, 1, 0, 0),
(71, '(NP)971-625-109-2', '(NP)971-625-109-2', 'Enhancing Reading Skills (Grade 3)', 2, 1, 0, 0),
(72, '(NP)971-625-110-6', '(NP)971-625-110-6', 'Enhancing Reading Skills (Grade 4)', 2, 1, 0, 0),
(73, '(NP)971-625-111-4', '(NP)971-625-111-4', 'Enhancing Reading Skills (Grade 5)', 2, 1, 0, 0),
(74, '(NP)971-625-112-2', '(NP)971-625-112-2', 'Enhancing Reading Skills (Grade 6)', 2, 1, 0, 0),
(75, '(NP)971-625-123-8', '(NP)971-625-123-8', 'Enriching Skills in Mathematics 1', 5, 1, 0, 0),
(76, '(NP)971-625-124-6', '(NP)971-625-124-6', 'Enriching Skills in Mathematics 2', 5, 1, 0, 0),
(77, '(NP)971-625-125-4', '(NP)971-625-125-4', 'Enriching Skills in Mathematics 3', 5, 1, 0, 0),
(78, '(NP)971-625-126-2', '(NP)971-625-126-2', 'Enriching Skills in Mathematics 4', 5, 1, 0, 0),
(79, '(NP)971-625-127-3', '(NP)971-625-127-3', 'Enriching Skills in Mathematics 5', 5, 1, 0, 0),
(80, '(NP)971-625-128-9', '(NP)971-625-128-9', 'Enriching Skills in Mathematics 6', 5, 1, 0, 0),
(81, '(NP)971-625-101-7', '(NP)971-625-101-7', 'Sanayang Aklat sa Filipino (Pagbasa 1)', 6, 1, 0, 0),
(82, '(NP)971-625-102-5', '(NP)971-625-102-5', 'Sanayang Aklat sa Filipino (Pagbasa 2)', 6, 1, 0, 0),
(83, '(NP)971-625-103-3', '(NP)971-625-103-3', 'Sanayang Aklat sa Filipino (Pagbasa 3)', 6, 1, 0, 0),
(84, '(NP)971-625-104-1', '(NP)971-625-104-1', 'Sanayang Aklat sa Filipino (Pagbasa 4)', 6, 1, 0, 0),
(85, '(NP)971-625-105-X', '(NP)971-625-105-X', 'Sanayang Aklat sa Filipino (Pagbasa 5)', 6, 1, 0, 0),
(86, '(NP)971-625-106-8', '(NP)971-625-106-8', 'Sanayang Aklat sa Filipino (Pagbasa 6)', 6, 1, 0, 0),
(87, '(NP)971-625-095-9', '(NP)971-625-095-9', 'Sanayang Aklat sa Filipino (Wika 1)', 6, 1, 0, 0),
(88, '(NP)971-625-096-7', '(NP)971-625-096-7', 'Sanayang Aklat sa Filipino (Wika 2)', 6, 1, 0, 0),
(89, '(NP)971-625-098-3', '(NP)971-625-098-3', 'Sanayang Aklat sa Filipino (Wika 4)', 6, 1, 0, 0),
(90, '(NP)971-625-099-1', '(NP)971-625-099-1', 'Sanayang Aklat sa Filipino (Wika 5)', 6, 1, 0, 0),
(91, '(NP)971-625-100-9', '(NP)971-625-100-9', 'Sanayang Aklat sa Filipino (Wika 6)', 6, 1, 0, 0),
(92, '978-971-625-219-4', '978-971-625-219-4', 'Creative Arts Experiences (Upg. Edition)-1', 4, 1, 0, 0),
(93, '978-971-625-220-0', '978-971-625-220-0', 'Creative Arts Experiences (Upg. Edition)-2', 4, 1, 0, 0),
(94, '978-971-625-218-7', '978-971-625-218-7', 'Creative Arts Experiences (Upg. Edition)-3', 4, 1, 0, 0),
(95, '978-971-625-243-9', '978-971-625-243-9', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Nursery', 6, 1, 0, 0),
(96, '978-971-625-244-6', '978-971-625-244-6', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Kinder', 6, 1, 0, 0),
(97, '978-971-625-245-3', '978-971-625-245-3', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Prep', 6, 1, 0, 0),
(98, '978-971-625-232-3', '978-971-625-232-3', 'Language Made Fun and Easy-Nursery', 1, 1, 0, 0),
(99, '978-971-625-233-0', '978-971-625-233-0', 'Language Made Fun and Easy-Kinder', 1, 1, 0, 0),
(100, '978-971-625-234-7', '978-971-625-234-7', 'Language Made Fun and Easy-Prep', 1, 1, 0, 0),
(101, '978-971-625-211-8', '978-971-625-211-8', 'Mathematics Made Fun &amp; Easy-Nursery', 5, 1, 0, 0),
(102, '978-971-625-212-5', '978-971-625-212-5', 'Mathematics Made Fun &amp; Easy-Kinder', 5, 1, 0, 0),
(103, '978-971-625-213-2', '978-971-625-213-2', 'Mathematics Made Fun &amp; Easy-Prep', 5, 1, 0, 0),
(104, '978-971-625-226-2', '978-971-625-226-2', 'Mathematics for the Young Minds-Nursery', 5, 1, 0, 0),
(105, '978-971-625-227-9', '978-971-625-227-9', 'Mathematics for the Young Minds-Kinder', 5, 1, 0, 0),
(106, '978-971-625-228-6', '978-971-625-228-6', 'Mathematics for the Young Minds-Prep', 5, 1, 0, 0),
(107, '978-971-625-216-3', '978-971-625-216-3', 'Makabagong Paraan ng Pagbasa (Binagong Edisyon)', 6, 1, 0, 0),
(108, '978-971-625-217-0', '978-971-625-217-0', 'Makabayan Tayo (Binagong Edisyon)', 6, 1, 0, 0),
(109, '978-971-625-193-7', '978-971-625-193-7', 'Reading Made Fun &amp; Easy-Nursery', 2, 1, 0, 0),
(110, '978-971-625-194-4', '978-971-625-194-4', 'Reading Made Fun &amp; Easy-Kinder', 2, 1, 0, 0),
(111, '978-971-625-195-1', '978-971-625-195-1', 'Reading Made Fun &amp; Easy-Prep', 2, 1, 0, 0),
(112, '978-971-625-223-1', '978-971-625-223-1', 'Reading for the Young Minds-Nursery', 2, 1, 0, 0),
(113, '978-971-625-224-8', '978-971-625-224-8', 'Reading for the Young Minds-Kinder', 2, 1, 0, 0),
(114, '978-971-625-225-5', '978-971-625-225-5', 'Reading for the Young Minds-Prep', 2, 1, 0, 0),
(115, '978-971-625-247-7', '978-971-625-247-7', 'Wonders of Integrated Science &amp; Health-1', 3, 1, 0, 0),
(116, '978-971-625-248-4', '978-971-625-248-4', 'Wonders of Integrated Science &amp; Health-2', 3, 1, 0, 0),
(117, '978-971-625-249-1', '978-971-625-249-1', 'Wonders of Integrated Science &amp; Health-3', 3, 1, 0, 0),
(118, '(NP)978-971-625-241-5', '(NP)978-971-625-241-5', 'Energizers', 1, 1, 0, 0),
(119, '978-971-625-268-2', '978-971-625-268-2', 'The Progressive Approach to Writing-Nursery', 8, 1, 0, 0),
(120, '978-971-625-267-5', '978-971-625-267-5', 'The Progressive Approach to Writing-Kinder', 8, 1, 0, 0),
(121, '978-971-625-266-8', '978-971-625-266-8', 'The Progressive Approach to Writing-Prep', 8, 1, 0, 0),
(122, '978-971-625-263-7', '978-971-625-263-7', 'Preschool Life Skills-1', 1, 1, 0, 0),
(123, '978-971-625-264-4', '978-971-625-264-4', 'Preschool Life Skills-2', 1, 1, 0, 0),
(124, '978-971-625-265-1', '978-971-625-265-1', 'Preschool Life Skills-3', 1, 1, 0, 0),
(125, '(NP)978-971-625-269-9', '(NP)978-971-625-269-9', 'Integrated Core Curriculum Week 1-7', 10, 1, 0, 0),
(126, '(NP)978-971-625-270-5', '(NP)978-971-625-270-5', 'Integrated Core Curriculum Week 8-16', 10, 1, 0, 0),
(127, '(NP)978-971-625-271-2', '(NP)978-971-625-271-2', 'Integrated Core Curriculum Week 17-24', 10, 1, 0, 0),
(128, '(NP)978-971-625-272.9', '(NP)978-971-625-272.9', 'Integrated Core Curriculum Week 25-32', 10, 1, 0, 0),
(129, '(NP)978-971-625-273-6', '(NP)978-971-625-273-6', 'Integrated Core Curriculum Week 33-40', 1, 1, 0, 0),
(130, 'S-978-971-625-243-9', 'S-978-971-625-243-9', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Nursery', 6, 1, 0, 0),
(131, 'S-978-971-625-244-6', 'S-978-971-625-244-6', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Kinder', 6, 1, 0, 0),
(132, 'S-978-971-625-245-3', 'S-978-971-625-245-3', 'Kaunlaran sa Pagbasa ng Bagong Alpabetong Filipino-Prep', 6, 1, 0, 0),
(133, 'S-978-971-625-232-3', 'S-978-971-625-232-3', 'Language Made Fun and Easy-Nursery', 1, 1, 0, 0),
(134, 'S-978-971-625-234-7', 'S-978-971-625-234-7', 'Language Made Fun and Easy-Prep', 1, 1, 0, 0),
(135, 'S-978-971-625-247-7', 'S-978-971-625-247-7', 'Wonders of Integrated Science and Health-Nursery', 3, 1, 0, 0),
(136, 'S-978-971-625-248-4', 'S-978-971-625-248-4', 'Wonders of Integrated Science and Health-Kinder', 3, 1, 0, 0),
(137, 'S-978-971-625-249-1', 'S-978-971-625-249-1', 'Wonders of Integrated Science and Health-Prep', 3, 1, 0, 0),
(138, 'S-978-971-625-263-7', 'S-978-971-625-263-7', 'Preschool Life Skills - Level 1', 1, 1, 0, 0),
(139, 'S-978-971-625-264-4', 'S-978-971-625-264-4', 'Preschool Life Skills - Level 2', 1, 1, 0, 0),
(140, 'S-978-971-625-265-1', 'S-978-971-625-265-1', 'Preschool Life Skills - Level 3', 1, 1, 0, 0),
(141, 'S-978-971-625-268-2', 'S-978-971-625-268-2', 'The Progressive Approach to Writing-Nursery', 1, 1, 0, 0),
(142, 'S-978-971-625-267-5', 'S-978-971-625-267-5', 'The Progressive Approach to Writing-Kinder', 1, 1, 0, 0),
(143, 'S-978-971-625-266-8', 'S-978-971-625-266-8', 'The Progressive Approach to Writing-Prep', 1, 1, 0, 0),
(144, 'S-978-971-625-269-9', 'S-978-971-625-269-9', 'Integrated Core Curriculum Week 1-7', 1, 1, 0, 0),
(145, 'S-978-971-625-270-5', 'S-978-971-625-270-5', 'Integrated Core Curriculum Week 8-16', 1, 1, 0, 0),
(146, 'S-978-971-625-271.2', 'S-978-971-625-271.2', 'Integrated Core Curriculum Week 17-24', 1, 1, 0, 0),
(147, 'S-978-971-625-272-9', 'S-978-971-625-272-9', 'Integrated Core Curriculum Week 25-32', 1, 1, 0, 0),
(148, 'S-978-971-625-273-6', 'S-978-971-625-273-6', 'Integrated Core Curriculum Week 33-40', 1, 1, 0, 0),
(149, 'S-978-971-625-303-0', 'S-978-971-625-303-0', 'Exploring the World of Mathematics-1', 5, 1, 0, 0),
(150, 'S-978-971-625-304-7', 'S-978-971-625-304-7', 'Exploring the World of Mathematics-2', 5, 1, 0, 0),
(151, 'S-978-971-625-300-9', 'S-978-971-625-300-9', 'On the Road to Language Scuccess-1', 1, 1, 0, 0),
(152, 'S-978-971-625-301-6', 'S-978-971-625-301-6', 'On the Road to Language Scuccess-2', 1, 1, 0, 0),
(153, 'S-978-971-625-302-3', 'S-978-971-625-302-3', 'On the Road to Language Scuccess-3', 1, 1, 0, 0),
(154, 'S-978-971-625-297-2', 'S-978-971-625-297-2', 'On the Road to Reading Success-1', 2, 1, 0, 0),
(155, 'S-978-971-625-298-9', 'S-978-971-625-298-9', 'On the Road to Reading Success-2', 2, 1, 0, 0);