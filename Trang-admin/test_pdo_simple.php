<?php
session_start();

// Fake login để có session
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4
];

include_once 'model/pdo.php';

echo "<h1>🔍 Test PDO Connection</h1>";

// Test 1: Kết nối PDO cơ bản
try {
    $test_query = "SELECT COUNT(*) as count FROM lichchieu WHERE ma_ke_hoach IS NOT NULL";
    $result = pdo_query($test_query);
    echo "✅ PDO Connection OK - Tổng lịch có mã kế hoạch: " . $result[0]['count'] . "<br>";
} catch (Exception $e) {
    echo "❌ Lỗi PDO: " . $e->getMessage() . "<br>";
    die();
}

// Test 2: Query chính xác như trong hàm
echo "<h3>Test Query Chính Xác:</h3>";
$sql = "SELECT 
            lc.ma_ke_hoach,
            lc.id_phim,
            lc.id_rap,
            lc.trang_thai_duyet,
            MIN(lc.ngay_chieu) as tu_ngay,
            MAX(lc.ngay_chieu) as den_ngay,
            COUNT(lc.id) as so_ngay_chieu,
            p.tieu_de as ten_phim,
            r.ten_rap
        FROM lichchieu lc
        LEFT JOIN phim p ON lc.id_phim = p.id
        LEFT JOIN rap_chieu r ON lc.id_rap = r.id
        WHERE lc.trang_thai_duyet = 'Chờ duyệt'
        AND lc.ma_ke_hoach IS NOT NULL
        GROUP BY lc.ma_ke_hoach
        ORDER BY lc.ngay_tao DESC";

try {
    $ds_lich = pdo_query($sql);
    echo "✅ Query OK - Số kế hoạch: " . count($ds_lich) . "<br><br>";
    
    if (!empty($ds_lich)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'><th>Mã KH</th><th>Phim</th><th>Rạp</th><th>Từ</th><th>Đến</th><th>Trạng thái</th></tr>";
        foreach ($ds_lich as $r) {
            echo "<tr>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ma_ke_hoach']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ten_phim']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ten_rap']) . "</td>";
            echo "<td style='padding: 5px;'>" . $r['tu_ngay'] . "</td>";
            echo "<td style='padding: 5px;'>" . $r['den_ngay'] . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['trang_thai_duyet']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Không có dữ liệu!";
    }
} catch (Exception $e) {
    echo "❌ Lỗi Query: " . $e->getMessage() . "<br>";
}

// Test 3: Test hàm lc_list_grouped_for_approval
echo "<h3>Test Hàm lc_list_grouped_for_approval:</h3>";
include_once 'model/lichchieu.php';

try {
    $result_func = lc_list_grouped_for_approval('cho_duyet');
    echo "✅ Hàm OK - Số kế hoạch: " . count($result_func) . "<br>";
    
    if (count($result_func) != count($ds_lich)) {
        echo "⚠️ KHÁC BIỆT: Query trực tiếp = " . count($ds_lich) . ", Hàm = " . count($result_func) . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Lỗi Hàm: " . $e->getMessage() . "<br>";
}
?>