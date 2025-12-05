# 🚀 SEPAY DEPLOYMENT - FINAL CHECKLIST

**Ngày**: 2025-12-04  
**Status**: ✅ SẴN SÀNG DEPLOY  
**Mục tiêu**: Khách hàng nhấn thanh toán Sepay → Thanh toán thật → Vé tự động được tạo + Email + Điểm

---

## ✅ HOÀN THÀNH (Localhost Testing)

### 1. ✅ Database Schema
- [x] Bảng `lich_su_thanh_toan_ve` (lịch sử thanh toán vé)
- [x] Cột `id_diem` trong bảng `taikhoan` (điểm hiện có)
- [x] Webhook xử lý đúng table names (`lichchieu`, `khung_gio_chieu`, v.v.)

### 2. ✅ Sepay Files
- [x] `config.php` - Cấu hình đầy đủ (email, database, domain, webhook URL)
- [x] `sepay_webhook.php` - Webhook xử lý thanh toán (7 bước)
- [x] `sepay_payment_ui.php` - Hiển thị QR code + auto-check
- [x] `check_payment_status.php` - API kiểm tra trạng thái vé
- [x] `create_payment.php` - Tạo payment QR
- [x] `db_connect.php` - Kết nối database
- [x] `README.md` - Hướng dẫn setup

### 3. ✅ UI Integration
- [x] Sepay button thêm vào `thanhtoan.php`
- [x] CSS styling cho Sepay button
- [x] JavaScript function `initiateSepayPayment()`

### 4. ✅ Sepay Webhook Registration
- [x] Webhook ID: 18954
- [x] URL: https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php
- [x] Status: Kích hoạt ✅

### 5. ✅ Email Configuration
- [x] Email: phanthienkhai2901@gmail.com
- [x] App Password: nvyh agju zvnp nacz
- [x] SMTP: smtp.gmail.com:587
- [x] HTML email template với thông tin vé + điểm

---

## 📋 DEPLOYMENT STEPS (Khi Deploy Lên Host)

### Step 1: Upload Files
```bash
Upload tất cả files từ folder:
  ✓ Trang-nguoi-dung/sepay/          (8 files)
  ✓ Trang-nguoi-dung/view/thanhtoan.php   (đã cập nhật)
```

### Step 2: Execute SQL Schema
**Chạy trên host phpMyAdmin:**

```sql
-- 1. Tạo bảng lịch sử thanh toán vé
CREATE TABLE IF NOT EXISTS `lich_su_thanh_toan_ve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ve` int(11) NOT NULL,
  `id_tk` int(11) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `phuong_thuc` varchar(50) DEFAULT 'sepay',
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `ma_gd_sepay` varchar(100) DEFAULT NULL,
  `noi_dung_chuyen_khoan` varchar(255) DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT current_timestamp(),
  `ghi_chu` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_ve` (`id_ve`),
  KEY `id_tk` (`id_tk`),
  FOREIGN KEY (`id_ve`) REFERENCES `ve`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_tk`) REFERENCES `taikhoan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment history for tickets';

-- 2. Thêm cột id_diem vào taikhoan (nếu chưa có)
ALTER TABLE `taikhoan` ADD COLUMN `id_diem` INT DEFAULT 0 COMMENT 'Điểm hiện có' AFTER `diem_tich_luy`;
```

### Step 3: Verify Configuration
**Kiểm tra `config.php`:**
```php
✓ DB_HOST = 'localhost' (hoặc hostname host)
✓ DB_USER = 'root' (hoặc username)
✓ DB_PASS = '' (hoặc password)
✓ DB_NAME = 'cinepass'
✓ BANK_ACCOUNT_NUMBER = '0384104942'
✓ BANK_CODE = 'MBBANK'
✓ SEPAY_WEBHOOK_URL = 'https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php'
✓ MAIL_USERNAME = 'phanthienkhai2901@gmail.com'
✓ MAIL_PASSWORD = 'nvyh agju zvnp nacz'
```

### Step 4: File Permissions
```bash
chmod 755 Trang-nguoi-dung/sepay/
chmod 666 Trang-nguoi-dung/sepay/webhook_logs.txt
```

### Step 5: Test Webhook
**Kiểm tra webhook nhận được từ Sepay:**
```bash
# Tạo test request
curl -X POST https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php \
  -H "Content-Type: application/json" \
  -d '{
    "gateway": "MB",
    "transactionDate": "2025-12-04 14:30:00",
    "accountNumber": "0384104942",
    "transferType": "in",
    "transferAmount": 400000,
    "accumulated": 400000,
    "content": "VE123",
    "referenceCode": "TEST123"
  }'
