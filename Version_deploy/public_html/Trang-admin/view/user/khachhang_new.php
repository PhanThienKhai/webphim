<?php include "./view/home/sideheader.php"; ?>

<div class="content-body">
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 12px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
        }

        .header-section h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-section p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .alert-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #1565c0;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table {
            margin: 0;
            font-size: 13px;
        }

        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .table th {
            padding: 16px;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transition: background 0.2s ease;
        }

        .user-id {
            color: #667eea;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-danger {
            background: #ffebee;
            color: #c62828;
        }

        .badge-primary {
            background: #e3f2fd;
            color: #1565c0;
        }

        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-edit {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-edit:hover {
            background: #1976d2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .btn-delete {
            background: #ffebee;
            color: #c62828;
        }

        .btn-delete:hover {
            background: #c62828;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }

            .header-section h2 {
                font-size: 20px;
            }

            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 10px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }
    </style>

    <!-- Header Section -->
    <div class="header-section">
        <h2>👥 Quản Lý Khách Hàng</h2>
        <p>Danh sách toàn bộ tài khoản khách hàng trong hệ thống</p>
    </div>

    <!-- Alert Message -->
    <?php if(isset($suatc) && $suatc != ""): ?>
        <div class="alert-info">
            ℹ️ <?= htmlspecialchars($suatc) ?>
        </div>
    <?php endif; ?>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-responsive">
            <?php if(!empty($loadall_kh1)): ?>
                <table class="table table-vertical-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên Khách Hàng</th>
                            <th>Tài Khoản</th>
                            <th>Email</th>
                            <th>Điện Thoại</th>
                            <th>Rạp Yêu Thích</th>
                            <th>Vai Trò</th>
                            <th style="width: 100px;">Vé Đã Mua</th>
                            <th style="width: 90px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($loadall_kh1 as $kh): ?>
                            <?php 
                                $linksua = "index.php?act=suatk&idsua=" . (int)$kh['id'];
                                $linkxoa = "index.php?act=xoatk&idxoa=" . (int)$kh['id'];
                                $vai_tro = $kh['vai_tro'] ?? 0;
                            ?>
                            <tr>
                                <td><span class="user-id">#<?= (int)$kh['id'] ?></span></td>
                                <td><?= htmlspecialchars($kh['name'] ?? '') ?></td>
                                <td><code><?= htmlspecialchars($kh['user'] ?? '') ?></code></td>
                                <td><?= htmlspecialchars($kh['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($kh['phone'] ?? '') ?></td>
                                <td><?= !empty($kh['ten_rap']) ? htmlspecialchars($kh['ten_rap']) : '—' ?></td>
                                <td>
                                    <?php
                                        if ($vai_tro == '1') {
                                            echo '<span class="badge badge-danger">Nhân Viên</span>';
                                        } elseif ($vai_tro == '2') {
                                            echo '<span class="badge badge-primary">Chủ Rạp</span>';
                                        } else {
                                            echo '<span class="badge badge-success">Khách Hàng</span>';
                                        }
                                    ?>
                                </td>
                                <td><strong><?= (int)($kh['so_ve'] ?? 0) ?></strong></td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn-action btn-edit" href="<?= $linksua ?>" title="Chỉnh sửa">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a class="btn-action btn-delete" href="<?= $linkxoa ?>" onclick="return confirm('Xóa tài khoản này?')" title="Xóa">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p style="font-size: 40px; margin: 0;">📭</p>
                    <p style="margin-top: 10px;">Không có khách hàng nào trong hệ thống</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
