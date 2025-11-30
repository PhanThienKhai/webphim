# CinePass - Hệ Thống Quản Lý Rạp Chiếu Phim
## Tài Liệu Training cho AI

---

## 1. TỔNG QUAN HỆ THỐNG

### 1.1 Mô Tả Chung
**Tên hệ thống**: CinePass  
**Loại**: Hệ thống quản lý rạp chiếu phim  
**Công nghệ**: PHP, MySQL, JavaScript  
**Phiên bản Database**: MariaDB 10.4.32  
**Phiên bản PHP**: 8.2.12  

### 1.2 Chức Năng Chính
- Quản lý phim, lịch chiếu, phòng chiếu
- Đặt vé và thanh toán online
- Quản lý nhân viên, chấm công, bảng lương
- Quản lý khuyến mãi, voucher, combo
- Hệ thống đánh giá bình luận phim
- Quản lý thành viên, hạng thành viên
- Tính năng QR code scanning vé
- Thanh toán VietQR
- Chat widget AI
- Export báo cáo (Word)

---

## 2. CẤU TRÚC DATABASE

### 2.1 Danh Sách Các Bảng (Tables)

#### **2.1.1 Bảng Quản Lý Phim**

**bang_luong**
- `id` (INT, Primary Key)
- `id_nv` (INT, Foreign Key) - ID nhân viên
- `id_rap` (INT, Foreign Key) - ID rạp
- `thang` (VARCHAR 7) - Tháng (YYYY-MM)
- `so_gio` (DECIMAL 10,2) - Tổng số giờ làm việc
- `luong_theo_gio` (DECIMAL 12,2) - Lương = số giờ × đơn giá
- `phu_cap` (DECIMAL 12,2) - Tổng phụ cấp
- `khau_tru` (DECIMAL 12,2) - Tổng khấu trừ
- `thuong` (DECIMAL 12,2) - Thưởng
- `tong_luong` (DECIMAL 12,2) - Tổng thực lãnh
- `trang_thai` (ENUM) - 'nhap', 'cho_duyet', 'da_duyet', 'da_thanh_toan'
- `ghi_chu` (TEXT)
- `nguoi_duyet` (INT, Foreign Key)
- `ngay_duyet` (DATETIME)
- `ngay_tao` (DATETIME)
- `ngay_cap_nhat` (DATETIME)

**bang_luong_chi_tiet**
- `id` (INT, Primary Key)
- `id_bang_luong` (INT, Foreign Key)
- `loai` (ENUM) - 'phu_cap' hoặc 'khau_tru'
- `ten_khoan` (VARCHAR 255) - Tên khoản (Ăn trưa, Đi muộn...)
- `so_tien` (DECIMAL 12,2)
- `ghi_chu` (VARCHAR 500)
- `ngay_tao` (DATETIME)

**binhluan**
- `id` (INT, Primary Key)
- `id_phim` (INT, Foreign Key)
- `id_user` (INT, Foreign Key)
- `noidung` (TEXT) - Nội dung bình luận
- `ngaybinhluan` (VARCHAR 255) - Ngày bình luận

**cham_cong**
- `id` (INT, Primary Key)
- `id_nv` (INT, Foreign Key)
- `id_rap` (INT, Foreign Key)
- `ngay` (DATE)
- `gio_vao` (TIME) - Giờ vào
- `gio_ra` (TIME) - Giờ ra
- `ghi_chu` (VARCHAR 255) - Ghi chú (Self check-in, Check-in nhanh...)
- `ngay_tao` (DATETIME)

**chi_tiet_combo**
- `id` (INT, Primary Key)
- `id_combo` (INT, Foreign Key)
- `ten_mon` (VARCHAR 255) - Tên món (Bắp rang bơ, Nước ngọt...)
- `so_luong` (INT) - Số lượng
- `don_gia` (DECIMAL 10,2) - Đơn giá

**combo_do_an**
- `id` (INT, Primary Key)
- `id_rap` (INT, Foreign Key) - NULL = áp dụng tất cả rạp
- `ten_combo` (VARCHAR 255)
- `mo_ta` (TEXT)
- `gia` (DECIMAL 10,2)
- `hinh_anh` (VARCHAR 255)
- `trang_thai` (TINYINT 1) - 1 = active, 0 = inactive
- `ngay_tao` (TIMESTAMP)

**don_nghi_phep**
- `id` (INT, Primary Key)
- `id_nhan_vien` (INT, Foreign Key)
- `id_rap` (INT, Foreign Key)
- `tu_ngay` (DATE)
- `den_ngay` (DATE)
- `ly_do` (VARCHAR 255) - Lý do (Về quê cưới chồng, Bệnh...)
- `trang_thai` (ENUM) - 'Chờ duyệt', 'Đã duyệt', 'Từ chối'
- `ngay_tao` (TIMESTAMP)

**ghe_ngoi**
- `id` (INT, Primary Key)
- `id_phong` (INT, Foreign Key)
- `ten_ghe` (VARCHAR 10) - VD: A1, B2, C1...
- `loai_ghe` (ENUM) - 'standard', 'vip', 'couple', 'disabled'
- `gia_ghe` (DECIMAL 10,2)
- `trang_thai` (TINYINT 1) - 1 = available

**hang_thanh_vien**
- `id` (INT, Primary Key)
- `ma_hang` (VARCHAR 50) - 'dong', 'bac', 'vang', 'kim_cuong'
- `ten_hang` (VARCHAR 100) - 'Đồng', 'Bạc', 'Vàng', 'Kim Cương'
- `diem_toi_thieu` (INT)
- `ti_le_tich_diem` (DECIMAL 5,2) - Nhân hệ số tích điểm
- `ti_le_giam_gia` (DECIMAL 5,2) - % giảm giá
- `uu_dai_khac` (TEXT) - JSON ưu đãi khác
- `mau_sac` (VARCHAR 7) - Hex color
- `thu_tu` (INT)

**hoa_don**
- `id` (INT, Primary Key)
- `ngay_tt` (DATETIME) - Ngày thanh toán
- `trang_thai` (INT 1) - 0 = chưa thanh toán, 1 = đã thanh toán
- `thanh_tien` (INT)
- `id_khuyen_mai` (INT, Foreign Key)
- `tien_giam` (DECIMAL 10,2)
- `thanh_toan_cuoi` (DECIMAL 10,2)

**khung_gio_chieu**
- `id` (INT, Primary Key)
- `id_lich_chieu` (INT, Foreign Key)
- `id_phong` (INT, Foreign Key)
- `thoi_gian_chieu` (TIME) - Giờ chiếu (12:00:00, 15:00:00...)

---

#### **2.1.2 Bảng Chat & Analytics**

**chat_history**
- `id` (INT, Primary Key)
- `id_user` (INT, Foreign Key)
- `session_id` (VARCHAR 255)
- `message` (TEXT)
- `is_user_message` (TINYINT 1)
- `timestamp` (DATETIME)
- `intent` (VARCHAR 100) - Ý định câu hỏi
- `confidence` (DECIMAL 5,4) - Độ tin cậy phản hồi (0-1)
- `response_time` (INT) - Thời gian phản hồi (ms)

**chat_analytics**
- `id` (INT, Primary Key)
- `session_id` (VARCHAR 255)
- `id_user` (INT, Foreign Key)
- `total_messages` (INT)
- `user_satisfaction` (TINYINT 1) - Mức độ hài lòng
- `resolved_issues` (TEXT) - Các vấn đề được giải quyết
- `chat_duration` (INT) - Thời lượng chat (giây)
- `bot_accuracy` (DECIMAL 5,4) - Độ chính xác bot
- `created_at` (DATETIME)

---

### 2.2 Mối Quan Hệ Giữa Các Bảng (ERD)

