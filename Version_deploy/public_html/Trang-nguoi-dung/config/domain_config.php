<?php
/**
 * Production Domain Configuration
 * File: Trang-nguoi-dung/config/domain_config.php
 * 
 * This file contains production domain settings.
 * Update this when deploying to different environments.
 */

// ===== PRODUCTION DOMAIN =====
// Domain của bạn
define('PRODUCTION_DOMAIN', 'https://webphim.online');

// Webphim base path
define('WEBPHIM_PATH', '/');  // Nếu folder webphim ở root, dùng '/'
                              // Nếu folder webphim ở /webphim/, dùng '/webphim/'

// Full base URL
define('BASE_URL', PRODUCTION_DOMAIN . WEBPHIM_PATH . 'Trang-nguoi-dung');

// ===== PAYMENT CALLBACK URLs =====
define('MOMO_CALLBACK_URL', BASE_URL . '/view/momo/xuly_callback_momo.php');
define('SEPAY_WEBHOOK_URL', BASE_URL . '/sepay/sepay_webhook.php');
define('VIETQR_RETURN_URL', BASE_URL . '/vietqr_return.php');

// ===== EMAIL LINK =====
define('USER_TICKETS_URL', BASE_URL . '/index.php?p=ve_cua_toi');

?>
