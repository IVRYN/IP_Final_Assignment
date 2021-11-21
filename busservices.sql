-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Nov 21, 2021 at 08:42 AM
-- Server version: 10.6.4-MariaDB-1:10.6.4+maria~focal
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `busservices`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(6) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `busbooking`
--

CREATE TABLE `busbooking` (
  `booking_id` int(6) NOT NULL,
  `depart_date` date NOT NULL,
  `depart_time` time NOT NULL,
  `journey` varchar(10) NOT NULL,
  `depart_station` varchar(20) DEFAULT NULL,
  `dest_station` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `busbooking`
--

INSERT INTO `busbooking` (`booking_id`, `depart_date`, `depart_time`, `journey`, `depart_station`, `dest_station`) VALUES
(19, '2021-11-25', '12:10:00', '4hr 45mins', 'bt_pahat', 'kl_sentral'),
(37, '2021-11-25', '20:59:00', '6hr 20mins', 'bt_pahat', 'kt_terminal');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(6) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `l_name` varchar(20) NOT NULL,
  `mobilehp` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `f_name`, `l_name`, `mobilehp`, `username`, `password`) VALUES
(1, 'Ahmad', 'Al-bab', '012345678', 'user', 'ee11cbb19052e40b07aac0ca060c23ee'),
(2, 'Alibaba', 'Saluja', '01132425234', 'user_2', '15e1576abc700ddfd9438e6ad1c86100');

-- --------------------------------------------------------

--
-- Table structure for table `customer_busbooking`
--

CREATE TABLE `customer_busbooking` (
  `customer_booking_id` int(6) NOT NULL,
  `customer_id` int(6) NOT NULL,
  `booking_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_busbooking`
--

INSERT INTO `customer_busbooking` (`customer_booking_id`, `customer_id`, `booking_id`) VALUES
(11, 1, 19),
(29, 2, 37);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `busbooking`
--
ALTER TABLE `busbooking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_busbooking`
--
ALTER TABLE `customer_busbooking`
  ADD PRIMARY KEY (`customer_booking_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `busbooking`
--
ALTER TABLE `busbooking`
  MODIFY `booking_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_busbooking`
--
ALTER TABLE `customer_busbooking`
  MODIFY `customer_booking_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_busbooking`
--
ALTER TABLE `customer_busbooking`
  ADD CONSTRAINT `customer_busbooking_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_busbooking_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `busbooking` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
