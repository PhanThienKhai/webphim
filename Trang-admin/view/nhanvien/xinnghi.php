<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Xin nghỉ phép</h3></div></div></div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=xinnghi">
        <div class="row">
            <div class="col-12 col-md-4 mb-15">
                <label>Từ ngày</label>
                <input class="form-control" type="date" name="tu_ngay" required />
            </div>
            <div class="col-12 col-md-4 mb-15">
                <label>Đến ngày</label>
                <input class="form-control" type="date" name="den_ngay" required />
            </div>
            <div class="col-12 mb-15">
                <label>Lý do</label>
                <input class="form-control" type="text" name="ly_do" required />
            </div>
            <div class="col-12">
                <button class="button button-primary" type="submit" name="gui" value="1">Gửi yêu cầu</button>
            </div>
        </div>
    </form>

    <div class="row mt-30">
        <div class="col-12">
            <h5>Lịch sử xin nghỉ</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Thời gian</th><th>Lý do</th><th>Trạng thái</th><th>Rạp</th></tr></thead>
                    <tbody>
                        <?php foreach (($dnp_cua_toi ?? []) as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['tu_ngay']) ?> → <?= htmlspecialchars($d['den_ngay']) ?></td>
                                <td><?= htmlspecialchars($d['ly_do']) ?></td>
                                <td><?= htmlspecialchars($d['trang_thai']) ?></td>
                                <td><?= htmlspecialchars($d['ten_rap']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

