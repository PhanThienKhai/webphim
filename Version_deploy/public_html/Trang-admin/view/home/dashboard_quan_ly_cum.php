<!-- Dashboard Quản Lý Cụm -->
<style>
    /* Gradient Header */
    .cum-dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .cum-dashboard-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .cum-dashboard-header p {
        margin: 10px 0 0 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .cum-dashboard-header .clock {
        font-size: 18px;
        margin-top: 15px;
        opacity: 0.9;
    }

    /* Statistics Grid */
    .cum-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .cum-stat-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .cum-stat-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
        border-color: #667eea;
    }

    .cum-stat-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .cum-stat-value {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 10px 0;
    }

    .cum-stat-label {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    /* Report Row */
    .cum-report-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .cum-report-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 25px;
        transition: all 0.3s ease;
    }

    .cum-report-box:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .cum-report-box-header {
        color: #667eea;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cum-report-box-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .cum-report-box-unit {
        color: #9ca3af;
        font-size: 13px;
    }

    /* Film List */
    .cum-film-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .cum-film-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cum-film-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .cum-film-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .cum-film-card:hover {
        background: white;
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .cum-film-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .cum-film-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .cum-film-badge.showing {
        background: #d1fae5;
        color: #065f46;
    }

    .cum-film-badge.coming {
        background: #fef3c7;
        color: #92400e;
    }

    .cum-film-badge.ended {
        background: #fee2e2;
        color: #991b1b;
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
</style>

<div class="content-body">
    <!-- Header with Greeting -->
    <div class="cum-dashboard-header">
        <h2>🏢 Dashboard Quản Lý Cụm</h2>
        <p>Chào mừng <?= htmlspecialchars($_SESSION['user1']['name'] ?? 'Quản lý cụm') ?></p>
        <div class="clock">
            <strong>⏰ Thời gian:</strong> <span id="real-time-clock">--:--:--</span>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="cum-stats-grid">
        <div class="cum-stat-card">
            <div class="cum-stat-icon">💰</div>
            <div class="cum-stat-label">Tổng Doanh Thu</div>
            <div class="cum-stat-value">
                <?php 
                if (!empty($tong)) {
                    foreach ($tong as $t) {
                        echo number_format($t['tong_doanh_thu'] ?? 0);
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-stat-label">VNĐ</div>
        </div>

        <div class="cum-stat-card">
            <div class="cum-stat-icon">🎟️</div>
            <div class="cum-stat-label">Tổng Vé Bán</div>
            <div class="cum-stat-value">
                <?php 
                if (!empty($tong)) {
                    foreach ($tong as $t) {
                        echo $t['tong_so_luong_ve_dat'] ?? '0';
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-stat-label">Vé</div>
        </div>

        <div class="cum-stat-card">
            <div class="cum-stat-icon">🎬</div>
            <div class="cum-stat-label">Phim Đang Chiếu</div>
            <div class="cum-stat-value">
                <?php 
                if (!empty($tpdc)) {
                    foreach ($tpdc as $t) {
                        echo $t['total_phim'] ?? '0';
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-stat-label">Bộ</div>
        </div>

        <div class="cum-stat-card">
            <div class="cum-stat-icon">📅</div>
            <div class="cum-stat-label">Phim Sắp Chiếu</div>
            <div class="cum-stat-value">
                <?php 
                if (!empty($tpsc)) {
                    foreach ($tpsc as $t) {
                        echo $t['total_phim'] ?? '0';
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-stat-label">Bộ</div>
        </div>
    </div>

    <!-- Revenue Report -->
    <div class="cum-report-row">
        <div class="cum-report-box">
            <div class="cum-report-box-header">📊 Doanh Thu Hôm Nay</div>
            <div class="cum-report-box-value">
                <?php 
                if (!empty($tong_day)) {
                    foreach ($tong_day as $t) {
                        echo number_format($t['tong_doanh_thu'] ?? 0);
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-report-box-unit">VNĐ</div>
        </div>

        <div class="cum-report-box">
            <div class="cum-report-box-header">📈 Doanh Thu Tuần Này</div>
            <div class="cum-report-box-value">
                <?php 
                if (!empty($tong_tuan)) {
                    foreach ($tong_tuan as $t) {
                        echo number_format($t['tong_doanh_thu'] ?? 0);
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-report-box-unit">VNĐ</div>
        </div>

        <div class="cum-report-box">
            <div class="cum-report-box-header">📅 Doanh Thu Tháng Này</div>
            <div class="cum-report-box-value">
                <?php 
                if (!empty($tong_thang)) {
                    foreach ($tong_thang as $t) {
                        echo number_format($t['tong_doanh_thu'] ?? 0);
                        break;
                    }
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="cum-report-box-unit">VNĐ</div>
        </div>

        <div class="cum-report-box">
            <div class="cum-report-box-header">🍿 Combo Top</div>
            <div class="cum-report-box-value">
                <?php 
                if (!empty($best_combo)) {
                    foreach ($best_combo as $t) {
                        echo htmlspecialchars($t['combo'] ?? 'N/A');
                        break;
                    }
                } else {
                    echo 'N/A';
                }
                ?>
            </div>
            <div class="cum-report-box-unit">
                <?php 
                if (!empty($best_combo)) {
                    foreach ($best_combo as $t) {
                        echo $t['so_luong_dat'] ?? '0';
                        break;
                    }
                } else {
                    echo '0';
                }
                ?> đơn
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-30">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>📊 Biểu Đồ Doanh Thu 30 Ngày Qua</h4>
                </div>
                <div class="content" style="padding: 20px;">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>🎬 Phân Bổ Phim Theo Trạng Thái</h4>
                </div>
                <div class="content" style="padding: 20px;">
                    <canvas id="movieStatusChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="box">
                <div class="head">
                    <h4>💰 Doanh Thu Theo Loại Combo</h4>
                </div>
                <div class="content" style="padding: 20px;">
                    <canvas id="comboChart" height="100"></canvas>
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
        
        // Initialize Charts
        initRevenueChart();
        initMovieStatusChart();
        initComboChart();
    });

    // Revenue Chart - 30 days
    function initRevenueChart() {
        var ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        
        var labels = [];
        var data = [];
        var today = new Date();
        
        for (var i = 29; i >= 0; i--) {
            var date = new Date(today);
            date.setDate(date.getDate() - i);
            labels.push((date.getDate()) + '/' + (date.getMonth() + 1));
            data.push(Math.floor(Math.random() * 50) + 20);
        }
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh Thu (Triệu VNĐ)',
                    data: data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + 'M';
                            }
                        }
                    }
                }
            }
        });
    }

    // Movie Status Chart
    function initMovieStatusChart() {
        var ctx = document.getElementById('movieStatusChart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Đang Chiếu', 'Sắp Chiếu', 'Kết Thúc'],
                datasets: [{
                    data: [12, 8, 5],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Combo Chart
    function initComboChart() {
        var ctx = document.getElementById('comboChart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Combo A', 'Combo B', 'Combo C', 'Combo D'],
                datasets: [{
                    label: 'Doanh Thu (Triệu VNĐ)',
                    data: [35, 28, 42, 31],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe'
                    ],
                    borderRadius: 4,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + 'M';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
