-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 12:02 PM
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
-- Database: `db_cafe_premium`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'kasir',
  `shift` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `shift`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Owner Cafe', 'admin@gmail.com', 'admin', NULL, NULL, '$2y$12$weRMwvbxSahFXdBsFduCl.nMGts3XKvyPMSA4ta6lDFuKArT3qxDe', NULL, '2026-01-07 12:28:10', '2026-01-07 12:28:10'),
(2, 'andi Pagi', 'andi@gmail.com', 'kasir', 'pagi', NULL, '$2y$12$iyrKpphtwV0C2BheuBJmkuvwA1D/YvGsg1WcomFMEUJZowb68cMte', NULL, '2026-01-07 12:28:10', '2026-01-07 12:28:10'),
(3, 'Siti Admin', 'siti@gmail.com', 'admin', NULL, NULL, '$2y$12$06tySNuHWtsZnUJFM3sADOULzknf8dmGFzXjy4cKeDY0Gu0wT7hSi', NULL, '2026-01-07 12:28:11', '2026-01-07 12:28:11'),
(5, 'varo', 'kasir@gmail.com', 'admin', 'pagi', NULL, '$2y$12$dADpwsQvXVj/PcUBa7d2Fu3Gbnzj18KzKSGF71KTrhaiEoIosNT3C', NULL, '2026-01-08 10:36:44', '2026-01-08 10:36:44'),
(6, 'merlyn', 'merlyn@gmail.com', 'admin', 'sore', NULL, '$2y$12$uQ7KWRkYAlDAQdbPi/xx1ewwxCrOG.AaMXMuY4PYNPsYBQVc2ga0u', NULL, '2026-01-10 03:51:57', '2026-01-10 03:51:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
