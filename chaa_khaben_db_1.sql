-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 09:58 PM
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
-- Database: `chaa_khaben_db_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`) VALUES
(2, 1),
(3, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `qty`, `cart_id`, `product_id`) VALUES
(67, 1, 1, 1),
(68, 6, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_image`) VALUES
(1, 'Buy Ingredients', '..\\images\\category\\buy-ingredients.jpg'),
(2, 'Tea', '..\\images\\category\\tea.webp'),
(3, 'Coffee', '..\\images\\category\\coffee.webp'),
(4, 'Snacks', '..\\images\\category\\snacks.jpeg'),
(5, 'Biscuits', '..\\images\\category\\biscuits.jpg'),
(6, 'High-Tea', '..\\images\\category\\high_tea.jpg'),
(7, 'Cookies', '..\\images\\category\\cookies.jpg'),
(8, 'Cakes', '..\\images\\category\\cakes.jpg'),
(9, 'Breads', '..\\images\\category\\bread.jpg'),
(10, 'Our Specialties', '..\\images\\our-specialities\\croissant-breakfast-sandwich.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `city_corporation`
--

CREATE TABLE `city_corporation` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city_corporation`
--

INSERT INTO `city_corporation` (`city_id`, `city_name`) VALUES
(1, 'Dhaka South'),
(2, 'Dhaka North');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `full_address` varchar(255) DEFAULT NULL,
  `city_corporation_id` int(11) DEFAULT NULL,
  `upazila_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `full_address`, `city_corporation_id`, `upazila_id`, `user_id`) VALUES
(1, 'South Mugda, Buter Adda er goli', 1, 5, 2),
(2, 'Apon Coffee House er shmane', 1, 4, 7),
(3, '8 No. North Mugda,Dhaka-1214', 1, 5, 8),
(4, NULL, NULL, NULL, 13),
(5, NULL, NULL, NULL, 14),
(6, NULL, NULL, NULL, 15),
(7, NULL, NULL, NULL, 16),
(8, NULL, NULL, NULL, 17),
(9, NULL, NULL, NULL, 18),
(10, NULL, NULL, NULL, 19),
(13, NULL, NULL, NULL, 22);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `actual_delivery_date` datetime DEFAULT NULL,
  `delivery_status` enum('pending','shipped','delivered') DEFAULT 'pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `actual_delivery_date`, `delivery_status`, `tracking_number`, `rider_id`, `order_id`, `admin_id`) VALUES
(1, NULL, 'shipped', NULL, 0, 1, 1),
(2, NULL, 'shipped', NULL, 0, 2, 1),
(3, '2025-04-07 18:42:39', 'delivered', NULL, 5, 3, NULL),
(4, '2025-04-07 18:29:33', 'delivered', NULL, 5, 4, NULL),
(5, NULL, 'shipped', NULL, 0, 5, 1),
(6, '2025-04-07 18:41:02', 'delivered', 'CHA20250407102138566', 5, 8, NULL),
(12, '2025-04-07 18:29:48', 'delivered', 'CHA20250407160734414', 5, 12, NULL),
(13, '2025-04-10 12:50:46', 'delivered', 'CHA20250409192834185', 6, 13, 1),
(14, NULL, 'shipped', 'CHA20250410122919701', 7, 14, 1),
(15, NULL, 'shipped', 'CHA20250411120737280', 2, 15, 1),
(16, '2025-04-17 19:48:06', 'delivered', 'CHA20250411120955380', 5, 16, 1),
(17, NULL, 'pending', 'CHA20250411125058917', 6, 17, NULL),
(18, NULL, 'pending', 'CHA20250411125227483', 3, 18, NULL),
(19, NULL, 'pending', 'CHA20250411130849876', 6, 19, NULL),
(20, NULL, 'pending', 'CHA20250411131003866', 6, 20, NULL),
(21, NULL, 'pending', 'CHA20250411131110273', 3, 21, NULL),
(22, NULL, 'pending', 'CHA20250411131715973', 3, 22, NULL),
(23, NULL, 'pending', 'CHA20250411181945301', 5, 23, NULL),
(25, NULL, 'shipped', 'CHA20250427182142266', 3, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `double_verification`
--

CREATE TABLE `double_verification` (
  `id` int(20) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `double_verification`
--

INSERT INTO `double_verification` (`id`, `otp`, `created_at`, `expired_at`, `user_id`) VALUES
(1, '999999', '2025-04-29 14:58:00', '2025-04-29 15:03:00', 1),
(2, '592640', '2025-04-29 16:04:37', '2025-04-29 16:09:37', 1),
(3, '281794', '2025-04-29 16:05:04', '2025-04-29 16:10:04', 1),
(4, '653892', '2025-04-29 16:05:57', '2025-04-29 16:10:57', 1),
(5, '295861', '2025-04-29 16:07:37', '2025-04-29 16:12:37', 1),
(6, '058179', '2025-04-29 16:08:10', '2025-04-29 16:13:10', 1),
(7, '390182', '2025-04-29 16:08:45', '2025-04-29 16:13:45', 1),
(8, '839426', '2025-04-29 16:14:23', '2025-04-29 16:19:23', 1),
(9, '806375', '2025-04-29 16:14:41', '2025-04-29 16:19:41', 1),
(10, '194523', '2025-04-29 16:15:16', '2025-04-29 16:20:16', 1),
(11, '260951', '2025-04-29 16:15:36', '2025-04-29 16:20:36', 1),
(12, '086752', '2025-04-29 16:16:47', '2025-04-29 16:21:47', 1),
(13, '556737', '2025-04-29 16:39:06', '2025-04-29 16:44:06', 1),
(14, '343830', '2025-04-29 16:41:53', '2025-04-29 16:46:53', 10),
(15, '896540', '2025-04-29 16:44:24', '2025-04-29 16:49:24', 2),
(16, '167303', '2025-04-30 19:40:43', '2025-04-30 15:45:43', 1),
(17, '637219', '2025-04-30 19:47:34', '2025-04-30 15:52:34', 2),
(18, '175371', '2025-04-30 19:49:18', '2025-04-30 15:54:18', 2),
(19, '856236', '2025-04-30 19:51:54', '2025-04-30 19:56:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gift_card`
--

CREATE TABLE `gift_card` (
  `gift_card_id` int(11) NOT NULL,
  `gift_amount` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `personal_note` text DEFAULT NULL,
  `receiver_name` varchar(55) NOT NULL,
  `reciever_mail` varchar(255) DEFAULT NULL,
  `sender_name` varchar(55) NOT NULL,
  `sender_mail` varchar(255) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_status` tinyint(1) DEFAULT 0,
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_card_transaction`
--

CREATE TABLE `gift_card_transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `used_amount` decimal(10,2) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `gift_card_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_products`
--

CREATE TABLE `ordered_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordered_products`
--

INSERT INTO `ordered_products` (`order_id`, `product_id`, `quantity`) VALUES
(5, 1, 1),
(5, 5, 2),
(5, 7, 1),
(5, 8, 1),
(5, 21, 3),
(5, 22, 1),
(8, 1, 2),
(8, 27, 1),
(8, 31, 1),
(8, 34, 1),
(8, 42, 1),
(12, 4, 2),
(12, 6, 2),
(12, 7, 1),
(12, 9, 1),
(12, 17, 1),
(12, 23, 1),
(12, 24, 1),
(12, 35, 1),
(13, 1, 1),
(13, 2, 1),
(13, 5, 1),
(13, 6, 1),
(14, 1, 1),
(14, 3, 1),
(14, 4, 1),
(14, 5, 1),
(14, 6, 2),
(14, 17, 2),
(14, 27, 1),
(14, 28, 1),
(14, 42, 1),
(15, 1, 1),
(15, 18, 1),
(15, 28, 1),
(16, 10, 3),
(16, 25, 3),
(16, 31, 1),
(17, 1, 1),
(18, 1, 39),
(20, 1, 37),
(21, 1, 1),
(22, 1, 1),
(23, 6, 20),
(26, 1, 1),
(26, 2, 1),
(26, 3, 2),
(26, 5, 7),
(26, 7, 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_charge` decimal(10,2) DEFAULT 50.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `total_amount`, `customer_id`, `delivery_charge`) VALUES
(1, '2025-04-06 20:30:59', 2255.00, 3, 50.00),
(2, '2025-04-06 20:37:52', 2305.00, 3, 50.00),
(3, '2025-04-07 06:48:08', 1067.00, 1, 50.00),
(4, '2025-04-07 06:53:24', 1180.00, 1, 50.00),
(5, '2025-04-07 07:05:18', 1520.00, 1, 50.00),
(6, '2025-04-07 08:16:11', 1330.00, 1, 50.00),
(7, '2025-04-07 08:18:51', 1330.00, 1, 50.00),
(8, '2025-04-07 08:21:38', 1330.00, 1, 50.00),
(12, '2025-04-07 14:07:34', 2275.00, 3, 50.00),
(13, '2025-04-09 17:28:34', 1166.00, 2, 50.00),
(14, '2025-04-10 10:29:19', 2400.00, 3, 50.00),
(15, '2025-04-11 10:07:37', 550.00, 3, 50.00),
(16, '2025-04-11 10:09:55', 1640.00, 3, 50.00),
(17, '2025-04-11 10:50:58', 350.00, 1, 50.00),
(18, '2025-04-11 10:52:27', 11750.00, 1, 50.00),
(19, '2025-04-11 11:08:49', 11150.00, 1, 50.00),
(20, '2025-04-11 11:10:03', 11150.00, 1, 50.00),
(21, '2025-04-11 11:11:11', 350.00, 1, 50.00),
(22, '2025-04-11 11:17:15', 350.00, 1, 50.00),
(23, '2025-04-11 16:19:45', 5850.00, 3, 50.00),
(24, '2025-04-27 16:18:49', 5460.00, 3, 50.00),
(25, '2025-04-27 16:19:33', 5460.00, 3, 50.00),
(26, '2025-04-27 16:21:42', 5460.00, 3, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_clear` tinyint(1) DEFAULT 0,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `coupon` varchar(50) DEFAULT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_method`, `payment_clear`, `total_amount`, `coupon`, `order_id`) VALUES
(1, 'SSLCOMMERZ', 1, 2255.00, 'orderDilam', 1),
(2, 'Cash on Delivery', 0, 2305.00, '', 2),
(3, 'SSLCOMMERZ', 1, 1067.00, 'orderDilam', 3),
(4, 'SSLCOMMERZ', 1, 1180.00, 'orderDilam', 4),
(5, 'Cash on Delivery', 0, 1520.00, '', 5),
(6, 'Cash on Delivery', 0, 1330.00, 'orderDilam', 6),
(7, 'Cash on Delivery', 0, 1330.00, 'orderDilam', 7),
(8, 'Cash on Delivery', 1, 1330.00, 'orderDilam', 8),
(12, 'SSLCOMMERZ', 1, 2275.00, 'orderDilam', 12),
(13, 'SSLCOMMERZ', 1, 1166.00, 'orderDilam', 13),
(14, 'Cash on Delivery', 0, 2400.00, 'orderDilam', 14),
(15, 'Cash on Delivery', 0, 550.00, 'orderDilam', 15),
(16, 'SSLCOMMERZ', 1, 1640.00, '', 16),
(17, 'SSLCOMMERZ', 1, 350.00, '', 17),
(18, 'SSLCOMMERZ', 1, 11750.00, '', 18),
(19, 'SSLCOMMERZ', 1, 11150.00, '', 19),
(20, 'SSLCOMMERZ', 1, 11150.00, '', 20),
(21, 'Cash on Delivery', 0, 350.00, '', 21),
(22, 'SSLCOMMERZ', 1, 350.00, '', 22),
(23, 'SSLCOMMERZ', 1, 5850.00, '', 23),
(24, 'SSLCOMMERZ', 1, 5460.00, '', 26);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_qty` int(11) DEFAULT NULL,
  `active_status` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_price`, `description`, `image`, `stock_qty`, `active_status`, `category_id`) VALUES
