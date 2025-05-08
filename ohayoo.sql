-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 08:32 AM
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
-- Database: `ohayoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `room_token` text NOT NULL,
  `user1_id` bigint(20) UNSIGNED NOT NULL,
  `user2_id` bigint(20) UNSIGNED NOT NULL,
  `statusU1` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=active 1=deleted 2=block',
  `lastStatusU1` timestamp NULL DEFAULT NULL,
  `statusU2` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=active 1=delete 2=block',
  `lastStatusU2` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_room`
--

INSERT INTO `chat_room` (`room_id`, `room_token`, `user1_id`, `user2_id`, `statusU1`, `lastStatusU1`, `statusU2`, `lastStatusU2`) VALUES
(4, 'b3c00d967818378acc6669f246bc2b9753d49c125c1fc8f8ebdfc1a26fc84aa4', 1382513644, 1093641831, 0, NULL, 0, NULL),
(5, '56fa007584654adcb98dcb9a5656115429c48d8afb4fb742944aa09ef42fbfa9', 1382513644, 1382513644, 0, NULL, 0, NULL),
(9, '2ad0d24f89ebfe3e45f6a16ff19642abce1db3566f12f0226ae6b7ecd0a58fd2', 1245533531, 1382513644, 0, NULL, 0, '2024-05-01 15:19:35'),
(10, 'c56c90530dba87f7e02299b998ca099fd79432cb2d05f7769d4032a621fb14a0', 1382513644, 1084115894, 0, NULL, 0, NULL),
(11, '97cc8e012494ec6c02553218352069980587f09bdf36cf7bac5e93b8bedec9d1', 1382513644, 1668992603, 0, NULL, 0, NULL),
(12, '467d0c35baf3c70bdc8ea52239f9b5d083a6d1b6cc952012c7a90f81aefb9ace', 1585660075, 1382513644, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_owner` bigint(20) NOT NULL,
  `replayID` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_text`, `comment_date`, `comment_owner`, `replayID`) VALUES
