<?php
/**
 * CONFIRM_PAYMENT - Create tickets immediately from session
 * POST /sepay/confirm_payment.php
 * 
 * Request: {amount, trans_id}
 * Response: {success, redirect_url, error}
 * 
 * SIMPLE: Just read from session and create tickets
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }
    
    $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
    $trans_id = $data['trans_id'] ?? '';
    $booking_from_request = $data['booking'] ?? [];
    
    error_log("CONFIRM_PAYMENT: Received JSON - amount=$amount, trans_id=$trans_id");
    error_log("CONFIRM_PAYMENT: Full request data: " . json_encode($data));
    error_log("CONFIRM_PAYMENT: Booking from request: " . json_encode($booking_from_request));
    error_log("CONFIRM_PAYMENT: Session keys: " . json_encode(array_keys($_SESSION)));
    
    if ($amount <= 0 || empty($trans_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid amount or trans_id']);
        exit;
    }
    
    // Try to get booking data from REQUEST first, then SESSION as fallback
    $tong = !empty($booking_from_request) ? $booking_from_request : ($_SESSION['tong'] ?? []);
    
    if (empty($tong)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No booking data (neither in request nor session)']);
        exit;
    }
    
    $user_id = $tong['user_id'] ?? $_SESSION['user']['id'] ?? $_SESSION['id_tk'] ?? 0;
    
    error_log("CONFIRM_PAYMENT: Session tong keys: " . json_encode(array_keys($tong)));
    error_log("CONFIRM_PAYMENT: user_id=$user_id");
    
    // Extract ALL fields from booking data
    $id_phim = (int)($tong['id_phim'] ?? 0);
    $id_rap = (int)($tong['id_rap'] ?? 0);
    // IMPORTANT: Session uses 'id_lichchieu' NOT 'id_lich_chieu'!
    $id_lich_chieu = (int)($tong['id_lichchieu'] ?? $tong['id_lich_chieu'] ?? 0);
    // For id_ngay_chieu: will be set by webhook or default to 0
    $id_ngay_chieu = (int)($tong['id_ngay_chieu'] ?? 0);
    
    // CRITICAL: Get seat list from booking data
    $ten_ghe = $tong['ten_ghe'] ?? [];
    $ghe_list = $ten_ghe['ghe'] ?? [];
    if (!is_array($ghe_list)) {
        $ghe_list = [];
    }
    
    error_log("CONFIRM_PAYMENT: Extracted - phim=$id_phim, rap=$id_rap, lich=$id_lich_chieu, ghe_count=" . count($ghe_list));
    
    // Validate
    if (!$id_phim || !$id_rap || !$id_lich_chieu || empty($ghe_list)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Incomplete booking',
            'debug' => [
                'phim' => $id_phim,
                'rap' => $id_rap,
                'lich' => $id_lich_chieu,
                'ghe_count' => count($ghe_list),
                'tong_keys' => array_keys($tong),
                'ten_ghe' => $ten_ghe
            ]
        ]);
        exit;
    }
    
    // Get combo from booking data
    $combo_str = '';
    
    // Try multiple ways to get combo data
    if (isset($tong['ten_doan']['doan']) && is_array($tong['ten_doan']['doan'])) {
        // Format: ['cơm ga', 'nước ngọt']
        $combo_str = implode(', ', $tong['ten_doan']['doan']);
    } elseif (isset($tong['combo']) && !empty($tong['combo'])) {
        // Format: 'cơm ga, nước ngọt' (already a string)
        $combo_str = $tong['combo'];
    }
    
    error_log("CONFIRM_PAYMENT: combo_str='$combo_str'");
    error_log("CONFIRM_PAYMENT: ten_doan=" . json_encode($tong['ten_doan'] ?? []));
    
    // Connect DB
    require('config.php');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'DB connection error: ' . $e->getMessage()]);
        exit;
    }
    
    // Create 1 ticket per seat
    // IMPORTANT: Create tickets with trang_thai='chua_thanh_toan' (unpaid), webhook will update to 'da_thanh_toan' (paid)
    $ticket_ids = [];
    $created_count = 0;
    
    try {
        foreach ($ghe_list as $ghe_name) {
            $sql = "INSERT INTO ve (id_phim, id_rap, id_thoi_gian_chieu, id_ngay_chieu, id_tk, ghe, combo, price, trang_thai, ngay_dat) 
                    VALUES (:id_phim, :id_rap, :id_lich_chieu, :id_ngay_chieu, :id_tk, :ghe, :combo, :price, 'chua_thanh_toan', NOW())";
            
            $stmt = $pdo->prepare($sql);
            
            // Use NULL for id_tk if user_id is 0 (guest booking)
            $id_tk_value = ($user_id > 0) ? $user_id : null;
            
            $result = $stmt->execute([
                ':id_phim' => $id_phim,
                ':id_rap' => $id_rap,
                ':id_lich_chieu' => $id_lich_chieu,
                ':id_ngay_chieu' => $id_ngay_chieu,
                ':id_tk' => $id_tk_value,
                ':ghe' => $ghe_name,
                ':combo' => $combo_str,
                ':price' => $amount
            ]);
            
            if ($result) {
                $ticket_id = $pdo->lastInsertId();
                $ticket_ids[] = $ticket_id;
                $created_count++;
                error_log("CONFIRM_PAYMENT: Created ticket=$ticket_id, seat=$ghe_name, user_id=$user_id, status=UNPAID");
            } else {
                error_log("CONFIRM_PAYMENT: Failed to create ticket for seat=$ghe_name");
                throw new Exception("Failed to create ticket for seat: $ghe_name");
            }
        }
        
        if ($created_count == 0) {
            throw new Exception("No tickets were created");
        }
        
        error_log("CONFIRM_PAYMENT: Successfully created $created_count tickets");
        
    } catch (Exception $e) {
        http_response_code(500);
        error_log("CONFIRM_PAYMENT: Error creating tickets - " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create tickets: ' . $e->getMessage(),
            'created' => $created_count
        ]);
        exit;
    }
    
    // Add points only if user is logged in
    if ($user_id > 0 && $amount > 0) {
        try {
            $points = floor($amount * 0.01);
            if ($points > 0) {
                $pdo->prepare("UPDATE taikhoan SET diem_tich_luy = COALESCE(diem_tich_luy, 0) + :pts WHERE id = :id")
                    ->execute([':pts' => $points, ':id' => $user_id]);
                error_log("CONFIRM_PAYMENT: Added $points points to user=$user_id");
            }
        } catch (Exception $e) {
            error_log("CONFIRM_PAYMENT: Warning - could not add points: " . $e->getMessage());
            // Don't fail the whole transaction just because points failed
        }
    }
    
    // Clear session
    unset($_SESSION['tong']);
    
    echo json_encode([
        'success' => true,
        'ticket_ids' => $ticket_ids,
        'first_ticket_id' => $ticket_ids[0] ?? 0,
        'message' => 'Vé đã được tạo. Vui lòng chuyển khoản để xác nhận thanh toán.',
        'redirect_url' => '/Trang-nguoi-dung/index.php?act=xacnhan'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    error_log("CONFIRM_PAYMENT ERROR: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
