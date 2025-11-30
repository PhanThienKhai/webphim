<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        :root {
            --primary-color: #7b92a8ff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }

        .page-header-professional {
            background: linear-gradient(135deg, var(--primary-color) 0%, #8aa2baff 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.15);
        }

        .page-header-professional h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header-professional .header-subtitle {
            font-size: 14px;
            opacity: 0.95;
            margin: 0;
        }

        .status-card-main {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .status-display {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0px;
            padding: 11px;
            align-items: center;
        }

        @media (max-width: 768px) {
            .status-display {
                grid-template-columns: 1fr;
            }
        }

        .time-display {
            text-align: center;
        }

        .time-display .clock {
            font-size: 56px;
            font-weight: 700;
            color: var(--primary-color);
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            line-height: 1;
        }

        .time-display .date {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .status-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .status-item .icon {
            font-size: 24px;
            min-width: 30px;
        }

        .status-item .content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .status-item .label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-item .value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .status-item.checked-in .value {
            color: var(--success-color);
        }

        .status-item.checked-out .value {
            color: var(--info-color);
        }

        .status-item.pending .value {
            color: var(--warning-color);
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .button-group .button {
            flex: 1;
            min-width: 150px;
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .button-group .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .button-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0052a3 100%);
            color: white;
        }

        .button-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);
            color: white;
        }

        .button-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            color: #333;
        }

        .card-modern {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .card-modern .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 20px;
        }

        .card-modern .card-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .card-modern .card-body {
            padding: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .stat-card {
            background: linear-gradient(135deg, #fff 0%, var(--light-bg) 100%);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-card.success .stat-value {
            color: var(--success-color);
        }

        .stat-card.info .stat-value {
            color: var(--info-color);
        }

        .stat-card.warning .stat-value {
            color: var(--warning-color);
        }
    </style>

    <!-- Professional Header -->
    <div class="page-header-professional">
        <h1>Chấm Công Nhân Viên</h1>
        <p class="header-subtitle">Quản lý thời gian làm việc và theo dõi công việc hàng ngày</p>
    </div>

    <?php if (!empty($error)): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <!-- Main Status Card -->
    <div class="status-card-main">
        <div class="status-display">
            <!-- Time Clock Section -->
            <div class="time-display">
                <div class="clock" id="currentTime">--:--:--</div>
                <div class="date" id="currentDate">Hôm nay</div>
            </div>

            <!-- Status Info Section -->
            <div class="status-info">
                <?php
                $status_text = 'Chưa chấm công';
                $status_class = 'pending';
                $show_checkin = false;
                $show_checkout = false;
                $checkin_time = null;
                $checkout_time = null;
                $working_hours = null;
                $break_time = 60;

                if (!empty($today_status)) {
                    if ($today_status['status'] === 'checked_in') {
                        $checkin_time = date('H:i', strtotime($today_status['checkin_time']));
                        $status_text = "Đã check-in lúc $checkin_time";
                        $status_class = 'checked-in';
                        $show_checkout = true;
                    } elseif ($today_status['status'] === 'checked_out') {
                        $checkin_time = date('H:i', strtotime($today_status['checkin_time']));
                        $checkout_time = date('H:i', strtotime($today_status['checkout_time']));
                        
                        $vao = strtotime($today_status['checkin_time']);
                        $ra = strtotime($today_status['checkout_time']);
                        $total_seconds = $ra - $vao;
                        
                        $break_duration = 0;
                        if ($total_seconds >= 4 * 3600) {
                            $break_duration = $today_status['break_duration'] ?? 60;
                        }
                        
                        $working_seconds = $total_seconds - ($break_duration * 60);
                        $working_seconds = max(0, $working_seconds);
                        $working_hours = round($working_seconds / 3600, 2);
                        
                        $status_text = "Đã hoàn tất";
                        $status_class = 'checked-out';
                    }
                } else {
                    $show_checkin = true;
                }
                ?>

                <!-- Status Item 1: Check-in -->
                <div class="status-item <?= $status_class ?>">
                    <div class="icon">📍</div>
                    <div class="content">
                        <div class="label">Check-in</div>
                        <div class="value"><?= $checkin_time ?? '—' ?></div>
                    </div>
                </div>

                <!-- Status Item 2: Check-out -->
                <div class="status-item <?= $status_class ?>">
                    <div class="icon">✓</div>
                    <div class="content">
                        <div class="label">Check-out</div>
                        <div class="value"><?= $checkout_time ?? '—' ?></div>
                    </div>
                </div>

                <!-- Status Item 3: Working Hours -->
                <div class="status-item <?= $status_class ?>">
                    <div class="icon">⏱️</div>
                    <div class="content">
                        <div class="label">Giờ làm</div>
                        <div class="value"><?= $working_hours !== null ? number_format($working_hours, 2) . 'h' : '—' ?></div>
                    </div>
                </div>

                <!-- Status Item 4: Main Status -->
                <div class="status-item">
                    <div class="icon">📊</div>
                    <div class="content">
                        <div class="label">Trạng thái</div>
                        <div class="value" style="font-size: 14px;"><?= $status_text ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons - Quick One-Click -->
        <div style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); padding: 10px 14px; border-top: 2px solid var(--border-color);">
            <div class="button-group" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
                <?php if ($show_checkin): ?>
                    <button class="button button-success" onclick="quickCheckIn()" style="padding: 16px 50px; font-size: 18px; font-weight: 700; border-radius: 10px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3); flex: 1; min-width: 250px;">
                        ✅ CHECK-IN NGAY
                    </button>
                <?php endif; ?>

                <?php if ($show_checkout): ?>
                    <button class="button button-warning" onclick="quickCheckOut()" style="padding: 16px 50px; font-size: 18px; font-weight: 700; border-radius: 10px; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3); flex: 1; min-width: 250px; color: #333;">
                        🔙 CHECK-OUT NGAY
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Quick Info Help Text -->
            <div style="text-align: center; margin-top: 15px; font-size: 12px; color: #999;">
                💡 Chạm nút trên để chấm công ngay (không cần điền ghi chú)
            </div>
        </div>
    </div>
    <div class="card-modern">
        <div class="card-header">
            <h5>Chấm Công Bằng Khuôn Mặt</h5>
        </div>
        <div class="card-body">
                    <!-- Face Tabs with Professional Styling -->
                    <div style="display: flex; gap: 5px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); flex-wrap: wrap;">
                        <button class="face-tab-btn active" onclick="switchFaceTab('photo')" style="padding: 12px 24px; border: none; border-bottom: 3px solid var(--primary-color); background: none; color: var(--primary-color); cursor: pointer; font-weight: 600; font-size: 15px;">📸 Chụp Ảnh Khuôn Mặt</button>
                        <button class="face-tab-btn" onclick="switchFaceTab('quick')" style="padding: 12px 24px; border: none; border-bottom: 3px solid transparent; background: none; color: #999; cursor: pointer; font-weight: 600; font-size: 15px;">⌨️ Quét Nhanh</button>
                    </div>

                    <!-- Tab 1: Photo with Face Detection -->
                    <div id="face-photo" class="face-tab-content">
                        <div style="background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); border-radius: 12px; padding: 20px; margin-bottom: 20px; text-align: center;">
                            <video id="face-video" autoplay playsinline style="width: 100%; max-width: 500px; max-height: 400px; border-radius: 10px; background: #000; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></video>
                            <canvas id="face-canvas" style="display: none;"></canvas>
                            <div id="face-placeholder" style="width: 100%; max-width: 500px; height: 350px; background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #999; margin: 0 auto; font-size: 16px; text-align: center; flex-direction: column; gap: 10px;">
                                <div style="font-size: 48px;"></div>
                                <div>Camera chưa được kích hoạt</div>
                            </div>
                            <div style="text-align: center; margin-top: 15px; font-weight: 600; font-size: 14px;">
                                <span id="face-detection-status" style="color: #dc3545;">Không phát hiện khuôn mặt</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 15px;">
                            <button onclick="startFaceCamera()" class="button button-primary" style="padding: 12px 24px; font-weight: 600;">Bắt Đầu Quay</button>
                            <button onclick="takeFaceSnapshot('checkin')" class="button button-success" style="padding: 12px 24px; font-weight: 600;">Chấm Công Vào</button>
                            <button onclick="takeFaceSnapshot('checkout')" class="button button-warning" style="padding: 12px 24px; font-weight: 600; color: #333;">Chấm Công Ra</button>
                            <button onclick="stopFaceCamera()" class="button" style="background: #6c757d; color: white; padding: 12px 24px; font-weight: 600; border: none; border-radius: 8px; cursor: pointer;">Dừng Quay</button>
                        </div>
                        <div id="face-photo-status" style="margin-top: 15px; padding: 12px; border-radius: 8px; text-align: center; font-weight: 600; display: none;"></div>
                    </div>

                    <!-- Tab 2: Quick Check-in/out -->
                    <div id="face-quick" class="face-tab-content" style="display: none;">
                        <div style="text-align: center; padding: 40px 20px; background: var(--light-bg); border-radius: 10px;">
                            <p style="font-size: 16px; color: #666; margin-bottom: 30px; font-weight: 500;">Sử dụng nút bên dưới để chấm công nhanh (không cần ảnh)</p>
                            <div style="display: flex; gap: 15px; flex-direction: column; align-items: center; max-width: 400px; margin: 0 auto;">
                                <button onclick="quickFaceCheckin()" class="button button-success" style="padding: 16px 40px; font-size: 16px; font-weight: 700; width: 100%; border-radius: 10px;">
                                    CHẤM CÔNG VÀO
                                </button>
                                <button onclick="quickFaceCheckout()" class="button button-warning" style="padding: 16px 40px; font-size: 16px; font-weight: 700; width: 100%; border-radius: 10px; color: #333;">
                                    CHẤM CÔNG RA
                                </button>
                            </div>
                        </div>
                        <div id="face-quick-status" style="margin-top: 15px; padding: 12px; border-radius: 8px; text-align: center; font-weight: 600; display: none;"></div>
                    </div>

                    <!-- Face Photos Gallery -->
                    <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid var(--border-color);">
                        <h6 style="margin-bottom: 15px; font-weight: 700; color: #333; font-size: 15px;">Ảnh Khuôn Mặt Chấm Công Hôm Nay</h6>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                            <?php
                            // Display check-in face photo
                            if (!empty($today_status['record']['anh_vao'])):
                                $anh_vao = htmlspecialchars($today_status['record']['anh_vao']);
                            ?>
                                <div style="text-align: center; background: var(--light-bg); padding: 12px; border-radius: 10px; border: 1px solid var(--border-color); overflow: hidden;">
                                    <img src="<?= $anh_vao ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px; cursor: pointer; transition: transform 0.3s;" 
                                         onmouseover="this.style.transform='scale(1.05)'" 
                                         onmouseout="this.style.transform='scale(1)'"
                                         onclick="showImageModal('<?= $anh_vao ?>', 'Check-in: <?= date('H:i', strtotime($today_status['record']['gio_vao'])) ?>')">
                                    <small style="display: block; margin-top: 10px; color: #28a745; font-weight: 600;">
                                        Vào: <?= date('H:i', strtotime($today_status['record']['gio_vao'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Display check-out face photo
                            if (!empty($today_status['record']['anh_ra'])):
                                $anh_ra = htmlspecialchars($today_status['record']['anh_ra']);
                            ?>
                                <div style="text-align: center; background: var(--light-bg); padding: 12px; border-radius: 10px; border: 1px solid var(--border-color); overflow: hidden;">
                                    <img src="<?= $anh_ra ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px; cursor: pointer; transition: transform 0.3s;" 
                                         onmouseover="this.style.transform='scale(1.05)'" 
                                         onmouseout="this.style.transform='scale(1)'"
                                         onclick="showImageModal('<?= $anh_ra ?>', 'Check-out: <?= date('H:i', strtotime($today_status['record']['gio_ra'])) ?>')">
                                    <small style="display: block; margin-top: 10px; color: #ffc107; font-weight: 600;">
                                        Ra: <?= date('H:i', strtotime($today_status['record']['gio_ra'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Show message if no face photos yet
                            if (empty($today_status['record']['anh_vao']) && empty($today_status['record']['anh_ra'])):
                            ?>
                                <p style="color: #999; text-align: center; grid-column: 1/-1; font-style: italic;">
                                    Chưa có ảnh khuôn mặt. Sử dụng tab "Chụp Ảnh Khuôn Mặt" để chấm công.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
        </div>
    </div>
    

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-label">Công Hôm Nay</div>
            <div class="stat-value">
                <?= !empty($today_status) && $today_status['status'] === 'checked_out' ? number_format($working_hours ?? 0, 1) : '—' ?>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">📊</div>
            <div class="stat-label">Tổng Công (Tháng)</div>
            <div class="stat-value"><?= $summary['total_days'] ?? 0 ?></div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">⏱️</div>
            <div class="stat-label">Giờ Làm (Tháng)</div>
            <div class="stat-value"><?= round($summary['total_hours'] ?? 0, 1) ?>h</div>
        </div>
    </div>

    <!-- Face Recognition Card -->
    

    <!-- Image Modal -->
    <div id="imageModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); text-align: center; padding-top: 50px;">
        <img id="modalImage" src="" style="max-width: 80%; max-height: 80%; border-radius: 10px;">
        <p id="modalCaption" style="color: white; margin-top: 20px;"></p>
        <button onclick="document.getElementById('imageModal').style.display='none'" style="margin-top: 20px; padding: 10px 20px; background: #fff; border: none; border-radius: 5px; cursor: pointer;">Đóng</button>
    </div>

    <script>
    function showImageModal(src, caption) {
        document.getElementById('modalImage').src = src;
        document.getElementById('modalCaption').textContent = caption;
        document.getElementById('imageModal').style.display = 'block';
    }

    // Function to play success sound
    function playSuccessSound() {
        // Use Web Speech API to speak "xin cảm ơn"
        if ('speechSynthesis' in window) {
            // Cancel any ongoing speech
            speechSynthesis.cancel();
            
            const utterance = new SpeechSynthesisUtterance('xin cảm ơn');
            utterance.lang = 'vi-VN'; // Vietnamese
            utterance.rate = 1; // Normal speed
            utterance.pitch = 1;
            utterance.volume = 1;
            
            speechSynthesis.speak(utterance);
        }
    }
    </script>

    <!-- Alerts and Warnings -->

    <!-- History Section - Professional Card -->
    <div class="card-modern">
        <div class="card-header">
            <h5>Lịch Sử Chấm Công</h5>
        </div>
        <div class="card-body">
            <!-- Month Filter -->
            <form method="GET" style="display: flex; gap: 15px; align-items: center; margin-bottom: 25px; flex-wrap: wrap;">
                <input type="hidden" name="act" value="nv_chamcong">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <label style="margin: 0; font-weight: 600; color: #333;">Chọn tháng:</label>
                    <input type="month" name="ym" value="<?= htmlspecialchars($ym ?? date('Y-m')) ?>" 
                           style="padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; font-weight: 500;" 
                           onchange="this.form.submit()">
                </div>
            </form>

            <!-- History Table -->
            <div class="table-responsive">
                <?php if (!empty($history)): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid var(--border-color);">
                                <th style="padding: 15px; text-align: left; font-weight: 700; color: #333; font-size: 14px;">📅 Ngày</th>
                                <th style="padding: 15px; text-align: center; font-weight: 700; color: #333; font-size: 14px;">📍 Check-in</th>
                                <th style="padding: 15px; text-align: center; font-weight: 700; color: #333; font-size: 14px;">✓ Check-out</th>
                                <th style="padding: 15px; text-align: center; font-weight: 700; color: #333; font-size: 14px;">⏱️ Giờ Làm</th>
                                <th style="padding: 15px; text-align: center; font-weight: 700; color: #333; font-size: 14px;">📍 Vị Trí GPS</th>
                                <th style="padding: 15px; text-align: left; font-weight: 700; color: #333; font-size: 14px;">📝 Ghi Chú</th>
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
                                    $break_duration = 0;
                                    if ($total_seconds >= 4 * 3600) {
                                        $break_duration = $record['break_duration'] ?? 60;
                                    }
                                    $working_seconds = $total_seconds - ($break_duration * 60);
                                    $working_seconds = max(0, $working_seconds);
                                    $hours = round($working_seconds / 3600, 1);
                                } else {
                                    $hours = '—';
                                }
                                
                                // Check if early or late
                                $status = '';
                                $status_color = '#999';
                                $checkin_h = date('H:i', $checkin);
                                if ($checkin_h > '08:30') {
                                    $status = 'Muộn';
                                    $status_color = '#dc3545';
                                } elseif ($checkin_h < '08:00') {
                                    $status = 'Sớm';
                                    $status_color = '#28a745';
                                } else {
                                    $status = 'Đúng giờ';
                                    $status_color = '#17a2b8';
                                }
                                ?>
                                <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                                    <td style="padding: 15px; color: #333; font-weight: 500;">
                                        <?= $date ?>
                                        <br><span style="color: <?= $status_color ?>; font-size: 12px; font-weight: 600;"><?= $status ?></span>
                                    </td>
                                    <td style="padding: 15px; text-align: center; color: var(--success-color); font-weight: 600; font-size: 15px;"><?= $checkin_time ?></td>
                                    <td style="padding: 15px; text-align: center; color: var(--warning-color); font-weight: 600; font-size: 15px;"><?= $checkout_time ?></td>
                                    <td style="padding: 15px; text-align: center; font-weight: 700; font-size: 15px; color: var(--info-color);"><?= $hours ?>h</td>
                                    <td style="padding: 15px; text-align: center; font-size: 12px;">
                                        <?php if (!empty($record['latitude']) && !empty($record['longitude'])): ?>
                                            <span style="color: #0066cc; cursor: pointer; text-decoration: underline;" 
                                                  title="Độ chính xác: ±<?= round($record['location_accuracy'] ?? 0) ?>m"
                                                  onclick="openMapLocation(<?= $record['latitude'] ?>, <?= $record['longitude'] ?>)">
                                                📍 <?= round($record['latitude'], 4) ?><br><?= round($record['longitude'], 4) ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #ddd;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 15px; color: #666; font-size: 13px;">
                                        <?= !empty($record['ghi_chu']) ? htmlspecialchars($record['ghi_chu']) : '<span style="color: #ddd;">—</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; background: var(--light-bg); border-radius: 10px; color: #999;">
                        <div style="font-size: 48px; margin-bottom: 15px;"></div>
                        <div style="font-size: 16px; font-weight: 500;">Không có dữ liệu chấm công trong tháng này</div>
                        <div style="font-size: 13px; margin-top: 8px; color: #ccc;">Hãy chấm công để xem lịch sử</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button for Quick Check-In/Out (Always Visible) -->
<?php if ($show_checkin || $show_checkout): ?>
<style>
    .fab-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9000;
        display: flex;
        gap: 10px;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .fab-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        cursor: pointer;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .fab-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    }
    
    .fab-button:active {
        transform: scale(0.95);
    }
    
    .fab-checkin {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
    }
    
    .fab-checkout {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #333;
    }
    
    .fab-label {
        background: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-right: 10px;
    }
    
    @media (max-width: 768px) {
        .fab-container {
            bottom: 10px;
            right: 10px;
        }
        
        .fab-button {
            width: 56px;
            height: 56px;
            font-size: 24px;
        }
        
        .fab-label {
            display: none;
        }
    }
</style>

<div class="fab-container">
    <?php if ($show_checkout): ?>
        <div style="display: flex; align-items: center;">
            <span class="fab-label">CHECK-OUT</span>
            <button class="fab-button fab-checkout" onclick="quickCheckOut()" title="Check-out ngay">🔙</button>
        </div>
    <?php endif; ?>
    
    <?php if ($show_checkin): ?>
        <div style="display: flex; align-items: center;">
            <span class="fab-label">CHECK-IN</span>
            <button class="fab-button fab-checkin" onclick="quickCheckIn()" title="Check-in ngay">✅</button>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Modern Check-in Modal -->
<div id="checkin-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); padding-top:50px; padding-bottom: 50px; overflow-y: auto;">
    <div style="background-color:white; margin:auto; padding:30px; border-radius:12px; max-width:500px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); border: 1px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h4 style="margin: 0; font-size: 22px; font-weight: 700; color: #333;">✅ CHECK-IN VÀO</h4>
            <span style="color:#ccc; font-size:32px; font-weight:bold; cursor:pointer; line-height: 1;" onclick="document.getElementById('checkin-modal').style.display='none';">&times;</span>
        </div>
        <form method="POST" style="border-top: 1px solid var(--border-color); padding-top: 20px;">
            <input type="hidden" name="action" value="checkin">
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px;">📝 Ghi chú (tùy chọn)</label>
                <input type="text" name="ghi_chu" class="form-control" placeholder="Ví dụ: Tập gym trước ca..." style="padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 12px; font-size: 14px;">🔐 Xác thực</label>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px; hover: background: var(--light-bg);">
                        <input type="radio" name="auth_method" value="manual" checked> <span>📱 Thủ công</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px;">
                        <input type="radio" name="auth_method" value="qr"> <span>📸 QR Code</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px;">
                        <input type="radio" name="auth_method" value="pin"> <span>🔐 PIN</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="submit" class="button button-success" style="flex:1; padding: 12px 20px; font-weight: 600;">✓ Check-in Vào</button>
                <button type="button" style="flex:1; padding: 12px 20px; background: #e9ecef; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #333;" onclick="document.getElementById('checkin-modal').style.display='none';">Hủy Bỏ</button>
            </div>
        </form>
    </div>
</div>

<!-- Modern Check-out Modal -->
<div id="checkout-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); padding-top:50px; padding-bottom: 50px; overflow-y: auto;">
    <div style="background-color:white; margin:auto; padding:30px; border-radius:12px; max-width:500px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); border: 1px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h4 style="margin: 0; font-size: 22px; font-weight: 700; color: #333;">CHECK-OUT RA</h4>
            <span style="color:#ccc; font-size:32px; font-weight:bold; cursor:pointer; line-height: 1;" onclick="document.getElementById('checkout-modal').style.display='none';">&times;</span>
        </div>
        
        <div id="checkout-warning" style="display:none; background: linear-gradient(135deg, #fff3cd 0%, #fffaeb 100%); color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeaa7; font-weight: 500;">
            ⚠️ Bạn chưa check-in hôm nay
        </div>

        <form method="POST" id="checkout-form" style="border-top: 1px solid var(--border-color); padding-top: 20px;">
            <input type="hidden" name="action" value="checkout">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px;">Giờ Nghỉ Trưa (phút)</label>
                <input type="number" name="break_duration" class="form-control" value="60" min="0" max="180" style="padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">
                <small style="color:#999; font-size: 13px; margin-top: 8px; display: block;">
                    📊 <strong>Công thức tính giờ làm:</strong><br>
                    Giờ làm = (Checkout - Checkin) - Giờ nghỉ<br>
                    Ví dụ: (17:00 - 08:00) - 60 phút = 8 giờ
                </small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px;">Ghi Chú (tùy chọn)</label>
                <input type="text" name="ghi_chu_ra" class="form-control" placeholder="Ví dụ: Có việc riêng..." style="padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 12px; font-size: 14px;">Xác Thực</label>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px;">
                        <input type="radio" name="auth_method" value="manual" checked> <span>Thủ công</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px;">
                        <input type="radio" name="auth_method" value="qr"> <span>QR Code</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px;">
                        <input type="radio" name="auth_method" value="pin"> <span>PIN</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="submit" class="button button-warning" style="flex:1; padding: 12px 20px; font-weight: 600; color: #333;">✓ Check-out Ra</button>
                <button type="button" style="flex:1; padding: 12px 20px; background: #e9ecef; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #333;" onclick="document.getElementById('checkout-modal').style.display='none';">Hủy Bỏ</button>
            </div>
        </form>
    </div>
</div>

<script>
// Extract user ID from PHP
const USER_ID = <?php echo isset($_SESSION['user1']['id']) ? $_SESSION['user1']['id'] : 'null'; ?>;

// Face detection variables
let faceStream = null;
let faceDetectionRunning = false;
let checkInFaceFingerprint = null; // Lưu fingerprint check-in để so sánh với check-out

// Khôi phục fingerprint từ sessionStorage khi page load
window.addEventListener('DOMContentLoaded', function() {
    const stored = sessionStorage.getItem('checkInFaceFingerprint');
    if (stored) {
        try {
            checkInFaceFingerprint = JSON.parse(stored);
        } catch (e) {
            console.warn('Không thể parse fingerprint từ sessionStorage');
        }
    }
});

// ========================================
// QUICK CHECK-IN/OUT FUNCTIONS (One-Click)
// ========================================

// Get current GPS location
function getGPSLocation() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) {
            console.warn('Geolocation không được hỗ trợ');
            resolve({latitude: null, longitude: null, accuracy: null});
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
                });
            },
            (error) => {
                console.warn('Không thể lấy GPS:', error);
                resolve({latitude: null, longitude: null, accuracy: null});
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-weight: 600;
        font-size: 15px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    notification.innerHTML = message;
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Quick Check-In (1 click - no modal)
async function quickCheckIn() {
    if (!confirm('✅ Xác nhận CHECK-IN vào lúc ' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}))) {
        return;
    }

    // Show loading
    showNotification('📍 Đang lấy vị trí GPS...', 'success');
    
    // Get GPS location
    const gps = await getGPSLocation();

    const formData = new FormData();
    formData.append('action', 'checkin');
    formData.append('latitude', gps.latitude);
    formData.append('longitude', gps.longitude);
    formData.append('location_accuracy', gps.accuracy);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        playSuccessSound();
        const gpsTxt = gps.latitude ? `📍 Vị trí: ${gps.latitude.toFixed(6)}, ${gps.longitude.toFixed(6)}` : '📍 (Không lấy được GPS)';
        showNotification('✅ Check-in thành công! ' + gpsTxt, 'success');
        setTimeout(() => window.location.reload(), 1500);
    })
    .catch(error => {
        showNotification('❌ Lỗi: ' + error.message, 'error');
    });
}

