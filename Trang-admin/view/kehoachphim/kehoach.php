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
} catch (Exception $e) {
    $danh_sach_phim = [];
    $danh_sach_phong = [];
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
                <h3><i class="zmdi zmdi-movie-alt"></i> Lập Kế Hoạch Chiếu Phim</h3>
                <p>Tạo lịch chiếu và khung giờ chiếu cùng một lúc</p>
            </div>
        </div><!-- Page Heading End -->
    </div><!-- Page Headings End -->
    
    <!-- Thông báo -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <?php if ($_GET['msg'] === 'success'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>🎉 Tạo kế hoạch chiếu thành công!</strong>
                        <?php if (isset($_GET['ke_hoach'])): ?>
                            <br><small>Mã kế hoạch: <code><?= htmlspecialchars($_GET['ke_hoach']) ?></code></small>
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['msg'] === 'error'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>❌ <?= $_GET['error'] ?? 'Có lỗi xảy ra!' ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div id="thongbao"></div>
    
    <!-- Main Form -->
    <div class="row">
        <div class="col-12">
            <div class="news-item">
                <div class="content">
                    <form id="form-kehoach" method="post" action="index.php?act=luu_kehoach">
                        
                        <!-- Chọn phim -->
                        <div class="row mb-20">
                            <div class="col-md-6">
                                <label class="form-label">Chọn Phim *</label>
                                <select name="ma_phim" id="ma_phim" class="form-control" required>
                                    <option value="">-- Chọn phim --</option>
                                    <?php foreach($danh_sach_phim as $phim): ?>
                                        <option value="<?= $phim['id'] ?>"><?= $phim['tieu_de'] ?> (<?= $phim['thoi_luong_phim'] ?> phút)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ghi chú</label>
                                <input type="text" name="ghi_chu" class="form-control" placeholder="Ghi chú cho kế hoạch chiếu...">
                            </div>
                        </div>
                        
                        <!-- Chọn ngày -->
                        <div class="row mb-20">
                            <div class="col-md-6">
                                <label class="form-label">Từ ngày *</label>
                                <input type="date" name="tu_ngay" id="tu_ngay" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Đến ngày *</label>
                                <input type="date" name="den_ngay" id="den_ngay" class="form-control" required>
                                <small class="text-muted">💡 Nhập cùng ngày nếu chỉ chiếu 1 ngày</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Khung giờ chiếu -->
                        <h4 class="mb-20"><i class="zmdi zmdi-time"></i> Khung Giờ Chiếu</h4>
                        
                        <div id="container-khung-gio">
                            <!-- Khung giờ đầu tiên -->
                            <div class="khung-gio-item mb-20" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Giờ chiếu *</label>
                                        <input type="time" name="gio_bat_dau[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phòng chiếu *</label>
                                        <select name="ma_phong[]" class="form-control" required>
                                            <option value="">-- Chọn phòng --</option>
                                            <?php foreach($danh_sach_phong as $phong): ?>
                                                <option value="<?= $phong['id'] ?>"><?= $phong['name'] ?> (<?= $phong['so_ghe'] ?> ghế)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="xoaKhungGio(this)">
                                            <i class="zmdi zmdi-delete"></i> Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="btn-them" class="btn btn-success mb-20" onclick="themKhungGio()">
                            <i class="zmdi zmdi-plus"></i> Thêm Khung Giờ
                        </button>
                        
                        <hr>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="zmdi zmdi-save"></i> Lưu Kế Hoạch
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

</div><!-- Content Body End -->

<script>
// Biến toàn cục
var soKhungGio = 1;

// Danh sách phòng từ PHP
var phongList = [
    <?php foreach($danh_sach_phong as $key => $phong): ?>
    {id: '<?= $phong['id'] ?>', name: '<?= $phong['name'] ?>', so_ghe: '<?= $phong['so_ghe'] ?>'}<?= $key < count($danh_sach_phong) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
];

// Function thêm khung giờ
function themKhungGio() {
    soKhungGio++;
    
    // Tạo options phòng
    var phongOptions = '<option value="">-- Chọn phòng --</option>';
    for (var i = 0; i < phongList.length; i++) {
        phongOptions += '<option value="' + phongList[i].id + '">' + phongList[i].name + ' (' + phongList[i].so_ghe + ' ghế)</option>';
    }
    
    // Tạo HTML khung giờ mới với cấu trúc admin
    var html = '<div class="khung-gio-item mb-20" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px;">' +
               '<div class="row">' +
                   '<div class="col-md-4">' +
                       '<label class="form-label">Giờ chiếu *</label>' +
                       '<input type="time" name="gio_bat_dau[]" class="form-control" required>' +
                   '</div>' +
                   '<div class="col-md-6">' +
                       '<label class="form-label">Phòng chiếu *</label>' +
                       '<select name="ma_phong[]" class="form-control" required>' +
                           phongOptions +
                       '</select>' +
                   '</div>' +
                   '<div class="col-md-2 d-flex align-items-end">' +
                       '<button type="button" class="btn btn-danger btn-sm" onclick="xoaKhungGio(this)">' +
                           '<i class="zmdi zmdi-delete"></i> Xóa' +
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
        hienThongBao('danger', '❌ Phải có ít nhất 1 khung giờ chiếu!');
        return;
    }
    
    button.closest('.khung-gio-item').remove();
    soKhungGio--;
    hienThongBao('success', '🗑️ Đã xóa khung giờ');
}

// Function hiển thị thông báo
function hienThongBao(type, message) {
    var className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
    var alertHtml = '<div class="' + className + ' alert-dismissible fade show">' +
                    '<strong>' + message + '</strong>' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
    
    document.getElementById('thongbao').innerHTML = alertHtml;
    
    // Tự động ẩn sau 4 giây
    setTimeout(function() {
        var alert = document.querySelector('#thongbao .alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(function() {
                document.getElementById('thongbao').innerHTML = '';
            }, 150);
        }
    }, 4000);
}

// Validation form
document.getElementById('form-kehoach').addEventListener('submit', function(e) {
    var tuNgay = document.getElementById('tu_ngay').value;
    var denNgay = document.getElementById('den_ngay').value;
    
    if (tuNgay && denNgay && new Date(denNgay) < new Date(tuNgay)) {
        e.preventDefault();
        hienThongBao('danger', '❌ Ngày kết thúc phải sau ngày bắt đầu!');
        return;
    }
    
    var khungGioItems = document.querySelectorAll('.khung-gio-item').length;
    if (khungGioItems === 0) {
        e.preventDefault();
        hienThongBao('danger', '❌ Phải có ít nhất 1 khung giờ chiếu!');
        return;
    }
    
    hienThongBao('success', '⏳ Đang lưu kế hoạch...');
});

// Hiển thị thông báo ban đầu
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'success'): ?>
        hienThongBao('success', '🎉 Tạo kế hoạch chiếu thành công!');
    <?php elseif ($_GET['msg'] === 'error'): ?>
        hienThongBao('danger', '❌ <?= $_GET['error'] ?? 'Có lỗi xảy ra!' ?>');
    <?php endif; ?>
<?php endif; ?>

console.log('🚀 Trang kế hoạch đã load thành công!');
</script>