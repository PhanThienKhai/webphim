<?php
/**
 * Face Detection API for Employee Attendance
 * Receives base64 image data, detects faces, saves attendance record
 */

session_start();

if (!function_exists('pdo_get_connection')) {
    include 'pdo.php';
}

if (!defined('ROLES')) {
    include '../helpers/quyen.php';
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Lỗi chưa xác định',
    'faces_detected' => 0,
    'face_data' => [],
    'debug' => [] // For debugging
];

try {
    // Get POST data
    $action = $_POST['action'] ?? null;
    $photo_data = $_POST['photo'] ?? null;
    
    if (!$action || !in_array($action, ['checkin', 'checkout'])) {
        throw new Exception('Action không hợp lệ (checkin/checkout)');
    }
    
    if (!$photo_data) {
        throw new Exception('Không có dữ liệu ảnh');
    }
    
    // Get current user ID from session (check multiple possible keys)
    $user_id = null;
    
    // Try from POST first (for test page)
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    }
    
    // Try common session keys
    if (!$user_id && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } elseif (!$user_id && isset($_SESSION['id_nv'])) {
        $user_id = $_SESSION['id_nv'];
    } elseif (!$user_id && isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } elseif (!$user_id && isset($_SESSION['nhan_vien_id'])) {
        $user_id = $_SESSION['nhan_vien_id'];
    }
    
    if (!$user_id) {
        $response['debug']['session_data'] = array_keys($_SESSION);
        throw new Exception('Không tìm thấy user_id. Vui lòng truy cập qua admin hoặc truyền user_id.');
    }
    
    $response['debug']['user_id'] = $user_id;
    
    // Create temp directory for photos if not exists
    $temp_dir = dirname(__DIR__) . '/assets/temp_photos/';
    
    // Create directory if not exists
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0755, true);
    }
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0777, true);
    }
    
    // Decode base64 image
    if (strpos($photo_data, 'data:image') === 0) {
        // Remove data:image/jpeg;base64, prefix
        $photo_data = substr($photo_data, strpos($photo_data, ',') + 1);
    }
    
    $image_binary = base64_decode($photo_data, true);
    if ($image_binary === false) {
        throw new Exception('Dữ liệu ảnh không hợp lệ');
    }
    
    // Save image with timestamp
    $timestamp = date('Y-m-d_H-i-s');
    $photo_filename = "employee_{$user_id}_{$action}_{$timestamp}.jpg";
    $photo_file = $temp_dir . $photo_filename;
    $bytes_written = file_put_contents($photo_file, $image_binary);
    
    if ($bytes_written === false) {
        throw new Exception('Không thể lưu ảnh');
    }
    
    // Store relative path in database (easier to access)
    $photo_relative_path = '/webphim/Trang-admin/assets/temp_photos/' . $photo_filename;
    
    // Detect faces using FaceDetector
    require_once 'FaceDetector.php';
    
    $detector = new FaceDetector();
    $detector->scan($photo_file);
    $faces = $detector->getFaces();
    
    $response['faces_detected'] = count($faces);
    $response['face_data'] = $faces;
    
    // Require at least 1 face detected
    if (count($faces) === 0) {
        throw new Exception('Không phát hiện được khuôn mặt. Vui lòng chụp lại ảnh.');
    }
    
    // Save attendance record to database
    $conn = pdo_get_connection();
    $today = date('Y-m-d');
    $now = date('H:i:s');
    
    // Get current attendance record for today
    // Note: Query should only check id_nv, not id_rap, to be consistent with how cc_check_today_status works
    $check_sql = "SELECT id, gio_vao, gio_ra FROM cham_cong WHERE id_nv = :user_id AND ngay = :today ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($check_sql);
    $stmt->execute([':user_id' => $user_id, ':today' => $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($action === 'checkin') {
        if ($record && $record['gio_vao']) {
            // Already checked in
            $response['success'] = true;
            $response['message'] = 'Bạn đã check-in hôm nay lúc ' . $record['gio_vao'];
        } else if ($record) {
            // Update existing record
            $update_sql = "UPDATE cham_cong SET gio_vao = :gio_vao, anh_vao = :photo_path WHERE id = :id";
            $stmt = $conn->prepare($update_sql);
            $stmt->execute([
                ':gio_vao' => $now,
                ':photo_path' => $photo_relative_path,
                ':id' => $record['id']
            ]);
            $response['success'] = true;
            $response['message'] = 'Check-in thành công lúc ' . $now;
        } else {
            // Create new record
            $insert_sql = "INSERT INTO cham_cong (id_nv, id_rap, ngay, gio_vao, anh_vao) 
                          VALUES (:user_id, :id_rap, :ngay, :gio_vao, :photo_path)";
            $stmt = $conn->prepare($insert_sql);
            
            // Get rap_id from session or default to 1
            $rap_id = $_SESSION['rap_id'] ?? 1;
            
            $stmt->execute([
                ':user_id' => $user_id,
                ':id_rap' => $rap_id,
                ':ngay' => $today,
                ':gio_vao' => $now,
                ':photo_path' => $photo_relative_path
            ]);
            $response['success'] = true;
            $response['message'] = 'Check-in thành công lúc ' . $now;
        }
    } else if ($action === 'checkout') {
        if (!$record) {
            throw new Exception('Bạn chưa check-in hôm nay');
        }
        
        // Update check-out time
        $update_sql = "UPDATE cham_cong SET gio_ra = :gio_ra, anh_ra = :photo_path WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        $stmt->execute([
            ':gio_ra' => $now,
            ':photo_path' => $photo_relative_path,
            ':id' => $record['id']
        ]);
        $response['success'] = true;
        $response['message'] = 'Check-out thành công lúc ' . $now;
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;
?>
