<?php
function load_phong(){
    $sql = "select * from phongchieu where 1";
    $re = pdo_query($sql);
    return $re;

}
function loadone_phong($id){
    $sql = "select * from phongchieu where id=".$id;
    $re = pdo_query_one($sql);
    return $re;

}
function xoa_phong($id)
{
    $sql = "delete from phongchieu where id=" . $id;
    pdo_execute($sql);
}

function update_phong($id,$name,$so_ghe=null,$dien_tich=null){
    if ($so_ghe === null && $dien_tich === null){
        $sql = "update phongchieu set `name`=? where `id`=?";
        pdo_execute($sql, $name, $id);
    } else {
        $sql = "update phongchieu set `name`=?, `so_ghe`=?, `dien_tich`=? where `id`=?";
        pdo_execute($sql, $name, $so_ghe, $dien_tich, $id);
    }
}
function them_phong($name,$id_rap,$so_ghe=0,$dien_tich=0){
    $sql = "insert into `phongchieu`(`name`,`id_rap`,`so_ghe`,`dien_tich`) values (?,?,?,?)";
    pdo_execute($sql, $name, $id_rap, $so_ghe, $dien_tich);
}
function loadall_phongchieu(){
    $sql = "select phongchieu.id, phongchieu.name,phongchieu.id_rap,rapchieu.id from phongchieu
            left join rapchieu on phongchieu.id_rap= rapchieu.id
            where phongchieu.id_rap";
    $re = pdo_query($sql);
    return $re;
}
function load_phong_by_rap($id_rap){
    $sql = "SELECT * FROM phongchieu WHERE id_rap = ? ORDER BY id ASC";
    return pdo_query($sql, $id_rap);
}
