-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 01:26 PM
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
-- Database: `traveldwar`
--

--
-- Dumping data for table `core_suppliers`
--

INSERT INTO `core_suppliers` (`id`, `name`, `description`, `cover_image`, `is_active`, `created_at`) VALUES
(1, 'Travelionbe', 'Travelionbe', NULL, 1, '0000-00-00 00:00:00'),
(2, 'Travelportal', 'Travelportal', NULL, 1, '0000-00-00 00:00:00'),
(3, 'TBO', 'TBO', 'default_supplier_cover_image.png', 1, '0000-00-00 00:00:00'),
(4, 'Travelportal Pvt. Ltd.', 'Travelportal Pvt. Ltd.', NULL, 1, '0000-00-00 00:00:00'),
(5, 'traveportalsupplier2020', 'traveportalsupplier2020', NULL, 1, '0000-00-00 00:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
