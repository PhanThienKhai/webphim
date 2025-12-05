<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto mb-10">
            <div class="page-heading"><h3>Quản lý rạp chiếu</h3></div>
        </div>
        <div class="col-12 col-lg-auto mb-10">
            <a class="button button-primary" href="index.php?act=themrp">+ Thêm rạp</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên rạp</th>
                            <th>Địa chỉ</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach (($ds_rap ?? []) as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id']) ?></td>
                            <td><?= htmlspecialchars($r['ten_rap']) ?></td>
                            <td><?= htmlspecialchars($r['dia_chi']) ?></td>
                            <td><?= htmlspecialchars($r['so_dien_thoai']) ?></td>
                            <td><?= htmlspecialchars($r['email']) ?></td>
                            <td><?= (int)$r['trang_thai'] === 1 ? 'Hoạt động' : 'Khóa' ?></td>
                            <td>
                                <a class="button button-sm button-primary" href="index.php?act=suarp&id=<?= (int)$r['id'] ?>">Sửa</a>
                                <a class="button button-sm button-danger" onclick="return confirm('Xóa rạp này?');" href="index.php?act=xoarp&id=<?= (int)$r['id'] ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

