# VietQR Banking - Webhook Integration Guide

## 📋 Tổng Quan

VietQR là hệ thống thanh toán QR Code Banking cho phép khách hàng chuyển khoản trực tiếp từ app ngân hàng của họ. Hệ thống webhook sẽ tự động nhận thông báo khi có chuyển khoản vào tài khoản của bạn.

## ✅ Đã Hoàn Tất

```
✓ vietqr_config.php         - Cấu hình Techcombank (79799999889)
✓ xuly_vietqr.php           - Trang hiển thị QR Code
✓ vietqr_confirm.php        - Trang xác nhận thanh toán
✓ vietqr_webhook.php        - Handler webhook từ Techcombank
✓ vietqr_webhook_test.php   - Script test webhook
✓ logs/                      - Folder lưu log
✓ thanhtoan.php             - Đã thêm nút VietQR
```

## 🔧 Cấu Hình Webhook

### Bước 1: Tạo Secret Key

Tạo file `.env` trong thư mục root `webphim/`:

```
TECHCOMBANK_WEBHOOK_SECRET=your-ultra-secret-key-here
```

Ví dụ an toàn:
```
TECHCOMBANK_WEBHOOK_SECRET=tcb_webhook_cinepass_2025_v1_abc123def456
```

### Bước 2: Đăng Ký Webhook với Techcombank

1. **Đăng nhập Portal**: https://portal.techcombank.com.vn/business
2. **Vào**: Settings → API & Webhooks → Register Webhook
3. **Điền thông tin**:
   - **Webhook URL**: `https://webphim.com/Trang-nguoi-dung/view/momo/vietqr_webhook.php`
   - **Event Type**: `transfer.received` (Nhận tiền chuyển khoản)
   - **Content Type**: `application/json`
   - **Secret Key**: (Dùng key bạn tạo ở Bước 1)
   - **Status**: Activate

4. **Copy Webhook ID** từ Techcombank (dùng để test/debug)

### Bước 3: Load Secret Key từ .env

Cập nhật `xuly_vietqr.php` hoặc tạo file `config/env.php`:

```php
<?php
// config/env.php
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}
?>
```

Sau đó trong `vietqr_config.php`:
```php
require_once __DIR__ . '/../../config/env.php';
define('WEBHOOK_SECRET_KEY', getenv('TECHCOMBANK_WEBHOOK_SECRET') ?: 'default-webhook-secret');
```

## 🧪 Test Webhook

### Cách 1: Test Script (Recommended)

```bash
php vietqr_webhook_test.php
```

Hoặc browser:
```
http://localhost/webphim/Trang-nguoi-dung/view/momo/vietqr_webhook_test.php
```

Output:
```
=== TEST 1: PAYMENT SUCCESS ===

Payload: {
  "transactionId": "TXN202511210001",
  "amount": 150000,
  ...
}
HTTP Status: 200
Response: {"status":"success","message":"Payment confirmed",...}

=== TEST 2: PAYMENT PENDING ===
...
```

### Cách 2: Test từ Techcombank Portal

1. Vào webhook settings
2. Click "Send Test"
3. Điền test data
4. Kiểm tra response là 200 OK

### Cách 3: Curl Command

```bash
curl -X POST https://webphim.com/Trang-nguoi-dung/view/momo/vietqr_webhook.php \
  -H "Content-Type: application/json" \
  -H "X-Signature: abc123" \
  -d '{
    "transactionId": "TXN202511210001",
    "amount": 150000,
    "description": "Dat ve phim #12345",
    "toAccount": "79799999889",
    "status": "SUCCESS",
    "timestamp": "2025-11-21T14:30:00Z"
  }'
```

## 📊 Webhook Payload Format

Techcombank sẽ gửi POST request với body sau:

```json
{
  "transactionId": "TXN202511210001",
  "amount": 150000,
  "description": "Dat ve phim #12345",
  "toAccount": "79799999889",
  "toName": "CINEPASS CINEMA",
  "fromAccount": "1111111111",
  "fromName": "NGUYEN VAN A",
  "status": "SUCCESS",
  "timestamp": "2025-11-21T14:30:00Z",
  "bankCode": "TCB"
}
```

**Status có thể là**: `SUCCESS`, `PENDING`, `FAILED`

**Description format**: `Dat ve phim #[ORDER_ID]`
- Webhook sẽ parse ORDER_ID từ description

## 🔄 Webhook Flow