// Quick Check-Out (1 click - no modal)
async function quickCheckOut() {
    if (!confirm('🔙 Xác nhận CHECK-OUT vào lúc ' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}))) {
        return;
    }

    // Show loading
    showNotification('📍 Đang lấy vị trí GPS...', 'success');
    
    // Get GPS location
    const gps = await getGPSLocation();

    const formData = new FormData();
    formData.append('action', 'checkout');
    formData.append('latitude', gps.latitude);
    formData.append('longitude', gps.longitude);
    formData.append('location_accuracy', gps.accuracy);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        playSuccessSound();
        const gpsTxt = gps.latitude ? `📍 Vị trí: ${gps.latitude.toFixed(6)}, ${gps.longitude.toFixed(6)}` : '📍 (Không lấy được GPS)';
        showNotification('🔙 Check-out thành công! ' + gpsTxt, 'success');
        setTimeout(() => window.location.reload(), 1500);
    })
    .catch(error => {
        showNotification('❌ Lỗi: ' + error.message, 'error');
    });
}

// Add CSS animation keyframes
const style = document.createElement('style');
style.innerHTML = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Function to open map location
function openMapLocation(lat, lng) {
    const url = `https://maps.google.com/?q=${lat},${lng}&z=17`;
    window.open(url, '_blank');
}

