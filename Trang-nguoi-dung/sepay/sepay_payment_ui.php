<?php
/**
 * Sepay Payment UI
 * Hiển thị QR code khi user chọn thanh toán Sepay
 * Được gọi từ: Trang-nguoi-dung/view/thanhtoan.php (payment method selection)
 */

session_start();

// Giả sử $ticket_id và $amount được truyền từ thanhtoan.php
if (!isset($_GET['ticket_id']) || !isset($_GET['amount'])) {
    die('Missing parameters');
}

$ticket_id = (int)$_GET['ticket_id'];
$amount = (int)$_GET['amount'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán bằng Sepay - Galaxy Studio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .payment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .payment-header h1 { font-size: 28px; margin-bottom: 10px; }
        .payment-header p { font-size: 14px; opacity: 0.9; }
        .payment-content { padding: 30px; }
        .qr-container {
            background: #f9fafb;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-image {
            max-width: 300px;
            margin: 0 auto;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .qr-image img {
            max-width: 100%;
            height: auto;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 14px;
            line-height: 1.6;
        }
        .info-box strong { color: #667eea; }
        .payment-details {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        .detail-row strong { color: #333; }
        .detail-row span { color: #667eea; font-weight: bold; }
        .status-message {
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            text-align: center;
            display: none;
        }
        .status-message.loading {
            background: #e3f2fd;
            color: #1976d2;
            display: block;
        }
        .status-message.success {
            background: #e8f5e9;
            color: #388e3c;
            display: block;
        }
        .status-message.error {
            background: #ffebee;
            color: #c62828;
            display: block;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover { background: #5568d3; }
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        .btn-secondary:hover { background: #d0d0d0; }
        .instructions {
            background: #fff3e0;
            border-left: 4px solid #f57c00;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 13px;
        }
        .instructions strong { color: #f57c00; }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <div class="payment-header">
                <h1>🏦 Thanh toán bằng Chuyển khoản</h1>
                <p>Galaxy Studio - Rạp chiếu phim hàng đầu</p>
            </div>

            <div class="payment-content">
                <!-- QR Code -->
                <div class="qr-container">
                    <p style="margin-bottom: 15px; color: #666;">Quét mã QR bằng app ngân hàng</p>
                    <div class="qr-image" id="qr-code-container">
                        <img src="https://qr.sepay.vn/img?bank=MBBANK&acc=0384104942&template=compact&amount=<?= $amount ?>&des=VE<?= $ticket_id ?>" alt="QR Code" />
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="payment-details">
                    <div class="detail-row">
                        <strong>Mã vé:</strong>
                        <span id="ticket-code">VE<?= $ticket_id ?></span>
                    </div>
                    <div class="detail-row">
                        <strong>Số tiền:</strong>
                        <span id="amount-display"><?= number_format($amount, 0, ',', '.') ?> VND</span>
                    </div>
                    <div class="detail-row">
                        <strong>Trạng thái:</strong>
                        <span id="status-badge" style="color: #ff9800;">Chưa thanh toán</span>
                    </div>
                </div>

                <!-- Bank Info -->
                <div class="info-box">
                    <strong>📋 Thông tin tài khoản:</strong><br>
                    Tên tài khoản: <strong>GALAXY STUDIO</strong><br>
                    Số tài khoản: <strong>0384104942</strong><br>
                    Ngân hàng: <strong>MB (Quân Đội)</strong>
                </div>

                <!-- Instructions -->
                <div class="instructions">
                    <strong>📝 Hướng dẫn:</strong><br>
                    1. Quét mã QR trên bằng app ngân hàng<br>
                    2. Kiểm tra số tiền và nội dung thanh toán<br>
                    3. Nhập mã PIN/mật khẩu để xác nhận<br>
                    4. Sau khi thanh toán, vé sẽ được xác nhận tự động
                </div>

                <!-- Status Messages -->
                <div class="status-message loading" id="status-loading">
                    ⏳ Đang kiểm tra trạng thái thanh toán...
                </div>
                <div class="status-message success" id="status-success">
                    ✓ Thanh toán thành công! Vé của bạn đã được xác nhận. <br>
                    <small>Vui lòng kiểm tra email để nhận chi tiết vé.</small>
                </div>
                <div class="status-message error" id="status-error"></div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="checkPaymentStatus()">Kiểm tra trạng thái</button>
                    <button class="btn btn-secondary" onclick="window.history.back()">Quay lại</button>
                </div>

                <div class="footer">
                    <p>Powered by Sepay | Galaxy Studio © 2025</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const TICKET_ID = <?= $ticket_id ?>;
        const CHECK_INTERVAL = 3000; // 3 giây
        let checkCount = 0;
        const MAX_CHECKS = 600; // Tối đa 30 phút

        /**
         * Kiểm tra trạng thái thanh toán
         */
        async function checkPaymentStatus() {
            try {
                const response = await fetch('/webphim/Trang-nguoi-dung/sepay/check_payment_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ticket_id: TICKET_ID })
                });

                const result = await response.json();

                if (result.success && result.status === 'paid') {
                    showSuccess();
                    clearInterval(autoCheckInterval);
                    return true;
                } else {
                    showLoading();
                    return false;
                }
            } catch (error) {
                showError('Lỗi: ' + error.message);
                return false;
            }
        }

        /**
         * Hiển thị thành công
         */
        function showSuccess() {
            document.getElementById('status-loading').style.display = 'none';
            document.getElementById('status-error').style.display = 'none';
            document.getElementById('status-success').style.display = 'block';
            document.getElementById('status-badge').textContent = 'Đã thanh toán';
            document.getElementById('status-badge').style.color = '#4caf50';
        }

        /**
         * Hiển thị đang kiểm tra
         */
        function showLoading() {
            document.getElementById('status-loading').style.display = 'block';
            document.getElementById('status-error').style.display = 'none';
            document.getElementById('status-success').style.display = 'none';
        }

        /**
         * Hiển thị lỗi
         */
        function showError(message) {
            document.getElementById('status-loading').style.display = 'none';
            document.getElementById('status-error').style.display = 'block';
            document.getElementById('status-error').textContent = '❌ ' + message;
        }

        /**
         * Auto check payment status mỗi 3 giây
         */
        let autoCheckInterval = setInterval(async () => {
            if (checkCount >= MAX_CHECKS) {
                clearInterval(autoCheckInterval);
                return;
            }
            checkCount++;

            const isPaid = await checkPaymentStatus();
            if (isPaid) {
                clearInterval(autoCheckInterval);
            }
        }, CHECK_INTERVAL);

        // Check lần đầu khi load trang
        window.addEventListener('load', checkPaymentStatus);
    </script>
</body>
</html>
