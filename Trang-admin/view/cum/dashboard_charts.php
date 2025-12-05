<?php
/**
 * Dashboard Charts - Quản Lý Cụm
 * Hiển thị 4 charts với dữ liệu thực từ DB
 */

// Lấy dữ liệu thực từ database với lọc theo rạp và ngày
$topMovies = get_top_movies(5, $from ?? null, $to ?? null, $id_rap ?? null);
$seatOccupancy = get_seat_occupancy($from ?? null, $to ?? null, $id_rap ?? null);

// Lọc dữ liệu doanh thu theo rạp nếu có chọn id_rap
if(!empty($id_rap)) {
    $rev_by_rap_rows = array_filter($rev_by_rap_rows, function($row) use ($id_rap) {
        return isset($row['id_rap']) && (int)$row['id_rap'] === (int)$id_rap;
    });
    $rev_by_rap_rows = array_values($rev_by_rap_rows); // Reindex
}

// Lọc dữ liệu doanh thu theo ngày nếu có chọn khoảng ngày
if(!empty($from) && !empty($to)) {
    $rev_by_date_rows = array_filter($rev_by_date_rows, function($row) use ($from, $to) {
        $rowDate = isset($row[0]) ? strtotime($row[0]) : 0;
        $fromDate = strtotime($from);
        $toDate = strtotime($to . ' 23:59:59');
        return $rowDate >= $fromDate && $rowDate <= $toDate;
    });
    $rev_by_date_rows = array_values($rev_by_date_rows); // Reindex
}
?>

<style>
    .charts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .chart-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-title i {
        color: #667eea;
        font-size: 18px;
    }

    .chart-canvas {
        position: relative;
        height: 300px;
    }

    @media (max-width: 768px) {
        .charts-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="charts-container">
    <!-- Chart 1: Doanh Thu Theo Rạp -->
    <div class="chart-box">
        <div class="chart-title">
            <i class="fas fa-chart-bar"></i>
            📊 Doanh Thu Theo Rạp
        </div>
        <div class="chart-canvas">
            <canvas id="revenueByRapChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Doanh Thu Theo Ngày (30 ngày) -->
    <div class="chart-box">
        <div class="chart-title">
            <i class="fas fa-chart-line"></i>
            📈 Doanh Thu Theo Ngày (30 Ngày)
        </div>
        <div class="chart-canvas">
            <canvas id="revenueByDateChart"></canvas>
        </div>
    </div>

    <!-- Chart 3: Phim Bán Chạy Nhất -->
    <div class="chart-box">
        <div class="chart-title">
            <i class="fas fa-film"></i>
            🎬 Phim Bán Chạy Nhất (Top 5)
        </div>
        <div class="chart-canvas">
            <canvas id="topMoviesChart"></canvas>
        </div>
    </div>

    <!-- Chart 4: Tỉ Lệ Ghế Đã Bán Vs Còn Lại -->
    <div class="chart-box">
        <div class="chart-title">
            <i class="fas fa-pie-chart"></i>
            📊 Tỉ Lệ Ghế Đã Bán
        </div>
        <div class="chart-canvas">
            <canvas id="seatOccupancyChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dữ liệu từ PHP (được chèn vào dưới đây)
        const revenueByRapData = <?php echo json_encode($rev_by_rap_rows ?? []); ?>;
        const revenueByDateData = <?php echo json_encode($rev_by_date_rows ?? []); ?>;
        const topMoviesData = <?php echo json_encode($topMovies ?? []); ?>;
        const seatData = <?php echo json_encode($seatOccupancy ?? []); ?>;
        
        initRevenueByRapChart(revenueByRapData);
        initRevenueByDateChart(revenueByDateData);
        initTopMoviesChart(topMoviesData);
        initSeatOccupancyChart(seatData);
    });

    // Chart 1: Doanh Thu Theo Rạp (Bar Chart)
    function initRevenueByRapChart(data) {
        const ctx = document.getElementById('revenueByRapChart');
        if (!ctx) return;

        const labels = data.map(item => item[0]);
        const revenues = data.map(item => Math.round(item[1] / 1000000)); // Chuyển thành triệu

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh Thu (Triệu VNĐ)',
                    data: revenues,
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe',
                        '#00f2fe',
                        '#43e97b'
                    ],
                    borderRadius: 6,
                    borderWidth: 0,
                    hoverBackgroundColor: '#667eea',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { size: 12 } }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value + 'M'
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Doanh Thu Theo Ngày (Line Chart)
    function initRevenueByDateChart(data) {
        const ctx = document.getElementById('revenueByDateChart');
        if (!ctx) return;

        const labels = data.map(item => {
            const date = new Date(item[0]);
            return date.getDate() + '/' + (date.getMonth() + 1);
        });
        const revenues = data.map(item => Math.round(item[1] / 1000000));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh Thu (Triệu VNĐ)',
                    data: revenues,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#764ba2'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                            callback: value => value + 'M'
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Phim Bán Chạy Nhất (Doughnut Chart)
    function initTopMoviesChart(data) {
        const ctx = document.getElementById('topMoviesChart');
        if (!ctx) return;

        if (!data || data.length === 0) {
            ctx.parentElement.innerHTML = '<div style="padding:40px; text-align:center; color:#999;">Không có dữ liệu</div>';
            return;
        }

        const labels = data.map(m => m.tieu_de || 'N/A');
        const tickets = data.map(m => parseInt(m.ticket_count) || 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: tickets,
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe',
                        '#00f2fe'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11 },
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': ' + ctx.parsed + ' vé'
                        }
                    }
                }
            }
        });
    }

    // Chart 4: Tỉ Lệ Ghế Đã Bán (Pie Chart)
    function initSeatOccupancyChart(data) {
        const ctx = document.getElementById('seatOccupancyChart');
        if (!ctx) return;

        // Lấy từ DB hoặc giả lập
        const totalSeats = data?.total_seats || 2400;
        const soldSeats = data?.sold_seats || 0;
        const availableSeats = Math.max(0, totalSeats - soldSeats);
        const occupancyPercent = totalSeats > 0 ? ((soldSeats / totalSeats) * 100).toFixed(1) : 0;

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Đã Bán (' + occupancyPercent + '%)', 'Còn Trống'],
                datasets: [{
                    data: [soldSeats, availableSeats],
                    backgroundColor: ['#10b981', '#f3f4f6'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 12 },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ctx.label.split('(')[0].trim() + ': ' + ctx.parsed + ' (' + percent + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
</script>

