<?php
function tintuc_all() {
    $sql = "SELECT * FROM tintuc ORDER BY ngay_dang DESC";
    return pdo_query($sql);
}

function tintuc_one($id) {
    $sql = "SELECT * FROM tintuc WHERE id = ?";
    return pdo_query_one($sql, $id);
}

function tintuc_insert($tieu_de, $tom_tat, $noi_dung, $hinh_anh) {
    $sql = "INSERT INTO tintuc (tieu_de, tom_tat, noi_dung, hinh_anh) VALUES (?, ?, ?, ?)";
    pdo_execute($sql, $tieu_de, $tom_tat, $noi_dung, $hinh_anh);
}

function tintuc_update($id, $tieu_de, $tom_tat, $noi_dung, $hinh_anh) {
    $sql = "UPDATE tintuc SET tieu_de=?, tom_tat=?, noi_dung=?, hinh_anh=? WHERE id=?";
    pdo_execute($sql, $tieu_de, $tom_tat, $noi_dung, $hinh_anh, $id);
}

function tintuc_delete($id) {
    $sql = "DELETE FROM tintuc WHERE id=?";
    pdo_execute($sql, $id);
} 