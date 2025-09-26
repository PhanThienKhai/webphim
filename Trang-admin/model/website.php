<?php
function website_get(){
    return pdo_query_one("SELECT * FROM thong_tin_website WHERE id = 1");
}

function website_update($ten_website, $dia_chi, $so_dien_thoai, $email, $mo_ta, $facebook, $instagram, $youtube, $logo = null){
    if ($logo !== null) {
        $sql = "UPDATE thong_tin_website SET ten_website=?, dia_chi=?, so_dien_thoai=?, email=?, mo_ta=?, facebook=?, instagram=?, youtube=?, logo=?, ngay_cap_nhat=NOW() WHERE id=1";
        pdo_execute($sql, $ten_website, $dia_chi, $so_dien_thoai, $email, $mo_ta, $facebook, $instagram, $youtube, $logo);
    } else {
        $sql = "UPDATE thong_tin_website SET ten_website=?, dia_chi=?, so_dien_thoai=?, email=?, mo_ta=?, facebook=?, instagram=?, youtube=?, ngay_cap_nhat=NOW() WHERE id=1";
        pdo_execute($sql, $ten_website, $dia_chi, $so_dien_thoai, $email, $mo_ta, $facebook, $instagram, $youtube);
    }
}

