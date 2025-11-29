<?php

function dh_tao($id_ve, $id_rap, $loai, $ly_do, $trang_thai_moi=null){
    pdo_execute("INSERT INTO yeu_cau_ve(id_ve,id_rap,loai,ly_do,trang_thai,trang_thai_moi) VALUES(?,?,?,?, 'cho_duyet', ?)",
        $id_ve, $id_rap, $loai, $ly_do, $trang_thai_moi);
}

function dh_list_by_rap($id_rap, $filter='all'){
    $sql = "SELECT 
                yc.id,
                yc.id_ve,
                yc.loai,
                yc.ly_do,
                yc.trang_thai,
                yc.ngay_tao,
                v.ghe,
                v.price,
                p.tieu_de,
                lc.ngay_chieu,
                kgc.thoi_gian_chieu,
                tk.name as ten_khach,
                tk.email,
                tk.phone
            FROM yeu_cau_ve yc
            LEFT JOIN ve v ON yc.id_ve = v.id
            LEFT JOIN phim p ON v.id_phim = p.id
            LEFT JOIN lichchieu lc ON p.id = lc.id_phim
            LEFT JOIN khung_gio_chieu kgc ON v.id_thoi_gian_chieu = kgc.id
            LEFT JOIN taikhoan tk ON v.id_tk = tk.id
            WHERE yc.id_rap = ?";
    
    if ($filter !== 'all') {
        $sql .= " AND yc.trang_thai = ?";
        return pdo_query($sql . " ORDER BY yc.ngay_tao DESC", $id_rap, $filter);
    }
    
    return pdo_query($sql . " ORDER BY yc.trang_thai DESC, yc.ngay_tao DESC", $id_rap);
}

function dh_update_trang_thai($id, $trang_thai){
    pdo_execute("UPDATE yeu_cau_ve SET trang_thai = ? WHERE id = ?", $trang_thai, $id);
}

function dh_one($id){ 
    return pdo_query_one("SELECT * FROM yeu_cau_ve WHERE id=?", $id); 
}

function dh_count_by_status($id_rap){
    $result = pdo_query_one(
        "SELECT 
            COUNT(*) as tong,
            SUM(CASE WHEN trang_thai='cho_duyet' THEN 1 ELSE 0 END) as cho_duyet,
            SUM(CASE WHEN trang_thai='da_duyet' THEN 1 ELSE 0 END) as da_duyet,
            SUM(CASE WHEN trang_thai='tu_choi' THEN 1 ELSE 0 END) as tu_choi
        FROM yeu_cau_ve 
        WHERE id_rap = ?", 
        $id_rap
    );
    return $result;
}

