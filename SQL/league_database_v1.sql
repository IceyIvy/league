-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 01:23 PM
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
-- Database: `league_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_approved_bookings`
--

CREATE TABLE `bpslo_approved_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gcash_reference` varchar(255) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_approved_bookings`
--

INSERT INTO `bpslo_approved_bookings` (`id`, `booking_id`, `date`, `time`, `name`, `mobile_number`, `email`, `gcash_reference`, `payment`, `approved_at`) VALUES
(12, 'BOOKID67a421f44a21c', '2025-12-31', '12:00 PM,07:00 PM,11:00 AM,06:00 PM', 'Mine Kho Jeje', '0987744558', 'mine@gmail.com', 'ASDA87897', 800.00, '2025-02-06 02:48:38'),
(13, 'BOOKID67a422f39c250', '2025-11-26', '07:00 AM,03:00 PM,02:00 PM,08:00 AM', 'Maricar Reyes', '09887744511', 'mari@gmail.com', '4564ASD', 800.00, '2025-02-14 09:39:50'),
(14, 'BOOKID67af0f5513065', '2025-04-13', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM,12:00 PM', 'Janeth Napoles', '09996665587', 'jp@gmail.com', '5454AAAA', 1200.00, '2025-02-14 09:39:51'),
(15, 'BOOKID67af13624b2dc', '2025-12-25', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM,12:00 PM,01:00 PM,02:00 PM,03:00 PM,04:00 PM,05:00 PM,06:00 PM,07:00 PM', 'Santa Claus', '09998884457', 'santa@gmail.com', '787ASDDDD', 2600.00, '2025-02-14 09:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_bookings`
--

