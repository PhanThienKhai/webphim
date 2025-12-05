<footer class="footer-wrapper">
    <?php
    // Lấy cấu hình website từ API (nếu chưa load ở header)
    if (!isset($web_config)) {
        $web_config = [
            'ten_website' => 'Galaxy Studio',
            'logo' => 'imgavt/Galaxy_Studio_2003_(Wordmark)_(Grey).webp',
            'dia_chi' => '',
            'so_dien_thoai' => '',
            'email' => '',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'mo_ta' => 'Nền tảng mua vé xem phim hàng đầu'
        ];
        
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'http://localhost/webphim/Trang-nguoi-dung/api_config.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            
            if ($response) {
                $data = json_decode($response, true);
                if ($data && $data['success'] && isset($data['data'])) {
                    $web_config = array_merge($web_config, $data['data']);
                }
            }
        } catch (Exception $e) {
            // Dùng config mặc định nếu lỗi
        }
    }
    ?>
    <section class="container">
        <div class="col-xs-4 col-md-2 footer-nav">
            <ul class="nav-link">
                <li><a href="index.php" class="nav-link__item">Trang chủ</a></li>
                <li><a href="index.php?act=dsphim1&sotrang=1" class="nav-link__item">Phim</a></li>
                <li><a href="index.php?act=rapchieu" class="nav-link__item">Rạp chiếu</a></li>
                <li><a href="index.php?act=khuyenmai" class="nav-link__item">Khuyến mãi</a></li>
            </ul>
        </div>
        <div class="col-xs-4 col-md-2 footer-nav">
            <ul class="nav-link">
                <li><a href="index.php?act=tintuc" class="nav-link__item">Tin tức</a></li>
                <li><a href="index.php?act=lienhe" class="nav-link__item">Liên hệ</a></li>
                <li><a href="#" class="nav-link__item">Điều khoản sử dụng</a></li>
                <li><a href="#" class="nav-link__item">Chính sách bảo mật</a></li>
            </ul>
        </div>
        <div class="col-xs-4 col-md-2 footer-nav">
            <ul class="nav-link">
                <?php if (!empty($web_config['so_dien_thoai'])): ?>
                    <li><a href="tel:<?= htmlspecialchars($web_config['so_dien_thoai']) ?>" class="nav-link__item">
                        <i class="fa fa-phone"></i> <?= htmlspecialchars($web_config['so_dien_thoai']) ?>
                    </a></li>
                <?php endif; ?>
                <?php if (!empty($web_config['email'])): ?>
                    <li><a href="mailto:<?= htmlspecialchars($web_config['email']) ?>" class="nav-link__item">
                        <i class="fa fa-envelope"></i> <?= htmlspecialchars($web_config['email']) ?>
                    </a></li>
                <?php endif; ?>
                <?php if (!empty($web_config['dia_chi'])): ?>
                    <li class="nav-link__item">
                        <i class="fa fa-map-marker"></i> <?= htmlspecialchars($web_config['dia_chi']) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="footer-info">
                <p class="heading-special--small"><?= htmlspecialchars($web_config['ten_website']) ?><br><span class="title-edition">trên mạng xã hội</span></p>

                <div class="social">
                    <?php if (!empty($web_config['facebook'])): ?>
                        <a href='<?= htmlspecialchars($web_config['facebook']) ?>' target="_blank" class="social__variant fa fa-facebook" title="Facebook"></a>
                    <?php endif; ?>
                    <?php if (!empty($web_config['instagram'])): ?>
                        <a href='<?= htmlspecialchars($web_config['instagram']) ?>' target="_blank" class="social__variant fa fa-instagram" title="Instagram"></a>
                    <?php endif; ?>
                    <?php if (!empty($web_config['youtube'])): ?>
                        <a href='<?= htmlspecialchars($web_config['youtube']) ?>' target="_blank" class="social__variant fa fa-youtube" title="YouTube"></a>
                    <?php endif; ?>
                </div>

                <div class="clearfix"></div>
                <p class="copy">&copy; <?= htmlspecialchars($web_config['ten_website']) ?>, 2025. Đã được cấu hình từ Admin. <?php if (!empty($web_config['mo_ta'])): ?>| <?= htmlspecialchars($web_config['mo_ta']) ?><?php endif; ?></p>
            </div>
        </div>
    </section>
</footer>
</div>

<!-- open/close -->
<div class="overlay overlay-hugeinc">

    <section class="container">

        <div class="col-sm-4 col-sm-offset-4">
            <button type="button" class="overlay-close">Close</button>
            <form id="login-form" class="login" method='get' novalidate=''>
                <p class="login__title">sign in <br><span class="login-edition">welcome to A.Movie</span></p>

                <div class="social social--colored">
                    <a href='#' class="social__variant fa fa-facebook"></a>
                    <a href='#' class="social__variant fa fa-twitter"></a>
                    <a href='#' class="social__variant fa fa-tumblr"></a>
                </div>

                <p class="login__tracker">or</p>

                <div class="field-wrap">
                    <input type='email' placeholder='Email' name='user-email' class="login__input">
                    <input type='password' placeholder='Password' name='user-password' class="login__input">

                    <input type='checkbox' id='#informed' class='login__check styled'>
                    <label for='#informed' class='login__check-info'>remember me</label>
                </div>

                <div class="login__control">
                    <button type='submit' class="btn btn-md btn--warning btn--wider">sign in</button>
                    <a href="#" class="login__tracker form__tracker">Forgot password?</a>
                </div>
            </form>
        </div>

    </section>
</div>

<!-- JavaScript-->
<!-- jQuery 1.9.1-->
<script src="ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>  
<script>window.jQuery || document.write('<script src="js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- jQuery UI -->
<script src="code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!-- Bootstrap 3-->
<script src="netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- jQuery REVOLUTION Slider -->
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<!--*** Google map  ***-->
<script src="https://maps.google.com/maps/api/js?sensor=true"></script>
<!--*** Google map infobox  ***-->
<script src="js/external/infobox.js"></script>

<!-- Mobile menu -->
<script src="js/jquery.mobile.menu.js"></script>
<!-- Select -->
<script src="js/external/jquery.selectbox-0.2.min.js"></script>
<!-- Stars rate -->
<script src="js/external/jquery.raty.js"></script>

<!-- Form element -->
<script src="js/external/form-element.js"></script>
<!-- Form validation -->
<script src="js/form.js"></script>

<!-- Twitter feed -->
<script src="js/external/twitterfeed.js"></script> -->

<!-- Custom -->
<script src="js/custom.js?v=<?php echo time(); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        init_BookingTwo();
    });
</script>
<script src="login-ui2/login-ui2/js/common.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        init_Home();
    });
</script>

</script>
</body>

</html>

