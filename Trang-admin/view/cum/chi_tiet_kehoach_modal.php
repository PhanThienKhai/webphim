<?php if (!empty($chi_tiet)): ?>
    <?php $first = $chi_tiet[0]; ?>
    
    <!-- Thông tin tổng quan -->
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-info"></i> Thông Tin Kế Hoạch</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Mã kế hoạch:</strong></td>
                            <td><code><?= htmlspecialchars($first['ma_ke_hoach'] ?? 'N/A') ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Phim:</strong></td>
                            <td>
                                <h6 class="mb-0"><?= htmlspecialchars($first['ten_phim']) ?></h6>
                                <small class="text-muted"><?= $first['thoi_luong_phim'] ?> phút</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Thể loại:</strong></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($first['ten_loai'] ?: 'Chưa phân loại') ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Rạp chiếu:</strong></td>
                            <td><?= htmlspecialchars($first['ten_rap']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Người tạo:</strong></td>
                            <td>Quản lý rạp</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                <?php
                                $st = $first['trang_thai_duyet'];
                                if ($st === 'Chờ duyệt') echo '<span class="badge badge-warning">Chờ duyệt</span>';
                                elseif ($st === 'Đã duyệt') echo '<span class="badge badge-success">Đã duyệt</span>';
                                elseif ($st === 'Từ chối') echo '<span class="badge badge-danger">Từ chối</span>';
                                else echo '<span class="badge badge-secondary">' . htmlspecialchars($st) . '</span>';
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if (!empty($first['img'])): ?>
            <div class="card">
                <div class="card-header"><h6>Poster Phim</h6></div>
                <div class="card-body text-center">
                    <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($first['img']) ?>" 
                         alt="<?= htmlspecialchars($first['ten_phim']) ?>" 
                         class="img-fluid rounded" style="max-height: 250px;">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mô tả phim -->
    <?php if (!empty($first['mo_ta'])): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-file-text"></i> Mô Tả Phim</h6>
                </div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($first['mo_ta'])) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chi tiết lịch chiếu -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-calendar"></i> Chi Tiết Lịch Chiếu (<?= count($chi_tiet) ?> ngày)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Ngày chiếu</th>
                                    <th>Khung giờ & Phòng</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chi_tiet as $index => $ct): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= date('d/m/Y', strtotime($ct['ngay_chieu'])) ?></strong><br>
                                        <small class="text-muted"><?= date('l', strtotime($ct['ngay_chieu'])) ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($ct['khung_gio'])): ?>
                                            <?php 
                                            $khung_gio_list = explode(', ', $ct['khung_gio']);
                                            foreach ($khung_gio_list as $kg) {
                                                echo '<span class="badge badge-primary me-1 mb-1">' . htmlspecialchars(trim($kg)) . '</span> ';
                                            }
                                            ?>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có khung giờ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $st = trim($ct['trang_thai_duyet'] ?? '');
                                        if ($st === 'Chờ duyệt') { $st_text='Chờ duyệt'; $badge_class='badge-warning'; }
                                        elseif ($st === 'Đã duyệt') { $st_text='Đã duyệt'; $badge_class='badge-success'; }
                                        elseif ($st === 'Từ chối') { $st_text='Từ chối'; $badge_class='badge-danger'; }
                                        else { $st_text=$st ?: 'N/A'; $badge_class='badge-secondary'; }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($st_text) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-warning">
        <i class="zmdi zmdi-alert-triangle"></i> Không tìm thấy thông tin kế hoạch chiếu.
    </div>
<?php endif; ?>