-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 09:14 AM
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
-- Database: `library_mobile`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL COMMENT 'รหัสหนังสือ',
  `title` varchar(150) NOT NULL COMMENT 'ชื่อหนังสือ',
  `author` varchar(150) NOT NULL COMMENT 'ผู้แต่ง',
  `cover_image` varchar(255) DEFAULT NULL COMMENT 'รูปภาพ',
  `status` enum('available','borrowed') NOT NULL COMMENT 'สถานะหนังสือ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `cover_image`, `status`) VALUES
(2, 'ทฤษฎีปล่อยเขา (The Let Them Theory)', 'เมล รอบบินส์ (Mel Robbins)', '1770361751_png', 'available'),
(3, 'บทลิขิตให้เป็นนางร้าย แต่ไฉนกลายเป็นแสงจันทร์ขาวของพระเอก เล่ม 6 (จบ)', 'จี้อิง', '1770361794_png', 'available'),
(4, 'เรื่องเล่าพิสดารของเด็กสาววงแหวนเหล็กแห่งเทพเจ้า', 'ฮาจิเมะ ชิมิซุ', '1770361827_png', '');

-- --------------------------------------------------------

--
-- Table structure for table `borrows`
--

CREATE TABLE `borrows` (
  `borrow_id` int(11) NOT NULL COMMENT 'รหัสการยืม',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `book_id` int(11) NOT NULL COMMENT 'รหัสหนังสือ',
  `borrow_date` date NOT NULL COMMENT 'วันที่ยืม',
  `due_date` date NOT NULL COMMENT 'วันที่กำหนดคืน',
  `return_date` datetime DEFAULT NULL,
  `status` enum('borrowing','returned') NOT NULL COMMENT 'สถานะการยืม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrows`
--

INSERT INTO `borrows` (`borrow_id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`) VALUES
(17, 1, 4, '2026-02-06', '2026-02-14', NULL, 'borrowing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้',
  `full_name` varchar(150) NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `username` varchar(50) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `password` varchar(150) NOT NULL COMMENT 'รหัสผ่าน',
  `role` enum('member','admin') NOT NULL COMMENT 'บทบาท',
  `created_at` datetime NOT NULL COMMENT 'วันที่สมัคร',
  `email` varchar(100) NOT NULL COMMENT 'อีเมลล์',
  `phone` varchar(50) NOT NULL COMMENT 'เบอร์โทร'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `password`, `role`, `created_at`, `email`, `phone`) VALUES
(1, 'เยาว์ลักษณ์ จิน๊ะหล้า', 'Chompu', '123456', 'member', '2026-02-06 11:08:06', 'yaolak16.022@gmail.com', '0955828287'),
(2, 'Admin', 'Admin', '1234', 'admin', '2026-02-06 05:42:19', 'admin@gmail.com', '0955828287');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`borrow_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหนังสือ', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `borrows`
--
ALTER TABLE `borrows`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการยืม', AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