```
bang_luong ──┬──→ nhan_vien (id_nv)
             ├──→ rap (id_rap)
             ├──→ nhan_vien (nguoi_duyet)
             └──→ bang_luong_chi_tiet (1:n)

cham_cong ───┬──→ nhan_vien (id_nv)
             └──→ rap (id_rap)

don_nghi_phep ┬──→ nhan_vien (id_nhan_vien)
              └──→ rap (id_rap)

ghe_ngoi ────→ phong (id_phong)

khung_gio_chieu ┬──→ lich_chieu (id_lich_chieu)
                └──→ phong (id_phong)

combo_do_an ──→ rap (id_rap) [NULL = áp dụng tất cả]
                └──→ chi_tiet_combo (1:n)

hoa_don ──────→ khuyen_mai (id_khuyen_mai)

binhluan ─────┬──→ phim (id_phim)
              └──→ tai_khoan_user (id_user)

chat_history  ┬──→ tai_khoan_user (id_user)
              └──→ chat_analytics (session_id)

chat_analytics ──→ tai_khoan_user (id_user)
```

---

## 3. QUẢN LÝ BẢNG LƯƠNG

### 3.1 Luồng Quy Trình Tính Lương

1. **Chấm công** → Ghi nhận `gio_vao` và `gio_ra` của nhân viên
2. **Tính số giờ** → `so_gio` = Tổng số giờ làm việc trong tháng
3. **Tính lương cơ bản** → `luong_theo_gio` = `so_gio` × `don_gia_gio`
4. **Cộng phụ cấp** → Từ bảng `bang_luong_chi_tiet` với `loai` = 'phu_cap'
5. **Trừ khấu trừ** → Từ bảng `bang_luong_chi_tiet` với `loai` = 'khau_tru'
6. **Cộng thưởng** → Nếu có `thuong`
7. **Tổng lương** → `tong_luong` = `luong_theo_gio` + `phu_cap` + `thuong` - `khau_tru`

### 3.2 Trạng Thái Bảng Lương

| Trạng Thái | Mô Tả |
|-----------|-------|
| `nhap` | Mới nhập liệu |
| `cho_duyet` | Chờ người quản lý duyệt |
| `da_duyet` | Đã được duyệt |
| `da_thanh_toan` | Đã thanh toán cho nhân viên |

### 3.3 Các Khoản Phụ Cấp/Khấu Trừ Điển Hình

**Phụ Cấp:**
- Ăn trưa
- Thưởng hiệu suất
- Thưởng bán hàng
- Cấp lương thêm

**Khấu Trừ:**
- Đi muộn
- Về sớm
- Vắng mặt không phép
- Phạt vi phạm quy định

---

## 4. QUẢN LÝ CHẤM CÔNG

### 4.1 Quy Trình Chấm Công

1. Nhân viên check-in lúc `gio_vao` (Tự check-in, Check-in nhanh, QR code scanning)
2. Ghi nhận `id_nv`, `id_rap`, `ngay`, `gio_vao`
3. Nhân viên check-out lúc `gio_ra`
4. Ghi nhận `gio_ra`, `ghi_chu` (lý do nếu có)

### 4.2 Các Loại Ghi Chú Chấm Công

- "Self check-in" - Tự check-in
- "Check-in nhanh" - Check-in nhanh qua QR
- "Self check-out" - Tự check-out
- "Cảm ơn sếp" - Ghi chú tùy ý

### 4.3 Tính Toán Thời Gian Làm Việc

```
Số giờ = (gio_ra - gio_vao) / 3600 (chuyển giây sang giờ)
Nếu gio_ra < gio_vao (qua ngày) thì:
  Số giờ = ((24*3600 - gio_vao) + gio_ra) / 3600
```

---

## 5. QUẢN LÝ PHÒNG & GHẾ NGỒI

### 5.1 Phân Loại Ghế

| Loại | Mô Tả | Gia | Đặc Điểm |
|------|-------|-----|---------|
| standard | Ghế thường | 100,000đ | Loại cơ bản |
| vip | Ghế VIP | 150,000đ | Ghế thoải mái hơn |
| couple | Ghế đôi | 200,000đ | Ghế đôi dành cho couple |
| disabled | Ghế khuyết tật | TBD | Ghế dành cho người khuyết tật |

### 5.2 Đặt Tên Ghế

- Format: `[Hàng][STT]` (VD: A1, B2, C1...)
- Hàng A-E: 5 hàng × 5 ghế = 25 ghế/phòng (tùy từng phòng)

---

## 6. LỊCH CHIẾU & KHUNG GIỜ

### 6.1 Cấu Trúc

- **lich_chieu**: Chứa thông tin phim chiếu theo ngày
- **khung_gio_chieu**: Chứa các giờ chiếu cụ thể cho mỗi phòng
  - VD: Phim A có thể chiếu ở phòng 1 lúc 12:00, phòng 2 lúc 15:00, phòng 1 lúc 20:00

### 6.2 Giờ Chiếu Điển Hình

- **Buổi sáng**: 10:00 - 12:00
- **Buổi chiều**: 12:00 - 15:00 - 17:00
- **Buổi tối**: 18:00 - 20:00 - 22:00
- **Buổi đêm khuya**: 23:00 - 00:00

---

## 7. QUẢN LÝ COMBO & ĐỒ ĂN

### 7.1 Cấu Trúc Combo

**combo_do_an**
- Mỗi combo có tên, mô tả, giá
- Có thể áp dụng cho 1 rạp hoặc tất cả rạp (id_rap = NULL)

**chi_tiet_combo**
- Liệt kê các mặt hàng trong combo
- Mỗi mặt hàng có: tên, số lượng, đơn giá

### 7.2 Danh Sách Mặt Hàng Phổ Biến

| Mặt Hàng | Ví Dụ Giá |
|----------|----------|
| Bắp rang bơ | 30,000đ |
| Nước ngọt | 15,000đ |
| Hotdog | 40,000đ |
| Bánh ngọt | 30,000đ |
| Kẹo | TBD |
| Cocacola | 20,000đ |

### 7.3 Ví Dụ Combo

- **Combo Standard**: Bắp + Nước ngọt = 45,000đ
- **Combo Premium**: Bắp + Nước ngọt + Hotdog = 85,000đ
- **Combo Family**: 2 Bắp + 2 Nước + 1 Bánh ngọt = 120,000đ
- **Combo VIP**: Bắp + Nước + Hotdog + Bánh + Kẹo = 150,000đ

---

## 8. HỆ THỐNG THÀNH VIÊN & HẠNG THÀNH VIÊN

### 8.1 Cấp Độ Thành Viên

| Hạng | Điểm Tối Thiểu | Hệ Số Tích Điểm | Giảm Giá | Màu Sắc | Thứ Tự |
|------|----------------|-----------------|---------|---------|--------|
| Đồng | 0 | 1.0x | 0% | #CD7F32 (Đồng) | 1 |
| Bạc | 1,000 | 1.2x | 5% | #C0C0C0 (Bạc) | 2 |
| Vàng | 5,000 | 1.5x | 10% | #FFD700 (Vàng) | 3 |
| Kim Cương | 15,000 | 2.0x | 15% | #B9F2FF (Xanh) | 4 |

### 8.2 Cơ Chế Tích Điểm - CHI TIẾT

#### 8.2.1 Công Thức Tính Điểm

```
Điểm nhận được = Số tiền thanh toán × Hệ số tích điểm của hạng thành viên

Ví dụ:
- Thành viên Đồng (hệ số 1.0x) đặt vé 200,000đ
  → Điểm = 200,000 × 1.0 = 200 điểm
  
- Thành viên Bạc (hệ số 1.2x) đặt vé 200,000đ
  → Điểm = 200,000 × 1.2 = 240 điểm
  
- Thành viên Vàng (hệ số 1.5x) đặt vé 200,000đ
  → Điểm = 200,000 × 1.5 = 300 điểm
  
- Thành viên Kim Cương (hệ số 2.0x) đặt vé 200,000đ
  → Điểm = 200,000 × 2.0 = 400 điểm
```

