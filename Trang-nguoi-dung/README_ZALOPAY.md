# Hướng dẫn tích hợp thanh toán ZaloPay

## Tổng quan

Hệ thống hỗ trợ **2 chế độ thanh toán ZaloPay**:

### 🎭 Chế độ DEMO (Mặc định)
- **Mục đích**: Phù hợp cho đồ án, demo sản phẩm
- **Hoạt động**: Thanh toán giả lập, không qua API thật
- **Ưu điểm**: 
  - Không cần đăng ký tài khoản ZaloPay Business
  - Không cần credentials
  - Hoạt động offline, không phụ thuộc internet
  - Đủ chức năng để demo hoặc nộp đồ án
- **Nhược điểm**: Không nhận tiền thật từ khách hàng

### 💰 Chế độ PRODUCTION (Thanh toán thật)
- **Mục đích**: Triển khai thương mại, nhận tiền từ khách
- **Hoạt động**: Kết nối API ZaloPay chính thức
- **Ưu điểm**: 
  - Nhận tiền thật vào tài khoản merchant
  - Có thông báo IPN (Instant Payment Notification)
  - Có chứng từ thanh toán hợp lệ
- **Nhược điểm**: 
  - Phải đăng ký tài khoản ZaloPay Business (1-3 ngày)
  - Phải có domain chính thức (localhost không nhận callback)
  - Tốn phí giao dịch

---

## Cách chuyển đổi chế độ

### Bật chế độ DEMO (hiện tại)
```php
// File: view/momo/xuly_zalopay.php (dòng 11)
define('ZALOPAY_MODE', 'DEMO');
```

### Bật chế độ PRODUCTION
```php
// File: view/momo/xuly_zalopay.php (dòng 11)
define('ZALOPAY_MODE', 'PRODUCTION');
```

**Lưu ý**: Khi chuyển sang PRODUCTION, bắt buộc phải có thông tin API hợp lệ (xem phần đăng ký bên dưới).

---

## 📋 Đăng ký ZaloPay Business (Chế độ PRODUCTION)

### Bước 1: Tạo tài khoản ZaloPay Business

1. Truy cập: https://merchant.zalopay.vn/register
2. Chọn loại tài khoản:
   - **Cá nhân**: Cần CCCD + ảnh selfie
   - **Doanh nghiệp**: Cần Giấy ĐKKD + Mã số thuế
3. Điền thông tin:
   - Tên doanh nghiệp/cá nhân
   - Số điện thoại (nhận OTP)
   - Email liên hệ
   - Địa chỉ kinh doanh
   - Ngành nghề: **Giải trí - Rạp chiếu phim**
4. Upload giấy tờ:
   - CCCD/CMND (2 mặt)
   - Giấy phép kinh doanh (nếu có)
   - Ảnh selfie cầm CCCD
5. Gửi đăng ký và chờ duyệt (1-3 ngày làm việc)

### Bước 2: Nhận thông tin API

Sau khi tài khoản được duyệt:

1. Đăng nhập: https://merchant.zalopay.vn
2. Vào mục **"Cài đặt" → "API Keys"**
3. Lấy thông tin sau:

```
App ID: 2554XXXXXXXXX (số nguyên)
Key1: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (32 ký tự)
Key2: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX (32 ký tự)
```

### Bước 3: Cấu hình vào hệ thống

Mở file `view/momo/xuly_zalopay.php`, tìm đoạn **PRODUCTION** và điền thông tin:

```php
if (ZALOPAY_MODE === 'PRODUCTION') {
    $ZALOPAY_ENDPOINT = "https://openapi.zalopay.vn/v2/create";
    $ZALOPAY_APP_ID = 2554123456789; // Thay bằng App ID của bạn
    $ZALOPAY_KEY1 = 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL'; // Thay bằng Key1 của bạn
    $ZALOPAY_KEY2 = 'kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz'; // Thay bằng Key2 của bạn
} else {
    // ... (giữ nguyên phần sandbox)
}
```

**⚠️ BẢO MẬT**: Không commit keys lên GitHub. Nên dùng biến môi trường:
```php
$ZALOPAY_APP_ID = getenv('ZALOPAY_APP_ID');
$ZALOPAY_KEY1 = getenv('ZALOPAY_KEY1');
$ZALOPAY_KEY2 = getenv('ZALOPAY_KEY2');
```

---

## 🔧 Cài đặt Callback URL

ZaloPay cần callback URL để thông báo kết quả thanh toán. **Yêu cầu bắt buộc**:

