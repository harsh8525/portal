-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 12:14 PM
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
-- Dumping data for table `core_agency_types`
--

INSERT INTO `core_agency_types` (`id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, 'Supplier', 'Supplier', 1, '2018-10-25 08:16:49'),
(2, 'B2B', 'B2B', 1, '2018-10-25 08:16:49'),
(3, 'B2C', 'B2C', 0, '2018-10-25 08:17:17'),
(4, 'Corporate', 'Corporate', 0, '2018-10-25 08:17:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
