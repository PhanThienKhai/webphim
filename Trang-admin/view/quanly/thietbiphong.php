<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Thiết bị phòng chiếu</h3></div></div></div>

    <form method="get" action="index.php">
        <input type="hidden" name="act" value="thietbiphong" />
        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-15">
                <label>Chọn phòng</label>
                <select class="form-control" name="id_phong" onchange="this.form.submit()">
                    <option value="">-- Chọn --</option>
                    <?php foreach (($ds_phong ?? []) as $p): ?>
                        <option value="<?= (int)$p['id'] ?>" <?= isset($id_phong) && $id_phong == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if (!empty($id_phong)): ?>
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="post" action="index.php?act=thietbiphong&id_phong=<?= (int)$id_phong ?>" class="mb-20">
            <div class="row">
                <div class="col-12 col-md-4 mb-15"><label>Thiết bị</label><input class="form-control" type="text" name="ten" required /></div>
                <div class="col-6 col-md-2 mb-15"><label>Số lượng</label><input class="form-control" type="number" name="so_luong" value="1" min="1" /></div>
                <div class="col-6 col-md-3 mb-15"><label>Tình trạng</label>
                    <select class="form-control" name="tinh_trang">
                        <option value="tot">Tốt</option>
                        <option value="can_bao_tri">Cần bảo trì</option>
                        <option value="hong">Hỏng</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 mb-15"><label>Ghi chú</label><input class="form-control" type="text" name="ghi_chu" /></div>
                <div class="col-12"><button class="button button-primary" type="submit" name="them" value="1">Thêm thiết bị</button></div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr><th>Thiết bị</th><th>Số lượng</th><th>Tình trạng</th><th>Ghi chú</th><th></th></tr></thead>
                <tbody>
                    <?php foreach (($ds_tb ?? []) as $tb): ?>
                        <tr>
                            <td><?= htmlspecialchars($tb['ten_thiet_bi']) ?></td>
                            <td><?= (int)$tb['so_luong'] ?></td>
                            <td><?= htmlspecialchars($tb['tinh_trang']) ?></td>
                            <td><?= htmlspecialchars($tb['ghi_chu']) ?></td>
                            <td><a class="button button-sm button-danger" href="index.php?act=thietbiphong&id_phong=<?= (int)$id_phong ?>&xoa=<?= (int)$tb['id'] ?>" onclick="return confirm('Xóa thiết bị này?')">Xóa</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

