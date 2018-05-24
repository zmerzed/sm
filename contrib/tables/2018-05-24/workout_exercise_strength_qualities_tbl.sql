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
-- Table structure for table `workout_exercise_strength_qualities_tbl`
--

CREATE TABLE `workout_exercise_strength_qualities_tbl` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `options` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workout_exercise_strength_qualities_tbl`
--

INSERT INTO `workout_exercise_strength_qualities_tbl` (`id`, `name`, `options`) VALUES
(1, 'ES', '{\"set_options\":[1,2,3,4,5,6,7,8,9,10],\"rep_options\":[\"5 sec\",\"10 sec\",\"15 sec\",\"20 sec\",\"30 sec\",\"45 sec\",\"60 sec\",\"75 sec\",\"90 sec\",\"5 meter\",\"10 meters\",\"15 meters\",\"20 meters\",\"25 meters\",\"30 meters\",\"40 meters\",\"50 meters\",\"60 meters\",\"100 meters\",\"200 meters\",\"300 meters\",\"400 meters\",\"800 meters\",\"25 feet\",\"50 feet\",\"75 feet\",\"100 feet\"],\"tempo\":[\"maximal\",\"submaximal\",\"fast\",\"slow\",\"Moderate\"],\"rest\":[\"15 sec\",\"30 sec\",\"45 sec\",\"60 sec\",\"75 sec\",\"90 sec\",\"120 sec\",\"150 sec\",\"3 min\",\"4 min\",\"5 min\"]}'),
(2, 'H', '{\"set_options\":[1,2,3,4,5,6,7,8,9,10],\"rep_options\":[\"6\",\"7\",\"8\",\"9\",\"10\",\"12\",\"15\",\"20\",\"6-8\",\"7-9\",\"8-10\",\"10-12\",\"12-15\",\"15-20\",\"(6,x,x)*\",\"(8,x,x)*\",\"(6,6,6)**\",\"(8,8,8)**\",\"(10,6,4)**\",\"12,10,8\",\"15,12,10\",\"20,15,12\",\"8,6,4,4,6,8\",\"8,6,4,20\",\"7,5,3,7,5,3\",\"AMRAP\"],\"tempo\":[\"2011\",\"3011\",\"4011\",\"5011\",\"2110\",\"3110\",\"4110\",\"5110\"],\"rest\":[\"15 sec\",\"30 sec\",\"45 sec\",\"60 sec\",\"75 sec\",\"90 sec\",\"120 sec\",\"150 sec\",\"3 min\",\"4 min\",\"5 min\"],\"repetition_pattern\":[\"*rest-pause\",\"**drop set\",\"Cluster\",\"Ascending Load\",\"Constant Load\",\"Descending Load*\",\"Pyramid Load\",\"Wave Load\",\"Step Load\",\"AMRAP(as many reps as possible)\"],\"tempo_explained\":[\"The First Number - Using the squat as an example, the 3 will represent the amount of time (in seconds) it takes you to descend to the bottom position.\",\"The Second Number - The second number refers to the time spent in the bottom/transition between eccentric(lowering) and the concentric(ascending) portion of the exercise. The 0 means the trainee immediately begins their ascent after they reach the bottom postion. If the prescription were 32X0, the trainee would pause for 2 seconds at the bottom position.\",\"The Third Number - The third number refers to ascending (concentric) phase of the lift. The X  indicates that the trainee must EXPLODE at the initiation of the concentric action and try to accelerate the load throughout the entire range of motion. Intent is vital; the load may move slowly but you must try to move as fast as possible. If number is 2, it should take 2 seconds to complete the lift even if they are capable of moving it faster.\",\"The Fourth Number - The fourth number indicates the pause at the moment before the start of the next repetition of the lift. For a 45 degree back extension with a tempo of 2012, the trainee will hold an isometric contraction in the extended position for two seconds before lowering.\"]}'),
(3, 'MS', '{\"set_options\":[1,2,3,4,5,6,7,8,9,10],\"rep_options\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"2-4\",\"3-5\",\"4-6\",\"(1-1-1-1-1)\",\"(4,x,x)*\",\"(4,4,4)**\",\"5,3,2,2,3,5\",\"6,4,2,2,4,6\",\"5,4,3,2,1\",\"3,2,1,1,2,3\"],\"tempo\":[\"20X1\",\"30X1\",\"40X1\",\"50X1\",\"21X0\",\"31X0\",\"41X0\",\"51X0\"],\"rest\":[\"15 sec\",\"30 sec\",\"45 sec\",\"60 sec\",\"75 sec\",\"90 sec\",\"120 sec\",\"150 sec\",\"3 min\",\"4 min\",\"5 min\"],\"repetition_pattern\":[\"*rest-pause\",\"**drop set\",\"Cluster\",\"Ascending Load*\",\"Constant Load*\",\"Descending Load*\",\"Pyramid Load\",\"Wave Load\",\"Step Load\"],\"tempo_explained\":[\"The First Number - Using the squat as an example, the 3 will represent the amount of time (in seconds) it takes you to descend to the bottom position.\",\"The Second Number - The second number refers to the time spent in the bottom/transition between eccentric(lowering) and the concentric(ascending) portion of the exercise. The 0 means the trainee immediately begins their ascent after they reach the bottom postion. If the prescription were 32X0, the trainee would pause for 2 seconds at the bottom position.\",\"The Third Number - The third number refers to ascending (concentric) phase of the lift. The X  indicates that the trainee must EXPLODE at the initiation of the concentric action and try to accelerate the load throughout the entire range of motion. Intent is vital; the load may move slowly but you must try to move as fast as possible. If number is 2, it should take 2 seconds to complete the lift even if they are capable of moving it faster.\",\"The Fourth Number u2013 The fourth number indicates the pause at the moment before the start of the next repetition of the lift. For a 45 degree back extension with a tempo of 2012, the trainee will hold an isometric contraction in the extended position for two seconds before lowering.\",\"*Ascending Load: Load is increased upon each successful set all while maintaining repetition range\",\"*Constant Load: load is slightly below your maximal performance for the prescribed repetitions, allows completition of all prescribed sets and reps\",\"*Descending Load: Initial load is at rep maximum, load is decrease after each set in order to maintain rep range\",\"*Pyramid Load: load is increased and repetitions decreased with each successive set\"]}'),
(4, 'SE', '{\"set_options\":[1,2,3,4,5,6,7,8,9,10],\"rep_options\":[\"20-25\",\"25-30\",\"35-40\",\"45-50\",\"50\",\"75\",\"100\",\"AMRAP\"],\"tempo\":[\"1010\",\"2010\",\"3010\",\"2011\",\"2110\",\"3011\",\"3110\"],\"rest\":[\"15 sec\",\"30 sec\",\"45 sec\",\"60 sec\",\"75 sec\",\"90 sec\",\"120 sec\",\"150 sec\",\"3 min\",\"4 min\",\"5 min\"],\"repetition_pattern\":[\"rest-pause\",\"drop set\",\"Cluster\",\"Ascending Load\",\"Constant Load\",\"Descending Load\",\"Pyramid Load\",\"Wave Load\",\"Step Load\"]}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workout_exercise_strength_qualities_tbl`
--
ALTER TABLE `workout_exercise_strength_qualities_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workout_exercise_strength_qualities_tbl`
--
ALTER TABLE `workout_exercise_strength_qualities_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
