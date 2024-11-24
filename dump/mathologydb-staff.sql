-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 02:55 PM
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
-- Database: `mathologydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `status` enum('present','absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `date`, `status`) VALUES
(1, 1, '2023-06-01', 'present'),
(2, 1, '2023-06-02', 'present'),
(3, 1, '2023-06-03', 'absent'),
(4, 2, '2023-06-01', 'present'),
(5, 2, '2023-06-02', 'absent'),
(8, 1, '2024-11-07', 'absent'),
(9, 2, '2024-11-07', 'absent'),
(10, 3, '2024-11-07', 'absent');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_classes`
--

CREATE TABLE `enrolled_classes` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolled_classes`
--

INSERT INTO `enrolled_classes` (`id`, `student_id`, `class_name`) VALUES
(1, 1, 'Advanced Mathematics'),
(2, 1, 'Physics Fundamentals'),
(3, 2, 'Advanced Mathematics'),
(4, 2, 'Computer Science Basics'),
(5, 1, 'Mathematics 101'),
(6, 1, 'Advanced Algebra'),
(7, 2, 'Geometry Basics');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `leave_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `fromDate` date DEFAULT NULL,
  `toDate` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leave_id`, `student_id`, `reason`, `fromDate`, `toDate`, `status`, `document_path`, `created_at`) VALUES
(1, 1, 'Family vacation', '2023-07-01', '2023-07-05', 'pending', NULL, '2024-11-04 15:34:36'),
(2, 2, 'Medical appointment', '2023-07-10', '2023-07-10', 'pending', NULL, '2024-11-04 15:34:36');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `details` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `price`, `details`) VALUES
(1, `package_1`, 'XX'),
(2, `package_2`, 'XX');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parent_id` int(50) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parent_id`, `username`, `password`, `name`) VALUES
(1729434667, 'test', '$2y$10$hYXsldyOh9FPa9HWD3Ycc.sUjuwNwdJD6ToTFhMgT3tOnG99ezPiG', 'Mathology');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(50) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `username`, `password`, `name`) VALUES
(1729434667, 'test', '$2y$10$hYXsldyOh9FPa9HWD3Ycc.sUjuwNwdJD6ToTFhMgT3tOnG99ezPiG', 'Mathology');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `student_name` varchar(100) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `parent_id`, `student_name`, `class`, `package_id`, `created_at`) VALUES
(1, 1729434667, 'Student One', 'Class A', '1', '2024-11-04 15:34:36'),
(2, 1729434667, 'Student Two', 'Class B', '2', '2024-11-04 15:34:36'),
(3, 1729434667, 'Student 3', 'Class A', '3', '2024-11-07 13:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `room` varchar(50) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `student_id`, `subject_id`, `title`, `room`, `instructor`, `time`) VALUES
(1, 1, 101, 'Algebra', 'Room A1', 'Dr. Smith', 'Monday 9:00 AM'),
(2, 1, 102, 'Geometry', 'Room B2', 'Prof. Johnson', 'Tuesday 11:00 AM'),
(3, 1, 103, 'Calculus', 'Room C3', 'Ms. Williams', 'Wednesday 2:00 PM'),
(4, 2, 101, 'Algebra', 'Room A1', 'Dr. Smith', 'Monday 10:30 AM'),
(5, 2, 104, 'Statistics', 'Room D4', 'Mr. Brown', 'Thursday 1:00 PM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student` (`student_id`);

--
-- Indexes for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD UNIQUE KEY `parent_id` (`parent_id`) USING BTREE;

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;