```

---

## 🎯 QWORKING FLOW KHI DEPLOY THẬT

### Khách hàng:
```
1. Vào Trang-nguoi-dung
2. Chọn phim, ghế, combo
3. Nhấn "Thanh toán"
4. Chọn "Sepay" (nút xanh)
5. → Trang QR code hiển thị
6. Mở app ngân hàng
7. Quét QR code
8. Kiểm tra: GALAXY STUDIO, 0384104942, giá tiền, nội dung VE{id}
9. Nhập PIN/Biometric
10. Xác nhận thanh toán
11. Chờ 3-5 giây
12. UI cập nhật: "Thanh toán thành công ✅"
13. Kiểm tra email để lấy mã vé
```

### Backend (Tự động):
```
1. Khách hàng chuyển khoản
2. Sepay gửi webhook → sepay_webhook.php
3. Webhook xử lý 7 bước:
   ✓ Extract ticket ID
   ✓ Verify số tiền
   ✓ Update ve.trang_thai = 1
   ✓ Tính điểm (amount * 0.01)
   ✓ Thêm vào taikhoan.id_diem
   ✓ Lưu lịch sử → lich_su_thanh_toan_ve
   ✓ Gửi email xác nhận
4. UI auto-refresh phát hiện payment success
5. Hiển thị "Thanh toán thành công"
```

---

## 🔍 MONITORING & VERIFICATION

### Kiểm tra Logs
```bash
# Xem webhook logs
tail -f Trang-nguoi-dung/sepay/webhook_logs.txt

# Expected logs:
# 2025-12-04 14:30:00 Webhook received: {...}
# 2025-12-04 14:30:00 Ticket ID extracted: 123
# 2025-12-04 14:30:00 Ticket found: ID=123, Price=400000
# 2025-12-04 14:30:00 Ticket updated to paid: ID=123
# 2025-12-04 14:30:00 Points added: user_id=17, points=4000
# 2025-12-04 14:30:01 ✓ SUCCESS: Ticket ID=123 payment processed
```

### Verify Database Changes
```sql
-- Kiểm tra vé đã thanh toán
SELECT id, price, trang_thai FROM ve WHERE id = 123;
-- Expected: trang_thai = 1 (đã thanh toán)

-- Kiểm tra điểm được thêm
SELECT id, name, id_diem FROM taikhoan WHERE id = 17;
-- Expected: id_diem = 4000 (400000 * 0.01)

-- Kiểm tra lịch sử thanh toán
SELECT * FROM lich_su_thanh_toan_ve WHERE id_ve = 123;
-- Expected: Có record với status = 'success'
```

---

## ⚠️ IMPORTANT NOTES

### 1. Database Migration
**PHẢI** chạy SQL schema trước upload code!
```
Nếu bỏ qua bước này → Webhook lỗi → Thanh toán không hoạt động
```

### 2. Email Configuration
Cần đảm bảo Gmail App Password đúng:
```
MAIL_PASSWORD = 'nvyh agju zvnp nacz'
(Không phải Gmail password chính)
```

### 3. Webhook Registration
Webhook ID 18954 chỉ hoạt động với:
```
Tài khoản: 0384104942
Ngân hàng: MB (Quân Đội)
URL: https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php
```

### 4. Domain Configuration
Nếu thay đổi domain:
```
Cập nhật lại:
- SEPAY_WEBHOOK_URL trong config.php
- Webhook URL trong Sepay Dashboard
```

### 5. File Permissions
```
Folder sepay/ cần write permission để ghi webhook_logs.txt
```

---

## 🚨 TROUBLESHOOTING

### Issue 1: Webhook không nhận được
```
❌ Kiểm tra:
- URL trong Sepay Dashboard đúng chưa?
- File permissions có write?
- Firewall/Port 443 open?
- Domain accessible từ internet?
```

### Issue 2: Email không gửi
```
❌ Kiểm tra:
- MAIL_PASSWORD đúng?
- Gmail account active?
- SMTP port 587 open?
- Check webhook_logs.txt có error gì
```

### Issue 3: Vé không update
```
❌ Kiểm tra:
- SQL schema executed?
- Webhook logs ghi gì?
- Database table names đúng?
- Webhook response 200 OK?
```

### Issue 4: Điểm không được thêm
```
❌ Kiểm tra:
- Cột id_diem tồn tại trong taikhoan?
- User ID trong webhook đúng?
- Points calculation: amount * 0.01?
```

---

## ✅ FINAL CHECKLIST TRƯỚC DEPLOY

- [ ] Upload tất cả files Sepay
- [ ] Execute SQL schema (2 lệnh)
- [ ] Verify config.php cấu hình đúng
- [ ] Set file permissions 755 + 666
- [ ] Test webhook lần đầu (curl)
- [ ] Kiểm tra webhook logs
- [ ] Verify database tables
- [ ] Đảm bảo Sepay Webhook ID 18954 active
- [ ] Test thanh toán thật với khách hàng
- [ ] Kiểm tra email nhận được
- [ ] Verify vé được tạo trong database
- [ ] Verify điểm được thêm vào taikhoan

---

## 📞 SUPPORT

**Khi deploy xong, nếu có lỗi:**

1. Kiểm tra `webhook_logs.txt`
2. Chạy test curl để verify webhook
3. Kiểm tra database tables
4. Verify config.php settings
5. Kiểm tra email configuration

---

**Status**: ✅ READY FOR PRODUCTION DEPLOYMENT

**Next Step**: Deploy lên host + Execute SQL + Test thanh toán thật

---
