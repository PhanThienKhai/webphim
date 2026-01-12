<?php
/**
 * Face Detection API - PHP-FaceDetector
 * Endpoint: POST /Trang-admin/api/detect_face.php
 * Purpose: Detect faces in image and verify employee attendance
 */

session_start();
header('Content-Type: application/json');

// Get correct path to webphim root
$apiDir = __DIR__;
$adminDir = dirname($apiDir);
$webphimRoot = dirname($adminDir);

// Include required files
if (!function_exists('pdo_get_connection')) {
    require_once $webphimRoot . '/Trang-admin/model/pdo.php';
}

if (!class_exists('FaceDetector')) {
    require_once $webphimRoot . '/Trang-admin/model/FaceDetector.php';
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['image']) || empty($input['image'])) {
        throw new Exception('Image data not provided');
    }
    
    if (!isset($input['nhanvien_id']) || empty($input['nhanvien_id'])) {
        throw new Exception('Employee ID not provided');
    }
    
    $imageData = $input['image'];
    $nhanvien_id = intval($input['nhanvien_id']);
    
    // Decode base64 image
    if (strpos($imageData, 'data:image') !== false) {
        $imageData = explode(',', $imageData)[1];
    }
    
    $imageData = base64_decode($imageData);
    if ($imageData === false) {
        throw new Exception('Invalid image data');
    }
    
    // Save temporary image
    $tempDir = sys_get_temp_dir();
    $tempFile = $tempDir . '/face_' . time() . '_' . uniqid() . '.jpg';
    
    if (file_put_contents($tempFile, $imageData) === false) {
        throw new Exception('Failed to save image');
    }
    
    // Detect faces using PHP-FaceDetector
    $detector = new FaceDetector($webphimRoot . '/Trang-admin/model/haarcascade_frontalface_default.xml');
    $detector->scan($tempFile);
    $faces = $detector->getFaces();
    
    // Clean up temp file
    @unlink($tempFile);
    
    // Check if exactly 1 face detected
    if (empty($faces) || count($faces) === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Không phát hiện khuôn mặt. Vui lòng chụp lại.',
            'faces_count' => 0
        ]);
        exit;
    }
    
    if (count($faces) > 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Phát hiện ' . count($faces) . ' khuôn mặt. Vui lòng chỉ chụp một người.',
            'faces_count' => count($faces)
        ]);
        exit;
    }
    
    // Get employee info
    $conn = pdo_get_connection();
    $stmt = $conn->prepare("
        SELECT id, name, email, trang_thai_lam_viec, id_rap
        FROM taikhoan 
        WHERE id = ? 
        LIMIT 1
    ");
    $stmt->execute([$nhanvien_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$employee) {
        throw new Exception('Không tìm thấy nhân viên');
    }
    
    // Check if employee is active
    if ($employee['trang_thai_lam_viec'] !== 'dang_lam') {
        throw new Exception('Tài khoản nhân viên không hoạt động');
    }
    
    // Check if already checked in today
    $today = date('Y-m-d');
    $stmt = $conn->prepare("
        SELECT id, gio_vao 
        FROM cham_cong 
        WHERE id_nv = ? 
        AND ngay = ? 
        AND gio_vao IS NOT NULL
        LIMIT 1
    ");
    $stmt->execute([$nhanvien_id, $today]);
    $existingCheckin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingCheckin) {
        echo json_encode([
            'success' => false,
            'message' => 'Bạn đã chấm công VÀO hôm nay lúc ' . $existingCheckin['gio_vao'],
            'already_checked_in' => true
        ]);
        exit;
    }
    
    // Check working schedule
    $stmt = $conn->prepare("
        SELECT id
        FROM lich_lam_viec 
        WHERE id_nhan_vien = ? 
        AND ngay = ?
        AND trang_thai = 'lich'
        LIMIT 1
    ");
    $stmt->execute([$nhanvien_id, $today]);
    $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$schedule) {
        echo json_encode([
            'success' => false,
            'message' => 'Bạn không có ca làm việc hôm nay'
        ]);
        exit;
    }
    
    // Create check-in record
    $stmt = $conn->prepare("
        INSERT INTO cham_cong (id_nv, id_rap, ngay, gio_vao, gio_ra, latitude, longitude, location_accuracy)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $rap_id = $employee['id_rap'] ?? null;
    $current_time = date('H:i:s');
    $latitude = $input['latitude'] ?? null;
    $longitude = $input['longitude'] ?? null;
    $accuracy = $input['location_accuracy'] ?? null;
    
    $stmt->execute([
        $nhanvien_id,
        $rap_id,
        $today,
        $current_time,
        $current_time,  // gio_ra = gio_vao initially (will be updated on checkout)
        $latitude,
        $longitude,
        $accuracy
    ]);
    
    // Return success
    echo json_encode([
        'success' => true,
        'message' => 'Chấm công VÀO thành công! Chào mừng ' . $employee['name'],
        'employee' => [
            'id' => $employee['id'],
            'name' => $employee['name'],
            'email' => $employee['email']
        ],
        'timestamp' => date('Y-m-d H:i:s'),
        'faces_detected' => 1,
        'face_coordinates' => $faces[0] ?? null
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>