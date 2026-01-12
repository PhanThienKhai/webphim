<?php
/**
 * Generate Sepay QR Code for Admin/Staff - Galaxy Studio
 * Tạo QR code thanh toán Sepay cho nhân viên bán vé tại quầy
 */

header('Content-Type: application/json; charset=utf-8');

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$ve_id = (int)($data['ve_id'] ?? 0);

if ($ve_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid ticket ID']);
    exit;
}

// Include config
require('config.php');

// Connect to database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
    exit;
}

// Get ticket info
$stmt = $pdo->prepare("SELECT id, price FROM ve WHERE id = :ve_id");
$stmt->execute([':ve_id' => $ve_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Ticket not found']);
    exit;
}

// Prepare Sepay QR data - Match customer side format
// Format: bank, acc, template, amount, des (description with VE{ticket_id})
$qr_url = 'https://qr.sepay.vn/img?' . http_build_query([
    'bank' => BANK_CODE,                    // e.g., MBBANK
    'acc' => BANK_ACCOUNT_NUMBER,           // e.g., 0384104942
    'template' => 'compact',                // QR template style
    'amount' => (int)$ticket['price'],      // Amount in VND
    'des' => 'VE' . $ve_id                  // Description - webhook parses this
]);

// Return QR URL
echo json_encode([
    'success' => true,
    'qr_url' => $qr_url,
    've_id' => $ve_id,
    'amount' => (int)$ticket['price']
]);
