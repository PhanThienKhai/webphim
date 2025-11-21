<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

/**
 * FILE XỬ LÝ THANH TOÁN MOMO THẬT (PRODUCTION MODE)
 * Chỉ chạy khi MOMO_MODE = 'PRODUCTION'
 */

// Load config từ file chính
require_once 'xuly_momo_atm.php';

// Kiểm tra mode
if (!defined('MOMO_MODE') || MOMO_MODE !== 'PRODUCTION') {
    die("Lỗi: File này chỉ chạy ở chế độ PRODUCTION!");
}

/**
 * Hàm gửi POST request đến MoMo API
 */
function execPostRequest($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    // Bỏ qua SSL certificate (chỉ dùng cho test localhost)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Lỗi kết nối MoMo: " . $error);
    }
    
    curl_close($ch);
    return $result;
}

try {
    // Lấy thông tin từ session
    $movieTitle = isset($_SESSION['tong']['tieu_de']) ? $_SESSION['tong']['tieu_de'] : 'Vé phim';
    
    if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
        $amount = (int)$_SESSION['tong']['gia_sau_giam'];
    } else {
        $amount = isset($_SESSION['tong']['gia_ghe']) ? (int)$_SESSION['tong']['gia_ghe'] : 0;
    }
    
    // Validate số tiền
    if ($amount < 10000) {
        throw new Exception("Số tiền thanh toán phải tối thiểu 10,000 VND");
    }
    
    if ($amount > 50000000) {
        throw new Exception("Số tiền thanh toán vượt quá giới hạn 50,000,000 VND");
    }
    
    // Tạo orderId và requestId duy nhất
    $orderId = time() . "";
    $requestId = time() . "";
    
    // URL callback
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/webphim/Trang-nguoi-dung/index.php';
    $redirectUrl = $baseUrl . "?act=xacnhan&message=Successful.";
    $ipnUrl = $baseUrl . "?act=momo_ipn"; // IPN để MoMo gọi lại khi thanh toán xong
    
    $orderInfo = "Thanh toan ve phim: " . $movieTitle;
    $requestType = "payWithATM";
    $extraData = ""; // Có thể thêm dữ liệu tùy chọn
    
    // Tạo signature theo chuẩn MoMo
    $rawHash = "accessKey=" . $MOMO_ACCESS_KEY .
               "&amount=" . $amount .
               "&extraData=" . $extraData .
               "&ipnUrl=" . $ipnUrl .
               "&orderId=" . $orderId .
               "&orderInfo=" . $orderInfo .
               "&partnerCode=" . $MOMO_PARTNER_CODE .
               "&redirectUrl=" . $redirectUrl .
               "&requestId=" . $requestId .
               "&requestType=" . $requestType;
    
    $signature = hash_hmac("sha256", $rawHash, $MOMO_SECRET_KEY);
    
    // Chuẩn bị payload
    $data = array(
        'partnerCode' => $MOMO_PARTNER_CODE,
        'partnerName' => "Galaxy Cinema",
        'storeId' => "GalaxyCinema",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    );
    
    // Gửi request đến MoMo
    $result = execPostRequest($MOMO_ENDPOINT, json_encode($data));
    $jsonResult = json_decode($result, true);
    
    // Kiểm tra kết quả
    if (isset($jsonResult['payUrl']) && !empty($jsonResult['payUrl'])) {
        // Lưu orderId vào session để verify sau này
        $_SESSION['momo_order_id'] = $orderId;
        $_SESSION['momo_request_id'] = $requestId;
        
        // Redirect đến trang thanh toán MoMo
        header("Location: " . $jsonResult['payUrl']);
        exit;
    } else {
        // Có lỗi từ MoMo
        $errorMsg = isset($jsonResult['message']) ? $jsonResult['message'] : 'Không xác định';
        $errorCode = isset($jsonResult['resultCode']) ? $jsonResult['resultCode'] : 'N/A';
        throw new Exception("Lỗi từ MoMo API: [$errorCode] $errorMsg");
    }
    
} catch (Exception $e) {
    // Hiển thị lỗi
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi thanh toán</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f5f5;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
            }
            .error-box {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                max-width: 500px;
                text-align: center;
            }
            .error-icon {
                font-size: 60px;
                color: #dc3545;
                margin-bottom: 20px;
            }
            h2 {
                color: #dc3545;
                margin-bottom: 15px;
            }
            .error-message {
                color: #666;
                margin-bottom: 25px;
                line-height: 1.6;
            }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                transition: all 0.3s;
            }
            .btn:hover {
                background: #5568d3;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <div class="error-icon">⚠️</div>
            <h2>Thanh toán thất bại</h2>
            <div class="error-message">
                <?= htmlspecialchars($e->getMessage()) ?>
            </div>
            <a href="../../index.php?act=thanhtoan" class="btn">Thử lại</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
