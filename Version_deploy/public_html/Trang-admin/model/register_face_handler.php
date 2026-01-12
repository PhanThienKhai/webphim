<?php
/**
 * Face Registration Handler
 * Receives base64 photo and fingerprint, registers face template
 */

session_start();

// Security check
if (!isset($_SESSION['user1']) || !isset($_SESSION['user1']['id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

try {
    // Include required files
    if (!function_exists('pdo_query_one')) {
        include 'pdo.php';
    }
    
    if (!function_exists('cc_register_face')) {
        include 'chamcong.php';
    }
    
    $user_id = $_SESSION['user1']['id'];
    $action = $_POST['action'] ?? null;
    $fingerprint = $_POST['fingerprint'] ?? null;
    $photo = $_POST['photo'] ?? null;
    
    if ($action !== 'register_face') {
        throw new Exception('Action không hợp lệ');
    }
    
    if (!$fingerprint) {
        throw new Exception('Không có dữ liệu khuôn mặt');
    }
    
    // Verify face using Haar Cascade if photo provided
    if ($photo) {
        $faceDetection = cc_detect_face_from_base64($photo);
        if (!$faceDetection['detected']) {
            throw new Exception($faceDetection['error']);
        }
        if ($faceDetection['face_count'] > 1) {
            throw new Exception('Phát hiện nhiều hơn 1 khuôn mặt. Vui lòng đăng ký một mình.');
        }
    }
    
    // Validate fingerprint is valid JSON
    $fp_data = json_decode($fingerprint, true);
    if (!$fp_data || !is_array($fp_data)) {
        throw new Exception('Dữ liệu khuôn mặt không hợp lệ');
    }
    
    // Register face
    $result = cc_register_face($user_id, $fingerprint);
    
    echo json_encode([
        'success' => true,
        'message' => $result['message'] ?? '✅ Đăng ký khuôn mặt thành công!'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?>
