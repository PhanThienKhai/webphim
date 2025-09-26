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
</head>

<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="brand">
            <img src="assets/images/favicon.ico" alt="" width="28" height="28"/>
            <h2>Galaxy Studio Admin</h2>
        </div>
        <p class="sub">Đăng nhập để vào trang quản trị</p>
        <?php if(isset($loi)&& ($loi)!=""): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($loi) ?></div>
        <?php endif; ?>
        <form action="<?= $_SERVER['PHP_SELF'];  ?>" method="post">
            <div class="mb-15">
                <label>Tài khoản</label>
                <input class="form-control" type="text" name="user" placeholder="Tên đăng nhập" required>
            </div>
            <div class="mb-15">
                <label>Mật khẩu</label>
                <input class="form-control" name="pass" type="password" placeholder="Mật khẩu" required>
            </div>
            <div class="mb-10">
                <button  class="button button-primary" name="dangnhap" type="submit" value="1">Đăng nhập</button>
            </div>
            <div class="foot">
                <span>© Galaxy Studio</span>
                <span>Bảo mật • Hỗ trợ</span>
            </div>
        </form>
    </div>
</div>

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
