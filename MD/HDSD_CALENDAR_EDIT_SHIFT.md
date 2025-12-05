# Hướng dẫn sử dụng - Chỉnh sửa Lịch Làm Việc Trực Tiếp trên Calendar

## 🎯 Tính năng mới

Giờ đây bạn có thể **chỉnh sửa hoặc xóa lịch làm việc trực tiếp** trên trang Calendar mà không cần đi đến trang khác!

---

## 📋 Các chức năng chính

### 1. **Phân công lịch hàng loạt** (Flow cũ - vẫn hoạt động)
- Chọn 1 hoặc nhiều nhân viên ở panel bên trái
- Click vào ngày trong Calendar
- Chọn khoảng thời gian (1 ngày hoặc nhiều ngày)
- Chọn 1 hoặc nhiều ca làm việc
- Nhấn "Lưu phân công hàng loạt"

### 2. **Chỉnh sửa ca làm việc** (MỚI!)
- Di chuột vào bất kỳ ca nào trên calendar → thấy nút **X** nhỏ ở phải
- **Click vào ca** (phần chữ) để mở modal sửa
- Sửa: Giờ bắt đầu, giờ kết thúc, loại ca, ghi chú
- Nhấn **"💾 Lưu"** để lưu hoặc **"❌ Hủy"** để đóng

### 3. **Xóa ca làm việc** (MỚI!)
**Cách 1 - Từ modal sửa:**
- Click vào ca → modal mở
- Nhấn **"🗑️ Xóa"** ở góc trái dưới
- Xác nhận xóa

**Cách 2 - Nhanh từ calendar:**
- Di chuột vào ca
- Nhấn nút **X** nhỏ ở bên phải ca
- Xác nhận xóa

---

## 🎨 Giao diện

### Trên Calendar:
```
📅 Ngày 10/12
├─ Nhân Viên 1
│  └─ 08:00-12:00 [✕]  ← Click X để xóa nhanh
├─ Nhân Viên 2
│  └─ 13:00-17:00 [✕]  ← Hoặc click vào ca để sửa
```

### Modal Sửa Shift:
```
┌─────────────────────────────┐
│ ✏️ Sửa ca làm việc          │
├─────────────────────────────┤
│ 👤 Nhân viên: [Tên - disabled]
│ 🕐 Giờ bắt đầu: [08:00]
│ 🕐 Giờ kết thúc: [12:00]
│ 📋 Ca làm việc: [Sáng ▼]
│ 📝 Ghi chú: [...]
├─────────────────────────────┤
│ [🗑️ Xóa] [❌ Hủy] [💾 Lưu] │
└─────────────────────────────┘
```

---

## 💡 Mẹo sử dụng

### Sửa nhanh nhiều ca:
1. Click ca 1 → sửa → Lưu
2. Calendar reload tự động
3. Click ca 2 → sửa → Lưu
4. ... tiếp tục

### Xóa nhanh nhiều ca:
- Di chuột vào ca → nhấn X
- Xác nhận → xóa liền

### Phân công + Sửa trong 1 lần:
1. Phân công hàng loạt cho nhân viên
2. Nếu có ca không đúng → click sửa
3. Không cần đi ra ngoài trang

---

## ⚙️ Backend API (cho developer)

### POST: Tạo phân công hàng loạt
```json
{
  "action": "create_assignments",
  "assignments": [
    {
      "nhanvien_id": 123,
      "ngay": "2025-12-10",
      "gio_bat_dau": "08:00",
      "gio_ket_thuc": "12:00",
      "ca_lam": "Sáng",
      "ghi_chu": "..."
    }
  ]
}
```

### POST: Sửa ca làm việc
```json
{
  "action": "update_shift",
  "id": 456,
  "gio_bat_dau": "09:00",
  "gio_ket_thuc": "13:00",
  "ca_lam": "Ca Sáng",
  "ghi_chu": "Ghi chú"
}
```

### POST: Xóa ca làm việc
```json
{
  "action": "delete_shift",
  "id": 456
}
```

### GET: Lấy thông tin ca
```
GET index.php?act=ql_lichlamviec_calendar&action=get_shift&id=456
```

---

## 🔒 Quyền hạn

- Chỉ quản lý rạp mình mới có thể sửa/xóa lịch của rạp đó
- Tự động kiểm tra quyền từ session

---

## 📝 Lưu ý

1. **Giờ kết thúc phải sau giờ bắt đầu** - Hệ thống sẽ báo lỗi nếu không
2. **Tuyệt đối không sửa trong quá trình xử lý** - Tungcông nhân viên có thể là
3. **Reload calendar** sau khi sửa/xóa để cập nhật dữ liệu
4. **Backup trước khi xóa hàng loạt** - Không có undo!

---

## 🐛 Troubleshooting

**Vấn đề: Nút X không hiển thị**
- Giải pháp: Di chuột vào ca → phải có nút X

**Vấn đề: Modal không mở khi click ca**
- Giải pháp: Kiểm tra console (F12) có lỗi gì không

**Vấn đề: Sửa nhưng không lưu được**
- Giải pháp: Kiểm tra:
  - Quyền hạn có hợp lệ?
  - Giờ kết thúc > giờ bắt đầu?
  - Kết nối mạng OK?

---

**Version**: v1.0  
**Cập nhật**: 04/12/2025
