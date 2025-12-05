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
    if (!$seats) {
        // Cập nhật số ghế = 0 nếu không có ghế nào
        pdo_execute("UPDATE phongchieu SET so_ghe = 0 WHERE id = ?", $id_phong);
        return; 
    }
    
    $sql = "INSERT INTO phong_ghe(id_phong,row_label,seat_number,code,tier,active) VALUES (?,?,?,?,?,?)";
    $active_seats_count = 0;
    
    foreach ($seats as $s){
        $row = $s['row_label'] ?? $s['row'] ?? '';
        $num = (int)($s['seat_number'] ?? $s['col'] ?? 0);
        $code = $s['code'] ?? ($row.$num);
        $tier = in_array(($s['tier'] ?? ''), ['cheap','middle','expensive'], true) ? $s['tier'] : 'cheap';
        $active = isset($s['active']) ? (int)$s['active'] : 1;
        pdo_execute($sql, $id_phong, $row, $num, $code, $tier, $active);
        
        // Đếm ghế hoạt động
        if ($active) $active_seats_count++;
    }
    
    // Cập nhật số ghế hoạt động vào bảng phongchieu
    pdo_execute("UPDATE phongchieu SET so_ghe = ? WHERE id = ?", $active_seats_count, $id_phong);
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

// Tạo sơ đồ ghế theo template
function pg_generate_by_template($id_phong, $template = 'medium', $custom_rows = null, $custom_cols = null){
    $config = [
        'small' => ['rows' => 8, 'cols' => 12, 'aisles' => [4, 9]],
        'medium' => ['rows' => 12, 'cols' => 18, 'aisles' => [5, 14]],
        'large' => ['rows' => 15, 'cols' => 24, 'aisles' => [7, 18]],
        'vip' => ['rows' => 10, 'cols' => 14, 'aisles' => [4, 11]],
        'custom' => ['rows' => $custom_rows ?? 12, 'cols' => $custom_cols ?? 18, 'aisles' => []]
    ];
    
    $cfg = $config[$template] ?? $config['medium'];
    $rows_count = $cfg['rows'];
    $cols_count = $cfg['cols'];
    $aisles = $cfg['aisles'];
    
    $rows = [];
    for ($i = 0; $i < $rows_count; $i++) { 
        $rows[] = chr(ord('A') + $i); 
    }
    
    $out = [];
    foreach ($rows as $idx => $r) {
        for ($c = 1; $c <= $cols_count; $c++) {
            // Xác định tier dựa trên template
            $tier = 'cheap';
            $active = 1;
            
            // Tắt ghế ở lối đi
            if (in_array($c, $aisles)) {
                $active = 0;
            }
            
            // Phân bổ tier theo template
            if ($template === 'small') {
                if ($idx >= 4) $tier = 'middle';
                if ($idx >= 6 && $c >= 4 && $c <= 9) $tier = 'expensive';
            } elseif ($template === 'medium') {
                if ($idx >= 6) $tier = 'middle';
                if ($idx >= 9 && $c >= 6 && $c <= 13) $tier = 'expensive';
            } elseif ($template === 'large') {
                if ($idx >= 8) $tier = 'middle';
                if ($idx >= 12 && $c >= 8 && $c <= 17) $tier = 'expensive';
            } elseif ($template === 'vip') {
                if ($idx >= 3) $tier = 'expensive';
                if ($idx < 3) $tier = 'middle';
            } elseif ($template === 'custom') {
                // Phân bổ tự động: 50% cheap, 30% middle, 20% expensive
                $third = floor($rows_count / 3);
                if ($idx >= $rows_count - $third) {
                    $tier = 'expensive';
                } elseif ($idx >= $third) {
                    $tier = 'middle';
                }
            }
            
            $out[] = [
                'row_label' => $r,
                'seat_number' => $c,
                'code' => $r . $c,
                'tier' => $tier,
                'active' => $active
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