#### 8.2.2 Mục Đích Tích Điểm

1. **Nâng Hạng Thành Viên**: Khi điểm ≥ ngưỡng, thành viên tự động nâng hạng
2. **Giảm Giá Lần Tới**: Mỗi lần thanh toán, hạng cao hơn = tích điểm nhiều hơn = giảm giá lần sau
3. **Khuyến Khích Quay Lại**: Càng mua nhiều → Nâng hạng → Được lợi nhiều hơn
4. **Loyalty Program**: Giữ khách hàng lâu dài

#### 8.2.3 Quy Trình Nâng Hạng

```
Thành viên mới (Hạng Đồng, 0 điểm)
    ↓ (Mua vé/combo, tích điểm)
Điểm ≥ 1,000 ?
    ↓ Có
Nâng lên Hạng Bạc (tự động)
    ↓ (Mua thêm)
Điểm ≥ 5,000 ?
    ↓ Có
Nâng lên Hạng Vàng (tự động)
    ↓ (Mua thêm)
Điểm ≥ 15,000 ?
    ↓ Có
Nâng lên Hạng Kim Cương (tối cao)
```

#### 8.2.4 Ví Dụ Toàn Bộ Luồng Tích Điểm

**Tháng 1**: 
- Khách hàng mới (Hạng Đồng, 0 điểm)
- Đặt vé: 200,000đ → Tích 200 điểm (200,000 × 1.0)
- Đặt combo: 50,000đ → Tích 50 điểm
- **Tổng điểm tháng 1**: 250 điểm

**Tháng 2**:
- Vẫn Hạng Đồng (< 1,000 điểm)
- Đặt vé: 300,000đ → Tích 300 điểm
- Đặt combo: 100,000đ → Tích 100 điểm
- **Tổng điểm tháng 2**: 250 + 300 + 100 = 650 điểm

**Tháng 3**:
- Vẫn Hạng Đồng (< 1,000 điểm)
- Đặt vé: 200,000đ → Tích 200 điểm
- Đặt combo: 80,000đ → Tích 80 điểm
- **Tổng điểm tháng 3**: 650 + 200 + 80 = **930 điểm** (sắp nâng)

**Tháng 4**:
- Đặt vé: 100,000đ → Tích 100 điểm
- **Tổng điểm**: 930 + 100 = **1,030 điểm** ✅ **Nâng lên Hạng Bạc!**

**Tháng 5** (Hạng Bạc):
- Từ đây, hệ số tích điểm là 1.2x
- Đặt vé: 200,000đ → Tích 240 điểm (200,000 × 1.2) [trước là 200]
- Đặt combo: 100,000đ → Tích 120 điểm (100,000 × 1.2) [trước là 100]
- **Lợi ích**: Mỗi lần mua, tích được nhiều điểm hơn → Dễ nâng hạng hơn

### 8.3 Ưu Đãi Thành Viên

**Hạng Đồng (0-999 điểm)**:
- Tích điểm 1.0x
- Không giảm giá vé
- Giảm combo: 0%

**Hạng Bạc (1,000-4,999 điểm)**:
- Tích điểm 1.2x (cộng 20%)
- Giảm giá vé: 5%
- Giảm combo: 3%
- Ưu tiên đặt combo

**Hạng Vàng (5,000-14,999 điểm)**:
- Tích điểm 1.5x (cộng 50%)
- Giảm giá vé: 10%
- Giảm combo: 5%
- Ưu tiên đặt vé ghế VIP
- Được tặng voucher 50,000đ/tháng

**Hạng Kim Cương (≥ 15,000 điểm)**:
- Tích điểm 2.0x (gấp đôi)
- Giảm giá vé: 15%
- Giảm combo: 10%
- Ghế VIP miễn phí upgrade 1 lần/tháng
- Được tặng voucher 100,000đ/tháng
- Hotline VIP 24/7
- Mời xem buổi chiếu VIP

### 8.4 Bảng So Sánh Chi Phí

**Khách A mua 4 vé × 200,000đ = 800,000đ**

| Hạng | Hệ Số | Điểm Nhận | Giảm Giá | Thanh Toán | Điểm Tích Lũy |
|------|-------|-----------|----------|-----------|---------------|
| Đồng | 1.0x | 800 điểm | 0% | 800,000đ | 800 |
| Bạc | 1.2x | 960 điểm | 5% (40k) | **760,000đ** | 960 |
| Vàng | 1.5x | 1,200 điểm | 10% (80k) | **720,000đ** | 1,200 |
| Kim Cương | 2.0x | 1,600 điểm | 15% (120k) | **680,000đ** | 1,600 |

**→ Khách Hạng Kim Cương tiết kiệm 120,000đ so với Hạng Đồng!**

---

## 9. HÓA ĐƠN & THANH TOÁN

### 9.1 Cấu Trúc Hóa Đơn

```
Hóa Đơn:
├─ Ngày thanh toán: ngay_tt
├─ Trạng thái: trang_thai (0=chưa TT, 1=đã TT)
├─ Thành tiền gốc: thanh_tien
├─ Khuyến mãi áp dụng: id_khuyen_mai
├─ Tiền giảm: tien_giam
└─ Thành tiền cuối cùng: thanh_toan_cuoi
```

### 9.2 Quy Trình Thanh Toán

1. Khách hàng chọn vé, combo
2. Hệ thống tính `thanh_tien` (tổng)
3. Kiểm tra khuyến mãi/voucher, tính `tien_giam`
4. Tính `thanh_toan_cuoi` = `thanh_tien` - `tien_giam`
5. Khách hàng thanh toán (VietQR hoặc hình thức khác)
6. Cập nhật `trang_thai` = 1 khi thanh toán thành công

---

## 10. BÌNH LUẬN & ĐÁNH GIÁ PHIM

### 10.1 Cấu Trúc Bình Luận

```
binhluan:
├─ id_phim: Phim được bình luận
├─ id_user: Người bình luận
├─ noidung: Nội dung bình luận
└─ ngaybinhluan: Ngày giờ bình luận (Format: "10:06:am 20-04-2025")
```

### 10.2 Ví Dụ Bình Luận

- "Hay quá bạn"
- "Đỉnh vãi"
- "Hay lắm"

---

## 11. HỆ THỐNG CHAT AI

### 11.1 Cấu Trúc Chat

**chat_history**: Lưu lịch sử từng tin nhắn

```
├─ session_id: Mã phiên chat
├─ id_user: Người dùng
├─ message: Nội dung tin nhắn
├─ is_user_message: 1=tin nhắn người dùng, 0=tin nhắn bot
├─ intent: Ý định câu hỏi (VD: "dat_ve", "ho_tro", "khuyen_mai")
├─ confidence: Độ tin cậy (0-1)
└─ response_time: Thời gian phản hồi (ms)
```

**chat_analytics**: Thống kê per session

```
├─ session_id: Mã phiên chat
├─ id_user: Người dùng
├─ total_messages: Tổng tin nhắn trong phiên
├─ user_satisfaction: Mức độ hài lòng (1-5)
├─ resolved_issues: Các vấn đề được giải quyết
├─ chat_duration: Thời lượng chat (giây)
└─ bot_accuracy: Độ chính xác bot (0-1)
```

### 11.2 Các Intent Phổ Biến

| Intent | Mô Tả |
|--------|-------|
| `dat_ve` | Đặt vé xem phim |
| `tra_cuu_lich` | Tra cứu lịch chiếu |
| `co_hoi_giam_gia` | Hỏi về khuyến mãi |
| `hang_thanh_vien` | Hỏi về hạng thành viên |
| `dat_combo` | Đặt combo đồ ăn |
| `ho_tro_khach_hang` | Hỗ trợ khách hàng |
| `lien_he` | Liên hệ rạp |

