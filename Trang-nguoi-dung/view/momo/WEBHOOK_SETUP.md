<?php
/**
 * VietQR Webhook Setup Guide
 * Hướng dẫn cấu hình webhook với Techcombank
 */

/**
 * ============================================
 * BƯỚC 1: ĐĂNG KÝ WEBHOOK VỚI TECHCOMBANK
 * ============================================
 * 
 * 1. Đăng nhập Techcombank Business Portal:
 *    https://portal.techcombank.com.vn/business
 * 
 * 2. Vào: API Management → Webhook Configuration
 * 
 * 3. Thêm Webhook mới:
 *    - Webhook URL: https://webphim.com/Trang-nguoi-dung/view/momo/vietqr_webhook.php
 *    - Event Types: Transfer Received (Nhận tiền chuyển khoản)
 *    - Status: Active
 *    - Retry Policy: 3 lần (mỗi 5 phút)
 * 
 * 4. Copy Secret Key: [SECRET_KEY]
 * 
 * ============================================
 * BƯỚC 2: LƯU SECRET KEY
 * ============================================
 * 
 * Tạo file: .env (trong thư mục root)
 * Thêm dòng:
 * TECHCOMBANK_WEBHOOK_SECRET=your-secret-key-here
 * 
 * HOẶC cập nhật file config:
 */

// File: Trang-nguoi-dung/config/webhook.php
return [
    'techcombank' => [
        'webhook_url' => 'https://webphim.com/Trang-nguoi-dung/view/momo/vietqr_webhook.php',
        'secret_key' => 'your-secret-key-from-techcombank',
        'enabled' => true,
        'timeout' => 30,
        'retry' => 3
    ]
];

/**
 * ============================================
 * BƯỚC 3: WEBHOOK PAYLOAD FORMAT
 * ============================================
 * 
 * Techcombank sẽ gửi POST request với body:
 * 
 * {
 *   "transactionId": "TXN202511210001",
 *   "amount": 150000,
 *   "description": "Dat ve phim #12345",
 *   "toAccount": "79799999889",
 *   "toName": "CINEPASS CINEMA",
 *   "fromAccount": "1234567890",
 *   "fromName": "NGUYEN VAN A",
 *   "status": "SUCCESS",
 *   "timestamp": "2025-11-21T14:30:00Z",
 *   "bankCode": "TCB"
 * }
 * 
 * Status có thể là: SUCCESS, PENDING, FAILED
 * 
 * ============================================
 * BƯỚC 4: WEBHOOK RESPONSE
 * ============================================
 * 
 * Server phải response:
 * 
 * {
 *   "status": "success",
 *   "message": "Payment confirmed",
 *   "order_id": 12345,
 *   "transaction_id": "TXN202511210001"
 * }
 * 
 * HTTP Status: 200 OK
 * 
 * Nếu lỗi, Techcombank sẽ retry
 * 
 * ============================================
 * BƯỚC 5: KIỂM TRA WEBHOOK LOG
 * ============================================
 * 
 * Tất cả webhook được log tại:
 * logs/webhook_vietqr.log
 * 
 * Xem log:
 * tail -f logs/webhook_vietqr.log
 * 
 * ============================================
 * BƯỚC 6: TEST WEBHOOK
 * ============================================
 * 
 * Techcombank cung cấp test interface trong portal:
 * 
 * 1. Vào: API Management → Webhook → Test
 * 2. Chọn event: "Transfer Received"
 * 3. Điền test data:
 *    - Amount: 150000
 *    - Description: Dat ve phim #12345
 *    - From Account: 1111111111
 *    - From Name: Test Customer
 * 4. Click "Send Test"
 * 5. Kiểm tra response là 200 OK
 * 
 * ============================================
 * BƯỚC 7: CẬP NHẬT DATABASE SCHEMA
 * ============================================
 * 
 * Chạy SQL sau:
 */

$sql_create_payment_log = "
CREATE TABLE IF NOT EXISTS payment_log (
  id INT PRIMARY KEY AUTO_INCREMENT,
  transaction_id VARCHAR(100) UNIQUE NOT NULL,
  order_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(50) DEFAULT 'vietqr',
  status VARCHAR(50),
  response_data JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES hoa_don(id),
  INDEX idx_transaction_id (transaction_id),
  INDEX idx_order_id (order_id),
  INDEX idx_created_at (created_at)
);
";

$sql_alter_hoa_don = "
ALTER TABLE hoa_don 
ADD COLUMN IF NOT EXISTS phuong_thuc VARCHAR(50) DEFAULT 'cash',
ADD COLUMN IF NOT EXISTS ngay_thanh_toan TIMESTAMP NULL;
";

$sql_alter_ve = "
ALTER TABLE ve 
MODIFY COLUMN trang_thai INT DEFAULT 0 COMMENT '0=pending, 1=paid, 2=used, 3=cancelled, 4=expired';
";

/**
 * ============================================
 * BƯỚC 8: KIỂM TRA HỆ THỐNG
 * ============================================
 * 
 * 1. Kiểm tra folder logs tồn tại:
 *    c:\xampp\htdocs\webphim\Trang-nguoi-dung\logs\
 * 
 * 2. Kiểm tra file webhook.php hoạt động:
 *    curl -X POST https://webphim.com/Trang-nguoi-dung/view/momo/vietqr_webhook.php \
 *      -H "Content-Type: application/json" \
 *      -H "X-Signature: abc123" \
 *      -d '{"transactionId":"TEST","amount":1000,"toAccount":"79799999889","status":"SUCCESS"}'
 * 
 * 3. Xem log: logs/webhook_vietqr.log
 * 
 * ============================================
 * BƯỚC 9: MONITORING & ALERTS
 * ============================================
 * 
 * Cách kiểm tra thanh toán thất bại:
 * 
 * SELECT * FROM payment_log WHERE status = 'failed' ORDER BY created_at DESC;
 * 
 * Cách retry thanh toán:
 * 
 * UPDATE hoa_don SET trang_thai = 'pending' WHERE id = [ORDER_ID];
 * DELETE FROM payment_log WHERE order_id = [ORDER_ID];
 * 
 * ============================================
 * GHI CHÚ QUAN TRỌNG
 * ============================================
 * 
 * 1. SECURITY:
 *    - Luôn validate signature webhook
 *    - Không bao giờ log sensitive data
 *    - Dùng HTTPS cho webhook URL
 * 
 * 2. IDEMPOTENCY:
 *    - Kiểm tra transaction_id tồn tại trước khi xử lý
 *    - Tránh xử lý 2 lần cùng 1 transaction
 * 
 * 3. TIMEOUT:
 *    - Webhook phải response trong 30 giây
 *    - Nếu quá lâu, Techcombank sẽ timeout & retry
 * 
 * 4. DOCUMENTATION:
 *    - Techcombank docs: https://developer.techcombank.com/api-reference
 *    - VietQR docs: https://vietqr.io
 * 
 * ============================================
 */

?>
