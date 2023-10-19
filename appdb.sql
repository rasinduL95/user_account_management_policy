-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2023 at 06:43 PM
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
-- Database: `appdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `log_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `log_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_policy`
--

CREATE TABLE `password_policy` (
  `id` int(11) NOT NULL,
  `min_length` int(11) DEFAULT NULL,
  `max_length` int(11) DEFAULT NULL,
  `require_upper_case` bit(1) DEFAULT NULL,
  `require_lower_case` bit(1) DEFAULT NULL,
  `require_digit` bit(1) DEFAULT NULL,
  `require_special_char` bit(1) DEFAULT NULL,
  `allowed_special_chars` varchar(50) DEFAULT NULL,
  `history_count` int(11) DEFAULT NULL,
  `expiration_days` int(11) DEFAULT NULL,
  `lockout_attempts` int(11) DEFAULT NULL,
  `lockout_duration_minutes` int(11) DEFAULT NULL,
  `is_active` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_policy`
--

INSERT INTO `password_policy` (`id`, `min_length`, `max_length`, `require_upper_case`, `require_lower_case`, `require_digit`, `require_special_char`, `allowed_special_chars`, `history_count`, `expiration_days`, `lockout_attempts`, `lockout_duration_minutes`, `is_active`) VALUES
(1, 6, 15, b'1', b'1', b'1', b'1', '1', NULL, 60, 3, 5, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Administrator', 'Full access to all system functions', '2023-10-16 15:41:48'),
(2, 'User', 'Standard employee role', '2023-10-16 15:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE `security_questions` (
  `id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_questions`
--

INSERT INTO `security_questions` (`id`, `question_text`, `is_active`) VALUES
(1, 'What is your mother\'s maiden name?', 1),
(2, 'In which city were you born?', 1),
(3, 'What is your favorite pet\'s name?', 1),
(4, 'What was the name of your first school?', 1),
(5, 'What is your favorite book?', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `f_name` text NOT NULL,
  `l_name` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(512) NOT NULL,
  `account_status` text NOT NULL,
  `email_verification` text NOT NULL,
  `created_date` date NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT NULL,
  `failed_login_attempts` int(1) NOT NULL,
  `is_locked` bit(1) NOT NULL,
  `password_changed_date` date DEFAULT NULL,
  `lockout_timestamp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `email`, `username`, `password`, `account_status`, `email_verification`, `created_date`, `role_id`, `failed_login_attempts`, `is_locked`, `password_changed_date`, `lockout_timestamp`) VALUES
(0, '', '', '', 'new_admin', '$2y$10$R7OG4o2aqCXxB.tIqsQ/k.EoFHOGypHVO4CuC1D1NwCvTSzvm2ZZO', '', '', '2023-10-19', 1, 0, b'0', '2023-02-19', NULL),
(1, 'Dakshina11', 'Dissanayake', 'dakshina321@gmail.com', 'admin123', '$2y$10$h/bniMktniKl0rK4B9iRP.7Js8CRPXTvO2aifFcweMH78NSDP15HK', 'requested', 'notverified', '2023-10-16', 1, 3, b'0', '2023-10-19', NULL),
(2, 'Jane', 'Smith', 'jane.smith@example.com', 'janesmith', '$3y$10$kaseDAMyDas.tzfXVks7deKapdtteBn915O6XhVv/sHbvWZ1Nt6xO', 'active', 'verified', '2023-10-16', 2, 0, b'0', '2023-10-19', NULL),
(3, 'Bob', 'Johnson', 'bob.johnson@example.com', 'bobjohnson', '$4y$10$123zeDAM3erH.tzfXVks7deKapdtteBn915O6XhVv/sHbvWZ1Nt6xO', 'inactive', 'notverified', '2023-10-16', 2, 0, b'0', '2023-10-19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_security_question_answers`
--

CREATE TABLE `user_security_question_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `security_question_id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_security_question_answers`
--

INSERT INTO `user_security_question_answers` (`id`, `user_id`, `security_question_id`, `answer_text`) VALUES
(1, 0, 1, 'Smith'),
(2, 0, 3, 'Buddy'),
(3, 2, 1, 'Johnson'),
(4, 2, 2, 'New York'),
(5, 3, 2, 'Los Angeles');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `password_policy`
--
ALTER TABLE `password_policy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_security_question_answers`
--
ALTER TABLE `user_security_question_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `security_question_id` (`security_question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_security_question_answers`
--
ALTER TABLE `user_security_question_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_security_question_answers`
--
ALTER TABLE `user_security_question_answers`
  ADD CONSTRAINT `user_security_question_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_security_question_answers_ibfk_2` FOREIGN KEY (`security_question_id`) REFERENCES `security_questions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
