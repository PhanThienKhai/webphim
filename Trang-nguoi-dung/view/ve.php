<!-- Main content -->
<?php 
// Reload session user nếu vừa thanh toán thành công
if (isset($_GET['thanh_toan']) && $_GET['thanh_toan'] == 'ok') {
    if (isset($_SESSION['user'])) {
        $user_updated = pdo_query_one("SELECT * FROM taikhoan WHERE id = ?", $_SESSION['user']['id']);
        if ($user_updated) {
            $_SESSION['user'] = $user_updated;
        }
    }
}

// Load QR config if exists
$qr_host = $_SERVER['HTTP_HOST'];
if (file_exists(__DIR__ . '/../config/qr_config.php')) {
    include __DIR__ . '/../config/qr_config.php';
    if (!empty(QR_SERVER_IP)) {
        $qr_host = QR_SERVER_IP;
        if (QR_SERVER_PORT != 80) {
            $qr_host .= ':' . QR_SERVER_PORT;
        }
    } else {
        // Auto-detect IP
        $ip = gethostbyname(gethostname());
        if ($ip !== gethostname() && strpos($ip, '127.') !== 0) {
            $qr_host = $ip;
        }
    }
} else {
    // Fallback function to get server IP address for LAN access
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        $ip = gethostbyname(gethostname());
        if ($ip !== gethostname() && strpos($ip, '127.') !== 0) {
            $qr_host = $ip;
        }
    }
}

include "view/search.php"; 
?>
<form action="index.php?act=huy_ve" method="post">
<section class="container">
    <div class="order-container">
        <?php
        // Kiểm tra xem biến $load_ve có tồn tại hay không
            // Nếu tồn tại, hiển thị danh sách vé đã mua
            echo "<h2>DANH SÁCH VÉ ĐÃ MUA</h2>";
            foreach ($load_ve as $ve) {
                extract($ve);
                
                // Kiểm tra thời gian hủy vé
                $ticket_check = can_cancel_or_exchange_ticket($id);
                $can_cancel = $ticket_check['can_cancel'];
                
                switch ($trang_thai) {
                    case 1:
                        $thong_bao = 'Đã thanh toán';
                        $huy_ve_style = '';
                        break;
                    case 2:
                        $thong_bao = 'Đã dùng';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    case 3:
                        $thong_bao = 'Đã hủy';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    case 4:
                        $thong_bao = 'Hết hạn';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    default:
                        $thong_bao = 'Trạng thái không xác định';
                        $huy_ve_style = '';
                }
                $linkct = "index.php?act=ctve&id=".$id;
                
                // Lấy thông tin rạp, nếu không có thì dùng default
                $ten_rap_hienthi = !empty($ten_rap) ? $ten_rap : 'Galaxy Studio Gò Vấp';
                $dia_chi_hienthi = !empty($dia_chi_rap) ? $dia_chi_rap : 'Địa chỉ chưa cập nhật';
                
                echo '<div class="ticket">
                        <div class="ticket-position">
                            <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text">Galaxy Studio</div> </div>
                            <div class="ticket__inner">
                            
                                <div class="ticket-secondary">
                                    <span class="ticket__item">Mã vé <strong class="ticket__number">' . $id . '</strong></span>
                                    <span class="ticket__item ticket__date">' . $ngay_chieu . '</span>
                                    <span class="ticket__item ticket__time">' . $thoi_gian_chieu . '</span>
                                    <span class="ticket__item">🏢 Rạp : <span class="ticket__cinema">' . $ten_rap_hienthi . '</span></span>
                                    <span class="ticket__item">📍 Địa chỉ : <span class="ticket__cinema">' . $dia_chi_hienthi . '</span></span>
                                    <span class="ticket__item">🚪 Phòng : <strong class="ticket__number">' . $tenphong . '</strong></span>
                                    <span class="ticket__item">👤 Người đặt: <span class="ticket__cinema">' . $name . '</span></span>
                                    <span class="ticket__item">🕐 Thời gian đặt: <span class="ticket__hall">' . $ngay_dat . '</span></span>
                                    <span class="ticket__item ticket__price" style="margin-top: 5px">💰 Giá: <strong class="ticket__cost">' . number_format($price) . ' vnđ</strong></span>
                                </div>
                                <div class="ticket-primery" style="position: relative;">
                                    <div style="position: absolute; top: 86px; right: -1px; width: 107px; height: 107px; background: #fff; border: 2px solid #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <img src="view/qr.php?data=' . urlencode("http://" . $qr_host . "/webphim/Trang-nguoi-dung/quete.php?id=" . $id) . '&t=' . time() . '" alt="QR Code" style="width: 115px; height: 115px; object-fit: contain;" />
                                    </div>
<span class="ticket__item ticket__item--primery ticket__film" style="display:flex;"> <strong class="ticket__movie" >PHIM : ' . $tieu_de . '</strong></span>                                    <span class="ticket__item ticket__item--primery">🪑 Ghế: <span class="ticket__place">' . $ghe . '</span></span>
                                    <span class="ticket__item ticket__item--primery">🍿 Combo: <span class="ticket__place">' . $combo . '</span></span>
                                </div>
                            </div>
                            <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">Galaxy Studio</div></div>
                        </div>
                        <div>
                        <a href="'.$linkct.'"><input type="button" value="Chi tiết vé" ></a>
                        <span>Trạng thái : '.$thong_bao.'</span>';
                        
                        // Hiển thị thông báo về khả năng hủy vé
                        if ($trang_thai == 1 && !$can_cancel) { // Đã thanh toán nhưng không thể hủy
                            echo '<br><span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">⏰ ' . $ticket_check['message'] . '</span>';
                        } elseif ($trang_thai == 1 && $can_cancel) {
                            echo '<br><span style="color: #10b981; font-size: 12px; margin-top: 5px; display: block;">✓ ' . $ticket_check['message'] . '</span>';
                            // Thêm nút hủy vé
                            echo '<button type="submit" name="huy_ticket" value="'.$id.'" class="btn btn-danger" style="margin-top: 10px; padding: 8px 16px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer;">Hủy vé</button>';
                        }
                        
                        echo '
                    </div>';
            }
            echo '<h1>(*) Khi hủy vé bạn liên hệ với đội ngũ cskh của Galaxy Studio để được hoàn tiền</h1>';

        ?>
    </div>
</section>
</form>

