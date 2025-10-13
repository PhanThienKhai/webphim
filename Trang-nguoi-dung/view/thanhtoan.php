<?php

include 'view/search.php';

// Get total price from session (seat price + combo price)
$gia_total = isset($_SESSION['tong']['gia_ghe']) ? $_SESSION['tong']['gia_ghe'] : 0;
$gia_goc = $gia_total; // Lưu giá gốc

// Xử lý mã khuyến mãi nếu có
$ma_giam_gia = '';
$giam_gia = 0;
$ten_km = '';
$error_km = '';

// Xử lý hủy mã khuyến mãi
if (isset($_POST['huy_ma'])) {
    unset($_SESSION['tong']['ma_khuyen_mai']);
    unset($_SESSION['tong']['giam_gia']);
    unset($_SESSION['tong']['gia_sau_giam']);
}

if (isset($_POST['ap_dung_ma']) && !empty($_POST['ma_khuyen_mai'])) {
    require_once __DIR__ . '/../../Trang-admin/model/khuyenmai.php';
    
    $ma_code = trim($_POST['ma_khuyen_mai']);
    $id_rap = $_SESSION['tong']['id_rap'] ?? null;
    
    // Tìm mã khuyến mãi
    $km = km_find_by_code($ma_code);
    
    if ($km) {
        // Kiểm tra mã có thuộc rạp này không
        if ($km['id_rap'] === null || $km['id_rap'] == $id_rap) {
            $ma_giam_gia = $ma_code;
            $ten_km = $km['ten_khuyen_mai'];
            $giam_gia = km_calculate_discount($km, $gia_total);
            
            // Cập nhật giá sau giảm
            $gia_total = max(0, $gia_total - $giam_gia);
            
            // Lưu vào session
            $_SESSION['tong']['ma_khuyen_mai'] = $ma_code;
            $_SESSION['tong']['giam_gia'] = $giam_gia;
            $_SESSION['tong']['gia_sau_giam'] = $gia_total;
        } else {
            $error_km = 'Mã khuyến mãi không áp dụng cho rạp này!';
        }
    } else {
        $error_km = 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn!';
    }
} elseif (isset($_SESSION['tong']['giam_gia'])) {
    // Lấy thông tin giảm giá từ session nếu đã áp dụng trước đó
    $giam_gia = $_SESSION['tong']['giam_gia'];
    $ma_giam_gia = $_SESSION['tong']['ma_khuyen_mai'] ?? '';
    $gia_total = $_SESSION['tong']['gia_sau_giam'] ?? $gia_total;
}

$gia = number_format($gia_total, 0, ',', '.');

?>

