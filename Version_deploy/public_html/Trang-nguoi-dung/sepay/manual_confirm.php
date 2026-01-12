<?php
/**
 * Manual Payment Confirmation - With Webhook Verification
 * Check webhook logs để verify transfer TRƯỚC khi confirm
 * POST /sepay/manual_confirm.php
 * 
 * Request: {ve_id, amount}
 * Checks: webhook_logs.txt for matching transfer with VE[id] + amount
 */

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }
    
    $ve_id = isset($data['ve_id']) ? (int)$data['ve_id'] : 0;
    $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
    
    error_log("MANUAL_CONFIRM: ve_id=$ve_id, amount=$amount - START VERIFICATION");
    
    // Connect to database
    require('config.php');
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // ⚠️ SET TIMEZONE CHO MYSQL CÙNG
        $pdo->exec("SET time_zone = '+07:00'");
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB connection error']);
        exit;
    }
    
    // If no ve_id provided, find by amount (most recent SEPAY ve with this amount that is unpaid)
    if ($ve_id <= 0 && $amount > 0) {
        $search_sql = "SELECT id FROM ve WHERE price = :amount AND trang_thai IN (0, 'chua_thanh_toan') AND ma_ve LIKE 'VE%' ORDER BY id DESC LIMIT 1";
        $stmt = $pdo->prepare($search_sql);
        $stmt->execute([':amount' => $amount]);
        $found = $stmt->fetch();
        
        if ($found) {
            $ve_id = (int)$found['id'];
            error_log("MANUAL_CONFIRM: Found ve by amount - ID=$ve_id");
        }
    }
    
    if ($ve_id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ve not found']);
        exit;
    }
    
    // ========== VERIFY TRANSFER FROM WEBHOOK LOGS ==========
    $webhook_log_file = __DIR__ . '/webhook_logs.txt';
    $transfer_found = false;
    $transfer_data = null;
    
    if (file_exists($webhook_log_file)) {
        $logs = file($webhook_log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        // Tìm transfer với VE[ve_id] + amount trong logs (từ mới nhất)
        for ($i = count($logs) - 1; $i >= 0; $i--) {
            $log_line = $logs[$i];
            
            // Parse log: "2025-12-09 14:30:45 Webhook received: {...JSON...}"
            if (strpos($log_line, 'Webhook received:') !== false) {
                // Extract JSON data
                $json_start = strpos($log_line, '{');
                if ($json_start !== false) {
                    $json_str = substr($log_line, $json_start);
                    $webhook = json_decode($json_str, true);
                    
                    if ($webhook) {
                        $transfer_amount = (int)($webhook['transferAmount'] ?? 0);
                        $transfer_content = $webhook['content'] ?? '';
                        $transfer_type = $webhook['transferType'] ?? '';
                        
                        // Check: type = 'in' AND amount matches AND content contains VE[ve_id]
                        if ($transfer_type === 'in' && 
                            $transfer_amount === $amount && 
                            stripos($transfer_content, "VE$ve_id") !== false) {
                            
                            $transfer_found = true;
                            $transfer_data = $webhook;
                            error_log("MANUAL_CONFIRM: ✅ TRANSFER VERIFIED - VE$ve_id + $amount found in webhook");
                            break;
                        }
                    }
                }
            }
        }
    }
    
    // If transfer not found in logs, return error
    if (!$transfer_found) {
        http_response_code(402); // Payment Required
        echo json_encode([
            'success' => false, 
            'message' => '❌ Chưa nhận transfer. Vui lòng chuyển khoản với mã VE' . $ve_id . ' và số tiền ' . number_format($amount) . ' VND',
            've_id' => $ve_id,
            'expected_amount' => $amount,
            'expected_content' => 'VE' . $ve_id
        ]);
        error_log("MANUAL_CONFIRM: ❌ TRANSFER NOT FOUND - VE$ve_id");
        exit;
    }
    
    // ========== TRANSFER VERIFIED! PROCEED WITH CONFIRMATION ==========
    
    // Check if ticket exists and is unpaid
    $check_sql = "SELECT id, trang_thai, price, ma_ve, id_tk, id_phim, id_rap, ghe, id_hd FROM ve WHERE id = :id";
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute([':id' => $ve_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Vé không tìm thấy']);
        exit;
    }
    
    // Check if already paid
    if (in_array($ticket['trang_thai'], ['da_thanh_toan', 'paid', 1])) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Vé đã được xác nhận thanh toán',
            'already_paid' => true,
            've_id' => $ve_id
        ]);
        exit;
    }
    
    // ========== UPDATE TICKET TO PAID ==========
    try {
        // Update ticket status to PAID (1 = da_thanh_toan)
        $update_sql = "UPDATE ve SET trang_thai = 1 WHERE id = :id";
        $stmt = $pdo->prepare($update_sql);
        $result = $stmt->execute([':id' => $ve_id]);
        
        if (!$result) {
            throw new Exception('Failed to update ticket status');
        }
        
            error_log("MANUAL_CONFIRM: ✅ Vé $ve_id xác nhận thành công - transfer verified");
            
            // ========== ENSURE ID_HD IS SET (LEGACY SUPPORT) ==========
            // If ticket has id_hd = 0, create hoa_don and update
            if ((int)$ticket['id_hd'] === 0) {
                $ngay_tt = date('Y-m-d H:i:s');
                $insert_hd = "INSERT INTO hoa_don (id, ngay_tt, thanh_tien) VALUES (NULL, :ngay_tt, :thanh_tien)";
                $stmt_hd = $pdo->prepare($insert_hd);
                $stmt_hd->execute([
                    ':ngay_tt' => $ngay_tt,
                    ':thanh_tien' => (int)$ticket['price']
                ]);
                $id_hd_new = $pdo->lastInsertId();
                
                // Update vé with new id_hd
                $update_id_hd = "UPDATE ve SET id_hd = :id_hd WHERE id = :ve_id";
                $stmt_id_hd = $pdo->prepare($update_id_hd);
                $stmt_id_hd->execute([
                    ':id_hd' => $id_hd_new,
                    ':ve_id' => $ve_id
                ]);
                
                error_log("MANUAL_CONFIRM: Created hoa_don ID=$id_hd_new and linked to ve $ve_id");
            }
            
            // ⚠️ KHÔNG CỘNG ĐIỂM Ở ĐÂY - Sẽ cộng ở case 'xacnhan' trong index.php
            // Lý do: Để tránh cộng 2 lần và để quản lý tập trung        // ========== LOG CONFIRMATION ==========
        file_put_contents(__DIR__ . '/manual_confirms.txt', 
            date('Y-m-d H:i:s') . " | VÉ ID: $ve_id | Số tiền: " . $ticket['price'] . " VND | User: $user_id | Status: SUCCESS (Transfer verified from webhook)\n", 
            FILE_APPEND
        );
        
        // Get id_hd from ticket
        $id_hd = (int)$ticket['id_hd'];
        
        // SUCCESS RESPONSE
        echo json_encode([
            'success' => true,
            'message' => '✅ Xác nhận thanh toán thành công!',
            've_id' => $ve_id,
            'id_hd' => $id_hd,
            'ma_ve' => $ticket['ma_ve'],
            'amount' => (int)$ticket['price'],
            'user_id' => $user_id,
            'points_earned' => $user_id > 0 ? $points_earned : 0,
            'redirect_url' => '/Trang-nguoi-dung/index.php?act=xacnhan'
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        error_log("MANUAL_CONFIRM ERROR: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
