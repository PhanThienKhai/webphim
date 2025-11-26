<?php
/**
 * Model quản lý Rạp Chiếu
 * File: Trang-nguoi-dung/model/rap.php
 */

// Lấy tất cả rạp đang hoạt động
function loadall_rap(){
    $sql = "SELECT * FROM rap_chieu WHERE trang_thai = 1 ORDER BY ten_rap";
    return pdo_query($sql);
}

// Lấy thông tin 1 rạp
function loadone_rap($id) {
    $sql = "SELECT * FROM rap_chieu WHERE id = ?";
    return pdo_query_one($sql, $id);
}

// Trả về các rạp có ít nhất 1 lịch chiếu từ hôm nay trở về sau (dùng cho header)
function load_active_raps(){
    $today = date('Y-m-d');
    $sql = "SELECT DISTINCT r.*
            FROM rap_chieu r
            JOIN lichchieu lc ON lc.id_rap = r.id
            WHERE DATE(lc.ngay_chieu) >= ?
              AND lc.trang_thai_duyet = 'Đã duyệt'
              AND r.trang_thai = 1
            ORDER BY r.ten_rap";
    return pdo_query($sql, $today);
}

// Lấy các rạp đang chiếu phim cụ thể (có lịch chiếu sắp tới)
function get_raps_showing_phim($id_phim) {
    $sql = "SELECT DISTINCT 
                r.id, 
                r.ten_rap, 
                r.dia_chi,
                r.so_dien_thoai,
                r.email,
                COUNT(DISTINCT lc.ngay_chieu) as so_ngay,
                COUNT(kgc.id) as so_suat,
                MIN(lc.ngay_chieu) as ngay_chieu_dau_tien
            FROM rap_chieu r
            JOIN lichchieu lc ON lc.id_rap = r.id
            JOIN khung_gio_chieu kgc ON kgc.id_lich_chieu = lc.id
            WHERE lc.id_phim = ? 
              AND lc.ngay_chieu >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'
              AND r.trang_thai = 1
            GROUP BY r.id, r.ten_rap, r.dia_chi, r.so_dien_thoai, r.email
            ORDER BY r.ten_rap";
    return pdo_query($sql, $id_phim);
}

// Lấy preview giờ chiếu của phim tại rạp (hiển thị 5 giờ đầu)
function get_preview_showtimes($id_phim, $id_rap, $limit = 5) {
    $sql = "SELECT DISTINCT kgc.thoi_gian_chieu
            FROM khung_gio_chieu kgc
            JOIN lichchieu lc ON kgc.id_lich_chieu = lc.id
            WHERE lc.id_phim = ? 
              AND lc.id_rap = ?
              AND lc.ngay_chieu >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'
            ORDER BY kgc.thoi_gian_chieu
            LIMIT ?";
    return pdo_query($sql, $id_phim, $id_rap, $limit);
}

// Kiểm tra rạp có đang chiếu phim này không
function check_rap_showing_phim($id_rap, $id_phim) {
    $sql = "SELECT COUNT(*) as count
            FROM lichchieu lc
            WHERE lc.id_rap = ?
              AND lc.id_phim = ?
              AND lc.ngay_chieu >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'";
    $result = pdo_query_one($sql, $id_rap, $id_phim);
    return $result['count'] > 0;
}
