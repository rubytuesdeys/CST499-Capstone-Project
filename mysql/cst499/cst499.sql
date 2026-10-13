-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2026 at 09:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cst499`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_code` varchar(10) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `instructor_name` varchar(100) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `enrolled_count` int(11) DEFAULT 0,
  `semester` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_code`, `class_name`, `instructor_name`, `capacity`, `enrolled_count`, `semester`) VALUES
(1, 'CST499', 'Capstone Project', 'Dr. Butler', 10, 5, 'Fall 2024'),
(2, 'CST310', 'Web Development', 'Dr. Johnson', 30, 17, 'Fall 2024'),
(3, 'MATH201', 'Calculus I', 'Prof. Williams', 35, 23, 'Fall 2024'),
(4, 'ENG101', 'English Composition', 'Prof. Brown', 25, 23, 'Fall 2024'),
(5, 'PHYS101', 'Physics I', 'Prof. Davis', 20, 20, 'Fall 2024'),
(6, 'CHEM101', 'Chemistry I', 'Dr. Miller', 28, 28, 'Fall 2024'),
(7, 'HIST201', 'World History', 'Prof. Wilson', 30, 28, 'Fall 2024'),
(8, 'BIO101', 'Biology I', 'Prof. Moore', 25, 13, 'Fall 2024'),
(9, 'CS201', 'Data Structures', 'Dr. Taylor', 25, 17, 'Fall 2024'),
(10, 'ECON101', 'Microeconomics', 'Prof. Anderson', 35, 30, 'Fall 2024');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'active',
  `grade` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `class_id`, `enrollment_date`, `status`, `grade`) VALUES
(6, 2, 6, '2026-10-05 03:51:39', 'waitlisted', NULL),
(7, 2, 1, '2026-10-05 04:16:55', 'active', NULL),
(9, 2, 5, '2026-10-05 04:17:04', 'waitlisted', NULL),
(11, 2, 3, '2026-10-05 04:49:36', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `address` varchar(70) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `student_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `address`, `phone`, `role`, `student_id`) VALUES
(1, 'admin.admin@Keystone.student.edu', '$2y$10$ZX1j2u.mX9fXeBGv/95rAufqk.CqnTDmedta59jsRYQ4pZi8Ts6oO', 'Admin', 'Admin', '1234 Admin', '1234567898', 'admin', NULL),
(2, 'darth.vader@Keystone.student.edu', '$2y$10$bX9yH5enDfF7icXMda33kubFbgd9B7pleJkEKDhE4uWpDdU9RZ47i', 'Darth', 'Vader', '12345 Tatooine', '1234567890', 'user', 'DAVADE1562'),
(4, 'marty.mcly@Keystone.student.edu', '$2y$10$R0IeN6y8r4j7/UzjaP0mE.jkL41jHjNnx0HAnDHAbEHkYw6VqOnsu', 'Marty', 'McFly', '123456 Delorean Dr', '0011051995', 'user', 'MAMCFL8114');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_code` (`class_code`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
