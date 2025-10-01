<?php

function cc_ensure_schema(){
    pdo_execute("CREATE TABLE IF NOT EXISTS cham_cong (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_nv INT NOT NULL,
        id_rap INT NOT NULL,
        ngay DATE NOT NULL,
        gio_vao TIME NOT NULL,
        gio_ra TIME NOT NULL,
        ghi_chu VARCHAR(255) DEFAULT NULL,
        ngay_tao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function cc_insert($id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu=null){
    cc_ensure_schema();
    pdo_execute("INSERT INTO cham_cong(id_nv,id_rap,ngay,gio_vao,gio_ra,ghi_chu) VALUES(?,?,?,?,?,?)",
        $id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu);
}

function cc_delete($id){ cc_ensure_schema(); pdo_execute("DELETE FROM cham_cong WHERE id=?", $id); }

function cc_list_by_rap_month($id_rap, $ym, $id_nv = null){
    cc_ensure_schema();
    if ($id_nv) {
        return pdo_query("SELECT cc.*, tk.name AS ten_nv FROM cham_cong cc JOIN taikhoan tk ON tk.id = cc.id_nv WHERE cc.id_rap = ? AND cc.id_nv = ? AND DATE_FORMAT(cc.ngay,'%Y-%m') = ? ORDER BY cc.ngay DESC, cc.gio_vao",
            $id_rap, $id_nv, $ym);
    }
    return pdo_query("SELECT cc.*, tk.name AS ten_nv FROM cham_cong cc JOIN taikhoan tk ON tk.id = cc.id_nv WHERE cc.id_rap = ? AND DATE_FORMAT(cc.ngay,'%Y-%m') = ? ORDER BY cc.ngay DESC, cc.gio_vao",
        $id_rap, $ym);
}

function cc_sum_hours($id_nv, $id_rap, $ym){
    cc_ensure_schema();
    $rows = pdo_query("SELECT TIMESTAMPDIFF(MINUTE, cc.ngay + INTERVAL TIME_TO_SEC(cc.gio_vao) SECOND, cc.ngay + INTERVAL TIME_TO_SEC(cc.gio_ra) SECOND) AS minutes
                       FROM cham_cong cc WHERE cc.id_nv=? AND cc.id_rap=? AND DATE_FORMAT(cc.ngay,'%Y-%m')=?",
                       $id_nv, $id_rap, $ym);
    $min = 0; foreach ($rows as $r) { $min += max(0, (int)($r['minutes'] ?? 0)); }
    return $min/60.0;
}

function luong_tinh_thang($id_rap, $ym, $rate_per_hour = 30000){
    // Tính lương cơ bản theo giờ * rate; có thể mở rộng multiplier ca đêm/OT sau
    $ds_nv = pdo_query("SELECT id, name FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
    $out = [];
    foreach ($ds_nv as $nv){
        $hours = cc_sum_hours((int)$nv['id'], $id_rap, $ym);
        $salary = (int)round($hours * $rate_per_hour);
        $out[] = ['id_nv' => (int)$nv['id'], 'ten_nv' => $nv['name'], 'so_gio' => $hours, 'luong' => $salary];
    }
    return $out;
}
