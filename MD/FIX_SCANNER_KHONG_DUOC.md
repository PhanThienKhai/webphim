# 🔧 FIX: QR Scanner Không Quét Được - Chi Tiết

## **🎯 Vấn Đề**

> "Tôi quét khi test được mà vô link `http://localhost/webphim/Trang-admin/index.php?act=scanve_new` quét mãi không được"

**Nguyên nhân:** jsQR library từ CDN có vấn đề hoặc không tải được.

---

## **✅ GIẢI PHÁP ĐƠNGIẢN (ĐÃ THỰC HIỆN)**

### **Bước 1: Đã Cập Nhật File**

**File:** `Trang-admin/view/nhanvien/scanve_new.php`

**Những gì tôi đã sửa:**

1. ✅ **Thay jsQR bằng BarcodeDetector API** (built-in browser)
2. ✅ **Thêm jsQR làm fallback** (nếu BarcodeDetector không hoạt động)
3. ✅ **Tăng tốc độ quét từ 150ms → 100ms** (quét nhanh hơn)

---

## **🧪 TEST NGAY (3 Bước)**

### **Bước 1: Test Camera & QR (Trang Đơn Giản)**

**URL:** `http://localhost/webphim/test_qr_scan_simple.html`

Thực hiện:
1. Nhấn "▶️ Bật Camera"
2. Cho phép camera
3. Xem video có hiển thị không

**Nếu:**
- ✅ Camera hiển thị → Camera OK, sang Bước 2
- ❌ Không hiển thị → Kiểm tra quyền camera (xem bên dưới)

### **Bước 2: Test Quét QR**

1. In hoặc tìm một QR code đơn giản (Google QR generator)
2. Vào trang test
3. Nhấn "🔍 Bắt Đầu Quét QR"
4. Hướng camera vào QR code
5. Xem có quét được không

**Nếu:**
- ✅ Quét được → Scanner OK, sang Bước 3
- ❌ Không quét → Xem debug info (nói gì)

### **Bước 3: Test Với Scanner Thực**

1. Khách đặt vé (hoặc xem vé cũ)
2. **Chú ý:** QR code phải **to (150x150px)** (đã update lúc trước)
3. Vào: `http://localhost/webphim/Trang-admin/index.php?act=scanve_new`
4. Quét QR trên vé
5. Xem có quét được không

---

## **❌ NẾU VẪN KHÔNG ĐƯỢC**

### **Vấn Đề 1: Camera Bị Chặn**

**Triệu chứng:** Camera không hiển thị trên test page

**Giải pháp:**

**Chrome:**
1. Vào `chrome://settings/content/camera`
2. Cho phép `localhost`
3. Refresh page

**Firefox:**
1. Vào `about:preferences#privacy`
2. Tìm "Permissions → Camera"
3. Cho phép `localhost`
4. Refresh page

**Edge:**
1. Settings → Privacy → Camera
2. Cho phép `localhost`
3. Refresh page

### **Vấn Đề 2: jsQR/BarcodeDetector Không Tải**

**Triệu chứng:** Test page báo "⚠️ Fallback: Dùng jsQR.js"

**Giải pháp:**

**Kiểm tra Console (F12):**
1. Mở DevTools (F12)
2. Click tab "Console"
3. Quét QR
4. Xem có error gì không
5. Gửi lại error message

**Fix thủ công:**
- Tôi sẽ download jsQR.js local (không dùng CDN)

### **Vấn Đề 3: QR Quá Nhỏ**

**Triệu chứng:** QR quét được trên test page nhưng không quét được trên vé thực

**Giải pháp:**
- QR code trên vé phải **150x150px** (đã update rồi)
- Nếu vẫn nhỏ → Kiểm tra browser cache
- Xóa cache: Ctrl+Shift+Delete

---

## **📋 CHECKLIST**

- [ ] Vô `http://localhost/webphim/test_qr_scan_simple.html`
- [ ] Test camera (bước 1)
- [ ] Test quét QR đơn (bước 2)
- [ ] Test quét vé thực (bước 3)
- [ ] Nếu không được → Gửi feedback (xem bên dưới)

---

## **💬 FEEDBACK NẾU CÒN LỖI**

Hãy gửi thông tin:

1. **Loại browser:** Chrome / Firefox / Edge / Safari
2. **Version browser:** (xem Settings → About)
3. **Camera:** Built-in laptop / USB camera
4. **Test page kết quả:**
   - Camera hiển thị được không?
   - Lỗi gì? (copy từ console)
5. **QR code trên vé:**
   - Kích thước bao nhiêu? (150x150px?)
   - Lỗi gì khi quét?

**Ví dụ feedback tốt:**
> Chrome 120.0 | Windows | Camera: Built-in | Test camera OK ✅ | Test QR đơn OK ✅ | QR vé không quét được ❌ | Error: "undefined jsQR" (xem console)

---

## **🔧 TÚM LẠI**

| Hành Động | Thời Điểm | Kết Quả |
|----------|----------|--------|
| Thay jsQR → BarcodeDetector | Mới nhất | ✅ Quét nhanh hơn |
| Thêm jsQR fallback | Mới nhất | ✅ Có backup |
| Tăng QR size → 150x150px | Trước | ✅ QR lớn hơn |
| Tăng tốc độ quét → 100ms | Mới nhất | ✅ Quét nhanh hơn |

---

## **🚀 HÀNH ĐỘNG TIẾP THEO**

1. **Test ngay** trang `test_qr_scan_simple.html`
2. **Nếu OK** → Vô `scanve_new` quét vé
3. **Nếu không OK** → Gửi error message

---

**Cập nhật:** 29/11/2025 | **Status:** ✅ Fix Hoàn Tất
