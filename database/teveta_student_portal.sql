-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2026 at 06:35 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teveta_student_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `activity` text,
  `activity_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `department_id` int DEFAULT NULL,
  `course_name` varchar(100) NOT NULL,
  `duration` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  KEY `department_id` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `department_id`, `course_name`, `duration`) VALUES
(1, 1, 'Diploma in Information and Communication Technology', '4 Years'),
(2, 2, 'Diploma in Electrical Installation', '4 Years'),
(3, 3, 'Advanced in Building Construction', '3 Years'),
(4, 4, 'Diploma in Automotive Engineering', '4 Years'),
(5, 4, 'Diploma in FBW', '4 Years'),
(6, 4, 'Diploma in TFD', '4 Years'),
(7, 5, 'Diploma in Business Management', '4 Years');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'ICT'),
(2, 'Electrical Engineering'),
(3, 'Building Construction'),
(4, 'Automotive Engineering'),
(5, 'FBW'),
(6, 'TFD'),
(7, 'Business Studies');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `result_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `assessment` decimal(5,2) DEFAULT NULL,
  `exam` decimal(5,2) DEFAULT NULL,
  `total` decimal(5,2) DEFAULT NULL,
  `grade` varchar(2) DEFAULT NULL,
  `remarks` varchar(20) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`result_id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `result_requests`
--

DROP TABLE IF EXISTS `result_requests`;
CREATE TABLE IF NOT EXISTS `result_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `message` text,
  PRIMARY KEY (`request_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

DROP TABLE IF EXISTS `semesters`;
CREATE TABLE IF NOT EXISTS `semesters` (
  `semester_id` int NOT NULL AUTO_INCREMENT,
  `semester_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `semester_name`) VALUES
(1, 'level 1'),
(2, 'level 2'),
(3, 'level 3'),
(4, 'level 4');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `reg_number` varchar(30) DEFAULT NULL,
  `national_id` varchar(30) DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `intake_year` year DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `reg_number` (`reg_number`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  KEY `department_id` (`department_id`),
  KEY `semester_id` (`semester_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Student') DEFAULT 'Student',
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `student_id`, `email`, `phone`, `gender`, `password`, `role`, `profile_picture`, `status`, `created_at`) VALUES
(1, 'System Administrator', NULL, 'admin@teveta.ac.mw', NULL, NULL, '$2y$10$1234567890123456789012345678901234567890123456789012', 'Admin', 'default.png', 'Active', '2026-07-30 03:13:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
