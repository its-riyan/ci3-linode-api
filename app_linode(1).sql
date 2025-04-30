-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 30, 2025 at 06:40 AM
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
-- Database: `app_linode`
--

-- --------------------------------------------------------

--
-- Table structure for table `this_instance`
--

CREATE TABLE `this_instance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `linode_instance_id` int(11) NOT NULL,
  `linode_instance_label` varchar(255) NOT NULL,
  `linode_instance_region` varchar(50) NOT NULL,
  `linode_instance_type` varchar(50) NOT NULL,
  `linode_instance_status` enum('running','offline','provisioning','shutting_down','rebooting','booting','unknown') NOT NULL,
  `linode_instance_pwd` varchar(255) NOT NULL,
  `linode_instance_price` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_next_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `this_instance`
--
ALTER TABLE `this_instance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id_instance` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `this_instance`
--
ALTER TABLE `this_instance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `this_instance`
--
ALTER TABLE `this_instance`
  ADD CONSTRAINT `this_instance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `this_user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
