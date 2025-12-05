<?php
function loadall_vephim(){
    $sql="SELECT v.id,phim.tieu_de,v.price, v.ngay_dat,v.ghe,v.combo, taikhoan.name, khung_gio_chieu.thoi_gian_chieu ,v.id_hd,v.trang_thai FROM ve v 
    LEFT JOIN taikhoan ON taikhoan.id = v.id_tk 
    LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu 
     LEFT JOIN phim ON phim.id = v.id_phim       
    LEFT JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
    WHERE 1 ORDER BY id DESC;";
    $re=pdo_query($sql);
    return $re;
}
function loadone_vephim($id){
    $sql="SELECT v.id, 
                 phim.tieu_de,
                 lichchieu.ngay_chieu, 
                 v.price, 
                 v.ngay_dat, 
                 v.ghe, 
                 v.combo, 
                 taikhoan.name, 
                 khung_gio_chieu.thoi_gian_chieu, 
                 v.id_hd, 
                 v.trang_thai, 
                 phongchieu.name as tenphong,
                 rap_chieu.ten_rap as tenrap,
                 v.ma_ve,
                 v.check_in_luc,
                 v.check_in_boi
          FROM ve v
          LEFT JOIN taikhoan ON taikhoan.id = v.id_tk
          LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
          LEFT JOIN phim ON phim.id = v.id_phim
          LEFT JOIN lichchieu ON lichchieu.id = v.id_ngay_chieu
          LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
          LEFT JOIN rap_chieu ON rap_chieu.id = v.id_rap
          WHERE v.id = ?";

    $re = pdo_query_one($sql, $id);
    return $re;
}
function update_vephim($id,$trang_thai){
    // Update trạng thái vé
    $sql = "update ve set `trang_thai`='{$trang_thai}' where `ve`.`id`=" . $id;
    pdo_execute($sql);
}

function loadall_vephim1($searchName, $searchTieuDe,$searchid){
    $sql = "SELECT v.id, phim.tieu_de,lichchieu.ngay_chieu , v.price, v.ngay_dat, v.ghe, v.combo, taikhoan.name, khung_gio_chieu.thoi_gian_chieu, v.id_hd, v.trang_thai ,phongchieu.name as tenphong
            FROM ve v 
            LEFT JOIN taikhoan ON taikhoan.id = v.id_tk 
            LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu 
            LEFT JOIN phim ON phim.id = v.id_phim
            LEFT JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
            LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
            WHERE taikhoan.name LIKE '%" . $searchName . "%' AND phim.tieu_de LIKE '%" . $searchTieuDe . "%' and v.id like '%" . $searchid . "%'
            ORDER BY v.id DESC";

    $re = pdo_query($sql);
    return $re;
}

function loadall_vephim1_by_rap($searchName, $searchTieuDe, $searchid, $id_rap){
    $sql = "SELECT v.id, phim.tieu_de, lichchieu.ngay_chieu, v.price, v.ngay_dat, v.ghe, v.combo, taikhoan.name, khung_gio_chieu.thoi_gian_chieu, v.id_hd, v.trang_thai, phongchieu.name as tenphong
            FROM ve v 
            LEFT JOIN taikhoan ON taikhoan.id = v.id_tk 
            LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu 
            LEFT JOIN phim ON phim.id = v.id_phim
            LEFT JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
            LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
            WHERE taikhoan.name LIKE ? AND phim.tieu_de LIKE ? AND v.id LIKE ? AND lichchieu.id_rap = ?
            ORDER BY v.id DESC";
    return pdo_query($sql, "%$searchName%", "%$searchTieuDe%", "%$searchid%", $id_rap);
}


function capnhat_tt_ve(){
    $sql = "UPDATE `ve`
INNER JOIN `lichchieu` ON `ve`.`id_ngay_chieu` = `lichchieu`.`id`
SET `ve`.`trang_thai` = 4
WHERE `lichchieu`.`ngay_chieu` < NOW() AND `ve`.`trang_thai` = 1;
";
    pdo_execute($sql);
}

