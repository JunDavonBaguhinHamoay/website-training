-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 19, 2025 at 08:03 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblreg`
--

DROP TABLE IF EXISTS `tblreg`;
CREATE TABLE IF NOT EXISTS `tblreg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dName` varchar(50) NOT NULL COMMENT '50',
  `dBreed` varchar(50) NOT NULL COMMENT '50',
  `dOwner` varchar(50) NOT NULL COMMENT '50',
  `dVaccinated` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dTownID` int DEFAULT NULL,
  `dLatitude` decimal(10,6) DEFAULT NULL,
  `dLongitude` decimal(10,6) DEFAULT NULL,
  `dRegistrationDate` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_dTownID` (`dTownID`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tblreg`
--

INSERT INTO `tblreg` (`id`, `dName`, `dBreed`, `dOwner`, `dVaccinated`, `dTownID`, `dLatitude`, `dLongitude`, `dRegistrationDate`) VALUES
(25, 'Chuckie', 'German Shepard', 'Jun Davon Hamoay', 'Yes', 14, NULL, NULL, '2025-03-18'),
(26, 'Bruno', 'Aspin', 'Price T Hamoay', 'No', 14, NULL, NULL, '2025-03-18');

-- --------------------------------------------------------

--
-- Table structure for table `towns`
--

DROP TABLE IF EXISTS `towns`;
CREATE TABLE IF NOT EXISTS `towns` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dTown` varchar(100) NOT NULL,
  `dLatitude` decimal(9,6) NOT NULL,
  `dLongitude` decimal(9,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `towns`
--

INSERT INTO `towns` (`id`, `dTown`, `dLatitude`, `dLongitude`) VALUES
(1, 'Banban', 9.603600, 124.134800),
(2, 'Bonkokan Ilaya', 9.616600, 124.127300),
(3, 'Bonkokan Ubos', 9.605400, 124.129200),
(4, 'Calvario', 9.613200, 124.071500),
(5, 'Candulang', 9.594200, 124.097400),
(6, 'Catugasan', 9.591400, 124.087000),
(7, 'Cayupo', 9.597000, 124.087400),
(8, 'Cogon', 9.605300, 124.113500),
(9, 'Jambawan', 9.621700, 124.121300),
(10, 'La Fortuna', 9.598900, 124.073900),
(11, 'Lomanoy', 9.592500, 124.081000),
(12, 'Macalingan', 9.599800, 124.105000),
(13, 'Malinao East', 9.605300, 124.122100),
(14, 'Malinao West', 9.614200, 124.113800),
(15, 'Nagsulay', 9.598600, 124.116200),
(16, 'Poblacion', 9.594200, 124.106600),
(17, 'Taug', 9.592000, 124.071500),
(18, 'Tiguis', 9.591700, 124.076200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$Am277n6qQk7ft0GZmN1ZGexr6UrH2t8t9G5TIL3Sb0egWlFXeTcyy', 'administrator'),
(2, 'Jinwoo', '$2y$10$MY77F2.D7//PnyHFl/TyJuJwDroV6uYeWqfDkh3Li7zCZHYCY5N8G', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
