<?php
function load_thongke_doanhthu1()
{   if(isset($_GET['trang'])){
    $trang= $_GET['trang'];
}else{
    $trang = 1 ;
}   
    $bghi=5;
    $vitri = ($trang - 1)* $bghi;
    $sql = "SELECT 
    phim.id as id, 
    phim.tieu_de as tieu_de, 
    loaiphim.name as ten_loaiphim, 
    COUNT(CASE WHEN ve.trang_thai IN (1, 2,4) THEN ve.id END) as so_luong_ve_dat, 
    SUM(CASE WHEN ve.trang_thai IN (1, 2,4) THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
FROM 
    phim
LEFT JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
LEFT JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
LEFT JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
LEFT JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
LEFT JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id
ORDER BY 
    phim.id DESC LIMIT $vitri,$bghi;
";

    $listtk = pdo_query($sql);

    return $listtk;
}

function load_thongke_doanhthu(){

    $sql = "SELECT 
    phim.id as id, 
    phim.tieu_de as tieu_de, 
    loaiphim.name as ten_loaiphim, 
    COUNT(CASE WHEN ve.trang_thai IN (1, 2,4) THEN ve.id END) as so_luong_ve_dat, 
    SUM(CASE WHEN ve.trang_thai IN (1, 2,4) THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
FROM 
    phim
LEFT JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
LEFT JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
LEFT JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
LEFT JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
LEFT JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id
ORDER BY 
    phim.id DESC ;
";

    $listtk = pdo_query($sql);

    return $listtk;
}

function tong(){
    $sql = "SELECT 
    SUM(so_luong_ve_dat) AS tong_so_luong_ve_dat,
    SUM(sum_thanhtien) AS tong_doanh_thu
FROM (
    SELECT 
        phim.id as id, 
        phim.tieu_de as tieu_de, 
        loaiphim.name as ten_loaiphim, 
        COUNT(CASE WHEN ve.trang_thai IN (1, 2,4) THEN ve.id END) as so_luong_ve_dat, 
        SUM(CASE WHEN ve.trang_thai IN (1, 2,4) THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
    FROM 
        phim
    LEFT JOIN 
        loaiphim ON loaiphim.id = phim.id_loai
    LEFT JOIN 
        lichchieu ON phim.id = lichchieu.id_phim
    LEFT JOIN 
        khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
    LEFT JOIN 
        ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
    LEFT JOIN 
        hoa_don ON hoa_don.id = ve.id_hd
    GROUP BY 
        phim.id
) AS phim_stats";

    $all = pdo_query($sql);
    return $all;
}

function doanhthu_theo_rap(){
    $sql = "SELECT r.id, r.ten_rap,
                   COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) AS so_ve,
                   COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN COALESCE(hd.thanh_tien, v.price) END),0) AS doanh_thu
            FROM rap_chieu r
            LEFT JOIN lichchieu lc ON lc.id_rap = r.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            GROUP BY r.id, r.ten_rap
            ORDER BY r.id";
    return pdo_query($sql);
}

// Doanh thu theo rạp trong khoảng thời gian
function doanhthu_theo_rap_khoang($from_date = null, $to_date = null){
    $where = '';
    $params = [];
    if ($from_date && $to_date) {
        $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) BETWEEN ? AND ?';
        $params = [$from_date, $to_date];
    } elseif ($from_date) {
        $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?';
        $params = [$from_date];
    } elseif ($to_date) {
        $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?';
        $params = [$to_date];
    }
    $sql = "SELECT r.id, r.ten_rap,
                   COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) AS so_ve,
                   COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN COALESCE(hd.thanh_tien, v.price) END),0) AS doanh_thu
            FROM rap_chieu r
            LEFT JOIN lichchieu lc ON lc.id_rap = r.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            $where
            GROUP BY r.id, r.ten_rap
            ORDER BY r.id";
    return pdo_query($sql, ...$params);
}

