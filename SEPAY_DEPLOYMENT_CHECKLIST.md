# 🚀 SEPAY INTEGRATION - DEPLOYMENT CHECKLIST

**Status**: ✅ Ready for Production Deployment  
**Last Updated**: 2025-12-04  
**Version**: 1.0

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### 1. Database Schema ✅
Chạy các lệnh SQL này trên host **TRƯỚC** khi deploy:

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
  FOREIGN KEY (`id_ve`) REFERENCES `ve`(`id`),
  FOREIGN KEY (`id_tk`) REFERENCES `taikhoan`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Thêm cột id_diem vào bảng taikhoan (nếu chưa có)
ALTER TABLE `taikhoan` ADD COLUMN `id_diem` INT DEFAULT 0 COMMENT 'Điểm hiện có' AFTER `diem_tich_luy`;
```

### 2. File Structure ✅
Đảm bảo tất cả file đã được upload:

```
✓ Trang-nguoi-dung/sepay/
  ├── config.php                    (cấu hình Sepay + Email)
  ├── sepay_webhook.php             (xử lý webhook từ Sepay)
  ├── sepay_payment_ui.php          (hiển thị QR code)
  ├── check_payment_status.php       (kiểm tra trạng thái thanh toán)
  ├── create_payment.php            (tạo payment QR)
  ├── db_connect.php                (kết nối database)
  ├── order.php                     (xử lý order)
  └── README.md                     (hướng dẫn)

✓ Trang-nguoi-dung/view/
  └── thanhtoan.php                 (đã thêm Sepay button)
```

### 3. Configuration ✅
Kiểm tra `config.php` đã cấu hình đúng:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinepass');

define('BANK_ACCOUNT_NUMBER', '0384104942');
define('BANK_CODE', 'MBBANK');

define('SEPAY_WEBHOOK_URL', 'https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php');
define('SEPAY_RETURN_URL', 'https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_return.php');

define('DOMAIN', 'https://webphim.gt.tc');

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'phanthienkhai2901@gmail.com');
define('MAIL_PASSWORD', 'nvyh agju zvnp nacz');
define('MAIL_FROM_EMAIL', 'phanthienkhai2901@gmail.com');

define('POINTS_PER_VND', 0.01);
define('POINTS_BONUS_RATE', 1.0);
```

### 4. Sepay Webhook Registration ✅
Đảm bảo webhook đã được đăng ký trên Sepay Dashboard:

```
Webhook ID: 18954
Tên: GALAXY CINEMA
URL: https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php
Trạng thái: Kích hoạt ✅
Kiểu chứng thực: Không cần chứng thực
Content Type: application/json
```

---

## 🔧 DEPLOYMENT STEPS

### Step 1: Upload File
```bash
# Upload tất cả file Sepay lên thư mục Trang-nguoi-dung/sepay/
scp -r Trang-nguoi-dung/sepay/ user@host:/var/www/webphim/Trang-nguoi-dung/
```

### Step 2: Update Database
```bash
# Login vào phpmyadmin hoặc MySQL CLI
mysql -u root -p cinepass < schema.sql
```

### Step 3: Verify Permissions
```bash
# Đảm bảo file webhook có quyền write để ghi log
chmod 755 Trang-nguoi-dung/sepay/
touch Trang-nguoi-dung/sepay/webhook_logs.txt
chmod 666 Trang-nguoi-dung/sepay/webhook_logs.txt
```

### Step 4: Test Webhook
```bash
# Test webhook bằng curl
curl -X POST https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php \
  -H "Content-Type: application/json" \
  -d '{
    "gateway": "MB",
    "transactionDate": "2025-12-04 14:30:00",
    "accountNumber": "0384104942",
    "transferType": "in",
    "transferAmount": 500000,
    "accumulated": 500000,
    "content": "VE1",
    "referenceCode": "TEST123"
  }'
```

---

## ✅ FUNCTIONALITY CHECKLIST

### Payment Flow
- [ ] Khách hàng đặt vé thành công
- [ ] Trang thanh toán hiển thị nút "Sepay"
- [ ] Click "Sepay" → Hiển thị QR code
- [ ] QR code chứa thông tin đúng (số tiền, mã vé VE{id})
- [ ] Auto-check payment status mỗi 3 giây
- [ ] Khi khách hàng chuyển khoản:
  - [ ] Sepay gửi webhook tới server
  - [ ] Webhook xử lý:
    - [ ] Extract ticket ID từ nội dung
    - [ ] Verify số tiền khớp
    - [ ] Update `ve.trang_thai = 1`
    - [ ] Tính và thêm điểm vào `taikhoan.id_diem`
    - [ ] Lưu record vào `lich_su_thanh_toan_ve`
    - [ ] Gửi email xác nhận tới khách hàng
  - [ ] UI cập nhật: "Thanh toán thành công"

### Email Confirmation
- [ ] Cấu hình Gmail App Password đúng
- [ ] Email gửi thành công tới khách hàng
- [ ] Email chứa thông tin vé (phim, rạp, ghế, điểm)

### Point System
- [ ] Khi thanh toán thành công, điểm được thêm vào tài khoản
- [ ] Công thức: `floor(amount * 0.01)` điểm
- [ ] Ví dụ: 500,000 VND → 5,000 điểm

### Error Handling
- [ ] Nếu số tiền không khớp → Webhook reject
- [ ] Nếu vé không tồn tại → Webhook reject
- [ ] Nếu vé đã thanh toán → Webhook reject
- [ ] Lỗi được ghi vào `webhook_logs.txt`

---

## 📝 MONITORING & DEBUGGING

### Check Webhook Logs
```bash
tail -f Trang-nguoi-dung/sepay/webhook_logs.txt
```

### Verify Database Changes
```sql
-- Kiểm tra vé đã thanh toán
SELECT id, trang_thai, price FROM ve WHERE id = 1;

-- Kiểm tra lịch sử thanh toán
SELECT * FROM lich_su_thanh_toan_ve WHERE id_ve = 1;

-- Kiểm tra điểm của user
SELECT id, name, id_diem FROM taikhoan WHERE id = 17;
```

### Common Issues

**Issue 1: Webhook không nhận được**
- Kiểm tra URL trong Sepay Dashboard
- Kiểm tra file permissions
- Kiểm tra firewall/port 443

**Issue 2: Email không gửi**
- Kiểm tra Gmail App Password trong config.php
- Kiểm tra email debug logs
- Kiểm tra SMTP port 587 open

**Issue 3: Điểm không được thêm**
- Kiểm tra cột `id_diem` tồn tại trong bảng `taikhoan`
- Kiểm tra webhook logs xem có lỗi gì

---

## 🚨 IMPORTANT NOTES

1. **Database Migration**: Nếu deploy từ dev sang production, **PHẢI** chạy SQL schema trước
2. **Email Configuration**: Đảm bảo Gmail App Password đúng, không phải Gmail password chính
3. **Domain Configuration**: Cập nhật DOMAIN và WEBHOOK_URL nếu thay đổi domain
4. **Webhook Registration**: Webhook ID 18954 chỉ hoạt động với bank account 0384104942
5. **File Permissions**: Folder `sepay/` cần có quyền write để ghi webhook logs

---

## 📞 SUPPORT

Nếu có vấn đề:
1. Kiểm tra `webhook_logs.txt` để xem lỗi chi tiết
2. Test webhook bằng curl command
3. Verify database schema
4. Kiểm tra email configuration

---

**Deployment Status**: ✅ Ready  
**Next Step**: Execute SQL schema + Upload files + Test with real payment
