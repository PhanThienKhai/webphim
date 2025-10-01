<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="page-heading"><h3>Bảng lương theo tháng</h3></div>
    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="bangluong" />
        <div class="row align-items-end">
            <div class="col-12 col-md-3 mb-10"><label>Tháng</label><input class="form-control" type="month" name="ym" value="<?= htmlspecialchars($ym) ?>" /></div>
            <div class="col-12 col-md-3 mb-10"><label>Đơn giá / giờ (VND)</label><input class="form-control" type="number" name="rate" value="<?= (int)$rate ?>" /></div>
            <div class="col-12 col-md-2 mb-10"><button class="button" type="submit">Tính</button></div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Nhân viên</th><th>Số giờ</th><th>Lương (VND)</th></tr></thead>
            <tbody>
                <?php foreach (($ds_luong ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ten_nv']) ?></td>
                        <td><?= number_format($r['so_gio'], 2) ?></td>
                        <td><?= number_format((int)$r['luong']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

