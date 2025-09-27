<?php

// Ensure table exists for seat map per room
function pg_ensure_schema(){
    $sql = "CREATE TABLE IF NOT EXISTS phong_ghe (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_phong INT NOT NULL,
                row_label VARCHAR(4) NOT NULL,
                seat_number INT NOT NULL,
                code VARCHAR(16) NOT NULL,
                tier ENUM('cheap','middle','expensive') NOT NULL DEFAULT 'cheap',
                active TINYINT(1) NOT NULL DEFAULT 1,
                UNIQUE KEY uniq_room_code (id_phong, code),
                KEY idx_room (id_phong)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    pdo_execute($sql);
}

function pg_list($id_phong){
    pg_ensure_schema();
    return pdo_query("SELECT row_label, seat_number, code, tier, active FROM phong_ghe WHERE id_phong = ? ORDER BY row_label, seat_number", $id_phong);
}

function pg_replace_map($id_phong, $seats){
    pg_ensure_schema();
    // Replace all seats for room
    pdo_execute("DELETE FROM phong_ghe WHERE id_phong = ?", $id_phong);
    if (!$seats) return;
    $sql = "INSERT INTO phong_ghe(id_phong,row_label,seat_number,code,tier,active) VALUES (?,?,?,?,?,?)";
    foreach ($seats as $s){
        $row = $s['row_label'] ?? $s['row'] ?? '';
        $num = (int)($s['seat_number'] ?? $s['col'] ?? 0);
        $code = $s['code'] ?? ($row.$num);
        $tier = in_array(($s['tier'] ?? ''), ['cheap','middle','expensive'], true) ? $s['tier'] : 'cheap';
        $active = isset($s['active']) ? (int)$s['active'] : 1;
        pdo_execute($sql, $id_phong, $row, $num, $code, $tier, $active);
    }
}

function pg_generate_default($id_phong, $rows_count = 12, $cols_count = 18){
    // Rows A.. with given count
    $rows = [];
    for ($i=0; $i<$rows_count; $i++) { $rows[] = chr(ord('A')+$i); }
    $cols = range(1, $cols_count);
    $out = [];
    foreach ($rows as $r){
        foreach ($cols as $c){
            $tier = 'cheap';
            if (in_array($r, ['F','G','H','I'], true)) $tier = 'middle';
            if (in_array($r, ['J','K','L'], true) && $c>=5 && $c<=12) $tier = 'expensive';
            $out[] = [
                'row_label' => $r,
                'seat_number' => $c,
                'code' => $r.$c,
                'tier' => $tier,
                'active' => 1
            ];
        }
    }
    pg_replace_map($id_phong, $out);
}

function pg_rows_cols_summary($id_phong){
    $rows = pdo_query("SELECT DISTINCT row_label FROM phong_ghe WHERE id_phong = ? ORDER BY row_label", $id_phong);
    $cols = pdo_query("SELECT MAX(seat_number) AS max_col FROM phong_ghe WHERE id_phong = ?", $id_phong);
    return [
        'rows' => array_map(function($r){ return $r['row_label']; }, $rows),
        'max_col' => (int)($cols[0]['max_col'] ?? 0)
    ];
}

function pg_list_for_time($id_tg){
    // Fetch id_phong from khung_gio_chieu then list
    $kg = pdo_query_one("SELECT id_phong FROM khung_gio_chieu WHERE id = ?", $id_tg);
    if (!$kg) return [];
    return pg_list((int)$kg['id_phong']);
}

