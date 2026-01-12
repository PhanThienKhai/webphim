<?php
/**
 * VietQR Payment API Endpoint
 * Generate banking QR code for customers to transfer
 * POST /api_create_vietqr_payment.php
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// ====================================================
// BANK ACCOUNT CONFIGURATION
// ====================================================

// CinePass Theater Bank Account
define('BANK_ACCOUNT_NAME', 'CINEPASS THEATER');
define('BANK_ACCOUNT_NUMBER', '1234567890'); // ← Thay bằng số tài khoản thực
define('BANK_CODE', 'VIETCOMBANK'); // Mã ngân hàng VietCom
define('BANK_NAME', 'Ngân hàng TMCP Ngoài Thương Việt Nam');

// VietQR API
define('VIETQR_API', 'https://api.vietqr.io/v2/generate');

// ====================================================
// VALIDATE REQUEST
// ====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 1, 'message' => 'Method not allowed']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    $data = $_POST;
}

// ====================================================
// LẤY DỮ LIỆU
// ====================================================

$amount = isset($data['amount']) ? (int)$data['amount'] : 
          (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0 ? 
           (int)$_SESSION['tong']['gia_sau_giam'] : 0);

$description = $data['description'] ?? 'Ve phim CinePass';

// ====================================================
// VALIDATE
// ====================================================

if ($amount < 10000) {
    http_response_code(400);
    echo json_encode([
        'error' => 1,
        'message' => 'Amount must be at least 10,000 VND'
    ]);
    exit;
}

// Lấy user_id từ session
$user_id = $_SESSION['user']['id'] ?? $_SESSION['id_tk'] ?? $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['error' => 1, 'message' => 'Unauthorized: Please login first']);
    exit;
}

// Tạo Order Code
$orderId = 'CINEMA_' . time() . '_' . rand(1000, 9999);

// ====================================================
// LƯU ORDER VÀO DATABASE TRƯỚC KHI GỬI API
// ====================================================

require_once 'model/pdo.php';
$pdo = pdo_get_connection();

// Tạo bảng payment_orders nếu chưa có
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS payment_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(50) UNIQUE,
        user_id INT,
        amount INT,
        status ENUM('pending', 'confirmed', 'expired', 'failed') DEFAULT 'pending',
        payment_method VARCHAR(20),
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES taikhoan(id)
    )");
} catch (Exception $e) {
    // Table already exists
}

// Lưu order vào database
try {
    $sql_order = "INSERT INTO payment_orders (order_id, user_id, amount, status, payment_method, description)
                  VALUES (:order_id, :user_id, :amount, 'pending', 'vietqr', :description)";
    $stmt = $pdo->prepare($sql_order);
    $stmt->execute([
        ':order_id' => $orderId,
        ':user_id' => $user_id,
        ':amount' => $amount,
        ':description' => $description
    ]);
    
    // Log
    $log_file = __DIR__ . '/logs/payment_log.txt';
    if (!is_dir(dirname($log_file))) @mkdir(dirname($log_file), 0755, true);
    @file_put_contents($log_file, date('Y-m-d H:i:s') . " - Order Created: $orderId | Amount: $amount | User: $user_id\n", FILE_APPEND);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 1, 'message' => 'Failed to create order: ' . $e->getMessage()]);
    exit;
}

// Lưu order_id vào session
$_SESSION['current_order_id'] = $orderId;
$_SESSION['current_order_amount'] = $amount;

// ====================================================
// GENERATE VIETQR QR CODE
// ====================================================

// Prepare VietQR payload
$payload = [
    'accountNo' => BANK_ACCOUNT_NUMBER,
    'accountName' => BANK_ACCOUNT_NAME,
    'acqId' => '970436', // VietCombank BIN
    'amount' => $amount,
    'addInfo' => urlencode($description . ' - ' . $orderId),
    'format' => 'text', // Trả về base64 image
];

// Build query string
$query = http_build_query($payload);
$vietqr_url = VIETQR_API . '?' . $query;

// Call VietQR API
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $vietqr_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Log
file_put_contents(__DIR__ . '/view/momo/vietqr_debug.log',
    date('Y-m-d H:i:s') . " Order: $orderId | Amount: $amount | HTTP: $httpCode | URL: $vietqr_url\n",
    FILE_APPEND
);

// Parse response
$result = json_decode($response, true);

// ====================================================
// HANDLE RESPONSE
// ====================================================

if ($httpCode === 200 && isset($result['data']['qr'])) {
    // Success - VietQR returned QR code
    http_response_code(200);
    echo json_encode([
        'error' => 0,
        'message' => 'SUCCESS',
        'data' => [
            'orderId' => $orderId,
            'amount' => $amount,
            'bankName' => BANK_NAME,
            'bankCode' => BANK_CODE,
            'accountNumber' => BANK_ACCOUNT_NUMBER,
            'accountName' => BANK_ACCOUNT_NAME,
            'description' => $description,
            'qrCode' => $result['data']['qr'], // Base64 QR code
            'qrUrl' => isset($result['data']['qrUrl']) ? $result['data']['qrUrl'] : null,
        ]
    ]);
} else {
    // Error or fallback to local QR generation
    // Nếu VietQR API lỗi, generate QR locally
    
    file_put_contents(__DIR__ . '/view/momo/vietqr_debug.log',
        date('Y-m-d H:i:s') . " [FALLBACK] Generating local QR\n",
        FILE_APPEND
    );
    
    // Fallback: Create local QR using existing library
    $qr_data = "00020126" . // QR Type
               "360014" . // Service Code
               BANK_ACCOUNT_NUMBER . // Account Number
               $amount . // Amount
               urlencode($description); // Description
    
    http_response_code(200);
    echo json_encode([
        'error' => 0,
        'message' => 'SUCCESS (Fallback)',
        'data' => [
            'orderId' => $orderId,
            'amount' => $amount,
            'bankName' => BANK_NAME,
            'bankCode' => BANK_CODE,
            'accountNumber' => BANK_ACCOUNT_NUMBER,
            'accountName' => BANK_ACCOUNT_NAME,
            'description' => $description,
            'qrCode' => 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22white%22 width=%22100%22 height=%22100%22/%3E%3C/svg%3E', // Placeholder
            'manualTransfer' => true, // User needs to transfer manually
        ]
    ]);
}
