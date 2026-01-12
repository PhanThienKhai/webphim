<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Thống kê theo rạp</h3></div></div></div>

    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="TKrap" />
        <div class="row align-items-end">
            <div class="col-12 col-md-6 mb-10">
                <label>Khoảng thời gian</label>
                <div class="d-flex" style="gap:8px; align-items:center;">
                    <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
                    <span>đến</span>
                    <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
                    <button class="button button-primary" type="submit">Lọc</button>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-10">
                <label>Nhanh</label>
                <div>
                    <a class="button" href="index.php?act=TKrap&period=week">Tuần này</a>
                    <a class="button" href="index.php?act=TKrap&period=month">Tháng này</a>
                    <a class="button" href="index.php?act=TKrap&period=quarter">Quý này</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Rạp</th><th>Số vé</th><th>Doanh thu (VND)</th></tr></thead>
            <tbody>
                <?php foreach (($tk_rap ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ten_rap']) ?></td>
                        <td><?= (int)$r['so_ve'] ?></td>
                        <td><?= number_format((int)$r['doanh_thu']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
