<?php
// Include các model cần thiết
include_once './model/phim_rap.php';
include_once './model/phong.php';

// Kiểm tra quyền
if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_QUAN_LY_RAP) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập tính năng này!</div>';
    return;
}

$ma_rap = $_SESSION['user1']['id_rap'] ?? null;
if (!$ma_rap) {
    echo '<div class="alert alert-warning">Không thể xác định rạp của bạn. Vui lòng liên hệ admin!</div>';
    return;
}

// Lấy danh sách phim và phòng với error handling
try {
    $danh_sach_phim = phim_assigned_to_rap($ma_rap);
    $danh_sach_phong = load_phong_by_rap($ma_rap);
    $lich_su_ke_hoach = ke_hoach_list_by_rap($ma_rap); // Lấy lịch sử kế hoạch
} catch (Exception $e) {
    $danh_sach_phim = [];
    $danh_sach_phong = [];
    $lich_su_ke_hoach = [];
}
?>

<?php include "./view/home/sideheader.php"; ?>

<!-- Content Body Start -->
<div class="content-body">
    
    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Lập Kế Hoạch Chiếu Phim</h3>
                <p>Tạo lịch chiếu và khung giờ chiếu cùng một lúc</p>
            </div>
        </div><!-- Page Heading End -->
    </div><!-- Page Headings End -->
    
    <!-- Thông báo toast ở trên cùng giữa -->
    <div id="toast-notification"></div>
    
    <!-- Button tạo kế hoạch mới -->
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-primary" id="btn-toggle-form" onclick="toggleFormKeHoach()">
                Tạo kế hoạch chiếu mới
            </button>
        </div>
    </div>
    
    <!-- Main Form - Ẩn mặc định -->
    <div class="row" id="form-container" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tạo kế hoạch chiếu mới</h4>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleFormKeHoach()">
                        Đóng
                    </button>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <form id="form-kehoach" method="post" action="index.php?act=luu_kehoach">
                        
                        <!-- Chọn phim (nhiều phim cùng lúc) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" style="font-size: 14px; margin-bottom: 0.5rem; font-weight: 600;">
                                    <i class="zmdi zmdi-movie"></i> Chọn Phim * 
                                    <span class="badge badge-info" id="count-phim-selected">0 phim</span>
                                </label>
                                
                                <!-- Search box -->
                                <input type="text" id="search-phim" class="form-control form-control-sm mb-2" 
                                       placeholder="🔍 Tìm kiếm phim..." style="max-width: 300px;">
                                
                                <!-- Container checkbox với scroll -->
                                <div style="border: 1px solid #dee2e6; border-radius: 6px; background: #fff;">
                                    
                                    <!-- Nút chọn tất cả - CỐ ĐỊNH -->
                                    <div style="position: sticky; top: 0; background: #f8f9fa; padding: 10px; border-bottom: 2px solid #dee2e6; z-index: 10;">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="chonTatCaPhim()">
                                            ✓ Chọn tất cả
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="boChonTatCaPhim()">
                                            ✗ Bỏ chọn tất cả
                                        </button>
                                    </div>
                                    
                                    <!-- Danh sách checkbox phim với scroll -->
                                    <div id="phim-checkbox-container" style="max-height: 300px; overflow-y: auto; padding: 10px;">
                                        <?php foreach($danh_sach_phim as $phim): ?>
                                            <div class="phim-checkbox-item" style="padding: 8px 10px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center;">
                                                <input class="phim-checkbox" 
                                                       type="checkbox" 
                                                       name="ma_phim[]" 
                                                       value="<?= $phim['id'] ?>" 
                                                       id="phim_<?= $phim['id'] ?>"
                                                       data-ten="<?= htmlspecialchars($phim['tieu_de']) ?>"
                                                       onchange="updateCountPhim()"
                                                       style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer; flex-shrink: 0;">
                                                <label for="phim_<?= $phim['id'] ?>" style="font-size: 13px; cursor: pointer; margin: 0; flex: 1;">
                                                    <strong><?= $phim['tieu_de'] ?></strong> 
                                                    <span class="text-muted">(<?= $phim['thoi_luong_phim'] ?> phút)</span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                </div>
                                
                                <small class="text-muted" style="font-size: 11px; display: block; margin-top: 5px;">
                                    💡 Chọn nhiều phim để tạo kế hoạch chiếu cùng lúc với cùng khung giờ
                                </small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Ghi chú</label>
                                <input type="text" name="ghi_chu" class="form-control form-control-sm" placeholder="Ghi chú chung cho tất cả kế hoạch chiếu...">
                            </div>
                        </div>
                        
                        <!-- Chọn ngày -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Từ ngày *</label>
                                <input type="date" name="tu_ngay" id="tu_ngay" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Đến ngày *</label>
                                <input type="date" name="den_ngay" id="den_ngay" class="form-control form-control-sm" required>
                                <small class="text-muted" style="font-size: 11px;">💡 Nhập cùng ngày nếu chỉ chiếu 1 ngày</small>
                            </div>
                        </div>
                        
                        <hr style="margin: 1rem 0;">
                        
                        <!-- Khung giờ chiếu -->
                        <h5 class="mb-2" style="font-size: 14px;"><i class="zmdi zmdi-time"></i> Khung Giờ Chiếu</h5>
                        
                        <div id="container-khung-gio">
                            <!-- Khung giờ đầu tiên -->
                            <div class="khung-gio-item mb-2" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Giờ chiếu *</label>
                                        <input type="time" name="gio_bat_dau[]" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Phòng chiếu *</label>
                                        <select name="ma_phong[]" class="form-control form-control-sm" required>
                                            <option value="">-- Chọn phòng --</option>
                                            <?php foreach($danh_sach_phong as $phong): ?>
                                                <option value="<?= $phong['id'] ?>"><?= $phong['name'] ?> (<?= $phong['so_ghe'] ?> ghế)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="xoaKhungGio(this)">
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="btn-them" class="btn btn-success btn-sm mb-3" onclick="themKhungGio()">
                            Thêm Khung Giờ
                        </button>
                        
                        <hr style="margin: 1rem 0;">
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Lưu Kế Hoạch
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử kế hoạch đã gửi -->
    <div class="row mb-30">
        <div class="col-12">
            <?php if (!empty($lich_su_ke_hoach)): ?>
            <div class="card">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Lịch sử kế hoạch đã gửi</h4>
                    <span class="badge badge-info"><?= count($lich_su_ke_hoach) ?> kế hoạch</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none;">Mã KH</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none;">Phim</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none;">Thời gian</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none; text-align: center;">Số ngày</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none; text-align: center;">Số suất</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none; text-align: center;">Trạng thái</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none;">Ngày tạo</th>
                                    <th style="padding: 12px 15px; background: linear-gradient(135deg, #d2d3daff 0%, #dfdae4ff 100%); color: black; border: none; text-align: center;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lich_su_ke_hoach as $kh): ?>
                                <tr style="border-bottom: 1px solid #e9ecef; transition: all 0.2s ease;">
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <code style="background: #f1f3f5; padding: 4px 8px; border-radius: 4px; color: #495057; font-size: 12px;"><?= htmlspecialchars(substr($kh['ma_ke_hoach'], -12)) ?></code>
                                        <?php if ($kh['ghi_chu']): ?>
                                            <br><small class="text-muted" style="font-size: 11px;">💬 <?= htmlspecialchars($kh['ghi_chu']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($kh['img'])): ?>
                                                <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($kh['img']) ?>" alt="Poster" class="rounded me-2 movie-poster-thumb" style="width: 125px; height: 170px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.15);" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="bg-light align-items-center justify-content-center rounded me-2" style="width: 45px; height: 65px; display: none;">
                                                    <i class="zmdi zmdi-movie-alt text-muted"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded me-2" style="width: 45px; height: 65px; border: 2px dashed #dee2e6;">
                                                    <i class="zmdi zmdi-movie-alt text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 3px;"><?= htmlspecialchars($kh['ten_phim']) ?></div>
                                                <small class="text-muted" style="font-size: 12px;">
                                                    <i class="zmdi zmdi-time" style="font-size: 11px;"></i> <?= $kh['thoi_luong_phim'] ?> phút
                                                    <span style="margin: 0 5px;">•</span>
                                                    <i class="zmdi zmdi-label" style="font-size: 11px;"></i> <?= htmlspecialchars($kh['ten_loai'] ?? 'N/A') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <div style="font-weight: 500; color: #2c3e50;"><?= date('d/m/Y', strtotime($kh['tu_ngay'])) ?></div>
                                        <?php if ($kh['tu_ngay'] != $kh['den_ngay']): ?>
                                            <small class="text-muted" style="font-size: 11px;"><i class="zmdi zmdi-long-arrow-down"></i> <?= date('d/m/Y', strtotime($kh['den_ngay'])) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; text-align: center;">
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;"><?= $kh['so_ngay_chieu'] ?></span>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; text-align: center;">
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;"><?= $kh['so_suat_chieu'] ?></span>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; text-align: center;"><?php
                                            $icons = [
                                                'Chờ duyệt' => '',
                                                'Đã duyệt' => '', 
                                                'Từ chối' => ''
                                            ];
                                            $badge_styles = [
                                                'warning' => 'background: #ffc107; color: #000;',
                                                'success' => 'background: #28a745; color: white;',
                                                'danger' => 'background: #dc3545; color: white;'
                                            ];
                                            $style = $badge_styles[$kh['badge_class']] ?? 'background: #6c757d; color: white;';
                                            echo '<span class="badge" style="' . $style . ' padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">';
                                            echo ($icons[$kh['trang_thai_duyet']] ?? '❓') . ' ' . $kh['trang_thai_duyet'];
                                            echo '</span>';
                                            ?>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <div style="font-size: 12px; color: #495057;"><?= date('d/m/Y H:i', strtotime($kh['ngay_tao'])) ?></div>
                                        <?php if ($kh['nguoi_tao_ten']): ?>
                                            <small class="text-muted" style="font-size: 11px;"><i class="zmdi zmdi-account" style="font-size: 11px;"></i> <?= htmlspecialchars($kh['nguoi_tao_ten']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; text-align: center;">
                                        <div class="btn-group-vertical" style="gap: 5px; display: flex; flex-direction: column; align-items: stretch;">
                                            <a href="index.php?act=xem_kehoach&ma=<?= urlencode($kh['ma_ke_hoach']) ?>" class="btn btn-sm btn-info" style="font-size: 11px; padding: 5px 10px; white-space: nowrap;">
                                                Xem chi tiết
                                            </a>
                                            <a href="index.php?act=thu_hoi_kehoach&ma=<?= urlencode($kh['ma_ke_hoach']) ?>" class="btn btn-sm btn-danger" style="font-size: 11px; padding: 5px 10px; white-space: nowrap;" onclick="return confirm('Bạn có chắc chắn muốn thu hồi kế hoạch này? Kế hoạch sẽ bị xóa hoàn toàn!');">
                                                Thu hồi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <strong>Chưa có kế hoạch chiếu nào!</strong>
                <p class="mb-0">Hãy tạo kế hoạch chiếu đầu tiên bằng form bên dưới.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    
</div><!-- Content Body End -->

<style>
/* Toast notification */
.footer-section {
    position: fixed;
    bottom: 0;
    left: 250px; /* Không che sidebar (sidebar width = 250px) */
    right: 0;
    width: calc(100% - 250px);
    background: #fff;
    border-top: 1px solid #e3e6f0;
    padding: 15px 20px;
    z-index: 999;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
}

/* Thêm padding-bottom cho content-body để không bị footer che */
.content-body {
    padding-bottom: 80px;
}

/* Responsive: sidebar thu nhỏ */
@media (max-width: 991px) {
    .footer-section {
        left: 0;
        width: 100%;
    }
}

/* CSS cho bảng lịch sử kế hoạch */
.card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

/* Thu nhỏ form lập kế hoạch */
#form-kehoach .form-control-sm {
    padding: 0.375rem 0.5rem;
    font-size: 13px;
    line-height: 1.5;
}

