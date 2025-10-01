<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto">
            <div class="page-heading">
                <h3><i class="zmdi zmdi-eye"></i> Chi Tiết Kế Hoạch Chiếu</h3>
                <p>Xem chi tiết thông tin kế hoạch và các suất chiếu</p>
            </div>
        </div>
        <div class="col-12 col-lg-auto">
            <a class="btn btn-secondary" href="index.php?act=duyet_lichchieu">
                <i class="zmdi zmdi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (!empty($chi_tiet)): ?>
        <?php $first = $chi_tiet[0]; ?>
        
        <!-- Thông tin tổng quan -->
        <div class="row mb-30">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="zmdi zmdi-info"></i> Thông Tin Kế Hoạch</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Mã kế hoạch:</strong></td>
                                <td><code><?= htmlspecialchars($first['ma_ke_hoach']) ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Phim:</strong></td>
                                <td>
                                    <h6><?= htmlspecialchars($first['ten_phim']) ?></h6>
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
                                <td><?= htmlspecialchars($first['nguoi_tao_ten'] ?: 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($first['ngay_tao'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    <?php
                                        $st = trim($first['trang_thai_duyet'] ?? '');
                                        $st_l = mb_strtolower($st, 'UTF-8');
                                        if (in_array($st_l, ['cho_duyet', 'chờ duyệt', 'cho duyet'])) { $badge_class='bg-warning'; $st_text='Chờ duyệt'; }
                                        elseif (in_array($st_l, ['da_duyet', 'đã duyệt', 'da duyet'])) { $badge_class='bg-success'; $st_text='Đã duyệt'; }
                                        elseif (in_array($st_l, ['tu_choi', 'từ chối', 'tu choi'])) { $badge_class='bg-danger'; $st_text='Từ chối'; }
                                        else { $badge_class='bg-secondary'; $st_text=$st; }
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($st_text) ?></span>
                                </td>
                            </tr>
                            <?php if (!empty($first['ghi_chu'])): ?>
                            <tr>
                                <td><strong>Ghi chú:</strong></td>
                                <td><?= htmlspecialchars($first['ghi_chu']) ?></td>
                            </tr>
                            <?php endif; ?>
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
                             class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($first['mo_ta'])): ?>
                <div class="card mt-3">
                    <div class="card-header"><h6>Mô tả phim</h6></div>
                    <div class="card-body">
                        <p class="text-muted"><?= nl2br(htmlspecialchars($first['mo_ta'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chi tiết suất chiếu -->
        <div class="card">
            <div class="card-header">
                <h5><i class="zmdi zmdi-calendar"></i> Chi Tiết Suất Chiếu</h5>
                <small>Tổng cộng <?= count($chi_tiet) ?> ngày chiếu</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ngày chiếu</th>
                                <th>Thứ</th>
                                <th>Khung giờ và phòng</th>
                                <th>Số suất</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chi_tiet as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= date('d/m/Y', strtotime($item['ngay_chieu'])) ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                            $thu_map = ['', 'Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
                                            $thu_num = date('w', strtotime($item['ngay_chieu']));
                                            echo $thu_map[$thu_num];
                                        ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['khung_gio'])): ?>
                                            <?php
                                                $khung_gio_arr = explode(',', $item['khung_gio']);
                                                foreach ($khung_gio_arr as $kg) {
                                                    [$gio, $phong] = explode('|', $kg);
                                                    echo '<span class="badge bg-primary me-1 mb-1">' . $gio . ' - ' . htmlspecialchars($phong) . '</span> ';
                                                }
                                            ?>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có suất chiếu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= !empty($item['khung_gio']) ? count(explode(',', $item['khung_gio'])) : 0 ?> suất
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <?php if ($first['trang_thai_duyet'] === 'Chờ duyệt'): ?>
        <div class="row mt-30">
            <div class="col-12 text-center">
                <a class="btn btn-success btn-lg me-3" 
                   href="index.php?act=duyet_kehoach&ma=<?= urlencode($first['ma_ke_hoach']) ?>&action=duyet"
                   onclick="return confirm('Duyệt toàn bộ kế hoạch chiếu này? Tất cả các ngày và suất chiếu sẽ được phê duyệt.')">
                    <i class="zmdi zmdi-check"></i> Duyệt Kế Hoạch
                </a>
                <a class="btn btn-danger btn-lg" 
                   href="index.php?act=duyet_kehoach&ma=<?= urlencode($first['ma_ke_hoach']) ?>&action=tu_choi"
                   onclick="return confirm('Từ chối kế hoạch chiếu này? Tất cả các suất chiếu sẽ bị từ chối.')">
                    <i class="zmdi zmdi-close"></i> Từ Chối Kế Hoạch
                </a>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-warning">
            <h5>Không tìm thấy thông tin kế hoạch</h5>
            <p>Kế hoạch chiếu không tồn tại hoặc bạn không có quyền xem.</p>
        </div>
    <?php endif; ?>

</div>