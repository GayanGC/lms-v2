-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 08:25 AM
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
-- Database: `lms_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(1, 17, 'Updated Profile', 'Fields: name, email, phone, address, bio, pic', '2025-12-22 10:58:15'),
(2, 3, 'Updated Profile', 'Fields: name, email, phone, address, bio, pic', '2025-12-22 21:24:26'),
(3, 1, 'Enrolled Course', 'Course ID: 22', '2025-12-22 21:43:39'),
(4, 1, 'Updated Profile', 'Fields: name, email, phone, address, bio, pic', '2025-12-23 11:53:00'),
(5, 1, 'Updated Profile', 'Fields: name, email, phone, address, bio, pic', '2025-12-23 11:53:33'),
(6, 1, 'Enrolled Course', 'Course ID: 24', '2025-12-23 12:07:58'),
(7, 1, 'Enrolled Course', 'Course ID: 20', '2025-12-23 12:13:22'),
(8, 3, 'Created Course', 'Title: bio', '2025-12-23 12:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `duration`, `instructor_id`, `created_at`) VALUES
(1, 'ps', 'maths subject', '4 Weeks', 3, '2025-12-18 09:55:42'),
(2, 'java', 'for coding', '8 Weeks', 4, '2025-12-18 11:05:15'),
(20, 'Web Development Basics', 'Learn HTML, CSS, and JavaScript from scratch.', '8 Weeks', 10, '2025-12-18 18:57:17'),
(21, 'Advanced Python', 'Deep dive into Python programming and Data Science.', '12 Weeks', 10, '2025-12-18 18:57:17'),
(22, 'Graphic Design Masterclass', 'Learn Photoshop and Illustrator for beginners.', '4 Weeks', 11, '2025-12-18 18:57:17'),
(23, 'Business English', 'Improve your professional communication skills.', '6 Weeks', 11, '2025-12-18 18:57:17'),
(24, 'dm', 'maths ', '4 Weeks', 25, '2025-12-19 09:31:25'),
(25, 'bio', 'AL', '4 Weeks', 3, '2025-12-23 07:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

CREATE TABLE `course_materials` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`id`, `course_id`, `instructor_id`, `title`, `file_path`, `upload_date`) VALUES
(4, 2, 4, 'notes 1', 'assets/uploads/1766055932_2025-S2-IT2120-Lecture-06-ContinuousProbabilityDistributions.pdf', '2025-12-18 11:05:32'),
(5, 2, 4, 'note2', 'assets/uploads/1766056730_2025-S2-IT2120-Lecture-10-ChiSquaredTests.pdf', '2025-12-18 11:18:50'),
(6, 1, 3, 'notes1', 'assets/uploads/1766057608_2025-S2-IT2120-Lecture-08-Statistical_Inference.pdf', '2025-12-18 11:33:28'),
(7, 20, 10, 'HTML Introduction', 'assets/uploads/dummy1.pdf', '2025-12-18 18:57:17'),
(8, 20, 10, 'CSS Styling Guide', 'assets/uploads/dummy2.pdf', '2025-12-18 18:57:17'),
(9, 21, 10, 'Python Setup Guide', 'assets/uploads/dummy3.pdf', '2025-12-18 18:57:17'),
(10, 22, 11, 'Photoshop Tools', 'assets/uploads/dummy4.pdf', '2025-12-18 18:57:17'),
(11, 24, 25, 'notes2', 'assets/uploads/1766136705_2025-S2-IT2120-Lecture-09-ConfidenceIntervals.pdf', '2025-12-19 09:31:45'),
(12, 1, 3, 'gg', 'assets/uploads/1766472959_AIML_Lecture_3_MCQ.pdf', '2025-12-23 06:55:59'),
(13, 25, 3, 'notes3', 'assets/uploads/1766473596_AIML_Lecture_2_MCQ.pdf', '2025-12-23 07:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`) VALUES
(1, 2, 1, '2025-12-18 10:11:17'),
(2, 2, 2, '2025-12-18 11:05:43'),
(3, 12, 20, '2025-12-18 18:57:17'),
(4, 12, 22, '2025-12-18 18:57:17'),
(5, 13, 20, '2025-12-18 18:57:17'),
(6, 13, 21, '2025-12-18 18:57:17'),
(7, 14, 23, '2025-12-18 18:57:17'),
(8, 24, 20, '2025-12-19 09:28:08'),
(9, 24, 2, '2025-12-19 09:28:31'),
(10, 1, 22, '2025-12-22 16:13:39'),
(11, 1, 24, '2025-12-23 06:37:58'),
(12, 1, 20, '2025-12-23 06:43:22');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assignment_name` varchar(255) NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  `total_marks` decimal(5,2) NOT NULL,
  `grade_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `status`, `address`, `bio`, `profile_pic`) VALUES
