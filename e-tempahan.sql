-- e-Mawaid database schema
-- Database: `e-tempahan` (MariaDB 10.4 / MySQL)
--
-- Import in phpMyAdmin: create a database named `e-tempahan`, then Import this file.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Users (role decides which dashboard is shown after login)
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Demo accounts for testing
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin123', 'admin'),
('user', 'user123', 'user');

-- --------------------------------------------------------
-- Bookings (tempahan) for halls (dewan) and rooms (bilik)
-- --------------------------------------------------------

CREATE TABLE `tempahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `bahagian` varchar(100) NOT NULL,
  `lain` varchar(100) NOT NULL,
  `tujuan` varchar(200) NOT NULL,
  `tempahan` enum('dewan','bilik') NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `jenis` enum('hari','jam') NOT NULL,
  `tarikh` date NOT NULL,
  `masa_mula` time(4) NOT NULL,
  `masa_akhir` time(4) NOT NULL,
  `status` enum('menunggu','lulus','gagal') DEFAULT 'menunggu',
  PRIMARY KEY (`id`),
  KEY `tt` (`user_id`),
  CONSTRAINT `tt` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
