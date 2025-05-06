-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 01:06 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studypool_clone`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `answer_text` text NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `tutor_id`, `answer_text`, `attachment_url`, `created_at`) VALUES
(1, 5, 28, 'Html and Css', '', '2025-04-25 19:17:11'),
(2, 3, 35, 'ROM stands for Read-Only Memory, a type of non-volatile memory used in electronic devices to store permanent data and instructions.', '', '2025-05-06 10:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `book_guides`
--

CREATE TABLE `book_guides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `tutor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_guides`
--

INSERT INTO `book_guides` (`id`, `title`, `description`, `price`, `attachment_url`, `tutor_id`) VALUES
(6, 'Computer Networks', 'Computer Networks: A Systems Approach, 3e. Larry L. Peterson and Bruce S. Davie. Network Architecture, Analysis, and Design, 2e. James D. McCabe.\r\n838 pages', '60.00', 'uploads/guides/Computer Networks.pdf', 25),
(7, 'Handout 2', 'Checking', '2.00', 'uploads/guides/Graph Theory in a Glance (Handout-2).pdf', 28),
(8, 'Software Engineering', 'Checking', '30.00', 'uploads/guides/Software-Engineering-9th-Edition-by-Ian-Sommerville.pdf', 32);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `subject` varchar(100) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `content`, `user_id`, `created_at`, `subject`, `fee`, `attachment_url`) VALUES
(9, 'Software Engineering', 'All about software', 35, '2025-05-06 15:39:29', 'Software', '20.00', 'uploads/notes/Software-Engineering-9th-Edition-by-Ian-Sommerville.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `question_text` text NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `status` enum('open','answered','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `school` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `category`, `question_text`, `attachment_url`, `status`, `created_at`, `school`, `course`) VALUES
(3, 20, 'Computer', 'what is ROM?', NULL, 'open', '2024-12-21 13:01:36', NULL, NULL),
(4, 20, 'Business', 'What are the documents that should be submitted to the registrar of companies for the registration of a limited liability company?', NULL, 'open', '2024-12-21 16:43:14', 'Summit Grove School.', 'Business and Analysis'),
(5, 27, 'Computer Science', 'Name frontend langusge?', NULL, 'answered', '2025-04-25 18:46:15', 'UET', 'CS'),
(6, 27, 'Writing', 'what is poem', NULL, 'open', '2025-04-26 14:13:34', 'UET', 'English'),
(8, 34, 'Science', 'What is atom?', NULL, 'open', '2025-05-06 10:34:58', 'JPS', 'Science');

-- --------------------------------------------------------

--
-- Table structure for table `ratings_reviews`
--

CREATE TABLE `ratings_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings_reviews`
--

