<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20">
        <div class="col-12 col-lg-8">
            <div class="page-heading"><h3>Thêm rạp chiếu</h3></div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="index.php?act=themrp">
        <div class="row">
            <div class="col-12 col-md-6 mb-15">
                <label>Tên rạp</label>
                <input class="form-control" type="text" name="ten_rap" required />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Số điện thoại</label>
                <input class="form-control" type="text" name="so_dien_thoai" required />
            </div>
            <div class="col-12 mb-15">
                <label>Địa chỉ</label>
                <input class="form-control" type="text" name="dia_chi" required />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Email</label>
                <input class="form-control" type="email" name="email" required />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Logo</label>
                <input class="form-control" type="file" name="logo" accept="image/*" />
            </div>
            <div class="col-12 mb-15">
                <label>Mô tả</label>
                <textarea class="form-control" name="mo_ta" rows="4"></textarea>
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Trạng thái vận hành</label>
                <select class="form-control" name="trang_thai">
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Khóa</option>
                </select>
            </div>
            <div class="col-12">
                <button class="button button-primary" type="submit" name="luu" value="1">Lưu</button>
                <a class="button" href="index.php?act=QLrap">Hủy</a>
            </div>
        </div>
    </form>
</div>
