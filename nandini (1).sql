-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 03:03 PM
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
-- Database: `nandini`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `button_text_2` varchar(100) DEFAULT NULL,
  `button_link_2` varchar(255) DEFAULT NULL,
  `background_color` varchar(7) DEFAULT '#ff6b35',
  `text_color` varchar(7) DEFAULT '#ffffff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `button_text_2`, `button_link_2`, `background_color`, `text_color`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Premium Puja Samagri Online', 'Discover authentic and high-quality puja items including agarbatti, dhoop, diyas, and all essential spiritual accessories for your divine worship.', 'Experience the divine with our carefully curated collection of traditional puja items.', 'banner_1748326098_608ab673.jpg', 'Shop Now', '/products', 'View Agarbatti', '/category/agarbatti-incense', '#ff6b35', '#ffffff', 1, 1, '2025-05-27 05:56:27', '2025-05-27 06:08:18'),
(3, 'Sacred Agarbatti Collection', 'Premium incense sticks for your daily prayers and meditation', 'Experience the divine with our carefully curated collection of traditional agarbatti.', NULL, 'Shop Agarbatti', '/category/agarbatti-incense', 'View All', '/products', '#ff6b35', '#ffffff', 1, 2, '2025-05-27 06:35:20', '2025-05-27 06:35:20'),
(4, 'Divine Puja Thali Sets', 'Complete brass and silver plated thali sets for worship', 'Elegant and traditional puja thali sets crafted with precision and devotion.', NULL, 'Shop Thali Sets', '/category/puja-thali-accessories', 'Learn More', '/products', '#f7931e', '#ffffff', 1, 3, '2025-05-27 06:35:20', '2025-05-27 06:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(18, NULL, 'ed1ef4961384e873e2d301769aa0b1de', 1, 1, 40.00, '2025-05-26 13:11:15', '2025-05-26 13:11:15'),
(19, NULL, 'ed1ef4961384e873e2d301769aa0b1de', 3, 1, 30.00, '2025-05-26 13:11:20', '2025-05-26 13:11:20'),
(35, 3, NULL, 2, 1, 60.00, '2025-05-28 07:55:13', '2025-05-28 07:55:13'),
(36, 3, NULL, 4, 1, 50.00, '2025-05-28 07:55:14', '2025-05-28 07:55:14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Agarbatti & Incense', 'agarbatti-incense', 'Premium quality agarbatti and incense sticks for daily puja and special occasions', 'category_1748330800_00748bc9.jpg', 1, 1, '2025-05-26 09:56:34', '2025-05-27 07:26:40'),
(2, 'Dhoop & Sambrani', 'dhoop-sambrani', 'Traditional dhoop sticks and sambrani for creating sacred atmosphere', 'category_1748331092_acfcd907.jpg', 1, 2, '2025-05-26 09:56:34', '2025-05-27 07:31:32'),
(3, 'Puja Thali & Accessories', 'puja-thali-accessories', 'Complete puja thali sets and essential accessories for worship', 'category_1748263473_86dc9ead.jpg', 1, 3, '2025-05-26 09:56:34', '2025-05-26 12:44:33'),
(4, 'Diyas & Candles', 'diyas-candles', 'Traditional diyas, candles and oil lamps for lighting during puja', 'category_1748331060_42f0fb9d.jpg', 1, 4, '2025-05-26 09:56:34', '2025-05-27 07:31:00'),
(5, 'Flowers & Garlands', 'flowers-garlands', 'Fresh flowers, artificial garlands and flower decorations', 'category_1748331024_ae30823c.jpg', 1, 5, '2025-05-26 09:56:34', '2025-05-27 07:30:24'),
(6, 'Puja Oils & Ghee', 'puja-oils-ghee', 'Pure oils, ghee and other liquid offerings for puja', 'category_1748330839_6b93c1ae.jpg', 1, 6, '2025-05-26 09:56:34', '2025-05-27 07:27:19'),
(7, 'Idols & Statues', 'idols-statues', 'Beautiful idols and statues of various deities', 'category_1748330996_df7d0f8d.jpg', 1, 7, '2025-05-26 09:56:34', '2025-05-27 07:29:56'),
(8, 'Puja Books & Mantras', 'puja-books-mantras', 'Religious books, mantra collections and spiritual literature', 'category_1748330959_78b95c8d.jpg', 1, 8, '2025-05-26 09:56:34', '2025-05-27 07:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('percentage','fixed_amount','free_shipping') NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `minimum_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `maximum_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_limit_per_customer` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `description`, `type`, `value`, `minimum_order_amount`, `maximum_discount_amount`, `usage_limit`, `usage_limit_per_customer`, `used_count`, `valid_from`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FIRST01', '10% OFF', '10% OFF', 'percentage', 10.00, 1.00, NULL, 2, 1, 0, NULL, NULL, 1, '2025-05-27 11:57:15', '2025-05-27 11:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `id` int(11) UNSIGNED NOT NULL,
  `coupon_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1748253181, 1),
(2, '2024-01-01-000002', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1748253306, 2),
(3, '2024-01-01-000003', 'App\\Database\\Migrations\\CreateProductsTable', 'default', 'App', 1748253306, 2),
(4, '2024-01-01-000004', 'App\\Database\\Migrations\\CreateCartTable', 'default', 'App', 1748253306, 2),
(5, '2024-01-01-000005', 'App\\Database\\Migrations\\CreateOrdersTable', 'default', 'App', 1748253371, 3),
(6, '2024-01-01-000006', 'App\\Database\\Migrations\\CreateOrderItemsTable', 'default', 'App', 1748253371, 3),
(7, '2024-01-01-000007', 'App\\Database\\Migrations\\CreateReviewsTable', 'default', 'App', 1748254392, 4),
(8, '2024-01-01-000008', 'App\\Database\\Migrations\\AddAdminRoleToUsers', 'default', 'App', 1748254392, 4),
(9, '2024-01-01-000009', 'App\\Database\\Migrations\\CreateBannersTable', 'default', 'App', 1748325034, 5),
(10, '2024-01-01-000009', 'App\\Database\\Migrations\\CreatePaymentTransactionsTable', 'default', 'App', 1748335705, 6),
(11, '2024-01-01-000010', 'App\\Database\\Migrations\\CreateCouponsTable', 'default', 'App', 1748346893, 7),
(12, '2024-01-01-000011', 'App\\Database\\Migrations\\CreateCouponUsageTable', 'default', 'App', 1748346893, 7),
(13, '2024-01-01-000012', 'App\\Database\\Migrations\\AddCouponFieldsToOrders', 'default', 'App', 1748346893, 7),
(14, '2025-01-28-120000', 'App\\Database\\Migrations\\CreateSettingsTable', 'default', 'App', 1748433752, 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `coupon_id` int(11) UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `billing_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `total_amount`, `coupon_id`, `coupon_code`, `shipping_amount`, `tax_amount`, `discount_amount`, `subtotal_amount`, `payment_method`, `payment_status`, `shipping_address`, `billing_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'ORD202505267592', 'delivered', 268.30, NULL, NULL, 50.00, 33.30, 0.00, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:01:38', '2025-05-28 13:00:15'),
(2, 3, 'ORD202505265186', 'delivered', 720.98, NULL, NULL, 0.00, 109.98, 0.00, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:03:00', '2025-05-26 15:21:21'),
(3, 3, 'ORD202505264505', 'delivered', 114.90, NULL, NULL, 50.00, 9.90, 0.00, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:03:33', '2025-05-26 15:21:03'),
(4, 3, 'ORD202505275199', 'delivered', 585.72, NULL, NULL, 50.00, 81.72, 0.00, 0.00, 'online', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-27 04:41:11', '2025-05-28 07:59:21'),
(5, 1, 'ORD202505273308', 'cancelled', 97.20, NULL, NULL, 50.00, 7.20, 0.00, 0.00, 'online', 'pending', '123 Admin Street, Business District', '123 Admin Street, Business District', '', '2025-05-27 08:44:05', '2025-05-27 08:44:16'),
(6, 1, 'ORD202505276381', 'cancelled', 97.20, NULL, NULL, 50.00, 7.20, 0.00, 0.00, 'online', 'pending', '123 Admin Street, Business District', '123 Admin Street, Business District', '', '2025-05-27 08:53:43', '2025-05-27 09:21:00'),
(7, 1, 'ORD202505272977', 'cancelled', 97.20, NULL, NULL, 50.00, 7.20, 0.00, 0.00, 'online', 'pending', '123 Admin Street, Business District', '123 Admin Street, Business District', '', '2025-05-27 08:59:53', '2025-05-27 09:20:56'),
(8, 3, 'ORD202505272119', 'delivered', 144.40, NULL, NULL, 50.00, 14.40, 0.00, 0.00, 'online', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-27 09:36:54', '2025-05-28 07:59:27'),
(9, 3, 'ORD202505271323', 'delivered', 120.80, NULL, NULL, 50.00, 10.80, 0.00, 60.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-27 12:38:15', '2025-05-28 13:00:04'),
(10, 1, 'ORD202505282884', 'delivered', 109.00, NULL, NULL, 50.00, 9.00, 0.00, 50.00, 'online', 'pending', '123 Admin Street, Business District', '123 Admin Street, Business District', '', '2025-05-28 12:41:40', '2025-05-28 12:42:49'),
(11, 4, 'ORD202505282432', 'delivered', 1014.80, NULL, NULL, 0.00, 154.80, 0.00, 860.00, 'cod', 'paid', 'LGF 19 Anora kala Sai Complex', 'LGF 19 Anora kala Sai Complex', '', '2025-05-28 12:45:39', '2025-05-28 12:55:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Sandalwood Agarbatti', 'N/A', 2, 65.00, 130.00, '2025-05-26 13:01:38', '2025-05-26 13:01:38'),
(2, 1, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 55.00, 55.00, '2025-05-26 13:01:38', '2025-05-26 13:01:38'),
(3, 2, 6, 'Brass Puja Thali Set', 'N/A', 1, 399.00, 399.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(4, 2, 5, 'Sambrani Cups', 'N/A', 1, 22.00, 22.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(5, 2, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 55.00, 55.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(6, 2, 1, 'Nag Champa Agarbatti', 'N/A', 1, 40.00, 40.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(7, 2, 2, 'Sandalwood Agarbatti', 'N/A', 1, 65.00, 65.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(8, 2, 3, 'Mogra Agarbatti', 'N/A', 1, 30.00, 30.00, '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(9, 3, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 55.00, 55.00, '2025-05-26 13:03:33', '2025-05-26 13:03:33'),
(10, 4, 6, 'Brass Puja Thali Set', 'N/A', 1, 399.00, 399.00, '2025-05-27 04:41:11', '2025-05-27 04:41:11'),
(11, 4, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 55.00, 55.00, '2025-05-27 04:41:11', '2025-05-27 04:41:11'),
(12, 5, 1, 'Nag Champa Agarbatti', 'N/A', 1, 40.00, 40.00, '2025-05-27 08:44:05', '2025-05-27 08:44:05'),
(13, 6, 1, 'Nag Champa Agarbatti', 'N/A', 1, 40.00, 40.00, '2025-05-27 08:53:43', '2025-05-27 08:53:43'),
(14, 7, 1, 'Nag Champa Agarbatti', 'N/A', 1, 40.00, 40.00, '2025-05-27 08:59:53', '2025-05-27 08:59:53'),
(15, 8, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 50.00, 50.00, '2025-05-27 09:36:54', '2025-05-27 09:36:54'),
(16, 8, 3, 'Mogra Agarbatti', 'N/A', 1, 30.00, 30.00, '2025-05-27 09:36:54', '2025-05-27 09:36:54'),
(17, 9, 2, 'Sandalwood Agarbatti', 'N/A', 1, 60.00, 60.00, '2025-05-27 12:38:15', '2025-05-27 12:38:15'),
(18, 10, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 50.00, 50.00, '2025-05-28 12:41:40', '2025-05-28 12:41:40'),
(19, 11, 7, 'Silver Plated Puja Thali', 'N/A', 1, 800.00, 800.00, '2025-05-28 12:45:39', '2025-05-28 12:45:39'),
(20, 11, 2, 'Sandalwood Agarbatti', 'N/A', 1, 60.00, 60.00, '2025-05-28 12:45:39', '2025-05-28 12:45:39');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `gateway_transaction_id` varchar(100) DEFAULT NULL,
  `payment_gateway` varchar(50) NOT NULL DEFAULT 'hdfc',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `status` enum('pending','processing','success','failed','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `gateway_status` varchar(50) DEFAULT NULL,
  `gateway_response` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `bank_ref_no` varchar(100) DEFAULT NULL,
  `failure_reason` varchar(255) DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `order_id`, `transaction_id`, `gateway_transaction_id`, `payment_gateway`, `amount`, `currency`, `status`, `gateway_status`, `gateway_response`, `payment_method`, `bank_ref_no`, `failure_reason`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'TXN20250527090809063', NULL, 'hdfc', 97.20, 'INR', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-27 09:08:09', '2025-05-27 09:08:09'),
(2, 6, 'TXN20250527090824005', NULL, 'hdfc', 97.20, 'INR', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-27 09:08:24', '2025-05-27 09:08:24'),
(5, 6, 'TXN20250527091428085', NULL, 'hdfc', 97.20, 'INR', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-27 09:14:28', '2025-05-27 09:19:31'),
(7, 6, 'TXN20250527092002328', 'DEMO1748337607', 'hdfc', 97.20, 'INR', 'failed', 'Failure', 'order_id=TXN20250527092002328&tracking_id=DEMO1748337607&bank_ref_no=DEMO224185&amount=97.20&currency=INR&merchant_param1=6&merchant_param2=1&payment_mode=Demo+Payment&card_name=Demo+Card&status_code=1&status_message=Transaction+Failed&response_code=1&failure_message=Demo+payment+failure&order_status=Failure', 'Demo Payment', 'DEMO224185', 'Demo payment failure', '2025-05-27 09:20:09', '2025-05-27 09:20:02', '2025-05-27 09:20:09'),
(8, 6, 'TXN20250527092023552', 'DEMO1748337629', 'hdfc', 97.20, 'INR', 'failed', 'Failure', 'order_id=TXN20250527092023552&tracking_id=DEMO1748337629&bank_ref_no=DEMO974866&amount=97.20&currency=INR&merchant_param1=6&merchant_param2=1&payment_mode=Demo+Payment&card_name=Demo+Card&status_code=1&status_message=Transaction+Failed&response_code=1&failure_message=Demo+payment+failure&order_status=Failure', 'Demo Payment', 'DEMO974866', 'Demo payment failure', '2025-05-27 09:20:31', '2025-05-27 09:20:23', '2025-05-27 09:20:31'),
(9, 6, 'TXN20250527092041046', 'DEMO1748337646', 'hdfc', 97.20, 'INR', 'failed', 'Failure', 'order_id=TXN20250527092041046&tracking_id=DEMO1748337646&bank_ref_no=DEMO855101&amount=97.20&currency=INR&merchant_param1=6&merchant_param2=1&payment_mode=Demo+Payment&card_name=Demo+Card&status_code=1&status_message=Transaction+Failed&response_code=1&failure_message=Demo+payment+failure&order_status=Failure', 'Demo Payment', 'DEMO855101', 'Demo payment failure', '2025-05-27 09:20:48', '2025-05-27 09:20:41', '2025-05-27 09:20:48'),
(10, 8, 'TXN20250527093658893', NULL, 'hdfc', 144.40, 'INR', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-27 09:36:58', '2025-05-27 09:36:58'),
(11, 10, 'TXN20250528124153275', NULL, 'hdfc', 109.00, 'INR', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-28 12:41:53', '2025-05-28 12:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `sku`, `stock_quantity`, `weight`, `dimensions`, `image`, `gallery`, `is_featured`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nag Champa Agarbatti', 'nag-champa-agarbatti', 'Premium quality Nag Champa incense sticks made from natural ingredients. Perfect for daily puja and meditation.', 'Premium Nag Champa incense sticks for daily worship', 45.00, 40.00, 'AGR001', 99, 0.15, '', 'product_1748324961_ae9d9f95.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-27 09:21:00'),
(2, 1, 'Sandalwood Agarbatti', 'sandalwood-agarbatti', 'Pure sandalwood incense sticks with authentic fragrance. Ideal for creating peaceful atmosphere during prayers.', 'Pure sandalwood incense sticks with authentic fragrance', 65.00, 60.00, 'AGR002', 73, 0.15, '', 'product_1748329326_504b3ba4.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-28 12:45:39'),
(3, 1, 'Mogra Agarbatti', 'mogra-agarbatti', 'Delicate mogra (jasmine) fragrance incense sticks. Perfect for evening prayers and special occasions.', 'Delicate mogra fragrance incense sticks', 35.00, 30.00, 'AGR003', 118, 0.15, '', 'product_1748329385_017e8d59.jpg', NULL, 0, 1, '', '', '2025-05-26 09:56:48', '2025-05-27 09:36:54'),
(4, 2, 'Traditional Dhoop Sticks', 'traditional-dhoop-sticks', 'Handmade traditional dhoop sticks with natural herbs and resins. Creates thick aromatic smoke perfect for puja.', 'Handmade traditional dhoop sticks with natural herbs', 55.00, 50.00, 'DHP001', 76, 0.20, '', 'product_1748329434_704a9bfb.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-28 12:42:09'),
(5, 2, 'Sambrani Cups', 'sambrani-cups', 'Ready-to-use sambrani cups made from pure benzoin resin. Just light and enjoy the divine fragrance.', 'Ready-to-use sambrani cups made from pure benzoin resin', 25.00, 22.00, 'SMB001', 149, 0.10, '', 'product_1748329451_1e5d084c.jpg', NULL, 0, 1, '', '', '2025-05-26 09:56:48', '2025-05-27 07:04:11'),
(6, 3, 'Brass Puja Thali Set', 'brass-puja-thali-set', 'Complete brass puja thali set with diya, incense holder, small bowls and decorative elements. Perfect for daily worship.', 'Complete brass puja thali set with all accessories', 450.00, 399.00, 'PTH001', 23, 0.80, '', 'product_1748329458_70403af6.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-27 07:04:18'),
(7, 3, 'Silver Plated Puja Thali', 'silver-plated-puja-thali', 'Elegant silver plated puja thali with intricate designs. Ideal for special occasions and festivals.', 'Elegant silver plated puja thali with intricate designs', 850.00, 800.00, 'PTH002', 14, 1.20, '', 'product_1748329466_8ec00319.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-28 12:45:39'),
(8, 4, 'Clay Diyas (Pack of 12)', 'clay-diyas-pack-12', 'Traditional handmade clay diyas perfect for Diwali and daily puja. Pack contains 12 pieces.', 'Traditional handmade clay diyas - pack of 12', 60.00, 50.00, 'DYA001', 200, 0.50, '', 'product_1748329474_d0f7fc34.jpg', NULL, 1, 1, '', '', '2025-05-26 09:56:48', '2025-05-27 07:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `review`, `is_verified`, `is_approved`, `helpful_count`, `created_at`, `updated_at`) VALUES
(3, 1, 3, NULL, 5, 'Excellent quality agarbatti', 'This is a fantastic product! The fragrance is amazing and lasts for a long time. Highly recommended for daily puja.', 1, 1, 0, '2025-05-28 07:59:35', '2025-05-28 08:02:34'),
(4, 1, 3, NULL, 5, 'Excellent quality agarbatti', 'This is a fantastic product! The fragrance is amazing and lasts for a long time. Highly recommended for daily puja.', 1, 1, 0, '2025-05-28 08:02:00', '2025-05-28 08:02:36'),
(8, 6, 2, NULL, 4, 'Average experience', 'Average product, nothing special but does the job.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(9, 5, 1, NULL, 3, 'Average experience', 'Average product, nothing special but does the job.', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(10, 7, 1, NULL, 5, 'Highly recommended', 'Good quality product, satisfied with the purchase.', 1, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(11, 4, 2, NULL, 3, 'Great value for money', 'This product exceeded my expectations. Highly recommended!', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(12, 7, 1, NULL, 5, 'Outstanding quality', 'Outstanding quality and excellent customer service.', 0, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(13, 7, 3, NULL, 3, 'Highly recommended', 'Great value for money, will definitely buy again.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(14, 4, 3, NULL, 4, 'Perfect for daily use', 'This product exceeded my expectations. Highly recommended!', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(15, 4, 2, NULL, 2, 'Great value for money', 'Good product but delivery was delayed.', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(16, 2, 1, NULL, 4, 'Good quality', 'Great value for money, will definitely buy again.', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(17, 7, 2, NULL, 5, 'Perfect for daily use', 'Good quality product, satisfied with the purchase.', 0, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(18, 7, 1, NULL, 2, 'Not satisfied', 'Great value for money, will definitely buy again.', 0, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(19, 3, 2, NULL, 3, 'Perfect for daily use', 'Not what I expected, quality could be better.', 1, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(20, 4, 3, NULL, 4, 'Great value for money', 'This product exceeded my expectations. Highly recommended!', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(21, 5, 1, NULL, 2, 'Good quality', 'Excellent fragrance and long-lasting effect.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 11:01:44'),
(22, 2, 2, NULL, 3, 'Good quality', 'Outstanding quality and excellent customer service.', 0, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(23, 4, 3, NULL, 5, 'Highly recommended', 'Not what I expected, quality could be better.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(24, 7, 2, NULL, 1, 'Good quality', 'Great value for money, will definitely buy again.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(25, 4, 1, NULL, 4, 'Outstanding quality', 'Good quality product, satisfied with the purchase.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(26, 6, 2, NULL, 2, 'Highly recommended', 'Good quality product, satisfied with the purchase.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(27, 8, 1, NULL, 5, 'Perfect for daily use', 'Could be improved in terms of quality and packaging.', 0, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(28, 5, 3, NULL, 1, 'Average experience', 'Could be improved in terms of quality and packaging.', 1, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(29, 4, 3, NULL, 1, 'Highly recommended', 'Could be improved in terms of quality and packaging.', 0, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(30, 4, 3, NULL, 2, 'Highly recommended', 'Outstanding quality and excellent customer service.', 1, 0, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(31, 6, 3, NULL, 4, 'Outstanding quality', 'Good product but delivery was delayed.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00'),
(32, 4, 2, NULL, 5, 'Perfect for daily use', 'Good product but delivery was delayed.', 1, 1, 0, '2025-05-28 10:54:00', '2025-05-28 10:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','text','number','boolean','json') NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Nandini Hub', 'string', 'Website name displayed in header and title', '2025-05-28 12:02:32', '2025-05-28 12:02:32'),
(2, 'site_tagline', 'Your Trusted Shopping Destination', 'string', 'Website tagline or slogan', '2025-05-28 12:02:32', '2025-05-28 12:02:32'),
(3, 'site_description', 'Nandini Hub is your one-stop destination for quality products at affordable prices.', 'text', 'Website description for SEO', '2025-05-28 12:02:32', '2025-05-28 12:02:32'),
(4, 'contact_email', 'info@nandinihub.com', 'string', 'Primary contact email address', '2025-05-28 12:02:32', '2025-05-28 12:02:32'),
(5, 'contact_phone', '+91 9876543210', 'string', 'Primary contact phone number', '2025-05-28 12:02:32', '2025-05-28 12:02:32'),
(6, 'google_analytics_id', 'G-TEST123456', 'string', 'Google Analytics Measurement ID (e.g., G-XXXXXXXXXX)', '2025-05-28 12:02:32', '2025-05-28 12:22:32'),
(7, 'google_analytics_enabled', '0', 'boolean', 'Enable or disable Google Analytics tracking', '2025-05-28 12:02:32', '2025-05-28 12:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `city`, `state`, `pincode`, `is_active`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@nandinihub.com', '$2y$10$D8EYVZHlgsH8H11BkCIDqe14D.QJVgcFAfieuP/a8tvYrN3BOUpiC', '+91 98765 43210', '123 Admin Street, Business District', 'Mumbai', 'Maharashtra', '400001', 1, 'admin', '2025-05-26 10:04:35', '2025-05-26 10:04:35'),
(2, 'Test', 'Customer', 'customer@test.com', '$2y$10$nYwd33PGZItMRhFDlhAd8uQ6d8308ByNwW/wRMF2LEcrsXYKRhJ3O', '+91 87654 32109', '456 Customer Lane, Residential Area', 'Delhi', 'Delhi', '110001', 1, 'customer', '2025-05-26 10:04:35', '2025-05-26 10:04:35'),
(3, 'Vinay', 'Singh', 'vinaysingh43@gmail.com', '$2y$10$MvxwBn4m4ZBWJdDd7Dh1hOuDaAPdaUaEKP1RisQVYFhkLlxrlSfj6', '919457790679', 'LGF 10 Anora kalan papnamow Road', 'Lucknow', 'Utter Pradesh', '226028', 1, 'customer', '2025-05-26 11:41:02', '2025-05-26 12:57:36'),
(4, 'Viplav nath', 'Singh', 'viplavnathsingh@gmail.com', '$2y$10$SNRoTgSHPRLB1IjYzj7dKOLYpRjCFiUaQ3xKpJjMlb0pLsQE8cszS', '9876543211', 'LGF 19 Anora kala Sai Complex', 'Lucknow', 'Utter Pradesh', '226028', 1, 'customer', '2025-05-28 12:44:17', '2025-05-28 12:44:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `valid_from` (`valid_from`),
  ADD KEY `valid_until` (`valid_until`);

--
-- Indexes for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `used_at` (`used_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `gateway_transaction_id` (`gateway_transaction_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `is_approved` (`is_approved`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_usage_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupon_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
