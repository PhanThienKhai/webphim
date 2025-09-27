<?php

function combo_ensure_schema(){
    // Table is expected to exist; keep as safety net for dev envs
    $sql = "CREATE TABLE IF NOT EXISTS combo_do_an (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ten_combo VARCHAR(255) NOT NULL,
                gia INT NOT NULL DEFAULT 0,
                hinh_anh VARCHAR(255) DEFAULT NULL,
                mo_ta TEXT DEFAULT NULL,
                trang_thai TINYINT(1) NOT NULL DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    pdo_execute($sql);
}

function combo_all(){
    combo_ensure_schema();
    return pdo_query("SELECT * FROM combo_do_an ORDER BY id DESC");
}

function combo_one($id){
    combo_ensure_schema();
    return pdo_query_one("SELECT * FROM combo_do_an WHERE id = ?", $id);
}

function combo_insert($ten, $gia, $hinh, $mo_ta, $trang_thai){
    combo_ensure_schema();
    pdo_execute("INSERT INTO combo_do_an(ten_combo, gia, hinh_anh, mo_ta, trang_thai) VALUES(?,?,?,?,?)",
        $ten, $gia, $hinh, $mo_ta, $trang_thai);
}

function combo_update($id, $ten, $gia, $hinh, $mo_ta, $trang_thai){
    combo_ensure_schema();
    if ($hinh !== null) {
        pdo_execute("UPDATE combo_do_an SET ten_combo=?, gia=?, hinh_anh=?, mo_ta=?, trang_thai=? WHERE id=?",
            $ten, $gia, $hinh, $mo_ta, $trang_thai, $id);
    } else {
        pdo_execute("UPDATE combo_do_an SET ten_combo=?, gia=?, mo_ta=?, trang_thai=? WHERE id=?",
            $ten, $gia, $mo_ta, $trang_thai, $id);
    }
}

function combo_delete($id){
    combo_ensure_schema();
    pdo_execute("DELETE FROM combo_do_an WHERE id = ?", $id);
}

function combo_toggle($id){
    $row = combo_one($id);
    if (!$row) return;
    $new = (int)($row['trang_thai']??1) ? 0 : 1;
    pdo_execute("UPDATE combo_do_an SET trang_thai=? WHERE id=?", $new, $id);
}

