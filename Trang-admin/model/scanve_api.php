<?php
/**
 * Scanve API - Xử lý kiểm tra vé & check-in
 * Hỗ trợ: QR Scanner + Manual Input + Image QR Detection
 */

// Handle image-based QR detection
function detect_qr_from_image($image_file) {
    // Try using PHP-QRCode or similar library if available
    // For now, we'll send to external QR decoder service
    
    if (!file_exists($image_file)) {
        return [
            'success' => false,
            'error' => 'File not found'
        ];
    }

    // Method 1: Try using local QRCode library if available
    $qr_data = try_local_qr_detection($image_file);
    if ($qr_data) {
        return [
            'success' => true,
            'qr_data' => $qr_data
        ];
    }

    // Method 2: Use online QR detection API (free tier)
    $qr_data = try_online_qr_detection($image_file);
    if ($qr_data) {
        return [
            'success' => true,
            'qr_data' => $qr_data
        ];
    }

    return [
        'success' => false,
        'error' => 'Could not detect QR code'
    ];
}

function try_local_qr_detection($image_file) {
    // Try using zxing library if available
    // Or other local QR detection methods
    
    // Check if we have access to any QR library
    // For now, return null to fall back to online detection
    return null;
}

function try_online_qr_detection($image_file) {
    // Use free QR code detection API
    // Example: api.qrserver.com or quickchart.io
    
    $file_content = file_get_contents($image_file);
    $base64_image = base64_encode($file_content);
    
    // Try QR Server API
    $url = "https://api.qrserver.com/v1/read-qr-code/";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => 'data:image/jpeg;base64,' . $base64_image]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $result = json_decode($response, true);
        if (isset($result[0]['symbol'][0]['data'])) {
            return $result[0]['symbol'][0]['data'];
        }
    }

    return null;
}

function check_ticket_by_code($ma_ve) {
    // Tìm vé theo mã hoặc ID
    $sql = "SELECT v.id, v.ma_ve, v.trang_thai, v.id_phim, v.id_ngay_chieu, 
                   v.id_thoi_gian_chieu, v.ghe, v.check_in_luc, v.check_in_boi,
                   p.tieu_de, lc.ngay_chieu, kgc.thoi_gian_chieu, pgc.name as tenphong
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pgc ON pgc.id = kgc.id_phong
            WHERE v.ma_ve = ? OR v.id = ?
            LIMIT 1";
    
    return pdo_query_one($sql, $ma_ve, $ma_ve);
}

function check_ticket_by_id($id_ve) {
    // Tìm vé theo ID
    $sql = "SELECT v.id, v.ma_ve, v.trang_thai, v.id_phim, v.id_ngay_chieu, 
                   v.id_thoi_gian_chieu, v.ghe, v.check_in_luc, v.check_in_boi,
                   p.tieu_de, lc.ngay_chieu, kgc.thoi_gian_chieu, pgc.name as tenphong
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            JOIN khung_gio_chieu kgc ON kgc.id = v.id_thoi_gian_chieu
            JOIN phongchieu pgc ON pgc.id = kgc.id_phong
            WHERE v.id = ?
            LIMIT 1";
    
    return pdo_query_one($sql, $id_ve);
}

function update_ticket_checkin($id_ve, $staff_id) {
    $sql = "UPDATE ve SET trang_thai = 4, check_in_luc = NOW(), check_in_boi = ? WHERE id = ?";
    return pdo_execute($sql, $staff_id, $id_ve);
}

function validate_ticket($ticket) {
    $errors = [];

    // Kiểm tra trang thái vé
    if ($ticket['trang_thai'] == 0) {
        $errors[] = 'Vé chưa thanh toán';
    } elseif ($ticket['trang_thai'] == 3) {
        $errors[] = 'Vé đã bị hủy';
    } elseif ($ticket['trang_thai'] == 4) {
        $errors[] = 'Vé đã được check-in lúc ' . htmlspecialchars($ticket['check_in_luc']);
    } elseif ($ticket['trang_thai'] != 1) {
        $errors[] = 'Trạng thái vé không rõ';
    }

    // Không kiểm tra thời gian chiếu - cho phép xem lại vé bất kỳ lúc nào
    // Người dùng có thể kiểm tra vé ngay cả sau khi suất chiếu kết thúc

    return $errors;
}

function get_checkin_history($id_rap) {
    $sql = "SELECT v.id, v.ma_ve, p.tieu_de, p.tieu_de as phim, v.check_in_luc, tk.name as staff_name
            FROM ve v
            JOIN phim p ON p.id = v.id_phim
            JOIN taikhoan tk ON tk.id = v.check_in_boi
            LEFT JOIN lichchieu lc ON lc.id = v.id_ngay_chieu
            WHERE v.trang_thai = 4 
              AND DATE(v.check_in_luc) = CURDATE()
              AND lc.id_rap = ?
            ORDER BY v.check_in_luc DESC
            LIMIT 50";
    
    return pdo_query($sql, $id_rap);
}

// ===== POST HANDLER =====

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === 'scan_qr_image' && isset($_FILES['image'])) {
    // Handle image-based QR detection
    $image_file = $_FILES['image']['tmp_name'];
    
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK && is_uploaded_file($image_file)) {
        $result = detect_qr_from_image($image_file);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Upload error: ' . ($_FILES['image']['error'] ?? 'unknown')
        ]);
    }
    exit;
}

?>