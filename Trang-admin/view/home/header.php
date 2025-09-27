<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from view/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Nov 2023 02:44:52 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ADMIN GALAXY STUDIO</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!-- Favicon -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
<!-- CSS
============================================ -->

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

<!-- Icon Font CSS -->
<link rel="stylesheet" href="assets/css/vendor/material-design-iconic-font.min.css">
<link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/vendor/themify-icons.css">
<link rel="stylesheet" href="assets/css/vendor/cryptocurrency-icons.css">
    <link rel="stylesheet" href="assets/css/ctve.css">

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
   
    <div class="main-wrapper">


        <!-- Header Section Start -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">

                    <!-- Header Logo (Header Left) Start -->
                    <div class="header-logo col-auto">
                        <a href="index.php">
                            <h3>Quản Trị Galaxy Studio</h3>
                            <!-- <img src="assets/images/logo/logo.png" alt="">
                            <img src="assets/images/logo/logo-light.png" class="logo-light" alt=""> -->
                        </a>
                    </div><!-- Header Logo (Header Left) End -->

                    <!-- Header Right Start -->
                    <div class="header-right flex-grow-1 col-auto">
                        <div class="row justify-content-between align-items-center">

                            <!-- Side Header Toggle & Search Start -->
                            <div class="col-auto">
                                <div class="row align-items-center">

                                    <!--Side Header Toggle-->
                                    <div class="col-auto"><button class="side-header-toggle"><i class="zmdi zmdi-menu"></i></button></div>

                                </div>
                            </div><!-- Side Header Toggle & Search End -->

                            <!-- Header Notifications Area Start -->
                            <div class="col-auto">

                                <ul class="header-notification-area">

                                    <!--Language-->
                                    <li class="adomx-dropdown position-relative col-auto">
                                        <a class="toggle" href="#"><img class="lang-flag" src="assets/images/flags/flag-6.jpg" alt="" style="border-radius: 5px"></a>

                                        <!-- Dropdown -->

                                    </li>
                                    <!--User-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#">
                                            <span class="user">
                                        <span class="avatar">
                                            <img src="assets/images/avatar/avatar-2.jpg" alt="">
                                            <span class="status"></span>
                                            </span>
                                            </span>
                                        </a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-user">

                                            <?php
                                            if (isset($_SESSION['user1'])) {
                                                require_once __DIR__ . '/../../helpers/quyen.php';
                                                $user1 = $_SESSION['user1'];
                                                $roleLabel = role_label($user1['vai_tro'] ?? -1);
                                                $name = htmlspecialchars($user1['name'] ?? '', ENT_QUOTES, 'UTF-8');
                                                $email = htmlspecialchars($user1['email'] ?? '', ENT_QUOTES, 'UTF-8');
                                                echo '<div class="head">
                                                      <h5 class="name"><a href="#">' . $roleLabel . ' - ' . $name . ' </a></h5>
                                                      <a class="mail" href="#">' . $email . '</a>
                                                       </div>
                                                         <div class="body">
                                                             <ul>';
                                                echo '<li><a href="index.php?act=dangxuat"><i class="zmdi zmdi-lock-open"></i>Đăng xuất</a></li>';
                                                echo '</ul></div></div>';
                                            }
                                            ?>



                                    </li>

                                </ul>

                            </div><!-- Header Notifications Area End -->

                        </div>
                    </div><!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
