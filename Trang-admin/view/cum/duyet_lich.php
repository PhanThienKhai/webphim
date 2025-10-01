<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="main-content-wrapper">
<div class="content-body">
    <!-- Header Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0"><i class="zmdi zmdi-check-circle text-primary"></i> Duyệt Kế Hoạch Chiếu</h4>
                    <p class="text-muted mb-0">Quản lý và duyệt các kế hoạch chiếu phim từ các rạp</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <div class="btn-group" role="group">
                        <a class="btn <?= ($_GET['filter'] ?? 'cho_duyet') === 'cho_duyet' ? 'btn-warning' : 'btn-outline-warning' ?>" 
                           href="index.php?act=duyet_lichchieu&filter=cho_duyet">
                            <i class="zmdi zmdi-time"></i> Chờ duyệt
                        </a>
                        <a class="btn <?= ($_GET['filter'] ?? '') === 'da_duyet' ? 'btn-success' : 'btn-outline-success' ?>" 
                           href="index.php?act=duyet_lichchieu&filter=da_duyet">
                            <i class="zmdi zmdi-check"></i> Đã duyệt
                        </a>
                        <a class="btn <?= ($_GET['filter'] ?? '') === 'tu_choi' ? 'btn-danger' : 'btn-outline-danger' ?>" 
                           href="index.php?act=duyet_lichchieu&filter=tu_choi">
                            <i class="zmdi zmdi-close"></i> Từ chối
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="zmdi zmdi-info"></i> <?= htmlspecialchars($msg) ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Danh sách kế hoạch -->
    <div class="card">
        <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0"><i class="zmdi zmdi-tag"></i> Mã KH</th>
                            <th class="border-0"><i class="zmdi zmdi-store"></i> Rạp</th>
                            <th class="border-0"><i class="zmdi zmdi-movie"></i> Phim</th>
                            <th class="border-0"><i class="zmdi zmdi-label"></i> Thể loại</th>
                            <th class="border-0"><i class="zmdi zmdi-calendar"></i> Thời gian</th>
                            <th class="border-0"><i class="zmdi zmdi-time"></i> Số suất</th>
                            <th class="border-0"><i class="zmdi zmdi-account"></i> Người tạo</th>
                            <th class="border-0"><i class="zmdi zmdi-flag"></i> Trạng thái</th>
                            <th class="border-0 text-center"><i class="zmdi zmdi-settings"></i> Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ds_lich)): ?>
                            <?php foreach ($ds_lich as $r): ?>
                                <?php
                                    $st = trim($r['trang_thai_duyet'] ?? '');
                                    $st_l = mb_strtolower($st, 'UTF-8');
                                    if ($st==='') { $st_norm=''; $st_text='-'; $badge_class='badge-secondary'; }
                                    elseif ($st_l==='cho_duyet' || $st_l==='chờ duyệt' || $st_l==='cho duyet') { $st_norm='cho_duyet'; $st_text='Chờ duyệt'; $badge_class='badge-warning'; }
                                    elseif ($st_l==='da_duyet' || $st_l==='đã duyệt' || $st_l==='da duyet') { $st_norm='da_duyet'; $st_text='Đã duyệt'; $badge_class='badge-success'; }
                                    elseif ($st_l==='tu_choi' || $st_l==='từ chối' || $st_l==='tu choi') { $st_norm='tu_choi'; $st_text='Từ chối'; $badge_class='badge-danger'; }
                                    else { $st_norm=$st; $st_text=$st; $badge_class='badge-secondary'; }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <code class="text-primary font-weight-bold"><?= htmlspecialchars(substr($r['ma_ke_hoach'], -8)) ?></code>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($r['ngay_tao'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-medium"><?= htmlspecialchars($r['ten_rap']) ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold text-dark"><?= htmlspecialchars($r['ten_phim']) ?></div>
                                            <small class="text-muted"><?= $r['thoi_luong_phim'] ?> phút</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?= htmlspecialchars($r['ten_loai'] ?: 'Chưa phân loại') ?></span>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div><?= date('d/m/Y', strtotime($r['tu_ngay'])) ?></div>
                                            <?php if ($r['tu_ngay'] != $r['den_ngay']): ?>
                                                <div class="text-muted">đến <?= date('d/m/Y', strtotime($r['den_ngay'])) ?></div>
                                            <?php endif; ?>
                                            <small class="text-success">(<?= $r['so_ngay_chieu'] ?> ngày)</small>
                                            <br><small class="text-muted">Ngày: <?= $r['danh_sach_ngay'] ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary"><?= $r['so_ngay_chieu'] ?> ngày</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="zmdi zmdi-account-circle text-muted mr-1"></i>
                                            <span><?= htmlspecialchars($r['nguoi_tao_ten'] ?: 'Quản lý rạp') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($st_text) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm">
                                            <button class="btn btn-outline-info btn-sm mb-1" 
                                                    onclick="xemChiTiet('<?= htmlspecialchars($r['ma_ke_hoach']) ?>')" 
                                                    title="Xem chi tiết">
                                                <i class="zmdi zmdi-eye"></i> Chi tiết
                                            </button>
                                            
                                            <?php if ($st_norm === 'cho_duyet'): ?>
                                                <div class="btn-group btn-group-sm">
                                                    <a class="btn btn-outline-success btn-sm" 
                                                       href="index.php?act=duyet_lichchieu&duyet_kehoach=1&ma_ke_hoach=<?= urlencode($r['ma_ke_hoach']) ?>&action=duyet" 
                                                       onclick="return confirm('Duyệt toàn bộ kế hoạch chiếu này?')" 
                                                       title="Duyệt kế hoạch">
                                                        <i class="zmdi zmdi-check"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger btn-sm" 
                                                       href="index.php?act=duyet_lichchieu&duyet_kehoach=1&ma_ke_hoach=<?= urlencode($r['ma_ke_hoach']) ?>&action=tu_choi" 
                                                       onclick="return confirm('Từ chối kế hoạch chiếu này?')" 
                                                       title="Từ chối kế hoạch">
                                                        <i class="zmdi zmdi-close"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($r['ghi_chu'])): ?>
                                            <div class="mt-2">
                                                <small class="text-info">
                                                    <i class="zmdi zmdi-comment-text"></i> 
                                                    <?= htmlspecialchars(substr($r['ghi_chu'], 0, 50)) ?>
                                                    <?= strlen($r['ghi_chu']) > 50 ? '...' : '' ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($r['ghi_chu'])): ?>
                                            <div class="mt-2">
                                                <small class="text-info">
                                                    <i class="zmdi zmdi-comment-text"></i> 
                                                    <?= htmlspecialchars(substr($r['ghi_chu'], 0, 50)) ?>
                                                    <?= strlen($r['ghi_chu']) > 50 ? '...' : '' ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="zmdi zmdi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <div class="mt-2">
                                            <h6>Không có kế hoạch chiếu nào</h6>
                                            <p class="mb-0">Chưa có kế hoạch chiếu nào để duyệt trong danh mục này.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>
