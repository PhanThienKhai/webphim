<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Yêu cầu đổi/hoàn vé</h3></div></div>
    </div>

    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=doi_hoan_ve" class="mb-20">
        <div class="row">
            <div class="col-12 col-md-2 mb-10"><label>ID vé</label><input class="form-control" type="number" name="id_ve" required></div>
            <div class="col-12 col-md-2 mb-10"><label>Loại</label>
                <select class="form-control" name="loai"><option value="doi">Đổi</option><option value="hoan">Hoàn</option></select>
            </div>
            <div class="col-12 col-md-3 mb-10"><label>Trạng thái mới (tuỳ chọn)</label><input class="form-control" type="number" name="trang_thai_moi" placeholder="VD: 0=Huỷ, 2=Đổi ..."></div>
            <div class="col-12 col-md-5 mb-10"><label>Lý do</label><input class="form-control" type="text" name="ly_do"></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="tao" value="1">Tạo yêu cầu</button></div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>ID vé</th><th>Loại</th><th>Lý do</th><th>Trạng thái</th><th>Ngày tạo</th><th></th></tr></thead>
            <tbody>
                <?php foreach (($ds_yc ?? []) as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= (int)$r['id_ve'] ?></td>
                        <td><?= htmlspecialchars($r['loai']) ?></td>
                        <td><?= htmlspecialchars($r['ly_do']) ?></td>
                        <td><?= htmlspecialchars($r['trang_thai']) ?></td>
                        <td><?= htmlspecialchars($r['ngay_tao']) ?></td>
                        <td>
                            <a class="button button-sm button-success" href="index.php?act=duyet_doihoan&id=<?= (int)$r['id'] ?>">Duyệt</a>
                            <a class="button button-sm button-warning" href="index.php?act=tuchoi_doihoan&id=<?= (int)$r['id'] ?>">Từ chối</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

