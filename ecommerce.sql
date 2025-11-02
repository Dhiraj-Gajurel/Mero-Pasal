-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 11:49 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(33, 9, 6, 1),
(46, 10, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Laptops'),
(6, 'Accessories'),
(7, 'Gadgets');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `payment` enum('COD','Khalti') NOT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total`, `name`, `address`, `contact`, `payment`, `payment_status`, `status`, `created_at`) VALUES
(7, 6, 7, 1, 168700.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'COD', 'Pending', 'Delivered', '2025-08-20 11:57:46'),
(8, 6, 7, 1, 168700.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'COD', 'Pending', 'Pending', '2025-08-20 12:31:28'),
(9, 6, 8, 1, 2500.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'COD', 'Pending', 'Pending', '2025-08-20 15:00:22'),
(10, 6, 10, 1, 4500.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'COD', 'Pending', 'Pending', '2025-08-20 16:55:58'),
(11, 6, 8, 2, 5000.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', '', '2025-08-20 16:57:25'),
(12, 6, 6, 1, 120000.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-20 16:57:56'),
(13, 6, 10, 1, 4500.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-20 17:14:59'),
(14, 6, 8, 1, 2500.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-20 17:15:21'),
(15, 6, 12, 1, 245999.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-20 17:18:00'),
(16, 6, 10, 4, 18000.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-21 01:17:48'),
(17, 6, 8, 2, 5000.00, 'dhiraj', 'thamel kathmandu', '98490112471', 'Khalti', 'Paid', 'Pending', '2025-08-21 03:58:28'),
(18, 10, 7, 1, 168700.00, 'Prava Gajurel', 'New York USA', '9861707126', 'Khalti', 'Paid', 'Pending', '2025-08-25 12:15:42'),
(19, 9, 7, 1, 168700.00, 'Dhiraj Gajurel', 'kalanki kathmandu', '9803397689', 'Khalti', 'Paid', 'Pending', '2025-08-29 11:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `offer` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `offer`, `category_id`, `image`) VALUES
(6, 'Acer Aspire 5 15 2023 13th Gen i7 | 8GB RAM | 512GB SSD | 15.6\" WUXGA Display | 2 Year Warranty', 'Brand 	Acer\r\nSeries	Acer Aspire Series\r\nProduct Model No 	Acer Aspire 5 A515-58P-70KL\r\nType 	Budget Notebook\r\nCPU / Processor	Intel® Core™ i7-1355U processor (Up to 5GHz, 12MB smart cache , Deca Core )\r\nMemory	8GB  DDR5 RAM\r\nStorage	512GB PCIe NVMe Gen3 SSD\r\nStorage Capability	NA\r\nGraphic Card	Intel IRIS XE Graphics\r\nDisplay Screen / Design / Resolution	15.6-inch IPS display; FHD (1920 x 1080 pixels) resolution, high-brightness Acer ComfyView LEDbacklit TFT LCD\r\nCamera	FHD (1920 x 1080) with Blue Glass\r\nOperation System	Windows 11 Home\r\nOptical Drive	-\r\nAudio & Video	Speakers\r\nNetwork / Connectivity Technology	802.11a/b/g/n/ac+ax wireless LAN\r\nDual Band (2.4 GHz and 5 GHz)\r\n2x2 MU-MIMO Technology\r\nSupports Bluetooth® 5.1 or above\r\nKeyboard Feature	Backlit\r\nSensors	FingerPrint\r\nInterface	1x USB Type-C port supporting:\r\n• USB 3.2 Gen 2 (up to 10 Gbps)\r\n• DisplayPort over USB-C\r\n• USB charging 5 V; 3 A\r\n• DC-in port 19 V; 45 W\r\n2x USB Standard A ports supporting\r\n• One port for USB 3.2 Gen 1\r\n• One port for USB 3.2 Gen 2\r\n1x HDMI® 2.1 port with HDCP support\r\n1x DC-in jack for AC adapter\r\n1x 3.5 mm headphone/speaker jack, supporting headsets with built-in microphone\r\nBattery / Power Supply	50 Wh 3-cell Li-ion battery\r\nDimensions	14.2\" (W) x 9.3\" (D) x 0.70\" (H)\r\nWeight 	4.2 Lbs\r\nWarranty	2-Year Local Warranty \r\nAcer Aspire 5 14 2023 Price in Nepal	Rs. 96,000', 120000.00, 80000.00, 4, 'uploads/Screenshot 2025-08-20 160740.png'),
(7, ' iPhone 16 Pro 128GB', 'Experience the iPhone 16 Pro, designed to elevate your everyday. Its stunning 6.1-inch Super Retina XDR display delivers unmatched clarity and vibrant colors. Powered by the ultra-efficient A18 Bionic chip, it handles everything from gaming to professional editing with ease. The 48MP Pro camera system, featuring Night Mode, 3x optical zoom, and 8K video recording, ensures every shot is flawless. With all-day battery life and 128GB storage, you have the space and power to create, work, and play effortlessly. Wrapped in a sleek stainless steel frame and premium glass back, the iPhone 16 Pro is built to impress.', 168700.00, 168000.00, 7, 'uploads/iphone-compare-iphone-16-pro-202409.jpg'),
(8, 'Havit HV-H2116D Wired Headphone with Microphone - Stereo Headset, 32Ohms Impedance, 40mm Driver', 'Speaker Input Impedance: 32 Ohms,\r\nSpeaker Sensitivity: 110 dB,\r\nConnection 3.5 mm connector (plug),\r\nVolume Controls,\r\nCable length: 2 m,\r\nProduct weight: 412,50 g,\r\n1 Year Warranty,', 2500.00, 2000.00, 6, 'uploads/3.webp'),
(10, 'Black Shark T11 TWS Wireless ENC Noise-Cancellation Earbuds , Low Latency with Sound RGB Light', 'Slide Cover Design with \"Shing\" Sound,\r\nRGB Lighting Effect,\r\nLow Latency,\r\nBluetooth 5.3,\r\n13mm Dynamic Driver,\r\n30 Hours Battery Life.', 4500.00, 4000.00, 6, 'uploads/6974521492672_main.avif'),
(11, 'Lenovo LOQ Gaming Laptop 2023 AMD Ryzen 7 7435HS | RTX 4060 | 24GB RAM | 512GB SSD | 15.6\" FHD 144Hz', 'Model:  Lenovo LOQ Gaming Laptop 2023,\r\nProcessor: AMD Ryzen™ 7 7435HS,\r\nRAM: 24GB SO-DIMM DDR5-5600,\r\nStorage: 512GB SSD,\r\nDisplay: 15.6\" FHD (1920x1080) IPS 350nits, 144Hz,\r\nGraphic: NVIDIA® GeForce RTX™ 4060 8GB GDDR6,\r\nWarranty: 1 Year Warranty.', 185000.00, 150000.00, 4, 'uploads/51POjVfR+hL.jpg'),
(12, 'Galaxy S25 Ultra 12/256GB', 'Display: 6.9″ QHD+ 120Hz (LTPO, 1-120Hz),\r\nProcessor: Snapdragon 8 Elite for Galaxy,\r\nRAM: 12GB/16GB LPDDR5X,\r\nStorage: 256GB/512GB/1TB (UFS 4.0),\r\nCameras:\r\nRear: 200MP (Wide), 50MP (Telephoto 5x), 10MP (Telephoto 3x), 50MP (Ultrawide),\r\nFront: 12MP (Wide),\r\nBattery: 5000mAh with 45W wired, 15W wireless, 4.5W reverse wireless,\r\nSoftware: One UI 7 (7 years of updates),\r\nColors: Titanium Black, Titanium Gray, Titanium Silver Blue, Titanium White Silver.', 245999.00, 245999.00, 7, 'uploads/Samsung_Galaxy_S25_Ultra_Price_in_Nepal.webp');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_ratings`
--

INSERT INTO `product_ratings` (`id`, `product_id`, `user_id`, `rating`, `created_at`) VALUES
(3, 7, 6, 1, '2025-08-20 10:40:30'),
(4, 6, 9, 5, '2025-08-20 12:37:33'),
(5, 6, 6, 5, '2025-08-20 12:52:54'),
(6, 8, 6, 5, '2025-08-20 12:53:34'),
(7, 6, 10, 1, '2025-08-20 12:58:39'),
(8, 8, 10, 1, '2025-08-25 12:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `address` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `address`, `contact`) VALUES
(1, 'admin', '$2y$10$Q5J2kW5z5Y5c5K5x5W5y5u5Q5J2kW5z5Y5c5K5x5W5y5u5', 'admin', ' lainchaur kathmandu', '98490112471'),
(2, 'user', '12345678', 'user', '', ''),
(3, 'user1', 'user123', 'user', '', ''),
(5, 'admin1', '$2y$10$ZoTMAP9wcb5/qy5IVlK.w.FN1eiS/Tp3a8ptU4515SwbUKMU5yKBG', 'admin', '', ''),
(6, 'dhiraj', '$2y$10$f3pf.2uGm7cpOnI0wufHEufOdhWA9GljGL2Sc0UuR.AUNLShuswwm', 'user', 'thamel kathmandu', '98490112471'),
(8, 'aa', '$2y$10$1glyBzazA1iAVlqVzSvZ.O4kzIFjk5qFUb.grvcQqmwqM66QTxOgG', 'user', '', ''),
(9, 'Dhiraj Gajurel', '$2y$10$hGXErKNtY9p1P0SZ1Ompc.3XBn86lDrck4GXaqSLjny4xidss7Z76', 'user', 'kalanki kathmandu', '9803397689'),
(10, 'Prava Gajurel', '$2y$10$WNcHJU3WGhZC0DdiovzqpermP4WtAgD.Dpn6AegfeWT6IgYnK6UiW', 'user', 'New York USA', '9861707126');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
