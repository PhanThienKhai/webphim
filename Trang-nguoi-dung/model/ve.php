<?php
function them_ve($gia_ghe, $ngay_tt, $ghe, $id_user, $id_kgc, $id_hd, $id_lc, $id_phim, $combo, $id_rap = null) 
{
    $sql = 'INSERT INTO `ve` (`price`, `ngay_dat`, `ghe`, `id_tk`, `id_thoi_gian_chieu`, `id_hd`, `id_ngay_chieu`, `id_phim`, `combo`, `id_rap`) VALUES (?,?,?,?,?,?,?,?,?,?)';

    // Kiểm tra xem combo có tồn tại không
    $comboValue = ($combo !== null) ? $combo : '';  // Nếu không có combo, gán giá trị mặc định

    return pdo_execute_return_interlastid($sql, $gia_ghe, $ngay_tt, $ghe, $id_user, $id_kgc, $id_hd, $id_lc, $id_phim, $comboValue, $id_rap);
}


function  dich_vu_insert($id_ve,$combo){
    $sql = 'INSERT INTO `dich_vu` (`id`, `combo`, `id_ve`) VALUES (NULL,?,?)';
    pdo_execute($sql,$combo,$id_ve);
}


function load_ve($id){
        $sql = "SELECT v.id, phim.tieu_de, lichchieu.ngay_chieu, khung_gio_chieu.thoi_gian_chieu, taikhoan.name, v.ghe, v.price, v.ngay_dat, v.combo, v.trang_thai, phongchieu.name as tenphong, rap_chieu.ten_rap, rap_chieu.dia_chi as dia_chi_rap
    FROM ve v
    LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
    LEFT JOIN taikhoan ON taikhoan.id = v.id_tk
    LEFT JOIN phim ON phim.id = v.id_phim
    LEFT JOIN lichchieu ON lichchieu.id = v.id_ngay_chieu
    LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
    LEFT JOIN rap_chieu ON rap_chieu.id = v.id_rap
    WHERE v.trang_thai IN (0, 1, 2, 3, 4)  -- Lấy cả vé chưa thanh toán (0) và đã thanh toán (1,2,3,4)
    AND v.id_tk = ?
    ORDER BY v.id DESC";
        $re = pdo_query($sql, $id);
        return $re;
}


function capnhat_tt(){
    $sql = "UPDATE ve SET trang_thai = '1' WHERE id_ve = ?";
    pdo_execute($sql);
}

function trangthai_ve($id)
{
    $sql = 'UPDATE ve SET trang_thai = 1 WHERE id_hd = ?';
    pdo_execute($sql, $id);
}
function trangthai_hd($id)
{
    $sql = 'UPDATE hoa_don SET trang_thai = 1 WHERE id = ?';
    pdo_execute($sql, $id);
}

function load_ve_tt($id)
{
        $sql = "SELECT h.thanh_tien, ve.id, h.ngay_tt, taikhoan.name, khung_gio_chieu.thoi_gian_chieu, lichchieu.ngay_chieu, phim.tieu_de, ve.ghe, ve.combo, phongchieu.name as tenphong, rap_chieu.ten_rap, rap_chieu.dia_chi as dia_chi_rap
    FROM hoa_don h
    JOIN ve ON ve.id_hd = h.id 
    JOIN taikhoan ON ve.id_tk = taikhoan.id 
    JOIN khung_gio_chieu ON khung_gio_chieu.id = ve.id_thoi_gian_chieu
    JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu 
    JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
    JOIN phim ON phim.id = lichchieu.id_phim
    LEFT JOIN rap_chieu ON rap_chieu.id = ve.id_rap
    WHERE h.id = ".$id;


    return pdo_query_one($sql);
}

function khoa_ghe($id_kgc, $id_lc, $id_phim)
{
    // Khóa ghế cho cả vé chưa thanh toán (0) và đã thanh toán (1)
    // Trạng thái 3 = đã hủy, 4 = hết hạn (không khóa)
    $sql = "SELECT ve.ghe FROM ve 
    JOIN khung_gio_chieu ON ve.id_thoi_gian_chieu = khung_gio_chieu.id 
    JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu  
    WHERE ve.trang_thai IN (0, 1, 2) 
      AND ve.id_thoi_gian_chieu = ? 
      AND lichchieu.id = ? 
      AND lichchieu.id_phim = ?";
    return pdo_query($sql, $id_kgc, $id_lc, $id_phim);
}


