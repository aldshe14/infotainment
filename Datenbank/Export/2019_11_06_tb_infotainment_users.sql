-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2019 at 10:38 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infotainment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_users`
--

CREATE TABLE `tb_infotainment_users` (
  `u_id` int(11) NOT NULL,
  `u_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_pswd` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_nickname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_register` datetime NOT NULL DEFAULT current_timestamp(),
  `u_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_infotainment_users`
--

INSERT INTO `tb_infotainment_users` (`u_id`, `u_email`, `u_pswd`, `u_nickname`, `u_register`, `u_role`) VALUES
(1, 'aldshe14@htl-shkoder.com', '$2y$10$JJbvQi9tniSEAVDG1U9Q2uywEQm4Jg04QjxW6wZPcYdhzalbBobeG', 'Aldo', '2019-10-04 00:00:00', 777),
(2, 'irebal14@htl-shkoder.com', '$2y$10$Ur97NHqqnE7k2jEaceZAouZuWQ1q0Uhkds2w0wng.GZkycNirs8RG', 'Irena', '2019-10-04 00:00:00', 777);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `fk_users_roles` (`u_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`u_role`) REFERENCES `tb_infotainment_roles` (`r_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
