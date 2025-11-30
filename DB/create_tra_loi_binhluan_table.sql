-- Create table for comment replies
CREATE TABLE IF NOT EXISTS `tra_loi_binhluan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_binhluan` int(11) NOT NULL COMMENT 'ID bình luận gốc',
  `id_user` int(11) DEFAULT NULL COMMENT 'ID người trả lời',
  `noidung` text NOT NULL COMMENT 'Nội dung trả lời',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo trả lời',
  PRIMARY KEY (`id`),
  KEY `id_binhluan` (`id_binhluan`),
  CONSTRAINT `tra_loi_binhluan_ibfk_1` FOREIGN KEY (`id_binhluan`) REFERENCES `binhluan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Trả lời bình luận phim';
