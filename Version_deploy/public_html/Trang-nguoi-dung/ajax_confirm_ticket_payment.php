<?php
/**
 * Ajax Endpoint: Confirm Ticket & Send Email After Payment
 * Called from checkout page when payment is confirmed
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// ====================================================
// VALIDATE REQUEST
// ====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get parameters
$ticket_ids = isset($_POST['ticket_ids']) ? json_decode($_POST['ticket_ids'], true) : [];
$order_id = $_POST['order_id'] ?? '';
$amount = (int)($_POST['amount'] ?? 0);

// Validate
if (empty($ticket_ids) || !is_array($ticket_ids) || empty($order_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

// Check user
$user_id = $_SESSION['id_user'] ?? 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

try {
    // ====================================================
    // 1. CONNECT DATABASE
    // ====================================================
    
    require_once __DIR__ . '/model/pdo.php';
    
    // ====================================================
    // 2. UPDATE TICKET STATUS TO 1 (PAID)
    // ====================================================
    
    $placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
    $sql_update = "UPDATE ve SET trang_thai = 1, check_in_luc = NULL WHERE id IN ($placeholders) AND id_khach_hang = ?";
    
    $stmt = $pdo->prepare($sql_update);
    $params = array_merge($ticket_ids, [$user_id]);
    $stmt->execute($params);
    
    // ====================================================
    // 3. GET TICKET & USER DETAILS
    // ====================================================
    
    $sql_user = "SELECT email, ho_ten FROM taikhoan WHERE id_user = ?";
    $stmt = $pdo->prepare($sql_user);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    $user_email = $user['email'] ?? '';
    $user_name = $user['ho_ten'] ?? 'Khách hàng';
    
    if (empty($user_email)) {
        throw new Exception('User email not found');
    }
    
    // ====================================================
    // 4. GET TICKET INFORMATION
    // ====================================================
    
    $sql_tickets = "SELECT v.id, v.ghe, 
                   p.tieu_de, p.img,
                   lc.ngay_chieu, 
                   kgc.thoi_gian_chieu,
                   pc.name as tenphong,
                   rc.ten_rap, rc.dia_chi
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pc ON pc.id = kgc.id_phong
            JOIN rap_chieu rc ON rc.id = lc.id_rap
            WHERE v.id IN ($placeholders)";
    
    $stmt = $pdo->prepare($sql_tickets);
    $stmt->execute($ticket_ids);
    $tickets = $stmt->fetchAll();
    
    if (empty($tickets)) {
        throw new Exception('No tickets found');
    }
    
    // ====================================================
    // 5. BUILD EMAIL CONTENT
    // ====================================================
    
    $tickets_info = '';
    foreach ($tickets as $ticket) {
        $tickets_info .= "
            <tr style='border-bottom: 1px solid #ddd;'>
                <td style='padding: 10px;'><strong>Vé #" . htmlspecialchars($ticket['id']) . "</strong></td>
                <td style='padding: 10px;'>" . htmlspecialchars($ticket['tieu_de']) . "</td>
                <td style='padding: 10px;'>" . date('d/m/Y', strtotime($ticket['ngay_chieu'])) . " - " . date('H:i', strtotime($ticket['thoi_gian_chieu'])) . "</td>
                <td style='padding: 10px;'>" . htmlspecialchars($ticket['tenphong']) . " | Ghế: <strong>" . htmlspecialchars($ticket['ghe']) . "</strong></td>
            </tr>
        ";
    }
    
    // Calculate loyalty points
    $loyalty_points = (int)($amount / 10000);
    
    // ====================================================
    // 6. SEND EMAIL
    // ====================================================
    
    $to = $user_email;
    $subject = "✓ Thanh Toán Thành Công & Xác Nhận Vé - CinePass Cinema";
    
    $message = "
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>
        <body style='font-family: Arial, sans-serif; color: #333; background: #f5f5f5;'>
            <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                
                <!-- Header -->
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;'>
                    <h1 style='margin: 0; font-size: 28px;'>✓ Thanh Toán Thành Công</h1>
                    <p style='margin: 10px 0 0 0; opacity: 0.9;'>Vé của bạn đã được xác nhận</p>
                </div>
                
                <!-- Content -->
                <div style='padding: 30px;'>
                    <h2 style='color: #333; margin-top: 0;'>Xin chào " . htmlspecialchars($user_name) . ",</h2>
                    <p>Cảm ơn bạn đã đặt vé tại <strong>CinePass Cinema</strong>. Thanh toán của bạn đã được xác nhận và vé của bạn sẵn sàng sử dụng.</p>
                    
                    <!-- Order Info -->
                    <div style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='margin-top: 0; color: #333;'>📋 Thông Tin Đơn Hàng</h3>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 8px;'><strong>Mã Đơn Hàng:</strong></td>
                                <td style='padding: 8px; text-align: right;'>" . htmlspecialchars($order_id) . "</td>
                            </tr>
                            <tr style='background: #f0f0f0;'>
                                <td style='padding: 8px;'><strong>Tổng Tiền:</strong></td>
                                <td style='padding: 8px; text-align: right; font-size: 18px; color: #667eea;'><strong>" . number_format($amount, 0, ',', '.') . " VND</strong></td>
                            </tr>
                            <tr>
                                <td style='padding: 8px;'><strong>Phương Thức:</strong></td>
                                <td style='padding: 8px; text-align: right;'>Chuyển khoản ngân hàng</td>
                            </tr>
                            <tr style='background: #f0f0f0;'>
                                <td style='padding: 8px;'><strong>Điểm Thưởng:</strong></td>
                                <td style='padding: 8px; text-align: right;'>+<strong>" . $loyalty_points . "</strong> điểm</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Tickets Info -->
                    <div style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='margin-top: 0; color: #333;'>🎫 Chi Tiết Vé</h3>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <thead>
                                <tr style='background: #667eea; color: white;'>
                                    <th style='padding: 10px; text-align: left;'>Mã Vé</th>
                                    <th style='padding: 10px; text-align: left;'>Phim</th>
                                    <th style='padding: 10px; text-align: left;'>Ngày & Giờ</th>
                                    <th style='padding: 10px; text-align: left;'>Vị Trí</th>
                                </tr>
                            </thead>
                            <tbody>
                                " . $tickets_info . "
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Instructions -->
                    <div style='background: #e8f5e9; padding: 20px; border-radius: 8px; border-left: 4px solid #4caf50; margin: 20px 0;'>
                        <h3 style='margin-top: 0; color: #2e7d32;'>📌 Hướng Dẫn Sử Dụng</h3>
                        <ul style='margin: 0; padding-left: 20px;'>
                            <li>Bạn có thể xem lại vé của mình trong tài khoản CinePass</li>
                            <li>Mang theo vé này (in hoặc trên điện thoại) khi vào rạp</li>
                            <li>Xuất trình vé với nhân viên rạp để nhận xác nhận</li>
                            <li>Vé sẽ tự động được check-in khi bạn quét mã QR tại cửa rạp</li>
                        </ul>
                    </div>
                    
                    <!-- CTA -->
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://" . $_SERVER['HTTP_HOST'] . "/Trang-nguoi-dung/index.php?p=ve_cua_toi' style='display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold;'>
                            👉 Xem Vé Của Tôi
                        </a>
                    </div>
                    
                    <!-- Support -->
                    <div style='background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center; color: #666; font-size: 13px;'>
                        <p style='margin: 0;'>Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với chúng tôi</p>
                        <p style='margin: 5px 0 0 0;'>📞 Hotline: 1900-xxxx | 📧 support@cinepass.com</p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style='background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;'>
                    <p style='margin: 0;'>© 2024 CinePass Cinema - Rạp Chiếu Phim Chất Lượng Cao</p>
                    <p style='margin: 5px 0 0 0;'>Cảm ơn bạn đã tin tưởng chúng tôi!</p>
                </div>
            </div>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@cinepass.com\r\n";
    
    $mail_sent = mail($to, $subject, $message, $headers);
    
    // ====================================================
    // 7. RESPONSE
    // ====================================================
    
    echo json_encode([
        'success' => true,
        'message' => 'Ticket confirmed and email sent successfully',
        'ticket_count' => count($tickets),
        'email_sent' => $mail_sent
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
    error_log('Ticket Confirmation Error: ' . $e->getMessage());
}
?>