#form-kehoach .form-label {
    margin-bottom: 0.3rem;
    font-weight: 500;
}

#form-kehoach .mb-3 {
    margin-bottom: 0.75rem !important;
}

.khung-gio-item {
    transition: all 0.2s ease;
}

.khung-gio-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Button tạo kế hoạch mới */
#btn-toggle-form {
    padding: 0.6rem 1.5rem;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

#btn-toggle-form:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Animation cho form */
#form-container {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Toast notification - hiển thị trên cùng giữa */
#toast-notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    min-width: 300px;
    max-width: 500px;
}

.toast-message {
    background-color: #444f5aff;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    animation: slideInDown 0.3s ease-out, fadeOut 0.3s ease-in 3.7s;
}

.toast-message.success {
    background-color: #27ae60;
}

.toast-message.error {
    background-color: #e74c3c;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
}

/* Cải thiện table */
.table {
    font-size: 14px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.005);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.movie-poster-thumb {
    transition: all 0.3s ease;
    border: 2px solid #fff;
}

.movie-poster-thumb:hover {
    transform: scale(1.8);
    z-index: 1000;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3) !important;
    cursor: pointer;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 8px;
}

.dropdown-item {
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 13px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #b1b5c6ff 0%, #c5b9d2ff 100%);
    color: white !important;
    transform: translateX(5px);
}

