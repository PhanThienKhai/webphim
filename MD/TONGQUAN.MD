# 📋 CINEPASS - TỔNG QUAN HỆ THỐNG

**Dành cho: ChatGPT Training** | **Mục đích: Hỗ trợ viết báo cáo đồ án Word**

---

## 🎯 GIỚI THIỆU HỆ THỐNG

### Tên Hệ Thống
**CINEPASS** - Hệ thống quản lý rạp chiếu phim toàn diện

### Loại Hệ Thống
- **Phân loại**: Hệ thống quản lý doanh nghiệp (ERP)
- **Lĩnh vực**: Rạp chiếu phim / Cinema Management
- **Phạm vi**: Quản lý 1 hoặc nhiều rạp chiếu phim

### Mục Đích Hệ Thống
- ✅ Quản lý phim chiếu (Movies)
- ✅ Quản lý lịch chiếu (Showtimes)
- ✅ Bán vé trực tuyến (Online Ticketing)
- ✅ Quản lý rạp & phòng chiếu (Cinema Management)
- ✅ Quản lý ghế & tình trạng vé (Seat Management)
- ✅ Xử lý thanh toán (Payment Processing)
- ✅ Check-in vé qua QR code (QR Scanning)
- ✅ Quản lý nhân viên & lương (Payroll)
- ✅ Chấm công (Attendance Tracking)
- ✅ Khuyến mãi & combo (Promotions)
- ✅ Quản lý nội dung (Content Management)
- ✅ Báo cáo & thống kê (Analytics & Reports)

---

## 📊 THÔNG TIN KỸ THUẬT

### Stack Công Nghệ

**Backend:**
- 🐘 **Ngôn ngữ**: PHP 7/8
- 🗄️ **Cơ sở dữ liệu**: MySQL/MariaDB
- 🔗 **Kết nối DB**: PDO (PHP Data Objects) - Prepared Statements
- 📦 **Các thư viện**: PHPMailer, phpQRCode, Face Detection

**Frontend:**
- 🎨 **HTML5/CSS3/JavaScript**
- 📱 **Responsive Design**: Bootstrap 4/5
- 🔌 **jQuery**: Xử lý AJAX, DOM manipulation
- 📊 **Chart Libraries**: Chart.js (implied)

**Công Cụ & Dịch Vụ Thanh Toán:**
- 💳 MoMo (Mobile Wallet)
- 💳 VNPay (Payment Gateway)
- 💳 ZaloPay (Mobile Wallet)
- 💳 VietQR (QR Payment)
- 💰 Tiền mặt (Cash)

**Phần Cứng Đặc Thù:**
- 📷 Camera (Face Recognition - optional)
- 📱 QR Code Scanner (Mobile/Device)
- 📍 GPS (Location Tracking)

---

## 🏗️ KIẾN TRÚC HỆ THỐNG

### Kiểu Kiến Trúc
- **Pattern**: MVC-like (Model-View-Controller)
- **Mô hình**: Monolithic (Single application)
- **Deployment**: Web-based (PHP/HTTP)

### Cấu Trúc Thư Mục
```
/xampp/htdocs/webphim/
├── Trang-admin/              # Admin Dashboard
│   ├── model/                # 30+ Business Logic Models
│   ├── view/                 # Admin Views
│   ├── assets/               # CSS, JS, Images
│   ├── helpers/              # Helper Functions
│   ├── PHP-FaceDetector/     # Face Recognition Library
│   └── index.php             # Admin Routing
├── Trang-nguoi-dung/         # Customer Frontend
│   ├── model/                # Customer-facing Models
│   ├── view/                 # Customer Views
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript
│   ├── config/               # Configuration Files
│   ├── PHPMailer/            # Email Library
│   ├── phpqrcode/            # QR Code Generation
│   └── index.php             # Customer Routing
└── DB/                       # Database Schema Files
    ├── cinepass.sql          # Main Schema (51 tables)
    └── migration scripts      # Update scripts
```

### Mô Hình Kết Nối Dữ Liệu
```
Frontend (HTML/CSS/JS)
        ↓ (HTTP Requests)
PHP Application Layer
        ↓ (PDO Queries)
MySQL/MariaDB Database
        ↓ (Data)
Backend Models (PHP Classes)
        ↓ (Responses)
JSON/HTML Output
```

---

## 👥 CÁC VAI TRÒ NGƯỜI DÙNG (6 Roles)

### 1. Khách Hàng (vai_tro = 0) ✅ FULLY IMPLEMENT

**Mô tả**: Người dùng thông thường, có thể mua vé trực tuyến

**Quyền Hạn:**
- ✅ Xem danh sách phim
- ✅ Xem chi tiết phim
- ✅ Xem lịch chiếu
- ✅ Đặt vé (chọn ghế)
- ✅ Thanh toán vé
- ✅ Check-in vé qua QR code
- ✅ Bình luận phim
- ✅ Xem lịch sử mua vé
- ✅ Xem khuyến mãi
- ⚠️ Xem điểm tích lũy (SCHEMA CÓ NHƯNG CHƯA IMPLEMENT)

