<?php
/**
 * VietQR Webhook Handler - Techcombank
 * Nhận thông báo chuyển khoản từ Techcombank API
 * 
 * API: https://api.techcombank.com/v1/notification/transfer
 * Method: POST
 * Headers: Authorization: Bearer [API_KEY]
 */

header('Content-Type: application/json; charset=utf-8');

// Log tất cả request webhook
$webhook_log = __DIR__ . '/../../logs/webhook_vietqr.log';
if (!file_exists(dirname($webhook_log))) {
    mkdir(dirname($webhook_log), 0777, true);
}

// Lấy raw input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log request
file_put_contents($webhook_log, "[" . date('Y-m-d H:i:s') . "] Webhook received\n", FILE_APPEND);
file_put_contents($webhook_log, "Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Validate webhook signature (nếu có)
if (!validateWebhookSignature($input)) {
    file_put_contents($webhook_log, "[" . date('Y-m-d H:i:s') . "] ❌ Invalid signature\n\n", FILE_APPEND);
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

// Kiểm tra method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Xử lý webhook
$response = processWebhook($data);

// Response
http_response_code($response['code']);
echo json_encode($response['data']);

// ============================================
// HÀM VALIDATE SIGNATURE
// ============================================
function validateWebhookSignature($payload) {
    /**
     * Techcombank gửi header:
     * X-Signature: SHA256(payload + SECRET_KEY)
     * 
     * Cần cấu hình SECRET_KEY từ Techcombank
     */
    
    $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? null;
    if (!$signature) {
        return false;
    }
    
    // TODO: Lấy SECRET_KEY từ config/env
    $secret = getenv('TECHCOMBANK_WEBHOOK_SECRET') ?? 'your-secret-key';
    $expected = hash_hmac('sha256', $payload, $secret);
    
    return hash_equals($expected, $signature);
}

// ============================================
// HÀM XỬ LÝ WEBHOOK
// ============================================
function processWebhook($data) {
    global $webhook_log;
    
    try {
        // Validate input
        if (!isset($data['transactionId'], $data['amount'], $data['description'], $data['toAccount'], $data['status'])) {
            throw new Exception('Missing required fields');
        }
        
        // Extract thông tin
        $transactionId = $data['transactionId'];
        $amount = $data['amount'];
        $description = $data['description']; // VD: "Dat ve phim #12345"
        $toAccount = $data['toAccount']; // 79799999889
        $status = $data['status']; // SUCCESS, PENDING, FAILED
        $fromAccount = $data['fromAccount'] ?? null;
        $fromName = $data['fromName'] ?? 'Customer';
        $timestamp = $data['timestamp'] ?? date('Y-m-d H:i:s');
        
        file_put_contents($webhook_log, "[" . date('Y-m-d H:i:s') . "] Processing: TxnID=$transactionId, Amount=$amount, Status=$status\n", FILE_APPEND);
        
        // Kiểm tra tài khoản nhận
        if ($toAccount !== '79799999889') {
            throw new Exception('Invalid receiving account');
        }
        
        // Parse order info từ description
        // Format: "Dat ve phim #12345" hoặc "DatVe12345"
        preg_match('/#?(\d+)/', $description, $matches);
        $orderId = $matches[1] ?? null;
        
        if (!$orderId) {
            throw new Exception('Cannot parse order ID from description: ' . $description);
        }
        
        file_put_contents($webhook_log, "  OrderID: $orderId\n", FILE_APPEND);
        
        // Kết nối database
        require_once(__DIR__ . '/../../../Trang-admin/model/pdo.php');
        
        // Kiểm tra order tồn tại
        $sql_check = "SELECT * FROM hoa_don WHERE id = ? AND trang_thai = 'pending'";
        $stmt = $pdo->prepare($sql_check);
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        
        if (!$order) {
            throw new Exception('Order not found or already processed');
        }
        
        file_put_contents($webhook_log, "  Order found: Amount={$order['tong']}, Status={$order['trang_thai']}\n", FILE_APPEND);
        
        // Validate số tiền
        if ((int)$amount !== (int)$order['tong']) {
            throw new Exception('Amount mismatch. Expected: ' . $order['tong'] . ', Got: ' . $amount);
        }
        
        // Xử lý theo status
        if ($status === 'SUCCESS') {
            // Cập nhật hóa đơn
            $sql_update = "UPDATE hoa_don SET trang_thai = 'paid', ngay_thanh_toan = NOW(), phuong_thuc = 'vietqr' WHERE id = ?";
            $stmt = $pdo->prepare($sql_update);
            $stmt->execute([$orderId]);
            
            // Cập nhật vé thành "Đã thanh toán"
            $sql_tickets = "UPDATE ve SET trang_thai = 1, ngay_tao = NOW() WHERE ma_hoa_don = ? AND trang_thai = 0";
            $stmt = $pdo->prepare($sql_tickets);
            $stmt->execute([$orderId]);
            
            // Log thành công
            $log_data = [
                'transaction_id' => $transactionId,
                'order_id' => $orderId,
                'amount' => $amount,
                'from_account' => $fromAccount,
                'from_name' => $fromName,
                'status' => 'SUCCESS',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Lưu log payment
            $sql_log = "INSERT INTO payment_log (transaction_id, order_id, amount, method, status, response_data, created_at) 
                       VALUES (?, ?, ?, 'vietqr', 'success', ?, NOW())";
            $stmt = $pdo->prepare($sql_log);
            $stmt->execute([$transactionId, $orderId, $amount, json_encode($log_data)]);
            
            file_put_contents($webhook_log, "  ✅ Order marked as PAID\n\n", FILE_APPEND);
            
            return [
                'code' => 200,
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment confirmed',
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId
                ]
            ];
        } 
        elseif ($status === 'PENDING') {
            file_put_contents($webhook_log, "  ⏳ Payment PENDING - waiting for confirmation\n\n", FILE_APPEND);
            
            return [
                'code' => 200,
                'data' => [
                    'status' => 'pending',
                    'message' => 'Payment pending',
                    'order_id' => $orderId
                ]
            ];
        } 
        else {
            // FAILED status
            $sql_update = "UPDATE hoa_don SET trang_thai = 'failed' WHERE id = ?";
            $stmt = $pdo->prepare($sql_update);
            $stmt->execute([$orderId]);
            
            file_put_contents($webhook_log, "  ❌ Payment FAILED\n\n", FILE_APPEND);
            
            return [
                'code' => 200,
                'data' => [
                    'status' => 'failed',
                    'message' => 'Payment failed',
                    'order_id' => $orderId
                ]
            ];
        }
        
    } catch (Exception $e) {
        file_put_contents($webhook_log, "[" . date('Y-m-d H:i:s') . "] ❌ ERROR: " . $e->getMessage() . "\n\n", FILE_APPEND);
        
        return [
            'code' => 400,
            'data' => [
                'status' => 'error',
                'message' => $e->getMessage()
            ]
        ];
    }
}

/**
 * Database schema cần có:
 * 
 * 1. Bảng payment_log (nếu chưa có):
 * CREATE TABLE payment_log (
 *   id INT PRIMARY KEY AUTO_INCREMENT,
 *   transaction_id VARCHAR(100) UNIQUE,
 *   order_id INT,
 *   amount DECIMAL(10,2),
 *   method VARCHAR(50),
 *   status VARCHAR(50),
 *   response_data JSON,
 *   created_at TIMESTAMP
 * );
 * 
 * 2. Cột trong hoa_don:
 * ALTER TABLE hoa_don ADD COLUMN phuong_thuc VARCHAR(50) DEFAULT 'cash';
 * ALTER TABLE hoa_don ADD COLUMN ngay_thanh_toan TIMESTAMP;
 * 
 * 3. Cột trong ve:
 * ALTER TABLE ve MODIFY trang_thai INT DEFAULT 0; 
 * -- 0=pending, 1=paid, 2=used, 3=cancelled, 4=expired
 */
?>
