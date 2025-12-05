<?php
/**
 * Check Payment Status
 * POST /sepay/check_payment_status.php
 * 
 * Input JSON: {"ticket_id": 123}
 * Output: {"success": true, "status": "paid|unpaid", "message": "..."}
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require('config.php');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data || !isset($data['ticket_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing ticket_id']);
        exit;
    }
    
    $ticket_id = (int)$data['ticket_id'];
    
    // Check ticket status
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
    
    echo json_encode([
        'success' => true,
        'status' => $status,
        'message' => $status == 'paid' ? 'Thanh toán thành công' : 'Chưa thanh toán'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

 }
 

?>