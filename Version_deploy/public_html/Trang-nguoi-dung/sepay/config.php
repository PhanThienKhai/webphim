<?php
/**
 * Sepay Payment Gateway Configuration
 * 配置文件 - Thay đổi thông tin tại đây
 */

// ====================================================
// DATABASE CONFIGURATION
// ====================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'u508775056_cinepass');
define('DB_PASS', 'Kpy123456@@');
define('DB_NAME', 'u508775056_cinepass');

// ====================================================
// SEPAY BANK ACCOUNT CONFIGURATION
// ====================================================
define('BANK_ACCOUNT_NAME', 'GALAXY STUDIO');
define('BANK_ACCOUNT_NUMBER', '0384104942');
define('BANK_CODE', 'MBBANK');
define('BANK_NAME', 'Ngân Hàng TMCP Quân Đội');

// ====================================================
// SEPAY WEBHOOK CONFIGURATION
// ====================================================
// Thay YOUR_DOMAIN bằng domain thực của bạn
// Ví dụ: https://webphim.online (cho production)
define('SEPAY_WEBHOOK_URL', 'https://webphim.online/Trang-nguoi-dung/sepay/sepay_webhook.php');
define('SEPAY_RETURN_URL', 'https://webphim.online/Trang-nguoi-dung/sepay/sepay_return.php');

// ====================================================
// APPLICATION CONFIGURATION
// ====================================================
define('ORDER_PREFIX', 'VE');  // Mã vé: VE123456
define('DOMAIN', 'https://webphim.online');

// ====================================================
// EMAIL CONFIGURATION (From ve.php)
// ====================================================
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'phanthienkhai2901@gmail.com');
define('MAIL_PASSWORD', 'nvyh agju zvnp nacz');     // Gmail App Password từ ve.php
define('MAIL_FROM_NAME', 'Galaxy Studio');
define('MAIL_FROM_EMAIL', 'phanthienkhai2901@gmail.com');

// ====================================================
// POINT CONFIGURATION
// ====================================================
define('POINTS_PER_VND', 0.01);  // 1 VND = 0.01 điểm (100,000 VND = 1000 điểm)
define('POINTS_BONUS_RATE', 1.0); // 100% điểm thêm cho thanh toán online

?>
