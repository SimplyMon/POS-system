-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 04:25 PM
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
(2, 'Danica Gaila', 'male', 'admin1', 'admin1', 'counter', '2024-08-05', '2024-08-05', 'assets/uploads/profile/1722806335.png'),
(3, 'Christian', 'male', 'christ', 'pogiako', 'manager', '2024-08-10', '2024-08-10', 'assets/uploads/profile/1723224380.png'),
(4, 'Nico', 'male', 'nico', 'gwapoako', 'counter', '2024-08-10', '2024-08-10', 'assets/uploads/profile/1723224821.png');

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
(1, '392604', '', 'NONE'),
(2, '430913', '', 'NONE'),
(3, '977117', '', 'NONE'),
(4, '949995', '', 'NONE'),
(5, '424413', '', 'NONE'),
(6, '156485', '', 'NONE'),
(7, '479891', '', 'NONE'),
(8, '875383', '', 'NONE'),
(9, '666782', '', 'NONE'),
(10, '940263', '', 'NONE'),
(11, '807964', '', 'NONE'),
(12, '543372', '', 'NONE'),
(13, '700298', '', 'NONE');

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
(1, 1, '392604', '156500', 'INV-547043', '4249', '2024-07-12', 'completed', 'Cash Payment', '1'),
(2, 2, '430913', '399149', 'INV-655140', '2489', '2024-06-11', 'completed', 'Cash Payment', '1'),
(3, 3, '977117', '287866', 'INV-888142', '149', '2024-05-11', 'canceled', 'Online Payment', '2'),
(4, 4, '949995', '595766', 'INV-647267', '179', '2024-05-16', 'completed', 'Cash Payment', 'CUSTOMER'),
(5, 5, '424413', '794284', 'INV-320805', '179', '2024-05-16', 'completed', 'Cash Payment', 'CUSTOMER'),
(6, 6, '156485', '544412', 'INV-619528', '149', '2024-05-17', 'canceled', 'Cash Payment', 'CUSTOMER'),
(7, 7, '479891', '987924', 'INV-449176', '1149', '2024-05-17', 'completed', 'Cash Payment', 'CUSTOMER'),
(8, 8, '875383', '869922', 'INV-172164', '298', '2024-05-19', 'completed', 'Online Payment', 'CUSTOMER'),
(9, 9, '666782', '346327', 'INV-986359', '149', '2024-05-19', 'completed', 'Cash Payment', 'CUSTOMER'),
(10, 10, '940263', '573723', 'INV-899067', '318', '2024-05-12', 'completed', 'Cash Payment', 'CUSTOMER'),
(11, 11, '807964', '972822', 'INV-599415', '348', '2024-05-13', 'canceled', 'Cash Payment', 'CUSTOMER'),
(12, 12, '543372', '362683', 'INV-666809', '348', '2024-05-22', 'pending', 'Cash Payment', 'CUSTOMER'),
(13, 13, '700298', '801165', 'INV-215252', '436', '2024-05-22', 'pending', 'Cash Payment', 'CUSTOMER');

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
(1, 1, 2, '149', '1'),
(2, 2, 5, '169', '1'),
(3, 2, 6, '179', '1'),
(4, 3, 2, '149', '1'),
(5, 4, 3, '179', '1'),
(6, 5, 3, '179', '1'),
(7, 6, 2, '149', '1'),
(8, 7, 2, '149', '1'),
(9, 8, 2, '149', '2'),
(10, 9, 2, '149', '1'),
(11, 10, 2, '149', '1'),
(12, 10, 4, '169', '1'),
(13, 11, 6, '179', '1'),
(14, 11, 5, '169', '1'),
(15, 12, 3, '179', '1'),
(16, 12, 4, '169', '1'),
(17, 13, 3, '179', '1'),
(18, 13, 17, '99', '1'),
(19, 13, 23, '89', '1'),
(20, 13, 26, '69', '1');

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
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `image`, `status`) VALUES
(1, 1, 'Inihaw na Bangus', 'Grilled whole milkfish marinated in spices. A smoky Filipino delight!', 169, 0, 'assets/uploads/products/1722801329.JPG', 0),
(2, 3, 'Sinigang na Bangus', 'Tangy beef soup with tamarind and vegetables. A hearty Filipino classic!', 149, 97, 'assets/uploads/products/1723400151.JPG', 0),
(3, 1, 'Pitso', 'Grilled marinated chicken breast with tangy, savory flavors. A Filipino favorite!', 179, 8, 'assets/uploads/products/1722801399.JPG', 0),
(4, 1, 'Bulalo', 'Rich beef shank soup with bone marrow and vegetables. A Filipino delicacy!', 169, 3, 'assets/uploads/products/1722801490.JPG', 0),
(5, 1, 'Boneless Bangus', 'Marinated and grilled boneless milkfish. A savory Filipino treat!', 169, 48, 'assets/uploads/products/1722801652.JPG', 0),
(6, 2, 'Sizzling Bulalo', 'Tender beef shank with bone marrow, with rich gravy. A flavorful Filipino classic!', 179, 0, 'assets/uploads/products/1722801723.jpg', 0),
(7, 2, 'Bangus Sisig', 'Crispy chopped milkfish with onions, chili, and citrus. A zesty Filipino favorite!', 159, 51, 'assets/uploads/products/1722801751.png', 0),
(8, 2, 'Beef Sisig', 'Sizzling chopped beef with onions, chili, and citrus. A savory Filipino favorite!', 159, 0, 'assets/uploads/products/1722801780.JPG', 0),
(9, 3, 'Sinigang na Baka', 'Tangy beef soup with tamarind and vegetables. A hearty Filipino classic!', 149, 1, 'assets/uploads/products/1722801885.JPG', 0),
(10, 3, 'Beef Steak', 'Tender beef slices marinated in soy sauce. A savory Filipino dish!', 149, 21, 'assets/uploads/products/1722801933.JPG', 0),
(11, 3, 'Caldereta', 'Hearty beef stew with tomatoes, potatoes, carrots, and bell peppers.', 149, 53, 'assets/uploads/products/1722802060.JPG', 0),
(12, 3, 'Garlic Pepper Beef', 'Tender beef slices stir-fried with garlic and pepper. A flavorful Filipino delight!', 149, 4, 'assets/uploads/products/1722802091.jpg', 0),
(13, 3, 'Beef Broccoli', 'Tender beef stir-fried with fresh broccoli in a savory sauce. A classic favorite!', 149, 0, 'assets/uploads/products/1722802132.JPG', 0),
(14, 4, 'Cornsilog', 'Corned beef served with garlic fried rice and a fried egg. A tasty Filipino breakfast!', 99, 23, 'assets/uploads/products/1722802176.JPG', 0),
(15, 4, 'Tapsilog', 'Beef tapa served with garlic fried rice and a fried egg. A tasty Filipino breakfast!', 99, 2, 'assets/uploads/products/1722802223.JPG', 0),
(16, 4, 'Hotsilog', 'Hotdogs served with garlic fried rice and a fried egg. A tasty Filipino breakfast!', 99, 2, 'assets/uploads/products/1722802254.jpg', 0),
(17, 4, 'Chixsilog', 'Fried chicken served with garlic fried rice and a fried egg. A tasty Filipino breakfast!', 99, 114, 'assets/uploads/products/1722802280.jpg', 0),
(18, 5, 'Pancit Canton', 'Stir-fried noodles with vegetables, meat, and shrimp. A flavorful Filipino favorite!', 89, 2, 'assets/uploads/products/1722802319.JPG', 0),
(19, 5, 'Pancit Bihon', 'Stir-fried rice noodles with vegetables, meat, and shrimp. A tasty Filipino classic!', 89, 21, 'assets/uploads/products/1722802343.jpg', 0),
(20, 5, 'Palabok', 'Rice noodles topped with shrimp sauce, and egg. A perfect Filipino specialty!', 89, 16, 'assets/uploads/products/1722802380.JPG', 0),
(21, 5, 'Spaghetti', 'Filipino-style sweet spaghetti with tomato sauce, hotdogs, and cheese.', 79, 2, 'assets/uploads/products/1722802440.JPG', 0),
(22, 5, 'Burger', 'Rich beef shank soup with bone marrow and vegetables. A Filipino delicacy!', 79, 41, 'assets/uploads/products/1722802465.jpg', 0),
(23, 6, 'Chopsuey', 'A colorful mixed vegetables stir-fried in a savory sauce. A tasty Filipino side dish!', 89, 36, 'assets/uploads/products/1722802489.JPG', 0),
(24, 6, 'Kangkong', 'Stir-fried water spinach with garlic and onions. A nutritious Filipino side dish!', 89, 0, 'assets/uploads/products/1722802540.JPG', 0),
(25, 7, 'Halo-Halo', 'A Filipino dessert made with a mix of shaved ice and fruits, A delightful treat!', 89, 29, 'assets/uploads/products/1722802571.JPG', 0),
(26, 7, 'Buko Pandan', 'A creamy Filipino dessert with young coconut strips, pandan, gelatin cubes.', 69, 0, 'assets/uploads/products/1722802626.JPG', 0),
(27, 7, 'Saging con Yelo', 'A simple and refreshing Filipino dessert made with sliced bananas, crushed ice.', 69, 40, 'assets/uploads/products/1722802657.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `raw_product`
--

CREATE TABLE `raw_product` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `raw_material_name` varchar(255) NOT NULL,
  `raw_material_quantity` int(11) NOT NULL,
  `date_delivered` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_product`
--

INSERT INTO `raw_product` (`id`, `product_id`, `raw_material_name`, `raw_material_quantity`, `date_delivered`) VALUES
(2, 2, 'Bangus', 100, '2024-08-12'),
(4, 8, 'Chopped Sisig', 0, '2024-08-12'),
(6, 3, 'Chicken', 100, '2024-08-20'),
(7, 1, 'Marinated Bangus', 100, '2024-08-18'),
(8, 3, 'Chicken', 49, '2024-08-12'),
(9, 1, 'Marinated Bangus', 20, '2024-08-11'),
(10, 4, 'Bone Marrow', 16, '2024-08-11'),
(11, 2, 'Bangus', 10, '2024-08-23'),
(12, 5, 'Boneless Bangus frozen', 1, '2024-08-13'),
(13, 6, 'bulalo', 0, '2024-08-05'),
(14, 7, 'Chopped Bangus', 58, '2024-08-21'),
(15, 1, 'Marinated Bangus', 10, '2024-08-13'),
(16, 17, 'Chicken', 89, '2024-08-28'),
(17, 11, 'Beef Tenderloin', 65, '2024-08-20');

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
-- Indexes for table `raw_product`
--
ALTER TABLE `raw_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `raw_product`
--
ALTER TABLE `raw_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `raw_product`
--
ALTER TABLE `raw_product`
  ADD CONSTRAINT `raw_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
