<?php

function dh_ensure_schema(){
    $sql = "CREATE TABLE IF NOT EXISTS yeu_cau_ve (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_ve INT NOT NULL,
                id_rap INT NOT NULL,
                loai ENUM('doi','hoan') NOT NULL,
                ly_do TEXT,
                trang_thai ENUM('cho_duyet','da_duyet','tu_choi') NOT NULL DEFAULT 'cho_duyet',
                trang_thai_moi INT DEFAULT NULL,
                ngay_tao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    pdo_execute($sql);
}

function dh_tao($id_ve, $id_rap, $loai, $ly_do, $trang_thai_moi=null){
    dh_ensure_schema();
    pdo_execute("INSERT INTO yeu_cau_ve(id_ve,id_rap,loai,ly_do,trang_thai,trang_thai_moi) VALUES(?,?,?,?, 'cho_duyet', ?)",
        $id_ve, $id_rap, $loai, $ly_do, $trang_thai_moi);
}

function dh_list_by_rap($id_rap){
    dh_ensure_schema();
    return pdo_query("SELECT * FROM yeu_cau_ve WHERE id_rap = ? ORDER BY id DESC", $id_rap);
}

function dh_update_trang_thai($id, $trang_thai){
    dh_ensure_schema();
    pdo_execute("UPDATE yeu_cau_ve SET trang_thai = ? WHERE id = ?", $trang_thai, $id);
}

function dh_one($id){ return pdo_query_one("SELECT * FROM yeu_cau_ve WHERE id=?", $id); }

