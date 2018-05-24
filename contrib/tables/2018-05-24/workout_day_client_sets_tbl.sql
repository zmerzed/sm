-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2018 at 12:41 PM
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
-- Table structure for table `workout_day_client_sets_tbl`
--

CREATE TABLE `workout_day_client_sets_tbl` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `set1_rep_1` double DEFAULT NULL,
  `set1_rep_2` double DEFAULT NULL,
  `set1_rep_3` double DEFAULT NULL,
  `set1_rep_4` double DEFAULT NULL,
  `set1_weight_1` double DEFAULT NULL,
  `set1_weight_2` double DEFAULT NULL,
  `set1_weight_3` double DEFAULT NULL,
  `set1_weight_4` double DEFAULT NULL,
  `set2_rep_1` double DEFAULT NULL,
  `set2_rep_2` double DEFAULT NULL,
  `set2_rep_3` double DEFAULT NULL,
  `set2_rep_4` double DEFAULT NULL,
  `set2_weight_1` double DEFAULT NULL,
  `set2_weight_2` double DEFAULT NULL,
  `set2_weight_3` double DEFAULT NULL,
  `set2_weight_4` double DEFAULT NULL,
  `set3_rep_1` double DEFAULT NULL,
  `set3_rep_2` double DEFAULT NULL,
  `set3_rep_3` double DEFAULT NULL,
  `set3_rep_4` double DEFAULT NULL,
  `set3_weight_1` double DEFAULT NULL,
  `set3_weight_2` double DEFAULT NULL,
  `set3_weight_3` double DEFAULT NULL,
  `set3_weight_4` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `workout_day_client_sets_tbl`
--

INSERT INTO `workout_day_client_sets_tbl` (`id`, `client_id`, `day_id`, `set1_rep_1`, `set1_rep_2`, `set1_rep_3`, `set1_rep_4`, `set1_weight_1`, `set1_weight_2`, `set1_weight_3`, `set1_weight_4`, `set2_rep_1`, `set2_rep_2`, `set2_rep_3`, `set2_rep_4`, `set2_weight_1`, `set2_weight_2`, `set2_weight_3`, `set2_weight_4`, `set3_rep_1`, `set3_rep_2`, `set3_rep_3`, `set3_rep_4`, `set3_weight_1`, `set3_weight_2`, `set3_weight_3`, `set3_weight_4`) VALUES
(5, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 3, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 3, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 3, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_day_client_sets_tbl`
--
ALTER TABLE `workout_day_client_sets_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_day_client_sets_tbl`
--
ALTER TABLE `workout_day_client_sets_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
