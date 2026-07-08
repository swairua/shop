-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jul 08, 2026 at 01:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:35:06'),
(2, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:43:46'),
(3, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:44:20'),
(4, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:45:15'),
(5, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:45:39'),
(6, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:45:47'),
(7, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:46:16'),
(8, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:50:05'),
(9, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:50:24'),
(10, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:50:38'),
(11, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 05:51:52'),
(12, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-08 05:52:00'),
(13, 1, 'login', 'Admin logged in', '::1', '', '2026-07-08 05:52:22'),
(14, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:00:32'),
(15, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:05:02'),
(16, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:05:10'),
(17, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:05:44'),
(18, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:06:00'),
(19, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 06:06:11'),
(20, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 09:41:04'),
(21, 1, 'login', 'Admin logged in', '::1', 'curl/8.13.0', '2026-07-08 09:41:10');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `website`, `meta_title`, `meta_description`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'apple', NULL, NULL, NULL, NULL, NULL, 0, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(2, 'Samsung', 'samsung', NULL, NULL, NULL, NULL, NULL, 0, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(3, 'Nike', 'nike', NULL, NULL, NULL, NULL, NULL, 0, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(4, 'Adidas', 'adidas', NULL, NULL, NULL, NULL, NULL, 0, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `callback_logs`
--

CREATE TABLE `callback_logs` (
  `id` int(11) NOT NULL,
  `source` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `method` varchar(10) NOT NULL,
  `headers` text DEFAULT NULL,
  `payload` longtext DEFAULT NULL,
  `response_code` int(11) DEFAULT NULL,
  `response_body` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `shipping_method` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `session_id`, `coupon_id`, `coupon_code`, `subtotal`, `discount`, `shipping_cost`, `tax`, `total`, `shipping_address_id`, `billing_address_id`, `shipping_method`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 'nelmbp7sru5pthlr85tjums1ub', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:01:31', '2026-07-08 05:01:31'),
(2, NULL, '2lermhe5unrj2rf18sjr4macro', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:02:48', '2026-07-08 05:02:48'),
(3, NULL, 'bchdd301l3somebe5is3qjqaob', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:03:52', '2026-07-08 05:03:52'),
(4, NULL, 'ilhlc59rig6ggf5gu4378ajtpj', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:05:11', '2026-07-08 05:05:11'),
(5, NULL, 'idhddahsbcqko2jq3ctkhrjv7a', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:07:30', '2026-07-08 05:07:30'),
(6, NULL, '306hpsmqn8c7pffqhupg44e1mo', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:07:43', '2026-07-08 05:07:43'),
(7, NULL, 'b4kjh0pr4fd4ft6jh18lhg1l76', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:07:44', '2026-07-08 05:07:44'),
(8, NULL, 'aoqgriubj6rmb3cu4gmae6o113', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:08:10', '2026-07-08 05:08:10'),
(9, NULL, 'qjunabumrrv9oelmide8gpc69t', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:08:12', '2026-07-08 05:08:12'),
(10, NULL, 'du175fubri1t8bptilh9ol0kih', NULL, NULL, 120000.00, 0.00, 0.00, 19200.00, 139200.00, NULL, NULL, NULL, NULL, '2026-07-08 05:16:51', '2026-07-08 06:10:31'),
(11, NULL, '79esrpqvjvlpnsifv6d5a27cra', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:14', '2026-07-08 05:19:14'),
(12, NULL, 'nko2g4dao1isgfn09crg5vlrku', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:17', '2026-07-08 05:19:17'),
(13, NULL, 'b5niff2pj7kb8qfo8lkqtlf70o', NULL, NULL, 240000.00, 0.00, 0.00, 38400.00, 278400.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:48', '2026-07-08 05:19:48'),
(14, NULL, 's36qs14u60nvoq9lrsji56qpkl', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:51', '2026-07-08 05:19:51'),
(15, NULL, 'tcdqpobgc91v2fl7m8pfshumdr', NULL, NULL, 240000.00, 0.00, 0.00, 38400.00, 278400.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:56', '2026-07-08 05:19:56'),
(16, NULL, 'lpcmge44brljibdh5cv3d7dfe6', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:19:57', '2026-07-08 05:19:57'),
(17, NULL, 'nng7vs9qibh7uqpbetdhofloba', NULL, NULL, 240000.00, 0.00, 0.00, 38400.00, 278400.00, NULL, NULL, NULL, NULL, '2026-07-08 05:20:15', '2026-07-08 05:20:15'),
(18, NULL, 'ou2bgbjd2vs4t5o7lb39u9d0c4', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '2026-07-08 05:20:15', '2026-07-08 05:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(1, 12, 1, NULL, 2, 120000.00, 240000.00, '2026-07-08 05:19:17'),
(2, 13, 1, NULL, 2, 120000.00, 240000.00, '2026-07-08 05:19:48'),
(3, 15, 1, NULL, 2, 120000.00, 240000.00, '2026-07-08 05:19:56'),
(4, 17, 1, NULL, 2, 120000.00, 240000.00, '2026-07-08 05:20:15'),
(6, 10, 1, NULL, 1, 120000.00, 120000.00, '2026-07-08 06:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image`, `icon`, `meta_title`, `meta_description`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Paint', 'paint', 'Marangi', NULL, NULL, '', '', 1, 'active', '2026-07-08 04:52:15', '2026-07-08 06:07:34'),
(2, NULL, 'Fashion', 'fashion', 'Clothing and accessories', NULL, NULL, '', '', 2, 'inactive', '2026-07-08 04:52:15', '2026-07-08 06:09:49'),
(3, NULL, 'Home & Living', 'home-living', 'Home and living products', NULL, NULL, NULL, NULL, 3, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(4, 1, 'Crown', 'crown', 'Mobile phones', NULL, NULL, '', '', 1, 'active', '2026-07-08 04:52:15', '2026-07-08 06:08:25'),
(5, 1, 'Marangi', 'marangi', 'marangi', NULL, NULL, '', '', 2, 'active', '2026-07-08 04:52:15', '2026-07-08 06:09:10'),
(6, NULL, 'Men', 'men', 'Men fashion', NULL, NULL, '', '', 1, 'inactive', '2026-07-08 04:52:15', '2026-07-08 06:09:24'),
(7, NULL, 'Women', 'women', 'Women fashion', NULL, NULL, '', '', 2, 'inactive', '2026-07-08 04:52:15', '2026-07-08 06:09:41'),
(8, 3, 'Furniture', 'furniture', 'Home furniture', NULL, NULL, NULL, NULL, 1, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `compare_list`
--

CREATE TABLE `compare_list` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percentage','fixed','free_shipping') NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_order_amount` decimal(12,2) DEFAULT NULL,
  `max_discount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_per_customer` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_token_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `password`, `image`, `balance`, `notes`, `status`, `email_verified_at`, `remember_token`, `remember_token_expiry`, `reset_token`, `reset_token_expiry`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john@example.com', '254700000001', '$2y$10$Rr74vvCXMrwEyERNgn0eSuhV6yoQ.mmAVGCTF7zSh8xURbKUst.b.', NULL, 0.00, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(2, 'Jane Smith', 'jane@example.com', '254700000002', '$2y$10$VQ7q2Pn0MBhMi70hlh/9/uVyJa5by8TzTReRRQe2hqsyf8CxKP4Ui', NULL, 0.00, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type` enum('shipping','billing','both') NOT NULL DEFAULT 'both',
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `variables` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse` varchar(100) DEFAULT 'Main',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('purchase','sale','return','adjustment','transfer_in','transfer_out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_callbacks`
--

CREATE TABLE `mpesa_callbacks` (
  `id` int(11) NOT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `merchant_request_id` varchar(100) DEFAULT NULL,
  `checkout_request_id` varchar(100) DEFAULT NULL,
  `result_code` int(11) DEFAULT NULL,
  `result_desc` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_transactions`
--

CREATE TABLE `mpesa_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `merchant_request_id` varchar(100) DEFAULT NULL,
  `checkout_request_id` varchar(100) DEFAULT NULL,
  `mpesa_receipt_number` varchar(50) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) DEFAULT NULL,
  `result_code` int(11) DEFAULT NULL,
  `result_desc` text DEFAULT NULL,
  `status` enum('pending','completed','failed','cancelled','reversed') NOT NULL DEFAULT 'pending',
  `raw_callback` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_callback`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `outstanding_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','paid','partially_paid','refunded','failed') NOT NULL DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `shipping_method` varchar(100) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `staff_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `comment` text DEFAULT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'KES',
  `status` enum('pending','completed','failed','refunded','reversed') NOT NULL DEFAULT 'pending',
  `payer_name` varchar(150) DEFAULT NULL,
  `payer_email` varchar(100) DEFAULT NULL,
  `payer_phone` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_failures`
--

CREATE TABLE `payment_failures` (
  `id` int(11) NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `error_code` varchar(50) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `retry_count` int(11) NOT NULL DEFAULT 0,
  `last_retry_at` datetime DEFAULT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT 0,
  `payload` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `request_data` longtext DEFAULT NULL,
  `response_data` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'manual',
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `slug`, `description`, `type`, `config`, `is_default`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cash on Delivery', 'cod', 'Pay when you receive', 'cod', NULL, 0, 1, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(2, 'M-Pesa', 'mpesa', 'Pay via M-Pesa', 'mpesa', NULL, 0, 2, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(3, 'Bank Transfer', 'bank', 'Pay via bank transfer', 'bank', NULL, 0, 3, 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `payment_reconciliations`
--

CREATE TABLE `payment_reconciliations` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `expected_amount` decimal(12,2) NOT NULL,
  `actual_amount` decimal(12,2) NOT NULL,
  `difference` decimal(12,2) NOT NULL,
  `status` enum('matched','unmatched','partial') NOT NULL DEFAULT 'unmatched',
  `notes` text DEFAULT NULL,
  `reconciled_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `tax_class_id` int(11) DEFAULT NULL,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(12,2) DEFAULT NULL,
  `discount_start` datetime DEFAULT NULL,
  `discount_end` datetime DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 5,
  `weight` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `type` enum('physical','digital') NOT NULL DEFAULT 'physical',
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `tax_class_id`, `sku`, `barcode`, `name`, `slug`, `description`, `short_description`, `cost_price`, `selling_price`, `discount_price`, `discount_start`, `discount_end`, `quantity`, `reorder_level`, `weight`, `length`, `width`, `height`, `featured_image`, `type`, `status`, `is_featured`, `is_new_arrival`, `is_best_seller`, `meta_title`, `meta_description`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'PRD-001', '200000000001', 'iPhone 15 Pro', 'iphone-15-pro', 'Latest Apple iPhone', NULL, 80000.00, 120000.00, NULL, NULL, NULL, 50, 5, NULL, NULL, NULL, NULL, 'https://picsum.photos/seed/iphone15/600/600', 'physical', 'active', 1, 1, 1, NULL, NULL, 8, '2026-07-08 04:52:15', '2026-07-08 05:38:49'),
(2, 2, 2, 1, 'PRD-002', '200000000002', 'Samsung Galaxy S24', 'samsung-galaxy-s24', 'Latest Samsung Galaxy', NULL, 70000.00, 100000.00, NULL, NULL, NULL, 40, 5, NULL, NULL, NULL, NULL, 'https://picsum.photos/seed/galaxys24/600/600', 'physical', 'active', 1, 1, 0, NULL, NULL, 1, '2026-07-08 04:52:15', '2026-07-08 05:38:49'),
(3, 3, 3, 1, 'PRD-003', '200000000003', 'Nike Air Max', 'nike-air-max', 'Comfortable running shoes', NULL, 4000.00, 8000.00, NULL, NULL, NULL, 100, 10, NULL, NULL, NULL, NULL, 'https://picsum.photos/seed/nikeairmax/600/600', 'physical', 'active', 0, 0, 1, NULL, NULL, 0, '2026-07-08 04:52:15', '2026-07-08 05:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `medium` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attributes`)),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `title` varchar(255) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `status` enum('pending','approved','disapproved') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'general',
  `type` enum('text','textarea','image','email','number','select','json') NOT NULL DEFAULT 'text',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group_name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'shop_name', 'My Shop', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(2, 'shop_email', 'admin@myshop.com', 'general', 'email', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(3, 'shop_phone', '+254700000000', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(4, 'shop_address', 'Nairobi, Kenya', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(5, 'shop_currency', 'KES', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(6, 'shop_currency_symbol', 'KSh', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(7, 'shop_timezone', 'Africa/Nairobi', 'general', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(8, 'shop_logo', '', 'general', 'image', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(9, 'shop_favicon', '', 'general', 'image', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(10, 'tax_enabled', '1', 'tax', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(11, 'tax_rate', '16.00', 'tax', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(12, 'tax_label', 'VAT (16%)', 'tax', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(13, 'shipping_enabled', '1', 'shipping', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(14, 'order_prefix', 'ORD-', 'orders', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(15, 'items_per_page', '12', 'catalog', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(16, 'enable_reviews', '1', 'catalog', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(17, 'enable_wishlist', '1', 'catalog', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(18, 'enable_compare', '1', 'catalog', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(19, 'meta_title', 'My Shop - Best Online Store', 'seo', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(20, 'meta_description', 'Welcome to our online store', 'seo', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(21, 'og_title', 'My Shop', 'seo', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(22, 'og_description', 'Welcome to our online store', 'seo', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(23, 'og_image', '', 'seo', 'image', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(24, 'facebook_url', '', 'social', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(25, 'twitter_url', '', 'social', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(26, 'instagram_url', '', 'social', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(27, 'youtube_url', '', 'social', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(28, 'mpesa_environment', 'sandbox', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(29, 'mpesa_consumer_key', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(30, 'mpesa_consumer_secret', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(31, 'mpesa_passkey', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(32, 'mpesa_shortcode', '174379', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(33, 'mpesa_till_number', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(34, 'mpesa_initiator_name', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(35, 'mpesa_initiator_password', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(36, 'mpesa_security_certificate', '', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(37, 'mpesa_callback_url', 'http://localhost/shop/api/mpesa/callback', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(38, 'mpesa_validation_url', 'http://localhost/shop/api/mpesa/validate', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(39, 'mpesa_confirmation_url', 'http://localhost/shop/api/mpesa/confirm', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(40, 'mpesa_queue_timeout_url', 'http://localhost/shop/api/mpesa/timeout', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(41, 'mpesa_result_url', 'http://localhost/shop/api/mpesa/result', 'mpesa', 'text', '2026-07-08 04:52:14', '2026-07-08 04:52:14'),
(6151, 'tiktok_url', '', 'social', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6152, 'linkedin_url', '', 'social', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6153, 'pinterest_url', '', 'social', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6154, 'whatsapp_number', '', 'social', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6155, 'whatsapp_message', 'Hi! I want to order from your shop', 'social', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6156, 'primary_color', '#2563eb', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6157, 'secondary_color', '#7c3aed', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6158, 'accent_color', '#f59e0b', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6159, 'header_bg', '#0f172a', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6160, 'footer_bg', '#0f172a', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6161, 'font_family', '\'Inter\', sans-serif', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01'),
(6162, 'border_radius', '0.5rem', 'appearance', 'text', '2026-07-08 05:34:01', '2026-07-08 05:34:01');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `slug`, `description`, `is_default`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Standard Shipping', 'standard', 'Standard delivery (5-7 business days)', 1, 'active', 1, '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(2, 'Express Shipping', 'express', 'Express delivery (1-2 business days)', 0, 'active', 2, '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_rates`
--

CREATE TABLE `shipping_rates` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `min_weight` decimal(10,2) DEFAULT NULL,
  `max_weight` decimal(10,2) DEFAULT NULL,
  `min_total` decimal(12,2) DEFAULT NULL,
  `max_total` decimal(12,2) DEFAULT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `additional_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estimated_days` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `countries` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `payment_terms` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_classes`
--

CREATE TABLE `tax_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_classes`
--

INSERT INTO `tax_classes` (`id`, `name`, `slug`, `rate`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Standard VAT', 'standard-vat', 16.00, 'percentage', 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15'),
(2, 'Zero Rated', 'zero-rated', 0.00, 'percentage', 'active', '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','staff') NOT NULL DEFAULT 'staff',
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_token_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `image`, `phone`, `status`, `remember_token`, `remember_token_expiry`, `reset_token`, `reset_token_expiry`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@shop.com', '$2y$10$BxvEJbUAREGRrs/d/n5cKePIA7pa03cpmJ7h21gM56ZTfTksy4wY.', 'super_admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, '2026-07-08 04:52:15', '2026-07-08 04:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_actlog_user` (`user_id`),
  ADD KEY `idx_actlog_action` (`action`),
  ADD KEY `idx_actlog_created` (`created_at`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_brands_slug` (`slug`),
  ADD KEY `idx_brands_status` (`status`);

--
-- Indexes for table `callback_logs`
--
ALTER TABLE `callback_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cblog_source` (`source`),
  ADD KEY `idx_cblog_type` (`type`),
  ADD KEY `idx_cblog_processed` (`processed`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cart_customer` (`customer_id`),
  ADD KEY `idx_cart_session` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cartitem_cart` (`cart_id`),
  ADD KEY `idx_cartitem_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`),
  ADD KEY `idx_categories_slug` (`slug`),
  ADD KEY `idx_categories_status` (`status`),
  ADD KEY `idx_categories_sort` (`sort_order`);

--
-- Indexes for table `compare_list`
--
ALTER TABLE `compare_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_compare` (`customer_id`,`product_id`),
  ADD KEY `idx_comp_customer` (`customer_id`),
  ADD KEY `idx_comp_product` (`product_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_coupons_code` (`code`),
  ADD KEY `idx_coupons_status` (`status`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_customers_email` (`email`),
  ADD KEY `idx_customers_status` (`status`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_custaddr_customer` (`customer_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_inv_product_warehouse` (`product_id`,`warehouse`),
  ADD KEY `idx_inv_product` (`product_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_invmov_product` (`product_id`),
  ADD KEY `idx_invmov_type` (`type`),
  ADD KEY `idx_invmov_created` (`created_at`);

--
-- Indexes for table `mpesa_callbacks`
--
ALTER TABLE `mpesa_callbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mpeback_type` (`transaction_type`),
  ADD KEY `idx_mpeback_request` (`merchant_request_id`),
  ADD KEY `idx_mpeback_processed` (`processed`);

--
-- Indexes for table `mpesa_transactions`
--
ALTER TABLE `mpesa_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mpesa_order` (`order_id`),
  ADD KEY `idx_mpesa_customer` (`customer_id`),
  ADD KEY `idx_mpesa_receipt` (`mpesa_receipt_number`),
  ADD KEY `idx_mpesa_request` (`merchant_request_id`),
  ADD KEY `idx_mpesa_checkout` (`checkout_request_id`),
  ADD KEY `idx_mpesa_status` (`status`),
  ADD KEY `idx_mpesa_phone` (`phone_number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_number` (`order_number`),
  ADD KEY `idx_orders_status` (`order_status`),
  ADD KEY `idx_orders_payment` (`payment_status`),
  ADD KEY `idx_orders_date` (`created_at`),
  ADD KEY `idx_orders_coupon` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orditem_order` (`order_id`),
  ADD KEY `idx_orditem_product` (`product_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ordhist_order` (`order_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pay_order` (`order_id`),
  ADD KEY `idx_pay_customer` (`customer_id`),
  ADD KEY `idx_pay_method` (`payment_method_id`),
  ADD KEY `idx_pay_status` (`status`),
  ADD KEY `idx_pay_transaction` (`transaction_id`);

--
-- Indexes for table `payment_failures`
--
ALTER TABLE `payment_failures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payfail_method` (`payment_method_id`),
  ADD KEY `idx_payfail_order` (`order_id`),
  ADD KEY `idx_payfail_resolved` (`resolved`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paylog_payment` (`payment_id`),
  ADD KEY `idx_paylog_action` (`action`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `payment_reconciliations`
--
ALTER TABLE `payment_reconciliations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payrec_payment` (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`),
  ADD KEY `idx_products_sku` (`sku`),
  ADD KEY `idx_products_slug` (`slug`),
  ADD KEY `idx_products_status` (`status`),
  ADD KEY `idx_products_featured` (`is_featured`),
  ADD KEY `idx_products_price` (`selling_price`),
  ADD KEY `idx_products_type` (`type`),
  ADD KEY `tax_class_id` (`tax_class_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodimg_product` (`product_id`),
  ADD KEY `idx_prodimg_main` (`is_main`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_var_product` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_customer` (`customer_id`),
  ADD KEY `idx_reviews_status` (`status`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `idx_settings_key` (`key`),
  ADD KEY `idx_settings_group` (`group_name`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shiprates_zone` (`zone_id`),
  ADD KEY `idx_shiprates_method` (`method_id`);

--
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_suppliers_status` (`status`);

--
-- Indexes for table `tax_classes`
--
ALTER TABLE `tax_classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_wishlist` (`customer_id`,`product_id`),
  ADD KEY `idx_wish_customer` (`customer_id`),
  ADD KEY `idx_wish_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `callback_logs`
--
ALTER TABLE `callback_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `compare_list`
--
ALTER TABLE `compare_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mpesa_callbacks`
--
ALTER TABLE `mpesa_callbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mpesa_transactions`
--
ALTER TABLE `mpesa_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_failures`
--
ALTER TABLE `payment_failures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_reconciliations`
--
ALTER TABLE `payment_reconciliations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16286;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_classes`
--
ALTER TABLE `tax_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `compare_list`
--
ALTER TABLE `compare_list`
  ADD CONSTRAINT `compare_list_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compare_list_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mpesa_transactions`
--
ALTER TABLE `mpesa_transactions`
  ADD CONSTRAINT `mpesa_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mpesa_transactions_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_reconciliations`
--
ALTER TABLE `payment_reconciliations`
  ADD CONSTRAINT `payment_reconciliations_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`tax_class_id`) REFERENCES `tax_classes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD CONSTRAINT `shipping_rates_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipping_rates_ibfk_2` FOREIGN KEY (`method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
