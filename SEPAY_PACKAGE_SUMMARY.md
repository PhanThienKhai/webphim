# 📦 SEPAY DEPLOYMENT PACKAGE

**Ngày tạo**: 2025-12-04  
**Version**: 1.0 Production Ready  
**Mục đích**: Thanh toán QR Code Bank Transfer qua Sepay

---

## 📂 FILES TO DEPLOY

### Folder: `Trang-nguoi-dung/sepay/`
```
📁 Trang-nguoi-dung/sepay/
├── config.php                      ← Configuration (Email, DB, Domain, Webhook)
├── sepay_webhook.php               ← Webhook handler (7-step payment processing)
├── sepay_payment_ui.php            ← QR code display + auto-check
├── check_payment_status.php        ← API: Check if ve.trang_thai = 1
├── create_payment.php              ← API: Generate payment QR URL
├── db_connect.php                  ← Database connection
├── order.php                       ← Order processing
├── README.md                       ← Setup guide
└── webhook_logs.txt                ← (Will be created after first webhook)
```

### File: `Trang-nguoi-dung/view/thanhtoan.php`
```
✓ Already updated with:
  - Sepay button (🏦 icon, green #059669)
  - CSS styling for .payment-sepay
  - JavaScript function initiateSepayPayment()
```

---

## 🗄️ DATABASE SCHEMA TO EXECUTE

### 1. Create Payment History Table
```sql
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. Add Points Column to User Account
```sql
ALTER TABLE `taikhoan` ADD COLUMN `id_diem` INT DEFAULT 0 COMMENT 'Điểm hiện có' AFTER `diem_tich_luy`;
```

---

## ⚙️ CONFIGURATION

### File: `Trang-nguoi-dung/sepay/config.php`

**Database Configuration:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinepass');
```

**Bank Account (Sepay):**
```php
define('BANK_ACCOUNT_NAME', 'GALAXY STUDIO');
define('BANK_ACCOUNT_NUMBER', '0384104942');
define('BANK_CODE', 'MBBANK');
define('BANK_NAME', 'Ngân Hàng TMCP Quân Đội');
```

**Sepay Webhook:**
```php
define('SEPAY_WEBHOOK_URL', 'https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php');
define('SEPAY_RETURN_URL', 'https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_return.php');
```

**Email (Gmail):**
```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'phanthienkhai2901@gmail.com');
define('MAIL_PASSWORD', 'nvyh agju zvnp nacz');
define('MAIL_FROM_EMAIL', 'phanthienkhai2901@gmail.com');
```

**Points Configuration:**
```php
define('POINTS_PER_VND', 0.01);      // 1 VND = 0.01 điểm
define('POINTS_BONUS_RATE', 1.0);    // 100% điểm cho online payment
```

---

## 🔄 PAYMENT FLOW

### Step 1: Customer selects payment method
```
UI shows 3 methods: [MoMo QR] [Sepay] [VietQR]
Customer clicks "Sepay" button
```

### Step 2: Redirect to QR payment page
```
onclick="initiateSepayPayment()"
→ Redirect to: sepay_payment_ui.php?ticket_id={id}&amount={price}
```

### Step 3: Display QR code
```
QR: https://qr.sepay.vn/img?bank=MBBANK&acc=0384104942&amount=400000&des=VE123
Shows: Account info, amount, ticket code, bank name
Auto-check every 3 seconds via AJAX to check_payment_status.php
```

### Step 4: Customer pays via bank app
```
Customer opens bank app
Scans QR code
Verifies: GALAXY STUDIO, 0384104942, 400,000 VND, content: VE123
Enters PIN/Biometric
Confirms payment
```

### Step 5: Sepay sends webhook
```
POST to: https://webphim.gt.tc/Trang-nguoi-dung/sepay/sepay_webhook.php
Sends: gateway, transactionDate, amount, content (VE123), referenceCode
```

### Step 6: Webhook processes (7 steps)
```
1. Extract ticket ID from content (VE123 → 123)
2. Find ticket in database
3. Verify amount matches ticket price
4. Check ticket not already paid
5. Update ve.trang_thai = 1 (PAID)
6. Calculate points (amount * 0.01)
7. Add points to user account
8. Save transaction history
9. Send confirmation email
```

