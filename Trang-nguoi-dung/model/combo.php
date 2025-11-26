<?php
/**
 * Model quản lý Combo Đồ Ăn
 * File: Trang-nguoi-dung/model/combo.php
 */

// Lấy combo của rạp cụ thể (bao gồm cả combo chung cho tất cả rạp)
function get_combos_for_rap($id_rap) {
    $sql = "SELECT 
                c.*,
                CASE 
                    WHEN c.id_rap IS NULL THEN '(Áp dụng tất cả rạp)'
                    ELSE ''
                END as note
            FROM combo_do_an c
            WHERE (c.id_rap = ? OR c.id_rap IS NULL)
              AND c.trang_thai = 1
            ORDER BY 
                CASE WHEN c.id_rap = ? THEN 0 ELSE 1 END,
                c.gia";
    return pdo_query($sql, $id_rap, $id_rap);
}

// Lấy tất cả combo (dùng cho admin)
function loadall_combo() {
    $sql = "SELECT 
                c.*, 
                CASE 
                    WHEN c.id_rap IS NULL THEN 'Tất cả rạp'
                    ELSE r.ten_rap 
                END as ten_rap_ap_dung
            FROM combo_do_an c
            LEFT JOIN rap_chieu r ON c.id_rap = r.id
            WHERE c.trang_thai = 1
            ORDER BY c.id_rap, c.gia";
    return pdo_query($sql);
}

// Lấy thông tin 1 combo
function loadone_combo($id) {
    $sql = "SELECT 
                c.*,
                CASE 
                    WHEN c.id_rap IS NULL THEN 'Tất cả rạp'
                    ELSE r.ten_rap 
                END as ten_rap_ap_dung
            FROM combo_do_an c
            LEFT JOIN rap_chieu r ON c.id_rap = r.id
            WHERE c.id = ?";
    return pdo_query_one($sql, $id);
}

// Kiểm tra combo có tồn tại tại rạp không
function check_combo_for_rap($id_combo, $id_rap) {
    $sql = "SELECT COUNT(*) as count
            FROM combo_do_an
            WHERE id = ?
              AND (id_rap = ? OR id_rap IS NULL)
              AND trang_thai = 1";
    $result = pdo_query_one($sql, $id_combo, $id_rap);
    return $result['count'] > 0;
}
?>
