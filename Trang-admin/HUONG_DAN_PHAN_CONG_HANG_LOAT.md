# 📋 Hướng Dẫn Phân Công Ca Làm Việc Hàng Loạt

## 🎯 Tính năng mới

Hệ thống bây giờ hỗ trợ **phân công hàng loạt** với các khả năng sau:

### ✨ Điểm nổi bật:
- ✅ **Chọn nhiều nhân viên** cùng lúc
- ✅ **Chọn nhiều ngày** (ngày đơn hoặc khoảng thời gian)
- ✅ **Chọn nhiều ca** cho mỗi ngày
- ✅ **Lọc theo ngày trong tuần** (chỉ áp dụng T2-T6, bỏ qua T7-CN)
- ✅ **Tạo ca tùy chỉnh** với giờ linh hoạt
- ✅ **Xem tổng quan** trước khi lưu

---

## 📖 Hướng dẫn sử dụng từng bước

### Bước 1: Chọn nhân viên 👥
1. Nhấp vào các **thẻ nhân viên** ở panel bên trái
2. Nhân viên được chọn sẽ có **dấu ✓ màu xanh**
3. Có thể dùng nút **"Chọn tất cả"** hoặc **"Bỏ chọn"**

### Bước 2: Mở modal phân công 📝
1. Nhấp vào **ngày bất kỳ** trên lịch
2. Modal sẽ hiện ra với form phân công

### Bước 3: Chọn khoảng thời gian 📅

#### Tùy chọn A: Ngày đơn
- Chọn radio **"Ngày đơn"**
- Ngày được tự động điền theo ngày bạn click

#### Tùy chọn B: Khoảng thời gian
- Chọn radio **"Khoảng thời gian"**
- Chọn **Từ ngày** và **Đến ngày**
- Chọn **các ngày trong tuần** muốn áp dụng:
  - ✅ Tích: T2, T3, T4, T5, T6 → Chỉ áp dụng ngày đi làm
  - ❌ Bỏ tích: T7, CN → Bỏ qua cuối tuần

**Ví dụ:** 
- Từ 1/10 đến 30/10
- Chỉ chọn T2-T6
- → Hệ thống tự động tạo ca cho ~22 ngày làm việc

### Bước 4: Chọn ca làm việc ⏰

Có 2 cách chọn ca:

#### Cách 1: Chọn ca có sẵn
Tích vào các thẻ ca:
- 🌅 **Ca Sáng**: 8:00 - 12:00
- ☀️ **Ca Chiều**: 13:00 - 17:00
- 🌙 **Ca Tối**: 17:00 - 22:00
- 🏢 **Hành chính**: 9:00 - 18:00

**Có thể chọn nhiều ca cùng lúc!**
- Ví dụ: Tích cả **Ca Sáng** + **Ca Tối** → Mỗi ngày sẽ có 2 ca

#### Cách 2: Tạo ca tùy chỉnh
1. Nhấp **"➕ Thêm ca tùy chỉnh"**
2. Điền thông tin:
   - **Tên ca**: VD "Ca khuya"
   - **Giờ bắt đầu**: 22:00
   - **Giờ kết thúc**: 06:00
3. Nhấp **"✅ Thêm ca này"**
4. Ca tùy chỉnh sẽ được thêm vào danh sách
5. Có thể thêm **nhiều ca tùy chỉnh**

### Bước 5: Xem tổng quan 📊
Phần **"Tổng quan"** ở dưới cùng sẽ hiển thị:
```
3 nhân viên × 22 ngày × 2 ca = 132 lượt phân công
```

Giúp bạn kiểm tra trước khi lưu!

### Bước 6: Thêm ghi chú (tùy chọn) 📝
- Có thể thêm ghi chú chung
- Ghi chú sẽ áp dụng cho **tất cả** các ca được tạo

### Bước 7: Lưu phân công 💾
1. Nhấp **"💾 Lưu phân công hàng loạt"**
2. Hệ thống sẽ hiện popup xác nhận với số lượng ca
3. Nhấp **OK** để xác nhận
4. Chờ hệ thống xử lý
5. Trang sẽ tự động tải lại khi thành công

---

## 💡 Các trường hợp sử dụng thực tế

### Trường hợp 1: Phân công ca cố định cho cả tháng
**Mục tiêu:** 3 nhân viên làm ca sáng từ T2-T6 cả tháng 10

**Các bước:**
1. Chọn 3 nhân viên
2. Chọn "Khoảng thời gian": 1/10 → 31/10
3. Chỉ tích T2, T3, T4, T5, T6
4. Tích **Ca Sáng**
5. Lưu → Tạo ~66 lượt phân công (3 × 22 × 1)

### Trường hợp 2: Phân công nhiều ca cho 1 nhân viên
**Mục tiêu:** Nhân viên A làm ca sáng + ca tối trong tuần này

**Các bước:**
1. Chọn nhân viên A
2. Chọn "Khoảng thời gian": 30/9 → 4/10
3. Chỉ tích T2, T3, T4, T5, T6
4. Tích cả **Ca Sáng** + **Ca Tối**
5. Lưu → Tạo 10 lượt (1 × 5 × 2)

### Trường hợp 3: Ca tùy chỉnh cho nhóm nhân viên
**Mục tiêu:** 2 nhân viên làm ca đặc biệt 14:00-20:00 vào T7-CN

