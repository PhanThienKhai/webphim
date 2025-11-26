<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20">
        <div class="col-12">
            <div class="page-heading">
                <h3>⏰ Chấm công của tôi</h3>
            </div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="row mb-20">
            <div class="col-12">
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="row mb-20">
            <div class="col-12">
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Check-in/Out Card -->
    <div class="row mb-30">
        <div class="col-12 col-lg-8" style="margin: 0 auto;">
            <div class="card">
                <div class="card-header">
                    <h5>🕐 Chấm công hôm nay</h5>
                </div>
                <div class="card-body">
                    <div style="text-align: center; padding: 30px 0;">
                        <div style="font-size: 48px; font-weight: 700; color: #007bff; font-family: 'Courier New', monospace; margin: 20px 0;" id="currentTime">--:--:--</div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 30px;" id="currentDate">Hôm nay</div>

                        <?php
                        $status_text = 'Chưa chấm công';
                        $status_class = 'alert-warning';
                        $show_checkin = false;
                        $show_checkout = false;
                        $checkin_time = null;
                        $checkout_time = null;
                        $working_hours = null;
                        $break_time = 60; // Default 60 minutes

                        if (!empty($today_status)) {
                            if ($today_status['status'] === 'checked_in') {
                                $checkin_time = date('H:i', strtotime($today_status['checkin_time']));
                                $status_text = "✓ Đã check-in lúc $checkin_time";
                                $status_class = 'alert-success';
                                $show_checkout = true;
                            } elseif ($today_status['status'] === 'checked_out') {
                                $checkin_time = date('H:i', strtotime($today_status['checkin_time']));
                                $checkout_time = date('H:i', strtotime($today_status['checkout_time']));
                                
                                // Calculate working hours (excluding break)
                                $vao = strtotime($today_status['checkin_time']);
                                $ra = strtotime($today_status['checkout_time']);
                                $total_seconds = $ra - $vao;
                                $break_duration = $today_status['break_duration'] ?? 60;
                                $working_seconds = $total_seconds - ($break_duration * 60);
                                $working_hours = round($working_seconds / 3600, 2);
                                
                                $status_text = "✓ Check-in: $checkin_time | Check-out: $checkout_time | Giờ làm: {$working_hours}h";
                                $status_class = 'alert-info';
                            }
                        } else {
                            $show_checkin = true;
                        }
                        ?>

                        <div class="alert <?= $status_class ?>" style="margin: 20px 0; text-align: left;">
                            <?= $status_text ?>
                        </div>

                        <!-- Check-in/Out Buttons -->
                        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 30px;">
                            <?php if ($show_checkin): ?>
                                <button class="button button-primary" onclick="document.getElementById('checkin-modal').style.display='block'">
                                    📍 Check-in
                                </button>
                            <?php endif; ?>

                            <?php if ($show_checkout): ?>
                                <button class="button button-danger" onclick="document.getElementById('checkout-modal').style.display='block'">
                                    ✓ Check-out
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Break Time Info -->
                        <?php if ($checkin_time && !$checkout_time): ?>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                                <strong>⏸️ Thông tin giờ làm:</strong><br>
                                ✓ Check-in: <strong><?= $checkin_time ?></strong><br>
                                🍽️ Giờ nghỉ trưa: <strong>12:00 - 13:00</strong> (60 phút)<br>
                                📊 Công thức: Giờ làm = (Checkout - Checkin) - Giờ nghỉ<br>
                                <small style="color: #666;">Ví dụ: Check-in 8:00 → Check-out 17:00 → Giờ làm = (9h) - 1h = 8h</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row mb-30">
        <div class="col-12 col-md-4 mb-15">
            <div class="card text-center">
                <div class="card-body">
                    <h6 style="color: #666; margin-bottom: 15px;">Công hôm nay</h6>
                    <div style="font-size: 32px; font-weight: 700; color: #007bff;">
                        <?= !empty($today_status) && $today_status['status'] === 'checked_out' ? number_format($working_hours ?? 0, 1) : '—' ?>
                    </div>
                    <small style="color: #999;">giờ</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-15">
            <div class="card text-center">
                <div class="card-body">
                    <h6 style="color: #666; margin-bottom: 15px;">Tổng công (tháng)</h6>
                    <div style="font-size: 32px; font-weight: 700; color: #28a745;">
                        <?= $summary['total_days'] ?? 0 ?>
                    </div>
                    <small style="color: #999;">ngày</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-15">
            <div class="card text-center">
                <div class="card-body">
                    <h6 style="color: #666; margin-bottom: 15px;">Giờ làm (tháng)</h6>
                    <div style="font-size: 32px; font-weight: 700; color: #17a2b8;">
                        <?= round($summary['total_hours'] ?? 0, 1) ?>h
                    </div>
                    <small style="color: #999;">giờ</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (!empty($today_status) && $today_status['status'] === 'checked_in'): ?>
        <?php 
            $checkin_obj = strtotime($today_status['checkin_time']);
            $standard_start = strtotime('08:30'); // Standard start time
            if ($checkin_obj > $standard_start + 300) { // More than 5 minutes late
        ?>
            <div class="row mb-20">
                <div class="col-12">
                    <div class="alert alert-warning">
                        ⚠️ Bạn check-in muộn hơn giờ quy định
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php endif; ?>

    <!-- History Section -->
    <div class="row mb-20">
        <div class="col-12">
            <h5 style="margin-bottom: 20px;">📋 Lịch sử chấm công</h5>

            <div class="row mb-15">
                <div class="col-12 col-md-3">
                    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                        <input type="hidden" name="act" value="nv_chamcong">
                        <label style="margin: 0; white-space: nowrap; font-weight: 600;">Tháng:</label>
                        <input type="month" name="ym" value="<?= htmlspecialchars($ym ?? date('Y-m')) ?>" 
                               class="form-control" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <?php if (!empty($history)): ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Giờ làm</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $record): ?>
                                <?php
                                $checkin = strtotime($record['gio_vao']);
                                $checkout = $record['gio_ra'] ? strtotime($record['gio_ra']) : null;
                                $checkin_time = date('H:i', $checkin);
                                $checkout_time = $checkout ? date('H:i', $checkout) : '—';
                                $date = date('d/m/Y', $checkin);
                                
                                // Calculate working hours
                                if ($checkout) {
                                    $total_seconds = $checkout - $checkin;
                                    $break_duration = $record['break_duration'] ?? 60;
                                    $working_seconds = $total_seconds - ($break_duration * 60);
                                    $hours = round($working_seconds / 3600, 1);
                                } else {
                                    $hours = '—';
                                }
                                
                                // Check if early or late
                                $status = '';
                                $checkin_h = date('H:i', $checkin);
                                if ($checkin_h > '08:30') {
                                    $status = '<span style="color: #dc3545; font-size: 12px;">🔴 Muộn</span>';
                                } elseif ($checkin_h < '08:00') {
                                    $status = '<span style="color: #28a745; font-size: 12px;">🟢 Sớm</span>';
                                }
                                ?>
                                <tr>
                                    <td><?= $date ?> <?= $status ?></td>
                                    <td><?= $checkin_time ?></td>
                                    <td><?= $checkout_time ?></td>
                                    <td><?= $hours ?> h</td>
                                    <td style="font-size: 12px; color: #666;">
                                        <?= !empty($record['ghi_chu']) ? htmlspecialchars($record['ghi_chu']) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        📭 Không có dữ liệu chấm công trong tháng này
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Check-in Modal -->
<div id="checkin-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); padding-top:50px;">
    <div style="background-color:#fefefe; margin:auto; padding:20px; border:1px solid #888; max-width:500px; border-radius:8px;">
        <span style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;" onclick="document.getElementById('checkin-modal').style.display='none';">&times;</span>
        <h4>Check-in</h4>
        <form method="POST">
            <input type="hidden" name="action" value="checkin">
            
            <div class="form-group">
                <label>Ghi chú (tùy chọn)</label>
                <input type="text" name="ghi_chu" class="form-control" placeholder="Ví dụ: Tập gym trước ca...">
            </div>

            <div class="form-group">
                <label>Xác thực</label>
                <div style="margin-top: 10px;">
                    <label style="margin-right: 20px;">
                        <input type="radio" name="auth_method" value="manual" checked> 📱 Thủ công
                    </label>
                    <label style="margin-right: 20px;">
                        <input type="radio" name="auth_method" value="qr"> 📸 QR Code
                    </label>
                    <label>
                        <input type="radio" name="auth_method" value="pin"> 🔐 PIN
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" class="button button-primary" style="flex:1;">✓ Check-in</button>
                <button type="button" class="button" style="flex:1;" onclick="document.getElementById('checkin-modal').style.display='none';">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Check-out Modal -->
