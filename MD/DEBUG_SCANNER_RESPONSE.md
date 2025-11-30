# 🔍 Debug: Scanner Nhận Được Nhưng Không Hiển Thị

## **🎯 Vấn Đề**

Quét QR được nhưng **không hiển thị kết quả**.

---

## **✅ BƯỚC 1: Kiểm Tra Console**

1. Vô: `http://localhost/webphim/Trang-admin/index.php?act=scanve_new`
2. Nhấn **F12** → Tab **Console**
3. Quét QR từ vé
4. **Xem console log**

**Dấu hiệu tốt:**
```
📤 Gửi request check-in với mã: [ID]
📥 Response status: 200 true
📥 Raw response: {"success":true,"message":"...","ticket":{...}}
✅ Kiểm tra vé: {success: true, ticket: {...}}
✅ Vé hợp lệ, hiển thị check-in button
```

**Dấu hiệu xấu:**
```
❌ Lỗi: [error message]
❌ Response không success: [data]
❌ JSON parse error: [error]
```

---

## **✅ BƯỚC 2: Dựa Vào Console Log, Giải Quyết**

### **Trường Hợp 1: "JSON parse error"**

**Nguyên nhân:** Backend trả về HTML thay vì JSON

**Giải pháp:**
- Kiểm tra URL có tồn tại không
- Kiểm tra backend có lỗi không (xem error log)
- Kiểm tra database connection

**Cách fix:** Gửi `index.php?act=scanve_check` raw (không qua /webphim/)

### **Trường Hợp 2: "Response không success"**

**Nguyên nhân:** Backend trả về `success: false`

**Xem message:** Console sẽ in ra error từ backend

**Giải pháp dựa trên error:**
- "Vé không tồn tại" → Mã vé sai
- "Chưa đăng nhập" → Session hết hạn
- "Vé chưa thanh toán" → Vé chưa được pay
- Khác → Xem thêm chi tiết

### **Trường Hợp 3: "Không có log gì"**

**Nguyên nhân:** JavaScript không chạy hoặc fetch fail

**Giải pháp:**
- Kiểm tra Network tab (F12 → Network)
- Xem có request POST được gửi không
- Xem response code

---

## **🔧 FIX NHANH**

Nếu console báo lỗi, **copy lỗi** rồi gửi cho admin.

Ví dụ:
> Console log: `❌ JSON parse error: Unexpected token '<' in JSON at position 0`
> 
> Nghĩa là backend trả HTML thay vì JSON, cần fix endpoint

---

## **📝 TEMPLATE FEEDBACK**

```
Browser: Chrome 120
URL: http://localhost/webphim/Trang-admin/index.php?act=scanve_new
Camera: OK (test được)
QR code quét: [screenshot của QR]

Console log:
[paste các log từ console]

Kết quả mong muốn:
[ghi lại kết quả mong muốn]
```

---

**Status:** ✅ Diagnostic Tool Ready
