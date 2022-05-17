-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2022 at 02:08 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii2_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1637056636),
('m130524_201442_init', 1637056641),
('m190124_110200_add_verification_token_column_to_user_table', 1637056641),
('m211116_185519_create_products_table', 1637089035),
('m211116_185832_create_user_adresses_table', 1637089141),
('m211116_191052_create_orders_table', 1637128494),
('m211117_055806_create_order_adresses_table', 1637128706),
('m211117_060021_create_cart_items_table', 1637128835),
('m211117_060513_create_order_items_table', 1637129294),
('m211123_133134_add_firstname_lastname_columns_to_user_table', 1637675037),
('m211129_085542_add_paypal_order_id_column_to_orders_table', 1638177452);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `firstname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total_price`, `status`, `firstname`, `lastname`, `email`, `transaction_id`, `paypal_order_id`, `created_at`, `created_by`) VALUES
(142, '10.00', 1, 'Alex', 'Mwangi', 'mwangialex26@gmail.com', '68G99289V9127782Y', '72L89142H0587431U', 1638195719, NULL),
(143, '55.00', 1, 'James', 'Smith', 'mwangialex26@gmail.com', '8PF706736U5415336', '4H8860888L1991934', 1638196120, 3),
(144, '25.00', 1, 'James', 'Smith', 'mwangialex26@gmail.com', '6SJ58714XV7704819', '3FK65863DN166272B', 1638257673, 3),
(145, '150.00', 0, 'James', 'Smith', 'mwangialex26@gmail.com', NULL, NULL, 1638261613, 3),
(146, '55.00', 1, 'Frank', 'Mwangi', 'frank@example.com', '2A897758V11663805', '8XD179376Y337482S', 1638339845, 7),
(147, '10.00', 1, 'Alex', 'Mwangi', 'mwangialex26@gmail.com', '5LK04112P1080244G', '92V35726828171708', 1638341019, 7);

-- --------------------------------------------------------

--
-- Table structure for table `order_adresses`
--

CREATE TABLE `order_adresses` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `adresses` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_adresses`
--

INSERT INTO `order_adresses` (`id`, `order_id`, `adresses`, `city`, `state`, `country`, `zipcode`) VALUES
(142, 142, '77441 - 00611', 'Nairobi', 'NA', 'Kenya', 'NA'),
(143, 143, 'Address', 'City', 'State', 'Country', 'Zipcodee'),
(144, 144, 'Address', 'City', 'State', 'Country', 'Zipcodee'),
(145, 145, 'Address', 'City', 'State', 'Country', 'Zipcodee'),
(146, 146, '77441-00612', 'Nakuru', 'NA', 'Kenya', 'NA'),
(147, 147, '77441 - 00612', 'Nairobi', 'NA', 'Kenya', 'NA');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `product_name`, `product_id`, `unit_price`, `order_id`, `quantity`) VALUES
(161, 'Ice Cream', 156, '10.00', 142, 1),
(162, 'Test Product 2', 151, '25.00', 143, 1),
(163, 'Test Product 5', 152, '20.00', 143, 1),
(164, 'Ice Cream', 156, '10.00', 143, 1),
(165, 'Test Product 2', 151, '25.00', 144, 1),
(166, 'Test Product 2', 151, '25.00', 145, 4),
(167, 'Ice Cream', 156, '10.00', 145, 5),
(168, 'Test Product 2', 151, '25.00', 146, 1),
(169, 'Test Product 5', 152, '20.00', 146, 1),
(170, 'Ice Cream', 156, '10.00', 146, 1),
(171, 'Ice Cream', 156, '10.00', 147, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(151, 'Test Product 2', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 'img_151.jpeg', '25.00', 1, '2021-11-22 08:58:59', '2021-11-22 08:58:59', 1, NULL),
(152, 'Test Product 5', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 'img_152.jpeg', '20.00', 1, '2021-11-22 09:04:48', '2021-11-22 09:04:48', NULL, NULL),
(153, 'Test Product 6', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 'img_153.jpeg', '15.00', 0, '2021-11-22 09:22:28', '2021-11-22 09:22:28', NULL, NULL),
(156, 'Ice Cream', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n', 'img_156.jpeg', '10.00', 1, '2021-11-23 11:37:51', '2021-11-23 11:37:51', NULL, NULL),
(157, 'Milkshake', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy.&nbsp;</p>\r\n', 'img_157.jpeg', '20.00', 1, '2021-11-23 11:39:56', '2021-11-23 11:39:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, '', '', 'admin', 's0_CuUuh3kjgmWWBM_omW5oKrglhYtS5', '$2y$13$l70MSu/Vcff3UmySLqzWXOJZApWhE5RSFmNVVMzZfqs2jElYv54fa', NULL, 'admin@example.com', 10, 1637056680, 1637056904, 'wIovuwSVt3y6M7Pu5bx1EzpQjwhGQUZm_1637056680'),
(3, 'James', 'Smith', 'James', 'VV1wIp4pKIDhTYZZQbmZajSHTa3WwUt7', '$2y$13$/TGgS0TIKabkmZc9P5BrWOhIRIxXMspk8iFn97LUa3.uL/A0iMW5i', NULL, 'mwangialex26@gmail.com', 10, 1637677173, 1641888849, '4ozUOavN4Zgo-UsGd81MkqlDeb_I8BKx_1637677173'),
(7, 'Frank', 'Mwangi', 'Frank', '0ktlQPlv2d-NbzvGbRpnbpZ1S4vWtt5g', '$2y$13$g0Asl3Re7jwzXx0bBvaiNO7t5XVHR1Wscyr1rfP0UOh.FLVdw3obe', NULL, 'frank@example.com', 10, 1638339676, 1638339706, '2hqitxSRQd2mdJTbKtrJKYxQwWRoxzSH_1638339676');

-- --------------------------------------------------------

--
-- Table structure for table `user_adresses`
--

CREATE TABLE `user_adresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_adresses`
--

INSERT INTO `user_adresses` (`id`, `user_id`, `address`, `city`, `state`, `country`, `zipcode`) VALUES
(1, 3, 'Address', 'City', 'State', 'Country', 'Zipcodee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-cart_items-product_id` (`product_id`),
  ADD KEY `idx-cart_items-user_id` (`user_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-orders-created_by` (`created_by`);

--
-- Indexes for table `order_adresses`
--
ALTER TABLE `order_adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-order_adresses-order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-order_items-product_id` (`product_id`),
  ADD KEY `idx-order_items-order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-products-created_by` (`created_by`),
  ADD KEY `idx-products-updated_by` (`updated_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `user_adresses`
--
ALTER TABLE `user_adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-user_adresses-user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `order_adresses`
--
ALTER TABLE `order_adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_adresses`
--
ALTER TABLE `user_adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk-cart_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-cart_items-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk-orders-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_adresses`
--
ALTER TABLE `order_adresses`
  ADD CONSTRAINT `fk-order_adresses-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk-order_items-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk-products-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-products-updated_by` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_adresses`
--
ALTER TABLE `user_adresses`
  ADD CONSTRAINT `fk-user_adresses-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
