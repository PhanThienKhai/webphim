<?php
/**
 * Create Sepay Payment Order (QR Code)
 * POST /sepay/create_payment.php
 * 
 * Input JSON: {
 *     "ticket_id": 123,
 *     "amount": 500000
 * }
 * 
 * Output: {
 *     "success": true,
 *     "qr_url": "https://qr.sepay.vn/img?...",
 *     "ticket_code": "VE123",
 *     "amount": 500000
 * }
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require('config.php');

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['ticket_id']) || !isset($data['amount'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing ticket_id or amount']);
    exit;
}

$ticket_id = (int)$data['ticket_id'];
$amount = (int)$data['amount'];

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit;
}

// Build payment content with ticket code
$payment_content = ORDER_PREFIX . $ticket_id;

// Build Sepay QR URL
// Documentation: https://docs.sepay.vn/huong-dan-tao-qr-code.html
$qr_params = [
    'bank' => BANK_CODE,
    'acc' => BANK_ACCOUNT_NUMBER,
    'template' => 'compact',  // compact QR code
    'amount' => $amount,
    'des' => urlencode($payment_content)
];

$qr_url = 'https://qr.sepay.vn/img?' . http_build_query($qr_params);

echo json_encode([
    'success' => true,
    'qr_url' => $qr_url,
    'ticket_code' => $payment_content,
    'amount' => $amount,
    'bank_account' => BANK_ACCOUNT_NUMBER,
    'bank_name' => BANK_NAME,
    'instruction' => 'Quét mã QR bằng app ngân hàng để thanh toán'
]);

?>
