<?php
include "./view/home/sideheader.php";

?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Tài Khoản / <span>Thêm Tài Khoản</span></h3>
            </div>
        </div>
    </div><!-- Page Headings End -->
    
    <!-- Alert Messages -->
    <?php if(isset($suatc) && ($suatc) != ""): ?>
        <div class="alert alert-danger mb-20" style="padding: 15px; border-radius: 6px; background: #ffebee; border-left: 4px solid #f44336; color: #c62828;">
            ✕ <?= htmlspecialchars($suatc) ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($error) && $error != ""): ?>
        <div class="alert alert-danger mb-20" style="padding: 15px; border-radius: 6px; background: #ffebee; border-left: 4px solid #f44336; color: #c62828;">
            ✕ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Add or Edit Product Start -->
    <form action="index.php?act=themuser" method="POST" class="adm-form">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="news-item">
                    <div class="content">
                        <h4 style="margin-top: 0; color: #333; font-size: 16px; margin-bottom: 20px;">Thông Tin Tài Khoản</h4>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tên</label>
                                <input class="form-control" type="text" placeholder="Nhập tên" name="name" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tài Khoản</label>
                                <input class="form-control" type="text" placeholder="Tên đăng nhập" name="user" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Mật Khẩu</label>
                                <input class="form-control" type="password" placeholder="Mật khẩu" name="pass" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Email</label>
                                <input class="form-control" type="email" placeholder="Email" name="email" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Số Điện Thoại</label>
                                <input class="form-control" type="text" placeholder="Số điện thoại" name="phone" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Địa Chỉ</label>
                                <input class="form-control" type="text" placeholder="Địa chỉ" name="dia_chi" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-20">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Vai Trò <span style="color: #f5576c;">*</span></label>
                                <select class="form-control" name="vai_tro" id="vai_tro" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                                    <option value="">-- Chọn vai trò --</option>
                                    <option value="0">Khách Hàng</option>
                                    <option value="1">Nhân Viên Rạp</option>
                                    <option value="3">Quản Lý Rạp</option>
                                    <option value="2">Admin Hệ Thống</option>
                                    <option value="4">Quản Lý Cụm Rạp</option>
                                </select>
                                <small id="role_hint" class="hint" style="display:block; margin-top: 6px; opacity: 0.8; font-size: 12px;"></small>
                            </div>
                            <div class="col-12 col-md-6 mb-20" id="wrap_rap" style="display: none;">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Rạp <span style="color: #f5576c;">*</span></label>
                                <select class="form-control" name="id_rap" id="id_rap" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                                    <option value="">-- Chọn rạp --</option>
                                    <?php foreach (rap_all() as $r): ?>
                                        <option value="<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['ten_rap']) ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                            <button class="button button-primary" type="submit" name="them" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                <i class="zmdi zmdi-plus"></i> Thêm Tài Khoản
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

    <script>
        (function(){
            var roleSelect = document.getElementById('vai_tro');
            var wrapRap = document.getElementById('wrap_rap');
            var rapSelect = document.getElementById('id_rap');
            var currentRole = <?= (int)($_SESSION['user1']['vai_tro'] ?? -1) ?>;
            var currentRap = <?= isset($_SESSION['user1']['id_rap']) ? (int)$_SESSION['user1']['id_rap'] : 'null' ?>;

            function applyRapVisibility(){
                var v = roleSelect.value;
                var needRap = (v === '1' || v === '3');
                wrapRap.style.display = needRap ? '' : 'none';
                rapSelect.required = needRap;
                if (!needRap) rapSelect.value = '';
            }

            // Nếu người tạo là QL rạp: chỉ cho phép tạo Nhân viên rạp tại rạp của họ
            if (currentRole === 3) {
                if (currentRap) {
                    rapSelect.value = String(currentRap);
                    rapSelect.disabled = true;
                    rapSelect.insertAdjacentHTML('afterend', '<small style="display:block;margin-top:6px;opacity:.7">Rạp mặc định theo tài khoản quản lý rạp.</small>');
                }
                // Ẩn các lựa chọn role khác ngoài 1 (Nhân viên rạp)
                Array.from(roleSelect.options).forEach(function(opt){
                    if (opt.value !== '1' && opt.value !== '') opt.disabled = true;
                });
                roleSelect.value = '1';
                document.getElementById('role_hint').textContent = 'Quản lý rạp chỉ có thể tạo tài khoản Nhân viên rạp thuộc rạp của mình.';
            }

            roleSelect.addEventListener('change', applyRapVisibility);
            applyRapVisibility();
        })();
    </script>

</div><!-- Content Body End -->
    <script>
        (function(){
            var roleSelect = document.getElementById('vai_tro');
            var wrapRap = document.getElementById('wrap_rap');
            var rapSelect = document.getElementById('id_rap');
            var currentRole = <?= (int)($_SESSION['user1']['vai_tro'] ?? -1) ?>;
            var currentRap = <?= isset($_SESSION['user1']['id_rap']) ? (int)$_SESSION['user1']['id_rap'] : 'null' ?>;

            function applyRapVisibility(){
                var v = roleSelect.value;
                var needRap = (v === '1' || v === '3');
                wrapRap.style.display = needRap ? '' : 'none';
                rapSelect.required = needRap;
                if (!needRap) rapSelect.value = '';
            }

            // Nếu người tạo là QL rạp: chỉ cho phép tạo Nhân viên rạp tại rạp của họ
            if (currentRole === 3) {
                if (currentRap) {
                    rapSelect.value = String(currentRap);
                    rapSelect.disabled = true;
                    rapSelect.insertAdjacentHTML('afterend', '<small style="display:block;margin-top:6px;opacity:.7">Rạp mặc định theo tài khoản quản lý rạp.</small>');
                }
                // Ẩn các lựa chọn role khác ngoài 1 (Nhân viên rạp)
                Array.from(roleSelect.options).forEach(function(opt){
                    if (opt.value !== '1' && opt.value !== '') opt.disabled = true;
                });
                roleSelect.value = '1';
                document.getElementById('role_hint').textContent = 'Quản lý rạp chỉ có thể tạo tài khoản Nhân viên rạp thuộc rạp của mình.';
            }

            roleSelect.addEventListener('change', applyRapVisibility);
            applyRapVisibility();
        })();
    </script>
</div><!-- Content Body End -->
