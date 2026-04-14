-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 01:07 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tubesiaev2`
--

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('asset','liability','equity','revenue','cos','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `account_code`, `account_name`, `account_type`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '110101', 'Petty Cash HO', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(2, '110102', 'Petty Cash Outlet', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(3, '110201', 'Bank 1', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(4, '110205', 'BCA Cake Zero', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(5, '110501', 'Raw Material Inventory', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(6, '110504', 'Finished Goods', 'asset', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(7, '210101', 'Account Payable Purchase', 'liability', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(8, '210301', 'Salary Payable', 'liability', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(9, '210208', 'PPN Payable', 'liability', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(10, '310101', 'Share Capital', 'equity', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(11, '310202', 'Retained Earnings Current Period', 'equity', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(12, '410101', 'Sales Food', 'revenue', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(13, '410102', 'Sales Beverage', 'revenue', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(14, '510101', 'COGS Food', 'cos', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(15, '510102', 'COGS Beverage', 'cos', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(16, '610201', 'Salary Expense', 'expense', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(17, '610302', 'Utilities Expense', 'expense', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(18, '610504', 'Technology Expense', 'expense', NULL, 1, '2026-03-13 09:08:56', '2026-03-13 09:08:56'),
(19, '110206', 'Account Receivable', 'asset', NULL, 1, '2026-03-13 16:34:53', '2026-03-13 16:34:53'),
(20, '210102', 'Account Payable Suspense', 'liability', NULL, 1, '2026-03-13 16:49:06', '2026-03-13 16:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `document_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submittal_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `submittal_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `letter_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nk_letter_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nk_letter_date` date DEFAULT NULL,
  `status` enum('WAITING','AEN','A','RCR','VOID') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WAITING',
  `submitted_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `purchase_id`, `location`, `receipt_date`, `created_at`, `updated_at`) VALUES
