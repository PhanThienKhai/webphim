<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <!-- Header Section -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Tài Khoản / <span>🎫 Người Quản Lý Rạp</span></h3>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="news-item">
                <div class="content">
                    <div class="table-responsive">
                        <?php if(!empty($ds_qllr)): ?>
                            <table class="table table-vertical-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Tên Quản Lý</th>
                                        <th>Tài Khoản</th>
                                        <th>Email</th>
                                        <th>Rạp Quản Lý</th>
                                        <th>Ngày Tạo</th>
                                        <th style="width: 90px;">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ds_qllr as $u): ?>
                                        <tr>
                                            <td><span style="color: #4facfe; font-weight: 600;">#<?= (int)$u['id'] ?></span></td>
                                            <td><?= htmlspecialchars($u['name']) ?></td>
                                            <td><code><?= htmlspecialchars($u['user']) ?></code></td>
                                            <td><?= htmlspecialchars($u['email']) ?></td>
                                            <td><strong><?= htmlspecialchars($u['ten_rap'] ?? '—') ?></strong></td>
                                            <td><?= !empty($u['ngay_tao']) ? date('d/m/Y', strtotime($u['ngay_tao'])) : '—' ?></td>
                                            <td>
                                                <div class="table-action-buttons">
                                                    <a class="edit button button-box button-xs button-info" href="index.php?act=suatk&idsua=<?= (int)$u['id'] ?>" title="Chỉnh sửa">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </a>
                                                    <a class="delete button button-box button-xs button-danger" href="index.php?act=xoatk&idxoa=<?= (int)$u['id'] ?>" onclick="return confirm('Xóa tài khoản này?')" title="Xóa">
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
                                <p style="margin-top: 10px;">Không có quản lý rạp nào trong hệ thống</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

