-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2023 at 11:58 AM
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
(65, 8, 7, '[{\"ref\":\"CHAT-0jw2utwpigwsmm2csvop\",\"from\":8,\"message\":\"3\",\"date_push\":\"2023-07-13 15:09:26\"}]', '2023-07-13 15:09:26'),
(68, 8, 11, '[{\"ref\":\"CHAT-ugyyxchhtmzarclfwduy\",\"from\":8,\"message\":\"6\",\"date_push\":\"2023-07-13 15:09:39\"},{\"ref\":\"CHAT-trpynsd7l8iyzycnyq2z\",\"from\":8,\"message\":\"32\",\"date_push\":\"2023-07-13 15:12:19\"},{\"ref\":\"CHAT-actmy6aimseop2ly9aoy\",\"from\":11,\"message\":\"wakay klaro ou\",\"date_push\":\"2023-07-13 16:44:39\"},{\"ref\":\"CHAT-pityoscvlcpdx5vqp6kn\",\"from\":8,\"message\":\"ikaw way klaro\",\"date_push\":\"2023-07-13 16:58:36\"},{\"ref\":\"CHAT-3lqy1mgbz4xoplhjooc0\",\"from\":11,\"message\":\"Ikaw ranang wala ni butho sa atong sabot oy\",\"date_push\":\"2023-07-13 16:59:00\"},{\"ref\":\"CHAT-oig1pit7vc1wgwbe4c9a\",\"from\":8,\"message\":null,\"date_push\":\"2023-07-13 17:46:21\"},{\"ref\":\"CHAT-uevzxwvn4ongt2dnbg63\",\"from\":8,\"message\":\"test\",\"date_push\":\"2023-07-13 17:46:58\"},{\"ref\":\"CHAT-zjihq6q0zmzbqobnttvf\",\"from\":8,\"message\":\"yaya\",\"date_push\":\"2023-07-13 17:47:03\"}]', '2023-07-13 17:47:03'),
(70, 8, 5, '[{\"ref\":\"CHAT-y3yeigmmrxipvk59hgzw\",\"from\":8,\"message\":\"1\",\"date_push\":\"2023-07-13 15:10:19\"}]', '2023-07-13 15:10:19'),
(71, 8, 6, '[{\"ref\":\"CHAT-ur4s6atjnwachcnx9yaf\",\"from\":8,\"message\":\"1\",\"date_push\":\"2023-07-13 15:10:39\"},{\"ref\":\"CHAT-lxt2ocqqdgyltcozlqsg\",\"from\":8,\"message\":\"312\",\"date_push\":\"2023-07-13 15:12:23\"}]', '2023-07-13 15:12:23'),
(72, 8, 10, '[{\"ref\":\"CHAT-ojf8abqrxts4ps4yffvo\",\"from\":8,\"message\":\"321\",\"date_push\":\"2023-07-13 15:12:27\"}]', '2023-07-13 15:12:27'),
(73, 8, 12, '[{\"ref\":\"CHAT-dyjlrvskir9kmhjj7sdc\",\"from\":8,\"message\":\"dsadas\",\"date_push\":\"2023-07-13 15:45:48\"},{\"ref\":\"CHAT-kwmeq12jp1d90fbzfawh\",\"from\":8,\"message\":\"okay rakas record nga 6 - 7?\",\"date_push\":\"2023-07-13 17:48:10\"},{\"ref\":\"CHAT-zudhr8fbrth90v60k5n6\",\"from\":8,\"message\":\"ahaha\",\"date_push\":\"2023-07-13 17:54:15\"}]', '2023-07-13 17:54:15');

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
(5, 'Ka chat', 'test1@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(6, 'Test', 'test2@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(7, 'Test', 'test3@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(8, 'FDCI-JOHN', 'fdc.johncarloy@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(9, 'johncarlo', 'prospteam@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(10, 'test2', 'test@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4'),
(11, 'johncarlo.ylanan', 'joh@gmail.com', '7965f326cdd2c5baac185fdcb963ca758fdd311c'),
(12, 'Christian Melendres', 'christian@gmail.com', '57d047767950b5ee6de9c9a1d77d35d8aff840b4');

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
(1, 8, '2014-07-14', 'Female', 'Test Hubby', 'Profile-1689127001.gif'),
(2, 5, '0000-00-00', 'Female', 'Wala lonely girl rako', 'Profile-1689218060.jpg');

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
(9, 8, '2023-07-13 17:24:17'),
(10, 11, '2023-07-13 13:38:31'),
(11, 6, '2023-07-12 17:11:28'),
(12, 5, '2023-07-13 11:13:44');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users_data`
--
ALTER TABLE `users_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_login_logs`
--
ALTER TABLE `users_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