<style>
    /* Cải thiện font và giao diện */
    .checkout-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    }
    
    .page-heading {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #667eea;
    }
    
    .book-result {
        list-style: none;
        padding: 0;
    }
    
    .book-result__item {
        padding: 12px 0;
        font-size: 16px;
        color: #34495e;
        border-bottom: 1px solid #ecf0f1;
    }
    
    .book-result__count {
        float: right;
        font-weight: 600;
        color: #667eea;
    }
    
    /* Style cho ô nhập mã khuyến mãi */
    .promo-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px;
        border-radius: 12px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .promo-title {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .promo-input-group {
        display: flex;
        gap: 10px;
    }
    
    .promo-input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid white;
        border-radius: 8px;
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 600;
        outline: none;
        transition: all 0.3s;
    }
    
    .promo-input:focus {
        border-color: #ffd564;
        box-shadow: 0 0 0 3px rgba(255, 213, 100, 0.3);
    }
    
    .promo-btn {
        padding: 12px 30px;
        background: white;
        color: #667eea;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 16px;
    }
    
    .promo-btn:hover {
        background: #ffd564;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .promo-error {
        color: #ff6b6b;
        background: white;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .promo-success {
        color: #51cf66;
        background: white;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 10px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .price-breakdown {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 16px;
    }
    
    .price-row.total {
        border-top: 2px solid #dee2e6;
        margin-top: 10px;
        padding-top: 15px;
        font-size: 20px;
        font-weight: bold;
        color: #dc3545;
    }
    
    .discount-row {
        color: #51cf66;
        font-weight: 600;
    }
</style>
<!-- Main content -->

<section class="container">
    <div class="order-container">
        <div class="order">
            <img class="order__images" alt='' src="images/tickets.png">
            <p class="order__title">Book a ticket <br><span class="order__descript">Tận Hưởng Thời Gian Xem Phim Vui Vẻ</span></p>
        </div>
    </div>
    <div class="order-step-area">
        <div class="order-step first--step order-step--disable ">1. Lịch Chiếu &amp; Thời gian</div>
        <div class="order-step second--step order-step--disable">2. Chọn ghế</div>
        <div class="order-step third--step">3. Thanh Toán </div>
    </div>
    <form action="" method="post">
    <div class="col-sm-12">
        <div class="checkout-wrapper">
            <h2 class="page-heading">Thông tin đặt vé</h2>
            <ul class="book-result">
                <li class="book-result__item">Phim: <span class="book-result__count booking-cost"><?php echo $_SESSION['tong']['tieu_de'] ?></span></li>
                
                <li class="book-result__item">🏢 Rạp chiếu: <span class="book-result__count booking-cost"><?php echo isset($_SESSION['tong']['ten_rap']) ? $_SESSION['tong']['ten_rap'] : 'N/A' ?></span></li>
                
                <li class="book-result__item">📍 Địa chỉ rạp: <span class="book-result__count booking-cost"><?php echo isset($_SESSION['tong']['dia_chi_rap']) ? $_SESSION['tong']['dia_chi_rap'] : 'N/A' ?></span></li>
                
                <li class="book-result__item">🚪 Phòng chiếu: <span class="book-result__count booking-cost"><?php echo isset($_SESSION['tong']['ten_phong']) ? $_SESSION['tong']['ten_phong'] : 'N/A' ?></span></li>

                <li class="book-result__item">📅 Ngày chiếu: <span class="book-result__count booking-cost"><?php echo $_SESSION['tong']['ngay_chieu'] ?></span></li>
                
                <li class="book-result__item">⏰ Khung giờ chiếu: <span class="book-result__count booking-cost"><?php echo $_SESSION['tong']['thoi_gian_chieu'] ?></span></li>
                <br>
                <hr>
                <li class="book-result__item">🪑 Số ghế: <span class="book-result__count booking-cost"><?php
                        if (isset($ten_ghe['ghe'])) {
                            $ghes = $ten_ghe['ghe'];
                            echo '<span class="choosen-plac">' . implode(', ', $ghes) . '</span>';

                            foreach ($ghes as $ghe) {
                                echo '<input type="hidden" name="ten_ghe[]" value="' . $ghe . '">';
                            }
                        }
                        ?>
</span></li>
                <li class="book-result__item">🍿 Combo: <span class="book-result__count booking-cost"><span class="check-doan"> <?php
                            if (isset($ten_doan['doan'])) {
                                foreach ($ten_doan['doan'] as $doan) {
                                    echo  '<span class="check-doan">' . $doan . '</span>';

                                }
                            } else {
                            } ?></span>
</span></li>
            </ul>
            
            <!-- Mã khuyến mãi -->
            <form method="post" style="margin: 0;">
                <div class="promo-section">
                    <div class="promo-title">
                        🎁 Bạn có mã khuyến mãi?
                    </div>
                    <div class="promo-input-group">
                        <input type="text" 
                               name="ma_khuyen_mai" 
                               class="promo-input" 
                               placeholder="Nhập mã khuyến mãi" 
                               value="<?php echo htmlspecialchars($ma_giam_gia); ?>"
                               <?php echo $ma_giam_gia ? 'readonly' : ''; ?>>
                        <?php if (!$ma_giam_gia): ?>
                            <button type="submit" name="ap_dung_ma" class="promo-btn">Áp dụng</button>
                        <?php else: ?>
                            <button type="submit" name="huy_ma" class="promo-btn" style="background: #ff6b6b;">Hủy mã</button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($error_km): ?>
                        <div class="promo-error">❌ <?php echo $error_km; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($ma_giam_gia && $giam_gia > 0): ?>
                        <div class="promo-success">
                            ✅ Đã áp dụng mã: <?php echo strtoupper($ma_giam_gia); ?>
                            <?php if ($ten_km): ?>
                                (<?php echo $ten_km; ?>)
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Chi tiết giá -->
            <div class="price-breakdown">
                <div class="price-row">
                    <span>Tổng tiền vé:</span>
                    <span><?php echo number_format($gia_goc, 0, ',', '.'); ?> VND</span>
                </div>
                
                <?php if ($giam_gia > 0): ?>
                <div class="price-row discount-row">
                    <span>🎉 Giảm giá:</span>
                    <span>- <?php echo number_format($giam_gia, 0, ',', '.'); ?> VND</span>
                </div>
                <?php endif; ?>
                
                <div class="price-row total">
                    <span>💰 Tổng thanh toán:</span>
                    <span><?php echo $gia; ?> VND</span>
                </div>
            </div>
    </form>

            <h2 class="page-heading">Chọn hình thức thanh toán</h2>
            <form action="" method="post">
                <!-- Hidden fields to pass data -->
                <input type="hidden" name="gia_thanh_toan" value="<?php echo $gia_total; ?>">
                <?php if ($ma_giam_gia): ?>
                    <input type="hidden" name="ma_khuyen_mai_applied" value="<?php echo $ma_giam_gia; ?>">
                    <input type="hidden" name="giam_gia_applied" value="<?php echo $giam_gia; ?>">
                <?php endif; ?>
                
            <div class="payment">
                <ul >
                    <li>  <a href="view/momo/xuly_momo_atm.php" class="payment__item">
                    <img alt='' src="images/payment/momo.jpg" style="width: 70px; border-radius: 8px;";>
                  <label for="" class="tt">MOMO ATM</label> </li>
                </a>
                </ul>
            </div>
            </form>

        </div>

    </div>

</section>

</form>
<div class="clearfix"></div>


<div class="clearfix"></div>