function ve_find_by_code($ma_ve){
    $sql = "SELECT v.*, phim.tieu_de, khung_gio_chieu.thoi_gian_chieu, lichchieu.ngay_chieu, phongchieu.name as tenphong
            FROM ve v
            JOIN phim ON phim.id = v.id_phim
            JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
            JOIN lichchieu ON lichchieu.id = v.id_ngay_chieu
            JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
            WHERE v.ma_ve = ?";
    $row = pdo_query_one($sql, $ma_ve);
    if (!$row && ctype_digit($ma_ve)) {
        // fallback: cho phép nhập ID vé trực tiếp
        $sql2 = "SELECT v.*, phim.tieu_de, khung_gio_chieu.thoi_gian_chieu, lichchieu.ngay_chieu, phongchieu.name as tenphong
                 FROM ve v
                 JOIN phim ON phim.id = v.id_phim
                 JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
                 JOIN lichchieu ON lichchieu.id = v.id_ngay_chieu
                 JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
                 WHERE v.id = ?";
        $row = pdo_query_one($sql2, (int)$ma_ve);
    }
    return $row;
}

function ve_checkin($id_ve, $id_nv){
    $sql = "UPDATE ve SET check_in_luc = NOW(), check_in_boi = ? WHERE id = ?";
    pdo_execute($sql, $id_nv, $id_ve);
}

function ve_create_admin($id_phim, $id_rap, $id_tg, $id_lc, $id_kh, $ghe_csv, $price, $id_nv, $combo_text = ''){
    $ma = substr(md5(uniqid((string)$id_kh, true)), 0, 12);
    $sql = "INSERT INTO ve(id_phim,id_rap,id_thoi_gian_chieu,id_ngay_chieu,id_tk,ghe,combo,price,id_hd,trang_thai,ngay_dat,ma_ve,tao_boi)
            VALUES(?,?,?,?,?,?,?,?,0,1,NOW(),?,?)";
    
    // Sử dụng kết nối trực tiếp để lấy lastInsertId
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_phim, $id_rap, $id_tg, $id_lc, $id_kh, $ghe_csv, $combo_text, $price, $ma, $id_nv]);
        $ve_id = $conn->lastInsertId();
        return (int)$ve_id;
    } catch(PDOException $e) {
        error_log("ve_create_admin error: " . $e->getMessage());
        throw $e;
    } finally {
        unset($conn);
    }
}

function ve_reserved_seats($id_tg, $id_lc){
    $sql = "SELECT ghe FROM ve WHERE id_thoi_gian_chieu = ? AND id_ngay_chieu = ?";
    $rows = pdo_query($sql, $id_tg, $id_lc);
    $seats = [];
    foreach ($rows as $r) {
        $g = trim($r['ghe'] ?? '');
        if ($g !== '') {
            foreach (explode(',', $g) as $s) { $seats[] = trim($s); }
        }
    }
    return array_values(array_unique($seats));
}

// Thống kê doanh thu theo NGÀY cho 1 nhân viên (theo trường tao_boi)
function ve_stats_by_staff_date_range($id_nv, $id_rap, $from_date, $to_date){
    $sql = "SELECT DATE(ngay_dat) AS ngay, COUNT(*) AS so_ve, COALESCE(SUM(price),0) AS doanh_thu
            FROM ve
            WHERE tao_boi = ? AND id_rap = ? AND trang_thai IN (1,2,4)
              AND DATE(ngay_dat) BETWEEN ? AND ?
            GROUP BY DATE(ngay_dat)
            ORDER BY ngay";
    return pdo_query($sql, $id_nv, $id_rap, $from_date, $to_date);
}

// Tổng hợp doanh thu cho 1 nhân viên trong khoảng ngày
function ve_sum_by_staff($id_nv, $id_rap, $from_date, $to_date){
    $row = pdo_query_one(
        "SELECT COUNT(*) AS so_ve, COALESCE(SUM(price),0) AS doanh_thu
         FROM ve WHERE tao_boi = ? AND id_rap = ? AND trang_thai IN (1,2,4)
           AND DATE(ngay_dat) BETWEEN ? AND ?",
        $id_nv, $id_rap, $from_date, $to_date
    );
    return $row ?: ['so_ve'=>0,'doanh_thu'=>0];
}
