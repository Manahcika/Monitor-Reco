-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 02:13 PM
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
-- Database: `monitors_reco`
--

-- --------------------------------------------------------

--
-- Table structure for table `monitors`
--

CREATE TABLE `monitors` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `price_idr` decimal(12,2) NOT NULL,
  `panel_type` enum('IPS','VA','TN','OLED') NOT NULL,
  `panel_size` varchar(20) NOT NULL,
  `resolution` varchar(20) NOT NULL,
  `refresh_rate` int(11) NOT NULL,
  `response_time` varchar(10) DEFAULT NULL,
  `hdr_support` tinyint(1) DEFAULT 0,
  `curvature` varchar(20) DEFAULT 'None',
  `panel_bit_depth` varchar(20) DEFAULT NULL,
  `aspect_ratio` varchar(10) DEFAULT NULL,
  `stand_capability` text DEFAULT NULL,
  `vesa_mount` tinyint(1) DEFAULT 1,
  `connectivity` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `tokopedia_link` varchar(255) DEFAULT NULL,
  `shopee_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitors`
--

INSERT INTO `monitors` (`id`, `brand`, `model`, `price_idr`, `panel_type`, `panel_size`, `resolution`, `refresh_rate`, `response_time`, `hdr_support`, `curvature`, `panel_bit_depth`, `aspect_ratio`, `stand_capability`, `vesa_mount`, `connectivity`, `image_url`, `tokopedia_link`, `shopee_link`, `created_at`, `updated_at`) VALUES
(1, 'Acer', 'EK251Q EBI', 1075000.00, 'IPS', '25 inch', '1920x1080 (FHD)', 100, '1ms', 0, 'None', '8 Bit (6 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, VGA', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(2, 'SKYWORTH', 'F24G30F GT (SOON)', 1550000.00, 'IPS', '24 inch', '1920x1080 (FHD)', 200, '1ms', 0, 'None', '8 Bit', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(3, 'BenQ', 'EX2780Q', 6100000.00, 'OLED', '27 inch', '2560x1440 (QHD)', 144, '5ms', 1, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Swivel, Height Adjustment', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 16:46:57'),
(4, 'Xiaomi', 'A27Gi', 1850000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 100, '1ms', 0, 'None', '8 Bit', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(5, 'SKYWORTH', 'H24G30Q', 1950000.00, 'IPS', '24 inch', '2560x1440 (QHD)', 180, '1ms', 0, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(6, 'SKYWORTH', 'H27G30Q', 2250000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 180, '1ms', 0, 'None', '8 Bit', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(7, 'MSI', 'MAG 255MF', 2350000.00, 'IPS', '25 inch', '1920x1080 (FHD)', 300, '1ms', 0, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(8, 'MSI', 'MAG 274QRF X24', 3300000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 240, '1ms', 0, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(9, 'Asus', 'ROG XG27WCS', 3300000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 180, '1ms', 0, 'None', '8 Bit', '16:9', 'Swivel, Height Adjustment', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(10, 'Asus', 'ROG XG27ACS', 3600000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 180, '1ms', 0, 'None', '8 Bit', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(11, 'SKYWORTH', 'F27G67Q Pro', 3700000.00, 'IPS', '27 inch', '2560x1440 (QHD)', 300, '1ms', 0, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(12, 'Lenovo', 'H27P', 3750000.00, 'OLED', '27 inch', '3840x2160 (UHD)', 60, '5ms', 1, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 16:45:55'),
(13, 'Xiaomi', 'G34WQi', 3800000.00, 'VA', '34 inch', '3440x1440 (UWQHD)', 180, '1ms', 0, '1500R', '8 Bit', '21:9', 'Swivel, Height Adjustment', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(14, 'SKYWORTH', 'H27G61UM', 4300000.00, 'IPS', '27 inch', '3840x2160 (UHD)', 160, '1ms', 0, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(15, 'Lenovo', 'Legion R25fw-30', 4900000.00, 'IPS', '25 inch', '1920x1080 (FHD)', 360, '1ms', 0, 'None', '8 Bit', '16:9', 'Swivel, Height Adjustment', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(16, 'Samsung', 'Odyssey G3 32', 5000000.00, 'OLED', '32 inch', '3840x2160 (UHD)', 60, '1ms', 1, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 16:46:20'),
(17, 'Samsung', 'M7 S43DM 70D', 5500000.00, 'OLED', '43 inch', '3840x2160 (UHD)', 60, '8ms', 1, 'None', '10 Bit (8 Bit + FRC)', '16:9', 'Tilt', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 16:46:35'),
(18, 'SKYWORTH', 'F25G65F Pro', 7100000.00, 'IPS', '25 inch', '1920x1080 (FHD)', 500, '1ms', 0, 'None', '8 Bit', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08'),
(19, 'Asus', 'ROG XG27UCG', 7100000.00, 'OLED', '27 inch', '1920x1080 (FHD)', 320, '0.03ms', 1, 'None', '10 Bit (Real)', '16:9', 'Swivel, Height Adjustment, Rotate (Pivot)', 1, 'HDMI, DP, USB-C', 'https://via.placeholder.com/300x200', NULL, NULL, '2025-08-03 07:17:08', '2025-08-03 07:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `monitor_usage`
--

CREATE TABLE `monitor_usage` (
  `id` int(11) NOT NULL,
  `monitor_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitor_usage`
--

INSERT INTO `monitor_usage` (`id`, `monitor_id`, `category_id`) VALUES
(1, 1, 3),
(2, 2, 1),
(3, 2, 3),
(4, 3, 1),
(5, 3, 2),
(6, 3, 4),
(7, 4, 3),
(8, 4, 5),
(9, 5, 1),
(10, 5, 3),
(11, 6, 1),
(12, 6, 3),
(13, 7, 1),
(14, 8, 1),
(15, 9, 1),
(16, 9, 5),
(17, 10, 1),
(18, 10, 5),
(19, 11, 1),
(20, 12, 2),
(21, 12, 4),
(22, 12, 3),
(23, 13, 1),
(24, 13, 5),
(25, 14, 2),
(26, 14, 4),
(27, 15, 1),
(28, 16, 1),
(29, 16, 3),
(30, 17, 3),
(31, 17, 4),
(32, 18, 1),
(33, 19, 1),
(34, 19, 2),
(35, 19, 4);

-- --------------------------------------------------------

--
-- Table structure for table `usage_categories`
--

CREATE TABLE `usage_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#007bff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usage_categories`
--

INSERT INTO `usage_categories` (`id`, `name`, `description`, `color`) VALUES
(1, 'Gaming', 'Cocok untuk gaming dengan refresh rate tinggi', '#28a745'),
(2, 'Editing', 'Cocok untuk editing dengan akurasi warna tinggi', '#dc3545'),
(3, 'Office', 'Cocok untuk pekerjaan kantor sehari-hari', '#007bff'),
(4, 'Content Creation', 'Cocok untuk pembuatan konten dan desain', '#fd7e14'),
(5, 'Programming', 'Cocok untuk programming dan development', '#6f42c1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monitors`
--
ALTER TABLE `monitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitor_usage`
--
ALTER TABLE `monitor_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `monitor_id` (`monitor_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `usage_categories`
--
ALTER TABLE `usage_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monitors`
--
ALTER TABLE `monitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `monitor_usage`
--
ALTER TABLE `monitor_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `usage_categories`
--
ALTER TABLE `usage_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `monitor_usage`
--
ALTER TABLE `monitor_usage`
  ADD CONSTRAINT `monitor_usage_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `monitor_usage_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `usage_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
