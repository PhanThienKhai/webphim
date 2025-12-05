<?php
function llv_list_by_rap($id_rap, $from = null, $to = null, $id_nv = null){
    $sql = "SELECT llv.*, tk.name as ten_nv
            FROM lich_lam_viec llv
            JOIN taikhoan tk ON tk.id = llv.id_nhan_vien
            WHERE llv.id_rap = ?";
    $args = [$id_rap];
    if ($id_nv) { $sql .= " AND llv.id_nhan_vien = ?"; $args[] = $id_nv; }
    if ($from) { $sql .= " AND llv.ngay >= ?"; $args[] = $from; }
    if ($to)   { $sql .= " AND llv.ngay <= ?"; $args[] = $to; }
    $sql .= " ORDER BY llv.ngay DESC, llv.gio_bat_dau ASC";
    return pdo_query($sql, ...$args);
}

function llv_list_by_user($id_user){
    $sql = "SELECT llv.*, rc.ten_rap
            FROM lich_lam_viec llv
            JOIN rap_chieu rc ON rc.id = llv.id_rap
            WHERE llv.id_nhan_vien = ?
            ORDER BY llv.ngay DESC, llv.gio_bat_dau ASC";
    return pdo_query($sql, $id_user);
}

function llv_list_by_user_month($id_user, $ym){
    $sql = "SELECT llv.*, rc.ten_rap
            FROM lich_lam_viec llv
            JOIN rap_chieu rc ON rc.id = llv.id_rap
            WHERE llv.id_nhan_vien = ? AND DATE_FORMAT(llv.ngay, '%Y-%m') = ?
            ORDER BY llv.ngay ASC, llv.gio_bat_dau ASC";
    return pdo_query($sql, $id_user, $ym);
}

function llv_insert($id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc, $ca_lam = null, $ghi_chu = null){
    $sql = "INSERT INTO lich_lam_viec(id_nhan_vien,id_rap,ngay,gio_bat_dau,gio_ket_thuc,ca_lam,ghi_chu)
            VALUES(?,?,?,?,?,?,?)";
    pdo_execute($sql, $id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc, $ca_lam, $ghi_chu);
}

function llv_delete($id){
    pdo_execute("DELETE FROM lich_lam_viec WHERE id = ?", $id);
}

function llv_update($id, $ngay, $gio_bat_dau, $gio_ket_thuc, $ca_lam = null, $ghi_chu = null){
    $sql = "UPDATE lich_lam_viec SET ngay=?, gio_bat_dau=?, gio_ket_thuc=?, ca_lam=?, ghi_chu=? WHERE id=?";
    pdo_execute($sql, $ngay, $gio_bat_dau, $gio_ket_thuc, $ca_lam, $ghi_chu, $id);
}

function llv_exists($id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc){
    $row = pdo_query_one(
        "SELECT id FROM lich_lam_viec WHERE id_nhan_vien=? AND id_rap=? AND ngay=? AND gio_bat_dau=? AND gio_ket_thuc=?",
        $id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc
    );
    return !empty($row);
}
