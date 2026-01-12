<?php
function loadall_phim()
    {if(isset($_GET['sotrang'])){
        $sotrang=$_GET['sotrang'];
    }else{
        $sotrang=1;
    }
    $bghi=5;
    $vitri=($sotrang-1)*$bghi;
        $sql = "SELECT p.id, p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi, p.link_trailer
        FROM phim p
        INNER JOIN loaiphim ON loaiphim.id = p.id_loai where 1 order by id asc limit $vitri,$bghi";
        $re = pdo_query($sql);
        return $re;
    }
function loadall_phim_hot()
    {
        $sql = "SELECT
        p.id,
        p.tieu_de,
        p.daodien,
        p.dienvien,
        p.img,
        p.mo_ta,
        p.date_phat_hanh,
        p.thoi_luong_phim,
        loaiphim.name,
        p.quoc_gia,
        p.gia_han_tuoi,
        COUNT(v.id) AS tong_so_ve
    FROM
        phim p
    INNER JOIN
        loaiphim ON loaiphim.id = p.id_loai
    LEFT JOIN
        ve v ON v.id_phim = p.id
    WHERE
        v.trang_thai IN (1, 2, 4)
    GROUP BY
        p.id
    ORDER BY
        tong_so_ve DESC
    LIMIT
        0, 6;
    ";
        $listsanpham = pdo_query($sql);
        return $listsanpham;
    }


function loadall_phim_home()
    {
        $sql = "SELECT p.id, p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi
    FROM phim p
    INNER JOIN loaiphim ON loaiphim.id = p.id_loai
    WHERE 1
    ORDER BY p.id DESC
    LIMIT 0,8;";
        $listsanpham = pdo_query($sql);
        return $listsanpham;
    }

function loadone_phim($id)
    {
        $sql = "SELECT p.id, p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi, p.link_trailer
    FROM phim p
    INNER JOIN loaiphim ON loaiphim.id = p.id_loai
    WHERE 1  and p.id =" . $id;
        $re = pdo_query_one($sql);
        return $re;
    }


//function loadall_phim1($kys="",$id_loai=0){
//    $sql="SELECT phim.id, phim.tieu_de, phim.img, phim.mo_ta, phim.thoi_luong_phim, phim.date_phat_hanh, loaiphim.name FROM phim left JOIN loaiphim ON phim.id_loai = loaiphim.id";
//    if($kys!=""){
//        $sql.=" and tieu_de like '%".$kys."%'";
//    }
//    if($id_loai>0){
//        $sql.=" and id_loai ='".$id_loai."'";
//    }
//    $sql.=" order by id desc";
//    $re=pdo_query($sql);
//    return  $re;
//}
function phim_select_all()
    {
        $sql = "SELECT phim.id, phim.tieu_de, phim.img, phim.mo_ta, phim.thoi_luong_phim, phim.date_phat_hanh, loaiphim.name FROM phim left JOIN loaiphim ON phim.id_loai = loaiphim.id
                where 1 order by id asc";
        return pdo_query($sql);
    }

function loadall_phim1($kys="",$id_loai=0)
    {
    $sql="SELECT p.id, p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi, p.link_trailer
    FROM phim p
    INNER JOIN loaiphim ON loaiphim.id = p.id_loai";
        if($kys!=""){
            $sql.=" and tieu_de like '%".$kys."%'";
        }
        if($id_loai>0){
            $sql.=" and id_loai ='".$id_loai."'";
        }
        $sql.=" order by id desc  ";
        $re=pdo_query($sql);
        return  $re;
    }

function load_phimdc()
{
        $sql = "SELECT p.id,p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi
    FROM phim p
    INNER JOIN loaiphim ON loaiphim.id = p.id_loai
    WHERE p.date_phat_hanh < CURDATE();
    ";
        $re=pdo_query($sql);
        return  $re;
}

function load_phimsc(){
        $sql = "SELECT p.id,p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, p.date_phat_hanh, p.thoi_luong_phim, loaiphim.name, p.quoc_gia, p.gia_han_tuoi
    FROM phim p
    INNER JOIN loaiphim ON loaiphim.id = p.id_loai
    WHERE p.date_phat_hanh > CURDATE()
    ";
        $re=pdo_query($sql);
        return  $re;
}

