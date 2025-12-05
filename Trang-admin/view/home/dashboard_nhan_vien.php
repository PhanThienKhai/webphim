<?php
// Dashboard cho Nhân viên
if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_NHAN_VIEN) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập!</div>';
    return;
}

$id_nhan_vien = $_SESSION['user1']['id'] ?? null;
$id_rap = $_SESSION['user1']['id_rap'] ?? null;

if (!$id_nhan_vien || !$id_rap) {
    echo '<div class="alert alert-warning">Không thể xác định thông tin nhân viên!</div>';
    return;
}

// Lấy thông tin nhân viên
$info_nv = pdo_query_one("SELECT * FROM taikhoan WHERE id = ?", $id_nhan_vien);
$thong_tin_rap = pdo_query_one("SELECT * FROM rap_chieu WHERE id = ?", $id_rap);
$ten_rap = $thong_tin_rap['ten_rap'] ?? 'Rạp';

// Lấy ngày filter từ GET
$from_date = isset($_GET['from']) ? trim($_GET['from']) : date('Y-m-d', strtotime('-7 days'));
$to_date = isset($_GET['to']) ? trim($_GET['to']) : date('Y-m-d');

// Lịch làm việc hôm nay
$lich_hom_nay = pdo_query_one("
    SELECT llv.*, tk.name as ten_nv
    FROM lich_lam_viec llv
    JOIN taikhoan tk ON llv.id_nhan_vien = tk.id
    WHERE llv.id_nhan_vien = ? AND llv.ngay = CURDATE()
", $id_nhan_vien);

// Lịch làm việc 7 ngày tới
$lich_7_ngay = pdo_query("
    SELECT *
    FROM lich_lam_viec
    WHERE id_nhan_vien = ? AND ngay >= CURDATE() AND ngay < DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ORDER BY ngay
", $id_nhan_vien);

// Các ca trực nhân viên này hôm nay
$ca_truc_hom_nay = pdo_query("
    SELECT DISTINCT kg.thoi_gian_chieu, COUNT(*) as so_lich
    FROM lichchieu lc
    JOIN khung_gio_chieu kg ON lc.id = kg.id_lich_chieu
    WHERE lc.id_rap = ? AND lc.ngay_chieu = CURDATE()
    GROUP BY kg.thoi_gian_chieu
    ORDER BY kg.thoi_gian_chieu
", $id_rap);

// Vé bán hôm nay (toàn rạp)
$ve_hom_nay = pdo_query_one("
    SELECT COUNT(*) as tong_ve, COALESCE(SUM(price), 0) as doanh_thu
    FROM ve
    WHERE id_rap = ? AND DATE(ngay_dat) = CURDATE() AND trang_thai = 1
", $id_rap);

// Phim chiếu hôm nay
$phim_hom_nay = pdo_query("
    SELECT DISTINCT p.id, p.tieu_de, p.img, COUNT(lc.id) as so_suat
    FROM phim p
    JOIN lichchieu lc ON p.id = lc.id_phim
    WHERE lc.id_rap = ? AND lc.ngay_chieu = CURDATE()
    GROUP BY p.id, p.tieu_de, p.img
    ORDER BY p.tieu_de
", $id_rap);

// Lịch chiếu chi tiết hôm nay
$lich_chieu_hom_nay = pdo_query("
    SELECT lc.*, p.tieu_de, kg.thoi_gian_chieu, pc.name as phong_chieu
    FROM lichchieu lc
    JOIN phim p ON lc.id_phim = p.id
    JOIN khung_gio_chieu kg ON lc.id = kg.id_lich_chieu
    JOIN phongchieu pc ON kg.id_phong = pc.id
    WHERE lc.id_rap = ? AND lc.ngay_chieu = CURDATE()
    ORDER BY kg.thoi_gian_chieu
", $id_rap);

// Thống kê vé theo khoảng ngày
$ve_theo_ngay = pdo_query("
    SELECT 
        DATE(ngay_dat) as ngay,
        COUNT(*) as so_ve,
        COALESCE(SUM(price), 0) as doanh_thu
    FROM ve
    WHERE id_rap = ? AND DATE(ngay_dat) >= ? AND DATE(ngay_dat) <= ? AND trang_thai = 1
    GROUP BY DATE(ngay_dat)
    ORDER BY ngay
", $id_rap, $from_date, $to_date);

?>

<style>
    /* Gradient Header */
    .nv-dashboard-header {
        background: linear-gradient(135deg, #adb8e5ff 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .nv-dashboard-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .nv-dashboard-header p {
        margin: 10px 0 0 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .nv-dashboard-header .clock {
        font-size: 18px;
        margin-top: 15px;
        opacity: 0.9;
    }

    /* Statistics Grid */
    .nv-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .nv-stat-card {
        background: white;
        border-radius: 8px;
        padding: 5px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .nv-stat-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
        border-color: #667eea;
    }

    .nv-stat-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .nv-stat-value {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 10px 0;
    }

    .nv-stat-label {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    /* Content Body */
    .content-body {
        padding: 20px;
    }

    .box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .box .head {
        margin-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 15px;
    }

    .box .head h4 {
        margin: 0;
        color: #1f2937;
        font-weight: 600;
    }

    .schedule-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 10px;
        border-left: 4px solid #667eea;
    }

    .schedule-item strong {
        display: block;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .schedule-item small {
        color: #6b7280;
        display: block;
    }

    .movie-today {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .movie-thumb {
        width: 60px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .movie-info {
        flex: 1;
    }

    .movie-info strong {
        display: block;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .movie-info small {
        color: #6b7280;
    }
</style>

<div class="content-body">
    <!-- Header with Greeting -->
    <div class="nv-dashboard-header">
        <h2>Dashboard Nhân Viên</h2>
        <p>Chào mừng <?= htmlspecialchars($info_nv['name'] ?? 'Nhân viên') ?> • <?= htmlspecialchars($ten_rap) ?></p>
        <div class="clock">
            <strong>Thời gian:</strong> <span id="real-time-clock">--:--:--</span>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="nv-stats-grid">
        <div class="nv-stat-card">
            <div class="nv-stat-icon">🎫</div>
            <div class="nv-stat-label">Vé Bán Hôm Nay</div>
            <div class="nv-stat-value">
                <?= $ve_hom_nay['tong_ve'] ?? 0 ?>
            </div>
            <div class="nv-stat-label">Vé</div>
        </div>

        <div class="nv-stat-card">
            <div class="nv-stat-icon">💰</div>
            <div class="nv-stat-label">Doanh Thu Hôm Nay</div>
            <div class="nv-stat-value">
                <?= number_format($ve_hom_nay['doanh_thu'] ?? 0) ?>
            </div>
            <div class="nv-stat-label">VNĐ</div>
        </div>

        <div class="nv-stat-card">
            <div class="nv-stat-icon">🎬</div>
            <div class="nv-stat-label">Phim Chiếu Hôm Nay</div>
            <div class="nv-stat-value">
                <?= count($phim_hom_nay) ?>
            </div>
            <div class="nv-stat-label">Bộ</div>
        </div>

        <div class="nv-stat-card">
            <div class="nv-stat-icon">📅</div>
            <div class="nv-stat-label">Lịch Làm Hôm Nay</div>
            <div class="nv-stat-value">
                <?= $lich_hom_nay ? '✓' : '—' ?>
            </div>
            <div class="nv-stat-label"><?= $lich_hom_nay ? 'Có lịch' : 'Không lịch' ?></div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Lịch làm việc hôm nay -->
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>Lịch Làm Việc Hôm Nay</h4>
                </div>
                <div class="content">
                    <?php if ($lich_hom_nay): ?>
                        <div class="schedule-item">
                            <strong><?= htmlspecialchars($lich_hom_nay['ten_nv']) ?></strong>
                            <small>Ca làm: <?= htmlspecialchars($lich_hom_nay['ca_lam']) ?></small>
                            <?php if ($lich_hom_nay['ghi_chu']): ?>
                                <small>Ghi chú: <?= htmlspecialchars($lich_hom_nay['ghi_chu']) ?></small>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px;">Hôm nay không có lịch làm việc</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lịch làm việc 7 ngày tới -->
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>Lịch Làm Việc 7 Ngày Tới</h4>
                </div>
                <div class="content">
                    <?php if (!empty($lich_7_ngay)): ?>
                        <?php foreach ($lich_7_ngay as $lich): ?>
                            <div class="schedule-item">
                                <strong><?= date('d/m/Y', strtotime($lich['ngay'])) ?></strong>
                                <small>Ca: <?= htmlspecialchars($lich['ca_lam']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px;">Chưa có lịch trong 7 ngày tới</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Phim chiếu hôm nay -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>Phim Chiếu Hôm Nay</h4>
                </div>
                <div class="content">
                    <?php if (!empty($phim_hom_nay)): ?>
                        <?php foreach ($phim_hom_nay as $phim): ?>
                            <div class="movie-today">
                                <img src="../Trang-nguoi-dung/imgavt/<?= htmlspecialchars($phim['img']) ?>" alt="<?= htmlspecialchars($phim['tieu_de']) ?>" class="movie-thumb">
                                <div class="movie-info">
                                    <strong><?= htmlspecialchars($phim['tieu_de']) ?></strong>
                                    <small><?= $phim['so_suat'] ?> suất chiếu</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px;">Hôm nay không có phim chiếu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch chiếu chi tiết hôm nay -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>Lịch Chiếu Chi Tiết Hôm Nay</h4>
                </div>
                <div class="content">
                    <?php if (!empty($lich_chieu_hom_nay)): ?>
                        <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
                            <thead style="background: #f3f4f6;">
                                <tr>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Giờ Chiếu</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Phim</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Phòng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lich_chieu_hom_nay as $lich): ?>
                                    <tr>
                                        <td style="padding: 12px; border: 1px solid #e5e7eb;"><?= htmlspecialchars($lich['thoi_gian_chieu']) ?></td>
                                        <td style="padding: 12px; border: 1px solid #e5e7eb;"><?= htmlspecialchars($lich['tieu_de']) ?></td>
                                        <td style="padding: 12px; border: 1px solid #e5e7eb;"><?= htmlspecialchars($lich['phong_chieu']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="color: #6b7280; text-align: center; padding: 20px;">Hôm nay không có lịch chiếu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart vé theo ngày -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>Vé Bán Theo Ngày (Tuần Này)</h4>
                </div>
                <div class="content" style="padding: 20px; position: relative; height: 400px;">
                    <canvas id="ticketChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    function updateClock() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();

        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;

        var clockElement = document.getElementById('real-time-clock');
        if (clockElement) {
            clockElement.innerText = hours + ":" + minutes + ":" + seconds;
        }

        setTimeout(updateClock, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateClock();
        initTicketChart();
    });

    function initTicketChart() {
        var ctx = document.getElementById('ticketChart');
        if (!ctx) return;
        
        var data = <?= json_encode($ve_theo_ngay) ?>;
        var labels = [];
        var tickets = [];
        var revenues = [];
        
        data.forEach(function(item) {
            var date = new Date(item.ngay);
            labels.push(date.getDate() + '/' + (date.getMonth() + 1));
            tickets.push(item.so_ve);
            revenues.push(item.doanh_thu);
        });
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Số Vé',
                        data: tickets,
                        backgroundColor: '#667eea',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Doanh Thu (VNĐ)',
                        data: revenues,
                        backgroundColor: 'rgba(102, 126, 234, 0.2)',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        yAxisID: 'y1',
                        type: 'line',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số Vé'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh Thu'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
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
