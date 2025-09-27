<?php
// Test khung giờ chiếu theo rạp
session_start();

include_once 'model/pdo.php';
include_once 'model/khunggio.php';

echo "<h1>🎬 Test Khung Giờ Chiếu Theo Rạp</h1>";

// Test các rạp khác nhau
$test_raps = [
    1 => "Galaxy Studio Quận 1",
    2 => "Galaxy Studio Quận 7", 
    3 => "Galaxy Studio Quận 2"
];

foreach ($test_raps as $id_rap => $ten_rap) {
    echo "<h3>🏢 $ten_rap (ID: $id_rap)</h3>";
    
    $khung_gio = loadall_khunggiochieu($id_rap);
    
    echo "<p><strong>Số khung giờ:</strong> " . count($khung_gio) . "</p>";
    
    if (!empty($khung_gio)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Phim</th><th>Phòng</th><th>Ngày</th><th>Giờ</th><th>ID Rạp</th>";
        echo "</tr>";
        
        foreach (array_slice($khung_gio, 0, 5) as $kg) { // Chỉ hiển thị 5 dòng đầu
            echo "<tr>";
            echo "<td>" . $kg['id'] . "</td>";
            echo "<td>" . htmlspecialchars($kg['tieu_de']) . "</td>";
            echo "<td>" . htmlspecialchars($kg['name']) . "</td>";
            echo "<td>" . $kg['ngay_chieu'] . "</td>";
            echo "<td>" . $kg['thoi_gian_chieu'] . "</td>";
            echo "<td>" . $kg['id_rap'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p><em>Không có khung giờ chiếu nào.</em></p>";
    }
}

// Test với rạp không tồn tại
echo "<h3>🚫 Test Rạp Không Tồn tại (ID: 999)</h3>";
$khung_gio_invalid = loadall_khunggiochieu(999);
echo "<p><strong>Số khung giờ:</strong> " . count($khung_gio_invalid) . "</p>";

// Test không filter (tất cả rạp)
echo "<h3>🌍 Tất Cả Rạp (Không Filter)</h3>";
$khung_gio_all = loadall_khunggiochieu();
echo "<p><strong>Tổng số khung giờ:</strong> " . count($khung_gio_all) . "</p>";

if (!empty($khung_gio_all)) {
    $rap_counts = [];
    foreach ($khung_gio_all as $kg) {
        $rap_id = $kg['id_rap'];
        $rap_counts[$rap_id] = ($rap_counts[$rap_id] ?? 0) + 1;
    }
    
    echo "<p><strong>Phân bố theo rạp:</strong></p><ul>";
    foreach ($rap_counts as $rap_id => $count) {
        $ten_rap = $test_raps[$rap_id] ?? "Rạp ID $rap_id";
        echo "<li>$ten_rap: $count khung giờ</li>";
    }
    echo "</ul>";
}
?>