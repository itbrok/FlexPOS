-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2022 at 06:38 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posinstall`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(30) NOT NULL,
  `type` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `type`) VALUES
(0, 'اخرى');

-- --------------------------------------------------------

--
-- Table structure for table `consumer`
--

CREATE TABLE `consumer` (
  `id` int(30) NOT NULL,
  `name` varchar(90) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `address` varchar(190) NOT NULL,
  `notes` varchar(230) NOT NULL,
  `added_by` int(30) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consumer`
--

INSERT INTO `consumer` (`id`, `name`, `phone_number`, `address`, `notes`, `added_by`, `date`) VALUES
(0, 'عميل افتراضي', '', '', '', 0, '2022-09-20 05:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `country` varchar(65) NOT NULL,
  `val` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`country`, `val`) VALUES
('iq', '1500');

-- --------------------------------------------------------

--
-- Table structure for table `debt`
--

CREATE TABLE `debt` (
  `id` int(30) NOT NULL,
  `employee_id` int(30) NOT NULL,
  `consumer_id` int(30) NOT NULL,
  `money_paid` decimal(30,3) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `order_id` bigint(30) NOT NULL,
  `employee_id` int(30) NOT NULL,
  `consumer_id` int(30) NOT NULL,
  `items_count` int(30) NOT NULL,
  `discount` int(30) NOT NULL,
  `price_paid` decimal(30,3) NOT NULL,
  `price_left` decimal(30,3) NOT NULL,
  `total_price` decimal(30,3) NOT NULL COMMENT 'بدون الخصم',
  `isitfinished` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `prodect_barcode` varchar(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `sold_price` decimal(30,3) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

CREATE TABLE `printers` (
  `id` int(11) NOT NULL,
  `PrinterName` text NOT NULL,
  `size` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(30) NOT NULL,
  `barcode` longtext NOT NULL,
  `number` varchar(30) NOT NULL,
  `name` varchar(90) NOT NULL,
  `quantity` int(12) NOT NULL,
  `buy_price` decimal(30,3) NOT NULL,
  `sell_price` decimal(30,3) NOT NULL,
  `wholesale_price` decimal(30,3) NOT NULL,
  `class_id` int(30) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `warehouse_name` varchar(30) NOT NULL,
  `corridor` varchar(30) NOT NULL,
  `shelf` varchar(30) NOT NULL,
  `box` varchar(30) NOT NULL,
  `note` text NOT NULL,
  `img` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `role_name` varchar(190) NOT NULL,
  `role_value` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `user_id`, `role_name`, `role_value`) VALUES
(1, 1, 'admin_panel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sittings`
--

CREATE TABLE `sittings` (
  `id` int(30) NOT NULL,
  `variable` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sittings`
--

INSERT INTO `sittings` (`id`, `variable`, `value`) VALUES
(1, 'defult_product_img', 'data:image/png;base64,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'),
(2, 'wholesale_password', '1234'),
(3, 'logo_small', 'data:image/png;base64,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'),
(4, 'logo_large', 'data:image/png;base64,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'),
(5, 'def_logo', 'data:image/png;base64,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'),
(6, 'papers', '{\"77\":{\"@paper_size\":\"77mm\",\"@logo_height\":\"30mm\",\"@logo_width\":\"77mm\",\"@max_items_count\":\"30\",\"@footer_text\":\"<div><strong>\\u0634\\u0643\\u0631\\u0627 \\u0644\\u062a\\u0633\\u0648\\u0642\\u0643\\u0645 \\u0645\\u0646\\u0627<\\/strong><br>\\u0646\\u0634\\u0648\\u0641\\u0643\\u0645 \\u0628\\u0639\\u062f\\u064a\\u0646<\\/div>\",\"logo\":\"logo_small\",\"iqd\":\"true\"},\"A4\":{\"@paper_size\":\"190mm\",\"@logo_height\":\"40mm\",\"@logo_width\":\"auto\",\"@max_items_count\":\"29\",\"@footer_text\":\"<div><strong>\\u0634\\u0643\\u0631\\u0627 \\u0644\\u062a\\u0633\\u0648\\u0642\\u0643\\u0645 \\u0645\\u0646\\u0627<\\/strong><br>\\u0646\\u0634\\u0648\\u0641\\u0643\\u0645 \\u0628\\u0639\\u062f\\u064a\\u0646<\\/div>\",\"logo\":\"logo_large\",\"iqd\":\"false\"}}');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `type`) VALUES
(0, 'اخرى'),
(1, 'قطعة'),
(2, 'باكيت'),
(3, 'طقم'),
(5, 'درزن'),
(6, 'علبة'),
(7, 'شريط');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `added_by` int(30) NOT NULL,
  `printer_id` int(30) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `added_by`, `printer_id`, `date`) VALUES
(1, 'Admin', 'admin', 'admin', 1, 0, '2022-10-01 04:42:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumer`
--
ALTER TABLE `consumer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`country`);

--
-- Indexes for table `debt`
--
ALTER TABLE `debt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `printers`
--
ALTER TABLE `printers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sittings`
--
ALTER TABLE `sittings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consumer`
--
ALTER TABLE `consumer`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debt`
--
ALTER TABLE `debt`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `printers`
--
ALTER TABLE `printers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sittings`
--
ALTER TABLE `sittings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
