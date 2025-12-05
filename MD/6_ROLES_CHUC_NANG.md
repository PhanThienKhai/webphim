# 👥 6 Roles - Chức Năng Nghiệp Vụ CinePass

## 1️⃣ **KHÁCH VÃNG LAI (Guest)** - vai_tro = -1

### Chức Năng:
- Xem danh sách phim đang chiếu
- Xem chi tiết phim (tóm tắt, diễn viên, đạo diễn, giờ chiếu)
- Xem trailer
- Xem thông tin rạp (địa chỉ, điện thoại)
- Xem lịch chiếu phim
<!-- - Xem khuyến mãi hiện tại  -->
<!-- - Xem tin tức điện ảnh -->
- Liên hệ hỗ trợ khách hàng
- Chat với AI Bot tư vấn phim

### Tính Năng Đặc Biệt:
- **Đặt vé nhanh**: Không cần đăng ký, chỉ cần thông tin cơ bản
  - Chọn phim
  - Chọn rạp & suất chiếu
  - Chọn ghế
  - Chọn combo (đồ ăn, nước)
  - Thanh toán
  - Nhận vé qua Email/SMS + Mã QR

### Không được phép:
- Tích điểm
- Quản lý lịch sử
- Đổi/hoàn vé (tự động)
- Bình luận & đánh giá

---

## 2️⃣ **THÀNH VIÊN (Member)** - vai_tro = 0

### Chức Năng:
- **Tất cả chức năng của Khách** +
- Đăng ký tài khoản (email, số điện thoại, mật khẩu)
- Đăng nhập
- Xem hồ sơ cá nhân
- Cập nhật thông tin (tên, địa chỉ, ảnh đại diện)
- Đổi mật khẩu
- Quên mật khẩu (reset qua email)

### Tích Điểm & Voucher:
- Đặt vé → Tích điểm tự động (1 vé = X điểm)
<!-- - Check-in xem phim → +thêm điểm -->
- Xem lịch sử điểm & sử dụng điểm
- Đổi điểm thành Voucher giảm giá
- Áp dụng Voucher khi đặt vé
- Xem hạng thành viên (Silver/Gold/Platinum)

### Lịch Sử & Quản Lý:
- Xem danh sách vé đã đặt (đã xem/chưa xem/hủy)
<!-- - Xem lịch sử giao dịch (đặt vé, thanh toán) -->
<!-- - Xem lịch sử xem phim (phim nào, khi nào, rạp nào) -->
- Lịch sử tích điểm & sử dụng voucher

### Bình Luận & Đánh Giá:
- Bình luận phim (text, rating)
<!-- - Đánh giá phim (1-5 sao) -->
- Xem bình luận của thành viên khác

### Đổi/Hoàn Vé: <!-- Chưa làm -->
<!-- - Đăng ký đổi vé (trước 2 giờ suất chiếu)
- Đăng ký hoàn vé (trước 6 giờ suất chiếu, có thể bị mất phí)
- Xem trạng thái yêu cầu đổi/hoàn -->

### Chức Năng Mở Rộng:
- Tìm kiếm nâng cao (theo thể loại, diễn viên, đạo diễn, năm)
- Yêu thích phim (bookmark)
<!-- - Nhận thông báo khuyến mãi -->
- Check-in tại rạp (scan QR)

---

## 3️⃣ **NHÂN VIÊN (Staff)** - vai_tro = 1

### Chức Năng Bán Vé:
- Đăng nhập vào hệ thống
- Bán vé trực tiếp tại quầy bán vé
  - Chọn phim, rạp, suất chiếu
  - Chọn ghế
  - Chọn combo
  - Chọn khuyến mãi cho khách
  - Tính toán giá vé (sáng/chiều/tối có giá khác)
  - Nhận thanh toán (tiền mặt, thẻ, QR pay)
  - In/gửi vé cho khách

### Check-in & Quản Lý:
- Scan mã QR trên vé khách → Check-in
- Check-in thủ công (nhập mã vé) nếu QR không hoạt động
- Xác nhận khách đã vào xem phim

### Phục Vụ Combo:
<!-- - Chuẩn bị combo (bỏng + nước + bánh)
- Phục vụ combo cho khách
- Quản lý combo tại quầy (tồn kho) -->

### Chấm Công & Lịch:
- Chấm công vào/ra ca làm việc
- Xem lịch làm việc của tháng
- Đăng ký nghỉ phép (gửi đơn cho quản lý)
- Xem đơn nghỉ đã duyệt/chưa duyệt

### Báo Cáo:
<!-- - Báo cáo vấn đề ca làm (thiết bị hỏng, sự cố)
- Báo cáo khách hàng gây rối -->

---

## 4️⃣ **ADMIN (Quản Trị Viên)** - vai_tro = 2

