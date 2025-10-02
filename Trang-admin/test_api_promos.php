<?php
// File debug API promos
session_start();
require_once 'model/pdo.php';
require_once 'model/khuyenmai.php';

echo "<h2>🔍 Debug API Promos</h2>";

// 1. Check session user
echo "<h3>1. Kiểm tra SESSION:</h3>";
echo "<pre>";
print_r($_SESSION['user1'] ?? 'NO SESSION');
echo "</pre>";

$id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
echo "<p>ID Rạp: <strong>" . ($id_rap ?: 'KHÔNG CÓ') . "</strong></p>";

// 2. Check database structure
echo "<h3>2. Kiểm tra cấu trúc bảng khuyen_mai:</h3>";
try {
    $columns = pdo_query("DESCRIBE khuyen_mai");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p style='color: red;'>LỖI: " . $e->getMessage() . "</p>";
}

// 3. Check data in khuyen_mai
echo "<h3>3. Dữ liệu trong bảng khuyen_mai:</h3>";
try {
    $all_km = pdo_query("SELECT * FROM khuyen_mai ORDER BY id DESC LIMIT 10");
    if (empty($all_km)) {
        echo "<p style='color: orange;'>⚠️ KHÔNG CÓ DỮ LIỆU trong bảng khuyen_mai</p>";
    } else {
        echo "<table border='1' cellpadding='5' style='font-size: 12px;'>";
        echo "<tr>";
        echo "<th>ID</th><th>Tên KM</th><th>Mã Code</th><th>Loại giảm</th><th>% giảm</th><th>Giá trị giảm</th>";
        echo "<th>Bắt đầu</th><th>Kết thúc</th><th>Trạng thái</th><th>ID Rạp</th>";
        echo "</tr>";
        foreach ($all_km as $km) {
            echo "<tr>";
            echo "<td>{$km['id']}</td>";
            echo "<td>{$km['ten_khuyen_mai']}</td>";
            echo "<td><strong>" . ($km['ma_khuyen_mai'] ?? '<span style="color:red;">NULL</span>') . "</strong></td>";
            echo "<td>{$km['loai_giam']}</td>";
            echo "<td>" . ($km['phan_tram_giam'] ?? 0) . "%</td>";
            echo "<td>" . number_format($km['gia_tri_giam'] ?? 0) . "đ</td>";
            echo "<td>{$km['ngay_bat_dau']}</td>";
            echo "<td>{$km['ngay_ket_thuc']}</td>";
            echo "<td>" . ($km['trang_thai'] ? '✓ Active' : '✗ Inactive') . "</td>";
            echo "<td>" . ($km['id_rap'] ?? 'NULL (toàn cụm)') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>LỖI: " . $e->getMessage() . "</p>";
}

// 4. Test function km_active_list_by_rap
echo "<h3>4. Test function km_active_list_by_rap($id_rap):</h3>";
if ($id_rap > 0) {
    try {
        $promos = km_active_list_by_rap($id_rap);
        echo "<p>Kết quả: <strong>" . count($promos) . " mã khuyến mãi</strong></p>";
        if (!empty($promos)) {
            echo "<pre>";
            print_r($promos);
            echo "</pre>";
        } else {
            echo "<p style='color: orange;'>⚠️ KHÔNG CÓ mã KM active cho rạp này</p>";
            echo "<p>Nguyên nhân có thể:</p>";
            echo "<ul>";
            echo "<li>Không có mã KM nào có trang_thai = 1</li>";
            echo "<li>Không có mã KM nào trong khoảng thời gian hiện tại (ngay_bat_dau <= NOW() <= ngay_ket_thuc)</li>";
            echo "<li>Không có mã KM nào cho id_rap = $id_rap hoặc id_rap IS NULL</li>";
            echo "</ul>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>LỖI: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>⚠️ KHÔNG THỂ TEST - Không có id_rap trong session</p>";
}

// 5. Test API output format
echo "<h3>5. Test API Response Format (JSON):</h3>";
if ($id_rap > 0) {
    try {
        $promos = km_active_list_by_rap($id_rap);
        $result = [];
        foreach ($promos as $p) {
            $discount_text = '';
            if ($p['loai_giam'] === 'phan_tram') {
                $discount_text = 'Giảm ' . number_format($p['phan_tram_giam']) . '%';
            } else {
                $discount_text = 'Giảm ' . number_format($p['gia_tri_giam']) . 'đ';
            }
            
            $result[] = [
                'code' => $p['ma_khuyen_mai'],
                'name' => $p['ten_khuyen_mai'],
                'desc' => $p['mo_ta'] ?? '',
                'discount_text' => $discount_text,
                'expires' => date('d/m/Y', strtotime($p['ngay_ket_thuc']))
            ];
        }
        echo "<pre>";
        echo json_encode(['promos' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "</pre>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>LỖI: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>⚠️ KHÔNG THỂ TEST - Không có id_rap trong session</p>";
}

echo "<hr>";
echo "<p>🕐 Thời gian hiện tại: <strong>" . date('Y-m-d H:i:s') . "</strong></p>";
echo "<p>💡 <strong>Gợi ý:</strong> Nếu không có dữ liệu, hãy thêm mã KM mẫu trong phpMyAdmin với:</p>";
echo "<ul>";
echo "<li>ma_khuyen_mai: 'TEST2024'</li>";
echo "<li>trang_thai: 1</li>";
echo "<li>ngay_bat_dau: hôm nay hoặc trước đó</li>";
echo "<li>ngay_ket_thuc: sau hôm nay</li>";
echo "<li>id_rap: NULL hoặc ID rạp của bạn</li>";
echo "</ul>";
?>
