-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 22, 2025 lúc 08:49 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cinepass`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `binhluan`
--

CREATE TABLE `binhluan` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `noidung` text NOT NULL,
  `ngaybinhluan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `binhluan`
--

INSERT INTO `binhluan` (`id`, `id_phim`, `id_user`, `noidung`, `ngaybinhluan`) VALUES
(30, 5, 17, 'Hay quá bạn', '10:06:am 20-04-2025'),
(31, 5, 17, 'Đỉnh vãi', '09:26:pm 22-04-2025'),
(32, 5, 17, 'Hay lắm', '02:08:pm 27-05-2025');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cham_cong`
--

CREATE TABLE `cham_cong` (
  `id` int(11) NOT NULL,
  `id_nv` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `ngay` date NOT NULL,
  `gio_vao` time NOT NULL,
  `gio_ra` time NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cham_cong`
--

INSERT INTO `cham_cong` (`id`, `id_nv`, `id_rap`, `ngay`, `gio_vao`, `gio_ra`, `ghi_chu`, `ngay_tao`) VALUES
(1, 30, 1, '2025-09-18', '06:07:00', '17:00:00', '', '2025-09-18 15:07:21'),
(2, 34, 1, '2025-09-18', '08:00:00', '17:00:00', 'làm tốt', '2025-09-18 15:14:40'),
(4, 34, 1, '2025-09-18', '08:00:00', '17:00:00', '', '2025-09-18 15:54:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_analytics`
--

CREATE TABLE `chat_analytics` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `total_messages` int(11) DEFAULT 0,
  `user_satisfaction` tinyint(1) DEFAULT NULL,
  `resolved_issues` text DEFAULT NULL,
  `chat_duration` int(11) DEFAULT NULL,
  `bot_accuracy` decimal(5,4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_user_message` tinyint(1) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `intent` varchar(100) DEFAULT NULL,
  `confidence` decimal(5,4) DEFAULT NULL,
  `response_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_combo`
--

CREATE TABLE `chi_tiet_combo` (
  `id` int(11) NOT NULL,
  `id_combo` int(11) NOT NULL,
  `ten_mon` varchar(255) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  `don_gia` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_combo`
--

INSERT INTO `chi_tiet_combo` (`id`, `id_combo`, `ten_mon`, `so_luong`, `don_gia`) VALUES
(1, 1, 'Bắp rang bơ', 1, 30000.00),
(2, 1, 'Nước ngọt', 1, 15000.00),
(3, 2, 'Bắp rang bơ', 1, 30000.00),
(4, 2, 'Nước ngọt', 1, 15000.00),
(5, 2, 'Hotdog', 1, 40000.00),
(6, 3, 'Bắp rang bơ', 2, 30000.00),
(7, 3, 'Nước ngọt', 2, 15000.00),
(8, 3, 'Bánh ngọt', 1, 30000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combo_do_an`
--

CREATE TABLE `combo_do_an` (
  `id` int(11) NOT NULL,
  `ten_combo` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `combo_do_an`
--

INSERT INTO `combo_do_an` (`id`, `ten_combo`, `mo_ta`, `gia`, `hinh_anh`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Combo Standard', 'Bắp rang + Nước ngọt', 45000.00, 'combo_standard.jpg', 1, '2025-08-15 14:48:59'),
(2, 'Combo Premium', 'Bắp rang + Nước ngọt + Hotdog', 85000.00, 'combo_premium.jpg', 1, '2025-08-15 14:48:59'),
(3, 'Combo Family', '2 Bắp rang + 2 Nước ngọt + 1 Bánh ngọt', 120000.00, 'combo_family.jpg', 1, '2025-08-15 14:48:59'),
(4, 'Combo VIP', 'Bắp rang + Nước ngọt + Hotdog + Bánh ngọt + Kẹo', 150000.00, 'combo_vip.jpg', 1, '2025-08-15 14:48:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_nghi_phep`
--

CREATE TABLE `don_nghi_phep` (
  `id` int(11) NOT NULL,
  `id_nhan_vien` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `tu_ngay` date NOT NULL,
  `den_ngay` date NOT NULL,
  `ly_do` varchar(255) NOT NULL,
  `trang_thai` enum('Chờ duyệt','Đã duyệt','Từ chối') DEFAULT 'Chờ duyệt',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_nghi_phep`
--

INSERT INTO `don_nghi_phep` (`id`, `id_nhan_vien`, `id_rap`, `tu_ngay`, `den_ngay`, `ly_do`, `trang_thai`, `ngay_tao`) VALUES
(2, 30, 1, '2025-10-01', '2025-10-05', 'Về quê cưới chồng', 'Đã duyệt', '2025-09-04 07:33:45'),
(3, 30, 1, '2025-09-11', '2025-09-14', 'Bệnh', 'Từ chối', '2025-09-04 08:04:17'),
(4, 34, 1, '2025-09-22', '2025-09-28', 'Về quê cưới vợ', 'Đã duyệt', '2025-09-22 05:28:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe_ngoi`
--

CREATE TABLE `ghe_ngoi` (
  `id` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `ten_ghe` varchar(10) NOT NULL,
  `loai_ghe` enum('standard','vip','couple','disabled') DEFAULT 'standard',
  `gia_ghe` decimal(10,2) NOT NULL,
  `trang_thai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe_ngoi`
--

INSERT INTO `ghe_ngoi` (`id`, `id_phong`, `ten_ghe`, `loai_ghe`, `gia_ghe`, `trang_thai`) VALUES
(1, 1, 'A1', 'standard', 100000.00, 1),
(2, 1, 'A2', 'standard', 100000.00, 1),
(3, 1, 'A3', 'standard', 100000.00, 1),
(4, 1, 'A4', 'standard', 100000.00, 1),
(5, 1, 'A5', 'standard', 100000.00, 1),
(6, 1, 'B1', 'vip', 150000.00, 1),
(7, 1, 'B2', 'vip', 150000.00, 1),
(8, 1, 'B3', 'vip', 150000.00, 1),
(9, 1, 'C1', 'couple', 200000.00, 1),
(10, 1, 'C2', 'couple', 200000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `id` int(11) NOT NULL,
  `ngay_tt` datetime NOT NULL,
  `trang_thai` int(1) DEFAULT 0,
  `thanh_tien` int(10) NOT NULL,
  `id_khuyen_mai` int(11) DEFAULT NULL,
  `tien_giam` decimal(10,2) DEFAULT 0.00,
  `thanh_toan_cuoi` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`id`, `ngay_tt`, `trang_thai`, `thanh_tien`, `id_khuyen_mai`, `tien_giam`, `thanh_toan_cuoi`) VALUES
(1, '2025-04-20 10:15:44', 0, 659000, NULL, 0.00, 0.00),
(2, '2025-04-20 10:25:52', 0, 459000, NULL, 0.00, 0.00),
(3, '2025-04-22 21:23:56', 0, 658000, NULL, 0.00, 0.00),
(4, '2025-04-23 12:36:43', 0, 200000, NULL, 0.00, 0.00),
(5, '2025-04-23 12:37:45', 0, 459000, NULL, 0.00, 0.00),
(6, '2025-04-23 12:43:23', 0, 425000, NULL, 0.00, 0.00),
(7, '2025-04-23 13:24:13', 0, 625000, NULL, 0.00, 0.00),
(8, '2025-04-23 13:25:41', 0, 1346000, NULL, 0.00, 0.00),
(9, '2025-04-23 13:26:51', 0, 859000, NULL, 0.00, 0.00),
(10, '2025-04-23 13:31:10', 0, 399000, NULL, 0.00, 0.00),
(11, '2025-04-25 17:57:38', 0, 425000, NULL, 0.00, 0.00),
(12, '2025-05-04 13:06:49', 0, 100000, NULL, 0.00, 0.00),
(13, '2025-05-25 21:31:39', 0, 459000, NULL, 0.00, 0.00),
(14, '2025-05-26 18:33:03', 0, 459000, NULL, 0.00, 0.00),
(15, '2025-05-26 19:24:37', 0, 100000, NULL, 0.00, 0.00),
(16, '2025-05-26 19:30:05', 0, 359000, NULL, 0.00, 0.00),
(17, '2025-05-26 20:46:23', 0, 559000, NULL, 0.00, 0.00),
(18, '2025-05-26 21:08:55', 0, 1883000, NULL, 0.00, 0.00),
(19, '2025-05-26 21:16:31', 0, 300000, NULL, 0.00, 0.00),
(20, '2025-05-27 11:57:27', 0, 200000, NULL, 0.00, 0.00),
(21, '2025-05-27 12:04:00', 0, 100000, NULL, 0.00, 0.00),
(22, '2025-05-27 15:58:25', 0, 300000, NULL, 0.00, 0.00),
(23, '2025-05-27 19:11:50', 0, 100000, NULL, 0.00, 0.00),
(24, '2025-05-28 08:32:47', 0, 359000, NULL, 0.00, 0.00),
(25, '2025-06-30 16:35:53', 0, 800000, NULL, 0.00, 0.00),
(26, '2025-07-02 09:19:16', 0, 859000, NULL, 0.00, 0.00),
(27, '2025-08-07 16:08:20', 0, 600000, NULL, 0.00, 0.00),
(28, '2025-09-04 15:34:33', 0, 200000, NULL, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khung_gio_chieu`
--

CREATE TABLE `khung_gio_chieu` (
  `id` int(11) NOT NULL,
  `id_lich_chieu` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `thoi_gian_chieu` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khung_gio_chieu`
--

INSERT INTO `khung_gio_chieu` (`id`, `id_lich_chieu`, `id_phong`, `thoi_gian_chieu`) VALUES
(1, 1, 1, '14:00:00'),
(2, 1, 1, '17:00:00'),
(3, 1, 1, '20:00:00'),
(4, 2, 1, '14:00:00'),
(5, 2, 1, '17:00:00'),
(6, 2, 1, '20:00:00'),
(7, 3, 1, '14:00:00'),
(8, 3, 1, '17:00:00'),
(9, 3, 1, '20:00:00'),
(10, 4, 1, '14:00:00'),
(11, 4, 1, '17:00:00'),
(12, 4, 1, '20:00:00'),
(13, 5, 1, '14:00:00'),
(14, 5, 1, '17:00:00'),
(15, 5, 1, '20:00:00'),
(16, 6, 1, '14:00:00'),
(17, 6, 1, '17:00:00'),
(18, 6, 1, '20:00:00'),
(19, 7, 1, '14:00:00'),
(20, 7, 1, '17:00:00'),
(21, 7, 1, '20:00:00'),
(22, 8, 1, '14:00:00'),
(23, 8, 1, '17:00:00'),
(24, 8, 1, '20:00:00'),
(25, 9, 1, '14:00:00'),
(26, 9, 1, '17:00:00'),
(27, 9, 1, '20:00:00'),
(28, 10, 1, '14:00:00'),
(29, 10, 1, '17:00:00'),
(30, 10, 1, '20:00:00'),
(31, 11, 1, '14:00:00'),
(32, 11, 1, '17:00:00'),
(33, 11, 1, '20:00:00'),
(34, 12, 1, '14:00:00'),
(35, 12, 1, '17:00:00'),
(36, 12, 1, '20:00:00'),
(37, 13, 1, '14:00:00'),
(38, 13, 1, '17:00:00'),
(39, 13, 1, '20:00:00'),
(40, 14, 1, '14:00:00'),
(41, 14, 1, '17:00:00'),
(42, 14, 1, '20:00:00'),
(43, 15, 1, '14:00:00'),
(44, 15, 1, '17:00:00'),
(45, 15, 1, '20:00:00'),
(46, 16, 1, '14:00:00'),
(47, 16, 1, '17:00:00'),
(48, 16, 1, '20:00:00'),
(49, 17, 1, '14:00:00'),
(50, 17, 1, '17:00:00'),
(51, 17, 1, '20:00:00'),
(52, 18, 1, '14:00:00'),
(53, 18, 1, '17:00:00'),
(54, 18, 1, '20:00:00'),
(55, 19, 1, '14:00:00'),
(56, 19, 1, '17:00:00'),
(57, 19, 1, '20:00:00'),
(58, 20, 1, '14:00:00'),
(59, 20, 1, '17:00:00'),
(60, 20, 1, '20:00:00'),
(61, 21, 1, '14:00:00'),
(62, 21, 1, '17:00:00'),
(63, 21, 1, '20:00:00'),
(64, 22, 1, '14:00:00'),
(65, 22, 1, '17:00:00'),
(66, 22, 1, '20:00:00'),
(67, 23, 1, '14:00:00'),
(68, 23, 1, '17:00:00'),
(69, 23, 1, '20:00:00'),
(70, 24, 1, '14:00:00'),
(71, 24, 1, '17:00:00'),
(72, 24, 1, '20:00:00'),
(73, 25, 1, '14:00:00'),
(74, 25, 1, '17:00:00'),
(75, 25, 1, '20:00:00'),
(76, 26, 1, '14:00:00'),
(77, 26, 1, '17:00:00'),
(78, 26, 1, '20:00:00'),
(79, 27, 1, '14:00:00'),
(80, 27, 1, '17:00:00'),
(81, 27, 1, '20:00:00'),
(82, 28, 1, '14:00:00'),
(83, 28, 1, '17:00:00'),
(84, 28, 1, '20:00:00'),
(85, 29, 1, '14:00:00'),
(86, 29, 1, '17:00:00'),
(87, 29, 1, '20:00:00'),
(88, 30, 1, '14:00:00'),
(89, 30, 1, '17:00:00'),
(90, 30, 1, '20:00:00'),
(91, 30, 1, '23:00:00'),
(92, 65, 1, '16:15:00'),
(93, 67, 16, '11:00:00'),
(94, 68, 16, '19:00:00'),
(95, 69, 16, '20:00:00'),
(96, 70, 16, '19:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `id` int(11) NOT NULL,
  `ten_khuyen_mai` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `phan_tram_giam` decimal(5,2) NOT NULL,
  `gia_tri_giam` decimal(10,2) DEFAULT 0.00,
  `loai_giam` enum('phan_tram','tien_mat') NOT NULL DEFAULT 'phan_tram',
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `dieu_kien_ap_dung` text DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`id`, `ten_khuyen_mai`, `mo_ta`, `phan_tram_giam`, `gia_tri_giam`, `loai_giam`, `ngay_bat_dau`, `ngay_ket_thuc`, `dieu_kien_ap_dung`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Khuyến mãi sinh nhật', 'Giảm 20% cho khách hàng trong tháng sinh nhật', 20.00, 0.00, 'phan_tram', '2025-01-01', '2025-12-31', 'Áp dụng cho khách hàng có sinh nhật trong tháng', 1, '2025-08-15 14:49:08'),
(2, 'Khuyến mãi học sinh sinh viên', 'Giảm 15% cho HSSV với thẻ học sinh/sinh viên', 15.00, 0.00, 'phan_tram', '2025-01-01', '2025-12-31', 'Xuất trình thẻ HSSV hợp lệ', 1, '2025-08-15 14:49:08'),
(3, 'Khuyến mãi ngày lễ', 'Giảm 10% cho tất cả khách hàng vào ngày lễ', 10.00, 0.00, 'phan_tram', '2025-01-01', '2025-12-31', 'Áp dụng vào các ngày lễ quốc gia', 1, '2025-08-15 14:49:08'),
(4, 'Khuyến mãi combo', 'Giảm 50000đ khi mua combo từ 200000đ', 0.00, 50000.00, 'tien_mat', '2025-01-01', '2025-12-31', 'Giá trị combo từ 200000đ trở lên', 1, '2025-08-15 14:49:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichchieu`
--

CREATE TABLE `lichchieu` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `ngay_chieu` date NOT NULL DEFAULT current_timestamp(),
  `trang_thai_duyet` enum('Chờ duyệt','Đã duyệt','Từ chối') NOT NULL DEFAULT 'Chờ duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lichchieu`
--

INSERT INTO `lichchieu` (`id`, `id_phim`, `id_rap`, `ngay_chieu`, `trang_thai_duyet`) VALUES
(1, 5, 1, '2026-05-29', 'Đã duyệt'),
(2, 5, 1, '2026-05-31', 'Đã duyệt'),
(3, 6, 1, '2026-05-29', 'Đã duyệt'),
(4, 6, 1, '2026-05-31', 'Đã duyệt'),
(5, 7, 1, '2026-05-29', 'Đã duyệt'),
(6, 7, 1, '2026-05-31', 'Đã duyệt'),
(7, 8, 1, '2026-05-29', 'Đã duyệt'),
(8, 8, 1, '2026-05-31', 'Đã duyệt'),
(9, 22, 1, '2026-05-29', 'Đã duyệt'),
(10, 22, 1, '2026-05-31', 'Đã duyệt'),
(11, 24, 1, '2026-05-29', 'Đã duyệt'),
(12, 24, 1, '2026-05-31', 'Đã duyệt'),
(13, 25, 1, '2026-05-29', 'Đã duyệt'),
(14, 25, 1, '2026-05-31', 'Đã duyệt'),
(15, 26, 1, '2026-05-29', 'Đã duyệt'),
(16, 26, 1, '2026-05-31', 'Đã duyệt'),
(17, 27, 1, '2026-05-29', 'Đã duyệt'),
(18, 27, 1, '2026-05-31', 'Đã duyệt'),
(19, 28, 1, '2026-05-29', 'Đã duyệt'),
(20, 28, 1, '2026-05-31', 'Đã duyệt'),
(21, 29, 1, '2026-05-29', 'Đã duyệt'),
(22, 29, 1, '2026-05-31', 'Đã duyệt'),
(23, 30, 1, '2026-05-29', 'Đã duyệt'),
(24, 30, 1, '2026-05-31', 'Đã duyệt'),
(25, 31, 1, '2026-05-29', 'Đã duyệt'),
(26, 31, 1, '2026-05-31', 'Đã duyệt'),
(27, 33, 1, '2026-05-29', 'Đã duyệt'),
(28, 33, 1, '2026-05-31', 'Đã duyệt'),
(29, 36, 1, '2026-05-29', 'Đã duyệt'),
(30, 36, 1, '2026-05-31', 'Đã duyệt'),
(65, 36, 1, '2025-09-14', 'Đã duyệt'),
(66, 38, 1, '2025-09-19', 'Đã duyệt'),
(67, 38, 2, '2025-09-23', 'Đã duyệt'),
(68, 26, 2, '2025-09-25', ''),
(69, 24, 2, '2025-09-25', ''),
(70, 38, 2, '2025-09-28', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_lam_viec`
--

CREATE TABLE `lich_lam_viec` (
  `id` int(11) NOT NULL,
  `id_nhan_vien` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `ngay` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `gio_ket_thuc` time NOT NULL,
  `ca_lam` varchar(50) DEFAULT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `trang_thai` enum('lich','doi','huy') DEFAULT 'lich',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_lam_viec`
--

INSERT INTO `lich_lam_viec` (`id`, `id_nhan_vien`, `id_rap`, `ngay`, `gio_bat_dau`, `gio_ket_thuc`, `ca_lam`, `ghi_chu`, `trang_thai`, `ngay_tao`) VALUES
(19, 34, 1, '2025-09-22', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(20, 34, 1, '2025-09-23', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(21, 34, 1, '2025-09-24', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(22, 34, 1, '2025-09-25', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(23, 34, 1, '2025-09-26', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(24, 34, 1, '2025-09-27', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15'),
(25, 34, 1, '2025-09-28', '08:00:00', '17:00:00', 'Full', 'Chúc em làm việc thật tốt', 'lich', '2025-09-18 09:35:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_xem_phim`
--

CREATE TABLE `lich_su_xem_phim` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `ngay_xem` datetime NOT NULL,
  `danh_gia` tinyint(1) DEFAULT NULL,
  `binh_luan` text DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaiphim`
--

CREATE TABLE `loaiphim` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaiphim`
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
-- Cấu trúc bảng cho bảng `nhan_vien_rap`
--

CREATE TABLE `nhan_vien_rap` (
  `id` int(11) NOT NULL,
  `id_tai_khoan` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `chuc_vu` varchar(100) NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `luong_co_ban` decimal(10,2) DEFAULT 0.00,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien_rap`
--

INSERT INTO `nhan_vien_rap` (`id`, `id_tai_khoan`, `id_rap`, `chuc_vu`, `ngay_bat_dau`, `luong_co_ban`, `trang_thai`, `ngay_tao`) VALUES
(1, 14, 1, 'Nhân viên bán vé', '2025-01-01', 8000000.00, 1, '2025-08-15 14:53:15'),
(2, 10, 1, 'Quản lý rạp', '2025-01-01', 15000000.00, 1, '2025-08-15 14:53:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phim`
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
  `link_trailer` varchar(5000) NOT NULL,
  `trang_thai_duyet` enum('cho_duyet','da_duyet','tu_choi') DEFAULT 'cho_duyet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phim`
--

INSERT INTO `phim` (`id`, `tieu_de`, `daodien`, `dienvien`, `img`, `mo_ta`, `date_phat_hanh`, `thoi_luong_phim`, `id_loai`, `quoc_gia`, `gia_han_tuoi`, `link_trailer`, `trang_thai_duyet`) VALUES
(5, 'Kỳ án trên băng', 'Justine Triet', 'Sandra Hüller, Swann Arlaud, Milo Machado-Graner', 'Kỳ án trên đồi tuyết.jpg', 'Cuộc sống của nhà văn Sandra cùng chồng Samuel và cậu con trai mù Daniel ở căn nhà gỗ hẻo lánh trên dãy Alps bất ngờ bị xáo trộn khi Samuel được tìm thấy đã chết một cách bí ẩn trên tuyết, khiến Sandra trở thành nghi phạm chính và mối quan hệ đầy mâu thuẫn giữa cô và chồng dần được phơi bày trước phiên tòa.', '2024-05-20', 87, 9, 'Anh', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/dZdtN-Tce78?si=zqudwVQYo8d2xdOv\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(6, 'Búp bê', 'Scott Cawthon', 'Scott Cawthon, Leon Riskin, Allen Simpson', 'Năm đêm kinh hoàng.jpg', 'Scott Cawthon, Leon Riskin, Allen Simpson', '2024-05-20', 123, 9, 'Trung Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/RdW5xbBhDfk?si=nyTKv1Gzf0-6s21k\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(7, 'Đất rừng phương nam', 'Nguyễn Quang Dũng', ' Trấn Thành, Nguyễn Trinh Hoan, Nguyen Tri Vien', 'Đất rừng phương nam.jpg', 'Đất rừng phương Nam là một bộ phim điện ảnh Việt Nam thuộc thể loại sử thi – tâm lý – chính kịch ra mắt vào năm 2023, được dựa trên cuốn tiểu thuyết cùng tên của nhà văn Đoàn Giỏi và bộ phim truyền hình Đất phương Nam vào năm 1997', '2024-05-20', 123, 5, 'Việt Nam', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/hktzirCnJmQ?si=4x--iJO1e1QnLPFj\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(8, 'THE MARVELS', ' Nia DaCosta', 'Brie Larson, Samuel L. Jackson, Zawe Ashton', 'Biệt đội Marvels.jpg', 'Carol Danvers bị vướng vào sức mạnh của Kamala Khan và Monica Rambeau, buộc họ phải hợp tác với nhau để cứu vũ trụ.', '2024-05-20', 233, 2, 'Mỹ', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/rX1znA4na5I?si=3csEmDaichYD6QBZ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(22, 'QUỶ LÙN TINH NGHỊCH: ĐỒNG TÂM HIỆP NHẠC', 'Walt Dohrn, Tim Heitz', 'Anna Kendrick, Zooey Deschanel, Justin Timberlake', 'wolfoo và hòn đảo kỳ bí.jpg', 'Sự xuất hiện đột ngột của John Dory, anh trai thất lạc đã lâu của Branch mở ra quá khứ bí mật được che giấu bấy lâu nay của Branch. Đó là quá khứ về một ban nhạc có tên BroZone từng rất nổi tiếng nhưng đã tan rã. Hành trình đi tìm lại các thành viên để làm một ban nhạc như xưa trở thành chuyến phiêu lưu âm nhạc đầy cảm xúc, tràn trề hi vọng về một cuộc sum họp gia đình tuyệt với nhất.', '2024-05-20', 85, 3, 'Mỹ', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/izi44lM_HSo?si=I5JLlxyg-9NKl5nN\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(24, 'CHÚA TỂ CỦA NHỮNG CHIẾC NHẪN - SỰ TRỞ VỀ CỦA NHÀ VUA ', 'Peter Jackson', 'Elijah Wood, Viggo Mortensen, Ian McKellen,...', 'la.jpg', 'Chương cuối cùng của loạt phim Chúa Tể Của Những Chiếc Nhẫn mang tới trận chiến cuối cùng giữa thiện và ác cùng tương lai của Trung Địa. Frodo và Sam đến với Mordor trên hành trình phá hủy chiếc nhẫn, trong khi Aragon tiếp tục lãnh đạo nhóm của mình chống lại đoàn quân của Sauron. Phần phim thứ ba này được coi là thành công nhất cả loạt phim trên khía cạnh thương mại và phê bình, với doanh thu toàn cầu vượt mốc 1 tỷ đô la cùng 11 giải Oscar danh giá. (Chiếu lại từ 29/11/2023)', '2024-05-20', 125, 1, 'Ấn Độ', 18, '<iframe width=\"560px\" height=\"315px\" src=\"https://www.youtube.com/embed/4qhMENRhQxo?si=fUzhrjWRsv5t6yfF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(25, 'YÊU LẠI VỢ NGẦU', 'Nam Dae-jung', 'Kang Ha-neul, Jung So-min, Kim Sun-young, Lim Chul-hyung, Yoon Kyung-ho, Jo Min-soo,....', 'vongau.jpg', 'Cặp vợ chồng trẻ No Jung Yeol (Kang Ha-neul) và Hong Na Ra (Jung So-min) từ cuộc sống hôn nhân màu hồng dần “hiện nguyên hình” trở thành cái gai trong mắt đối phương với vô vàn thói hư, tật xấu. Không thể đi đến tiếng nói chung, Jung Yeol và Na Ra quyết định ra toà ly dị. Tuy nhiên, họ phải chờ 30 ngày cho đến khi mọi thủ tục chính thức được hoàn tất. Trong khoảng thời gian này, một vụ tai nạn xảy ra khiến cả hai mất đi ký ức và không nhớ người kia là ai. 30 ngày chờ đợi để được “đường ai nấy đi” nhưng nhiều tình huống trớ trêu khiến cả hai bắt đầu nảy sinh tình cảm trở lại. Liệu khi nhớ ra mọi thứ, họ vẫn sẽ ký tên vào tờ giấy ly hôn?', '2024-05-20', 119, 3, 'Hàn Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/081I7DXNknc?si=S1UeeKF1caKSTcAJ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(26, 'KẺ ĂN HỒN', 'Trần Hữu Tấn', 'Hoàng Hà, Võ Điền Gia Huy, Huỳnh Thanh Trực, NSƯT Chiều Xuân, Nghệ sĩ Viết Liên, NSND Ngọc Thư, Nguyễn Hữu Tiến, Nguyễn Phước Lộc, Nghinh Lộc, Lý Hồng Ân, Vũ Đức Ngọc…', 'anhon.jpg', 'him về hàng loạt cái chết bí ẩn ở Làng Địa Ngục, nơi có ma thuật cổ xưa: 5 mạng đổi bình Rượu Sọ Người. Thập Nương - cô gái áo đỏ là kẻ nắm giữ bí thuật luyện nên loại rượu mạnh nhất!', '2024-05-20', 135, 1, 'Việt Nam', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/Ac5PuRpFeRU?si=FCxQZOQ2_88BleIl\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(27, 'NGƯỜI VỢ CUỐI CÙNG', 'Victor Vũ', 'Kaity Nguyễn - Thuận Nguyễn - NSƯT Quang Thắng - NSƯT Kim Oanh - Đinh Ngọc Diệp - Anh Dũng - Quốc Huy - Bé Lưu Ly', 'nguoivo.jpg', 'Lấy cảm hứng từ tiểu thuyết Hồ Oán Hận, của nhà văn Hồng Thái, Người Vợ Cuối Cùng là một bộ phim tâm lý cổ trang, lấy bối cảnh Việt Nam vào triều Nguyễn. LINH - Người vợ bất đắc dĩ của một viên quan tri huyện, xuất thân là con của một gia đình nông dân nghèo khó, vì không thể hoàn thành nghĩa vụ sinh con nối dõi nên đã chịu sự chèn ép của những người vợ lớn trong gia đình. Sự gặp gỡ tình cờ của cô và người yêu thời thanh mai trúc mã của mình - NH N đã dẫn đến nhiều câu chuyện bất ngờ xảy ra khiến cuộc sống cô hoàn toàn thay đổi.', '2024-05-20', 132, 2, 'Việt Nam', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/TtS_V55VcxA?si=NnMnDilitNBaTXOF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(28, 'THIẾU NIÊN VÀ CHIM DIỆC', 'Miyazaki Hayao', 'Santoki Soma, Suda Masaki, Shibasaki Ko, Aimyon, Kimura Yoshino, Kimura Takuya, Kobayashi Karou', 'vietnam_-_poster_-_15.12.2023_1_.jpg', 'Đến từ Studio Ghibli và đạo diễn Miyazaki Hayao, bộ phim là câu chuyện về hành trình kỳ diệu của cậu thiếu niên Mahito trong một thế giới hoàn toàn mới lạ. Trải qua nỗi đau mất mẹ cùng mối quan hệ chẳng mấy vui vẻ với gia đình cũng như bạn học, Mahito dần cô lập bản thân... cho đến khi cậu gặp một chú chim diệc biết nói kỳ lạ. Mahito cùng chim diệc bước vào một tòa tháp bí ẩn, nơi một thế giới thần kỳ mở ra, đưa cậu gặp gỡ những người mình yêu thương... trong một nhân dạng hoàn toàn khác.', '2024-05-20', 124, 5, 'Nhật Bản', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/eggzAobZzHc?si=aQBXzb5cGIvWybYy\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(29, 'Bảy Viên Ngọc Rồng Siêu Cấp: Siêu Anh Hùng', 'Tokuda', 'Masako Nozawa,Toshio Furukawa,Yuko Minaguchi,...', 'poster.jpg', 'Đội quân Ruy Băng Đỏ đã bị Son Goku tiêu diệt. Thế nhưng, những kẻ kế nghiệp của chúng đã tạo ra hai chiến binh Android mới là Gamma 1 và Gamma 2. Hai Android này tự nhận mình là “Siêu anh hùng”. Chúng bắt đầu tấn công Piccolo và Gohan… Mục tiêu của Đội quân Ruy Băng Đỏ mới này là gì? Trước nguy cơ cận kề, đã đến lúc các siêu anh hùng thực thụ phải thức tỉnh!', '2024-05-20', 128, 8, 'Nhật Bản', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cQoNi0BVkj8?si=noUmGcjm6CJn8Rm0\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(30, 'NHỮNG KỶ NGUYÊN CỦA TAYLOR SWIFT', 'Sam Wrench', 'Taylor Swift', '700x1000_18_.jpg', 'Hiện tượng văn hóa tiếp tục trên màn ảnh lớn! Đắm chìm trong trải nghiệm xem phim hòa nhạc độc nhất vô nhị với góc nhìn ngoạn mục, đậm chất điện ảnh về chuyến lưu diễn mang tính lịch sử. Khuyến khích khán giả đeo vòng tay tình bạn và mặc trang phục Taylor Swift Eras Tour!', '2024-05-20', 168, 9, 'Mỹ', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cwLAor_smGw?si=2xnYd5m-iCFpB-Yn\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(31, 'WONKA', 'Paul King', 'Timothée Chalamet, Hugh Grant, Rowan Atkinson, Matt Lucas, Mathew Baynton.', '700x1000_22_.jpg', 'Dựa trên nhân vật từ quyến sách gối đầu giường của các em nhỏ trên toàn thế giới \"Charlie và Nhà Máy Sôcôla\" và phiên bản phim điện ảnh cùng tên vào năm 2005, WONKA kể câu chuyện kỳ diệu về hành trình của nhà phát minh, ảo thuật gia và nhà sản xuất sôcôla vĩ đại nhất thế giới trở thành WILLY WONKA đáng yêu mà chúng ta biết ngày nay. Từ đạo diễn loạt phim Paddington và nhà sản xuất loạt phim chuyển thể đình đám Harry Potter, WONKA hứa hẹn sẽ là một bộ phim đầy vui nhộn và màu sắc cho khán giả dịp Lễ Giáng Sinh năm nay.', '2024-05-20', 116, 9, 'Anh', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/1JHj4hc5MEI?si=buPaabXX7WVAd61P\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(33, 'NGƯỜI MẶT TRỜI', 'vfdv', 'df', '406x600-nmt.jpg', '400 năm qua, loài Ma Cà Rồng đã bí mật sống giữa loài người trong hòa bình, nhưng hiểm họa bỗng ập đến khi một cô gái loài người phát hiện được thân phận của hai anh em Ma Cà Rồng. Người anh khát máu quyết săn lùng cô để bảo vệ bí mật giống loài, trong khi người còn lại chạy đua với thời gian để bảo vệ cô bằng mọi giá.', '2024-05-20', 145, 9, 'dfb', 32, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/L3t9jW4eRAs?si=OwjViaUsQ2yMosxw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(36, 'ĐỊA ĐẠO', 'Bùi Thạc Chuyên', 'Thái Hòa; Quang Tuấn; Diễm Hằng Lamoon; Anh Tú Wilson; Hồ Thu Anh', '350x495-diadao_1.jpg', 'Nhân dịp kỷ niệm 50 năm đất nước hoà bình này còn phim nào thoả được nỗi niềm thưởng thức thước phim thời chiến đầy hào hùng như Địa Đạo: Mặt Trời Trong Bóng Tối. Nay còn có thêm định dạng 4DX cho khán giả trải nghiệm chui hầm dưới lòng Củ Chi đất thép.\r\n', '2025-04-04', 130, 9, 'Việt ', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/watch?v=7BTwfVoP4YY\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'da_duyet'),
(37, 'Lật mặt', 'Bùi Thạc Chuyên', 'Khải', 'Latmat8.png', 'kakaka', '2025-05-30', 150, 2, 'Việt ', 18, 'https://www.youtube.com/watch?v=7BTwfVoP4YY', 'cho_duyet'),
(38, 'PHIM IUH', 'Bùi Thạc Chuyên', 'Khải', 'nen.jpg', 'ssss', '2025-09-19', 128, 3, 'Việt ', 18, 'https://www.youtube.com/watch?v=7BTwfVoP4YY', 'cho_duyet');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phim_rap`
--

CREATE TABLE `phim_rap` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phim_rap`
--

INSERT INTO `phim_rap` (`id`, `id_phim`, `id_rap`, `ngay_tao`) VALUES
(50, 24, 2, '2025-09-22 05:33:02'),
(51, 26, 2, '2025-09-22 05:33:02'),
(52, 38, 2, '2025-09-22 05:34:53'),
(53, 29, 1, '2025-09-22 06:22:46'),
(54, 6, 1, '2025-09-22 06:22:46'),
(55, 24, 1, '2025-09-22 06:22:46'),
(56, 26, 1, '2025-09-22 06:22:46'),
(57, 5, 1, '2025-09-22 06:22:46'),
(58, 37, 1, '2025-09-22 06:22:46'),
(59, 33, 1, '2025-09-22 06:22:46'),
(60, 27, 1, '2025-09-22 06:22:46'),
(61, 30, 1, '2025-09-22 06:22:46'),
(62, 38, 1, '2025-09-22 06:22:46'),
(63, 22, 1, '2025-09-22 06:22:46'),
(64, 8, 1, '2025-09-22 06:22:46'),
(65, 28, 1, '2025-09-22 06:22:46'),
(66, 31, 1, '2025-09-22 06:22:46'),
(67, 25, 1, '2025-09-22 06:22:46'),
(68, 7, 1, '2025-09-22 06:22:46'),
(69, 36, 1, '2025-09-22 06:22:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongchieu`
--

CREATE TABLE `phongchieu` (
  `id` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `so_ghe` int(11) DEFAULT 0,
  `loai_phong` enum('2D','3D','4DX','IMAX') DEFAULT '2D',
  `dien_tich` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phongchieu`
--

INSERT INTO `phongchieu` (`id`, `id_rap`, `name`, `so_ghe`, `loai_phong`, `dien_tich`) VALUES
(1, 1, 'P101', 120, '2D', 0.00),
(2, 1, 'P102', 100, '2D', 0.00),
(15, 1, 'P103', 80, '2D', 0.00),
(16, 2, 'P101', 100, '2D', 100.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_ghe`
--

CREATE TABLE `phong_ghe` (
  `id` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `row_label` varchar(4) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `tier` enum('cheap','middle','expensive') NOT NULL DEFAULT 'cheap',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_ghe`
--

INSERT INTO `phong_ghe` (`id`, `id_phong`, `row_label`, `seat_number`, `code`, `tier`, `active`) VALUES
(433, 1, 'A', 1, 'A1', 'cheap', 1),
(434, 1, 'A', 2, 'A2', 'cheap', 1),
(435, 1, 'A', 3, 'A3', 'cheap', 1),
(436, 1, 'A', 4, 'A4', 'cheap', 1),
(437, 1, 'A', 5, 'A5', 'cheap', 1),
(438, 1, 'A', 6, 'A6', 'cheap', 1),
(439, 1, 'A', 7, 'A7', 'cheap', 1),
(440, 1, 'A', 8, 'A8', 'cheap', 1),
(441, 1, 'A', 9, 'A9', 'cheap', 1),
(442, 1, 'A', 10, 'A10', 'cheap', 1),
(443, 1, 'A', 11, 'A11', 'cheap', 1),
(444, 1, 'A', 12, 'A12', 'cheap', 1),
(445, 1, 'A', 13, 'A13', 'cheap', 1),
(446, 1, 'A', 14, 'A14', 'cheap', 1),
(447, 1, 'A', 15, 'A15', 'cheap', 1),
(448, 1, 'A', 16, 'A16', 'cheap', 1),
(449, 1, 'A', 17, 'A17', 'cheap', 1),
(450, 1, 'A', 18, 'A18', 'cheap', 1),
(451, 1, 'B', 1, 'B1', 'cheap', 1),
(452, 1, 'B', 2, 'B2', 'cheap', 1),
(453, 1, 'B', 3, 'B3', 'cheap', 1),
(454, 1, 'B', 4, 'B4', 'cheap', 1),
(455, 1, 'B', 5, 'B5', 'cheap', 1),
(456, 1, 'B', 6, 'B6', 'cheap', 1),
(457, 1, 'B', 7, 'B7', 'cheap', 1),
(458, 1, 'B', 8, 'B8', 'cheap', 1),
(459, 1, 'B', 9, 'B9', 'cheap', 1),
(460, 1, 'B', 10, 'B10', 'cheap', 1),
(461, 1, 'B', 11, 'B11', 'cheap', 1),
(462, 1, 'B', 12, 'B12', 'cheap', 1),
(463, 1, 'B', 13, 'B13', 'cheap', 1),
(464, 1, 'B', 14, 'B14', 'cheap', 1),
(465, 1, 'B', 15, 'B15', 'cheap', 1),
(466, 1, 'B', 16, 'B16', 'cheap', 1),
(467, 1, 'B', 17, 'B17', 'cheap', 1),
(468, 1, 'B', 18, 'B18', 'cheap', 1),
(469, 1, 'C', 1, 'C1', 'cheap', 1),
(470, 1, 'C', 2, 'C2', 'cheap', 1),
(471, 1, 'C', 3, 'C3', 'cheap', 1),
(472, 1, 'C', 4, 'C4', 'cheap', 1),
(473, 1, 'C', 5, 'C5', 'cheap', 1),
(474, 1, 'C', 6, 'C6', 'cheap', 1),
(475, 1, 'C', 7, 'C7', 'cheap', 1),
(476, 1, 'C', 8, 'C8', 'cheap', 1),
(477, 1, 'C', 9, 'C9', 'cheap', 1),
(478, 1, 'C', 10, 'C10', 'cheap', 1),
(479, 1, 'C', 11, 'C11', 'cheap', 1),
(480, 1, 'C', 12, 'C12', 'cheap', 1),
(481, 1, 'C', 13, 'C13', 'cheap', 1),
(482, 1, 'C', 14, 'C14', 'cheap', 1),
(483, 1, 'C', 15, 'C15', 'cheap', 1),
(484, 1, 'C', 16, 'C16', 'cheap', 1),
(485, 1, 'C', 17, 'C17', 'cheap', 1),
(486, 1, 'C', 18, 'C18', 'cheap', 1),
(487, 1, 'D', 1, 'D1', 'cheap', 1),
(488, 1, 'D', 2, 'D2', 'cheap', 1),
(489, 1, 'D', 3, 'D3', 'cheap', 1),
(490, 1, 'D', 4, 'D4', 'cheap', 1),
(491, 1, 'D', 5, 'D5', 'cheap', 1),
(492, 1, 'D', 6, 'D6', 'cheap', 1),
(493, 1, 'D', 7, 'D7', 'cheap', 1),
(494, 1, 'D', 8, 'D8', 'cheap', 1),
(495, 1, 'D', 9, 'D9', 'cheap', 1),
(496, 1, 'D', 10, 'D10', 'cheap', 1),
(497, 1, 'D', 11, 'D11', 'cheap', 1),
(498, 1, 'D', 12, 'D12', 'cheap', 1),
(499, 1, 'D', 13, 'D13', 'cheap', 1),
(500, 1, 'D', 14, 'D14', 'cheap', 1),
(501, 1, 'D', 15, 'D15', 'cheap', 1),
(502, 1, 'D', 16, 'D16', 'cheap', 1),
(503, 1, 'D', 17, 'D17', 'cheap', 1),
(504, 1, 'D', 18, 'D18', 'cheap', 1),
(505, 1, 'E', 1, 'E1', 'cheap', 1),
(506, 1, 'E', 2, 'E2', 'cheap', 1),
(507, 1, 'E', 3, 'E3', 'cheap', 1),
(508, 1, 'E', 4, 'E4', 'cheap', 1),
(509, 1, 'E', 5, 'E5', 'cheap', 1),
(510, 1, 'E', 6, 'E6', 'cheap', 1),
(511, 1, 'E', 7, 'E7', 'cheap', 1),
(512, 1, 'E', 8, 'E8', 'cheap', 1),
(513, 1, 'E', 9, 'E9', 'cheap', 1),
(514, 1, 'E', 10, 'E10', 'cheap', 1),
(515, 1, 'E', 11, 'E11', 'cheap', 1),
(516, 1, 'E', 12, 'E12', 'cheap', 1),
(517, 1, 'E', 13, 'E13', 'cheap', 1),
(518, 1, 'E', 14, 'E14', 'cheap', 1),
(519, 1, 'E', 15, 'E15', 'cheap', 1),
(520, 1, 'E', 16, 'E16', 'cheap', 1),
(521, 1, 'E', 17, 'E17', 'cheap', 1),
(522, 1, 'E', 18, 'E18', 'cheap', 1),
(523, 1, 'F', 1, 'F1', 'middle', 1),
(524, 1, 'F', 2, 'F2', 'middle', 1),
(525, 1, 'F', 3, 'F3', 'middle', 1),
(526, 1, 'F', 4, 'F4', 'middle', 1),
(527, 1, 'F', 5, 'F5', 'middle', 1),
(528, 1, 'F', 6, 'F6', 'middle', 1),
(529, 1, 'F', 7, 'F7', 'middle', 1),
(530, 1, 'F', 8, 'F8', 'middle', 1),
(531, 1, 'F', 9, 'F9', 'middle', 1),
(532, 1, 'F', 10, 'F10', 'middle', 1),
(533, 1, 'F', 11, 'F11', 'middle', 1),
(534, 1, 'F', 12, 'F12', 'middle', 1),
(535, 1, 'F', 13, 'F13', 'middle', 1),
(536, 1, 'F', 14, 'F14', 'middle', 1),
(537, 1, 'F', 15, 'F15', 'middle', 1),
(538, 1, 'F', 16, 'F16', 'middle', 1),
(539, 1, 'F', 17, 'F17', 'middle', 1),
(540, 1, 'F', 18, 'F18', 'middle', 1),
(541, 1, 'G', 1, 'G1', 'middle', 1),
(542, 1, 'G', 2, 'G2', 'middle', 1),
(543, 1, 'G', 3, 'G3', 'middle', 1),
(544, 1, 'G', 4, 'G4', 'middle', 1),
(545, 1, 'G', 5, 'G5', 'middle', 1),
(546, 1, 'G', 6, 'G6', 'middle', 1),
(547, 1, 'G', 7, 'G7', 'middle', 1),
(548, 1, 'G', 8, 'G8', 'middle', 1),
(549, 1, 'G', 9, 'G9', 'middle', 1),
(550, 1, 'G', 10, 'G10', 'middle', 1),
(551, 1, 'G', 11, 'G11', 'middle', 1),
(552, 1, 'G', 12, 'G12', 'middle', 1),
(553, 1, 'G', 13, 'G13', 'middle', 1),
(554, 1, 'G', 14, 'G14', 'middle', 1),
(555, 1, 'G', 15, 'G15', 'middle', 1),
(556, 1, 'G', 16, 'G16', 'middle', 1),
(557, 1, 'G', 17, 'G17', 'middle', 1),
(558, 1, 'G', 18, 'G18', 'middle', 1),
(559, 1, 'H', 1, 'H1', 'middle', 1),
(560, 1, 'H', 2, 'H2', 'middle', 1),
(561, 1, 'H', 3, 'H3', 'middle', 1),
(562, 1, 'H', 4, 'H4', 'middle', 1),
(563, 1, 'H', 5, 'H5', 'middle', 1),
(564, 1, 'H', 6, 'H6', 'middle', 1),
(565, 1, 'H', 7, 'H7', 'middle', 1),
(566, 1, 'H', 8, 'H8', 'middle', 1),
(567, 1, 'H', 9, 'H9', 'middle', 1),
(568, 1, 'H', 10, 'H10', 'middle', 1),
(569, 1, 'H', 11, 'H11', 'middle', 1),
(570, 1, 'H', 12, 'H12', 'middle', 1),
(571, 1, 'H', 13, 'H13', 'middle', 1),
(572, 1, 'H', 14, 'H14', 'middle', 1),
(573, 1, 'H', 15, 'H15', 'middle', 1),
(574, 1, 'H', 16, 'H16', 'middle', 1),
(575, 1, 'H', 17, 'H17', 'middle', 1),
(576, 1, 'H', 18, 'H18', 'middle', 1),
(577, 1, 'I', 1, 'I1', 'middle', 1),
(578, 1, 'I', 2, 'I2', 'middle', 1),
(579, 1, 'I', 3, 'I3', 'middle', 1),
(580, 1, 'I', 4, 'I4', 'middle', 1),
(581, 1, 'I', 5, 'I5', 'middle', 1),
(582, 1, 'I', 6, 'I6', 'middle', 1),
(583, 1, 'I', 7, 'I7', 'middle', 1),
(584, 1, 'I', 8, 'I8', 'middle', 1),
(585, 1, 'I', 9, 'I9', 'middle', 1),
(586, 1, 'I', 10, 'I10', 'middle', 1),
(587, 1, 'I', 11, 'I11', 'middle', 1),
(588, 1, 'I', 12, 'I12', 'middle', 1),
(589, 1, 'I', 13, 'I13', 'middle', 1),
(590, 1, 'I', 14, 'I14', 'middle', 1),
(591, 1, 'I', 15, 'I15', 'middle', 1),
(592, 1, 'I', 16, 'I16', 'middle', 1),
(593, 1, 'I', 17, 'I17', 'middle', 1),
(594, 1, 'I', 18, 'I18', 'middle', 1),
(595, 1, 'J', 1, 'J1', 'cheap', 1),
(596, 1, 'J', 2, 'J2', 'cheap', 1),
(597, 1, 'J', 3, 'J3', 'cheap', 1),
(598, 1, 'J', 4, 'J4', 'cheap', 1),
(599, 1, 'J', 5, 'J5', 'expensive', 1),
(600, 1, 'J', 6, 'J6', 'expensive', 1),
(601, 1, 'J', 7, 'J7', 'expensive', 1),
(602, 1, 'J', 8, 'J8', 'expensive', 1),
(603, 1, 'J', 9, 'J9', 'expensive', 1),
(604, 1, 'J', 10, 'J10', 'expensive', 1),
(605, 1, 'J', 11, 'J11', 'expensive', 1),
(606, 1, 'J', 12, 'J12', 'expensive', 1),
(607, 1, 'J', 13, 'J13', 'cheap', 1),
(608, 1, 'J', 14, 'J14', 'cheap', 1),
(609, 1, 'J', 15, 'J15', 'cheap', 1),
(610, 1, 'J', 16, 'J16', 'cheap', 1),
(611, 1, 'J', 17, 'J17', 'cheap', 1),
(612, 1, 'J', 18, 'J18', 'cheap', 1),
(613, 1, 'K', 1, 'K1', 'cheap', 1),
(614, 1, 'K', 2, 'K2', 'cheap', 1),
(615, 1, 'K', 3, 'K3', 'cheap', 1),
(616, 1, 'K', 4, 'K4', 'cheap', 1),
(617, 1, 'K', 5, 'K5', 'expensive', 1),
(618, 1, 'K', 6, 'K6', 'expensive', 1),
(619, 1, 'K', 7, 'K7', 'expensive', 1),
(620, 1, 'K', 8, 'K8', 'expensive', 1),
(621, 1, 'K', 9, 'K9', 'expensive', 1),
(622, 1, 'K', 10, 'K10', 'expensive', 1),
(623, 1, 'K', 11, 'K11', 'expensive', 1),
(624, 1, 'K', 12, 'K12', 'expensive', 1),
(625, 1, 'K', 13, 'K13', 'cheap', 1),
(626, 1, 'K', 14, 'K14', 'cheap', 1),
(627, 1, 'K', 15, 'K15', 'cheap', 1),
(628, 1, 'K', 16, 'K16', 'cheap', 1),
(629, 1, 'K', 17, 'K17', 'cheap', 1),
(630, 1, 'K', 18, 'K18', 'cheap', 1),
(631, 1, 'L', 1, 'L1', 'cheap', 1),
(632, 1, 'L', 2, 'L2', 'cheap', 1),
(633, 1, 'L', 3, 'L3', 'cheap', 1),
(634, 1, 'L', 4, 'L4', 'cheap', 1),
(635, 1, 'L', 5, 'L5', 'expensive', 1),
(636, 1, 'L', 6, 'L6', 'expensive', 1),
(637, 1, 'L', 7, 'L7', 'expensive', 1),
(638, 1, 'L', 8, 'L8', 'expensive', 1),
(639, 1, 'L', 9, 'L9', 'expensive', 1),
(640, 1, 'L', 10, 'L10', 'expensive', 1),
(641, 1, 'L', 11, 'L11', 'expensive', 1),
(642, 1, 'L', 12, 'L12', 'expensive', 1),
(643, 1, 'L', 13, 'L13', 'cheap', 1),
(644, 1, 'L', 14, 'L14', 'cheap', 1),
(645, 1, 'L', 15, 'L15', 'cheap', 1),
(646, 1, 'L', 16, 'L16', 'cheap', 1),
(647, 1, 'L', 17, 'L17', 'cheap', 1),
(648, 1, 'L', 18, 'L18', 'cheap', 1),
(1169, 2, 'A', 1, 'A1', 'cheap', 1),
(1170, 2, 'A', 2, 'A2', 'cheap', 1),
(1171, 2, 'A', 3, 'A3', 'cheap', 1),
(1172, 2, 'A', 4, 'A4', 'cheap', 1),
(1173, 2, 'A', 5, 'A5', 'cheap', 1),
(1174, 2, 'A', 6, 'A6', 'cheap', 1),
(1175, 2, 'A', 7, 'A7', 'cheap', 1),
(1176, 2, 'A', 8, 'A8', 'cheap', 1),
(1177, 2, 'B', 1, 'B1', 'cheap', 1),
(1178, 2, 'B', 2, 'B2', 'cheap', 1),
(1179, 2, 'B', 3, 'B3', 'cheap', 1),
(1180, 2, 'B', 4, 'B4', 'cheap', 1),
(1181, 2, 'B', 5, 'B5', 'cheap', 1),
(1182, 2, 'B', 6, 'B6', 'cheap', 1),
(1183, 2, 'B', 7, 'B7', 'cheap', 1),
(1184, 2, 'B', 8, 'B8', 'cheap', 1),
(1185, 2, 'C', 1, 'C1', 'cheap', 1),
(1186, 2, 'C', 2, 'C2', 'cheap', 1),
(1187, 2, 'C', 3, 'C3', 'cheap', 1),
(1188, 2, 'C', 4, 'C4', 'cheap', 1),
(1189, 2, 'C', 5, 'C5', 'cheap', 1),
(1190, 2, 'C', 6, 'C6', 'cheap', 1),
(1191, 2, 'C', 7, 'C7', 'cheap', 1),
(1192, 2, 'C', 8, 'C8', 'cheap', 1),
(1193, 2, 'D', 1, 'D1', 'cheap', 1),
(1194, 2, 'D', 2, 'D2', 'cheap', 1),
(1195, 2, 'D', 3, 'D3', 'cheap', 1),
(1196, 2, 'D', 4, 'D4', 'cheap', 1),
(1197, 2, 'D', 5, 'D5', 'cheap', 1),
(1198, 2, 'D', 6, 'D6', 'cheap', 1),
(1199, 2, 'D', 7, 'D7', 'cheap', 1),
(1200, 2, 'D', 8, 'D8', 'cheap', 1),
(1201, 2, 'E', 1, 'E1', 'cheap', 1),
(1202, 2, 'E', 2, 'E2', 'cheap', 1),
(1203, 2, 'E', 3, 'E3', 'cheap', 1),
(1204, 2, 'E', 4, 'E4', 'cheap', 1),
(1205, 2, 'E', 5, 'E5', 'cheap', 1),
(1206, 2, 'E', 6, 'E6', 'cheap', 1),
(1207, 2, 'E', 7, 'E7', 'cheap', 1),
(1208, 2, 'E', 8, 'E8', 'cheap', 1),
(1209, 2, 'F', 1, 'F1', 'middle', 1),
(1210, 2, 'F', 2, 'F2', 'middle', 1),
(1211, 2, 'F', 3, 'F3', 'middle', 1),
(1212, 2, 'F', 4, 'F4', 'middle', 1),
(1213, 2, 'F', 5, 'F5', 'middle', 1),
(1214, 2, 'F', 6, 'F6', 'middle', 1),
(1215, 2, 'F', 7, 'F7', 'middle', 1),
(1216, 2, 'F', 8, 'F8', 'middle', 1),
(1217, 2, 'G', 1, 'G1', 'middle', 1),
(1218, 2, 'G', 2, 'G2', 'middle', 1),
(1219, 2, 'G', 3, 'G3', 'middle', 1),
(1220, 2, 'G', 4, 'G4', 'middle', 1),
(1221, 2, 'G', 5, 'G5', 'middle', 1),
(1222, 2, 'G', 6, 'G6', 'middle', 1),
(1223, 2, 'G', 7, 'G7', 'middle', 1),
(1224, 2, 'G', 8, 'G8', 'middle', 1),
(1225, 2, 'H', 1, 'H1', 'middle', 1),
(1226, 2, 'H', 2, 'H2', 'middle', 1),
(1227, 2, 'H', 3, 'H3', 'middle', 1),
(1228, 2, 'H', 4, 'H4', 'middle', 1),
(1229, 2, 'H', 5, 'H5', 'middle', 1),
(1230, 2, 'H', 6, 'H6', 'middle', 1),
(1231, 2, 'H', 7, 'H7', 'middle', 1),
(1232, 2, 'H', 8, 'H8', 'middle', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rap_chieu`
--

CREATE TABLE `rap_chieu` (
  `id` int(11) NOT NULL,
  `ten_rap` varchar(255) NOT NULL,
  `dia_chi` text NOT NULL,
  `so_dien_thoai` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `mo_ta` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rap_chieu`
--

INSERT INTO `rap_chieu` (`id`, `ten_rap`, `dia_chi`, `so_dien_thoai`, `email`, `trang_thai`, `ngay_tao`, `mo_ta`, `logo`) VALUES
(1, 'Galaxy Studio Quận 1', '123 Nguyễn Huệ, Quận 1, TP.HCM', '028 1234 5678', 'quan1@galaxy.com', 1, '2025-08-15 14:43:33', 'Rạp chiếu phim cao cấp tại trung tâm Quận 1', NULL),
(2, 'Galaxy Studio Quận 7', '456 Nguyễn Thị Thập, Quận 7, TP.HCM', '028 8765 4321', 'quan7@galaxy.com', 1, '2025-08-15 14:43:33', 'Rạp chiếu phim hiện đại tại Quận 7', NULL),
(3, 'Galaxy Studio Quận 2', '789 Mai Chí Thọ, Quận 2, TP.HCM', '028 9876 5432', 'quan2@galaxy.com', 1, '2025-08-15 14:43:33', 'Rạp chiếu phim sang trọng tại Thủ Đức', NULL),
(4, 'Galaxy Studio Bình Dương', '321 Đại lộ Bình Dương, TP.Thủ Dầu Một, Bình Dương', '0274 1234 5678', 'binhduong@galaxy.com', 1, '2025-08-15 14:43:33', 'Rạp chiếu phim đầu tiên tại Bình Dương', NULL),
(5, 'Galaxy Studio Đồng Nai', '654 Quốc lộ 1A, TP.Biên Hòa, Đồng Nai', '0251 8765 4321', 'dongnai@galaxy.com', 1, '2025-08-15 14:43:33', 'Rạp chiếu phim chất lượng cao tại Đồng Nai', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
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
  `id_rap` int(11) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `name`, `user`, `pass`, `email`, `phone`, `dia_chi`, `vai_tro`, `id_rap`, `img`, `ngay_tao`) VALUES
(10, 'Quản lý rạp 1', 'quanly_rap1', '123456', 'ptk@gmail.com', '0384104942', 'Phu Yen', 3, 1, '', '2025-08-15 14:48:18'),
(17, 'Phan Thiên Khải', 'phanthienkhai', 'Kpy123456', 'phanthienkhai111@gmail.com', '0384104942', 'Gò Vấp', 0, NULL, '', '2025-08-15 14:48:18'),
(19, 'Quản lý cụm rạp', 'quanly_cum', '123456', 'quanly@galaxy.com', '0987654321', 'TP.HCM', 4, NULL, '', '2025-08-17 10:00:00'),
(30, 'Cao Lan Anh', 'CaoLanAnh_Rap1', '123456', 'caolananhn@gmail.com', '0999999999', 'Gò Vấp', 1, 1, '', '2025-09-04 06:42:22'),
(31, 'adminss', 'quanly_rap2', '123456', 'khoi@gmail.com', '0999999999', 'Quận 7', 3, 2, '', '2025-09-04 08:30:05'),
(33, 'ADMIN HỆ THỐNG', 'admin_hethong', '123456', 'khoi@gmail.com', '0999999999', 'Gò Vấp', 2, NULL, '', '2025-09-18 05:57:32'),
(34, 'Phan Thiên Khải', 'Phanthienkhai_rap1', '123456', 'phanthienkhai2901@gmail.com', '0999999999', 'Gò Vấp', 1, 1, '', '2025-09-18 08:11:05'),
(35, 'Phúc Hưng', 'Phuchung_rap2', '123456', 'phanthienkhai111@gmail.com', '0999999999', 'Gò Vấp', 1, 2, '', '2025-09-22 05:30:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int(11) NOT NULL,
  `id_hoa_don` int(11) NOT NULL,
  `phuong_thuc` enum('momo','vnpay','zalopay','bank_transfer','qr_code','cash') NOT NULL,
  `ma_giao_dich` varchar(255) DEFAULT NULL,
  `so_tien` decimal(10,2) NOT NULL,
  `trang_thai` enum('pending','success','failed','cancelled') DEFAULT 'pending',
  `thong_tin_thanh_toan` text DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thiet_bi_phong`
--

CREATE TABLE `thiet_bi_phong` (
  `id` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `ten_thiet_bi` varchar(100) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  `tinh_trang` enum('tot','can_bao_tri','hong') DEFAULT 'tot',
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_tin_website`
--

CREATE TABLE `thong_tin_website` (
  `id` int(11) NOT NULL,
  `ten_website` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_tin_website`
--

INSERT INTO `thong_tin_website` (`id`, `ten_website`, `logo`, `dia_chi`, `so_dien_thoai`, `email`, `mo_ta`, `facebook`, `instagram`, `youtube`, `ngay_cap_nhat`) VALUES
(1, 'Galaxy Cinema', 'galaxy_logo.png', '123 Nguyễn Huệ, Quận 1, TP.HCM', '028 1234 5678', 'info@galaxy.com', 'Hệ thống rạp chiếu phim Galaxy - Trải nghiệm điện ảnh tuyệt vời', 'https://facebook.com/galaxycinema', 'https://instagram.com/galaxycinema', 'https://youtube.com/galaxycinema', '2025-08-17 10:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuc`
--

CREATE TABLE `tintuc` (
  `id` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `tom_tat` text DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `ngay_dang` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve`
--

CREATE TABLE `ve` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `id_rap` int(11) DEFAULT NULL,
  `id_thoi_gian_chieu` int(11) NOT NULL,
  `id_ngay_chieu` int(11) NOT NULL,
  `id_tk` int(11) NOT NULL,
  `ghe` varchar(255) NOT NULL,
  `combo` text NOT NULL,
  `price` varchar(10) NOT NULL,
  `id_hd` int(11) NOT NULL,
  `trang_thai` tinyint(4) NOT NULL DEFAULT 0,
  `ngay_dat` datetime NOT NULL,
  `ma_ve` varchar(32) DEFAULT NULL,
  `check_in_luc` datetime DEFAULT NULL,
  `check_in_boi` int(11) DEFAULT NULL,
  `tao_boi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ve`
--

INSERT INTO `ve` (`id`, `id_phim`, `id_rap`, `id_thoi_gian_chieu`, `id_ngay_chieu`, `id_tk`, `ghe`, `combo`, `price`, `id_hd`, `trang_thai`, `ngay_dat`, `ma_ve`, `check_in_luc`, `check_in_boi`, `tao_boi`) VALUES
(311, 28, 1, 59, 40, 17, 'A4,A5', 'Combo-Halo ', '459000', 305, 0, '2025-04-17 17:09:27', NULL, NULL, NULL, NULL),
(312, 26, 1, 36, 25, 17, 'F5,F6', 'Combo-Halo ', '659000', 1, 1, '2025-04-20 10:15:44', NULL, NULL, NULL, NULL),
(313, 5, 1, 11, 14, 17, 'A8,A7,A6,A5', 'Combo-Coca ', '459000', 2, 1, '2025-04-20 10:25:52', NULL, NULL, NULL, NULL),
(314, 27, 1, 25, 24, 17, 'A4,A3', 'Combo-Hủy-Diệt , Combo-Halo ', '658000', 3, 1, '2025-04-22 21:23:56', NULL, NULL, NULL, NULL),
(315, 27, 1, 25, 24, 17, 'A6,A5', '', '200000', 4, 0, '2025-04-23 12:36:43', NULL, NULL, NULL, NULL),
(316, 27, 1, 25, 24, 17, 'A7,A6,A5', 'Combo-Halo ', '459000', 5, 0, '2025-04-23 12:37:45', NULL, NULL, NULL, NULL),
(317, 7, 1, 55, 38, 17, 'A7,A6,A5', 'Combo-Wish-C1 ', '425000', 6, 1, '2025-04-23 12:43:23', NULL, NULL, NULL, NULL),
(318, 24, 1, 51, 34, 18, 'A8,A7,A6,A5,A4', 'Combo-Wish-C1 ', '625000', 7, 1, '2025-04-23 13:24:13', NULL, NULL, NULL, NULL),
(319, 8, 1, 12, 13, 18, 'H5', 'Combo-Coca , Combo-Halo , Combo-Halo-2 , Combo-Hủy-Diệt , Combo-Wish-B3 , Combo-Wish-C1 ', '1346000', 8, 1, '2025-04-23 13:25:41', NULL, NULL, NULL, NULL),
(320, 5, 1, 54, 37, 18, 'H5,H4', 'Combo-Halo ', '859000', 9, 1, '2025-04-23 13:26:51', NULL, NULL, NULL, NULL),
(321, 26, 1, 36, 25, 18, 'A6,A5', 'Combo-Hủy-Diệt ', '399000', 10, 1, '2025-04-23 13:31:10', NULL, NULL, NULL, NULL),
(322, 26, 1, 36, 25, 17, 'A2,A3,A4', 'Combo-Wish-C1 ', '425000', 11, 3, '2025-04-25 17:57:38', NULL, NULL, NULL, NULL),
(323, 26, 1, 36, 25, 17, 'A7', '', '100000', 12, 0, '2025-05-04 13:06:49', NULL, NULL, NULL, NULL),
(324, 26, 1, 53, 36, 17, 'A5,A4', 'Combo-Halo ', '459000', 13, 3, '2025-05-25 21:31:39', NULL, NULL, NULL, NULL),
(325, 7, 1, 7, 11, 17, 'A4,A3', 'Combo-Halo ', '459000', 14, 0, '2025-05-26 18:33:03', NULL, NULL, NULL, NULL),
(326, 6, 1, 7, 3, 17, 'A4', '', '100000', 15, 0, '2025-05-26 19:24:37', NULL, NULL, NULL, NULL),
(327, 25, 1, 39, 13, 17, 'A4', 'Combo-Halo ', '359000', 16, 0, '2025-05-26 19:30:05', NULL, NULL, NULL, NULL),
(328, 5, 1, 6, 2, 17, 'H7', 'Combo-Halo ', '559000', 17, 1, '2025-05-26 20:46:23', NULL, NULL, NULL, NULL),
(329, 5, 1, 3, 1, 17, 'H5,H4,H3,H2,A2', 'Combo-Hủy-Diệt , Combo-Wish-C1 , Combo-Halo ', '1883000', 18, 1, '2025-05-26 21:08:55', NULL, NULL, NULL, NULL),
(330, 5, 1, 1, 1, 17, 'H5', '', '300000', 19, 1, '2025-05-26 21:16:31', NULL, NULL, NULL, NULL),
(331, 5, 1, 1, 1, 17, 'C4,C5', '', '200000', 20, 0, '2025-05-27 11:57:27', NULL, NULL, NULL, NULL),
(332, 5, 1, 1, 1, 17, 'A5', '', '100000', 21, 1, '2025-05-27 12:04:00', NULL, NULL, NULL, NULL),
(333, 5, 1, 1, 1, 17, 'H7', '', '300000', 22, 0, '2025-05-27 15:58:25', NULL, NULL, NULL, NULL),
(334, 5, 1, 6, 2, 17, 'C5', '', '100000', 23, 0, '2025-05-27 19:11:50', NULL, NULL, NULL, NULL),
(335, 5, 1, 1, 1, 17, 'A4', 'Combo-Halo ', '359000', 24, 2, '2025-05-28 08:32:47', NULL, NULL, NULL, NULL),
(336, 6, 1, 10, 4, 17, 'E5,D5,A5,A4,A3,A2', '', '800000', 25, 2, '2025-06-30 16:35:53', NULL, NULL, NULL, NULL),
(337, 5, 1, 2, 1, 17, 'D5,D4,D3', 'Combo-Halo ', '859000', 26, 0, '2025-07-02 09:19:16', NULL, NULL, NULL, NULL),
(338, 26, 1, 46, 16, 17, 'A7,G7,D5', '', '600000', 27, 0, '2025-08-07 16:08:20', NULL, NULL, NULL, NULL),
(339, 6, 1, 8, 3, 17, 'J9', '', '300000', 0, 1, '2025-09-04 15:21:11', '8a8824509746', NULL, NULL, 30),
(340, 5, NULL, 5, 2, 17, 'D5', '', '200000', 28, 0, '2025-09-04 15:34:33', NULL, NULL, NULL, NULL),
(341, 29, 1, 64, 22, 17, 'L16,K16,J16,G14,G13,H13,J13,I11,H11,H12,G12,F12', 'Combo Standard , Combo Premium , Combo Family , Combo VIP', '2400000', 0, 1, '2025-09-04 15:54:21', '41645651ac5c', '2025-09-22 12:15:53', 34, 30),
(342, 26, 1, 43, 15, 17, 'B11', 'Combo VIP', '250000', 0, 1, '2025-09-22 12:14:48', '82130def856d', '2025-09-22 12:15:38', 34, 34);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `ma_voucher` varchar(20) NOT NULL,
  `ten_voucher` varchar(255) NOT NULL,
  `gia_tri` decimal(10,2) NOT NULL,
  `loai_giam` enum('phan_tram','tien_mat') NOT NULL DEFAULT 'tien_mat',
  `dieu_kien_su_dung` text DEFAULT NULL,
  `ngay_het_han` date NOT NULL,
  `so_luong` int(11) DEFAULT -1,
  `da_su_dung` int(11) DEFAULT 0,
  `trang_thai` tinyint(1) DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `voucher`
--

INSERT INTO `voucher` (`id`, `ma_voucher`, `ten_voucher`, `gia_tri`, `loai_giam`, `dieu_kien_su_dung`, `ngay_het_han`, `so_luong`, `da_su_dung`, `trang_thai`, `ngay_tao`) VALUES
(1, 'WELCOME2025', 'Voucher chào mừng năm mới', 50000.00, 'tien_mat', 'Áp dụng cho đơn hàng từ 200000đ', '2025-12-31', 1000, 0, 1, '2025-08-15 14:49:15'),
(2, 'STUDENT15', 'Voucher học sinh sinh viên', 15.00, 'phan_tram', 'Áp dụng cho HSSV với thẻ hợp lệ', '2025-12-31', 500, 0, 1, '2025-08-15 14:49:15'),
(3, 'FAMILY100', 'Voucher gia đình', 100000.00, 'tien_mat', 'Áp dụng cho đơn hàng từ 500000đ', '2025-12-31', 200, 0, 1, '2025-08-15 14:49:15'),
(4, 'VIP20', 'Voucher VIP', 20.00, 'phan_tram', 'Áp dụng cho tất cả đơn hàng', '2025-12-31', 100, 0, 1, '2025-08-15 14:49:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeu_cau_ve`
--

CREATE TABLE `yeu_cau_ve` (
  `id` int(11) NOT NULL,
  `id_ve` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `loai` enum('doi','hoan') NOT NULL,
  `ly_do` text DEFAULT NULL,
  `trang_thai` enum('cho_duyet','da_duyet','tu_choi') NOT NULL DEFAULT 'cho_duyet',
  `trang_thai_moi` int(11) DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `yeu_cau_ve`
--

INSERT INTO `yeu_cau_ve` (`id`, `id_ve`, `id_rap`, `loai`, `ly_do`, `trang_thai`, `trang_thai_moi`, `ngay_tao`) VALUES
(1, 1, 1, 'doi', '', 'tu_choi', NULL, '2025-09-18 14:36:12'),
(2, 1, 1, 'doi', '', 'tu_choi', NULL, '2025-09-18 14:37:23');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `cham_cong`
--
ALTER TABLE `cham_cong`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chat_analytics`
--
ALTER TABLE `chat_analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `session_id` (`session_id`);

--
-- Chỉ mục cho bảng `chi_tiet_combo`
--
ALTER TABLE `chi_tiet_combo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_combo` (`id_combo`);

--
-- Chỉ mục cho bảng `combo_do_an`
--
ALTER TABLE `combo_do_an`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `don_nghi_phep`
--
ALTER TABLE `don_nghi_phep`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nhan_vien` (`id_nhan_vien`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ghe_phong` (`id_phong`,`ten_ghe`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `khung_gio_chieu`
--
ALTER TABLE `khung_gio_chieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lich_chieu` (`id_lich_chieu`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `lichchieu_ibfk_2` (`id_rap`);

--
-- Chỉ mục cho bảng `lich_lam_viec`
--
ALTER TABLE `lich_lam_viec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nhan_vien` (`id_nhan_vien`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `lich_su_xem_phim`
--
ALTER TABLE `lich_su_xem_phim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `loaiphim`
--
ALTER TABLE `loaiphim`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nhan_vien_rap`
--
ALTER TABLE `nhan_vien_rap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tai_khoan` (`id_tai_khoan`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_loai` (`id_loai`);

--
-- Chỉ mục cho bảng `phim_rap`
--
ALTER TABLE `phim_rap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_phim_rap` (`id_phim`,`id_rap`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `phong_ghe`
--
ALTER TABLE `phong_ghe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_room_code` (`id_phong`,`code`),
  ADD KEY `idx_room` (`id_phong`);

--
-- Chỉ mục cho bảng `rap_chieu`
--
ALTER TABLE `rap_chieu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hoa_don` (`id_hoa_don`);

--
-- Chỉ mục cho bảng `thiet_bi_phong`
--
ALTER TABLE `thiet_bi_phong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `thong_tin_website`
--
ALTER TABLE `thong_tin_website`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_ve` (`ma_ve`),
  ADD KEY `id_tk` (`id_tk`),
  ADD KEY `ve_ibfk_2` (`id_thoi_gian_chieu`),
  ADD KEY `id_ngay_chieu` (`id_ngay_chieu`),
  ADD KEY `check_in_boi` (`check_in_boi`),
  ADD KEY `tao_boi` (`tao_boi`);

--
-- Chỉ mục cho bảng `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_voucher` (`ma_voucher`);

--
-- Chỉ mục cho bảng `yeu_cau_ve`
--
ALTER TABLE `yeu_cau_ve`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `cham_cong`
--
ALTER TABLE `cham_cong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chat_analytics`
--
ALTER TABLE `chat_analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_combo`
--
ALTER TABLE `chi_tiet_combo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `combo_do_an`
--
ALTER TABLE `combo_do_an`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `don_nghi_phep`
--
ALTER TABLE `don_nghi_phep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `khung_gio_chieu`
--
ALTER TABLE `khung_gio_chieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `lich_lam_viec`
--
ALTER TABLE `lich_lam_viec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `lich_su_xem_phim`
--
ALTER TABLE `lich_su_xem_phim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `loaiphim`
--
ALTER TABLE `loaiphim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `nhan_vien_rap`
--
ALTER TABLE `nhan_vien_rap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `phim`
--
ALTER TABLE `phim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `phim_rap`
--
ALTER TABLE `phim_rap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `phong_ghe`
--
ALTER TABLE `phong_ghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1233;

--
-- AUTO_INCREMENT cho bảng `rap_chieu`
--
ALTER TABLE `rap_chieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thiet_bi_phong`
--
ALTER TABLE `thiet_bi_phong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thong_tin_website`
--
ALTER TABLE `thong_tin_website`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT cho bảng `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `yeu_cau_ve`
--
ALTER TABLE `yeu_cau_ve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`),
  ADD CONSTRAINT `binhluan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `taikhoan` (`id`);

--
-- Các ràng buộc cho bảng `don_nghi_phep`
--
ALTER TABLE `don_nghi_phep`
  ADD CONSTRAINT `dnp_ibfk_1` FOREIGN KEY (`id_nhan_vien`) REFERENCES `taikhoan` (`id`),
  ADD CONSTRAINT `dnp_ibfk_2` FOREIGN KEY (`id_rap`) REFERENCES `rap_chieu` (`id`);

--
-- Các ràng buộc cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD CONSTRAINT `lichchieu_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`),
  ADD CONSTRAINT `lichchieu_ibfk_2` FOREIGN KEY (`id_rap`) REFERENCES `rap_chieu` (`id`);

--
-- Các ràng buộc cho bảng `lich_lam_viec`
--
ALTER TABLE `lich_lam_viec`
  ADD CONSTRAINT `llv_ibfk_1` FOREIGN KEY (`id_nhan_vien`) REFERENCES `taikhoan` (`id`),
  ADD CONSTRAINT `llv_ibfk_2` FOREIGN KEY (`id_rap`) REFERENCES `rap_chieu` (`id`);

--
-- Các ràng buộc cho bảng `phim_rap`
--
ALTER TABLE `phim_rap`
  ADD CONSTRAINT `pr_phim_fk` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`),
  ADD CONSTRAINT `pr_rap_fk` FOREIGN KEY (`id_rap`) REFERENCES `rap_chieu` (`id`);

--
-- Các ràng buộc cho bảng `thiet_bi_phong`
--
ALTER TABLE `thiet_bi_phong`
  ADD CONSTRAINT `tbp_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phongchieu` (`id`);

--
-- Các ràng buộc cho bảng `ve`
--
ALTER TABLE `ve`
  ADD CONSTRAINT `ve_checkin_boi_fk` FOREIGN KEY (`check_in_boi`) REFERENCES `taikhoan` (`id`),
  ADD CONSTRAINT `ve_tao_boi_fk` FOREIGN KEY (`tao_boi`) REFERENCES `taikhoan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
