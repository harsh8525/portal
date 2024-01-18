-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 01:43 PM
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
-- Dumping data for table `core_bank_details`
--

INSERT INTO `core_bank_details` (`id`, `bank_code`, `beneficiary_name`, `account_number`, `bank_name`, `bank_address`, `swift_code`, `iban_number`, `sort_code`, `status`, `created_at`) VALUES
(1, '1234', 'Amar InfoTech', '1234567890125486', 'Axis Bank', '4th Floor, Sunrise Avenue, Stadium - Commerce Six Road, Opp Saraspur Nagrik Bank, Navarangpura, Ahmedabad, Gujarat 380009', '986542', '9856421230', '654987', 1, '2019-04-26 20:11:19'),
(2, '98564', 'TravelOTAs', '9856421302145685', 'ICICI Bank', 'Ahmedabad', '546546546', '7487878748', '74874874', 0, '2019-04-26 20:14:10');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
