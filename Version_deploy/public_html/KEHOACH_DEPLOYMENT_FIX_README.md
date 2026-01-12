# 🎬 Khắc Phục Vấn Đề Kế Hoạch Chiếu - Deployment

## Vấn Đề Tìm Thấy

Khi deploy lên server, có 2 vấn đề chính:

### 1. ❓ Status Kế Hoạch Hiển Thị "?" Thay Vì Trạng Thái Thực
**Nguyên Nhân:**
- Cột `trang_thai_duyet` trong bảng `lichchieu` chứa giá trị NULL hoặc rỗng
- Localhost có dữ liệu cũ hoặc được update, còn server chưa cập nhật

**Triệu Chứng:**
- Khi xem danh sách kế hoạch → status hiển thị ❓ thay vì ⏳ (Chờ duyệt), ✅ (Đã duyệt), hay ❌ (Từ chối)

**Giải Pháp:**
- Cập nhật code để default status = 'Chờ duyệt' khi NULL
- Chạy SQL patch để cập nhật database

---

### 2. 🔐 Quản Lý Cụm Không Thấy Kế Hoạch Để Duyệt
**Nguyên Nhân:**
- Function `lc_list_grouped_for_approval()` không filtrì theo `id_cum`
- Nó lấy tất cả kế hoạch từ mọi rạp, không check xem rạp đó thuộc cum của người dùng không
- Quản lý cụm A chỉ nên thấy kế hoạch từ các rạp trong cum A

**Triệu Chứng:**
- Quản lý cụm click vào "Duyệt kế hoạch chiếu" → không hiện kế hoạch (hoặc hiện kế hoạch của cụm khác)

**Giải Pháp:**
- Cập nhật function `lc_list_grouped_for_approval($filter, $id_cum)` để filtrì theo cum
- Truyền `$_SESSION['user1']['id_cum']` vào function

---

## Các File Đã Sửa

### 1️⃣ `Trang-admin/view/kehoachphim/kehoach.php`
**Dòng 258** - Xử lý status display:

```php
// Fix: Default to 'Chờ duyệt' if NULL or empty
$status = trim($kh['trang_thai_duyet'] ?? '');
if (empty($status)) {
    $status = 'Chờ duyệt';
}

// Thêm icon cho status
$icons = [
    'Chờ duyệt' => '⏳',
    'Đã duyệt' => '✅', 
    'Từ chối' => '❌'
];

echo ($icons[$status] ?? '?') . ' ' . htmlspecialchars($status);
```

**Thay Đổi:**
- ✅ Default NULL → 'Chờ duyệt'
- ✅ Thêm icon emoji cho status
- ✅ Bảo vệ XSS với `htmlspecialchars()`

---

### 2️⃣ `Trang-admin/model/lichchieu.php`
**Dòng 257** - Function `lc_list_grouped_for_approval()`:

**Trước:**
```php
function lc_list_grouped_for_approval($filter = 'cho_duyet') {
    // Không có tham số $id_cum
    // Lấy tất cả kế hoạch từ mọi rạp
}
```

**Sau:**
```php
function lc_list_grouped_for_approval($filter = 'cho_duyet', $id_cum = null) {
    $where_parts = [];
    
    // Lọc status
    if ($filter === 'cho_duyet') {
        $where_parts[] = "(lc.trang_thai_duyet = 'Chờ duyệt' OR lc.trang_thai_duyet IS NULL)";
    }
    
    // FIX: Filtrì theo cum
    if (!empty($id_cum)) {
        $where_parts[] = "r.id_cum = ?";
        $params[] = $id_cum;
    }
    
    // SELECT IFNULL(lc.trang_thai_duyet, 'Chờ duyệt') - Default NULL
}
```

**Thay Đổi:**
- ✅ Thêm tham số `$id_cum`
- ✅ Filtrì `WHERE r.id_cum = ?` để chỉ show kế hoạch của cum hiện tại
- ✅ Sử dụng `IFNULL()` để default NULL → 'Chờ duyệt'
- ✅ Hỗ trợ NULL values trong WHERE clause

---