**Tính năng đặc thù:**
- Đặt vé trực tuyến
- Thanh toán qua nhiều phương thức
- Nhận vé điện tử (PDF + QR code)
- In vé hoặc lưu trên điện thoại

---

### 2. Nhân Viên (vai_tro = 1) ✅ FULLY IMPLEMENT

**Mô tả**: Nhân viên rạp chiếu (nhân viên bán vé, quản lý vé, etc.)

**Quyền Hạn:**
- ✅ Xem lịch làm việc của mình
- ✅ Check-in/Check-out (Vào/Ra ca)
- ✅ Xem lịch sử chấm công
- ✅ Xin nghỉ phép
- ✅ Xem lương tháng
- ✅ Xem lịch sử lương
- ⚠️ Nhận diện khuôn mặt (Schema có, chưa tích hợp)

**Tính năng đặc thù:**
- Chấm công tự động (QR code + GPS)
- Quản lý lịch làm việc
- Xem chi tiết tính lương

**Lưu ý:**
- Chấm công có schema mismatch giữa code và DB

---

### 3. Admin (vai_tro = 2) ✅ MOSTLY IMPLEMENT

**Mô tả**: Quản trị viên hệ thống - quyền cao nhất

**Quyền Hạn:**
- ✅ Quản lý tất cả dữ liệu
- ✅ Quản lý phim (Thêm/Sửa/Xóa)
- ✅ Quản lý lịch chiếu (Duyệt lịch)
- ✅ Quản lý vé (Check-in, hoàn vé)
- ✅ Quản lý rạp & phòng chiếu
- ✅ Quản lý ghế
- ✅ Quản lý nhân viên
- ✅ Quản lý lương (Tính toán, duyệt, thanh toán)
- ✅ Quản lý chấm công
- ✅ Quản lý khuyến mãi & combo
- ✅ Quản lý nội dung (Tin tức, thông tin website)
- ✅ Xem báo cáo & thống kê

**Tính năng đặc thù:**
- Dashboard quản trị toàn hệ thống
- Duyệt & phê duyệt lịch chiếu
- Tính toán & thanh toán lương nhân viên
- Xuất báo cáo (Excel, PDF)

**Lưu ý:**
- Một số tính năng có schema/code mismatch (xem phần vấn đề)

---

### 4. Quản Lý Rạp (vai_tro = 3) ⚠️ INCOMPLETE

**Mô tả**: Quản lý một rạp chiếu cụ thể

**Quyền Hạn (Dự kiến):**
- ⚠️ Quản lý phòng chiếu trong rạp
- ⚠️ Quản lý ghế
- ⚠️ Quản lý thiết bị
- ⚠️ Quản lý nhân viên (của rạp)
- ⚠️ Xem báo cáo rạp
- ⚠️ Phê duyệt lịch chiếu local

**Tình trạng:**
- ❌ Chưa fully implement trong code
- ❌ Thiếu permission checks
- ❌ Chỉ có query cơ bản

---

### 5. Quản Lý Cụm (vai_tro = 4) ⚠️ INCOMPLETE

**Mô tả**: Quản lý nhiều rạp (cụm rạp)

**Quyền Hạn (Dự kiến):**
- ⚠️ Quản lý nhiều rạp
- ⚠️ Quản lý lịch chiếu toàn cụm
- ⚠️ Quản lý nhân viên toàn cụm
- ⚠️ Báo cáo doanh thu toàn cụm
- ⚠️ Phê duyệt lịch chiếu toàn cụm

**Tình trạng:**
- ❌ Chưa fully implement trong code
- ❌ Thiếu business logic
- ❌ Chỉ có query cơ bản

---

### 6. Khách Vãng Lai (vai_tro = -1) ❌ NOT IMPLEMENT

**Mô tả**: Khách không đăng ký tài khoản (Guest)

**Quyền Hạn (Dự kiến):**
- ❌ Xem phim
- ❌ Đặt vé (không cần account)
- ❌ Thanh toán

**Tình trạng:**
- ❌ CHƯA IMPLEMENT
- ❌ Schema có nhưng code không handle
- ❌ Cần phát triển nếu muốn hỗ trợ guest checkout

---

## 🗄️ CỞ SỞ DỮ LIỆU

### Thông Tin Chung
- **Tên Database**: cinepass
- **RDBMS**: MySQL/MariaDB
- **Tổng bảng**: 51 tables
- **Bảng lõi**: 45 tables
- **Bảng tạm / orphan**: 6 tables

### Các Bảng Dữ Liệu (45 Bảng Lõi)

#### Domain 1: PHIM (6 Bảng)
```
phim                      - Thông tin phim
loai_phim                 - Thể loại phim
phim_rap                  - Liên kết phim-rạp
binhluan                  - Bình luận phim
tra_loi_binhluan         - Trả lời bình luận
tintuc                    - Tin tức/blog
```

#### Domain 2: RẠP & PHÒNG (7 Bảng)
```
rap_chieu                 - Thông tin rạp
phongchieu                - Phòng chiếu
phong_ghe                 - Ghế trong phòng
thiet_bi                  - Thiết bị rạp
thong_tin_website         - Thông tin website
khung_gio_chieu           - Khung giờ chiếu
combo                     - Combo snacks
```

