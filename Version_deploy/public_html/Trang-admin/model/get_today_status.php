<?php
/**
 * Get Today's Attendance Status (for AJAX)
 */

session_start();

if (!function_exists('pdo_get_connection')) {
    include 'pdo.php';
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Lỗi',
    'status' => 'not_checked_in',
    'record' => null
];

try {
    // Get user ID from session
    $user_id = null;
    
    if (isset($_SESSION['user1']['id'])) {
        $user_id = $_SESSION['user1']['id'];
    }
    
    if (!$user_id) {
        throw new Exception('Không tìm thấy user_id');
    }
    
    // Get today's attendance record
    $conn = pdo_get_connection();
    $today = date('Y-m-d');
    
    $sql = "SELECT * FROM cham_cong WHERE id_nv = :user_id AND ngay = :today ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id, ':today' => $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        // Check if checked out
        $now = time();
        $gio_ra_timestamp = strtotime($today . ' ' . $record['gio_ra']);
        
        if ($gio_ra_timestamp > $now) {
            $response['status'] = 'checked_in';
        } else {
            $response['status'] = 'checked_out';
        }
        
        $response['record'] = $record;
    } else {
        $response['status'] = 'not_checked_in';
    }
    
    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>