function load_lc_p($id, $id_lichchieu, $id_gio) {
    $sql = "SELECT 
                p.id AS id_phim, 
                p.tieu_de,
                p.img,
                p.thoi_luong_phim,
                lc.ngay_chieu,
                lc.id AS id_lichchieu,
                kgc.thoi_gian_chieu,
                kgc.id AS id_gio,
                phong.name as ten_phong,
                phong.id as id_phong,
                phong.loai_phong,
                rap.ten_rap,
                rap.id as id_rap,
                rap.dia_chi as dia_chi_rap,
                rap.so_dien_thoai as sdt_rap
            FROM khung_gio_chieu kgc
            JOIN lichchieu lc ON kgc.id_lich_chieu = lc.id
            JOIN phim p ON lc.id_phim = p.id
            JOIN phongchieu phong ON kgc.id_phong = phong.id
            JOIN rap_chieu rap ON lc.id_rap = rap.id
            WHERE p.id = ? 
              AND lc.id = ? 
              AND kgc.id = ?";

    return pdo_query_one($sql, $id, $id_lichchieu, $id_gio);
}


function dat_phim($ten, $ngay, $gio, $gia,$tk)
{
    $sql = "insert into `ve` (`id_thoi_gian_chieu`,`id_ngay_chieu`,`id_phim`,`id_tk`,`ghe`,`price`) values ('$gio','$ngay','$ten','$gia','$tk','0','$gia')";
    pdo_execute($sql);
}

/**
 * Lấy danh sách phim đang chiếu tại rạp cụ thể (có lịch chiếu từ hôm nay)
 * @param int $id_rap ID của rạp
 * @return array Danh sách phim
 */
function load_phim_dang_chieu_theo_rap($id_rap) {
    $sql = "SELECT DISTINCT
                p.id,
                p.tieu_de,
                p.daodien,
                p.dienvien,
                p.img,
                p.mo_ta,
                p.date_phat_hanh,
                p.thoi_luong_phim,
                p.quoc_gia,
                p.gia_han_tuoi,
                p.link_trailer,
                lp.name as ten_loai,
                MIN(lc.ngay_chieu) as ngay_chieu_dau,
                MAX(lc.ngay_chieu) as ngay_chieu_cuoi,
                COUNT(DISTINCT DATE(lc.ngay_chieu)) as so_ngay_chieu,
                COUNT(kgc.id) as tong_suat_chieu
            FROM phim p
            INNER JOIN loaiphim lp ON lp.id = p.id_loai
            INNER JOIN lichchieu lc ON lc.id_phim = p.id
            LEFT JOIN khung_gio_chieu kgc ON kgc.id_lich_chieu = lc.id
            WHERE lc.id_rap = ?
              AND DATE(lc.ngay_chieu) >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'
            GROUP BY p.id, p.tieu_de, p.daodien, p.dienvien, p.img, p.mo_ta, 
                     p.date_phat_hanh, p.thoi_luong_phim, p.quoc_gia, 
                     p.gia_han_tuoi, p.link_trailer, lp.name
            ORDER BY ngay_chieu_dau ASC, p.tieu_de ASC";
    
    return pdo_query($sql, $id_rap);
}

/**
 * Lấy giờ chiếu preview của phim tại rạp (để hiển thị nhanh trên card)
 * @param int $id_phim
 * @param int $id_rap
 * @param int $limit Số giờ chiếu tối đa
 * @return array
 */
function get_gio_chieu_preview($id_phim, $id_rap, $limit = 4) {
    // Ép kiểu limit về integer để tránh SQL injection
    $limit = (int)$limit;
    
    $sql = "SELECT DISTINCT 
                kgc.thoi_gian_chieu,
                lc.ngay_chieu,
                DATE(lc.ngay_chieu) as ngay
            FROM khung_gio_chieu kgc
            JOIN lichchieu lc ON kgc.id_lich_chieu = lc.id
            WHERE lc.id_phim = ?
              AND lc.id_rap = ?
              AND DATE(lc.ngay_chieu) >= CURDATE()
              AND lc.trang_thai_duyet = 'Đã duyệt'
            ORDER BY lc.ngay_chieu ASC, kgc.thoi_gian_chieu ASC
            LIMIT $limit";
    
    return pdo_query($sql, $id_phim, $id_rap);
}