<?php
function loadall_lichchieu(){
    $sql = "select l.id, phim.tieu_de, l.ngay_chieu, l.id_rap from lichchieu l 
            left join phim on phim.id= l.id_phim
            where 1 order by id desc";
    $re = pdo_query($sql);
    return $re;
}

function loadone_lichchieu($id)
{
    $sql = "select * from lichchieu where id =" . $id;
    $re = pdo_query_one($sql);
    return $re;
}

function loadall_lichchieu_by_rap($id_rap){
    $sql = "select l.id, phim.tieu_de, l.ngay_chieu, l.id_rap, l.trang_thai_duyet from lichchieu l 
            left join phim on phim.id= l.id_phim
            where l.id_rap = ? order by id desc";
    return pdo_query($sql, $id_rap);
}

function them_lichchieu($id_phim, $ngay_chieu, $id_rap){
    // Đặt trạng thái mặc định rõ ràng để tránh phụ thuộc DEFAULT của DB
    $sql = "INSERT INTO lichchieu(id_phim,ngay_chieu,id_rap,trang_thai_duyet) VALUES (?,?,?,?)";
    pdo_execute($sql, $id_phim, $ngay_chieu, $id_rap, 'Chờ duyệt');
}

function sua_lichchieu($id,$id_phim,$ngay_chieu,$id_rap)
{
    $sql = "update lichchieu set `id_phim`=?,`ngay_chieu`=?,`id_rap`=? where `lichchieu`.`id`=?";
    pdo_execute($sql, $id_phim, $ngay_chieu, $id_rap, $id);
}

function xoa_lc($id)
{
    $sql = "DELETE FROM lichchieu WHERE id=" . $id;
    pdo_execute($sql);
}

function lc_list_by_trang_thai($trang_thai = 'cho_duyet'){
    // Chấp nhận cả dạng có dấu/không dấu để tương thích dữ liệu cũ
    $map = [
        'cho_duyet' => ['cho_duyet','Chờ duyệt','cho duyet'],
        'da_duyet'  => ['da_duyet','Đã duyệt','da duyet'],
        'tu_choi'   => ['tu_choi','Từ chối','tu choi'],
    ];
    $vals = $map[$trang_thai] ?? [$trang_thai];
    $place = implode(',', array_fill(0, count($vals), '?'));
    $sql = "SELECT lc.*, p.tieu_de, r.ten_rap
            FROM lichchieu lc
            JOIN phim p ON p.id = lc.id_phim
            JOIN rap_chieu r ON r.id = lc.id_rap
            WHERE lc.trang_thai_duyet IN ($place)
            ORDER BY lc.ngay_chieu DESC";
    return pdo_query($sql, ...$vals);
}

function lc_duyet($id, $trang_thai){
    pdo_execute("UPDATE lichchieu SET trang_thai_duyet = ? WHERE id = ?", $trang_thai, $id);
}

// Danh sách lịch chiếu theo rạp và khoảng thời gian
function lc_list_by_rap_and_date_range($id_rap, $from_date, $to_date){
    $sql = "SELECT lc.*, p.tieu_de, r.ten_rap
            FROM lichchieu lc
            JOIN phim p ON p.id = lc.id_phim
            JOIN rap_chieu r ON r.id = lc.id_rap
            WHERE lc.id_rap = ? AND DATE(lc.ngay_chieu) BETWEEN ? AND ?
            ORDER BY lc.ngay_chieu ASC";
    return pdo_query($sql, $id_rap, $from_date, $to_date);
}
