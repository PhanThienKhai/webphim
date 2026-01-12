<?php
include "./view/home/sideheader.php";
if (is_array($loadtk)) {
    extract($loadtk);
}
?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Tài Khoản / <span>Chỉnh Sửa Tài Khoản</span></h3>
            </div>
        </div>
    </div><!-- Page Headings End -->

    <!-- Alert Messages -->
    <?php if(isset($error) && $error != ""): ?>
        <div class="alert alert-danger mb-20" style="padding: 15px; border-radius: 6px; background: #ffebee; border-left: 4px solid #f44336; color: #c62828;">
            ✕ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Add or Edit Product Start -->
    <form action="index.php?act=updateuser" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="news-item">
                    <div class="content">
                        <h4 style="margin-top: 0; color: #333; font-size: 16px; margin-bottom: 20px;">Chỉnh Sửa Thông Tin Tài Khoản</h4>
                        
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        
                        <div class="row">
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tên</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($name) ?>" name="name" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tài Khoản</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($user) ?>" name="user" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Mật Khẩu</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($pass) ?>" name="pass" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Email</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($email) ?>" name="email" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Số Điện Thoại</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($phone) ?>" name="phone" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Địa Chỉ</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars($dia_chi) ?>" name="dia_chi" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Phụ Cấp Cố Định (VND)</label>
                                <input class="form-control" type="number" step="0.01" value="<?= number_format((float)($phu_cap_co_dinh ?? 0), 2, '.', '') ?>" name="phu_cap_co_dinh" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                                <small style="color: #999; margin-top: 4px; display: block;">Ăn trưa, xăng xe, phụ cấp khác...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12 mb-30">
                <div class="news-item">
                    <div class="content">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="button button-primary" type="submit" name="capnhat" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                <i class="zmdi zmdi-check"></i> Cập Nhật
                            </button>
                            <a class="button button-outline" href="index.php?act=QTvien" style="border: 1px solid #ddd; color: #666; padding: 12px 30px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-block;">
                                <i class="zmdi zmdi-list"></i> Danh Sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div><!-- Content Body End -->
</div><!-- Content Body End -->