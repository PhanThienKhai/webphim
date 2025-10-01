<?php
// Kiểm tra quyền truy cập - chỉ quản lý rạp mới được tạo kế hoạch
if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_QUAN_LY_RAP) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập tính năng này!</div>';
    return;
}

// Lấy thông tin rạp của quản lý
$ma_rap = $_SESSION['user1']['id_rap'] ?? null;
if (!$ma_rap) {
    echo '<div class="alert alert-warning">Không thể xác định rạp của bạn. Vui lòng liên hệ admin!</div>';
    return;
}

// Load danh sách phim được phân phối cho rạp này
$danh_sach_phim = phim_assigned_to_rap($ma_rap);
$danh_sach_phong = load_phong_by_rap($ma_rap);
?>

<?php include "./view/home/sideheader.php"; ?>

<!-- Content Body Start -->
<div class="content-body">
    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-12 col-lg-auto mb-3">
            <div class="page-heading">
                <h3><i class="zmdi zmdi-movie-alt"></i> Lập Kế Hoạch Chiếu Phim</h3>
                <p class="text-muted">Tạo lịch chiếu và khung giờ chiếu cùng một lúc</p>
            </div>
        </div>
    </div>
    
    <!-- Thông báo kết quả -->
    <?php if (isset($thongbao) && !empty($thongbao)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert <?= strpos($thongbao, '✅') !== false || strpos($thongbao, '🎬') !== false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show">
                    <strong><?= $thongbao ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc và danh sách kế hoạch hiện có -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="zmdi zmdi-filter-list"></i> Kế Hoạch Chiếu Hiện Có</h5>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lọc theo phim:</label>
                            <select id="filter-phim" class="form-control">
                                <option value="">-- Tất cả phim --</option>
                                <?php foreach($danh_sach_phim as $phim): ?>
                                    <option value="<?= $phim['id'] ?>">
                                        <?= $phim['tieu_de'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lọc theo tháng:</label>
                            <input type="month" id="filter-thang" class="form-control" value="<?= date('Y-m') ?>">
                        </div>
                    </div>
                    
                    <!-- Danh sách kế hoạch -->
                    <div id="danh-sach-ke-hoach">
                        <?php
                        // Load kế hoạch chiếu hiện có của rạp
                        $sql_kehoach = "SELECT lc.*, p.tieu_de, p.thoi_luong_phim 
                                       FROM lichchieu lc 
                                       INNER JOIN phim p ON p.id = lc.id_phim 
                                       WHERE lc.id_rap = ? 
                                       ORDER BY lc.ngay_chieu DESC, lc.id DESC 
                                       LIMIT 10";
                        $kehoach_hien_tai = pdo_query($sql_kehoach, $ma_rap);
                        ?>
                        
                        <?php if (count($kehoach_hien_tai) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Phim</th>
                                            <th>Ngày chiếu</th>
                                            <th>Trạng thái</th>
                                            <th>Khung giờ - Phòng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($kehoach_hien_tai as $kh): ?>
                                            <tr data-phim-id="<?= $kh['id_phim'] ?>" data-ngay="<?= $kh['ngay_chieu'] ?>">
                                                <td>
                                                    <strong><?= $kh['tieu_de'] ?></strong><br>
                                                    <small class="text-muted"><?= $kh['thoi_luong_phim'] ?> phút</small>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($kh['ngay_chieu'])) ?></td>
                                                <td>
                                                    <?php if ($kh['trang_thai_duyet'] == 1): ?>
                                                        <span class="badge badge-success">Đã duyệt</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Chờ duyệt</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sql_khung = "SELECT kgc.thoi_gian_chieu, pc.name as ten_phong 
                                                                  FROM khung_gio_chieu kgc 
                                                                  INNER JOIN phongchieu pc ON pc.id = kgc.id_phong 
                                                                  WHERE kgc.id_lich_chieu = ? 
                                                                  ORDER BY kgc.thoi_gian_chieu";
                                                    $khung_gio = pdo_query($sql_khung, $kh['id']);
                                                    ?>
                                                    <?php foreach($khung_gio as $kg): ?>
                                                        <div class="mb-1">
                                                            <span class="badge badge-info"><?= date('H:i', strtotime($kg['thoi_gian_chieu'])) ?></span>
                                                            <small class="text-muted">- <?= $kg['ten_phong'] ?></small>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary export-word" data-id="<?= $kh['id'] ?>">
                                                        <i class="zmdi zmdi-download"></i> Word
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="zmdi zmdi-info"></i> Chưa có kế hoạch chiếu nào. Hãy tạo kế hoạch mới bên dưới!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Planning Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="zmdi zmdi-calendar-alt"></i> Form Lập Kế Hoạch Chiếu</h4>
                    <small>Điền thông tin để tạo kế hoạch chiếu hoàn chỉnh</small>
                </div>
                <div class="card-body">
                    <form id="kehoach-form" method="post" action="index.php?act=luu_kehoach">
                        <!-- Container thông báo -->
                        <div id="thong-bao-container" class="mb-3"></div>
                        
                        <div class="row">
                            <!-- Chọn phim -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label"><i class="zmdi zmdi-movie"></i> Chọn Phim *</label>
                                <select name="ma_phim" id="ma_phim" class="form-control" required>
                                    <option value="">-- Chọn phim để chiếu --</option>
                                    <?php foreach($danh_sach_phim as $phim): ?>
                                        <option value="<?= $phim['id'] ?>" 
                                                data-thoi-luong="<?= $phim['thoi_luong_phim'] ?>">
                                            <?= $phim['tieu_de'] ?> (<?= $phim['thoi_luong_phim'] ?> phút)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Khoảng thời gian chiếu -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="zmdi zmdi-calendar"></i> Thời Gian Chiếu *</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Từ ngày:</label>
                                        <input type="date" name="tu_ngay" id="tu_ngay" 
                                               class="form-control" required
                                               min="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Đến ngày:</label>
                                        <input type="date" name="den_ngay" id="den_ngay" 
                                               class="form-control" required
                                               min="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="zmdi zmdi-info"></i> 
                                    Để cùng 1 ngày = chiếu 1 lần. Khác ngày = chiếu liên tục nhiều ngày.
                                </small>
                                <div id="thong-tin-tong-quan"></div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="zmdi zmdi-comment-text"></i> Ghi Chú</label>
                                <textarea name="ghi_chu" id="ghi_chu" 
                                       class="form-control" rows="2"
                                       placeholder="Ghi chú cho kế hoạch chiếu (gửi lên quản lý cụm)"></textarea>
                            </div>
                        </div>

                        <!-- Khung giờ chiếu -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="border rounded p-3" style="background: rgba(255,255,255,0.1);">
                                    <h5 class="mb-3"><i class="zmdi zmdi-time"></i> Khung Giờ Chiếu & Phòng</h5>
                                    <p class="text-muted mb-3">Mỗi khung giờ có thể chọn phòng chiếu khác nhau</p>
                                    
                                    <div id="khung-gio-container">
                                        <div class="khung-gio-item mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <label class="form-label">Giờ Chiếu *</label>
                                                    <input type="time" name="gio_bat_dau[]" class="form-control gio-bat-dau" required>
                                                    <small class="text-muted">Thời gian bắt đầu</small>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Phòng Chiếu *</label>
                                                    <select name="ma_phong[]" class="form-control phong-chieu" required>
                                                        <option value="">-- Chọn phòng --</option>
                                                        <?php foreach($danh_sach_phong as $phong): ?>
                                                            <option value="<?= $phong['id'] ?>">
                                                                <?= $phong['name'] ?> (<?= $phong['so_ghe'] ?> ghế)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <small class="text-muted">Phòng cho khung giờ này</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-khung-gio" 
                                                            style="margin-top: 8px;" onclick="xoaKhungGio(this)">
                                                        <i class="zmdi zmdi-delete"></i> Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" id="btn-them-khung-gio" class="btn btn-success btn-sm" onclick="themKhungGioMoi()">
                                        <i class="zmdi zmdi-plus"></i> Thêm Khung Giờ & Phòng
                                    </button>
                                    
                                    <!-- Test button đơn giản -->
                                    <button type="button" onclick="alert('Test button hoạt động!')" class="btn btn-warning btn-sm ml-2">
                                        TEST
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-label"><i class="zmdi zmdi-comment-text"></i> Ghi Chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3" 
                                          placeholder="Ghi chú thêm về kế hoạch chiếu này..."></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="button" id="preview-kehoach" class="btn btn-info">
                                        <i class="zmdi zmdi-eye"></i> Xem Trước
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="zmdi zmdi-floppy"></i> Lưu Kế Hoạch
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="zmdi zmdi-refresh"></i> Làm Mới
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="preview-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="zmdi zmdi-eye"></i> Xem Trước Kế Hoạch Chiếu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="preview-content">
                    <!-- Preview content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="$('#kehoach-form').submit();">
                        <i class="zmdi zmdi-check"></i> Xác Nhận Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// === JavaScript hoàn toàn mới, đơn giản ===

function themKhungGioMoi() {
    alert('BẮT ĐẦU THÊM KHUNG GIỜ!');
    
    var container = document.getElementById('khung-gio-container');
    if (!container) {
        alert('KHÔNG TÌM THẤY CONTAINER!');
        return;
    }
    
    var html = '<div style="background:yellow; border:5px solid red; padding:15px; margin:10px 0;">' +
              '<h4>🎬 KHUNG GIỜ MỚI ĐÃ ĐƯỢC THÊM!</h4>' +
              '<p><strong>Giờ chiếu:</strong> <input type="time" name="gio_bat_dau[]" style="padding:5px; font-size:16px;"></p>' +
              '<p><strong>Phòng:</strong> ' +
              '<select name="ma_phong[]" style="padding:5px; font-size:16px;">' +
              '<option value="">-- Chọn phòng --</option>' +
              '<?php foreach($danh_sach_phong as $phong): ?>' +
              '<option value="<?= $phong["id"] ?>"><?= $phong["name"] ?> (<?= $phong["so_ghe"] ?> ghế)</option>' +
              '<?php endforeach; ?>' +
              '</select></p>' +
              '<button type="button" onclick="this.parentElement.remove(); alert(\'Đã xóa!\');" style="background:red; color:white; padding:10px; font-size:16px;">🗑️ XÓA KHUNG GIỜ NÀY</button>' +
              '</div>';
    
    container.innerHTML = container.innerHTML + html;
    alert('✅ ĐÃ THÊM XONG! Kiểm tra bên dưới!');
}

alert('JavaScript đã load!');
$(document).ready(function() {
    alert('jQuery đã sẵn sàng!');
    
    // Các function jQuery khác...
    
    // Hiển thị thông báo từ URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('msg') === 'success') {
        showMessage('success', 'Tạo kế hoạch chiếu thành công!');
    } else if (urlParams.get('msg') === 'error') {
        showMessage('error', urlParams.get('error') || 'Có lỗi xảy ra!');
    }
    
    // Validation khoảng ngày
    $('#tu_ngay, #den_ngay').change(function() {
        const tuNgay = $('#tu_ngay').val();
        const denNgay = $('#den_ngay').val();
        
        if (tuNgay && denNgay) {
            const diff = (new Date(denNgay) - new Date(tuNgay)) / (1000 * 60 * 60 * 24);
            if (diff < 0) {
                alert('Ngày kết thúc phải sau hoặc bằng ngày bắt đầu!');
                $('#den_ngay').val('');
                return;
            }
            if (diff > 31) {
                alert('Thời gian chiếu tối đa 31 ngày!');
                $('#den_ngay').val('');
                return;
            }
            
            // Hiển thị thông tin tổng quan
            if (diff === 0) {
                $('#thong-tin-tong-quan').html(`
                    <div class="alert alert-info mt-2">
                        <i class="zmdi zmdi-calendar"></i> 
                        <strong>Chiếu 1 ngày:</strong> ${formatDate(tuNgay)}
                    </div>
                `);
            } else {
                $('#thong-tin-tong-quan').html(`
                    <div class="alert alert-success mt-2">
                        <i class="zmdi zmdi-repeat"></i> 
                        <strong>Chiếu liên tục ${diff + 1} ngày:</strong> từ ${formatDate(tuNgay)} đến ${formatDate(denNgay)}
                    </div>
                `);
            }
        } else {
            $('#thong-tin-tong-quan').html('');
        }
    });
    
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('vi-VN');
    }
    // Tự động tính giờ kết thúc khi chọn phim và giờ bắt đầu
    function calculateEndTime() {
        const thoiLuong = $('#ma_phim option:selected').data('thoi-luong') || 0;
        
        $('.gio-bat-dau').each(function() {
            const gioBatDau = $(this).val();
            if (gioBatDau && thoiLuong) {
                const [hour, minute] = gioBatDau.split(':').map(Number);
                const totalMinutes = hour * 60 + minute + thoiLuong + 15; // +15 phút dọn dẹp
                const endHour = Math.floor(totalMinutes / 60);
                const endMinute = totalMinutes % 60;
                
                const gioKetThuc = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
                $(this).closest('.khung-gio-item').find('.gio-ket-thuc').val(gioKetThuc);
            }
        });
    }

    // Event listeners
    $('#ma_phim').change(calculateEndTime);
    $(document).on('change', '.gio-bat-dau', calculateEndTime);
    
    // Debug nút thêm khung giờ
    console.log('Tìm nút add-khung-gio:', $('#add-khung-gio').length);
    console.log('addKhungGio function có tồn tại:', typeof addKhungGio);

    // Xóa khung giờ - cập nhật event handler
    $(document).on('click', '.remove-khung-gio', function() {
        const items = $('.khung-gio-item');
        if (items.length > 1) {
            $(this).closest('.khung-gio-item').remove();
            console.log('Đã xóa khung giờ!');
        } else {
            alert('Phải có ít nhất một khung giờ chiếu!');
        }
    });

    // Preview kế hoạch
    $('#preview-kehoach').click(function() {
        const formData = $('#kehoach-form').serialize();
        
        // Validate form first
        if (!$('#kehoach-form')[0].checkValidity()) {
            $('#kehoach-form')[0].reportValidity();
            return;
        }

        $.post('index.php?act=preview_kehoach', formData, function(response) {
            $('#preview-content').html(response);
            $('#preview-modal').modal('show');
        }).fail(function() {
            alert('Có lỗi khi tạo preview. Vui lòng thử lại!');
        });
    });

    // Form validation và hiển thị thông báo
    $('#kehoach-form').submit(function(e) {
        const khungGioItems = $('.khung-gio-item').length;
        if (khungGioItems === 0) {
            e.preventDefault();
            showMessage('error', 'Vui lòng thêm ít nhất một khung giờ chiếu!');
            return false;
        }
        
        // Kiểm tra từ ngày đến ngày
        const tuNgay = $('#tu_ngay').val();
        const denNgay = $('#den_ngay').val();
        
        if (tuNgay && denNgay) {
            const diff = (new Date(denNgay) - new Date(tuNgay)) / (1000 * 60 * 60 * 24);
            if (diff < 0) {
                e.preventDefault();
                showMessage('error', 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu!');
                return false;
            }
        }
        
        // Kiểm tra xung đột phòng-giờ
        const conflicts = checkTimeRoomConflicts();
        if (conflicts.length > 0) {
            e.preventDefault();
            showMessage('error', 'Có xung đột phòng chiếu: ' + conflicts.join(', '));
            return false;
        }
        
        showMessage('info', 'Đang xử lý kế hoạch chiếu...');
        return true;
    });
    
    // Hàm hiển thị thông báo
    function showMessage(type, message) {
        const alertClass = type === 'error' ? 'alert-danger' : 
                          type === 'success' ? 'alert-success' : 'alert-info';
        const icon = type === 'error' ? 'zmdi-alert-triangle' : 
                    type === 'success' ? 'zmdi-check-circle' : 'zmdi-info';
        
        const messageHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="zmdi ${icon}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('#thong-bao-container').html(messageHtml);
        
        // Auto hide sau 5 giây
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
    
    // Kiểm tra xung đột phòng-giờ
    function checkTimeRoomConflicts() {
        const conflicts = [];
        const timeRoomPairs = [];
        
        $('.khung-gio-item').each(function() {
            const time = $(this).find('[name="gio_bat_dau[]"]').val();
            const room = $(this).find('[name="ma_phong[]"]').val();
            const roomName = $(this).find('[name="ma_phong[]"] option:selected').text();
            
            if (time && room) {
                const pair = `${time}-${room}`;
                if (timeRoomPairs.includes(pair)) {
                    conflicts.push(`${time} tại ${roomName}`);
                } else {
                    timeRoomPairs.push(pair);
                }
            }
        });
        
        return conflicts;
    } {
            const tuNgay = $('#tu_ngay').val();
            const denNgay = $('#den_ngay').val();
            
            if (!tuNgay || !denNgay) {
                e.preventDefault();
                alert('Vui lòng chọn đầy đủ khoảng thời gian chiếu!');
                return false;
            }
            
            const diff = (new Date(denNgay) - new Date(tuNgay)) / (1000 * 60 * 60 * 24);
            if (diff > 31) {
                e.preventDefault();
                alert('Thời gian chiếu tối đa 31 ngày!');
                return false;
            }
            
            // Xác nhận tạo lịch liên tục
            if (!confirm(`Bạn có chắc muốn tạo lịch chiếu liên tục ${diff + 1} ngày?\nLưu ý: Thao tác này sẽ tạo ${(diff + 1) * khungGioItems} suất chiếu!`)) {
                e.preventDefault();
                return false;
            }
        }

        // Kiểm tra xung đột phòng-giờ  
        const phongGioMap = {};
        let hasConflict = false;
        
        $('.khung-gio-item').each(function() {
            const gio = $(this).find('.gio-bat-dau').val();
            const phong = $(this).find('.phong-chieu').val();
            
            if (gio && phong) {
                const key = phong + '_' + gio;
                if (phongGioMap[key]) {
                    e.preventDefault();
                    alert('Phòng "' + $(this).find('.phong-chieu option:selected').text() + '" đã có khung giờ ' + gio + '! Vui lòng kiểm tra lại.');
                    hasConflict = true;
                    return false;
                }
                phongGioMap[key] = true;
            }
        });
        
        if (hasConflict) return false;
    });
    
    // Bộ lọc kế hoạch chiếu
    $('#filter-phim, #filter-thang').on('change', function() {
        filterKeHoach();
    });
    
    function filterKeHoach() {
        const selectedPhim = $('#filter-phim').val();
        const selectedThang = $('#filter-thang').val();
        
        $('#danh-sach-ke-hoach table tbody tr').each(function() {
            const phimId = $(this).data('phim-id');
            const ngayChieu = $(this).data('ngay');
            const thangChieu = ngayChieu.substring(0, 7); // YYYY-MM
            
            let showRow = true;
            
            // Lọc theo phim
            if (selectedPhim && phimId != selectedPhim) {
                showRow = false;
            }
            
            // Lọc theo tháng
            if (selectedThang && thangChieu != selectedThang) {
                showRow = false;
            }
            
            $(this).toggle(showRow);
        });
    }
    
    // Export Word
    $('.export-word').on('click', function() {
        const keHoachId = $(this).data('id');
        
        // Tạo form ẩn để submit
        const form = $('<form method="post" action="index.php?act=export_word_kehoach">' +
                      '<input type="hidden" name="kehoach_id" value="' + keHoachId + '">' +
                      '</form>');
        
        $('body').append(form);
        form.submit();
        form.remove();
        
        // Hiện thông báo
        alert('Đang tạo file Word... Vui lòng đợi trong giây lát!');
    });
});
</script>

<style>
.khung-gio-item {
    border: 1px dashed rgba(255,255,255,0.3);
    border-radius: 8px;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    transition: all 0.3s ease;
}

.khung-gio-item:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

#khung-gio-container .form-control {
    background: rgba(255,255,255,0.9);
    border: 1px solid #e2e8f0;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
}

.gap-2 {
    gap: 8px!important;
}

.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
}
</style>

</div> <!-- End content-body -->