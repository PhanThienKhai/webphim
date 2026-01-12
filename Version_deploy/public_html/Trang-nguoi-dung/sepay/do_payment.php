<?php
/**
 * DO_PAYMENT - Validate session + prepare payment
 * POST /sepay/do_payment.php
 * 
 * Request: {amount}
 * Response: {success, redirect_url, error}
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Parse JSON request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }
    
    // Validate amount
    $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
    if ($amount <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid amount']);
        exit;
    }
    
    // Check booking session exists
    if (empty($_SESSION['tong'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No booking session']);
        exit;
    }
    
    // Extract booking data from session
    $user_id = $_SESSION['user']['id'] ?? $_SESSION['id_tk'] ?? 0;
    $session_tong = $_SESSION['tong'];
    $id_phim = (int)($session_tong['id_phim'] ?? 0);
    $id_rap = (int)($session_tong['id_rap'] ?? 0);
    // IMPORTANT: Session uses 'id_lichchieu' - use for both id_thoi_gian_chieu and id_ngay_chieu
    $id_lich_chieu = (int)($session_tong['id_lichchieu'] ?? 0);
    $id_ngay_chieu = $id_lich_chieu;
    
    // CRITICAL: Lấy id_thoi_gian_chieu từ session
    // Session có thể chứa: 'id_gio' hoặc cần lấy từ khung_gio_chieu
    $id_thoi_gian_chieu = (int)($session_tong['id_gio'] ?? $session_tong['id_thoi_gian_chieu'] ?? 0);
    
    // Nếu không có id_thoi_gian_chieu, lấy từ database (khung_gio_chieu đầu tiên của lichchieu này)
    if ($id_thoi_gian_chieu <= 0 && $id_lich_chieu > 0) {
        require_once(__DIR__ . '/config.php');
        try {
            $pdo_temp = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $stmt_temp = $pdo_temp->prepare("SELECT id FROM khung_gio_chieu WHERE id_lich_chieu = ? LIMIT 1");
            $stmt_temp->execute([$id_lich_chieu]);
            $row_temp = $stmt_temp->fetch();
            if ($row_temp) {
                $id_thoi_gian_chieu = (int)$row_temp['id'];
            }
        } catch (Exception $e) {
            error_log("DO_PAYMENT: Could not fetch id_thoi_gian_chieu: " . $e->getMessage());
        }
    }
    
    // Get seat array - handle both nested and flat structures
    $ten_ghe_arr = [];
    if (isset($session_tong['ten_ghe'])) {
        if (is_array($session_tong['ten_ghe'])) {
            // Could be: ['ghe'] => [list] or just [list]
            $ten_ghe_arr = isset($session_tong['ten_ghe']['ghe']) ? $session_tong['ten_ghe']['ghe'] : $session_tong['ten_ghe'];
        }
    }
    
    // Get combo array
    $combo_data = [];
    if (isset($session_tong['ten_doan'])) {
        if (is_array($session_tong['ten_doan'])) {
            $combo_data = isset($session_tong['ten_doan']['doan']) ? $session_tong['ten_doan']['doan'] : $session_tong['ten_doan'];
        }
    }
    
    error_log("DO_PAYMENT: Session data - phim=$id_phim, rap=$id_rap, lich=$id_lich_chieu, ghe_count=" . count($ten_ghe_arr) . ", combo_count=" . count($combo_data));
    
    // Validate booking - allow incomplete (test mode)
    // ⚠️ TODO: Fix session to always include id_lichchieu
    if (!$id_phim || !$id_rap || empty($ten_ghe_arr)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Incomplete booking - missing phim/rap/ghe']);
        exit;
    }
    
    if ($id_lich_chieu <= 0) {
        error_log("DO_PAYMENT WARNING: id_lich_chieu is 0 or missing - will set to 0");
        // For now, allow 0 value - webhook will handle
    }
    
    // ============ TẠO VÉ TRONG DATABASE NGAY (TRƯỚC KHI HIỂN THỊ QR) ============
    // Similar to MoMo: Create unpaid tickets immediately
    require_once(__DIR__ . '/config.php');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // ============ TẠO HÓA ĐƠN TRƯỚC (tương tự MoMo) ============
        $ngay_tt = date('Y-m-d H:i:s');
        $sql_hd = "INSERT INTO hoa_don (id, ngay_tt, thanh_tien) VALUES (NULL, :ngay_tt, :thanh_tien)";
        $stmt_hd = $pdo->prepare($sql_hd);
        $stmt_hd->execute([
            ':ngay_tt' => $ngay_tt,
            ':thanh_tien' => $amount
        ]);
        $id_hd = $pdo->lastInsertId();
        error_log("DO_PAYMENT: Created hoa_don ID=$id_hd, amount=$amount");
        
        // Get combo string from session
        $combo_str = '';
        if (!empty($combo_data) && is_array($combo_data)) {
            $combo_str = implode(', ', $combo_data);
        } elseif (isset($session_tong['combo']) && !empty($session_tong['combo'])) {
            $combo_str = $session_tong['combo'];
        }
        
        // Merge all seats into one string (like MoMo)
        $ghe_string = implode(',', $ten_ghe_arr);
        
        // Create ONE ticket for all seats (matching DB schema expectation)
        $ma_ve = 'SEPAY_' . date('dmY') . '_' . time();
        
        // Column order MUST match execute() order!
        $sql = "INSERT INTO ve (id_tk, id_phim, id_rap, id_thoi_gian_chieu, id_ngay_chieu, ghe, price, combo, trang_thai, ma_ve, ngay_dat, id_hd) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW(), ?)";
        
        $stmt = $pdo->prepare($sql);
        $id_tk_value = ($user_id > 0) ? $user_id : null;
        
        $result = $stmt->execute([
            $id_tk_value,           // id_tk (NULL if guest)
            $id_phim,               // id_phim
            $id_rap,                // id_rap
            $id_thoi_gian_chieu,    // id_thoi_gian_chieu (NOT id_lich_chieu!)
            $id_ngay_chieu,         // id_ngay_chieu
            $ghe_string,            // ghe (comma-separated)
            $amount,                // price
            $combo_str,             // combo
            $ma_ve,                 // ma_ve
            $id_hd                  // id_hd (link to hoa_don)
        ]);
        
        if (!$result) {
            $error_info = $stmt->errorInfo();
            throw new Exception("Failed to create ticket: " . $error_info[2]);
        }
        
        $id = $pdo->lastInsertId();
        
        // ============ UPDATE ma_ve WITH ID ============
        // QR code format: VE[id] (e.g., VE551)
        $qr_content = 'VE' . $id;
        $update_ma_ve = "UPDATE ve SET ma_ve = :ma_ve WHERE id = :ve_id";
        $stmt_update = $pdo->prepare($update_ma_ve);
        $stmt_update->execute([
            ':ma_ve' => $qr_content,
            ':ve_id' => $id
        ]);
        
        error_log("DO_PAYMENT: Created unpaid ve ID=$id, QR_content=$qr_content, user=$user_id, amount=$amount, id_hd=$id_hd");
        
    } catch (Exception $e) {
        http_response_code(500);
        error_log("DO_PAYMENT DB ERROR: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Failed to create ticket: ' . $e->getMessage()]);
        exit;
    }
    
    // Generate transaction ID
    $trans_id = $user_id . time();
    
    // Prepare booking data to pass to payment UI
    // IMPORTANT: Format must match what confirm_payment.php expects
    $booking_data = [
        'id_phim' => $id_phim,
        'id_rap' => $id_rap,
        'id_lichchieu' => $id_lich_chieu,  // Pass as id_lichchieu for consistency
        'id_ngay_chieu' => $id_ngay_chieu,
        'user_id' => $user_id,
        'ten_ghe' => [
            'ghe' => $ten_ghe_arr  // Nested format for compatibility
        ],
        'ten_doan' => [
            'doan' => $combo_data  // Nested format for compatibility
        ]
    ];
    
    error_log("DO_PAYMENT: Preparing booking_data: " . json_encode($booking_data));
    
    // Encode booking data to pass to payment UI
    $booking_encoded = base64_encode(json_encode($booking_data));
    
    // Success - redirect to payment UI with encoded booking data AND id
    echo json_encode([
        'success' => true,
        'id' => $id,
        'redirect_url' => '/Trang-nguoi-dung/sepay/sepay_payment_ui.php?amount=' . $amount . '&trans_id=' . $trans_id . '&id=' . $id . '&booking=' . urlencode($booking_encoded)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    error_log("DO_PAYMENT ERROR: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
