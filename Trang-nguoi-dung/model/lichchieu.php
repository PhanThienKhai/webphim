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
    ORDER BY l.ngay_chieu ASC";
    return pdo_query($sql, $id_phim, $id_rap);
}