function loadone_vephim($id){
    $sql="SELECT v.id, v.id_tk, phim.tieu_de, lichchieu.ngay_chieu, v.price, v.ngay_dat, v.ghe, v.combo, taikhoan.name, khung_gio_chieu.thoi_gian_chieu, v.id_hd, v.trang_thai, phongchieu.name as tenphong, rap_chieu.ten_rap, rap_chieu.dia_chi as dia_chi_rap
    FROM ve v
    LEFT JOIN taikhoan ON taikhoan.id = v.id_tk
    LEFT JOIN khung_gio_chieu ON khung_gio_chieu.id = v.id_thoi_gian_chieu
    LEFT JOIN phim ON phim.id = v.id_phim
    LEFT JOIN lichchieu ON lichchieu.id = khung_gio_chieu.id_lich_chieu
    LEFT JOIN phongchieu ON phongchieu.id = khung_gio_chieu.id_phong
    LEFT JOIN rap_chieu ON rap_chieu.id = v.id_rap
    WHERE v.id = ".$id;

    $re =pdo_query_one($sql);
    return $re ;

}
function huy_vephim($id) {
    // Lấy thông tin vé trước khi hủy
    $sql_get = "SELECT price, id_tk FROM ve WHERE id = ?";
    $ve = pdo_query_one($sql_get, $id);
    
    if (!$ve) {
        return ['success' => false, 'message' => 'Vé không tồn tại'];
    }
    
    // Kiểm tra xem có thể hủy không
    $can_cancel = can_cancel_or_exchange_ticket($id);
    if (!$can_cancel['can_cancel']) {
        return ['success' => false, 'message' => $can_cancel['message']];
    }
    
    // Update trạng thái vé = 3 (Đã hủy)
    $sql_update = "UPDATE ve SET trang_thai = 3 WHERE id = ?";
    pdo_execute($sql_update, $id);
    
    // Hoàn điểm: Tính điểm từ giá vé (0.01 điểm/VND)
    $points_refund = floor($ve['price'] * 0.01);
    
    if ($points_refund > 0 && $ve['id_tk']) {
        // Cộng điểm vào taikhoan - Cập nhật CẢ 2 cột
        $sql_points = "UPDATE taikhoan SET 
                       id_diem = COALESCE(id_diem, 0) + ?, 
                       diem_tich_luy = COALESCE(diem_tich_luy, 0) + ?,
                       tong_diem_tich_luy = COALESCE(tong_diem_tich_luy, 0) + ?
                       WHERE id = ?";
        pdo_execute($sql_points, $points_refund, $points_refund, $points_refund, $ve['id_tk']);
        
        // Ghi lịch sử điểm - Dùng 'cong' vì đây là hoàn điểm (cộng, không phải trừ)
        $sql_history = "INSERT INTO lich_su_diem (id_tk, loai_giao_dich, so_diem, ly_do, id_ve, nguoi_thuc_hien) 
                        VALUES (?, 'cong', ?, ?, ?, 'system')";
        $ly_do = "Hoàn điểm do hủy vé (Mã vé: VE{$id})";
        pdo_execute($sql_history, $ve['id_tk'], $points_refund, $ly_do, $id);
    }
    
    return ['success' => true, 'message' => 'Hủy vé thành công! Đã hoàn ' . $points_refund . ' điểm', 'points_refund' => $points_refund];
}