// Doanh thu theo từng phim của 1 rạp trong khoảng thời gian (hoặc toàn bộ nếu không truyền thời gian)
function doanhthu_phim_theo_rap($id_rap, $from_date = null, $to_date = null){
    // Ensure we only return films that actually have schedules in the given rap.
    $params = [$id_rap];
    $dateWhere = '';
    // Use ticket date as fallback when invoice date is missing, and use ticket price when invoice amount is missing
    if ($from_date && $to_date) { $dateWhere = ' AND DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) BETWEEN ? AND ?'; $params[] = $from_date; $params[] = $to_date; }
    elseif ($from_date) { $dateWhere = ' AND DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?'; $params[] = $from_date; }
    elseif ($to_date) { $dateWhere = ' AND DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?'; $params[] = $to_date; }

    // Use INNER JOIN for lichchieu so films without any schedule in the rap are excluded.
    $sql = "SELECT p.id AS id_phim, p.tieu_de,
             COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) AS so_ve,
             COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN COALESCE(hd.thanh_tien, v.price) END),0) AS doanh_thu
         FROM phim p
         INNER JOIN lichchieu lc ON lc.id_phim = p.id AND lc.id_rap = ?
         LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
         LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
         LEFT JOIN hoa_don hd ON hd.id = v.id_hd
         WHERE 1=1 $dateWhere
         GROUP BY p.id, p.tieu_de
         ORDER BY doanh_thu DESC, so_ve DESC";
    return pdo_query($sql, ...$params);
}

// Doanh thu theo từng phim toàn hệ thống trong khoảng thời gian (hoặc toàn bộ nếu không truyền thời gian)
function doanhthu_phim_toan_he_thong($from_date = null, $to_date = null){
    $params = [];
    $dateWhere = '';
    if ($from_date && $to_date) { $dateWhere = ' WHERE DATE(hd.ngay_tt) BETWEEN ? AND ?'; $params[] = $from_date; $params[] = $to_date; }
    elseif ($from_date) { $dateWhere = ' WHERE DATE(hd.ngay_tt) >= ?'; $params[] = $from_date; }
    elseif ($to_date) { $dateWhere = ' WHERE DATE(hd.ngay_tt) <= ?'; $params[] = $to_date; }
    $sql = "SELECT p.id AS id_phim, p.tieu_de,
                   COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) AS so_ve,
                   COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN hd.thanh_tien END),0) AS doanh_thu
            FROM phim p
            LEFT JOIN lichchieu lc ON lc.id_phim = p.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            $dateWhere
            GROUP BY p.id, p.tieu_de
            ORDER BY doanh_thu DESC, so_ve DESC";
    return pdo_query($sql, ...$params);
}

// Hiệu suất rạp: vé, doanh thu, doanh thu trung bình/ve trong khoảng thời gian
function hieusuat_rap_khoang($from_date = null, $to_date = null){
    $where = '';
    $params = [];
    if ($from_date && $to_date) { $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) BETWEEN ? AND ?'; $params = [$from_date, $to_date]; }
    elseif ($from_date) { $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?'; $params = [$from_date]; }
    elseif ($to_date) { $where = 'WHERE DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?'; $params = [$to_date]; }
    $sql = "SELECT r.id, r.ten_rap,
                   COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) AS so_ve,
                   COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN COALESCE(hd.thanh_tien, v.price) END),0) AS doanh_thu,
                   CASE WHEN COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END) > 0
                        THEN ROUND(SUM(CASE WHEN v.trang_thai IN (1,2,4) THEN COALESCE(hd.thanh_tien, v.price) END) / COUNT(CASE WHEN v.trang_thai IN (1,2,4) THEN v.id END), 0)
                        ELSE 0 END AS dt_tb_ve
            FROM rap_chieu r
            LEFT JOIN lichchieu lc ON lc.id_rap = r.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            $where
            GROUP BY r.id, r.ten_rap
            ORDER BY doanh_thu DESC";
    return pdo_query($sql, ...$params);
}

