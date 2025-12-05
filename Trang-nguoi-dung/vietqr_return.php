<?php
/**
 * VietQR Payment Return/Confirmation Page
 * Auto-create vé and hóa đơn after user confirms payment
 */

session_start();

// Get parameters
$orderId = $_GET['orderId'] ?? '';
$amount = (int)($_GET['amount'] ?? 0);
$status = $_GET['status'] ?? 'pending';

// ====================================================
// CREATE TỰ ĐỘNG: VÉ + HÓA ĐƠN
// ====================================================

if ($status === 'confirmed' && $amount > 0 && !empty($orderId)) {
    // Include PDO connection
    require_once 'model/pdo.php';
    
    // Get $pdo instance
    $pdo = pdo_get_connection();
    
    // Get user info - sửa từ $_SESSION['id_user'] thành $_SESSION['user']['id']
    $user_id = $_SESSION['user']['id'] ?? 0;
    if ($user_id <= 0) {
        http_response_code(401);
        echo "Lỗi: Vui lòng đăng nhập trước";
        exit;
    }

    try {
        // ====================================================
        // 1. CREATE TỰ ĐỘNG: VÉ (VE)
        // ====================================================

        if (isset($_SESSION['ghe_da_chon']) && !empty($_SESSION['ghe_da_chon'])) {
            $ghe_array = $_SESSION['ghe_da_chon'];
            
            foreach ($ghe_array as $ghe) {
                // Insert vé - sửa id_khach_hang thành id_tk
                $sql_ve = "INSERT INTO ve (id_ghe, id_lich_chieu, id_tk, ghi_chu, trang_thai, thoi_gian_dat) 
                          VALUES (:id_ghe, :id_lich_chieu, :id_tk, :ghi_chu, 0, NOW())";
                
                $stmt = $pdo->prepare($sql_ve);
                $stmt->execute([
                    ':id_ghe' => $ghe['id_ghe'],
                    ':id_lich_chieu' => $ghe['id_lich_chieu'],
                    ':id_tk' => $user_id,
                    ':ghi_chu' => 'VietQR Payment - Order: ' . $orderId,
                ]);
            }
        }

        // ====================================================
        // 2. ADD LOYALTY POINTS
        // ====================================================

        $loyalty_points = (int)($amount / 1000); // 1 point per 1,000 VND (same as MoMo)
        
        $sql_loyalty = "UPDATE taikhoan SET diem_tich_luy = diem_tich_luy + :points, tong_diem_tich_luy = tong_diem_tich_luy + :points WHERE id = :id";
        $stmt = $pdo->prepare($sql_loyalty);
        $stmt->execute([
            ':points' => $loyalty_points,
            ':id' => $user_id,
        ]);

        // ====================================================
        // 4. SEND EMAIL CONFIRMATION
        // ====================================================
        
        $user_info = $pdo->query("SELECT email, name FROM taikhoan WHERE id = " . $user_id)->fetch(PDO::FETCH_ASSOC);
        if ($user_info && !empty($user_info['email'])) {
            $to = $user_info['email'];
            $subject = "✓ Thanh toán thành công - Vé phim CinePass";
            $message = "
                <html>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body>
                    <h2>✓ Thanh Toán Thành Công</h2>
                    <p>Xin chào <strong>" . htmlspecialchars($user_info['name']) . "</strong>,</p>
                    <p>Cảm ơn bạn đã đặt vé phim tại <strong>CinePass</strong>!</p>
                    
                    <h3>Thông tin thanh toán:</h3>
                    <ul>
                        <li><strong>Tổng tiền:</strong> " . number_format($amount, 0, ',', '.') . " VND</li>
                        <li><strong>Phương thức:</strong> VietQR</li>
                        <li><strong>Mã đơn hàng:</strong> " . htmlspecialchars($orderId) . "</li>
                        <li><strong>Điểm thưởng nhận được:</strong> <strong>+" . $loyalty_points . " điểm</strong></li>
                    </ul>
                    
                    <p>Vui lòng đến rạp chiếu trước giờ chiếu 15 phút để check-in với vé của bạn.</p>
                    <p><a href='http://localhost/webphim/Trang-nguoi-dung/index.php?p=ve_cua_toi'>👉 Xem vé của tôi</a></p>
                    
                    <p>Cảm ơn bạn!</p>
                </body>
                </html>
            ";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: noreply@cinepass.com" . "\r\n";
            
            $mail_sent = @mail($to, $subject, $message, $headers);
            
            // Debug log
            $log_file = __DIR__ . '/logs/email_log.txt';
            if (!is_dir(dirname($log_file))) {
                @mkdir(dirname($log_file), 0755, true);
            }
            $log_message = date('Y-m-d H:i:s') . " - VietQR Email\n";
            $log_message .= "To: $to\n";
            $log_message .= "Status: " . ($mail_sent ? "SUCCESS" : "FAILED") . "\n";
            $log_message .= "Subject: $subject\n";
            $log_message .= "---\n";
            @file_put_contents($log_file, $log_message, FILE_APPEND);
        }

        // ====================================================
        // 5. CLEAR SESSION
        // ====================================================

        unset($_SESSION['ghe_da_chon']);
        unset($_SESSION['tong']);

        $success = true;
        $message = "Thanh toán thành công! Vé của bạn đã được tạo.";

    } catch (Exception $e) {
        $success = false;
        $message = "Lỗi: " . $e->getMessage();
        
        error_log("VietQR Payment Error: " . $e->getMessage());
    }
} else {
    $success = false;
    $message = "Thanh toán bị hủy hoặc không hợp lệ.";
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? "Thanh Toán Thành Công" : "Thanh Toán Thất Bại"; ?> - CinePass</title>
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
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
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

        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: bounce 0.6s ease-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }

        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .buttons {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        button {
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
            border: 2px solid #dee2e6;
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #f5c6cb;
        }

        .error h1 {
            color: #721c24;
        }

        .success {
            background: transparent;
        }

        .success h1 {
            color: #28a745;
        }

        @media (max-width: 480px) {
            .content {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .icon {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content <?php echo $success ? 'success' : 'error'; ?>">
            <!-- Icon -->
            <div class="icon">
                <?php echo $success ? '✓' : '✕'; ?>
            </div>

            <!-- Title -->
            <h1>
                <?php echo $success ? 'Thanh Toán Thành Công!' : 'Thanh Toán Thất Bại'; ?>
            </h1>

            <!-- Message -->
            <div class="message">
                <?php echo $message; ?>
            </div>

            <!-- Details (if success) -->
            <?php if ($success): ?>
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Mã Đơn Hàng</span>
                    <span class="detail-value"><?php echo htmlspecialchars($orderId); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Số Tiền</span>
                    <span class="detail-value"><?php echo number_format($amount, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phương Thức</span>
                    <span class="detail-value">VietQR</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Hóa Đơn</span>
                    <span class="detail-value">#<?php echo $hoadon_id ?? '---'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Điểm Thưởng</span>
                    <span class="detail-value">+<?php echo (int)($amount / 10000); ?> đ</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="buttons">
                <a href="/webphim/Trang-nguoi-dung/index.php?p=ve_cua_toi">
                    <button class="btn-primary">📽️ Xem Vé Của Tôi</button>
                </a>
                <a href="/webphim/Trang-nguoi-dung/index.php">
                    <button class="btn-secondary">← Quay Lại Trang Chủ</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
