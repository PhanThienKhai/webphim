<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20">
        <div class="col-12 col-lg-8">
            <div class="page-heading"><h3>Sửa rạp chiếu</h3></div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="index.php?act=suarp&id=<?= (int)($rp['id'] ?? 0) ?>">
        <div class="row">
            <div class="col-12 col-md-6 mb-15">
                <label>Tên rạp</label>
                <input class="form-control" type="text" name="ten_rap" required value="<?= htmlspecialchars($rp['ten_rap'] ?? '') ?>" />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Số điện thoại</label>
                <input class="form-control" type="text" name="so_dien_thoai" required value="<?= htmlspecialchars($rp['so_dien_thoai'] ?? '') ?>" />
            </div>
            <div class="col-12 mb-15">
                <label>Địa chỉ</label>
                <input class="form-control" type="text" name="dia_chi" required value="<?= htmlspecialchars($rp['dia_chi'] ?? '') ?>" />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Email</label>
                <input class="form-control" type="email" name="email" required value="<?= htmlspecialchars($rp['email'] ?? '') ?>" />
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Logo (tùy chọn, thay mới)</label>
                <input class="form-control" type="file" name="logo" accept="image/*" />
            </div>
            <div class="col-12 mb-15">
                <label>Mô tả</label>
                <textarea class="form-control" name="mo_ta" rows="4"><?= htmlspecialchars($rp['mo_ta'] ?? '') ?></textarea>
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Trạng thái vận hành</label>
                <select class="form-control" name="trang_thai">
                    <?php $tt = isset($rp['trang_thai']) ? (int)$rp['trang_thai'] : 1; ?>
                    <option value="1" <?= $tt===1 ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="0" <?= $tt===0 ? 'selected' : '' ?>>Khóa</option>
                </select>
            </div>
            <div class="col-12">
                <button class="button button-primary" type="submit" name="capnhat" value="1">Cập nhật</button>
                <a class="button" href="index.php?act=QLrap">Hủy</a>
            </div>
        </div>
    </form>
</div>