#### Domain 3: LỊCH CHIẾU & VÉ (3 Bảng)
```
lichchieu                 - Lịch chiếu phim
ve                        - Vé bán
lich_su_thanh_toan       - Lịch sử thanh toán vé
```

#### Domain 4: NHÂN VIÊN & LƯƠNG (11 Bảng)
```
taikhoan                  - Tài khoản người dùng
nhan_vien                 - Thông tin nhân viên
nhan_vien_rap             - Liên kết nhân viên-rạp
lich_lam_viec             - Lịch làm việc
cham_cong                 - Chấm công vào/ra ⚠️ (SCHEMA MISMATCH)
cong_them_gio             - Công thêm giờ
nghi_phep                 - Nghỉ phép
bang_luong                - Bảng lương tháng
doi_hoan                  - Đổi quà (placeholder)
thiet_bi_nhan_vien       - Thiết bị của nhân viên
lich_su_thanh_toan_luong - Lịch sử thanh toán lương ⚠️ (FK MISMATCH)
```

#### Domain 5: KHUYẾN MÃI & ĐIỂM (6 Bảng)
```
khuyen_mai                - Khuyến mãi
lich_su_diem              - Lịch sử tích điểm ❌ (ORPHAN)
quy_tac_tich_diem         - Quy tắc tích điểm ❌ (ORPHAN)
hang_thanh_vien           - Hạng thành viên ❌ (ORPHAN)
ghe_ngoi                  - Thông tin ghế (không dùng)
chat_analytics            - Chat analytics (không dùng)
```

#### Domain 6: QUẢN LÝ KHÁC (6 Bảng)
```
email_template            - Email templates
loai_cuong_thut           - Loại cường cáp (?)
chuoi_shop                - Chuỗi cửa hàng
tong_hop_doanh_thu        - Tổng hợp doanh thu
thong_ke                  - Thống kê (inferred)
chat_history              - Chat history (không dùng)
```

### Orphan Tables (Có Schema Nhưng Không Implement)
```
lich_su_xem_phim          - Lịch sử xem phim (không có model)
lich_su_diem              - Lịch sử tích điểm (không có model)
quy_tac_tich_diem         - Quy tắc tích điểm (không có model)
hang_thanh_vien           - Hạng thành viên (không có model)
ghe_ngoi                  - Thông tin ghế (không dùng)
chat_analytics            - Chat analytics (không dùng)
```

---

## 🔧 CÁC MODULE & MODELS

### Danh Sách 30+ PHP Model Files

#### Core Database
- `pdo.php` - Database abstraction layer (PDO connection)

#### Phim Domain
- `phim.php` - Movie CRUD operations
- `loai_phim.php` - Movie category management
- `phim_rap.php` - Movie-Cinema mapping
- `binhluan.php` - Comments & replies
- `tintuc.php` - News/blog posts

#### Rạp & Phòng Domain
- `rap.php` - Cinema information
- `phong.php` - Auditorium management
- `phong_ghe.php` - Seat management
- `thietbi.php` - Equipment management
- `website.php` - Website configuration

#### Lịch Chiếu & Vé Domain
- `lichchieu.php` - Showtime scheduling & approval
- `khunggio.php` - Showtime slot management
- `ve.php` - Ticket sales & status
- `scanve_api.php` - QR code check-in (DEPRECATED, merged into main routing)
- `combo.php` - Snack combo management

#### Nhân Viên Domain
- `taikhoan.php` - User account management (all roles)
- `lichlamviec.php` - Work schedule
- `chamcong.php` - Attendance tracking ⚠️ (SCHEMA MISMATCH)
- `chamcong_detector.php` - Face detection wrapper
- `nghiphep.php` - Leave management
- `bang_luong.php` - Payroll calculation
- `thanh_toan.php` - Salary payment ⚠️ (FK MISMATCH)

#### Khuyến Mãi & Điểm Domain
- `khuyenmai.php` - Promotion management
- `doihoan.php` - Reward redemption (shell, not implemented)

#### Quản Lý & Thống Kê
- `thongke.php` - Analytics & reports
- `get_today_status.php` - Daily status helper
- `register_face_handler.php` - Face template registration

#### Face Recognition (Optional)
- `FaceDetector.php` - Face detection library wrapper
- `PHP-FaceDetector-master/FaceDetector.php` - Main detection library

---

## ✨ CÁC TÍNH NĂNG CHÍNH

### 1. Quản Lý Phim
- ✅ Thêm/Sửa/Xóa phim
- ✅ Phân loại phim theo thể loại
- ✅ Liên kết phim với rạp chiếu
- ✅ Bình luận & đánh giá phim
- ⚠️ Lịch sử xem phim (ORPHAN TABLE)

### 2. Quản Lý Rạp & Phòng
- ✅ Quản lý thông tin rạp
- ✅ Quản lý phòng chiếu
- ✅ Quản lý ghế (hàng, cột, loại: thường/VIP)
- ✅ Quản lý thiết bị rạp

