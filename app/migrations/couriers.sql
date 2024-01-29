-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 25, 2024 at 06:36 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u991133494_kioretail`
--

-- --------------------------------------------------------

--
-- Table structure for table `sma_couriers`
--

DROP TABLE IF EXISTS `sma_couriers`;
CREATE TABLE IF NOT EXISTS `sma_couriers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `meta_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sma_couriers`
--

INSERT INTO `sma_couriers` (`id`, `name`, `phone_number`, `address`, `is_active`, `meta_data`, `created_at`, `updated_at`) VALUES
(1, 'Rede x', '6896868', 'Purana paltan', 0, NULL, '2023-07-23 11:04:19', '2023-12-08 18:26:48'),
(2, 'Call Courier', '123456789', 'lahore', 1, NULL, '2023-11-14 18:56:40', '2023-11-14 18:56:40'),
(3, 'Leopards Courier', '123456789', 'lahore', 1, NULL, '2023-11-14 18:56:40', '2023-11-14 18:56:40'),
(4, 'TCS', '123456789', 'lahore', 1, NULL, '2023-11-14 18:56:40', '2023-11-14 18:56:40'),
(5, 'PostEx', '123456789', 'lahore', 1, NULL, '2023-11-15 00:01:02', '2023-11-15 00:01:02'),
(6, 'Trax', '123456789', 'lahore', 1, NULL, '2023-11-16 00:01:14', '2023-11-16 00:01:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
