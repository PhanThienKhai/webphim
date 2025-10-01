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

function them_lichchieu_return_id($id_phim, $ngay_chieu, $id_rap){
    // Đặt trạng thái mặc định rõ ràng để tránh phụ thuộc DEFAULT của DB
    $sql = "INSERT INTO lichchieu(id_phim,ngay_chieu,id_rap,trang_thai_duyet) VALUES (?,?,?,?)";
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_phim, $ngay_chieu, $id_rap, 'Chờ duyệt']);
        return $conn->lastInsertId();
    } catch(PDOException $e) {
        throw $e;
    } finally {
        unset($conn);
    }
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

// 🎬 HÀM MỚI CHO KẾ HOẠCH CHIẾU PHIM TÍCH HỢP
function them_lichchieu_kehoach($ma_phim, $ma_rap, $ngay_chieu, $ghi_chu = '', $ma_ke_hoach = '', $nguoi_tao = null) {
    $sql = "INSERT INTO lichchieu (ma_ke_hoach, id_phim, id_rap, ngay_chieu, trang_thai_duyet, ghi_chu, nguoi_tao, ngay_tao) 
            VALUES (?, ?, ?, ?, 'Chờ duyệt', ?, ?, NOW())";
    
    try {
        // Sử dụng cùng một connection cho cả INSERT và LAST_INSERT_ID
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_ke_hoach, $ma_phim, $ma_rap, $ngay_chieu, $ghi_chu, $nguoi_tao]);
        
        // Lấy ID vừa được chèn
        $lastId = $conn->lastInsertId();
        
        return $lastId ? intval($lastId) : false;
    } catch (Exception $e) {
        error_log("Lỗi thêm lịch chiếu: " . $e->getMessage());
        return false;
    }
}

function them_khunggiochieu($id_lich_chieu, $id_phong, $gio_chieu) {
    $sql = "INSERT INTO khung_gio_chieu (id_lich_chieu, id_phong, thoi_gian_chieu)
            VALUES (?, ?, ?)";
    
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_lich_chieu, $id_phong, $gio_chieu]);
        return true;
    } catch (Exception $e) {
        error_log("Lỗi thêm khung giờ chiếu: " . $e->getMessage());
        return false;
    }
}

// 🔥 CÁC HÀM MỚI CHO QUẢN LÝ KẾ HOẠCH THEO NHÓM

// Tạo mã kế hoạch duy nhất
function tao_ma_ke_hoach($id_rap) {
    return 'KH_' . $id_rap . '_' . date('YmdHis') . '_' . rand(100, 999);
}

// Lấy danh sách kế hoạch chiếu (nhóm theo mã kế hoạch) - cho quản lý cụm
function ke_hoach_list_by_cum($id_cum = null) {
    $where_clause = $id_cum ? "AND r.id_cum = ?" : "";
    $params = $id_cum ? [$id_cum] : [];
    
    $sql = "SELECT 
                lc.ma_ke_hoach,
                lc.trang_thai_duyet,
                MIN(lc.ngay_chieu) as tu_ngay,
                MAX(lc.ngay_chieu) as den_ngay,
                lc.ghi_chu,
                lc.nguoi_tao,
                lc.ngay_tao,
                p.tieu_de as ten_phim,
                p.thoi_luong_phim,
                p.img,
                lp.name as ten_loai,
                r.ten_rap,
                tk.name as nguoi_tao_ten,
                COUNT(DISTINCT lc.ngay_chieu) as so_ngay_chieu,
                COUNT(kgc.id) as so_suat_chieu
            FROM lichchieu lc
            JOIN phim p ON p.id = lc.id_phim
            LEFT JOIN loaiphim lp ON lp.id = p.id_loai
            JOIN rap_chieu r ON r.id = lc.id_rap
            LEFT JOIN taikhoan tk ON tk.id = lc.nguoi_tao
            LEFT JOIN khung_gio_chieu kgc ON kgc.id_lich_chieu = lc.id
            WHERE lc.ma_ke_hoach IS NOT NULL $where_clause
            GROUP BY lc.ma_ke_hoach, lc.trang_thai_duyet, lc.ghi_chu, lc.nguoi_tao, lc.ngay_tao,
                     p.tieu_de, p.thoi_luong_phim, p.img, lp.name, r.ten_rap, tk.name
            ORDER BY lc.ngay_tao DESC";
    
    return pdo_query($sql, ...$params);
}

