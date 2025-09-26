<?php
include "./view/home/sideheader.php";

?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">

        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3> Quản Lý Tài Khoản <span>/ Thêm Tài Khoản Quản Trị</span></h3>
            </div>
        </div><!-- Page Heading End -->

        <!-- Page Button Group Start -->

    </div><!-- Page Headings End -->
    <?php if(isset($suatc)&&($suatc)!= ""){
        echo'<p  style="color: red; text-align: center;">' .$suatc. '</p>';
    }
    ?> 
    <!-- Add or Edit Product Start -->
    <form action="index.php?act=themuser" method="POST" class="adm-form">
        <div class="add-edit-product-wrap col-12">

            <div class="add-edit-product-form card">

                <div class="section-title">Thông tin tài khoản</div>
                <div class="form-grid">
                    <div class="field"><label>Tên</label><input class="form-control" type="text" placeholder="Nhập tên" name="name" required></div>
                    <div class="field"><label>Tài khoản</label><input class="form-control" type="text" placeholder="Tên đăng nhập" name="user" required></div>
                    <div class="field"><label>Mật khẩu</label><input class="form-control" type="password" placeholder="Mật khẩu" name="pass" required></div>
                    <div class="field"><label>Email</label><input class="form-control" type="email" placeholder="Email" name="email" required></div>
                    <div class="field"><label>Số Điện Thoại</label><input class="form-control" type="text" placeholder="Số điện thoại" name="phone"></div>
                    <div class="field"><label>Địa Chỉ</label><input class="form-control" type="text" placeholder="Địa chỉ" name="dia_chi"></div>
                    <div class="field"><label>Vai trò</label>
                        <select class="form-control" name="vai_tro" id="vai_tro" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="0">Khách hàng</option>
                            <option value="1">Nhân viên rạp</option>
                            <option value="3">Quản lý rạp</option>
                            <option value="2">Admin hệ thống</option>
                            <option value="4">Quản lý cụm rạp</option>
                        </select>
                        <small id="role_hint" class="hint" style="display:block;margin-top:6px;opacity:.8"></small>
                    </div>
                    <div class="field" id="wrap_rap"><label>Rạp <span class="hint">Bắt buộc với Nhân viên/Quản lý rạp</span></label>
                        <select class="form-control" name="id_rap" id="id_rap">
                            <option value="">-- Chọn rạp --</option>
                            <?php foreach (rap_all() as $r): ?>
                                <option value="<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['ten_rap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h4 class="title">Thao tác</h4>

                <div class="product-upload-gallery row flex-wrap actions">


                    <!-- Button Group Start -->
                    <div class="row">
                        <div class="d-flex flex-wrap justify-content-end col mbn-10">
                            <button class="button button-primary mb-10 ml-10 mr-0" type="submit" name="them">Thêm</button>
                            <a class="button button-outline mb-10 ml-10 mr-0" href="index.php?act=QTvien">Danh sách</a>
                        </div>
                    </div><!-- Button Group End -->

                </div>
                    </div><!-- Add or Edit Product End -->
                    <?php if(isset($error)&&$error !=""){
                echo '<p  style="color: red; text-align: center;"
                > '.$error.' </p>';
            } ?>
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
