<?php
function loadall_khunggiochieu($id_rap = null) {
    // Truy vấn SQL join 3 bảng với tùy chọn lọc theo rạp
    $where_clause = $id_rap ? "AND lichchieu.id_rap = ?" : "";
    $sql = "SELECT khung_gio_chieu.id, khung_gio_chieu.thoi_gian_chieu, phim.tieu_de, phongchieu.name,lichchieu.ngay_chieu, lichchieu.id_rap
            FROM lichchieu
            LEFT JOIN khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
            LEFT JOIN phim ON phim.id = lichchieu.id_phim
            LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
            WHERE khung_gio_chieu.id IS NOT NULL $where_clause
            ORDER BY khung_gio_chieu.id DESC";

    return $id_rap ? pdo_query($sql, $id_rap) : pdo_query($sql);
}
function khunggio_by_lich($id_lich){
    return pdo_query("SELECT * FROM khung_gio_chieu WHERE id_lich_chieu = ? ORDER BY thoi_gian_chieu", $id_lich);
}

// 🎬 Hàm mới: Lấy khung giờ chiếu theo rạp
function khunggio_by_rap($id_rap) {
    $sql = "SELECT kgc.*, lc.ngay_chieu, lc.id_phim, p.tieu_de as ten_phim, pc.name as ten_phong
            FROM khung_gio_chieu kgc
            JOIN lichchieu lc ON lc.id = kgc.id_lich_chieu
            JOIN phim p ON p.id = lc.id_phim  
            JOIN phongchieu pc ON pc.id = kgc.id_phong
            WHERE lc.id_rap = ?
            ORDER BY lc.ngay_chieu DESC, kgc.thoi_gian_chieu ASC";
    return pdo_query($sql, $id_rap);
}
function loadone_khung_gio_chieu($id)
{
    $sql = "select * from khung_gio_chieu where id =" . $id;
    $re = pdo_query_one($sql);
    return $re;
}

function kgc_conflict_exists($id_lc, $id_phong, $thoi_gian_chieu, $exclude_id = null){
    // Get show date for this lich chieu
    $lc = pdo_query_one("SELECT ngay_chieu FROM lichchieu WHERE id = ?", $id_lc);
    if (!$lc) return false;
    $ngay = $lc['ngay_chieu'];
    $sql = "SELECT kg.id FROM khung_gio_chieu kg
            INNER JOIN lichchieu l ON l.id = kg.id_lich_chieu
            WHERE kg.id_phong = ? AND l.ngay_chieu = ? AND kg.thoi_gian_chieu = ?";
    $params = [$id_phong, $ngay, $thoi_gian_chieu];
    if ($exclude_id) { $sql .= " AND kg.id <> ?"; $params[] = $exclude_id; }
    $row = pdo_query_one($sql, ...$params);
    return !empty($row);
}

function them_kgc($id_lc, $id_phong, $thoi_gian_chieu){
    $sql = "INSERT INTO `khung_gio_chieu` (`id_lich_chieu`,`id_phong`,`thoi_gian_chieu`) VALUES ('$id_lc','$id_phong','$thoi_gian_chieu')";
    pdo_execute($sql);
}

function sua_kgc($id,$id_lc,$id_phong,$thoi_gian_chieu)
{
    $sql = "UPDATE khung_gio_chieu SET `id_lich_chieu`='{$id_lc}',`id_phong`='{$id_phong}',`thoi_gian_chieu`='{$thoi_gian_chieu}'WHERE `khung_gio_chieu`.`id`=" . $id;

    pdo_execute($sql);
}
function xoa_kgc($id)
{
    $sql = "DELETE FROM khung_gio_chieu WHERE id=" . $id;
    pdo_execute($sql);
}
