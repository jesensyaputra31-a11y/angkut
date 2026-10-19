-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2026 at 03:10 PM
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
-- Database: `angkut_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `report_id`, `officer_id`, `assigned_by`, `assigned_at`, `completed_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, '2026-10-18 22:49:44', '2026-10-18 22:57:54', 'hddd', '2026-10-18 22:49:44', '2026-10-18 22:57:54'),
(2, 3, 3, 2, '2026-10-19 00:57:47', '2026-10-19 00:58:44', 'kl;kdpfjsdigpjsp', '2026-10-19 00:57:47', '2026-10-19 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(100) DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sampah Menumpuk', 'Tumpukan sampah di lingkungan warga', 1, NULL, NULL),
(2, 'Pembuangan Sampah Liar', 'Pembuangan sampah di lokasi terlarang', 1, NULL, NULL),
(3, 'Sampah Tidak Diangkut', 'Sampah yang belum diangkut sesuai jadwal', 1, NULL, NULL),
(4, 'Sampah di Sungai', 'Sampah yang mencemari sungai atau saluran air', 1, NULL, NULL),
(5, 'Tempat Sampah Rusak', 'Tempat sampah umum yang rusak', 1, NULL, NULL),
(6, 'Lainnya', 'Masalah sampah lain yang perlu ditangani', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_code` varchar(30) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reporter_phone` varchar(20) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('submitted','verification','needs_information','queued','in_progress','resolved','rejected','out_of_scope','duplicate') NOT NULL DEFAULT 'submitted',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `duplicate_of_report_id` bigint(20) UNSIGNED DEFAULT NULL,
  `public_approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_code`, `user_id`, `reporter_phone`, `category_id`, `description`, `address`, `latitude`, `longitude`, `status`, `priority`, `duplicate_of_report_id`, `public_approved_at`, `created_at`, `updated_at`) VALUES
