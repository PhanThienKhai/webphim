<?php
/**
 * Chat API for In-rạp Messaging
 * Handles getting messages, sending messages, marking as read
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

if (!function_exists('pdo_get_connection')) {
    include 'pdo.php';
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$response = [
    'success' => false,
    'message' => 'Lỗi không xác định',
    'data' => []
];

try {
    // Kiểm tra session
    if (empty($_SESSION['user1']['id']) || empty($_SESSION['user1']['id_rap'])) {
        throw new Exception('Vui lòng đăng nhập lại');
    }
    
    // Get current user
    $user_id = (int)$_SESSION['user1']['id'];
    $user_rap = (int)$_SESSION['user1']['id_rap'];
    
    $action = trim($_POST['action'] ?? $_GET['action'] ?? '');
    
    if (!$action) {
        throw new Exception('Action không hợp lệ');
    }

    $conn = pdo_get_connection();

    // ============================================
    // GET MESSAGES (Lấy tin nhắn)
    // ============================================
    if ($action === 'get_messages') {
        $limit = (int)($_GET['limit'] ?? 20);
        $offset = (int)($_GET['offset'] ?? 0);
        
        // Lấy tin nhắn của rạp (broadcast hoặc gửi cho user này)
        $sql = "SELECT 
                    t.id,
                    t.id_nguoi_gui,
                    t.noi_dung,
                    t.thoi_gian,
                    t.da_doc,
                    tk.name as ten_nguoi_gui,
                    tk.img as avatar
                FROM tin_nhan t
                LEFT JOIN taikhoan tk ON t.id_nguoi_gui = tk.id
                WHERE t.id_rap = :id_rap 
                  AND (t.id_nguoi_nhan IS NULL OR t.id_nguoi_nhan = :user_id OR t.id_nguoi_gui = :user_id)
                ORDER BY t.thoi_gian DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_rap', $user_rap, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format thời gian
        foreach ($messages as &$msg) {
            $msg['thoi_gian_formatted'] = date('H:i', strtotime($msg['thoi_gian']));
            $msg['ngay'] = date('d/m', strtotime($msg['thoi_gian']));
            $msg['is_current_user'] = ($msg['id_nguoi_gui'] == $user_id);
        }
        
        $response['success'] = true;
        $response['data'] = array_reverse($messages); // Reverse để mới nhất ở dưới
        $response['count'] = count($messages);
    }

    // ============================================
    // SEND MESSAGE (Gửi tin nhắn)
    // ============================================
    elseif ($action === 'send_message') {
        $noi_dung = trim($_POST['noi_dung'] ?? '');
        $id_nguoi_nhan = (int)($_POST['id_nguoi_nhan'] ?? 0);
        
        if (empty($noi_dung)) {
            throw new Exception('Nội dung không được để trống');
        }
        
        if (strlen($noi_dung) > 5000) {
            throw new Exception('Nội dung quá dài (max 5000 ký tự)');
        }
        
        // Insert tin nhắn
        $sql = "INSERT INTO tin_nhan (id_rap, id_nguoi_gui, id_nguoi_nhan, noi_dung, thoi_gian, da_doc)
                VALUES (:id_rap, :id_nguoi_gui, :id_nguoi_nhan, :noi_dung, NOW(), 0)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_rap' => $user_rap,
            ':id_nguoi_gui' => $user_id,
            ':id_nguoi_nhan' => $id_nguoi_nhan > 0 ? $id_nguoi_nhan : null,
            ':noi_dung' => $noi_dung
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Gửi tin nhắn thành công';
        $response['data']['message_id'] = $conn->lastInsertId();
    }

    // ============================================
    // GET UNREAD COUNT (Đếm tin chưa đọc)
    // ============================================
    elseif ($action === 'get_unread_count') {
        $sql = "SELECT COUNT(*) as unread_count
                FROM tin_nhan
                WHERE id_rap = :id_rap 
                  AND (id_nguoi_nhan = :user_id OR id_nguoi_nhan IS NULL)
                  AND id_nguoi_gui != :user_id
                  AND da_doc = 0";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_rap' => $user_rap,
            ':user_id' => $user_id
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['success'] = true;
        $response['data']['unread_count'] = (int)$result['unread_count'];
    }

    // ============================================
    // MARK AS READ (Đánh dấu đã đọc)
    // ============================================
    elseif ($action === 'mark_as_read') {
        $sql = "UPDATE tin_nhan 
                SET da_doc = 1
                WHERE id_rap = :id_rap 
                  AND (id_nguoi_nhan = :user_id OR id_nguoi_nhan IS NULL)
                  AND da_doc = 0";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_rap' => $user_rap,
            ':user_id' => $user_id
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Đánh dấu đã đọc thành công';
    }

    else {
        throw new Exception('Action không được hỗ trợ');
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
} catch (Throwable $e) {
    $response['success'] = false;
    $response['message'] = 'Lỗi hệ thống: ' . $e->getMessage();
    http_response_code(500);
}

// Đảm bảo output là JSON sạch
ob_clean();
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;
?>
