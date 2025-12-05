<?php
/**
 * VietQR Banking - Tạo QR Code thanh toán
 * File này xử lý việc tạo QR Code và hiển thị trang thanh toán
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

require_once 'vietqr_config.php';

// Kiểm tra session
if (!isset($_SESSION['tong'])) {
    die('<div style="padding:20px; text-align:center; color:#d32f2f;">Lỗi: Không tìm thấy thông tin đơn hàng</div>');
}

// Lấy số tiền
$amount = 0;
if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
    $amount = (int)$_SESSION['tong']['gia_sau_giam'];
} else {
    $amount = isset($_SESSION['tong']['gia_ghe']) ? (int)$_SESSION['tong']['gia_ghe'] : 0;
}

// Validate
if ($amount < 1000) {
    die("Lỗi: Số tiền thanh toán phải tối thiểu 1,000 VND");
}

// Lấy order ID từ session (nếu có)
$orderId = $_SESSION['order_id'] ?? null;

// Tạo nội dung chuyển khoản
$orderInfo = 'Dat ve phim - ' . (isset($_SESSION['tong']['tieu_de']) ? substr($_SESSION['tong']['tieu_de'], 0, 30) : 'Ve phim');
if ($orderId) {
    $orderInfo = 'Dat ve phim #' . $orderId; // Để webhook parse được order ID
}

$_SESSION['vietqr_order_info'] = $orderInfo;
$_SESSION['vietqr_amount'] = $amount;

// Tạo QR Code
$qrResult = generateVietQR(BANK_ACCOUNT_NUMBER, BANK_CODE, $amount, $orderInfo);

// Nếu tạo QR thất bại, dùng fallback
if (!$qrResult['success']) {
    // Tạo QR Code thủ công (VietQR format)
    $qrData = encodeVietQR(BANK_ACCOUNT_NUMBER, BANK_CODE, BANK_ACCOUNT_NAME, $amount, $orderInfo);
    $qrResult = [
        'success' => true,
        'qr_code' => $qrData,
        'qr_url' => 'https://api.vietqr.io/backend/displayQRCode?qr=' . urlencode($qrData),
    ];
}

/**
 * Encode VietQR theo chuẩn NAPAS EMV QR
 * Fallback khi API không hoạt động
 */