// Switch between face tabs
function switchFaceTab(tabName) {
    document.querySelectorAll('.face-tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.face-tab-btn').forEach(b => {
        b.style.borderBottomColor = 'transparent';
        b.style.color = '#666';
    });
    
    document.getElementById('face-' + tabName).style.display = 'block';
    event.target.style.borderBottomColor = '#007bff';
    event.target.style.color = '#007bff';
}

// Start face camera
async function startFaceCamera() {
    if (faceStream) return;
    
    const video = document.getElementById('face-video');
    const placeholder = document.getElementById('face-placeholder');
    
    try {
        faceStream = await navigator.mediaDevices.getUserMedia({
            video: { 
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        });
        
        video.srcObject = faceStream;
        video.style.display = 'block';
        placeholder.style.display = 'none';
        
        // Start face detection loop
        detectAndCaptureFace();
        
        showFaceStatus('Camera bật thành công', '#28a745', 'face-photo-status');
    } catch (err) {
        showFaceStatus('Lỗi: ' + err.message, '#dc3545', 'face-photo-status');
        console.error('Camera error:', err);
    }
}

// Stop face camera
function stopFaceCamera() {
    if (faceStream) {
        faceStream.getTracks().forEach(track => track.stop());
        faceStream = null;
        faceDetectionRunning = false;
    }
    
    const video = document.getElementById('face-video');
    const placeholder = document.getElementById('face-placeholder');
    video.style.display = 'none';
    placeholder.style.display = 'flex';
    
    document.getElementById('face-detection-status').textContent = 'Không phát hiện khuôn mặt';
    document.getElementById('face-detection-status').style.color = '#dc3545';
}

// Tính toán fingerprint khuôn mặt (histogram của pixel)
function calculateFaceFingerprint(canvas) {
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    
    // Chia ảnh thành 16 vùng (4x4 grid)
    const gridSize = 4;
    const cellWidth = canvas.width / gridSize;
    const cellHeight = canvas.height / gridSize;
    const fingerprint = [];
    
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            let totalBrightness = 0;
            let pixelCount = 0;
            
            const startX = Math.floor(col * cellWidth);
            const endX = Math.floor((col + 1) * cellWidth);
            const startY = Math.floor(row * cellHeight);
            const endY = Math.floor((row + 1) * cellHeight);
            
            for (let y = startY; y < endY; y++) {
                for (let x = startX; x < endX; x++) {
                    const idx = (y * canvas.width + x) * 4;
                    const r = data[idx];
                    const g = data[idx + 1];
                    const b = data[idx + 2];
                    const brightness = (r + g + b) / 3;
                    totalBrightness += brightness;
                    pixelCount++;
                }
            }
            
            const avgBrightness = pixelCount > 0 ? totalBrightness / pixelCount : 0;
            fingerprint.push(Math.round(avgBrightness));
        }
    }
    
    return fingerprint;
}

