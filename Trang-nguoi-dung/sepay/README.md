# Hướng dẫn Setup Sepay Payment Gateway cho Galaxy Studio

## 📋 Mục lục
1. [Yêu cầu](#yêu-cầu)
2. [Cấu hình](#cấu-hình)
3. [Database Schema](#database-schema)
4. [Đăng ký Sepay](#đăng-ký-sepay)
5. [Webhook Configuration](#webhook-configuration)
6. [Testing](#testing)
7. [Deploy lên Host](#deploy-lên-host)

---

## 📦 Yêu cầu

- PHP 7.0+
- MySQL 5.7+
- cURL enabled
- Database: `cinepass`

---

## ⚙️ Cấu hình

### Bước 1: Cập nhật `config.php`

File: `Trang-nguoi-dung/sepay/config.php`

```php
// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinepass');

// Bank Account (Đã cấu hình sẵn)
define('BANK_ACCOUNT_NAME', 'GALAXY STUDIO');
define('BANK_ACCOUNT_NUMBER', '0384104942');
define('BANK_CODE', 'MBBANK');
define('BANK_NAME', 'Ngân Hàng TMCP Quân Đội');

// Webhook URL (Thay YOUR_DOMAIN thực)
define('SEPAY_WEBHOOK_URL', 'https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php');
define('SEPAY_RETURN_URL', 'https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_payment_ui.php');

// Email (Nếu muốn gửi email xác nhận)
define('MAIL_FROM_EMAIL', 'your_email@gmail.com');
define('MAIL_FROM_NAME', 'Galaxy Studio');
```

---

## 🗄️ Database Schema

### Cần thêm/sửa bảng:

#### 1. Bảng `ve` (Vé)
```sql
-- Thêm cột nếu chưa có
ALTER TABLE ve ADD COLUMN trang_thai TINYINT(1) DEFAULT 0 COMMENT '0=Unpaid, 1=Paid';
```

#### 2. Bảng `lich_su_thanh_toan` (Lịch sử thanh toán)
```sql
CREATE TABLE IF NOT EXISTS lich_su_thanh_toan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  loai_giao_dich VARCHAR(50), -- 'sepay', 'momo', 'vnpay'
  so_tien DECIMAL(10,2),
  noi_dung VARCHAR(255),
  trang_thai ENUM('pending','success','failed') DEFAULT 'pending',
  tham_chieu VARCHAR(255), -- reference code từ Sepay
  ve_id INT, -- Link đến bảng ve
  thoi_gian TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ve_id) REFERENCES ve(id)
);
```

#### 3. Bảng `taikhoan` (User points)
```sql
-- Thêm cột điểm nếu chưa có
ALTER TABLE taikhoan ADD COLUMN id_diem INT DEFAULT 0 COMMENT 'Loyalty points';
```

---

## 🔐 Đăng ký Sepay

### Bước 1: Tạo tài khoản
1. Vào https://my.sepay.vn
2. Chọn **Đăng ký** → Chọn plan **Free**
3. Điền thông tin → Xác thực

### Bước 2: Liên kết tài khoản ngân hàng
1. Vào **Quản lý tài khoản**
2. Chọn **Thêm tài khoản ngân hàng**
3. Chọn bank: **MB (Quân Đội)**
4. Nhập số tài khoản: `0384104942`
5. Xác thực qua OTP/eToken

### Bước 3: Kiểm tra thông tin
- Webhook sẽ được set trong phần sau

---

## 🔗 Webhook Configuration

### Trong Sepay Dashboard:

1. Vào **Tích hợp** → **Webhooks**
2. Chọn **Thêm Webhooks**
3. Điền thông tin:
   - **Khi tài khoản ngân hàng là:** Chọn tài khoản MB bạn vừa liên kết
   - **Gọi đến URL:** `https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php`
   - **Kiểu chứng thực:** Để trống (sử dụng body validation)
4. Chọn **Lưu**

### Webhook sẽ:
- ✅ Nhận thông tin thanh toán từ Sepay
- ✅ Lưu vé vào database
- ✅ Gửi email xác nhận cho user
- ✅ Tích điểm cho user
- ✅ Cập nhật trạng thái vé thành "Đã thanh toán"

---

## 🧪 Testing

### Local Testing:

1. **Test tạo QR:**
```bash
curl -X POST http://localhost/webphim/Trang-nguoi-dung/sepay/create_payment.php \
  -H "Content-Type: application/json" \
  -d '{"ticket_id": 123, "amount": 500000}'
```

2. **Test check status:**
```bash
curl -X POST http://localhost/webphim/Trang-nguoi-dung/sepay/check_payment_status.php \
  -H "Content-Type: application/json" \
  -d '{"ticket_id": 123}'
```

3. **Test webhook (simulate Sepay):**
```bash
curl -X POST http://localhost/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php \
  -H "Content-Type: application/json" \
  -d '{
    "gateway": "MBBank",
    "transactionDate": "2025-12-04 14:30:00",
    "accountNumber": "0384104942",
    "transferType": "in",
    "transferAmount": 500000,
    "accumulated": 5000000,
    "content": "Thanh toán VE123",
    "referenceCode": "MB123456789"
  }'
```

### Giao diện UI:
```
http://localhost/webphim/Trang-nguoi-dung/sepay/sepay_payment_ui.php?ticket_id=123&amount=500000
```

---

## 🚀 Deploy lên Host

### Bước 1: Cập nhật config.php với domain thực

```php
define('SEPAY_WEBHOOK_URL', 'https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php');
define('SEPAY_RETURN_URL', 'https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_payment_ui.php');
```

### Bước 2: Upload file lên host
```
/sepay/
  ├── config.php (✅ Updated with real domain)
  ├── sepay_webhook.php
  ├── sepay_payment_ui.php
  ├── check_payment_status.php
  ├── create_payment.php
  └── db_connect.php (cũ, có thể xóa)
```

### Bước 3: Cấp quyền thư mục
```bash
chmod 755 /webphim/Trang-nguoi-dung/sepay/
chmod 644 /webphim/Trang-nguoi-dung/sepay/*.php
```

### Bước 4: Update Sepay Webhook URL
Vào **Sepay Dashboard** → **Webhooks** → Sửa URL thành domain thực:
```
https://yourdomain.com/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php
```

### Bước 5: Test trên Host
1. Quét QR code thanh toán
2. Kiểm tra webhook logs: `/webphim/Trang-nguoi-dung/sepay/webhook_logs.txt`
3. Kiểm tra database: Vé status = 1 (Paid)

---

## 📝 Workflow

### User Flow:
```
1. User chọn vé → Chọn thanh toán "Sepay"
2. Hiển thị QR code → User quét bằng app ngân hàng
3. User thanh toán thành công
4. Sepay gửi webhook → Hệ thống cập nhật vé
5. Gửi email xác nhận + tích điểm
6. UI tự động refresh → Hiển thị "Thanh toán thành công"
```

### Backend Flow:
```
User QR Scan
    ↓
Bank transfers money
    ↓
Sepay receives transaction
    ↓
Sepay sends webhook
    ↓
sepay_webhook.php
    ├─ Verify amount ✓
    ├─ Update ve.trang_thai = 1
    ├─ Add points to user
    ├─ Send confirmation email
    └─ Log transaction
    ↓
Database updated ✓
```

---

## 🔍 Troubleshooting

### Webhook không nhận được
- [ ] Kiểm tra URL có public không (không phải localhost)
- [ ] Check firewall cho phép POST 443
- [ ] Kiểm tra logs: `webhook_logs.txt`
- [ ] Test webhook lại trong Sepay Dashboard

### VE không cập nhật trạng thái
- [ ] Kiểm tra ticket ID đúng không (VE123)
- [ ] Kiểm tra amount match (số tiền)
- [ ] Check database có bảng `lich_su_thanh_toan` không
- [ ] Xem logs: `webhook_logs.txt`

### Email không gửi
- [ ] Enable `php_mail()` trên host
- [ ] Hoặc config SMTP (xem phần Email trong config.php)

---

## 📞 Support

- Sepay Docs: https://docs.sepay.vn
- Webhook: https://docs.sepay.vn/tich-hop-webhooks.html

---

**Đã setup xong! 🎉 Deploy lên host 1 lần là dùng được ngay!**
