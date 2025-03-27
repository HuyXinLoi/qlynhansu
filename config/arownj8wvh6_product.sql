-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2025 at 10:16 PM
-- Server version: 10.11.10-MariaDB-cll-lve
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arownj8wvh6_product`
--

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `Ma_NV` varchar(3) NOT NULL,
  `Ten_NV` varchar(100) NOT NULL,
  `Phai` varchar(3) DEFAULT NULL,
  `Noi_Sinh` varchar(200) DEFAULT NULL,
  `Ma_Phong` varchar(2) DEFAULT NULL,
  `Luong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`Ma_NV`, `Ten_NV`, `Phai`, `Noi_Sinh`, `Ma_Phong`, `Luong`) VALUES
('A02', 'Trần Văn Chính', 'NAM', 'Bình Định', 'QT', 500),
('A03', 'Lê Trần Bích Yến', 'NU', 'TP HCM', 'TC', 700),
('A04', 'Trần Anh Tuấn', 'NAM', 'Hà Nội', 'KT', 800),
('AB1', 'Nguyễn Văn Võ Song Toàn', 'NAM', 'Hà Nội', 'TC', 2222000),
('B01', 'Trần Thanh Mai', 'NU', 'Hải Phòng', 'TC', 800),
('B02', 'Trần Thị Thu Thủy', 'NU', 'TP HCM', 'KT', 700),
('B03', 'Nguyễn Thị Nở', 'NU', 'Ninh Bình', 'KT', 400);

-- --------------------------------------------------------

--
-- Table structure for table `phongban`
--

CREATE TABLE `phongban` (
  `Ma_Phong` varchar(2) NOT NULL,
  `Ten_Phong` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phongban`
--

INSERT INTO `phongban` (`Ma_Phong`, `Ten_Phong`) VALUES
('KT', 'Kỹ Thuật'),
('QT', 'Quản Trị'),
('TC', 'Tài Chính');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `username`, `password`, `fullname`, `email`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin@example.com', 'admin'),
(2, 'user1', '6ad14ba9986e3615423dfca256d04e3f', 'Nguyễn Văn A', 'nguyenvana@example.com', 'user'),
(3, 'user2', 'efd398f9c21a334f1c3940de1862d5e8', 'Trần Thị B', 'tranthib@example.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`Ma_NV`),
  ADD KEY `Ma_Phong` (`Ma_Phong`);

--
-- Indexes for table `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`Ma_Phong`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`Ma_Phong`) REFERENCES `phongban` (`Ma_Phong`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