function load_doanhthu_thang1(){
      if(isset($_GET['trang'])){
        $trang= $_GET['trang'];
    }else{
        $trang = 1 ;
    }   
        $bghi=5;
        $vitri = ($trang - 1)* $bghi;
    $sql = "SELECT 
    phim.id AS id, 
    phim.tieu_de AS tieu_de, 
    loaiphim.name AS ten_loaiphim, 
    MONTH(hoa_don.ngay_tt) AS thang,
    COUNT(ve.id) AS so_luong_ve_dat, 
    SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
    phim
LEFT JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
LEFT JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
LEFT JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
LEFT JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
LEFT JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id, thang
ORDER BY 
    phim.id DESC, thang DESC LIMIT $vitri,$bghi;

";
    $listtk = pdo_query($sql);

    return $listtk;
}
function load_doanhthu_thang(){
 
  $sql = "SELECT 
  phim.id AS id, 
  phim.tieu_de AS tieu_de, 
  loaiphim.name AS ten_loaiphim, 
  MONTH(hoa_don.ngay_tt) AS thang,
  COUNT(ve.id) AS so_luong_ve_dat, 
  SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
  phim
LEFT JOIN 
  loaiphim ON loaiphim.id = phim.id_loai
LEFT JOIN 
  lichchieu ON phim.id = lichchieu.id_phim
LEFT JOIN 
  khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
LEFT JOIN 
  ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
LEFT JOIN 
  hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
  phim.id, thang
ORDER BY 
  phim.id DESC, thang DESC;

";
  $listtk = pdo_query($sql);

  return $listtk;
}
function load_doanhthu_ngay1(){
       if(isset($_GET['trang'])){
        $trang= $_GET['trang'];
    }else{
        $trang = 1 ;
    }   
        $bghi=5;
        $vitri = ($trang - 1)* $bghi;
    $sql = "SELECT 
    phim.id AS id, 
    phim.tieu_de AS tieu_de, 
    loaiphim.name AS ten_loaiphim, 
    DATE(hoa_don.ngay_tt) AS ngay,
    COUNT(ve.id) AS so_luong_ve_dat,
    SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
    phim
INNER JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
INNER JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
INNER JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
INNER JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
INNER JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id, phim.tieu_de, loaiphim.name, DATE(hoa_don.ngay_tt)
ORDER BY 
    phim.id DESC, ngay DESC LIMIT $vitri,$bghi

;

";
    $listtk = pdo_query($sql);

    return $listtk;
}

function load_doanhthu_ngay(){
    $sql = "SELECT 
    phim.id AS id, 
    phim.tieu_de AS tieu_de, 
    loaiphim.name AS ten_loaiphim, 
    DATE(hoa_don.ngay_tt) AS ngay,
    COUNT(ve.id) AS so_luong_ve_dat,
    SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
    phim
INNER JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
INNER JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
INNER JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
INNER JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
INNER JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id, phim.tieu_de, loaiphim.name, DATE(hoa_don.ngay_tt)
ORDER BY 
    phim.id DESC, ngay DESC

;

";
    $listtk = pdo_query($sql);

    return $listtk;
}

function load_doanhthu_tuan1(){
      if(isset($_GET['trang'])){
        $trang= $_GET['trang'];
    }else{
        $trang = 1 ;
    }   
        $bghi=5;
        $vitri = ($trang - 1)* $bghi;
    $sql = "SELECT 
    phim.id AS id, 
    phim.tieu_de AS tieu_de, 
    loaiphim.name AS ten_loaiphim, 
    YEARWEEK(hoa_don.ngay_tt) AS tuan,
    COUNT(ve.id) AS so_luong_ve_dat, 
    SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
    phim
INNER JOIN 
    loaiphim ON loaiphim.id = phim.id_loai
INNER JOIN 
    lichchieu ON phim.id = lichchieu.id_phim
INNER JOIN 
    khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
INNER JOIN 
    ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
INNER JOIN 
    hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
    phim.id, phim.tieu_de, loaiphim.name, YEARWEEK(hoa_don.ngay_tt)
ORDER BY 
    phim.id DESC, tuan DESC LIMIT $vitri,$bghi;


";
    $listtk = pdo_query($sql);

    return $listtk;
}
function load_doanhthu_tuan(){

  $sql = "SELECT 
  phim.id AS id, 
  phim.tieu_de AS tieu_de, 
  loaiphim.name AS ten_loaiphim, 
  YEARWEEK(hoa_don.ngay_tt) AS tuan,
  COUNT(ve.id) AS so_luong_ve_dat, 
  SUM(hoa_don.thanh_tien) AS sum_thanhtien
FROM 
  phim
INNER JOIN 
  loaiphim ON loaiphim.id = phim.id_loai
INNER JOIN 
  lichchieu ON phim.id = lichchieu.id_phim
INNER JOIN 
  khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
INNER JOIN 
  ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id AND ve.trang_thai IN (1, 2,4)
INNER JOIN 
  hoa_don ON hoa_don.id = ve.id_hd
GROUP BY 
  phim.id, phim.tieu_de, loaiphim.name, YEARWEEK(hoa_don.ngay_tt)
ORDER BY 
  phim.id DESC, tuan DESC ;


";
  $listtk = pdo_query($sql);

  return $listtk;
}

