# 48 USE CASE SPECIFICATIONS - CinePass Cinema Management System

*(Trích xuất từ Diagram Use Case - Version: Chi tiết đầy đủ)*

---

## KHÁCH HÀNG VÃNG LAI (PUBLIC)

### 1. Xem Danh Sách, Chi Tiết, Lịch Chiếu, Tìm Kiếm, Thể Loại Phim
- **Tên use case:** Xem danh sách, chi tiết, lịch chiếu, tìm kiếm, thể loại phim
- **Mô tả sơ lược:** Cho phép khách vãng lai xem danh sách phim đang chiếu, chi tiết phim kèm trailer, lịch chiếu các rạp, tìm kiếm và lọc theo thể loại
- **Actor chính:** Khách vãng lai
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Hệ thống có phim trong database
  - Lịch chiếu phim đã được lên kế hoạch
  - Phim có thể loại được gán
- **Hậu điều kiện:** 
  - Danh sách phim được hiển thị đầy đủ thông tin (mô tả, trailer, lịch chiếu, thể loại)
  - Khách vãng lai có thể chọn phim để xem chi tiết hoặc đặt vé
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách vào trang chủ | |
| | 2. Hệ thống load danh sách phim từ database |
| | 3. Hiển thị 5-10 phim mỗi trang với hình, tên, thể loại, giới hạn tuổi |
| 4. Khách click menu "Phim" hoặc "Danh sách phim" | |
| | 5. Mở trang danh sách phim có lọc (thể loại, từ khóa, ngày) |
| 6. Khách lọc theo thể loại (Hành động, Hài, v.v.) hoặc nhập từ khóa tìm kiếm | |
| | 7. Hệ thống filter phim theo điều kiện → trả về danh sách phim phù hợp |
| 8. Khách chọn 1 phim | |
| | 9. Hiển thị chi tiết: mô tả, trailer, diễn viên, đạo diễn, thời lượng, lịch chiếu |
| 10. Khách xem chi tiết | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 4a: Nếu khách không tìm được phim → đề xuất phim hot, phim sắp chiếu
  - 7a: Nếu không có phim phù hợp → hiển thị "Không tìm thấy phim"

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 5a: Lỗi load database → hiển thị "Lỗi kết nối, vui lòng thử lại"
  - 9a: Trailer không tải được → hiển thị thông báo, người dùng có thể bỏ qua

### 2. Đăng Ký Tài Khoản
- **Tên use case:** Đăng ký tài khoản
- **Mô tả sơ lược:** Cho phép khách vãng lai tự đăng ký tài khoản thành viên bằng email và mật khẩu, email xác nhận được gửi
- **Actor chính:** Khách vãng lai
- **Actor phụ:** Email gateway (gửi email)
- **Tiền điều kiện:** 
  - Email chưa tồn tại trong hệ thống
  - Trang đăng ký có thể truy cập
- **Hậu điều kiện:** 
  - Tài khoản được tạo (vai_tro = 0)
  - Email xác nhận được gửi tới email người dùng
  - Người dùng có thể đăng nhập sau khi verify email
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách click "Đăng ký" trên trang chủ | |
| | 2. Mở form đăng ký (email, mật khẩu, tên, số điện thoại) |
| 3. Nhập email, mật khẩu (tối thiểu 8 ký tự), họ tên, SĐT | |
| | 4. Validate input (kiểm tra format email, độ dài mật khẩu) |
| | 5. Kiểm tra email có tồn tại trong table `taikhoan` |
| 6. Nếu tất cả hợp lệ, click "Đăng ký" | |
| | 7. Hash mật khẩu (bcrypt) |
| | 8. Insert vào table `taikhoan` (vai_tro = 0, trang_thai = 'chưa verify') |
| | 9. Tạo token verify, lưu vào table `email_verify` |
| | 10. Gửi email verify link tới email người dùng |
| 11. Khách nhận email, click link verify | |
| | 12. Cập nhật trang_thai = 'đã verify' |
| 13. Redirect trang "Đăng ký thành công" | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 5a: Email đã tồn tại → hiển thị "Email đã được đăng ký, vui lòng đăng nhập hoặc lấy lại mật khẩu"

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Input không hợp lệ → hiển thị lỗi chi tiết (email sai format, mật khẩu quá yếu)
  - 10a: Gửi email thất bại → hiển thị "Gửi email thất bại, vui lòng thử lại sau"

---

## KHÁCH HÀNG ĐẶT VÉ NHANH ă(GUEST - KHÔNG ĐĂNG NHẬP)

### 3. Chọn Phim, Ngày Chiếu, Khung Giờ, Email, Chọn Ghế, Combo Đồ Ăn
- **Tên use case:** Chọn phim, ngày chiếu, khung giờ, email, chọn ghế, combo đồ ăn
- **Mô tả sơ lược:** Khách không đăng ký có thể đặt vé nhanh bằng cách nhập email, chọn phim/rạp/suất/ghế/combo
- **Actor chính:** Khách vãng lai
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Phim/rạp/suất chiếu tồn tại
  - Phòng có sơ đồ ghế
  - Ghế còn trống
- **Hậu điều kiện:** 
  - Vé được khóa tạm thời (trang_thai = 0, time_lock = current_time + 15 phút)
  - Email người dùng được lưu
  - Sẵn sàng cho bước thanh toán
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách click "Đặt vé nhanh" | |
| | 2. Hiển thị form: email, phim, rạp, ngày, giờ, ghế, combo |
| 3. Khách nhập email | |
| | 4. Validate email format |
| 5. Chọn phim từ dropdown | |
| | 6. Load danh sách rạp có phim → dropdown rạp |
| 7. Chọn rạp | |
| | 8. Load danh sách ngày chiếu phim ở rạp → dropdown ngày |
| 9. Chọn ngày | |
| | 10. Load danh sách khung giờ chiếu → dropdown giờ |
| 11. Chọn khung giờ | |
| | 12. Load sơ đồ ghế phòng (tô màu: trống=xanh, khóa=vàng, bán=đỏ) |
| 13. Khách chọn 1 hoặc nhiều ghế trống | |
| | 14. Khóa tạm ghế (lock với time_lock = 15 phút), tính giá |
| 15. Khách chọn combo (bắp, nước) hoặc bỏ | |
| | 16. Tính tổng tiền = (giá ghế × số ghế) + giá combo |
| 17. Xem lại → click "Tiếp tục thanh toán" | |
| | 18. Tạo hóa đơn tạm (id_hd), lưu vào table `hoa_don` (trang_thai = 0) |

- **Luồng sự kiện thay thế (alternate flow):**
  - 13a: Ghế được chọn bị khác người book trước → cảnh báo "Ghế đã được chọn, vui lòng chọn ghế khác"
  - 15a: Khách không chọn combo → tổng tiền = giá ghế

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 14a: Hết hạn 15 phút → tự động release ghế, yêu cầu khách chọn lại

### 4. Xử Lý Thanh Toán
- **Tên use case:** Xử lý thanh toán
- **Mô tả sơ lược:** Khách thanh toán vé bằng ZaloPay hoặc tiền mặt, hệ thống cập nhật trạng thái vé/hóa đơn
- **Actor chính:** Khách vãng lai / Thành viên
- **Actor phụ:** ZaloPay gateway
- **Tiền điều kiện:** 
  - Vé được khóa (trang_thai = 0)
  - Hóa đơn tạm đã được tạo (trang_thai = 0)
  - Ghế chưa hết thời gian khóa (< 15 phút)
- **Hậu điều kiện:** 
  - Vé cập nhật (trang_thai = 1 - đã thanh toán)
  - Hóa đơn cập nhật (trang_thai = 1, ngay_tt = now())
  - Điểm được tích lũy (nếu member)
  - Email xác nhận + QR code gửi tới người dùng
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách click "Thanh toán" | |
| | 2. Hiển thị tóm tắt đơn (phim, ghế, giá, combo) |
| | 3. Hiển thị form chọn phương thức (ZaloPay, Tiền mặt tại rạp) |
| 4. Chọn ZaloPay | |
| | 5. Redirect tới ZaloPay gateway |
| 6. Nhập thông tin thanh toán trên ZaloPay | ZaloPay |
| | (ZaloPay xác thực thẻ → callback IPN) |
| 7. ZaloPay trả kết quả | |
| | 8. Hệ thống nhận callback IPN → check status_code |
| | 9. Nếu thành công (status_code=1): UPDATE ve.trang_thai = 1 |
| | 10. UPDATE hoa_don.trang_thai = 1, ngay_tt = now() |
| | 11. Tính điểm (nếu member): diem += price/1000, INSERT diem table |
| | 12. Tạo mã QR từ id_ve (PHP-QRCode) |
| | 13. Gửi email xác nhận (PHPMailer): vé, QR, thông tin phim |
| 14. Hiển thị "Thanh toán thành công" + link tải QR | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 3a: Chọn "Thanh toán tại rạp" → tạo hóa đơn pending, khách thanh toán khi đến rạp

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 5a: Thanh toán thất bại → hiển thị "Giao dịch thất bại, vui lòng thử lại"
  - 7a: Timeout callback → hóa đơn chuyển sang pending, gửi email thông báo