(4, 1, 'Gudang Center', '2026-03-13', '2026-03-13 16:31:16', '2026-03-13 16:31:16'),
(5, 4, 'Gudang Center', '2026-03-14', '2026-03-13 17:00:31', '2026-03-13 17:00:31'),
(6, 5, 'Gudang Center', '2026-03-14', '2026-03-13 21:45:14', '2026-03-13 21:45:14'),
(7, 6, 'Gudang Center', '2026-03-14', '2026-03-14 01:38:28', '2026-03-14 01:38:28'),
(8, 9, 'Gudang Center', '2026-04-01', '2026-03-31 05:33:39', '2026-03-31 05:33:39'),
(9, 10, 'Gudang Center', '2026-04-01', '2026-04-01 05:48:50', '2026-04-01 05:48:50'),
(10, 12, 'Jl. in aja dulu', '2026-04-13', '2026-04-14 01:16:48', '2026-04-14 01:16:48'),
(11, 13, 'Gudang Center', '2026-04-14', '2026-04-14 01:27:34', '2026-04-14 01:27:34'),
(12, 15, 'Gudang Center', '2026-04-14', '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(13, 8, 'Gudang Center', '2026-04-14', '2026-04-14 05:06:28', '2026-04-14 05:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` bigint UNSIGNED NOT NULL,
  `goods_receipt_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty_received` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipt_items`
--

INSERT INTO `goods_receipt_items` (`id`, `goods_receipt_id`, `product_id`, `qty_received`, `created_at`, `updated_at`) VALUES
(4, 4, 1, 10, '2026-03-13 16:31:16', '2026-03-13 16:31:16'),
(5, 5, 1, 10, '2026-03-13 17:00:31', '2026-03-13 17:00:31'),
(6, 6, 1, 10, '2026-03-13 21:45:14', '2026-03-13 21:45:14'),
(7, 7, 1, 15, '2026-03-14 01:38:28', '2026-03-14 01:38:28'),
(8, 8, 1, 15, '2026-03-31 05:33:39', '2026-03-31 05:33:39'),
(9, 9, 1, 7, '2026-04-01 05:48:50', '2026-04-01 05:48:50'),
(10, 9, 2, 10, '2026-04-01 05:48:50', '2026-04-01 05:48:50'),
(11, 10, 1, 2, '2026-04-14 01:16:48', '2026-04-14 01:16:48'),
(12, 11, 1, 3, '2026-04-14 01:27:34', '2026-04-14 01:27:34'),
(13, 11, 2, 2, '2026-04-14 01:27:34', '2026-04-14 01:27:34'),
(14, 12, 1, 5, '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(15, 12, 2, 3, '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(16, 12, 3, 1, '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(17, 13, 1, 20, '2026-04-14 05:06:28', '2026-04-14 05:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `purchase_id`, `invoice_date`, `total_amount`, `status`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, '2026-03-14', 100000.00, 'paid', NULL, NULL, '2026-03-13 17:01:00', '2026-03-13 17:01:00'),
(2, 5, '2026-03-14', 100000.00, 'paid', NULL, NULL, '2026-03-13 21:45:51', '2026-03-13 21:45:51'),
(3, 6, '2026-03-14', 150000.00, 'unpaid', NULL, NULL, '2026-03-14 01:38:38', '2026-03-14 01:38:38'),
(4, 9, '2026-04-02', 150000.00, 'paid', NULL, NULL, '2026-03-31 05:35:01', '2026-03-31 05:35:01'),
(5, 10, '2026-04-01', 170000.00, 'paid', NULL, NULL, '2026-04-01 05:49:54', '2026-04-01 05:49:54'),
(6, 11, '2026-04-14', 100000.00, 'paid', NULL, NULL, '2026-04-13 21:15:47', '2026-04-13 21:15:47'),
(7, 13, '2026-04-14', 50000.00, 'unpaid', NULL, NULL, '2026-04-14 01:17:46', '2026-04-14 01:17:46'),
(8, 14, '2026-04-14', 118000.00, 'unpaid', NULL, NULL, '2026-04-14 01:23:47', '2026-04-14 01:23:47'),
(9, 1, '2026-04-14', 90000.00, 'unpaid', NULL, NULL, '2026-04-14 01:30:51', '2026-04-14 01:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `journal_date` date NOT NULL,
  `reference_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `journal_date`, `reference_code`, `transaction_type`, `description`, `branch_id`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, '2026-03-13', 'GR-4', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-03-13 16:31:16', '2026-03-13 16:31:16', NULL),
(5, '2026-03-13', 'PO-3', 'Purchase', 'Purchase from PT Abadi Jaya', NULL, NULL, '2026-03-13 16:46:01', '2026-03-13 16:46:01', NULL),
(6, '2026-03-14', 'GR-5', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-03-13 17:00:31', '2026-03-13 17:00:31', NULL),
(7, '2026-03-14', 'GR-6', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-03-13 21:45:14', '2026-03-13 21:45:14', NULL),
(8, '2026-03-14', 'GR-7', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-03-14 01:38:28', '2026-03-14 01:38:28', NULL),
(9, '2026-04-01', 'GR-8', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-03-31 05:33:39', '2026-03-31 05:33:39', NULL),
(10, '2026-04-01', 'GR-9', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-04-01 05:48:51', '2026-04-01 05:48:51', NULL),
(11, '2026-04-13', 'GR-10', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-04-14 01:16:49', '2026-04-14 01:16:49', NULL),
(12, '2026-04-14', 'GR-11', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-04-14 01:27:34', '2026-04-14 01:27:34', NULL),
(13, '2026-04-14', 'GR-12', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-04-14 05:05:58', '2026-04-14 05:05:58', NULL),
(14, '2026-04-14', 'GR-13', 'Goods Receipt', 'Inventory receipt from purchase', NULL, NULL, '2026-04-14 05:06:28', '2026-04-14 05:06:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_details`
--

CREATE TABLE `journal_entry_details` (
  `id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entry_details`
--

INSERT INTO `journal_entry_details` (`id`, `journal_entry_id`, `account_id`, `description`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 'Inventory Increase', 100000.00, 0.00, '2026-03-13 16:31:16', '2026-03-13 16:31:16'),
(2, 4, 7, 'Purchase Liability', 0.00, 100000.00, '2026-03-13 16:31:16', '2026-03-13 16:31:16'),
(3, 5, 5, 'Inventory Purchase', 100000.00, 0.00, '2026-03-13 16:46:01', '2026-03-13 16:46:01'),
(4, 5, 3, 'Bank 1', 0.00, 100000.00, '2026-03-13 16:46:01', '2026-03-13 16:46:01'),
(5, 6, 5, 'Inventory Increase', 100000.00, 0.00, '2026-03-13 17:00:31', '2026-03-13 17:00:31'),
(6, 6, 7, 'Purchase Liability', 0.00, 100000.00, '2026-03-13 17:00:31', '2026-03-13 17:00:31'),
(7, 7, 5, 'Inventory Increase', 100000.00, 0.00, '2026-03-13 21:45:14', '2026-03-13 21:45:14'),
(8, 7, 7, 'Purchase Liability', 0.00, 100000.00, '2026-03-13 21:45:14', '2026-03-13 21:45:14'),
(9, 8, 5, 'Inventory Increase', 150000.00, 0.00, '2026-03-14 01:38:28', '2026-03-14 01:38:28'),
(10, 8, 7, 'Purchase Liability', 0.00, 150000.00, '2026-03-14 01:38:28', '2026-03-14 01:38:28'),
(11, 9, 5, 'Inventory Increase', 150000.00, 0.00, '2026-03-31 05:33:39', '2026-03-31 05:33:39'),
(12, 9, 7, 'Purchase Liability', 0.00, 150000.00, '2026-03-31 05:33:39', '2026-03-31 05:33:39'),
(13, 10, 5, 'Inventory Increase', 170000.00, 0.00, '2026-04-01 05:48:51', '2026-04-01 05:48:51'),
(14, 10, 7, 'Purchase Liability', 0.00, 170000.00, '2026-04-01 05:48:51', '2026-04-01 05:48:51'),
(15, 11, 5, 'Inventory Increase', 20000.00, 0.00, '2026-04-14 01:16:49', '2026-04-14 01:16:49'),
(16, 11, 7, 'Purchase Liability', 0.00, 20000.00, '2026-04-14 01:16:49', '2026-04-14 01:16:49'),
(17, 12, 5, 'Inventory Increase', 50000.00, 0.00, '2026-04-14 01:27:34', '2026-04-14 01:27:34'),
(18, 12, 7, 'Purchase Liability', 0.00, 50000.00, '2026-04-14 01:27:34', '2026-04-14 01:27:34'),
(19, 13, 5, 'Inventory Increase', 180000.00, 0.00, '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(20, 13, 7, 'Purchase Liability', 0.00, 180000.00, '2026-04-14 05:05:58', '2026-04-14 05:05:58'),
(21, 14, 5, 'Inventory Increase', 200000.00, 0.00, '2026-04-14 05:06:28', '2026-04-14 05:06:28'),
(22, 14, 7, 'Purchase Liability', 0.00, 200000.00, '2026-04-14 05:06:28', '2026-04-14 05:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `master_systems`
--

CREATE TABLE `master_systems` (
  `equipment_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_03_11_064211_create_products_table', 1),
(6, '2026_03_11_064326_create_suppliers_table', 1),
(7, '2026_03_11_064344_create_purchases_table', 1),
(8, '2026_03_11_064354_create_purchase_items_table', 1),
(9, '2026_03_11_064410_create_sales_table', 1),
(10, '2026_03_11_064420_create_sale_items_table', 1),
(11, '2026_03_11_064430_create_goods_receipts_table', 1),
(12, '2026_03_11_064435_create_goods_receipt_items_table', 1),
(13, '2026_03_11_064440_create_invoices_table', 1),
(14, '2026_03_12_000000_add_invoice_date_to_invoices_table', 1),
(15, '2026_03_15_000001_create_chart_of_accounts_table', 1),
(16, '2026_03_15_000002_create_journal_entries_table', 1),
(17, '2026_03_15_000003_create_journal_entry_details_table', 1),
(18, '2024_12_01_000000_create_master_systems_table', 2),
(19, '2024_12_01_000001_create_documents_table', 2),
(20, '2026_04_14_054002_create_simple_purchases_table', 3),
(21, '2026_04_14_054012_create_simple_purchase_items_table', 3),
(22, '2026_04_15_000000_fix_simple_purchases_enums', 4),
(23, '2026_04_14_032140_add_midtrans_order_id_to_simple_purchases_table', 5),
(24, '2026_04_16_000000_add_payment_status_to_sales_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `stock`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Cabai Merah', '25435', 51, 12000.00, '2026-03-13 09:10:12', '2026-04-14 05:39:04'),
(2, 'Bawang Merah - KG', '54684', 31, 10000.00, '2026-04-01 05:47:21', '2026-04-14 05:05:58'),
(3, 'Cabai Rawit', '20001', 10, 90000.00, '2026-04-14 01:58:18', '2026-04-14 05:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_date` date NOT NULL,
  `required_date` date NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `branch`, `document_date`, `required_date`, `supplier_id`, `payment_method`, `total`, `created_at`, `updated_at`) VALUES
(1, 'Pesta Kebun', '2026-03-13', '2026-03-14', 1, 'cash', 100000.00, '2026-03-13 09:10:41', '2026-03-13 09:10:41'),
(3, 'Pesta Kebun', '2026-03-13', '2026-03-14', 1, 'cash', 100000.00, '2026-03-13 16:46:01', '2026-03-13 16:46:01'),
(4, 'Pesta Kebun', '2026-03-13', '2026-03-14', 1, 'cash', 100000.00, '2026-03-13 16:59:51', '2026-03-13 16:59:51'),
(5, 'Pesta Kebun', '2026-03-14', '2026-03-14', 1, 'cash', 100000.00, '2026-03-13 21:45:01', '2026-03-13 21:45:01'),
(6, 'Pesta Kebun', '2026-03-14', '2026-03-14', 1, 'cash', 150000.00, '2026-03-14 01:38:17', '2026-03-14 01:38:17'),
(7, 'Kopi Sianida', '2026-03-14', '2026-03-14', 1, 'cash', 100000.00, '2026-03-14 06:06:11', '2026-03-14 06:06:11'),
(8, 'Kopi Sianida', '2026-03-27', '2026-03-28', 1, 'cash', 200000.00, '2026-03-27 09:29:50', '2026-03-27 09:29:50'),
(9, 'Kopi Sianida', '2026-03-31', '2026-04-01', 2, 'cash', 150000.00, '2026-03-31 05:32:59', '2026-03-31 05:33:00'),
(10, 'Kopi Sianida', '2026-04-01', '2026-04-02', 3, 'cash', 170000.00, '2026-04-01 05:48:14', '2026-04-01 05:48:14'),
(11, 'Kopi Sianida', '2026-04-14', '2026-04-15', 4, 'cash', 100000.00, '2026-04-13 21:12:19', '2026-04-13 21:12:19'),
(12, 'PT Dale ganteng', '2026-04-14', '2026-04-15', 4, 'cash', 20000.00, '2026-04-14 01:12:37', '2026-04-14 01:12:37'),
(13, 'PT pt an', '2026-04-14', '2026-04-15', 1, 'credit', 50000.00, '2026-04-14 01:17:46', '2026-04-14 01:17:46'),
(14, 'PT pt anjay', '2026-04-14', '2026-04-15', 3, 'credit', 118000.00, '2026-04-14 01:23:47', '2026-04-14 01:23:47'),
(15, 'PT pt anjay', '2026-04-14', '2026-04-15', 2, 'cash', 180000.00, '2026-04-14 05:05:36', '2026-04-14 05:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `qty`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 10000.00, '2026-03-13 09:10:41', '2026-03-13 09:10:41'),
(3, 3, 1, 10, 10000.00, '2026-03-13 16:46:01', '2026-03-13 16:46:01'),
(4, 4, 1, 10, 10000.00, '2026-03-13 16:59:51', '2026-03-13 16:59:51'),
(5, 5, 1, 10, 10000.00, '2026-03-13 21:45:01', '2026-03-13 21:45:01'),
(6, 6, 1, 15, 10000.00, '2026-03-14 01:38:17', '2026-03-14 01:38:17'),
(7, 7, 1, 10, 10000.00, '2026-03-14 06:06:11', '2026-03-14 06:06:11'),
(8, 8, 1, 20, 10000.00, '2026-03-27 09:29:50', '2026-03-27 09:29:50'),
(9, 9, 1, 15, 10000.00, '2026-03-31 05:33:00', '2026-03-31 05:33:00'),
(10, 10, 2, 10, 10000.00, '2026-04-01 05:48:14', '2026-04-01 05:48:14'),
(11, 10, 1, 7, 10000.00, '2026-04-01 05:48:14', '2026-04-01 05:48:14'),
(12, 11, 1, 10, 10000.00, '2026-04-13 21:12:19', '2026-04-13 21:12:19'),
(13, 12, 1, 2, 10000.00, '2026-04-14 01:12:37', '2026-04-14 01:12:37'),
(14, 13, 2, 2, 10000.00, '2026-04-14 01:17:46', '2026-04-14 01:17:46'),
(15, 13, 1, 3, 10000.00, '2026-04-14 01:17:46', '2026-04-14 01:17:46'),
(16, 14, 2, 7, 10000.00, '2026-04-14 01:23:47', '2026-04-14 01:23:47'),
(17, 14, 1, 4, 12000.00, '2026-04-14 01:23:47', '2026-04-14 01:23:47'),
(18, 15, 1, 5, 12000.00, '2026-04-14 05:05:36', '2026-04-14 05:05:36'),
(19, 15, 3, 1, 90000.00, '2026-04-14 05:05:36', '2026-04-14 05:05:36'),
(20, 15, 2, 3, 10000.00, '2026-04-14 05:05:36', '2026-04-14 05:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sales_date` date NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('UNPAID','PAID') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNPAID',
  `midtrans_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `branch`, `sales_date`, `customer_name`, `total`, `payment_status`, `midtrans_order_id`, `created_at`, `updated_at`) VALUES
(1, 'Pesta Kebun', '2026-03-14', 'Dale', 250000.00, 'UNPAID', NULL, '2026-03-13 21:46:44', '2026-03-13 21:46:44'),
(2, 'Kopi Sianida', '2026-04-03', 'Dale', 140000.00, 'UNPAID', NULL, '2026-03-31 05:35:51', '2026-03-31 05:35:51'),
(3, 'Kopi Sianida', '2026-04-01', 'Dale', 50000.00, 'UNPAID', NULL, '2026-04-01 05:50:23', '2026-04-01 05:50:23'),
(4, 'Tebet', '2026-04-14', 'Mr. mpruyy', 316000.00, 'PAID', 'SO-00004-1776171273', '2026-04-14 02:03:27', '2026-04-14 05:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `qty`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 25, 10000.00, 250000.00, '2026-03-13 21:46:44', '2026-03-13 21:46:44'),
(2, 2, 1, 10, 14000.00, 140000.00, '2026-03-31 05:35:51', '2026-03-31 05:35:51'),
(3, 3, 1, 5, 10000.00, 50000.00, '2026-04-01 05:50:23', '2026-04-01 05:50:23'),
(4, 4, 2, 10, 10000.00, 100000.00, '2026-04-14 02:03:27', '2026-04-14 02:03:27'),
(5, 4, 1, 18, 12000.00, 216000.00, '2026-04-14 02:03:27', '2026-04-14 02:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `simple_purchases`
--

CREATE TABLE `simple_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('UNPAID','PAID') COLLATE utf8mb4_unicode_ci DEFAULT 'PAID',
  `midtrans_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_status` enum('NOT_RECEIVED','PARTIAL','RECEIVED') COLLATE utf8mb4_unicode_ci DEFAULT 'NOT_RECEIVED',
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `simple_purchases`
--

INSERT INTO `simple_purchases` (`id`, `supplier_id`, `date`, `payment_method`, `payment_status`, `midtrans_order_id`, `receipt_status`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-04-14', 'cash', 'PAID', NULL, 'PARTIAL', 10000.00, '2026-04-13 22:52:56', '2026-04-13 23:33:55'),
(2, 2, '2026-04-14', 'cash', 'PAID', NULL, 'PARTIAL', 100000.00, '2026-04-13 23:34:30', '2026-04-13 23:34:43'),
(3, 2, '2026-04-14', 'qris', 'PAID', NULL, 'PARTIAL', 100000.00, '2026-04-13 23:47:10', '2026-04-13 23:50:42'),
(4, 1, '2026-04-14', 'qris', 'PAID', NULL, 'NOT_RECEIVED', 100000.00, '2026-04-14 00:10:38', '2026-04-14 00:11:10'),
(5, 3, '2026-04-14', 'qris', 'PAID', NULL, 'RECEIVED', 100000.00, '2026-04-14 00:11:47', '2026-04-14 00:54:44'),
(6, 3, '2026-04-14', 'qris', 'PAID', NULL, 'RECEIVED', 100000.00, '2026-04-14 00:38:54', '2026-04-14 01:53:13'),
(7, 2, '2026-04-14', 'qris', 'PAID', NULL, 'RECEIVED', 150000.00, '2026-04-14 00:58:39', '2026-04-14 05:39:04'),
(8, 3, '2026-04-14', 'qris', 'PAID', NULL, 'NOT_RECEIVED', 10000.00, '2026-04-13 18:16:32', '2026-04-13 21:11:28'),
(9, 1, '2026-04-14', 'qris', 'UNPAID', 'SP-00009-1776154167', 'NOT_RECEIVED', 150000.00, '2026-04-13 21:12:19', '2026-04-14 01:09:27'),
(10, 3, '2026-04-14', 'cash', 'PAID', 'SP-00010-1776165573', 'RECEIVED', 100000.00, '2026-04-14 01:35:27', '2026-04-14 04:21:12'),
(11, 1, '2026-04-14', 'transfer', 'PAID', 'SP-00011-1776165766', 'RECEIVED', 56000.00, '2026-04-14 04:22:38', '2026-04-14 04:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `simple_purchase_items`
--

CREATE TABLE `simple_purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `simple_purchase_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty_order` int NOT NULL,
  `qty_received` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `simple_purchase_items`
--

INSERT INTO `simple_purchase_items` (`id`, `simple_purchase_id`, `product_id`, `qty_order`, `qty_received`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 10000.00, 10000.00, '2026-04-13 22:52:56', '2026-04-13 22:52:56'),
(2, 2, 1, 10, 0, 10000.00, 100000.00, '2026-04-13 23:34:30', '2026-04-13 23:34:30'),
(3, 3, 2, 10, 0, 10000.00, 100000.00, '2026-04-13 23:47:10', '2026-04-13 23:47:10'),
(4, 4, 2, 10, 0, 10000.00, 100000.00, '2026-04-14 00:10:38', '2026-04-14 00:10:38'),
(5, 5, 2, 10, 10, 10000.00, 100000.00, '2026-04-14 00:11:47', '2026-04-14 00:54:44'),
(6, 6, 2, 10, 10, 10000.00, 100000.00, '2026-04-14 00:38:54', '2026-04-14 01:53:13'),
(7, 7, 1, 15, 15, 10000.00, 150000.00, '2026-04-14 00:58:39', '2026-04-14 05:39:04'),
(8, 8, 1, 1, 0, 10000.00, 10000.00, '2026-04-13 18:16:32', '2026-04-13 18:16:32'),
(9, 9, 1, 15, 0, 10000.00, 150000.00, '2026-04-13 21:12:19', '2026-04-13 21:12:19'),
(10, 10, 1, 5, 5, 12000.00, 60000.00, '2026-04-14 01:35:27', '2026-04-14 01:43:43'),
(11, 10, 2, 4, 4, 10000.00, 40000.00, '2026-04-14 01:35:27', '2026-04-14 01:43:43'),
(12, 11, 1, 3, 3, 12000.00, 36000.00, '2026-04-14 04:22:38', '2026-04-14 04:23:44'),
(13, 11, 2, 2, 2, 10000.00, 20000.00, '2026-04-14 04:22:38', '2026-04-14 04:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'PT Abadi Jaya', 'IDR', '2026-03-13 09:10:25', '2026-03-13 09:10:25'),
(2, 'PT lala Mpruy', 'IDR', '2026-03-31 05:32:23', '2026-03-31 05:32:23'),
(3, 'PT melia Sehat', 'IDR', '2026-04-01 05:46:48', '2026-04-01 05:46:48'),
(4, 'PT melia Sehat sejahtera', 'IDR', '2026-04-13 21:11:30', '2026-04-13 21:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$10$m6JDGGc5MmIOR9shzwRAbeOPhQwMkq7mewmFjGfgFuZwirOy8tN12', NULL, '2026-03-13 16:30:54', '2026-03-13 16:30:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  ADD KEY `chart_of_accounts_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_submittal_no_unique` (`submittal_no`),
  ADD KEY `documents_submitted_by_foreign` (`submitted_by`),
  ADD KEY `documents_system_code_foreign` (`system_code`),
  ADD KEY `documents_document_no_nk_letter_date_revision_index` (`document_no`,`nk_letter_date`,`revision`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipts_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_reference_code_unique` (`reference_code`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`);

--
-- Indexes for table `journal_entry_details`
--
ALTER TABLE `journal_entry_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_details_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `journal_entry_details_account_id_foreign` (`account_id`);

--
-- Indexes for table `master_systems`
--
ALTER TABLE `master_systems`
  ADD PRIMARY KEY (`equipment_code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `simple_purchases`
--
ALTER TABLE `simple_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simple_purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `simple_purchase_items`
--
ALTER TABLE `simple_purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simple_purchase_items_simple_purchase_id_foreign` (`simple_purchase_id`),
  ADD KEY `simple_purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `journal_entry_details`
--
ALTER TABLE `journal_entry_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `simple_purchases`
--
ALTER TABLE `simple_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `simple_purchase_items`
--
ALTER TABLE `simple_purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_system_code_foreign` FOREIGN KEY (`system_code`) REFERENCES `master_systems` (`equipment_code`);

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_entry_details`
--
ALTER TABLE `journal_entry_details`
  ADD CONSTRAINT `journal_entry_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `journal_entry_details_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simple_purchases`
--
ALTER TABLE `simple_purchases`
  ADD CONSTRAINT `simple_purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simple_purchase_items`
--
ALTER TABLE `simple_purchase_items`
  ADD CONSTRAINT `simple_purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simple_purchase_items_simple_purchase_id_foreign` FOREIGN KEY (`simple_purchase_id`) REFERENCES `simple_purchases` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
