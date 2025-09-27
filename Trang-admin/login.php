<?php
session_start();
include "model/pdo.php";
include "model/taikhoan.php";

if (isset($_POST['dangnhap']) && ($_POST['dangnhap'])) {
    $user = trim($_POST['user'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    $user_info = check_tk($user, $pass);

    if ($user_info && in_array((int)$user_info['vai_tro'], [1,2,3,4], true)) {
        $_SESSION['user1'] = $user_info;
        header('location: index.php?act=home');
        exit;
    }

    $loi = "Sai tài khoản hoặc mật khẩu";
}
?>
<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from demo.hasthemes.com/adomx-preview/light/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Nov 2023 02:47:53 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Custom Style CSS Only For Demo Purpose -->
    <link id="cus-style" rel="stylesheet" href="assets/css/style-primary.css">
    <link rel="stylesheet" href="assets/css/admin-custom.css">
    
    <!-- Animate.css for smooth animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body class="login-page">

<!-- 🎬 Login Background with Cinema Theme -->
<div class="login-wrapper">
    <!-- Background Video/Animation -->
    <div class="login-bg">
        <div class="cinema-lights">
            <div class="light"></div>
            <div class="light"></div>
            <div class="light"></div>
            <div class="light"></div>
        </div>
        <div class="film-strip">
            <div class="film-hole"></div>
            <div class="film-hole"></div>
            <div class="film-hole"></div>
            <div class="film-hole"></div>
        </div>
    </div>
    
    <!-- Modern Login Card -->
    <div class="login-container">
        <div class="login-card">
            <!-- Header Section -->
            <div class="login-header">
                <div class="brand-logo">
                    <div class="logo-circle">
                        <i class="zmdi zmdi-movie"></i>
                    </div>
                    <h1>Galaxy Studio</h1>
                    <p class="brand-subtitle">Cinema Management System</p>
                </div>
                <div class="login-title">
                    <h2>Đăng nhập Admin</h2>
                    <p>Truy cập hệ thống quản lý rạp chiếu</p>
                </div>
            </div>

            <!-- Error Alert -->
            <?php if(isset($loi) && ($loi)!=""): ?>
                <div class="alert alert-error animate__animated animate__shakeX">
                    <i class="zmdi zmdi-alert-triangle"></i>
                    <span><?= htmlspecialchars($loi) ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" class="login-form">
                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="zmdi zmdi-account input-icon"></i>
                        <input 
                            class="form-input" 
                            type="text" 
                            name="user" 
                            placeholder="Tên đăng nhập"
                            autocomplete="username"
                            required
                        >
                        <span class="input-line"></span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="zmdi zmdi-lock input-icon"></i>
                        <input 
                            class="form-input" 
                            name="pass" 
                            type="password" 
                            placeholder="Mật khẩu"
                            autocomplete="current-password"
                            required
                        >
                        <span class="input-line"></span>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="zmdi zmdi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-login" name="dangnhap" type="submit" value="1">
                        <span class="btn-text">Đăng nhập</span>
                        <span class="btn-loading">
                            <i class="zmdi zmdi-refresh zmdi-hc-spin"></i>
                        </span>
                    </button>
                </div>

                <!-- Additional Options -->
                <div class="login-options">
                    <div class="forgot-password">
                        <a href="#" onclick="showForgotPasswordAlert()">
                            <i class="zmdi zmdi-help"></i>
                            Quên mật khẩu?
                        </a>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <div class="footer-links">
                    <span>© 2025 Galaxy Studio</span>
                    <span>•</span>
                    <a href="#" onclick="showSupportInfo()">Hỗ trợ</a>
                    <span>•</span>
                    <a href="#" onclick="showSecurityInfo()">Bảo mật</a>
                </div>
                <div class="version-info">
                    <small>Version 2.1.0</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 🔐 Password Toggle Function
function togglePassword() {
    const passwordInput = document.querySelector('input[name="pass"]');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'zmdi zmdi-eye-off';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'zmdi zmdi-eye';
    }
}

// 🎬 Interactive Functions
function showForgotPasswordAlert() {
    alert('Liên hệ quản trị viên hệ thống để đặt lại mật khẩu.\nEmail: admin@galaxystudio.vn\nHotline: 1900-2025');
}

function showSupportInfo() {
    alert('Hỗ trợ kỹ thuật 24/7\nHotline: 1900-2025\nEmail: support@galaxystudio.vn');
}

function showSecurityInfo() {
    alert('Hệ thống được bảo mật với:\n• Mã hóa SSL/TLS\n• Xác thực 2 lớp\n• Giám sát 24/7');
}

// ✨ Form Animation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.login-form');
    const inputs = document.querySelectorAll('.form-input');
    
    // Input focus animations
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentNode.classList.remove('focused');
            }
        });
        
        // Check if input has value on load
        if (input.value !== '') {
            input.parentNode.classList.add('focused');
        }
    });
    
    // Login button loading effect
    form.addEventListener('submit', function() {
        const btn = document.querySelector('.btn-login');
        btn.classList.add('loading');
        
        setTimeout(() => {
            btn.classList.remove('loading');
        }, 3000);
    });
});
</script>

<!-- JS
============================================ -->

<!-- Global Vendor, plugins & Activation JS -->
<script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/popper.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>
<!--Plugins JS-->
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/plugins/tippy4.min.js.js"></script>
<!--Main JS-->
<script src="assets/js/main.js"></script>

</body>
</html>