### 5. Áp Dụng Khuyến Mãi
- **Tên use case:** Áp dụng khuyến mãi
- **Mô tả sơ lược:** Khách nhập mã khuyến mãi để giảm giá vé trong quá trình đặt vé
- **Actor chính:** Khách vãng lai / Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Mã khuyến mãi hợp lệ (tồn tại trong DB)
  - Chưa hết hạn sử dụng
  - Chưa vượt số lần dùng tối đa
- **Hậu điều kiện:** 
  - Giá tiền được giảm
  - Tổng tiền được cập nhật
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách nhập mã khuyến mãi (ô "Mã giảm giá") | |
| | 2. Validate mã (kiểm tra định dạng, tồn tại trong table `khuyenmai`) |
| | 3. Kiểm tra ngày hết hạn (date_end >= today) |
| | 4. Kiểm tra số lần dùng (used_count < max_count) |
| | 5. Lấy giá trị giảm (% hoặc tiền) từ DB |
| 6. Click "Áp dụng" | |
| | 7. Tính tiền giảm = price × (discount_percent / 100) OR discount_fixed |
| | 8. Cập nhật tổng tiền = price - discount |
| 9. Hiển thị tiền giảm + tiền mới | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 2a: Mã không tồn tại → "Mã không hợp lệ"
  - 3a: Hết hạn → "Mã đã hết hạn sử dụng"

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Vượt số lần dùng → "Bạn đã dùng mã này quá nhiều"

---

## KHÁCH HÀNG THÀNH VIÊN (MEMBER - CÓ ĐĂNG NHẬP)

### 6. Đăng Nhập
- **Tên use case:** Đăng nhập
- **Mô tả sơ lược:** Thành viên đăng nhập bằng email/mật khẩu để truy cập hệ thống
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Tài khoản đã đăng ký
  - Tài khoản không bị khóa
  - Email đã verify
- **Hậu điều kiện:** 
  - Session được tạo
  - Cookie được lưu trên browser
  - Chuyển hướng trang chủ
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách click "Đăng nhập" | |
| | 2. Mở form đăng nhập (email, mật khẩu) |
| 3. Nhập email + mật khẩu | |
| | 4. Validate input (kiểm tra không rỗng) |
| | 5. Tìm email trong table `taikhoan` |
| | 6. So sánh hash(mật khẩu nhập) với password_hash trong DB |
| 7. Nhấn "Đăng nhập" | |
| | 8. Nếu khớp: tạo session_id, lưu vào table `sessions` |
| | 9. Lưu session_id vào cookie (HttpOnly, Secure) |
| | 10. Cập nhật last_login = now() |
| 11. Redirect trang chủ | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 5a: Email không tồn tại → "Email không đúng hoặc chưa đăng ký"

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Mật khẩu sai → hiển thị "Mật khẩu không đúng, vui lòng thử lại"
  - 6b: Tài khoản bị khóa → "Tài khoản đã bị khóa, vui lòng liên hệ hỗ trợ"

### 7. Quên Mật Khẩu
- **Tên use case:** Quên mật khẩu
- **Mô tả sơ lược:** Thành viên quên mật khẩu, nhận link reset qua email
- **Actor chính:** Thành viên
- **Actor phụ:** Email gateway
- **Tiền điều kiện:** 
  - Email tồn tại trong hệ thống
- **Hậu điều kiện:** 
  - Email reset được gửi với link đặt lại mật khẩu
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Click "Quên mật khẩu" trên trang đăng nhập | |
| | 2. Mở form "Nhập email để reset" |
| 3. Nhập email | |
| | 4. Kiểm tra email tồn tại trong table `taikhoan` |
| | 5. Tạo token reset ngẫu nhiên, lưu vào table `password_reset` (expires = now + 1 giờ) |
| 6. Click "Gửi link reset" | |
| | 7. Gửi email với link: /reset-password?token=XXX |
| 8. Thành viên nhận email, click link | |
| | 9. Validate token (chưa hết hạn, tồn tại trong DB) |
| | 10. Mở form "Nhập mật khẩu mới" |
| 11. Nhập mật khẩu mới (tối thiểu 8 ký tự), confirm | |
| | 12. Hash mật khẩu mới, update table `taikhoan` |
| | 13. Xóa token từ table `password_reset` |
| 14. Hiển thị "Đặt lại mật khẩu thành công" → Redirect đăng nhập | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Email không tồn tại → hiển thị "Email không tìm thấy"
  - 9a: Token hết hạn → "Link reset đã hết hạn, vui lòng yêu cầu link mới"

### 8. Đổi Mật Khẩu
- **Tên use case:** Đổi mật khẩu
- **Mô tả sơ lược:** Thành viên đổi mật khẩu hiện tại bằng mật khẩu mới
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
- **Hậu điều kiện:** 
  - Mật khẩu được cập nhật
  - Session được làm mới
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Đăng nhập → Vào "Hồ sơ cá nhân" | |
| | 2. Hiển thị tab "Bảo mật" |
| 3. Click "Đổi mật khẩu" | |
| | 4. Mở form (mật khẩu cũ, mật khẩu mới, xác nhận mới) |
| 5. Nhập mật khẩu cũ | |
| | 6. So sánh hash(mật khẩu cũ) với password_hash hiện tại |
| 7. Nhập mật khẩu mới (tối thiểu 8 ký tự), xác nhận | |
| | 8. Validate (mật khẩu mới ≠ mật khẩu cũ, độ dài, độ mạnh) |
| 9. Click "Đổi mật khẩu" | |
| | 10. Hash mật khẩu mới, update table `taikhoan` |
| | 11. Logout all sessions cũ, tạo session mới |
| 12. Hiển thị "Đổi mật khẩu thành công" | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Mật khẩu cũ sai → "Mật khẩu cũ không đúng"
  - 8a: Mật khẩu mới yếu → "Mật khẩu phải chứa chữ hoa, chữ thường, số, ký tự đặc biệt"

### 9. Quản Lí Tài Khoản
- **Tên use case:** Quản lý tài khoản
- **Mô tả sơ lược:** Thành viên xem/sửa thông tin hồ sơ cá nhân
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
- **Hậu điều kiện:** 
  - Thông tin được cập nhật trong database
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Đăng nhập → Click "Hồ sơ cá nhân" | |
| | 2. Hiển thị form hồ sơ (họ tên, email, SĐT, địa chỉ, ngày sinh) |
| 3. Xem thông tin hiện tại | |
| 4. Sửa các trường (SĐT, địa chỉ, etc.) - không sửa email | |
| | 5. Validate dữ liệu (SĐT format, độ dài địa chỉ) |
| 6. Click "Lưu" | |
| | 7. Update table `taikhoan` |
| | 8. Hiển thị "Cập nhật thành công" |

### 10. Đặt Vé Online
- **Tên use case:** Đặt vé Online
- **Mô tả sơ lược:** Thành viên đặt vé qua website, tích điểm tự động
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
  - Phim/suất/ghế còn trống
- **Hậu điều kiện:** 
  - Vé được tạo (trang_thai = 0)
  - Chờ thanh toán
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Click "Đặt vé" trên trang chủ | |
| | 2. Hiển thị form đặt vé (phim, rạp, ngày, giờ, ghế, combo) |
| 3. Chọn phim | |
| | 4. Load rạp + ngày + giờ như flow UC #3 |
| 5. Chọn rạp/ngày/giờ | |
| 6. Chọn ghế + combo | |
| | 7. Tính tiền (bao gồm điểm dùng nếu có) |
| 8. Xem tính toán → Click "Tiếp tục thanh toán" | |
| | 9. Tạo hóa đơn tạm, tạo vé (trang_thai = 0) |
| | 10. Redirect sang bước thanh toán |

### 11. Chọn Rạp
- **Tên use case:** Chọn rạp
- **Mô tả sơ lược:** Khi đặt vé, thành viên chọn rạp chiếu để xem lịch chiếu tại rạp đó
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Phim được chọn đó có lịch chiếu ở một rạp trở lên
- **Hậu điều kiện:** 
  - Rạp được chọn
  - Danh sách lịch chiếu của rạp được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên click để chọn rạp | |
| | 2. Hệ thống load danh sách rạp có phim được chọn |
| | 3. Hiển thị dropdown rạp (tên rạp, địa chỉ, số điểm) |
| 4. Thành viên chọn 1 rạp | |
| | 5. Tải lịch chiếu của rạp đó (ngày, giờ, phòng, giá) |
| | 6. Cập nhật dropdown ngày chiếu |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 2a: Không có rạp nào chiếu phim → "Phim này không được chiếu tại rạp nào"