---

## 12. NHÂN VIÊN & QUẢN LÝ

### 12.1 Quản Lý Nhân Viên

**Các thông tin cần quản lý:**
- Họ tên, email, số điện thoại
- Vị trí công việc
- Lương cơ bản theo giờ
- Rạp làm việc

### 12.2 Quy Trình Nghỉ Phép

1. Nhân viên nộp `don_nghi_phep`
2. Chỉ định lý do (`ly_do`: Bệnh, Về quê, Cưới...)
3. Chọn khoảng thời gian (`tu_ngay` - `den_ngay`)
4. Quản lý duyệt: trạng thái → "Đã duyệt" hoặc "Từ chối"

---

## 13. KHUYẾN MÃI & VOUCHER

### 13.1 Cấu Trúc Khuyến Mãi - CHI TIẾT

**Bảng khuyen_mai**:
```
├─ id (INT, Primary Key)
├─ ma_khuyen_mai (VARCHAR 50) - Mã duy nhất (VD: WELCOME50, VIP2025)
├─ ten_khuyen_mai (VARCHAR 255) - Tên khuyến mãi
├─ mo_ta (TEXT) - Mô tả chi tiết
├─ loai_khuyen_mai (ENUM) - 'giam_tien', 'giam_phan_tram', 'hoa_don_mien_phi', 'tang_voucher'
├─ gia_tri_giam (DECIMAL 10,2) - Giá trị giảm (nếu giam_tien)
├─ phan_tram_giam (DECIMAL 5,2) - % giảm (nếu giam_phan_tram)
├─ so_luong_ton (INT) - Số lượng voucher còn
├─ so_luong_da_dung (INT) - Số lượng đã sử dụng
├─ dieu_kien_ap_dung (TEXT) - JSON điều kiện (min tiền, loại ghế...)
├─ trang_thai (ENUM) - 'active', 'het_han', 'tat'
├─ ngay_bat_dau (DATE) - Ngày bắt đầu
├─ ngay_ket_thuc (DATE) - Ngày kết thúc (NULL = không kỳ hạn)
├─ ap_dung_cho (ENUM) - 'toan_bo', 'hang_bac', 'hang_vang', 'hang_kim_cuong'
└─ ngay_tao (TIMESTAMP)
```

### 13.2 Loại Khuyến Mãi

#### **13.2.1 Giảm Tiền Cố Định (giam_tien)**

```
Mã: WELCOME50
Tên: Chào mừng thành viên mới
Loại: Giảm tiền cố định
Giá trị: 50,000đ
Điều kiện: 
  - Đơn tối thiểu 200,000đ
  - Chỉ áp dụng lần đầu
  - Hạng: Tất cả

Ví dụ:
  Giỏ hàng: 300,000đ (2 vé)
  Giảm: -50,000đ
  Thanh toán: 250,000đ
```

#### **13.2.2 Giảm Phần Trăm (giam_phan_tram)**

```
Mã: VIP2025
Tên: Giảm 20% cho thành viên VIP
Loại: Giảm % 
Giá trị: 20%
Điều kiện:
  - Áp dụng cho: Hạng Kim Cương
  - Không có giới hạn đơn
  - Hết hạn: 31-12-2025

Ví dụ:
  Giỏ hàng: 500,000đ (4 vé + 1 combo)
  Giảm: 500,000 × 20% = -100,000đ
  Thanh toán: 400,000đ
```

#### **13.2.3 Mua 2 Tặng 1 (tang_voucher)**

```
Mã: BUY2GIFT1
Tên: Mua 2 vé tặng 1 voucher combo
Loại: Tặng voucher
Giá trị: Voucher 50,000đ
Điều kiện:
  - Tối thiểu 2 vé
  - Ap dụng tất cả hạng
  - Số lượng: 1000 voucher

Ví dụ:
  Giỏ: 2 vé (200,000đ × 2)
  Thanh toán: 400,000đ
  Quà tặng: Voucher 50,000đ (sử dụng lần sau)
```

#### **13.2.4 Hoá Đơn Miễn Phí (hoa_don_mien_phi)**

```
Mã: FREEMOVIE
Tên: 1 vé miễn phí vào cuối tuần
Loại: Hoá đơn miễn phí
Giá trị: 150,000đ (giá vé trung bình)
Điều kiện:
  - Chỉ T7, CN
  - Min 2 vé
  - Chỉ áp dụng hạng Vàng+
  - Số lượng: 500

Ví dụ:
  Giỏ T7: 3 vé (150k + 150k + 150k)
  Miễn: -150,000đ (1 vé)
  Thanh toán: 300,000đ
```

### 13.3 Điều Kiện Áp Dụng (JSON)

```json
{
  "min_tien": 200000,
  "max_tien": null,
  "min_ve": 2,
  "loai_ghe": ["standard", "vip"],
  "loai_phim": ["hanh_dong", "hoat_hinh"],
  "ngay_ap_dung": ["0", "1", "2", "3", "4", "5", "6"],
  "gio_ap_dung": ["12:00-18:00", "19:00-23:00"],
  "so_lan_ap_dung_toi_da": 1,
  "ap_dung_kem_khuyen_mai_khac": false,
  "ap_dung_kem_hang_thanh_vien": true
}
```

**Giải thích:**
- `min_tien`: Giá tiền tối thiểu
- `max_tien`: Giá tiền tối đa (null = không giới hạn)
- `min_ve`: Số vé tối thiểu
- `loai_ghe`: Chỉ áp dụng loại ghế nào
- `loai_phim`: Chỉ áp dụng phim thể loại nào
- `ngay_ap_dung`: Ngày trong tuần (0=CN, 1=T2...)
- `gio_ap_dung`: Khung giờ áp dụng
- `so_lan_ap_dung_toi_da`: Tối đa bao nhiêu lần/khách
- `ap_dung_kem_khuyen_mai_khac`: Có kết hợp với khuyến mãi khác không
- `ap_dung_kem_hang_thanh_vien`: Có kết hợp với ưu đãi hạng không

### 13.4 Ví Dụ Khuyến Mãi Thực Tế

**KM 1: Chào Mừng Thành Viên Mới**
```
Mã: WELCOME50
Tên: Giảm 50K cho hóa đơn đầu tiên
Giá trị: -50,000đ
Điều kiện: Min 200k, lần đầu, tất cả hạng
Hạn: 31-03-2026
Còn: 500/500 voucher
```

**KM 2: Học Sinh Giảm 30%**
```
Mã: STUDENT30
Tên: Giảm 30% cho học sinh
Giá trị: 30%
Điều kiện: Min 100k, có CCCD học sinh
Hạn: 31-12-2025
Còn: 1000/2000 voucher
```

**KM 3: Tiền Sử Dụng Lại**
```
Mã: CASHBACK20
Tên: Hoàn 20% vào ví digital
Giá trị: 20% (tối đa 200k)
Điều kiện: Min 500k, thanh toán VietQR, hạng Vàng+
Hạn: Không kỳ hạn
Còn: Unlimited
```

**KM 4: Buổi Chiếu Sáng**
```
Mã: MORNING20
Tên: Giảm 20% buổi sáng (10-14h)
Giá trị: 20%
Điều kiện: 
  - Chỉ buổi 10h, 12h, 14h
  - Ghế loại standard
  - T2-T6 (không T7, CN)
Hạn: 31-01-2026
Còn: Unlimited
```

### 13.5 Ưu Tiên Khuyến Mãi Khi Áp Dụng

```
Nếu khách hàng có 3 mã khuyến mãi hợp lệ:
1. Hệ thống chọn mã có lợi nhất cho khách
2. Hoặc cho khách chọn mã nào áp dụng

Ví dụ:
- KM 1: Giảm 50k (cố định)
- KM 2: Giảm 30% = 150k (nếu hóa đơn 500k)
- KM 3: Giảm 20% = 100k

→ Chọn KM 2 (tiết kiệm nhiều nhất: 150k)
```

