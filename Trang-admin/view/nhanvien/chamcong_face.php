<?php
/**
 * Face Recognition Attendance System
 * Tích hợp vào admin panel
 */

// Get user ID from session
$user_id = $_SESSION['user1']['id'] ?? null;
$user_name = $_SESSION['user1']['ten_nv'] ?? 'Nhân viên';
?>

<style>
.chamcong-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #ddd;
}

.tab-btn {
    padding: 12px 20px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 14px;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.tab-btn.active {
    border-bottom-color: #007bff;
    color: #007bff;
    font-weight: bold;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.camera-container {
    position: relative;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
}

#video {
    width: 100%;
    height: 400px;
    display: none;
}

.camera-placeholder {
    width: 100%;
    height: 400px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    text-align: center;
}

.camera-placeholder.active {
    display: none;
}

#photoPreview {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    display: none;
    margin-bottom: 15px;
    border-radius: 8px;
}

.status {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 4px;
    font-size: 14px;
    text-align: center;
    min-height: 20px;
    opacity: 0;
    transition: opacity 0.3s;
}

.status.info {
    background: #e7f3ff;
    color: #0066cc;
    opacity: 1;
}

.status.success {
    background: #d4edda;
    color: #155724;
    opacity: 1;
}

.status.warning {
    background: #fff3cd;
    color: #856404;
    opacity: 1;
}

.status.error {
    background: #f8d7da;
    color: #721c24;
    opacity: 1;
}

.button-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 15px;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-primary:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.info-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #dee2e6;
}

.info-row:last-child {
    border-bottom: none;
}

.label {
    font-weight: bold;
    color: #666;
}

.value {
    color: #333;
}

#faceDetected {
    opacity: 0;
    height: 0;
    overflow: hidden;
    transition: all 0.3s;
}

#faceDetected.show {
    opacity: 1;
    height: auto;
    margin-bottom: 15px;
    padding: 15px;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    color: #155724;
}

canvas {
    display: none;
}
</style>

<div class="chamcong-container">
    <h2>👤 Chấm Công Bằng Khuôn Mặt</h2>
    <p style="color: #666; margin-bottom: 20px;">Xin chào: <strong><?php echo htmlspecialchars($user_name); ?></strong></p>
    
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('photo')">📸 Chấm Công với Ảnh</button>
        <button class="tab-btn" onclick="switchTab('manual')">⌨️ Chấm Công Nhanh</button>
    </div>

    <!-- Status Message -->
    <div id="status" class="status"></div>

    <!-- Photo Tab -->
    <div id="photo" class="tab-content active">
        <div class="camera-container">
            <video id="video"></video>
            <div id="cameraPlaceholder" class="camera-placeholder">
                📷 Camera sẽ hiển thị ở đây
            </div>
        </div>

        <img id="photoPreview" alt="Preview">

        <div id="faceDetected">
            ✅ <strong>Phát hiện khuôn mặt!</strong> Bấm Check-in hoặc Check-out để lưu.
        </div>

        <div class="button-group">
            <button class="btn-primary" onclick="capturePhoto()">🎥 Bật Camera</button>
            <button class="btn-danger" onclick="retakePhoto()" style="display: none;" id="retakeBtn">🔄 Chụp Lại</button>
        </div>

        <div class="button-group">
            <button class="btn-success" id="checkinBtn" onclick="recordAttendance('checkin')" disabled>✅ Check-in</button>
            <button class="btn-danger" id="checkoutBtn" onclick="recordAttendance('checkout')" disabled>❌ Check-out</button>
        </div>
    </div>

    <!-- Manual Tab -->
    <div id="manual" class="tab-content">
        <div class="info-box">
            ⌨️ <strong>Chấm công nhanh:</strong> Không cần chụp ảnh, chỉ bấm nút Check-in/out
        </div>

        <div class="button-group">
            <button class="btn-success" style="padding: 15px;" onclick="quickCheckin()">
                ✅ CHECKIN<br><small id="todayCheckin">Hôm nay: Chưa check-in</small>
            </button>
            <button class="btn-danger" style="padding: 15px;" onclick="quickCheckout()">
                ❌ CHECKOUT<br><small id="todayCheckout">Hôm nay: Chưa check-out</small>
            </button>
        </div>
    </div>

    <canvas id="canvas"></canvas>
</div>

