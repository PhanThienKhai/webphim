-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 26, 2025 lúc 01:55 PM
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
(31, 5, 17, 'Đỉnh vãi', '09:26:pm 22-04-2025');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `id` int(11) NOT NULL,
  `ngay_tt` datetime NOT NULL,
  `trang_thai` int(1) DEFAULT 0,
  `thanh_tien` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`id`, `ngay_tt`, `trang_thai`, `thanh_tien`) VALUES
(1, '2025-04-20 10:15:44', 0, 659000),
(2, '2025-04-20 10:25:52', 0, 459000),
(3, '2025-04-22 21:23:56', 0, 658000),
(4, '2025-04-23 12:36:43', 0, 200000),
(5, '2025-04-23 12:37:45', 0, 459000),
(6, '2025-04-23 12:43:23', 0, 425000),
(7, '2025-04-23 13:24:13', 0, 625000),
(8, '2025-04-23 13:25:41', 0, 1346000),
(9, '2025-04-23 13:26:51', 0, 859000),
(10, '2025-04-23 13:31:10', 0, 399000),
(11, '2025-04-25 17:57:38', 0, 425000),
(12, '2025-05-04 13:06:49', 0, 100000),
(13, '2025-05-25 21:31:39', 0, 459000),
(14, '2025-05-26 18:33:03', 0, 459000);

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
(90, 30, 1, '20:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichchieu`
--

CREATE TABLE `lichchieu` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) NOT NULL,
  `ngay_chieu` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lichchieu`
--

INSERT INTO `lichchieu` (`id`, `id_phim`, `ngay_chieu`) VALUES
(1, 5, '2025-05-29'),
(2, 5, '2025-05-31'),
(3, 6, '2025-05-29'),
(4, 6, '2025-05-31'),
(5, 7, '2025-05-29'),
(6, 7, '2025-05-31'),
(7, 8, '2025-05-29'),
(8, 8, '2025-05-31'),
(9, 22, '2025-05-29'),
(10, 22, '2025-05-31'),
(11, 24, '2025-05-29'),
(12, 24, '2025-05-31'),
(13, 25, '2025-05-29'),
(14, 25, '2025-05-31'),
(15, 26, '2025-05-29'),
(16, 26, '2025-05-31'),
(17, 27, '2025-05-29'),
(18, 27, '2025-05-31'),
(19, 28, '2025-05-29'),
(20, 28, '2025-05-31'),
(21, 29, '2025-05-29'),
(22, 29, '2025-05-31'),
(23, 30, '2025-05-29'),
(24, 30, '2025-05-31'),
(25, 31, '2025-05-29'),
(26, 31, '2025-05-31'),
(27, 33, '2025-05-29'),
(28, 33, '2025-05-31'),
(29, 36, '2025-05-29'),
(30, 36, '2025-05-31');

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
  `link_trailer` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phim`
--

