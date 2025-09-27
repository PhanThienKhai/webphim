<?php
session_start();

// Fake login cluster manager
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4
];

// Include files cần thiết
include_once 'model/pdo.php';
include_once 'model/lichchieu.php';

// Test giao diện
$ds_lich_cho_duyet = lc_list_grouped_for_approval('cho_duyet');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Giao Diện Duyệt Lịch</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .plan-card { border: 1px solid #ddd; margin: 10px 0; padding: 15px; background: #f9f9f9; }
        .btn { padding: 8px 15px; margin: 5px; border: none; cursor: pointer; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>🎬 Test Giao Diện Duyệt Lịch Chiếu</h1>
    
    <p><strong>Số kế hoạch chờ duyệt:</strong> <?= count($ds_lich_cho_duyet) ?></p>
    
    <?php if (empty($ds_lich_cho_duyet)): ?>
        <div class="alert alert-info">
            <h3>📝 Không có kế hoạch nào chờ duyệt</h3>
            <p>Tất cả kế hoạch lịch chiếu đã được xử lý.</p>
        </div>
    <?php else: ?>
        <div class="plan-list">
            <h3>📋 Danh sách kế hoạch chờ duyệt:</h3>
            
            <?php foreach ($ds_lich_cho_duyet as $index => $lich): ?>
                <div class="plan-card">
                    <div class="plan-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4>🎭 <?= htmlspecialchars($lich['ten_phim']) ?></h4>
                            <div class="plan-info">
                                <span class="badge">📍 <?= htmlspecialchars($lich['ten_rap']) ?></span>
                                <span class="badge">📅 <?= $lich['tu_ngay'] ?> → <?= $lich['den_ngay'] ?></span>
                                <span class="badge">🎬 <?= $lich['so_ngay_chieu'] ?> ngày chiếu</span>
                            </div>
                            <p><strong>Mã kế hoạch:</strong> <?= htmlspecialchars($lich['ma_ke_hoach']) ?></p>
                            <p><strong>Người tạo:</strong> <?= htmlspecialchars($lich['nguoi_tao_ten']) ?></p>
                            <p><strong>Ngày tạo:</strong> <?= $lich['ngay_tao'] ?></p>
                        </div>
                        <div class="plan-actions">
                            <button class="btn btn-success" onclick="duyetKeHoach('<?= $lich['ma_ke_hoach'] ?>')">
                                ✅ Duyệt
                            </button>
                            <button class="btn btn-danger" onclick="tuChoiKeHoach('<?= $lich['ma_ke_hoach'] ?>')">
                                ❌ Từ chối
                            </button>
                        </div>
                    </div>
                    
                    <div class="plan-details" style="margin-top: 10px;">
                        <p><strong>📅 Danh sách ngày chiếu:</strong> <?= htmlspecialchars($lich['danh_sach_ngay']) ?></p>
                        <?php if (!empty($lich['ghi_chu'])): ?>
                            <p><strong>📝 Ghi chú:</strong> <?= htmlspecialchars($lich['ghi_chu']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <script>
        function duyetKeHoach(maKeHoach) {
            if (confirm('Xác nhận duyệt kế hoạch ' + maKeHoach + '?')) {
                // Gửi AJAX đến server
                fetch('index.php?act=duyet_lichchieu', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=duyet&ma_ke_hoach=' + encodeURIComponent(maKeHoach)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã duyệt kế hoạch thành công!');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể duyệt kế hoạch'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi duyệt kế hoạch!');
                });
            }
        }
        
        function tuChoiKeHoach(maKeHoach) {
            const ghiChu = prompt('Nhập lý do từ chối (tùy chọn):');
            if (ghiChu !== null) { // User didn't cancel
                fetch('index.php?act=duyet_lichchieu', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=tu_choi&ma_ke_hoach=' + encodeURIComponent(maKeHoach) + '&ghi_chu=' + encodeURIComponent(ghiChu)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã từ chối kế hoạch!');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể từ chối kế hoạch'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi từ chối kế hoạch!');
                });
            }
        }
    </script>
</body>
</html>