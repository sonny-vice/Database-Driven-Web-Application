-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 08:28 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `software_projects_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `pid` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `short_description` text NOT NULL,
  `phase` enum('design','development','testing','deployment','complete') NOT NULL,
  `uid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`pid`, `title`, `start_date`, `end_date`, `short_description`, `phase`, `uid`, `created_at`, `updated_at`) VALUES
(1, 'Hospital Booking System', '2026-01-10', '2026-03-15', 'Online system for booking hospital appointments', 'development', 1, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(2, 'E-commerce Website Redesign', '2026-02-01', NULL, 'Redesign of an existing online store for better UX', 'design', 2, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(3, 'Student Attendance Tracker', '2026-01-20', '2026-02-28', 'System to track and manage student attendance records', 'testing', 1, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(4, 'Fitness Mobile App', '2026-03-05', NULL, 'Mobile app for tracking workouts and nutrition', 'development', 3, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(5, 'HR Management Dashboard', '2026-02-15', '2026-04-10', 'Internal dashboard for managing employee data', 'deployment', 2, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(6, 'Customer Support Chatbot', '2026-01-05', '2026-03-01', 'AI chatbot for handling customer queries', 'complete', 3, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(7, 'Inventory Management System', '2026-02-10', NULL, 'Tool for managing stock and inventory levels', 'development', 1, '2026-04-06 09:30:16', '2026-04-06 09:30:16'),
(8, 'Online Learning Platform', '2026-01-25', '2026-05-01', 'Platform for hosting and delivering online courses', 'design', 2, '2026-04-06 09:30:16', '2026-04-06 09:30:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'alexdev', '$2y$10$examplehash1', 'alex@example.com', '2026-04-06 09:29:00'),
(2, 'maria_pm', '$2y$10$examplehash2', 'maria@example.com', '2026-04-06 09:29:00'),
(3, 'saulo_test', '$2y$10$examplehash3', 'saulo@example.com', '2026-04-06 09:29:00'),
(11, 'testuser', '$2y$10$GCkh9nfLV6o6cjXBBRZaneMEcP34stsbvTykcYq9z096YwicCC3gO', 'test@user.com', '2026-04-06 15:09:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `fk_projects_user` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_user` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
