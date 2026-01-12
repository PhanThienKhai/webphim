/**
 * Advanced Face Recognition for Attendance System
 * Using face-api.js (TensorFlow.js based)
 * 
 * Features:
 * - Real-time face detection with bounding box
 * - Face recognition & matching
 * - Quality validation
 * - Multiple face detection
 */

let faceDetectionRunning = false;
let detectedFaces = [];
let faceModelsLoaded = false;
let faceStream = null;

// Initialize face-api models
async function initializeFaceModels() {
    if (faceModelsLoaded) return;
    
    try {
        console.log('🔄 Loading face detection models...');
        
        const MODEL_URL = '/webphim/Trang-admin/assets/models/';
        
        // Load models from CDN if local models not available
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
            ]);
            console.log('✓ Models loaded from local');
        } catch (e) {
            console.log('Loading from CDN...');
            // Load from CDN
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights/'),
                faceapi.nets.faceLandmark68Net.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights/'),
                faceapi.nets.faceRecognitionNet.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights/'),
                faceapi.nets.faceExpressionNet.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights/')
            ]);
            console.log('✓ Models loaded from CDN');
        }
        
        faceModelsLoaded = true;
        console.log('✓ All models loaded successfully');
    } catch (err) {
        console.error('❌ Failed to load models:', err);
        throw new Error('Không thể tải models. Vui lòng refresh và thử lại.');
    }
}

// Start camera
async function startFaceCamera() {
    if (faceStream) return;
    
    const video = document.getElementById('face-video');
    const placeholder = document.getElementById('face-placeholder');
    
    try {
        console.log('Starting camera...');
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { 
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        });
        
        faceStream = stream;
        video.srcObject = stream;
        video.style.display = 'block';
        placeholder.style.display = 'none';
        
        console.log('✓ Camera started');
        showFaceStatus('✓ Camera đã bật', '#28a745', 'face-photo-status');
        
        // Start real-time detection
        startRealtimeDetection(video);
        
    } catch (err) {
        console.error('Camera error:', err);
        showFaceStatus('❌ Không thể bật camera: ' + err.message, '#dc3545', 'face-photo-status');
    }
}

// Real-time face detection on video stream
async function startRealtimeDetection(video) {
    if (!faceModelsLoaded) {
        await initializeFaceModels();
    }
    
    const displayCanvas = document.getElementById('face-display-canvas');
    const statusText = document.getElementById('face-detection-status');
    
    async function detect() {
        try {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceExpressions();
            
            // Update status
            if (detections.length === 0) {
                statusText.textContent = '❌ Không phát hiện khuôn mặt';
                statusText.style.color = '#dc3545';
            } else if (detections.length > 1) {
                statusText.textContent = '⚠️ Phát hiện ' + detections.length + ' khuôn mặt - chỉ một người!';
                statusText.style.color = '#ffc107';
            } else {
                const detection = detections[0];
                const quality = calculateFaceQuality(detection);
                statusText.textContent = '✓ Khuôn mặt tốt (' + quality + '%)';
                statusText.style.color = quality >= 75 ? '#28a745' : '#ffc107';
            }
            
            // Draw on canvas
            const ctx = displayCanvas.getContext('2d');
            ctx.clearRect(0, 0, displayCanvas.width, displayCanvas.height);
            ctx.drawImage(video, 0, 0, displayCanvas.width, displayCanvas.height);
            
            if (detections.length > 0) {
                displayCanvas.style.display = 'block';
                detections.forEach(detection => {
                    const box = detection.detection.box;
                    const color = detections.length === 1 ? '#28a745' : '#ffc107';
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);
                });
            }
            
        } catch (err) {
            console.error('Detection error:', err);
        }
        
        if (faceStream) {
            requestAnimationFrame(detect);
        }
    }
    
    detect();
}

// Calculate face quality score
function calculateFaceQuality(detection) {
    let score = 100;
    const box = detection.detection.box;
    
    // Size check
    const faceArea = (box.width * box.height) / (640 * 480);
    if (faceArea < 0.15 || faceArea > 0.8) {
        score -= 20;
    }
    
    // Expression check
    const expressions = detection.expressions;
    if (expressions.crying > 0.5 || expressions.angry > 0.5 || expressions.sad > 0.5) {
        score -= 15;
    }
    
    return Math.max(Math.min(score, 100), 0);
}