### 3. Lịch Chiếu & Thanh Toán Vé
- ✅ Tạo lịch chiếu (phim + phòng + ngày + giờ)
- ✅ Duyệt lịch chiếu ⚠️ (STATUS FORMAT MISMATCH)
- ✅ Đặt vé trực tuyến (chọn ghế)
- ✅ Thanh toán vé (MoMo, VNPay, ZaloPay, VietQR, Cash)
- ✅ Check-in vé qua QR code
- ✅ Combo snacks kèm vé
- ⚠️ Tích điểm khi mua vé (CHƯA IMPLEMENT)

### 4. Quản Lý Nhân Viên
- ✅ Tạo tài khoản nhân viên
- ✅ Gán vai trò (Nhân viên, Admin, QL Rạp, QL Cụm)
- ✅ Quản lý lịch làm việc
- ✅ Chấm công vào/ra (QR code + GPS) ⚠️ (SCHEMA MISMATCH)
- ✅ Xin nghỉ phép (duyệt/từ chối)
- ✅ Công thêm giờ
- ⚠️ Face recognition (đăng ký được, chưa tích hợp vào check-in)

### 5. Quản Lý Lương
- ✅ Tính bảng lương tháng (giờ công, phụ cấp, khấu trừ, thưởng)
- ✅ Duyệt bảng lương
- ✅ Thanh toán lương (chuyển khoản, tiền mặt)
- ✅ Lịch sử thanh toán
- ⚠️ FK sai trong code (id_nv → nhan_vien.id không tồn tại!)

### 6. Khuyến Mãi & Loyalty
- ✅ Quản lý khuyến mãi (mã code, % giảm)
- ✅ Áp dụng mã khuyến mãi khi thanh toán
- ❌ Tích điểm (CHƯA IMPLEMENT - ORPHAN TABLE)
- ❌ Quy tắc tích điểm (CHƯA IMPLEMENT - ORPHAN TABLE)
- ❌ Hạng thành viên (CHƯA IMPLEMENT - ORPHAN TABLE)
- ❌ Đổi quà (Shell only, not functional)

### 7. Quản Lý Nội Dung
- ✅ Đăng tin tức/blog
- ✅ Bình luận trên tin tức
- ✅ Quản lý thông tin website (logo, footer, liên hệ)

### 8. Báo Cáo & Thống Kê
- ✅ Doanh thu (theo ngày, tuần, tháng, rạp, phim)
- ✅ Số vé bán (theo phim, rạp, ngày)
- ✅ Khách hàng (khách mới, khách quay lại, top khách)
- ✅ Chấm công & lương
- ✅ Xuất báo cáo (Excel, PDF, CSV)

### 9. QR Code Management ✅ FULLY IMPLEMENT

#### Tổng Quan về Công Nghệ QR Code

QR Code (Quick Response Code) là một dạng mã vạch hai chiều được thiết kế để mã hóa thông tin và cho phép các thiết bị có camera quét nhanh chóng. Trong hệ thống CINEPASS, công nghệ QR Code được ứng dụng rộng rãi để hỗ trợ ba chức năng chính: (1) mã hóa thông tin vé để khách hàng có thể mang vé điện tử, (2) check-in vé tại rạp chiếu phim thông qua quét mã, và (3) chấm công tự động cho nhân viên. Sử dụng QR Code giúp tăng hiệu suất hoạt động, giảm sai sót thủ công, và cải thiện trải nghiệm khách hàng.

#### Công Nghệ và Library Sử Dụng

Hệ thống CINEPASS sử dụng hai thư viện chính để xử lý QR Code. Thứ nhất là **phpQRCode**, một thư viện PHP mạnh mẽ dành cho việc tạo mã QR từ dữ liệu chuỗi. Thư viện này được đặt tại thư mục `/Trang-nguoi-dung/phpqrcode/` và có khả năng tạo ra các mã QR ở nhiều định dạng khác nhau, bao gồm PNG image hoặc bảng HTML. Khi một khách hàng thanh toán vé thành công, hệ thống gọi hàm `QRcode::png()` để tạo một tập tin PNG chứa mã QR tương ứng với thông tin vé.

```php
require_once('phpqrcode/qrlib.php');
$ticket_data = "CINEPASS_" . $id_ve . "_" . $ma_ve . "_" . time();
$filename = 'temp_photos/qr_' . $id_ve . '.png';
QRcode::png($ticket_data, $filename, 'L', 4, 2);
```

Thứ hai là **jsQR**, một thư viện JavaScript cho phép quét và giải mã mã QR trực tiếp trên trình duyệt web mà không cần gọi đến máy chủ backend. Thư viện này được lưu trữ tại `/Trang-admin/js/jsqr.min.js` và `/Trang-nguoi-dung/js/jsQR.min.js`, hỗ trợ tất cả các trình duyệt hiện đại như Chrome, Firefox, Edge, và Safari. Bằng cách sử dụng jsQR, nhân viên rạp có thể quét QR code bằng camera của thiết bị di động hoặc máy tính mà không cần kết nối đến máy chủ trong quá trình quét.

