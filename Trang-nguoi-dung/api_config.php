<?php
/**
 * API Endpoint: Lấy cấu hình website từ admin
 * Được gọi bởi header.php, footer.php để hiển thị thông tin từ cấu hình
 */

header('Content-Type: application/json; charset=utf-8');

try {
    // Kết nối database
    $pdo_config = [
        'host' => 'localhost',
        'db' => 'cinepass',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4'
    ];
    
    $dsn = "mysql:host={$pdo_config['host']};dbname={$pdo_config['db']};charset={$pdo_config['charset']}";
    $pdo = new PDO($dsn, $pdo_config['user'], $pdo_config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Lấy cấu hình
    $stmt = $pdo->prepare("SELECT * FROM thong_tin_website WHERE id = 1");
    $stmt->execute();
    $config = $stmt->fetch();
    
    if (!$config) {
        // Default nếu chưa có cấu hình
        $config = [
            'id' => 1,
            'ten_website' => 'Galaxy Studio',
            'logo' => 'Galaxy_Studio_2003_(Wordmark)_(Grey).webp',
            'dia_chi' => '',
            'so_dien_thoai' => '',
            'email' => '',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'mo_ta' => 'Nền tảng mua vé xem phim hàng đầu',
            'ngay_cap_nhat' => date('Y-m-d H:i:s')
        ];
    }
    
    // Xử lý logo path
    if (!empty($config['logo'])) {
        // Nếu không có http, thêm imgavt/
        if (strpos($config['logo'], 'http') === false && strpos($config['logo'], 'imgavt/') === false) {
            $config['logo'] = 'imgavt/' . $config['logo'];
        }
    } else {
        $config['logo'] = 'imgavt/Galaxy_Studio_2003_(Wordmark)_(Grey).webp';
    }
    
    // Xử lý video_banner path
    if (empty($config['video_banner'])) {
        $config['video_banner'] = 'video/OFFICIAL TRAILER.mp4'; // Video mặc định
    } else {
        // Nếu không có video/, thêm vào
        if (strpos($config['video_banner'], 'http') === false && strpos($config['video_banner'], 'video/') === false) {
            $config['video_banner'] = 'video/' . $config['video_banner'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $config
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