CREATE TABLE `bpslo_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gcash_reference` varchar(50) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_declined_bookings`
--

CREATE TABLE `bpslo_declined_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gcash_reference` varchar(255) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `reason` text NOT NULL,
  `declined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_declined_bookings`
--

INSERT INTO `bpslo_declined_bookings` (`id`, `booking_id`, `date`, `time`, `name`, `mobile_number`, `email`, `gcash_reference`, `payment`, `reason`, `declined_at`) VALUES
(1, 'BOOKID677617853dbf8', '2024-12-02', '01:00 PM,02:00 PM,03:00 PM,04:00 PM,06:00 PM,05:00 PM,07:00 PM', 'James Ryan', '09784574574', 'james@gmail.com', '456', 1400.00, 'no payment received', '2025-01-26 02:24:53'),
(2, 'BOOKID677617c3471dc', '2024-12-03', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM', 'Pogasi', '09996665512', 'pongasi@gmail.com', '454', 1000.00, 'no payment received after 12 hour', '2025-01-26 02:27:56'),
(3, 'BOOKID679973c7a5d1b', '2024-07-31', '09:00 AM,05:00 PM,11:00 AM,06:00 PM,10:00 AM', 'Feliz Manalo', '098712`2243', 'felix@gmail.com', '87', 1000.00, 'adasds', '2025-02-02 01:59:21'),
(4, 'BOOKID67a02029c3e31', '2025-12-29', '02:00 PM,03:00 PM,04:00 PM,05:00 PM', 'Freddy Kosi', '09876543212', 'fred@gmail.com', '0', 800.00, 'no payment again', '2025-02-04 01:49:18'),
(5, 'BOOKID67a30205aa58b', '2025-10-27', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM,12:00 PM', 'Janeth Miranda', '09668885574', 'jm@gmail.com', '5474', 1200.00, 'no payment received', '2025-02-05 13:57:56'),
(6, 'BOOKID6774f4d913123', '2024-12-01', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM,12:00 PM,08:00 PM', 'Ivy Christian', '09996665547', 'ivy@gmail.com', '0', 1400.00, 'last testing for cancelling', '2025-02-05 14:20:19'),
(7, 'BOOKID6774f4d913123', '2024-12-01', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM,12:00 PM,08:00 PM', 'Ivy Christian', '09996665547', 'ivy@gmail.com', '0', 1400.00, 'fuck you', '2025-02-05 14:22:35'),
(8, 'BOOKID67a301eb0e218', '2025-10-16', '07:00 AM,06:00 PM,07:00 PM,08:00 PM', 'Marie Felix', '09665544114', 'mf@gmai.com', '457', 800.00, 'fuck you marie', '2025-02-05 14:23:49'),
(9, 'BOOKID67a170d468f8c', '2025-09-23', '08:00 AM,09:00 AM,10:00 AM,11:00 AM,07:00 AM,12:00 PM', 'Jean Mahasol', '0977221121', 'jean@gmail.com', '65', 1200.00, '123', '2025-02-05 14:24:11'),
(10, 'BOOKID677617b9a5988', '2024-12-03', '07:00 AM,08:00 AM,09:00 AM,10:00 AM,11:00 AM', 'Pogasi', '09996665512', 'pongasi@gmail.com', '454', 1000.00, 'pongasi', '2025-02-05 14:24:35'),
(11, 'BOOKID67761782a1752', '2024-12-02', '01:00 PM,02:00 PM,03:00 PM,04:00 PM,06:00 PM,05:00 PM,07:00 PM', 'James Ryan', '09784574574', 'james@gmail.com', '456', 1400.00, '456ASDWQ', '2025-02-05 14:26:00'),
(12, 'BOOKID67a37585272f3', '2025-11-29', '07:00 AM,08:00 AM,09:00 AM,04:00 PM,10:00 AM,05:00 PM,03:00 PM,02:00 PM', 'Kurt John', '09998882241', 'kurt@gmail.com', '564ASDEEE', 1600.00, 'cancel lang', '2025-02-05 14:28:47'),
(13, 'BOOKID67a3761f7f244', '2025-11-19', '07:00 AM,02:00 PM,08:00 AM,03:00 PM,09:00 AM,04:00 PM,10:00 AM,05:00 PM', 'James Bryan', '098874574454', 'jb@gmail.com', 'ASDASD23123', 1600.00, 'asdassssssssss', '2025-02-05 14:31:09'),
(14, 'BOOKID67a377455f1e2', '2025-12-31', '02:00 PM,08:00 AM,03:00 PM,07:00 AM', 'KIng Boo', '09684574474', 'king@gmail.com', '456TTTT', 800.00, 'wanan fuck everyone', '2025-02-05 15:09:46'),
(15, 'BOOKID67a3776c0f7ca', '2025-12-31', '09:00 AM,04:00 PM,10:00 AM,05:00 PM', 'Marisa Jean', '09776662431', 'marisa@gmail.com', 'MAR12111', 800.00, 'wanan fuck everyone', '2025-02-05 15:09:46'),
(16, 'BOOKID67a3778ab93de', '2025-12-31', '01:00 PM,08:00 PM', 'JOhn Jay', '09774455154', 'jj@gmail.com', 'POP121', 400.00, 'wanan fuck everyone', '2025-02-05 15:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_league`
--

CREATE TABLE `bpslo_league` (
  `id` int(11) NOT NULL,
  `league_id` varchar(50) NOT NULL,
  `who` text NOT NULL,
  `what` text NOT NULL,
  `when` date NOT NULL,
  `where` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_league`
--

INSERT INTO `bpslo_league` (`id`, `league_id`, `who`, `what`, `when`, `where`, `description`, `created_at`) VALUES
(4, 'LEAGUEID_67a427a7431e9', 'Mr. HOnda', 'Lumba dagan', '2025-10-28', 'pasil gym', 'kontra speed', '2025-02-06 03:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_match_cancelled`
--

CREATE TABLE `bpslo_match_cancelled` (
  `id` int(11) NOT NULL,
  `match_id` varchar(50) NOT NULL,
  `team_a` varchar(100) NOT NULL,
  `team_b` varchar(100) NOT NULL,
  `when` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `canceled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_match_cancelled`
--

INSERT INTO `bpslo_match_cancelled` (`id`, `match_id`, `team_a`, `team_b`, `when`, `time`, `description`, `created_at`, `canceled_at`) VALUES
(1, 'MATCH_679191160bf63', 'Team Jean', 'Team James', '2024-12-31', '12:59:00', 'asjfs tpoasrj ogshfjas;i e', '2025-01-27 06:24:32', '2025-01-27 06:24:32'),
(2, 'MATCH_6791b23d6e1e1', 'Patty', 'JeanTeam', '2023-12-31', '23:58:00', 'good pls', '2025-01-27 06:24:55', '2025-01-27 06:24:55'),
(3, 'MATCH_679b8960b26dd', 'United Kingdome Ice Age', 'Team Probinsyanas', '2025-05-26', '15:30:00', 'duplicate', '2025-01-30 14:16:31', '2025-01-30 14:16:31'),
(4, 'MATCH_679f6b63dcf71', 'Dallas Mavericks', 'Pasil Warriors State', '2025-02-03', '11:00:00', 'new teams', '2025-02-04 01:53:42', '2025-02-04 01:53:42'),
(5, 'MATCH_67a174a39745c', 'Pasil Warriors State', 'Miami Heaters', '2025-12-30', '12:00:00', 'This is an exciting match', '2025-02-04 02:01:21', '2025-02-04 02:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_match_schedules`
--

CREATE TABLE `bpslo_match_schedules` (
  `id` int(11) NOT NULL,
  `match_id` varchar(50) NOT NULL,
  `team_a` varchar(100) NOT NULL,
  `team_b` varchar(100) NOT NULL,
  `when` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_match_schedules`
--

INSERT INTO `bpslo_match_schedules` (`id`, `match_id`, `team_a`, `team_b`, `when`, `time`, `description`, `created_at`) VALUES
(21, 'MATCH_67a17ce27cc46', 'Pasil Warriors State', 'Miami Heaters', '2025-12-25', '13:00:00', 'good year end game', '2025-02-04 02:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_match_winners`
--

CREATE TABLE `bpslo_match_winners` (
  `id` int(11) NOT NULL,
  `result_id` varchar(50) NOT NULL,
  `sport` varchar(50) NOT NULL,
  `division` varchar(50) NOT NULL,
  `team_a_name` varchar(100) NOT NULL,
  `team_b_name` varchar(100) NOT NULL,
  `team_a_score` int(11) NOT NULL,
  `team_b_score` int(11) NOT NULL,
  `winner` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_match_winners`
--

INSERT INTO `bpslo_match_winners` (`id`, `result_id`, `sport`, `division`, `team_a_name`, `team_b_name`, `team_a_score`, `team_b_score`, `winner`, `description`, `posted_at`) VALUES
(10, 'RESULT_67a4197c6fa03', 'Basketball', 'Men\'s', 'Pasil Warriors State', 'Dallas Mavericks', 12, 111, 'Dallas Mavericks', 'asdasd', '2025-02-06 02:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_organizers`
--

CREATE TABLE `bpslo_organizers` (
  `id` int(11) NOT NULL,
  `organizer_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `last_session` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_organizers`
--

INSERT INTO `bpslo_organizers` (`id`, `organizer_id`, `email`, `password`, `role`, `last_session`, `created_at`) VALUES
(11, 'REGID67919287def45', 'admin@gmail.com', '$2y$10$MhgIVyB6wWIRs3pQ1JRFluCQuTGSt0IG2ci2um3BTjB3u3xblYBuC', 'Admin', '2025-02-14 10:13:17', '2025-01-23 00:51:20'),
(13, 'REGID67959542ee450', 'organizer@gmail.com', '$2y$10$ZFeE7L5X1wk1Z6c..cYWg.gMUKO3A4HOvXrYqpxkBExbWxah.0Hya', 'Organizer', '2025-02-14 10:17:25', '2025-01-26 01:52:03'),
(22, 'REGID67af14023149d', 'test@gmail.com', '$2y$10$kyXNGpKLaNEO3hHtSzefjOqomY/AkadqL/sym4DDInMfywv8E8jma', 'Organizer', NULL, '2025-02-14 09:59:30'),
(24, 'REGID67af14fb0630b', 'test1@gmail.com', '$2y$10$sly4C86/CfylF5KnYUjoP.zTrLdOKRFwjQOI9IxXnecWMqZmwyRJC', 'Organizer', NULL, '2025-02-14 10:03:39'),
(26, 'REGID67af151200334', 'test2@gmail.com', '$2y$10$WAmKrYt4N8DK6X.BQFLKOuGb5mBct//FF4u0hAa3AxfTFYl92l6c2', 'Organizer', NULL, '2025-02-14 10:04:02'),
(27, 'REGID67af15835a378', 'test3@gmail.com', '$2y$10$aE2YuW0/kbpcehKNh6sFJey5irc4auibaNgW0EqLJ6SidRZcTfDsO', 'Organizer', NULL, '2025-02-14 10:05:55'),
(28, 'REGID67af15967aa32', 'juan@gmail.com', '$2y$10$33VBea927H4l.PFbgBRlZO5w1TWpkAIpnpegUZIyE2sI9ISSL7ZcC', 'Organizer', NULL, '2025-02-14 10:06:14'),
(29, 'REGID67af15adcf72a', 'mark@gmail.com', '$2y$10$yoWBVu1CuGvvcxCrUMXRB.My3duiSV44N2FSc2aqeKcKzYyeCLtxG', 'Organizer', NULL, '2025-02-14 10:06:37'),
(30, 'REGID67af15df2362f', 'planas@gmail.com', '$2y$10$a5.qdd6Q8S6nRQp/G0OH4uT0VSHpBN1RQJ6uiyfQZlNAQ3uzv.bEO', 'Organizer', NULL, '2025-02-14 10:07:27'),
(31, 'REGID67af160ad1541', 'pablo@gmail.com', '$2y$10$2TG5vKHI/VKrtsVf2yrKDu1bn7B2pJMMKDXMQgh.dSDF4DaaoX.MK', 'Organizer', NULL, '2025-02-14 10:08:10'),
(32, 'REGID67af161254443', 'janice@gmail.com', '$2y$10$1bkgX/WFal/q5P2.T5CbQ.fKPoVfw/r.4eyUfhYDuVPotfQk6C0nm', 'Organizer', NULL, '2025-02-14 10:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_organizers_inactive`
--

CREATE TABLE `bpslo_organizers_inactive` (
  `id` int(11) NOT NULL,
  `organizer_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `last_session` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `moved_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_organizers_inactive`
--

INSERT INTO `bpslo_organizers_inactive` (`id`, `organizer_id`, `email`, `password`, `role`, `last_session`, `created_at`, `moved_date`) VALUES
(1, 'REGID6757db5929b84', 'user@gmail.com', '$2y$10$FtrjSdPuAh7ATPJe15GnfuG5gWMdZs2y/PUARXxA7dzVYhLK9DFbC', 'Organizer', '2024-11-17 04:54:41', '2024-12-10 06:10:33', '2024-12-17 05:47:54'),
(2, 'REGID6757db52b2caa', 'admin@gmail.com', '$2y$10$Ay52uzqgh8sZmPWgDa6ryOF8Y2naFv4dOwr4/8TEUwZupDPj2SBAi', 'Organizer', '2024-10-17 03:54:24', '2024-12-10 06:10:26', '2024-12-17 05:48:09'),
(3, 'REGID677a198b1e456', 'admin_kapitan@gmail.com', '$2y$10$9BBYBmGB92YjFn6k3zdoCOXyjWU4CRbLugo5QvdozJWaPUi4UNT.O', 'Organizer', '2024-01-05 07:04:44', '2025-01-05 05:32:59', '2025-01-05 07:06:00'),
(4, 'REGID6798455ee2255', 'jean@gmail.com', '$2y$10$KsJFQTWRv8ZjgQYn6FQBlOSzifm9Uaoz8yoiXgROL1hypfsLEfWPK', 'Admin', '2025-01-04 01:48:06', '2025-01-28 02:47:59', '2025-02-04 01:48:32'),
(5, 'REGID67a1717686e95', 'ana@gmail.com', '$2y$10$ubEB6qLAmZ1AyOSHknzYEeiYIkdg3oAa6gwedqYZkeW.sjDCYtSC.', 'Admin', '2025-01-04 01:47:53', '2025-02-04 01:46:30', '2025-02-04 01:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_posts`
--

CREATE TABLE `bpslo_posts` (
  `id` int(11) NOT NULL,
  `post_id` varchar(50) NOT NULL,
  `what` text NOT NULL,
  `when` date NOT NULL,
  `where` text NOT NULL,
  `why` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_posts`
--

INSERT INTO `bpslo_posts` (`id`, `post_id`, `what`, `when`, `where`, `why`, `created_at`) VALUES
(3, 'POSTID_67a3121de5cfe', 'Gym not available!!!!!!!!', '2025-10-30', 'Pasil gym na pud (Ex B)', 'basta dle available that day', '2025-02-05 07:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_registrations`
--

CREATE TABLE `bpslo_registrations` (
  `id` int(11) NOT NULL,
  `application_id` varchar(50) DEFAULT NULL,
  `sport` varchar(50) DEFAULT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `division` varchar(50) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `sitio` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `photo` longblob DEFAULT NULL,
  `nso` longblob DEFAULT NULL,
  `voter_cert` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_registrations_approved`
--

CREATE TABLE `bpslo_registrations_approved` (
  `id` int(11) NOT NULL,
  `player_id` varchar(250) NOT NULL,
  `sport` varchar(250) NOT NULL,
  `team_name` varchar(250) NOT NULL,
  `division` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) NOT NULL,
  `birth_date` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `sitio` varchar(250) NOT NULL,
  `mobile_number` varchar(250) NOT NULL,
  `email_address` varchar(250) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_registrations_approved`
--

INSERT INTO `bpslo_registrations_approved` (`id`, `player_id`, `sport`, `team_name`, `division`, `first_name`, `middle_name`, `last_name`, `birth_date`, `age`, `sex`, `sitio`, `mobile_number`, `email_address`, `approved_at`) VALUES
(31, 'PLAYERID679e09972616f', 'Basketball', 'Dallas Mavericks', 'Men\'s', 'Santas', 'Swelo', 'Claus', '2005-09-14', 19, 'Male', 'T. Cavan', '091425432123', 'santa@gmail.com', '2025-02-01 11:46:31'),
(32, 'PLAYERID679e09ee68a02', 'Basketball', 'Oklahoma City Storm', 'Men\'s', 'James', 'Harden', 'Harder', '1993-06-18', 31, 'Male', 'L. Flores', '09198624942', 'jamesharder@gmail.com', '2025-02-01 11:47:58'),
(35, 'PLAYERID679e0accb2bb9', 'Volleyball', 'Volly Rockers', 'Men\'s', 'Alden', 'Pachas', 'Bernardo', '1995-09-16', 29, 'Male', 'L. Sun-ok', '09993332212', 'alden@gmail.com', '2025-02-01 11:51:40'),
(36, 'PLAYERID679e0c015ad0c', 'Basketball', 'Poker Faces', 'Men\'s', 'Mary', 'Dixon', 'Anita', '1998-03-04', 26, 'Female', 'L. Sun-ok', '09993332222', 'mary@gmail.com', '2025-02-01 11:56:49'),
(37, 'PLAYERID679e0c03d7f0f', 'Volleyball', 'Volly Rockers', 'Women\'s', 'Janice', 'Pistla', 'Pongasi', '2003-04-10', 21, 'Female', 'L. Puthawan', '09193332215', 'janice@gmail.com', '2025-02-01 11:56:51'),
(41, 'PLAYERID679e0d4b6a925', 'Basketball', 'Dallas Mavericks', 'Men\'s', 'Luka', 'Delta', 'Doncic', '2003-10-26', 21, 'Male', 'Mahayahay 1', '09993311122', 'luka_lalkers@gmail.com', '2025-02-01 12:02:19'),
(42, 'PLAYERID679ece8ec5cc8', 'Basketball', 'Miami Heaters', 'Women\'s', 'Maine', 'Marisa', 'Mendoza', '2000-08-20', 24, 'Female', 'Mahayahay 2', '09874574574', 'main@gmail.com', '2025-02-02 01:46:54'),
(43, 'PLAYERID67a1758db1b43', 'Basketball', 'Pasil Warriors State', 'Men\'s', 'Ivy', 'Narte', 'Otida', '2002-11-30', 22, 'Male', 'Mahayahay 1', '0995749587', 'ivy@gmail.com', '2025-02-04 02:03:57'),
(44, 'PLAYERID67a175fe6e22f', 'Basketball', 'Pasil Warriors State', 'Men\'s', 'Emman', 'Lumacad', 'Pongasi', '2001-12-29', 23, 'Male', 'Mahayahay 2', '09676615332', 'emman@gmail.com', '2025-02-04 02:05:50'),
(45, 'PLAYERID67a3027b66e78', 'Basketball', 'Dallas Mavericks', 'Men\'s', 'Luka', 'James', 'Doncic', '1995-12-31', 29, 'Male', 'L. Sun-ok', '09672344223', 'luka@gmail.com', '2025-02-05 06:17:31'),
(46, 'PLAYERID67a30288b0a79', 'Volleyball', 'Volly Rockers', 'Men\'s', 'Marie', 'Car', 'Indot', '2006-12-31', 18, 'Female', 'Mahayahay 2', '09878455514', 'mari@gmail.com', '2025-02-05 06:17:44'),
(47, 'PLAYERID67a302a868cb8', 'Basketball', 'Miami Heaters', 'Men\'s', 'Juan', 'Rosales', 'Pongasi', '2001-12-31', 23, 'Male', 'L. Flores', '09993332215', 'rosales@gmail.com', '2025-02-05 06:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_registrations_declined`
--

CREATE TABLE `bpslo_registrations_declined` (
  `id` int(11) NOT NULL,
  `application_id` varchar(50) DEFAULT NULL,
  `sport` varchar(50) DEFAULT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `division` varchar(50) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `declined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_registrations_declined`
--

INSERT INTO `bpslo_registrations_declined` (`id`, `application_id`, `sport`, `team_name`, `division`, `first_name`, `middle_name`, `last_name`, `mobile_number`, `email_address`, `reason`, `declined_at`) VALUES
(1, 'REGID6757d45564a13', 'Basketball', 'Poker Face', 'Men\'s', 'Alden', 'Swelo', 'Bayot', '1425432123', 'dickes@gmail.com', 'aden richards', '2025-01-26 05:27:26'),
(2, 'REGID6757d496569dd', 'Volleyball', 'Volly Rockers', 'Women\'s', 'Jaca', 'Planas', 'Swelto', '09198624942', 'jaca@gasdasd', 'analyn', '2025-01-26 05:27:38'),
(3, 'REGID677a153babcfb', 'Basketball', 'Santa 2k25', 'Men\'s', 'Ivy Christian', 'Otida', 'Smith', '09998887787', 'sainta@gmail.com', 'not today', '2025-01-26 05:29:26'),
(4, 'REGID677a14cf679de', 'Basketball', 'Santa 2k25', 'Men\'s', 'Santa', 'Claus', 'Claus', '09998887787', 'sainta@gmail.com', '69', '2025-01-26 09:30:21'),
(5, 'REGID679e0ac14dff0', '', 'Simple Team', 'Men\'s', 'Pusssia', 'Ann', 'Lust', '09993332211', 'Pusssia@gmail.com', 'no sporrts', '2025-02-01 11:52:03'),
(6, 'REGID67a1704ee189a', 'Basketball', 'Miami Heaters', 'Men\'s', 'Jo', 'Marie', 'Kissa', '09887766225', 'jomarie@gmail.com', 'too stupid', '2025-02-04 02:04:08');

-- --------------------------------------------------------

--
-- Table structure for table `bpslo_teams`
--

CREATE TABLE `bpslo_teams` (
  `id` int(11) NOT NULL,
  `team_id` varchar(255) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `sport` varchar(255) NOT NULL,
  `division` varchar(255) NOT NULL,
  `player_count` int(11) NOT NULL,
  `wins` int(11) NOT NULL,
  `losses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpslo_teams`
--

INSERT INTO `bpslo_teams` (`id`, `team_id`, `team_name`, `sport`, `division`, `player_count`, `wins`, `losses`) VALUES
(5, 'TEAMID679e06ca123f0', 'Pasil Warriors State', 'Basketball', 'Men\'s', 2, 11, 6),
(6, 'TEAMID679e06d2bded3', 'Miami Heaters', 'Basketball', 'Men\'s', 1, 6, 11),
(7, 'TEAMID679e06e25a67f', 'Dallas Mavericks', 'Basketball', 'Men\'s', 3, 2, 1),
(10, 'TEAMID679e071573c94', 'Volly Rockers', 'Volleyball', 'Men\'s', 2, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bpslo_approved_bookings`
--
ALTER TABLE `bpslo_approved_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_bookings`
--
ALTER TABLE `bpslo_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_declined_bookings`
--
ALTER TABLE `bpslo_declined_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_league`
--
ALTER TABLE `bpslo_league`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_match_cancelled`
--
ALTER TABLE `bpslo_match_cancelled`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_match_schedules`
--
ALTER TABLE `bpslo_match_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_match_winners`
--
ALTER TABLE `bpslo_match_winners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_organizers`
--
ALTER TABLE `bpslo_organizers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bpslo_organizers_inactive`
--
ALTER TABLE `bpslo_organizers_inactive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_posts`
--
ALTER TABLE `bpslo_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_registrations`
--
ALTER TABLE `bpslo_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `bpslo_registrations_approved`
--
ALTER TABLE `bpslo_registrations_approved`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bpslo_registrations_declined`
--
ALTER TABLE `bpslo_registrations_declined`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `bpslo_teams`
--
ALTER TABLE `bpslo_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_id` (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bpslo_approved_bookings`
--
ALTER TABLE `bpslo_approved_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bpslo_bookings`
--
ALTER TABLE `bpslo_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bpslo_declined_bookings`
--
ALTER TABLE `bpslo_declined_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bpslo_league`
--
ALTER TABLE `bpslo_league`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bpslo_match_cancelled`
--
ALTER TABLE `bpslo_match_cancelled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bpslo_match_schedules`
--
ALTER TABLE `bpslo_match_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `bpslo_match_winners`
--
ALTER TABLE `bpslo_match_winners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bpslo_organizers`
--
ALTER TABLE `bpslo_organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `bpslo_organizers_inactive`
--
ALTER TABLE `bpslo_organizers_inactive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bpslo_posts`
--
ALTER TABLE `bpslo_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bpslo_registrations`
--
ALTER TABLE `bpslo_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bpslo_registrations_approved`
--
ALTER TABLE `bpslo_registrations_approved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `bpslo_registrations_declined`
--
ALTER TABLE `bpslo_registrations_declined`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bpslo_teams`
--
ALTER TABLE `bpslo_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