<!-- ### Quản Lý Phim:
- Thêm phim mới
- Sửa thông tin phim (tóm tắt, diễn viên, đạo diễn, thể loại, thời lượng)
- Xóa phim
- Quản lý phim sắp chiếu vs đã hết hạn -->

<!-- ### Quản Lý Rạp:
- Thêm/sửa/xóa rạp chiếu phim
- Quản lý phòng chiếu, số ghế, loại ghế
- Quản lý máy chiếu, âm thanh, điều hòa -->

### Quản Lý Nhân Viên:
- Thêm/sửa/xóa tài khoản nhân viên
- Phân quyền (Admin, Manager, Staff)
- Quản lý thông tin nhân viên (bộ phận, chức vụ)
- Reset mật khẩu nhân viên

<!-- ### Quản Lý Khuyến Mãi & Voucher:
- Tạo chương trình khuyến mãi
- Quản lý mã voucher (tạo, hết hạn, disable)
- Đặt điều kiện voucher (giá tối thiểu, % giảm, ngày áp dụng) -->

### Quản Lý Tài Khoản Thành Viên:
- Xem danh sách thành viên
- Khóa/mở khóa tài khoản (nếu vi phạm)
- Reset mật khẩu thành viên
- Quản lý hạng thành viên (Silver/Gold/Platinum)

### Hệ Thống & Backup:
- **Backup dữ liệu**: Sao lưu toàn bộ database (hàng ngày/tuần)
- **Restore dữ liệu**: Khôi phục từ backup khi cần
- **Cấu hình hệ thống**: 
  - Cổng thanh toán (Momo, ZaloPay, VNPay, ATM)
  - Email SMTP (gửi vé, khôi phục mật khẩu)
  - SMS gateway (gửi OTP, thông báo)
  - Tham số hệ thống (thời gian hủy vé, % phí, v.v)

### Monitoring & Logs:
- Xem nhật ký hoạt động hệ thống (logs)
- Theo dõi lỗi (error logs)
- Giám sát hiệu suất server

---

## 5️⃣ **QUẢN LÝ RẠP (Cinema Manager)** - vai_tro = 3

**Quản lý 1 rạp cụ thể (không toàn hệ thống) - Chỉ có quyền trên rạp được gán**

### Kế Hoạch Chiếu Phim:
- Tạo kế hoạch chiếu (submit lên Quản lý cụm duyệt)
- Chỉnh sửa kế hoạch (trước khi submit)
- Thu hồi/xóa kế hoạch (nếu chưa duyệt)
- Export kế hoạch thành Word
- Xem chi tiết kế hoạch đã submit
- Gửi kế hoạch cho Quản lý cụm duyệt

### Lịch Chiếu & Phòng Chiếu:
- Xem lịch chiếu rạp (do Quản lý cụm phân bổ phim)
- Quản lý phòng chiếu (tên, sức chứa, loại ghế)
- Sửa thông tin phòng chiếu
- Quản lý ghế ngồi (VIP, thường, khuyết tật)
- Quản lý khung giờ chiếu (sáng/chiều/tối)

### Giá Vé & Khuyến Mãi:
- Quản lý giá vé theo suất (sáng/chiều/tối có giá khác)
- Quản lý giá vé theo loại ghế (VIP, thường)
- Tạo khuyến mãi riêng cho rạp (khác với khuyến mãi toàn cụm)
- Quản lý mã khuyến mãi rạp (tạo, disable, xóa)

### Quản Lý Nhân Viên Rạp:
- Thêm/sửa/xóa nhân viên (chỉ cho rạp này)
- Xem danh sách nhân viên rạp
- Phân ca làm việc (ai làm ca sáng, chiều, tối)
- Duyệt yêu cầu nghỉ phép
- Xem chấm công nhân viên (ngày nào vào/ra)
- Quản lý lương/thưởng nhân viên rạp

### Thiết Bị & Bảo Trì:
- Quản lý thiết bị phòng chiếu (máy chiếu, âm thanh, điều hòa)
- Báo cáo hỏng hóc thiết bị
- Xem lịch bảo trì thiết bị

### Combo & Đồ Ăn:
- Thêm/sửa/xóa combo menu (bỏng, nước, bánh)
- Quản lý giá combo riêng rạp
- Quản lý tồn kho combo
- Cập nhật combo có sẵn/hết hàng

### Quản Lý Vé:
- Duyệt yêu cầu đổi vé từ khách
- Duyệt yêu cầu hoàn vé từ khách
- Từ chối yêu cầu đổi/hoàn vé (kèm lý do)
- Xem thống kê vé đổi/hoàn (số lượng, tiền hoàn)

### Báo Cáo Rạp:
- **Báo cáo doanh thu rạp**:
  - Tổng doanh thu rạp (vé + combo)
  - Doanh thu theo ngày/tuần/tháng
  - Chi tiết doanh thu từng suất chiếu
  - Doanh thu vé vs doanh thu combo
