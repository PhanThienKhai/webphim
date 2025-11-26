# 💳 HƯỚNG DẪN TÍCH HỢP THANH TOÁN MOMO THẬT

## 📋 Tổng quan

Hệ thống thanh toán MoMo hiện có **2 chế độ**:
- **DEMO**: Thanh toán giả lập (không cần tài khoản MoMo Business)
- **PRODUCTION**: Thanh toán thật qua MoMo API

---

## 🎯 Chế độ hiện tại: DEMO

Để chuyển sang PRODUCTION, làm theo các bước dưới đây:

---

## 🚀 BƯỚC 1: Đăng ký tài khoản MoMo Business

### 1.1. Truy cập
- Website: https://business.momo.vn
- Hoặc: https://developers.momo.vn

### 1.2. Đăng ký
- Click "Đăng ký"
- Điền thông tin doanh nghiệp:
  - Tên doanh nghiệp
  - Mã số thuế
  - Địa chỉ
  - Người đại diện
  - Email & SĐT

### 1.3. Xác thực
- MoMo sẽ liên hệ xác thực thông tin (1-3 ngày làm việc)
- Có thể yêu cầu giấy tờ pháp lý (ĐKKD, CMND/CCCD, v.v.)

### 1.4. Lấy API Credentials
Sau khi tài khoản được duyệt, vào **Developer Portal** → **API Keys** để lấy:
- ✅ **Partner Code** (Mã đối tác)
- ✅ **Access Key** (Khóa truy cập)
- ✅ **Secret Key** (Khóa bí mật)

⚠️ **LƯU Ý**: Giữ Secret Key tuyệt đối bảo mật!

---

## 🔧 BƯỚC 2: Cấu hình hệ thống

### 2.1. Mở file cấu hình
File: `view/momo/xuly_momo_atm.php`

### 2.2. Đổi MODE
Tìm dòng:
```php
define('MOMO_MODE', 'DEMO'); // Dòng ~18
```

Đổi thành:
```php
define('MOMO_MODE', 'PRODUCTION');
```

### 2.3. Điền API Credentials
Tìm section:
```php
if (MOMO_MODE === 'PRODUCTION') {
    $MOMO_ENDPOINT = "https://payment.momo.vn/v2/gateway/api/create";
    $MOMO_PARTNER_CODE = 'MOMOXXXXXXXXXXX'; // ← Thay ở đây
    $MOMO_ACCESS_KEY = 'XXXXXXXXXXXXXXXX'; // ← Thay ở đây
    $MOMO_SECRET_KEY = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // ← Thay ở đây
}
```

Thay bằng thông tin từ MoMo Business Portal.

---

## 🧪 BƯỚC 3: Test trên môi trường Sandbox (Tùy chọn)

MoMo cung cấp môi trường **Sandbox** để test trước khi lên Production:

### 3.1. Đổi endpoint
```php
$MOMO_ENDPOINT = "https://test-payment.momo.vn/v2/gateway/api/create";
```

### 3.2. Dùng credentials của Sandbox
- Lấy từ MoMo Developer Portal → Sandbox Keys

### 3.3. Test
- Đặt vé → Thanh toán MoMo
- Sử dụng tài khoản test MoMo cung cấp
- Kiểm tra callback & IPN

---

## 📱 BƯỚC 4: Deploy lên Production

### 4.1. Đổi endpoint sang Production
```php
$MOMO_ENDPOINT = "https://payment.momo.vn/v2/gateway/api/create";
```

### 4.2. Dùng Production credentials
```php
$MOMO_PARTNER_CODE = 'MOxxxxxxxxxxxx'; // Production Partner Code
$MOMO_ACCESS_KEY = 'xxxxxxxxxx'; // Production Access Key
$MOMO_SECRET_KEY = 'xxxxxxxxxx'; // Production Secret Key
```

### 4.3. Cập nhật URL callback
Trong file `xuly_momo_api.php`, tìm:
```php
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/webphim/Trang-nguoi-dung/index.php';
```

Đổi thành domain thật:
```php
$baseUrl = 'https://yourdomain.com/index.php';
```