#### Ứng Dụng QR Code cho Vé Khách Hàng

Khi một khách hàng hoàn thành quá trình mua vé trực tuyến, hệ thống tạo một mã vé duy nhất và sinh mã QR tương ứng. Dữ liệu được mã hóa trong QR code tuân theo định dạng `CINEPASS_[id_ve]_[ma_ve]_[timestamp]`, ví dụ: `CINEPASS_12345_VE20231215001_1702656000`. Mã QR này chứa đủ thông tin để nhân viên rạp xác thực vé mà không cần tra cứu cơ sở dữ liệu ngay lập tức. Tập tin PNG chứa mã QR được lưu trữ trong thư mục `/Trang-nguoi-dung/temp_photos/` với kích thước tiêu chuẩn 200x200 pixels và mức lỗi L (7% correction capability), đảm bảo vé vẫn có thể được quét ngay cả khi in không hoàn hảo.

Quy trình tạo mã QR vé diễn ra như sau: đầu tiên, khách hàng hoàn thành thanh toán, hệ thống tạo một bản ghi mới trong bảng `ve` với tất cả thông tin liên quan (lịch chiếu, ghế, giá cả). Sau đó, hệ thống sinh ra mã vé (ma_ve) duy nhất và gọi thư viện phpQRCode để tạo mã QR. Tập tin PNG được lưu tạm thời để khách hàng tải xuống hoặc in ra. Cuối cùng, khách hàng có thể lưu vé điện tử trên điện thoại của mình hoặc in ra giấy để mang đến rạp.

#### Quá Trình Check-in Vé Sử Dụng QR Code

Tại rạp chiếu phim, nhân viên sử dụng giao diện check-in để quét mã QR trên vé của khách hàng. Quá trình này bắt đầu bằng việc nhân viên mở ứng dụng check-in trên thiết bị (web hoặc app) và chọn chức năng "Quét QR code". Thư viện jsQR kích hoạt camera của thiết bị, cho phép nhân viên quét mã QR trên vé. Sau khi quét, hệ thống giải mã dữ liệu QR và lấy ra các thông tin chủ yếu như ID vé, mã vé, và timestamp.

Tiếp theo, hệ thống thực hiện một loạt các kiểm tra validation để đảm bảo vé hợp lệ: (1) kiểm tra xem vé có tồn tại trong cơ sở dữ liệu không, (2) xác minh rằng khách hàng đã hoàn thành thanh toán, (3) kiểm tra lịch chiếu vẫn còn hiệu lực (chưa quá giờ bắt đầu quá nhiều), và (4) đảm bảo vé chưa được sử dụng trước đó (chống check-in lặp lại). Nếu tất cả các điều kiện đều thỏa mãn, hệ thống cập nhật trạng thái vé thành `checked_in` và lưu thời gian check-in vào cơ sở dữ liệu. Nhân viên nhận được thông báo xác nhận "✓ Vé hợp lệ - Hoan nghênh khách hàng", cùng với các thông tin chi tiết như tên phim, giờ chiếu, phòng, và số ghế. Trong trường hợp vé không hợp lệ (vé đã sử dụng, chưa thanh toán, hoặc hết hạn), hệ thống hiển thị thông báo lỗi cụ thể để nhân viên xử lý.

#### Ứng Dụng QR Code cho Chấm Công Nhân Viên

Bên cạnh ứng dụng cho vé khách hàng, QR Code cũng được sử dụng để chấm công tự động cho nhân viên. Mỗi nhân viên được cấp một mã QR định kỳ (hoặc sử dụng mã QR cố định) với định dạng `NHANVIEN_[id_nv]_[id_rap]_[ngay]`, ví dụ: `NHANVIEN_789_001_20231215`. Khi nhân viên đến rạp, họ quét mã QR này để thực hiện check-in vào ca làm việc. Hệ thống ghi nhận thời gian vào (gio_vao), vị trí địa lý (GPS/location_checkin) thông qua dịch vụ định vị của thiết bị, và phương thức xác thực (QR, face recognition, hoặc PIN).

Trong suốt thời gian làm việc, nhân viên thực hiện các nhiệm vụ của mình. Khi kết thúc ca, nhân viên quét lại mã QR hoặc sử dụng giao diện check-out để rời khỏi ca làm việc. Hệ thống ghi nhận thời gian ra (gio_ra), vị trí checkout, và phương thức xác thực. Sau đó, hệ thống tự động tính toán số giờ công (`giờ_công = gio_ra - gio_vao`), xác định thời gian nghỉ (break_duration) nếu có, và lưu toàn bộ thông tin vào bảng `cham_cong`. Dữ liệu này được sử dụng để tính lương hàng tháng cho nhân viên. Sử dụng QR Code cho chấm công giúp loại bỏ gian lận chấm công, cung cấp dữ liệu chính xác về giờ công, và tạo hồ sơ kiểm toán đầy đủ.

#### Cơ Chế Quét QR Code Trên Trình Duyệt