### 1. Phải có domain công khai
- ❌ **Không được**: `localhost`, `127.0.0.1`, `192.168.x.x`
- ✅ **Được phép**: `yourdomain.com`, `api.yoursite.vn`

### 2. Phải sử dụng HTTPS
- ❌ `http://yourdomain.com` → Bị từ chối
- ✅ `https://yourdomain.com` → Hợp lệ

### 3. Đăng ký Callback URL trên ZaloPay Portal

1. Đăng nhập https://merchant.zalopay.vn
2. Vào **"Cài đặt" → "Callback URL"**
3. Nhập URL:
   ```
   https://yourdomain.com/webphim/Trang-nguoi-dung/index.php?act=zalopay_callback
   ```
4. Nhấn **"Lưu"** và **"Test Callback"** để kiểm tra

### 4. Test callback trên localhost (cho dev)

**Giải pháp**: Dùng **ngrok** để tạo tunnel công khai:

```bash
# Cài ngrok: https://ngrok.com/download
ngrok http 80

# Kết quả:
# Forwarding: https://abc123.ngrok.io -> http://localhost:80
```

Sau đó dùng URL ngrok:
```
https://abc123.ngrok.io/webphim/Trang-nguoi-dung/index.php?act=zalopay_callback
```

---

## 💳 Phương thức thanh toán được hỗ trợ

Khi khách hàng thanh toán qua ZaloPay, họ có thể chọn:

1. **Ví ZaloPay** (nếu đã có tài khoản ZaloPay)
2. **Thẻ ATM nội địa** (Visa/Mastercard/JCB của các ngân hàng Việt Nam)
3. **Thẻ quốc tế** (Visa/Mastercard có hỗ trợ 3D Secure)
4. **QR Code** (quét mã từ app ZaloPay)

**Lưu ý**: Để hỗ trợ thẻ quốc tế, phải đăng ký riêng và tốn thêm phí.

---

## 💰 Phí giao dịch

| Phương thức | Phí merchant (bạn chịu) | Phí khách hàng |
|-------------|-------------------------|----------------|
| Ví ZaloPay  | 1.5% - 2.0%            | 0 VND          |
| ATM nội địa | 2.0% - 2.5%            | 0 - 3,300 VND  |
| Thẻ quốc tế | 3.0% - 3.5%            | 0 VND          |

**Ví dụ**: 
- Khách đặt vé 100,000 VND → Bạn nhận: 98,000 VND (phí 2%)
- Số tiền rút về tài khoản: Sau 1-2 ngày làm việc

---

## 📊 So sánh DEMO vs PRODUCTION

| Tính năng | DEMO | PRODUCTION |
|-----------|------|------------|
| Nhận tiền thật | ❌ | ✅ |
| Cần đăng ký | ❌ | ✅ (1-3 ngày) |
| Cần credentials | ❌ | ✅ |
| Cần domain công khai | ❌ | ✅ (HTTPS) |
| Hoạt động offline | ✅ | ❌ |
| Có IPN callback | ❌ | ✅ |
| Phí giao dịch | 0 VND | 1.5% - 3.5% |
| Chứng từ hợp lệ | ❌ | ✅ |
| Phù hợp cho | Đồ án, Demo | Kinh doanh thật |

---

## 🧪 Test thanh toán (Sandbox)

ZaloPay có môi trường Sandbox để test **miễn phí**:

### Credentials Sandbox (đã tích hợp sẵn)
```
Endpoint: https://sb-openapi.zalopay.vn/v2/create
App ID: 2553
Key1: PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL
Key2: kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz
```

