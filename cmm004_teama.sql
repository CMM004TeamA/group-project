-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 01:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmm004_teama`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCategoryPath` (IN `category_path` TEXT)   BEGIN
    DECLARE current_parent_id INT DEFAULT NULL;
    DECLARE current_category_name VARCHAR(20);
    DECLARE next_separator_pos INT;
    DECLARE temp_category_id INT;

 
    IF category_path IS NULL THEN
        SET category_path = '';
    END IF;

    
    SET category_path = TRIM(category_path);

   
    WHILE LENGTH(category_path) > 0 DO
        
        SET next_separator_pos = LOCATE('>', category_path);

        
        IF next_separator_pos > 0 THEN
            SET current_category_name = TRIM(SUBSTRING(category_path, 1, next_separator_pos - 1));
            SET category_path = TRIM(SUBSTRING(category_path, next_separator_pos + 1));
        ELSE
            SET current_category_name = TRIM(category_path);
            SET category_path = '';
        END IF;

        SET temp_category_id = (
            SELECT category_id
            FROM Categories
            WHERE category_name = current_category_name
              AND ((parent_category_id IS NULL AND current_parent_id IS NULL) OR
                   (parent_category_id = current_parent_id)) 
            LIMIT 1
        );

        IF temp_category_id IS NULL THEN
            INSERT INTO Categories (category_name, parent_category_id)
            VALUES (current_category_name, current_parent_id);
            SET current_parent_id = LAST_INSERT_ID(); 
        ELSE
            
            SET current_parent_id = temp_category_id;
        END IF;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `adminactions`
--

CREATE TABLE `adminactions` (
  `action_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `action_type` enum('edit','delete') NOT NULL,
  `action_details` text DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `time_carted` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(20) NOT NULL,
  `parent_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `parent_category_id`) VALUES
