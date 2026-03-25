-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 04:17 AM
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
-- Database: `prosthesis_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `notes` text DEFAULT '',
  `status` varchar(20) DEFAULT 'scheduled',
  `reminder_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `doctor_name`, `appointment_date`, `appointment_time`, `appointment_type`, `notes`, `status`, `reminder_sent`, `created_at`) VALUES
(1, 1, 'Dr. Smith', '2026-02-25', '10:15:00', 'Follow-up', '', 'scheduled', 0, '2026-02-24 08:45:20'),
(2, 1, 'Dr. Smith', '2026-02-25', '07:15:00', 'Check-up', '', 'cancelled', 0, '2026-02-24 08:46:04'),
(3, 1, 'Dr. Smith', '2026-02-26', '10:00:00', 'Check-up', '', 'scheduled', 0, '2026-02-24 08:54:09'),
(4, 1, 'Dr. Smith', '2026-03-03', '10:00:00', 'Check-up', 'Test appointment', 'scheduled', 0, '2026-02-24 09:02:42'),
(5, 1, 'Dr. Brown', '2026-02-25', '09:00:00', 'Check-up', '', 'scheduled', 0, '2026-02-24 09:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `comorbidities`
--

CREATE TABLE `comorbidities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `diabetes` varchar(10) NOT NULL DEFAULT 'No',
  `hypertension` varchar(10) NOT NULL DEFAULT 'No',
  `hyperthyroidism` varchar(10) NOT NULL DEFAULT 'No',
  `hypothyroidism` varchar(10) NOT NULL DEFAULT 'No',
  `heart_disease` varchar(10) NOT NULL DEFAULT 'No',
  `blood_disorder` varchar(10) NOT NULL DEFAULT 'No',
  `immune_disorder` varchar(10) NOT NULL DEFAULT 'No',
  `osteoporosis` varchar(10) NOT NULL DEFAULT 'No',
  `other` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comorbidities`
--

INSERT INTO `comorbidities` (`id`, `user_id`, `diabetes`, `hypertension`, `hyperthyroidism`, `hypothyroidism`, `heart_disease`, `blood_disorder`, `immune_disorder`, `osteoporosis`, `other`, `created_at`) VALUES
(1, 1, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-02-24 07:09:57'),
(2, 6, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-12 02:57:53'),
(3, 7, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-19 04:32:06'),
(4, 8, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-19 06:39:43'),
(5, 9, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-19 07:00:57'),
(6, 10, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'Yes', '', '2026-03-19 07:09:12'),
(7, 11, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-19 07:16:24'),
(8, 12, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '', '2026-03-19 07:53:58'),
(9, 13, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '', '2026-03-20 06:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `compliance_scores`
--

CREATE TABLE `compliance_scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_tasks` int(11) DEFAULT 0,
  `completed_tasks` int(11) DEFAULT 0,
  `points_earned` int(11) DEFAULT 0,
  `score` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_tasks`
--

CREATE TABLE `daily_tasks` (
  `id` int(11) NOT NULL,
  `task_name` varchar(200) NOT NULL,
  `task_order` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_tasks`
--

INSERT INTO `daily_tasks` (`id`, `task_name`, `task_order`, `created_at`) VALUES
(9, 'Remove and rinse prosthesis', 1, '2026-03-12 05:07:44'),
(10, 'Brush prosthesis gently', 2, '2026-03-12 05:07:44'),
(11, 'Clean mouth and gums', 3, '2026-03-12 05:07:44'),
(12, 'Soak in cleaning solution', 4, '2026-03-12 05:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `doctor_name`, `specialization`, `is_available`, `created_at`) VALUES
(1, 'Dr. Megan', 'Prosthodontist', 1, '2026-02-24 08:33:37'),
(2, 'Dr. Aryan', 'Prosthodentist', 1, '2026-02-24 08:33:37'),
(3, 'Dr. Revathi', 'Periodontist', 1, '2026-02-24 08:33:37'),
(4, 'Dr. Brown', 'General Dentist', 1, '2026-02-24 08:33:37'),
(5, 'Dr. Davis', 'Orthodontist', 1, '2026-02-24 08:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_history`
--

CREATE TABLE `notification_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_history`
--

INSERT INTO `notification_history` (`id`, `user_id`, `notification_type`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'appointment', 'Appointment Scheduled', 'Your appointment with Dr. Smith is scheduled for February 25, 2026 at 10:15 AM', 0, '2026-02-24 08:45:20'),
(2, 1, 'appointment', 'Appointment Scheduled', 'Your appointment with Dr. Smith is scheduled for February 25, 2026 at 7:15 AM', 0, '2026-02-24 08:46:04'),
(3, 1, 'appointment', 'Appointment Scheduled', 'Your appointment with Dr. Smith is scheduled for February 26, 2026 at 10:00 AM', 0, '2026-02-24 08:54:09'),
(4, 1, 'appointment_cancelled', 'Appointment Cancelled', 'Your appointment with Dr. Smith on February 25, 2026 at 7:15 AM has been cancelled.', 0, '2026-02-24 08:54:49'),
(5, 1, 'appointment', 'Appointment Scheduled', 'Your appointment with Dr. Smith is scheduled for March 3, 2026 at 10:00 AM', 0, '2026-02-24 09:02:42'),
(6, 1, 'appointment', 'Appointment Scheduled', 'Your appointment with Dr. Brown is scheduled for February 25, 2026 at 9:00 AM', 0, '2026-02-24 09:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `privacy_consent`
--

CREATE TABLE `privacy_consent` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data_sharing_consent` tinyint(1) DEFAULT 0,
  `research_consent` tinyint(1) DEFAULT 0,
  `marketing_consent` tinyint(1) DEFAULT 0,
  `consent_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prosthesis`
--

CREATE TABLE `prosthesis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prosthesis_type` varchar(50) NOT NULL,
  `prosthesis_name` varchar(100) NOT NULL,
  `installation_date` date NOT NULL,
  `next_maintenance_date` date NOT NULL,
  `notes` text DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prosthesis`
--

INSERT INTO `prosthesis` (`id`, `user_id`, `prosthesis_type`, `prosthesis_name`, `installation_date`, `next_maintenance_date`, `notes`, `created_at`) VALUES
(1, 1, 'Fixed', 'Crown', '2026-02-24', '2026-03-24', '', '2026-02-24 07:01:29'),
(2, 7, 'Fixed', 'Crown', '2026-03-19', '2026-09-19', 'Initial selection during onboarding', '2026-03-19 04:32:06'),
(3, 8, 'Fixed', 'Implant', '2026-03-19', '2026-09-19', 'Initial selection during onboarding', '2026-03-19 06:39:43'),
(4, 9, 'Removable', 'Full Denture', '0000-00-00', '0000-00-00', '', '2026-03-19 07:00:57'),
(5, 10, 'Removable', 'Partial Denture', '2026-03-19', '2026-09-19', 'Initial selection during onboarding', '2026-03-19 07:09:12'),
(6, 11, 'Fixed', 'Bridge', '2026-03-19', '2026-09-19', 'Initial selection during onboarding', '2026-03-19 07:16:24'),
(7, 12, 'Removable', 'Full Denture', '2026-03-19', '2026-09-19', 'Initial selection during onboarding', '2026-03-19 07:53:58'),
(8, 13, 'Fixed', 'Implant', '2026-03-20', '2026-09-20', 'Initial selection during onboarding', '2026-03-20 06:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reminder_title` varchar(255) DEFAULT NULL,
  `reminder_time` time DEFAULT NULL,
  `reminder_type` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminder_history`
--

CREATE TABLE `reminder_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `is_completed` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminder_history`
--

INSERT INTO `reminder_history` (`id`, `user_id`, `title`, `date`, `time`, `is_completed`) VALUES
(1, 1, 'Daily Maintenance', '2026-03-19', '10:25 AM', 1),
(2, 1, 'Deep Cleaning', '2026-03-19', '10:25 AM', 1),
(3, 1, 'Monthly Maintenance', '2026-03-19', '10:25 AM', 1),
(4, 1, 'Deep Cleaning', '2026-03-19', '10:42 AM', 1),
(5, 1, 'Daily Maintenance', '2026-03-19', '10:43 AM', 1),
(6, 1, 'Daily Maintenance', '2026-03-19', '10:43 AM', 1),
(7, 1, 'Daily Maintenance', '2026-03-19', '11:00 AM', 1),
(8, 1, 'Daily Maintenance', '2026-03-19', '11:00 AM', 1),
(9, 1, 'Daily Maintenance', '2026-03-19', '12:08 PM', 1),
(10, 8, 'Daily Maintenance', '2026-03-19', '12:09 PM', 1),
(11, 8, 'Daily Maintenance', '2026-03-19', '12:10 PM', 1),
(12, 8, 'Daily Maintenance', '2026-03-19', '12:10 PM', 1),
(13, 9, 'Daily Maintenance', '2026-03-19', '12:31 PM', 1),
(14, 9, 'Daily Maintenance', '2026-03-19', '12:31 PM', 1),
(15, 10, 'Daily Maintenance', '2026-03-19', '12:39 PM', 1),
(16, 10, 'Daily Maintenance', '2026-03-19', '12:39 PM', 1),
(17, 11, 'Daily Maintenance', '2026-03-19', '12:46 PM', 1),
(18, 11, 'Daily Maintenance', '2026-03-19', '12:46 PM', 1),
(19, 1, 'Daily Maintenance', '2026-03-20', '08:36 AM', 1),
(20, 1, 'Daily Maintenance', '2026-03-20', '08:37 AM', 1),
(21, 13, 'Daily Maintenance', '2026-03-20', '12:22 PM', 1),
(22, 13, 'Daily Maintenance', '2026-03-20', '12:22 PM', 1),
(23, 1, 'Daily Maintenance', '2026-03-20', '12:34 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `task_completions`
--

CREATE TABLE `task_completions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `completion_date` date NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(3) NOT NULL,
  `date_of_birth` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `age`, `date_of_birth`, `password`, `created_at`, `total_points`) VALUES
(1, 'anshul', 'anshul@gmail.com', '9852314670', 25, '2004-06-08', '$2y$10$JD/DbehWzt2CpCF8f1mNZ.5GeX0QKIiZs0AmR9jC.0Ly8RcRZTHle', '2026-02-24 04:12:54', 0),
(2, 'anshula', 'anshula@gmail.com', '9852314670', 25, '2004-06-08', '$2y$10$e5Q2PSGnUeM4IEC2N72z4edsw3Dh3cBDUlj7fe.dm7ThjdPb0/j9S', '2026-02-24 04:18:32', 0),
(3, 'vidhya', '123@gmail.com', '7534968120', 25, '2000-01-01', '$2y$10$wlCKeVWhFXDpTf5ilZrrBOJ/3LngLjtvsIvLgyl6Vd2ziwER.QXo.', '2026-02-25 02:55:45', 0),
(4, 'kaveri', 'kaveri@gmail.com', '9517328460', 30, '1996-01-01', '$2y$10$msgedZ0LT7jgUu0b1T6/5u2RdEThk/4bP3xk2to0O3qxNyAUP9S2W', '2026-02-25 04:01:51', 0),
(5, 'Navya', 'navya@gmail.com', '970548621', 22, '2004-02-25', '$2y$10$JMo9O/AorrIyKSje0hFVpuicFYTQ0kH1DlRFxI.jVHFNH9ttPR2hq', '2026-03-12 02:48:57', 0),
(6, 'keerthana', 'pendemkeerthanamar@gmail.com', '8247433184', 21, '2005-08-01', '$2y$10$Mp5p83YAW/bt07KQL6Vvx.NHcaKhEGSeExQtwA1L40A5IcmmDiney', '2026-03-12 02:57:53', 0),
(7, 'sai', 'sai@gmail.com', '9638052774', 20, '2005-12-20', '$2y$10$BpLIja2itqoGSjWuqjimb.r0G9CwLYsDp6VrExvSGUO17dZRm27uG', '2026-03-19 04:32:06', 0),
(8, 'pragna ', 'pragna@gmail.com', '9638052447', 25, '2000-05-10', '$2y$10$kXv65Qqsiiva2HqqFoYaX.OxaKwbXQdUrtT4ua5JDB4MGXEWtBgjy', '2026-03-19 06:39:43', 0),
(9, 'sri', 'sri@gmail.com', '9630857722', 20, '2005-03-20', '$2y$10$cRxhfB7Xj5hMcHnN6ojAPezRn01tYTupQjZjyXpPszeotribpmTDS', '2026-03-19 07:00:57', 0),
(10, 'hari', 'hari@gmail.com', '9680524790', 20, '2005-03-20', '$2y$10$WVKc1fR1d/yJAlJip88Z.eTwofEFTqESDJut3YCefcrqfaIlrNv7a', '2026-03-19 07:09:12', 0),
(11, 'sam', 'sam@gmail.com', '9804752369', 20, '2005-04-04', '$2y$10$B4AqVmBAoamRibhCWk9VOOKoZJMjCYLgNKEiVvIEWPm/tC5aevuwe', '2026-03-19 07:16:24', 0),
(12, 'test', '1@1.c', '94499494494984646465', 2147483647, '2026-03-11', '$2y$10$QHQRNb2GFWIIU5UpVVWBeO.suu1S.Z3u6OwVx0QfuClYkwm1bN8Q2', '2026-03-19 07:53:58', 0),
(13, 'see', 'see@gmail.com', '9666508247', 20, '2005-08-20', '$2y$10$ne16bFVn5QaN6eNiz/ZfV.Wyigpdf4gWq5VhY0YJwJ2UHnErefAMq', '2026-03-20 06:52:32', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(20) DEFAULT 'English',
  `font_size` varchar(20) DEFAULT 'Medium',
  `high_contrast` tinyint(1) DEFAULT 0,
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `reminder_sound` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tasks`
--

CREATE TABLE `user_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `is_completed` tinyint(4) DEFAULT 1,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tasks`
--

INSERT INTO `user_tasks` (`id`, `user_id`, `task_id`, `is_completed`, `date`) VALUES
(1, 7, 9, 1, '2026-03-19'),
(2, 7, 10, 1, '2026-03-19'),
(3, 7, 11, 1, '2026-03-19'),
(4, 7, 12, 1, '2026-03-19'),
(5, 1, 9, 1, '2026-03-19'),
(6, 1, 10, 1, '2026-03-19'),
(7, 1, 11, 1, '2026-03-19'),
(8, 1, 12, 1, '2026-03-19'),
(9, 8, 9, 1, '2026-03-19'),
(10, 8, 10, 1, '2026-03-19'),
(11, 8, 11, 1, '2026-03-19'),
(12, 8, 12, 1, '2026-03-19'),
(13, 9, 9, 0, '2026-03-19'),
(14, 9, 10, 0, '2026-03-19'),
(15, 9, 11, 0, '2026-03-19'),
(16, 9, 12, 0, '2026-03-19'),
(17, 10, 9, 1, '2026-03-19'),
(18, 10, 10, 1, '2026-03-19'),
(19, 10, 11, 1, '2026-03-19'),
(20, 10, 12, 1, '2026-03-19'),
(21, 11, 9, 1, '2026-03-19'),
(22, 11, 10, 1, '2026-03-19'),
(23, 11, 11, 1, '2026-03-19'),
(24, 11, 12, 1, '2026-03-19'),
(25, 1, 9, 0, '2026-03-20'),
(26, 1, 10, 0, '2026-03-20'),
(27, 1, 11, 0, '2026-03-20'),
(28, 1, 12, 0, '2026-03-20'),
(29, 13, 9, 1, '2026-03-20'),
(30, 13, 10, 1, '2026-03-20'),
(31, 13, 11, 1, '2026-03-20'),
(32, 13, 12, 1, '2026-03-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comorbidities`
--
ALTER TABLE `comorbidities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `compliance_scores`
--
ALTER TABLE `compliance_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_history`
--
ALTER TABLE `notification_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `privacy_consent`
--
ALTER TABLE `privacy_consent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `prosthesis`
--
ALTER TABLE `prosthesis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminder_history`
--
ALTER TABLE `reminder_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_completions`
--
ALTER TABLE `task_completions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comorbidities`
--
ALTER TABLE `comorbidities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `compliance_scores`
--
ALTER TABLE `compliance_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_history`
--
ALTER TABLE `notification_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `privacy_consent`
--
ALTER TABLE `privacy_consent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prosthesis`
--
ALTER TABLE `prosthesis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminder_history`
--
ALTER TABLE `reminder_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `task_completions`
--
ALTER TABLE `task_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tasks`
--
ALTER TABLE `user_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `comorbidities`
--
ALTER TABLE `comorbidities`
  ADD CONSTRAINT `comorbidities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `compliance_scores`
--
ALTER TABLE `compliance_scores`
  ADD CONSTRAINT `compliance_scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD CONSTRAINT `emergency_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notification_history`
--
ALTER TABLE `notification_history`
  ADD CONSTRAINT `notification_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `privacy_consent`
--
ALTER TABLE `privacy_consent`
  ADD CONSTRAINT `privacy_consent_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `prosthesis`
--
ALTER TABLE `prosthesis`
  ADD CONSTRAINT `prosthesis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reminder_history`
--
ALTER TABLE `reminder_history`
  ADD CONSTRAINT `reminder_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_completions`
--
ALTER TABLE `task_completions`
  ADD CONSTRAINT `task_completions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD CONSTRAINT `user_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_tasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `daily_tasks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