(1, 'kamal kamal kamal', 'kamal234@gmail.com', '', '$2y$10$zD3qfvwovrbixcy7FK7p1eOpYzcLFVY9PRnGKV8WHkJrg2S5.LcBS', 'student', '2025-12-18 08:29:24', 'approved', '', '', 'assets/uploads/profiles/user_1_1766471013.jpg'),
(2, 'kamal sahan', 'kamal2@gmail.com', NULL, '$2y$10$xOA31amTLvGNP1VedWaJc.2OzwahC9QyQFpNU3YeToVgNzULw3Yiq', 'student', '2025-12-18 08:33:49', 'approved', NULL, NULL, NULL),
(3, 'sanjeewani kariyawsam', 'sanja1@gmail.com', '', '$2y$10$Kuv9k8OtyM9ahKY2mjK8DOsRCKN0s/0VwDQwAaPuH8D5v.dJBcumC', 'instructor', '2025-12-18 09:47:28', 'approved', '', '', 'assets/uploads/profiles/user_3_1766418866.jpg'),
(4, 'kamal sahan 2', 'kamal3@gmail.com', NULL, '$2y$10$hZ4tbW3c31Ku8diidsT4RukYods/SJFltwnSI2qLPwq7kc.X3oxQq', 'instructor', '2025-12-18 11:04:08', 'approved', NULL, NULL, NULL),
(10, 'Dr. Kasun Perera', 'kasun@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'instructor', '2025-12-18 18:57:17', 'approved', NULL, NULL, NULL),
(11, 'Ms. Ama Silva', 'ama@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'instructor', '2025-12-18 18:57:17', 'approved', NULL, NULL, NULL),
(12, 'Sunil Perera', 'sunil@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 18:57:17', 'approved', NULL, NULL, NULL),
(13, 'Nimali Fernando', 'nimali@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 18:57:17', 'approved', NULL, NULL, NULL),
(14, 'Ruwan Dissanayake', 'ruwan@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 18:57:17', 'approved', NULL, NULL, NULL),
(15, 'System Admin', 'admin@edulms.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'admin', '2025-12-18 19:04:17', 'approved', NULL, NULL, NULL),
(16, 'System Admin 2', 'admin@lms.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'admin', '2025-12-18 19:20:49', 'approved', NULL, NULL, NULL),
(17, 'asnika silva', 'admin2@gmail.com', '', '$2y$10$WKSbyq.lniFolNFEl4mLJebjSF8pdUAeM8KjemO/478268FqRV.Oi', 'admin', '2025-12-18 19:29:21', 'approved', '', '', 'assets/uploads/profiles/user_17_1766381295.jpg'),
(18, 'Saman Kumara (New)', 'saman_new@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 19:55:55', 'pending', NULL, NULL, NULL),
(19, 'Dr. Jayalath (New)', 'jayalath_new@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'instructor', '2025-12-18 19:55:55', 'pending', NULL, NULL, NULL),
(20, 'Nimali Perera (New)', 'nimali_new@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 18:55:55', 'pending', NULL, NULL, NULL),
(21, 'Shehan (New)', 'shehan_new@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-18 17:55:55', 'pending', NULL, NULL, NULL),
(22, 'Chart Student 1', 'chart1@gmail.com', NULL, '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'student', '2025-12-16 19:55:55', 'approved', NULL, NULL, NULL),
(24, 'kamal kaml ', 'kamal23@lms.com', NULL, '$2y$10$e8lIJSQbuwVJ7yI8tt9uLuRFCjZBjIVVq7iP52LkPyZzH2f0OTIKO', 'student', '2025-12-19 09:25:44', 'approved', NULL, NULL, NULL),
(25, 'sunil kamal', 'sunil23@gmail.com', NULL, '$2y$10$6T.WMQQmKgNIKGlUp3jdr.XdS/Bd45dVO2iVdGlIbIdVvzR00U8Zu', 'instructor', '2025-12-19 09:30:04', 'approved', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `course_materials`
--
ALTER TABLE `course_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD CONSTRAINT `course_materials_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_materials_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