### 13.6 Trạng Thái Khuyến Mãi

| Trạng Thái | Mô Tả |
|-----------|-------|
| `active` | Đang hoạt động, có thể sử dụng |
| `het_han` | Hết kỳ hạn, không thể sử dụng |
| `tat` | Admin tắt, không thể sử dụng |
| `het_so_luong` | Hết số lượng voucher, không thể sử dụng |

### 13.7 API Khuyến Mãi

**Kiểm tra mã khuyến mãi**:
```
POST /api/khuyen-mai/check
Request:
{
  "ma_khuyen_mai": "WELCOME50",
  "tong_gia": 300000,
  "so_ve": 2,
  "hang_thanh_vien": "bac",
  "loai_ghe": "standard",
  "ngay": "2025-11-29",
  "gio": "19:00"
}

Response:
{
  "valid": true,
  "ten_khuyen_mai": "Giảm 50K cho hóa đơn đầu tiên",
  "loai_giam": "giam_tien",
  "gia_tri_giam": 50000,
  "thanh_toan_cuoi": 250000,
  "ghi_chu": "Áp dụng thành công"
}
```

**Lấy danh sách khuyến mãi hiện hành**:
```
GET /api/khuyen-mai/active?hang=bac&ngay=2025-11-29
Response:
[
  {
    "ma": "WELCOME50",
    "ten": "Giảm 50K...",
    "gia_tri": 50000,
    "dieu_kien": "Min 200k"
  },
  {
    "ma": "STUDENT30",
    "ten": "Giảm 30% cho học sinh",
    "gia_tri": "30%",
    "dieu_kien": "Có CCCD học sinh"
  }
]
```

---

## 14. SCANNED TICKET (QR CODE)

### 14.1 Tính Năng

- Quét QR code trên vé để xác minh
- Ghi nhận thành công kiểm duyệt vé
- Lưu log quét (user, timestamp, tình trạng)

### 14.2 Dữ Liệu Vé

```
{
  "ve_id": 123,
  "phim": "Tên phim",
  "thoi_gian": "2025-11-29 19:00",
  "phong": "P1",
  "ghe": "A1",
  "gia": 150000,
  "user_name": "Tên khách hàng"
}
```

---

## 15. THANH TOÁN VIETQR

### 15.1 Luồng Thanh Toán VietQR

1. Khách hàng chọn "Thanh toán bằng VietQR"
2. Gọi API tạo QR code: `api_create_vietqr_payment.php`
3. Hiển thị QR code cho khách hàng
4. Khách hàng quét QR và thanh toán
5. Callback return: `vietqr_return.php`
6. Cập nhật trạng thái hóa đơn = "Đã thanh toán"

### 15.2 File Liên Quan

- `api_create_vietqr_payment.php` - Tạo QR payment
- `vietqr_checkout.php` - Trang checkout
- `vietqr_return.php` - Xử lý callback thanh toán

---

## 16. EXPORT BÁO CÁO

### 16.1 Chức Năng Export

- Export bảng lương ra Word
- Export danh sách bán vé
- Export doanh thu
- Export nhân sự

### 16.2 File Liên Quan

- `export_word.php` - Hàm export sang Word

---

## 17. PHÂN QUYỀN HỆ THỐNG

### 17.1 Các Role Chính

| Role | Quyền |
|------|-------|
| **Admin** | - Quản lý toàn bộ hệ thống<br>- Quản lý nhân viên<br>- Xem báo cáo doanh thu<br>- Duyệt bảng lương, nghỉ phép |
| **Manager Rạp** | - Quản lý rạp cụ thể<br>- Quản lý lịch chiếu<br>- Xem báo cáo rạp<br>- Duyệt bảng lương nhân viên rạp |
| **Nhân Viên Bán Vé** | - Bán vé, combo<br>- Chấm công<br>- Xem lịch chiếu |
| **Nhân Viên Hỗ Trợ** | - Chat với khách hàng<br>- Quét vé<br>- Hỗ trợ thanh toán |
| **Khách Hàng** | - Xem lịch chiếu<br>- Đặt vé<br>- Bình luận phim<br>- Xem khuyến mãi |

### 17.2 File Quản Lý Quyền

- `helpers/quyen.php` - Hàm kiểm tra quyền

---

## 18. DANH SÁCH RẠP & PHÒNG

### 18.1 Quản Lý Rạp

```
rap:
├─ id_rap
├─ ten_rap (VD: "Rạp BIG Cinema")
├─ dia_chi
├─ so_dien_thoai
├─ ghe_chu_quan (Người quản lý rạp)
└─ trang_thai (active/inactive)
```

### 18.2 Quản Lý Phòng Chiếu

```
phong:
├─ id_phong
├─ id_rap (Rạp nào)
├─ ten_phong (VD: "Phòng 1", "Phòng VIP")
├─ tong_ghe (Tổng số ghế)
└─ trang_thai
```

---

## 19. PHIM

### 19.1 Thông Tin Phim

```
phim:
├─ id_phim (INT, Primary Key)
├─ ten_phim (VARCHAR 255) - Tên phim
├─ danh_gia (DECIMAL 3,1) - Xếp hạng sao (0-10)
├─ trang_thai (ENUM) - 'dang_chieu' / 'sap_chieu' / 'het_chieu'
├─ hinh_anh_poster (VARCHAR 255) - Đường dẫn ảnh poster
├─ trailer_link (VARCHAR 500) - Link trailer YouTube
├─ mo_ta_chi_tiet (TEXT) - Mô tả chi tiết nội dung
├─ loai_phim (VARCHAR 100) - Thể loại phim
├─ thoi_luong (INT) - Thời lượng phim (phút)
├─ nam_phat_hanh (INT) - Năm phát hành
├─ dao_dien (VARCHAR 255) - Đạo diễn
├─ dien_vien (TEXT) - Danh sách diễn viên
├─ quoc_gia (VARCHAR 100) - Quốc gia sản xuất
└─ ngay_tao (TIMESTAMP)
```

### 19.2 Trạng Thái Phim

| Trạng Thái | Mô Tả | Có Thể Đặt Vé |
|-----------|-------|---------------|
| `dang_chieu` | Đang chiếu tại rạp | ✅ Có |
| `sap_chieu` | Sắp chiếu (trong 30 ngày tới) | ✅ Có (pre-order) |
| `het_chieu` | Đã hết chiếu | ❌ Không |

### 19.3 Loại Phim (Thể Loại)

| Thể Loại | Mô Tả | Độ Tuổi Khề |
|----------|-------|------------|
| Hành động | Action, phim hành động | 13+ |
| Tình cảm | Romance, kịch tính | 16+ |
| Hoạt hình | Animation, phim hoạt hình | 3+ |
| Kinh dị | Horror, thriller | 16+ |
| Hài kịch | Comedy, phim hài | 6+ |
| Khoa học viễn tưởng | Sci-fi, tương lai | 13+ |
| Thần thoại | Fantasy, phép thuật | 10+ |
| Chiến tranh | War, lịch sử | 16+ |
| Tâm lý | Drama, tâm lý | 18+ |
| Tài liệu | Documentary | 0+ |

### 19.4 Ví Dụ Phim Thực Tế

