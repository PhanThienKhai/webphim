<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Cấu hình website</h3></div></div></div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="index.php?act=cauhinh">
        <div class="row">
            <div class="col-12 col-md-6 mb-15"><label>Tên website</label><input class="form-control" type="text" name="ten_website" value="<?= htmlspecialchars($cfg['ten_website'] ?? '') ?>" required></div>
            <div class="col-12 col-md-6 mb-15"><label>Logo</label><input class="form-control" type="file" name="logo" accept="image/*"></div>
            <div class="col-12 mb-15"><label>Địa chỉ</label><input class="form-control" type="text" name="dia_chi" value="<?= htmlspecialchars($cfg['dia_chi'] ?? '') ?>"></div>
            <div class="col-12 col-md-4 mb-15"><label>Số điện thoại</label><input class="form-control" type="text" name="so_dien_thoai" value="<?= htmlspecialchars($cfg['so_dien_thoai'] ?? '') ?>"></div>
            <div class="col-12 col-md-4 mb-15"><label>Email</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars($cfg['email'] ?? '') ?>"></div>
            <div class="col-12 col-md-4 mb-15"><label>Facebook</label><input class="form-control" type="text" name="facebook" value="<?= htmlspecialchars($cfg['facebook'] ?? '') ?>"></div>
            <div class="col-12 col-md-4 mb-15"><label>Instagram</label><input class="form-control" type="text" name="instagram" value="<?= htmlspecialchars($cfg['instagram'] ?? '') ?>"></div>
            <div class="col-12 col-md-4 mb-15"><label>Youtube</label><input class="form-control" type="text" name="youtube" value="<?= htmlspecialchars($cfg['youtube'] ?? '') ?>"></div>
            <div class="col-12 mb-15"><label>Mô tả</label><textarea class="form-control" name="mo_ta" rows="4"><?= htmlspecialchars($cfg['mo_ta'] ?? '') ?></textarea></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="luu" value="1">Lưu cấu hình</button></div>
        </div>
    </form>
</div>

