<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <!-- Header Section -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Tài Khoản / <span>Admin Hệ Thống</span></h3>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success mb-20">
            ✓ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger mb-20">
            ✕ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="news-item">
                <div class="content">
                    <h4 style="margin-top: 0; color: #333; font-size: 16px;">Thêm Admin Hệ Thống Mới</h4>
                    <form method="post" action="index.php?act=cum_admin">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tên Admin</label>
                                <input class="form-control" type="text" name="name" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Tài Khoản</label>
                                <input class="form-control" type="text" name="user" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Mật Khẩu</label>
                                <input class="form-control" type="text" name="pass" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Email</label>
                                <input class="form-control" type="email" name="email" required style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Điện Thoại</label>
                                <input class="form-control" type="text" name="phone" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12 col-md-6 mb-15">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px;">Địa Chỉ</label>
                                <input class="form-control" type="text" name="dia_chi" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; font-size: 13px;">
                            </div>
                            <div class="col-12">
                                <button class="button button-primary" type="submit" name="them" value="1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                    <i class="zmdi zmdi-plus"></i> Thêm Admin
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="news-item">
                <div class="content">
                    <div class="table-responsive">
                        <?php if(!empty($ds_admin)): ?>
                            <table class="table table-vertical-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Tên Admin</th>
                                        <th>Tài Khoản</th>
                                        <th>Email</th>
                                        <th>Điện Thoại</th>
                                        <th>Ngày Tạo</th>
                                        <th style="width: 90px;">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ds_admin as $ad): ?>
                                        <tr>
                                            <td><span style="color: #667eea; font-weight: 600;">#<?= (int)$ad['id'] ?></span></td>
                                            <td><?= htmlspecialchars($ad['name']) ?></td>
                                            <td><code><?= htmlspecialchars($ad['user']) ?></code></td>
                                            <td><?= htmlspecialchars($ad['email']) ?></td>
                                            <td><?= htmlspecialchars($ad['phone'] ?? '—') ?></td>
                                            <td><?= !empty($ad['ngay_tao']) ? date('d/m/Y', strtotime($ad['ngay_tao'])) : '—' ?></td>
                                            <td>
                                                <div class="table-action-buttons">
                                                    <a class="edit button button-box button-xs button-info" href="index.php?act=suatk&idsua=<?= (int)$ad['id'] ?>" title="Chỉnh sửa">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </a>
                                                    <a class="delete button button-box button-xs button-danger" href="index.php?act=cum_admin_xoa&id=<?= (int)$ad['id'] ?>" onclick="return confirm('Xóa tài khoản này?')" title="Xóa">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: #999;">
                                <p style="font-size: 40px; margin: 0;">📭</p>
                                <p style="margin-top: 10px;">Không có admin hệ thống nào</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