**Các bước:**
1. Chọn 2 nhân viên
2. Chọn "Khoảng thời gian": 1/10 → 31/10
3. Chỉ tích T7, CN
4. Nhấp "➕ Thêm ca tùy chỉnh"
   - Tên: "Ca cuối tuần"
   - Giờ: 14:00 - 20:00
5. Lưu → Tạo ~16 lượt (2 × 8 × 1)

### Trường hợp 4: Phân công đa dạng
**Mục tiêu:** 
- 5 nhân viên
- Cả tháng (chỉ ngày làm việc)
- Mỗi ngày có 3 ca: Sáng, Chiều, Tối

**Các bước:**
1. Chọn 5 nhân viên
2. Khoảng thời gian: 1/10 → 31/10, chỉ T2-T6
3. Tích: Ca Sáng + Ca Chiều + Ca Tối
4. Lưu → Tạo **330 lượt** (5 × 22 × 3) 🚀

---

## ⚠️ Lưu ý quan trọng

### Về validation:
- ❌ Hệ thống sẽ **KHÔNG** kiểm tra trùng lịch
- ⚠️ Bạn có thể tạo 2 ca cùng lúc cho 1 nhân viên → Lỗi logic
- 💡 **Khuyến nghị:** Kiểm tra lịch trước khi phân công hàng loạt

### Về hiệu suất:
- ⏱️ Tạo > 100 ca cùng lúc có thể mất vài giây
- 🔄 Đợi trang reload hoàn toàn
- 📊 Kiểm tra kết quả sau khi reload

### Về dữ liệu:
- 💾 Tất cả thay đổi được lưu vào database
- 🔙 Không có chức năng "Undo" → Cẩn thận khi lưu
- 🗑️ Phải xóa thủ công nếu tạo nhầm

---

## 🐛 Xử lý lỗi

### Lỗi 1: "Chưa chọn nhân viên"
**Nguyên nhân:** Chưa chọn nhân viên nào
**Giải pháp:** Click vào thẻ nhân viên ở panel trái

### Lỗi 2: "Chưa chọn ca làm việc"
**Nguyên nhân:** Không tích ca nào và không tạo ca tùy chỉnh
**Giải pháp:** Tích ít nhất 1 ca có sẵn hoặc tạo ca mới

### Lỗi 3: "Không có ngày nào phù hợp"
**Nguyên nhân:** 
- Chọn khoảng thời gian nhưng bỏ tích tất cả ngày trong tuần
- Hoặc khoảng thời gian không hợp lệ

**Giải pháp:** Kiểm tra lại khoảng thời gian và các ngày được chọn

### Lỗi 4: "Giờ kết thúc phải sau giờ bắt đầu"
**Nguyên nhân:** Ca tùy chỉnh có giờ không hợp lệ
**Giải pháp:** Điều chỉnh lại giờ (VD: 08:00 → 17:00)

### Lỗi 5: "Server trả về dữ liệu không hợp lệ"
**Nguyên nhân:** Lỗi server hoặc backend
**Giải pháp:** 
1. Kiểm tra console (F12)
2. Báo với admin
3. Thử lại với số lượng ít hơn

---

## 🎨 Giao diện trực quan

### Màu sắc hiểu rõ:
- **Xanh dương gradient** = Panel chính
- **Tím gradient** = Phần được chọn
- **Xanh lá** = Thành công / Đã chọn
- **Vàng** = Cảnh báo / Chưa đầy đủ
- **Đỏ** = Lỗi / Xóa

### Icon ý nghĩa:
- 🌅 Ca Sáng
- ☀️ Ca Chiều  
- 🌙 Ca Tối
- 🏢 Hành chính
- ✅ Đã chọn
- 📊 Tổng quan
- 💾 Lưu
- 🗑️ Xóa

---

## 🚀 Tips & Tricks

### Tip 1: Phân công nhanh cho team
1. Dùng "Chọn tất cả" nhân viên
2. Chọn khoảng thời gian dài
3. Chỉ chọn ngày làm việc (T2-T6)
4. Tích 1-2 ca chính
5. Lưu → Phân công cho cả tháng trong 30 giây!

### Tip 2: Kiểm tra trước khi lưu
- Luôn xem phần **Tổng quan**
- Tính toán: Số nhân viên × Số ngày × Số ca
- Nếu số quá lớn bất thường → Kiểm tra lại!

### Tip 3: Phân công theo pattern
**Pattern 1: Luân phiên ca**
- Tuần 1: Team A = Ca Sáng, Team B = Ca Tối
- Tuần 2: Đảo ngược

**Pattern 2: Cố định theo nhân viên**
- Nhân viên A: Luôn ca Sáng + Chiều
- Nhân viên B: Luôn ca Tối

### Tip 4: Xử lý cuối tuần
- Tạo phân công riêng cho T7-CN
- Thường ít ca hơn ngày thường
- Có thể dùng ca tùy chỉnh với giờ khác

---

## 📞 Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra console (F12 → Console tab)
2. Chụp màn hình lỗi
3. Liên hệ admin với thông tin:
   - Số nhân viên
   - Khoảng thời gian
   - Số ca
   - Message lỗi

---

**Cập nhật:** Tháng 10, 2025
**Phiên bản:** 2.0 - Bulk Assignment