### Tài khoản test
Dùng app **ZaloPay Sandbox** (riêng cho dev):
- Tải app: [Hướng dẫn tại docs.zalopay.vn](https://docs.zalopay.vn/v2/start/#tai-app-zalopay-sandbox)
- Đăng nhập bằng số test: `0963918435` / PIN: `111111`
- Ví test có sẵn 10,000,000 VND

### Thẻ test
```
Số thẻ: 4111 1111 1111 1111 (Visa)
Tên: TEST USER
Ngày hết hạn: 03/30
CVV: 737
OTP: 123456
```

---

## 🔍 Kiểm tra giao dịch

### Dashboard merchant
1. Đăng nhập: https://merchant.zalopay.vn
2. **"Giao dịch" → "Danh sách đơn hàng"**
3. Xem chi tiết:
   - Mã giao dịch (app_trans_id)
   - Số tiền
   - Trạng thái (Thành công/Thất bại)
   - Phí
   - Thời gian thanh toán

### Xuất báo cáo
- Vào **"Báo cáo" → "Doanh thu"**
- Chọn khoảng thời gian
- Tải xuống Excel/PDF

---

## 🛠 Troubleshooting (Khắc phục lỗi)

### 1. Lỗi: "Invalid signature" (MAC không hợp lệ)

**Nguyên nhân**: 
- Key1 hoặc Key2 sai
- Thứ tự fields trong chuỗi MAC không đúng

**Cách fix**:
```php
// Đảm bảo thứ tự chính xác:
$data = $order["app_id"] . "|" . 
        $order["app_trans_id"] . "|" . 
        $order["app_user"] . "|" . 
        $order["amount"] . "|" . 
        $order["app_time"] . "|" . 
        $order["embed_data"] . "|" . 
        $order["item"];

$mac = hash_hmac("sha256", $data, $ZALOPAY_KEY1);
```

### 2. Lỗi: "Invalid app_id"

**Nguyên nhân**: App ID chưa được kích hoạt hoặc sai

**Cách fix**:
- Kiểm tra lại App ID trên merchant portal
- Đảm bảo tài khoản đã được duyệt
- Liên hệ support nếu vẫn lỗi

### 3. Lỗi: "Amount invalid"

**Nguyên nhân**: 
- Số tiền < 1,000 VND
- Số tiền > giới hạn cho phép
- Kiểu dữ liệu không phải integer

**Cách fix**:
```php
$amount = (int)$tong_tien; // Đảm bảo là số nguyên
if ($amount < 1000) {
    die('Số tiền tối thiểu 1,000 VND');
}
```

### 4. Lỗi: "Callback URL không nhận được"

**Nguyên nhân**: 
- Domain không công khai
- Không dùng HTTPS
- Firewall chặn

**Cách fix**:
- Dùng ngrok cho localhost:
  ```bash
  ngrok http 80
  ```
- Đăng ký callback URL mới trên portal
- Kiểm tra firewall/security group

### 5. Lỗi: "Request timeout"

**Nguyên nhân**: Server ZaloPay chậm hoặc mất kết nối

**Cách fix**:
```php
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Tăng timeout lên 10s
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
```

---

## 📞 Liên hệ hỗ trợ

### ZaloPay Support
- **Hotline**: 1900 5555 47 (7:30 - 22:00 hàng ngày)
- **Email**: support@zalopay.vn
- **Merchant Support**: merchant@zalopay.vn
- **Facebook**: fb.com/ZaloPayOfficial

### Tài liệu kỹ thuật
- **API Docs**: https://docs.zalopay.vn/v2
- **Sandbox Guide**: https://docs.zalopay.vn/v2/start/
- **FAQ**: https://docs.zalopay.vn/v2/general/faq.html

---

## 📝 Checklist triển khai

### Cho đồ án (DEMO mode)
- [x] File `xuly_zalopay.php` đã có
- [x] Thiết lập `ZALOPAY_MODE = 'DEMO'`
- [x] Test thanh toán trên localhost
- [x] Kiểm tra điểm tích lũy được cộng
- [x] Giao diện hiển thị đẹp

### Cho production (PRODUCTION mode)
- [ ] Đăng ký tài khoản ZaloPay Business
- [ ] Chờ duyệt (1-3 ngày)
- [ ] Lấy App ID + Key1 + Key2
- [ ] Cấu hình vào `xuly_zalopay.php`
- [ ] Có domain công khai + HTTPS
- [ ] Đăng ký Callback URL trên portal
- [ ] Test với Sandbox app
- [ ] Test callback nhận được
- [ ] Test thanh toán thật với số tiền nhỏ (10K VND)
- [ ] Kiểm tra tiền vào tài khoản merchant
- [ ] Bật chế độ PRODUCTION

---

## 🎯 Kết luận

### Nên dùng DEMO khi:
- ✅ Làm đồ án tốt nghiệp
- ✅ Demo sản phẩm cho khách hàng
- ✅ Test chức năng trên localhost
- ✅ Chưa có giấy phép kinh doanh

### Nên dùng PRODUCTION khi:
- ✅ Triển khai thương mại thật
- ✅ Muốn nhận tiền từ khách hàng
- ✅ Đã có domain + HTTPS
- ✅ Đã đăng ký tài khoản ZaloPay Business

**Lưu ý**: Bạn có thể bắt đầu với DEMO, sau đó chuyển sang PRODUCTION khi sẵn sàng - chỉ cần thay đổi 1 dòng code!

---

## 📄 License & Credits

- **ZaloPay API**: © VNG Corporation
- **Hệ thống booking**: Đồ án tốt nghiệp
- **Documentation**: Tạo bởi AI Assistant

**Version**: 1.0  
**Last updated**: <?= date('d/m/Y') ?>
