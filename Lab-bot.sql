-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 20, 2018 at 03:04 PM
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
-- Table structure for table `Queue`
--

CREATE TABLE `Queue` (
  `Position` int(11) NOT NULL,
  `rID` varchar(255) NOT NULL,
  `Handling_By` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('4psVMiIL0Dq', 'Finished', '11@11.com', 'as@as.com', '2018-06-19 17:30:57', '2018-06-19 17:31:36'),
('5u13rwAWM4j', 'Finished', '22@22.com', 'ad@ad.com', '2018-06-19 17:31:09', '2018-06-19 17:32:15'),
('70aQRWqS5fK', 'Finished', '33@33.com', 'ad@ad.com', '2018-06-19 19:23:13', '2018-06-19 19:31:22'),
('C2nDjvqLEdk', 'Finished', '11@11.com', 'as@as.com', '2018-06-20 10:58:52', '2018-06-20 10:59:23'),
('Eu2McCfOeNQ', 'Canceled', '44@44.com', 'as@as.com', '2018-06-19 17:31:25', NULL),
('Fl4u6xFms6S', 'Canceled', '22@22.com', 'as@as.com', '2018-06-19 17:54:24', NULL),
('fpjMvfvrqiG', 'Finished', '11@11.com', 'as@as.com', '2018-06-19 19:21:34', '2018-06-19 19:24:20'),
('hMtHqReWG7v', 'Canceled', '44@44.com', NULL, '2018-06-19 19:30:09', NULL),
('IXKBwfAWpBS', 'Suspended', '33@33.com', 'as@as.com', '2018-06-20 11:05:00', NULL),
('JtQzjyMkfLD', 'Canceled', '33@33.com', 'as@as.com', '2018-06-19 17:54:52', NULL),
('kWxREfywCEB', 'Finished', '33@33.com', 'as@as.com', '2018-06-19 17:14:47', '2018-06-19 17:15:29'),
('NKf7uxD0USa', 'Finished', '33@33.com', 'as@as.com', '2018-06-19 17:31:18', '2018-06-19 17:32:01'),
('qOfI2pbN7kb', 'Finished', '22@22.com', 'ad@ad.com', '2018-06-19 17:13:59', '2018-06-19 17:15:53'),
('r8xxKQ2YULh', 'Finished', '11@11.com', 'as@as.com', '2018-06-19 17:13:32', '2018-06-19 17:15:13'),
('Rbte6t6ZZPT', 'Finished', '22@22.com', 'ad@ad.com', '2018-06-20 11:04:06', '2018-06-20 11:22:40'),
('sd5IkA1Ffaj', 'Finished', '11@11.com', 'as@as.com', '2018-06-20 11:02:39', '2018-06-20 11:07:31'),
('SRjpk92FIus', 'Canceled', '33@33.com', NULL, '2018-06-19 17:35:06', NULL),
('T6JqTOFQueG', 'Finished', '44@44.com', 'as@as.com', '2018-06-19 19:24:49', '2018-06-19 19:25:13'),
('TFq814OFgYg', 'Canceled', '33@33.com', 'ad@ad.com', '2018-06-19 17:36:00', NULL),
('UaF3wEIK9hG', 'Finished', '44@44.com', 'as@as.com', '2018-06-19 17:55:01', '2018-06-19 17:57:20'),
('UkKGyc93zar', 'Finished', '22@22.com', 'ad@ad.com', '2018-06-19 19:22:16', '2018-06-19 19:24:06'),
('vR0UmVw2te8', 'Canceled', '44@44.com', NULL, '2018-06-19 19:21:16', NULL),
('VunKpE4bvDd', 'Finished', '44@44.com', 'as@as.com', '2018-06-19 17:43:49', '2018-06-19 17:49:33'),
('XqpQfnLjI63', 'Canceled', '11@11.com', NULL, '2018-06-19 17:34:51', NULL),
('y8NJJnPU5bn', 'Canceled', '22@22.com', NULL, '2018-06-20 10:59:11', NULL),
('Zf7TibTjRl7', 'Finished', '11@11.com', 'ad@ad.com', '2018-06-19 17:54:13', '2018-06-19 17:57:16');

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