```
Phim 1:
├─ Tên: "Avatar: The Way of Water"
├─ Thể loại: Khoa học viễn tưởng, Hành động
├─ Năm: 2022
├─ Thời lượng: 192 phút
├─ Đạo diễn: James Cameron
├─ Diễn viên: Sam Worthington, Zoe Saldana...
├─ Đánh giá: 7.8/10
├─ Trạng thái: Đang chiếu
└─ Mô tả: Tiếp theo của Avatar, Jake Sully tiếp tục...

Phim 2:
├─ Tên: "Tây Du Ký: Hồi Hương Tây Phương"
├─ Thể loại: Hoạt hình, Phiêu lưu
├─ Năm: 2024
├─ Thời lượng: 120 phút
├─ Đạo diễn: Guo Jun
├─ Diễn viên: Diễn viên voice
├─ Đánh giá: 8.2/10
├─ Trạng thái: Đang chiếu
└─ Mô tả: Hành trình lấy kinh Phật...
```

### 19.5 Thông Tin Bổ Sung cho Phim

**Mức Độ Rạp Có Phim**:
- 1 phim có thể chiếu tại nhiều rạp cùng lúc
- Mỗi rạp lên lịch chiếu khác nhau
- Tương quan: `phim` ← (1:n) → `lich_chieu`

**Dữ Liệu Phim Liên Quan**:
- Bình luận/đánh giá từ khách hàng (bảng `binhluan`)
- Số lượng vé bán
- Tỷ lệ chiếm seat
- Doanh thu

### 19.6 API Phim

**Get danh sách phim đang chiếu**:
```
GET /api/phim/dang-chieu
Response:
[
  {
    "id_phim": 1,
    "ten_phim": "Avatar...",
    "danh_gia": 7.8,
    "hinh_anh_poster": "images/phim/avatar.jpg",
    "loai_phim": "Khoa học viễn tưởng",
    "trang_thai": "dang_chieu"
  },
  ...
]
```

**Get chi tiết phim**:
```
GET /api/phim/1
Response:
{
  "id_phim": 1,
  "ten_phim": "Avatar...",
  "thoi_luong": 192,
  "nam_phat_hanh": 2022,
  "dao_dien": "James Cameron",
  "dien_vien": ["Sam Worthington", "Zoe Saldana"],
  "mo_ta_chi_tiet": "...",
  "danh_gia": 7.8,
  "so_binh_luan": 234,
  "binh_luans": [
    {"id_user": 1, "noidung": "Hay quá", "ngay": "..."}
  ],
  "lich_chieu": [
    {"id_lich_chieu": 1, "id_rap": 1, "ngay": "2025-11-30", "gio_chieu": ["12:00", "15:00", "20:00"]}
  ]
}
```

---

## 20. THÔNG TIN KỸ THUẬT

### 20.1 Stack Công Nghệ

| Thành Phần | Công Nghệ |
|-----------|-----------|
| **Backend** | PHP 8.2.12 |
| **Database** | MySQL/MariaDB 10.4.32 |
| **Frontend** | HTML, CSS, JavaScript |
| **Framework Frontend** | Bootstrap (có thể) |
| **Thư viện JS** | jQuery, jQueryUI, QR code (jsqr) |
| **Email** | PHPMailer |
| **Export** | Word format |

### 20.2 Cấu Trúc Thư Mục

```
webphim/
├─ index.php (Trang chủ)
├─ DB/ (Database backup)
├─ js/ (JavaScript)
├─ Trang-admin/ (Admin panel)
│  ├─ index.php
│  ├─ login.php
│  ├─ routes_scanve.php
│  ├─ helpers/
│  │  ├─ export_word.php
│  │  └─ quyen.php
│  ├─ model/ (Business logic)
│  │  ├─ pdo.php
│  │  ├─ phim.php
│  │  ├─ rap.php
│  │  ├─ phong.php
│  │  ├─ phong_ghe.php
│  │  ├─ ve.php
│  │  ├─ taikhoan.php
│  │  ├─ chamcong.php
│  │  ├─ bangluong.php
│  │  ├─ khuyenmai.php
│  │  ├─ combo.php
│  │  └─ ...v.v...
│  └─ view/ (UI templates)
│     ├─ home/
│     ├─ phim/
│     ├─ vephim/
│     ├─ nhanvien/
│     ├─ quanly/
│     └─ ...v.v...
└─ Trang-nguoi-dung/ (Customer portal)
   ├─ index.php
   ├─ quete.php
   ├─ ajax.googleapis.com/
   ├─ css/
   ├─ js/
   ├─ images/
   ├─ PHPMailer/ (Email)
   ├─ phpqrcode/ (Generate QR)
   └─ ...v.v...
```

### 20.3 API Endpoints (Model Pattern)

- `model/phim.php` - CRUD Phim
- `model/rap.php` - CRUD Rạp
- `model/ve.php` - CRUD Vé, thanh toán
- `model/taikhoan.php` - Quản lý tài khoản
- `model/chamcong.php` - Chấm công
- `model/bangluong.php` - Bảng lương
- `model/scanve_api.php` - API quét vé QR

---

## 21. USE CASES CHÍNH

### 21.1 UC1: Đặt Vé Xem Phim - CHI TIẾT TOÀN BỘ WORKFLOW

**Actors**: Khách hàng, Hệ thống, Payment Gateway

#### **21.1.1 Quy Trình Đặt Vé Từng Bước**

