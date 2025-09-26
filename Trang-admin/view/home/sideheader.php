
   <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
            <div class="side-header-inner custom-scroll">

                <?php 
                    require_once __DIR__ . '/../../helpers/quyen.php';
                    $role = $_SESSION['user1']['vai_tro'] ?? -1;
                    $currentAct = $_GET['act'] ?? 'home';
                ?>
                <nav class="side-header-menu" id="side-header-menu">
                    <ul>
                        <li><a href="index.php?act=home" class="<?= $currentAct==='home'?'is-active':''; ?>"><i class="fa fa-institution"></i> <span>Trang chủ</span></a></li>

                        <?php if ($role == ROLE_ADMIN_HE_THONG): ?>
                            <li><a href="index.php?act=cauhinh" class="<?= $currentAct==='cauhinh'?'is-active':''; ?>"><i class="zmdi zmdi-settings"></i> <span>Cấu hình website</span></a></li>
                            <li><a href="#"><i class="fa fa-user"></i> <span>Quản Lý Tài Khoản</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=themuser" class="<?= $currentAct==='themuser'?'is-active':''; ?>"><i class="zmdi zmdi-account-add"></i> <span>Thêm tài khoản</span></a></li>
                                    <li><a href="index.php?act=QTkh" class="<?= $currentAct==='QTkh'?'is-active':''; ?>"><i class="fa fa-users"></i> <span>Khách hàng</span></a></li>
                                    <li><a href="index.php?act=QTvien" class="<?= $currentAct==='QTvien'?'is-active':''; ?>"><i class="fa fa-id-badge"></i> <span>Nhân viên</span></a></li>
                                    <li><a href="index.php?act=QLquanlyrap" class="<?= $currentAct==='QLquanlyrap'?'is-active':''; ?>"><i class="fa fa-user-secret"></i> <span>Quản lý rạp</span></a></li>
                                    <li><a href="index.php?act=QLquanlycum" class="<?= $currentAct==='QLquanlycum'?'is-active':''; ?>"><i class="zmdi zmdi-city-alt"></i> <span>Quản lý cụm rạp</span></a></li>
                                    <li><a href="index.php?act=cum_admin" class="<?= $currentAct==='cum_admin'?'is-active':''; ?>"><i class="zmdi zmdi-shield-security"></i> <span>Admin hệ thống</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="fa fa-line-chart" ></i> <span>Thống Kê Doanh Thu</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=DTdh&&trang=1"><i class="fa fa-line-chart" ></i><span>Danh Thu Phim</span></a></li>
                                    <li><a href="index.php?act=DTngay&&trang=1"><i class="fa fa-line-chart" ></i><span>Theo Ngày</span></a></li>
                                    <li><a href="index.php?act=DTtuan&&trang=1"><i class="fa fa-line-chart" ></i><span>Theo Tuần</span></a></li>
                                    <li><a href="index.php?act=DTthang&&trang=1"><i class="fa fa-line-chart" ></i><span>Theo Tháng</span></a></li>
                                </ul>
                            </li>
                            
                        <?php elseif ($role == ROLE_QUAN_LY_CUM): ?>
                            <li class="has-sub-menu"><a href="#"><i class="fa fa-building"></i> <span>Quản Lý Rạp</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=QLrap" class="<?= $currentAct==='QLrap'?'is-active':''; ?>"><i class="fa fa-list"></i> <span>Danh sách rạp</span></a></li>
                                    <li><a href="index.php?act=themrp" class="<?= $currentAct==='themrp'?'is-active':''; ?>"><i class="fa fa-plus"></i> <span>Thêm rạp</span></a></li>
                                </ul>
                            </li>
                            <li><a href="index.php?act=QLloaiphim" class="<?= $currentAct==='QLloaiphim'?'is-active':''; ?>"><i class="fa fa-window-restore"></i> <span>Quản Lý Loại Phim</span></a></li>
                            <li><a href="index.php?act=QLphim" class="<?= $currentAct==='QLphim'?'is-active':''; ?>"><i class="fa fa-film"></i> <span>Quản Lý Phim (Cụm)</span></a></li>
                            <li><a href="index.php?act=duyet_lichchieu" class="<?= $currentAct==='duyet_lichchieu'?'is-active':''; ?>"><i class="zmdi zmdi-check"></i> <span>Duyệt kế hoạch chiếu</span></a></li>
                            <li><a href="index.php?act=lich_rap" class="<?= $currentAct==='lich_rap'?'is-active':''; ?>"><i class="zmdi zmdi-calendar"></i> <span>Lịch theo rạp</span></a></li>
                            <li><a href="index.php?act=phanphim" class="<?= $currentAct==='phanphim'?'is-active':''; ?>"><i class="zmdi zmdi-collection-video"></i> <span>Phân phối phim</span></a></li>
                            <li class="has-sub-menu"><a href="#"><i class="zmdi zmdi-accounts"></i> <span>Tài khoản</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=themuser"><i class="zmdi zmdi-account-add"></i> <span>Thêm tài khoản</span></a></li>
                                    <li><a href="index.php?act=QTkh"><i class="fa fa-users"></i> <span>Khách hàng</span></a></li>
                                    <li><a href="index.php?act=QTvien"><i class="fa fa-id-badge"></i> <span>Nhân viên</span></a></li>
                                    <li><a href="index.php?act=QLquanlyrap"><i class="fa fa-user-secret"></i> <span>Quản lý rạp</span></a></li>

                                    <li><a href="index.php?act=cum_admin"><i class="zmdi zmdi-shield-security"></i> <span>Admin hệ thống</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="fa fa-line-chart" ></i> <span>Thống Kê</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=DTdh&&trang=1" class="<?= $currentAct==='DTdh'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Doanh Thu Phim</span></a></li>
                                    <li><a href="index.php?act=DTngay&&trang=1" class="<?= $currentAct==='DTngay'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Theo Ngày</span></a></li>
                                    <li><a href="index.php?act=DTtuan&&trang=1" class="<?= $currentAct==='DTtuan'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Theo Tuần</span></a></li>
                                    <li><a href="index.php?act=DTthang&&trang=1" class="<?= $currentAct==='DTthang'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Theo Tháng</span></a></li>
                                    <li><a href="index.php?act=DTphim_rap" class="<?= $currentAct==='DTphim_rap'?'is-active':''; ?>"><i class="fa fa-bar-chart" ></i><span>DT Phim theo Rạp</span></a></li>
                                    <li><a href="index.php?act=hieusuat_rap" class="<?= $currentAct==='hieusuat_rap'?'is-active':''; ?>"><i class="fa fa-area-chart" ></i><span>Hiệu suất Rạp</span></a></li>
                                    <li><a href="index.php?act=TKrap" class="<?= $currentAct==='TKrap'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Theo Rạp</span></a></li>
                                </ul>
                            </li>
                        <?php elseif ($role == ROLE_QUAN_LY_RAP): ?>
                            <li><a href="index.php?act=ql_lichlamviec"><i class="zmdi zmdi-calendar"></i> <span>Lịch làm việc</span></a></li>
                            <li><a href="index.php?act=ql_duyetnghi"><i class="zmdi zmdi-time-restore"></i> <span>Duyệt nghỉ phép</span></a></li>
                            <li><a href="index.php?act=kehoach" class="<?= $currentAct==='kehoach'?'is-active':''; ?>"><i class="zmdi zmdi-movie-alt"></i> <span>Lập kế hoạch chiếu phim</span></a></li>
                            <li><a href="index.php?act=phong"><i class="zmdi zmdi-local-movies zmdi-hc-fw"></i> <span>Quản Lý Phòng</span></a></li>
                            <li class="has-sub-menu"><a href="#"><i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> <span>Quản Lý Suất Chiếu</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=QLsuatchieu"><i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i><span>Suất Chiếu</span></a></li>
                                    <li><a href="index.php?act=thoigian"><i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i><span>Khung Giờ</span></a></li>
                                    <li><a href="index.php?act=lich_rap" class="<?= $currentAct==='lich_rap'?'is-active':''; ?>"><i class="zmdi zmdi-calendar"></i><span>Lịch theo rạp</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="zmdi zmdi-local-offer"></i> <span>Ưu đãi</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=QLkm" class="<?= $currentAct==='QLkm'?'is-active':''; ?>"><i class="zmdi zmdi-ticket-star"></i> <span>Khuyến mãi (Mã giảm giá)</span></a></li>
                                    <li><a href="index.php?act=QLcombo" class="<?= $currentAct==='QLcombo'?'is-active':''; ?>"><i class="zmdi zmdi-cocktail"></i> <span>Combo</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="zmdi zmdi-accounts"></i> <span>Nhân sự</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=chamcong" class="<?= $currentAct==='chamcong'?'is-active':''; ?>"><i class="zmdi zmdi-check"></i> <span>Chấm công</span></a></li>
                                    <li><a href="index.php?act=bangluong" class="<?= $currentAct==='bangluong'?'is-active':''; ?>"><i class="zmdi zmdi-money"></i> <span>Bảng lương</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="fa fa-line-chart" ></i> <span>Thống Kê</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=DTphim_rap" class="<?= $currentAct==='DTphim_rap'?'is-active':''; ?>"><i class="fa fa-bar-chart" ></i><span>Doanh thu phim</span></a></li>
                                    <li><a href="index.php?act=TKrap" class="<?= $currentAct==='TKrap'?'is-active':''; ?>"><i class="fa fa-line-chart" ></i><span>Tổng quan rạp</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="fa fa-user"></i> <span>Tài khoản</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=themuser" class="<?= $currentAct==='themuser'?'is-active':''; ?>"><i class="zmdi zmdi-account-add"></i> <span>Thêm tài khoản</span></a></li>
                                    <li><a href="index.php?act=QTvien" class="<?= $currentAct==='QTvien'?'is-active':''; ?>"><i class="fa fa-id-badge"></i> <span>Nhân viên</span></a></li>
                                </ul>
                            </li>
                            <li class="has-sub-menu"><a href="#"><i class="ti-shopping-cart"></i> <span>Vé</span></a>
                                <ul class="side-header-sub-menu">
                                    <li><a href="index.php?act=ve"><i class="ti-shopping-cart"></i> <span>Quản lý vé</span></a></li>
                                    <li><a href="index.php?act=doi_hoan_ve"><i class="zmdi zmdi-refresh"></i> <span>Đổi/Hoàn vé</span></a></li>
                                </ul>
                            </li>
                            <li><a href="index.php?act=thietbiphong"><i class="zmdi zmdi-wrench"></i> <span>Thiết bị phòng</span></a></li>
                            <li><a href="index.php?act=QLfeed&&sotrang=1"><i class="fa fa-comments" ></i> <span>Bình luận/Feedback</span></a></li>
                        <?php elseif ($role == ROLE_NHAN_VIEN): ?>
                            <li><a href="index.php?act=ve"><i class="ti-shopping-cart"></i> <span>Đặt/Quản lý vé</span></a></li>
                            <li><a href="index.php?act=nv_datve"><i class="zmdi zmdi-ticket-star"></i> <span>Đặt vé cho khách</span></a></li>
                            <li><a href="index.php?act=scanve"><i class="zmdi zmdi-check"></i> <span>Kiểm tra vé</span></a></li>
                            <li><a href="index.php?act=nv_lichlamviec"><i class="zmdi zmdi-calendar"></i> <span>Lịch làm việc</span></a></li>
                            <li><a href="index.php?act=xinnghi"><i class="zmdi zmdi-time-restore"></i> <span>Xin nghỉ phép</span></a></li>
                            <li><a href="index.php?act=nv_baocao"><i class="fa fa-line-chart"></i> <span>Báo cáo cá nhân</span></a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div><!-- Side Header Inner End -->
        </div><!-- Side Header End -->
