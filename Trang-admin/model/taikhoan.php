<?php
function loadall_taikhoan() {
    $sql = "SELECT 
        t.id,
        t.name,
        IFNULL(COUNT(ve.id), 0) AS so_ve,
        t.user,
        t.pass,
        t.email,
        t.phone,
        t.dia_chi,
        t.vai_tro,
        r.ten_rap AS ten_rap
    FROM taikhoan t
    LEFT JOIN ve ON ve.id_tk = t.id AND ve.trang_thai IN (1, 2, 4)
    LEFT JOIN rap_chieu r ON r.id = t.id_rap
    WHERE t.vai_tro = 0
    GROUP BY t.id
    ORDER BY t.id DESC";
    return pdo_query($sql);
}


function check_tk($user,$pass){
    // Dùng prepared statement để tránh lỗi do ký tự lạ và tăng độ ổn định
    $sql = "SELECT * FROM taikhoan WHERE user = ? AND pass = ?";
    return pdo_query_one($sql, $user, $pass);
}
function dang_xuat(){
    unset($_SESSION['user']);
}

function insert_taikhoan($email,$user,$pass,$name,$sdt,$dc){
    $sql="INSERT INTO `taikhoan` ( `email`, `user`, `pass`,`dia_chi`,`phone`,`name`,`vai_tro`) VALUES ( '$email', '$user','$pass','$dc','$sdt','$name','1'); ";
    pdo_execute($sql);
}

function sua_tk($id,$name,$user,$pass,$email,$sdt,$dc){
    $sql = "update taikhoan set name ='".$name."', user ='".$user."',pass ='".$pass."',email ='".$email."',phone ='".$sdt."',dia_chi ='".$dc."' where id=".$id;

    pdo_execute($sql);
}
function xoa_tk($id)
{
    $sql = "delete from taikhoan where id=" . $id;
    pdo_execute($sql);
}
function loadone_taikhoan($id){
    $sql = "select * from taikhoan where id =".$id;
    $result = pdo_query_one($sql);
    return $result;
}

// Lấy vai trò và rạp của tài khoản (để kiểm tra phân quyền thao tác)
function tk_get_role_rap($id){
    return pdo_query_one("SELECT id, vai_tro, id_rap FROM taikhoan WHERE id = ?", $id);
}

function loadall_taikhoan_nv() {
    $sql = "SELECT t.id, t.name, t.user, t.pass, t.email, t.phone, t.dia_chi, t.vai_tro, r.ten_rap
            FROM taikhoan t
            LEFT JOIN rap_chieu r ON r.id = t.id_rap
            WHERE t.vai_tro = 1
            ORDER BY t.id DESC";
    return pdo_query($sql);
}

function loadall_taikhoan_nv_by_rap($id_rap) {
    $sql = "SELECT t.id, t.name, t.user, t.pass, t.email, t.phone, t.dia_chi, t.vai_tro, r.ten_rap
            FROM taikhoan t
            LEFT JOIN rap_chieu r ON r.id = t.id_rap
            WHERE t.vai_tro = 1 AND t.id_rap = ?
            ORDER BY t.id DESC";
    return pdo_query($sql, $id_rap);
}

function get_tk_by_email($email){
    return pdo_query_one("SELECT * FROM taikhoan WHERE email = ?", $email);
}

function insert_taikhoan_nv_for_rap($email,$user,$pass,$name,$sdt,$dc,$id_rap){
    $sql="INSERT INTO `taikhoan` ( `email`, `user`, `pass`,`dia_chi`,`phone`,`name`,`vai_tro`, `id_rap`) VALUES ( ?, ?, ?, ?, ?, ?, 1, ?)";
    pdo_execute($sql, $email, $user, $pass, $dc, $sdt, $name, $id_rap);
}

// Generic: thêm tài khoản với vai trò và rạp (nếu có)
function insert_user_role($email,$user,$pass,$name,$sdt,$dc,$vai_tro,$id_rap=null){
    if ($id_rap === null) {
        $sql = "INSERT INTO taikhoan (email, user, pass, dia_chi, phone, name, vai_tro, img, ngay_tao) VALUES (?,?,?,?,?,?,?, '', NOW())";
        pdo_execute($sql, $email, $user, $pass, $dc, $sdt, $name, $vai_tro);
    } else {
        $sql = "INSERT INTO taikhoan (email, user, pass, dia_chi, phone, name, vai_tro, id_rap, img, ngay_tao) VALUES (?,?,?,?,?,?,?,?, '', NOW())";
        pdo_execute($sql, $email, $user, $pass, $dc, $sdt, $name, $vai_tro, $id_rap);
    }
}

function loadall_taikhoan_by_role($role){
    return pdo_query("SELECT t.*, r.ten_rap FROM taikhoan t LEFT JOIN rap_chieu r ON r.id = t.id_rap WHERE t.vai_tro = ? ORDER BY t.id DESC", $role);
}

function loadall_quanly_rap(){
    $sql = "SELECT t.*, r.ten_rap FROM taikhoan t LEFT JOIN rap_chieu r ON r.id = t.id_rap WHERE t.vai_tro = 3 ORDER BY t.id DESC";
    return pdo_query($sql);
}


