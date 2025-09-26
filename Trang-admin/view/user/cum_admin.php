<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Quản lý tài khoản Admin hệ thống</h3></div></div>
    </div>

    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=cum_admin" class="mb-20">
        <div class="row">
            <div class="col-12 col-md-4 mb-15"><label>Tên</label><input class="form-control" type="text" name="name" required></div>
            <div class="col-12 col-md-4 mb-15"><label>Tài khoản</label><input class="form-control" type="text" name="user" required></div>
            <div class="col-12 col-md-4 mb-15"><label>Mật khẩu</label><input class="form-control" type="text" name="pass" required></div>
            <div class="col-12 col-md-4 mb-15"><label>Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="col-12 col-md-4 mb-15"><label>Điện thoại</label><input class="form-control" type="text" name="phone"></div>
            <div class="col-12 col-md-4 mb-15"><label>Địa chỉ</label><input class="form-control" type="text" name="dia_chi"></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="them" value="1">Thêm Admin hệ thống</button></div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Tên</th><th>User</th><th>Email</th><th>Rạp</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
            <tbody>
                <?php foreach (($ds_admin ?? []) as $ad): ?>
                    <tr>
                        <td><?= (int)$ad['id'] ?></td>
                        <td><?= htmlspecialchars($ad['name']) ?></td>
                        <td><?= htmlspecialchars($ad['user']) ?></td>
                        <td><?= htmlspecialchars($ad['email']) ?></td>
                        <td><?= htmlspecialchars($ad['ten_rap'] ?? '') ?></td>
                        <td><?= htmlspecialchars($ad['ngay_tao']) ?></td>
                        <td>
                            <a class="button button-sm button-info" href="index.php?act=suatk&idsua=<?= (int)$ad['id'] ?>">Sửa</a>
                            <a class="button button-sm button-danger" href="index.php?act=cum_admin_xoa&id=<?= (int)$ad['id'] ?>" onclick="return confirm('Xóa tài khoản này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