// So sánh 2 fingerprint (độ tương đồng 0-100%)
function compareFaceFingerprints(fp1, fp2) {
    if (!fp1 || !fp2 || fp1.length !== fp2.length) {
        return 0;
    }
    
    let matchCount = 0;
    const threshold = 15; // Độ chênh lệch cho phép
    
    for (let i = 0; i < fp1.length; i++) {
        if (Math.abs(fp1[i] - fp2[i]) <= threshold) {
            matchCount++;
        }
    }
    
    const similarity = (matchCount / fp1.length) * 100;
    return Math.round(similarity);
}

// Detect face and capture
function detectAndCaptureFace() {
    if (!faceStream) return;
    
    const video = document.getElementById('face-video');
    const canvas = document.getElementById('face-canvas');
    const statusEl = document.getElementById('face-detection-status');
    
    faceDetectionRunning = true;
    
    function analyzeFrame() {
        if (!faceDetectionRunning) return;
        
        try {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            
            // Pixel analysis: count dark pixels (face region)
            let darkPixels = 0;
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                const brightness = (r + g + b) / 3;
                
                if (brightness < 150) {
                    darkPixels++;
                }
            }
            
            const totalPixels = canvas.width * canvas.height;
            const darkRatio = darkPixels / totalPixels;
            
            // Face detection: 15-65% dark pixels = face present
            if (darkRatio > 0.15 && darkRatio < 0.65) {
                statusEl.textContent = '✓ Phát hiện khuôn mặt (tỷ lệ: ' + (darkRatio * 100).toFixed(0) + '%)';
                statusEl.style.color = '#28a745';
            } else {
                statusEl.textContent = 'Không phát hiện (tỷ lệ: ' + (darkRatio * 100).toFixed(0) + '%)';
                statusEl.style.color = '#dc3545';
            }
        } catch (e) {
            console.error('Frame analysis error:', e);
        }
        
        if (faceDetectionRunning) {
            requestAnimationFrame(analyzeFrame);
        }
    }
    
    analyzeFrame();
}