### 12. Chọn Ghế, Combo Đồ Ăn
- **Tên use case:** Chọn ghế, combo đồ ăn
- **Mô tả sơ lược:** Thành viên chọn ghế ngồi từ sơ đồ phòng và chọn các combo bắp, nước
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Phòng/suất chiếu đã được chọn
  - Phòng có sơ đồ ghế được cấu hình
  - Ghế còn trống
- **Hậu điều kiện:** 
  - Ghế được khóa tạm thời (time_lock = 15 phút)
  - Combo được thêm vào giỏ hàng
  - Tổng tiền được tính
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên xem sơ đồ ghế phòng | |
| | 2. Hệ thống load sơ đồ ghế từ phong_ghe table |
| | 3. Tô màu ghế: xanh (trống), vàng (khóa), đỏ (đã bán) |
| 4. Thành viên click chọn 1 hoặc nhiều ghế trống | |
| | 5. Khóa tạm ghế (time_lock = 15 phút) |
| | 6. Tính giá từng ghế theo loại (VIP, thường) |
| 7. Thành viên chọn combo (bắp, nước, combo combo) | |
| | 8. Hiển thị danh sách combo có sẵn + giá |
| 9. Thành viên chọn combo, số lượng | |
| | 10. Tính tổng tiền = (ghế × giá ghế) + (combo × giá combo) |
| 11. Xem tính toán chi tiết | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 7a: Thành viên không chọn combo → tổng tiền = giá ghế

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Ghế bị chọn bởi người khác trong 15 phút → "Ghế vừa được chọn, vui lòng chọn ghế khác"
  - 5a: Hết hạn 15 phút → ghế tự động release, yêu cầu chọn lại

### 13. Thanh Toán (Member)
- **Tên use case:** Thanh toán (Member)
- **Mô tả sơ lược:** Thành viên thanh toán vé đã chọn bằng ZaloPay/thẻ, tích điểm tự động
- **Actor chính:** Thành viên
- **Actor phụ:** ZaloPay gateway
- **Tiền điều kiện:** 
  - Vé được khóa (trang_thai = 0)
  - Hóa đơn tạm đã được tạo
  - Ghế chưa hết thời gian khóa
- **Hậu điều kiện:** 
  - Vé cập nhật (trang_thai = 1)
  - Điểm được tích lũy (1 điểm = 1000 VND)
  - Email xác nhận + QR code gửi tới
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên click "Thanh toán" | |
| | 2. Hiển thị form chọn phương thức (ZaloPay, Thẻ, Tiền mặt) |
| 3. Chọn ZaloPay hoặc Thẻ | |
| | 4. Redirect tới ZaloPay gateway |
| 5. Nhập thông tin thanh toán trên ZaloPay | ZaloPay |
| | (ZaloPay xác thực) → callback thành công |
| 6. Xác nhận thanh toán | |
| | 7. Nhận callback → cập nhật ve.trang_thai = 1 |
| | 8. Cập nhật hoa_don.trang_thai = 1, ngay_thanh_toan = now() |
| | 9. Tính điểm: diem = price / 1000, insert vào diem table |
| | 10. Tạo mã QR từ id_ve |
| | 11. Gửi email: thông tin vé, QR code, lịch chiếu |
| 12. Hiển thị "Thanh toán thành công" | |
| 13. Tải hoặc xem QR code | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 5a: Thanh toán thất bại → "Giao dịch bị từ chối, vui lòng thử lại"
  - 7a: Timeout callback → hóa đơn chuyển pending, email thông báo

### 14. Lịch Sử Đặt Vé
- **Tên use case:** Lịch sử đặt vé
- **Mô tả sơ lược:** Thành viên xem danh sách tất cả vé đã mua/đã đặt
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
  - Thành viên có vé trong hệ thống
- **Hậu điều kiện:** 
  - Danh sách vé được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào menu "Vé của tôi" hoặc "Lịch sử đặt vé" | |
| | 2. Load danh sách vé: SELECT * FROM ve WHERE id_tk = member_id ORDER BY ngay_dat DESC |
| | 3. Hiển thị bảng (phim, rạp, ngày chiếu, giá, trạng thái) |
| 4. Thành viên xem danh sách | |
| 5. Click vé để xem chi tiết | |
| | 6. Mở trang chi tiết vé (QR code, thông tin phim, phòng, ghế, giờ chiếu) |
| 7. Thành viên có thể tải QR hoặc in vé | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 2a: Không có vé → "Bạn chưa đặt vé nào"

### 15. Dùng Voucher/Mã Giảm Giá Khi Thanh Toán
- **Tên use case:** Dùng voucher/mã giảm giá khi thanh toán
- **Mô tả sơ lược:** Thành viên nhập mã voucher để giảm giá vé trước khi thanh toán
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Mã voucher hợp lệ (tồn tại, chưa hết hạn, chưa vượt số lần dùng)
- **Hậu điều kiện:** 
  - Giá tiền được giảm trên hóa đơn
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên nhập mã voucher vào ô "Mã giảm giá" | |
| | 2. Validate mã (format, tồn tại trong khuyenmai table) |
| | 3. Kiểm tra date_end >= today, used_count < max_count |
| 4. Click "Áp dụng" | |
| | 5. Tính tiền giảm (% hoặc fixed amount) |
| | 6. Cập nhật tổng tiền mới = price - discount |
| 7. Xem giá đã giảm | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 2a: Mã không hợp lệ → "Mã không tồn tại"
  - 3a: Hết hạn → "Mã đã hết hạn", vượt số lần → "Bạn đã dùng mã này quá nhiều"

### 16. Bình Luận/Đánh Giá
- **Tên use case:** Bình luận/đánh giá
- **Mô tả sơ lược:** Thành viên gửi bình luận và rating phim sau khi xem
- **Actor chính:** Thành viên
- **Actor phụ:** Quản lý rạp (duyệt)
- **Tiền điều kiện:** 
  - Thành viên đã mua vé phim này (trang_thai_ve = 4 - đã dùng)
  - Thành viên đã đăng nhập
- **Hậu điều kiện:** 
  - Bình luận được lưu (chờ duyệt hoặc đã duyệt)
  - Hiển thị dưới trang chi tiết phim
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào chi tiết phim → click "Bình luận" | |
| | 2. Mở form bình luận (rating 1-5 sao, tiêu đề, nội dung) |
| 3. Chọn số sao (1-5 ⭐) | |
| 4. Nhập tiêu đề (max 100 ký tự) | |
| 5. Nhập nội dung (max 500 ký tự) | |
| | 6. Validate (không rỗng, độ dài hợp lệ) |
| 7. Click "Gửi bình luận" | |
| | 8. Filter từ nhạy cảm (nếu có từ lạm dụng → chờ duyệt) |
| | 9. Insert vào binhluan table |
| 10. Hiển thị "Bình luận chờ duyệt" hoặc "Bình luận thành công" | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Form rỗng → "Vui lòng điền đầy đủ thông tin"

### 17. Tích Điểm (Mua Vé Tích Điểm, Thứ Hạng, Lịch Sử Điểm)
- **Tên use case:** Tích điểm (mua vé tích điểm, thứ hạng, lịch sử điểm)
- **Mô tả sơ lược:** Thành viên xem tổng điểm tích lũy, thứ hạng (Bạc/Vàng/Kim cương), lịch sử giao dịch điểm
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
  - Thành viên có vé thanh toán thành công
- **Hậu điều kiện:** 
  - Thông tin điểm được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào "Tích điểm" hoặc "Tài khoản" → "Điểm" | |
| | 2. Load điểm từ diem table WHERE id_tk = member_id |
| | 3. Xác định thứ hạng: Bạc (0-5000), Vàng (5001-10000), Kim cương (>10000) |
| | 4. Hiển thị: Tổng điểm, thứ hạng, điểm cần để lên hạng |
| 5. Click "Xem lợi ích thành viên" | |
| | 6. Hiển thị bảng lợi ích (Bạc: -5%, Vàng: -10%, Kim cương: -15%) |
| 7. Xem "Lịch sử điểm" | |
| | 8. Load lịch sử từ diem table (ngày, tên phim, +/-điểm, số dư) |
| 9. Click "Dùng điểm" | |
| | 10. Form để chuyển đổi điểm thành tiền giảm giá |

---

## NHÂN VIÊN RẠP (STAFF)

### 18. Bán Vé (Tại Quầy)
- **Tên use case:** Bán vé (tại quầy)
- **Mô tả sơ lược:** Nhân viên rạp bán vé cho khách tại quầy, in vé hoặc gửi email
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Máy in vé
- **Tiền điều kiện:** 
  - Nhân viên đã đăng nhập (vai_tro = 1)
  - Phim/suất/ghế còn trống
