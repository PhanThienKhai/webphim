<?php
/**
 * Check Payment Status (Sepay)
 * POST /sepay/check_payment_status.php
 * 
 * ⚠️ IMPORTANT - Updated Flow:
 * 1. User books tickets → confirm_payment.php creates UNPAID tickets
 * 2. QR code generated with first ticket ID (VE[ticket_id])
 * 3. User transfers money
 * 4. Webhook receives transfer → Updates tickets to PAID
 * 5. User clicks "Kiểm tra" → This endpoint checks status
 * 
 * This endpoint CHECKS status, doesn't CREATE tickets
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// ===== TEST MODE =====
define('TEST_MODE', false); // PRODUCTION MODE
// ====================

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['paid' => false, 'status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }
    
    // Can receive either:
    // 1. ticket_id directly (from old flow)
    // 2. trans_id (from sepay_payment_ui via trans_id_TIMESTAMP format)
    // 3. booking data with amount/user_id (from sepay_payment_ui)
    
    $ticket_id = isset($data['ticket_id']) ? (int)$data['ticket_id'] : 0;
    $trans_id = isset($data['trans_id']) ? $data['trans_id'] : '';
    $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
    
    error_log("CHECK_PAYMENT_STATUS: ticket_id=$ticket_id, trans_id=$trans_id, amount=$amount");
    
    // ===== TEST MODE =====
    if (TEST_MODE === true) {
        echo json_encode([
            'paid' => true,
            'status' => 'paid',
            'message' => '✅ [TEST MODE] Thanh toán thành công!',
            'test_mode' => true,
            'ticket_id' => $ticket_id
        ]);
        exit;
    }
    // ====================
    
    // PRODUCTION MODE: Check payment status from database
    require('config.php');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode(['paid' => false, 'status' => 'error', 'message' => 'DB connection error: ' . $e->getMessage()]);
        exit;
    }
    
    // If no ticket_id, try to find by trans_id (ma_ve format) or recent booking
    if ($ticket_id <= 0 && !empty($trans_id)) {
        // Try to find ticket by SEPAY ma_ve pattern matching trans_id
        $check_sql = "SELECT id, trang_thai, price, ma_ve, id_tk, id_phim, id_rap, ghe, combo
                      FROM ve WHERE ma_ve LIKE 'SEPAY_%' AND price = :amount 
                      ORDER BY id DESC LIMIT 1";
        
        $stmt = $pdo->prepare($check_sql);
        $stmt->execute([':amount' => $amount]);
        $ticket = $stmt->fetch();
        
        if ($ticket) {
            $ticket_id = $ticket['id'];
            error_log("CHECK_PAYMENT_STATUS: Found ticket by amount - ID=$ticket_id");
        }
    }
    
    // If still no ticket_id, fail
    if ($ticket_id <= 0) {
        echo json_encode([
            'paid' => false,
            'status' => 'not_found',
            'message' => 'No ticket found'
        ]);
        exit;
    }
    
    // Check ticket status in database
    $check_sql = "SELECT id, trang_thai, price, ma_ve, id_tk, id_phim, id_rap, ghe, combo
                  FROM ve WHERE id = :ticket_id";
    
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute([':ticket_id' => $ticket_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        echo json_encode([
            'paid' => false, 
            'status' => 'not_found',
            'message' => 'Ticket not found'
        ]);
        exit;
    }
    
    error_log("CHECK_PAYMENT_STATUS: Ticket found - Status=" . $ticket['trang_thai'] . ", Price=" . $ticket['price']);
    
    // Check payment status
    $is_paid = in_array($ticket['trang_thai'], ['da_thanh_toan', 'paid', 1]);
    
    if ($is_paid) {
        // Ticket already paid - success
        echo json_encode([
            'paid' => true,
            'status' => 'paid',
            'message' => '✅ Thanh toán thành công! Vé của bạn đã sẵn sàng.',
            'ticket_id' => $ticket_id,
            'ticket_code' => $ticket['ma_ve'],
            'amount' => (int)$ticket['price'],
            'redirect_url' => '/Trang-nguoi-dung/index.php?act=xacnhan'
        ]);
        error_log("CHECK_PAYMENT_STATUS: Ticket $ticket_id is PAID");
    } else {
        // Ticket still unpaid - waiting for transfer
        echo json_encode([
            'paid' => false,
            'status' => 'unpaid',
            'message' => '⏳ Chúng tôi chưa nhận được chuyển khoản. Vui lòng kiểm tra lại hoặc thử lại sau...',
            'ticket_id' => $ticket_id,
            'amount' => (int)$ticket['price'],
            'wait_time' => 'Vui lòng chờ 1-2 phút sau khi chuyển khoản'
        ]);
        error_log("CHECK_PAYMENT_STATUS: Ticket $ticket_id is UNPAID");
    }
    
} catch (Exception $e) {
    http_response_code(500);
    error_log("CHECK_PAYMENT_STATUS ERROR: " . $e->getMessage());
    echo json_encode(['paid' => false, 'status' => 'error', 'message' => $e->getMessage()]);
}
?>

