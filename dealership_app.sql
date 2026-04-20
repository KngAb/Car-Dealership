-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2026 at 02:35 PM
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
-- Database: `dealership_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_type` enum('test_drive','purchase') NOT NULL,
  `status` enum('pending','approved','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `booking_date`, `booking_type`, `status`, `created_at`, `full_name`, `email`, `phone`, `booking_time`, `message`) VALUES
(1, 4, 2, '2026-04-30', '', 'approved', '2026-04-08 06:57:23', 'car', 'masonjonh12@yahoo.com', '08066152639', '19:01:00', 'Long ride'),
(2, 4, 2, '2026-04-16', '', 'approved', '2026-04-08 07:03:57', 'Abraham', 'awure2k3@yahoo.com', '09024702105', '20:03:00', ''),
(3, 4, 2, '2026-04-15', '', 'approved', '2026-04-08 07:05:28', 'Destiny', 'Arkhamproctector@gmail.com', '09024702105', '08:08:00', ' '),
(4, 4, 2, '2026-04-04', '', 'pending', '2026-04-08 07:06:04', 'Abraham', 'rkhamproctector@gmail.com', '2147483647', '20:07:00', ' '),
(5, 4, 2, '2026-04-22', '', 'cancelled', '2026-04-08 07:11:18', 'Mark', 'awure2k3@yahoo.com', '08032988921', '13:16:00', ' '),
(6, 4, 2, '2026-04-23', '', 'pending', '2026-04-08 08:51:07', 'Abraham', 'testacc@yahoo.com', '09024704931', '21:51:00', '     '),
(7, 6, 2, '2026-04-24', '', 'pending', '2026-04-08 13:52:01', 'Abraham', 'testacc@yahoo.com', '09039828764', '03:52:00', '  ');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') DEFAULT NULL,
  `transmission` enum('manual','automatic') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','reserved','sold') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `price`, `mileage`, `fuel_type`, `transmission`, `description`, `status`, `created_at`, `image`) VALUES
(2, 'Honda', 'Accord', '2025', 25000000.00, 10, 'diesel', 'manual', 'Red', 'sold', '2026-04-07 08:57:57', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775552279/drxabjdqbvfnupxvkgfi.jpg'),
(3, 'Lexus', 'RX350', '2009', 9000000.00, 20, 'petrol', 'automatic', 'Black', 'available', '2026-04-08 14:05:17', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775657116/h0gpdqisz6r4ctqivmqr.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `car_id`, `price`, `created_at`) VALUES
(34, 4, 2, 25000000.00, '2026-04-08 07:17:47'),
(37, 6, 2, 25000000.00, '2026-04-08 13:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `car_images`
--

CREATE TABLE `car_images` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_path` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `contact_phone` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_ref` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `name`, `phone`, `address`, `created_at`, `payment_ref`) VALUES
(4, 2, 25000000.00, 'pending', 'Abraham', '09024702105', '27 STREET', '2026-04-07 11:48:05', NULL),
(5, 2, 0.00, 'pending', 'Abraham', '09024702105', '27 STREET', '2026-04-07 12:01:38', NULL),
(6, 2, 25000000.00, 'pending', 'm...', 'm,.,', ',m.,/', '2026-04-07 12:02:04', NULL),
(7, 2, 25000000.00, 'paid', 'Abraham', '09024702105', '12 Simon str', '2026-04-07 14:20:24', NULL),
(8, 2, 25000000.00, 'paid', 'Abraham', '08066152639', '76 hdhd', '2026-04-07 14:29:39', NULL),
(9, 2, 25000000.00, 'pending', 'Abraham', '09024702105', '12 DUROJAYE', '2026-04-07 19:30:54', NULL),
(10, 2, 25000000.00, 'paid', 'Abraham', '09024702105', '34 Durojaye', '2026-04-07 19:47:40', NULL),
(11, 2, 25000000.00, 'pending', 'Abraham', '09024702105', 'hshshh', '2026-04-08 03:39:03', NULL),
(12, 2, 25000000.00, 'paid', 'Abraham', '09024702105', 'AZ', '2026-04-08 03:47:29', NULL),
(13, 2, 25000000.00, 'paid', 'Abraham', '09024702105', 'dgydg', '2026-04-08 03:53:15', NULL),
(14, 2, 25000000.00, 'paid', 'Abraham', '2147483647', 'bjj', '2026-04-08 03:55:44', NULL),
(15, 2, 25000000.00, 'pending', 'Abraham', '12900', 'jhjhjh', '2026-04-08 04:16:47', 'TXN-69d5d6af713124.27139264'),
(16, 2, 25000000.00, 'pending', 'Abraham', '0903333', 'bnnk', '2026-04-08 04:17:38', 'TXN-69d5d6e2f2df85.99255003'),
(17, 2, 25000000.00, 'pending', 'ml', 'nkk', 'nkkjj', '2026-04-08 04:18:07', 'TXN-69d5d6ffb1e744.32870177'),
(18, 2, 25000000.00, 'pending', 'Abraham', '09024702105', '12 Awolowo', '2026-04-08 10:32:52', 'TXN-69d62ed4485c98.10697508'),
(19, 2, 25000000.00, 'pending', 'Abraham', '08066152639', '12 Sybil street', '2026-04-08 10:36:43', 'TXN-69d62fbb9471d4.15876560'),
(20, 6, 25000000.00, 'paid', 'Luke Skywalker', '0807645892', '12 Simon Street', '2026-04-08 13:53:48', 'TXN-69d65dec819075.34181133');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `car_id`, `price`) VALUES
(4, 4, 2, 25000000.00),
(5, 6, 2, 25000000.00),
(6, 7, 2, 25000000.00),
(7, 8, 2, 25000000.00),
(8, 9, 2, 25000000.00),
(9, 10, 2, 25000000.00),
(10, 11, 2, 25000000.00),
(11, 12, 2, 25000000.00),
(12, 13, 2, 25000000.00),
(13, 14, 2, 25000000.00),
(14, 15, 2, 25000000.00),
(15, 16, 2, 25000000.00),
(16, 17, 2, 25000000.00),
(17, 18, 2, 25000000.00),
(18, 19, 2, 25000000.00),
(19, 20, 2, 25000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('card','bank_transfer','cash') DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','super_admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `password`, `role`, `created_at`, `profile_pic`) VALUES
(2, 'Enahoro', 'Ayoola', 'Awure', 'Arkhamproctector@gmail.com', '0903333', '$2y$10$aRVWM2Rmx7biaIScEk0oGuPz6Gjcg7APyyevVwFJfCXU6Ga1v3Zu.', 'user', '2026-04-07 08:01:58', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775548919/yenchbajvdemdbkbshtg.jpg'),
(3, 'Ehigbare', 'Emmanuel', 'Awure', 'awure2k3@yahoo.com', '080239944', '$2y$10$EGmpFfg4R.bFwo23WBpJ9eBh3yufDEoLOgDF8HscvzuuKxTZUt5E6', 'super_admin', '2026-04-07 08:21:47', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775550109/n6waendozcnxjtsbwzgz.jpg'),
(4, 'John', 'Mason', 'Ayodeji', 'masonjonh12@yahoo.com', '09012342134', '$2y$10$iqq4UGsbqy7mCfzltYN92.zft45Hg4l9jGh4lR9RKeQj2YKjT3f32', 'user', '2026-04-08 06:52:15', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775631137/hkpoo8gh5dfuurykt9nw.jpg'),
(5, 'Nathaniel', 'Osagie', 'Eghomare', 'nathanieleghomare@gmail.com', '09073724442', '$2y$10$jM0BSzqNFaSHxJ7rg51Ru.ITRNPMDa1xcSm/h.ce8Lld.G2fxttGm', 'admin', '2026-04-08 13:27:51', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775654873/l8wasxgnvb6uowlhpqi0.jpg'),
(6, 'Abraham', 'Ayomide', 'abcde', 'abraayo@yaho.com', '09024702390', '$2y$10$S3YzA6g8K/yGy8vM13GR9u9QfZZcewlzeTEtbXkOIDG23sO0/fwf6', 'user', '2026-04-08 13:46:32', 'https://res.cloudinary.com/dxv8tl0at/image/upload/v1775655994/ousfog7dcfnu5rspi0fa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `car_id`, `created_at`) VALUES
(8, 4, 2, '2026-04-08 06:54:30'),
(10, 6, 2, '2026-04-08 13:48:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_car` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
