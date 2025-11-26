<?php
/**
 * VietQR Webhook Test Script
 * Dùng để test webhook handler trước khi đi production
 */

// Cấu hình
$webhookUrl = 'http://localhost/webphim/Trang-nguoi-dung/view/momo/vietqr_webhook.php';
$secretKey = 'default-webhook-secret'; // Lấy từ vietqr_config.php

// Test case 1: Thanh toán thành công
echo "=== TEST 1: PAYMENT SUCCESS ===\n\n";

$testData1 = [
    'transactionId' => 'TXN202511210001',
    'amount' => 150000,
    'description' => 'Dat ve phim #12345',
    'toAccount' => '79799999889',
    'toName' => 'CINEPASS CINEMA',
    'fromAccount' => '1111111111',
    'fromName' => 'NGUYEN VAN A',
    'status' => 'SUCCESS',
    'timestamp' => date('c'),
    'bankCode' => 'TCB',
];

sendWebhook($testData1, $secretKey, $webhookUrl);

// Test case 2: Thanh toán pending
echo "\n=== TEST 2: PAYMENT PENDING ===\n\n";

$testData2 = array_merge($testData1, [
    'transactionId' => 'TXN202511210002',
    'status' => 'PENDING',
]);

sendWebhook($testData2, $secretKey, $webhookUrl);

// Test case 3: Thanh toán thất bại
echo "\n=== TEST 3: PAYMENT FAILED ===\n\n";

$testData3 = array_merge($testData1, [
    'transactionId' => 'TXN202511210003',
    'status' => 'FAILED',
]);

sendWebhook($testData3, $secretKey, $webhookUrl);

// Test case 4: Signature không hợp lệ
echo "\n=== TEST 4: INVALID SIGNATURE ===\n\n";

sendWebhook($testData1, 'wrong-secret-key', $webhookUrl);

// Test case 5: Missing fields
echo "\n=== TEST 5: MISSING FIELDS ===\n\n";

$testData5 = [
    'transactionId' => 'TXN202511210005',
    'amount' => 150000,
    // Missing 'description', 'toAccount', 'status'
];

sendWebhook($testData5, $secretKey, $webhookUrl);

// ============================================
// HÀM GỬI WEBHOOK
// ============================================
function sendWebhook($data, $secret, $url) {
    $payload = json_encode($data);
    $signature = hash_hmac('sha256', $payload, $secret);
    
    echo "Payload: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "Signature: $signature\n";
    echo "URL: $url\n";
    
    // Gửi request
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-Signature: ' . $signature,
        ],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Status: $httpCode\n";
    if ($response) {
        echo "Response: " . json_encode(json_decode($response, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
    if ($error) {
        echo "Error: $error\n";
    }
    echo "\n";
}

/**
 * CÁCH CHẠY TEST:
 * 
 * 1. Command line:
 *    php vietqr_webhook_test.php
 * 
 * 2. Hoặc qua browser:
 *    http://localhost/webphim/Trang-nguoi-dung/view/momo/vietqr_webhook_test.php
 * 
 * 3. Kiểm tra log:
 *    tail -f logs/webhook_vietqr.log
 */
?>
