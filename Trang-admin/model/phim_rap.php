<?php
function pr_list_by_rap($id_rap){
    $sql = "SELECT p.id, p.tieu_de, (pr.id IS NOT NULL) as duoc_phan
            FROM phim p
            LEFT JOIN phim_rap pr ON pr.id_phim = p.id AND pr.id_rap = ?
            ORDER BY p.tieu_de";
    return pdo_query($sql, $id_rap);
}

function pr_assign_many($id_rap, $ids_phim){
    foreach ($ids_phim as $id_phim){
        pdo_execute("INSERT IGNORE INTO phim_rap(id_phim,id_rap) VALUES(?,?)", $id_phim, $id_rap);
    }
}

function pr_clear_and_assign($id_rap, $ids_phim){
    pdo_execute("DELETE FROM phim_rap WHERE id_rap = ?", $id_rap);
    if (!empty($ids_phim)) pr_assign_many($id_rap, $ids_phim);
}

function pr_check($id_phim, $id_rap){
    $row = pdo_query_one("SELECT id FROM phim_rap WHERE id_phim = ? AND id_rap = ?", $id_phim, $id_rap);
    return !empty($row);
}

// Gán phim cho nhiều rạp (tùy chọn thay thế toàn bộ hay bổ sung)
function pr_assign_to_raps($ids_rap, $ids_phim, $replace = false){
    if (empty($ids_rap) || empty($ids_phim)) return;
    foreach ($ids_rap as $id_rap){
        if ($replace) {
            pdo_execute("DELETE FROM phim_rap WHERE id_rap = ?", $id_rap);
        }
        pr_assign_many($id_rap, $ids_phim);
    }
}

function pr_remove_film_all($id_phim){
    pdo_execute("DELETE FROM phim_rap WHERE id_phim = ?", $id_phim);
}
