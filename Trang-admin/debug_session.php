<?php
session_start();
include_once 'model/pdo.php';
include_once 'model/lichchieu.php';
include_once 'helpers/quyen.php';

echo "<h1>Debug Trang Duyệt Kế Hoạch</h1>";

// Kiểm tra session hiện tại
echo "<h3>Session hiện tại:</h3>";
if (isset($_SESSION['user1'])) {
    echo "<pre>" . print_r($_SESSION['user1'], true) . "</pre>";
    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
    echo "<p>Vai trò: $currentRole (ROLE_QUAN_LY_CUM = " . ROLE_QUAN_LY_CUM . ")</p>";
} else {
    echo "<p>Chưa đăng nhập!</p>";
}

// Test quyền truy cập
echo "<h3>Kiểm tra quyền:</h3>";
$act = 'duyet_lichchieu';
$hasPermission = allowed_act($act, $currentRole ?? -1);
echo "<p>Quyền truy cập '$act': " . ($hasPermission ? 'CÓ' : 'KHÔNG') . "</p>";

// Test dữ liệu
echo "<h3>Dữ liệu kế hoạch:</h3>";
$ds_lich = lc_list_grouped_for_approval('cho_duyet');
echo "<p>Số lượng: " . count($ds_lich) . "</p>";

// Debug SQL trực tiếp
echo "<h3>Debug SQL trực tiếp:</h3>";
try {
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
                tk.name as nguoi_tao_ten
            FROM lichchieu lc
            LEFT JOIN phim p ON lc.id_phim = p.id
            LEFT JOIN loai_phim lp ON p.id_loai_phim = lp.id
            LEFT JOIN rap_chieu r ON lc.id_rap = r.id
            LEFT JOIN taikhoan tk ON lc.nguoi_tao = tk.id
            WHERE lc.trang_thai_duyet = 'Chờ duyệt'
            AND lc.ma_ke_hoach IS NOT NULL
            GROUP BY lc.ma_ke_hoach
            ORDER BY lc.ngay_tao DESC";
    
    $result = pdo_query($sql);
    echo "<p>Kết quả SQL trực tiếp: " . count($result) . " kế hoạch</p>";
    
    if (!empty($result)) {
        echo "<ul>";
        foreach ($result as $r) {
            echo "<li><strong>" . htmlspecialchars($r['ma_ke_hoach']) . "</strong> - " . 
                 htmlspecialchars($r['ten_phim']) . " - " . 
                 htmlspecialchars($r['ten_rap']) . " - " . 
                 htmlspecialchars($r['trang_thai_duyet']) . "</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p>Lỗi SQL: " . $e->getMessage() . "</p>";
}

if (!empty($ds_lich)) {
    echo "<ul>";
    foreach ($ds_lich as $r) {
        echo "<li>" . htmlspecialchars($r['ma_ke_hoach']) . " - " . htmlspecialchars($r['ten_phim']) . " (" . htmlspecialchars($r['trang_thai_duyet']) . ")</li>";
    }
    echo "</ul>";
}
?>