### 3️⃣ `Trang-admin/index.php`
**Dòng 2326** - Gọi function với id_cum:

**Trước:**
```php
$filter = $_GET['filter'] ?? 'cho_duyet';
$ds_lich = lc_list_grouped_for_approval($filter);
```

**Sau:**
```php
$filter = $_GET['filter'] ?? 'cho_duyet';
$id_cum = $_SESSION['user1']['id_cum'] ?? null;  // ← Lấy cum của user
$ds_lich = lc_list_grouped_for_approval($filter, $id_cum);
```

**Thay Đổi:**
- ✅ Lấy `id_cum` từ session
- ✅ Truyền vào function để filtrì dữ liệu

---

### 4️⃣ `DEPLOYMENT_PATCH_KEHOACH.sql`
**File SQL để chạy trên database deploy:**

```sql
-- Cập nhật NULL trang_thai_duyet
UPDATE lichchieu 
SET trang_thai_duyet = 'Chờ duyệt'
WHERE trang_thai_duyet IS NULL OR trang_thai_duyet = '';

-- Đặt DEFAULT
ALTER TABLE lichchieu 
MODIFY COLUMN trang_thai_duyet VARCHAR(50) NOT NULL DEFAULT 'Chờ duyệt';
```

---

## Hướng Dẫn Deploy

### Bước 1: Cập Nhật Code
Copy các file đã sửa vào server:
```
Trang-admin/view/kehoachphim/kehoach.php
Trang-admin/model/lichchieu.php
Trang-admin/index.php
```

### Bước 2: Chạy SQL Patch
```bash
# SSH vào server
mysql -u root -p cinepass < DEPLOYMENT_PATCH_KEHOACH.sql
```

Hoặc import file SQL qua phpMyAdmin.

### Bước 3: Kiểm Tra Kết Quả
1. Đăng nhập Admin → Quản lý Rạp
2. Click "Lập Kế Hoạch Chiếu Mới"
3. Status phải hiển thị ⏳ (không phải ❓)
4. Đăng nhập Quản Lý Cụm
5. Click "Duyệt Kế Hoạch Chiếu"
6. Phải thấy kế hoạch từ các rạp trong cum của bạn

---

## Các Bảng Liên Quan

```
lichchieu
├── id (PK)
├── ma_ke_hoach
├── id_phim
├── id_rap
├── ngay_chieu
├── trang_thai_duyet ← Cần cập nhật (NULL → 'Chờ duyệt')
├── ghi_chu
├── nguoi_tao
└── ngay_tao

rap_chieu
├── id (PK)
├── ten_rap
├── id_cum ← Để filtrì kế hoạch theo cum
└── ...
```

---

## Ghi Chú Quan Trọng

### Trên Localhost (Có Thể Không Cần Làm)
- Database có dữ liệu cũ từ khi phát triển
- Các record cũ có thể đã set status = 'Chờ duyệt'
- Không cần chạy SQL patch

### Trên Server Mới (PHẢI Làm)
- Database được restore từ file SQL cũ
- Những record cũ có NULL `trang_thai_duyet`
- **PHẢI** chạy `DEPLOYMENT_PATCH_KEHOACH.sql`

---

## Troubleshooting

### Status vẫn hiển thị "?"
```sql
-- Kiểm tra database
SELECT id, ma_ke_hoach, trang_thai_duyet, COUNT(*) as cnt
FROM lichchieu 
GROUP BY ma_ke_hoach;

-- Nếu vẫn có NULL, chạy:
UPDATE lichchieu SET trang_thai_duyet = 'Chờ duyệt' WHERE trang_thai_duyet IS NULL;
```

### Quản lý cụm vẫn không thấy kế hoạch
```php
// Kiểm tra trong index.php, dòng 2326
error_log("ID_CUM: " . var_export($_SESSION['user1']['id_cum'], true));
```

Kiểm tra logs để xem `id_cum` có được truyền không.

---

## Thông Tin Liên Hệ

Nếu có lỗi khác, check logs:
- `Trang-admin/logs/php-error.log` - PHP errors
- `Trang-admin/logs/database.log` - Database errors
- Browser DevTools Console - JavaScript errors

---
