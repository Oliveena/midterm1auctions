-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 06:19 PM
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
-- Database: `midterm1auctions`
--

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `id` int(11) NOT NULL,
  `itemDescription` varchar(1000) NOT NULL,
  `itemImagePath` varchar(200) NOT NULL,
  `sellerName` varchar(100) NOT NULL,
  `sellerEmail` varchar(320) NOT NULL,
  `lastBidPrice` decimal(10,2) NOT NULL,
  `lastBidderName` varchar(100) DEFAULT NULL,
  `lastBidderEmail` varchar(320) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`id`, `itemDescription`, `itemImagePath`, `sellerName`, `sellerEmail`, `lastBidPrice`, `lastBidderName`, `lastBidderEmail`) VALUES
(1, '<p>A pretty blue vase.</p>', 'uploads/pexels-photo-220987_67d8365e7e825.jpeg', 'Bob', 'bob@bobby.ca', 10.00, NULL, NULL),
(2, '<p>Vinfast VF6, 2025, good condition.&nbsp;</p>', 'uploads/1-2025-VinFast-VF6-front-view_67d836c6582cb.jpeg', 'Zlata', 'zlata@hotmail.com', 65000.00, NULL, NULL),
(3, '<p>A Mahindra tractor.&nbsp;</p>', 'uploads/2655HST_CLEAN_LIFESTYLEF_LOADER_12_14_17_667_67d837014cbfe.jpg', 'Agatha', 'agatha@gmail.com', 50000.00, NULL, NULL),
(4, '<p>Vintage grandfather clock, late XIX century.&nbsp;</p>', 'uploads/grandfatherclock_67d83738c036d.jpeg', 'Valeriu', 'val@valval.ca', 7777.01, 'Roger', 'roger@rabbit.re');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(2, 'Victor Hugo', 'victor@hugo.com', '$2y$10$IOvNtbqcIDx2AYvpxv5SoeEKOZhvd6ECxTICyvC4o0HHDd5BGQRw6'),
(3, 'Bob Bobby', 'bob@bobby.ca', '$2y$10$RxutBwoqq4zm.0tUU37skOskyy3tvZPQGjMUW1iTxvwOPHQlMl4Za'),
(4, 'Discovery', 'ten@eleven.com', '$2y$10$sUhIeFCTW/gtY38HzhdB/OEFbN1Gn6Ll01ibWZhvrbrqqr8B2QhJG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `itemImagePath` (`itemImagePath`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
