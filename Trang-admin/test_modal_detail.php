<?php
// Test modal chi tiết
session_start();

// Fake login cluster manager
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4
];

include_once 'model/pdo.php';

echo "<h1>🔍 Test Modal Chi Tiết Kế Hoạch</h1>";

$ma_ke_hoach = 'KH_1_20250927165324_767';

$sql = "SELECT 
                                lc.id,
                                lc.ngay_chieu,
                                lc.ma_ke_hoach,
                                lc.trang_thai_duyet,
                                lc.ghi_chu,
                                lc.ngay_tao,
                                p.tieu_de as ten_phim,
                                p.thoi_luong_phim,
                                p.img,
                                lp.name as ten_loai,
                                r.ten_rap,
                                GROUP_CONCAT(
                                    CONCAT(kgc.thoi_gian_chieu, ' (', ph.name, ')') 
                                    ORDER BY kgc.thoi_gian_chieu 
                                    SEPARATOR ', '
                                ) as khung_gio
                            FROM lichchieu lc
                            LEFT JOIN phim p ON lc.id_phim = p.id
                            LEFT JOIN loaiphim lp ON p.id_loai = lp.id
                            LEFT JOIN rap_chieu r ON lc.id_rap = r.id
                            LEFT JOIN khung_gio_chieu kgc ON lc.id = kgc.id_lich_chieu
                            LEFT JOIN phongchieu ph ON kgc.id_phong = ph.id
                            WHERE lc.ma_ke_hoach = ?
                            GROUP BY lc.id
                            ORDER BY lc.ngay_chieu";

echo "<h3>SQL Query:</h3>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";
echo "<p><strong>Tham số:</strong> $ma_ke_hoach</p>";

try {
    $chi_tiet = pdo_query($sql, $ma_ke_hoach);
    echo "<h3>✅ Kết quả: " . count($chi_tiet) . " lịch chiếu</h3>";
    
    if (!empty($chi_tiet)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Ngày</th><th>Phim</th><th>Loại</th><th>Rạp</th><th>Khung giờ</th><th>Trạng thái</th>";
        echo "</tr>";
        foreach ($chi_tiet as $ct) {
            echo "<tr>";
            echo "<td style='padding: 5px;'>" . $ct['id'] . "</td>";
            echo "<td style='padding: 5px;'>" . $ct['ngay_chieu'] . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($ct['ten_phim']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($ct['ten_loai'] ?? 'N/A') . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($ct['ten_rap']) . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($ct['khung_gio'] ?? 'Chưa có') . "</td>";
            echo "<td style='padding: 5px;'>" . htmlspecialchars($ct['trang_thai_duyet']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<h3>❌ Lỗi: " . $e->getMessage() . "</h3>";
}
?>