function tong_day(){
    $today = date('Y-m-d'); // Lấy ngày hôm nay

    $sql = "SELECT 
        SUM(so_luong_ve_dat) AS tong_so_luong_ve_dat,
        SUM(sum_thanhtien) AS tong_doanh_thu
    FROM (
        SELECT 
            phim.id as id, 
            phim.tieu_de as tieu_de, 
            loaiphim.name as ten_loaiphim, 
            COUNT(CASE WHEN ve.trang_thai IN (1, 2,4) AND DATE(hoa_don.ngay_tt) = '$today' THEN ve.id END) as so_luong_ve_dat, 
            SUM(CASE WHEN ve.trang_thai IN (1, 2,4) AND DATE(hoa_don.ngay_tt) = '$today' THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
        FROM 
            phim
        LEFT JOIN 
            loaiphim ON loaiphim.id = phim.id_loai
        LEFT JOIN 
            lichchieu ON phim.id = lichchieu.id_phim
        LEFT JOIN 
            khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
        LEFT JOIN 
            ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
        LEFT JOIN 
            hoa_don ON hoa_don.id = ve.id_hd
        WHERE 
            DATE(hoa_don.ngay_tt) = '$today'
        GROUP BY 
            phim.id
    ) AS phim_stats";

    $result = pdo_query($sql);
    return $result;
}


function tong_week(){
    // Lấy ngày đầu tiên của tuần
    $firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));

    $sql = "SELECT 
        SUM(so_luong_ve_dat) AS tong_so_luong_ve_dat,
        SUM(sum_thanhtien) AS tong_doanh_thu
    FROM (
        SELECT 
            phim.id as id, 
            phim.tieu_de as tieu_de, 
            loaiphim.name as ten_loaiphim, 
            COUNT(CASE WHEN ve.trang_thai IN (1, 2,4) AND DATE(hoa_don.ngay_tt) >= '$firstDayOfWeek' THEN ve.id END) as so_luong_ve_dat, 
            SUM(CASE WHEN ve.trang_thai IN (1, 2,4) AND DATE(hoa_don.ngay_tt) >= '$firstDayOfWeek' THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
        FROM 
            phim
        LEFT JOIN 
            loaiphim ON loaiphim.id = phim.id_loai
        LEFT JOIN 
            lichchieu ON phim.id = lichchieu.id_phim
        LEFT JOIN 
            khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
        LEFT JOIN 
            ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
        LEFT JOIN 
            hoa_don ON hoa_don.id = ve.id_hd
        WHERE 
            DATE(hoa_don.ngay_tt) >= '$firstDayOfWeek'
        GROUP BY 
            phim.id
    ) AS phim_stats";

    $result = pdo_query($sql);
    return $result;
}


function tong_thang(){
    // Lấy ngày đầu tiên của tháng
    $firstDayOfMonth = date('Y-m-01');

    $sql = "SELECT 
        SUM(so_luong_ve_dat) AS tong_so_luong_ve_dat,
        SUM(sum_thanhtien) AS tong_doanh_thu
    FROM (
        SELECT 
            phim.id as id, 
            phim.tieu_de as tieu_de, 
            loaiphim.name as ten_loaiphim, 
            COUNT(CASE WHEN ve.trang_thai IN (1, 2) AND DATE(hoa_don.ngay_tt) >= '$firstDayOfMonth' THEN ve.id END) as so_luong_ve_dat, 
            SUM(CASE WHEN ve.trang_thai IN (1, 2) AND DATE(hoa_don.ngay_tt) >= '$firstDayOfMonth' THEN hoa_don.thanh_tien ELSE 0 END) as sum_thanhtien
        FROM 
            phim
        LEFT JOIN 
            loaiphim ON loaiphim.id = phim.id_loai
        LEFT JOIN 
            lichchieu ON phim.id = lichchieu.id_phim
        LEFT JOIN 
            khung_gio_chieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
        LEFT JOIN 
            ve ON ve.id_thoi_gian_chieu = khung_gio_chieu.id
        LEFT JOIN 
            hoa_don ON hoa_don.id = ve.id_hd
        WHERE 
            DATE(hoa_don.ngay_tt) >= '$firstDayOfMonth'
        GROUP BY 
            phim.id
    ) AS phim_stats";

    $result = pdo_query($sql);
    return $result;
}

