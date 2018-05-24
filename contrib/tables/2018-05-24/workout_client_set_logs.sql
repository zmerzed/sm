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
-- Table structure for table `workout_client_set_logs`
--

CREATE TABLE `workout_client_set_logs` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `exercise_log_id` int(11) NOT NULL,
  `reps` varchar(255) NOT NULL,
  `isMet` tinyint(1) NOT NULL,
  `isDone` tinyint(1) NOT NULL,
  `seq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workout_client_set_logs`
--

INSERT INTO `workout_client_set_logs` (`id`, `client_id`, `exercise_log_id`, `reps`, `isMet`, `isDone`, `seq`) VALUES
(28, 3, 6, '', 1, 1, 1),
(29, 3, 6, '', 1, 1, 2),
(30, 3, 6, '', 1, 1, 3),
(31, 3, 6, '', 1, 1, 4),
(32, 3, 6, '', 1, 1, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_client_set_logs`
--
ALTER TABLE `workout_client_set_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_client_set_logs`
--
ALTER TABLE `workout_client_set_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
