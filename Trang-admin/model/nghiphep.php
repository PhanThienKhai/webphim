<?php
function np_list_by_rap($id_rap){
    $sql = "SELECT dnp.*, tk.name as ten_nv
            FROM don_nghi_phep dnp
            JOIN taikhoan tk ON tk.id = dnp.id_nhan_vien
            WHERE dnp.id_rap = ?
            ORDER BY dnp.ngay_tao DESC";
    return pdo_query($sql, $id_rap);
}

function np_list_by_user($id_user){
    $sql = "SELECT dnp.*, rc.ten_rap
            FROM don_nghi_phep dnp
            JOIN rap_chieu rc ON rc.id = dnp.id_rap
            WHERE dnp.id_nhan_vien = ?
            ORDER BY dnp.ngay_tao DESC";
    return pdo_query($sql, $id_user);
}

function np_insert($id_nv, $id_rap, $tu_ngay, $den_ngay, $ly_do){
    $sql = "INSERT INTO don_nghi_phep(id_nhan_vien,id_rap,tu_ngay,den_ngay,ly_do) VALUES(?,?,?,?,?)";
    pdo_execute($sql, $id_nv, $id_rap, $tu_ngay, $den_ngay, $ly_do);
}

function np_update_trang_thai($id, $trang_thai){
    $sql = "UPDATE don_nghi_phep SET trang_thai = ? WHERE id = ?";
    pdo_execute($sql, $trang_thai, $id);
}

