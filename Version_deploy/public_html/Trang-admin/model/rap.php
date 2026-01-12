<?php

function rap_all(){
    $sql = "SELECT * FROM rap_chieu ORDER BY id ASC";
    return pdo_query($sql);
}

function rap_one($id){
    $sql = "SELECT * FROM rap_chieu WHERE id = ?";
    return pdo_query_one($sql, $id);
}

function rap_insert($ten_rap, $dia_chi, $so_dien_thoai, $email, $mo_ta = null, $logo = null, $trang_thai = 1){
    $sql = "INSERT INTO rap_chieu(ten_rap, dia_chi, so_dien_thoai, email, trang_thai, mo_ta, logo)
            VALUES(?, ?, ?, ?, ?, ?, ?)";
    pdo_execute($sql, $ten_rap, $dia_chi, $so_dien_thoai, $email, $trang_thai, $mo_ta, $logo);
}

function rap_update($id, $ten_rap, $dia_chi, $so_dien_thoai, $email, $mo_ta = null, $logo = null, $trang_thai = 1){
    $sql = "UPDATE rap_chieu 
            SET ten_rap = ?, dia_chi = ?, so_dien_thoai = ?, email = ?, trang_thai = ?, mo_ta = ?, logo = ?
            WHERE id = ?";
    pdo_execute($sql, $ten_rap, $dia_chi, $so_dien_thoai, $email, $trang_thai, $mo_ta, $logo, $id);
}

function rap_delete($id){
    $sql = "DELETE FROM rap_chieu WHERE id = ?";
    pdo_execute($sql, $id);
}