INSERT INTO `ratings_reviews` (`id`, `user_id`, `tutor_id`, `rating`, `review`, `created_at`) VALUES
(3, 34, 23, 4, 'Great Teacher', '2025-05-06 10:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_book_guide_payment`
--

CREATE TABLE `tbl_book_guide_payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_guide_id` int(11) NOT NULL,
  `payment_status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_book_guide_payment`
--

INSERT INTO `tbl_book_guide_payment` (`id`, `user_id`, `book_guide_id`, `payment_status`, `payment_date`) VALUES
(1, 27, 7, 'completed', '2025-04-29 14:27:04'),
(2, 30, 7, 'completed', '2025-04-29 16:23:49'),
(3, 31, 7, 'completed', '2025-04-29 21:18:47'),
(4, 34, 6, 'completed', '2025-05-06 15:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `verification_status` enum('approved','pending','rejected') DEFAULT 'pending',
  `photo_path` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `subject_specialization` varchar(255) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `email`, `password`, `first_name`, `last_name`, `verification_status`, `photo_path`, `bio`, `phone_number`, `location`, `subject_specialization`, `years_of_experience`, `education_level`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'abc@gmail.com', '$2y$10$chY8.ISU8a1WkGvRNvtqtuPUr85IOlXD6hLmIAXCTkv/JEBRHsWCW', 'abc', 'def', 'approved', 'uploads/id.jpg', 'Experienced tutor in Mathematics', '123-456-7890', 'New York, NY', 'Mathematics', 5, 'Bachelor\'s', '2024-12-24 13:09:16', '2024-12-24 13:09:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','tutor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`, `created_at`, `verification_status`) VALUES
(20, 'noor@gmail.com', 'noor', '$2y$10$e7JrGPW0V49eKIFTeSbKYO9xmWnl93hfnjVA.IriETZdyW6MSRgbK', 'student', '2024-12-21 12:22:52', 'pending'),
(22, 'ashna@gmail.com', 'ashna', '$2y$10$2eJQsy9gUdA46O4YymYeQeGSCvXs1oBU/V9k17KgH50JQ2ZMH4h2i', 'tutor', '2024-12-21 12:43:04', 'pending'),
(23, 'Shahneelaiqbal1@gmail.com', 'fazeela', '$2y$10$4e46.cRNeNAtJTHyTN4QPecuUe.NHOOHsQv4TihuRbblIQEn1FLnK', 'tutor', '2024-12-22 09:40:37', 'pending'),
(25, 'abc@gmail.com', 'abc', '$2y$10$chY8.ISU8a1WkGvRNvtqtuPUr85IOlXD6hLmIAXCTkv/JEBRHsWCW', 'tutor', '2024-12-24 09:11:48', 'approved'),
(27, 'shahneela230@gmail.com', 'sheela', '$2y$10$1x9uVZZyXlcLAYJD0ecZGOkzKr1TUmi3Bdb9Qu5aPWatJLM4hlCmm', 'student', '2025-04-25 18:39:49', 'pending'),
(29, 'sana@gmail.com', 'Sana', '$2y$10$7pDxKy7mUDG5drMUc0avJeGRtEyGRB5m97QyNfpSBnLcn4ecdY1Wa', 'student', '2025-04-29 10:46:14', 'pending'),
(30, 'mano@gmail.com', 'mano', '$2y$10$.kkVIhH/fFgkhEc7Zsx1ZOxOS1iNtZUg19p2CcjNMzeOrlzNgeSt6', 'student', '2025-04-29 10:59:11', 'pending'),
(31, 'sunny@gmail.com', 'sunny', '$2y$10$fywe4aMKxsS0kkS.JUkQLOrSo60.AQwkOYS5mKGpZMokJebRhiPC2', 'student', '2025-04-29 16:03:40', 'pending'),
(32, 'aneela123@gmail.com', 'aneela', '$2y$10$B44OlbWuWBibXLDvn79Yx.MJRR5FZZMznw8B2/UDK3PwoxSru.WmS', 'tutor', '2025-04-29 16:08:09', 'pending'),
(34, 'shahneelaiqbal@outlook.com', 'shahneela', '$2y$10$b05fIS.jYwfTs76mnAlG8u4.PiBLmUbRwtLuo/TXDrxbL.fGXfoPS', 'student', '2025-05-06 10:33:58', 'pending'),
(35, 'shahneela2@gmail.com', 'Anam', '$2y$10$opfTyjsajcroGvGyS/1nceH5iiSeXixFw8e2EK.gr7Ybvlw28o4Ma', 'tutor', '2025-05-06 10:37:52', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notebank_id` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `notebank_id`, `school`, `course`) VALUES
(1, 20, '1', 'UET', 'DB');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `book_guides`
--
ALTER TABLE `book_guides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `tbl_book_guide_payment`
--
ALTER TABLE `tbl_book_guide_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_guide_id` (`book_guide_id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `book_guides`
--
ALTER TABLE `book_guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_book_guide_payment`
--
ALTER TABLE `tbl_book_guide_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD CONSTRAINT `ratings_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_reviews_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tbl_book_guide_payment`
--
ALTER TABLE `tbl_book_guide_payment`
  ADD CONSTRAINT `tbl_book_guide_payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tbl_book_guide_payment_ibfk_2` FOREIGN KEY (`book_guide_id`) REFERENCES `book_guides` (`id`);

--
-- Constraints for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD CONSTRAINT `tbl_payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_payment_ibfk_2` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
