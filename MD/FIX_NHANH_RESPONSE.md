# ⚡ FIX NHANH: Scanner Không Hiển Thị Kết Quả

## **✅ ĐÓNG GÓP**

Vừa thêm debug logging vào scanner:
- ✅ Console sẽ in chi tiết từng bước
- ✅ Xem được request/response
- ✅ Dễ dàng tìm lỗi

---

## **🔍 CÁC BƯỚC DEBUG**

### **Bước 1: Quét và Xem Console**

1. Vô: `http://localhost/webphim/Trang-admin/index.php?act=scanve_new`
2. Nhấn **F12** → Tab **Console**
3. Quét QR từ vé
4. **Xem console log** xuất hiện

**Dấu hiệu OK:**
```
📤 Gửi request check-in với mã: 1
📥 Response status: 200 true
✅ Kiểm tra vé: {success: true, ...}
✅ Vé hợp lệ, hiển thị check-in button
```

**Nếu báo lỗi → Copy lỗi → Gửi cho admin**

### **Bước 2: Test Endpoint Trực Tiếp**

URL: `http://localhost/webphim/test_endpoint_scanve.html`

Thực hiện:
1. Nhập mã vé (VD: 1, 2, 3...)
2. Nhấn "🔍 Test scanve_check"
3. Xem output

**Output tốt:**
```json
{
  "success": true,
  "message": "Vé hợp lệ - Nhấn Check-in để xác nhận",
  "ticket": {
    "id": 1,
    "movie_title": "Tên phim",
    ...
  }
}
```

**Output xấu:**
- Báo "Vé không tồn tại" → Không có vé này trong DB
- Báo lỗi khác → Xem thêm chi tiết

### **Bước 3: Gửi Feedback**

Nếu vẫn không được:

```
Browser: [Chrome/Firefox/Edge/Safari]
URL: [link bị lỗi]
Quét được: [OK/Không]
Console log: [copy từ console]
Test endpoint result: [copy output]
```

---

## **📝 TÓMMẮT**

| Hành Động | Kết Quả |
|----------|--------|
| Thêm debug logging | ✅ Dễ debug |
| Test endpoint page | ✅ Kiểm tra nhanh |
| Console chi tiết | ✅ Thấy được error |

---

**Xong! ✅** Quét lại và xem console
