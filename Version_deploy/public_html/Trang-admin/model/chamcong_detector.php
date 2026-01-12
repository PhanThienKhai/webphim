<?php
/**
 * Face Detection API for Employee Attendance
 * Receives base64 image data, detects faces, saves attendance record
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');

session_start();

if (!function_exists('pdo_get_connection')) {
    include 'pdo.php';
}

if (!defined('ROLES')) {
    include '../helpers/quyen.php';
}

// Include attendance functions (for face verification with Haar Cascade)
if (!function_exists('cc_verify_face_strict')) {
    include 'chamcong.php';
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
    $fingerprint_data = $_POST['fingerprint_' . $action] ?? null; // fingerprint_checkin hoặc fingerprint_checkout
    
    // Get GPS data (optional)
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $location_accuracy = isset($_POST['location_accuracy']) ? (float)$_POST['location_accuracy'] : null;
    
    if (!$action || !in_array($action, ['checkin', 'checkout'])) {
        throw new Exception('Action không hợp lệ (checkin/checkout)');
    }
    
    if (!$photo_data) {
        throw new Exception('Không có dữ liệu ảnh');
    }
    
    if (!$fingerprint_data) {
        $response['debug']['post_keys'] = array_keys($_POST);
        $response['debug']['expected_key'] = 'fingerprint_' . $action;
        throw new Exception('Không có dữ liệu khuôn mặt (fingerprint)');
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
    
    // BƯỚC 1: Verify face sử dụng Haar Cascade + Fingerprint FIRST
    // cc_verify_face_strict() sẽ:
    // - Detect faces trong ảnh (Haar Cascade) - OPTIONAL, nếu fail thì skip
    // - So sánh fingerprint với template đã đăng ký (MAIN VERIFICATION)
    try {
        // Try Haar Cascade, but don't fail if it can't detect
        $haar_result = null;
        try {
            $haar_result = cc_detect_face_from_base64($photo_data);
            if (!$haar_result['detected']) {
                // Log warning but continue with fingerprint verification
                $response['debug']['haar_status'] = 'NOT_DETECTED: ' . $haar_result['error'];
            } else {
                $response['debug']['haar_status'] = 'DETECTED: ' . $haar_result['face_count'] . ' face(s)';
                if ($haar_result['face_count'] > 1) {
                    throw new Exception('❌ Phát hiện nhiều hơn 1 khuôn mặt. Vui lòng chấm công một mình.');
                }
            }
        } catch (Exception $e) {
            // If Haar Cascade fails, just warn but continue
            $response['debug']['haar_error'] = $e->getMessage();
            $response['debug']['haar_status'] = 'ERROR_SKIPPED';
        }
        
        // MAIN: Verify fingerprint (40% similarity required)
        $verification_result = cc_verify_face_strict($user_id, $fingerprint_data, $photo_data);
        $response['face_verification'] = $verification_result;
    } catch (Exception $e) {
        throw new Exception('❌ Xác minh khuôn mặt thất bại: ' . $e->getMessage());
    }
    
    // BƯỚC 2: Only save image AFTER verification succeeds
    $timestamp = date('Y-m-d_H-i-s');
    $photo_filename = "employee_{$user_id}_{$action}_{$timestamp}.jpg";
    $photo_file = $temp_dir . $photo_filename;
    $bytes_written = file_put_contents($photo_file, $image_binary);
    
    if ($bytes_written === false) {
        throw new Exception('Không thể lưu ảnh');
    }
    
    // Store relative path in database (easier to access)
    $photo_relative_path = '/webphim/Trang-admin/assets/temp_photos/' . $photo_filename;
    
    // Save attendance record to database
    $conn = pdo_get_connection();
    $today = date('Y-m-d');
    $now = date('H:i:s');
    
    // Get id_rap from taikhoan table (get employee's assigned cinema)
    $employee_sql = "SELECT id_rap FROM taikhoan WHERE id = :user_id LIMIT 1";
    $stmt_emp = $conn->prepare($employee_sql);
    $stmt_emp->execute([':user_id' => $user_id]);
    $employee = $stmt_emp->fetch(PDO::FETCH_ASSOC);
    
    if (!$employee || !$employee['id_rap']) {
        throw new Exception('Không tìm thấy thông tin rạp của nhân viên');
    }
    
    $rap_id = $employee['id_rap'];
    $response['debug']['rap_id_from_db'] = $rap_id;
    
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
            $update_sql = "UPDATE cham_cong SET gio_vao = :gio_vao, anh_vao = :photo_path, fingerprint_vao = :fingerprint, latitude = :latitude, longitude = :longitude, location_accuracy = :location_accuracy WHERE id = :id";
            $stmt = $conn->prepare($update_sql);
            $stmt->execute([
                ':gio_vao' => $now,
                ':photo_path' => $photo_relative_path,
                ':fingerprint' => $fingerprint_data,
                ':latitude' => $latitude,
                ':longitude' => $longitude,
                ':location_accuracy' => $location_accuracy,
                ':id' => $record['id']
            ]);
            $response['success'] = true;
            $response['message'] = 'Check-in thành công lúc ' . $now;
        } else {
            // Create new record with correct rap_id from employee's assigned cinema
            $insert_sql = "INSERT INTO cham_cong (id_nv, id_rap, ngay, gio_vao, anh_vao, fingerprint_vao, latitude, longitude, location_accuracy) 
                          VALUES (:user_id, :id_rap, :ngay, :gio_vao, :photo_path, :fingerprint, :latitude, :longitude, :location_accuracy)";
            $stmt = $conn->prepare($insert_sql);
            
            $stmt->execute([
                ':user_id' => $user_id,
                ':id_rap' => $rap_id,
                ':ngay' => $today,
                ':gio_vao' => $now,
                ':photo_path' => $photo_relative_path,
                ':fingerprint' => $fingerprint_data,
                ':latitude' => $latitude,
                ':longitude' => $longitude,
                ':location_accuracy' => $location_accuracy
            ]);
            $response['success'] = true;
            $response['message'] = 'Check-in thành công lúc ' . $now;
        }
    } else if ($action === 'checkout') {
        if (!$record) {
            throw new Exception('Bạn chưa check-in hôm nay');
        }
        
        // Update check-out time
        $update_sql = "UPDATE cham_cong SET gio_ra = :gio_ra, anh_ra = :photo_path, fingerprint_ra = :fingerprint, latitude = :latitude, longitude = :longitude, location_accuracy = :location_accuracy WHERE id = :id";
        $stmt = $conn->prepare($update_sql);
        $stmt->execute([
            ':gio_ra' => $now,
            ':photo_path' => $photo_relative_path,
            ':fingerprint' => $fingerprint_data,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':location_accuracy' => $location_accuracy,
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