(12, 97, 'hello world', '2024-04-13 18:18:52', 1245533531, NULL),
(24, 100, 'hello', '2024-04-14 01:44:23', 1382513644, NULL),
(29, 98, 'dzdz', '2024-04-14 13:52:38', 1382513644, NULL),
(32, 98, 'hi', '2024-04-14 13:59:58', 1382513644, NULL),
(36, 98, 'hello', '2024-04-14 20:21:03', 1382513644, NULL),
(37, 100, 'hello world', '2024-04-14 20:31:27', 1382513644, NULL),
(38, 101, 'hello', '2024-04-14 20:31:50', 1382513644, NULL),
(39, 100, 'he', '2024-04-14 20:32:08', 1382513644, NULL),
(40, 96, 'helo', '2024-04-14 20:32:48', 1382513644, NULL),
(42, 101, 'hello world', '2024-04-14 20:56:42', 1382513644, NULL),
(43, 101, 'hello badie', '2024-04-14 22:50:22', 1245533531, NULL),
(44, 101, 'hello world', '2024-04-14 22:50:37', 1382513644, NULL),
(47, 101, 'hello', '2024-04-14 23:48:21', 1382513644, NULL),
(48, 92, 'hello', '2024-04-14 23:51:57', 1382513644, NULL),
(49, 76, 'hello', '2024-04-14 23:52:53', 1382513644, NULL),
(58, 102, 'hello', '2024-04-15 00:33:08', 1382513644, NULL),
(59, 103, 'HHHHH', '2024-04-15 16:33:03', 1382513644, NULL),
(60, 104, 'hello', '2024-04-15 20:37:14', 1382513644, NULL),
(61, 104, 'world', '2024-04-15 20:37:16', 1382513644, 60),
(62, 104, 'hi', '2024-04-15 20:37:18', 1382513644, NULL),
(63, 104, 'my', '2024-04-15 20:37:19', 1382513644, 62),
(65, 94, 'hello', '2024-04-16 10:50:50', 1382513644, NULL),
(66, 105, 'timensiwin', '2024-04-16 11:11:38', 1382513644, NULL),
(68, 106, 'hello world', '2024-04-18 19:22:02', 1382513644, NULL),
(70, 104, 'hello', '2024-04-19 19:19:16', 1382513644, NULL),
(71, 104, 'hi', '2024-04-19 19:19:18', 1382513644, NULL),
(72, 105, 'hello', '2024-04-25 14:54:13', 1382513644, NULL),
(73, 105, 'hello', '2024-04-25 14:54:37', 1382513644, NULL),
(74, 108, 'hellozd', '2024-04-27 15:54:30', 1382513644, NULL),
(77, 108, 'he', '2024-04-28 16:30:53', 1382513644, NULL),
(78, 109, 'hello', '2024-04-28 18:11:24', 1382513644, NULL),
(79, 108, 'hello', '2024-04-30 23:08:52', 1382513644, NULL),
(80, 112, 'rf', '2024-05-01 16:23:01', 1382513644, NULL),
(81, 112, 'rf', '2024-05-01 16:23:04', 1382513644, NULL),
(82, 108, 'dd', '2024-05-01 16:23:38', 1382513644, NULL),
(83, 109, 'hekk', '2024-05-01 16:24:20', 1382513644, NULL),
(84, 109, 'hekk', '2024-05-01 16:28:10', 1382513644, NULL),
(85, 109, 'hekk', '2024-05-01 16:28:36', 1382513644, NULL),
(86, 109, 'hekk', '2024-05-01 16:30:34', 1382513644, NULL),
(87, 109, 'hekk', '2024-05-01 16:31:03', 1382513644, NULL),
(88, 108, 'hi', '2024-05-01 16:31:16', 1382513644, NULL),
(89, 116, 'hello', '2024-05-02 10:58:02', 1382513644, NULL),
(90, 117, 'edef', '2024-05-02 19:50:26', 1382513644, NULL),
(91, 118, 'hello', '2024-05-05 11:24:14', 1382513644, NULL),
(92, 144, 'hello', '2024-05-07 19:04:15', 1382513644, NULL),
(93, 145, 'hello', '2024-05-07 21:31:22', 1382513644, NULL),
(94, 148, 'hello', '2024-05-08 12:30:46', 1382513644, NULL),
(95, 148, 'hello', '2024-05-08 12:49:11', 1382513644, NULL),
(96, 144, 'ederf', '2024-05-08 13:20:20', 1382513644, NULL),
(97, 144, 'rfe', '2024-05-08 13:20:21', 1382513644, NULL),
(98, 144, 'ergf', '2024-05-08 13:20:22', 1382513644, NULL),
(99, 144, 'rfg', '2024-05-08 13:20:23', 1382513644, NULL),
(100, 144, 'rfgr', '2024-05-08 13:20:23', 1382513644, NULL),
(101, 144, 'dfr', '2024-05-08 13:21:46', 1382513644, NULL),
(102, 130, 'hello', '2024-05-08 14:54:13', 1382513644, NULL),
(103, 151, 'hello', '2024-05-08 16:37:51', 1382513644, NULL),
(105, 88, 'helo', '2024-05-17 15:03:07', 1245533531, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `like_comment`
--

CREATE TABLE `like_comment` (
  `like_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `like_post`
--

CREATE TABLE `like_post` (
  `like_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `like_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `like_post`
--

INSERT INTO `like_post` (`like_id`, `user_id`, `like_date`, `post_id`) VALUES
(98, 1382513644, '2024-04-11 23:45:21', 76),
(100, 1382513644, '2024-04-12 01:56:29', 77),
(101, 1382513644, '2024-04-12 01:56:31', 78),
(113, 1382513644, '2024-04-12 02:00:04', 95),
(116, 1382513644, '2024-04-12 02:38:33', 89),
(170, 1382513644, '2024-04-12 20:41:50', 88),
(177, 1084115894, '2024-04-13 13:21:00', 98),
(178, 1084115894, '2024-04-13 13:21:08', 97),
(224, 1382513644, '2024-04-14 20:33:09', 96),
(247, 1382513644, '2024-04-14 22:55:19', 98),
(248, 1245533531, '2024-04-14 22:55:21', 98),
(255, 1245533531, '2024-04-14 23:48:54', 100),
(256, 1382513644, '2024-04-14 23:48:57', 100),
(263, 1382513644, '2024-04-14 23:49:16', 97),
(274, 1245533531, '2024-04-14 23:49:27', 97),
(275, 1382513644, '2024-04-15 00:00:36', 101),
(276, 1245533531, '2024-04-15 00:01:01', 101),
(277, 1382513644, '2024-04-15 00:01:35', 102),
(278, 1245533531, '2024-04-15 00:01:40', 102),
(283, 1382513644, '2024-04-16 10:50:56', 94),
(284, 1382513644, '2024-04-16 11:11:09', 104),
(291, 1382513644, '2024-04-18 20:24:41', 105),
(296, 1382513644, '2024-04-18 21:15:15', 106),
(299, 1382513644, '0000-00-00 00:00:00', 108),
(360, 1382513644, '0000-00-00 00:00:00', 115),
(362, 1585660075, '0000-00-00 00:00:00', 136),
(369, 1382513644, '0000-00-00 00:00:00', 148),
(370, 1382513644, '0000-00-00 00:00:00', 130),
(371, 1382513644, '0000-00-00 00:00:00', 151),
(372, 1245533531, '0000-00-00 00:00:00', 152);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` bigint(20) UNSIGNED NOT NULL,
  `msg_content` text NOT NULL,
  `msg_from` bigint(20) UNSIGNED NOT NULL,
  `msg_to` bigint(20) UNSIGNED NOT NULL,
  `msg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `msg_status` tinyint(4) NOT NULL DEFAULT -1,
  `msg_type` int(11) NOT NULL DEFAULT 0,
  `reply` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `msg_content`, `msg_from`, `msg_to`, `msg_date`, `msg_status`, `msg_type`, `reply`) VALUES
(1, 'hello', 1382513644, 1245533531, '2024-04-30 23:08:27', 1, 0, NULL),
(2, 'hi', 1382513644, 1245533531, '2024-04-30 23:08:31', 1, 0, NULL),
(3, 'how are you', 1382513644, 1245533531, '2024-04-30 23:08:38', 1, 0, NULL),
(4, 'hello', 1382513644, 1093641831, '2024-05-01 09:54:41', -1, 0, NULL),
(5, 'zd', 1382513644, 1093641831, '2024-05-01 09:54:42', -1, 0, NULL),
(6, 'ef', 1382513644, 1093641831, '2024-05-01 09:54:43', -1, 0, NULL),
(9, 'f', 1382513644, 1093641831, '2024-05-01 09:57:03', -1, 0, NULL),
(10, 'hello', 1382513644, 1084115894, '2024-05-01 10:01:34', -1, 0, NULL),
(11, 'how are you', 1382513644, 1084115894, '2024-05-01 10:01:39', -1, 0, NULL),
(12, 'eg', 1382513644, 1245533531, '2024-05-01 10:11:24', 1, 0, NULL),
(13, 'rgr', 1382513644, 1245533531, '2024-05-01 10:11:25', 1, 0, NULL),
(14, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:25', 1, 0, NULL),
(15, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:26', 1, 0, NULL),
(16, 'r', 1382513644, 1245533531, '2024-05-01 10:11:26', 1, 0, NULL),
(17, 'gr', 1382513644, 1245533531, '2024-05-01 10:11:26', 1, 0, NULL),
(18, 'g', 1382513644, 1245533531, '2024-05-01 10:11:26', 1, 0, NULL),
(19, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:27', 1, 0, NULL),
(20, 'g', 1382513644, 1245533531, '2024-05-01 10:11:27', 1, 0, NULL),
(21, 'rr', 1382513644, 1245533531, '2024-05-01 10:11:28', 1, 0, NULL),
(22, 'grg', 1382513644, 1245533531, '2024-05-01 10:11:28', 1, 0, NULL),
(23, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:29', 1, 0, NULL),
(24, 'r', 1382513644, 1245533531, '2024-05-01 10:11:29', 1, 0, NULL),
(25, 'g', 1382513644, 1245533531, '2024-05-01 10:11:29', 1, 0, NULL),
(26, 'r', 1382513644, 1245533531, '2024-05-01 10:11:29', 1, 0, NULL),
(27, 'gr', 1382513644, 1245533531, '2024-05-01 10:11:29', 1, 0, NULL),
(28, 'g', 1382513644, 1245533531, '2024-05-01 10:11:30', 1, 0, NULL),
(29, 'rgr', 1382513644, 1245533531, '2024-05-01 10:11:43', 1, 0, NULL),
(30, 'rgr', 1382513644, 1245533531, '2024-05-01 10:11:44', 1, 0, NULL),
(31, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:45', 1, 0, NULL),
(32, 'rgr', 1382513644, 1245533531, '2024-05-01 10:11:45', 1, 0, NULL),
(33, 'g', 1382513644, 1245533531, '2024-05-01 10:11:45', 1, 0, NULL),
(34, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:46', 1, 0, NULL),
(35, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:46', 1, 0, NULL),
(36, 'rg', 1382513644, 1245533531, '2024-05-01 10:11:46', 1, 0, NULL),
(37, 'r', 1382513644, 1245533531, '2024-05-01 10:11:47', 1, 0, NULL),
(38, 'th', 1382513644, 1245533531, '2024-05-01 10:11:47', 1, 0, NULL),
(39, 't', 1382513644, 1245533531, '2024-05-01 10:11:47', 1, 0, NULL),
(40, 'h', 1382513644, 1245533531, '2024-05-01 10:11:48', 1, 0, NULL),
(41, 'th', 1382513644, 1245533531, '2024-05-01 10:11:48', 1, 0, NULL),
(42, 't', 1382513644, 1245533531, '2024-05-01 10:11:48', 1, 0, NULL),
(43, 'h', 1382513644, 1245533531, '2024-05-01 10:11:48', 1, 0, NULL),
(44, 't', 1382513644, 1245533531, '2024-05-01 10:11:48', 1, 0, NULL),
(45, 'h', 1382513644, 1245533531, '2024-05-01 10:11:49', 1, 0, NULL),
(46, 'th', 1382513644, 1245533531, '2024-05-01 10:11:49', 1, 0, NULL),
(48, 'helo', 1382513644, 1245533531, '2024-05-01 11:08:25', 1, 0, NULL),
(49, 'zdz', 1382513644, 1245533531, '2024-05-01 11:50:47', 1, 0, NULL),
(50, 'hello', 1382513644, 1245533531, '2024-05-01 11:58:22', 1, 0, NULL),
(51, 'hello', 1382513644, 1084115894, '2024-05-01 14:42:20', -1, 0, NULL),
(52, 'hello', 1382513644, 1093641831, '2024-05-01 15:03:23', -1, 0, NULL),
(53, 'hi', 1382513644, 1093641831, '2024-05-01 15:03:26', -1, 0, NULL),
(54, 'hello', 1382513644, 1245533531, '2024-05-01 15:13:25', 1, 0, NULL),
(55, '/upload/chat/1382513644user0a036b6e83a60c52ebd532bd7c9d1e4fade026b5ab77de0267e3d7654538b923.ogg', 1382513644, 1245533531, '2024-05-01 15:13:49', 1, 2, NULL),
(56, 'hello', 1245533531, 1382513644, '2024-05-01 15:15:14', 1, 0, 48),
(57, 'hello', 1382513644, 1084115894, '2024-05-01 15:16:50', -1, 0, NULL),
(58, 'hello', 1382513644, 1245533531, '2024-05-01 15:20:47', 1, 0, NULL),
(59, '/upload/chat/1245533531user69d75617af1be76a63e82988c65f2f1baeb05759e5f7ab4e43f5b08393f09e08.ogg', 1245533531, 1382513644, '2024-05-01 15:21:59', 1, 2, 58),
(60, 'hello', 1382513644, 1382513644, '2024-05-01 15:22:30', 1, 0, NULL),
(61, '😍😗', 1382513644, 1382513644, '2024-05-01 18:57:55', 1, 0, NULL),
(62, '/upload/chat/1382513644user8ad59466522cbcc5544c971938f995492eebbe94e3490b614baa5c5d52885ebd.ogg', 1382513644, 1245533531, '2024-05-01 18:58:27', 1, 2, 59),
(63, '/upload/chat/1382513644user4c6f4ae79dce85bcf205328ed5593df407b8df9634421edfe0123817d5ee760c.ogg', 1382513644, 1245533531, '2024-05-01 19:05:34', 1, 2, NULL),
(64, '/upload/post1.png', 1245533531, 1382513644, '2024-05-01 19:05:47', 1, 1, NULL),
(65, '$200? Too steep. Can you lower the price a bit? 😕', 1382513644, 1245533531, '2024-05-01 19:13:17', 1, 0, NULL),
(66, '/upload/chat/1382513644user5c932803f6cc941bde87e9ecc7abdd57316d31253c521a9be9e5aa7864ce8980.ogg', 1382513644, 1245533531, '2024-05-02 19:21:10', 1, 2, NULL),
(67, 'hello', 1382513644, 1245533531, '2024-05-02 19:21:35', 1, 0, NULL),
(68, 'zde', 1382513644, 1245533531, '2024-05-02 19:25:08', 1, 0, NULL),
(69, 'rfr', 1382513644, 1245533531, '2024-05-02 19:25:14', 1, 0, NULL),
(70, 'f', 1382513644, 1245533531, '2024-05-02 19:25:22', 1, 0, NULL),
(71, 'f', 1382513644, 1245533531, '2024-05-02 19:25:23', 1, 0, NULL),
(72, 'f', 1382513644, 1245533531, '2024-05-02 19:25:24', 1, 0, NULL),
(73, 'f', 1382513644, 1245533531, '2024-05-02 19:25:24', 1, 0, NULL),
(74, 'hello', 1382513644, 1245533531, '2024-05-02 19:25:39', 1, 0, 64),
(75, 'yes', 1382513644, 1245533531, '2024-05-02 19:25:49', 1, 0, 74),
(76, 'hello', 1382513644, 1668992603, '2024-05-03 14:38:51', -1, 0, NULL),
(77, 'how are you', 1382513644, 1668992603, '2024-05-03 14:38:59', -1, 0, NULL),
(78, 'hello', 1585660075, 1382513644, '2024-05-03 15:16:45', 1, 0, NULL),
(79, 'helo', 1382513644, 1382513644, '2024-05-04 09:59:14', 1, 0, NULL),
(80, '/upload/chat/1382513644user0bc1a64e7cb9c6ae372f4ee18cc6315f68b43f01d1b482cc59854c65bf1c795a.ogg', 1382513644, 1382513644, '2024-05-04 09:59:23', 1, 2, NULL),
(81, 'hi', 1245533531, 1382513644, '2024-05-04 11:56:43', 1, 0, NULL),
(82, 'hello', 1245533531, 1382513644, '2024-05-04 11:56:49', 1, 0, NULL),
(83, 'hello', 1245533531, 1382513644, '2024-05-04 11:57:03', 1, 0, NULL),
(84, 'dsf', 1382513644, 1245533531, '2024-05-04 12:10:46', 1, 0, 72),
(85, 'hello', 1382513644, 1245533531, '2024-05-04 21:56:56', 1, 0, NULL),
(86, 'efe', 1382513644, 1245533531, '2024-05-04 21:56:59', 1, 0, NULL),
(88, 'hello', 1382513644, 1668992603, '2024-05-05 00:01:40', -1, 0, NULL),
(89, 'hello', 1382513644, 1668992603, '2024-05-05 11:04:18', -1, 0, NULL),
(90, 'hello', 1382513644, 1245533531, '2024-05-05 11:16:26', 1, 0, NULL),
(91, 'ffe', 1382513644, 1245533531, '2024-05-05 11:16:34', 1, 0, NULL),
(92, 'hello', 1382513644, 1245533531, '2024-05-05 11:17:29', 1, 0, NULL),
(93, 'hello', 1382513644, 1245533531, '2024-05-05 11:28:13', 1, 0, NULL),
(94, 'go to your mather', 1382513644, 1245533531, '2024-05-05 11:28:23', 1, 0, NULL),
(95, 'zdz', 1245533531, 1382513644, '2024-05-09 21:28:43', -1, 0, NULL),
(96, 'hhh', 1245533531, 1382513644, '2024-05-27 18:33:35', -1, 0, NULL),
(97, '/upload/chat/1245533531user4b74be72b9a686a83f3987f5c8b63231f2c55d3877ab47c8bb60b14634f45798.ogg', 1245533531, 1382513644, '2024-05-27 18:33:40', -1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `postmedia`
--

CREATE TABLE `postmedia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('video','img') NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postmedia`
--

INSERT INTO `postmedia` (`id`, `post_id`, `type`, `url`) VALUES
(56, 79, 'img', '/upload/post/661573a28dcb67.495158193c0deb2530813a67ac9ce81a7610954f0912c6188fbbcd0858a84b7b4b55fc9f.png'),
(58, 81, 'video', '/upload/post/661573d4696b65.3840177782a765535f8b5c3e27d4b5759d277a2f3c58dbef7e31d48593b7a0796474110e.mp4'),
(59, 83, 'video', '/upload/post/66158e9d0852f7.909501145f0807a9ae98d75016903eb36b10b20f776bb7dea313d35c2a7c8e5a9c9fe886.mp4'),
(60, 88, 'img', '/upload/post/6615b45aae2e35.46023194c50299857c0289fed51b3b42a31725de18537c70d5377bd6a1670a7b5ac68c0b.png'),
(61, 93, 'video', '/upload/post/6617256b725314.017070207c1e080ee14430c62585f8adb02bf22f86a7a61e657db397200a05056c7fafad.mp4'),
(62, 94, 'video', '/upload/post/6617d8c8dadcf7.17937361c4eb7b9f9015f3d76aed8d4b02e6147fb3ddeabf6dc5f15fc43cce7a44448851.mp4'),
(63, 97, 'video', '/upload/post/661814571ab7d4.043218364d60795275d5f0adee09907a4b2bffad25ec36e21f7f632970a0174a64b97e11.mp4'),
(65, 104, 'video', '/upload/post/661d491b5917a0.514310862f88fd0bd211696c0e6ddcbe8e487c16d4e9e40707c19df8d6d4dd367b4df734.mp4'),
(66, 106, 'video', '/upload/post/661e71420f6e03.72284211009134821748ee6414f2ed19410c8fb419d5327b722dd2f41ee69cefd4bf2689.mp4'),
(68, 110, 'img', '/upload/post/66326a79ab3349.270829108e8c165e5f6a2e62960a530fcbd6cbd2948277960f18d97490a448bd3c0c8142.jpeg'),
(69, 110, 'img', '/upload/post/66326a79ab3349.2708291085b4302d6de1e9560f74550ca86b22f1afa3f2686462ac96fa22d1574480049a.jpg'),
(70, 110, 'img', '/upload/post/66326a79ab3349.27082910402376b5bea89624581b843359bb5e22ec1060246598e0e14330e8ceeeec2bcd.jpg'),
(71, 110, 'img', '/upload/post/66326a79ab3349.2708291037fc15b503e57c0f6f79364bc2eef606c1775e5c4062d3550cfc88c9acf25272.jpeg'),
(77, 118, 'img', '/upload/post/6633dd57571bc1.785142670ed16396629cbe326ec97f5b5c8b217917acc75953e96ca3b147b80d52ad8045.jpg'),
(78, 118, 'video', '/upload/post/6633dd57571bc1.78514267236beb92883729528aa9aef90ef8b03a4d3e81e842322cfea2beb3d7fdcfac67.mp4'),
(80, 129, 'img', '/upload/post/6637c3aed1a083.586131449f39c6f4dd763ab4d97a8caf19f7923edf6a46428e15eaf40263efb37ddc6b70.jpg'),
(81, 130, 'img', '/upload/post/6637c3bac43c30.9579718893a65449af3643509bf9bfc3797a6d1ff57de90ebe8b1d919b31ad8542565e21.jpg'),
(100, 150, 'video', '/upload/post/663ba963249242.30334820243782bf54580b2fd4d116512e5a5b9cd3735647b9f076de0c98443b96d5ccf4.mp4'),
(101, 151, 'img', '/upload/post/663ba9a0952cc8.75038514ddf2a2eedd931628457164de8ebb9f83f792a52decc9c9c5bf368567ef0ea236.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_owner` bigint(20) UNSIGNED NOT NULL,
  `post_visibility` enum('public','friend','only_me') NOT NULL DEFAULT 'public',
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `text_content` text DEFAULT NULL,
  `id_unique` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_owner`, `post_visibility`, `post_date`, `text_content`, `id_unique`) VALUES
(76, 1382513644, 'public', '2024-04-09 16:49:50', 'ee', '661571ae24eb23.44701605'),
(77, 1382513644, 'public', '2024-04-09 16:52:00', 'efe', '661572307c9b66.69309043'),
(78, 1382513644, 'public', '2024-04-09 16:54:54', 'rf', '661572de11afd7.52002078'),
(79, 1382513644, 'public', '2024-04-09 16:58:10', 'efe', '661573a28dcb67.49515819'),
(80, 1382513644, 'public', '2024-04-09 16:58:19', 'zde', '661573ab3c6373.78434360'),
(81, 1382513644, 'public', '2024-04-09 16:59:00', 'hello world', '661573d4696b65.38401777'),
(82, 1382513644, 'public', '2024-04-09 18:52:50', 'hello world', '66158e824f6826.34639935'),
(83, 1382513644, 'public', '2024-04-09 18:53:17', 'hello', '66158e9d0852f7.90950114'),
(84, 1382513644, 'public', '2024-04-09 18:54:41', 'd', '66158ef1b2d3e5.71631031'),
(85, 1382513644, 'public', '2024-04-09 19:00:50', 'eee', '66159062006db1.27597131'),
(86, 1382513644, 'public', '2024-04-09 21:31:23', 'hello', '6615b3ab78db46.76394416'),
(87, 1382513644, 'public', '2024-04-09 21:31:32', 'ef', '6615b3b4e34ff1.69009779'),
(88, 1382513644, 'public', '2024-04-09 21:34:18', 'ddd', '6615b45aae2e35.46023194'),
(89, 1382513644, 'public', '2024-04-09 21:35:00', 'ddd', '6615b484595ca5.88368949'),
(90, 1382513644, 'friend', '2024-04-10 16:08:43', 'hello world', '6616b98bdf0059.03308450'),
(91, 1382513644, 'public', '2024-04-10 16:23:08', 'hello', '6616bcec34e056.86294863'),
(92, 1382513644, 'public', '2024-04-10 23:47:41', 'efef', '6617251ddb0578.02599811'),
(93, 1382513644, 'friend', '2024-04-10 23:48:59', '', '6617256b725314.01707020'),
(94, 1382513644, 'public', '2024-04-11 12:34:16', '', '6617d8c8dadcf7.17937361'),
(95, 1382513644, 'public', '2024-04-11 16:38:10', 'hello world\r\nhy badie \r\nmy name is badie', '661811f2a32089.37607730'),
(96, 1382513644, 'public', '2024-04-11 16:40:48', 'hello world&lt;br/&gt;hy badie&lt;br/&gt;my name is badie', '66181290b54943.92504118'),
(97, 1382513644, 'friend', '2024-04-11 16:48:23', '', '661814571ab7d4.04321836'),
(98, 1084115894, 'public', '2024-04-13 11:19:43', 'hello world im a female', '661a6a4f20fe45.05899915'),
(100, 1382513644, 'public', '2024-04-13 23:44:12', '', '661b18cccc15e6.18865937'),
(101, 1382513644, 'public', '2024-04-14 11:36:24', '&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;', '661bbfb82d7d54.74168072'),
(102, 1382513644, 'public', '2024-04-14 23:01:31', 'hello world', '661c604b94cf29.86917689'),
(103, 1382513644, 'public', '2024-04-15 15:32:51', 'dddddd', '661d48a36b6c80.11461470'),
(104, 1382513644, 'public', '2024-04-15 15:34:51', 'dd', '661d491b5917a0.51431086'),
(105, 1382513644, 'public', '2024-04-16 10:10:43', 'jhqshjb', '661e4ea3e281d9.02033359'),
(106, 1382513644, 'public', '2024-04-16 12:38:26', 'derf', '661e71420f6e03.72284211'),
(108, 1382513644, 'public', '2024-04-27 14:51:20', 'hello wolrd', '662d10e86b16f4.69070600'),
(110, 1093641831, 'public', '2024-05-01 16:14:49', '', '66326a79ab3349.27082910'),
(113, 1382513644, 'public', '2024-05-01 18:57:34', 'hello world 😅😀&lt;br/&gt;', '6632909e4a0bc7.49506626'),
(115, 1245533531, 'only_me', '2024-05-02 00:36:12', 'hello wolrd my name is hala hola c 😎😉&lt;br/&gt;😆😅', '6632dffc3447c1.69103003'),
(118, 1093641831, 'public', '2024-05-02 18:37:11', 'this is test for update post', '6633dd57571bc1.78514267'),
(125, 1093641831, 'public', '2024-05-05 16:58:44', 'fef&lt;br/&gt;', '6637bac4eb0159.66134850'),
(127, 1668992603, 'public', '2024-05-05 17:36:31', 'zdzdf', '6637c39fd862b4.99185518'),
(128, 1668992603, 'public', '2024-05-05 17:36:37', 'hello$', '6637c3a5744bd2.96784435'),
(129, 1668992603, 'public', '2024-05-05 17:36:46', '', '6637c3aed1a083.58613144'),
(130, 1668992603, 'public', '2024-05-05 17:36:58', '', '6637c3bac43c30.95797188'),
(131, 1382513644, 'public', '2024-05-05 18:29:09', 'hello&lt;br/&gt;', '6637cff5666c50.88070672'),
(132, 1382513644, 'public', '2024-05-05 18:32:13', 'hello wolrd&lt;br/&gt;', '6637d0ad2bcd82.74514857'),
(133, 1382513644, 'only_me', '2024-05-05 19:01:10', 'dfd', '6637d77624b690.07681485'),
(134, 1382513644, 'public', '2024-05-05 19:04:39', 'edfe', '6637d8475ec726.31502100'),
(135, 1382513644, 'friend', '2024-05-05 19:05:26', 'this for my friends', '6637d8762af447.94526725'),
(136, 1585660075, 'only_me', '2024-05-05 19:07:16', 'only me', '6637d8e4844df9.94234518'),
(137, 1585660075, 'friend', '2024-05-05 19:07:31', 'friends', '6637d8f361bc56.01350183'),
(139, 1585660075, 'public', '2024-05-05 19:07:44', 'public', '6637d900755575.09260060'),
(148, 1382513644, 'friend', '2024-05-08 09:08:19', 'hello my freind', '663b41036d6867.83725469'),
(150, 1382513644, 'public', '2024-05-08 16:33:39', '', '663ba963249242.30334820'),
(151, 1382513644, 'public', '2024-05-08 16:34:40', '', '663ba9a0952cc8.75038514'),
(152, 1245533531, 'public', '2024-05-17 15:01:15', 'hello world', '6647713bbc7b51.06269136'),
(154, 1245533531, 'public', '2024-05-27 18:33:18', 'hello', '6654d1ee387944.19659739');

-- --------------------------------------------------------

--
-- Table structure for table `postshared`
--

CREATE TABLE `postshared` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `text_content` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` bigint(20) UNSIGNED NOT NULL,
  `userName` varchar(20) NOT NULL,
  `fName` varchar(20) NOT NULL,
  `lName` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `gender` enum('female','male') NOT NULL,
  `dateBirth` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT -1,
  `imgP` text DEFAULT NULL,
  `imgC` text DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `fName`, `lName`, `email`, `gender`, `dateBirth`, `status`, `imgP`, `imgC`, `pass`, `last_active`, `bio`) VALUES
(1084115894, 'best', 'khdiaja', 'ablo', 'khdiaja@gmail.com', 'female', 1715637600, 1, '/upload/profile/user-female.png', '/upload/cover/cover2.png', '$argon2i$v=19$m=65536,t=4,p=1$Y3NvZVYvRUFpcnZmLnV3cA$yum0p5cEFF2RqowhK8U/ZEXCJixkh68RVoSkGwLpQC0', '2024-05-03 00:01:22', NULL),
(1089753326, 'kako', 'Badie', 'Bahida', 'kako@gmil.com', 'female', 1717970400, 1, '/upload/profile/user-female.png', NULL, '$argon2i$v=19$m=65536,t=4,p=1$dklKYnk0RXVRM1FzUFZiSg$lIjQn1dusw0E7UvpWr6Fde/Bhdk3//a7QsOl/yyl2ms', '2024-05-09 22:28:34', NULL),
(1093641831, 'bibi', 'imane', 'ima', 'dedze@gma.com', 'female', 1715292000, 1, '/upload/profile/user-female.png', NULL, '$argon2i$v=19$m=65536,t=4,p=1$ZVEvOTVmZUo5bm41OWZSYg$6Xf1N3xQlLR5gEG4359Dr8d94N8NRkNgw6fVLd51btU', '2024-04-30 17:59:53', NULL),
(1245533531, 'hala', 'hala', 'hola', 'hala@gmail.com', 'male', 1715205600, 1, '/upload/users/1245533531/media/pictures/4a74905c645b7bc4f6f4f0c0e0bf2d8382bcecd64f7d72e7bbe89217c2c971d5.jpeg', '/upload/cover/cover3.png', '$argon2i$v=19$m=65536,t=4,p=1$WGhhUkRhaWRnN3hIdkFLbg$DVOT0mpO9LXCanPvssLSfTwyEGA5lj2u/cojt7w3MtU', '2024-05-28 18:03:19', ''),
(1382513644, 'badie', 'Badie', 'dev', 'badi3bahida16@gmail.com', 'male', 1087336800, 1, '/upload/users/1382513644/media/pictures/73517446193c3fc5f59be4ae5b817bdf0e1aa65247d465ad53830856fc5f287b.jpg', '/upload/users/1382513644/media/covers/a016b9dedc109f2d0ad9bfbc505d353889077cb1cf3da757cc93e15969e4aa9a.jpg', '$argon2i$v=19$m=65536,t=4,p=1$N1E5ZkNodWVxYzZZNkR0QQ$Po+tqsaAJNv/3OMKAYox5zXEHY+NlmpsidxpKK9JjzM', '2024-05-27 19:32:57', 'I love dev and art. 🥰 I’m passionate about photography and learning. I Think dev is a Philosophy. 😊'),
(1585660075, 'jawad', 'jawad', 'amohoche', 'jawad@gmail.com', 'male', 1086300000, 1, '/upload/profile/user-male.png', NULL, '$argon2i$v=19$m=65536,t=4,p=1$WkJJOFM0OTEwdzBkVDhYSw$T4sYKAmfZiFK/ElvQKqkXOnjKZ9XYqFNnEiImHrqPmc', '2024-05-08 11:06:42', NULL),
(1668992603, 'sara', 'sara', 'Bahida', 'sara6@gmail.com', 'female', 1717452000, 1, '/upload/profile/user-female.png', NULL, '$argon2i$v=19$m=65536,t=4,p=1$Smx2R0luTERPcXZYcFJJaQ$yMEG/7ofS9DcFi5LEkDghbrjSP7aSS90nbN0aWv9b+o', '2024-05-05 20:05:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_follow`
--

CREATE TABLE `user_follow` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `follower_id` bigint(20) UNSIGNED NOT NULL,
  `followed_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_follow`
--

INSERT INTO `user_follow` (`id`, `follower_id`, `followed_id`) VALUES
(24, 1245533531, 1382513644),
(66, 1382513644, 1245533531),
(73, 1585660075, 1245533531),
(87, 1585660075, 1382513644);

-- --------------------------------------------------------

--
-- Table structure for table `user_relation`
--

CREATE TABLE `user_relation` (
  `id_relation` bigint(20) UNSIGNED NOT NULL,
  `from_u` bigint(20) UNSIGNED NOT NULL,
  `to_u` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_relation`
--

INSERT INTO `user_relation` (`id_relation`, `from_u`, `to_u`, `date`, `status`) VALUES
(26, 1382513644, 1668992603, '2024-05-04 23:58:44', 'F'),
(27, 1668992603, 1382513644, '2024-05-04 23:58:44', 'F'),
(28, 1382513644, 1245533531, '2024-05-05 17:44:09', 'F'),
(29, 1245533531, 1382513644, '2024-05-05 17:44:09', 'F'),
(32, 1382513644, 1585660075, '2024-05-08 09:54:22', 'F'),
(33, 1585660075, 1382513644, '2024-05-08 09:54:22', 'F');

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `u_s_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `chat_auth` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_session`
--

INSERT INTO `user_session` (`u_s_id`, `user_id`, `token`, `chat_auth`) VALUES
(18, 1668992603, '702d8729352ebbf865d09beefe09caaffed7019905fe6f7680a5f7edbe293e7a', 'f94e97ea9a626bd3c088'),
(31, 1585660075, '373a0ef210571e2761b7c04864346c2d8ce13e6d09bc691bee13eec2f3abf8e6', 'a654bac7bbcf5862d832'),
(33, 1245533531, '9fb5945c121b40c9d1bb3bbf8d0845f4d3e5da2db1671a35a57804f3daadbdb0', '6e1240e80548e96cdce3');

-- --------------------------------------------------------

--
-- Table structure for table `writing_message_notifier`
--

CREATE TABLE `writing_message_notifier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disc_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_id` (`room_id`),
  ADD UNIQUE KEY `room_tokn` (`room_token`) USING HASH,
  ADD KEY `R12` (`user1_id`),
  ADD KEY `r13` (`user2_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`) USING BTREE,
  ADD KEY `r2` (`post_id`),
  ADD KEY `r44` (`replayID`);

--
-- Indexes for table `like_comment`
--
ALTER TABLE `like_comment`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `id` (`like_id`),
  ADD KEY `r4` (`comment_id`);

--
-- Indexes for table `like_post`
--
ALTER TABLE `like_post`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `id` (`like_id`),
  ADD KEY `r5` (`post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`) USING BTREE,
  ADD UNIQUE KEY `msg_id` (`msg_id`),
  ADD KEY `r8` (`reply`);

--
-- Indexes for table `postmedia`
--
ALTER TABLE `postmedia`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `r1` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`) USING BTREE,
  ADD UNIQUE KEY `id_unique` (`id_unique`),
  ADD UNIQUE KEY `post_id` (`post_id`);

--
-- Indexes for table `postshared`
--
ALTER TABLE `postshared`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `user_follow`
--
ALTER TABLE `user_follow`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `RF1` (`follower_id`),
  ADD KEY `RF2` (`followed_id`);

--
-- Indexes for table `user_relation`
--
ALTER TABLE `user_relation`
  ADD UNIQUE KEY `id_relation` (`id_relation`),
  ADD KEY `RR1` (`from_u`),
  ADD KEY `RR2` (`to_u`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD UNIQUE KEY `u_s_id` (`u_s_id`),
  ADD KEY `UAZD2` (`user_id`);

--
-- Indexes for table `writing_message_notifier`
--
ALTER TABLE `writing_message_notifier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `R15` (`disc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `room_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `like_comment`
--
ALTER TABLE `like_comment`
  MODIFY `like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `like_post`
--
ALTER TABLE `like_post`
  MODIFY `like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `postmedia`
--
ALTER TABLE `postmedia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `postshared`
--
ALTER TABLE `postshared`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1668992604;

--
-- AUTO_INCREMENT for table `user_follow`
--
ALTER TABLE `user_follow`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user_relation`
--
ALTER TABLE `user_relation`
  MODIFY `id_relation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `u_s_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `writing_message_notifier`
--
ALTER TABLE `writing_message_notifier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `R12` FOREIGN KEY (`user1_id`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `r13` FOREIGN KEY (`user2_id`) REFERENCES `users` (`userId`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `r44` FOREIGN KEY (`replayID`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `like_comment`
--
ALTER TABLE `like_comment`
  ADD CONSTRAINT `r4` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `like_post`
--
ALTER TABLE `like_post`
  ADD CONSTRAINT `r5` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `r8` FOREIGN KEY (`reply`) REFERENCES `messages` (`msg_id`) ON DELETE CASCADE;

--
-- Constraints for table `postmedia`
--
ALTER TABLE `postmedia`
  ADD CONSTRAINT `r1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_follow`
--
ALTER TABLE `user_follow`
  ADD CONSTRAINT `RF1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `RF2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `user_relation`
--
ALTER TABLE `user_relation`
  ADD CONSTRAINT `RR1` FOREIGN KEY (`from_u`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `RR2` FOREIGN KEY (`to_u`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `UAZD2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `writing_message_notifier`
--
ALTER TABLE `writing_message_notifier`
  ADD CONSTRAINT `R15` FOREIGN KEY (`disc_id`) REFERENCES `chat_room` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