```
┌─────────────────────────────────────────────────┐
│  BƯỚC 1: KHÁCH HÀNG ĐẶT VÉ                     │
└─────────────────────────────────────────────────┘

1.1 Xem Lịch Chiếu
    ├─ Khách truy cập /phim hoặc /trang-nguoi-dung
    ├─ Hệ thống load danh sách phim "dang_chieu"
    ├─ Khách chọn phim quan tâm
    └─ Xem chi tiết: tên, poster, trailer, đánh giá, bình luận

1.2 Chọn Rạp & Ngày Chiếu
    ├─ Khách chọn rạp (nếu nhiều rạp)
    ├─ Chọn ngày chiếu (calendar)
    ├─ Hệ thống hiển thị danh sách giờ chiếu
    └─ API: GET /api/lich-chieu?id_phim=1&id_rap=1&ngay=2025-11-30

1.3 Chọn Giờ Chiếu & Phòng
    ├─ Khách xem giờ chiếu có sẵn (12:00, 15:00, 20:00...)
    ├─ Chọn 1 giờ chiếu
    ├─ Hệ thống load bản đồ ghế phòng
    └─ API: GET /api/ghe-ngoi/phong/1?lich_chieu=1

1.4 Chọn Ghế
    ├─ Hiển thị bản đồ ghế với màu:
    │   - Xám: ghế trống
    │   - Xanh: ghế đã chọn
    │   - Đỏ: ghế đã bán
    │   - Vàng: ghế VIP
    ├─ Khách click chọn từ 1-N ghế
    ├─ Tính tổng tiền tạm thời
    └─ API: GET /api/ghe-ngoi/availability?id_phong=1&id_lich_chieu=1

Ví dụ: Khách chọn ghế A1 (standard 100k) + B2 (VIP 150k) = 250k

┌─────────────────────────────────────────────────┐
│  BƯỚC 2: THÊM COMBO (TUỲ CHỌN)                 │
└─────────────────────────────────────────────────┘

2.1 Xem Danh Sách Combo
    ├─ Hệ thống hiển thị các combo có sẵn
    ├─ Mỗi combo: tên, ảnh, thành phần, giá
    └─ API: GET /api/combo?id_rap=1

    Ví dụ:
    - Combo Standard: Bắp + Nước = 45k
    - Combo Premium: Bắp + Nước + Hotdog = 85k
    - Combo Family: 2 Bắp + 2 Nước + Bánh = 120k

2.2 Chọn Combo
    ├─ Khách chọn 0-N combo
    ├─ Tính tổng giá vé + combo
    └─ Hiển thị: Tổng = 250k (vé) + 85k (combo) = 335k

┌─────────────────────────────────────────────────┐
│  BƯỚC 3: KIỂM TRA HẠNG & ÁONẠP DỤNG KH KHUYẾN MÃI    │
└─────────────────────────────────────────────────┘

3.1 Kiểm Tra Hạng Thành Viên
    ├─ Nếu khách đã login: kiểm tra hạng hiện tại
    ├─ Lấy hang_thanh_vien từ tai_khoan_user
    ├─ Áp dụng giảm giá theo hạng (0%, 5%, 10%, 15%)
    └─ VD: Hạng Bạc → Giảm 5% = 335k × 5% = 16,750đ

3.2 Kiểm Tra Khuyến Mãi
    ├─ Hệ thống lấy danh sách KM đang hoạt động:
    │   - check ngay_bat_dau ≤ hôm nay ≤ ngay_ket_thuc
    │   - check trang_thai = 'active'
    │   - check so_luong_da_dung < so_luong_ton
    │   - check dieu_kien_ap_dung (min tiền, ghế, giờ...)
    │
    ├─ Hiển thị danh sách KM hợp lệ cho khách chọn
    └─ VD:
       - WELCOME50: Giảm 50k (nếu lần đầu)
       - STUDENT30: Giảm 30% (nếu có CCCD học sinh)
       - MORNING20: Giảm 20% buổi sáng (nếu chọn giờ sáng)

3.3 Khách Nhập Mã Khuyến Mãi
    ├─ Input: Mã khuyến mãi (VD: "WELCOME50")
    ├─ Hệ thống validate:
    │   - KM có tồn tại?
    │   - KM còn hạn?
    │   - KM còn số lượng?
    │   - Điều kiện thỏa mãn? (min tiền, loại ghế...)
    │   - Khách đã sử dụng hết lần?
    │
    ├─ Nếu hợp lệ: hiển thị tiền giảm
    └─ Nếu không: hiển thị lý do từ chối

3.4 Tính Tiền Sau Giảm
    ├─ Tiền gốc: Vé + Combo = 335,000đ
    ├─ Giảm hạng thành viên (5%): -16,750đ = 318,250đ
    ├─ Giảm khuyến mãi (WELCOME50): -50,000đ = 268,250đ
    └─ **TỔNG THANH TOÁN: 268,250đ** ← Hiển thị cho khách

┌─────────────────────────────────────────────────┐
│  BƯỚC 4: THANH TOÁN                            │
└─────────────────────────────────────────────────┘

4.1 Chọn Hình Thức Thanh Toán
    ├─ VietQR (QR code ngân hàng)
    ├─ Ví điện tử (Momo, ZaloPay...)
    ├─ Thẻ tín dụng (nếu có)
    ├─ Tiền mặt tại quầy (nếu offline)
    └─ Khách chọn 1 hình thức

4.2 Thanh Toán VietQR (Ví Dụ)
    ├─ Khách click "Thanh toán VietQR"
    ├─ Hệ thống call API: vietqr_checkout.php
    │   - Tạo yêu cầu thanh toán
    │   - Lấy thông tin: tiền, tên khách, số vé...
    │
    ├─ Hiển thị QR code cho khách
    ├─ Khách mở app ngân hàng, quét QR
    ├─ Nhập PIN → Gửi tiền
    ├─ Ngân hàng gọi callback: vietqr_return.php
    │   - Hệ thống nhận xác nhận thanh toán
    │   - Cập nhật hóa_don.trang_thai = 1 (đã thanh toán)
    │   - Cập nhật ve.trang_thai = 1 (đã bán)
    │   - Tính điểm thành viên + tích lũy
    │
    └─ Hiển thị "Thanh toán thành công" ✅

4.3 Nhận Vé
    ├─ Hệ thống tạo vé (ve_id, QR code)
    ├─ Gửi email chứa:
    │   - Số vé
    │   - QR code
    │   - Chi tiết (phim, giờ, ghế, rạp)
    │   - Hướng dẫn (in vé hoặc show QR tại cổng)
    │
    └─ Hiển thị trên app: "Vé đã được gửi tới email"

┌─────────────────────────────────────────────────┐
│  BƯỚC 5: TÍCH ĐIỂM (NẾU THÀNH VIÊN)            │
└─────────────────────────────────────────────────┘

5.1 Tính Điểm
    ├─ Tiền thanh toán thực: 268,250đ
    ├─ Hệ số hạng Bạc: 1.2x
    ├─ Điểm nhận: 268,250 × 1.2 = 321,900 ≈ 322 điểm
    └─ Tích vào tài khoản

5.2 Kiểm Tra Nâng Hạng
    ├─ Điểm cũ: 950 điểm
    ├─ Điểm mới: 950 + 322 = 1,272 điểm
    ├─ Điểm cần Hạng Vàng: 5,000
    └─ Chưa đủ nâng hạng (còn cần 3,728 điểm)

5.3 Gửi Thông Báo
    ├─ Email: "Bạn vừa tích được 322 điểm"
    ├─ SMS: "Tích điểm thành công"
    └─ App: Popup thông báo

┌─────────────────────────────────────────────────┐
│  BỰ 6: QUÉT VÉ VÀO RẠP (SAU)                  │
└─────────────────────────────────────────────────┘

6.1 Khách Tới Rạp
    ├─ In vé hoặc mở trên điện thoại show QR
    └─ Tới cổng kiểm duyệt

6.2 Nhân Viên Quét VÉ
    ├─ Quét QR code bằng thiết bị mobile
    ├─ Hệ thống kiểm tra:
    │   - Vé hợp lệ?
    │   - Chưa được sử dụng?
    │   - Còn hiệu lực? (giờ chiếu chưa bắt đầu)
    │
    ├─ Nếu OK: Hiển thị ✅ "Chào mừng [Tên Khách]"
    ├─ Cập nhật ve.trang_thai = 2 (đã kiểm tra)
    └─ Khách vào xem phim

6.3 Nếu Có Vấn Đề
    ├─ Vé lỗi → Gọi quản lý
    ├─ Vé bị sử dụng 2 lần → Từ chối
    ├─ Giờ chiếu đã bắt đầu → Có thể từ chối
    └─ Ghi log lỗi để hỗ trợ khách hàng
```

#### **21.1.2 Ví Dụ Thực Tế Toàn Bộ**

```
Khách: Nguyễn Văn A (Hạng Bạc, 950 điểm, email: a@email.com)

BƯỚC 1: Chọn Phim
  → Avatar: The Way of Water (3h12m, 7.8/10 sao)

BƯỚC 2: Chọn Rạp & Ngày
  → Rạp 1 Big Cinema
  → Ngày 29-11-2025
  → Giờ 20:00 (tối)

BƯỚC 3: Chọn Ghế
  → A1 (Standard) - 100,000đ
  → B2 (VIP) - 150,000đ
  → Tổng vé: 250,000đ

BƯỚC 4: Thêm Combo
  → Combo Premium (Bắp + Nước + Hotdog) - 85,000đ
  → Tổng vé + combo: 335,000đ

BƯỚC 5: Kiểm Tra Hạng & Khuyến Mãi
  → Hạng Bạc: Giảm 5% = 335,000 × 5% = -16,750đ
  → Sau hạng: 318,250đ
  → Áp dụng KM WELCOME50 (nếu lần đầu): -50,000đ
  → Tổng thanh toán: 268,250đ

BƯỚC 6: Thanh Toán
  → Chọn VietQR
  → Quét QR code
  → Thanh toán: 268,250đ ✅ THÀNH CÔNG

BƯỚC 7: Nhận Vé
  → Email: a@email.com
  → Nội dung:
     - Vé số: VE-20251129-000123
     - Phim: Avatar: The Way of Water
     - Giờ: 20:00 - 29/11/2025
     - Phòng: Phòng 1
     - Ghế: A1, B2
     - QR code: [QR code ở đây]
     - Ghi chú: In vé hoặc hiển thị QR tại cổng

BƯỚC 8: Tích Điểm
  → Điểm: 268,250 × 1.2 = 322 điểm
  → Tổng điểm: 950 + 322 = 1,272 điểm
  → Thông báo: "Bạn vừa tích được 322 điểm"

BƯỚC 9: Vào Rạp
  → 19:50 (10 phút trước): Tới cổng
  → Nhân viên quét QR code
  → Kiểm tra: ✅ OK
  → Vé: VE-20251129-000123 hợp lệ
  → Khách vào xem phim
```

