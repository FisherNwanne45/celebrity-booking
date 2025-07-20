-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 09:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `celebrity_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$12$jn2zhUYkm5oZK4G5eQOLw.Eb9W31PXz/IBacgsB/1mOrqlu4bh9we', '2025-07-19 07:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `celebrity_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `event_date` date NOT NULL,
  `event_details` text NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `celebrity_id`, `user_name`, `user_email`, `user_phone`, `event_date`, `event_details`, `status`, `created_at`) VALUES
(1, 1, 'Jim Shalli', 'fisherfresh@outlook.com', '12052520525', '2025-07-23', 'fresh event, nor try am', 'pending', '2025-07-20 17:53:17'),
(2, 1, 'Jim Shalli', 'fisherfresh@outlook.com', '12052520525', '2025-07-23', 'fresh event, nor try am', 'confirmed', '2025-07-20 17:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `celebrities`
--

CREATE TABLE `celebrities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `profile` text NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `celebrities`
--

INSERT INTO `celebrities` (`id`, `name`, `category`, `description`, `profile`, `fee`, `picture`, `created_at`) VALUES
(1, 'Tom Cruise', 'Entertainment', 'American actor', 'Tom Cruise – Biography\r\n\r\nFull Name: Thomas Cruise Mapother IV\r\nBorn: July 3, 1962, Syracuse, New York, USA\r\nOccupation: Actor, Producer\r\nHeight: 5′7″ (1.70 m)\r\nYears Active: 1981–present\r\n\r\n\r\nTom Cruise is an American actor and producer renowned for his versatility, charisma, and dedication to high-intensity roles. With a career spanning over four decades, he is one of the highest-grossing box office stars in film history and one of Hollywood’s most iconic leading men.\r\n\r\nCruise rose to fame in the early 1980s with roles in *Risky Business* (1983) and *Top Gun* (1986), quickly becoming a global heartthrob. He solidified his reputation as a serious actor with critically acclaimed performances in films like *Rain Man* (1988), *Born on the Fourth of July* (1989), *A Few Good Men* (1992), and *Magnolia* (1999), earning multiple Academy Award nominations.\r\n\r\nHe is perhaps best known for his portrayal of **Ethan Hunt** in the long-running *Mission: Impossible* franchise, which he also produces. Known for doing many of his own stunts, Cruise is widely respected for his commitment to physically demanding and high-risk action sequences.\r\n\r\nOff-screen, Cruise has been a prominent and sometimes controversial figure due to his involvement with the Church of Scientology and high-profile relationships.\r\n\r\n\r\nNotable Films\r\n\r\nTop Gun (1986) & Top Gun: Maverick (2022)\r\nRain Man (1988)\r\nJerry Maguire (1996)\r\nMission: Impossible series (1996–2023)\r\nMinority Report (2002)\r\nEdge of Tomorrow (2014)\r\n\r\n\r\nAwards & Recognition\r\n\r\n* 3× Golden Globe Awards\r\n* 3× Academy Award nominations\r\n* AFI\'s Lifetime Achievement Award (2023, rumored consideration)\r\n* One of Time Magazine\'s \"100 Most Influential People\" (multiple times)\r\n', 1000.00, 'assets/uploads/687cb3fc2aa42_17-tomcruiseag.webp', '2025-07-19 07:44:13'),
(3, 'LeBron James', 'Sports', 'NBA superstar and philanthropist.', 'NBA superstar and philanthropist.', 500000.00, 'assets/uploads/687cb40d94b1b_LeBron_James_-_51959723161_(cropped).jpg', '2025-07-19 07:44:13'),
(5, 'Beyoncé', 'Music', 'Internationally renowned singer and performer.', 'Internationally renowned singer and performer.', 300000.00, 'assets/uploads/687d38c331a30_beyonce.webp', '2025-07-20 18:43:15'),
(6, 'Shallipopi', 'Music', 'Benin boy, laho', 'Shallipopi is an igbo smoker who came to earth from pluto. He nor get joy, the boy dey hot these days.', 23000.00, 'assets/uploads/687d3902d69f2_Shallipopi.jpg', '2025-07-20 18:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `details` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `phone`, `event_date`, `details`, `created_at`, `is_read`) VALUES
(3, 'Jim Shalli', 'fisherfresh@outlook.com', '12052520525', '0000-00-00', 'I want book wizkid and davido to perform together', '2025-07-20 18:24:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `instructions` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `details`, `instructions`, `is_active`, `created_at`) VALUES
(1, 'Bank Transfer', 'Account Name: Celebrity Booking\r\nAccount Number: 12345678\r\nBank: International Bank', 'Transfer the exact amount to the account above and email us the receipt. ', 1, '2025-07-19 07:44:13'),
(2, 'PayPal', 'paypal@celebritybooking.com', 'Send payment as Friends and Family to the email above.', 1, '2025-07-19 07:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'CelebBooking', '2025-07-20 15:24:01', '2025-07-20 15:35:18'),
(2, 'phone_number', '', '2025-07-20 15:24:01', '2025-07-20 15:39:21'),
(3, 'address', '', '2025-07-20 15:24:01', '2025-07-20 15:39:21'),
(4, 'email', 'info@celebritybooking.com', '2025-07-20 15:24:01', '2025-07-20 15:24:01'),
(5, 'facebook', 'https://facebook.com/', '2025-07-20 15:24:01', '2025-07-20 16:12:35'),
(6, 'twitter', 'https://twitter.com/', '2025-07-20 15:24:01', '2025-07-20 16:12:35'),
(7, 'instagram', 'https://instagram.com/', '2025-07-20 15:24:01', '2025-07-20 16:12:35'),
(8, 'linkedin', 'https://linkedin.com/company/', '2025-07-20 15:24:01', '2025-07-20 16:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `smtp_settings`
--

CREATE TABLE `smtp_settings` (
  `id` int(11) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `encryption` varchar(10) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smtp_settings`
--

INSERT INTO `smtp_settings` (`id`, `host`, `port`, `username`, `password`, `encryption`, `from_email`, `from_name`, `last_updated`) VALUES
(1, 'smtp.gmail.com', 465, 'barfumehouse@gmail.com', 'xsqk otfa owjj quqb', 'ssl', 'barfumehouse@gmail.com', 'Celebrity Booking System', '2025-07-20 17:51:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `celebrity_id` (`celebrity_id`);

--
-- Indexes for table `celebrities`
--
ALTER TABLE `celebrities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `celebrities`
--
ALTER TABLE `celebrities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`celebrity_id`) REFERENCES `celebrities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