```
1. Khách hàng quét QR → Transfer tiền vào 79799999889
                        ↓
2. Techcombank API phát hiện chuyển khoản
                        ↓
3. POST request tới vietqr_webhook.php
   Headers: X-Signature: [hash]
   Body: {transactionId, amount, description, status, ...}
                        ↓
4. Webhook handler validate signature & xử lý:
   ✓ Kiểm tra order tồn tại
   ✓ Validate số tiền
   ✓ Update hóa đơn: trang_thai = 'paid'
   ✓ Update vé: trang_thai = 1 (đã thanh toán)
   ✓ Lưu log payment_log table
                        ↓
5. Response 200 OK
   {
     "status": "success",
     "message": "Payment confirmed",
     "order_id": 12345,
     "transaction_id": "TXN202511210001"
   }
```

## 🗄️ Database Schema

Cần chạy SQL sau:

```sql
-- Tạo bảng log payment
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

-- Cập nhật hoa_don table
ALTER TABLE hoa_don 
ADD COLUMN IF NOT EXISTS phuong_thuc VARCHAR(50) DEFAULT 'cash',
ADD COLUMN IF NOT EXISTS ngay_thanh_toan TIMESTAMP NULL;

-- Cập nhật ve table (nếu cần)
ALTER TABLE ve 
MODIFY COLUMN trang_thai INT DEFAULT 0 COMMENT '0=pending, 1=paid, 2=used, 3=cancelled, 4=expired';
```

## 📝 Monitoring & Debugging

### Xem Webhook Log

```bash
# Windows
type logs\webhook_vietqr.log

# Linux
tail -f logs/webhook_vietqr.log
```

Log format:
```
[2025-11-21 14:30:00] Webhook received
Data: {...payload...}
[2025-11-21 14:30:00] Processing: TxnID=TXN202511210001, Amount=150000, Status=SUCCESS
  OrderID: 12345
  Order found: Amount=150000, Status=pending
  ✅ Order marked as PAID
```

### Debug Failed Webhooks

1. Kiểm tra signature không hợp lệ:
   ```sql
   SELECT * FROM payment_log WHERE status = 'error' ORDER BY created_at DESC LIMIT 5;
   ```

2. Retry failed webhook:
   ```sql
   UPDATE hoa_don SET trang_thai = 'pending' WHERE id = [ORDER_ID];
   DELETE FROM payment_log WHERE order_id = [ORDER_ID];
   ```

3. Manual confirm payment:
   ```sql
   UPDATE hoa_don SET trang_thai = 'paid', phuong_thuc = 'vietqr', ngay_thanh_toan = NOW() WHERE id = [ORDER_ID];
   UPDATE ve SET trang_thai = 1 WHERE ma_hoa_don = [ORDER_ID];
   ```

## ⚠️ Lưu Ý An Toàn

1. **HTTPS bắt buộc**: Webhook URL phải dùng HTTPS, không HTTP
2. **Validate signature**: Luôn kiểm tra X-Signature header
3. **Idempotency**: Không xử lý cùng 1 transaction 2 lần
4. **Timeout**: Response phải < 30 giây, nếu không Techcombank sẽ retry
5. **Sensitive data**: Không log customer bank account trong file log
6. **Retry policy**: Techcombank retry 3 lần khi webhook timeout

## 🚀 Production Deployment

Checklist trước deploy:

- [ ] `.env` file được tạo với TECHCOMBANK_WEBHOOK_SECRET
- [ ] Webhook URL đã đăng ký với Techcombank
- [ ] SSL/HTTPS được enable
- [ ] `logs/` folder tồn tại và writable
- [ ] Database migration đã chạy
- [ ] Test webhook thành công
- [ ] Xem log không có lỗi
- [ ] payment_log table tồn tại
- [ ] hoa_don & ve table đã update schema

## 📞 Techcombank Support

- **Developer Portal**: https://developer.techcombank.com
- **API Docs**: https://developer.techcombank.com/api-reference
- **Webhook Docs**: https://developer.techcombank.com/webhooks
- **Support Email**: api-support@techcombank.com

## 🔗 File Liên Quan

| File | Mục đích |
|------|---------|
| `vietqr_config.php` | Cấu hình + helper functions |
| `xuly_vietqr.php` | Trang QR Code |
| `vietqr_confirm.php` | Trang xác nhận (UI) |
| `vietqr_webhook.php` | **Webhook handler (quan trọng!)** |
| `vietqr_webhook_test.php` | Test script |
| `thanhtoan.php` | Trang chọn phương thức (đã thêm VietQR) |
| `logs/webhook_vietqr.log` | Webhook log file |

---

**Cập nhật lần cuối**: 2025-11-21
**Version**: 1.0 (Beta)