// Lấy chi tiết kế hoạch chiếu
function ke_hoach_chi_tiet($ma_ke_hoach, $id_rap = null) {
    $where_clause = $id_rap ? "AND lc.id_rap = ?" : "";
    $params = $id_rap ? [$ma_ke_hoach, $id_rap] : [$ma_ke_hoach];
    
    $sql = "SELECT 
                lc.*,
                p.tieu_de as ten_phim,
                p.thoi_luong_phim,
                p.mo_ta,
                p.img,
                lp.name as ten_loai,
                r.ten_rap,
                tk.name as nguoi_tao_ten,
                GROUP_CONCAT(DISTINCT CONCAT(kgc.thoi_gian_chieu, '|', pc.name) ORDER BY kgc.thoi_gian_chieu) as khung_gio
            FROM lichchieu lc
            JOIN phim p ON p.id = lc.id_phim
            LEFT JOIN loaiphim lp ON lp.id = p.id_loai
            JOIN rap_chieu r ON r.id = lc.id_rap
            LEFT JOIN taikhoan tk ON tk.id = lc.nguoi_tao
            LEFT JOIN khung_gio_chieu kgc ON kgc.id_lich_chieu = lc.id
            LEFT JOIN phongchieu pc ON pc.id = kgc.id_phong
            WHERE lc.ma_ke_hoach = ? $where_clause
            GROUP BY lc.id, lc.ma_ke_hoach, lc.id_phim, lc.id_rap, lc.ngay_chieu, lc.trang_thai_duyet, 
                     lc.ghi_chu, lc.nguoi_tao, lc.ngay_tao, p.tieu_de, p.thoi_luong_phim, p.mo_ta, 
                     p.img, lp.name, r.ten_rap, tk.name
            ORDER BY lc.ngay_chieu ASC";
    
    return pdo_query($sql, ...$params);
}

// Duyệt/từ chối toàn bộ kế hoạch
function ke_hoach_duyet($ma_ke_hoach, $trang_thai, $nguoi_duyet = null, $ly_do = '') {
    $sql = "UPDATE lichchieu 
            SET trang_thai_duyet = ?, 
                nguoi_tao = COALESCE(?, nguoi_tao)
            WHERE ma_ke_hoach = ?";
    
    try {
        pdo_execute($sql, $trang_thai, $nguoi_duyet, $ma_ke_hoach);
        
        // Log lý do từ chối nếu có
        if ($trang_thai === 'Từ chối' && $ly_do) {
            error_log("Kế hoạch $ma_ke_hoach bị từ chối: $ly_do");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Lỗi duyệt kế hoạch: " . $e->getMessage());
        return false;
    }
}

// Hàm mới: Lấy danh sách lịch chiếu nhóm theo mã kế hoạch để duyệt
function lc_list_grouped_for_approval($filter = 'cho_duyet') {
    $where_clause = "";
    $params = [];
    
    // Lọc theo trạng thái - sử dụng tên chính xác trong DB
    if ($filter === 'cho_duyet') {
        $where_clause = "WHERE lc.trang_thai_duyet = 'Chờ duyệt'";
    } elseif ($filter === 'da_duyet') {
        $where_clause = "WHERE lc.trang_thai_duyet = 'Đã duyệt'";
    } elseif ($filter === 'tu_choi') {
        $where_clause = "WHERE lc.trang_thai_duyet = 'Từ chối'";
    }
    
    $sql = "SELECT 
                lc.ma_ke_hoach,
                lc.id_phim,
                lc.id_rap,
                lc.trang_thai_duyet,
                lc.ghi_chu,
                lc.nguoi_tao,
                lc.ngay_tao,
                MIN(lc.ngay_chieu) as tu_ngay,
                MAX(lc.ngay_chieu) as den_ngay,
                COUNT(lc.id) as so_ngay_chieu,
                p.tieu_de as ten_phim,
                p.thoi_luong_phim,
                p.img,
                lp.name as ten_loai,
                r.ten_rap,
                tk.name as nguoi_tao_ten,
                GROUP_CONCAT(DISTINCT DATE_FORMAT(lc.ngay_chieu, '%d/%m') ORDER BY lc.ngay_chieu SEPARATOR ', ') as danh_sach_ngay
            FROM lichchieu lc
            LEFT JOIN phim p ON lc.id_phim = p.id
            LEFT JOIN loaiphim lp ON p.id_loai = lp.id
            LEFT JOIN rap_chieu r ON lc.id_rap = r.id
            LEFT JOIN taikhoan tk ON lc.nguoi_tao = tk.id
            $where_clause
            AND lc.ma_ke_hoach IS NOT NULL
            GROUP BY lc.ma_ke_hoach
            ORDER BY lc.ngay_tao DESC, p.tieu_de";
    
    try {
        // Không dùng ...$params khi mảng rỗng
        $result = pdo_query($sql);
        // Debug log
        error_log("lc_list_grouped_for_approval: Filter=$filter, SQL=$sql, Results=" . count($result));
        return $result;
    } catch (Exception $e) {
        error_log("Lỗi lấy danh sách kế hoạch: " . $e->getMessage());
        return [];
    }
}
