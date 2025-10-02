<?php

// Lớp tương thích với schema khuyen_mai trong DB hiện tại
// Bảng cột tham chiếu: id, ten_khuyen_mai, mo_ta, phan_tram_giam, gia_tri_giam, loai_giam (phan_tram|tien_mat),
// ngay_bat_dau, ngay_ket_thuc, dieu_kien_ap_dung, trang_thai, ngay_tao

function km_all($unused = null){
    return pdo_query("SELECT * FROM khuyen_mai ORDER BY id DESC");
}

function km_one($id){ return pdo_query_one("SELECT * FROM khuyen_mai WHERE id=?", $id); }

function km_insert($ten, $ma_code, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta, $id_rap = null){
    pdo_execute("INSERT INTO khuyen_mai(ten_khuyen_mai, ma_khuyen_mai, loai_giam, phan_tram_giam, gia_tri_giam, ngay_bat_dau, ngay_ket_thuc, trang_thai, dieu_kien_ap_dung, mo_ta, id_rap)
                 VALUES(?,?,?,?,?,?,?,?,?,?,?)",
        $ten, strtoupper(trim($ma_code)), $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta, $id_rap);
}

function km_update($id, $ten, $ma_code, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta, $id_rap = null){
    pdo_execute("UPDATE khuyen_mai SET ten_khuyen_mai=?, ma_khuyen_mai=?, loai_giam=?, phan_tram_giam=?, gia_tri_giam=?, ngay_bat_dau=?, ngay_ket_thuc=?, trang_thai=?, dieu_kien_ap_dung=?, mo_ta=?, id_rap=? WHERE id=?",
        $ten, strtoupper(trim($ma_code)), $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta, $id_rap, $id);
}

function km_delete($id){ pdo_execute("DELETE FROM khuyen_mai WHERE id=?", $id); }

function km_toggle($id){ $r = km_one($id); if(!$r) return; $new = (int)($r['trang_thai']??1)?0:1; pdo_execute("UPDATE khuyen_mai SET trang_thai=? WHERE id=?", $new, $id); }

// Tìm mã khuyến mãi theo mã code (ma_khuyen_mai)
function km_find_by_code($code){
    $sql = "SELECT * FROM khuyen_mai 
            WHERE ma_khuyen_mai = ? 
            AND trang_thai = 1 
            AND ngay_bat_dau <= NOW() 
            AND ngay_ket_thuc >= NOW() 
            LIMIT 1";
    return pdo_query_one($sql, trim(strtoupper($code)));
}

// Tính toán giảm giá từ mã khuyến mãi
function km_calculate_discount($km_row, $original_price){
    if (!$km_row) return 0;
    
    $loai_giam = $km_row['loai_giam'] ?? 'phan_tram';
    
    if ($loai_giam === 'phan_tram') {
        $phan_tram = (int)($km_row['phan_tram_giam'] ?? 0);
        return (int)($original_price * $phan_tram / 100);
    } else {
        // Giảm theo tiền mặt
        return (int)($km_row['gia_tri_giam'] ?? 0);
    }
}

// Lấy danh sách mã khuyến mãi đang hoạt động (cho nhân viên tham khảo)
function km_active_list(){
    $sql = "SELECT id, ten_khuyen_mai, mo_ta, loai_giam, phan_tram_giam, gia_tri_giam, ngay_ket_thuc
            FROM khuyen_mai 
            WHERE trang_thai = 1 
            AND ngay_bat_dau <= NOW() 
            AND ngay_ket_thuc >= NOW() 
            ORDER BY ngay_ket_thuc ASC";
    return pdo_query($sql);
}

// Lấy danh sách mã khuyến mãi đang hoạt động theo rạp (cho dropdown)
function km_active_list_by_rap($id_rap){
    $sql = "SELECT id, ten_khuyen_mai, ma_khuyen_mai, mo_ta, loai_giam, phan_tram_giam, gia_tri_giam, ngay_ket_thuc
            FROM khuyen_mai 
            WHERE (id_rap = ? OR id_rap IS NULL)
            AND trang_thai = 1 
            AND ngay_bat_dau <= NOW() 
            AND ngay_ket_thuc >= NOW() 
            ORDER BY ngay_ket_thuc ASC";
    return pdo_query($sql, $id_rap);
}
