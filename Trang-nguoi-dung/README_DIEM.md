# 📊 HỆ THỐNG TÍCH ĐIỂM RẠP CHIẾU PHIM

## ⚙️ CẤU HÌNH HIỆN TẠI (SAU KHI CẬP NHẬT)

### 1. Tỷ lệ quy đổi
- **1,000 VND = 1 điểm** (ti_le_quy_doi = 0.001)
- Vé 80,000 VND → **80 điểm**
- Combo 50,000 VND → **50 điểm** (hoặc có thể tính riêng)

### 2. Hạng thành viên

| Hạng | Điểm yêu cầu | Hệ số tích điểm | Giảm giá | Màu sắc |
|------|--------------|-----------------|----------|---------|
| 🥉 Đồng | 0 | 1.0x | 0% | #CD7F32 |
| 🥈 Bạc | 1,000 | 1.2x | 5% | #C0C0C0 |
| 🥇 Vàng | 5,000 | 1.5x | 10% | #FFD700 |
| 💎 Kim Cương | 15,000 | 2.0x | 15% | #B9F2FF |

### 3. Ví dụ tích điểm

#### Kịch bản 1: Khách hàng mới
- Đặt 1 vé 80,000 VND
- Hạng: Đồng (hệ số 1.0x)
- Điểm nhận: 80 × 1.0 = **80 điểm**
- Còn cần: 920 điểm nữa → Lên Bạc

#### Kịch bản 2: Đặt vé nhiều lần
- Đặt 13 lần vé 80,000 VND = 1,040,000 VND
- Tổng điểm: ~1,040 điểm
- **→ Lên hạng Bạc** ✨

#### Kịch bản 3: Hạng Bạc đặt vé
- Đã có hạng Bạc (hệ số 1.2x)
- Đặt 1 vé 80,000 VND
- Điểm nhận: 80 × 1.2 = **96 điểm**

#### Kịch bản 4: Đặt 8 ghế cùng lúc
- 8 ghế × 80,000 = 640,000 VND
- Hạng Đồng: 640 × 1.0 = **640 điểm** (vẫn còn Đồng)
- Hạng Bạc: 640 × 1.2 = **768 điểm** (tăng nhanh hơn)

---

## 🎯 ĐƯỜNG LÊN HẠNG (VỚI VÉ 80K/VÉ)

### Hạng Đồng → Bạc (1,000 điểm)
- Cần: **~13 vé** (1,040,000 VND)
- Thời gian: ~1-2 tháng (xem phim 2 tuần/lần)

### Hạng Bạc → Vàng (5,000 điểm)
- Cần thêm: 4,000 điểm
- Với hệ số 1.2x: **~42 vé nữa** (3,360,000 VND)
- Tổng cộng: ~55 vé (4,400,000 VND)
- Thời gian: ~4-6 tháng

### Hạng Vàng → Kim Cương (15,000 điểm)
- Cần thêm: 10,000 điểm
- Với hệ số 1.5x: **~83 vé nữa** (6,640,000 VND)
- Tổng cộng: ~138 vé (11,040,000 VND)
- Thời gian: ~1-2 năm
- **→ Dành cho khách VIP thực sự!** 👑

---

## 🔧 TÙY CHỈNH HỆ THỐNG

### Nếu thấy vẫn dễ lên hạng quá:
```sql
-- Tăng điểm yêu cầu lên gấp đôi
UPDATE hang_thanh_vien SET diem_toi_thieu = 2000 WHERE ma_hang = 'bac';
UPDATE hang_thanh_vien SET diem_toi_thieu = 10000 WHERE ma_hang = 'vang';
UPDATE hang_thanh_vien SET diem_toi_thieu = 30000 WHERE ma_hang = 'kim_cuong';
```

### Nếu thấy khó lên hạng quá:
```sql
-- Giảm điểm yêu cầu xuống
UPDATE hang_thanh_vien SET diem_toi_thieu = 500 WHERE ma_hang = 'bac';
UPDATE hang_thanh_vien SET diem_toi_thieu = 2000 WHERE ma_hang = 'vang';
UPDATE hang_thanh_vien SET diem_toi_thieu = 8000 WHERE ma_hang = 'kim_cuong';
```

### Nếu muốn tăng tỷ lệ tích điểm:
```sql
-- 500 VND = 1 điểm (thay vì 1,000 VND = 1 điểm)
UPDATE quy_tac_tich_diem SET ti_le_quy_doi = 0.002 WHERE loai = 'dat_ve';
```

---

## 📈 SO SÁNH TRƯỚC/SAU

| Tiêu chí | TRƯỚC (Cũ) | SAU (Mới) |
|----------|------------|-----------|
| Tỷ lệ quy đổi | 100 VND = 1đ | 1,000 VND = 1đ |
| Vé 80K → Điểm | 800 điểm | 80 điểm |
| Lên Bạc | 1 vé | 13 vé |
| Lên Vàng | 3 vé | 55 vé |
| Lên Kim Cương | 7 vé | 138 vé |
| **Đánh giá** | Quá dễ 😅 | Hợp lý ✅ |

---

## 🚀 CÁCH SỬ DỤNG

1. Mở phpMyAdmin → Chọn database `cinepass`
2. Vào tab SQL → Paste nội dung file `update_diem_system.sql`
3. Click "Go" để chạy
4. Kiểm tra kết quả bằng query cuối file
5. Test bằng cách đặt vé và xem điểm nhận được

---

## ⚠️ LƯU Ý

- File SQL có option **reset điểm user** (comment lại) - CHỈ BẬT NẾU MUỐN TEST LẠI TỪ ĐẦU
- Sau khi update, user cũ sẽ giữ nguyên điểm hiện tại
- Hệ thống sẽ tính theo quy tắc mới cho các giao dịch tiếp theo
- Có thể điều chỉnh lại các con số cho phù hợp với chiến lược kinh doanh

---

**Made with ❤️ for Galaxy Cinema**
