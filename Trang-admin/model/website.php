<?php
function website_get(){
    return pdo_query_one("SELECT * FROM thong_tin_website WHERE id = 1");
}

function website_update($ten_website, $dia_chi, $so_dien_thoai, $email, $mo_ta, $facebook, $instagram, $youtube, $logo = null, $video_banner = null){
    $params = [$ten_website, $dia_chi, $so_dien_thoai, $email, $mo_ta, $facebook, $instagram, $youtube];
    
    // Xây dựng SQL động
    $set_clauses = [
        "ten_website=?",
        "dia_chi=?",
        "so_dien_thoai=?",
        "email=?",
        "mo_ta=?",
        "facebook=?",
        "instagram=?",
        "youtube=?",
        "ngay_cap_nhat=NOW()"
    ];
    
    if ($logo !== null) {
        $set_clauses[] = "logo=?";
        $params[] = $logo;
    }
    
    if ($video_banner !== null) {
        $set_clauses[] = "video_banner=?";
        $params[] = $video_banner;
    }
    
    $sql = "UPDATE thong_tin_website SET " . implode(", ", $set_clauses) . " WHERE id=1";
    pdo_execute($sql, ...$params);
}

// Hàm để lấy video banner
function website_get_banner_video(){
    $config = pdo_query_one("SELECT video_banner FROM thong_tin_website WHERE id = 1");
    return $config['video_banner'] ?? 'video/OFFICIAL TRAILER.mp4';
}

?>
