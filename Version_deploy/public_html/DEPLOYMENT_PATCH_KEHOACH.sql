-- ============================================================================
-- DEPLOYMENT PATCH - Khắc phục vấn đề Kế Hoạch Chiếu
-- ============================================================================
-- Ngày tạo: 2025-12-08
-- 
-- VẤN ĐỀ:
-- 1. Status hiển thị "?" - trang_thai_duyet = NULL
-- 2. Quản lý cụm không thấy kế hoạch để duyệt
--
-- GIẢI PHÁP:
-- 1. Cập nhật tất cả NULL trang_thai_duyet = 'Chờ duyệt'
-- 2. Thêm cột id_cum vào bảng rap_chieu (nếu chưa có)
-- ============================================================================

-- 1. Cập nhật tất cả NULL trang_thai_duyet thành 'Chờ duyệt'
UPDATE lichchieu 
SET trang_thai_duyet = 'Chờ duyệt'
WHERE trang_thai_duyet IS NULL 
   OR trang_thai_duyet = '';

-- 2. Kiểm tra xem cột trang_thai_duyet có nullable chưa - nếu có thì đổi thành NOT NULL
-- (An toàn: chỉ thay đổi nếu cần)
-- ALTER TABLE lichchieu 
-- MODIFY COLUMN trang_thai_duyet VARCHAR(50) NOT NULL DEFAULT 'Chờ duyệt';

-- Lưu ý: Nếu bạn chắc chắn muốn thay đổi constraint, hãy bỏ comment dòng trên
-- Nhưng nên backup database trước khi chạy

-- 3. Kiểm tra xem bảng rap_chieu có cột id_cum chưa (nếu chưa có thì thêm)
-- CẢNH BÁO: Chỉ chạy 2 dòng dưới nếu bảng rap_chieu CHƯA CÓ cột id_cum
-- ALTER TABLE rap_chieu ADD COLUMN id_cum INT DEFAULT NULL AFTER id;
-- ALTER TABLE rap_chieu ADD FOREIGN KEY (id_cum) REFERENCES cum(id) ON DELETE SET NULL;

-- ============================================================================
-- KIỂM TRA KẾT QUẢ
-- ============================================================================
-- Chạy câu lệnh này để kiểm tra:
--
-- SELECT id, ma_ke_hoach, trang_thai_duyet, COUNT(*) as so_lich
-- FROM lichchieu 
-- GROUP BY ma_ke_hoach 
-- HAVING trang_thai_duyet IS NULL OR trang_thai_duyet = '';
-- 
-- Kết quả nên trống (0 bản ghi)
-- 
-- ============================================================================