(14, 'Accessories', 1),
(28, 'Accessories', 15),
(42, 'Accessories', 29),
(56, 'Accessories', 43),
(13, 'Backpack', 11),
(27, 'Backpack', 25),
(41, 'Backpack', 39),
(55, 'Backpack', 53),
(11, 'Bags', 1),
(25, 'Bags', 15),
(39, 'Bags', 29),
(53, 'Bags', 43),
(23, 'Boots', 21),
(51, 'Boots', 49),
(43, 'Boys', NULL),
(26, 'Briefcase', 25),
(54, 'Briefcase', 53),
(2, 'Clothing', 1),
(16, 'Clothing', 15),
(30, 'Clothing', 29),
(44, 'Clothing', 43),
(6, 'Coats', 2),
(20, 'Coats', 16),
(34, 'Coats', 30),
(48, 'Coats', 44),
(40, 'Crossbody bag', 39),
(22, 'Dress shoes', 21),
(50, 'Dress shoes', 49),
(5, 'Dresses', 2),
(33, 'Dresses', 30),
(8, 'Flats', 7),
(36, 'Flats', 35),
(29, 'Girls', NULL),
(12, 'Handbag', 11),
(9, 'Heels', 7),
(37, 'Heels', 35),
(15, 'Men', NULL),
(4, 'Pants & Jeans', 2),
(18, 'Pants & Jeans', 16),
(32, 'Pants & Jeans', 30),
(46, 'Pants & Jeans', 44),
(7, 'Shoes', 1),
(21, 'Shoes', 15),
(35, 'Shoes', 29),
(49, 'Shoes', 43),
(10, 'Sneakers', 7),
(24, 'Sneakers', 21),
(38, 'Sneakers', 35),
(52, 'Sneakers', 49),
(19, 'Suits', 16),
(47, 'Suits', 44),
(3, 'Top & T-Shirts', 2),
(17, 'Top & T-Shirts', 16),
(31, 'Top & T-Shirts', 30),
(45, 'Top & T-Shirts', 44),
(1, 'Women', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `collection_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE `conditions` (
  `condition_id` int(11) NOT NULL,
  `condition_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conditions`
--

INSERT INTO `conditions` (`condition_id`, `condition_name`) VALUES
(3, 'Fairly worn (less than 10 wears)'),
(1, 'New with tag'),
(2, 'New without tag'),
(4, 'Worn but good');

-- --------------------------------------------------------

--
-- Table structure for table `itemimages`
--

CREATE TABLE `itemimages` (
  `image_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_path` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `size_id` int(11) DEFAULT NULL,
  `condition_id` int(11) NOT NULL,
  `title` varchar(75) NOT NULL,
  `description` text DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `date_altered` datetime DEFAULT curdate() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `time_sent` datetime DEFAULT current_timestamp(),
  `read_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `reservation_date` datetime DEFAULT current_timestamp(),
  `notified_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `date_reviewed` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sizecategories`
--

CREATE TABLE `sizecategories` (
  `size_category_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizecategories`
--

INSERT INTO `sizecategories` (`size_category_id`, `size_id`, `category_id`) VALUES
(57, 1, 3),
(58, 1, 4),
(59, 1, 5),
(60, 1, 6),
(49, 2, 3),
(50, 2, 4),
(51, 2, 5),
(52, 2, 6),
(41, 3, 3),
(42, 3, 4),
(43, 3, 5),
(44, 3, 6),
(33, 4, 3),
(34, 4, 4),
(35, 4, 5),
(36, 4, 6),
(29, 5, 3),
(30, 5, 4),
(31, 5, 5),
(32, 5, 6),
(25, 6, 3),
(26, 6, 4),
(27, 6, 5),
(28, 6, 6),
(37, 7, 3),
(38, 7, 4),
(39, 7, 5),
(40, 7, 6),
(45, 8, 3),
(46, 8, 4),
(47, 8, 5),
(48, 8, 6),
(53, 9, 3),
(54, 9, 4),
(55, 9, 5),
(56, 9, 6),
(1, 10, 3),
(2, 10, 4),
(3, 10, 5),
(4, 10, 6),
(5, 11, 3),
(6, 11, 4),
(7, 11, 5),
(8, 11, 6),
(9, 12, 3),
(10, 12, 4),
(11, 12, 5),
(12, 12, 6),
(13, 13, 3),
(14, 13, 4),
(15, 13, 5),
(16, 13, 6),
(17, 14, 3),
(18, 14, 4),
(19, 14, 5),
(20, 14, 6),
(21, 15, 3),
(22, 15, 4),
(23, 15, 5),
(24, 15, 6),
(152, 16, 31),
(153, 16, 32),
(154, 16, 33),
(155, 16, 34),
(279, 16, 45),
(280, 16, 46),
(281, 16, 47),
(282, 16, 48),
(148, 17, 31),
(149, 17, 32),
(150, 17, 33),
(151, 17, 34),
(275, 17, 45),
(276, 17, 46),
(277, 17, 47),
(278, 17, 48),
(156, 18, 31),
(157, 18, 32),
(158, 18, 33),
(159, 18, 34),
(283, 18, 45),
(284, 18, 46),
(285, 18, 47),
(286, 18, 48),
(64, 19, 31),
(65, 19, 32),
(66, 19, 33),
(67, 19, 34),
(191, 19, 45),
(192, 19, 46),
(193, 19, 47),
(194, 19, 48),
(112, 20, 31),
(113, 20, 32),
(114, 20, 33),
(115, 20, 34),
(239, 20, 45),
(240, 20, 46),
(241, 20, 47),
(242, 20, 48),
(128, 21, 31),
(129, 21, 32),
(130, 21, 33),
(131, 21, 34),
(255, 21, 45),
(256, 21, 46),
(257, 21, 47),
(258, 21, 48),
(144, 22, 31),
(145, 22, 32),
(146, 22, 33),
(147, 22, 34),
(271, 22, 45),
(272, 22, 46),
(273, 22, 47),
(274, 22, 48),
(80, 23, 31),
(81, 23, 32),
(82, 23, 33),
(83, 23, 34),
(207, 23, 45),
(208, 23, 46),
(209, 23, 47),
(210, 23, 48),
(100, 24, 31),
(101, 24, 32),
(102, 24, 33),
(103, 24, 34),
(227, 24, 45),
(228, 24, 46),
(229, 24, 47),
(230, 24, 48),
(104, 25, 31),
(105, 25, 32),
(106, 25, 33),
(107, 25, 34),
(231, 25, 45),
(232, 25, 46),
(233, 25, 47),
(234, 25, 48),
(108, 26, 31),
(109, 26, 32),
(110, 26, 33),
(111, 26, 34),
(235, 26, 45),
(236, 26, 46),
(237, 26, 47),
(238, 26, 48),
(116, 27, 31),
(117, 27, 32),
(118, 27, 33),
(119, 27, 34),
(243, 27, 45),
(244, 27, 46),
(245, 27, 47),
(246, 27, 48),
(120, 28, 31),
(121, 28, 32),
(122, 28, 33),
(123, 28, 34),
(247, 28, 45),
(248, 28, 46),
(249, 28, 47),
(250, 28, 48),
(124, 29, 31),
(125, 29, 32),
(126, 29, 33),
(127, 29, 34),
(251, 29, 45),
(252, 29, 46),
(253, 29, 47),
(254, 29, 48),
(132, 30, 31),
(133, 30, 32),
(134, 30, 33),
(135, 30, 34),
(259, 30, 45),
(260, 30, 46),
(261, 30, 47),
(262, 30, 48),
(136, 31, 31),
(137, 31, 32),
(138, 31, 33),
(139, 31, 34),
(263, 31, 45),
(264, 31, 46),
(265, 31, 47),
(266, 31, 48),
(140, 32, 31),
(141, 32, 32),
(142, 32, 33),
(143, 32, 34),
(267, 32, 45),
(268, 32, 46),
(269, 32, 47),
(270, 32, 48),
(68, 33, 31),
(69, 33, 32),
(70, 33, 33),
(71, 33, 34),
(195, 33, 45),
(196, 33, 46),
(197, 33, 47),
(198, 33, 48),
(72, 34, 31),
(73, 34, 32),
(74, 34, 33),
(75, 34, 34),
(199, 34, 45),
(200, 34, 46),
(201, 34, 47),
(202, 34, 48),
(76, 35, 31),
(77, 35, 32),
(78, 35, 33),
(79, 35, 34),
(203, 35, 45),
(204, 35, 46),
(205, 35, 47),
(206, 35, 48),
(84, 36, 31),
(85, 36, 32),
(86, 36, 33),
(87, 36, 34),
(211, 36, 45),
(212, 36, 46),
(213, 36, 47),
(214, 36, 48),
(88, 37, 31),
(89, 37, 32),
(90, 37, 33),
(91, 37, 34),
(215, 37, 45),
(216, 37, 46),
(217, 37, 47),
(218, 37, 48),
(92, 38, 31),
(93, 38, 32),
(94, 38, 33),
(95, 38, 34),
(219, 38, 45),
(220, 38, 46),
(221, 38, 47),
(222, 38, 48),
(96, 39, 31),
(97, 39, 32),
(98, 39, 33),
(99, 39, 34),
(223, 39, 45),
(224, 39, 46),
(225, 39, 47),
(226, 39, 48),
(318, 40, 8),
(319, 40, 9),
(320, 40, 10),
(321, 41, 8),
(322, 41, 9),
(323, 41, 10),
(336, 42, 8),
(337, 42, 9),
(338, 42, 10),
(339, 43, 8),
(340, 43, 9),
(341, 43, 10),
(342, 44, 8),
(343, 44, 9),
(344, 44, 10),
(345, 45, 8),
(346, 45, 9),
(347, 45, 10),
(405, 45, 22),
(406, 45, 23),
(407, 45, 24),
(348, 46, 8),
(349, 46, 9),
(350, 46, 10),
(408, 46, 22),
(409, 46, 23),
(410, 46, 24),
(351, 47, 8),
(352, 47, 9),
(353, 47, 10),
(411, 47, 22),
(412, 47, 23),
(413, 47, 24),
(354, 48, 8),
(355, 48, 9),
(356, 48, 10),
(414, 48, 22),
(415, 48, 23),
(416, 48, 24),
(357, 49, 8),
(358, 49, 9),
(359, 49, 10),
(417, 49, 22),
(418, 49, 23),
(419, 49, 24),
(360, 50, 8),
(361, 50, 9),
(362, 50, 10),
(420, 50, 22),
(421, 50, 23),
(422, 50, 24),
(324, 51, 8),
(325, 51, 9),
(326, 51, 10),
(381, 51, 22),
(382, 51, 23),
(383, 51, 24),
(327, 52, 8),
(328, 52, 9),
(329, 52, 10),
(384, 52, 22),
(385, 52, 23),
(386, 52, 24),
(330, 53, 8),
(331, 53, 9),
(332, 53, 10),
(387, 53, 22),
(388, 53, 23),
(389, 53, 24),
(333, 54, 8),
(334, 54, 9),
(335, 54, 10),
(390, 54, 22),
(391, 54, 23),
(392, 54, 24),
(393, 55, 22),
(394, 55, 23),
(395, 55, 24),
(396, 56, 22),
(397, 56, 23),
(398, 56, 24),
(399, 58, 22),
(400, 58, 23),
(401, 58, 24),
(402, 59, 22),
(403, 59, 23),
(404, 59, 24),
(519, 60, 36),
(520, 60, 37),
(521, 60, 38),
(646, 60, 50),
(647, 60, 51),
(648, 60, 52),
(444, 61, 36),
(445, 61, 37),
(446, 61, 38),
(571, 61, 50),
(572, 61, 51),
(573, 61, 52),
(447, 62, 36),
(448, 62, 37),
(449, 62, 38),
(574, 62, 50),
(575, 62, 51),
(576, 62, 52),
(468, 63, 36),
(469, 63, 37),
(470, 63, 38),
(595, 63, 50),
(596, 63, 51),
(597, 63, 52),
(477, 64, 36),
(478, 64, 37),
(479, 64, 38),
(604, 64, 50),
(605, 64, 51),
(606, 64, 52),
(483, 65, 36),
(484, 65, 37),
(485, 65, 38),
(610, 65, 50),
(611, 65, 51),
(612, 65, 52),
(486, 66, 36),
(487, 66, 37),
(488, 66, 38),
(613, 66, 50),
(614, 66, 51),
(615, 66, 52),
(492, 67, 36),
(493, 67, 37),
(494, 67, 38),
(619, 67, 50),
(620, 67, 51),
(621, 67, 52),
(498, 68, 36),
(499, 68, 37),
(500, 68, 38),
(625, 68, 50),
(626, 68, 51),
(627, 68, 52),
(507, 69, 36),
(508, 69, 37),
(509, 69, 38),
(634, 69, 50),
(635, 69, 51),
(636, 69, 52),
(510, 70, 36),
(511, 70, 37),
(512, 70, 38),
(637, 70, 50),
(638, 70, 51),
(639, 70, 52),
(513, 71, 36),
(514, 71, 37),
(515, 71, 38),
(640, 71, 50),
(641, 71, 51),
(642, 71, 52),
(516, 72, 36),
(517, 72, 37),
(518, 72, 38),
(643, 72, 50),
(644, 72, 51),
(645, 72, 52),
(453, 73, 36),
(454, 73, 37),
(455, 73, 38),
(580, 73, 50),
(581, 73, 51),
(582, 73, 52),
(456, 74, 36),
(457, 74, 37),
(458, 74, 38),
(583, 74, 50),
(584, 74, 51),
(585, 74, 52),
(459, 75, 36),
(460, 75, 37),
(461, 75, 38),
(586, 75, 50),
(587, 75, 51),
(588, 75, 52),
(462, 76, 36),
(463, 76, 37),
(464, 76, 38),
(589, 76, 50),
(590, 76, 51),
(591, 76, 52),
(465, 77, 36),
(466, 77, 37),
(467, 77, 38),
(592, 77, 50),
(593, 77, 51),
(594, 77, 52),
(450, 78, 36),
(451, 78, 37),
(452, 78, 38),
(577, 78, 50),
(578, 78, 51),
(579, 78, 52),
(471, 79, 36),
(472, 79, 37),
(473, 79, 38),
(598, 79, 50),
(599, 79, 51),
(600, 79, 52),
(474, 80, 36),
(475, 80, 37),
(476, 80, 38),
(601, 80, 50),
(602, 80, 51),
(603, 80, 52),
(480, 81, 36),
(481, 81, 37),
(482, 81, 38),
(607, 81, 50),
(608, 81, 51),
(609, 81, 52),
(489, 82, 36),
(490, 82, 37),
(491, 82, 38),
(616, 82, 50),
(617, 82, 51),
(618, 82, 52),
(495, 83, 36),
(496, 83, 37),
(497, 83, 38),
(622, 83, 50),
(623, 83, 51),
(624, 83, 52),
(501, 84, 36),
(502, 84, 37),
(503, 84, 38),
(628, 84, 50),
(629, 84, 51),
(630, 84, 52),
(504, 85, 36),
(505, 85, 37),
(506, 85, 38),
(631, 85, 50),
(632, 85, 51),
(633, 85, 52);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`) VALUES
(61, '0 baby'),
(40, '1'),
(62, '1 baby'),
(78, '1 junior'),
(19, '1-3 months'),
(41, '1.5'),
(51, '10'),
(73, '10 child'),
(33, '10 years'),
(52, '11'),
(74, '11 child'),
(34, '11 years'),
(75, '11.5 child'),
(53, '12'),
(76, '12 child'),
(35, '12 years'),
(23, '12-18 months'),
(54, '13'),
(77, '13 child'),
(36, '13 years'),
(55, '14'),
(37, '14 years'),
(56, '15'),
(38, '15 years'),
(57, '15.5'),
(58, '16'),
(39, '16 years'),
(59, '16.5'),
(24, '18-24 months'),
(42, '2'),
(63, '2 baby'),
(79, '2 junior'),
(25, '2 years'),
(43, '2.5'),
(80, '2.5 junior'),
(44, '3'),
(64, '3 baby'),
(81, '3 junior'),
(26, '3 years'),
(20, '3-6 months'),
(65, '3.5 baby'),
(45, '4'),
(66, '4 baby'),
(82, '4 junior'),
(27, '4 years'),
(10, '4XL'),
(46, '5'),
(67, '5 baby'),
(83, '5 junior'),
(28, '5 years'),
(11, '5XL'),
(47, '6'),
(68, '6 baby'),
(84, '6 junior'),
(29, '6 years'),
(21, '6-9 months'),
(85, '6.5 junior'),
(12, '6XL'),
(48, '7'),
(69, '7 child'),
(30, '7 years'),
(13, '7XL'),
(49, '8'),
(70, '8 child'),
(31, '8 years'),
(71, '8.5 child'),
(14, '8XL'),
(50, '9'),
(72, '9 child'),
(32, '9 years'),
(22, '9-12 months'),
(15, '9XL'),
(6, 'L'),
(5, 'M'),
(17, 'Newborns'),
(16, 'Preemie'),
(4, 'S'),
(60, 'Tiny baby'),
(18, 'Up to 1 month'),
(7, 'XL'),
(3, 'XS'),
(8, 'XXL'),
(2, 'XXS'),
(9, 'XXXL'),
(1, 'XXXS');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`status_id`, `status_name`) VALUES
(1, 'Available'),
(4, 'Collected'),
(5, 'Expired'),
(2, 'In Cart'),
(3, 'Reserved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `date_joined` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminactions`
--
ALTER TABLE `adminactions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `fk_adminactions_adminid` (`admin_id`),
  ADD KEY `fk_adminactions_itemid` (`item_id`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`user_id`,`item_id`),
  ADD KEY `fk_cartitems_item_id` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `unique_category` (`category_name`,`parent_category_id`),
  ADD KEY `fk_parent_category` (`parent_category_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `fk_collections_reservationid` (`reservation_id`),
  ADD KEY `fk_collections_userid` (`user_id`),
  ADD KEY `fk_collections_itemid` (`item_id`);

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`condition_id`),
  ADD UNIQUE KEY `condition_name` (`condition_name`);

--
-- Indexes for table `itemimages`
--
ALTER TABLE `itemimages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `fk_itemimage_itemid` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_items_userid` (`user_id`),
  ADD KEY `fk_items_categoryid` (`category_id`),
  ADD KEY `fk_items_statusid` (`status_id`),
  ADD KEY `fk_items_conditionid` (`condition_id`),
  ADD KEY `fk_items_sizeid` (`size_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notifications_userid` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `fk_reservations_userid` (`user_id`),
  ADD KEY `fk_reservations_itemid` (`item_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_reviews_userid` (`user_id`),
  ADD KEY `fk_reviews_itemid` (`item_id`);

--
-- Indexes for table `sizecategories`
--
ALTER TABLE `sizecategories`
  ADD PRIMARY KEY (`size_category_id`),
  ADD UNIQUE KEY `size_id` (`size_id`,`category_id`),
  ADD KEY `fk_sizecategories_categoryid` (`category_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`),
  ADD UNIQUE KEY `size_name` (`size_name`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminactions`
--
ALTER TABLE `adminactions`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conditions`
--
ALTER TABLE `conditions`
  MODIFY `condition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `itemimages`
--
ALTER TABLE `itemimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sizecategories`
--
ALTER TABLE `sizecategories`
  MODIFY `size_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=649;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminactions`
--
ALTER TABLE `adminactions`
  ADD CONSTRAINT `fk_adminactions_adminid` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_adminactions_itemid` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `fk_cartitems_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `fk_cartitems_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_parent_category` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `fk_collections_itemid` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `fk_collections_reservationid` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `fk_collections_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `itemimages`
--
ALTER TABLE `itemimages`
  ADD CONSTRAINT `fk_itemimage_itemid` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_categoryid` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_items_conditionid` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`condition_id`),
  ADD CONSTRAINT `fk_items_sizeid` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`),
  ADD CONSTRAINT `fk_items_statusid` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`),
  ADD CONSTRAINT `fk_items_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_itemid` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `fk_reservations_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_itemid` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `fk_reviews_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sizecategories`
--
ALTER TABLE `sizecategories`
  ADD CONSTRAINT `fk_sizecategories_categoryid` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_sizecategories_sizeid` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