// Take face snapshot and record attendance
async function takeFaceSnapshot(action) {
    if (!faceStream) {
        showFaceStatus('Camera không bật', '#dc3545', 'face-photo-status');
        return;
    }
    
    if (!USER_ID || USER_ID === 'null') {
        showFaceStatus('Không tìm thấy user_id', '#dc3545', 'face-photo-status');
        return;
    }
    
    const video = document.getElementById('face-video');
    const canvas = document.getElementById('face-canvas');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const photoBase64 = canvas.toDataURL('image/jpeg', 0.8);
    const currentFingerprint = calculateFaceFingerprint(canvas);
    
    // Xác minh khuôn mặt nếu check-out
    if (action === 'checkout') {
        if (!checkInFaceFingerprint) {
            showFaceStatus('⚠️ Không tìm thấy ảnh check-in. Vui lòng check-in trước!', '#dc3545', 'face-photo-status');
            return;
        }
        
        const similarity = compareFaceFingerprints(checkInFaceFingerprint, currentFingerprint);
        
        if (similarity < 70) {
            showFaceStatus(`❌ Khuôn mặt không khớp! (Độ tương đồng: ${similarity}%). Vui lòng kiểm tra lại.`, '#dc3545', 'face-photo-status');
            return;
        }
        
        showFaceStatus(`✓ Khuôn mặt xác minh thành công (Độ tương đồng: ${similarity}%)`, '#28a745', 'face-photo-status');
    }
    
    showFaceStatus('Đang xử lý...', '#80a6ccff', 'face-photo-status');
    
    try {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('photo', photoBase64);
        formData.append('user_id', USER_ID);
        
        const response = await fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Lưu fingerprint nếu check-in thành công
            if (action === 'checkin') {
                checkInFaceFingerprint = currentFingerprint;
                sessionStorage.setItem('checkInFaceFingerprint', JSON.stringify(currentFingerprint));
                showFaceStatus('✓ Chấm công VÀO thành công! Khuôn mặt đã được lưu lại.', '#28a745', 'face-photo-status');
            } else {
                // Xóa fingerprint sau khi check-out thành công
                checkInFaceFingerprint = null;
                sessionStorage.removeItem('checkInFaceFingerprint');
                showFaceStatus('✓ Chấm công RA thành công!', '#28a745', 'face-photo-status');
            }
            
            playSuccessSound();
            
            // Reload page after 1.5 seconds to get updated data from database
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showFaceStatus('' + result.message, '#dc3545', 'face-photo-status');
        }
    } catch (err) {
        showFaceStatus('Lỗi: ' + err.message, '#dc3545', 'face-photo-status');
        console.error('Error:', err);
    }
}

