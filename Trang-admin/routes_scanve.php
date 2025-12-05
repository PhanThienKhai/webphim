<?php
/**
 * DEPRECATED: This file has been merged into index.php
 * 
 * The following cases are now handled directly in index.php switch statement:
 * - case "scanve_new": Displays QR scanner interface and handles POST check-in requests
 * - case "scanve_check": API endpoint to check ticket from QR code
 * - case "scanve_history": API endpoint to get check-in history
 * 
 * Do not use or include this file anymore - it's kept only for reference.
 */
?>
            'ticket' => [
                'movie_title' => htmlspecialchars($ticket['tieu_de']),
                'screening_date' => htmlspecialchars($ticket['ngay_chieu']),
                'screening_time' => htmlspecialchars($ticket['thoi_gian_chieu']),
                'room_name' => htmlspecialchars($ticket['tenphong']),
                'seat' => htmlspecialchars($ticket['ghe']),
                'screening_time' => htmlspecialchars($ticket['ngay_chieu'] . ' ' . $ticket['thoi_gian_chieu'])
            ]
        ]);
        exit;
    }

    // Handle regular form POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kiemtra'])) {
        $ma_ve = trim($_POST['ma_ve'] ?? '');
        
        $ticket = check_ticket_by_code($ma_ve);
        if (!$ticket) {
            $err = 'Vé không tồn tại!';
        } else {
            $errors = validate_ticket($ticket);
            if (!empty($errors)) {
                $err = implode(' | ', $errors);
            } else {
                update_ticket_checkin($ticket['id'], $_SESSION['user1']['id']);
                $msg = 'Check-in thành công! Khách được vào phòng chiếu.';
                $ve_chi_tiet = $ticket;
            }
        }
    }

    include "./view/nhanvien/scanve_new.php";
    break;

    // Handle regular form POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kiemtra'])) {
        $ma_ve = trim($_POST['ma_ve'] ?? '');
        
        $ticket = check_ticket_by_code($ma_ve);
        if (!$ticket) {
            $err = 'Vé không tồn tại!';
        } else {
            $errors = validate_ticket($ticket);
            if (!empty($errors)) {
                $err = implode(' | ', $errors);
            } else {
                update_ticket_checkin($ticket['id'], $_SESSION['user1']['id']);
                $msg = 'Check-in thành công! Khách được vào phòng chiếu.';
                $ve_chi_tiet = $ticket;
            }
        }
    }

    include "./view/nhanvien/scanve_new.php";
    break;

case "scanve_check":
    header('Content-Type: application/json');

    // Kiểm tra quyền
    if (!allowed_act('scanve', (int)$_SESSION['user1']['vai_tro'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Không có quyền']);
        exit;
    }

    // Handle AJAX JSON POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }

    $ma_ve = trim($input['ma_ve'] ?? '');

    if (empty($ma_ve)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã vé']);
        exit;
    }

    // Tìm vé theo mã hoặc ID
    $ticket = check_ticket_by_code($ma_ve);
    
    if (!$ticket) {
        echo json_encode([
            'success' => false, 
            'message' => 'Vé không tồn tại',
            'details' => 'Không tìm thấy vé với mã: ' . htmlspecialchars($ma_ve)
        ]);
        exit;
    }

    // Validate vé
    $errors = validate_ticket($ticket);
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => $errors[0],
            'details' => implode('<br>', array_map('htmlspecialchars', $errors)),
            'ticket' => [
                'id' => (int)$ticket['id'],
                'movie_title' => htmlspecialchars($ticket['tieu_de']),
                'screening_date' => htmlspecialchars($ticket['ngay_chieu']),
                'screening_time' => htmlspecialchars($ticket['thoi_gian_chieu']),
                'room_name' => htmlspecialchars($ticket['tenphong']),
                'seat' => htmlspecialchars($ticket['ghe']),
                'trang_thai' => (int)$ticket['trang_thai'],
                'check_in_luc' => htmlspecialchars($ticket['check_in_luc'] ?? '')
            ]
        ]);
        exit;
    }

    // Vé hợp lệ - trả về thông tin để hiển thị
    echo json_encode([
        'success' => true,
        'message' => 'Vé hợp lệ',
        'ticket' => [
            'id' => (int)$ticket['id'],
            'movie_title' => htmlspecialchars($ticket['tieu_de']),
            'screening_date' => htmlspecialchars($ticket['ngay_chieu']),
            'screening_time' => htmlspecialchars($ticket['thoi_gian_chieu']),
            'room_name' => htmlspecialchars($ticket['tenphong']),
            'seat' => htmlspecialchars($ticket['ghe']),
            'trang_thai' => (int)$ticket['trang_thai'],
            'check_in_luc' => htmlspecialchars($ticket['check_in_luc'] ?? '')
        ]
    ]);
    exit;

case "scanve_history":
    header('Content-Type: application/json');

    if (!allowed_act('scanve', (int)$_SESSION['user1']['vai_tro'])) {
        echo json_encode(['success' => false, 'message' => 'Không có quyền']);
        exit;
    }

    $id_rap = (int)$_SESSION['user1']['id_rap'];
    $history = get_checkin_history($id_rap);

    $formatted = [];
    foreach ($history as $item) {
        $formatted[] = [
            'ma_ve' => htmlspecialchars($item['ma_ve']),
            'phim' => htmlspecialchars($item['tieu_de']),
            'check_in_time' => htmlspecialchars($item['check_in_luc']),
            'staff' => htmlspecialchars($item['staff_name'])
        ];
    }

    echo json_encode(['success' => true, 'history' => $formatted]);
    exit;
