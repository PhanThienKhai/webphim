<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Lịch chiếu theo rạp</h3></div></div></div>

    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="lich_rap" />
        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-10">
                <label>Chọn rạp</label>
                <select class="form-control" name="id_rap">
                    <option value="">-- Chọn rạp --</option>
                    <?php foreach (($ds_rap ?? []) as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= isset($id_rap) && (int)$id_rap === (int)$r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['ten_rap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2 mb-10">
                <label>Chế độ xem</label>
                <select class="form-control" name="view">
                    <option value="day" <?= ($view==='day'?'selected':'') ?>>Theo ngày</option>
                    <option value="week" <?= ($view==='week'?'selected':'') ?>>Theo tuần</option>
                </select>
            </div>
            <div class="col-6 col-md-2 mb-10">
                <label>Ngày gốc</label>
                <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($date) ?>" />
            </div>
            <div class="col-12 col-md-2 mb-10">
                <button class="button button-primary" type="submit">Xem</button>
            </div>
        </div>
    </form>

    <?php if (!empty($id_rap)): ?>
    <div class="mb-10"><strong>Khoảng thời gian:</strong> <?= htmlspecialchars($from_date) ?> đến <?= htmlspecialchars($to_date) ?></div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Ngày chiếu</th><th>Phim</th><th>Rạp</th><th>Trạng thái duyệt</th></tr></thead>
            <tbody>
                <?php foreach (($ds_lich ?? []) as $lc): ?>
                    <tr>
                        <td><?= htmlspecialchars($lc['ngay_chieu']) ?></td>
                        <td><?= htmlspecialchars($lc['tieu_de']) ?></td>
                        <td><?= htmlspecialchars($lc['ten_rap']) ?></td>
                        <td><?= htmlspecialchars($lc['trang_thai_duyet'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