function gui_mail_ve($load_ve_tt) {
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phanthienkhai2901@gmail.com';
        $mail->Password   = 'nvyh agju zvnp nacz';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email người gửi
        $mail->setFrom('phanthienkhai2901@gmail.com', 'Galaxy Studio');
        $mail->addAddress($_SESSION['user']['email']);

        // Nội dung
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for booking movie tickets';
        $mail->Body    = 'Xác nhận Đặt Vé Xem Phim Thành Công <br><hr>

                               Chào '.$_SESSION['user']['name'].',

                            Chúng tôi xin chân thành cảm ơn bạn đã chọn Galaxy Studio để trải nghiệm bộ phim tuyệt vời. Chúc mừng! Đơn đặt vé của bạn đã được xác nhận thành công. 
                             Dưới đây là thông tin chi tiết về đơn đặt vé của bạn:<br>
                             - Mã đặt vé: ' . $load_ve_tt['id'] . ' <br>
                             - Tên phim: ' . $load_ve_tt['tieu_de'] . '<br>
                             - Rạp : Galaxy Studio Gò Vấp <br>
                             - Phòng: ' . $load_ve_tt['tenphong'] . '<br>
                             - Xuất chiếu: ' . $load_ve_tt['thoi_gian_chieu'] . ' --- ' . $load_ve_tt['ngay_chieu'] . '<br>
                             - Ghế ngồi: ' . $load_ve_tt['ghe'] . '<br>
                             - Combo: ' . $load_ve_tt['combo'] . '<br>
                             - Ngày thanh toán: ' . $load_ve_tt['ngay_tt'] . '<br>
                             - Thành tiền: ' . number_format($load_ve_tt['thanh_tien']) . ' VND<br>
                             <hr>
                              Lưu ý quan trọng:<br>

                               Hãy đảm bảo bạn đến sớm trước thời gian chiếu để có đủ thời gian kiểm tra vé và chọn ghế.<br>
                                 Mã đặt vé trên có thể được sử dụng để kiểm tra thông tin đặt vé tại quầy vé hoặc máy tự động tại rạp.<br>
                                 Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ gì thêm, vui lòng liên hệ với chúng tôi qua số điện thoại 0384104942 hoặc email Phanthienkhai111@gmail.com.<br>
                                  Chúng tôi rất mong đợi sự xuất hiện của bạn và hy vọng bạn sẽ có một trải nghiệm thú vị tại rạp phim của chúng tôi.<br>

                                   Trân trọng,<br>
                                    Galaxy Studio<br>
        ';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Kiểm tra xem vé có được phép hủy/đổi không
 * @param $id Mã vé
 * @return array ['can_cancel' => bool, 'remaining_hours' => int, 'message' => string]
 */
function can_cancel_or_exchange_ticket($id) {
    $sql = "SELECT 
                CONCAT(lc.ngay_chieu, ' ', kgc.thoi_gian_chieu) as gio_chieu,
                v.trang_thai
            FROM ve v
            LEFT JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            LEFT JOIN lichchieu lc ON lc.id = kgc.id_lich_chieu
            WHERE v.id = ?
            LIMIT 1";
    
    $result = pdo_query_one($sql, $id);
    
    if (!$result) {
        return ['can_cancel' => false, 'remaining_hours' => 0, 'message' => 'Vé không tồn tại'];
    }
    
    // Kiểm tra trạng thái vé
    $trang_thai = $result['trang_thai'];
    if ($trang_thai == 2) { // Đã dùng
        return ['can_cancel' => false, 'remaining_hours' => 0, 'message' => 'Vé đã được sử dụng, không thể hủy/đổi'];
    }
    if ($trang_thai == 3) { // Đã hủy
        return ['can_cancel' => false, 'remaining_hours' => 0, 'message' => 'Vé đã hủy'];
    }
    if ($trang_thai == 4) { // Hết hạn
        return ['can_cancel' => false, 'remaining_hours' => 0, 'message' => 'Vé đã hết hạn'];
    }
    
    // Kiểm tra thời gian chiếu
    $gio_chieu = $result['gio_chieu'];
    if (empty($gio_chieu)) {
        return ['can_cancel' => true, 'remaining_hours' => 999, 'message' => 'Thời gian chiếu chưa được cập nhật'];
    }
    
    try {
        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        $gio_chieu_obj = new DateTime($gio_chieu, new DateTimeZone('Asia/Ho_Chi_Minh'));
        
        // Tính khoảng cách (hours)
        $interval = $now->diff($gio_chieu_obj);
        $remaining_hours = $interval->days * 24 + $interval->h;
        
        // Nếu giờ chiếu đã qua, không thể hủy
        if ($now > $gio_chieu_obj) {
            return ['can_cancel' => false, 'remaining_hours' => 0, 'message' => 'Giờ chiếu đã qua'];
        }
        
        // Nếu còn >= 4 tiếng, có thể hủy/đổi
        if ($remaining_hours >= 4) {
            return [
                'can_cancel' => true, 
                'remaining_hours' => $remaining_hours, 
                'message' => "Bạn còn {$remaining_hours} giờ để hủy/đổi vé"
            ];
        } else {
            return [
                'can_cancel' => false, 
                'remaining_hours' => $remaining_hours, 
                'message' => "Không thể hủy/đổi vé. Phải hủy trước giờ chiếu ít nhất 4 tiếng"
            ];
        }
    } catch (Exception $e) {
        return ['can_cancel' => true, 'remaining_hours' => 999, 'message' => 'Không thể xác định thời gian chiếu'];
    }
}