Thư viện jsQR cung cấp một giải pháp nhẹ nhàng và hiệu quả để quét QR code trực tiếp trên trình duyệt web mà không cần cài đặt ứng dụng ngoài. Khi người dùng kích hoạt chức năng quét, JavaScript yêu cầu quyền truy cập camera của thiết bị. Nếu được cấp phép, trình duyệt mở camera và hiển thị luồng video trực tiếp trên màn hình. Hệ thống liên tục chụp ảnh từ video feed, xử lý từng frame bằng thuật toán nhận dạng QR code của jsQR. Khi phát hiện mã QR, thư viện giải mã dữ liệu và trả về cho ứng dụng. Dữ liệu này sau đó được gửi đến máy chủ backend để xác thực và xử lý tiếp theo.

Quá trình này diễn ra nhanh chóng, thường chỉ mất vài trăm miligiây từ khi quét đến khi nhận được kết quả. Lợi ích của cách tiếp cận này là giảm độ trễ mạng, tiết kiệm tài nguyên máy chủ, và cung cấp trải nghiệm người dùng mượt mà. Ngoài ra, jsQR hỗ trợ tất cả các trình duyệt hiện đại, cho phép triển khai trên nhiều nền tảng (web, mobile) mà không cần code riêng.

#### Bảo Mật và Xác Thực QR Code

Mặc dù QR Code cung cấp tiện lợi, nhưng vẫn tồn tại một số mối lo ngại về bảo mật. Hiện tại, dữ liệu được mã hóa trong QR code là plaintext (chữ thường), có nghĩa là bất cứ ai quét được QR code cũng có thể đọc thông tin bên trong. Để giảm thiểu rủi ro, hệ thống thực hiện các kiểm tra validation nghiêm ngặt trên máy chủ: kiểm tra định dạng dữ liệu, xác nhận vé/nhân viên tồn tại trong cơ sở dữ liệu, kiểm tra trạng thái hiện tại (chưa sử dụng, đã thanh toán), và xác minh timestamp để phát hiện các vé quá cũ hoặc bị giả mạo.

Để cải thiện bảo mật hơn nữa, hệ thống có thể áp dụng các biện pháp bổ sung như thêm timestamp vào dữ liệu QR để phát hiện replay attack (quét lại vé cũ), tạo chữ ký số (digital signature) hoặc hash của dữ liệu để xác minh tính toàn vẹn, mã hóa dữ liệu nhạy cảm trong QR nếu cần, và giới hạn tốc độ quét (rate limiting) để ngăn chặn các cuộc tấn công brute force. Các biện pháp này đặc biệt quan trọng đối với các ứng dụng yêu cầu mức độ bảo mật cao.

---

## ⚠️ CÁC VẤN ĐỀ QUAN TRỌNG CẦN LƯU Ý

### 🔴 CRITICAL ISSUES (Mức Độ Cao)

#### 1. Schema Mismatch - Chấm Công (cham_cong)
- **Vị trí**: `Trang-admin/model/chamcong.php` hàm `cc_ensure_schema()`
- **Vấn đề**: Code tạo bảng động với schema khác hoàn toàn với `cinepass.sql`
- **Schema DB**: 
  - `fingerprint_vao` (JSON)
  - `fingerprint_ra` (JSON)
  - `latitude`, `longitude`
  - `location_accuracy`
  - `has_mask`
- **Schema Code**:
  - `gio_vao`, `gio_ra`
  - `location_checkin`, `location_checkout`
  - `auth_method_in`, `auth_method_out`
  - `break_duration`
- **Hậu quả**: Hai bảng incompatible → dữ liệu không đồng bộ
- **Khuyến cáo**: Cần unify schema hoặc migration script

#### 2. FK Reference Sai - Thanh Toán Lương (lich_su_thanh_toan)
- **Vị trí**: `Trang-admin/model/thanh_toan.php` hàm `tt_save_payment()`
- **Vấn đề**: Code FK tới `nhan_vien.id` nhưng bảng này không tồn tại!
- **Schema DB**: FK `id_nv` → `nhan_vien_rap.id`
- **Schema Code**: FK `id_nv` → `nhan_vien.id` (❌ KHÔNG TỒN TẠI)
- **Hậu quả**: FK constraint sẽ fail nếu bật strict mode
- **Khuyến cáo**: Sửa FK tới `nhan_vien_rap.id` hoặc tạo view `nhan_vien`

#### 3. Status Format Inconsistency - Lịch Chiếu (lichchieu)
- **Vị trí**: `Trang-admin/model/lichchieu.php` hàm `lc_list_by_trang_thai()`
- **Vấn đề**: Code dùng diacritics, schema dùng underscores
- **Code**: `'Chờ duyệt'`, `'Đã duyệt'`, `'Từ chối'` (with diacritics)
- **Schema DB**: `'cho_duyet'`, `'da_duyet'`, `'tu_choi'` (underscore, no diacritics)
- **Hậu quả**: Query fail, data filtering inconsistent
- **Khuyến cáo**: Standardize lên một format duy nhất

---

### 🟠 HIGH PRIORITY ISSUES

