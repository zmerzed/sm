-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2018 at 11:40 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

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
-- Table structure for table `workout_activity_logs`
--

CREATE TABLE IF NOT EXISTS `workout_activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_type` int(11) NOT NULL,
  `log_description` text NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workout_activity_logs`
--

INSERT INTO `workout_activity_logs` (`id`, `user_id`, `log_type`, `log_description`, `trainer_id`, `client_id`, `created_at`) VALUES
(1, 4, 0, 'Created Workout', 4, 0, '2018-05-30 08:01:31'),
(2, 4, 0, 'Completed Workout', 4, 3, '2018-05-30 07:13:02'),
(3, 4, 0, 'Assigned Workout', 4, 3, '2018-05-30 07:13:02'),
(4, 4, 0, 'Created Workout', 4, 0, '2018-05-30 07:13:02'),
(5, 4, 0, 'Created Workout', 4, 0, '2018-05-30 07:13:02'),
(6, 4, 0, 'Added Client', 4, 3, '2018-05-30 09:02:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_activity_logs`
--
ALTER TABLE `workout_activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_activity_logs`
--
ALTER TABLE `workout_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
