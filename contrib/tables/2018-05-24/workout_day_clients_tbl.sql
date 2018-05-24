-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2018 at 12:40 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sm`
--

-- --------------------------------------------------------

--
-- Table structure for table `workout_day_clients_tbl`
--

CREATE TABLE `workout_day_clients_tbl` (
  `workout_client_ID` int(11) UNSIGNED NOT NULL,
  `workout_client_dayID` int(11) NOT NULL,
  `workout_client_workout_ID` int(11) NOT NULL,
  `workout_clientID` int(11) NOT NULL,
  `workout_day_availability` int(11) NOT NULL,
  `workout_client_schedule` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `workout_day_clients_tbl`
--

INSERT INTO `workout_day_clients_tbl` (`workout_client_ID`, `workout_client_dayID`, `workout_client_workout_ID`, `workout_clientID`, `workout_day_availability`, `workout_client_schedule`) VALUES
(13, 9, 4, 3, 1, '2018-05-21 12:00:00'),
(14, 10, 5, 3, 1, '2018-05-22 12:00:00'),
(15, 11, 6, 3, 3, '2018-05-24 12:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_day_clients_tbl`
--
ALTER TABLE `workout_day_clients_tbl`
  ADD PRIMARY KEY (`workout_client_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_day_clients_tbl`
--
ALTER TABLE `workout_day_clients_tbl`
  MODIFY `workout_client_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
