<?php
/**
 * HƯỚNG DẪN DEPLOYMENT - SePay Integration Fix
 * 
 * ✅ CÁC FILE ĐÃ ĐƯỢC FIX:
 * 
 * 1. CREATE_TICKET_BEFORE_PAYMENT.php (TẠO MỚI)
 *    - Tạo vé trước khi redirect sang SePay
 *    - Vé được tạo với trang_thai = 0 (chưa thanh toán)
 *    - Trả về ticket_id để dùng làm reference
 * 
 * 2. view/thanhtoan.php (CẬP NHẬT)
 *    - Sửa hàm initiateSepayPayment() từ sync → async
 *    - Gọi CREATE_TICKET_BEFORE_PAYMENT.php trước khi redirect
 *    - Chỉ redirect khi tickets đã được tạo thành công
 * 
 * 3. sepay/sepay_webhook.php (GIỮ NGUYÊN - Hoạt động đúng)
 *    - Tìm vé theo ID từ nội dung thanh toán (VE123)
 *    - Cập nhật trang_thai = 1 khi nhận webhook từ SePay
 * 
 * ====================================================
 * FLOW THANH TOÁN SEPAY (FIXED)
 * ====================================================
 * 
 * 1. KHÁCH CLICK "SEPAY"
 *    ↓
 *    initiateSepayPayment() được gọi
 *
 * 2. GỌI CREATE_TICKET_BEFORE_PAYMENT.php
 *    ↓
 *    - Tạo VÉ với trang_thai = 0
 *    - Trả về ticket_ids: [123, 124, 125]
 *    - Trả về redirect_url
 *
 * 3. REDIRECT SANG SEPAY PAYMENT UI
 *    ↓
 *    sepay_payment_ui.php?ticket_id=123&amount=200000
 *    ↓
 *    Hiển thị QR code với nội dung: "VE123"
 *
 * 4. KHÁCH QUÉT QR + CHUYỂN KHOẢN
 *    ↓
 *    SePay ghi nhận giao dịch
 *
 * 5. SEPAY WEBHOOK TRIGGER
 *    ↓
 *    POST /Trang-nguoi-dung/sepay/sepay_webhook.php
 *    ↓
 *    - Tách ID từ content: "VE123" → 123
 *    - Tìm vé ID 123 trong DB
 *    - Cập nhật trang_thai = 1 ✓
 *    - Gửi email xác nhận
 *
 * ====================================================
 * TESTING INSTRUCTIONS
 * ====================================================
 * 
 * 1. Kiểm tra webhook được trigger:
 *    - Xem file: /Trang-nguoi-dung/sepay/webhook_logs.txt
 *    - Nên có log: "Payment Confirmed: CINEMA_... | Amount: 200000"
 *
 * 2. Kiểm tra ticket được tạo:
 *    SELECT * FROM ve WHERE trang_thai = 1 AND ghi_chu LIKE 'SePay%'
 *    - Nên có tickets với status = 1
 *
 * 3. Kiểm tra email gửi:
 *    - Xem file: /Trang-nguoi-dung/logs/email_log.txt
 *    - Nên có log: "Status: SUCCESS"
 *
 * ====================================================
 * DEBUGGING CHECKLIST
 * ====================================================
 * 
 * ❌ Khách quét QR nhưng không thấy kết quả:
 *    → Check webhook_logs.txt - SePay có gọi webhook không?
 *    → Check database - ve.trang_thai có = 1 không?
 *    
 * ❌ Vé bị mất sau khi thanh toán:
 *    → Check payment_creation.log - tickets có được tạo không?
 *    → Check webhook_logs.txt - có error message không?
 *    
 * ❌ Webhook bị lỗi:
 *    → Check SEPAY_WEBHOOK_URL trong config.php
 *    → Phải là public URL: https://webphim.online/...
 *    → Test bằng curl: curl -X POST https://webphim.online/.../sepay_webhook.php
 *
 * ====================================================
 * SEPAY WEBHOOK CONFIGURATION (ADMIN PANEL)
 * ====================================================
 * 
 * URL: https://webphim.online/Trang-nguoi-dung/sepay/sepay_webhook.php
 * Mỗi lần thanh toán thành công, SePay sẽ POST dữ liệu tới URL này
 * 
 * POST body:
 * {
 *   "gateway": "NAPAS",
 *   "transactionDate": "2025-12-08 14:30:00",
 *   "accountNumber": "0384104942",
 *   "transferAmount": 200000,
 *   "transferType": "in",
 *   "content": "VE123 - Galaxy Studio Movie",
 *   "referenceCode": "123456789",
 *   ...
 * }
 * 
 * ====================================================
 * IMPORTANT FILES MODIFIED
 * ====================================================
 * 
 * webphim_deploy/Trang-nguoi-dung/
 * ├── view/thanhtoan.php (UPDATED - initiateSepayPayment)
 * ├── api_create_vietqr_payment.php (UPDATED - lưu order trước)
 * ├── vietqr_return.php (UPDATED - fix user_id session)
 * └── sepay/
 *     ├── CREATE_TICKET_BEFORE_PAYMENT.php (NEW)
 *     └── sepay_webhook.php (REVIEWED - OK)
 * 
 * ====================================================
 */

echo "✅ Deployment Instructions Created\n";
echo "📝 Chi tiết xem file này\n";
?>
