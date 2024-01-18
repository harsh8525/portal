-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 12:11 PM
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
-- Dumping data for table `core_service_types`
--

INSERT INTO `core_service_types` (`id`, `name`, `description`, `guideline`, `image`, `is_active`, `created_at`) VALUES
(1, 'DomesticFlight', '', '', '', 0, '2018-05-01 01:31:48'),
(2, 'InternationalFlight', '', '', '', 0, '2018-05-01 01:31:48'),
(3, 'DomesticHotel', '', '', '', 0, '2018-05-01 01:32:21'),
(4, 'InternationalHotel', '', '', '', 0, '2018-05-01 01:32:21'),
(5, 'Tour', 'A guided visit to one or more sites', 'e.g. walking tour, bus tour, sightseeing cruise', 'service_type_image_1691_2019_09_05_18_20_55.png', 1, '2018-05-01 01:32:35'),
(6, 'Activity', 'An instructed, hands-on experience', 'e.g. snorkelling, cooking class, surfing lesson ', 'service_type_image_2566_2019_09_05_10_39_00.jpeg', 1, '2018-05-01 01:32:35'),
(7, 'Transfer', 'The service of taking travels between locations with a focus on transportation rather than sightseeing', 'e.g. airport transfer, cruise port transfer, boat transfer', 'service_type_image_1959_2019_08_05_17_46_18.png', 0, '2018-05-01 01:32:35'),
(8, 'Ticket', '', '', '', 0, '2018-05-01 01:32:35'),
(9, 'Hotel', 'Hotel', 'Hotel', 'service_type_image_2521_2019_09_05_14_15_40.png', 1, '2019-04-12 21:36:46'),
(10, 'test', 'test', 'tesr', 'service_type_image_2410_2019_06_03_07_19_31.vnd.microsoft.icon', 0, '2019-06-03 01:49:31');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
