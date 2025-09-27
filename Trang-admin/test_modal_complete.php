<?php
// Test modal chi tiết hoàn chỉnh
session_start();

// Fake session cluster manager
$_SESSION['user1'] = [
    'id' => 19,
    'name' => 'Quản lý cụm rạp',
    'vai_tro' => 4
];

include_once 'model/pdo.php';

// Lấy dữ liệu chi tiết kế hoạch
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

$chi_tiet = pdo_query($sql, $ma_ke_hoach);

echo "<!DOCTYPE html>";
echo "<html><head><title>Test Modal Chi Tiết</title>";
echo '<meta charset="UTF-8">';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
echo "</head><body>";
echo "<div class='container mt-4'>";
echo "<h2>🎬 Test Modal Chi Tiết Kế Hoạch</h2>";
echo "<p><strong>Mã kế hoạch:</strong> $ma_ke_hoach</p>";

// Include modal content trực tiếp
if (!empty($chi_tiet)) {
    include "./view/cum/chi_tiet_kehoach_modal.php";
} else {
    echo '<div class="alert alert-warning">Không có dữ liệu chi tiết!</div>';
}

echo "</div>";
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
echo "</body></html>";
?>