INSERT INTO `phim` (`id`, `tieu_de`, `daodien`, `dienvien`, `img`, `mo_ta`, `date_phat_hanh`, `thoi_luong_phim`, `id_loai`, `quoc_gia`, `gia_han_tuoi`, `link_trailer`) VALUES
(5, 'Kỳ án trên băng', 'Justine Triet', 'Sandra Hüller, Swann Arlaud, Milo Machado-Graner', 'Kỳ án trên đồi tuyết.jpg', 'Cuộc sống của nhà văn Sandra cùng chồng Samuel và cậu con trai mù Daniel ở căn nhà gỗ hẻo lánh trên dãy Alps bất ngờ bị xáo trộn khi Samuel được tìm thấy đã chết một cách bí ẩn trên tuyết, khiến Sandra trở thành nghi phạm chính và mối quan hệ đầy mâu thuẫn giữa cô và chồng dần được phơi bày trước phiên tòa.', '2024-05-20', 87, 9, 'Anh', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/dZdtN-Tce78?si=zqudwVQYo8d2xdOv\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(6, 'Búp bê', 'Scott Cawthon', 'Scott Cawthon, Leon Riskin, Allen Simpson', 'Năm đêm kinh hoàng.jpg', 'Scott Cawthon, Leon Riskin, Allen Simpson', '2024-05-20', 123, 9, 'Trung Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/RdW5xbBhDfk?si=nyTKv1Gzf0-6s21k\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(7, 'Đất rừng phương nam', 'Nguyễn Quang Dũng', ' Trấn Thành, Nguyễn Trinh Hoan, Nguyen Tri Vien', 'Đất rừng phương nam.jpg', 'Đất rừng phương Nam là một bộ phim điện ảnh Việt Nam thuộc thể loại sử thi – tâm lý – chính kịch ra mắt vào năm 2023, được dựa trên cuốn tiểu thuyết cùng tên của nhà văn Đoàn Giỏi và bộ phim truyền hình Đất phương Nam vào năm 1997', '2024-05-20', 123, 5, 'Việt Nam', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/hktzirCnJmQ?si=4x--iJO1e1QnLPFj\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(8, 'THE MARVELS', ' Nia DaCosta', 'Brie Larson, Samuel L. Jackson, Zawe Ashton', 'Biệt đội Marvels.jpg', 'Carol Danvers bị vướng vào sức mạnh của Kamala Khan và Monica Rambeau, buộc họ phải hợp tác với nhau để cứu vũ trụ.', '2024-05-20', 233, 2, 'Mỹ', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/rX1znA4na5I?si=3csEmDaichYD6QBZ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(22, 'QUỶ LÙN TINH NGHỊCH: ĐỒNG TÂM HIỆP NHẠC', 'Walt Dohrn, Tim Heitz', 'Anna Kendrick, Zooey Deschanel, Justin Timberlake', 'wolfoo và hòn đảo kỳ bí.jpg', 'Sự xuất hiện đột ngột của John Dory, anh trai thất lạc đã lâu của Branch mở ra quá khứ bí mật được che giấu bấy lâu nay của Branch. Đó là quá khứ về một ban nhạc có tên BroZone từng rất nổi tiếng nhưng đã tan rã. Hành trình đi tìm lại các thành viên để làm một ban nhạc như xưa trở thành chuyến phiêu lưu âm nhạc đầy cảm xúc, tràn trề hi vọng về một cuộc sum họp gia đình tuyệt với nhất.', '2024-05-20', 85, 3, 'Mỹ', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/izi44lM_HSo?si=I5JLlxyg-9NKl5nN\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(24, 'CHÚA TỂ CỦA NHỮNG CHIẾC NHẪN - SỰ TRỞ VỀ CỦA NHÀ VUA ', 'Peter Jackson', 'Elijah Wood, Viggo Mortensen, Ian McKellen,...', 'la.jpg', 'Chương cuối cùng của loạt phim Chúa Tể Của Những Chiếc Nhẫn mang tới trận chiến cuối cùng giữa thiện và ác cùng tương lai của Trung Địa. Frodo và Sam đến với Mordor trên hành trình phá hủy chiếc nhẫn, trong khi Aragon tiếp tục lãnh đạo nhóm của mình chống lại đoàn quân của Sauron. Phần phim thứ ba này được coi là thành công nhất cả loạt phim trên khía cạnh thương mại và phê bình, với doanh thu toàn cầu vượt mốc 1 tỷ đô la cùng 11 giải Oscar danh giá. (Chiếu lại từ 29/11/2023)', '2024-05-20', 125, 1, 'Ấn Độ', 18, '<iframe width=\"560px\" height=\"315px\" src=\"https://www.youtube.com/embed/4qhMENRhQxo?si=fUzhrjWRsv5t6yfF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(25, 'YÊU LẠI VỢ NGẦU', 'Nam Dae-jung', 'Kang Ha-neul, Jung So-min, Kim Sun-young, Lim Chul-hyung, Yoon Kyung-ho, Jo Min-soo,....', 'vongau.jpg', 'Cặp vợ chồng trẻ No Jung Yeol (Kang Ha-neul) và Hong Na Ra (Jung So-min) từ cuộc sống hôn nhân màu hồng dần “hiện nguyên hình” trở thành cái gai trong mắt đối phương với vô vàn thói hư, tật xấu. Không thể đi đến tiếng nói chung, Jung Yeol và Na Ra quyết định ra toà ly dị. Tuy nhiên, họ phải chờ 30 ngày cho đến khi mọi thủ tục chính thức được hoàn tất. Trong khoảng thời gian này, một vụ tai nạn xảy ra khiến cả hai mất đi ký ức và không nhớ người kia là ai. 30 ngày chờ đợi để được “đường ai nấy đi” nhưng nhiều tình huống trớ trêu khiến cả hai bắt đầu nảy sinh tình cảm trở lại. Liệu khi nhớ ra mọi thứ, họ vẫn sẽ ký tên vào tờ giấy ly hôn?', '2024-05-20', 119, 3, 'Hàn Quốc', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/081I7DXNknc?si=S1UeeKF1caKSTcAJ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(26, 'KẺ ĂN HỒN', 'Trần Hữu Tấn', 'Hoàng Hà, Võ Điền Gia Huy, Huỳnh Thanh Trực, NSƯT Chiều Xuân, Nghệ sĩ Viết Liên, NSND Ngọc Thư, Nguyễn Hữu Tiến, Nguyễn Phước Lộc, Nghinh Lộc, Lý Hồng Ân, Vũ Đức Ngọc…', 'anhon.jpg', 'him về hàng loạt cái chết bí ẩn ở Làng Địa Ngục, nơi có ma thuật cổ xưa: 5 mạng đổi bình Rượu Sọ Người. Thập Nương - cô gái áo đỏ là kẻ nắm giữ bí thuật luyện nên loại rượu mạnh nhất!', '2024-05-20', 135, 1, 'Việt Nam', 18, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/Ac5PuRpFeRU?si=FCxQZOQ2_88BleIl\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(27, 'NGƯỜI VỢ CUỐI CÙNG', 'Victor Vũ', 'Kaity Nguyễn - Thuận Nguyễn - NSƯT Quang Thắng - NSƯT Kim Oanh - Đinh Ngọc Diệp - Anh Dũng - Quốc Huy - Bé Lưu Ly', 'nguoivo.jpg', 'Lấy cảm hứng từ tiểu thuyết Hồ Oán Hận, của nhà văn Hồng Thái, Người Vợ Cuối Cùng là một bộ phim tâm lý cổ trang, lấy bối cảnh Việt Nam vào triều Nguyễn. LINH - Người vợ bất đắc dĩ của một viên quan tri huyện, xuất thân là con của một gia đình nông dân nghèo khó, vì không thể hoàn thành nghĩa vụ sinh con nối dõi nên đã chịu sự chèn ép của những người vợ lớn trong gia đình. Sự gặp gỡ tình cờ của cô và người yêu thời thanh mai trúc mã của mình - NH N đã dẫn đến nhiều câu chuyện bất ngờ xảy ra khiến cuộc sống cô hoàn toàn thay đổi.', '2024-05-20', 132, 2, 'Việt Nam', 16, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/TtS_V55VcxA?si=NnMnDilitNBaTXOF\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(28, 'THIẾU NIÊN VÀ CHIM DIỆC', 'Miyazaki Hayao', 'Santoki Soma, Suda Masaki, Shibasaki Ko, Aimyon, Kimura Yoshino, Kimura Takuya, Kobayashi Karou', 'vietnam_-_poster_-_15.12.2023_1_.jpg', 'Đến từ Studio Ghibli và đạo diễn Miyazaki Hayao, bộ phim là câu chuyện về hành trình kỳ diệu của cậu thiếu niên Mahito trong một thế giới hoàn toàn mới lạ. Trải qua nỗi đau mất mẹ cùng mối quan hệ chẳng mấy vui vẻ với gia đình cũng như bạn học, Mahito dần cô lập bản thân... cho đến khi cậu gặp một chú chim diệc biết nói kỳ lạ. Mahito cùng chim diệc bước vào một tòa tháp bí ẩn, nơi một thế giới thần kỳ mở ra, đưa cậu gặp gỡ những người mình yêu thương... trong một nhân dạng hoàn toàn khác.', '2024-05-20', 124, 5, 'Nhật Bản', 6, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/eggzAobZzHc?si=aQBXzb5cGIvWybYy\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(29, 'Bảy Viên Ngọc Rồng Siêu Cấp: Siêu Anh Hùng', 'Tokuda', 'Masako Nozawa,Toshio Furukawa,Yuko Minaguchi,...', 'poster.jpg', 'Đội quân Ruy Băng Đỏ đã bị Son Goku tiêu diệt. Thế nhưng, những kẻ kế nghiệp của chúng đã tạo ra hai chiến binh Android mới là Gamma 1 và Gamma 2. Hai Android này tự nhận mình là “Siêu anh hùng”. Chúng bắt đầu tấn công Piccolo và Gohan… Mục tiêu của Đội quân Ruy Băng Đỏ mới này là gì? Trước nguy cơ cận kề, đã đến lúc các siêu anh hùng thực thụ phải thức tỉnh!', '2024-05-20', 128, 8, 'Nhật Bản', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cQoNi0BVkj8?si=noUmGcjm6CJn8Rm0\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(30, 'NHỮNG KỶ NGUYÊN CỦA TAYLOR SWIFT', 'Sam Wrench', 'Taylor Swift', '700x1000_18_.jpg', 'Hiện tượng văn hóa tiếp tục trên màn ảnh lớn! Đắm chìm trong trải nghiệm xem phim hòa nhạc độc nhất vô nhị với góc nhìn ngoạn mục, đậm chất điện ảnh về chuyến lưu diễn mang tính lịch sử. Khuyến khích khán giả đeo vòng tay tình bạn và mặc trang phục Taylor Swift Eras Tour!', '2024-05-20', 168, 9, 'Mỹ', 12, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/cwLAor_smGw?si=2xnYd5m-iCFpB-Yn\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(31, 'WONKA', 'Paul King', 'Timothée Chalamet, Hugh Grant, Rowan Atkinson, Matt Lucas, Mathew Baynton.', '700x1000_22_.jpg', 'Dựa trên nhân vật từ quyến sách gối đầu giường của các em nhỏ trên toàn thế giới \"Charlie và Nhà Máy Sôcôla\" và phiên bản phim điện ảnh cùng tên vào năm 2005, WONKA kể câu chuyện kỳ diệu về hành trình của nhà phát minh, ảo thuật gia và nhà sản xuất sôcôla vĩ đại nhất thế giới trở thành WILLY WONKA đáng yêu mà chúng ta biết ngày nay. Từ đạo diễn loạt phim Paddington và nhà sản xuất loạt phim chuyển thể đình đám Harry Potter, WONKA hứa hẹn sẽ là một bộ phim đầy vui nhộn và màu sắc cho khán giả dịp Lễ Giáng Sinh năm nay.', '2024-05-20', 116, 9, 'Anh', 13, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/1JHj4hc5MEI?si=buPaabXX7WVAd61P\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(33, 'NGƯỜI MẶT TRỜI', 'vfdv', 'df', '406x600-nmt.jpg', '400 năm qua, loài Ma Cà Rồng đã bí mật sống giữa loài người trong hòa bình, nhưng hiểm họa bỗng ập đến khi một cô gái loài người phát hiện được thân phận của hai anh em Ma Cà Rồng. Người anh khát máu quyết săn lùng cô để bảo vệ bí mật giống loài, trong khi người còn lại chạy đua với thời gian để bảo vệ cô bằng mọi giá.', '2024-05-20', 145, 9, 'dfb', 32, '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/L3t9jW4eRAs?si=OwjViaUsQ2yMosxw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>'),
(36, 'Địa đạo', 'Bùi Thạc Chuyên', 'Thái Hòa; Quang Tuấn; Diễm Hằng Lamoon; Anh Tú Wilson; Hồ Thu Anh', '350x495-diadao_1.jpg', 'Nhân dịp kỷ niệm 50 năm đất nước hoà bình này còn phim nào thoả được nỗi niềm thưởng thức thước phim thời chiến đầy hào hùng như Địa Đạo: Mặt Trời Trong Bóng Tối. Nay còn có thêm định dạng 4DX cho khán giả trải nghiệm chui hầm dưới lòng Củ Chi đất thép.\r\n', '2025-04-04', 128, 8, 'Việt ', 18, 'https://www.youtube.com/watch?v=7BTwfVoP4YY');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongchieu`
--

CREATE TABLE `phongchieu` (
  `id` int(11) NOT NULL,
  `id_rap` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phongchieu`
--

INSERT INTO `phongchieu` (`id`, `id_rap`, `name`) VALUES
(1, 1, 'P101'),
(2, 2, 'P102'),
(15, 0, 'P103');

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
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `name`, `user`, `pass`, `email`, `phone`, `dia_chi`, `vai_tro`, `img`) VALUES
(10, 'Phan Thiên Khải', 'admin', '123456', 'Phanthienkhai111@gmail.com', '0384104942', 'Phu Yen', 2, ''),
(14, 'nhanvien', 'nhanvien', '123456', 'nhanvien@gmail.com', '0797517278', 'nhanvien', 1, ''),
(17, 'Phan Thiên Khải', 'phanthienkhai', 'Kpy123456', 'Phanthienkhai2901@gmail.com', '0384104942', 'Gò Vấp', 0, ''),
(18, 'Cao Lan Anh', 'caolananh', '123456', 'caolananhn@gmail.com', '0797517278', 'Gò Vấp', 0, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve`
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
-- Đang đổ dữ liệu cho bảng `ve`
--

INSERT INTO `ve` (`id`, `id_phim`, `id_thoi_gian_chieu`, `id_ngay_chieu`, `id_tk`, `ghe`, `combo`, `price`, `id_hd`, `trang_thai`, `ngay_dat`) VALUES
(311, 28, 59, 40, 17, 'A4,A5', 'Combo-Halo ', '459000', 305, 0, '2025-04-17 17:09:27'),
(312, 26, 36, 25, 17, 'F5,F6', 'Combo-Halo ', '659000', 1, 1, '2025-04-20 10:15:44'),
(313, 5, 11, 14, 17, 'A8,A7,A6,A5', 'Combo-Coca ', '459000', 2, 1, '2025-04-20 10:25:52'),
(314, 27, 25, 24, 17, 'A4,A3', 'Combo-Hủy-Diệt , Combo-Halo ', '658000', 3, 1, '2025-04-22 21:23:56'),
(315, 27, 25, 24, 17, 'A6,A5', '', '200000', 4, 0, '2025-04-23 12:36:43'),
(316, 27, 25, 24, 17, 'A7,A6,A5', 'Combo-Halo ', '459000', 5, 0, '2025-04-23 12:37:45'),
(317, 7, 55, 38, 17, 'A7,A6,A5', 'Combo-Wish-C1 ', '425000', 6, 1, '2025-04-23 12:43:23'),
(318, 24, 51, 34, 18, 'A8,A7,A6,A5,A4', 'Combo-Wish-C1 ', '625000', 7, 1, '2025-04-23 13:24:13'),
(319, 8, 12, 13, 18, 'H5', 'Combo-Coca , Combo-Halo , Combo-Halo-2 , Combo-Hủy-Diệt , Combo-Wish-B3 , Combo-Wish-C1 ', '1346000', 8, 1, '2025-04-23 13:25:41'),
(320, 5, 54, 37, 18, 'H5,H4', 'Combo-Halo ', '859000', 9, 1, '2025-04-23 13:26:51'),
(321, 26, 36, 25, 18, 'A6,A5', 'Combo-Hủy-Diệt ', '399000', 10, 1, '2025-04-23 13:31:10'),
(322, 26, 36, 25, 17, 'A2,A3,A4', 'Combo-Wish-C1 ', '425000', 11, 1, '2025-04-25 17:57:38'),
(323, 26, 36, 25, 17, 'A7', '', '100000', 12, 0, '2025-05-04 13:06:49'),
(324, 26, 53, 36, 17, 'A5,A4', 'Combo-Halo ', '459000', 13, 1, '2025-05-25 21:31:39'),
(325, 7, 7, 11, 17, 'A4,A3', 'Combo-Halo ', '459000', 14, 0, '2025-05-26 18:33:03');

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
-- Chỉ mục cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`);

--
-- Chỉ mục cho bảng `loaiphim`
--
ALTER TABLE `loaiphim`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_loai` (`id_loai`);

--
-- Chỉ mục cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tk` (`id_tk`),
  ADD KEY `ve_ibfk_2` (`id_thoi_gian_chieu`),
  ADD KEY `id_ngay_chieu` (`id_ngay_chieu`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `khung_gio_chieu`
--
ALTER TABLE `khung_gio_chieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT cho bảng `loaiphim`
--
ALTER TABLE `loaiphim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `phim`
--
ALTER TABLE `phim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

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
-- Các ràng buộc cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD CONSTRAINT `lichchieu_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
