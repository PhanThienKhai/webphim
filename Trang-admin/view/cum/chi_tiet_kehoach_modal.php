<?php if (!empty($chi_tiet)): ?>
    <?php $first = $chi_tiet[0]; ?>
    
    <!-- Thông tin tổng quan - KHÔNG CÓ HEADER -->
    <div class="row mb-3">
        <div class="col-md-8">
            <table class="table table-sm table-borderless">
                <tr>
                    <td style="width: 140px;"><strong>Mã kế hoạch:</strong></td>
                    <td><code class="bg-light px-2 py-1"><?= htmlspecialchars($ma_ke_hoach) ?></code></td>
                </tr>
                <tr>
                    <td><strong>Phim:</strong></td>
                    <td>
                        <div class="font-weight-bold text-dark"><?= htmlspecialchars($first['ten_phim']) ?></div>
                        <small class="text-muted"><?= $first['thoi_luong_phim'] ?> phút</small>
                    </td>
                </tr>
                <tr>
                    <td><strong>Thể loại:</strong></td>
                    <td><span class="badge badge-info"><?= htmlspecialchars($first['ten_loai'] ?: 'Chưa phân loại') ?></span></td>
                </tr>
                <tr>
                    <td><strong>Rạp chiếu:</strong></td>
                    <td><?= htmlspecialchars($first['ten_rap']) ?></td>
                </tr>
                <tr>
                    <td><strong>Trạng thái:</strong></td>
                    <td>
                        <?php
                        $st = trim($first['trang_thai_duyet'] ?? '');
                        if ($st === 'Chờ duyệt' || $st === 'cho_duyet') echo '<span class="badge badge-warning">Chờ duyệt</span>';
                        elseif ($st === 'Đã duyệt' || $st === 'da_duyet') echo '<span class="badge badge-success">Đã duyệt</span>';
                        elseif ($st === 'Từ chối' || $st === 'tu_choi') echo '<span class="badge badge-danger">Từ chối</span>';
                        else echo '<span class="badge badge-secondary">' . htmlspecialchars($st ?: 'N/A') . '</span>';
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="col-md-4">
            <?php if (!empty($first['img'])): ?>
                <div class="text-center">
                    <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($first['img']) ?>" 
                         alt="<?= htmlspecialchars($first['ten_phim']) ?>" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 220px; object-fit: cover;">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Chi tiết lịch chiếu - KHÔNG CÓ HEADER -->
    <div class="border-top pt-3 mt-3">
        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
            <table class="table table-bordered table-sm table-hover mb-0">
                <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th style="width: 130px;">Ngày chiếu</th>
                        <th>Khung giờ & Phòng</th>
                        <th style="width: 110px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chi_tiet as $index => $ct): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td>
                            <div class="font-weight-medium"><?= date('d/m/Y', strtotime($ct['ngay_chieu'])) ?></div>
                            <small class="text-muted"><?= date('l', strtotime($ct['ngay_chieu'])) ?></small>
                        </td>
                        <td>
                            <?php if (!empty($ct['khung_gio'])): ?>
                                <?php 
                                $khung_gio_list = explode(', ', $ct['khung_gio']);
                                foreach ($khung_gio_list as $kg) {
                                    echo '<span class="badge badge-primary mr-1 mb-1" style="font-size: 11px;">' . htmlspecialchars(trim($kg)) . '</span> ';
                                }
                                ?>
                            <?php else: ?>
                                <span class="text-muted">Chưa có khung giờ</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $st = trim($ct['trang_thai_duyet'] ?? '');
                            if ($st === 'Chờ duyệt' || $st === 'cho_duyet') { $st_text='Chờ duyệt'; $badge_class='badge-warning'; }
                            elseif ($st === 'Đã duyệt' || $st === 'da_duyet') { $st_text='Đã duyệt'; $badge_class='badge-success'; }
                            elseif ($st === 'Từ chối' || $st === 'tu_choi') { $st_text='Từ chối'; $badge_class='badge-danger'; }
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

<?php else: ?>
    <div class="alert alert-warning mb-0">
        <i class="zmdi zmdi-alert-triangle"></i> Không tìm thấy thông tin kế hoạch chiếu.
    </div>
<?php endif; ?>