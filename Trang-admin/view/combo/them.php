<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="page-heading"><h3>Thêm Combo</h3></div>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="index.php?act=combo_them">
        <div class="row">
            <div class="col-12 col-md-6 mb-15"><label>Tên</label><input class="form-control" type="text" name="ten" required></div>
            <div class="col-12 col-md-3 mb-15"><label>Giá</label><input class="form-control" type="number" name="gia" min="0" required></div>
            <div class="col-12 col-md-3 mb-15"><label>Trạng thái</label>
                <select class="form-control" name="trang_thai"><option value="1">Hiển thị</option><option value="0">Ẩn</option></select>
            </div>
            <div class="col-12 col-md-6 mb-15"><label>Hình ảnh</label><input class="form-control" type="file" name="hinh" accept="image/*"></div>
            <div class="col-12 mb-15"><label>Mô tả</label><textarea class="form-control" name="mo_ta" rows="4"></textarea></div>
            <div class="col-12">
                <button class="button button-primary" type="submit" name="luu" value="1">Lưu</button>
                <a class="button" href="index.php?act=QLcombo">Hủy</a>
            </div>
        </div>
    </form>
</div>