- **Hậu điều kiện:** 
  - Vé được tạo (trang_thai = 1)
  - Khách nhận vé (in hoặc email)
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên vào menu "Bán vé" | |
| | 2. Hiển thị form bán vé (phim, rạp, ngày, giờ, ghế, combo) |
| 3. Chọn phim/rạp/ngày/giờ/ghế/combo (tương tự UC #3) | |
| 4. Nhập email hoặc SĐT khách (hoặc để trống) | |
| | 5. Tính tiền |
| 6. Khách thanh toán bằng tiền mặt | |
| | 7. Tạo vé (trang_thai = 1) |
| 8. Nhân viên chọn: In vé hoặc Gửi email | |
| | 9. Nếu in: gửi lệnh in → máy in vé |
| | 10. Nếu email: gửi email với QR code |
| 11. Khách nhận vé | |

### 19. Kiểm Tra Vé Cho Khách Bằng Mã, QR
- **Tên use case:** Kiểm tra vé cho khách bằng mã, QR (check-in)
- **Mô tả sơ lược:** Nhân viên scan QR hoặc nhập mã vé để kiểm tra và check-in khách vào phòng chiếu
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Thiết bị scanner/camera QR
- **Tiền điều kiện:** 
  - Vé đã thanh toán (trang_thai = 1)
  - Suất chiếu chưa bắt đầu (ngay_chieu >= now())
  - Khách có QR code hoặc mã vé
- **Hậu điều kiện:** 
  - Vé được check-in (trang_thai = 4 - đã sử dụng)
  - check_in_luc = now(), check_in_boi = nhân viên ID
  - Khách được phép vào phòng chiếu
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên sử dụng thiết bị scan QR tại cửa phòng | |
| | 2. Mở form nhập mã vé (hoặc tự động từ scanner) |
| 3. Khách đưa điện thoại (QR) hoặc mã vé | |
| | 4. Nhân viên scan QR (hoặc nhập mã) |
| | 5. Hệ thống gọi ve_find_by_code(ma_ve) |
| | 6. Kiểm tra vé tồn tại trong DB |
| | 7. Kiểm tra trang_thai = 1 (đã thanh toán) |
| | 8. Kiểm tra ngay_chieu >= now() (chưa bắt đầu) |
| 9. Nếu hợp lệ: hiển thị "✅ Vé hợp lệ" (màu xanh) | |
| | 10. Cập nhật ve.trang_thai = 4 (checked-in) |
| | 11. Cập nhật check_in_luc = now(), check_in_boi = staff_id |
| 12. Nhân viên cho khách vào phòng chiếu | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 5a: Vé không tồn tại → "❌ Vé không hợp lệ" (màu đỏ)
  - 7a: Vé chưa thanh toán (trang_thai=0) → "❌ Vé chưa thanh toán"
  - 8a: Suất chiếu đã bắt đầu (ngay_chieu < now()) → "❌ Suất chiếu đã bắt đầu"
  - 10a: Vé đã được check-in (trang_thai=4) → "❌ Vé này đã được sử dụng"

### 20. Chấm Công (Khuôn Mặt)
- **Tên use case:** Chấm công (khuôn mặt)
- **Mô tả sơ lược:** Nhân viên chấm công bằng nhận diện khuôn mặt (facial recognition)
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Camera, facial recognition engine
- **Tiền điều kiện:** 
  - Nhân viên có lịch làm hôm nay
  - Camera hoạt động bình thường
- **Hậu điều kiện:** 
  - Thời gian chấm công được lưu (gio_vao/gio_ra)
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên đứng trước camera chấm công | |
| | 2. Camera capture khuôn mặt |
| | 3. Facial recognition engine so sánh với DB |
| | 4. Nếu khớp: xác định nhân viên (id_nv) |
| | 5. Ghi nhận giờ vào/ra tùy theo lần chấm |
| | 6. Insert vào chamcong table (id_nv, id_rap, ngay, gio_vao hoặc gio_ra) |
| 7. Hiển thị "Chấm công thành công" + giờ | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 3a: Khuôn mặt không khớp → "Không nhận diện được"
  - 2a: Camera bị lỗi → "Camera không hoạt động"

### 21. Xin Nghỉ Phép
- **Tên use case:** Xin nghỉ phép
- **Mô tả sơ lược:** Nhân viên gửi yêu cầu nghỉ phép, quản lý rạp duyệt
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Quản lý rạp (duyệt)
- **Tiền điều kiện:** 
  - Nhân viên đã đăng nhập
  - Ngày yêu cầu chưa đăng ký nghỉ
- **Hậu điều kiện:** 
  - Request được tạo (chờ duyệt)
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên click "Xin nghỉ phép" | |
| | 2. Mở form (chọn ngày, lý do, loại nghỉ) |
| 3. Chọn 1 hoặc nhiều ngày | |
| 4. Nhập lý do (bệnh, việc riêng, etc.) | |
| 5. Click "Gửi yêu cầu" | |
| | 6. Kiểm tra ngày không trùng lịch làm |
| | 7. Insert vào nghiphep table (trang_thai = 'chờ duyệt') |
| | 8. Gửi notification tới quản lý rạp |
| 9. Hiển thị "Yêu cầu được gửi" | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Ngày đã có lịch → "Ngày này bạn có lịch làm"

### 22. Xem Lịch Làm Việc
- **Tên use case:** Xem lịch làm việc
- **Mô tả sơ lược:** Nhân viên xem ca làm được phân công (sáng/chiều/tối)
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Nhân viên đã đăng nhập
  - Có lịch làm trong hệ thống
- **Hậu điều kiện:** 
  - Lịch làm được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên vào "Lịch làm của tôi" | |
| | 2. Load lịch từ lich_lam_viec WHERE id_nhan_vien = staff_id |
| | 3. Hiển thị lịch dạng bảng (tuần hoặc tháng) |
| 4. Xem chi tiết ca làm (giờ bắt đầu, giờ kết thúc, phòng) | |
| 5. Click "Lịch tuần" hoặc "Lịch tháng" | |
| | 6. Chuyển đổi view hiển thị |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 2a: Không có lịch → "Bạn chưa có lịch làm nào"

### 23. Báo Cáo Thống Kê Cá Nhân
- **Tên use case:** Báo cáo thống kê cá nhân
- **Mô tả sơ lược:** Nhân viên xem báo cáo cá nhân (vé bán, lương, chấm công)
- **Actor chính:** Nhân viên rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Nhân viên đã đăng nhập
  - Có dữ liệu chấm công/vé/lương
- **Hậu điều kiện:** 
  - Báo cáo được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Nhân viên vào "Thống kê cá nhân" | |
| | 2. Chọn tháng (mặc định tháng hiện tại) |
| 3. Chọn tháng, năm | |
| | 4. Hệ thống tính toán: |
| | - Tổng vé bán: COUNT(ve) WHERE id_nv = staff_id |
| | - Doanh thu: SUM(price) FROM ve |
| | - Chấm công: COUNT(days) WITH gio_vao AND gio_ra |
| | - Lương: tính từ số ca làm × lương_giờ |
| 5. Click "Xem báo cáo" | |
| | 6. Hiển thị bảng tổng hợp (vé bán, doanh thu, ca làm, lương) |
| 7. Nhân viên xem báo cáo | |

---

## QUẢN LÝ RẠP (CINEMA MANAGER)

### 24. Lập Kế Hoạch Chiếu Phim
- **Tên use case:** Lập kế hoạch chiếu phim
- **Mô tả sơ lược:** Quản lý rạp thêm lịch chiếu phim (ngày, giờ, phòng), gửi duyệt quản lý cụm
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Quản lý cụm (duyệt)
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập (vai_tro = 3)
  - Phim tồn tại
  - Phòng có sơ đồ ghế
- **Hậu điều kiện:** 
  - Lịch chiếu được tạo (trang_thai_duyet = 'Chờ duyệt')
  - Chờ quản lý cụm duyệt
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Lịch chiếu" | |
| | 2. Hiển thị danh sách lịch hiện có + nút "Thêm lịch" |
| 3. Click "Thêm lịch chiếu" | |
| | 4. Mở form (phim, ngày, khung giờ, phòng) |
| 5. Chọn phim (dropdown) | |
| 6. Chọn ngày chiếu | |
| 7. Chọn khung giờ (sáng/chiều/tối) | |
| 8. Chọn phòng chiếu | |
| | 9. Validate (không trùng lịch, phòng có ghế, phim có loại phim) |
| 10. Click "Lưu" | |
| | 11. Insert vào table `lichchieu` (trang_thai_duyet = 'Chờ duyệt') |
| | 12. Gửi notification tới Quản lý cụm |
| 13. Hiển thị "Thêm lịch thành công, chờ duyệt" | |

- **Luồng sự kiện thay thế (alternate flow):**
  - 9a: Trùng lịch → "Khung giờ này phòng đã có lịch"
  - 9b: Phòng không có ghế → "Phòng chưa cấu hình sơ đồ ghế"

### 25. Xem Lịch Của Rạp Mình
- **Tên use case:** Xem lịch của rạp mình
- **Mô tả sơ lược:** Quản lý rạp xem tất cả lịch chiếu của rạp mình
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
- **Hậu điều kiện:** 
  - Danh sách lịch được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Quản lý lịch chiếu" | |
| | 2. Load tất cả lịch của rạp (WHERE id_rap = manager_rap_id) |
| | 3. Hiển thị danh sách theo ngày/tháng (phim, giờ, phòng, trang thái duyệt, số vé bán) |
| 4. Khách xem, lọc theo trạng thái (tất cả/chờ duyệt/đã duyệt/từ chối) | |
| | 5. Hiển thị danh sách được lọc |
| 6. Click 1 lịch để xem chi tiết / chỉnh sửa / xóa | |

### 26. Quản Lý Phòng - Ghế
- **Tên use case:** Quản lý phòng - ghế
- **Mô tả sơ lược:** Quản lý rạp tạo phòng chiếu mới, cấu hình số ghế, loại ghế (VIP/thường)
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
- **Hậu điều kiện:** 
  - Phòng/ghế được tạo, sơ đồ cập nhật
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Phòng chiếu" | |
| | 2. Hiển thị danh sách phòng + nút "Thêm phòng" |
| 3. Click "Thêm phòng mới" | |
| | 4. Mở form (tên phòng, số ghế, cấu hình ghế) |
| 5. Nhập tên phòng (VD: "Phòng 1", "IMAX") | |
| 6. Nhập số ghế (VD: 100) | |
| 7. Thiết lập loại ghế (% VIP, % thường) | |
| | 8. Validate (tên không rỗng, số ghế > 0) |
| 9. Click "Lưu" | |
| | 10. Insert vào phongchieu table |
| | 11. Tạo sơ đồ ghế mặc định trong phong_ghe table |
| | 12. Hiển thị "Phòng được thêm thành công" |

### 27. Quản Lý Khuyến Mãi - Combo Đồ Ăn
- **Tên use case:** Quản lý khuyến mãi - combo đồ ăn
- **Mô tả sơ lược:** Tạo/sửa combo bắp-nước, giá, khuyến mãi cho rạp
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
- **Hậu điều kiện:** 
  - Combo/khuyến mãi được tạo, hiển thị cho khách
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Combo & Khuyến mãi" | |
| | 2. Hiển thị danh sách combo hiện có |
| 3. Click "Thêm combo" | |
| | 4. Mở form (tên combo, giá, hình ảnh, mô tả) |
| 5. Nhập tên combo, giá, upload hình | |
| | 6. Validate (tên không rỗng, giá > 0) |
| 7. Click "Lưu" | |
| | 8. Insert vào combo_do_an table |
| 9. Hoặc click "Khuyến mãi" | |
| | 10. Mở form tạo khuyến mãi (% giảm, tiền cố định, ngày hết hạn) |
| 11. Nhập thông tin khuyến mãi | |
| | 12. Insert vào khuyenmai table |

### 28. Quản Lý Nhân Sự (Chấm Công, Lương)
- **Tên use case:** Quản lý nhân sự (chấm công, lương)
- **Mô tả sơ lược:** Quản lý rạp xem chấm công nhân viên, tính lương, phân ca
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
  - Có nhân viên trong rạp
- **Hậu điều kiện:** 
  - Chấm công được xem, lương được tính
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Quản lý nhân sự" | |
| | 2. Hiển thị danh sách nhân viên |
| 3. Xem chấm công nhân viên | |
| | 4. Load từ chamcong table GROUP BY id_nv, month |
| | 5. Hiển thị: tên nhân viên, số ngày chấm, giờ làm, lương |
| 6. Tính lương = tổng_giờ × lương_giờ | |
| 7. Click "Phân ca" | |
| | 8. Mở form phân công lịch làm |
| 9. Chọn nhân viên, ngày, ca (sáng/chiều/tối) | |
| | 10. Insert vào lich_lam_viec table |

### 29. Quản Lý Tài Khoản Trong Rạp Của Mình
- **Tên use case:** Quản lý tài khoản trong rạp của mình
- **Mô tả sơ lược:** Quản lý rạp quản lý tài khoản nhân viên của rạp (thêm, sửa, khóa)
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
- **Hậu điều kiện:** 
  - Tài khoản được tạo/cập nhật
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Quản lý nhân viên" | |
| | 2. Hiển thị danh sách nhân viên rạp |
| 3. Click "Thêm nhân viên" | |
| | 4. Mở form (email, họ tên, SĐT, địa chỉ) |
| 5. Nhập thông tin nhân viên | |
| | 6. Validate (email không tồn tại) |
| 7. Click "Lưu" | |
| | 8. Tạo tài khoản (vai_tro = 1, id_rap = manager_rap_id) |
| | 9. Gửi email thông báo tài khoản tới nhân viên |
| 10. Hiển thị "Thêm nhân viên thành công" | |

### 30. Quản Lý Vé
- **Tên use case:** Quản lý vé
- **Mô tả sơ lược:** Xem danh sách vé, hủy vé, xem trạng thái
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có vé trong rạp
- **Hậu điều kiện:** 
  - Danh sách vé được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Quản lý vé" | |
| | 2. Load danh sách vé của rạp (WHERE id_rap = manager_rap_id) |
| | 3. Hiển thị bảng (phim, khách, giá, ngày, trạng thái) |
| 4. Lọc vé theo trạng thái (tất cả/chờ thanh toán/đã thanh toán/hủy) | |
| | 5. Hiển thị danh sách được lọc |
| 6. Click vé để xem chi tiết | |
| 7. Nếu cần: click "Hủy vé" | |
| | 8. Cập nhật ve.trang_thai = 'hủy', refund tiền |

### 31. Quản Lý Thiết Bị - Tài Sản
- **Tên use case:** Quản lý thiết bị - tài sản
- **Mô tả sơ lược:** Quản lý máy chiếu, tủ lạnh, tài sản khác, báo cáo hỏng hóc
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập
- **Hậu điều kiện:** 
  - Danh sách thiết bị được quản lý
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Thiết bị" | |
| | 2. Hiển thị danh sách thiết bị (máy chiếu, tủ lạnh, âm thanh, etc.) |
| 3. Xem danh sách thiết bị | |
| 4. Click "Báo cáo hỏng hóc" nếu phát hiện vấn đề | |
| | 5. Mở form báo cáo (thiết bị, vấn đề, mức độ, mô tả) |
| 6. Nhập thông tin báo cáo | |
| | 7. Insert vào thietbi table (trang_thai = 'báo cáo') |
| 8. Gửi thông báo tới quản lý cụm | |

### 32. Quản Lý Bình Luận
- **Tên use case:** Quản lý bình luận
- **Mô tả sơ lược:** Xem, duyệt, từ chối, trả lời bình luận khách
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có bình luận từ khách
- **Hậu điều kiện:** 
  - Bình luận được duyệt/từ chối
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Quản lý bình luận" | |
| | 2. Load danh sách bình luận chờ duyệt |
| | 3. Hiển thị (khách, phim, rating, nội dung, ngày) |
| 4. Click bình luận để xem đầy đủ | |
| 5. Nhận xét bình luận: duyệt hoặc từ chối | |
| | 6. Nếu duyệt: update trang_thai = 'đã duyệt' |
| | 7. Nếu từ chối: update trang_thai = 'từ chối' + nhập lý do |
| | 8. Có thể trả lời bình luận |

### 33. Xem Báo Cáo Trong Rạp Của Mình
- **Tên use case:** Xem báo cáo trong rạp của mình
- **Mô tả sơ lược:** Xem báo cáo doanh thu rạp, thống kê vé, combo
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có vé/combo đã thanh toán
- **Hậu điều kiện:** 
  - Báo cáo được hiển thị, có thể export
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Báo cáo" | |
| | 2. Chọn khoảng thời gian (ngày, tháng) |
| 3. Chọn từ ngày - đến ngày | |
| | 4. Load dữ liệu từ hoa_don, combo, vé |
| | 5. Tính toán: |
| | - Tổng vé bán: COUNT(ve) |
| | - Doanh thu vé: SUM(price FROM ve) |
| | - Doanh thu combo: SUM(price FROM combo) |
| | - Tổng doanh thu: vé + combo |
| 6. Click "Xem báo cáo" | |
| | 7. Hiển thị bảng tổng hợp + biểu đồ |
| 8. Có thể click "Export PDF" hoặc "Export Excel" | |

---

## QUẢN LÝ CỤM (CLUSTER MANAGER)

### 34. Quản Lý Phim
- **Tên use case:** Quản lý phim
- **Mô tả sơ lược:** Quản lý cụm thêm phim mới, sửa info, xóa phim (hoặc Admin)
- **Actor chính:** Quản lý cụm / Admin
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý cụm/Admin đã đăng nhập (vai_tro = 4/2)
- **Hậu điều kiện:** 
  - Phim được thêm/cập nhật/xóa
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Quản lý phim" | |
| | 2. Hiển thị danh sách phim + nút "Thêm phim" |
| 3. Click "Thêm phim mới" | |
| | 4. Mở form (tên phim, mô tả, trailer, đạo diễn, diễn viên, hình, thời lượng, loại phim, giới hạn tuổi) |
| 5. Nhập đầy đủ thông tin | |
| | 6. Validate (tên không rỗng, hình upload được, trailer URL hợp lệ) |
| 7. Upload hình | |
| | 8. Lưu hình vào server, lấy URL |
| 9. Click "Lưu" | |
| | 10. Insert vào table `phim` |
| | 11. Hiển thị "Phim được thêm thành công" |

- **Luồng sự kiện thay thế (alternate flow):**
  - 5a: Click "Sửa phim" → tương tự, nhưng UPDATE instead of INSERT
  - 5b: Click "Xóa phim" → Kiểm tra phim có lịch chiếu chưa, nếu có → "Không thể xóa, phim đang có lịch chiếu"

### 35. Quản Lý Rạp
- **Tên use case:** Quản lý rạp
- **Mô tả sơ lược:** Quản lý cụm thêm rạp, sửa thông tin, xóa rạp
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý cụm đã đăng nhập
- **Hậu điều kiện:** 
  - Rạp được thêm/cập nhật/xóa
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Quản lý rạp" | |
| | 2. Hiển thị danh sách rạp trong cụm |
| 3. Click "Thêm rạp mới" | |
| | 4. Mở form (tên rạp, địa chỉ, SĐT, quản lý rạp) |
| 5. Nhập tên rạp, địa chỉ, SĐT | |
| 6. Chọn quản lý rạp từ dropdown (danh sách user vai_tro = 3) | |
| | 7. Validate (tên không rỗng, SĐT format hợp lệ) |
| 8. Click "Lưu" | |
| | 9. Insert vào table `rap_chieu` |
| | 10. Cập nhật table `taikhoan` (set id_rap cho quản lý rạp) |
| | 11. Hiển thị "Thêm rạp thành công" |

### 36. Quản Lý Loại Phim
- **Tên use case:** Quản lý loại phim
- **Mô tả sơ lược:** Thêm/sửa/xóa loại phim (Hành động, Hài, Kinh dị, v.v.)
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý cụm đã đăng nhập
- **Hậu điều kiện:** 
  - Loại phim được quản lý
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Loại phim" | |
| | 2. Hiển thị danh sách loại phim |
| 3. Click "Thêm loại mới" | |
| | 4. Mở form (tên loại) |
| 5. Nhập tên loại (vd: "Hành động", "Hài hước") | |
| | 6. Validate (tên không rỗng, không trùng) |
| 7. Click "Lưu" | |
| | 8. Insert vào table `loaiphim` |
| | 9. Hiển thị "Thêm loại thành công" |

### 37. Phân Phối Phim
- **Tên use case:** Phân phối phim
- **Mô tả sơ lược:** Quản lý cụm quyết định phim nào chiếu ở rạp nào
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Phim tồn tại
  - Có rạp trong cụm
- **Hậu điều kiện:** 
  - Phim được gán cho rạp
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Phân phối phim" | |
| | 2. Hiển thị danh sách phim + rạp |
| 3. Chọn phim | |
| 4. Chọn 1 hoặc nhiều rạp để phân phối | |
| 5. Set ngày bắt đầu chiếu | |
| | 6. Validate (phim + rạp + ngày không trùng) |
| 7. Click "Lưu" | |
| | 8. Tạo bản ghi phân phối (hoặc update tag phim available ở rạp) |
| | 9. Hiển thị "Phân phối phim thành công" |

### 38. Duyệt Kế Hoạch Chiếu
- **Tên use case:** Duyệt kế hoạch chiếu
- **Mô tả sơ lược:** Xem lịch chiếu từ quản lý rạp, duyệt hoặc từ chối
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Quản lý rạp (nhận thông báo)
- **Tiền điều kiện:** 
  - Có lịch chiếu chờ duyệt (trang_thai_duyet = 'Chờ duyệt')
- **Hậu điều kiện:** 
  - Lịch được duyệt hoặc từ chối
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Duyệt lịch chiếu" | |
| | 2. Hiển thị danh sách lịch chờ duyệt (trang_thai_duyet = 'Chờ duyệt') |
| 3. Click 1 lịch để xem chi tiết (phim, rạp, ngày, giờ, phòng) | |
| | 4. Kiểm tra xung đột lịch, tính khả dụng phòng |
| 5. Quản lý cụm click "Duyệt" hoặc "Từ chối" | |
| | 6. Nếu duyệt: update trang_thai_duyet = 'Đã duyệt' |
| | 7. Nếu từ chối: update trang_thai_duyet = 'Từ chối' + nhập lý do |
| | 8. Gửi notification tới quản lý rạp |
| 9. Hiển thị "Cập nhật thành công" | |

### 38.5. Xem Lịch Chiếu Từng Rạp
- **Tên use case:** Xem lịch chiếu từng rạp
- **Mô tả sơ lược:** Quản lý cụm xem lịch chiếu chi tiết của từng rạp
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có rạp trong cụm
  - Có lịch chiếu đã duyệt
- **Hậu điều kiện:** 
  - Lịch chiếu được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Xem lịch rạp" | |
| | 2. Dropdown chọn rạp |
| 3. Chọn rạp | |
| | 4. Chọn khoảng thời gian (ngày, tuần, tháng) |
| 5. Chọn khoảng thời gian | |
| | 6. Load lịch chiếu: SELECT * FROM lichchieu WHERE id_rap = X AND trang_thai_duyet = 'Đã duyệt' AND ngay_chieu BETWEEN start AND end |
| | 7. Hiển thị danh sách (phim, giờ chiếu, phòng, số vé bán/tổng ghế, thu nhập dự kiến) |

### 39. Xem Thống Kê Cả Cụm
- **Tên use case:** Xem thống kê cả cụm
- **Mô tả sơ lược:** Xem báo cáo tổng doanh thu, so sánh giữa các rạp
- **Actor chính:** Quản lý cụm
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có dữ liệu doanh thu từ các rạp
- **Hậu điều kiện:** 
  - Báo cáo được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Thống kê cụm" | |
| | 2. Mở form chọn khoảng thời gian (ngày, tháng, quý, năm) |
| 3. Chọn khoảng thời gian | |
| | 4. Tổng hợp dữ liệu: SELECT SUM(thanh_tien) FROM hoa_don WHERE date BETWEEN X AND Y GROUP BY id_rap |
| | 5. Tính KPI (doanh thu tổng, trung bình/rạp, rạp cao nhất, rạp thấp nhất) |
| | 6. Vẽ biểu đồ cột so sánh doanh thu các rạp |
| 7. Hiển thị báo cáo + biểu đồ | |
| 8. Có thể export PDF hoặc Excel | |

### 40. Quản Lí Tài Khoản (Cả Cụm)
- **Tên use case:** Quản lí tài khoản (cả cụm)
- **Mô tả sơ lược:** Quản lý cụm và Admin xem, sửa, khóa tài khoản
- **Actor chính:** Quản lý cụm / Admin
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý cụm/Admin đã đăng nhập
- **Hậu điều kiện:** 
  - Tài khoản được cập nhật (sửa info, khóa, mở khóa)
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Quản lý tài khoản" | |
| | 2. Hiển thị danh sách tài khoản (tất cả hoặc lọc theo vai_tro) |
| 3. Lọc theo vai_tro (thành viên/nhân viên/quản lý rạp) | |
| | 4. Hiển thị danh sách được lọc |
| 5. Click 1 tài khoản | |
| | 6. Mở form chi tiết (email, tên, SĐT, vai_tro, trang_thai) |
| 7. Sửa thông tin hoặc click "Khóa/Mở khóa" | |
| | 8. Update table `taikhoan` |
| | 9. Hiển thị "Cập nhật thành công" |

### 42. Phân Công Lịch Làm Việc Cho Nhân Viên
- **Tên use case:** Phân công lịch làm việc cho nhân viên
- **Mô tả sơ lược:** Quản lý cụm phân công lịch làm cho nhân viên các rạp
- **Actor chính:** Quản lý cụm (qua quản lý rạp)
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có nhân viên trong cụm
- **Hậu điều kiện:** 
  - Lịch làm được cập nhật
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Phân công ca làm" | |
| | 2. Chọn rạp (dropdown) |
| 3. Chọn rạp | |
| | 4. Load danh sách nhân viên của rạp |
| 5. Chọn nhân viên | |
| | 6. Hiển thị lịch ca (7 ngày hoặc 30 ngày) |
| 7. Chọn ngày + ca (sáng/chiều/tối) → click "Phân công" | |
| | 8. Insert vào table `lichlamviec` |
| | 9. Hiển thị "Phân công thành công" |

### 43. Duyệt Nghỉ Phép
- **Tên use case:** Duyệt nghỉ phép
- **Mô tả sơ lược:** Quản lý cụm hoặc quản lý rạp duyệt yêu cầu nghỉ phép
- **Actor chính:** Quản lý cụm / Quản lý rạp
- **Actor phụ:** Nhân viên (nhận thông báo)
- **Tiền điều kiện:** 
  - Có request nghỉ phép từ nhân viên
- **Hậu điều kiện:** 
  - Request được duyệt hoặc từ chối
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Duyệt nghỉ phép" | |
| | 2. Hiển thị danh sách request chờ duyệt |
| 3. Click request để xem chi tiết (nhân viên, ngày, lý do) | |
| 4. Click "Duyệt" hoặc "Từ chối" | |
| | 5. Nếu duyệt: update nghiphep.trang_thai = 'Đã duyệt' |
| | 6. Nếu từ chối: cập nhật + nhập lý do |
| | 7. Gửi notification tới nhân viên |
| 8. Hiển thị "Cập nhật thành công" | |

---

## ADMIN HỆ THỐNG (ADMIN)

### 41. Cấu Hình Website
- **Tên use case:** Cấu hình website
- **Mô tả sơ lược:** Admin cấu hình thông tin website (tiêu đề, logo, email, liên hệ)
- **Actor chính:** Admin
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Admin đã đăng nhập (vai_tro = 2)
- **Hậu điều kiện:** 
  - Cấu hình website được lưu
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Vào "Cấu hình website" | |
| | 2. Mở form cấu hình (tiêu đề, mô tả, logo, email liên hệ, hotline, địa chỉ, v.v.) |
| 3. Sửa các thông tin | |
| 4. Upload logo mới (nếu có) | |
| 5. Click "Lưu" | |
| | 6. Update table `website_config` |
| | 7. Xóa cache để apply ngay |
| 8. Hiển thị "Cấu hình thành công" | |

---

---

## QUẢN LÝ RẠP - THÊM (CINEMA MANAGER - ADD-ON)

### 44. Quản Lý Tài Khoản Trong Rạp Của Mình (Cinema Manager)
- **Tên use case:** Quản lý tài khoản nhân viên trong rạp của mình
- **Mô tả sơ lược:** Quản lý rạp xem danh sách nhân viên, thêm nhân viên mới, sửa thông tin, khóa/mở khóa tài khoản nhân viên
- **Actor chính:** Quản lý rạp
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Quản lý rạp đã đăng nhập (vai_tro = 3)
  - Có nhân viên trong rạp
- **Hậu điều kiện:** 
  - Tài khoản nhân viên được tạo/cập nhật/khóa
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Quản lý rạp vào "Quản lý nhân viên" | |
| | 2. Hiển thị danh sách nhân viên (tên, email, SĐT, chức vụ, trạng thái) |
| 3. Click "Thêm nhân viên mới" | |
| | 4. Mở form (email, họ tên, SĐT, địa chỉ, chức vụ) |
| 5. Nhập thông tin nhân viên | |
| | 6. Validate (email không tồn tại, SĐT format hợp lệ) |
| 7. Click "Lưu" | |
| | 8. Tạo tài khoản (vai_tro = 1, id_rap = current_manager_rap_id) |
| | 9. Gửi email thông báo tài khoản tới nhân viên |
| 10. Hiển thị "Thêm nhân viên thành công" | |
| 11. Hoặc click 1 nhân viên để sửa/khóa | |
| | 12. Nếu sửa: update thông tin |
| | 13. Nếu khóa: cập nhật trang_thai = 'khóa' |

- **Luồng sự kiện thay thế (alternate flow):**
  - 11a: Xóa nhân viên → Kiểm tra có lịch làm tương lai không, nếu có → "Không thể xóa, nhân viên còn lịch làm"

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Email đã tồn tại → "Email đã được sử dụng"
  - 9a: Gửi email thất bại → "Thêm nhân viên thành công, nhưng không gửi email được"

---

## KHÁCH HÀNG VÃNG LAI - ADD-ON

### 45. Xem Thông Tin Rạp
- **Tên use case:** Xem thông tin rạp chi tiết
- **Mô tả sơ lược:** Khách vãng lai xem thông tin chi tiết từng rạp (địa chỉ, liên hệ, giờ mở, sơ đồ phòng, tiện ích)
- **Actor chính:** Khách vãng lai
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Rạp tồn tại trong hệ thống
  - Rạp có thông tin được cập nhật
- **Hậu điều kiện:** 
  - Thông tin rạp được hiển thị đầy đủ
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách vào trang chủ → Click "Thông tin rạp" hoặc tìm rạp | |
| | 2. Hiển thị danh sách rạp (hình, tên, địa chỉ, sao đánh giá) |
| 3. Khách chọn 1 rạp | |
| | 4. Load thông tin chi tiết rạp từ database |
| | 5. Hiển thị: địa chỉ, SĐT, giờ mở cửa, email liên hệ, mô tả, hình ảnh |
| 6. Khách xem "Phòng chiếu" | |
| | 7. Hiển thị danh sách phòng (tên phòng, số ghế, công nghệ âm thanh/chiếu) |
| 8. Khách xem "Tiện ích" | |
| | 9. Hiển thị: quầy bán combo, wifi, bãi đỗ xe, nhà vệ sinh, etc. |
| 10. Khách xem "Bản đồ" | |
| | 11. Hiển thị bản đồ Google Maps + chỉ đường |

- **Luồng sự kiện thay thế (alternate flow):**
  - 3a: Khách tìm rạp gần nhất → Sử dụng geolocation → Hiển thị rạp có bán kính 5-10km

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Load thông tin thất bại → Hiển thị "Lỗi tải thông tin rạp"
  - 11a: Bản đồ không load → Hiển thị địa chỉ text + link Maps

---

## KHÁCH HÀNG THÀNH VIÊN - ADD-ON

### 46. Hoàn Vé / Đổi Vé (Request)
- **Tên use case:** Hoàn vé / Đổi vé
- **Mô tả sơ lược:** Thành viên gửi yêu cầu hoàn vé (refund) hoặc đổi vé (exchange) với điều kiện hạn chế
- **Actor chính:** Thành viên
- **Actor phụ:** Quản lý rạp (duyệt)
- **Tiền điều kiện:** 
  - Thành viên đã mua vé (trang_thai_ve = 1)
  - Vé chưa sử dụng (trang_thai_ve ≠ 4)
  - Còn hơn 48 giờ trước suất chiếu
- **Hậu điều kiện:** 
  - Request được tạo (chờ duyệt)
  - Nếu duyệt → hoàn tiền hoặc đổi vé
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào "Vé của tôi" | |
| | 2. Hiển thị danh sách vé (phim, rạp, giờ chiếu, trạng thái) |
| 3. Click vé → Click "Hoàn/Đổi vé" | |
| | 4. Kiểm tra điều kiện (>48h, chưa dùng) |
| 5. Nếu hợp lệ: mở form yêu cầu | |
| | 6. Hiển thị: lý do hoàn (bận, bệnh, lý do khác), chọn hoàn hay đổi |
| 7. Chọn "Hoàn vé" → Nhập lý do | |
| | 8. Insert request vào table `doihoan` (trang_thai = 'chờ duyệt') |
| | 9. Gửi notification tới quản lý rạp |
| 10. Hiển thị "Yêu cầu được gửi, chờ duyệt" | |
| 11. Hoặc chọn "Đổi vé" → Chọn phim/ngày/giờ/ghế mới | |
| | 12. Kiểm tra ghế còn trống |
| | 13. Insert request đổi vé |

- **Luồng sự kiện thay thế (alternate flow):**
  - 7a: Chọn "Đổi vé" → Chọn suất chiếu khác (cùng phim hoặc phim khác) → Quản lý rạp duyệt → Cập nhật vé

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 4a: Không đủ 48h → Hiển thị "Không thể hoàn/đổi vé, cần tối thiểu 48h trước suất chiếu"
  - 4b: Vé đã dùng → "Vé đã được sử dụng, không thể hoàn/đổi"
  - 12a: Ghế mới full → "Ghế đã hết, vui lòng chọn suất khác"

---

## FINE-TUNING & GENERAL FEATURES

### 47. Tìm Kiếm Phim (Search Feature)
- **Tên use case:** Tìm kiếm phim
- **Mô tả sơ lược:** Khách tìm kiếm phim theo tên, diễn viên, đạo diễn, thể loại
- **Actor chính:** Khách vãng lai / Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Có phim trong hệ thống
- **Hậu điều kiện:** 
  - Kết quả tìm kiếm được hiển thị
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Khách vào trang chủ / trang phim | |
| | 2. Hiển thị thanh tìm kiếm (search bar) |
| 3. Khách click vào search bar | |
| | 4. Mở dropdown gợi ý phim hot |
| 5. Khách nhập từ khóa (ví dụ: "Avatar", "Đặng Đình") | |
| | 6. Hệ thống search real-time: SELECT * FROM phim WHERE ten_phim LIKE '%keyword%' OR daodien LIKE '%keyword%' OR dien_vien LIKE '%keyword%' |
| | 7. Hiển thị dropdown gợi ý (tối đa 10 kết quả) |
| 8. Khách xem gợi ý, chọn 1 phim hoặc nhấn Enter | |
| | 9. Redirect sang trang kết quả tìm kiếm |
| | 10. Hiển thị danh sách phim phù hợp (sắp xếp: tên giống nhất trước, sau đó diễn viên/đạo diễn) |
| 11. Khách lọc kết quả (ngày chiếu, rạp, thể loại) | |
| | 12. Filter kết quả theo điều kiện |

- **Luồng sự kiện thay thế (alternate flow):**
  - 5a: Khách nhập từ khóa không có phim → Hiển thị "Không tìm thấy phim, hãy thử từ khóa khác"
  - 5b: Khách nhập chữ tiếng Việt không dấu (ví dụ: "Avatar" vs "avat...") → Hệ thống tìm kiếm không dấu

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Search database timeout → Hiển thị "Lỗi tìm kiếm, vui lòng thử lại"
  - 7a: Kết nối chậm → Hiển thị loading spinner

### 48. Các UC Bổ Sung Khác Cho Thành Viên & Nhân Viên

#### 48.1. Sử Dụng Điểm Để Giảm Giá Vé (Thành viên)
- **Tên use case:** Sử dụng điểm để giảm giá vé
- **Mô tả sơ lược:** Thành viên đổi điểm tích lũy thành tiền giảm giá khi đặt vé (ví dụ: 1000 điểm = 10,000 VND)
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
  - Thành viên có điểm tích lũy >= 1000
  - Đang ở bước thanh toán
- **Hậu điều kiện:** 
  - Điểm được trừ đi
  - Tổng tiền thanh toán được giảm
  - Lịch sử điểm được ghi nhận
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên ở bước thanh toán | |
| | 2. Hiển thị tổng điểm member (VD: 5000 điểm) |
| 3. Thành viên click "Dùng điểm để giảm giá" | |
| | 4. Mở dialog nhập số điểm muốn dùng |
| 5. Nhập số điểm (VD: 2000 điểm) | |
| | 6. Validate (điểm có đủ không, > 0) |
| | 7. Tính tiền giảm = diem_dung / 100 (VD: 2000/100 = 20,000 VND) |
| | 8. Kiểm tra: tổng tiền - tiền giảm >= 0 |
| 9. Click "Áp dụng" | |
| | 10. Cập nhật tổng tiền = price - (diem_dung / 100) |
| | 11. Lưu diem_dung vào session |
| 12. Xem giá đã giảm, tiếp tục thanh toán | |
| | 13. Sau thanh toán thành công: UPDATE diem, TRỪ diem_dung |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 6a: Không đủ điểm → "Bạn không đủ điểm để giảm giá"
  - 8a: Giá cuối < 0 → "Điểm dùng quá lớn, vui lòng điều chỉnh"

#### 48.2. Bình Luận/Đánh Giá Phim (Thành viên)
- **Tên use case:** Bình luận/Đánh giá phim
- **Mô tả sơ lược:** Thành viên gửi bình luận, rating phim sau khi xem
- **Actor chính:** Thành viên
- **Actor phụ:** Quản lý rạp (duyệt)
- **Tiền điều kiện:** 
  - Thành viên đã mua vé phim này (trang_thai_ve = 4 - đã dùng)
  - Thành viên đã đăng nhập
- **Hậu điều kiện:** 
  - Bình luận được lưu (chờ duyệt nếu chứa từ nhạy cảm)
  - Hiển thị dưới phim
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào chi tiết phim → Click "Bình luận" | |
| | 2. Mở form (sao 1-5, tiêu đề, nội dung bình luận) |
| 3. Chọn sao (1-5 ⭐) | |
| 4. Nhập tiêu đề (tối đa 100 ký tự) | |
| 5. Nhập nội dung (tối đa 500 ký tự) | |
| | 6. Validate (không rỗng, độ dài hợp lệ) |
| 7. Click "Gửi" | |
| | 8. Filter từ nhạy cảm, nếu có → trang_thai = 'chờ duyệt' |
| | 9. Nếu không → trang_thai = 'đã duyệt' |
| | 10. Insert vào table `binhluan` |
| 11. Hiển thị "Bình luận của bạn đang chờ duyệt" | |

#### 48.3. Xem Thông Tin Thứ Hạng & Lịch Sử Điểm (Thành viên)
- **Tên use case:** Xem thông tin thứ hạng & lịch sử điểm
- **Mô tả sơ lược:** Thành viên xem tổng điểm, thứ hạng (Bạc/Vàng/Kim cương), lợi ích, lịch sử giao dịch điểm
- **Actor chính:** Thành viên
- **Actor phụ:** Không
- **Tiền điều kiện:** 
  - Thành viên đã đăng nhập
  - Thành viên có vé thanh toán thành công
- **Hậu điều kiện:** 
  - Thông tin điểm được hiển thị đầy đủ
- **Luồng sự kiện chính (main flow):**

| Actor | System |
|-------|--------|
| 1. Thành viên vào "Tích điểm" hoặc "Tài khoản" → Tab "Điểm" | |
| | 2. Load tổng điểm từ diem table WHERE id_tk = member_id |
| | 3. Xác định thứ hạng: Bạc (0-5000), Vàng (5001-10000), Kim cương (>10000) |
| | 4. Hiển thị: Tổng điểm, thứ hạng hiện tại, điểm cần để lên hạng |
| 5. Click "Xem lợi ích thành viên" | |
| | 6. Hiển thị bảng lợi ích (Bạc: -5%, Vàng: -10%, Kim cương: -15% giá vé) |
| 7. Click "Lịch sử điểm" | |
| | 8. Load danh sách giao dịch từ diem table (ngày, phim, loại (+/-), điểm, số dư) |
| | 9. Hiển thị danh sách (sắp xếp ngày mới nhất trước) |
| 10. Thành viên xem lịch sử | |

- **Luồng sự kiện ngoại lệ (exception flow):**
  - 8a: Không có lịch sử → "Bạn chưa có giao dịch điểm nào"

---

## TỔNG HỢP CẬP NHẬT

**Tổng cộng: 48 Use Case Specifications**

**Phân bố theo Actor:**
- 👥 **Khách vãng lai:** 7 UCs (+ UC #45, #47)
- 👥 **Khách thành viên:** 14 UCs (+ UC #46, #48.2, #48.3)
- 👥 **Nhân viên rạp:** 7 UCs (+ UC #48.1)
- 👥 **Quản lý rạp:** 11 UCs (+ UC #44)
- 👥 **Quản lý cụm:** 10 UCs
- 👥 **Admin:** 1 UC

**Format:**
- ✅ Tên use case
- ✅ Mô tả sơ lược (chi tiết)
- ✅ Actor chính / phụ
- ✅ Tiền điều kiện (bullet points)
- ✅ Hậu điều kiện (chi tiết)
- ✅ Luồng sự kiện chính (bảng Actor/System)
- ✅ Luồng sự kiện thay thế (alternate flows)
- ✅ Luồng sự kiện ngoại lệ (exception flows)

**🎉 Sẵn sàng copy vào Word/PDF cho đồ án!**

---

## TỔNG HỢP

**Tổng cộng: 48 Use Case Specifications**

**Phân bố theo Actor:**
- 👥 **Khách vãng lai:** 6 UCs
- 👥 **Khách thành viên:** 11 UCs
- 👥 **Nhân viên rạp:** 6 UCs
- 👥 **Quản lý rạp:** 10 UCs
- 👥 **Quản lý cụm:** 10 UCs (bao gồm UC #42, #43)
- 👥 **Admin:** 1 UC
- + **Add-on/Fine-tuning:** 4 UCs

**Format:**
- ✅ Tên use case
- ✅ Mô tả sơ lược
- ✅ Actor chính / phụ
- ✅ Tiền điều kiện
- ✅ Hậu điều kiện
- ✅ Luồng sự kiện chính (main flow) - dạng bảng Actor/System
- ✅ Luồng sự kiện thay thế (alternate flow)
- ✅ Luồng sự kiện ngoại lệ (exception flow)

**Sẵn sàng copy vào Word! 📄**