// Quick check-in (without photo)
async function quickFaceCheckin() {
    if (!USER_ID || USER_ID === 'null') {
        showFaceStatus('Không tìm thấy user_id', '#dc3545', 'face-quick-status');
        return;
    }
    
    showFaceStatus('Đang chấm công vào...', '#0066cc', 'face-quick-status');
    
    try {
        const formData = new FormData();
        formData.append('action', 'checkin');
        formData.append('user_id', USER_ID);
        
        const response = await fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showFaceStatus('Chấm công VÀO thành công!', '#28a745', 'face-quick-status');
            playSuccessSound();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showFaceStatus('' + result.message, '#dc3545', 'face-quick-status');
        }
    } catch (err) {
        showFaceStatus('Lỗi: ' + err.message, '#dc3545', 'face-quick-status');
        console.error('Error:', err);
    }
}

// Quick check-out (without photo)
async function quickFaceCheckout() {
    if (!USER_ID || USER_ID === 'null') {
        showFaceStatus('Không tìm thấy user_id', '#dc3545', 'face-quick-status');
        return;
    }
    
    showFaceStatus('Đang chấm công ra...', '#0066cc', 'face-quick-status');
    
    try {
        const formData = new FormData();
        formData.append('action', 'checkout');
        formData.append('user_id', USER_ID);
        
        const response = await fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showFaceStatus('Chấm công RA thành công!', '#28a745', 'face-quick-status');
            playSuccessSound();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showFaceStatus('' + result.message, '#dc3545', 'face-quick-status');
        }
    } catch (err) {
        showFaceStatus('Lỗi: ' + err.message, '#dc3545', 'face-quick-status');
        console.error('Error:', err);
    }
}

// Show face status message
function showFaceStatus(message, color, elementId) {
    const statusEl = document.getElementById(elementId);
    statusEl.textContent = message;
    statusEl.style.background = color;
    statusEl.style.color = 'white';
    statusEl.style.display = 'block';
    
    if (message.includes('')) {
        setTimeout(() => {
            statusEl.style.display = 'none';
        }, 3000);
    }
}

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

