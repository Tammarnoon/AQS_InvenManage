-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 02:31 PM
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
-- Database: `db_aquatech`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cate_id` int(50) NOT NULL,
  `cate_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cate_id`, `cate_name`) VALUES
(2, 'หมวด1'),
(3, 'หมวด2'),
(4, 'หมวด3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `cus_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `tel` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`cus_id`, `name`, `address`, `tel`, `date`) VALUES
(1, 'cutomer_test', 'chiang mai', '0888888888', '2024-11-03 08:15:44'),
(3, 'customer_name', '                            customer_address', '0111111111', '2024-11-03 08:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `ref_cus_id` int(11) NOT NULL,
  `bill_number` varchar(100) NOT NULL,
  `total_price` float(10,2) NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `payment_mode` varchar(100) NOT NULL,
  `order_placed_by_id` varchar(200) NOT NULL,
  `order_paid_confirm_user` varchar(200) NOT NULL,
  `paid_money` float(10,2) NOT NULL,
  `money_change` float(10,2) NOT NULL,
  `order_date` varchar(200) NOT NULL,
  `paid_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `ref_cus_id`, `bill_number`, `total_price`, `order_status`, `payment_mode`, `order_placed_by_id`, `order_paid_confirm_user`, `paid_money`, `money_change`, `order_date`, `paid_date`) VALUES
(1, 3, 'BIL-20241103-099', 136654.33, 'ถูกยกเลิก', 'ถูกยกเลิก', 'staff', 'staff', 0.00, 0.00, '03-11-2024', '03-11-2024'),
(2, 1, 'BIL-20241103-149', 92210.33, 'จ่ายแล้ว', 'บัตรเครดิต', 'staff', 'staff', 98665.05, 0.00, '03-11-2024', '03-11-2024');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_item`
--

CREATE TABLE `tbl_order_item` (
  `id` int(11) NOT NULL,
  `ref_bill_number` varchar(200) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `price` float(10,2) NOT NULL,
  `quantity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_item`
--

INSERT INTO `tbl_order_item` (`id`, `ref_bill_number`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 'BIL-20241103-099', 3, 'product_test3', 3322.33, '1'),
(2, 'BIL-20241103-099', 2, 'product_test2', 44444.00, '3'),
(3, 'BIL-20241103-149', 2, 'product_test2', 44444.00, '2'),
(4, 'BIL-20241103-149', 3, 'product_test3', 3322.33, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(50) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_detail` text NOT NULL,
  `product_qty` int(5) NOT NULL,
  `product_price` float(10,2) NOT NULL,
  `product_img` varchar(500) NOT NULL,
  `product_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ref_cate_id` int(50) NOT NULL COMMENT 'tbl_category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_name`, `product_detail`, `product_qty`, `product_price`, `product_img`, `product_date`, `ref_cate_id`) VALUES
(1, 'product_test1', '<p>product_test1_detail</p>', 31, 233.00, '205800957420241103_091232.png', '2024-11-03 08:12:32', 2),
(2, 'product_test2', '<p>product_test3</p>', 35, 44444.00, '22505142520241103_091245.png', '2024-11-03 08:12:45', 3),
(3, 'product_test3', '<p>product_test3_detail</p>', 0, 3322.33, '32200063420241103_091305.png', '2024-11-03 08:13:05', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_in`
--

CREATE TABLE `tbl_stock_in` (
  `id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_in` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock_in`
--

INSERT INTO `tbl_stock_in` (`id`, `product_name`, `quantity`, `date_in`) VALUES
(1, 'product_test1', 30, '2024-11-03 15:14:06'),
(2, 'product_test2', 37, '2024-11-03 15:14:17'),
(3, 'product_test1', 1, '2024-11-03 15:14:24'),
(4, 'product_test3', 1, '2024-11-03 15:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `title_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `user_level` varchar(10) NOT NULL,
  `user_img` varchar(500) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `password`, `title_name`, `name`, `surname`, `user_level`, `user_img`, `date`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'นาย', 'ธรรมนูญ', 'ถนอมตระกูลชัย', 'admin', '167161278520241025_142558.png', '2024-10-19 19:29:22'),
(2, 'staff', '6ccb4b7c39a6e77f76ecfa935a855c6c46ad5611', 'นางสาว', 'staff_name', 'staff_surname', 'staff', '129998400120241025_142537.png', '2024-10-19 19:29:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cate_id`),
  ADD UNIQUE KEY `cate_name` (`cate_name`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`cus_id`),
  ADD UNIQUE KEY `tel` (`tel`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_item`
--
ALTER TABLE `tbl_order_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_stock_in`
--
ALTER TABLE `tbl_stock_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cate_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order_item`
--
ALTER TABLE `tbl_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_stock_in`
--
ALTER TABLE `tbl_stock_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
