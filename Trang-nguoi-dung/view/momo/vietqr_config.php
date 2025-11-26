<?php
/**
 * VietQR Banking Configuration
 * QR Code Banking cho thanh toán chuyển khoản
 */

// ============================================
// THÔNG TIN NGÂN HÀNG (CẬP NHẬT TECHCOMBANK)
// ============================================
// Tài khoản Techcombank đã được cấu hình

define('BANK_ACCOUNT_NAME', 'CINEPASS CINEMA'); // Tên chủ tài khoản (VIẾT HOA)
define('BANK_ACCOUNT_NUMBER', '79799999889'); // Số tài khoản Techcombank
define('BANK_CODE', 'TCB'); // Techcombank

// Danh sách mã ngân hàng phổ biến
$BANK_CODES = [
    'VCB' => 'Vietcombank',
    'VIB' => 'VIB',
    'BID' => 'BIDV',
    'TCB' => 'Techcombank',
    'ACB' => 'ACB',
    'TPB' => 'TPBank',
    'SHB' => 'SHB',
    'MB' => 'MBBank',
    'CTG' => 'VietinBank',
    'STB' => 'Sacombank',
    'HDB' => 'HDBank',
    'EIB' => 'Eximbank',
];

// ============================================
// API VietQR (QR Code Generator)
// ============================================
define('VIETQR_API_ENDPOINT', 'https://api.vietqr.io/backend/generateQRCode');

/**
 * Tạo URL VietQR cho QR Code Banking
 * 
 * @param string $accountNumber Số tài khoản
 * @param string $bankCode Mã ngân hàng
 * @param int $amount Số tiền (VNĐ)
 * @param string $description Nội dung chuyển khoản (tối đa 100 ký tự)
 * @return array ['success' => bool, 'qr_url' => string, 'error' => string]
 */
function generateVietQR($accountNumber, $bankCode, $amount, $description = '') {
    try {
        // Validation
        if (empty($accountNumber) || empty($bankCode) || $amount < 1000) {
            return ['success' => false, 'error' => 'Thông tin không hợp lệ'];
        }
        
        // Chuẩn bị dữ liệu
        $data = [
            'accountNo' => $accountNumber,
            'accountName' => BANK_ACCOUNT_NAME,
            'acqId' => $bankCode,
            'amount' => $amount,
            'addInfo' => substr($description, 0, 100), // Max 100 chars
            'format' => 'text', // Lấy text để tạo QR, hoặc 'png' cho ảnh
        ];
        
        // Gửi request đến VietQR API
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => VIETQR_API_ENDPOINT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $result = json_decode($response, true);
            if ($result['code'] === '00' && !empty($result['data'])) {
                return [
                    'success' => true,
                    'qr_code' => $result['data'],
                    'qr_url' => 'https://api.vietqr.io/backend/displayQRCode?qr=' . urlencode($result['data']),
                ];
            }
        }
        
        return ['success' => false, 'error' => 'Không thể tạo QR Code'];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Format tiền tệ VNĐ
 */
function formatVND($amount) {
    return number_format($amount, 0, ',', '.') . ' đ';
}

// ============================================
// WEBHOOK CONFIGURATION
// ============================================
define('WEBHOOK_SECRET_KEY', getenv('TECHCOMBANK_WEBHOOK_SECRET') ?: 'default-webhook-secret');
define('WEBHOOK_ENABLED', true);
define('WEBHOOK_LOG_PATH', __DIR__ . '/../../logs/webhook_vietqr.log');

/**
 * Webhook Status Codes
 */
$WEBHOOK_STATUS = [
    'SUCCESS' => 'Thanh toán thành công',
    'PENDING' => 'Chờ xác nhận',
    'FAILED' => 'Thanh toán thất bại',
];

/**
 * Lấy thông tin webhook từ config
 * @return array
 */
function getWebhookConfig() {
    return [
        'url' => 'https://' . $_SERVER['HTTP_HOST'] . '/Trang-nguoi-dung/view/momo/vietqr_webhook.php',
        'secret' => WEBHOOK_SECRET_KEY,
        'enabled' => WEBHOOK_ENABLED,
        'timeout' => 30,
        'retry_count' => 3,
    ];
}

/**
 * Validate Webhook Signature
 * @param string $payload Raw payload
 * @param string $signature Signature từ header
 * @return bool
 */
function verifyWebhookSignature($payload, $signature) {
    $expected = hash_hmac('sha256', $payload, WEBHOOK_SECRET_KEY);
    return hash_equals($expected, $signature);
}