#### 1. Tính Năng Chưa Implement - Tích Điểm
- **Schema Tables**: 
  - `lich_su_diem` (orphan)
  - `quy_tac_tich_diem` (orphan)
  - `hang_thanh_vien` (orphan)
- **Code Models**: KHÔNG CÓ
- **Hậu quả**: Khách không tích điểm khi:
  - Mua vé
  - Mua combo
  - Bình luận phim
- **Trạng thái**: ❌ CHƯA IMPLEMENT
- **Khuyến cáo**: Cần develop model `lich_su_diem.php` nếu muốn enable tính năng

#### 2. vai_tro = -1 (Khách Vãng Lai) Chưa Implement
- **Mô tả**: Guest checkout không cần tài khoản
- **Schema**: Có hỗ trợ
- **Code**: `taikhoan.php` KHÔNG handle vai_tro = -1
- **Hậu quả**: Guest không thể thanh toán hoặc checkout không rõ ràng
- **Trạng thái**: ❌ CHƯA IMPLEMENT
- **Khuyến cáo**: Implement logic cho vai_tro = -1 nếu cần guest checkout

#### 3. vai_tro = 3, 4 Incomplete
- **vai_tro = 3**: Quản lý rạp (incomplete)
- **vai_tro = 4**: Quản lý cụm (incomplete)
- **Code**: `taikhoan.php` chỉ có query cơ bản
- **Hậu quả**: Không có permission checks, business logic không rõ
- **Trạng thái**: ⚠️ PARTIALLY IMPLEMENT
- **Khuyến cáo**: Phát triển đầy đủ hai role này hoặc sử dụng Admin

#### 4. Face Recognition Chưa Tích Hợp
- **Models**: 
  - `FaceDetector.php` ✓ (có)
  - `chamcong_detector.php` ✓ (có)
  - `register_face_handler.php` ✓ (có - đăng ký)
- **Check-in API**: `scanve_api.php` ❌ (chỉ QR, không face)
- **Hậu quả**: Có thể đăng ký mặt nhưng không check-in bằng mặt
- **Trạng thái**: ⚠️ PARTIAL (registration only)
- **Khuyến cáo**: Tích hợp face matching vào check-in logic

---

### 🟡 MEDIUM PRIORITY ISSUES

#### 1. Lịch Sử Xem Phim (Orphan Table)
- **Table**: `lich_su_xem_phim` (có schema)
- **Model**: KHÔNG CÓ
- **Hậu quả**: Không track lịch sử xem phim của khách
- **Khuyến cáo**: Implement model nếu cần feature này

#### 2. Quy Tắc Tích Điểm (Orphan Table)
- **Table**: `quy_tac_tich_diem` (có schema)
- **Model**: KHÔNG CÓ
- **Hậu quả**: Không thể customize quy tắc tích điểm
- **Khuyến cáo**: Implement sau khi tích điểm ready

---

## 📈 THỐNG KÊ HỆ THỐNG

| Metric | Giá Trị |
|--------|---------|
| **Tổng Bảng Dữ Liệu** | 51 |
| **Bảng Lõi** | 45 |
| **Bảng Orphan** | 6 |
| **PHP Model Files** | 30+ |
| **User Roles** | 6 (2 incomplete, 1 not implemented) |
| **Modules** | 6 main domains |
| **Features Implemented** | ~90 |
| **Features Incomplete** | ~5-6 |
| **Features Not Implemented** | ~3-4 |
| **APIs** | ~15+ endpoints (implied) |
| **Payment Methods** | 5 (MoMo, VNPay, ZaloPay, VietQR, Cash) |

---

## 🎯 IMPLEMENTATION STATUS

### Domains Implementation Status

| Domain | Implementation | Ghi Chú |
|--------|----------------|---------|
| 1. Phim | ✅ 95% | Thiếu: Lịch sử xem phim, tích điểm |
| 2. Rạp & Phòng | ✅ 100% | Hoàn chỉnh |
| 3. Lịch Chiếu & Vé | ✅ 90% | Thiếu: Face check-in, tích điểm; Status inconsistent |
| 4. Nhân Viên & Lương | ⚠️ 80% | Schema mismatch chấm công & thanh toán; Incomplete role 3, 4 |
| 5. Khuyến Mãi & Điểm | ❌ 30% | Chưa: Tích điểm, quy tắc, hạng, đổi quà |
| 6. Nội Dung | ✅ 100% | Hoàn chỉnh |
| **Tổng Trung Bình** | **⚠️ 82%** | **Đủ dùng nhưng có một số vấn đề** |

---

## 🔐 Bảo Mật

### Điểm Mạnh
- ✅ Sử dụng PDO Prepared Statements (chống SQL injection)
- ✅ Validation input cơ bản
- ✅ Password hashing (assumed)
- ✅ User role-based access control

### Điểm Yếu / Cần Cải Thiện
- ⚠️ Thiếu HTTPS/SSL enforcement (assumed)
- ⚠️ Không có 2FA (Two-Factor Authentication)
- ⚠️ Không có rate limiting (assumed)
- ⚠️ Không có CSRF token (assumed)
- ⚠️ Face template không encrypt (implied)
- ⚠️ Không có audit logging (assumed)