<script>
    let video = null;
    let canvas = null;
    let stream = null;
    let photoData = null;
    const USER_ID = <?php echo $user_id; ?>; // From session

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }

    function showStatus(message, type = 'info') {
        const statusEl = document.getElementById('status');
        statusEl.className = 'status ' + type;
        statusEl.textContent = message;
        setTimeout(() => statusEl.className = 'status', 3000);
    }

    async function capturePhoto() {
        try {
            showStatus('⏳ Đang bật camera...', 'info');
            
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            const placeholder = document.getElementById('cameraPlaceholder');

            const stream_obj = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });

            stream = stream_obj;
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.classList.add('active');

            document.getElementById('retakeBtn').style.display = 'block';

            showStatus('✅ Camera đã bật - Đặt khuôn mặt vào camera...', 'success');
            
            video.onloadedmetadata = () => {
                showStatus('👁️ Đang tìm khuôn mặt...', 'info');
                detectAndCapture();
            };

        } catch (error) {
            showStatus('❌ Lỗi: ' + error.message, 'error');
        }
    }

    function detectAndCapture() {
        if (!video || !stream) return;
        
        canvas = document.getElementById('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        if (canvas.width === 0) {
            setTimeout(detectAndCapture, 100);
            return;
        }

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);

        try {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;

            let darkPixels = 0;
            let totalPixels = canvas.width * canvas.height;
            
            for (let i = 0; i < data.length; i += 4) {
                const brightness = (data[i] + data[i+1] + data[i+2]) / 3;
                if (brightness < 100) darkPixels++;
            }

            const darkRatio = darkPixels / totalPixels;
            
            if (darkRatio > 0.15 && darkRatio < 0.65) {
                takeSnapshot();
                return;
            }
            
            requestAnimationFrame(detectAndCapture);
            
        } catch (e) {
            console.error('Detection error:', e);
            setTimeout(detectAndCapture, 100);
        }
    }

    function takeSnapshot() {
        canvas = document.getElementById('canvas');
        photoData = canvas.toDataURL('image/jpeg', 0.8);
        
        const preview = document.getElementById('photoPreview');
        preview.src = photoData;
        preview.style.display = 'block';

        document.getElementById('faceDetected').classList.add('show');
        document.getElementById('checkinBtn').disabled = false;
        document.getElementById('checkoutBtn').disabled = false;

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.style.display = 'none';
        document.getElementById('cameraPlaceholder').classList.remove('active');

        showStatus('✅ Phát hiện khuôn mặt! Bấm Check-in hoặc Check-out', 'success');
    }

    function retakePhoto() {
        photoData = null;
        document.getElementById('photoPreview').style.display = 'none';
        document.getElementById('faceDetected').classList.remove('show');
        document.getElementById('checkinBtn').disabled = true;
        document.getElementById('checkoutBtn').disabled = true;
        
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        
        capturePhoto();
    }

    function recordAttendance(type) {
        if (!photoData) {
            showStatus('⚠️ Vui lòng chụp ảnh trước', 'warning');
            return;
        }

        const action = type === 'checkin' ? 'check-in' : 'check-out';
        showStatus('⏳ Đang xử lý ' + action + ' (phát hiện khuôn mặt)...', 'info');
        
        const formData = new FormData();
        formData.append('action', type === 'checkin' ? 'checkin' : 'checkout');
        formData.append('photo', photoData);
        formData.append('user_id', USER_ID);
        
        fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showStatus('✅ ' + data.message + '\n👤 Phát hiện ' + data.faces_detected + ' khuôn mặt', 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                let errorMsg = data.message;
                if (data.debug && data.debug.session_data) {
                    errorMsg += '\n📌 Debug: Session keys = ' + data.debug.session_data.join(', ');
                }
                showStatus('❌ ' + errorMsg, 'error');
            }
        })
        .catch(e => {
            showStatus('❌ Lỗi: ' + e.message, 'error');
            console.error('Error:', e);
        });
    }

    function quickCheckin() {
        showStatus('⏳ Đang check-in...', 'info');
        const formData = new FormData();
        formData.append('action', 'checkin');
        formData.append('user_id', USER_ID);
        
        fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showStatus('✅ ' + data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showStatus('❌ ' + data.message, 'error');
            }
        })
        .catch(e => {
            showStatus('❌ Lỗi: ' + e.message, 'error');
        });
    }

    function quickCheckout() {
        showStatus('⏳ Đang check-out...', 'info');
        const formData = new FormData();
        formData.append('action', 'checkout');
        formData.append('user_id', USER_ID);
        
        fetch('/webphim/Trang-admin/model/chamcong_detector.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showStatus('✅ ' + data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showStatus('❌ ' + data.message, 'error');
            }
        })
        .catch(e => {
            showStatus('❌ Lỗi: ' + e.message, 'error');
        });
    }

    window.addEventListener('load', () => {
        const checkinTime = document.querySelector('[data-checkin]')?.textContent || 'Chưa check-in';
        const checkoutTime = document.querySelector('[data-checkout]')?.textContent || 'Chưa check-out';
        
        document.getElementById('todayCheckin').textContent = 'Hôm nay: ' + checkinTime;
        document.getElementById('todayCheckout').textContent = 'Hôm nay: ' + checkoutTime;
    });
</script>
