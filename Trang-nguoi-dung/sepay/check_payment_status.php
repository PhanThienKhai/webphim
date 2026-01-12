<?php
/**
 * Check Payment Status + Create Ticket (Sepay)
 * POST /sepay/check_payment_status.php
 * 
 * Input JSON: {"ticket_id": 123}
 * Output: {"success": true, "status": "paid|unpaid", "redirect_url": "..."}
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// ===== TEST MODE =====
define('TEST_MODE', true); // true = test, false = prod
// ====================

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data || !isset($data['ticket_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing ticket_id']);
        exit;
    }
    
    $ticket_id = (int)$data['ticket_id'];
    
    // ===== TEST MODE: Tạo vé ngay (giống Momo) =====
    if (TEST_MODE === true) {
        // KHÔNG tạo vé ở đây, chỉ đánh dấu thanh toán thành công
        // Controller (index.php?act=xacnhan) sẽ tạo vé dựa vào session['tong']
        // Chúng ta chỉ cần xác nhận thanh toán
        
        // Clear session vé cũ để tạo vé mới (fix lỗi lần 2 không tạo vé được)
        unset($_SESSION['id_hd']);
        unset($_SESSION['id_ve']);
        unset($_SESSION['tong_tien']); // Clear giá vé cũ
        unset($_SESSION['da_tao_ve_' . session_id()]);
        unset($_SESSION['da_cong_diem_' . ($_SESSION['id_hd'] ?? '')]);
        
        echo json_encode([
            'success' => true,
            'status' => 'paid',
            'message' => '✅ [TEST MODE] Thanh toán thành công!',
            'test_mode' => true,
            'ticket_created' => false,
            'note' => 'Controller sẽ tạo vé từ session[tong]'
        ]);
        exit;
    }
    // ================================================
    
    // PRODUCTION MODE: Kiểm tra DB
    require('config.php');
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    
    $sql = "SELECT trang_thai FROM ve WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ticket_id' => $ticket_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Ticket not found']);
        exit;
    }
    
    // Status: 0 = Unpaid, 1 = Paid
    $status = $ticket['trang_thai'] == 1 ? 'paid' : 'unpaid';
    $redirect_url = $status === 'paid' ? '/webphim/Trang-nguoi-dung/index.php?act=ve&id=' . $ticket_id : null;
    
    echo json_encode([
        'success' => true,
        'status' => $status,
        'message' => $status == 'paid' ? 'Thanh toán thành công' : 'Chưa thanh toán',
        'test_mode' => false,
        'redirect_url' => $redirect_url
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

 }
 

?>