<div id="checkout-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); padding-top:50px;">
    <div style="background-color:#fefefe; margin:auto; padding:20px; border:1px solid #888; max-width:500px; border-radius:8px;">
        <span style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;" onclick="document.getElementById('checkout-modal').style.display='none';">&times;</span>
        <h4>Check-out</h4>
        
        <div id="checkout-warning" style="display:none; background:#fff3cd; color:#856404; padding:10px; border-radius:5px; margin-bottom:15px; border:1px solid #ffeaa7;">
            ⚠️ Bạn chưa check-in hôm nay
        </div>

        <form method="POST" id="checkout-form">
            <input type="hidden" name="action" value="checkout">
            
            <div class="form-group">
                <label>Giờ nghỉ trưa (phút)</label>
                <input type="number" name="break_duration" class="form-control" value="60" min="0" max="180">
                <small style="color:#666;">
                    📊 <strong>Công thức tính giờ làm:</strong><br>
                    Giờ làm = (Checkout - Checkin) - Giờ nghỉ<br>
                    Ví dụ: (17:00 - 08:00) - 60 phút = 8 giờ
                </small>
            </div>

            <div class="form-group">
                <label>Ghi chú (tùy chọn)</label>
                <input type="text" name="ghi_chu_ra" class="form-control" placeholder="Ví dụ: Có việc riêng...">
            </div>

            <div class="form-group">
                <label>Xác thực</label>
                <div style="margin-top: 10px;">
                    <label style="margin-right: 20px;">
                        <input type="radio" name="auth_method" value="manual" checked> 📱 Thủ công
                    </label>
                    <label style="margin-right: 20px;">
                        <input type="radio" name="auth_method" value="qr"> 📸 QR Code
                    </label>
                    <label>
                        <input type="radio" name="auth_method" value="pin"> 🔐 PIN
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" class="button button-danger" style="flex:1;">✓ Check-out</button>
                <button type="button" class="button" style="flex:1;" onclick="document.getElementById('checkout-modal').style.display='none';">Hủy</button>
            </div>
        </form>

    </div>
