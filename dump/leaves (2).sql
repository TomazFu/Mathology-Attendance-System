.leave-requests-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.leave-request-card {
    background: white;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    gap: 40px;
}

.leave-details {
    flex: 2;
}

.leave-details h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.leave-details p {
    margin: 5px 0;
    color: #666;
}

.approval-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-right: 20px;
    min-width: 300px;
}

.response-reason {
    width: 100%;
    min-height: 100px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
    margin-bottom: 10px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.approve-btn, .reject-btn {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
}

.approve-btn {
    background-color: #4CAF50;
    color: white;
}

.reject-btn {
    background-color: #f44336;
    color: white;
}

.approve-btn:hover {
    background-color: #45a049;
}

.reject-btn:hover {
    background-color: #da190b;
}

.no-requests {
    text-align: center;
    padding: 40px;
    color: #666;
} -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 12:49 AM
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
  `supportive_document_path` varchar(255) DEFAULT NULL,
  `leave_type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leave_id`, `student_id`, `reason`, `fromDate`, `toDate`, `status`, `document_path`, `supportive_document_path`, `leave_type`, `created_at`) VALUES
(19, 2, 'x', '2024-11-07', '2024-11-25', 'pending', '../../uploads/medical/mc_erd_6744d3911ca5b.png', NULL, 'medical', '2024-11-25 19:44:17'),
(20, 2, 'x', '2024-11-07', '2024-11-25', 'pending', '../../uploads/medical/mc_erd_6744d3911e787.png', NULL, 'medical', '2024-11-25 19:44:17'),
(38, 1, 'test', '2024-10-31', '2024-11-06', 'pending', '../../uploads/medical/mc_use_case_67476f913e8b4.png', '../../uploads/supporting/erd_67476f913ebb0.png', 'medical', '2024-11-27 19:14:25'),
(39, 3, 'asf', '2024-12-01', '2024-12-31', 'pending', NULL, NULL, 'gap', '2024-11-27 19:17:07'),
(42, 3, 'asdf', '2024-12-06', '2024-12-07', 'approved', NULL, NULL, 'normal', '2024-11-27 19:30:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