// Stop camera
function stopFaceCamera() {
    if (faceStream) {
        faceStream.getTracks().forEach(track => track.stop());
        faceStream = null;
    }
    
    const video = document.getElementById('face-video');
    const placeholder = document.getElementById('face-placeholder');
    const displayCanvas = document.getElementById('face-display-canvas');
    
    video.style.display = 'none';
    placeholder.style.display = 'flex';
    displayCanvas.style.display = 'none';
    
    console.log('✓ Camera stopped');
    showFaceStatus('Camera đã tắt', '#666', 'face-photo-status');
}

// Take face snapshot for attendance
async function takeFaceSnapshot(action) {
    if (!faceStream) {
        showFaceStatus('❌ Camera không bật', '#dc3545', 'face-photo-status');
        return;
    }
    
    if (typeof USER_ID === 'undefined' || !USER_ID || USER_ID === 'null') {
        showFaceStatus('❌ Không tìm thấy user_id', '#dc3545', 'face-photo-status');
        return;
    }
    
    const video = document.getElementById('face-video');
    const canvas = document.getElementById('face-canvas');
    
    if (!video.videoWidth || !video.videoHeight) {
        showFaceStatus('❌ Video chưa sẵn sàng...', '#dc3545', 'face-photo-status');
        return;
    }
    
    // Initialize models if needed
    if (!faceModelsLoaded) {
        showFaceStatus('🔄 Đang tải models...', '#0066cc', 'face-photo-status');
        try {
            await initializeFaceModels();
        } catch (err) {
            showFaceStatus('❌ ' + err.message, '#dc3545', 'face-photo-status');
            return;
        }
    }
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Detect face quality
    showFaceStatus('📸 Kiểm tra chất lượng ảnh...', '#0066cc', 'face-photo-status');
    
    try {
        const detections = await faceapi
            .detectAllFaces(canvas, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceExpressions();
        
        // Validate
        if (detections.length === 0) {
            showFaceStatus('❌ Không phát hiện khuôn mặt', '#dc3545', 'face-photo-status');
            return;
        }
        
        if (detections.length > 1) {
            showFaceStatus('⚠️ Phát hiện ' + detections.length + ' khuôn mặt - chỉ một người!', '#dc3545', 'face-photo-status');
            return;
        }
        
        const quality = calculateFaceQuality(detections[0]);
        if (quality < 60) {
            showFaceStatus('⚠️ Chất lượng ảnh không đủ (' + quality + '%). Vui lòng chụp lại.', '#ffc107', 'face-photo-status');
            return;
        }
        
        showFaceStatus('✓ Chất lượng tốt (' + quality + '%)', '#28a745', 'face-photo-status');
        
    } catch (err) {
        console.error('Face detection error:', err);
        showFaceStatus('❌ Lỗi phân tích: ' + err.message, '#dc3545', 'face-photo-status');
        return;
    }
    
    // Send to server
    const photoBase64 = canvas.toDataURL('image/jpeg', 0.95);
    
    showFaceStatus('⏳ Đang xử lý...', '#0066cc', 'face-photo-status');
    
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
            const msg = action === 'checkin' ? 'VÀO' : 'RA';
            showFaceStatus('✓ Chấm công ' + msg + ' thành công!', '#28a745', 'face-photo-status');
            playSuccessSound();
            setTimeout(() => location.reload(), 1500);
        } else {
            showFaceStatus('❌ ' + (result.message || 'Lỗi không xác định'), '#dc3545', 'face-photo-status');
        }
    } catch (err) {
        showFaceStatus('❌ Lỗi: ' + err.message, '#dc3545', 'face-photo-status');
        console.error('Error:', err);
    }
}

// Show status message
function showFaceStatus(message, color, elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.style.backgroundColor = color + '22';
        element.style.borderLeft = '4px solid ' + color;
        element.style.color = color;
        element.style.display = 'block';
    }
}

// Play success sound
function playSuccessSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch (e) {
        console.log('Cannot play sound');
    }
}

// Switch tabs
function switchFaceTab(tab) {
    document.querySelectorAll('.face-tab-content').forEach(el => el.style.display = 'none');
    document.getElementById('face-' + tab).style.display = 'block';
    
    document.querySelectorAll('.face-tab-btn').forEach(el => {
        el.style.color = '#999';
        el.style.borderBottomColor = 'transparent';
    });
    event.target.style.color = 'var(--primary-color)';
    event.target.style.borderBottomColor = 'var(--primary-color)';
}

// Export for use
window.FaceRecognition = {
    initializeFaceModels,
    startFaceCamera,
    stopFaceCamera,
    takeFaceSnapshot,
    showFaceStatus,
    playSuccessSound,
    switchFaceTab,
    isReady: () => faceModelsLoaded
};

console.log('✓ Face Recognition Advanced loaded');
