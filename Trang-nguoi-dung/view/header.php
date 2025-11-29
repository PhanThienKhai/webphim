<!doctype html>
<html>

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>Galaxy Studio: Trang bán vé số 1 Việt Nam</title>
    <meta name="description" content="A Template by Gozha.net">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Gozha.net">

    <!-- Mobile Specific Metas-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <link rel="shortcut icon" type="image/x-icon" href="../images/movie-img1.jpg">

    <!-- Fonts -->
    <!-- Font awesome - icon font -->
    <link href="netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- Roboto -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700' rel='stylesheet' type='text/css'>
    <!-- Open Sans -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:800italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.css">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="login-ui2/login-ui2/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" />
    <!-- Mobile menu -->
    <link href="css/gozha-nav.css" rel="stylesheet" />
    <!-- Select -->
    <link href="css/external/jquery.selectbox.css" rel="stylesheet" />

    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />

    <!-- Custom -->
    <link href="css/style3860.css?v=1" rel="stylesheet" />
    <link rel="stylesheet" href="css/dv.css">
    <!-- Modernizr -->
    <script src="js/external/modernizr.custom.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
    <script src="js/custom.js"></script>
    <![endif]-->
</head>

<body>
<div class="wrapper">

    <!-- Header section -->
    <header class="header-wrapper header-wrapper--home">
        <div class="container">
            <!-- Logo link-->
            <a href='index.php' class="logo">
                <img alt='logo' src="imgavt/Galaxy_Studio_2003_(Wordmark)_(Grey).webp"> 
                <!-- Galaxy Studio -->
            </a>

            <!-- Main website navigation-->
            <nav id="navigation-box">
                <!-- Toggle for mobile menu mode -->
                <a href="#" id="navigation-toggle">
                        <span class="menu-icon">
                            <span class="icon-toggle" role="button" aria-label="Toggle Navigation">
                              <span class="lines"></span>
                            </span>
                        </span>
                </a>
                <ul id="navigation">
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php">Trang chủ</a>

                    </li>
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php?act=dsphim1&sotrang=1">Phim</a>
                        <ul>
                            <li class="menu__nav-item"><a href="index.php?act=phimdangchieu" >Tất cả Phim</a></li>
                        </ul>
                    </li>

                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="">Thể loại</a>
                        <ul>
                            <?php foreach ($loadloai as $loaip){
                                extract($loaip);
                                $linkloaip = 'index.php?act=theloai&id_loai='.$id;
                                echo '<li class="menu__nav-item"><a href="'.$linkloaip.'" >'.$name.'</a></li>';
                            } ?>

                        </ul>
                    </li>
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php?act=rapchieu">Rạp chiếu</a>
                        <?php if (!empty($allRaps) && is_array($allRaps)) { ?>
                        <ul>
                            <?php foreach ($allRaps as $r) {
                                // Link mới: dẫn tới trang phim theo rạp
                                $r_link = 'index.php?act=phim_theo_rap&id_rap=' . $r['id'];
                                echo '<li class="menu__nav-item"><a href="' . $r_link . '">' . htmlspecialchars($r['ten_rap']) . '</a></li>'; 
                            } ?>
                        </ul>
                        <?php } ?>

                    </li>
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php?act=khuyenmai">Khuyến mãi</a>

                    </li>
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php?act=lienhe">Liên hệ</a>

                    </li>
                    <li>
                        <span class="sub-nav-toggle plus"></span>
                        <a href="index.php?act=tintuc">Tin tức</a>

                    </li>
                </ul>
            </nav>
            <div class="control-panel">
                <?php if (isset($_SESSION['user'])): 
                    $user_data = $_SESSION['user'];
                    $name = isset($user_data['name']) ? $user_data['name'] : 'User';
                    $id_user = isset($user_data['id']) ? (int)$user_data['id'] : 0;
                    $vai_tro_user = isset($user_data['vai_tro']) ? (int)$user_data['vai_tro'] : 0;
                    $diem_user = isset($user_data['diem_tich_luy']) ? (int)$user_data['diem_tich_luy'] : 0;
                    
                    // DEBUG: Log dữ liệu session
                    // error_log("DEBUG header - diem_user: $diem_user, vai_tro: $vai_tro_user, all_user_data: " . json_encode($user_data));

                    $hang_user = isset($user_data['hang_thanh_vien']) ? $user_data['hang_thanh_vien'] : 'dong';
                    
                    // Chỉ load thông tin hạng nếu là thành viên
                    $mau_hang = '#CD7F32'; // Màu mặc định
                    if ($vai_tro_user == 0 && file_exists('model/diem.php')) {
                        require_once 'model/diem.php';
                        $hang_info = get_thong_tin_hang($hang_user);
                        $mau_hang = $hang_info ? $hang_info['mau_sac'] : '#CD7F32';
                    }
                ?>
                    <!-- Dropdown menu user -->
                    <div style="position: relative; display: inline-block;">
                        <button class="btn btn-md btn--warning btn--book" 
                                style="display: flex; align-items: center; gap: 10px; cursor: pointer; border: none;"
                                onclick="toggleUserMenu()">
                            <span><?= htmlspecialchars($name) ?></span>
                            <?php if ($vai_tro_user == 0 && $diem_user >= 0): ?>
                                <span style="background: <?= $mau_hang ?>; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                    <i class="fa fa-star"></i>
                                    <?= number_format($diem_user) ?>
                                </span>
                            <?php endif; ?>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        
                        <!-- Dropdown content -->
                        <div id="userMenuDropdown" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 5px; background: white; min-width: 200px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); border-radius: 10px; z-index: 1000; overflow: hidden;">
                            <?php if ($vai_tro_user == 0): ?>
                                <a href="index.php?act=lich_su_diem" style="display: block; padding: 12px 20px; color: #333; text-decoration: none; border-bottom: 1px solid #eee;">
                                    <i class="fa fa-star"></i> Lịch sử điểm
                                </a>
                            <?php endif; ?>
                            <a href="index.php?act=ve&id=<?= $id_user ?>" style="display: block; padding: 12px 20px; color: #333; text-decoration: none; border-bottom: 1px solid #eee;">
                                <i class="fa fa-ticket"></i> Vé của tôi
                            </a>
                            <a href="index.php?act=dangnhap" style="display: block; padding: 12px 20px; color: #333; text-decoration: none; border-bottom: 1px solid #eee;">
                                <i class="fa fa-user"></i> Thông tin cá nhân
                            </a>
                            <a href="index.php?act=dangxuat" style="display: block; padding: 12px 20px; color: #dc3545; text-decoration: none;">
                                <i class="fa fa-sign-out"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                    
                    <script>
                    function toggleUserMenu() {
                        const menu = document.getElementById('userMenuDropdown');
                        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                    }
                    
                    // Đóng menu khi click bên ngoài
                    document.addEventListener('click', function(event) {
                        const menu = document.getElementById('userMenuDropdown');
                        const button = event.target.closest('.btn--book');
                        if (!button && menu) {
                            menu.style.display = 'none';
                        }
                    });
                    </script>
                <?php else: ?>
                    <a href="index.php?act=dangnhap" class="btn btn-md btn--warning btn--book">Đăng nhập</a>
                <?php endif; ?>
            </div>

        </div>
    </header>




