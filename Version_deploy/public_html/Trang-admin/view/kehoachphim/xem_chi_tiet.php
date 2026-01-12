<?php
// Kiểm tra quyền
if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_QUAN_LY_RAP) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập tính năng này!</div>';
    return;
}

if (empty($chi_tiet_ke_hoach)) {
    echo '<div class="alert alert-warning">Không tìm thấy thông tin kế hoạch chiếu!</div>';
    return;
}

$ke_hoach_info = $chi_tiet_ke_hoach[0]; // Lấy thông tin chung từ dòng đầu tiên
?>

<?php include "./view/home/sideheader.php"; ?>

<!-- Content Body Start -->
<div class="content-body">
    
    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3><i class="zmdi zmdi-eye"></i> Chi tiết kế hoạch chiếu</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?act=kehoach">Kế hoạch chiếu</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($ma_ke_hoach) ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-button">
                <a href="index.php?act=export_kehoach&ma=<?= urlencode($ma_ke_hoach) ?>" class="btn btn-primary" target="_blank">
                    <i class="zmdi zmdi-download"></i> Xuất Word
                </a>
                <a href="index.php?act=kehoach" class="btn btn-secondary">
                    <i class="zmdi zmdi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div><!-- Page Headings End -->
    
    <!-- Thông tin kế hoạch -->
    <div class="row mb-30">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="zmdi zmdi-info"></i> Thông tin kế hoạch</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?php if ($ke_hoach_info['img']): ?>
                                <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($ke_hoach_info['img']) ?>" alt="Poster" class="img-fluid rounded" style="max-height: 300px;">
                            <?php else: ?>
                                <div class="bg-light p-4 text-center rounded">
                                    <i class="zmdi zmdi-movie-alt" style="font-size: 4rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Không có poster</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Mã kế hoạch:</strong></div>
                                <div class="col-sm-9"><code><?= htmlspecialchars($ke_hoach_info['ma_ke_hoach']) ?></code></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Phim:</strong></div>
                                <div class="col-sm-9">
                                    <h5 class="mb-1"><?= htmlspecialchars($ke_hoach_info['ten_phim']) ?></h5>
                                    <small class="text-muted"><?= $ke_hoach_info['thoi_luong_phim'] ?> phút | <?= htmlspecialchars($ke_hoach_info['ten_loai'] ?? 'N/A') ?></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Rạp chiếu:</strong></div>
                                <div class="col-sm-9"><?= htmlspecialchars($ke_hoach_info['ten_rap']) ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Trạng thái:</strong></div>
                                <div class="col-sm-9">
                                    <?php
                                    $badge_class = '';
                                    $icon = '';
                                    switch($ke_hoach_info['trang_thai_duyet']) {
                                        case 'Chờ duyệt':
                                            $badge_class = 'warning';
                                            $icon = '⏳';
                                            break;
                                        case 'Đã duyệt':
                                            $badge_class = 'success';
                                            $icon = '✅';
                                            break;
                                        case 'Từ chối':
                                            $badge_class = 'danger';
                                            $icon = '❌';
                                            break;
                                        default:
                                            $badge_class = 'secondary';
                                            $icon = '❓';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $badge_class ?> badge-lg">
                                        <?= $icon ?> <?= $ke_hoach_info['trang_thai_duyet'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Người tạo:</strong></div>
                                <div class="col-sm-9"><?= htmlspecialchars($ke_hoach_info['nguoi_tao_ten'] ?? 'N/A') ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Ngày tạo:</strong></div>
                                <div class="col-sm-9"><?= date('d/m/Y H:i:s', strtotime($ke_hoach_info['ngay_tao'])) ?></div>
                            </div>
                            <?php if ($ke_hoach_info['ghi_chu']): ?>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Ghi chú:</strong></div>
                                <div class="col-sm-9"><?= htmlspecialchars($ke_hoach_info['ghi_chu']) ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ($ke_hoach_info['mo_ta']): ?>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Mô tả phim:</strong></div>
                                <div class="col-sm-9"><?= nl2br(htmlspecialchars($ke_hoach_info['mo_ta'])) ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chi tiết lịch chiếu -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="zmdi zmdi-calendar"></i> Chi tiết lịch chiếu</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Poster</th>
                                    <th>Ngày chiếu</th>
                                    <th>Khung giờ chiếu</th>
                                    <th>Phòng chiếu</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $stt = 1;
                                foreach ($chi_tiet_ke_hoach as $item): 
                                ?>
                                <tr>
                                    <td><?= $stt++ ?></td>
                                    <td>
                                        <?php if ($item['img']): ?>
                                            <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($item['img']) ?>" alt="Poster" class="img-thumbnail" style="width: 50px; height: 75px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 75px; border-radius: 4px;">
                                                <i class="zmdi zmdi-movie-alt text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= date('d/m/Y', strtotime($item['ngay_chieu'])) ?></strong>
                                        <br><small class="text-muted"><?= date('l', strtotime($item['ngay_chieu'])) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($item['khung_gio']) {
                                            $khung_gio_list = explode(',', $item['khung_gio']);
                                            foreach ($khung_gio_list as $kg) {
                                                $parts = explode('|', $kg);
                                                if (count($parts) == 2) {
                                                    echo '<span class="badge badge-light me-1">' . 
                                                         date('H:i', strtotime($parts[0])) . 
                                                         '</span>';
                                                }
                                            }
                                        } else {
                                            echo '<small class="text-muted">Chưa có khung giờ</small>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($item['khung_gio']) {
                                            $khung_gio_list = explode(',', $item['khung_gio']);
                                            $phong_list = [];
                                            foreach ($khung_gio_list as $kg) {
                                                $parts = explode('|', $kg);
                                                if (count($parts) == 2 && !in_array($parts[1], $phong_list)) {
                                                    $phong_list[] = $parts[1];
                                                }
                                            }
                                            echo implode(', ', $phong_list);
                                        } else {
                                            echo '<small class="text-muted">Chưa có phòng</small>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $badge_class ?>">
                                            <?= $icon ?> <?= $item['trang_thai_duyet'] ?>
                                        </span>
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
    
</div><!-- Content Body End -->

<style>
.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}

.table td {
    vertical-align: middle;
    padding: 0.75rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-warning {
    background-color: #f6c23e;
    color: #1e1e1e;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-danger {
    background-color: #e74a3b;
}

.badge-light {
    background-color: #f8f9fc;
    color: #5a5c69;
    border: 1px solid #e3e6f0;
}

.img-thumbnail {
    border: 1px solid #e3e6f0;
    border-radius: 4px;
    transition: transform 0.2s ease;
}

.img-thumbnail:hover {
    transform: scale(1.1);
    cursor: pointer;
}
</style>