-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2024 at 08:43 PM
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
(7, 'jacob@gmail.com', '$2y$10$.ZCBvS/RrLMSsIPMf9Rzv.lk6ommpH7uHbG5BLEeg8IB2pxSqwOd6', 'Jacob Jones', 'Degree', '012-3456788', 2, 'Active', '2024-11-27 18:47:56'),
(15, '', '', 'Tomaz', 'Diploma', '012-3456789', 6, 'Active', '2024-11-27 19:42:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
