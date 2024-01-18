-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 10:46 AM
-- Server version: 8.0.33-0ubuntu0.20.04.2
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelportal`
--

--
-- Dumping data for table `core_payment_types`
--

INSERT INTO `core_payment_types` (`id`, `name`, `code`, `description`, `is_active`, `created_at`) VALUES
(1, 'Credit', 'CREDIT', '', 1, '2017-12-08 02:47:50'),
(2, 'Cash', 'CASH', '', 1, '2017-12-08 02:47:50'),
(3, 'Cheque', 'CHEQUE', '', 1, '2018-02-01 05:58:22'),
(4, 'Bank Transfer (NEFT)', 'BANK_TRANSFER_(NEFT)', '', 1, '2017-12-08 02:47:50'),
(5, 'Credit Card', 'CREDIT_CARD', '', 1, '2017-12-08 02:47:50'),
(6, 'Debit Card', 'DEBIT_CARD', '', 1, '2017-12-08 02:47:50'),
(7, 'Bank Transfer (IMPS)', 'BANK_TRANSFER_(IMPS)', NULL, 1, '2019-08-19 06:23:01'),
(8, 'Bank Transfer (RTGS)', 'BANK_TRANSFER_(RTGS)', NULL, 1, '2019-08-19 06:23:01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