(1, 'ANG-20261019-9LHIPO', 2, NULL, 1, 'fjjpjpfos', 'jl merdeka', -2.9906870, 104.7527899, 'resolved', 'high', NULL, NULL, '2026-10-18 22:34:39', '2026-10-18 22:57:54'),
(2, 'ANG-20261019-SXOUXO', 4, NULL, 1, 'weokfjkjfk;fm;a', 'mlksdm;dkjf', -2.9871417, 104.7446116, 'submitted', 'medium', NULL, NULL, '2026-10-19 00:49:30', '2026-10-19 00:49:30'),
(3, 'ANG-20261019-O3HISU', 4, '0897654321', 1, 'kkajfkjpjfpg', 'flnwlke;f', -2.9839786, 104.7319340, 'resolved', 'high', NULL, NULL, '2026-10-19 00:55:31', '2026-10-19 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `report_photos`
--

CREATE TABLE `report_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `photo_type` enum('initial','after') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_photos`
--

INSERT INTO `report_photos` (`id`, `report_id`, `uploaded_by`, `photo_type`, `file_path`, `mime_type`, `file_size`, `created_at`) VALUES
(1, 1, 2, 'initial', 'reports/XAIJ9KVk8Tk8k7H5HXH8rGxMVLNAIt58wM9Sct85.png', 'image/png', 86319, NULL),
(2, 1, 3, 'after', 'reports/PJ3mAbXKFnWThU8juCrFcnJpSkCRwHe8YpLzqzL0.png', 'image/png', 1898893, NULL),
(3, 2, 4, 'initial', 'reports/d5KTAoovm0jGWnGwoS9ll2xmcj1jHVLcpUZ4yMLd.png', 'image/png', 225505, NULL),
(4, 3, 4, 'initial', 'reports/trSBcxzUQDEY9F0c45QHassF87NkrJs0JZChkyXO.jpg', 'image/png', 95983, NULL),
(5, 3, 3, 'after', 'reports/Y9UiNFzwLqBJc99SFwU9RfNpHyz0YbPBdm3jiNIr.jpg', 'image/png', 95983, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_updates`
--

CREATE TABLE `report_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(30) DEFAULT NULL,
  `new_status` varchar(30) NOT NULL,
  `note` text DEFAULT NULL,
  `visibility` enum('public','internal') NOT NULL DEFAULT 'public',
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_updates`
--

INSERT INTO `report_updates` (`id`, `report_id`, `old_status`, `new_status`, `note`, `visibility`, `updated_by`, `created_at`) VALUES
(1, 1, 'submitted', 'queued', 'Status diperbarui oleh Admin.', 'public', 2, '2026-10-18 22:49:34'),
(2, 1, 'queued', 'in_progress', 'Laporan telah ditugaskan ke petugas lapangan.', 'public', 2, '2026-10-18 22:49:44'),
(3, 1, 'in_progress', 'resolved', 'hddd', 'public', 3, '2026-10-18 22:57:54'),
(4, 3, 'submitted', 'queued', 'Status diperbarui oleh Admin.', 'public', 2, '2026-10-19 00:56:49'),
(5, 3, 'queued', 'in_progress', 'Laporan telah ditugaskan ke petugas lapangan.', 'public', 2, '2026-10-19 00:57:47'),
(6, 3, 'in_progress', 'resolved', 'kl;kdpfjsdigpjsp', 'public', 3, '2026-10-19 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('citizen','admin','officer') NOT NULL DEFAULT 'citizen',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `is_active`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'zam', 'email@gmail.com', '0897654321', '$2y$12$UeAYTCUJK8qgy5qHjlOXTubRc1J4hAlHkvruQSqRfmzRyGVYyTb4G', 'citizen', 1, NULL, NULL, '2026-10-18 22:06:24', '2026-10-18 22:06:24'),
(2, 'Admin DLH', 'admin@angkut.id', '081234567890', '$2y$12$he9CEegzlYlcj5Bs5K5rEu2/OFD8mJBd9qVdS8Gi45JsgonzZo1w6', 'admin', 1, NULL, NULL, '2026-10-18 22:14:27', '2026-10-18 22:14:27'),
(3, 'Petugas Lapangan 1', 'petugas@angkut.id', '089876543210', '$2y$12$imvL8YmPuQE1kItwRzTOFOH2tguNu7Kir9g3h/e9/RAo7BsUDy.ui', 'officer', 1, NULL, NULL, '2026-10-18 22:14:27', '2026-10-18 22:14:27'),
(4, 'zam', 'zam@gmail.com', '0897654321', '$2y$12$k0EU5hq8Ix9etlQExrQT8OLREX4nmu3JNq.KtJaf7R4It1I0vkwy6', 'citizen', 1, NULL, NULL, '2026-10-19 00:48:56', '2026-10-19 00:48:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `idx_assignments_officer` (`officer_id`),
  ADD KEY `idx_assignments_report` (`report_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_audit_user` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_code` (`report_code`),
  ADD KEY `fk_reports_user` (`user_id`),
  ADD KEY `fk_reports_duplicate` (`duplicate_of_report_id`),
  ADD KEY `idx_reports_status` (`status`),
  ADD KEY `idx_reports_category` (`category_id`),
  ADD KEY `idx_reports_location` (`latitude`,`longitude`),
  ADD KEY `idx_reports_created` (`created_at`);

--
-- Indexes for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_photos_report` (`report_id`);

--
-- Indexes for table `report_updates`
--
ALTER TABLE `report_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_updates_report_date` (`report_id`,`created_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sessions_user` (`user_id`),
  ADD KEY `idx_sessions_activity` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_photos`
--
ALTER TABLE `report_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_updates`
--
ALTER TABLE `report_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `assignments_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_reports_duplicate` FOREIGN KEY (`duplicate_of_report_id`) REFERENCES `reports` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD CONSTRAINT `report_photos_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_photos_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `report_updates`
--
ALTER TABLE `report_updates`
  ADD CONSTRAINT `report_updates_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_updates_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
