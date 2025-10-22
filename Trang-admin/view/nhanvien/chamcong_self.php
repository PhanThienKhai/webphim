<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        .checkin-card {
            max-width: 600px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            text-align: center;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }
        .current-time {
            font-size: 48px;
            font-weight: 700;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .current-date {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0;
        }
        .status-not-checked {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.5);
        }
        .status-checked-in {
            background: rgba(16, 185, 129, 0.3);
            border: 2px solid #10b981;
        }
        .status-checked-out {
            background: rgba(239, 68, 68, 0.3);
            border: 2px solid #ef4444;
        }
        .btn-check {
            background: white;
            color: #667eea;
            border: none;
            padding: 16px 48px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin: 10px;
        }
        .btn-check:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .btn-check:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-checkout {
            background: #ef4444;
            color: white;
        }
        .working-hours {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .history-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-row {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            gap: 10px;
        }
        .stat-item {
            flex: 1;
            background: rgba(255,255,255,0.15);
            padding: 15px;
            border-radius: 8px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.8;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-top: 5px;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .pulse {
            animation: pulse 2s infinite;
        }
    </style>

    <div class="page-heading"><h3>⏰ Chấm công của tôi</h3></div>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" style="max-width:600px;margin:0 auto 20px">
            ✓ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="max-width:600px;margin:0 auto 20px">
            ✗ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="checkin-card">
        <div class="current-date" id="currentDate"></div>
        <div class="current-time pulse" id="currentTime"></div>
        
        <?php
        // DEBUG: Show raw status
        if (isset($_GET['debug'])) {
            echo '<div style="background:yellow;color:black;padding:10px;margin:10px 0;font-size:12px">';
            echo '<strong>DEBUG INFO:</strong><br>';
            echo 'Status: ' . ($today_status['status'] ?? 'NULL') . '<br>';
            echo 'Record: ' . (isset($today_status['record']) ? 'EXISTS' : 'NULL') . '<br>';
            echo 'Checkin time: ' . ($today_status['checkin_time'] ?? 'NULL') . '<br>';
            echo 'Checkout time: ' . ($today_status['checkout_time'] ?? 'NULL') . '<br>';
            echo 'Full data: <pre>' . print_r($today_status, true) . '</pre>';
            echo '</div>';
        }
        ?>
        
        <?php 
        $status_text = '';
        $status_class = '';
        $show_checkin = false;
        $show_checkout = false;
        $checkin_time = '';
        $working_duration = '';
        
        if ($today_status['status'] === 'not_checked_in') {
            $status_text = '⚪ Chưa check-in';
            $status_class = 'status-not-checked';
            $show_checkin = true;
        } elseif ($today_status['status'] === 'checked_in') {
            $checkin_time = $today_status['checkin_time'];
            $status_text = '🟢 Đã check-in lúc ' . date('H:i', strtotime($checkin_time));
            $status_class = 'status-checked-in';
            $show_checkout = true;
            
            // Tính thời gian đã làm việc
            $diff = time() - strtotime($checkin_time);
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $working_duration = $hours . 'h ' . $minutes . 'p';
        } else {
            $checkin_time = $today_status['checkin_time'];
            $checkout_time = $today_status['checkout_time'];
            $status_text = '🔴 Đã check-out lúc ' . date('H:i', strtotime($checkout_time));
            $status_class = 'status-checked-out';
            
            $diff = strtotime($checkout_time) - strtotime($checkin_time);
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $working_duration = $hours . 'h ' . $minutes . 'p';
        }
        ?>
        
        <div class="status-badge <?= $status_class ?>">
            <?= $status_text ?>
        </div>
        
        <?php if ($working_duration): ?>
            <div class="working-hours">
                <div style="font-size:14px;opacity:0.8">Thời gian làm việc hôm nay:</div>
                <div style="font-size:32px;font-weight:700;margin-top:10px"><?= $working_duration ?></div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top:30px">
            <?php if ($show_checkin): ?>
                <form method="post" action="index.php?act=nv_chamcong" style="display:inline">
                    <button type="submit" name="action" value="checkin" class="btn-check">
                        🟢 Check-in ngay
                    </button>
                </form>
            <?php endif; ?>
            
            <?php if ($show_checkout): ?>
                <form method="post" action="index.php?act=nv_chamcong" style="display:inline">
                    <button type="submit" name="action" value="checkout" class="btn-check btn-checkout"
                            onclick="return confirm('Xác nhận check-out?')">
                        🔴 Check-out
                    </button>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="stat-row">
            <div class="stat-item">
                <div class="stat-label">Tổng giờ tháng</div>
                <div class="stat-value"><?= number_format($total_hours, 1) ?>h</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Ngày làm việc</div>
                <div class="stat-value"><?= count($history) ?></div>
            </div>
            <?php if (isset($summary)): ?>
                <div class="stat-item">
                    <div class="stat-label">Đi muộn</div>
                    <div class="stat-value"><?= $summary['late_count'] ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="max-width:900px;margin:0 auto">
        <h4 style="margin-bottom:15px">📋 Lịch sử tháng <?= date('m/Y', strtotime($ym . '-01')) ?></h4>
        
        <div class="history-table">
            <table class="table table-bordered" style="margin:0">
                <thead style="background:#f9fafb">
                    <tr>
                        <th>Ngày</th>
                        <th>Thứ</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Tổng giờ</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:30px;color:#6b7280">
                                Chưa có dữ liệu chấm công trong tháng này
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $h): 
                            $ngay = $h['ngay'];
                            $thu = date('l', strtotime($ngay));
                            $thu_viet = [
                                'Monday' => 'Thứ 2',
                                'Tuesday' => 'Thứ 3',
                                'Wednesday' => 'Thứ 4',
                                'Thursday' => 'Thứ 5',
                                'Friday' => 'Thứ 6',
                                'Saturday' => 'Thứ 7',
                                'Sunday' => 'CN'
                            ];
                            
                            $diff = strtotime($h['gio_ra']) - strtotime($h['gio_vao']);
                            $hours = round($diff / 3600, 1);
                            
                            $row_class = '';
                            if ($hours > 12) $row_class = 'style="background:#fee2e2"';
                            elseif ($hours < 1) $row_class = 'style="background:#fef3c7"';
                        ?>
                            <tr <?= $row_class ?>>
                                <td><?= date('d/m/Y', strtotime($ngay)) ?></td>
                                <td><?= $thu_viet[$thu] ?? $thu ?></td>
                                <td><strong><?= date('H:i', strtotime($h['gio_vao'])) ?></strong></td>
                                <td><strong><?= date('H:i', strtotime($h['gio_ra'])) ?></strong></td>
                                <td>
                                    <span style="font-weight:600;color:<?= $hours >= 8 ? '#10b981' : '#f59e0b' ?>">
                                        <?= $hours ?> giờ
                                    </span>
                                </td>
                                <td style="font-size:13px;color:#6b7280"><?= htmlspecialchars($h['ghi_chu'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Real-time clock
function updateTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const dateStr = now.toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    
    document.getElementById('currentTime').textContent = timeStr;
    document.getElementById('currentDate').textContent = dateStr;
}

updateTime();
setInterval(updateTime, 1000);

// Confetti effect on success
<?php if (!empty($success) && strpos($success, 'Check-in') !== false): ?>
    // Simple confetti effect
    setTimeout(() => {
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${['#667eea', '#764ba2', '#10b981', '#f59e0b', '#ef4444'][Math.floor(Math.random() * 5)]};
                top: -10px;
                left: ${Math.random() * 100}vw;
                opacity: ${Math.random()};
                transform: rotate(${Math.random() * 360}deg);
                animation: fall ${2 + Math.random() * 2}s linear;
                pointer-events: none;
                z-index: 9999;
            `;
            document.body.appendChild(confetti);
            setTimeout(() => confetti.remove(), 4000);
        }
    }, 100);
    
    const style = document.createElement('style');
    style.textContent = '@keyframes fall { to { top: 100vh; } }';
    document.head.appendChild(style);
<?php endif; ?>
</script>

<?php include __DIR__ . '/../home/footer.php'; ?>
