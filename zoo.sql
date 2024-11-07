-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 10:50 AM
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
-- Database: `zoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookedevents`
--

CREATE TABLE `bookedevents` (
  `booking_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookedevents`
--

INSERT INTO `bookedevents` (`booking_id`, `event_id`, `user_id`, `booking_date`) VALUES
(2, 11, 1, '2024-08-15 08:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `contactinquiries`
--

CREATE TABLE `contactinquiries` (
  `inquiry_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactinquiries`
--

INSERT INTO `contactinquiries` (`inquiry_id`, `user_id`, `name`, `email`, `message`, `submitted_at`) VALUES
(3, 1, 'User', 'user1@gmail.com', 'I want to be a Volunteer.', '2024-08-15 08:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `educationalcontent`
--

CREATE TABLE `educationalcontent` (
  `content_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educationalcontent`
--

INSERT INTO `educationalcontent` (`content_id`, `event_id`, `title`, `content`, `uploaded_by`, `upload_date`) VALUES
(10, 11, 'Sustainable Living for a Greener Planet', 'Learn practical tips for living sustainably, including reducing waste, conserving energy, and supporting eco-friendly products and practices that help protect the environment.', 1, '2024-08-15 07:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `description`, `event_date`, `start_time`, `end_time`, `location`, `image`, `created_by`, `created_at`) VALUES
(11, 'Earth Day Celebration', 'Celebrate Earth Day with activities and workshops focused on sustainability and protecting our planet. Fun for the whole family!', '2025-04-22', '10:00:00', '16:00:00', 'ZooParc Central Lawn', 'images/8.jpeg', 6, '2024-08-15 07:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `foodoutlets`
--

CREATE TABLE `foodoutlets` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foodoutlets`
--

INSERT INTO `foodoutlets` (`food_id`, `food_name`, `description`, `price`, `image`, `created_at`) VALUES
(3, 'Classic Cheeseburger', 'A juicy beef patty topped with melted cheddar cheese, fresh lettuce, tomato, pickles, and a special sauce, served on a toasted sesame seed bun.\r\n', 8.99, 'images/Classic Cheeseburger.png', '2024-08-15 07:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','volunteer','visitor') DEFAULT 'visitor',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `registration_date`) VALUES
(1, 'user', 'user@gmail.com', '$2y$10$UVgEKnsR0.Cnaobaco.scueagpMyn5i9wa8Cmr/OQ6V4cHD9RnLmu', 'visitor', '2024-08-14 11:55:23'),
(6, 'vl', 'vl@gmail.com', '$2y$10$bPw2xc5Uu/HB.FAzlHaWAuDJA1//NOgAsQMH3ZWebWy0wZjn.LbLi', 'volunteer', '2024-08-14 13:15:09'),
(7, 'admin', 'admin@gmail.com', '$2y$10$9Jrn4bGME5UyivnLMh9FTe6ifzYJp71RB1gkvBmh16dXI7louIwt2', 'admin', '2024-08-14 13:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `volunteer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `areas_of_interest` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`volunteer_id`, `name`, `email`, `password`, `contact_number`, `areas_of_interest`, `registration_date`) VALUES
(4, 'user12', 'user12@gmail.com', '$2y$10$60JTMEHgsMX19kkwFXB6XulJ0RO.SPDHQG4.cfcm6Qm4HFSIG1PVO', '0778458956', 'Event manage', '2024-08-15 08:04:52'),
(5, 'user13', 'user13@gmail.com', '$2y$10$L6euuHuw2y2KD/ya6MInquPEY/BKB5x7dRisW8wI2LXs2uE1B6KPq', '0768957452', 'event manage', '2024-08-15 08:05:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookedevents`
--
ALTER TABLE `bookedevents`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contactinquiries`
--
ALTER TABLE `contactinquiries`
  ADD PRIMARY KEY (`inquiry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `educationalcontent`
--
ALTER TABLE `educationalcontent`
  ADD PRIMARY KEY (`content_id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `foodoutlets`
--
ALTER TABLE `foodoutlets`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`volunteer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookedevents`
--
ALTER TABLE `bookedevents`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contactinquiries`
--
ALTER TABLE `contactinquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `educationalcontent`
--
ALTER TABLE `educationalcontent`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `foodoutlets`
--
ALTER TABLE `foodoutlets`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookedevents`
--
ALTER TABLE `bookedevents`
  ADD CONSTRAINT `bookedevents_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `bookedevents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `contactinquiries`
--
ALTER TABLE `contactinquiries`
  ADD CONSTRAINT `contactinquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `educationalcontent`
--
ALTER TABLE `educationalcontent`
  ADD CONSTRAINT `educationalcontent_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `educationalcontent_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
