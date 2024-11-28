-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 01:40 AM
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
  `status` enum('present','absent','late') NOT NULL
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
(13, 3, '2024-11-25', 'present'),
(14, 1, '2024-11-26', 'absent'),
(15, 2, '2024-11-26', 'present'),
(16, 3, '2024-11-26', 'present'),
(17, 1, '2024-11-27', 'present'),
(18, 2, '2024-11-27', 'present'),
(19, 3, '2024-11-27', 'present');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_classes`
--

CREATE TABLE `enrolled_classes` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolled_classes`
--

INSERT INTO `enrolled_classes` (`id`, `student_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 4);

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
  `response_reason` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `supportive_document_path` varchar(255) DEFAULT NULL,
  `leave_type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leave_id`, `student_id`, `reason`, `fromDate`, `toDate`, `status`, `response_reason`, `document_path`, `supportive_document_path`, `leave_type`, `created_at`) VALUES
(46, 3, 'asdf', '2024-12-01', '2024-12-02', 'approved', NULL, NULL, NULL, 'normal', '2024-11-28 00:12:27'),
(48, 3, 'dfs', '2025-01-01', '2025-01-31', 'approved', 'Approved', NULL, NULL, 'gap', '2024-11-28 00:18:12'),
(49, 3, 'no', '2024-12-01', '2024-12-02', 'rejected', 'test', '../uploads/medical/mc_Project_Schedule_6747b7c24a24f.jpg', NULL, 'medical', '2024-11-28 00:22:26'),
(50, 3, 'asfd', '2024-12-02', '2024-12-03', 'approved', 'Approved', '../uploads/medical/mc_Project_Schedule_6747b963c08df.jpg', NULL, 'medical', '2024-11-28 00:29:23'),
(51, 3, 'sdf', '2024-11-01', '2024-11-30', 'approved', 'Approved', NULL, NULL, 'gap', '2024-11-28 00:35:09'),
(52, 2, 'ssadd', '2024-11-01', '2024-11-30', 'approved', 'dfasdssaddfffdadf', NULL, NULL, 'gap', '2024-11-28 00:38:10'),
(53, 2, 'dfs', '2024-11-01', '2024-11-30', 'approved', 'Approved', NULL, NULL, 'gap', '2024-11-28 00:38:14'),
(54, 3, 'sdfsdf', '2024-11-01', '2024-11-30', 'rejected', 'adf', NULL, NULL, 'gap', '2024-11-28 00:38:20');

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
  `deposit_fee` int(11) DEFAULT NULL,
  `details` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `price`, `deposit_fee`, `details`) VALUES
(1, 'Pre-Primary and Primary Level Regular Program (Monthly)', 280, 280, 'Monthly payment plan for Regular Program (Pre-Primary and Primary) - 8 hours per month'),
(2, 'Pre-Primary and Primary Level Regular Program (Quarterly)', 800, 280, 'Quarterly payment plan for Regular Program (Pre-Primary and Primary) - 24 hours per quarter'),
(3, 'Pre-Primary and Primary Level Regular Program (Half-Yearly)', 1560, 280, 'Half-Yearly payment plan for Regular Program (Pre-Primary and Primary) - 48 hours per 6 months'),
(4, 'Pre-Primary and Primary Level Maintenance Program (Quarterly)', 690, 280, 'Quarterly payment plan for Maintenance Program (Pre-Primary and Primary) - 18 hours per quarter'),
(5, 'Pre-Primary and Primary Level Intensive Program (Monthly)', 420, 280, 'Monthly payment plan for Intensive Program (Pre-Primary and Primary) - 12 hours per month'),
(6, 'Pre-Primary and Primary Level Intensive Program (Quarterly)', 1200, 280, 'Quarterly payment plan for Intensive Program (Pre-Primary and Primary) - 36 hours per quarter'),
(7, 'Pre-Primary and Primary Level Super Intensive Program (Monthly)', 560, 280, 'Monthly payment plan for Super Intensive Program (Pre-Primary and Primary) - 16 hours per month'),
(8, 'Pre-Primary and Primary Level Super Intensive Program (Quarterly)', 1600, 280, 'Quarterly payment plan for Super Intensive Program (Pre-Primary and Primary) - 48 hours per quarter'),
(9, 'Secondary Level Regular Program (Monthly)', 330, 330, 'Monthly payment plan for Regular Program (Secondary) - 8 hours per month'),
(10, 'Secondary Level Regular Program (Quarterly)', 945, 330, 'Quarterly payment plan for Regular Program (Secondary) - 24 hours per quarter'),
(11, 'Secondary Level Regular Program (Half-Yearly)', 1850, 330, 'Half-Yearly payment plan for Regular Program (Secondary) - 48 hours per 6 months'),
(12, 'Secondary Level Maintenance Program (Quarterly)', 815, 330, 'Quarterly payment plan for Maintenance Program (Secondary) - 18 hours per quarter'),
(13, 'Secondary Level Intensive Program (Monthly)', 495, 330, 'Monthly payment plan for Intensive Program (Secondary) - 12 hours per month'),
(14, 'Secondary Level Intensive Program (Quarterly)', 1415, 330, 'Quarterly payment plan for Intensive Program (Secondary) - 36 hours per quarter'),
(15, 'Secondary Level Super Intensive Program (Monthly)', 660, 330, 'Monthly payment plan for Super Intensive Program (Secondary) - 16 hours per month'),
(16, 'Secondary Level Super Intensive Program (Quarterly)', 1885, 330, 'Quarterly payment plan for Super Intensive Program (Secondary) - 48 hours per quarter'),
(17, 'Upper Secondary Level Regular Program (Monthly)', 380, 380, 'Monthly payment plan for Regular Program (Upper Secondary) - 8 hours per month'),
(18, 'Upper Secondary Level Regular Program (Quarterly)', 1085, 380, 'Quarterly payment plan for Regular Program (Upper Secondary) - 24 hours per quarter'),
(19, 'Upper Secondary Level Regular Program (Half-Yearly)', 2130, 380, 'Half-Yearly payment plan for Regular Program (Upper Secondary) - 48 hours per 6 months'),
(20, 'Upper Secondary Level Maintenance Program (Quarterly)', 935, 380, 'Quarterly payment plan for Maintenance Program (Upper Secondary) - 18 hours per quarter'),
(21, 'Upper Secondary Level Intensive Program (Monthly)', 570, 380, 'Monthly payment plan for Intensive Program (Upper Secondary) - 12 hours per month'),
(22, 'Upper Secondary Level Intensive Program (Quarterly)', 1625, 380, 'Quarterly payment plan for Intensive Program (Upper Secondary) - 36 hours per quarter'),
(23, 'Upper Secondary Level Super Intensive Program (Monthly)', 760, 380, 'Monthly payment plan for Super Intensive Program (Upper Secondary) - 16 hours per month'),
(24, 'Upper Secondary Level Super Intensive Program (Quarterly)', 2170, 380, 'Quarterly payment plan for Super Intensive Program (Upper Secondary) - 48 hours per quarter'),
(25, 'Post-Secondary Level Regular Program (Monthly)', 480, 480, 'Monthly payment plan for Regular Program (Post-Secondary) - 8 hours per month'),
(26, 'Post-Secondary Level Regular Program (Quarterly)', 1370, 480, 'Quarterly payment plan for Regular Program (Post-Secondary) - 24 hours per quarter'),
(27, 'Post-Secondary Level Regular Program (Half-Yearly)', 2700, 480, 'Half-Yearly payment plan for Regular Program (Post-Secondary) - 48 hours per 6 months'),
(28, 'Post-Secondary Level Maintenance Program (Quarterly)', 1180, 480, 'Quarterly payment plan for Maintenance Program (Post-Secondary) - 18 hours per quarter'),
(29, 'Post-Secondary Level Intensive Program (Monthly)', 720, 480, 'Monthly payment plan for Intensive Program (Post-Secondary) - 12 hours per month'),
(30, 'Post-Secondary Level Intensive Program (Quarterly)', 2055, 480, 'Quarterly payment plan for Intensive Program (Post-Secondary) - 36 hours per quarter'),
(31, 'Post-Secondary Level Super Intensive Program (Monthly)', 960, 480, 'Monthly payment plan for Super Intensive Program (Post-Secondary) - 16 hours per month'),
(32, 'Post-Secondary Level Super Intensive Program (Quarterly)', 2740, 480, 'Quarterly payment plan for Super Intensive Program (Post-Secondary) - 48 hours per quarter');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parent_id` int(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parent_id`, `email`, `password`, `name`) VALUES
(2, 'asdf@gmail.com', '$2y$10$rioS6IyB9wWcAd7r.FaonetXtAuswXreZLAMS.eIB/3MDs01D.QUK', 'asdf'),
(1729434667, 'p23015018@student.newinti.edu.my', '$2y$10$hYXsldyOh9FPa9HWD3Ycc.sUjuwNwdJD6ToTFhMgT3tOnG99ezPiG', 'Mathology'),
(1732524960, 'testing@example.com', '$2y$10$.5wdw9z5mB0tfj09q2VOYOdAH4dv3Wh0Bki34PRhoYs9WQD8.fBO.', 'testing');

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
(3, 1729434667, 1, 1, 2435, '2024-11-05', 'cash', 1, 400, 1, 'paid'),
(4, 1729434667, 1, 1, 910, '2024-10-29', 'cash', 1, 100, 1, 'paid'),
(5, 1729434667, 1, 31, 2109, '2024-11-08', 'cheque', 1, 999, 1, 'paid'),
(6, 1729434667, 1, 1, 660, '2024-11-27', 'cash', 0, 280, 1, 'paid'),
(7, 1729434667, 3, 3, 1990, '2024-11-22', 'cash', 1, 280, 1, 'paid'),
(8, 1729434667, 3, 3, 1990, '2024-11-01', 'cash', 1, 280, 1, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qualification` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `leave_left` int(11) DEFAULT NULL,
  `current_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `email`, `password`, `name`, `qualification`, `contact_number`, `leave_left`, `current_status`, `created_at`) VALUES
(1, 'jane@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jane Cooper', 'Degree', '012-3456789', 2, 'Active', '2024-11-27 18:47:56'),
(2, 'miles@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Floyd Miles', 'Degree', '012-3456789', 3, 'On Leave', '2024-11-27 18:47:56'),
(3, 'ronald@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Ronald Richards', 'Degree', '012-3456789', 2, 'On Leave', '2024-11-27 18:47:56'),
(4, 'marvin@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Marvin McKinney', 'Degree', '012-3456789', 8, 'Active', '2024-11-27 18:47:56'),
(5, 'bell@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jerome Bell', 'Degree', '012-3456789', 6, 'Active', '2024-11-27 18:47:56'),
(6, 'murphy@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Kathryn Murphy', 'Degree', '012-3456789', 5, 'Active', '2024-11-27 18:47:56'),
(15, 'p23015018@student.newinti.edu.my', '$2y$10$Y6OBN2F8a2oCLySEh.R1OujEq6C37chZ/bnIqXuZuOb1Mwhes98M6', 'Tomaz', 'Diploma', '012-3456789', 6, 'Active', '2024-11-27 19:42:32');

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
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `room` varchar(50) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `day` varchar(10) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject_id`, `title`, `room`, `instructor`, `day`, `start_time`, `end_time`) VALUES
(1, 101, 'Algebra', 'Room A1', 'Dr. Smith', 'Monday', '09:00:00', '11:00:00'),
(2, 102, 'Geometry', 'Room B2', 'Prof. Johnson', 'Tuesday', '11:00:00', '12:00:00'),
(3, 103, 'Calculus', 'Room C3', 'Ms. Williams', 'Wednesday', '14:00:00', '16:00:00'),
(4, 104, 'Statistics', 'Room D4', 'Mr. Brown', 'Thursday', '13:00:00', '18:00:00');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `student_id` (`student_id`);

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
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
  MODIFY `parent_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9223372036854775807;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  ADD CONSTRAINT `enrolled_classes_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrolled_classes_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
