<?php
// Dashboard cho Quản lý rạp
if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_QUAN_LY_RAP) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập!</div>';
    return;
}

$id_rap = $_SESSION['user1']['id_rap'] ?? null;
if (!$id_rap) {
    echo '<div class="alert alert-warning">Không thể xác định rạp của bạn!</div>';
    return;
}

// Lấy thông tin rạp
$thong_tin_rap = pdo_query_one("SELECT * FROM rap_chieu WHERE id = ?", $id_rap);
$ten_rap = $thong_tin_rap['ten_rap'] ?? 'Rạp của tôi';

// Thống kê nhanh
$stats = [];

// 1. Số phim đang chiếu
$result = pdo_query_one("
    SELECT COUNT(DISTINCT lc.id_phim) as total
    FROM lichchieu lc 
    WHERE lc.id_rap = ? AND lc.ngay_chieu >= CURDATE()
", $id_rap);
$stats['phim'] = $result['total'] ?? 0;

// 2. Doanh thu hôm nay
$result = pdo_query_one("
    SELECT COALESCE(SUM(v.price), 0) as total
    FROM ve v
    WHERE v.id_rap = ? 
    AND DATE(v.ngay_dat) = CURDATE()
    AND v.trang_thai = 1
", $id_rap);
$stats['doanh_thu_today'] = $result['total'] ?? 0;

// 3. Số nhân viên
$result = pdo_query_one("
    SELECT COUNT(*) as total
    FROM taikhoan 
    WHERE id_rap = ? AND vai_tro = ?
", $id_rap, ROLE_NHAN_VIEN);
$stats['nhan_vien'] = $result['total'] ?? 0;

// 4. Kế hoạch chờ duyệt
$result = pdo_query_one("
    SELECT COUNT(DISTINCT ma_ke_hoach) as total
    FROM lichchieu
    WHERE id_rap = ? AND trang_thai_duyet = 'Chờ duyệt'
", $id_rap);
$stats['ke_hoach_cho'] = $result['total'] ?? 0;

// 5. Vé đã bán hôm nay
$result = pdo_query_one("
    SELECT COUNT(*) as total
    FROM ve v
    WHERE v.id_rap = ? 
    AND DATE(v.ngay_dat) = CURDATE()
    AND v.trang_thai = 1
", $id_rap);
$stats['ve_hom_nay'] = $result['total'] ?? 0;

// 6. Đơn nghỉ chờ duyệt
$result = pdo_query_one("
    SELECT COUNT(*) as total
    FROM don_nghi_phep
    WHERE id_rap = ? AND trang_thai = 'Chờ duyệt'
", $id_rap);
$stats['don_nghi'] = $result['total'] ?? 0;

// Top 5 phim bán chạy tuần này
$top_phim = pdo_query("
    SELECT 
        p.tieu_de,
        p.img,
        COUNT(v.id) as so_ve,
        SUM(v.price) as doanh_thu
    FROM ve v
    JOIN phim p ON v.id_phim = p.id
    WHERE v.id_rap = ? 
    AND v.ngay_dat >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND v.trang_thai = 1
    GROUP BY p.id, p.tieu_de, p.img
    ORDER BY so_ve DESC
    LIMIT 5
", $id_rap);

// Lịch làm việc hôm nay
$lich_lam_today = pdo_query("
    SELECT 
        tk.name as ten_nv,
        llv.ca_lam,
        llv.ghi_chu
    FROM lich_lam_viec llv
    JOIN taikhoan tk ON llv.id_nhan_vien = tk.id
    WHERE llv.id_rap = ? 
    AND llv.ngay = CURDATE()
    ORDER BY llv.ca_lam
    LIMIT 10
", $id_rap);

// Doanh thu 7 ngày gần nhất
// Doanh thu 7 ngày gần nhất
$doanh_thu_7_ngay = pdo_query("
    SELECT 
        DATE(v.ngay_dat) as ngay,
        SUM(v.price) as doanh_thu
    FROM ve v
    WHERE v.id_rap = ? 
    AND v.ngay_dat >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND v.trang_thai = 1
    GROUP BY DATE(v.ngay_dat)
    ORDER BY ngay
", $id_rap);

?>

<!-- Content Body Start -->
<div class="content-body dashboard-rap">
    
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <h2>👋 Chào mừng trở lại, <?= htmlspecialchars($_SESSION['user1']['name']) ?>!</h2>
                <p>Quản lý <strong><?= htmlspecialchars($ten_rap) ?></strong> • <?= date('d/m/Y') ?></p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-primary">
                <div class="stat-icon">🎬</div>
                <div class="stat-info">
                    <h3><?= $stats['phim'] ?></h3>
                    <p>Phim đang chiếu</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-success">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3><?= number_format($stats['doanh_thu_today']) ?>đ</h3>
                    <p>Doanh thu hôm nay</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-info">
                <div class="stat-icon">🎫</div>
                <div class="stat-info">
                    <h3><?= $stats['ve_hom_nay'] ?></h3>
                    <p>Vé đã bán hôm nay</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-warning">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3><?= $stats['nhan_vien'] ?></h3>
                    <p>Nhân viên</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-pending">
                <div class="stat-icon">📋</div>
                <div class="stat-info">
                    <h3><?= $stats['ke_hoach_cho'] ?></h3>
                    <p>Kế hoạch chờ duyệt</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-danger">
                <div class="stat-icon">🏖️</div>
                <div class="stat-info">
                    <h3><?= $stats['don_nghi'] ?></h3>
                    <p>Đơn nghỉ chờ duyệt</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <a href="index.php?act=kehoach" class="stat-card stat-action" style="text-decoration: none;">
                <div class="stat-icon">➕</div>
                <div class="stat-info">
                    <h3>Tạo mới</h3>
                    <p>Kế hoạch chiếu</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Top Phim -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>🏆 Top Phim Tuần Này</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_phim)): ?>
                        <div class="top-movies-list">
                            <?php foreach ($top_phim as $index => $phim): ?>
                            <div class="movie-item">
                                <div class="movie-rank">#<?= $index + 1 ?></div>
                                <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($phim['img']) ?>" alt="<?= htmlspecialchars($phim['tieu_de']) ?>" class="movie-thumb">
                                <div class="movie-info">
                                    <h6><?= htmlspecialchars($phim['tieu_de']) ?></h6>
                                    <small><?= $phim['so_ve'] ?> vé • <?= number_format($phim['doanh_thu']) ?>đ</small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Chưa có dữ liệu bán vé tuần này</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lịch làm việc hôm nay -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>📅 Lịch Làm Việc Hôm Nay</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($lich_lam_today)): ?>
                        <div class="schedule-list">
                            <?php foreach ($lich_lam_today as $ll): ?>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <span class="badge badge-primary"><?= htmlspecialchars($ll['ca_lam']) ?></span>
                                </div>
                                <div class="schedule-info">
                                    <strong><?= htmlspecialchars($ll['ten_nv']) ?></strong>
                                    <?php if ($ll['ghi_chu']): ?>
                                        <small class="text-muted"> - <?= htmlspecialchars($ll['ghi_chu']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="index.php?act=ql_lichlamviec_calendar" class="btn btn-sm btn-outline-primary mt-3">Xem tất cả lịch làm việc →</a>
                    <?php else: ?>
                        <p class="text-muted">Chưa có lịch làm việc cho hôm nay</p>
                        <a href="index.php?act=ql_lichlamviec_calendar" class="btn btn-sm btn-primary mt-2">Phân công nhân viên</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Doanh thu -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>📈 Doanh Thu 7 Ngày Gần Đây</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>⚡ Thao Tác Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="index.php?act=kehoach" class="action-btn">
                            <span class="action-icon">📋</span>
                            <span>Lập kế hoạch</span>
                        </a>
                        <a href="index.php?act=ql_lichlamviec_calendar" class="action-btn">
                            <span class="action-icon">👥</span>
                            <span>Phân công ca</span>
                        </a>
                        <a href="index.php?act=ql_duyetnghi" class="action-btn">
                            <span class="action-icon">✅</span>
                            <span>Duyệt nghỉ phép</span>
                        </a>
                        <a href="index.php?act=ve" class="action-btn">
                            <span class="action-icon">🎫</span>
                            <span>Quản lý vé</span>
                        </a>
                        <a href="index.php?act=phong" class="action-btn">
                            <span class="action-icon">🎬</span>
                            <span>Phòng chiếu</span>
                        </a>
                        <a href="index.php?act=TKrap" class="action-btn">
                            <span class="action-icon">📊</span>
                            <span>Thống kê</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- Content Body End -->

<style>
.dashboard-rap {
    font-family: 'Inter', sans-serif;
}

.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.welcome-card h2 {
    font-size: 28px !important;
    font-weight: 600;
    margin-bottom: 5px;
}

.welcome-card p {
    font-size: 16px !important;
    opacity: 0.9;
    margin: 0;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.stat-icon {
    font-size: 48px;
    line-height: 1;
}

.stat-info h3 {
    font-size: 32px !important;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.stat-info p {
    font-size: 14px !important;
    color: #6c757d;
    margin: 0;
}

.stat-primary { border-left: 4px solid #667eea; }
.stat-success { border-left: 4px solid #28a745; }
.stat-info { border-left: 4px solid #17a2b8; }
.stat-warning { border-left: 4px solid #ffc107; }
.stat-pending { border-left: 4px solid #ff9800; }
.stat-danger { border-left: 4px solid #dc3545; }
.stat-action { 
    border-left: 4px solid #6c757d; 
    cursor: pointer;
    color: inherit;
}
.stat-action:hover {
    background: #f8f9fa;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
    padding: 15px 20px;
    border-radius: 12px 12px 0 0 !important;
}

.card-header h5 {
    font-size: 18px !important;
    font-weight: 600;
    margin: 0;
}

.top-movies-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.movie-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.movie-rank {
    font-size: 24px;
    font-weight: 700;
    color: #667eea;
    min-width: 40px;
    text-align: center;
}

.movie-thumb {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
}

.movie-info h6 {
    font-size: 14px !important;
    font-weight: 600;
    margin: 0 0 4px 0;
}

.movie-info small {
    font-size: 12px !important;
    color: #6c757d;
}

.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.schedule-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.schedule-time .badge {
    font-size: 12px;
    padding: 6px 12px;
}

.schedule-info strong {
    font-size: 14px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.action-icon {
    font-size: 36px;
}

.action-btn span:last-child {
    font-size: 14px;
    font-weight: 500;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart doanh thu
const ctx = document.getElementById('revenueChart');
if (ctx) {
    const chartData = <?= json_encode($doanh_thu_7_ngay) ?>;
    const labels = chartData.map(d => {
        const date = new Date(d.ngay);
        return date.getDate() + '/' + (date.getMonth() + 1);
    });
    const data = chartData.map(d => d.doanh_thu);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            }
        }
    });
}
</script>
