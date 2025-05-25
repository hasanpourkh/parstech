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
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `cash_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cash_reference` varchar(255) DEFAULT NULL,
  `cash_paid_at` timestamp NULL DEFAULT NULL,
  `card_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `card_reference` varchar(255) DEFAULT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `card_paid_at` timestamp NULL DEFAULT NULL,
  `pos_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pos_reference` varchar(255) DEFAULT NULL,
  `pos_paid_at` timestamp NULL DEFAULT NULL,
  `cheque_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cheque_number` varchar(255) DEFAULT NULL,
  `cheque_bank` varchar(255) DEFAULT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `reference`, `customer_id`, `seller_id`, `currency_id`, `total_price`, `total_amount`, `discount`, `tax`, `final_amount`, `paid_amount`, `remaining_amount`, `status`, `paid_at`, `payment_status`, `cash_amount`, `cash_reference`, `cash_paid_at`, `card_amount`, `card_reference`, `card_number`, `card_paid_at`, `pos_amount`, `pos_reference`, `pos_paid_at`, `cheque_amount`, `cheque_number`, `cheque_bank`, `cheque_due_date`, `description`, `created_at`, `updated_at`, `deleted_at`, `title`, `issued_at`, `payment_method`, `payment_notes`) VALUES
(1, 'invoices-10001', NULL, 1, 1, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-21 08:45:39', '2025-05-21 08:45:39', NULL, 'قبف', '2025-05-21 08:45:39', NULL, NULL),
(6, 'invoices-10002', NULL, 1, 1, 1, 150000.00, 0.00, 0.00, 0.00, 150000.00, 150000.00, 0.00, 'paid', '2025-05-22 19:58:13', 'unpaid', 140000.00, NULL, '2025-05-22 19:58:13', 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 11:04:14', '2025-05-22 19:58:13', NULL, NULL, '2025-05-22 11:04:14', 'cash', NULL),
(7, 'invoices-10003', NULL, 2, 1, 1, 800000.00, 0.00, 0.00, 0.00, 800000.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 14:41:15', '2025-05-22 14:41:15', NULL, NULL, '2025-05-22 14:41:15', NULL, NULL),
(8, 'invoices-10004', NULL, 2, 1, 1, 400000.00, 0.00, 0.00, 0.00, 400000.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 14:42:33', '2025-05-22 14:42:33', NULL, NULL, '2025-05-22 14:42:33', NULL, NULL),
(9, 'invoices-10005', NULL, 2, 1, 1, 150000.00, 0.00, 0.00, 0.00, 150000.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 14:43:02', '2025-05-22 14:43:02', NULL, NULL, '2025-05-22 14:43:02', NULL, NULL),
(10, 'invoices-10006', NULL, 1, 1, 1, 201500.00, 0.00, 0.00, 0.00, 201500.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 15:54:19', '2025-05-22 15:54:19', NULL, NULL, '2025-05-22 15:54:19', NULL, NULL),
(11, 'invoices-10007', NULL, 2, 1, 1, 201500.00, 0.00, 0.00, 0.00, 201500.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-22 15:59:25', '2025-05-22 15:59:25', NULL, NULL, '2025-05-22 15:59:25', NULL, NULL),
(12, 'invoices-10008', NULL, 1, 1, 1, 801500.00, 0.00, 0.00, 0.00, 801500.00, 801500.00, 0.00, 'paid', '2025-05-23 16:36:34', 'unpaid', 801500.00, NULL, '2025-05-23 16:36:34', 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-23 16:36:00', '2025-05-23 16:36:34', NULL, NULL, '2025-05-23 16:36:00', 'cash', NULL),
(17, 'invoices-10009', NULL, 1, 1, 1, 200000.00, 0.00, 0.00, 0.00, 200000.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-25 16:45:00', '2025-05-25 16:45:00', NULL, NULL, '2025-05-25 16:45:00', NULL, NULL),
(18, 'invoices-10010', '15', 1, 1, 1, 401500.00, 0.00, 0.00, 0.00, 401500.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-25 16:47:37', '2025-05-25 16:47:37', NULL, 'ثقف', '2025-05-25 16:47:37', NULL, NULL),
(19, 'invoices-10011', NULL, 2, 1, 1, 201500.00, 0.00, 0.00, 0.00, 201500.00, 0.00, 0.00, 'pending', NULL, 'unpaid', 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, '2025-05-25 16:53:12', '2025-05-25 16:53:12', NULL, 'فروش سریع', '2025-05-25 16:53:12', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_seller_id_foreign` (`seller_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  ADD CONSTRAINT `sales_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
