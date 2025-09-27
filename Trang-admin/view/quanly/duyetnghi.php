<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Duyệt đơn nghỉ phép</h3></div></div></div>

    <?php if (!empty($msg)): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Nhân viên</th><th>Khoảng thời gian</th><th>Lý do</th><th>Trạng thái</th><th>Hành động</th></tr></thead>
            <tbody>
                <?php foreach (($dnp ?? []) as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['ten_nv']) ?></td>
                        <td><?= htmlspecialchars($d['tu_ngay']) ?> → <?= htmlspecialchars($d['den_ngay']) ?></td>
                        <td><?= htmlspecialchars($d['ly_do']) ?></td>
                        <td><?= htmlspecialchars($d['trang_thai']) ?></td>
                        <td>
                            <?php if ($d['trang_thai'] === 'Chờ duyệt'): ?>
                                <a class="button button-sm button-success" href="index.php?act=ql_duyetnghi&duyet=<?= (int)$d['id'] ?>">Duyệt</a>
                                <a class="button button-sm button-danger" href="index.php?act=ql_duyetnghi&tuchoi=<?= (int)$d['id'] ?>">Từ chối</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

