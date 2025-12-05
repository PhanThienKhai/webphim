<?php 
// Trang đăng ký khuôn mặt cho nhân viên
if (!isset($_SESSION['user1'])) {
    header('Location: /webphim/Trang-admin/login.php');
    exit;
}

$user_id = $_SESSION['user1']['id'];
$error = '';
$success = '';

// Check if already registered
if (!function_exists('pdo_query_one')) {
    include __DIR__ . '/../../model/pdo.php';
}

$user = pdo_query_one("SELECT id, name, face_registered_at FROM taikhoan WHERE id = ?", $user_id);
$already_registered = !empty($user['face_registered_at']);
?>

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

        .video-container {
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        video, canvas {
            width: 100%;
            max-width: 500px;
            max-height: 400px;
            border-radius: 10px;
            background: #000;
            display: none;
            margin: 0 auto;
        }

        .video-placeholder {
            width: 100%;
            max-width: 500px;
            height: 350px;
            background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            margin: 0 auto;
            font-size: 16px;
            text-align: center;
            flex-direction: column;
            gap: 10px;
        }

        .video-placeholder .icon {
            font-size: 48px;
        }

        .status-text {
            text-align: center;
            margin-top: 15px;
            font-weight: 600;
            font-size: 14px;
        }

        .status-success {
            color: #28a745;
        }

        .status-error {
            color: #dc3545;
        }

        .status-info {
            color: #17a2b8;
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px 0;
        }

        .button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .button:hover {
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

        .button-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #bd2130 100%);
            color: white;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .alert-info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }

        .instruction {
            background: #f8f9fa;
            border-left: 4px solid var(--info-color);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .instruction h6 {
            margin: 0 0 10px 0;
            color: #333;
            font-weight: 700;
        }

        .instruction ul {
            margin: 0;
            padding-left: 20px;
        }

        .instruction li {
            margin-bottom: 8px;
        }

        .registered-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>

    <!-- Header -->
    <div class="page-header-professional">
        <h1>Đăng Ký Khuôn Mặt
            <?php if ($already_registered): ?>
                <span class="registered-badge">✓ ĐÃ ĐĂNG KÝ</span>
            <?php endif; ?>
        </h1>
        <p class="header-subtitle">Quét khuôn mặt của bạn để sử dụng chấm công bằng nhận diện khuôn mặt</p>
    </div>

    <!-- Messages -->
    <?php if ($error): ?>
        <div class="alert alert-error">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- Instructions -->
    <div class="card-modern">
        <div class="card-header">
            <h5>📋 Hướng Dẫn Đăng Ký</h5>
        </div>
        <div class="card-body">
            <div class="instruction">
                <h6>🎯 Cách Thực Hiện:</h6>
                <ul>
                    <li>✓ Đảm bảo ánh sáng đủ sáng</li>
                    <li>✓ Mặt hướng thẳng vào camera</li>
                    <li>✓ Nhấn "Bắt Đầu Quay" để kích hoạt camera</li>
                    <li>✓ Nhấn "Đăng Ký Khuôn Mặt" khi camera phát hiện khuôn mặt rõ ràng</li>
                    <li>✓ Chờ xác nhận thành công</li>
                </ul>
            </div>
            <div class="instruction" style="border-color: var(--danger-color); background: #fff3cd;">
                <h6 style="color: var(--danger-color);">⚠️ Lưu Ý:</h6>
                <ul>
                    <li>Một khi đã đăng ký, bạn KHÔNG thể check-in/out bằng khuôn mặt người khác</li>
                    <li>Hãy chắc chắn quét khuôn mặt của chính bạn</li>
                    <li>Hệ thống sẽ xác minh khuôn mặt ở độ chính xác 90%</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Camera Area -->
    <div class="card-modern">
        <div class="card-header">
            <h5>📷 Quét Khuôn Mặt</h5>
        </div>
        <div class="card-body">
            <div class="video-container">
                <video id="register-face-video" autoplay playsinline></video>
                <canvas id="register-face-canvas"></canvas>
                <div id="register-face-placeholder" class="video-placeholder">
                    <div class="icon">📹</div>
                    <div>Nhấn "Bắt Đầu Quay" để kích hoạt camera</div>
                </div>
                <div id="register-face-status" class="status-text status-info">
                    Chưa kích hoạt camera
                </div>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <button class="button button-primary" onclick="startRegisterFaceCamera()">
                    ▶️ Bắt Đầu Quay
                </button>
                <button class="button button-success" onclick="registerFace()">
                    ✓ Đăng Ký Khuôn Mặt
                </button>
                <button class="button button-danger" onclick="stopRegisterFaceCamera()">
                    ⏹️ Dừng Quay
                </button>
            </div>

            <?php if ($already_registered): ?>
                <div class="alert alert-success">
                    ✅ Bạn đã đăng ký khuôn mặt. Nhấn "Đăng Ký Khuôn Mặt" để cập nhật template mới.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    let registerFaceStream = null;

    // Tính toán fingerprint khuôn mặt (histogram của pixel)
function calculateFaceFingerprint(canvas) {
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    
    // SECURITY: Validate image quality before fingerprinting (RELAXED)
    let minBright = 255, maxBright = 0, totalBright = 0;
    const samples = Math.min(1000, data.length / 4); // Sample pixels for speed
    
    for (let i = 0; i < samples; i++) {
        const idx = Math.floor(Math.random() * (data.length / 4)) * 4;
        const r = data[idx];
        const g = data[idx + 1];
        const b = data[idx + 2];
        const brightness = (r * 0.299 + g * 0.587 + b * 0.114);
        
        minBright = Math.min(minBright, brightness);
        maxBright = Math.max(maxBright, brightness);
        totalBright += brightness;
    }
    
    const contrast = maxBright - minBright;
    const avgBright = totalBright / samples;
    
    // REJECT only if completely invalid (contrast < 5 = solid color, or completely black)
    if (contrast < 5) {
        // Too uniform - likely blank/solid color image
        return null; // Signal error to caller
    }
    // RELAXED: Allow wider brightness range (0-255 instead of 30-220)
    // if (avgBright < 5 || avgBright > 250) {
    //     return null;
    // }
    
    // Improved: Use 6x6 grid instead of 4x4 for better accuracy
    const gridSize = 6;
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
                    
                    // Use weighted formula for better brightness calculation
                    const brightness = (r * 0.299 + g * 0.587 + b * 0.114);
                    totalBrightness += brightness;
                    pixelCount++;
                }
            }
            
            const avgBrightness = pixelCount > 0 ? totalBrightness / pixelCount : 0;
            // Quantize to 0-100 range for better fingerprinting
            fingerprint.push(Math.round(avgBrightness / 2.55));
        }
    }
    
    return fingerprint;
}    // Bắt đầu camera
    async function startRegisterFaceCamera() {
        if (registerFaceStream) return;
        
        const video = document.getElementById('register-face-video');
        const placeholder = document.getElementById('register-face-placeholder');
        
        document.getElementById('register-face-status').textContent = '🔄 Đang bật camera...';
        document.getElementById('register-face-status').className = 'status-text status-info';
        
        try {
            let stream = null;
            let attempts = [];
            
            // Attempt 1: Modern API (Chrome, Firefox, Edge, newer Safari)
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    console.log('Thử modern API: navigator.mediaDevices.getUserMedia');
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 640 },
                            height: { ideal: 480 }
                        }
                    });
                    console.log('✓ Modern API thành công');
                    attempts.push('Modern API');
                }
            } catch (e) {
                console.warn('Modern API thất bại:', e.message);
                attempts.push('Modern API thất bại');
            }
            
            // Attempt 2: Old webkit API (older Safari)
            if (!stream) {
                try {
                    if (navigator.webkitGetUserMedia) {
                        console.log('Thử webkit API: navigator.webkitGetUserMedia');
                        stream = await new Promise((resolve, reject) => {
                            navigator.webkitGetUserMedia(
                                { video: { facingMode: 'user' } },
                                resolve,
                                reject
                            );
                        });
                        console.log('✓ Webkit API thành công');
                        attempts.push('Webkit API');
                    }
                } catch (e) {
                    console.warn('Webkit API thất bại:', e.message);
                    attempts.push('Webkit API thất bại');
                }
            }
            
            // Attempt 3: Mozilla API
            if (!stream) {
                try {
                    if (navigator.mozGetUserMedia) {
                        console.log('Thử Mozilla API: navigator.mozGetUserMedia');
                        stream = await new Promise((resolve, reject) => {
                            navigator.mozGetUserMedia(
                                { video: { facingMode: 'user' } },
                                resolve,
                                reject
                            );
                        });
                        console.log('✓ Mozilla API thành công');
                        attempts.push('Mozilla API');
                    }
                } catch (e) {
                    console.warn('Mozilla API thất bại:', e.message);
                    attempts.push('Mozilla API thất bại');
                }
            }
            
            // Attempt 4: Generic getUserMedia
            if (!stream) {
                try {
                    if (navigator.getUserMedia) {
                        console.log('Thử generic API: navigator.getUserMedia');
                        stream = await new Promise((resolve, reject) => {
                            navigator.getUserMedia(
                                { video: { facingMode: 'user' } },
                                resolve,
                                reject
                            );
                        });
                        console.log('✓ Generic API thành công');
                        attempts.push('Generic API');
                    }
                } catch (e) {
                    console.warn('Generic API thất bại:', e.message);
                    attempts.push('Generic API thất bại');
                }
            }
            
            if (!stream) {
                const debugInfo = attempts.join(', ');
                console.error('Tất cả API đều thất bại:', debugInfo);
                throw new Error('Trình duyệt không hỗ trợ camera. Hãy thử Safari, Chrome, Firefox hoặc Edge. (Đã thử: ' + debugInfo + ')');
            }
            
            registerFaceStream = stream;
            video.srcObject = registerFaceStream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            
            document.getElementById('register-face-status').textContent = '✓ Camera bật thành công';
            document.getElementById('register-face-status').className = 'status-text status-success';
        } catch (err) {
            console.error('Camera error:', err);
            console.error('Browser info:', navigator.userAgent);
            
            // Provide specific error messages
            let errorMsg = err.message;
            if (err.name === 'NotAllowedError' || err.message.includes('Permission denied')) {
                errorMsg = '❌ Bạn chưa cấp quyền camera. Vui lòng:\n1. Vào Cài đặt > Safari > Camera\n2. Chọn "Allow"';
            } else if (err.name === 'NotFoundError' || err.message.includes('Requested device not found')) {
                errorMsg = '❌ Không tìm thấy camera trên thiết bị';
            } else if (err.name === 'NotReadableError') {
                errorMsg = '❌ Camera đang bị sử dụng bởi ứng dụng khác';
            } else if (err.name === 'SecurityError') {
                errorMsg = '❌ Lỗi bảo mật. Kiểm tra HTTPS hoặc localhost';
            } else if (err.name === 'TypeError' || err.message.includes('not a function')) {
                errorMsg = '❌ Trình duyệt không hỗ trợ camera API. Cập nhật Safari lên phiên bản 11+';
            }
            
            document.getElementById('register-face-status').textContent = errorMsg;
            document.getElementById('register-face-status').className = 'status-text status-error';
        }
    }

    // Dừng camera
    function stopRegisterFaceCamera() {
        if (registerFaceStream) {
            registerFaceStream.getTracks().forEach(track => track.stop());
            registerFaceStream = null;
        }
        
        const video = document.getElementById('register-face-video');
        const placeholder = document.getElementById('register-face-placeholder');
        video.style.display = 'none';
        placeholder.style.display = 'flex';
        
        document.getElementById('register-face-status').textContent = 'Camera đã dừng';
        document.getElementById('register-face-status').className = 'status-text status-info';
    }

    // Đăng ký khuôn mặt
    async function registerFace() {
        if (!registerFaceStream) {
            alert('Vui lòng bắt đầu quay camera trước');
            return;
        }

        const video = document.getElementById('register-face-video');
        const canvas = document.getElementById('register-face-canvas');
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const fingerprint = calculateFaceFingerprint(canvas);
        
        // SECURITY: Reject if fingerprint is invalid (null = image quality too poor)
        if (fingerprint === null) {
            document.getElementById('register-face-status').textContent = '❌ Ảnh không hợp lệ! Ánh sáng yếu, quá sáng, hoặc che mặt. Vui lòng chụp lại.';
            document.getElementById('register-face-status').className = 'status-text status-error';
            return;
        }
        
        const photoBase64 = canvas.toDataURL('image/jpeg', 0.95); // Capture base64 for Haar Cascade (high quality)
        
        document.getElementById('register-face-status').textContent = '⏳ Đang xử lý...';
        document.getElementById('register-face-status').className = 'status-text status-info';
        
        const formData = new FormData();
        formData.append('action', 'register_face');
        formData.append('fingerprint', JSON.stringify(fingerprint));
        formData.append('photo', photoBase64); // Add photo for Haar Cascade face detection
        
        try {
            // Send to register_face_handler.php
            const response = await fetch('/webphim/Trang-admin/model/register_face_handler.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                document.getElementById('register-face-status').textContent = '✅ ' + result.message;
                document.getElementById('register-face-status').className = 'status-text status-success';
                
                // Reload page after 2 seconds
                setTimeout(() => location.reload(), 2000);
            } else {
                document.getElementById('register-face-status').textContent = '❌ ' + result.message;
                document.getElementById('register-face-status').className = 'status-text status-error';
            }
        } catch (err) {
            document.getElementById('register-face-status').textContent = '❌ Lỗi: ' + err.message;
            document.getElementById('register-face-status').className = 'status-text status-error';
        }
    }
    </script>
</div>

<?php include __DIR__ . '/../home/footer.php'; ?>
