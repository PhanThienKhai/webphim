-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2024 at 05:04 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinepass`
--

-- --------------------------------------------------------

--
-- Table structure for table `binhluan`
--

CREATE TABLE `binhluan` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `noidung` text NOT NULL,
  `ngaybinhluan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `binhluan`
--

INSERT INTO `binhluan` (`id`, `id_phim`, `id_user`, `noidung`, `ngaybinhluan`) VALUES
(9, 8, 1, 'haha', '12:28:am 04-12-2023'),
(12, 7, 2, 'lo', '01:17:pm 04-12-2023'),
(13, 26, 2, 'sợ ghê', '06:07:pm 04-12-2023'),
(14, 26, 2, 'sợ j kkk', '06:29:pm 04-12-2023'),
(15, 24, 7, 'phim hay wa', '05:27:pm 05-12-2023'),
(16, 24, 7, 'sợ ghê', '05:27:pm 05-12-2023'),
(17, 29, 9, 'Mong chờ', '09:12:am 09-12-2023'),
(18, 25, 10, 'yêu ghê', '10:16:pm 09-12-2023'),
(19, 25, 10, 'phim hay wa', '10:16:pm 09-12-2023'),
(21, 33, 2, 'Mong chờ', '08:12:pm 13-12-2023'),
(24, 33, 1, 'rat oke nha', '02:11:pm 19-01-2024'),
(25, 22, 1, 'rất oke', '02:25:pm 19-01-2024'),
(26, 33, 1, 'hi', '02:25:pm 19-01-2024'),
(27, 33, 13, 'hay nha', '04:27:pm 19-01-2024'),
(28, 33, 15, 'hay qua ', '04:34:pm 19-01-2024'),
(29, 8, 16, 'rat oke nha', '04:38:pm 19-01-2024');

-- --------------------------------------------------------

--
-- Table structure for table `hoa_don`
--