---

## 💡 Khuyến Cáo & Cải Tiến

### Short-term (Ngay)
1. ✅ Fix schema mismatch chấm công
2. ✅ Fix FK reference thanh toán lương
3. ✅ Standardize status format lịch chiếu
4. ✅ Implement vai_tro = -1 (guest) nếu cần

### Medium-term (1-2 tháng)
1. ✅ Implement tích điểm system hoàn chỉnh
2. ✅ Hoàn thiện vai_tro = 3, 4 (quản lý)
3. ✅ Tích hợp face recognition vào check-in
4. ✅ Implement lịch sử xem phim

### Long-term (3+ tháng)
1. ✅ Migrate sang framework hiện đại (Laravel, Symfony)
2. ✅ Implement unit tests & integration tests
3. ✅ Refactor code (reduce redundancy)
4. ✅ API documentation (REST API standardization)
5. ✅ Mobile app (React Native / Flutter)
6. ✅ Tăng cường bảo mật (HTTPS, 2FA, Audit logging)

---

## 📋 LUỒNG HOẠT ĐỘNG CHÍNH

### Luồng 1: Khách Mua Vé

```
Khách Hàng
   ↓
1. Login / Đăng ký tài khoản (vai_tro = 0)
   ↓
2. Xem danh sách phim
   ↓
3. Chọn phim → Xem lịch chiếu
   ↓
4. Chọn lịch chiếu → Chọn rạp & phòng
   ↓
5. Chọn ghế & combo snacks
   ↓
6. Xem giá (+ áp dụng khuyến mãi)
   ↓
7. Thanh toán (MoMo/VNPay/ZaloPay/VietQR/Cash)
   ↓
8. Nhận vé (PDF + QR code)
   ↓
9. Check-in tại rạp (quét QR code)
   ↓
Hoàn tất
```

### Luồng 2: Nhân Viên Chấm Công

```
Nhân Viên
   ↓
1. Đến rạp → Lấy QR code device
   ↓
2. Quét QR → Check-in vào ca (lưu GPS + giờ)
   ↓
3. Làm việc...
   ↓
4. Kết thúc ca → Quét QR → Check-out (lưu GPS + giờ)
   ↓
5. System tính break duration
   ↓
6. Lưu vào cham_cong table (⚠️ SCHEMA MISMATCH)
   ↓
Hoàn tất
```

### Luồng 3: Admin Thanh Toán Lương

```
Admin
   ↓
1. Chọn tháng & rạp
   ↓
2. System tự động tính bảng lương:
   - Giờ công từ chấm công
   - Lương cơ bản × giờ công
   - + Phụ cấp
   - - Khấu trừ (BHXH, thuế)
   - + Thưởng
   ↓
3. Review bảng lương
   ↓
4. Duyệt bảng lương (Đã duyệt)
   ↓
5. Thanh toán:
   - Chọn phương thức (chuyển khoản, tiền mặt, check)
   - In hóa đơn
   ↓
6. Lưu vào lich_su_thanh_toan (⚠️ FK MISMATCH)
   ↓
Hoàn tất
```

---

## 📞 THÔNG TIN LIÊN HỆ & SUPPORT

**Hệ thống**: CINEPASS - Cinema Management System  
**Stack**: PHP + MySQL  
**Trạng thái**: Development / Testing  
**Mục đích**: Báo cáo đồ án - Hệ thống quản lý rạp chiếu phim

---

## 🏆 KẾT LUẬN

**CINEPASS** là hệ thống quản lý rạp chiếu phim khá hoàn chỉnh với:
- ✅ **90%+ functionality** đã implement
- ✅ **6 user roles** (2 incomplete, 1 not yet)
- ✅ **45 core database tables** đầy đủ
- ⚠️ **Một số vấn đề** cần fix (schema mismatch, FK reference)
- ❌ **Một số tính năng** chưa implement (tích điểm, lịch sử xem phim)

**Dùng hệ thống này cho**: Báo cáo đồ án, demonstration, POC (Proof of Concept)

**Không dùng trong production** mà không:
- Fix các schema mismatches
- Complete các incomplete features
- Tăng cường bảo mật

---

**File này sẵn sàng training ChatGPT!** 🚀

Bạn có thể copy-paste toàn bộ file này vào ChatGPT với prompt như:

```
"Tôi có hệ thống quản lý rạp chiếu phim gọi là CINEPASS.
Dữ liệu đầy đủ được mô tả trong file dưới đây.

[PASTE TONGQUAN.MD]

Dựa trên thông tin này, hãy giúp tôi:
1. Viết báo cáo Word chương 'Phân tích hệ thống'
2. Vẽ sơ đồ kiến trúc hệ thống
3. Vẽ sơ đồ use case theo từng vai trò
4. Vẽ sơ đồ lưu luồng dữ liệu
5. Viết phần kết luận về hệ thống

Vui lòng sử dụng:
- Tiếng Việt
- Ngôn ngữ học thuật / báo cáo đồ án
- ASCII diagrams hoặc mô tả để tôi vẽ sau
"
```

👍 File được tạo thành công!
