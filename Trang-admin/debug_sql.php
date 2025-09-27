<?php
session_start();
include_once 'model/pdo.php';

echo "<h1>Debug Chi Tiết SQL</h1>";

// 1. Kiểm tra tất cả kế hoạch có ma_ke_hoach
echo "<h3>1. Tất cả kế hoạch có mã kế hoạch:</h3>";
$all = pdo_query("SELECT ma_ke_hoach, trang_thai_duyet, ten_phim FROM lichchieu lc LEFT JOIN phim p ON lc.id_phim = p.id WHERE ma_ke_hoach IS NOT NULL GROUP BY ma_ke_hoach");
echo "<p>Tổng số: " . count($all) . "</p>";
foreach ($all as $r) {
    echo "<li>" . htmlspecialchars($r['ma_ke_hoach']) . " - " . htmlspecialchars($r['ten_phim']) . " - [" . htmlspecialchars($r['trang_thai_duyet']) . "]</li>";
}

// 2. Kiểm tra trạng thái chính xác
echo "<h3>2. Các trạng thái duyệt có trong DB:</h3>";
$statuses = pdo_query("SELECT DISTINCT trang_thai_duyet FROM lichchieu WHERE ma_ke_hoach IS NOT NULL");
foreach ($statuses as $s) {
    $status = $s['trang_thai_duyet'];
    echo "<li>[" . htmlspecialchars($status) . "] - Length: " . strlen($status) . "</li>";
}

// 3. Test với các variant của "Chờ duyệt"
echo "<h3>3. Test các variant:</h3>";
$variants = ['Chờ duyệt', 'cho_duyet', 'Cho duyet', 'CHỜ DUYỆT'];
foreach ($variants as $v) {
    $count = pdo_query("SELECT COUNT(*) as cnt FROM lichchieu WHERE trang_thai_duyet = ? AND ma_ke_hoach IS NOT NULL", $v);
    echo "<li>[" . htmlspecialchars($v) . "]: " . $count[0]['cnt'] . " records</li>";
}

// 4. Test truy vấn hoàn chỉnh
echo "<h3>4. Test truy vấn đầy đủ:</h3>";
$full_query = "SELECT lc.ma_ke_hoach, lc.trang_thai_duyet, p.tieu_de 
               FROM lichchieu lc 
               LEFT JOIN phim p ON lc.id_phim = p.id 
               WHERE lc.trang_thai_duyet = 'Chờ duyệt' AND lc.ma_ke_hoach IS NOT NULL
               GROUP BY lc.ma_ke_hoach";
$result = pdo_query($full_query);
echo "<p>Kết quả: " . count($result) . " records</p>";
foreach ($result as $r) {
    echo "<li>" . htmlspecialchars($r['ma_ke_hoach']) . " - " . htmlspecialchars($r['tieu_de']) . "</li>";
}
?>