CREATE TABLE `hoa_don` (
  `id` int(11) NOT NULL,
  `ngay_tt` datetime NOT NULL,
  `trang_thai` int(1) DEFAULT 0,
  `thanh_tien` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoa_don`
--

INSERT INTO `hoa_don` (`id`, `ngay_tt`, `trang_thai`, `thanh_tien`) VALUES
(95, '2023-11-30 14:03:54', 0, 385000),
(96, '2023-11-30 16:27:55', 0, 510000),
(97, '2023-11-30 17:23:11', 1, 285000),
(98, '2023-11-30 17:27:37', 0, 285000),
(99, '2023-11-30 17:32:42', 0, 0),
(100, '2023-11-30 17:33:00', 0, 285000),
(101, '2023-11-30 17:35:24', 0, 285000),
(102, '2023-11-30 17:36:22', 0, 285000),
(103, '2023-11-30 18:07:37', 1, 285000),
(104, '2023-12-01 11:35:07', 1, 385000),
(105, '2023-12-01 12:03:11', 1, 385000),
(106, '2023-12-01 12:07:47', 1, 285000),
(107, '2023-12-01 16:12:59', 1, 985000),
(108, '2023-12-01 16:24:43', 1, 1185000),
(109, '2023-12-01 16:30:13', 1, 585000),
(110, '2023-12-01 22:56:12', 1, 3585000),
(111, '2023-12-01 23:25:23', 1, 200000),
(112, '2023-12-01 23:32:30', 0, 1185000),
(113, '2023-12-01 23:47:23', 0, 3600000),
(114, '2023-12-02 11:21:12', 1, 700000),
(115, '2023-12-02 16:27:44', 1, 285000),
(116, '2023-12-03 21:10:54', 0, 200000),
(117, '2023-12-03 21:13:29', 0, 410000),
(118, '2023-12-03 21:15:41', 1, 200000),
(119, '2023-12-04 00:35:11', 1, 1085000),
(120, '2023-12-04 10:18:51', 0, 200000),
(121, '2023-12-04 10:57:58', 0, 510000),
(122, '2023-12-04 10:59:07', 0, 510000),
(123, '2023-12-04 10:59:18', 0, 510000),
(124, '2023-12-04 11:29:53', 0, 385000),
(125, '2023-12-04 11:30:21', 0, 385000),
(126, '2023-12-04 11:30:40', 0, 385000),
(127, '2023-12-04 11:32:47', 0, 385000),
(128, '2023-12-04 11:52:08', 0, 325000),
(129, '2023-12-04 13:11:10', 0, 485000),
(130, '2023-12-04 13:39:36', 1, 685000),
(131, '2023-12-04 13:46:10', 1, 2125000),
(132, '2023-12-04 16:51:06', 0, 485000),
(133, '2023-12-04 16:56:09', 0, 485000),
(134, '2023-12-04 17:01:28', 0, 710000),
(135, '2023-12-04 17:02:23', 0, 710000),
(136, '2023-12-04 17:02:50', 0, 710000),
(137, '2023-12-04 17:03:27', 0, 710000),
(138, '2023-12-04 17:04:18', 0, 710000),
(139, '2023-12-04 20:06:52', 1, 285000),
(140, '2023-12-05 11:53:07', 0, 1025000),
(141, '2023-12-05 12:04:38', 0, 600000),
(142, '2023-12-05 12:05:36', 0, 600000),
(143, '2023-12-05 12:08:01', 0, 385000),
(144, '2023-12-05 12:08:46', 0, 385000),
(145, '2023-12-05 12:09:21', 0, 485000),
(146, '2023-12-05 12:10:47', 0, 485000),
(147, '2023-12-05 12:47:56', 1, 785000),
(148, '2023-12-05 13:25:18', 0, 585000),
(149, '2023-12-05 13:25:36', 0, 585000),
(150, '2023-12-05 13:26:04', 0, 585000),
(151, '2023-12-05 13:29:57', 0, 1085000),
(152, '2023-12-05 13:31:59', 0, 585000),
(153, '2023-12-05 14:06:20', 0, 738000),
(154, '2023-12-05 14:06:36', 0, 300000),
(155, '2023-12-05 14:32:36', 1, 1018000),
(156, '2023-12-05 18:10:36', 1, 1699000),
(157, '2023-12-05 23:39:26', 0, 1498000),
(158, '2023-12-06 21:54:46', 1, 1700000),
(159, '2023-12-06 23:01:22', 0, 499000),
(160, '2023-12-07 12:04:25', 0, 600000),
(161, '2023-12-08 11:17:09', 1, 499000),
(162, '2023-12-08 12:02:20', 1, 1499000),
(163, '2023-12-08 12:14:09', 1, 1499000),
(164, '2023-12-08 12:32:02', 1, 299000),
(165, '2023-12-08 16:42:28', 1, 1784000),
(166, '2023-12-08 17:05:02', 1, 1177000),
(167, '2023-12-08 17:11:19', 1, 1429000),
(168, '2023-12-08 17:30:43', 1, 599000),
(169, '2023-12-08 20:55:39', 1, 899000),
(170, '2023-12-08 21:20:32', 1, 1084000),
(171, '2023-12-09 16:39:07', 1, 336000),
(172, '2023-12-09 19:11:34', 0, 585000),
(173, '2023-12-09 21:38:39', 0, 400000),
(174, '2023-12-09 22:01:08', 1, 785000),
(175, '2023-12-10 14:18:22', 1, 1099000),
(176, '2023-12-10 15:12:25', 1, 799000),
(177, '2023-12-10 15:16:56', 1, 799000),
(178, '2023-12-10 15:20:22', 0, 599000),
(179, '2023-12-10 15:22:28', 0, 600000),
(180, '2023-12-10 15:24:36', 0, 799000),
(181, '2023-12-10 18:16:38', 0, 599000),
(182, '2023-12-10 22:50:30', 0, 799000),
(183, '2023-12-10 23:52:57', 0, 0),
(184, '2023-12-11 09:55:13', 0, 600000),
(185, '2023-12-11 22:16:16', 1, 799000),
(186, '2023-12-11 22:29:43', 1, 1018000),
(187, '2023-12-12 11:13:36', 0, 984000),
(188, '2023-12-12 11:14:33', 0, 984000),
(189, '2023-12-12 11:14:46', 0, 984000),
(190, '2023-12-12 11:16:41', 0, 984000),
(191, '2023-12-12 11:16:59', 0, 499000),
(192, '2023-12-12 11:19:11', 0, 499000),
(193, '2023-12-12 11:19:23', 0, 499000),
(194, '2023-12-12 11:19:35', 0, 799000),
(195, '2023-12-12 11:20:12', 1, 499000),
(196, '2023-12-12 11:35:54', 0, 899000),
(197, '2023-12-12 11:36:45', 0, 899000),
(198, '2023-12-12 11:38:28', 0, 899000),
(199, '2023-12-12 11:40:09', 0, 899000),
(200, '2023-12-12 11:40:36', 0, 499000),
(201, '2023-12-12 11:41:36', 0, 499000),
(202, '2023-12-12 11:43:34', 0, 499000),
(203, '2023-12-12 11:43:47', 0, 799000),
(204, '2023-12-12 11:44:15', 0, 799000),
(205, '2023-12-12 11:45:15', 0, 799000),
(206, '2023-12-12 11:46:08', 0, 799000),
(207, '2023-12-12 11:46:23', 0, 799000),
(208, '2023-12-12 11:47:43', 0, 799000),
(209, '2023-12-12 11:48:40', 0, 799000),
(210, '2023-12-12 11:48:55', 0, 499000),
(211, '2023-12-12 11:49:05', 0, 799000),
(212, '2023-12-12 11:49:40', 0, 559000),
(213, '2023-12-12 11:49:50', 0, 399000),
(214, '2023-12-12 11:51:27', 0, 300000),
(215, '2023-12-12 11:52:14', 0, 300000),
(216, '2023-12-12 11:52:25', 0, 300000),
(217, '2023-12-12 11:52:38', 0, 499000),
(218, '2023-12-12 11:57:14', 0, 499000),
(219, '2023-12-12 11:57:27', 0, 499000),
(220, '2023-12-12 11:58:33', 0, 499000),
(221, '2023-12-12 11:58:46', 0, 300000),
(222, '2023-12-12 11:59:16', 0, 499000),
(223, '2023-12-12 12:02:09', 0, 499000),
(224, '2023-12-12 12:02:21', 0, 499000),
(225, '2023-12-12 12:03:41', 0, 499000),
(226, '2023-12-12 12:05:09', 0, 499000),
(227, '2023-12-12 12:05:35', 0, 499000),
(228, '2023-12-12 12:06:25', 0, 499000),
(229, '2023-12-12 12:07:28', 0, 499000),
(230, '2023-12-12 12:07:41', 0, 499000),
(231, '2023-12-12 12:08:24', 0, 499000),
(232, '2023-12-12 12:09:23', 0, 499000),
(233, '2023-12-12 12:09:34', 0, 499000),
(234, '2023-12-12 12:11:52', 0, 499000),
(235, '2023-12-12 12:12:04', 0, 499000),
(236, '2023-12-12 12:12:17', 0, 499000),
(237, '2023-12-12 12:13:20', 0, 499000),
(238, '2023-12-12 12:13:29', 0, 499000),
(239, '2023-12-12 12:13:49', 0, 499000),
(240, '2023-12-12 12:14:23', 0, 499000),
(241, '2023-12-12 12:14:35', 0, 499000),
(242, '2023-12-12 12:16:25', 0, 499000),
(243, '2023-12-12 12:17:00', 0, 499000),
(244, '2023-12-12 12:17:16', 0, 499000),
(245, '2023-12-12 12:18:01', 0, 499000),
(246, '2023-12-12 12:18:30', 0, 799000),
(247, '2023-12-12 12:19:41', 0, 799000),
(248, '2023-12-12 12:19:58', 0, 499000),
(249, '2023-12-12 12:20:12', 0, 699000),
(250, '2023-12-12 12:22:38', 0, 799000),
(251, '2023-12-12 12:23:41', 0, 300000),
(252, '2023-12-12 12:29:20', 0, 400000),
(253, '2023-12-12 12:29:42', 0, 799000),
(254, '2023-12-12 12:30:25', 0, 799000),
(255, '2023-12-12 12:30:42', 1, 1003000),
(256, '2023-12-12 16:13:20', 0, 784000),
(257, '2023-12-12 16:13:39', 0, 459000),
(258, '2023-12-12 16:14:10', 1, 459000),
(259, '2023-12-12 16:26:18', 1, 1284000),
(260, '2023-12-12 20:06:08', 0, 1303000),
(261, '2023-12-12 20:06:20', 0, 499000),
(262, '2023-12-13 20:00:40', 0, 1484000),
(263, '2023-12-13 20:01:01', 0, 799000),
(264, '2023-12-13 20:01:21', 0, 599000),
(265, '2023-12-13 20:02:39', 1, 599000),
(266, '2023-12-13 23:41:31', 1, 2284000),
(267, '2023-12-13 23:44:42', 0, 499000),
(268, '2023-12-13 23:45:55', 0, 2499000),
(269, '2023-12-13 23:46:23', 0, 799000),
(270, '2023-12-15 19:33:09', 0, 984000),
(271, '2023-12-15 22:11:29', 0, 899000),
(272, '2023-12-26 10:16:02', 0, 1884000),
(273, '2023-12-26 10:16:24', 0, 499000),
(274, '2023-12-26 10:21:26', 0, 600000),
(275, '2023-12-26 10:21:42', 0, 600000),
(276, '2023-12-26 10:21:54', 0, 799000),
(277, '2023-12-26 10:22:27', 0, 300000),
(278, '2023-12-26 10:24:27', 0, 100000),
(279, '2023-12-26 10:24:56', 0, 400000),
(280, '2023-12-26 10:25:26', 0, 400000),
(281, '2023-12-26 10:26:16', 0, 400000),
(282, '2023-12-26 10:26:28', 0, 299000),
(283, '2023-12-26 10:26:39', 0, 399000),
(284, '2023-12-27 14:22:33', 0, 0),
(285, '2024-01-19 13:10:54', 0, 984000),
(286, '2024-01-19 13:16:06', 0, 599000),
(287, '2024-01-19 13:18:42', 0, 1059000),
(288, '2024-01-19 13:20:11', 0, 559000),
(289, '2024-01-19 13:25:59', 0, 200000),
(290, '2024-01-19 13:55:03', 0, 1259000),
(291, '2024-01-19 13:58:31', 0, 500000),
(292, '2024-01-19 14:01:09', 0, 859000),
(293, '2024-01-19 14:27:02', 0, 1584000),
(294, '2024-01-19 14:29:38', 0, 959000),
(295, '2024-01-19 16:29:29', 0, 1658000),
(296, '2024-01-19 16:36:18', 0, 1418000),
(297, '2024-01-19 17:11:43', 0, 500000),
(298, '2024-01-19 17:11:43', 0, 500000),
(299, '2024-01-19 17:14:02', 0, 200000),
(300, '2024-01-19 17:15:23', 0, 100000),
(301, '2024-01-19 17:18:36', 0, 400000),
(302, '2024-01-19 17:24:59', 0, 200000),
(303, '2024-01-19 17:25:00', 0, 200000),
(304, '2024-01-23 10:40:53', 0, 1259000);

-- --------------------------------------------------------

--
-- Table structure for table `khung_gio_chieu`
--

CREATE TABLE `khung_gio_chieu` (
  `id` int(11) NOT NULL,
  `id_lich_chieu` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `thoi_gian_chieu` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khung_gio_chieu`
--

INSERT INTO `khung_gio_chieu` (`id`, `id_lich_chieu`, `id_phong`, `thoi_gian_chieu`) VALUES
(6, 10, 1, '23:15:00'),
(7, 11, 1, '23:15:00'),
(8, 10, 2, '17:00:00'),
(9, 11, 2, '17:00:00'),
(10, 14, 1, '23:15:00'),
(11, 14, 1, '12:00:00'),
(12, 13, 1, '00:00:00'),
(13, 11, 1, '22:00:00'),
(14, 15, 1, '22:00:00'),
(15, 17, 1, '00:00:00'),
(16, 17, 2, '21:00:00'),
(17, 16, 1, '19:00:00'),
(18, 16, 2, '21:00:00'),
(19, 21, 2, '23:15:00'),
(20, 21, 1, '21:00:00'),
(21, 22, 1, '17:00:00'),
(22, 22, 2, '21:00:00'),
(23, 23, 2, '17:00:00'),
(24, 23, 2, '21:00:00'),
(25, 24, 1, '12:00:00'),
(26, 24, 1, '21:00:00'),
(29, 27, 2, '10:00:00'),
(30, 26, 2, '10:00:00'),
(31, 26, 2, '12:00:00'),
(32, 26, 2, '22:50:00'),
(33, 26, 1, '22:30:00'),
(34, 26, 2, '04:00:00'),
(36, 25, 1, '22:00:00'),
(39, 29, 10, '06:00:00'),
(40, 15, 10, '15:00:00'),
(41, 30, 10, '03:00:00'),
(42, 30, 1, '16:34:00'),
(44, 31, 2, '16:09:00'),
(45, 31, 10, '19:12:00'),
(46, 31, 1, '16:12:00'),
(47, 31, 1, '22:12:00'),
(48, 32, 2, '17:08:00'),
(49, 32, 2, '17:12:00'),
(50, 33, 1, '17:08:00'),
(51, 34, 1, '15:09:00'),
(52, 35, 1, '18:39:00'),
(53, 36, 1, '19:46:00'),
(54, 37, 2, '23:11:00'),
(55, 38, 1, '21:11:00'),
(56, 39, 2, '20:11:00'),
(57, 40, 1, '19:18:00'),
(58, 40, 1, '12:38:00'),
(59, 40, 2, '11:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `lichchieu`
--

CREATE TABLE `lichchieu` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `ngay_chieu` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lichchieu`
--

INSERT INTO `lichchieu` (`id`, `id_phim`, `ngay_chieu`) VALUES
(10, 6, '2023-11-27'),
(11, 7, '2023-11-27'),
(12, 8, '2023-12-01'),
(13, 8, '2023-12-02'),
(14, 5, '2023-12-04'),
(15, 6, '2023-12-02'),
(16, 7, '2023-12-09'),
(17, 7, '2023-12-16'),
(18, 27, '0000-00-00'),
(19, 27, '2023-12-23'),
(20, 27, '2023-12-19'),
(21, 24, '2024-01-03'),
(22, 24, '2023-12-27'),
(23, 24, '2023-12-31'),
(24, 27, '2024-01-01'),
(25, 26, '2023-12-30'),
(27, 29, '2024-01-12'),
(30, 33, '2024-01-12'),
(31, 33, '2024-01-20'),
(32, 30, '2024-01-21'),
(33, 25, '2024-01-22'),
(34, 24, '2024-01-19'),
(35, 25, '2024-01-25'),
(36, 26, '2024-01-20'),
(37, 5, '2024-01-20'),
(38, 7, '2024-01-20'),
(39, 22, '2024-01-20'),
(40, 28, '2024-01-27'),
(41, 28, '2024-01-23'),
(42, 28, '2024-01-24'),
(43, 28, '2024-01-25'),
(44, 28, '2024-01-26'),
(45, 28, '2024-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `loaiphim`
--

CREATE TABLE `loaiphim` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaiphim`
--

INSERT INTO `loaiphim` (`id`, `name`) VALUES
(1, 'Kinh Dị'),
(2, 'Ngôn Tình'),
(3, 'Hài'),
(5, 'Ca nhạc'),
(8, 'Cổ Trang'),
(9, 'Hoạt Hình');

-- --------------------------------------------------------

--
-- Table structure for table `phim`
--

CREATE TABLE `phim` (
  `id` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `daodien` varchar(255) NOT NULL,
  `dienvien` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `mo_ta` text NOT NULL,
  `date_phat_hanh` date NOT NULL,
  `thoi_luong_phim` int(11) NOT NULL,
  `id_loai` int(11) NOT NULL,
  `quoc_gia` varchar(255) NOT NULL,
  `gia_han_tuoi` int(11) NOT NULL,
  `link_trailer` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phim`
--

INSERT INTO `phim` (`id`, `tieu_de`, `daodien`, `dienvien`, `img`, `mo_ta`, `date_phat_hanh`, `thoi_luong_phim`, `id_loai`, `quoc_gia`, `gia_han_tuoi`, `link_trailer`) VALUES
(5, 'Kỳ án trên băng', 'Justine Triet', 'Sandra Hüller, Swann Arlaud, Milo Machado-Graner', 'Kỳ án trên đồi tuyết.jpg', 'Cuộc sống của nhà văn Sandra cùng chồng Samuel và cậu con trai mù Daniel ở căn nhà gỗ hẻo lánh trên dãy Alps bất ngờ bị xáo trộn khi Samuel được tìm thấy đã chết một cách bí ẩn trên tuyết, khiến Sandra trở thành nghi phạm chính và mối quan hệ đầy mâu thuẫn giữa cô và chồng dần được phơi bày trước phiên tòa.', '2023-12-13', 87, 5, 'Anh', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/dZdtN-Tce78?si=zqudwVQYo8d2xdOv\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(6, 'Búp bê', 'Scott Cawthon', 'Scott Cawthon, Leon Riskin, Allen Simpson', 'Năm đêm kinh hoàng.jpg', 'Scott Cawthon, Leon Riskin, Allen Simpson', '2023-12-14', 123, 9, 'Trung Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/RdW5xbBhDfk?si=nyTKv1Gzf0-6s21k\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(7, 'Đất rừng phương nam', 'Nguyễn Quang Dũng', ' Trấn Thành, Nguyễn Trinh Hoan, Nguyen Tri Vien', 'Đất rừng phương nam.jpg', 'Đất rừng phương Nam là một bộ phim điện ảnh Việt Nam thuộc thể loại sử thi – tâm lý – chính kịch ra mắt vào năm 2023, được dựa trên cuốn tiểu thuyết cùng tên của nhà văn Đoàn Giỏi và bộ phim truyền hình Đất phương Nam vào năm 1997', '2023-10-10', 123, 5, 'Việt Nam', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/hktzirCnJmQ?si=4x--iJO1e1QnLPFj\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(8, 'THE MARVELS', ' Nia DaCosta', 'Brie Larson, Samuel L. Jackson, Zawe Ashton', 'Biệt đội Marvels.jpg', 'Carol Danvers bị vướng vào sức mạnh của Kamala Khan và Monica Rambeau, buộc họ phải hợp tác với nhau để cứu vũ trụ.', '2023-11-09', 233, 2, 'Mỹ', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/rX1znA4na5I?si=3csEmDaichYD6QBZ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(22, 'QUỶ LÙN TINH NGHỊCH: ĐỒNG TÂM HIỆP NHẠC', 'Walt Dohrn, Tim Heitz', 'Anna Kendrick, Zooey Deschanel, Justin Timberlake', 'wolfoo và hòn đảo kỳ bí.jpg', 'Sự xuất hiện đột ngột của John Dory, anh trai thất lạc đã lâu của Branch mở ra quá khứ bí mật được che giấu bấy lâu nay của Branch. Đó là quá khứ về một ban nhạc có tên BroZone từng rất nổi tiếng nhưng đã tan rã. Hành trình đi tìm lại các thành viên để làm một ban nhạc như xưa trở thành chuyến phiêu lưu âm nhạc đầy cảm xúc, tràn trề hi vọng về một cuộc sum họp gia đình tuyệt với nhất.', '2023-11-23', 85, 3, 'Mỹ', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/izi44lM_HSo?si=I5JLlxyg-9NKl5nN\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(24, 'CHÚA TỂ CỦA NHỮNG CHIẾC NHẪN - SỰ TRỞ VỀ CỦA NHÀ VUA ', 'Peter Jackson', 'Elijah Wood, Viggo Mortensen, Ian McKellen,...', 'la.jpg', 'Chương cuối cùng của loạt phim Chúa Tể Của Những Chiếc Nhẫn mang tới trận chiến cuối cùng giữa thiện và ác cùng tương lai của Trung Địa. Frodo và Sam đến với Mordor trên hành trình phá hủy chiếc nhẫn, trong khi Aragon tiếp tục lãnh đạo nhóm của mình chống lại đoàn quân của Sauron. Phần phim thứ ba này được coi là thành công nhất cả loạt phim trên khía cạnh thương mại và phê bình, với doanh thu toàn cầu vượt mốc 1 tỷ đô la cùng 11 giải Oscar danh giá. (Chiếu lại từ 29/11/2023)', '2023-12-26', 125, 1, 'Ấn Độ', 18, '<iframe width=\"560px\" height=\"315px\" src=\"https://www.youtube.com/embed/4qhMENRhQxo?si=fUzhrjWRsv5t6yfF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(25, 'YÊU LẠI VỢ NGẦU', 'Nam Dae-jung', 'Kang Ha-neul, Jung So-min, Kim Sun-young, Lim Chul-hyung, Yoon Kyung-ho, Jo Min-soo,....', 'vongau.jpg', 'Cặp vợ chồng trẻ No Jung Yeol (Kang Ha-neul) và Hong Na Ra (Jung So-min) từ cuộc sống hôn nhân màu hồng dần “hiện nguyên hình” trở thành cái gai trong mắt đối phương với vô vàn thói hư, tật xấu. Không thể đi đến tiếng nói chung, Jung Yeol và Na Ra quyết định ra toà ly dị. Tuy nhiên, họ phải chờ 30 ngày cho đến khi mọi thủ tục chính thức được hoàn tất. Trong khoảng thời gian này, một vụ tai nạn xảy ra khiến cả hai mất đi ký ức và không nhớ người kia là ai. 30 ngày chờ đợi để được “đường ai nấy đi” nhưng nhiều tình huống trớ trêu khiến cả hai bắt đầu nảy sinh tình cảm trở lại. Liệu khi nhớ ra mọi thứ, họ vẫn sẽ ký tên vào tờ giấy ly hôn?', '2023-12-20', 119, 3, 'Hàn Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/081I7DXNknc?si=S1UeeKF1caKSTcAJ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(26, 'KẺ ĂN HỒN', 'Trần Hữu Tấn', 'Hoàng Hà, Võ Điền Gia Huy, Huỳnh Thanh Trực, NSƯT Chiều Xuân, Nghệ sĩ Viết Liên, NSND Ngọc Thư, Nguyễn Hữu Tiến, Nguyễn Phước Lộc, Nghinh Lộc, Lý Hồng Ân, Vũ Đức Ngọc…', 'anhon.jpg', 'him về hàng loạt cái chết bí ẩn ở Làng Địa Ngục, nơi có ma thuật cổ xưa: 5 mạng đổi bình Rượu Sọ Người. Thập Nương - cô gái áo đỏ là kẻ nắm giữ bí thuật luyện nên loại rượu mạnh nhất!', '2023-12-08', 135, 1, 'Việt Nam', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/Ac5PuRpFeRU?si=FCxQZOQ2_88BleIl\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(27, 'NGƯỜI VỢ CUỐI CÙNG', 'Victor Vũ', 'Kaity Nguyễn - Thuận Nguyễn - NSƯT Quang Thắng - NSƯT Kim Oanh - Đinh Ngọc Diệp - Anh Dũng - Quốc Huy - Bé Lưu Ly', 'nguoivo.jpg', 'Lấy cảm hứng từ tiểu thuyết Hồ Oán Hận, của nhà văn Hồng Thái, Người Vợ Cuối Cùng là một bộ phim tâm lý cổ trang, lấy bối cảnh Việt Nam vào triều Nguyễn. LINH - Người vợ bất đắc dĩ của một viên quan tri huyện, xuất thân là con của một gia đình nông dân nghèo khó, vì không thể hoàn thành nghĩa vụ sinh con nối dõi nên đã chịu sự chèn ép của những người vợ lớn trong gia đình. Sự gặp gỡ tình cờ của cô và người yêu thời thanh mai trúc mã của mình - NH N đã dẫn đến nhiều câu chuyện bất ngờ xảy ra khiến cuộc sống cô hoàn toàn thay đổi.', '2023-12-02', 132, 2, 'Việt Nam', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/TtS_V55VcxA?si=NnMnDilitNBaTXOF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(28, 'THIẾU NIÊN VÀ CHIM DIỆC', 'Miyazaki Hayao', 'Santoki Soma, Suda Masaki, Shibasaki Ko, Aimyon, Kimura Yoshino, Kimura Takuya, Kobayashi Karou', 'vietnam_-_poster_-_15.12.2023_1_.jpg', 'Đến từ Studio Ghibli và đạo diễn Miyazaki Hayao, bộ phim là câu chuyện về hành trình kỳ diệu của cậu thiếu niên Mahito trong một thế giới hoàn toàn mới lạ. Trải qua nỗi đau mất mẹ cùng mối quan hệ chẳng mấy vui vẻ với gia đình cũng như bạn học, Mahito dần cô lập bản thân... cho đến khi cậu gặp một chú chim diệc biết nói kỳ lạ. Mahito cùng chim diệc bước vào một tòa tháp bí ẩn, nơi một thế giới thần kỳ mở ra, đưa cậu gặp gỡ những người mình yêu thương... trong một nhân dạng hoàn toàn khác.', '2023-12-14', 124, 5, 'Nhật Bản', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/eggzAobZzHc?si=aQBXzb5cGIvWybYy\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(29, 'Bảy Viên Ngọc Rồng Siêu Cấp: Siêu Anh Hùng', 'Tokuda', 'Masako Nozawa,Toshio Furukawa,Yuko Minaguchi,...', 'poster.jpg', 'Đội quân Ruy Băng Đỏ đã bị Son Goku tiêu diệt. Thế nhưng, những kẻ kế nghiệp của chúng đã tạo ra hai chiến binh Android mới là Gamma 1 và Gamma 2. Hai Android này tự nhận mình là “Siêu anh hùng”. Chúng bắt đầu tấn công Piccolo và Gohan… Mục tiêu của Đội quân Ruy Băng Đỏ mới này là gì? Trước nguy cơ cận kề, đã đến lúc các siêu anh hùng thực thụ phải thức tỉnh!', '2023-12-23', 128, 8, 'Nhật Bản', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cQoNi0BVkj8?si=noUmGcjm6CJn8Rm0\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(30, 'NHỮNG KỶ NGUYÊN CỦA TAYLOR SWIFT', 'Sam Wrench', 'Taylor Swift', '700x1000_18_.jpg', 'Hiện tượng văn hóa tiếp tục trên màn ảnh lớn! Đắm chìm trong trải nghiệm xem phim hòa nhạc độc nhất vô nhị với góc nhìn ngoạn mục, đậm chất điện ảnh về chuyến lưu diễn mang tính lịch sử. Khuyến khích khán giả đeo vòng tay tình bạn và mặc trang phục Taylor Swift Eras Tour!', '2024-01-07', 168, 9, 'Mỹ', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cwLAor_smGw?si=2xnYd5m-iCFpB-Yn\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(31, 'WONKA', 'Paul King', 'Timothée Chalamet, Hugh Grant, Rowan Atkinson, Matt Lucas, Mathew Baynton.', '700x1000_22_.jpg', 'Dựa trên nhân vật từ quyến sách gối đầu giường của các em nhỏ trên toàn thế giới \"Charlie và Nhà Máy Sôcôla\" và phiên bản phim điện ảnh cùng tên vào năm 2005, WONKA kể câu chuyện kỳ diệu về hành trình của nhà phát minh, ảo thuật gia và nhà sản xuất sôcôla vĩ đại nhất thế giới trở thành WILLY WONKA đáng yêu mà chúng ta biết ngày nay. Từ đạo diễn loạt phim Paddington và nhà sản xuất loạt phim chuyển thể đình đám Harry Potter, WONKA hứa hẹn sẽ là một bộ phim đầy vui nhộn và màu sắc cho khán giả dịp Lễ Giáng Sinh năm nay.', '2023-12-17', 116, 9, 'Anh', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/1JHj4hc5MEI?si=buPaabXX7WVAd61P\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(33, 'NGƯỜI MẶT TRỜI', 'vfdv', 'df', '406x600-nmt.jpg', '400 năm qua, loài Ma Cà Rồng đã bí mật sống giữa loài người trong hòa bình, nhưng hiểm họa bỗng ập đến khi một cô gái loài người phát hiện được thân phận của hai anh em Ma Cà Rồng. Người anh khát máu quyết săn lùng cô để bảo vệ bí mật giống loài, trong khi người còn lại chạy đua với thời gian để bảo vệ cô bằng mọi giá.', '2023-12-21', 145, 9, 'dfb', 32, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/L3t9jW4eRAs?si=OwjViaUsQ2yMosxw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `phongchieu`
--

CREATE TABLE `phongchieu` (
  `id` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phongchieu`
--

INSERT INTO `phongchieu` (`id`, `id_rap`, `name`) VALUES
(1, 1, 'P101'),
(2, 2, 'P102'),
(10, 0, 'P109');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `vai_tro` int(1) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `name`, `user`, `pass`, `email`, `phone`, `dia_chi`, `vai_tro`, `img`) VALUES
(1, 'Kháchhang', 'kien481', '123456', 'lkien0408@gmail.com', '0366508004', 'Yên Bình - Phúc La - Hà Đông', 0, ''),
(2, 'loeen', 'admin kien', '12345678', 'lkien0408@gmail.com', '0366508008', 'hà nội', 0, ''),
(4, 'doraemon', 'kien', '1234', 'kienltph35295@fpt.edu.vn', '0366508004', 'hà nội', 0, ''),
(6, 'HaHa', 'kuku123', '1234', 'lkien610@gmail.com', '0358835442', 'Phú Xuyên Hà Nội', 0, ''),
(7, 'magaming', 'kien2004', '1234', 'nguyenvanbang28052004@gmail.com', '0366508004', 'hanoi', 0, ''),
(8, 'Van A', 'admin', '123456', 'kienltph35295@gmail.com', '0366508004', 'Phú Xuyên Hà Nội', 1, ''),
(9, 'Ngân Hàng', 'nganhang', '1234', 'ngan@gmail.com', '1234', 'Đà Nẵng', 0, ''),
(10, 'Kiên Trung', 'admin2', '1234', 'kien610@gmail.com', '1234567890', 'Đà nẵng', 2, ''),
(11, 'KUKA', 'kuka123', '1234', 'email.ra@gmail.com', '0123456789', 'juja2', 1, ''),
(12, 'talon', 'taloi123', '12345678', 'llo667527@gmail.com', '0366508004', 'Ấn độ', 0, ''),
(13, 'demoweb', 'demoweb', 'demoweb', 'nnn@gmail.com', '0987666644', 'hcm', 0, ''),
(14, 'nhanvien', 'nhanvien', '1234', 'nhanvien@gmail.com', '0987666642', 'nhanvien', 1, ''),
(15, 'democode', 'democode2', 'democode', 'dothanhnhan20k@gmail.com', '0364877525', 'nnnnnn', 0, ''),
(16, 'demoweb22', 'demoweb22', 'demoweb22', 'nnnnnn@gmail.com', '98765432', 'demoweb22', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ve`
--

CREATE TABLE `ve` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_thoi_gian_chieu` int(11) NOT NULL,
  `id_ngay_chieu` int(11) NOT NULL,
  `id_tk` int(11) NOT NULL,
  `ghe` varchar(255) NOT NULL,
  `combo` text NOT NULL,
  `price` varchar(10) NOT NULL,
  `id_hd` int(11) NOT NULL,
  `trang_thai` tinyint(4) NOT NULL DEFAULT 0,
  `ngay_dat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ve`
--

INSERT INTO `ve` (`id`, `id_phim`, `id_thoi_gian_chieu`, `id_ngay_chieu`, `id_tk`, `ghe`, `combo`, `price`, `id_hd`, `trang_thai`, `ngay_dat`) VALUES
(296, 33, 44, 31, 1, 'E8,D7,D6,D4,D5', 'Combo-Halo ', '1259000', 290, 2, '2024-01-19 13:55:03'),
(297, 33, 44, 31, 1, 'B9,B8,B7,B6,B5', '', '500000', 291, 1, '2024-01-19 13:58:31'),
(298, 33, 44, 31, 1, 'G7,G6', 'Combo-Halo ', '859000', 292, 0, '2024-01-19 14:01:09'),
(299, 33, 44, 31, 13, 'H4,H5,B3,C3,E4,E5', 'Combo-Wish-C1 , Combo-Halo ', '1584000', 293, 2, '2024-01-19 14:27:02'),
(300, 25, 50, 33, 13, 'D6,D5,C4,B4,A4', 'Combo-Halo ', '959000', 294, 2, '2024-01-19 14:29:38'),
(301, 33, 44, 31, 15, 'C7,E6,G5,G6,G7', 'Combo-Hủy-Diệt , Combo-Halo ', '1658000', 295, 1, '2024-01-19 16:29:29'),
(302, 33, 44, 31, 16, 'G3,E2,F2,F3,E3', 'Combo-Coca , Combo-Halo ', '1418000', 296, 1, '2024-01-19 16:36:18'),
(303, 5, 54, 37, 16, 'E5,D5,C5', '', '500000', 297, 0, '2024-01-19 17:11:43'),
(304, 5, 54, 37, 16, 'E5,D5,C5', '', '500000', 298, 1, '2024-01-19 17:11:43'),
(305, 7, 55, 38, 16, 'E4', '', '200000', 299, 1, '2024-01-19 17:14:02'),
(306, 22, 56, 39, 16, 'A3', '', '100000', 300, 1, '2024-01-19 17:15:23'),
(307, 28, 57, 40, 16, 'D5,C5,B5', '', '400000', 301, 1, '2024-01-19 17:18:36'),
(308, 33, 44, 31, 16, 'D6', '', '200000', 302, 0, '2024-01-19 17:24:59'),
(309, 33, 44, 31, 16, 'D6', '', '200000', 303, 0, '2024-01-19 17:25:00'),
(310, 28, 57, 40, 15, 'F5,E5,E4,D4,C4,B4', 'Combo-Halo ', '1259000', 304, 0, '2024-01-23 10:40:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khung_gio_chieu`
--
ALTER TABLE `khung_gio_chieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lich_chieu` (`id_lich_chieu`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Indexes for table `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`);

--
-- Indexes for table `loaiphim`
--
ALTER TABLE `loaiphim`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_loai` (`id_loai`);

--
-- Indexes for table `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tk` (`id_tk`),
  ADD KEY `ve_ibfk_2` (`id_thoi_gian_chieu`),
  ADD KEY `id_ngay_chieu` (`id_ngay_chieu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `khung_gio_chieu`
--
ALTER TABLE `khung_gio_chieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `lichchieu`
--
ALTER TABLE `lichchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `loaiphim`
--
ALTER TABLE `loaiphim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `phim`
--
ALTER TABLE `phim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `phongchieu`
--
ALTER TABLE `phongchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ve`
--
ALTER TABLE `ve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`),
  ADD CONSTRAINT `binhluan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `taikhoan` (`id`);

--
-- Constraints for table `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD CONSTRAINT `lichchieu_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
