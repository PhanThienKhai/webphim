<!-- Form nhập thông tin khách vãng lai -->
<style>
.guest-booking-container {
    max-width: 600px;
    margin: 60px auto;
    padding: 0 15px;
}

.guest-info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.guest-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    text-align: center;
}

.guest-card-header h2 {
    margin: 0 0 10px 0;
    font-size: 2rem;
    font-weight: 700;
}

.guest-card-header p {
    margin: 0;
    font-size: 1rem;
    opacity: 0.95;
}

.guest-card-body {
    padding: 40px 30px;
}

.guest-notice {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.guest-notice-icon {
    color: #ff9800;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.guest-notice-text {
    flex: 1;
}

.guest-notice-text strong {
    display: block;
    color: #856404;
    margin-bottom: 5px;
}

.guest-notice-text p {
    margin: 0;
    color: #856404;
    font-size: 0.9rem;
    line-height: 1.5;
}

.form-group-guest {
    margin-bottom: 20px;
}

.form-group-guest label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group-guest label .required {
    color: #e74c3c;
    margin-left: 3px;
}

.form-control-guest {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
    box-sizing: border-box;
}

.form-control-guest:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control-guest.error {
    border-color: #e74c3c;
}

.error-message {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 5px;
    display: none;
}

.error-message.show {
    display: block;
}

.guest-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-guest {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-continue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-continue:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
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
}

.login-link-section {
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e9ecef;
    text-align: center;
}

.login-link-section p {
    color: #6c757d;
    margin: 0 0 10px 0;
    font-size: 0.95rem;
}

.login-link-section a {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s;
}

.login-link-section a:hover {
    color: #5568d3;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 576px) {
    .guest-card-header {
        padding: 25px 20px;
    }
    
    .guest-card-header h2 {
        font-size: 1.5rem;
    }
    
    .guest-card-body {
        padding: 30px 20px;
    }
    
    .guest-actions {
        flex-direction: column-reverse;
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
            <div class="guest-notice" style="background: #f8d7da; border-color: #f5c6cb;">
                <div class="guest-notice-icon" style="color: #dc3545;">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="guest-notice-text">
                    <strong style="color: #721c24;">Lỗi</strong>
                    <p style="color: #721c24;"><?= $thongbao['guest_error'] ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="guest-notice">
                <div class="guest-notice-icon">
                    <i class="fa fa-info-circle"></i>
                </div>
                <div class="guest-notice-text">
                    <strong>Đặt vé không cần đăng ký tài khoản</strong>
                    <p>Thông tin của bạn sẽ được sử dụng để gửi xác nhận vé qua Email/SMS. Bạn có thể đăng ký tài khoản sau để quản lý vé dễ dàng hơn.</p>
                </div>
            </div>
            
            <form action="index.php?act=datve2" method="POST" id="guestForm">
                <div class="form-group-guest">
                    <label for="guest_name">
                        Họ và tên<span class="required">*</span>
                    </label>
                    <input type="text" 
                           class="form-control-guest" 
                           id="guest_name" 
                           name="guest_name" 
                           placeholder="Nhập họ và tên của bạn"
                           required>
                    <span class="error-message" id="error_name">Vui lòng nhập họ và tên</span>
                </div>
                
                <div class="form-group-guest">
                    <label for="guest_phone">
                        Số điện thoại<span class="required">*</span>
                    </label>
                    <input type="tel" 
                           class="form-control-guest" 
                           id="guest_phone" 
                           name="guest_phone" 
                           placeholder="Nhập số điện thoại (VD: 0912345678)"
                           pattern="[0-9]{10}"
                           required>
                    <span class="error-message" id="error_phone">Số điện thoại không hợp lệ (10 số)</span>
                </div>
                
                <div class="form-group-guest">
                    <label for="guest_email">
                        Email<span class="required">*</span>
                    </label>
                    <input type="email" 
                           class="form-control-guest" 
                           id="guest_email" 
                           name="guest_email" 
                           placeholder="Nhập email của bạn"
                           required>
                    <span class="error-message" id="error_email">Email không hợp lệ</span>
                </div>
                
                <div class="guest-actions">
                    <a href="javascript:history.back()" class="btn-guest btn-back">
                        <i class="fa fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <button type="submit" class="btn-guest btn-continue">
                        <i class="fa fa-arrow-right"></i>
                        Tiếp tục đặt vé
                    </button>
                </div>
            </form>
            
            <div class="login-link-section">
                <p>Đã có tài khoản?</p>
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
    }
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
});
</script>

<div class="clearfix"></div>
