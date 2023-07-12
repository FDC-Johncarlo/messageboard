-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2023 at 11:46 AM
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
(21, 8, 11, '[{\"ref\":\"CHAT-hct6opy1tmb1kf56l6b8\",\"from\":8,\"message\":\"TEST\",\"date_push\":\"2023-07-12 16:31:27\"},{\"ref\":\"CHAT-xiymhpjcthbi8n3riy8m\",\"from\":8,\"message\":\"chat 2\",\"date_push\":\"2023-07-12 16:41:48\"},{\"ref\":\"CHAT-wcjqhyovzch6pnmqkdem\",\"from\":11,\"message\":\"Hoy hahaha\",\"date_push\":\"2023-07-12 16:42:53\"}]', '2023-07-12 16:42:53'),
(23, 8, 10, '[{\"ref\":\"CHAT-os1lkincxujk7apa8fkt\",\"from\":8,\"message\":\"new chat with someone\",\"date_push\":\"2023-07-12 16:42:16\"}]', '2023-07-12 16:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(5, 'Test', 'test1@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(6, 'Test', 'test2@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(7, 'Test', 'test3@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(8, 'FDCI-JOHN', 'fdc.johncarloy@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(9, 'johncarlo', 'prospteam@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(10, 'test2', 'test@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(11, 'johncarlo.ylanan', 'joh@gmail.com', '7965f326cdd2c5baac185fdcb963ca758fdd311c');

-- --------------------------------------------------------

--
-- Table structure for table `users_data`
--

CREATE TABLE `users_data` (
  `id` int(11) NOT NULL,
  `fk_id` int(11) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `hubby` longtext DEFAULT NULL,
  `profile` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_data`
--

INSERT INTO `users_data` (`id`, `fk_id`, `birth_date`, `gender`, `hubby`, `profile`) VALUES
(1, 8, '2014-07-14', 'Female', 'Test Hubby', 'Profile-1689127001.gif');

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
(8, 7, '2023-07-10 14:33:35'),
(9, 8, '2023-07-12 17:27:44'),
(10, 11, '2023-07-12 17:27:15'),
(11, 6, '2023-07-12 17:11:28');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users_data`
--
ALTER TABLE `users_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_login_logs`
--
ALTER TABLE `users_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
