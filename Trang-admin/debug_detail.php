<?php
// Debug chi tiết hàm lc_list_grouped_for_approval
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4
];

include_once 'model/pdo.php';
include_once 'model/lichchieu.php';

echo "<h1>🔍 Debug Hàm lc_list_grouped_for_approval</h1>";

// Test trực tiếp SQL trong hàm
$filter = 'cho_duyet';
$where_clause = "";
$params = [];

// Lọc theo trạng thái - sử dụng tên chính xác trong DB
if ($filter === 'cho_duyet') {
    $where_clause = "WHERE lc.trang_thai_duyet = 'Chờ duyệt'";
} elseif ($filter === 'da_duyet') {
    $where_clause = "WHERE lc.trang_thai_duyet = 'Đã duyệt'";
} elseif ($filter === 'tu_choi') {
    $where_clause = "WHERE lc.trang_thai_duyet = 'Từ chối'";
}

$sql = "SELECT 
            lc.ma_ke_hoach,
            lc.id_phim,
            lc.id_rap,
            lc.trang_thai_duyet,
            lc.ghi_chu,
            lc.nguoi_tao,
            lc.ngay_tao,
            MIN(lc.ngay_chieu) as tu_ngay,
            MAX(lc.ngay_chieu) as den_ngay,
            COUNT(lc.id) as so_ngay_chieu,
            p.tieu_de as ten_phim,
            p.thoi_luong_phim,
            p.img,
            lp.name as ten_loai,
            r.ten_rap,
            tk.name as nguoi_tao_ten,
            GROUP_CONCAT(DISTINCT DATE_FORMAT(lc.ngay_chieu, '%d/%m') ORDER BY lc.ngay_chieu SEPARATOR ', ') as danh_sach_ngay
        FROM lichchieu lc
        LEFT JOIN phim p ON lc.id_phim = p.id
        LEFT JOIN loaiphim lp ON p.id_loai = lp.id
        LEFT JOIN rap_chieu r ON lc.id_rap = r.id
        LEFT JOIN taikhoan tk ON lc.nguoi_tao = tk.id
        $where_clause
        AND lc.ma_ke_hoach IS NOT NULL
        GROUP BY lc.ma_ke_hoach
        ORDER BY lc.ngay_tao DESC, p.tieu_de";

echo "<h3>SQL Query:</h3>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

try {
    $result = pdo_query($sql);
    echo "<h3>✅ Kết quả: " . count($result) . " kế hoạch</h3>";
    
    if (!empty($result)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>Mã KH</th><th>Phim</th><th>Rạp</th><th>Từ</th><th>Đến</th><th>Trạng thái</th><th>Người tạo</th>";
        echo "</tr>";
        foreach ($result as $r) {
            echo "<tr>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ma_ke_hoach']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ten_phim']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['ten_rap']) . "</td>";
            echo "<td style='padding: 5px;'>" . $r['tu_ngay'] . "</td>";
            echo "<td style='padding: 5px;'>" . $r['den_ngay'] . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['trang_thai_duyet']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($r['nguoi_tao_ten']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "<br>";
}

// Test hàm gốc
echo "<h3>Test Hàm Gốc:</h3>";
try {
    $result_func = lc_list_grouped_for_approval('cho_duyet');
    echo "Kết quả hàm: " . count($result_func) . " kế hoạch<br>";
} catch (Exception $e) {
    echo "❌ Lỗi hàm: " . $e->getMessage() . "<br>";
}

// Kiểm tra error log
echo "<h3>Error Logs:</h3>";
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    $logs = file_get_contents($error_log);
    $recent_logs = array_slice(explode("\n", $logs), -20); // 20 dòng cuối
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_logs)) . "</pre>";
} else {
    echo "Không tìm thấy error log hoặc không có lỗi.<br>";
}
?>