-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 12, 2018 at 05:13 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Summer`
--

-- --------------------------------------------------------

--
-- Table structure for table `AdminEnrollment`
--

CREATE TABLE `AdminEnrollment` (
  `ID` int(11) NOT NULL,
  `aEmail` varchar(255) NOT NULL,
  `mCode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `AdminEnrollment`
--

INSERT INTO `AdminEnrollment` (`ID`, `aEmail`, `mCode`) VALUES
(1, 'ad@ad.com', 'G52OSC'),
(2, 'ad@ad.com', 'G52SWM'),
(3, 'as@as.com', 'G52SWM');

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admins`
--

INSERT INTO `Admins` (`Email`, `Password`) VALUES
('ad@ad.com', '111111'),
('as@as.com', '111111');

-- --------------------------------------------------------

--
-- Table structure for table `Enrollment`
--

CREATE TABLE `Enrollment` (
  `ID` int(11) NOT NULL,
  `uEmail` varchar(255) NOT NULL,
  `mCode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Enrollment`
--

INSERT INTO `Enrollment` (`ID`, `uEmail`, `mCode`) VALUES
(1, '11@11.com', 'G52OSC'),
(2, '11@11.com', 'G52SWM'),
(3, '22@22.com', 'G52SWM');

-- --------------------------------------------------------

--
-- Table structure for table `Labs`
--

CREATE TABLE `Labs` (
  `ID` int(11) NOT NULL,
  `mCode` varchar(10) NOT NULL,
  `Weekday` int(11) NOT NULL,
  `Start_Time` time NOT NULL,
  `End_Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Labs`
--

INSERT INTO `Labs` (`ID`, `mCode`, `Weekday`, `Start_Time`, `End_Time`) VALUES
(1, 'G52OSC', 0, '11:10:00', '15:10:00'),
(2, 'G52SWM', 0, '15:10:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Modules`
--