- **Báo cáo khác**:
  - Xem feedback/bình luận khách về rạp
  - Thống kê khách hàng (tổng khách, khách repeat)
  - Báo cáo ca làm (đã thực hiện/chưa)

---

## 6️⃣ **QUẢN LÝ CỤM RẠP (Cluster Manager)** - vai_tro = 4

**Chiến lược & quản lý cao cấp - Quản lý danh mục (phim, loại phim, rạp) cho toàn cụm**

### Quản Lý Phim & Loại Phim:
- Thêm/sửa/xóa loại phim
- Thêm/sửa/xóa phim (cấp độ danh mục)
- Quản lý phim sắp chiếu vs đã hết hạn
- Cấu hình phim trước khi phân bổ cho rạp

### Quản Lý Rạp (Danh Mục):
- Thêm rạp mới vào cụm
- Sửa thông tin rạp (địa chỉ, điện thoại, tên quản lý)
- Xóa rạp khỏi cụm (nếu đóng cửa)
- Phân quyền quản lý rạp (gán người quản lý cho rạp nào)

### Kế Hoạch Chiếu & Phân Bổ Phim:
- Xem kế hoạch chiếu từ các Quản lý rạp (submit pending)
- Duyệt kế hoạch chiếu (approve/reject)
- Xem chi tiết kế hoạch chiếu đã duyệt
- Phân bổ phim cho các rạp (phim nào chiếu ở rạp nào)
  - VD: Phim A chiếu 5 suất ở rạp 1, 3 suất ở rạp 2
- Tối ưu lịch chiếu toàn cụm (không cạnh tranh giữa rạp)
- Thu hồi phim từ rạp khi cần

### Combo & Khuyến Mãi Chung:
- Thêm/sửa/xóa combo chung cấp cụm
- Quản lý giá combo
- Tạo khuyến mãi toàn cụm (applies tất cả rạp)
  - VD: "Mua vé Thứ 2-4 giảm 20%"
  - Áp dụng cho tất cả rạp
- Quản lý mã khuyến mãi chung

### Quản Lý Tài Khoản & Nhân Viên:
- Thêm/sửa/xóa tài khoản (Khách hàng, Nhân viên, Quản lý rạp)
- Xem danh sách nhân viên tất cả rạp

### Báo Cáo & Phân Tích Toàn Cụm:
- **Doanh thu toàn cụm**:
  - Tổng doanh thu tất cả rạp
  - Doanh thu theo rạp (rạp nào bán chạy nhất?)
  - Doanh thu theo phim (phim nào hot?)
  - Doanh thu vé vs combo toàn cụm
  
- **So sánh & Đánh giá rạp**:
  - Rạp nào có doanh thu cao nhất/thấp nhất?
  - Rạp nào có khách tối đa/tối thiểu?
  - Rạp nào có tỷ lệ fill rate cao nhất?
  - Hiệu suất từng rạp
  
- **Báo cáo chi tiết**:
  - Báo cáo tổng hợp từ tất cả rạp
  - Báo cáo theo ngày/tuần/tháng
  
- **Xu hướng & Dự báo**:
  - Xu hướng tăng/giảm doanh thu
  - Phim trend nào
  - Suất chiếu nào bán chạy nhất
  - Dự báo doanh thu tháng/quý/năm tới
  - Dự báo demand phim nào

---

## 📊 Bảng Tóm Tắt

| Role | Chức Năng Chính | Scope | Quyền Hạn |
|------|-----------------|-------|---------|
| **Khách** | Xem phim, đặt vé, chat | Công khai | Chỉ xem & đặt |
| **Thành viên** | Khách + tích điểm, voucher, quản lý hồ sơ | Cá nhân | Quản lý tài khoản riêng |
| **Nhân viên** | Bán vé, check-in, phục vụ combo | Rạp (quầy) | Bán hàng, check-in |
| **Admin** | Quản lý phim, rạp, nhân viên, hệ thống, backup | Toàn hệ thống | Quản trị toàn bộ |
| **Quản lý rạp** | Lịch chiếu, nhân viên, giá vé, báo cáo | 1 Rạp | Quản lý 1 rạp chi tiết |
| **Quản lý cụm** | Phân bổ phim, marketing, phân tích, dự báo | Nhiều rạp | Chiến lược toàn cụm |

---

## 🎯 Mối Quan Hệ

```
Admin (Toàn hệ thống)
    ↓
Quản lý cụm (Chiến lược 5-10 rạp)
    ↓
Quản lý rạp (Chi tiết 1 rạp)
    ↓
Nhân viên (Thực thi tại quầy)
    ↓
Khách / Thành viên (Sử dụng dịch vụ)
```

---

**Vậy là bạn đã có danh sách đầy đủ! 📋✨**