### 21.2 UC2: Quét Vé Vào Cổng

**Actors**: Nhân viên hỗ trợ, Khách hàng, QR Scanner

**Flow**:
1. Khách hàng tới rạp
2. Nhân viên quét QR trên vé (điện thoại hoặc giấy)
3. Kiểm tra tính hợp lệ
4. Ghi nhận thành công
5. Khách hàng vào xem phim

### 21.3 UC3: Tính Lương Nhân Viên

**Actors**: Người quản lý lương

**Flow**:
1. Tập hợp dữ liệu chấm công tháng
2. Tính số giờ làm việc
3. Tính lương cơ bản
4. Thêm phụ cấp/khấu trừ
5. Duyệt bảng lương
6. Thanh toán/Export báo cáo

### 21.4 UC4: Chat với Bot AI

**Actors**: Khách hàng, Bot AI

**Flow**:
1. Khách hàng mở chat widget
2. Gửi câu hỏi (đặt vé, giờ chiếu, khuyến mãi...)
3. Bot phân tích intent
4. Trả lời dựa vào kiến thức
5. Lưu lịch sử chat
6. Thống kê độ hài lòng

### 21.5 UC5: Duyệt Đơn Nghỉ Phép

**Actors**: Quản lý rạp, Nhân viên

**Flow**:
1. Nhân viên nộp đơn nghỉ phép
2. Kỳ hạn và lý do
3. Quản lý xem xét
4. Duyệt (Chấp nhận/Từ chối)
5. Thông báo cho nhân viên

---

## 22. WORKFLOW THANH TOÁN

```
Khách hàng chọn vé
         ↓
Chọn combo (nếu có)
         ↓
Áp dụng mã khuyến mãi/voucher
         ↓
Tính tổng = tổng vé + combo - khuyến mãi
         ↓
Chọn hình thức thanh toán
         ├─ VietQR → Tạo QR → Quét → Callback
         ├─ Tiền mặt → Ghi nhận
         └─ Hình thức khác
         ↓
Cập nhật hóa đơn (trang_thai = 1)
         ↓
Cập nhật thành viên (tích điểm)
         ↓
Gửi vé qua email/hiển thị QR
         ↓
Hoàn thành
```

---

## 23. QUYẾT ĐỊNH THIẾT KẾ

### 23.1 Lưu Trữ Giá

- Giá ghế được lưu trong `ghe_ngoi.gia_ghe`
- Giá combo được lưu trong `combo_do_an.gia`
- Giá từng mặt hàng combo được lưu trong `chi_tiet_combo.don_gia`
- **Lý do**: Tách biệt để theo dõi lịch sử giá, dễ báo cáo

### 23.2 Bảng Lương Chi Tiết

- Tách riêng `bang_luong_chi_tiet` để lưu từng khoản phụ cấp/khấu trừ
- **Lý do**: Dễ audit, dễ báo cáo chi tiết từng khoản

### 23.3 Trạng Thái Hóa Đơn

- Sử dụng `tinyint(1)` cho trạng thái
- 0 = Chưa thanh toán, 1 = Đã thanh toán
- **Lý do**: Nhanh, tiết kiệm storage

### 23.4 Khung Giờ Chiếu

- Tách riêng `khung_gio_chieu` để cho phép 1 lịch chiếu có multiple giờ/phòng
- **Lý do**: Linh hoạt, hỗ trợ một bộ phim chiếu nhiều phòng, nhiều giờ

---

## 24. CONSTRAINT & VALIDATION

### 24.1 Business Rules

1. **Không được đặt vé quá khứ** - Ngày chiếu ≥ ngày hôm nay
2. **Ghế chỉ đặt 1 lần** - Kiểm tra trạng thái ghế trước khi bán
3. **Bảng lương chỉ có 1 bản/tháng/nhân viên** - Unique(id_nv, id_rap, thang)
4. **Thành viên > 0 điểm** - Điểm không âm
5. **Giá ≥ 0** - Không có giá âm

### 24.2 Validation

- **Email**: Format email hợp lệ
- **Số điện thoại**: 10-11 chữ số
- **Ngày**: Định dạng YYYY-MM-DD
- **Thời gian**: Định dạng HH:MM:SS

---

## 25. MẪU DỮ LIỆU

### 25.1 Ví Dụ Bảng Lương

```
ID: 4
Nhân viên: #30
Rạp: #1
Tháng: 2025-11
Số giờ: 5.13
Lương cơ bản: 153,999đ
Phụ cấp: 500,000đ
Khấu trừ: 0đ
Thưởng: 0đ
Tổng: 653,999đ
Trạng thái: Chờ duyệt
```

### 25.2 Ví Dụ Vé

```
ID: 123
Phim: "Tên Phim"
Lịch chiếu: 2025-11-29
Phòng: 1
Ghế: A1 (Standard)
Giá: 100,000đ
Khách hàng: #17
Trạng thái: Đã thanh toán
```

### 25.3 Ví Dụ Bình Luận

```
ID: 30
Phim: #5
Người: #17 (Tên người dùng)
Nội dung: "Hay quá bạn"
Ngày: 10:06:am 20-04-2025
```

---

## 26. KPI & THỐNG KÊ

### 26.1 KPI Kinh Doanh

- Tổng doanh thu theo tháng/rạp
- Số vé bán (theo phim, theo rạp)
- Tỷ lệ chiếm seat trung bình
- Doanh thu combo
- Tỷ lệ khách hàng quay lại (repeat rate)

### 26.2 KPI Chat AI

- Tổng số câu hỏi xử lý
- Intent phổ biến
- Độ chính xác bot
- Thời gian phản hồi trung bình
- Tỷ lệ hài lòng khách hàng

### 26.3 KPI Nhân Sự

- Tỷ lệ chấm công đúng giờ
- Chi phí lương trung bình/tháng
- Số lần vắng mặt không phép
- Tỷ lệ duyệt nghỉ phép

---

## 27. NOTES CHO AI TRAINING

### 27.1 Thói Quen Dữ Liệu

- Ngày giờ thường dùng `DATETIME` (datetime)
- Ngày dùng `DATE` (date)
- Giờ dùng `TIME` (time)
- Tiền tệ dùng `DECIMAL(12,2)` để tránh mất chính xác
- Trạng thái thường dùng `ENUM` hoặc `TINYINT`

### 27.2 Convention

- **Bảng**: snake_case, số ít (VD: `phim`, `ve`, không `phims`)
- **Cột**: snake_case, có tiền tố `id_` cho FK (VD: `id_phim`, `id_user`)
- **Date columns**: thường `ngay_tao` (created), `ngay_cap_nhat` (updated)

### 27.3 Phần Tử Thứ Hai Nên Nhớ

- **Hạng thành viên**: Tích điểm = tiền × hệ số
- **Bảng lương**: Tính từ chấm công → số giờ
- **Combo**: Có thể áp dụng 1 rạp hoặc tất cả (id_rap = NULL)
- **Chat AI**: Lưu intent + confidence để dễ audit, improve model

### 27.4 API Endpoints Quan Trọng

- `scanve_api.php` - Quét vé
- `api_create_vietqr_payment.php` - Tạo QR thanh toán
- `vietqr_return.php` - Callback thanh toán

---

**Tài liệu được tạo ngày: 29-11-2025**  
**Phiên bản Database: cinepass_moinhatttttttttttttttttttt**  
**Tổng số bảng dữ liệu: 20+ bảng**
