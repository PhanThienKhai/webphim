# 🔤 HƯỚNG DẪN FONT CHO TRANG ADMIN

## ✅ ĐÃ THAY ĐỔI

Tất cả font **"Open Sans"** đã được thay thế bằng **"Inter"** - font hiện đại nhất 2025

### 📁 CÁC FILE ĐÃ CẬP NHẬT:

1. ✅ `Trang-admin/assets/css/style.css`
2. ✅ `Trang-admin/assets/css/style-primary.css`  
3. ✅ `Trang-admin/assets/css/admin-override.css`

---

## 🎯 VỊ TRÍ ĐIỀU CHỈNH KÍCH THƯỚC TRANG

### **1. Zoom toàn trang (Đơn giản nhất)**

📍 **File:** `Trang-admin/assets/css/admin-override.css`

**Dòng ~530:** Thêm vào cuối file
```css
/* ========== THU NHỎ TOÀN TRANG ========== */
body {
    zoom: 0.85; /* Điều chỉnh: 0.7 (nhỏ nhất) - 1.0 (gốc) */
}

/* Giảm khoảng cách */
.content-body {
    padding: 12px !important;
}

.card, .table-wrapper {
    margin-bottom: 15px !important;
}
```

### **2. Điều chỉnh trong file style.css**

📍 **File:** `Trang-admin/assets/css/style.css`

**Dòng ~88:** Đã có sẵn zoom
```css
body {
  background-color: #ffffff;
  font-size: 14px;
  line-height: 1.75;
  font-style: normal;
  font-weight: normal;
  visibility: visible;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: #666666;
  position: relative;
  zoom: 0.9; /* ← CHỈNH ĐÂY: 0.7-1.0 */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
```

---

## 🎨 CÁC GIÁ TRỊ ZOOM GỢI Ý

| Zoom  | Kích thước | Phù hợp cho |
|-------|------------|-------------|
| 0.70  | 70% - Rất nhỏ | Màn hình nhỏ 13-14 inch |
| 0.80  | 80% - Nhỏ | Laptop 15 inch |
| 0.85  | 85% - Hơi nhỏ | **← KHUYẾN NGHỊ** |
| 0.90  | 90% - Vừa | Màn hình lớn |
| 1.00  | 100% - Gốc | Màn hình rất lớn |

---

## 📝 CÁCH CHỈNH NHANH

### **Phương án A: Chỉnh trong style.css (Toàn trang admin)**
```css
/* File: Trang-admin/assets/css/style.css - Dòng ~88 */
body {
  zoom: 0.85; /* Thay đổi giá trị này */
}
```

### **Phương án B: Chỉnh trong admin-override.css (Ưu tiên cao nhất)**
```css
/* File: Trang-admin/assets/css/admin-override.css - Cuối file */
body {
    zoom: 0.8 !important; /* Thêm !important để override */
}
```

---

## 🔧 CÁCH SỬ DỤNG

1. **Mở file CSS** bạn muốn chỉnh
2. **Tìm dòng** `zoom: 0.9;` 
3. **Thay đổi giá trị** từ 0.7 đến 1.0
4. **Lưu file** (Ctrl + S)
5. **Refresh browser** (Ctrl + F5)

---

## ✨ FONT INTER ĐÃ ĐƯỢC IMPORT TỪ:

```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
```

**Font weights có sẵn:**
- 300 - Light
- 400 - Regular (Mặc định)
- 500 - Medium  
- 600 - SemiBold
- 700 - Bold
- 800 - ExtraBold

---

## 🎯 CÁC VỊ TRÍ QUAN TRỌNG

### **1. Tiêu đề (Headings)**
```css
/* admin-override.css - Dòng ~43 */
h1, h2, h3, .page-heading {
    font-weight: 700 !important;
    letter-spacing: -0.02em !important;
}
```

### **2. Nội dung chữ thường**
```css
/* admin-override.css - Dòng ~53 */
body, p, span, div, td, th {
    font-weight: 400 !important;
}
```

### **3. Buttons & Badges**
```css
/* admin-override.css - Dòng ~49 */
.btn, .badge, .nav-link {
    font-weight: 500 !important;
}
```

---

## 🚀 NÂNG CAO

### **Thu nhỏ responsive theo màn hình:**

```css
/* Thêm vào admin-override.css */
@media screen and (min-width: 1024px) and (max-width: 1600px) {
    body {
        zoom: 0.85;
    }
}

@media screen and (max-width: 1023px) {
    body {
        zoom: 0.8;
    }
}
```

---

## ⚠️ LƯU Ý

- **Luôn backup** file CSS trước khi chỉnh
- **Refresh với Ctrl + F5** để clear cache
- **Kiểm tra trên nhiều trình duyệt** (Chrome, Firefox, Edge)
- **Zoom < 0.7** có thể làm chữ quá nhỏ, khó đọc
- **Zoom > 1.0** sẽ phóng to, có thể tràn màn hình

---

## 📞 HỖ TRỢ

Nếu gặp vấn đề:
1. Kiểm tra file CSS có được load không (F12 > Network)
2. Clear cache browser (Ctrl + Shift + Delete)
3. Kiểm tra Console có lỗi không (F12 > Console)

---

**📅 Cập nhật:** October 1, 2025  
**✍️ Font:** Inter  
**🎨 Zoom mặc định:** 0.9 (trong style.css)
