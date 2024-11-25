-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 09:16 AM
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
(6, 1, '2024-11-24', 'present'),
(7, 1, '2024-11-23', 'present'),
(8, 1, '2024-11-22', 'absent'),
(9, 1, '2024-11-21', 'present'),
(10, 1, '2024-11-20', 'present'),
(11, 1, '2024-11-25', 'present'),
(12, 2, '2024-11-25', 'present'),
(13, 3, '2024-11-25', 'present');

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
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `manager_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`manager_id`, `username`, `password`) VALUES
(1, 'manager', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `details` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `price`, `details`) VALUES
(1, 'Pre-primary/Primary: Regular-Monthly', 280, 'This is the Monthly payment package for the Regular program in Pre-primary and Primary level - 8 Hours'),
(2, 'Pre-primary/Primary: Regular-Quarterly', 800, 'This is the Quarterly payment package for the Regular program in Pre-primary and Primary level - 24 Hours'),
(3, 'Pre-primary/Primary: Regular-HalfYearly', 1560, 'This is the Half Yearly payment package for the Regular program in Pre-primary and Primary level - 48 Hours'),
(4, 'Pre-primary/Primary: Maintenance-Quarterly', 690, 'This is the Quarterly payment package for the Maintenance program in Pre-primary and Primary level - 18 Hours'),
(5, 'Pre-primary/Primary: Intensive-Monthly', 420, 'This is the Monthly payment package for the Intensive program in Pre-primary and Primary level - 12 Hours'),
(6, 'Pre-primary/Primary: Intensive-Quarterly', 1200, 'This is the Quarterly payment package for the Intensive program in Pre-primary and Primary level - 36 Hours'),
(7, 'Pre-primary/Primary: SuperIntensive-Monthly', 560, 'This is the Monthly payment package for the Super Intensive program in Pre-primary and Primary level - 16 Hours'),
(8, 'Pre-primary/Primary: SuperIntensive-Quarterly', 1600, 'This is the Quarterly payment package for the Super Intensive program in Pre-primary and Primary level - 48 Hours'),
(9, 'Secondary: Regular-Monthly', 330, 'This is the Monthly payment package for the Regular program in Secondary level - 8 Hours'),
(10, 'Secondary: Regular-Quarterly', 945, 'This is the Quarterly payment package for the Regular program in Secondary level - 24 Hours'),
(11, 'Secondary: Regular-HalfYearly', 1850, 'This is the Half Yearly payment package for the Regular program in Secondary level - 48 Hours'),
(12, 'Secondary: Maintenance-Quarterly', 815, 'This is the Quarterly payment package for the Maintenance program in Secondary level - 18 Hours'),
(13, 'Secondary: Intensive-Monthly', 495, 'This is the Monthly payment package for the Intensive program in Secondary level - 12 Hours'),
(14, 'Secondary: Intensive-Quarterly', 1415, 'This is the Quarterly payment package for the Intensive program in Secondary level - 36 Hours'),
(15, 'Secondary: SuperIntensive-Monthly', 660, 'This is the Monthly payment package for the Super Intensive program in Secondary level - 16 Hours'),
(16, 'Secondary: SuperIntensive-Quarterly', 1885, 'This is the Quarterly payment package for the Super Intensive program in Secondary level - 48 Hours'),
(17, 'Upper Secondary: Regular-Monthly', 380, 'This is the Monthly payment package for the Regular program in Upper Secondary level - 8 Hours'),
(18, 'Upper Secondary: Regular-Quarterly', 1085, 'This is the Quarterly payment package for the Regular program in Upper Secondary level - 24 Hours'),
(19, 'Upper Secondary: Regular-HalfYearly', 2130, 'This is the Half Yearly payment package for the Regular program in Upper Secondary level - 48 Hours'),
(20, 'Upper Secondary: Maintenance-Quarterly', 935, 'This is the Quarterly payment package for the Maintenance program in Upper Secondary level - 18 Hours'),
(21, 'Upper Secondary: Intensive-Monthly', 570, 'This is the Monthly payment package for the Intensive program in Upper Secondary level - 12 Hours'),
(22, 'Upper Secondary: Intensive-Quarterly', 1625, 'This is the Quarterly payment package for the Intensive program in Upper Secondary level - 36 Hours'),
(23, 'Upper Secondary: SuperIntensive-Monthly', 760, 'This is the Monthly payment package for the Super Intensive program in Upper Secondary level - 16 Hours'),
(24, 'Upper Secondary: SuperIntensive-Quarterly', 2170, 'This is the Quarterly payment package for the Super Intensive program in Upper Secondary level - 48 Hours'),
(25, 'Post-Secondary: Regular-Monthly', 480, 'This is the Monthly payment package for the Regular program in Post-Secondary level - 8 Hours'),
(26, 'Post-Secondary: Regular-Quarterly', 1370, 'This is the Quarterly payment package for the Regular program in Post-Secondary level - 24 Hours'),
(27, 'Post-Secondary: Regular-HalfYearly', 2700, 'This is the Half Yearly payment package for the Regular program in Post-Secondary level - 48 Hours'),
(28, 'Post-Secondary: Maintenance-Quarterly', 1180, 'This is the Quarterly payment package for the Maintenance program in Post-Secondary level - 18 Hours'),
(29, 'Post-Secondary: Intensive-Monthly', 720, 'This is the Monthly payment package for the Intensive program in Post-Secondary level - 12 Hours'),
(30, 'Post-Secondary: Intensive-Quarterly', 2055, 'This is the Quarterly payment package for the Intensive program in Post-Secondary level - 36 Hours'),
(31, 'Post-Secondary: SuperIntensive-Monthly', 960, 'This is the Monthly payment package for the Super Intensive program in Post-Secondary level - 16 Hours'),
(32, 'Post-Secondary: SuperIntensive-Quarterly', 2740, 'This is the Quarterly payment package for the Super Intensive program in Post-Secondary level - 48 Hours');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parent_id` int(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parent_id`, `username`, `password`, `name`) VALUES
(1729434667, 'test', '$2y$10$hYXsldyOh9FPa9HWD3Ycc.sUjuwNwdJD6ToTFhMgT3tOnG99ezPiG', 'Mathology'),
(2, 'asdf', '$2y$10$rioS6IyB9wWcAd7r.FaonetXtAuswXreZLAMS.eIB/3MDs01D.QUK', 'asdf');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `registration` tinyint(1) DEFAULT 0,
  `deposit_fee` int(11) DEFAULT NULL,
  `diagnostic_test` tinyint(1) DEFAULT 0,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `parent_id`, `student_id`, `package_id`, `amount`, `date`, `payment_method`, `registration`, `deposit_fee`, `diagnostic_test`, `status`) VALUES
(1, 1729434667, 1, 1, 530, '2024-10-28', 'cash', 1, 100, 1, 'paid'),
(2, 1729434667, 1, 1, 380, '2024-10-31', 'credit-card', 0, 100, 0, 'paid'),
(3, 1729434667, 1, 1, 2435, '2024-11-05', 'cash', 1, 400, 1, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qualification` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `leave_left` int(11) DEFAULT NULL,
  `current_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `username`, `password`, `name`, `qualification`, `contact_number`, `leave_left`, `current_status`) VALUES
(1, 'jane', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jane Cooper', 'Degree', '012-3456789', 2, 'Active'),
(2, 'miles', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Floyd Miles', 'Degree', '012-3456789', 3, 'On Leave'),
(3, 'ronald', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Ronald Richards', 'Degree', '012-3456789', 2, 'On Leave'),
(4, 'marvin', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Marvin McKinney', 'Degree', '012-3456789', 8, 'Active'),
(5, 'bell', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jerome Bell', 'Degree', '012-3456789', 6, 'Active'),
(6, 'murphy', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Kathryn Murphy', 'Degree', '012-3456789', 5, 'Active'),
(7, 'jacob', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jacob Jones', 'Degree', '012-3456788', 2, 'Active');

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
  `total_fees` decimal(10,2) DEFAULT NULL,
  `fees_paid` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `parent_id`, `student_name`, `class`, `package_id`, `total_fees`, `fees_paid`, `created_at`) VALUES
(1, 1729434667, 'Student One', 'Class A', 1, 100.00, 0.00, '2024-11-04 15:34:36'),
(2, 1729434667, 'Student Two', 'Class B', 2, 1000.00, 1000.00, '2024-11-04 15:34:36'),
(3, 1729434667, 'Student 3', 'Class A', 3, 1500.00, 1000.00, '2024-11-07 13:44:30');

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `username` (`username`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `package_id` (`package_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `parent_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1732498610;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