### 4.4. Đăng ký URL IPN với MoMo
- Vào MoMo Business Portal
- Đăng ký URL IPN: `https://yourdomain.com/index.php?act=momo_ipn`
- MoMo sẽ gọi URL này sau khi thanh toán thành công

---

## ✅ BƯỚC 5: Test thanh toán thật

### 5.1. Quy trình test
1. Đăng nhập website với tài khoản thành viên
2. Chọn phim → Chọn ghế → Thanh toán
3. Click "Thanh toán qua MoMo"
4. Hệ thống redirect đến cổng MoMo
5. Quét mã QR hoặc nhập SĐT MoMo
6. Xác nhận thanh toán trên app MoMo
7. MoMo redirect về website
8. Kiểm tra:
   - ✅ Vé được tạo
   - ✅ Hóa đơn được cập nhật
   - ✅ Điểm tích lũy được cộng
   - ✅ Email xác nhận được gửi

### 5.2. Test với số tiền nhỏ
- Lần đầu test với số tiền nhỏ (10,000 - 50,000 VND)
- Kiểm tra toàn bộ flow hoạt động
- Sau đó mới mở cho khách hàng

---

## 🛡️ BẢO MẬT

### Các điểm cần lưu ý:

1. **Secret Key**: 
   - KHÔNG commit lên Git
   - Lưu trong file config riêng hoặc biến môi trường
   - Không share với bất kỳ ai

2. **HTTPS**:
   - Bắt buộc dùng HTTPS cho Production
   - MoMo có thể từ chối callback nếu dùng HTTP

3. **Verify Signature**:
   - Luôn verify signature trong IPN callback
   - Tránh fake request

4. **IP Whitelist**:
   - MoMo có thể yêu cầu whitelist IP server
   - Liên hệ support MoMo để đăng ký

---

## 📊 SO SÁNH DEMO vs PRODUCTION

| Tính năng | DEMO | PRODUCTION |
|-----------|------|------------|
| Cần tài khoản MoMo Business | ❌ | ✅ |
| Thanh toán thật | ❌ | ✅ |
| Tích điểm | ✅ | ✅ |
| Tạo vé/hóa đơn | ✅ | ✅ |
| Gửi email | ✅ | ✅ |
| Redirect MoMo | ❌ | ✅ |
| Quét QR thanh toán | ❌ | ✅ |
| Phí giao dịch | ❌ | ✅ (2-3%) |

---

## 💰 PHÍ GIAO DỊCH

MoMo thu phí từ merchant:
- **Phí cố định**: ~2-3% giá trị giao dịch
- **Phí tối thiểu**: 1,000 - 2,000 VND/giao dịch
- Có thể thương lượng nếu doanh số lớn

**Ví dụ:**
- Vé 80,000 VND → Phí ~2,000 VND (2.5%)
- Merchant nhận: 78,000 VND

---

## 🆘 TROUBLESHOOTING

### Lỗi: "Invalid signature"
- ✅ Kiểm tra Secret Key đúng chưa
- ✅ Kiểm tra thứ tự các field trong rawHash
- ✅ Dùng HMAC SHA256, không phải MD5

### Lỗi: "Partner not found"
- ✅ Kiểm tra Partner Code
- ✅ Đảm bảo tài khoản đã active

### Lỗi: "Amount invalid"
- ✅ Số tiền >= 10,000 VND
- ✅ Số tiền <= 50,000,000 VND
- ✅ Kiểu dữ liệu là integer

### Không nhận callback
- ✅ Kiểm tra URL callback đúng
- ✅ Đảm bảo server public (không localhost)
- ✅ Kiểm tra firewall/security group

---

## 📞 HỖ TRỢ

- **Email**: business@momo.vn
- **Hotline**: 1900 6363
- **Docs**: https://developers.momo.vn
- **Support Portal**: https://business.momo.vn/support

---

## 🎉 DONE!

Sau khi hoàn tất các bước trên, hệ thống sẽ:
- ✅ Thanh toán THẬT qua MoMo
- ✅ Tự động cộng điểm tích lũy
- ✅ Gửi email xác nhận
- ✅ Tích hợp hoàn chỉnh vào flow đặt vé

---

**Made with ❤️ for Galaxy Cinema**
