<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Doanh thu theo phim <?= !empty($id_rap) ? '(Rạp đã chọn)' : '(Toàn hệ thống)' ?></h3></div></div></div>

    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="DTphim_rap" />
        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-10">
                <label>Chọn rạp</label>
                <select class="form-control" name="id_rap">
                    <option value="0">— Toàn hệ thống —</option>
                    <?php foreach (($ds_rap ?? []) as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= isset($id_rap) && (int)$id_rap === (int)$r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['ten_rap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-5 mb-10">
                <label>Khoảng thời gian</label>
                <div class="d-flex" style="gap:8px; align-items:center;">
                    <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
                    <span>đến</span>
                    <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
                    <button class="button button-primary" type="submit">Lọc</button>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-10">
                <label>Nhanh</label>
                <div>
                    <a class="button" href="index.php?act=DTphim_rap&period=week<?php if(!empty($id_rap)) echo '&id_rap='.(int)$id_rap; ?>">Tuần này</a>
                    <a class="button" href="index.php?act=DTphim_rap&period=month<?php if(!empty($id_rap)) echo '&id_rap='.(int)$id_rap; ?>">Tháng này</a>
                    <a class="button" href="index.php?act=DTphim_rap&period=quarter<?php if(!empty($id_rap)) echo '&id_rap='.(int)$id_rap; ?>">Quý này</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Phim</th><th>Số vé</th><th>Doanh thu (VND)</th></tr></thead>
            <tbody>
                <?php foreach (($dt_phim_rap ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['tieu_de']) ?></td>
                        <td><?= (int)$r['so_ve'] ?></td>
                        <td><?= number_format((int)$r['doanh_thu']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

