-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 05:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `amount`, `category`) VALUES
(1, 'Code relay', 100.00, 'Technical'),
(2, 'Decode emoji', 150.00, 'Technical'),
(3, 'Creative writing', 100.00, 'Communication'),
(4, 'Listening to audio and answering questions', 50.00, 'Communication'),
(5, 'Meme guessing', 200.00, 'Arts'),
(6, 'Badminton', 250.00, 'Sports'),
(7, 'Tech Rapid fire', 200.00, 'Technical'),
(8, 'Reverse engineering', 150.00, 'Technical'),
(9, 'Art from waste', 300.00, 'Arts'),
(10, 'Bgmi', 250.00, 'Sports'),
(11, 'Room cricket', 100.00, 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utr` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `gender`, `email`, `phone`, `password`, `reg_date`, `utr`) VALUES
(1, 'mohammad', 'saniya', 'saniyamohd', 'Female', 'saniya@gmail.com', '8956478320', '$2y$10$nz/iSJNg42cfk46HwUsC3.RIAPFGSKZVe7blB5p.W5JaEt0BDsU/2', '2025-04-23 16:20:08', 'TEMP_1'),
(2, 'mohammad', 'abc', 'abcuser', 'Female', 'user@gmail.com', '9845673499', '$2y$10$TqFlvMRVC1ihuvWvIy8ZWuoSficm6knKeeVyKFdC1y9dbJLIx.r6W', '2025-04-24 05:06:02', 'TEMP_2');

-- --------------------------------------------------------

--
-- Table structure for table `users_events`
--

CREATE TABLE `users_events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT curdate(),
  `payment_time` time DEFAULT curtime(),
  `utr` varchar(100) DEFAULT NULL,
  `utr_status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_events`
--

INSERT INTO `users_events` (`id`, `user_id`, `event_name`, `amount`, `payment_date`, `payment_time`, `utr`, `utr_status`) VALUES
(1, 1, 'Creative writing', 100.00, NULL, NULL, NULL, 'Verified'),
(2, 1, 'Listening to audio and answering questions', 50.00, NULL, NULL, NULL, 'Verified'),
(3, 1, 'Meme guessing ', 200.00, NULL, NULL, NULL, 'Verified'),
(4, 2, 'Decode emoji ', 150.00, NULL, NULL, NULL, 'Rejected'),
(5, 2, 'Creative writing', 100.00, NULL, NULL, NULL, 'Rejected'),
(6, 4, 'Code relay', 100.00, NULL, NULL, NULL, 'Pending'),
(7, 7, 'Creative writing', 100.00, NULL, NULL, NULL, 'Pending'),
(8, 7, 'Listening to audio and answering questions', 50.00, NULL, NULL, NULL, 'Pending'),
(9, 7, 'Meme guessing', 200.00, NULL, NULL, NULL, 'Pending'),
(10, 9, 'Decode emoji', 150.00, NULL, NULL, NULL, 'Pending'),
(11, 9, 'Creative writing', 100.00, NULL, NULL, NULL, 'Pending'),
(12, 9, 'Listening to audio and answering questions', 50.00, NULL, NULL, NULL, 'Pending'),
(13, 10, 'Code relay', 100.00, '2025-04-30', '22:29:59', '512034868776', 'Verified'),
(14, 10, 'Decode emoji', 150.00, '2025-04-30', '22:29:59', '512034868776', 'Verified'),
(15, 10, 'Creative writing', 100.00, '2025-04-30', '22:29:59', '512034868776', 'Verified'),
(16, 11, 'Code relay', 100.00, '2026-02-14', '20:34:53', '512034868775', 'Pending'),
(17, 11, 'Decode emoji', 150.00, '2026-02-14', '20:34:53', '512034868775', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_name` (`event_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_utr` (`utr`);

--
-- Indexes for table `users_events`
--
ALTER TABLE `users_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users_events`
--
ALTER TABLE `users_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_events`
--
ALTER TABLE `users_events`
  ADD CONSTRAINT `users_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
