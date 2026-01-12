<?php
/**
 * Chuẩn bị thanh toán SePay
 * KHÔNG tạo vé ở đây - sẽ tạo sau khi payment được xác nhận
 * Endpoint này chỉ validate dữ liệu và return redirect URL
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

error_log("CREATE_TICKET: Request received. Method=" . $_SERVER['REQUEST_METHOD']);

// Kiểm tra user đã login
if (!isset($_SESSION['user']['id']) && !isset($_SESSION['id_tk'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 1, 'message' => 'Unauthorized - Please login']);
    error_log("CREATE_TICKET: Unauthorized - no session user");
    exit;
}

$user_id = $_SESSION['user']['id'] ?? $_SESSION['id_tk'];
error_log("CREATE_TICKET: User ID = $user_id");

try {
    $raw_data = file_get_contents('php://input');
    error_log("CREATE_TICKET: Raw POST data = " . substr($raw_data, 0, 200));
    
    $data = json_decode($raw_data, true);
    
    if ($data === null && $raw_data !== '') {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }
    
    // Validate amount (seats sẽ lấy từ session)
    if (!isset($data['amount']) || $data['amount'] <= 0) {
        throw new Exception('Invalid or missing amount: ' . ($data['amount'] ?? 'null'));
    }
    
    // Kiểm tra session có booking data không
    if (empty($_SESSION['tong'])) {
        throw new Exception('Missing session booking data - no $_SESSION[tong]');
    }
    
    $amount = (int)$data['amount'];
    error_log("CREATE_TICKET: Amount=$amount, Session booking exists");
    
    // Log
    $log_file = __DIR__ . '/payment_creation.log';
    @file_put_contents($log_file, 
        date('Y-m-d H:i:s') . " - SePay payment initiated: User=$user_id, Amount=$amount\n", 
        FILE_APPEND
    );
    
    // Không tạo ticket_id tạm, chỉ dùng amount + session data
    echo json_encode([
        'success' => true,
        'message' => 'Ready for payment',
        'redirect_url' => '/sepay/sepay_payment_ui.php?amount=' . $amount  // Không thêm basePath, JS sẽ thêm
    ]);
    error_log("CREATE_TICKET: Success - redirecting to payment UI");
    
} catch (Exception $e) {
    error_log("CREATE_TICKET: Error - " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 1,
        'message' => $e->getMessage()
    ]);
}
?>
