<?php
/**
 * Manual Payment Confirmation - Xác nhận thanh toán và tạo vé
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['amount'])) {
        echo json_encode(['success' => false, 'message' => 'Missing amount parameter']);
        exit;
    }
    
    $amount = (int)$data['amount'];
    $user_id = $_SESSION['user']['id'] ?? $_SESSION['id_tk'] ?? null;
    
    error_log("MANUAL_CONFIRM: amount=$amount, user=$user_id");
    
    // Kiểm tra session booking data
    if (empty($_SESSION['tong'])) {
        echo json_encode(['success' => false, 'message' => 'No booking session']);
        exit;
    }
    
    // Connect DB
    require('config.php');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $db_error) {
        error_log("DB CONNECTION ERROR: " . $db_error->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $db_error->getMessage()]);
        exit;
    }
    
    // Lấy session booking data
    $session_tong = $_SESSION['tong'];
    $id_phim = (int)($session_tong['id_phim'] ?? 0);
    $id_rap = (int)($session_tong['id_rap'] ?? 0);
    
    // IMPORTANT: Session uses 'id_lich_chieu', but table uses 'id_thoi_gian_chieu'
    // They refer to the same thing (show schedule)
    $id_lich_chieu = (int)($session_tong['id_lich_chieu'] ?? 0);
    $id_ngay_chieu = (int)($session_tong['ngay_chieu'] ?? 0);
    $ten_ghe_arr = $session_tong['ten_ghe']['ghe'] ?? [];
    $combo_str = '';
    
    if (isset($session_tong['ten_doan']['doan'])) {
        $combo_str = implode(', ', $session_tong['ten_doan']['doan']);
    }
    
    if (!$id_phim || !$id_rap || !$id_lich_chieu) {
        echo json_encode(['success' => false, 'message' => 'Missing booking: id_phim, id_rap, id_lich_chieu']);
        exit;
    }
    
    if (empty($ten_ghe_arr)) {
        echo json_encode(['success' => false, 'message' => 'No seats selected']);
        exit;
    }
    
    // Tạo vé từ session data
    $ticket_ids = [];
    
    foreach ($ten_ghe_arr as $ghe_name) {
        // INSERT vé: dùng đúng field name 'id_thoi_gian_chieu'
        $insert_sql = "INSERT INTO ve (id_phim, id_rap, id_thoi_gian_chieu, id_ngay_chieu, id_tk, ghe, combo, price, trang_thai, ngay_dat) 
                      VALUES (:id_phim, :id_rap, :id_thoi_gian_chieu, :id_ngay_chieu, :id_tk, :ghe, :combo, :price, 1, NOW())";
        
        $stmt = $pdo->prepare($insert_sql);
        $stmt->execute([
            ':id_phim' => $id_phim,
            ':id_rap' => $id_rap,
            ':id_thoi_gian_chieu' => $id_lich_chieu,  // From session id_lich_chieu
            ':id_ngay_chieu' => $id_ngay_chieu,
            ':id_tk' => $user_id,                      // User ID, NOT user_id
            ':ghe' => $ghe_name,
            ':combo' => $combo_str,
            ':price' => $amount
        ]);
        
        $new_ticket_id = $pdo->lastInsertId();
        $ticket_ids[] = $new_ticket_id;
        error_log("MANUAL_CONFIRM: Created ticket ID=$new_ticket_id, seat=$ghe_name");
    }
    
    // Tích điểm: dùng taikhoan.diem_tich_luy (field name chính xác)
    if ($user_id && $user_id > 0 && $amount > 0) {
        $points = floor($amount * 0.01);
        if ($points > 0) {
            $pdo->prepare("UPDATE taikhoan SET diem_tich_luy = COALESCE(diem_tich_luy, 0) + :points WHERE id = :id")
                ->execute([':points' => $points, ':id' => $user_id]);
            error_log("MANUAL_CONFIRM: Points added - user=$user_id, points=$points");
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Tickets created successfully',
        'ticket_ids' => $ticket_ids,
        'primary_ticket_id' => $ticket_ids[0] ?? null,
        'status' => 'paid'
    ]);
    
} catch (Exception $e) {
    error_log("MANUAL_CONFIRM ERROR: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
