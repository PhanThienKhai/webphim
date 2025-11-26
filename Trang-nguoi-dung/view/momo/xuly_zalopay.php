<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// ====================================================
// CẤU HÌNH THANH TOÁN ZALOPAY
// ====================================================
// Đổi MODE để chuyển giữa DEMO và THẬT:
// - 'DEMO': Thanh toán giả lập, không cần credentials ZaloPay
// - 'PRODUCTION': Thanh toán thật qua ZaloPay API

define('ZALOPAY_MODE', 'DEMO'); // Đổi thành 'PRODUCTION' khi có tài khoản ZaloPay Business

// ====================================================
// THÔNG TIN TÀI KHOẢN ZALOPAY (CHỈ CẦN KHI MODE = PRODUCTION)
// ====================================================
// Đăng ký tại: https://docs.zalopay.vn hoặc https://business.zalopay.vn
// Sau khi đăng ký, lấy thông tin này từ ZaloPay Business Portal

if (ZALOPAY_MODE === 'PRODUCTION') {
    // ⚠️ THAY BẰNG THÔNG TIN TÀI KHOẢN THẬT CỦA BẠN
    $ZALOPAY_ENDPOINT = "https://openapi.zalopay.vn/v2/create"; // Production endpoint
    $ZALOPAY_APP_ID = 0; // App ID từ ZaloPay Business (VD: 2553)
    $ZALOPAY_KEY1 = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // Key 1 từ ZaloPay
    $ZALOPAY_KEY2 = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // Key 2 từ ZaloPay
} else {
    // Tài khoản TEST (sandbox - chỉ cho demo)
    $ZALOPAY_ENDPOINT = "https://sb-openapi.zalopay.vn/v2/create"; // Sandbox endpoint
    $ZALOPAY_APP_ID = 2553; // App ID test
    $ZALOPAY_KEY1 = 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL'; // Key 1 test
    $ZALOPAY_KEY2 = 'kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz'; // Key 2 test
}

$movieTitle = isset($_SESSION['tong']['tieu_de']) ? $_SESSION['tong']['tieu_de'] : 'Vé phim';

// Lấy số tiền thanh toán
if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
    $amount = (int)$_SESSION['tong']['gia_sau_giam'];
} else {
    $amount = isset($_SESSION['tong']['gia_ghe']) ? (int)$_SESSION['tong']['gia_ghe'] : 0;
}

// Validate số tiền
if ($amount < 10000) {
    die("Lỗi: Số tiền thanh toán phải tối thiểu 10,000 VND");
}

// Lưu số tiền vào session để dùng sau khi thanh toán
$_SESSION['tong_tien'] = $amount;

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán ZaloPay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0068FF 0%, #00A7FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .zalopay-logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #0068FF, #00A7FF);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(0, 104, 255, 0.3);
        }
        
        h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .movie-title {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .amount {
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(135deg, #0068FF, #00A7FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 30px 0;
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
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
        }
        
        .btn-pay {
            background: linear-gradient(135deg, #0068FF, #00A7FF);
            color: white;
        }
        
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 104, 255, 0.4);
        }
        
        .btn-cancel {
            background: #e9ecef;
            color: #6c757d;
        }
        
        .btn-cancel:hover {
            background: #dee2e6;
        }
        
        .notice {
            background: #cfe2ff;
            border: 1px solid #0068FF;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
            color: #004085;
        }
        
        .payment-methods {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .method-badge {
            padding: 5px 12px;
            background: #e3f2fd;
            border-radius: 20px;
            font-size: 12px;
            color: #0068FF;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="zalopay-logo">Z</div>
        <h2>Thanh toán qua ZaloPay</h2>
        <p class="movie-title"><?= htmlspecialchars($movieTitle) ?></p>
        
        <div class="amount"><?= number_format($amount) ?> ₫</div>
        
        <div class="info-box">
            <div class="info-item">
                <span class="info-label">🎬 Phim</span>
                <span class="info-value"><?= htmlspecialchars($movieTitle) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">🪑 Ghế</span>
                <span class="info-value"><?= isset($_SESSION['tong']['ghe']) ? implode(', ', $_SESSION['tong']['ghe']) : 'N/A' ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">📅 Ngày chiếu</span>
                <span class="info-value"><?= isset($_SESSION['tong']['ngay_chieu']) ? $_SESSION['tong']['ngay_chieu'] : 'N/A' ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">⏰ Giờ chiếu</span>
                <span class="info-value"><?= isset($_SESSION['tong']['thoi_gian_chieu']) ? $_SESSION['tong']['thoi_gian_chieu'] : 'N/A' ?></span>
            </div>
        </div>
        
        <div class="payment-methods">
            <span class="method-badge">💳 Thẻ ATM</span>
            <span class="method-badge">💰 Ví ZaloPay</span>
            <span class="method-badge">🏦 Ngân hàng</span>
        </div>
        
        <div class="notice">
            <?php if (ZALOPAY_MODE === 'DEMO'): ?>
                ⚠️ <strong>Chế độ Demo:</strong> Đây là thanh toán giả lập cho mục đích đồ án. Nhấn "Thanh toán" để hoàn tất đặt vé và nhận điểm tích lũy.
            <?php else: ?>
                🔒 <strong>Thanh toán bảo mật:</strong> Bạn sẽ được chuyển đến cổng thanh toán ZaloPay chính thức. Tiền sẽ chuyển vào tài khoản merchant.
            <?php endif; ?>
        </div>
        
        <div class="btn-group">
            <button class="btn btn-cancel" onclick="window.location.href='../../index.php?act=thanhtoan'">
                Hủy
            </button>
            <button class="btn btn-pay" onclick="processPayment()">
                Thanh toán
            </button>
        </div>
    </div>
    
    <script>
        function processPayment() {
            const mode = '<?= ZALOPAY_MODE ?>';
            
            if (mode === 'DEMO') {
                // Chế độ DEMO: Redirect trực tiếp
                document.querySelector('.btn-pay').textContent = 'Đang xử lý...';
                document.querySelector('.btn-pay').disabled = true;
                
                setTimeout(() => {
                    window.location.href = '../../index.php?act=xacnhan&message=Successful.';
                }, 1500);
            } else {
                // Chế độ PRODUCTION: Gọi API ZaloPay thật
                document.querySelector('.btn-pay').textContent = 'Đang kết nối ZaloPay...';
                document.querySelector('.btn-pay').disabled = true;
                
                // Chuyển sang trang xử lý API
                window.location.href = 'xuly_zalopay_api.php';
            }
        }
    </script>
</body>
</html>
