<?php
/**
 * Public QR Ticket Viewer
 * Scan QR code from ticket → Show ticket info
 * No login required
 */

// Get ticket ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ticket = null;
$error = null;

if ($id > 0) {
    // Include database functions
    include __DIR__ . '/model/pdo.php';
    
    // Query ticket info
    $sql = "SELECT v.id, v.ma_ve, v.trang_thai, v.ghe, v.check_in_luc,
                   p.tieu_de, p.img, 
                   lc.ngay_chieu, 
                   kgc.thoi_gian_chieu, 
                   pc.name as tenphong,
                   rc.ten_rap, rc.dia_chi
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pc ON pc.id = kgc.id_phong
            JOIN rap_chieu rc ON rc.id = lc.id_rap
            WHERE v.id = ?
            LIMIT 1";
    
    $ticket = pdo_query_one($sql, $id);
    
    if (!$ticket) {
        $error = "Vé không tồn tại hoặc đã bị xóa";
    }
} else {
    $error = "Vui lòng cung cấp ID vé";
}

// Determine ticket status color and text
$status_info = ['color' => '#999', 'text' => 'Không xác định', 'icon' => '❓'];
switch ($ticket['trang_thai'] ?? 0) {
    case 1:
        $status_info = ['color' => '#4caf50', 'text' => 'Đã thanh toán', 'icon' => '✅'];
        break;
    case 2:
        $status_info = ['color' => '#2196f3', 'text' => 'Đã sử dụng', 'icon' => '✔️'];
        break;
    case 3:
        $status_info = ['color' => '#f44336', 'text' => 'Đã hủy', 'icon' => '❌'];
        break;
    case 4:
        $status_info = ['color' => '#2196f3', 'text' => 'Đã check-in', 'icon' => '🎬'];
        break;
}

