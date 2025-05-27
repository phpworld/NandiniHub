-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2025 at 07:20 PM
-- Server version: 8.0.40-cll-lve
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exportmarket_nandini`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(8, 1, NULL, 2, 1, 65.00, '2025-05-26 12:51:27', '2025-05-26 12:51:27'),
(18, NULL, 'ed1ef4961384e873e2d301769aa0b1de', 1, 1, 40.00, '2025-05-26 13:11:15', '2025-05-26 13:11:15'),
(19, NULL, 'ed1ef4961384e873e2d301769aa0b1de', 3, 1, 30.00, '2025-05-26 13:11:20', '2025-05-26 13:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Agarbatti & Incense', 'agarbatti-incense', 'Premium quality agarbatti and incense sticks for daily puja and special occasions', NULL, 1, 1, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(2, 'Dhoop & Sambrani', 'dhoop-sambrani', 'Traditional dhoop sticks and sambrani for creating sacred atmosphere', NULL, 1, 2, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(3, 'Puja Thali & Accessories', 'puja-thali-accessories', 'Complete puja thali sets and essential accessories for worship', 'category_1748263473_86dc9ead.jpg', 1, 3, '2025-05-26 09:56:34', '2025-05-26 12:44:33'),
(4, 'Diyas & Candles', 'diyas-candles', 'Traditional diyas, candles and oil lamps for lighting during puja', NULL, 1, 4, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(5, 'Flowers & Garlands', 'flowers-garlands', 'Fresh flowers, artificial garlands and flower decorations', NULL, 1, 5, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(6, 'Puja Oils & Ghee', 'puja-oils-ghee', 'Pure oils, ghee and other liquid offerings for puja', NULL, 1, 6, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(7, 'Idols & Statues', 'idols-statues', 'Beautiful idols and statues of various deities', NULL, 1, 7, '2025-05-26 09:56:34', '2025-05-26 09:56:34'),
(8, 'Puja Books & Mantras', 'puja-books-mantras', 'Religious books, mantra collections and spiritual literature', NULL, 1, 8, '2025-05-26 09:56:34', '2025-05-26 09:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
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
(8, '2024-01-01-000008', 'App\\Database\\Migrations\\AddAdminRoleToUsers', 'default', 'App', 1748254392, 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `shipping_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `billing_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `total_amount`, `shipping_amount`, `tax_amount`, `discount_amount`, `payment_method`, `payment_status`, `shipping_address`, `billing_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'ORD202505267592', 'pending', 268.30, 50.00, 33.30, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:01:38', '2025-05-26 13:01:38'),
(2, 3, 'ORD202505265186', 'pending', 720.98, 0.00, 109.98, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:03:00', '2025-05-26 13:03:00'),
(3, 3, 'ORD202505264505', 'pending', 114.90, 50.00, 9.90, 0.00, 'cod', 'pending', 'LGF 10 Anora kalan papnamow Road', 'LGF 10 Anora kalan papnamow Road', '', '2025-05-26 13:03:33', '2025-05-26 13:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_sku` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
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
(9, 3, 4, 'Traditional Dhoop Sticks', 'N/A', 1, 55.00, 55.00, '2025-05-26 13:03:33', '2025-05-26 13:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `short_description` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gallery` text COLLATE utf8mb4_general_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meta_description` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `sku`, `stock_quantity`, `weight`, `dimensions`, `image`, `gallery`, `is_featured`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nag Champa Agarbatti', 'nag-champa-agarbatti', 'Premium quality Nag Champa incense sticks made from natural ingredients. Perfect for daily puja and meditation.', 'Premium Nag Champa incense sticks for daily worship', 45.00, 40.00, 'AGR001', 99, 0.15, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:00'),
(2, 1, 'Sandalwood Agarbatti', 'sandalwood-agarbatti', 'Pure sandalwood incense sticks with authentic fragrance. Ideal for creating peaceful atmosphere during prayers.', 'Pure sandalwood incense sticks with authentic fragrance', 65.00, NULL, 'AGR002', 72, 0.15, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:00'),
(3, 1, 'Mogra Agarbatti', 'mogra-agarbatti', 'Delicate mogra (jasmine) fragrance incense sticks. Perfect for evening prayers and special occasions.', 'Delicate mogra fragrance incense sticks', 35.00, 30.00, 'AGR003', 119, 0.15, NULL, NULL, NULL, 0, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:00'),
(4, 2, 'Traditional Dhoop Sticks', 'traditional-dhoop-sticks', 'Handmade traditional dhoop sticks with natural herbs and resins. Creates thick aromatic smoke perfect for puja.', 'Handmade traditional dhoop sticks with natural herbs', 55.00, NULL, 'DHP001', 77, 0.20, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:33'),
(5, 2, 'Sambrani Cups', 'sambrani-cups', 'Ready-to-use sambrani cups made from pure benzoin resin. Just light and enjoy the divine fragrance.', 'Ready-to-use sambrani cups made from pure benzoin resin', 25.00, 22.00, 'SMB001', 149, 0.10, NULL, NULL, NULL, 0, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:00'),
(6, 3, 'Brass Puja Thali Set', 'brass-puja-thali-set', 'Complete brass puja thali set with diya, incense holder, small bowls and decorative elements. Perfect for daily worship.', 'Complete brass puja thali set with all accessories', 450.00, 399.00, 'PTH001', 24, 0.80, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 13:03:00'),
(7, 3, 'Silver Plated Puja Thali', 'silver-plated-puja-thali', 'Elegant silver plated puja thali with intricate designs. Ideal for special occasions and festivals.', 'Elegant silver plated puja thali with intricate designs', 850.00, NULL, 'PTH002', 15, 1.20, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 09:56:48'),
(8, 4, 'Clay Diyas (Pack of 12)', 'clay-diyas-pack-12', 'Traditional handmade clay diyas perfect for Diwali and daily puja. Pack contains 12 pieces.', 'Traditional handmade clay diyas - pack of 12', 60.00, 50.00, 'DYA001', 200, 0.50, NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-05-26 09:56:48', '2025-05-26 09:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `review` text COLLATE utf8mb4_general_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `helpful_count` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pincode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('customer','admin') COLLATE utf8mb4_general_ci DEFAULT 'customer',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `city`, `state`, `pincode`, `is_active`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@nandinihub.com', '$2y$10$D8EYVZHlgsH8H11BkCIDqe14D.QJVgcFAfieuP/a8tvYrN3BOUpiC', '+91 98765 43210', '123 Admin Street, Business District', 'Mumbai', 'Maharashtra', '400001', 1, 'admin', '2025-05-26 10:04:35', '2025-05-26 10:04:35'),
(2, 'Test', 'Customer', 'customer@test.com', '$2y$10$nYwd33PGZItMRhFDlhAd8uQ6d8308ByNwW/wRMF2LEcrsXYKRhJ3O', '+91 87654 32109', '456 Customer Lane, Residential Area', 'Delhi', 'Delhi', '110001', 1, 'customer', '2025-05-26 10:04:35', '2025-05-26 10:04:35'),
(3, 'Vinay', 'Singh', 'vinaysingh43@gmail.com', '$2y$10$MvxwBn4m4ZBWJdDd7Dh1hOuDaAPdaUaEKP1RisQVYFhkLlxrlSfj6', '919457790679', 'LGF 10 Anora kalan papnamow Road', 'Lucknow', 'Utter Pradesh', '226028', 1, 'customer', '2025-05-26 11:41:02', '2025-05-26 12:57:36');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
