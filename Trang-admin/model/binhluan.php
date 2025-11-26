<?php
 function loadall_binhluan($id){
    $sql = "
        SELECT binhluan.id, binhluan.noidung, taikhoan.name, phim.tieu_de,  binhluan.ngaybinhluan FROM `binhluan` 
        LEFT JOIN taikhoan ON binhluan.id_user = taikhoan.id
        LEFT JOIN phim ON binhluan.id_phim = phim.id
        WHERE phim.id =$id ;
    ";
    $result =  pdo_query($sql);
    return $result;
 }
 function loadall_bl(){
    if(isset($_GET['sotrang'])){
        $sotrang =$_GET['sotrang'];
    }else{
        $sotrang= 1;
    }
    $bghi = 5;
    $vitri = ($sotrang-1 )*$bghi ;
    $sql = "
        SELECT binhluan.id, binhluan.noidung, taikhoan.name, phim.tieu_de,  binhluan.ngaybinhluan FROM `binhluan` 
        LEFT JOIN taikhoan ON  taikhoan.id=binhluan.id_user
        LEFT JOIN phim ON  phim.id=binhluan.id_phim 
        WHERE phim.id limit $vitri,$bghi;
    ";
    $result =  pdo_query($sql);
    return $result;
 }
function load_binhluan($id_phim = 0){
    $sql = "select * from binhluan where 1";

     if($id_phim > 0){
        $sql .= " and id_phim = $id_phim";
     }

     $sql .= " order by id_phim desc";
    $result = pdo_query($sql);
    return $result;
}
function delete_binhluan($id){
    $sql = "delete from binhluan where id = '$id'";
    pdo_execute($sql);
}

// Reply functions
function add_reply_binhluan($id_binhluan, $id_user, $noidung){
    $sql = "INSERT INTO tra_loi_binhluan (id_binhluan, id_user, noidung, ngay_tao) 
            VALUES ('$id_binhluan', '$id_user', '$noidung', NOW())";
    pdo_execute($sql);
    return pdo_last_insert_id();
}

function load_replies_binhluan($id_binhluan){
    $sql = "SELECT tra_loi_binhluan.id, tra_loi_binhluan.noidung, tra_loi_binhluan.ngay_tao, 
            taikhoan.name, taikhoan.id as id_user
            FROM tra_loi_binhluan
            LEFT JOIN taikhoan ON tra_loi_binhluan.id_user = taikhoan.id
            WHERE tra_loi_binhluan.id_binhluan = '$id_binhluan'
            ORDER BY tra_loi_binhluan.ngay_tao ASC";
    return pdo_query($sql);
}

function delete_reply_binhluan($id){
    $sql = "DELETE FROM tra_loi_binhluan WHERE id = '$id'";
    pdo_execute($sql);
}


?>