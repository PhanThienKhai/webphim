<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="page-heading"><h3>Sửa mã khuyến mãi</h3></div>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="post" action="index.php?act=km_sua&id=<?= (int)($row['id'] ?? 0) ?>">
        <div class="row">
            <div class="col-12 col-md-4 mb-15"><label>Tên khuyến mãi</label><input class="form-control" type="text" name="ten_khuyen_mai" value="<?= htmlspecialchars($row['ten_khuyen_mai'] ?? '') ?>" required></div>
            <div class="col-12 col-md-2 mb-15"><label>Loại giảm</label><select class="form-control" name="loai_giam">
                    <?php $loai = $row['loai_giam'] ?? 'phan_tram'; ?>
                    <option value="phan_tram" <?= $loai==='phan_tram'?'selected':'' ?>>%</option>
                    <option value="tien_mat" <?= $loai==='tien_mat'?'selected':'' ?>>VND</option>
                </select></div>
            <div class="col-6 col-md-2 mb-15"><label>% Giảm</label><input class="form-control" type="number" name="phan_tram_giam" step="0.01" value="<?= htmlspecialchars($row['phan_tram_giam'] ?? '') ?>"></div>
            <div class="col-6 col-md-2 mb-15"><label>Số tiền giảm</label><input class="form-control" type="number" name="gia_tri_giam" value="<?= htmlspecialchars($row['gia_tri_giam'] ?? '') ?>"></div>
            <div class="col-6 col-md-2 mb-15"><label>Trạng thái</label><select class="form-control" name="trang_thai">
                    <?php $tt=(int)($row['trang_thai'] ?? 1); ?>
                    <option value="1" <?= $tt===1?'selected':'' ?>>Hoạt động</option>
                    <option value="0" <?= $tt===0?'selected':'' ?>>Tắt</option>
                </select></div>
            <div class="col-6 col-md-3 mb-15"><label>Từ ngày</label><input class="form-control" type="date" name="ngay_bat_dau" value="<?= htmlspecialchars($row['ngay_bat_dau'] ?? '') ?>"></div>
            <div class="col-6 col-md-3 mb-15"><label>Đến ngày</label><input class="form-control" type="date" name="ngay_ket_thuc" value="<?= htmlspecialchars($row['ngay_ket_thuc'] ?? '') ?>"></div>
            <div class="col-12 col-md-6 mb-15"><label>Điều kiện áp dụng</label><input class="form-control" type="text" name="dieu_kien_ap_dung" value="<?= htmlspecialchars($row['dieu_kien_ap_dung'] ?? '') ?>"></div>
            <div class="col-12 mb-15"><label>Mô tả</label><textarea class="form-control" name="mo_ta" rows="3"><?= htmlspecialchars($row['mo_ta'] ?? '') ?></textarea></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="capnhat" value="1">Lưu</button> <a class="button" href="index.php?act=QLkm">Hủy</a></div>
        </div>
    </form>
</div>
