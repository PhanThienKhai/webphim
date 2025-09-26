<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Khuyến mãi (Mã giảm giá)</h3></div></div>
        <div class="col-12 col-lg-auto"><a class="button button-primary" href="index.php?act=km_them">+ Thêm mã</a></div>
    </div>

    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Tên KM</th><th>Loại</th><th>Giảm</th><th>Hiệu lực</th><th>Điều kiện</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
                <?php foreach (($ds_km ?? []) as $km): ?>
                    <tr>
                        <td><?= htmlspecialchars($km['ten_khuyen_mai'] ?? '') ?></td>
                        <td><?= htmlspecialchars($km['loai_giam'] ?? '') ?></td>
                        <td>
                            <?php if (($km['loai_giam'] ?? '')==='phan_tram'): ?>
                                <?= number_format((float)($km['phan_tram_giam'] ?? 0), 2) ?>%
                            <?php else: ?>
                                <?= number_format((int)($km['gia_tri_giam'] ?? 0)) ?> VND
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(($km['ngay_bat_dau'] ?? '').' → '.($km['ngay_ket_thuc'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($km['dieu_kien_ap_dung'] ?? '') ?></td>
                        <td><?= ((int)($km['trang_thai'] ?? 1)===1?'Hoạt động':'Tắt') ?></td>
                        <td>
                            <a class="button button-sm" href="index.php?act=km_toggle&id=<?= (int)($km['id'] ?? 0) ?>">Đổi trạng thái</a>
                            <a class="button button-sm button-info" href="index.php?act=km_sua&id=<?= (int)($km['id'] ?? 0) ?>">Sửa</a>
                            <a class="button button-sm button-danger" href="index.php?act=km_xoa&id=<?= (int)($km['id'] ?? 0) ?>" onclick="return confirm('Xóa mã này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