(1, 'Premium Tea Leaf', 300.00, 'Our premium tea leaves offer a rich, bold flavor with a smooth, aromatic finish.', '..\\images\\buy-ingredients\\chaa_pata.jpg', 1, 1, 1),
(2, 'Buttermilk Biscuits', 300.00, 'Fluffy, buttery biscuits with a hint of tangy buttermilk flavor.', '..\\images\\biscuits\\buttermilk_biscuits.jpg', 18, 1, 5),
(3, 'Nankhatai Biscuits', 250.00, 'Traditional shortbread cookies with a melt-in-your-mouth texture and a hint of cardamom.', '../images/biscuits/Nankhatai.webp', 17, 0, 5),
(4, 'Coconut Biscuits', 270.00, 'Crunchy and aromatic biscuits packed with fresh coconut flavor.', '../images/biscuits/coconut_biscuits.webp', 18, 1, 5),
(5, 'Jam-Drop Biscuits', 320.00, 'Sweet, soft biscuits filled with a dollop of fruity jam in the center.', '../images/biscuits/jam_drop_biscuits.webp', 11, 1, 5),
(6, 'Nutty Biscuits', 290.00, 'Crunchy biscuits loaded with mixed nuts for a delightful bite.', '../images/biscuits/nutty_biscuits.webp', 20, 1, 5),
(7, 'Pineapple Biscuits', 230.00, 'Sweet, fruity biscuits infused with the tropical flavor of pineapple.', '../images/biscuits/pineapple_biscuits.jpg', 10, 1, 5),
(8, 'Toast Biscuits', 260.00, 'Crisp, golden-brown biscuits with a satisfying crunch and a touch of sweetness.', '../images/biscuits/toast_biscuits.webp', 20, 1, 5),
(9, 'Digestive Biscuits', 285.00, 'Whole wheat biscuits with a light, crumbly texture, perfect for a healthy snack.', '../images/biscuits/digestive.jpg', 19, 1, 5),
(10, 'Custard Cream Biscuits', 330.00, 'Soft, creamy biscuits with a smooth custard filling in the center.', '../images/biscuits/custard_cream_biscuits.webp', 19, 1, 5),
(11, 'Cake Rusk', 290.00, 'Crunchy, twice-baked cake slices, perfect for dipping in your tea.', '../images/biscuits/cake_rusk.webp', 20, 1, 5),
(12, 'Vanilla Pound Cake', 300.00, 'Classic, buttery pound cake with a smooth vanilla flavor.', '../images/cake/vanilla_pound_cake.jpg', 20, 1, 8),
(13, 'Chocolate Pound Cake', 350.00, 'Rich, moist chocolate cake with a dense texture and decadent flavor.', '../images/cake/chocolate_pound_cake.jpg', 20, 1, 8),
(14, 'Ghee Cake', 390.00, 'A rich, aromatic cake made with ghee for a soft and indulgent treat.', '../images/cake/ghee_cake.jpeg', 20, 1, 8),
(15, 'Cappuccino', 300.00, 'A creamy, frothy coffee with a perfect balance of espresso and steamed milk.', '../images/coffee/cappuccino.jpg', 20, 1, 3),
(16, 'Iced Americano', 250.00, 'Chilled espresso served over ice for a strong, bold flavor.', '../images/coffee/iced_americano.jpg', 20, 1, 3),
(17, 'Cold Coffee', 250.00, 'Refreshing iced coffee blended with milk and a touch of sweetness.', '../images/coffee/Cold_coffee.jpg', 18, 1, 3),
(18, 'Brownie Cookies', 150.00, 'Thick, fudgy cookies with the rich taste of brownies.', '../images/cookies/brownie_cookies.jpg', 19, 1, 7),
(19, 'Chocolate Chip Cookies', 130.00, 'Soft, chewy cookies packed with delicious chocolate chips.', '../images/cookies/chocolate_chip_cookies.jpg', 20, 1, 7),
(20, 'Red Velvet Cookies', 160.00, 'Soft, buttery cookies with the distinct flavor and color of red velvet cake.', '../images/cookies/red_velvet_cookies.jpg', 20, 1, 7),
(21, 'Alu Puri', 10.00, 'Spiced potato-filled flatbreads served with a side of chutney.', '../images/snacks/alu_puri.webp', 40, 1, 4),
(22, 'Daal Puri', 10.00, 'Lentil-filled flatbreads, perfectly spiced and soft, served hot.', '../images/snacks/daal_puri.jpg', 40, 1, 4),
(23, 'Chicken Kathi Roll', 150.00, 'A flavorful wrap filled with tender chicken, veggies, and tangy sauces.', '../images/snacks/chicken_kathi_roll.jpg', 29, 1, 4),
(24, 'Paneer Kathi Roll', 140.00, 'A vegetarian wrap filled with spiced paneer, fresh veggies, and flavorful sauces.', '../images/snacks/paneer_kathi_roll.webp', 29, 1, 4),
(25, 'Mughlai Paratha', 100.00, 'Flaky, crispy paratha stuffed with a savory filling of egg and chicken.', '../images/snacks/mughlai_paratha.jpg', 39, 1, 4),
(26, 'Shingara', 15.00, 'A crispy, deep-fried pastry filled with spiced potatoes and peas.', '../images/snacks/shingara.jpeg', 50, 1, 4),
(27, 'Somucha', 20.00, 'A triangular pastry filled with chicken, vegetables, perfect for snacking.', '../images/snacks/somucha.jpg', 48, 1, 4),
(28, 'Vegetable Egg Roll', 50.00, 'A soft, rolled flatbread filled with scrambled eggs and mixed vegetables.', '../images/snacks/Vegetable-Egg-Rolls.jpg', 27, 1, 4),
(29, 'Mini Macarons', 300.00, 'Delicate, colorful almond meringue cookies filled with smooth, flavored cream.', '../images/high-tea/macarons.jpg', 15, 1, 6),
(30, 'Mini Apple Tarts', 310.00, 'Sweet, buttery tarts filled with a rich apple filling, perfect for dessert.', '../images/high-tea/mini_apple_tarts.jpg', 15, 1, 6),
(31, 'Mini Croissants', 300.00, 'Light, flaky croissants with a soft, buttery interior.', '../images/high-tea/mini_croissants.jpg', 28, 1, 6),
(32, 'Mini Sandwiches', 200.00, 'Bite-sized sandwiches with halal smoked salami, fresh egg, and crispy lettuce.', '../images/high-tea/mini_sandwiches.jpg', 30, 1, 6),
(33, 'Profiteroles', 300.00, 'Light and airy pastry puffs filled with rich cream and topped with chocolate glaze.', '../images/high-tea/profiteroles.jpg', 15, 1, 6),
(34, 'Scones', 300.00, 'Buttery, crumbly baked goods, often enjoyed with clotted cream and jam.', '../images/high-tea/scones.avif', 14, 1, 6),
(35, 'Classic Milk Tea', 50.00, 'A comforting blend of strong tea and milk, sweetened to perfection.', '../images/tea/dudh_cha.avif', 98, 1, 2),
(36, 'Ginger Tea', 20.00, 'A warm and soothing tea infused with the zesty taste of fresh ginger.', '../images/tea/ginger_tea.png', 100, 1, 2),
(37, 'Green Tea', 30.00, 'A refreshing, light tea with a delicate, earthy flavor.', '../images/tea/green_tea.webp', 50, 1, 2),
(39, 'Masala Chai', 40.00, 'A spiced tea made with a blend of aromatic spices and milk for a warming experience.', '../images/tea/Masala-Chai.jpg', 100, 1, 2),
(40, 'Matka Chai', 60.00, 'Traditional tea served in a clay cup, enhancing the flavor with its earthy aroma.', '../images/tea/Matka-Chai.jpg', 50, 1, 2),
(41, 'Tulsi Tea', 20.00, 'A fragrant herbal tea made from fresh tulsi leaves, known for its calming properties.', '../images/tea/tulsi_tea.avif', 50, 1, 2),
(42, 'English Tea', 60.00, 'A classic black tea, served with milk on the side for a traditional taste.', '../images/tea/english_tea.png', 28, 1, 2),
(43, 'Bun Kabab', 50.00, 'Spiced halal beef patty, fried egg, onions, and chutney served in a soft bun for a nostalgic street-style flavor.', '..\\images\\snacks\\bun-kabab.jpg', 50, 1, 4),
(44, 'Butter Bun', 25.00, 'Soft and fluffy buns glazed with rich butter for a melt-in-mouth experience.', '../images\\bread\\butter-bun.jpg', 60, 1, 9),
(45, 'Cheesy Garlic Bread ', 200.00, 'Toasted bread loaded with garlic butter and stretchy mozzarella cheese for the ultimate savory bite.', '..\\images\\bread\\cheesy-garlic-bread.jpg', 50, 1, 9),
(46, 'Custard Bun ', 30.00, 'Pillowy bun filled with smooth, creamy vanilla custard made from milk and eggs.', '..\\images\\bread\\custard_bun.jpg', 30, 1, 9),
(47, 'Focaccia', 350.00, 'Olive oil-rich Italian flatbread infused with rosemary and sea salt for aromatic, savory perfection.', '..\\images\\bread\\focaccia.webp', 10, 1, 9),
(48, 'Milk Bread', 150.00, 'Fluffy Japanese-style bread made with halal milk and butter, perfect for toasts or sandwiches.', '..\\images\\bread\\milk-bread.jpg', 30, 1, 9),
(49, 'Multigrain Bread', 300.00, 'Hearty and nutritious, packed with flaxseeds, oats, and whole grains for a wholesome slice.', '..\\images\\bread\\multigrain-bread.jpg', 20, 1, 9),
(50, 'Whole Wheat Bread', 250.00, 'Soft, fiber-rich bread made with 100% whole wheat flour and no preservatives.', '..\\images\\bread\\whole-wheat-bread.jpg', 20, 1, 9),
(51, 'Sourdough Bread', 350.00, 'Naturally fermented bread with a chewy crust and tangy depth of flavor.', '..\\images\\bread\\sourdough.jpg', 10, 1, 9),
(52, 'White Bread', 100.00, 'Classic soft bread made with refined flour and halal ingredients, perfect for everyday use.', '..\\images\\bread\\white-bread.jpg', 50, 1, 9),
(53, 'Croissant Breakfast Sandwich', 280.00, 'Flaky croissant sandwich filled with halal chicken sausage, scrambled egg, and cheese for a rich morning bite.', '..\\images\\our-specialities\\croissant-breakfast-sandwich.jpg', 20, 1, 10),
(54, 'Focaccia Sandwich', 280.00, 'Herb-infused focaccia loaded with fresh veggies, halal smoked turkey slices, and creamy dressing.', '..\\images\\our-specialities\\focaccia-sandwich.avif', 10, 1, 10),
(56, 'Malai Chai', 60.00, 'Thick and creamy chai infused with cardamom and full-cream milk, slow-brewed to perfection.', '..\\images\\our-specialities\\malai-cha.webp', 40, 1, 10),
(59, 'Beef Sliders', 270.00, 'Mini burger buns filled with juicy halal ground beef patties, cheddar, and our signature sauce.', '..\\images\\our-specialities\\sliders.jpg', 30, 1, 10),
(60, 'testing', 1.00, 'whaatever', '../uploads/istockphoto-1442417585-1024x1024.jpg', 10, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_audit`
--

CREATE TABLE `product_audit` (
  `audit_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `active_status` tinyint(1) DEFAULT 1,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_price` int(11) DEFAULT NULL,
  `stock_qty` int(11) DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `change_status` enum('added','updated','deleted') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_audit`
--

INSERT INTO `product_audit` (`audit_id`, `product_id`, `active_status`, `product_name`, `description`, `product_price`, `stock_qty`, `image`, `change_status`, `timestamp`, `admin_id`) VALUES
(1, 1, NULL, NULL, NULL, NULL, 40, NULL, 'updated', '2025-04-03 15:07:01', 1),
(2, 1, NULL, NULL, NULL, NULL, NULL, 0, 'updated', '2025-04-03 15:20:17', 1),
(3, 2, NULL, NULL, NULL, 330, NULL, NULL, 'updated', '2025-04-03 15:20:47', 1),
(4, 1, NULL, NULL, NULL, NULL, 45, NULL, 'updated', '2025-04-03 15:24:46', 1),
(7, 59, 1, 'Beef Sliders', 'Mini burger buns filled with juicy halal ground beef patties, cheddar, and our signature sauce.', 270, 30, 0, 'added', '2025-04-09 18:20:05', 1),
(8, 3, 0, NULL, NULL, NULL, NULL, NULL, 'updated', '2025-04-10 06:42:28', 1),
(9, 2, NULL, NULL, NULL, 300, NULL, NULL, 'updated', '2025-04-11 06:38:46', 1),
(11, 60, 1, 'testing', 'whaatever', 1, 10, 0, 'added', '2025-04-28 11:49:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rider`
--

CREATE TABLE `rider` (
  `rider_id` int(11) NOT NULL,
  `rider_active_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `pending_delivery` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rider`
--

INSERT INTO `rider` (`rider_id`, `rider_active_status`, `pending_delivery`, `user_id`) VALUES
(1, 'active', 2, 3),
(2, 'active', 4, 4),
(3, 'active', 4, 5),
(4, 'active', 4, 6),
(5, 'active', 3, 10),
(6, 'active', 4, 11),
(7, 'active', 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `rider_preferred_area`
--

CREATE TABLE `rider_preferred_area` (
  `rider_id` int(11) DEFAULT NULL,
  `city_corporation_id` int(11) DEFAULT NULL,
  `upazila_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rider_preferred_area`
--

INSERT INTO `rider_preferred_area` (`rider_id`, `city_corporation_id`, `upazila_id`) VALUES
(4, 1, 4),
(4, 1, 5),
(4, 1, 7),
(2, 2, 10),
(2, 2, 11),
(2, 2, 12),
(3, 1, 3),
(3, 1, 4),
(3, 1, 5),
(6, 1, 1),
(6, 1, 4),
(6, 1, 5),
(7, 2, 10),
(7, 2, 11),
(7, 2, 12),
(5, 2, 9),
(5, 2, 10),
(5, 2, 11);

-- --------------------------------------------------------

--
-- Table structure for table `upazila`
--

CREATE TABLE `upazila` (
  `upazila_id` int(11) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `upazila_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upazila`
--

INSERT INTO `upazila` (`upazila_id`, `city_id`, `upazila_name`) VALUES
(1, 1, 'Paltan'),
(2, 1, 'Motijheel'),
(3, 1, 'sabujbag'),
(4, 1, 'Khilgaon'),
(5, 1, 'Mugda'),
(6, 1, 'Shahjahanpur'),
(7, 1, 'Wari'),
(8, 2, 'Gulshan'),
(9, 2, 'Bhatara'),
(10, 2, 'Banani'),
(11, 2, 'Badda'),
(12, 2, 'Rampura');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `user_type` enum('admin','customer','rider') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `fname`, `lname`, `phone`, `user_type`, `image`, `password`) VALUES
(1, 'zamanaziza5@gmail.com', 'Aziza', 'Zaman', '01994457580', 'admin', '../uploads/cvphoto.jpg', '123'),
(2, 'azizazamansadia@gmail.com', 'Aziza', 'Sadia', '01950898422', 'customer', NULL, '456'),
(3, 'mokhles@gmail.com', 'Mokhles', 'Rahman', '01912154829', 'rider', NULL, '789'),
(4, 'fayaz123@gmail.com', 'Fayaz', 'Kabir', '01549863128', 'rider', NULL, '789'),
(5, 'abrar3@gmail.com', 'Abrar', 'Shikder', '01946321855', 'rider', NULL, '789'),
(6, 'zubair101@gmail.com', 'Zubair', 'Ahmed', '01854321975', 'rider', NULL, '789'),
(7, 'adiba@gmail.com', 'Adiba', 'Zaman', '01950898422', 'customer', NULL, '$2y$10$OI6wMMQTO7LhI5tAMesjnOn0T3cPhJiuILquAQp.3RIo7mAV6peqm'),
(8, 'nisa1@gmail.com', 'Nisa', 'Rahaman', '01950898422', 'customer', '../uploads/cvphoto.jpg', '$2y$10$6cjBwOqBzJKItrgy0ZlLyORNEDVVPJsgpQ2rV6ah9Zfyt3qnroAM6'),
(10, 'mahbubk@gmail.com', 'Mahbub', 'Khalil', '01986215678', 'rider', '../uploads/SrurzImage.jpg', '$2y$10$o1/X5J.bXICljKpVvNjso.ePkwAl2B0W.0m7hk2lRfuB.sVqNBaYy'),
(11, 'billalk@gmail.com', 'Billal', 'Kabir', '01651327895', 'rider', NULL, '$2y$10$bhqlo/BKMFRFToy2yjm58.Sn.44xcbr6L7QVPTOB5sH1zTIrLL79O'),
(12, 'ramzanmia@gmail.com', 'Ramzan', 'Mia', '01398675694', 'rider', NULL, '$2y$10$8PRpEkomXCpryEQY4BjSM.lGo2hGBWtIP1/hdX.hGBuuoH.MKhfAO'),
(13, 'aribakhan@gmail.com', 'Ariba', 'Khan', '01324568985', 'customer', NULL, '$2y$10$xPoWJ2UAF085rd35fw4FGeKcV.Dq.vGla9KVe7cF/l8Ki6eyy0En2'),
(14, 'kabirahmed@gmail.com', 'kabir ', 'Ahmed', '01236985632', 'customer', NULL, '$2y$10$Kv1HmybnpRmx56zdKqCwBuvZ9MOZSu1fWenyNfNFgiFg9m9bJbHZu'),
(15, 'alizaker@gmail.com', 'Ali', 'Zaker', '01568423698', 'customer', NULL, '$2y$10$cYQ0mj3ZONxY7wFnxUJcs.o9rdAlCNcYEQwjzWPTrCcYELlA1H9La'),
(16, 'mahadiazgar@gmail.com', 'Mahadi', 'Azgar', '01645893125', 'customer', NULL, '$2y$10$777TAneYPqDKL0Ho3HjdveaVBQ2vgsL27aaxvvRluxjbspNuuru/W'),
(17, 'tazizzaman@gmail.com', 'Taziz', 'Zaman', '01964258963', 'customer', NULL, '$2y$10$o/hMsVJA9vAfXFKM9z6WKuAK6KZ3HRpFVECQjognfL4VLTWgNdVPu'),
(18, 'jannat@gmail.com', 'Jannatul', 'Ferdous', '01812369452', 'customer', NULL, '$2y$10$eNMTrh6wStrx/mlGBGcSKOZ9TMnjbrteCiVOeoGO8dDOs4kwcYjoS'),
(19, 'kubrakhan@gmail.com', 'Kubra', 'Khan', '01365875236', 'customer', NULL, '$2y$10$9/JT3imDCM7QzewPKjQcfeYKz83AYr.ijnlEAGPURVKqh3H96rDo6'),
(22, 'tamin@gmail.com', 'Tamin', 'Islam', '01642589763', 'customer', NULL, '$2y$10$s1XmhMLIFfO8avIRJo82B.AKWSFq36fqhZK/9VSwkMUgrcdv5qthe');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `customer_id`, `product_id`) VALUES
(2, 1, 6),
(9, 3, 5),
(10, 3, 6),
(13, 3, 9),
(14, 3, 4),
(15, 3, 22),
(22, 3, 8),
(24, 3, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `city_corporation`
--
ALTER TABLE `city_corporation`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_ca_city` (`city_corporation_id`),
  ADD KEY `fk_ca_upazila` (`upazila_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `rider_id` (`rider_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_admin_id` (`admin_id`);

--
-- Indexes for table `double_verification`
--
ALTER TABLE `double_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gift_card`
--
ALTER TABLE `gift_card`
  ADD PRIMARY KEY (`gift_card_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `gift_card_transaction`
--
ALTER TABLE `gift_card_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `gift_card_id` (`gift_card_id`);

--
-- Indexes for table `ordered_products`
--
ALTER TABLE `ordered_products`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_customer` (`customer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_audit`
--
ALTER TABLE `product_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `rider`
--
ALTER TABLE `rider`
  ADD PRIMARY KEY (`rider_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rider_preferred_area`
--
ALTER TABLE `rider_preferred_area`
  ADD KEY `rider_id` (`rider_id`),
  ADD KEY `fk_rpa_city` (`city_corporation_id`),
  ADD KEY `fk_rpa_upazila` (`upazila_id`);

--
-- Indexes for table `upazila`
--
ALTER TABLE `upazila`
  ADD PRIMARY KEY (`upazila_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `city_corporation`
--
ALTER TABLE `city_corporation`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `double_verification`
--
ALTER TABLE `double_verification`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `gift_card`
--
ALTER TABLE `gift_card`
  MODIFY `gift_card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift_card_transaction`
--
ALTER TABLE `gift_card_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `product_audit`
--
ALTER TABLE `product_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rider`
--
ALTER TABLE `rider`
  MODIFY `rider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `upazila`
--
ALTER TABLE `upazila`
  MODIFY `upazila_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ca_city` FOREIGN KEY (`city_corporation_id`) REFERENCES `city_corporation` (`city_id`),
  ADD CONSTRAINT `fk_ca_upazila` FOREIGN KEY (`upazila_id`) REFERENCES `upazila` (`upazila_id`);

--
-- Constraints for table `gift_card`
--
ALTER TABLE `gift_card`
  ADD CONSTRAINT `gift_card_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gift_card_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