</div>

<script>
// Update time
function updateTime() {
    const now = new Date();
    const time = String(now.getHours()).padStart(2, '0') + ':' +
                 String(now.getMinutes()).padStart(2, '0') + ':' +
                 String(now.getSeconds()).padStart(2, '0');
    const date = now.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    document.getElementById('currentTime').textContent = time;
    document.getElementById('currentDate').textContent = date;
}

updateTime();
setInterval(updateTime, 1000);

// Close modal when clicking outside
window.onclick = function(event) {
    const checkinModal = document.getElementById('checkin-modal');
    const checkoutModal = document.getElementById('checkout-modal');
    if (event.target === checkinModal) checkinModal.style.display = 'none';
    if (event.target === checkoutModal) checkoutModal.style.display = 'none';
}

// Sidebar handlers
function attachSidebarCloseHandler() {
    const closeBtn = document.querySelector('.side-header-close');
    const sideHeader = document.querySelector('.side-header');
    
    if (closeBtn && sideHeader) {
        const newCloseBtn = closeBtn.cloneNode(true);
        closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);
        
        newCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sideHeader.classList.remove('show');
            sideHeader.classList.add('hide');
        }, true);
        
        return true;
    }
    return false;
}

function attachSidebarToggleHandler() {
    const toggleBtn = document.querySelector('.side-header-toggle');
    const sideHeader = document.querySelector('.side-header');
    
    if (toggleBtn && sideHeader) {
        const newToggleBtn = toggleBtn.cloneNode(true);
        toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
        
        newToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (sideHeader.classList.contains('show')) {
                sideHeader.classList.remove('show');
                sideHeader.classList.add('hide');
            } else {
                sideHeader.classList.remove('hide');
                sideHeader.classList.add('show');
            }
        }, true);
        
        return true;
    }
    return false;
}

if (!attachSidebarCloseHandler()) {
    document.addEventListener('DOMContentLoaded', attachSidebarCloseHandler);
    setTimeout(attachSidebarCloseHandler, 500);
}

if (!attachSidebarToggleHandler()) {
    document.addEventListener('DOMContentLoaded', attachSidebarToggleHandler);
    setTimeout(attachSidebarToggleHandler, 500);
}

let retries = 0;
const retryInterval = setInterval(() => {
    if (attachSidebarCloseHandler() && attachSidebarToggleHandler() || retries++ > 5) {
        clearInterval(retryInterval);
    }
}, 1000);
</script>