</div>

<style>
/* Fix header bị che */
.main-content-wrapper {
    margin-left: 260px; /* Đẩy toàn bộ nội dung tránh sidebar */
    padding: 0;
    min-height: 100vh;
}
.content-body {
    padding: 20px;
}
/* Responsive cho mobile */
@media (max-width: 768px) {
    .main-content-wrapper {
        margin-left: 0;
        padding: 10px;
    }
}

/* Làm đẹp table */
.table {
    font-size: 0.9rem;
}
.table thead th {
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
}
.table tbody tr {
    transition: all 0.2s ease;
}
.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

/* Badges đẹp hơn */
.badge {
    font-size: 0.75em;
    padding: 0.4em 0.8em;
    font-weight: 500;
}
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.badge-info { background-color: #17a2b8; }
.badge-primary { background-color: #007bff; }

/* Buttons đẹp hơn */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.2rem;
}
.btn-outline-info:hover { background-color: #17a2b8; border-color: #17a2b8; }
.btn-outline-success:hover { background-color: #28a745; border-color: #28a745; }
.btn-outline-danger:hover { background-color: #dc3545; border-color: #dc3545; }

/* Code styling */
code {
    background-color: #e9ecef;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.8em;
}

/* Card shadow */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

/* Table card không có body padding */
.card .table-responsive {
    border-radius: 0.375rem;
}
.card .table-responsive .table {
    margin-bottom: 0;
}
</style>

<!-- Modal Chi tiết kế hoạch -->
<div class="modal fade" id="modalChiTiet" tabindex="-1" role="dialog" aria-labelledby="modalChiTietLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChiTietLabel">Chi tiết kế hoạch chiếu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalChiTietContent">
                <div class="text-center">
                    <i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Đang tải...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
function xemChiTiet(maKeHoach) {
    $('#modalChiTiet').modal('show');
    $('#modalChiTietContent').html('<div class="text-center"><i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Đang tải...</div>');
    
    // AJAX load nội dung chi tiết
    $.get('index.php', {
        act: 'ajax_chi_tiet_kehoach_new',
        ma: maKeHoach
    }, function(data) {
        $('#modalChiTietContent').html(data);
    }).fail(function() {
        $('#modalChiTietContent').html('<div class="alert alert-danger">Lỗi khi tải dữ liệu!</div>');
    });
}
</script>
