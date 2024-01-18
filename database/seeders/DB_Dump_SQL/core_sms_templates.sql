-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2023 at 11:09 AM
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
-- Dumping data for table `core_sms_templates`
--

INSERT INTO `core_sms_templates` (`id`, `name`, `code`, `to_phone_no`, `content`, `created_at`) VALUES
(1, 'Booking Confirmation', 'BOOKING_CONFIRMATION', '917359841082', 'Dear Customer, #ServiceType#\nfor #Description#\non #TravelDate#\n #NoOfGuests#\n #Traveller#\nTotal Amount: #Currency#\n #Amount#\nBooking Ref: #BookingRef#\nStatus: #Status#\n\nCheck your mail for more details.', '2019-08-03 06:05:28'),
(3, 'Booking Cancellation', 'BOOKING_CANCELLATION', '7359841082', 'Dear Customer, #ServiceType#\nbooking cancellation request for #Description# \non #TravelDate# \nBooking Ref: #BookingRef#\ninitiatiated with Cancelation Ref: #CancellationRef# \nStatus : #Status#\non #DateTime# \n\nCheck your mail for more details.', '2018-07-26 00:19:05'),
(4, 'Booking Cancellation Refund Processed', 'BOOKING_CANCELLATION_REFUND_PROCESSED', NULL, 'Dear Customer, #ServiceType#\nbooking cancellation request for #Description#\non #TravelDate# \nBooking Ref: #BookingRef#\ninitiatiated with Cancelation Ref: #CancellationRef#\nhas been processed. Refund amount of #Currency#\n#Amount# has been credited in your wallet account. \n\nCheck your mail for more details.', '2018-10-13 04:36:46'),
(5, 'Wallet Credit', 'WALLET_CREDIT', NULL, '<p>Dear Customer, Amount {{Currency}} {{Amount}} credited to your wallet account with Trannsaction Ref: {{TransactionId}}. Current wallet balance is: {{Currency}} {{WalletAmount}}.</p>', '2019-07-23 13:06:16'),
(6, 'Wallet Debit', 'WALLET_DEBIT', NULL, '<p>Dear Customer, Amount {{Currency}} {{Amount}} debited from your wallet account with Trannsaction Ref: {{TransactionId}}. Current wallet balance is: {{Currency}} {{WalletAmount}}.</p>', '2018-09-17 00:42:10'),
(7, 'Quotation Generation', 'QUOTATION_GENERATION', NULL, '<p>Dear Customer, Your {{ServiceType}} quotation details for {{Description}} on {{TravelDate}}, {{NoOfGuests}} Traveller(s), Total Amount : {{Currency}} {{Amount}}, Quotation Ref: {{BookingRef}}. Check your mail for more details.</p>', '2019-08-03 06:08:39');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