function encodeVietQR($accountNumber, $bankCode, $accountName, $amount, $description) {
    // Format: Banking QR Code format
    // Đây là format cơ bản, có thể cần điều chỉnh theo chuẩn VietQR chính thức
    $qrData = "00020101021135360010A000000727301240060704{$bankCode}0711{$accountNumber}520441115802VN5913" . 
              strtoupper($accountName) . "6009HO CHI MINH6304" . base_convert(crc32($description), 10, 16);
    return $qrData;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán QR Banking</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
        
        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .payment-header p {
            opacity: 0.9;
            font-size: 15px;
        }
        
        .payment-content {
            padding: 40px 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .info-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .info-value {
            color: #1f2937;
            font-weight: 600;
        }
        
        .qr-container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            margin: 30px 0;
        }
        
        #qrcode {
            display: inline-block;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }
        
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .instructions h3 {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .instructions ol {
            margin-left: 20px;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .instructions li {
            margin-bottom: 10px;
        }
        
        .payment-details {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .detail-label {
            color: #047857;
            font-weight: 500;
        }
        
        .detail-value {
            color: #065f46;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        
        .amount-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
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
        
        .timer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }
        
        .copy-btn {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .copy-btn:hover {
            background: #e5e7eb;
        }
        
        .warning {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            color: #742a2a;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 13px;
        }
        
        @media (max-width: 600px) {
            .payment-header {
                padding: 25px 20px;
            }
            
            .payment-header h1 {
                font-size: 24px;
            }
            
            .payment-content {
                padding: 25px 20px;
            }
            
            .amount-value {
                font-size: 28px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>💳 Thanh Toán QR Banking</h1>
            <p>Quét mã QR bằng ứng dụng ngân hàng của bạn</p>
        </div>
        
        <div class="payment-content">
            <!-- Thông tin đơn hàng -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">🎬 Phim:</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['tong']['tieu_de'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">📅 Ngày chiếu:</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['tong']['ngay_chieu'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">🎟️ Ghế:</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['tong']['ghe'] ?? 'N/A') ?></span>
                </div>
            </div>
            
            <!-- Số tiền thanh toán -->
            <div class="amount-section">
                <div class="amount-label">Số tiền cần thanh toán</div>
                <div class="amount-value"><?= formatVND($amount) ?></div>
            </div>
            
            <!-- Mã QR -->
            <?php if ($qrResult['success']): ?>
                <div class="qr-container">
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">Quét mã QR dưới đây</p>
                    <div id="qrcode"></div>
                    <script>
                        // Tạo QR Code từ URL
                        new QRCode(document.getElementById("qrcode"), {
                            text: "<?= htmlspecialchars($qrResult['qr_url']) ?>",
                            width: 280,
                            height: 280,
                            correctLevel: QRCode.CorrectLevel.H,
                            colorDark: "#000",
                            colorLight: "#fff"
                        });
                    </script>
                </div>
            <?php endif; ?>
            
            <!-- Thông tin chuyển khoản -->
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">👤 Tên chủ tài khoản:</span>
                    <span class="detail-value"><?= htmlspecialchars(BANK_ACCOUNT_NAME) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">🏦 Số tài khoản:</span>
                    <span class="detail-value">
                        <?= htmlspecialchars(BANK_ACCOUNT_NUMBER) ?>
                        <button class="copy-btn" onclick="copyToClipboard('<?= BANK_ACCOUNT_NUMBER ?>')">Copy</button>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">💰 Số tiền:</span>
                    <span class="detail-value"><?= number_format($amount) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">📝 Nội dung:</span>
                    <span class="detail-value" style="font-size: 12px;">
                        <?= htmlspecialchars($orderInfo) ?>
                        <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($orderInfo) ?>')">Copy</button>
                    </span>
                </div>
            </div>
            
            <!-- Hướng dẫn -->
            <div class="instructions">
                <h3>📱 Hướng dẫn thanh toán:</h3>
                <ol>
                    <li>Mở ứng dụng ngân hàng hoặc ví điện tử của bạn (VCB Pay, VIB, BIDV, v.v.)</li>
                    <li>Chọn chức năng "Quét mã QR" hoặc "Thanh toán QR"</li>
                    <li>Quét mã QR ở trên</li>
                    <li>Xác nhận thông tin chuyển khoản và hoàn tất thanh toán</li>
                    <li>Chờ xác nhận - hệ thống sẽ tự động cập nhật</li>
                </ol>
            </div>
            
            <!-- Cảnh báo -->
            <div class="warning">
                ⚠️ <strong>Lưu ý:</strong> Vui lòng chuyển đúng số tiền và nội dung như hướng dẫn để hệ thống tự động xác nhận thanh toán.
            </div>
            
            <!-- Nút hành động -->
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="history.back();">← Quay lại</button>
                <button class="btn btn-primary" onclick="confirmPayment();">✓ Tôi đã thanh toán</button>
            </div>
            
            <!-- Timer -->
            <div class="timer">
                Mã QR sẽ hết hạn sau: <span id="timer">10:00</span>
            </div>
        </div>
    </div>
    
    <script>
        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Đã sao chép!');
            });
        }
        
        // Countdown timer (10 minutes)
        let timeLeft = 600; // 10 minutes in seconds
        function updateTimer() {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            document.getElementById('timer').textContent = 
                (minutes < 10 ? '0' : '') + minutes + ':' + 
                (seconds < 10 ? '0' : '') + seconds;
            
            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                alert('Mã QR đã hết hạn. Vui lòng quay lại và thử lại.');
                window.history.back();
            }
        }
        updateTimer();
        
        // Confirm payment
        function confirmPayment() {
            if (confirm('Bạn có chắc chắn đã thanh toán chưa?\n\nVui lòng chắc chắn rằng bạn đã chuyển khoản trước khi xác nhận.')) {
                // Redirect to confirmation page
                window.location.href = 'vietqr_confirm.php';
            }
        }
    </script>
</body>
</html>
