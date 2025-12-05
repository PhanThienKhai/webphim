<?php

/**
 * Thanh toán lương - Model
 * Xử lý lịch sử thanh toán, receipt, v.v.
 */

// Tạo bảng lịch sử thanh toán nếu chưa tồn tại
function tt_ensure_schema() {
    pdo_execute("CREATE TABLE IF NOT EXISTS lich_su_thanh_toan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_bang_luong INT NOT NULL,
        id_nv INT NOT NULL,
        thang VARCHAR(7) NOT NULL,
        so_tien DECIMAL(15, 2) NOT NULL,
        phuong_thuc VARCHAR(50) DEFAULT 'mock_transfer',
        status VARCHAR(50) DEFAULT 'da_thanh_toan',
        ngay_thanh_toan DATETIME DEFAULT CURRENT_TIMESTAMP,
        ghi_chu TEXT,
        receipt_id VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_nv) REFERENCES nhan_vien(id),
        FOREIGN KEY (id_bang_luong) REFERENCES bang_luong(id),
        INDEX idx_thang (thang),
        INDEX idx_id_nv (id_nv)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// Tạo receipt ID (mock payment reference)
function tt_generate_receipt_id() {
    return 'RCP' . date('Ymd') . mt_rand(100000, 999999);
}

// Lưu lịch sử thanh toán
function tt_save_payment($id_bang_luong, $id_nv, $thang, $so_tien, $ghi_chu = null) {
    tt_ensure_schema();
    
    $receipt_id = tt_generate_receipt_id();
    $phuong_thuc = 'mock_transfer'; // Mock: giả lập transfer
    
    $sql = "INSERT INTO lich_su_thanh_toan 
            (id_bang_luong, id_nv, thang, so_tien, phuong_thuc, status, receipt_id, ghi_chu) 
            VALUES (?, ?, ?, ?, ?, 'da_thanh_toan', ?, ?)";
    
    $result = pdo_execute($sql, $id_bang_luong, $id_nv, $thang, $so_tien, $phuong_thuc, $receipt_id, $ghi_chu);
    
    if ($result) {
        return [
            'success' => true,
            'receipt_id' => $receipt_id,
            'message' => 'Thanh toán thành công'
        ];
    }
    return ['success' => false, 'message' => 'Lỗi lưu lịch sử thanh toán'];
}

// Lấy lịch sử thanh toán của nhân viên
function tt_get_history($id_nv, $limit = 10) {
    tt_ensure_schema();
    
    $sql = "SELECT * FROM lich_su_thanh_toan 
            WHERE id_nv = ? 
            ORDER BY ngay_thanh_toan DESC 
            LIMIT ?";
    
    return pdo_query($sql, $id_nv, $limit);
}

// Lấy chi tiết thanh toán theo receipt ID
function tt_get_receipt($receipt_id) {
    tt_ensure_schema();
    
    $sql = "SELECT l.*, n.ten_nv 
            FROM lich_su_thanh_toan l
            JOIN nhan_vien_rap n ON l.id_nv = n.id
            WHERE l.receipt_id = ?";
    
    return pdo_query_one($sql, $receipt_id);
}

// Lấy tất cả thanh toán của tháng
function tt_get_by_month($thang) {
    tt_ensure_schema();
    
    $sql = "SELECT l.*, n.ten_nv 
            FROM lich_su_thanh_toan l
            JOIN nhan_vien_rap n ON l.id_nv = n.id
            WHERE l.thang = ?
            ORDER BY l.ngay_thanh_toan DESC";
    
    return pdo_query($sql, $thang);
}

// Tính tổng tiền thanh toán theo tháng
function tt_get_total_by_month($thang) {
    tt_ensure_schema();
    
    $sql = "SELECT COUNT(*) as count, SUM(so_tien) as total 
            FROM lich_su_thanh_toan 
            WHERE thang = ? AND status = 'da_thanh_toan'";
    
    $result = pdo_query_one($sql, $thang);
    return $result ?? ['count' => 0, 'total' => 0];
}

// Kiểm tra đã thanh toán chưa
function tt_is_paid($id_nv, $thang) {
    tt_ensure_schema();
    
    $sql = "SELECT id FROM lich_su_thanh_toan 
            WHERE id_nv = ? AND thang = ? AND status = 'da_thanh_toan' 
            LIMIT 1";
    
    return pdo_query_one($sql, $id_nv, $thang) != null;
}

// Lấy chi tiết receipt để in
function tt_get_receipt_details($receipt_id) {
    tt_ensure_schema();
    
    $payment = tt_get_receipt($receipt_id);
    if (!$payment) {
        return null;
    }
    
    return [
        'receipt_id' => $receipt_id,
        'ten_nv' => $payment['ten_nv'],
        'id_nv' => $payment['id_nv'],
        'thang' => $payment['thang'],
        'so_tien' => $payment['so_tien'],
        'ngay_thanh_toan' => $payment['ngay_thanh_toan'],
        'phuong_thuc' => 'Mock Transfer (Giả lập)',
        'status' => $payment['status'],
        'ghi_chu' => $payment['ghi_chu']
    ];
}

?>
