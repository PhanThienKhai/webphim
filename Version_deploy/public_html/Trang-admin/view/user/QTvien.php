<?php include "./view/home/sideheader.php"; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading"><h3>Quản Lý Tài Khoản / <span>👨‍💼 Nhân Viên</span></h3></div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="news-item">
                <div class="content">
                    <div class="categories"><a href="index.php?act=themuser" class="product">➕ Thêm Tài Khoản Nhân Viên</a></div>
                </div>
            </div>
            <div class="news-item">
                <div class="content">
                    <div class="table-responsive">
                        <?php if(!empty($loadalltk)): ?>
                            <table class="table table-vertical-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Tên Nhân Viên</th>
                                        <th>Tài Khoản</th>
                                        <th>Email</th>
                                        <th>Điện Thoại</th>
                                        <th>Rạp</th>
                                        <th>Vai Trò</th>
                                        <th style="width: 90px;">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($loadalltk as $kh): ?>
                                    <?php 
                                        $linksua = "index.php?act=suatk&idsua=" . (int)$kh['id'];
                                        $linkxoa = "index.php?act=xoatk&idxoa=" . (int)$kh['id'];
                                        $vai_tro = $kh['vai_tro'] ?? 0;
                                    ?>
                                    <tr>
                                        <td><span style="color: #f5576c; font-weight: 600;">#<?= (int)$kh['id'] ?></span></td>
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
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="table-action-buttons">
                                                <a class="edit button button-box button-xs button-info" href="<?= $linksua ?>" title="Chỉnh sửa">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </a>
                                                <a class="delete button button-box button-xs button-danger" href="<?= $linkxoa ?>" onclick="return confirm('Xóa tài khoản này?')" title="Xóa">
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
                                <p style="margin-top: 10px;">Không có nhân viên nào trong hệ thống</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
