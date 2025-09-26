<?php

// Lớp tương thích với schema khuyen_mai trong DB hiện tại
// Bảng cột tham chiếu: id, ten_khuyen_mai, mo_ta, phan_tram_giam, gia_tri_giam, loai_giam (phan_tram|tien_mat),
// ngay_bat_dau, ngay_ket_thuc, dieu_kien_ap_dung, trang_thai, ngay_tao

function km_all($unused = null){
    return pdo_query("SELECT * FROM khuyen_mai ORDER BY id DESC");
}

function km_one($id){ return pdo_query_one("SELECT * FROM khuyen_mai WHERE id=?", $id); }

function km_insert($ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta){
    pdo_execute("INSERT INTO khuyen_mai(ten_khuyen_mai, loai_giam, phan_tram_giam, gia_tri_giam, ngay_bat_dau, ngay_ket_thuc, trang_thai, dieu_kien_ap_dung, mo_ta)
                 VALUES(?,?,?,?,?,?,?,?,?)",
        $ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta);
}

function km_update($id, $ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta){
    pdo_execute("UPDATE khuyen_mai SET ten_khuyen_mai=?, loai_giam=?, phan_tram_giam=?, gia_tri_giam=?, ngay_bat_dau=?, ngay_ket_thuc=?, trang_thai=?, dieu_kien_ap_dung=?, mo_ta=? WHERE id=?",
        $ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau, $ket_thuc, $trang_thai, $dieu_kien, $mo_ta, $id);
}

function km_delete($id){ pdo_execute("DELETE FROM khuyen_mai WHERE id=?", $id); }

function km_toggle($id){ $r = km_one($id); if(!$r) return; $new = (int)($r['trang_thai']??1)?0:1; pdo_execute("UPDATE khuyen_mai SET trang_thai=? WHERE id=?", $new, $id); }
