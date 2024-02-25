-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2024 at 08:03 AM
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
-- Database: `mohammad_enterprise`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `todal_buy` varchar(255) DEFAULT NULL,
  `total_pay` varchar(255) DEFAULT NULL,
  `due` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `todal_buy`, `total_pay`, `due`, `created_at`, `updated_at`) VALUES
(5, 'Al aksa', NULL, NULL, NULL, '2024-02-23 12:39:18', '2024-02-23 12:39:18'),
(6, 'Dining Lounge', NULL, NULL, NULL, '2024-02-23 12:39:25', '2024-02-23 12:39:25'),
(7, 'Pizza town', NULL, NULL, NULL, '2024-02-23 12:39:30', '2024-02-23 12:39:30'),
(8, 'pizza burge gulshan', NULL, NULL, NULL, '2024-02-23 12:39:38', '2024-02-23 12:39:38'),
(9, 'booter adda', NULL, NULL, NULL, '2024-02-23 12:39:44', '2024-02-23 12:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_11_27_064806_create_expenses_table', 1),
(6, '2023_11_29_052513_create_customers_table', 1),
(7, '2023_11_29_054223_update_customers_table', 1),
(8, '2023_12_06_050809_create_products_table', 1),
(9, '2023_12_11_053102_create_sales_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `twelve_kg` varchar(255) DEFAULT NULL,
  `twentyfive_kg` varchar(255) DEFAULT NULL,
  `thirtythree_kg` varchar(255) DEFAULT NULL,
  `thirtyfive_kg` varchar(255) DEFAULT NULL,
  `fourtyfive_kg` varchar(255) DEFAULT NULL,
  `others_kg` varchar(255) DEFAULT NULL,
  `empty_twelve_kg` varchar(255) DEFAULT NULL,
  `empty_twentyfive_kg` varchar(255) DEFAULT NULL,
  `empty_thirtythree_kg` varchar(255) DEFAULT NULL,
  `empty_thirtyfive_kg` varchar(255) DEFAULT NULL,
  `empty_fourtyfive_kg` varchar(255) DEFAULT NULL,
  `empty_others_kg` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `twelve_kg`, `twentyfive_kg`, `thirtythree_kg`, `thirtyfive_kg`, `fourtyfive_kg`, `others_kg`, `empty_twelve_kg`, `empty_twentyfive_kg`, `empty_thirtythree_kg`, `empty_thirtyfive_kg`, `empty_fourtyfive_kg`, `empty_others_kg`, `price`, `date`, `created_at`, `updated_at`) VALUES
(2, '100', '20', NULL, '60', '10', NULL, '100', '20', NULL, '60', '10', NULL, '250000', '24-02-2024', '2024-02-23 12:38:54', '2024-02-23 12:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `twelve_kg` varchar(255) DEFAULT NULL,
  `twentyfive_kg` varchar(255) DEFAULT NULL,
  `thirtythree_kg` varchar(255) DEFAULT NULL,
  `thirtyfive_kg` varchar(255) DEFAULT NULL,
  `fourtyfive_kg` varchar(255) DEFAULT NULL,
  `others_kg` varchar(255) DEFAULT NULL,
  `empty_twelve_kg` varchar(255) DEFAULT NULL,
  `empty_twentyfive_kg` varchar(255) DEFAULT NULL,
  `empty_thirtythree_kg` varchar(255) DEFAULT NULL,
  `empty_thirtyfive_kg` varchar(255) DEFAULT NULL,
  `empty_fourtyfive_kg` varchar(255) DEFAULT NULL,
  `empty_others_kg` varchar(255) DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `is_due_bill` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `pay` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_name`, `customer_id`, `twelve_kg`, `twentyfive_kg`, `thirtythree_kg`, `thirtyfive_kg`, `fourtyfive_kg`, `others_kg`, `empty_twelve_kg`, `empty_twentyfive_kg`, `empty_thirtythree_kg`, `empty_thirtyfive_kg`, `empty_fourtyfive_kg`, `empty_others_kg`, `date`, `is_due_bill`, `price`, `pay`, `due`, `created_at`, `updated_at`) VALUES
(13, 'Pizza town', '7', '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2024-02-25', '0', '5650', '2000', '3650', '2024-02-23 12:41:32', '2024-02-23 12:41:32'),
(14, 'Pizza town', '7', NULL, NULL, NULL, NULL, NULL, NULL, '200', '100', '200', '200', '200', NULL, '2024-02-24', '1', '0', '1000', '0', '2024-02-23 12:42:38', '2024-02-23 12:42:38'),
(15, 'booter adda', '9', '1', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, '2024-02-25', '0', '1550', '500', '1050', '2024-02-24 15:05:43', '2024-02-24 15:05:43'),
(16, 'pizza burge gulshan', '8', '1', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, '2024-02-24', '0', '1600', '600', '1000', '2024-02-24 15:06:41', '2024-02-24 15:06:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
