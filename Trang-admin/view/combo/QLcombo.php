<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Khuyến mãi / Combo</h3></div></div>
        <div class="col-12 col-lg-auto"><a class="button button-primary" href="index.php?act=combo_them">+ Thêm combo</a></div>
    </div>

    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Tên</th><th>Giá</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
                <?php foreach (($ds_combo ?? []) as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['ten_combo']) ?></td>
                        <td><?= number_format((int)$c['gia']) ?></td>
                        <td><?= ((int)$c['trang_thai']===1?'Hiển thị':'Ẩn') ?></td>
                        <td>
                            <a class="button button-sm" href="index.php?act=combo_toggle&id=<?= (int)$c['id'] ?>">Đổi trạng thái</a>
                            <a class="button button-sm button-info" href="index.php?act=combo_sua&id=<?= (int)$c['id'] ?>">Sửa</a>
                            <a class="button button-sm button-danger" href="index.php?act=combo_xoa&id=<?= (int)$c['id'] ?>" onclick="return confirm('Xóa combo này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

