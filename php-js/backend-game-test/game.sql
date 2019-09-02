-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2014 at 09:02 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_combats`
--

CREATE TABLE IF NOT EXISTS `game_combats` (
`combatID` int(11) NOT NULL,
  `army1Username` varchar(255) NOT NULL,
  `army2Username` varchar(255) NOT NULL,
  `winner` tinyint(2) NOT NULL,
  `army1Units` int(11) NOT NULL,
  `army2Units` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;

--
-- Dumping data for table `game_combats`
--

INSERT INTO `game_combats` (`combatID`, `army1Username`, `army2Username`, `winner`, `army1Units`, `army2Units`) VALUES
(157, 'PeluFunk', 'i618', 0, 100, 60),
(158, 'Kangaxx_', 'break2k', 1, 100, 120),
(159, 'rizalomaniac', 'xxx_birdman_xxx', 0, 100, 100),
(160, 'nerbys_sretlow', 'xxx_birdman_xxx', 0, 100, 100),
(161, 'Vyazovyh', 'devanmc436', 0, 150, 120),
(162, 'craazyfist', 'Sadissst', 0, 200, 100),
(163, 'Nythyn', 'akash_100294', 0, 100, 30),
(164, 'metal7core', 'rizalomaniac', 1, 200, 280),
(165, 'craazyfist', 'russellsims', 1, 200, 280),
(166, 'shortman_alan', 'binari0', 1, 200, 280),
(167, 'Kangaxx_', 'carterly', 0, 50, 48),
(168, 'Radar_666', 'TusharV8', 0, 50, 1),
(169, 'DrunkAus', 'bdehaas', 0, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `game_combats_events`
--

CREATE TABLE IF NOT EXISTS `game_combats_events` (
  `combatID` int(11) NOT NULL,
`eventID` int(11) NOT NULL,
  `duration` bigint(20) NOT NULL,
  `army1Units` int(11) NOT NULL,
  `army2Units` int(11) NOT NULL,
  `eventCode` smallint(6) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=488 ;

--
-- Dumping data for table `game_combats_events`
--

INSERT INTO `game_combats_events` (`combatID`, `eventID`, `duration`, `army1Units`, `army2Units`, `eventCode`) VALUES
(157, 436, 838, 89, 25, 2),
(157, 437, 1046, 71, 11, 0),
(157, 438, 200, 57, 0, 4),
(158, 439, 1284, 38, 94, 3),
(158, 440, 446, 8, 76, 4),
(158, 441, 415, 0, 73, 2),
(159, 442, 807, 66, 89, 4),
(159, 443, 496, 47, 65, 1),
(159, 444, 446, 38, 46, 4),
(159, 445, 669, 27, 23, 1),
(159, 446, 192, 23, 13, 2),
(159, 447, 357, 12, 0, 1),
(160, 448, 307, 85, 90, 4),
(160, 449, 1707, 67, 53, 0),
(160, 450, 476, 55, 46, 2),
(160, 451, 361, 51, 36, 3),
(160, 452, 392, 35, 18, 1),
(160, 453, 492, 24, 8, 0),
(160, 454, 446, 21, 0, 0),
(161, 455, 784, 128, 71, 2),
(161, 456, 623, 112, 45, 1),
(161, 457, 1646, 105, 20, 0),
(161, 458, 1326, 101, 8, 1),
(161, 459, 561, 97, 0, 4),
(162, 460, 1138, 101, 57, 4),
(162, 461, 523, 77, 28, 3),
(162, 462, 507, 71, 14, 3),
(162, 463, 334, 63, 0, 1),
(163, 464, 1003, 95, 11, 1),
(163, 465, 646, 77, 0, 1),
(164, 466, 1053, 149, 223, 4),
(164, 467, 2100, 73, 166, 1),
(164, 468, 1230, 48, 143, 3),
(164, 469, 1130, 15, 132, 3),
(164, 470, 323, 5, 130, 4),
(164, 471, 634, 0, 128, 1),
(165, 472, 2800, 194, 264, 0),
(165, 473, 4707, 115, 233, 0),
(165, 474, 1200, 91, 211, 3),
(165, 475, 1384, 13, 174, 3),
(165, 476, 1200, 2, 170, 1),
(165, 477, 1742, 0, 170, 1),
(166, 478, 1969, 142, 166, 4),
(166, 479, 738, 122, 161, 3),
(166, 480, 838, 77, 140, 3),
(166, 481, 1523, 7, 82, 2),
(166, 482, 269, 0, 74, 4),
(167, 483, 1230, 24, 11, 0),
(167, 484, 246, 18, 1, 3),
(167, 485, 146, 18, 0, 3),
(168, 486, 630, 41, 0, 0),
(169, 487, 523, 50, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `game_combats_events_interfeerences`
--

CREATE TABLE IF NOT EXISTS `game_combats_events_interfeerences` (
  `eventID` int(11) NOT NULL,
  `interfeerenceCode` smallint(6) NOT NULL,
  `interfeerenceVictim` tinyint(2) NOT NULL,
  `interfeerenceCasualties` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game_combats_events_interfeerences`
--

INSERT INTO `game_combats_events_interfeerences` (`eventID`, `interfeerenceCode`, `interfeerenceVictim`, `interfeerenceCasualties`) VALUES
(437, 2, 0, 13),
(438, 0, 0, 4),
(443, 0, 1, 8),
(444, 2, 1, 3),
(447, 0, 0, 5),
(448, 0, 0, 10),
(453, 0, 0, 7),
(457, 0, 1, 6),
(460, 0, 0, 24),
(464, 0, 1, 4),
(465, 0, 0, 15),
(468, 1, 1, 15),
(470, 2, 0, 2),
(474, 1, 1, 14),
(477, 0, 0, 0),
(478, 0, 1, 28),
(479, 0, 0, 5),
(482, 2, 1, 6),
(486, 2, 0, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game_combats`
--
ALTER TABLE `game_combats`
 ADD PRIMARY KEY (`combatID`);

--
-- Indexes for table `game_combats_events`
--
ALTER TABLE `game_combats_events`
 ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `game_combats_events_interfeerences`
--
ALTER TABLE `game_combats_events_interfeerences`
 ADD KEY `eventID` (`eventID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game_combats`
--
ALTER TABLE `game_combats`
MODIFY `combatID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT for table `game_combats_events`
--
ALTER TABLE `game_combats_events`
MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=488;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