CREATE TABLE `Modules` (
  `Code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Modules`
--

INSERT INTO `Modules` (`Code`) VALUES
('G52OSC'),
('G52SWM');

-- --------------------------------------------------------

--
-- Table structure for table `Queue`
--

CREATE TABLE `Queue` (
  `Position` int(11) NOT NULL,
  `rID` varchar(255) NOT NULL,
  `Handling_By` varchar(255) DEFAULT NULL,
  `Preference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE `Requests` (
  `ID` varchar(255) NOT NULL,
  `State` varchar(15) NOT NULL,
  `Made_In` varchar(10) NOT NULL,
  `Generated_By` varchar(255) NOT NULL,
  `Handled_By` varchar(255) DEFAULT NULL,
  `Created_Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Finished_Time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Requests`
--

INSERT INTO `Requests` (`ID`, `State`, `Made_In`, `Generated_By`, `Handled_By`, `Created_Time`, `Finished_Time`) VALUES
('BbiOmXribxG', 'Finished', 'G52OSC', '22@22.com', 'as@as.com', '2018-07-20 12:16:51', '2018-07-20 12:42:49'),
('d8Ch2WkqPC6', 'Finished', 'G52OSC', '11@11.com', 'ad@ad.com', '2018-07-26 14:28:07', '2018-07-26 14:28:49'),
('DCJjiO9IJnW', 'Finished', 'G52OSC', '22@22.com', 'ad@ad.com', '2018-07-20 12:43:47', '2018-07-20 12:43:53'),
('eMWUqHsK75a', 'Finished', 'G52SWM', '22@22.com', 'as@as.com', '2018-07-27 15:36:33', '2018-07-27 15:37:57'),
('FOJK1704ciC', 'Finished', 'G52OSC', '11@11.com', 'ad@ad.com', '2018-07-18 14:02:08', '2018-07-20 11:21:00'),
('HkhShJdvWIf', 'Finished', 'G52SWM', '11@11.com', 'ad@ad.com', '2018-07-27 15:35:56', '2018-07-27 15:37:53'),
('P6Ans7ob4cu', 'Finished', 'G52SWM', '11@11.com', 'ad@ad.com', '2018-08-12 16:08:50', '2018-08-12 16:11:34'),
('sYzcwPS7yJk', 'Canceled', 'G52OSC', '11@11.com', NULL, '2018-07-18 14:01:42', NULL),
('tyyrrvkBOHF', 'Finished', 'G52OSC', '11@11.com', 'as@as.com', '2018-07-25 11:47:10', '2018-07-25 11:48:49'),
('uL58nOsP6ji', 'Finished', 'G52SWM', '22@22.com', 'as@as.com', '2018-07-27 15:28:44', '2018-07-27 15:39:31'),
('XQvPDkJQaUD', 'Finished', 'G52SWM', '11@11.com', 'ad@ad.com', '2018-08-02 20:32:32', '2018-08-02 20:32:34'),
('YVfnK7GCDsT', 'Finished', 'G52OSC', '11@11.com', 'as@as.com', '2018-07-25 11:48:57', '2018-07-25 11:51:37');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Email`, `Password`) VALUES
('11@11.com', '111111'),
('22@22.com', '111111'),
('33@33.com', '111111'),
('44@44.com', '111111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AdminEnrollment`
--
ALTER TABLE `AdminEnrollment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ae_fk1` (`aEmail`),
  ADD KEY `ae_fk2` (`mCode`);

--
-- Indexes for table `Admins`
--
ALTER TABLE `Admins`
  ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `Enrollment`
--
ALTER TABLE `Enrollment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `e_fk1` (`uEmail`),
  ADD KEY `e_fk2` (`mCode`);

--
-- Indexes for table `Labs`
--
ALTER TABLE `Labs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `l_fk` (`mCode`);

--
-- Indexes for table `Modules`
--
ALTER TABLE `Modules`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `Queue`
--
ALTER TABLE `Queue`
  ADD PRIMARY KEY (`Position`),
  ADD UNIQUE KEY `q_fk2` (`Handling_By`) USING BTREE,
  ADD KEY `q_fk1` (`rID`) USING BTREE,
  ADD KEY `q_fk3` (`Preference`);

--
-- Indexes for table `Requests`
--
ALTER TABLE `Requests`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `r_fk1` (`Generated_By`),
  ADD KEY `r_fk2` (`Handled_By`),
  ADD KEY `r_fk3` (`Made_In`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AdminEnrollment`
--
ALTER TABLE `AdminEnrollment`
  ADD CONSTRAINT `ae_fk1` FOREIGN KEY (`aEmail`) REFERENCES `Admins` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ae_fk2` FOREIGN KEY (`mCode`) REFERENCES `Modules` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Enrollment`
--
ALTER TABLE `Enrollment`
  ADD CONSTRAINT `e_fk1` FOREIGN KEY (`uEmail`) REFERENCES `Users` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `e_fk2` FOREIGN KEY (`mCode`) REFERENCES `Modules` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Labs`
--
ALTER TABLE `Labs`
  ADD CONSTRAINT `l_fk` FOREIGN KEY (`mCode`) REFERENCES `Modules` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Queue`
--
ALTER TABLE `Queue`
  ADD CONSTRAINT `q_fk1` FOREIGN KEY (`rID`) REFERENCES `Requests` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `q_fk2` FOREIGN KEY (`Handling_By`) REFERENCES `Admins` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `q_fk3` FOREIGN KEY (`Preference`) REFERENCES `Admins` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Requests`
--
ALTER TABLE `Requests`
  ADD CONSTRAINT `r_fk1` FOREIGN KEY (`Generated_By`) REFERENCES `Users` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_fk2` FOREIGN KEY (`Handled_By`) REFERENCES `Admins` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_fk3` FOREIGN KEY (`Made_In`) REFERENCES `Modules` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
