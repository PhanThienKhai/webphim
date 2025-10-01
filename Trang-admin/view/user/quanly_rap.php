<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Quản Lý Tài Khoản / <span>Quản lý rạp</span></h3></div></div>
    </div>

    <div class="table-responsive">
        <table class="table table-vertical-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Rạp</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($ds_qllr ?? []) as $u): ?>
                    <tr>
                        <td>#<?= (int)$u['id'] ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['user']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['ten_rap'] ?? '') ?></td>
                        <td><?= htmlspecialchars($u['ngay_tao'] ?? '') ?></td>
                        <td class="action h4">
                            <div class="table-action-buttons">
                                <a class="edit button button-box button-xs button-info" href="index.php?act=suatk&idsua=<?= (int)$u['id'] ?>"><i class="zmdi zmdi-edit"></i></a>
                                <a class="delete button button-box button-xs button-danger" href="index.php?act=xoatk&idxoa=<?= (int)$u['id'] ?>" onclick="return confirm('Xóa tài khoản này?')"><i class="zmdi zmdi-delete"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

