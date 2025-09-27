<?php
function loadall_rap(){
    $sql = "SELECT * FROM rap_chieu ORDER BY id ASC";
    return pdo_query($sql);
}

// Trả về các rạp có ít nhất 1 lịch chiếu từ hôm nay trở về sau
function load_active_raps(){
    $today = date('Y-m-d');
    $sql = "SELECT DISTINCT r.*
            FROM rap_chieu r
            JOIN lichchieu lc ON lc.id_rap = r.id
            WHERE DATE(lc.ngay_chieu) >= ?
            ORDER BY r.id";
    return pdo_query($sql, $today);
}
