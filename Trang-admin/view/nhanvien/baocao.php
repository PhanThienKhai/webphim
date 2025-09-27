<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Báo cáo cá nhân</h3></div></div></div>

    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="nv_baocao" />
        <div class="row align-items-end">
            <div class="col-6 col-md-3 mb-10"><label>Từ ngày</label><input class="form-control" type="date" name="from" value="<?= htmlspecialchars($_GET['from'] ?? date('Y-m-d', strtotime('-30 days'))) ?>" /></div>
            <div class="col-6 col-md-3 mb-10"><label>Đến ngày</label><input class="form-control" type="date" name="to" value="<?= htmlspecialchars($_GET['to'] ?? date('Y-m-d')) ?>" /></div>
            <div class="col-6 col-md-3 mb-10"><label>Tháng tính công</label><input class="form-control" type="month" name="ym" value="<?= htmlspecialchars($_GET['ym'] ?? date('Y-m')) ?>" /></div>
            <div class="col-12 col-md-2 mb-10"><button class="button" type="submit">Lọc</button></div>
        </div>
    </form>

    <div class="row mb-20">
        <div class="col-12 col-md-4"><div class="card-sm"><strong>Tổng vé bán:</strong> <?= (int)($sum['so_ve'] ?? 0) ?></div></div>
        <div class="col-12 col-md-4"><div class="card-sm"><strong>Doanh thu:</strong> <?= number_format((int)($sum['doanh_thu'] ?? 0)) ?> đ</div></div>
        <div class="col-12 col-md-4"><div class="card-sm"><strong>Giờ công (<?= htmlspecialchars($_GET['ym'] ?? date('Y-m')) ?>):</strong> <?= number_format((float)($sum_hours ?? 0),2) ?> h</div></div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Ngày</th><th>Số vé</th><th>Doanh thu</th></tr></thead>
            <tbody>
                <?php foreach (($by_date ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ngay']) ?></td>
                        <td><?= (int)$r['so_ve'] ?></td>
                        <td><?= number_format((int)$r['doanh_thu']) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

