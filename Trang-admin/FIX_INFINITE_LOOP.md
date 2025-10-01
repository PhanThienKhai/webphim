# 🐛 Sửa Lỗi: Maximum Call Stack Size Exceeded

## ❌ Lỗi ban đầu

```
Uncaught RangeError: Maximum call stack size exceeded
at openAssignModal
```

### Nguyên nhân:
Code có **2 định nghĩa function `openAssignModal`**:

```javascript
// Định nghĩa thứ 1 (dòng ~1492)
function openAssignModal(date) {
    // Logic cũ...
}

// Định nghĩa thứ 2 (dòng ~2077) - SAI!
const originalOpenModal = openAssignModal;
function openAssignModal(date) {
    originalOpenModal(date);  // ← Gọi lại chính nó → VÔ HẠN!
    // Logic mới...
}
```

**Kết quả:** Vòng lặp vô hạn → Stack overflow!

---

## ✅ Cách sửa

### Bước 1: Gộp logic vào function duy nhất

Thay vì override, tôi đã **cập nhật trực tiếp** function `openAssignModal` gốc:

```javascript
function openAssignModal(date) {
    console.log('Opening modal for date:', date);
    const modal = document.getElementById('assignModal');
    if (modal) {
        // ✅ Logic cũ (giữ nguyên)
        const alerts = document.querySelectorAll('.alert-danger, .alert-success, .alert-warning');
        alerts.forEach(alert => alert.remove());
        
        document.getElementById('assignDate').value = date;
        document.getElementById('displayDate').value = date;
        
        // ✅ Logic mới (thêm vào)
        // Reset date mode to single
        const singleModeRadio = document.querySelector('input[name="dateMode"][value="single"]');
        if (singleModeRadio) singleModeRadio.checked = true;
        
        const singleSection = document.getElementById('singleDateSection');
        const rangeSection = document.getElementById('rangeDateSection');
        if (singleSection) singleSection.style.display = 'block';
        if (rangeSection) rangeSection.style.display = 'none';
        
        // Set start and end dates for range mode
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        if (startDateInput) startDateInput.value = date;
        if (endDateInput) endDateInput.value = date;
        
        // Reset shifts
        document.querySelectorAll('.shift-template-check').forEach(ch => ch.checked = false);
        customShifts = [];
        renderCustomShifts();
        
        // Reset custom shift form
        const customSection = document.getElementById('customShiftSection');
        const toggleBtn = document.getElementById('toggleCustomShift');
        if (customSection) customSection.style.display = 'none';
        if (toggleBtn) toggleBtn.textContent = '➕ Thêm ca tùy chỉnh';
        
        // ✅ Logic cũ tiếp tục
        modal.style.display = 'flex';
        modal.style.opacity = '0';
        modal.offsetHeight;
        modal.style.transition = 'opacity 0.3s ease';
        modal.style.opacity = '1';
        
        updateSelectedDisplay();
        
        // ✅ Logic mới
        if (typeof updateSummary === 'function') {
            updateSummary();
        }
        
        // ✅ Logic cũ
        document.body.style.overflow = 'hidden';
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAssignModal();
            }
        });
    }
}
```

### Bước 2: Xóa định nghĩa duplicate

Đã xóa phần code sai ở cuối file (dòng 2077-2102).

---

## 🔍 Kiểm tra

### Trước khi sửa:
```powershell
PS> Select-String "function openAssignModal"
# Kết quả: 2 matches (SAI!)
```

### Sau khi sửa:
```powershell
PS> Select-String "function openAssignModal"
# Kết quả: 1 match (ĐÚNG!)
```

---

## 🎯 Kết quả

✅ **Không còn vòng lặp vô hạn**  
✅ **Modal mở được bình thường**  
✅ **Tất cả tính năng mới hoạt động**  
✅ **Không ảnh hưởng tính năng cũ**  

---

## 💡 Bài học

### ❌ Sai lầm:
```javascript
// SAI: Override function bằng cách gọi lại chính nó
const originalFunc = myFunc;
function myFunc() {
    originalFunc();  // Vòng lặp!
}
```

### ✅ Đúng:
```javascript
// ĐÚNG: Cập nhật trực tiếp function gốc
function myFunc() {
    // Logic cũ + Logic mới
}
```

Hoặc nếu thực sự cần override:
```javascript
// ĐÚNG: Lưu reference trước khi override
const _originalFunc = myFunc;
myFunc = function() {
    _originalFunc.apply(this, arguments);
    // Logic mới
}
```

---

## 📝 Checklist test

Sau khi sửa, test các trường hợp:

- [x] Click vào ngày → Modal mở
- [x] Modal hiển thị đúng ngày đã click
- [x] Nhân viên đã chọn hiển thị trong modal
- [x] Chọn chế độ "Ngày đơn" → OK
- [x] Chọn chế độ "Khoảng thời gian" → OK
- [x] Chọn ca có sẵn → OK
- [x] Thêm ca tùy chỉnh → OK
- [x] Tổng quan hiển thị đúng → OK
- [x] Lưu phân công → OK
- [x] Không có lỗi console → OK

---

**Trạng thái:** ✅ ĐÃ SỬA XONG  
**Ngày sửa:** 1 Tháng 10, 2025  
**File:** `lichlamviec_calendar.php`
