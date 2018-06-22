-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 22, 2018 at 06:38 PM
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
(3, '22@22.com', 'G52OSC'),
(4, '33@33.com', 'G52SWM');

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
(1, 'G52OSC', 5, '17:00:00', '19:00:00'),
(2, 'G52SWM', 4, '03:00:00', '08:00:00');

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
  `Handling_By` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Queue`
--

INSERT INTO `Queue` (`Position`, `rID`, `Handling_By`) VALUES
(1, '4vLYpmJ5KiU', 'null'),
(2, 'XThOP6VX6bh', 'null');

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE `Requests` (
  `ID` varchar(255) NOT NULL,
  `State` varchar(255) NOT NULL,
  `Generated_By` varchar(255) NOT NULL,
  `Handled_By` varchar(255) DEFAULT NULL,
  `Created_Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Finished_Time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Requests`
--

INSERT INTO `Requests` (`ID`, `State`, `Generated_By`, `Handled_By`, `Created_Time`, `Finished_Time`) VALUES
('2eX5Fe8z1ER', 'Canceled', '11@11.com', NULL, '2018-06-21 15:24:44', NULL),
('4vLYpmJ5KiU', 'Waiting', '11@11.com', NULL, '2018-06-22 17:11:00', NULL),
('bhWcomaLL9y', 'Canceled', '11@11.com', 'ad@ad.com', '2018-06-21 15:25:14', NULL),
('eXiK3cc5f0b', 'Finished', '11@11.com', 'ad@ad.com', '2018-06-21 15:27:58', '2018-06-21 15:28:17'),
('HsehiHGTku9', 'Canceled', '11@11.com', NULL, '2018-06-21 15:24:23', NULL),
('LdMRqDK4Bq3', 'Canceled', '11@11.com', NULL, '2018-06-21 15:25:02', NULL),
('qfTDUAD2mXY', 'Canceled', '11@11.com', NULL, '2018-06-21 15:02:02', NULL),
('XThOP6VX6bh', 'Waiting', '22@22.com', NULL, '2018-06-22 17:11:29', NULL);

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
  ADD KEY `q_fk` (`rID`);

--
-- Indexes for table `Requests`
--
ALTER TABLE `Requests`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `r_fk1` (`Generated_By`),
  ADD KEY `r_fk2` (`Handled_By`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Email`);

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `q_fk` FOREIGN KEY (`rID`) REFERENCES `Requests` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Requests`
--
ALTER TABLE `Requests`
  ADD CONSTRAINT `r_fk1` FOREIGN KEY (`Generated_By`) REFERENCES `Users` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_fk2` FOREIGN KEY (`Handled_By`) REFERENCES `Admins` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
