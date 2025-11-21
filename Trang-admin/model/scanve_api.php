<?php
/**
 * Scanve API - Xử lý kiểm tra vé & check-in
 * Hỗ trợ: QR Scanner + Manual Input
 */

function check_ticket_by_code($ma_ve) {
    // Tìm vé theo mã hoặc ID
    $sql = "SELECT v.id, v.ma_ve, v.trang_thai, v.id_phim, v.id_ngay_chieu, 
                   v.id_thoi_gian_chieu, v.ghe, v.check_in_luc, v.check_in_boi,
                   p.tieu_de, lc.ngay_chieu, kgc.thoi_gian_chieu, pgc.name as tenphong
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pgc ON pgc.id = kgc.id_phong
            WHERE v.ma_ve = ? OR v.id = ?
            LIMIT 1";
    
    return pdo_query_one($sql, $ma_ve, $ma_ve);
}

function check_ticket_by_id($id_ve) {
    // Tìm vé theo ID
    $sql = "SELECT v.id, v.ma_ve, v.trang_thai, v.id_phim, v.id_ngay_chieu, 
                   v.id_thoi_gian_chieu, v.ghe, v.check_in_luc, v.check_in_boi,
                   p.tieu_de, lc.ngay_chieu, kgc.thoi_gian_chieu, pgc.name as tenphong
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pgc ON pgc.id = kgc.id_phong
            WHERE v.id = ?
            LIMIT 1";
    
    return pdo_query_one($sql, $id_ve);
}

function update_ticket_checkin($id_ve, $staff_id) {
    $sql = "UPDATE ve SET trang_thai = 4, check_in_luc = NOW(), check_in_boi = ? WHERE id = ?";
    return pdo_execute($sql, $staff_id, $id_ve);
}

function validate_ticket($ticket) {
    $errors = [];

    // Kiểm tra trang thái vé
    if ($ticket['trang_thai'] == 0) {
        $errors[] = 'Vé chưa thanh toán';
    } elseif ($ticket['trang_thai'] == 3) {
        $errors[] = 'Vé đã bị hủy';
    } elseif ($ticket['trang_thai'] == 4) {
        $errors[] = 'Vé đã được check-in lúc ' . htmlspecialchars($ticket['check_in_luc']);
    } elseif ($ticket['trang_thai'] != 1) {
        $errors[] = 'Trạng thái vé không rõ';
    }

    // Không kiểm tra thời gian chiếu - cho phép xem lại vé bất kỳ lúc nào
    // Người dùng có thể kiểm tra vé ngay cả sau khi suất chiếu kết thúc

    return $errors;
}

function get_checkin_history($id_rap) {
    $sql = "SELECT v.id, v.ma_ve, p.tieu_de, p.tieu_de as phim, v.check_in_luc, tk.name as staff_name
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN taikhoan tk ON tk.id = v.check_in_boi
            LEFT JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            WHERE v.trang_thai = 4 
              AND DATE(v.check_in_luc) = CURDATE()
              AND lc.id_rap = ?
            ORDER BY v.check_in_luc DESC
            LIMIT 50";
    
    return pdo_query($sql, $id_rap);
}
