<?php
function lichchieu_select_by_id_phim($id)
{
    $sql = "SELECT l.id, l.ngay_chieu, phim.tieu_de
    FROM lichchieu l
    LEFT JOIN phim ON phim.id = l.id_phim
    WHERE l.id_phim = '$id'
    ORDER BY l.ngay_chieu ASC;
    ";
        $re=pdo_query($sql);
        return  $re;
}

function khunggiochieu_select_by_idxc($id_lc)
{
    $sql = "SELECT g.id, g.id_lich_chieu,g.thoi_gian_chieu,lichchieu.ngay_chieu,g.id_phong FROM khung_gio_chieu g 
    INNER JOIN lichchieu ON lichchieu.id = g.id_lich_chieu 
    INNER JOIN phongchieu ON phongchieu.id = g.id_phong WHERE lichchieu.id ='$id_lc'";
    $re=pdo_query($sql);
    return  $re;
}

// Load lichchieu (ngay_chieu entries) for a specific film in a specific rap
function lichchieu_select_by_id_phim_and_rap($id_phim, $id_rap)
{
    $sql = "SELECT l.id, l.ngay_chieu, phim.tieu_de, l.id_rap
    FROM lichchieu l
    LEFT JOIN phim ON phim.id = l.id_phim
    WHERE l.id_phim = ? AND l.id_rap = ?
      AND l.trang_thai_duyet = 'Đã duyệt'
    ORDER BY l.ngay_chieu ASC";
    return pdo_query($sql, $id_phim, $id_rap);
}

// Lấy ngày chiếu của phim tại rạp cụ thể (mới)
function get_dates_for_phim_at_rap($id_phim, $id_rap) {
    $sql = "SELECT 
                lc.ngay_chieu,
                lc.id as id_lich_chieu,
                COUNT(kgc.id) as so_suat
            FROM lichchieu lc
            LEFT JOIN khung_gio_chieu kgc ON kgc.id_lich_chieu = lc.id
            WHERE lc.id_phim = ?
              AND lc.id_rap = ?
              AND lc.ngay_chieu >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'
            GROUP BY lc.ngay_chieu, lc.id
            ORDER BY lc.ngay_chieu";
    // Không giới hạn - hiển thị tất cả ngày chiếu
    return pdo_query($sql, $id_phim, $id_rap);
}

// Lấy giờ chiếu theo phim, rạp, ngày (mới)
function get_showtimes_for_date($id_phim, $id_rap, $ngay_chieu) {
    $sql = "SELECT 
                kgc.id,
                kgc.thoi_gian_chieu,
                p.name as ten_phong,
                p.id as id_phong,
                p.loai_phong,
                lc.id as id_lich_chieu,
                -- Đếm tổng ghế trong phòng
                (SELECT COUNT(*) 
                 FROM phong_ghe pg 
                 WHERE pg.id_phong = p.id AND pg.active = 1
                ) as tong_ghe
            FROM khung_gio_chieu kgc
            JOIN lichchieu lc ON kgc.id_lich_chieu = lc.id
            JOIN phongchieu p ON kgc.id_phong = p.id
            WHERE lc.id_phim = ?
              AND lc.id_rap = ?
              AND lc.ngay_chieu = ?
              AND lc.trang_thai_duyet = 'Đã duyệt'
            ORDER BY kgc.thoi_gian_chieu";
    
    $results = pdo_query($sql, $id_phim, $id_rap, $ngay_chieu);
    
    // Tính ghế trống cho mỗi suất chiếu
    foreach ($results as &$r) {
        // Đếm ghế đã đặt
        $sql_ghe = "SELECT ghe FROM ve 
                    WHERE id_thoi_gian_chieu = ? 
                      AND trang_thai = 1";
        $ghe_dat = pdo_query($sql_ghe, $r['id']);
        
        $tong_ghe_dat = 0;
        foreach ($ghe_dat as $g) {
            if (!empty($g['ghe'])) {
                $ghe_array = explode(',', $g['ghe']);
                $tong_ghe_dat += count($ghe_array);
            }
        }
        
        $r['ghe_da_dat'] = $tong_ghe_dat;
        $r['ghe_trong'] = $r['tong_ghe'] - $tong_ghe_dat;
    }
    
    return $results;
}

// Helper function: Lấy tên ngày trong tuần (tiếng Việt)
function get_day_name($date) {
    $days = [
        'Monday' => 'Thứ Hai',
        'Tuesday' => 'Thứ Ba',
        'Wednesday' => 'Thứ Tư',
        'Thursday' => 'Thứ Năm',
        'Friday' => 'Thứ Sáu',
        'Saturday' => 'Thứ Bảy',
        'Sunday' => 'Chủ Nhật'
    ];
    $day_en = date('l', strtotime($date));
    return $days[$day_en] ?? $day_en;
}
