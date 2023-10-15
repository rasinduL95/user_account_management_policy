-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2023 at 06:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `week6`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_registration`
--

CREATE TABLE `user_registration` (
  `id` int(11) NOT NULL,
  `f_name` text NOT NULL,
  `l_name` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(512) NOT NULL,
  `account_status` text NOT NULL,
  `email_verification` text NOT NULL,
  `created_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_registration`
--

INSERT INTO `user_registration` (`id`, `f_name`, `l_name`, `email`, `username`, `password`, `account_status`, `email_verification`, `created_date`) VALUES
(6, 'Dakshina11', 'Dissanayake', 'dakshina321@gmail.com', '2020mis008', '$2y$10$kRzeDAMyD9H.tzfXVks7deKapdtteBn915O6XhVv/sHbvWZ1Nt6xO', 'requested', 'notverified', '2023-10-01'),
(7, 'Dakshina11', 'Dissanayake', 'dakshina321@gmail.com', 'q', '$2y$10$4GoQffyrQgzgDEPumeCZk.hBgyAWMkrFwF0HLKuU8LI..WRvORwXG', 'requested', 'notverified', '2023-10-15'),
(8, 'Dakshina11', 'Dissanayake', 'dakshina321@gmail.com', '2020mis008', '$2y$10$iywYzgst1lryRjR2SLn/O.jpCYR5lV9N1fEVlFE7jkeJfxdI5HAOe', 'requested', 'notverified', '2023-10-15'),
(9, '122', '333', 'dakshina321@gmail.com', '2020mis008', '$2y$10$lLJ4.E2EWEgGdVrC2Yze8OtDTc/gc2qNKKtP.E6RIzHmmGlOFSJCC', 'requested', 'notverified', '2023-10-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
