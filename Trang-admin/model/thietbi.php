<?php
function tb_list_by_phong($id_phong){
    return pdo_query("SELECT * FROM thiet_bi_phong WHERE id_phong = ? ORDER BY id DESC", $id_phong);
}

function tb_insert($id_phong, $ten, $so_luong = 1, $tinh_trang = 'tot', $ghi_chu = null){
    $sql = "INSERT INTO thiet_bi_phong(id_phong,ten_thiet_bi,so_luong,tinh_trang,ghi_chu) VALUES(?,?,?,?,?)";
    pdo_execute($sql, $id_phong, $ten, $so_luong, $tinh_trang, $ghi_chu);
}

function tb_update($id, $ten, $so_luong, $tinh_trang, $ghi_chu){
    $sql = "UPDATE thiet_bi_phong SET ten_thiet_bi=?, so_luong=?, tinh_trang=?, ghi_chu=? WHERE id=?";
    pdo_execute($sql, $ten, $so_luong, $tinh_trang, $ghi_chu, $id);
}

function tb_delete($id){
    pdo_execute("DELETE FROM thiet_bi_phong WHERE id = ?", $id);
}

