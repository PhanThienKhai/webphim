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
            <thead><tr><th>Mã code</th><th>Tên KM</th><th>Loại</th><th>Giảm</th><th>Hiệu lực</th><th>Điều kiện</th><th>Rạp áp dụng</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
                <?php foreach (($ds_km ?? []) as $km): ?>
                    <tr>
                        <td>
                            <strong style="font-family:monospace;color:#3b82f6;font-size:14px">
                                <?= htmlspecialchars($km['ma_khuyen_mai'] ?? 'N/A') ?>
                            </strong>
                        </td>
                        <td><?= htmlspecialchars($km['ten_khuyen_mai'] ?? '') ?></td>
                        <td>
                            <?php 
                            $loai_text = ($km['loai_giam'] ?? '') === 'phan_tram' ? 'Phần trăm' : 'Tiền mặt';
                            ?>
                            <span style="display:inline-block;padding:4px 10px;border-radius:4px;font-size:12px;font-weight:600;
                                        background:<?= ($km['loai_giam'] ?? '') === 'phan_tram' ? '#dbeafe' : '#fef3c7' ?>;
                                        color:<?= ($km['loai_giam'] ?? '') === 'phan_tram' ? '#1e40af' : '#92400e' ?>">
                                <?= $loai_text ?>
                            </span>
                        </td>
                        <td>
                            <?php if (($km['loai_giam'] ?? '')==='phan_tram'): ?>
                                <span style="color:#10b981;font-weight:600"><?= number_format((float)($km['phan_tram_giam'] ?? 0), 0) ?>%</span>
                            <?php else: ?>
                                <span style="color:#f59e0b;font-weight:600"><?= number_format((int)($km['gia_tri_giam'] ?? 0)) ?>đ</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:13px"><?= htmlspecialchars(($km['ngay_bat_dau'] ?? '').' → '.($km['ngay_ket_thuc'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($km['dieu_kien_ap_dung'] ?? '') ?></td>
                        <td>
                            <?php if (empty($km['id_rap'])): ?>
                                <span style="color:#8b5cf6;font-weight:600">🌐 Toàn cụm</span>
                            <?php else: ?>
                                <span style="color:#6b7280">Rạp #<?= (int)$km['id_rap'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)($km['trang_thai'] ?? 1)===1): ?>
                                <span style="color:#10b981;font-weight:600">✓ Hoạt động</span>
                            <?php else: ?>
                                <span style="color:#ef4444">✗ Tắt</span>
                            <?php endif; ?>
                        </td>
                        <td style="white-space:nowrap">
                            <a class="button button-sm" href="index.php?act=km_toggle&id=<?= (int)($km['id'] ?? 0) ?>">Đổi TT</a>
                            <a class="button button-sm button-info" href="index.php?act=km_sua&id=<?= (int)($km['id'] ?? 0) ?>">Sửa</a>
                            <a class="button button-sm button-danger" href="index.php?act=km_xoa&id=<?= (int)($km['id'] ?? 0) ?>" onclick="return confirm('Xóa mã này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
