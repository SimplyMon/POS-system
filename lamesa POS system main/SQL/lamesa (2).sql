-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2024 at 11:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = '+08:00';

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lamesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `gender`, `email`, `password`, `position`, `created`, `updated`, `image`) VALUES
(1, 'mon', 'male', 'admin', 'admin', 'owner', '2024-08-02', '2024-08-02', 'assets/uploads/profile/1722880110.png'),
(2, 'Danica Gaila', 'male', 'admin1', 'admin1', 'counter', '2024-08-05', '2024-08-05', 'assets/uploads/profile/1722806335.png');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=visible 1=hidden\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`) VALUES
(1, 'Paborito Meals', 'This is Paborito Meals\r\n', 1),
(2, 'Sizzling Meals', 'This is SIZZLING MEALS', 1),
(3, 'Special Meals', 'This is SPECIAL MEALS', 1),
(4, 'Breakfast Meals', 'This is BREAKFAST MEALS', 1),
(5, 'Merienda Meals', 'This is MERIENDA MEALS', 1),
(6, 'Side Dish', 'SIDE DISH', 1),
(7, 'Dessert', 'This is DESSERT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT 'NONE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`) VALUES
(1, 'Simon', '', 'NONE'),
(2, 'Lebron James', '', 'NONE');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `total_amount` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(100) DEFAULT NULL,
  `payment_mode` varchar(100) NOT NULL,
  `order_placed_by_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `customer_name`, `tracking_no`, `invoice_no`, `total_amount`, `order_date`, `order_status`, `payment_mode`, `order_placed_by_id`) VALUES
(1, 1, 'Simon', '151443', 'INV-531545', '357', '2024-08-05', 'pending', 'Online Payment', '1'),
(2, 2, 'Lebron James', '260496', 'INV-246897', '169', '2024-08-05', 'pending', 'Cash Payment', 'CUSTOMER');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 1, 17, '99', '1'),
(2, 1, 23, '89', '1'),
(3, 1, 4, '169', '1'),
(4, 2, 4, '169', '1');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `image`, `status`, `created_at`) VALUES
(1, 1, 'Inihaw na Bangus', 'this is bagnus', 169, 9, 'assets/uploads/products/1722801329.JPG', 0, '2024-08-03'),
(2, 3, 'Sinigang na Bangus', '', 149, 1, 'assets/uploads/products/1722801349.JPG', 0, '2024-08-03'),
(3, 1, 'Pitso', 'THis is Pitso', 179, 116, 'assets/uploads/products/1722801399.JPG', 0, '2024-08-04'),
(4, 1, 'Bulalo', 'bone', 169, 119, 'assets/uploads/products/1722801490.JPG', 0, '2024-08-04'),
(5, 1, 'Boneless Bangus', 'bangus', 169, 54, 'assets/uploads/products/1722801652.JPG', 0, '2024-08-05'),
(6, 2, 'Sizzling Bulalo', '', 179, 40, 'assets/uploads/products/1722801723.jpg', 0, '2024-08-05'),
(7, 2, 'Bangus Sisig', '', 159, 52, 'assets/uploads/products/1722801751.png', 0, '2024-08-05'),
(8, 2, 'Beef Sisig', '', 159, 2, 'assets/uploads/products/1722801780.JPG', 0, '2024-08-05'),
(9, 3, 'Sinigang na Baka', '', 149, 2, 'assets/uploads/products/1722801885.JPG', 0, '2024-08-05'),
(10, 3, 'Beef Steak', '', 149, 23, 'assets/uploads/products/1722801933.JPG', 0, '2024-08-05'),
(11, 3, 'Caldereta', '', 149, 52, 'assets/uploads/products/1722802060.JPG', 0, '2024-08-05'),
(12, 3, 'Garlic Pepper Beef', '', 149, 5, 'assets/uploads/products/1722802091.jpg', 0, '2024-08-05'),
(13, 3, 'Beef Broccoli', '', 149, 0, 'assets/uploads/products/1722802132.JPG', 0, '2024-08-05'),
(14, 4, 'Cornsilog', '', 99, 24, 'assets/uploads/products/1722802176.JPG', 0, '2024-08-05'),
(15, 4, 'Tapsilog', '', 99, 3, 'assets/uploads/products/1722802223.JPG', 0, '2024-08-05'),
(16, 4, 'Hotsilog', '', 99, 2, 'assets/uploads/products/1722802254.jpg', 0, '2024-08-05'),
(17, 4, 'Chixsilog', '', 99, 122, 'assets/uploads/products/1722802280.jpg', 0, '2024-08-05'),
(18, 5, 'Pancit Canton', '', 89, 2, 'assets/uploads/products/1722802319.JPG', 0, '2024-08-05'),
(19, 5, 'Pancit Bihon', '', 89, 23, 'assets/uploads/products/1722802343.jpg', 0, '2024-08-05'),
(20, 5, 'Palabok', '', 89, 24, 'assets/uploads/products/1722802380.JPG', 0, '2024-08-05'),
(21, 5, 'Spaghetti', '', 79, 4, 'assets/uploads/products/1722802440.JPG', 0, '2024-08-05'),
(22, 5, 'Burger', '', 79, 42, 'assets/uploads/products/1722802465.jpg', 0, '2024-08-05'),
(23, 6, 'Chopsuey', '', 89, 41, 'assets/uploads/products/1722802489.JPG', 0, '2024-08-05'),
(24, 6, 'Kangkong', '', 89, 0, 'assets/uploads/products/1722802540.JPG', 0, '2024-08-05'),
(25, 7, 'Halo-Halo', '', 89, 31, 'assets/uploads/products/1722802571.JPG', 0, '2024-08-05'),
(26, 7, 'Buko Pandan', '', 69, 2, 'assets/uploads/products/1722802626.JPG', 0, '2024-08-05'),
(27, 7, 'Saging con Yelo', '', 69, 42, 'assets/uploads/products/1722802657.jpg', 0, '2024-08-05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
