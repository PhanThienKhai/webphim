<?php
function loadall_khunggiochieu() {
    // Truy vấn SQL join 3 bảng
    $sql = "SELECT khung_gio_chieu.id, khung_gio_chieu.thoi_gian_chieu, phim.tieu_de, phongchieu.name,lichchieu.ngay_chieu
            FROM lichchieu
            LEFT JOIN khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
            LEFT JOIN phim ON phim.id = lichchieu.id_phim
            LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
            WHERE 1
            ORDER BY khung_gio_chieu.id DESC";

    $re = pdo_query($sql);
    return $re;
}
function khunggio_by_lich($id_lich){
    return pdo_query("SELECT * FROM khung_gio_chieu WHERE id_lich_chieu = ? ORDER BY thoi_gian_chieu", $id_lich);
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