// Generate QR code URL for printing on ticket
$qr_data = urlencode("http://" . $_SERVER['HTTP_HOST'] . "/Trang-nguoi-dung/quete.php?id=" . $id);
$qr_code_url = "view/qr.php?data=" . $qr_data . "&t=" . time();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎫 Thông Tin Vé</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
        }
        
        .error-box {
            background: #ffebee;
            border: 2px solid #f44336;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            color: #c62828;
        }
        
        .error-box h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .error-box p {
            font-size: 16px;
        }
        
        .ticket-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.4s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Movie Poster */
        .poster-section {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #52576eff 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
        }
        
        .poster-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-poster {
            font-size: 48px;
        }
        
        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Ticket Info */
        .info-section {
            padding: 25px;
        }
        
        .info-section h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
            word-wrap: break-word;
        }
        
        .ticket-code {
            color: #666;
            font-size: 13px;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
        }
        
        .status-line {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 12px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        
        .status-icon {
            font-size: 20px;
        }
        
        .status-text {
            font-weight: bold;
            color: #333;
        }
        
        .info-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-icon {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
            word-wrap: break-word;
        }
        
        /* Seat Info */
        .seat-badge {
            display: inline-block;
            background: linear-gradient(135deg, #717480ff 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        /* QR Code Section */
        .qr-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #eee;
            text-align: center;
        }
        
        .qr-container {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }
        
        .qr-code {
            width: 180px;
            height: 180px;
            /* border: 3px solid #667eea; */
            border-radius: 8px;
            padding: 0px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .qr-note {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
            font-style: italic;
        }
        
        /* Download Section */
        .download-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px 0;
            border-top: 2px dashed #eee;
            border-bottom: 2px dashed #eee;
        }
        
        .download-btn {
            display: inline-block;
            background: linear-gradient(135deg, #484a54ff 0%, #4c3d5cff 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .download-btn:active {
            transform: translateY(0);
        }
        
        /* Footer */
        .footer {
            padding: 15px 25px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Check-in Info */
        .checkin-info {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #2e7d32;
        }
        
        /* Cinema Info */
        .cinema-header {
            background: linear-gradient(135deg, #4e5056ff 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
        }
        
        .cinema-header h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .cinema-header p {
            font-size: 13px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($error): ?>
            <div class="error-box">
                <h2>❌ Lỗi</h2>
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php elseif ($ticket): ?>
            <div class="ticket-card">
                <!-- Poster -->
                <div class="poster-section">
                    <?php 
                    // Fix image path - convert relative path to full URL
                    $img_url = $ticket['img'] ?? '';
                    if (!empty($img_url)) {
                        // If it's a relative path, make it full URL
                        if (strpos($img_url, 'http') !== 0) {
                            // If path doesn't start with /, add it
                            if (strpos($img_url, '/') !== 0) {
                                $img_url = '/Trang-nguoi-dung/imgavt/' . $img_url;
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($img_url)): ?>
                        <img src="<?= htmlspecialchars($img_url) ?>" alt="<?= htmlspecialchars($ticket['tieu_de']) ?>" onerror="this.style.display='none'">
                    <?php endif; ?>
                    <?php if (empty($img_url)): ?>
                        <div class="no-poster">🎬</div>
                    <?php endif; ?>
                </div>
                
                <!-- Cinema Info -->
                <div class="cinema-header">
                    <h3>🏢 <?= htmlspecialchars($ticket['ten_rap'] ?? 'N/A') ?></h3>
                    <p>📍 <?= htmlspecialchars($ticket['dia_chi'] ?? 'N/A') ?></p>
                </div>
                
                <!-- Ticket Info -->
                <div class="info-section">
                    <h1><?= htmlspecialchars($ticket['tieu_de'] ?? 'N/A') ?></h1>
                    <div class="ticket-code">Mã vé: <?= htmlspecialchars($ticket['id'] ?? 'N/A') ?></div>
                    
                    <!-- Status -->
                    <div class="status-line">
                        <span class="status-icon"><?= $status_info['icon'] ?></span>
                        <span class="status-text" style="color: <?= $status_info['color'] ?>;">
                            <?= htmlspecialchars($status_info['text']) ?>
                        </span>
                    </div>
                    
                    <!-- Check-in Info (if already checked in) -->
                    <?php if ($ticket['trang_thai'] == 4 && !empty($ticket['check_in_luc'])): ?>
                        <div class="checkin-info">
                            ✓ Đã check-in lúc: <?= date('H:i - d/m/Y', strtotime($ticket['check_in_luc'])) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Date & Time -->
                    <div class="info-row">
                        <div class="info-icon">📅</div>
                        <div class="info-content">
                            <div class="info-label">Ngày Chiếu</div>
                            <div class="info-value">
                                <?= date('d/m/Y', strtotime($ticket['ngay_chieu'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time -->
                    <div class="info-row">
                        <div class="info-icon">⏰</div>
                        <div class="info-content">
                            <div class="info-label">Giờ Chiếu</div>
                            <div class="info-value">
                                <?= date('H:i', strtotime($ticket['thoi_gian_chieu'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room -->
                    <div class="info-row">
                        <div class="info-icon">🚪</div>
                        <div class="info-content">
                            <div class="info-label">Phòng Chiếu</div>
                            <div class="info-value">
                                <?= htmlspecialchars($ticket['tenphong'] ?? 'N/A') ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seats -->
                    <div class="info-row">
                        <div class="info-icon">🪑</div>
                        <div class="info-content">
                            <div class="info-label">Ghế</div>
                            <div class="info-value">
                                <div class="seat-badge">
                                    <?= htmlspecialchars($ticket['ghe'] ?? 'N/A') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Code -->
                    <div class="qr-section">
                        <div class="info-label">Mã QR Vé</div>
                        <div class="qr-container">
                            <img src="<?= htmlspecialchars($qr_code_url) ?>" alt="QR Code" class="qr-code">
                        </div>
                        <p class="qr-note">Quét mã QR này để xem thông tin vé</p>
                    </div>
                </div>
                
                <!-- Download PDF Button -->
                <div class="download-section">
                    <a href="download_ticket_pdf.php?id=<?= $id ?>" class="download-btn" target="_blank">
                        Tải Vé PDF
                    </a>
                </div>
                
                <!-- Footer -->
                <div class="footer">
                    🎫 Galaxy Studio Cinema | Scan QR code để xem vé của bạn
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Auto-refresh khi dữ liệu thay đổi (check-in, etc.)
        setInterval(() => {
            location.reload();
        }, 5000);
        
        // Fullscreen trên mobile
        function requestFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen().catch(err => {
                    // Browser không cho phép fullscreen
                });
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }
        
        // Request fullscreen khi load trên mobile
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            document.addEventListener('touchstart', requestFullscreen);
        }
    </script>
</body>
</html>
