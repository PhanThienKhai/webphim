<?php
// Model xử lý tích điểm

// ============ LẤY QUY TẮC TÍCH ĐIỂM ============
function get_quy_tac_tich_diem($loai = 'dat_ve') {
    $sql = "SELECT * FROM quy_tac_tich_diem 
            WHERE loai = ? 
            AND trang_thai = 1 
            AND ngay_bat_dau <= CURDATE() 
            AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= CURDATE())
            ORDER BY id DESC LIMIT 1";
    return pdo_query_one($sql, $loai);
}

// ============ TÍNH ĐIỂM TỪ SỐ TIỀN ============
function tinh_diem_tu_tien($so_tien, $loai = 'dat_ve') {
    $quy_tac = get_quy_tac_tich_diem($loai);
    if (!$quy_tac) return 0;
    
    // Nếu có điểm cố định, trả về điểm cố định
    if ($quy_tac['diem_co_dinh'] > 0) {
        return (int)$quy_tac['diem_co_dinh'];
    }
    
    // Tính theo tỷ lệ quy đổi
    $ti_le = (float)$quy_tac['ti_le_quy_doi'];
    $diem = (int)($so_tien * $ti_le);
    
    return $diem;
}

// ============ CỘNG ĐIỂM ============
function cong_diem($id_tk, $so_diem, $ly_do, $id_ve = null, $id_hoa_don = null) {
    if ($so_diem <= 0) return false;
    
    // Kiểm tra vai trò (không cộng điểm cho guest)
    $user = pdo_query_one("SELECT vai_tro, hang_thanh_vien FROM taikhoan WHERE id = ?", $id_tk);
    if (!$user || $user['vai_tro'] != 0) {
        return false; // Chỉ cộng điểm cho khách hàng thành viên (vai_tro = 0)
    }
    
    // Lấy hệ số hạng
    $hang = pdo_query_one("SELECT ti_le_tich_diem FROM hang_thanh_vien WHERE ma_hang = ?", $user['hang_thanh_vien']);
    
    // Nhân hệ số theo hạng
    if ($hang) {
        $so_diem = (int)($so_diem * (float)$hang['ti_le_tich_diem']);
    }
    
    // Cập nhật điểm trong tài khoản
    $sql = "UPDATE taikhoan 
            SET diem_tich_luy = diem_tich_luy + ?,
                tong_diem_tich_luy = tong_diem_tich_luy + ?
            WHERE id = ?";
    pdo_execute($sql, $so_diem, $so_diem, $id_tk);
    
    // Lưu lịch sử
    $sql = "INSERT INTO lich_su_diem (id_tk, loai_giao_dich, so_diem, ly_do, id_ve, id_hoa_don) 
            VALUES (?, 'cong', ?, ?, ?, ?)";
    pdo_execute($sql, $id_tk, $so_diem, $ly_do, $id_ve, $id_hoa_don);
    
    // Kiểm tra nâng hạng
    kiem_tra_nang_hang($id_tk);
    
    return $so_diem;
}

// ============ TRỪ ĐIỂM (Dùng điểm) ============
function tru_diem($id_tk, $so_diem, $ly_do, $id_ve = null, $id_hoa_don = null) {
    if ($so_diem <= 0) return false;
    
    // Kiểm tra đủ điểm không
    $user = pdo_query_one("SELECT diem_tich_luy FROM taikhoan WHERE id = ?", $id_tk);
    if (!$user || $user['diem_tich_luy'] < $so_diem) {
        return false; // Không đủ điểm
    }
    
    // Trừ điểm
    $sql = "UPDATE taikhoan SET diem_tich_luy = diem_tich_luy - ? WHERE id = ?";
    pdo_execute($sql, $so_diem, $id_tk);
    
    // Lưu lịch sử
    $sql = "INSERT INTO lich_su_diem (id_tk, loai_giao_dich, so_diem, ly_do, id_ve, id_hoa_don) 
            VALUES (?, 'tru', ?, ?, ?, ?)";
    pdo_execute($sql, $id_tk, $so_diem, $ly_do, $id_ve, $id_hoa_don);
    
    return true;
}

// ============ KIỂM TRA & NÂNG HẠNG ============
function kiem_tra_nang_hang($id_tk) {
    $user = pdo_query_one("SELECT tong_diem_tich_luy, hang_thanh_vien FROM taikhoan WHERE id = ?", $id_tk);
    if (!$user) return false;
    
    // Lấy hạng tương ứng
    $hang_moi = pdo_query_one("
        SELECT ma_hang, ten_hang FROM hang_thanh_vien 
        WHERE diem_toi_thieu <= ? 
        ORDER BY diem_toi_thieu DESC LIMIT 1
    ", $user['tong_diem_tich_luy']);
    
    if ($hang_moi && $hang_moi['ma_hang'] != $user['hang_thanh_vien']) {
        // Cập nhật hạng mới
        pdo_execute("UPDATE taikhoan SET hang_thanh_vien = ? WHERE id = ?", $hang_moi['ma_hang'], $id_tk);
        
        return $hang_moi; // Trả về thông tin hạng mới để hiển thị thông báo
    }
    
    return false;
}

// ============ LẤY LỊCH SỬ ĐIỂM ============
function get_lich_su_diem($id_tk, $limit = 20) {
    // LIMIT không thể dùng placeholder trong PDO, phải validate và concat trực tiếp
    $limit = (int)$limit; // Ép kiểu để tránh SQL injection
    $sql = "SELECT * FROM lich_su_diem 
            WHERE id_tk = ? 
            ORDER BY ngay_tao DESC 
            LIMIT $limit";
    return pdo_query($sql, $id_tk);
}

// ============ TÍNH GIẢM GIÁ THEO HẠNG ============
function tinh_giam_gia_theo_hang($id_tk, $tong_tien) {
    $user = pdo_query_one("SELECT hang_thanh_vien FROM taikhoan WHERE id = ?", $id_tk);
    if (!$user) return 0;
    
    $hang = pdo_query_one("SELECT ti_le_giam_gia FROM hang_thanh_vien WHERE ma_hang = ?", $user['hang_thanh_vien']);
    if (!$hang) return 0;
    
    $ti_le = (float)$hang['ti_le_giam_gia'];
    $giam_gia = (int)($tong_tien * $ti_le / 100);
    
    return $giam_gia;
}

// ============ QUY ĐỔI ĐIỂM → TIỀN ============
function quy_doi_diem_thanh_tien($so_diem, $ti_le = 1000) {
    // Mặc định: 1 điểm = 1.000đ
    return $so_diem * $ti_le;
}

// ============ TÍNH ĐIỂM CẦN DÙNG ĐỂ GIẢM X TIỀN ============
function tinh_diem_can_dung($so_tien_muon_giam, $ti_le = 1000) {
    return (int)ceil($so_tien_muon_giam / $ti_le);
}

// ============ LẤY THÔNG TIN HẠNG THÀNH VIÊN ============
function get_thong_tin_hang($ma_hang) {
    $sql = "SELECT * FROM hang_thanh_vien WHERE ma_hang = ?";
    return pdo_query_one($sql, $ma_hang);
}

// ============ LẤY TẤT CẢ HẠNG THÀNH VIÊN ============
function get_all_hang_thanh_vien() {
    $sql = "SELECT * FROM hang_thanh_vien ORDER BY thu_tu ASC";
    return pdo_query($sql);
}
?>
