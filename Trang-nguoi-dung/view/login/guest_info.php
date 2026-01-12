<!-- Form nhập thông tin khách vãng lai -->
<style>
* {
    box-sizing: border-box;
}

.guest-booking-container {
    max-width: 650px;
    margin: 60px auto;
    padding: 0 15px;
}

.guest-info-card {
    background: white;
    border-radius: 25px;
    box-shadow: 0 10px 50px rgba(0,0,0,0.12);
    overflow: hidden;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.guest-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.guest-card-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.guest-card-header::after {
    content: '';
    position: absolute;
    bottom: -30px;
    left: -50px;
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.guest-card-header h2 {
    margin: 0 0 10px 0;
    font-size: 2.2rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.guest-card-header h2 i {
    margin-right: 12px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.guest-card-header p {
    margin: 0;
    font-size: 1.05rem;
    opacity: 0.95;
    position: relative;
    z-index: 1;
}

.guest-card-body {
    padding: 45px 35px;
}

.guest-notice {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe5a1 100%);
    border: 1px solid #ffc107;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 30px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    animation: fadeIn 0.8s ease-out 0.2s both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.guest-notice-icon {
    color: #ff9800;
    font-size: 1.8rem;
    flex-shrink: 0;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.guest-notice-text {
    flex: 1;
}

.guest-notice-text strong {
    display: block;
    color: #856404;
    margin-bottom: 5px;
    font-size: 1.05rem;
}

.guest-notice-text p {
    margin: 0;
    color: #856404;
    font-size: 0.9rem;
    line-height: 1.6;
}

.form-group-guest {
    margin-bottom: 25px;
    animation: fadeIn 0.8s ease-out both;
}

.form-group-guest:nth-child(1) { animation-delay: 0.3s; }
.form-group-guest:nth-child(2) { animation-delay: 0.4s; }
.form-group-guest:nth-child(3) { animation-delay: 0.5s; }

.form-group-guest label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-group-guest label .required {
    color: #e74c3c;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 15px;
    color: #667eea;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 2;
}

.form-control-guest {
    width: 100%;
    padding: 13px 15px 13px 45px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    /* font-size: 1rem; */
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-sizing: border-box;
    background: #f8f9fa;
}

.form-control-guest:hover {
    border-color: #ddd;
    background: white;
}

.form-control-guest:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
    background: white;
}

.form-control-guest.error {
    border-color: #e74c3c;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.error-message {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 6px;
    display: none;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-message.show {
    display: block;
}

.guest-actions {
    display: flex;
    gap: 15px;
    margin-top: 35px;
    animation: fadeIn 0.8s ease-out 0.6s both;
}

.btn-guest {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}

.btn-guest::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-guest:active::before {
    width: 300px;
    height: 300px;
}

.btn-continue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    z-index: 1;
}

.btn-continue:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.45);
}

.btn-continue:active {
    transform: translateY(-1px);
}

.btn-continue.loading {
    opacity: 0.8;
    pointer-events: none;
}

.btn-continue.loading span {
    display: inline-block;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.btn-back {
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
}

.btn-back:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-back:active {
    transform: translateY(0);
}

.login-link-section {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
    text-align: center;
    animation: fadeIn 0.8s ease-out 0.7s both;
}

.login-link-section p {
    color: #6c757d;
    margin: 0 0 12px 0;
    font-size: 0.98rem;
}

.login-link-section a {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.login-link-section a:hover {
    color: #5568d3;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 576px) {
    .guest-booking-container {
        margin: 40px auto;
    }
    
    .guest-card-header {
        padding: 30px 20px;
    }
    
    .guest-card-header h2 {
        font-size: 1.8rem;
    }
    
    .guest-card-body {
        padding: 30px 20px;
    }
    
    .guest-actions {
        flex-direction: column-reverse;
        gap: 12px;
    }
    
    .btn-guest {
        padding: 14px;
    }
}
</style>

<div class="guest-booking-container">
    <div class="guest-info-card">
        <div class="guest-card-header">
            <h2><i class="fa fa-user-circle"></i> Đặt vé nhanh</h2>
            <p>Nhập thông tin của bạn để tiếp tục đặt vé</p>
        </div>
        
        <div class="guest-card-body">
            <?php if (isset($thongbao['guest_error'])): ?>
            <div class="guest-notice" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-color: #f1b0b7;">
                <div class="guest-notice-icon" style="color: #dc3545;">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="guest-notice-text">
                    <strong style="color: #721c24; font-size: 1.05rem;">⚠️ Lỗi</strong>
                    <p style="color: #721c24;"><?= $thongbao['guest_error'] ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="guest-notice">
                <div class="guest-notice-icon">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <div class="guest-notice-text">
                    <strong>✨ Đặt vé không cần đăng ký tài khoản</strong>
                    <p>Thông tin của bạn sẽ được sử dụng để gửi xác nhận vé qua Email. Bạn có thể đăng ký tài khoản sau để quản lý vé dễ dàng hơn.</p>
                </div>
            </div>
            
            <form action="index.php?act=datve2" method="POST" id="guestForm">
                <div class="form-group-guest">
                    <label for="guest_name">
                        <i class="fa fa-user" style="color: #667eea;"></i>
                        Họ và tên<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa fa-user"></i>
                        <input type="text" 
                               class="form-control-guest" 
                               id="guest_name" 
                               name="guest_name" 
                               placeholder="Nhập họ và tên của bạn"
                               required>
                    </div>
                    <span class="error-message" id="error_name">❌ Vui lòng nhập họ và tên (ít nhất 2 ký tự)</span>
                </div>
                
                <div class="form-group-guest">
                    <label for="guest_phone">
                        <i class="fa fa-phone" style="color: #667eea;"></i>
                        Số điện thoại<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa fa-phone"></i>
                        <input type="tel" 
                               class="form-control-guest" 
                               id="guest_phone" 
                               name="guest_phone" 
                               placeholder="VD: 0912345678"
                               pattern="[0-9]{10}"
                               required>
                    </div>
                    <span class="error-message" id="error_phone">❌ Số điện thoại không hợp lệ (10 số bắt đầu từ 0)</span>
                </div>
                
                <div class="form-group-guest">
                    <label for="guest_email">
                        <i class="fa fa-envelope" style="color: #667eea;"></i>
                        Email<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fa fa-envelope"></i>
                        <input type="email" 
                               class="form-control-guest" 
                               id="guest_email" 
                               name="guest_email" 
                               placeholder="Nhập email của bạn"
                               required>
                    </div>
                    <span class="error-message" id="error_email">❌ Email không hợp lệ</span>
                </div>
                
                <div class="guest-actions">
                    <a href="javascript:history.back()" class="btn-guest btn-back">
                        <i class="fa fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <button type="submit" class="btn-guest btn-continue" id="submitBtn">
                        <i class="fa fa-arrow-right"></i>
                        <span>Tiếp tục đặt vé</span>
                    </button>
                </div>
            </form>
            
            <div class="login-link-section">
                <p>👤 Đã có tài khoản?</p>
                <a href="index.php?act=dangnhap">
                    <i class="fa fa-sign-in"></i> Đăng nhập ngay
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('guestForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Validate name
    const name = document.getElementById('guest_name');
    const errorName = document.getElementById('error_name');
    if (name.value.trim().length < 2) {
        name.classList.add('error');
        errorName.classList.add('show');
        isValid = false;
    } else {
        name.classList.remove('error');
        errorName.classList.remove('show');
    }
    
    // Validate phone
    const phone = document.getElementById('guest_phone');
    const errorPhone = document.getElementById('error_phone');
    const phonePattern = /^0[0-9]{9}$/;
    if (!phonePattern.test(phone.value)) {
        phone.classList.add('error');
        errorPhone.classList.add('show');
        isValid = false;
    } else {
        phone.classList.remove('error');
        errorPhone.classList.remove('show');
    }
    
    // Validate email
    const email = document.getElementById('guest_email');
    const errorEmail = document.getElementById('error_email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        email.classList.add('error');
        errorEmail.classList.add('show');
        isValid = false;
    } else {
        email.classList.remove('error');
        errorEmail.classList.remove('show');
    }
    
    if (!isValid) {
        e.preventDefault();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.classList.add('loading');
    submitBtn.innerHTML = '<i class="fa fa-spinner"></i> <span>Đang xử lý...</span>';
});

// Clear error on input
document.querySelectorAll('.form-control-guest').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('error');
        const errorId = 'error_' + this.id.replace('guest_', '');
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.classList.remove('show');
        }
    });
    
    // Add focus effect
    input.addEventListener('focus', function() {
        this.style.background = 'white';
    });
});
</script>

<div class="clearfix"></div>
