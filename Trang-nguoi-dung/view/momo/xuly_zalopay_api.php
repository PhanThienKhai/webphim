<?php
/**
 * ZaloPay PRODUCTION API Integration
 * Xử lý thanh toán thật qua ZaloPay Gateway
 * Documentation: https://docs.zalopay.vn/v2/start/
 */

session_start();
header('Content-Type: text/html; charset=UTF-8');

// Load configuration từ xuly_zalopay.php
require_once 'xuly_zalopay.php';

// Kiểm tra chế độ PRODUCTION
if (ZALOPAY_MODE !== 'PRODUCTION') {
    die('API này chỉ hoạt động ở chế độ PRODUCTION. Vui lòng đổi ZALOPAY_MODE trong xuly_zalopay.php');
}

// Lấy số tiền từ session
$tong_tien = isset($_SESSION['tong_tien']) ? (int)$_SESSION['tong_tien'] : 0;

if ($tong_tien < 10000) {
    die('Số tiền không hợp lệ. Tối thiểu 10,000 VND');
}

/**
 * Hàm gửi request POST đến ZaloPay API
 */
function execPostRequest($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Content-Length: ' . strlen($data)
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    // Tắt SSL verify cho localhost (chỉ dev, production phải bật)
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

try {
    // 1. Tạo mã đơn hàng (app_trans_id)
    $transID = date("ymd") . "_" . time(); // Format: yymmdd_timestamp
    
    // 2. Tạo embeddata (dữ liệu bổ sung)
    $embeddata = json_encode([
        'redirecturl' => 'https://' . $_SERVER['HTTP_HOST'] . '/webphim/Trang-nguoi-dung/index.php?act=xacnhan',
        'merchantinfo' => 'Cinema Booking',
        'promotioninfo' => ''
    ]);
    
    // 3. Tạo item (danh sách sản phẩm)
    $items = json_encode([[
        'itemid' => 've_phim',
        'itemname' => 'Vé xem phim',
        'itemprice' => $tong_tien,
        'itemquantity' => 1
    ]]);
    
    // 4. Tạo order data array
    $order = [
        'app_id' => $ZALOPAY_APP_ID,
        'app_user' => isset($_SESSION['id']) ? 'user_' . $_SESSION['id'] : 'guest_' . time(),
        'app_time' => round(microtime(true) * 1000), // milliseconds
        'app_trans_id' => $transID,
        'amount' => $tong_tien,
        'item' => $items,
        'embed_data' => $embeddata,
        'description' => 'Thanh toán vé xem phim - ' . date('d/m/Y H:i'),
        'bank_code' => '', // Empty = Hiển thị tất cả phương thức
        'callback_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/webphim/Trang-nguoi-dung/index.php?act=zalopay_callback'
    ];
    
    // 5. Tạo MAC signature (HMAC SHA256)
    // Format: app_id|app_trans_id|app_user|amount|app_time|embed_data|item
    $data = $order["app_id"] . "|" . 
            $order["app_trans_id"] . "|" . 
            $order["app_user"] . "|" . 
            $order["amount"] . "|" . 
            $order["app_time"] . "|" . 
            $order["embed_data"] . "|" . 
            $order["item"];
    
    $order["mac"] = hash_hmac("sha256", $data, $ZALOPAY_KEY1);
    
    // 6. Gửi request đến ZaloPay
    $result = execPostRequest($ZALOPAY_ENDPOINT, http_build_query($order));
    $result = json_decode($result, true);
    
    // 7. Xử lý response
    if ($result['return_code'] == 1) {
        // Thành công - Lưu thông tin và redirect
        $_SESSION['zalopay_trans_id'] = $transID;
        $_SESSION['zalopay_zp_trans_id'] = $result['zp_trans_token'];
        
        // Redirect đến trang thanh toán ZaloPay
        header('Location: ' . $result['order_url']);
        exit;
        
    } else {
        // Lỗi từ ZaloPay API
        throw new Exception($result['return_message'] ?? 'Không thể kết nối ZaloPay');
    }
    
} catch (Exception $e) {
    // Hiển thị trang lỗi với style đẹp
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi thanh toán - ZaloPay</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }
            
            .error-container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 500px;
                width: 100%;
                padding: 40px;
                text-align: center;
            }
            
            .error-icon {
                width: 80px;
                height: 80px;
                background: #fee;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 40px;
            }
            
            h1 {
                color: #e53e3e;
                font-size: 24px;
                margin-bottom: 10px;
            }
            
            .error-message {
                color: #666;
                margin: 20px 0;
                line-height: 1.6;
                background: #f7fafc;
                padding: 15px;
                border-radius: 10px;
                border-left: 4px solid #e53e3e;
            }
            
            .error-details {
                background: #fff5f5;
                padding: 15px;
                border-radius: 10px;
                margin: 20px 0;
                text-align: left;
                font-size: 14px;
                color: #c53030;
            }
            
            .btn-group {
                display: flex;
                gap: 10px;
                margin-top: 30px;
            }
            
            .btn {
                flex: 1;
                padding: 15px 30px;
                border: none;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
            }
            
            .btn-back {
                background: #e2e8f0;
                color: #2d3748;
            }
            
            .btn-back:hover {
                background: #cbd5e0;
                transform: translateY(-2px);
            }
            
            .btn-retry {
                background: linear-gradient(135deg, #0068FF 0%, #00A7FF 100%);
                color: white;
            }
            
            .btn-retry:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0, 104, 255, 0.4);
            }
            
            .help-text {
                margin-top: 20px;
                font-size: 14px;
                color: #718096;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1>Lỗi thanh toán</h1>
            <div class="error-message">
                Không thể kết nối với cổng thanh toán ZaloPay. Vui lòng thử lại sau.
            </div>
            
            <div class="error-details">
                <strong>Chi tiết lỗi:</strong><br>
                <?= htmlspecialchars($e->getMessage()) ?>
            </div>
            
            <div class="btn-group">
                <a href="../../index.php?act=thanhtoan" class="btn btn-back">
                    Quay lại
                </a>
                <a href="xuly_zalopay.php" class="btn btn-retry">
                    Thử lại
                </a>
            </div>
            
            <div class="help-text">
                Nếu lỗi vẫn tiếp tục, vui lòng liên hệ bộ phận hỗ trợ:<br>
                <strong>Hotline: 1900 5555 47</strong> | <strong>Email: support@zalopay.vn</strong>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>