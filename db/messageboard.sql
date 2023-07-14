-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2023 at 11:41 AM
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
-- Database: `messageboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `pair_one` int(11) NOT NULL COMMENT 'users id',
  `pair_two` int(11) NOT NULL COMMENT 'users id',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`message`)),
  `last_update` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `pair_one`, `pair_two`, `message`, `last_update`) VALUES
(75, 13, 14, '[{\"ref\":\"CHAT-6148qxncxd0ihzxiyd2n\",\"from\":13,\"message\":\"Hidsadasdsa\",\"date_push\":\"2023-07-14 15:35:57\"},{\"ref\":\"CHAT-tesk6k6ygylihnq3djyp\",\"from\":14,\"message\":\"dasdasddasdasdsadasdas\",\"date_push\":\"2023-07-14 15:36:00\"}]', '2023-07-14 15:36:00'),
(76, 13, 15, '[{\"ref\":\"CHAT-rif1jyoczkl2qrmkew3p\",\"from\":13,\"message\":\"hahaha\",\"date_push\":\"2023-07-14 15:58:47\"}]', '2023-07-14 15:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `date_register` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `date_register`) VALUES
(13, 'John Carlo A. Ylanan', 'johncarlo.ylanan@gmail.com', '6f525405380da0d3bda319203a111ba5355ebad3', '2023-07-14'),
(14, 'Christian Melendres', 'christianupdate@gmail.com', '4f6236fc02a5f738da5b8cd6b9c6210372afb5f6', '2023-07-14'),
(15, 'Test User', 'test@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4', '2023-07-14');

-- --------------------------------------------------------

--
-- Table structure for table `users_data`
--

CREATE TABLE `users_data` (
  `id` int(11) NOT NULL,
  `fk_id` int(11) NOT NULL,
  `birth_date` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `hubby` longtext DEFAULT NULL,
  `profile` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_data`
--

INSERT INTO `users_data` (`id`, `fk_id`, `birth_date`, `gender`, `hubby`, `profile`) VALUES
(3, 13, '07/14/1999', 'Male', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Profile-1689314859.gif'),
(4, 14, '07/14/1999', 'Female', 'Programming | UI | UX', 'Profile-1689314888.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users_login_logs`
--

CREATE TABLE `users_login_logs` (
  `id` int(11) NOT NULL,
  `fk_id` int(11) NOT NULL,
  `last_login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_login_logs`
--

INSERT INTO `users_login_logs` (`id`, `fk_id`, `last_login_time`) VALUES
(13, 14, '2023-07-14 17:10:02'),
(14, 13, '2023-07-14 17:15:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_data`
--
ALTER TABLE `users_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login_logs`
--
ALTER TABLE `users_login_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users_data`
--
ALTER TABLE `users_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_login_logs`
--
ALTER TABLE `users_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
