<?php
/**
 * VietQR Banking - Xác nhận thanh toán
 * Xử lý sau khi người dùng quét QR và thực hiện chuyển khoản
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

// Kiểm tra session
if (!isset($_SESSION['vietqr_amount']) || !isset($_SESSION['tong'])) {
    header('Location: ../../index.php');
    exit;
}

// Lấy thông tin từ session
$amount = $_SESSION['vietqr_amount'];
$orderInfo = $_SESSION['vietqr_order_info'] ?? 'Dat ve phim';
$userId = $_SESSION['user_id'] ?? null;

// Mô phỏng xác nhận thanh toán (thực tế cần gọi API ngân hàng để kiểm tra)
// Trong production, bạn cần webhook từ ngân hàng hoặc polling API
$paymentConfirmed = true; // Giả lập là thanh toán thành công

if ($paymentConfirmed) {
    // Lưu thông tin thanh toán vào database (tùy chọn)
    // Ở đây chúng ta chỉ lưu vào session
    $_SESSION['payment_method'] = 'vietqr';
    $_SESSION['payment_status'] = 'confirmed';
    $_SESSION['payment_time'] = date('Y-m-d H:i:s');
    
    // Xóa thông tin tạm thời
    unset($_SESSION['vietqr_amount']);
    unset($_SESSION['vietqr_order_info']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán</title>
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            text-align: center;
            padding: 60px 40px;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        h1 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f0fdf4;
            border: 2px solid #d1fae5;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .info-label {
            color: #047857;
            font-weight: 500;
        }
        
        .info-value {
            color: #065f46;
            font-weight: 600;
        }
        
        .status {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
            display: inline-block;
        }
        
        .details {
            text-align: left;
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .details p {
            margin-bottom: 10px;
            color: #4b5563;
        }
        
        .details strong {
            color: #1f2937;
        }
        
        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        .note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        
        <h1>Thanh Toán Thành Công!</h1>
        <p class="subtitle">Đơn hàng của bạn đã được xác nhận</p>
        
        <div class="status">✓ Đã xác nhận</div>
        
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Phương thức thanh toán:</span>
                <span class="info-value">QR Banking</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số tiền:</span>
                <span class="info-value"><?= number_format($amount) ?> đ</span>
            </div>
            <div class="info-row">
                <span class="info-label">Thời gian:</span>
                <span class="info-value"><?= date('H:i - d/m/Y') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value" style="color: #10b981;">Hoàn tất</span>
            </div>
        </div>
        
        <div class="details">
            <p><strong>📌 Nội dung:</strong> <?= htmlspecialchars($orderInfo) ?></p>
            <p><strong>🎬 Phim:</strong> <?= htmlspecialchars($_SESSION['tong']['tieu_de'] ?? 'N/A') ?></p>
            <p><strong>📅 Ngày chiếu:</strong> <?= htmlspecialchars($_SESSION['tong']['ngay_chieu'] ?? 'N/A') ?></p>
            <p><strong>🎟️ Ghế:</strong> <?= htmlspecialchars($_SESSION['tong']['ghe'] ?? 'N/A') ?></p>
        </div>
        
        <div class="note">
            ℹ️ <strong>Lưu ý:</strong> Vé của bạn đã được lưu vào hệ thống. Bạn có thể xem chi tiết vé trong tài khoản cá nhân hoặc check email để nhận xác nhận.
        </div>
        
        <div class="buttons">
            <a href="../../index.php?act=ve" class="btn btn-secondary" style="text-decoration: none;">Xem vé của tôi</a>
            <a href="../../index.php" class="btn btn-primary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Tiếp tục</a>
        </div>
    </div>
</body>
</html>
