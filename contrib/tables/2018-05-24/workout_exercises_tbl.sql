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
-- Table structure for table `workout_exercises_tbl`
--

CREATE TABLE `workout_exercises_tbl` (
  `exer_ID` int(11) NOT NULL,
  `exer_day_ID` int(11) NOT NULL,
  `exer_workout_ID` int(11) NOT NULL,
  `exer_body_part` varchar(255) DEFAULT NULL,
  `exer_type` varchar(255) DEFAULT NULL,
  `exer_exercise_1` varchar(255) DEFAULT NULL,
  `exer_exercise_2` varchar(255) DEFAULT NULL,
  `exer_sq` varchar(10) DEFAULT NULL,
  `exer_sets` varchar(10) DEFAULT NULL,
  `exer_rep` text,
  `exer_tempo` varchar(255) DEFAULT NULL,
  `exer_rest` varchar(255) DEFAULT NULL,
  `exer_impl1` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workout_exercises_tbl`
--

INSERT INTO `workout_exercises_tbl` (`exer_ID`, `exer_day_ID`, `exer_workout_ID`, `exer_body_part`, `exer_type`, `exer_exercise_1`, `exer_exercise_2`, `exer_sq`, `exer_sets`, `exer_rep`, `exer_tempo`, `exer_rest`, `exer_impl1`) VALUES
(5, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 9, 4, 'Abs/Hip Flexors', NULL, NULL, NULL, 'ES', NULL, '20 sec', NULL, NULL, NULL),
(13, 10, 5, 'Abs/Hip Flexors', NULL, NULL, NULL, 'H', '5', '7', NULL, '45 sec', NULL),
(14, 10, 5, 'Back', 'Bent-Over Row - Elbows Out', NULL, NULL, 'ES', '7', '30 sec', 'maximal', '45 sec', NULL),
(15, 11, 6, 'Abs/Hip Flexors', 'Abs Wheel Rollout', NULL, NULL, 'ES', '6', '30 sec', 'maximal', '60 sec', NULL),
(16, 11, 6, 'Abs/Hip Flexors', 'Core Row', NULL, NULL, 'ES', '6', '45 sec', 'Moderate', '30 sec', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_exercises_tbl`
--
ALTER TABLE `workout_exercises_tbl`
  ADD PRIMARY KEY (`exer_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_exercises_tbl`
--
ALTER TABLE `workout_exercises_tbl`
  MODIFY `exer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