function best_combo(){
    $sql = "SELECT 
    v.combo,
    COUNT(v.combo) AS so_luong_dat
FROM 
    ve v
LEFT JOIN 
    taikhoan ON taikhoan.id = v.id_tk
LEFT JOIN 
    khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
LEFT JOIN 
    phim ON phim.id = v.id_phim
LEFT JOIN 
    lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
WHERE 
    v.trang_thai IN (1, 2,4)
GROUP BY 
    v.combo
ORDER BY 
    so_luong_dat DESC
LIMIT 1;

";
    $result = pdo_query($sql);
    return $result;
}

// ============ Dashboard helpers (with optional cinema and date range) ============
function tk_sum_revenue($id_rap = null, $from_date = null, $to_date = null){
    $where = ['v.trang_thai IN (1,2,4)'];
    $params = [];
    if ($id_rap) { $where[] = 'lc.id_rap = ?'; $params[] = $id_rap; }
    if ($from_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?'; $params[] = $from_date; }
    if ($to_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?'; $params[] = $to_date; }
    $whereSql = 'WHERE '.implode(' AND ', $where);
    $sql = "SELECT COALESCE(SUM(COALESCE(hd.thanh_tien, v.price)),0) AS total_revenue
            FROM ve v
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            LEFT JOIN khung_gio_chieu kg ON kg.id = v.id_thoi_gian_chieu
            LEFT JOIN lichchieu lc ON lc.id = kg.id_lich_chieu
            $whereSql";
    $row = pdo_query_one($sql, ...$params);
    return (int)($row['total_revenue'] ?? 0);
}

function tk_count_tickets($id_rap = null, $from_date = null, $to_date = null){
    $where = ['v.trang_thai IN (1,2,4)'];
    $params = [];
    if ($id_rap) { $where[] = 'lc.id_rap = ?'; $params[] = $id_rap; }
    if ($from_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?'; $params[] = $from_date; }
    if ($to_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?'; $params[] = $to_date; }
    $whereSql = 'WHERE '.implode(' AND ', $where);
    $sql = "SELECT COUNT(v.id) AS so_ve
            FROM ve v
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            LEFT JOIN khung_gio_chieu kg ON kg.id = v.id_thoi_gian_chieu
            LEFT JOIN lichchieu lc ON lc.id = kg.id_lich_chieu
            $whereSql";
    $row = pdo_query_one($sql, ...$params);
    return (int)($row['so_ve'] ?? 0);
}

function tk_count_movies_showing($id_rap = null, $from_date = null, $to_date = null){
    $where = [];
    $params = [];
    if ($id_rap) { $where[] = 'lc.id_rap = ?'; $params[] = $id_rap; }
    if ($from_date) { $where[] = 'DATE(lc.ngay_chieu) >= ?'; $params[] = $from_date; }
    if ($to_date) { $where[] = 'DATE(lc.ngay_chieu) <= ?'; $params[] = $to_date; }
    $whereSql = empty($where) ? '' : ('WHERE '.implode(' AND ', $where));
    $sql = "SELECT COUNT(DISTINCT lc.id_phim) AS so_phim
            FROM lichchieu lc
            $whereSql";
    $row = pdo_query_one($sql, ...$params);
    return (int)($row['so_phim'] ?? 0);
}

function best_combo_khoang($id_rap = null, $from_date = null, $to_date = null){
    $where = ['v.trang_thai IN (1,2,4)'];
    $params = [];
    if ($id_rap) { $where[] = 'lc.id_rap = ?'; $params[] = $id_rap; }
    if ($from_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) >= ?'; $params[] = $from_date; }
    if ($to_date) { $where[] = 'DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) <= ?'; $params[] = $to_date; }
    $whereSql = 'WHERE '.implode(' AND ', $where);
    $sql = "SELECT v.combo, COUNT(v.combo) AS so_luong_dat
            FROM ve v
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            LEFT JOIN khung_gio_chieu kg ON kg.id = v.id_thoi_gian_chieu
            LEFT JOIN lichchieu lc ON lc.id = kg.id_lich_chieu
            $whereSql
            GROUP BY v.combo
            ORDER BY so_luong_dat DESC
            LIMIT 1";
    return pdo_query_one($sql, ...$params);
}

// Doanh thu theo ngày trong khoảng (option lọc rạp)
function tk_revenue_by_date($from_date, $to_date, $id_rap = null){
    $params = [];
    $where = ['DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) BETWEEN ? AND ?'];
    $params[] = $from_date; $params[] = $to_date;
    if ($id_rap) { $where[] = 'lc.id_rap = ?'; $params[] = $id_rap; }
    $whereSql = 'WHERE '.implode(' AND ', $where);
    $sql = "SELECT DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) AS ngay,
                   COALESCE(SUM(COALESCE(hd.thanh_tien, v.price)),0) AS revenue
            FROM ve v
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            LEFT JOIN khung_gio_chieu kg ON kg.id = v.id_thoi_gian_chieu
            LEFT JOIN lichchieu lc ON lc.id = kg.id_lich_chieu
            $whereSql
            GROUP BY DATE(COALESCE(hd.ngay_tt, v.ngay_dat))
            ORDER BY DATE(COALESCE(hd.ngay_tt, v.ngay_dat))";
    return pdo_query($sql, ...$params);
}

// Doanh thu theo rạp trong khoảng
function tk_revenue_by_rap($from_date, $to_date){
    $sql = "SELECT r.id as id_rap, r.ten_rap,
                   COALESCE(SUM(CASE WHEN v.trang_thai IN (1,2,4)
                        AND DATE(COALESCE(hd.ngay_tt, v.ngay_dat)) BETWEEN ? AND ?
                        THEN COALESCE(hd.thanh_tien, v.price) ELSE 0 END),0) AS revenue
            FROM rap_chieu r
            LEFT JOIN lichchieu lc ON lc.id_rap = r.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            LEFT JOIN hoa_don hd ON hd.id = v.id_hd
            GROUP BY r.id, r.ten_rap
            ORDER BY revenue DESC";
    return pdo_query($sql, $from_date, $to_date);
}

// Top 5 phim bán chạy nhất (theo số vé đã bán)
function get_top_movies($limit = 5, $from_date = null, $to_date = null, $id_rap = null) {
    $limit = (int)$limit;
    $params = [];
    $where_clauses = ["v.trang_thai IN (1,2,4)"];
    
    if ($from_date && $to_date) {
        $where_clauses[] = "DATE(v.ngay_dat) BETWEEN ? AND ?";
        $params[] = $from_date;
        $params[] = $to_date;
    }
    
    if ($id_rap) {
        $where_clauses[] = "lc.id_rap = ?";
        $params[] = $id_rap;
    }
    
    $where = "WHERE " . implode(" AND ", $where_clauses);
    
    $sql = "SELECT p.id, p.tieu_de, COUNT(v.id) as ticket_count
            FROM phim p
            LEFT JOIN lichchieu lc ON lc.id_phim = p.id
            LEFT JOIN khung_gio_chieu kg ON kg.id_lich_chieu = lc.id
            LEFT JOIN ve v ON v.id_thoi_gian_chieu = kg.id
            " . $where . "
            GROUP BY p.id, p.tieu_de
            ORDER BY ticket_count DESC
            LIMIT " . $limit;
    
    return empty($params) ? pdo_query($sql) : pdo_query($sql, ...$params);
}

// Tỉ lệ ghế đã bán vs còn trống
function get_seat_occupancy($from_date = null, $to_date = null, $id_rap = null) {
    $params = [];
    $where_clauses = [];
    
    if ($from_date && $to_date) {
        $where_clauses[] = "DATE(v.ngay_dat) BETWEEN ? AND ?";
        $params[] = $from_date;
        $params[] = $to_date;
    }
    
    if ($id_rap) {
        $where_clauses[] = "p.id_rap = ?";
        $params[] = $id_rap;
    }
    
    $where = !empty($where_clauses) ? "AND " . implode(" AND ", $where_clauses) : "";
    
    $sql = "SELECT 
            COUNT(DISTINCT pg.id) as total_seats,
            COUNT(DISTINCT CASE WHEN v.trang_thai IN (1,2,4) " . $where . " THEN v.id END) as sold_seats
            FROM phongchieu p
            LEFT JOIN phong_ghe pg ON pg.id_phong = p.id
            LEFT JOIN ve v ON v.id_phim IS NOT NULL";
    
    return empty($params) ? pdo_query_one($sql) : pdo_query_one($sql, ...$params);
}