.dropdown-item i {
    margin-right: 8px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: white;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

/* Alert không còn dùng - đã dùng toast */

.img-thumbnail {
    border: 1px solid #e3e6f0;
    transition: transform 0.2s ease;
}

.img-thumbnail:hover {
    transform: scale(1.05);
    cursor: pointer;
}

/* Đảm bảo tất cả text đều 14px */
h1, h2, h3, h4, h5, h6 {
    font-size: 14px !important;
}

p, span, div, td, th, input, select, textarea, button, a {
    font-size: 14px !important;
}

small {
    font-size: 12px !important;
}

code {
    font-size: 13px !important;
}



/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px !important;
    }
    
    .d-flex.align-items-center img {
        width: 30px !important;
        height: 45px !important;
    }
}
</style>

<script>
// Biến toàn cục
var soKhungGio = 1;

// Danh sách phòng từ PHP
var phongList = [
    <?php foreach($danh_sach_phong as $key => $phong): ?>
    {id: '<?= $phong['id'] ?>', name: '<?= $phong['name'] ?>', so_ghe: '<?= $phong['so_ghe'] ?>'}<?= $key < count($danh_sach_phong) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
];

// Function toggle hiển thị form
function toggleFormKeHoach() {
    var formContainer = document.getElementById('form-container');
    var btnToggle = document.getElementById('btn-toggle-form');
    
    if (formContainer.style.display === 'none') {
        // Hiển thị form
        formContainer.style.display = 'block';
        btnToggle.style.display = 'none';
        
        // Scroll xuống form
        formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        // Ẩn form
        formContainer.style.display = 'none';
        btnToggle.style.display = 'block';
        
        // Scroll lên button
        btnToggle.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Function thêm khung giờ
function themKhungGio() {
    soKhungGio++;
    
    // Tạo options phòng
    var phongOptions = '<option value="">-- Chọn phòng --</option>';
    for (var i = 0; i < phongList.length; i++) {
        phongOptions += '<option value="' + phongList[i].id + '">' + phongList[i].name + ' (' + phongList[i].so_ghe + ' ghế)</option>';
    }
    
    // Tạo HTML khung giờ mới với cấu trúc admin - kích thước nhỏ gọn
    var html = '<div class="khung-gio-item mb-2" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">' +
               '<div class="row">' +
                   '<div class="col-md-4">' +
                       '<label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Giờ chiếu *</label>' +
                       '<input type="time" name="gio_bat_dau[]" class="form-control form-control-sm" required>' +
                   '</div>' +
                   '<div class="col-md-6">' +
                       '<label class="form-label" style="font-size: 13px; margin-bottom: 0.3rem;">Phòng chiếu *</label>' +
                       '<select name="ma_phong[]" class="form-control form-control-sm" required>' +
                           phongOptions +
                       '</select>' +
                   '</div>' +
                   '<div class="col-md-2 d-flex align-items-end">' +
                       '<button type="button" class="btn btn-danger btn-sm" onclick="xoaKhungGio(this)">' +
                           'Xóa' +
                       '</button>' +
                   '</div>' +
               '</div>' +
               '</div>';
    
    // Thêm vào container
    document.getElementById('container-khung-gio').insertAdjacentHTML('beforeend', html);
}

// Function xóa khung giờ
function xoaKhungGio(button) {
    var items = document.querySelectorAll('.khung-gio-item');
    
    if (items.length <= 1) {
        hienThongBao('error', '❌ Phải có ít nhất 1 khung giờ chiếu!');
        return;
    }
    
    button.closest('.khung-gio-item').remove();
    soKhungGio--;
    hienThongBao('success', '🗑️ Đã xóa khung giờ');
}

// Function hiển thị thông báo toast
function hienThongBao(type, message) {
    var toastContainer = document.getElementById('toast-notification');
    var toastClass = type === 'success' ? 'toast-message success' : 'toast-message error';
    
    var toastHtml = '<div class="' + toastClass + '">' + message + '</div>';
    toastContainer.innerHTML = toastHtml;
    
    // Tự động ẩn sau 4 giây
    setTimeout(function() {
        toastContainer.innerHTML = '';
    }, 4000);
}

// Validation form
document.getElementById('form-kehoach').addEventListener('submit', function(e) {
    // Kiểm tra có chọn phim nào không
    var selectedPhims = document.querySelectorAll('.phim-checkbox:checked').length;
    if (selectedPhims === 0) {
        e.preventDefault();
        hienThongBao('error', '❌ Vui lòng chọn ít nhất 1 phim!');
        return;
    }
    
    var tuNgay = document.getElementById('tu_ngay').value;
    var denNgay = document.getElementById('den_ngay').value;
    
    if (tuNgay && denNgay && new Date(denNgay) < new Date(tuNgay)) {
        e.preventDefault();
        hienThongBao('error', '❌ Ngày kết thúc phải sau ngày bắt đầu!');
        return;
    }
    
    var khungGioItems = document.querySelectorAll('.khung-gio-item').length;
    if (khungGioItems === 0) {
        e.preventDefault();
        hienThongBao('error', '❌ Phải có ít nhất 1 khung giờ chiếu!');
        return;
    }
    
    hienThongBao('success', '⏳ Đang lưu kế hoạch cho ' + selectedPhims + ' phim...');
});

// ============ FUNCTIONS CHO MULTI-SELECT PHIM ============

// Update số lượng phim được chọn
function updateCountPhim() {
    var count = document.querySelectorAll('.phim-checkbox:checked').length;
    document.getElementById('count-phim-selected').textContent = count + ' phim';
}

// Chọn tất cả phim (chỉ những phim đang hiển thị)
function chonTatCaPhim() {
    var visibleCheckboxes = document.querySelectorAll('.phim-checkbox-item:not([style*="display: none"]) .phim-checkbox');
    visibleCheckboxes.forEach(function(cb) {
        cb.checked = true;
    });
    updateCountPhim();
    hienThongBao('success', '✓ Đã chọn ' + visibleCheckboxes.length + ' phim');
}

// Bỏ chọn tất cả
function boChonTatCaPhim() {
    document.querySelectorAll('.phim-checkbox').forEach(function(cb) {
        cb.checked = false;
    });
    updateCountPhim();
    hienThongBao('success', '✗ Đã bỏ chọn tất cả');
}

// Search phim
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('search-phim');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var searchText = this.value.toLowerCase().trim();
            var items = document.querySelectorAll('.phim-checkbox-item');
            
            items.forEach(function(item) {
                var tenPhim = item.querySelector('.phim-checkbox').getAttribute('data-ten').toLowerCase();
                if (tenPhim.includes(searchText)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});

// Hiển thị thông báo ban đầu từ URL
<?php if (isset($_GET['msg'])): ?>
    window.addEventListener('DOMContentLoaded', function() {
        <?php if ($_GET['msg'] === 'success'): ?>
            <?php if (isset($_GET['success'])): ?>
                hienThongBao('success', '✅ <?= addslashes($_GET['success']) ?>');
            <?php else: ?>
                hienThongBao('success', '🎉 Tạo kế hoạch chiếu thành công!<?php if (isset($_GET['ke_hoach'])): ?> Mã: <?= htmlspecialchars($_GET['ke_hoach']) ?><?php endif; ?>');
            <?php endif; ?>
        <?php elseif ($_GET['msg'] === 'error'): ?>
            hienThongBao('error', '❌ <?= addslashes($_GET['error'] ?? 'Có lỗi xảy ra!') ?>');
        <?php endif; ?>
        
        // Xóa msg khỏi URL sau khi hiển thị
        setTimeout(function() {
            var url = new URL(window.location);
            url.searchParams.delete('msg');
            url.searchParams.delete('error');
            url.searchParams.delete('success');
            url.searchParams.delete('ke_hoach');
            window.history.replaceState({}, '', url);
        }, 500);
    });
<?php endif; ?>

console.log('🚀 Trang kế hoạch đã load thành công!');
</script>