### Step 7: UI updates
```
Auto-check detects payment success
Shows: "Thanh toán thành công ✅"
Displays: "Kiểm tra email để nhận chi tiết vé"
```

### Step 8: Customer receives email
```
From: Galaxy Studio <phanthienkhai2901@gmail.com>
Contains:
- Mã vé: VE123
- Phim: Avatar 3
- Rạp: Galaxy Studio Gò Vấp
- Ngày/Giờ: 04/12/2025 19:30
- Ghế: J5, J6, J7
- Điểm thưởng: +4,000 điểm
```

---

## 🧪 TESTING STEPS

### Pre-Deployment Test (Localhost)
```bash
# 1. Create test ticket
INSERT INTO ve (id_phim, id_rap, id_thoi_gian_chieu, id_ngay_chieu, id_tk, ghe, price, ngay_dat)
VALUES (1, 1, 1, 1, 17, 'J5', 400000, NOW());

# 2. Test webhook with ticket ID
curl -X POST http://localhost/webphim/Trang-nguoi-dung/sepay/sepay_webhook.php \
  -H "Content-Type: application/json" \
  -d '{"gateway":"MB","transactionDate":"2025-12-04 14:30","accountNumber":"0384104942","transferType":"in","transferAmount":400000,"accumulated":400000,"content":"VE1","referenceCode":"TEST123"}'

# 3. Verify changes
SELECT ve.id, ve.trang_thai, taikhoan.id_diem 
FROM ve 
JOIN taikhoan ON ve.id_tk = taikhoan.id 
WHERE ve.id = 1;

# 4. Check email sent
tail -f Trang-nguoi-dung/sepay/webhook_logs.txt
```

### Production Test (After Deploy)
```
1. Have customer make real payment
2. Monitor webhook_logs.txt
3. Verify database updates
4. Confirm email received
5. Check points added to account
```

---

## 🎯 SUCCESS CRITERIA

✅ When everything works:
- [ ] Customer places order → Vé được tạo
- [ ] Customer clicks Sepay → QR code displays
- [ ] Customer pays via bank → Money received at 0384104942
- [ ] Webhook processes → ve.trang_thai updates to 1
- [ ] Email sends → Customer receives ticket confirmation
- [ ] Points added → taikhoan.id_diem increases
- [ ] UI updates → Shows "Thanh toán thành công"
- [ ] Transaction logged → lich_su_thanh_toan_ve has record

---

## 📝 DEPLOYMENT CHECKLIST

### Before Deploy
- [ ] Backup database
- [ ] Backup website files

### Upload Phase
- [ ] Upload `/sepay/` folder (8 files)
- [ ] Upload updated `thanhtoan.php`

### Database Phase
- [ ] Execute CREATE TABLE lich_su_thanh_toan_ve
- [ ] Execute ALTER TABLE taikhoan ADD id_diem

### Configuration Phase
- [ ] Verify config.php settings
- [ ] Update domain if needed
- [ ] Verify email credentials

### Verification Phase
- [ ] Test webhook with curl
- [ ] Check logs generated
- [ ] Verify database tables created
- [ ] Test with real payment

### Monitoring Phase
- [ ] Watch webhook_logs.txt
- [ ] Monitor payment transactions
- [ ] Check email delivery
- [ ] Verify points system

---

## 📞 SUPPORT CONTACTS

**For Sepay Issues:**
- Dashboard: https://my.sepay.vn
- Support: support@sepay.vn
- Webhook ID: 18954

**For Payment Issues:**
- Bank: MB (Quân Đội) 0384104942
- Account Name: GALAXY STUDIO

**For Email Issues:**
- Gmail: phanthienkhai2901@gmail.com
- SMTP: smtp.gmail.com:587

---

## 🎉 YOU'RE ALL SET!

The system is ready for production deployment.

**Next Steps:**
1. Upload all files to host
2. Execute SQL schema
3. Verify configuration
4. Test with real payment
5. Monitor logs and transactions

**Good luck! 🚀**

---
