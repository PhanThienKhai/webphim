<?php
session_start();

// Simulate quản lý cụm login
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4,
    'id_rap' => null
];

include_once 'model/pdo.php';
include_once 'model/lichchieu.php';

echo "<h1>Test Duyệt Kế Hoạch - Quản Lý Cụm</h1>";
echo "<p>Session user: " . print_r($_SESSION['user1'], true) . "</p>";

$filter = 'cho_duyet';
$ds_lich = lc_list_grouped_for_approval($filter);

echo "<h3>Số lượng kế hoạch chờ duyệt: " . count($ds_lich) . "</h3>";

if (!empty($ds_lich)) {
    echo "<table border='1'>";
    echo "<tr><th>Mã KH</th><th>Phim</th><th>Rạp</th><th>Từ ngày</th><th>Đến ngày</th><th>Trạng thái</th></tr>";
    foreach ($ds_lich as $r) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($r['ma_ke_hoach']) . "</td>";
        echo "<td>" . htmlspecialchars($r['ten_phim']) . "</td>";
        echo "<td>" . htmlspecialchars($r['ten_rap']) . "</td>";
        echo "<td>" . $r['tu_ngay'] . "</td>";
        echo "<td>" . $r['den_ngay'] . "</td>";
        echo "<td>" . htmlspecialchars($r['trang_thai_duyet']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Không có kế hoạch nào!</p>";
}
?>