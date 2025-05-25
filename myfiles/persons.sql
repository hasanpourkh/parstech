-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 11:50 PM
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
-- Database: `parstech_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `share_percent` decimal(5,2) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `accounting_code` varchar(255) DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_list` varchar(255) DEFAULT NULL,
  `tax_type` varchar(255) DEFAULT NULL,
  `national_code` varchar(255) DEFAULT NULL,
  `economic_code` varchar(255) DEFAULT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `phone3` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `marriage_date` date DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `last_purchase_at` timestamp NULL DEFAULT NULL,
  `total_purchases` bigint(20) NOT NULL DEFAULT 0,
  `purchase_percent` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `full_name` varchar(255) GENERATED ALWAYS AS (concat(coalesce(`first_name`,''),' ',coalesce(`last_name`,''))) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`id`, `first_name`, `last_name`, `company_name`, `title`, `photo`, `type`, `share_percent`, `nickname`, `accounting_code`, `credit_limit`, `price_list`, `tax_type`, `national_code`, `economic_code`, `registration_number`, `branch_code`, `description`, `address`, `country`, `province`, `city`, `postal_code`, `phone`, `mobile`, `fax`, `phone1`, `phone2`, `phone3`, `email`, `website`, `birth_date`, `marriage_date`, `join_date`, `last_purchase_at`, `total_purchases`, `purchase_percent`, `created_at`, `updated_at`) VALUES
(1, 'مصطفی', 'عباس آبادی', NULL, NULL, NULL, 'customer', NULL, NULL, 'persons=-1001', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'یسبل', 'ایران', '11', '1256', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-05-21 07:53:21', '2025-05-21 07:53:21'),
(2, 'مشتری', 'حضوری', NULL, NULL, NULL, 'guest', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-05-22 14:41:15', '2025-05-22 14:41:15'),
(3, 'مصطفی', 'اکرادی', 'پارس تک', NULL, NULL, 'shareholder', NULL, NULL, 'persons=-1002', 0.00, NULL, NULL, '5730008971', NULL, NULL, NULL, NULL, 'نقاب خ ابوترابی', 'ایران', '11', '1256', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-05-22 19:01:26', '2025-05-22 19:01:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
