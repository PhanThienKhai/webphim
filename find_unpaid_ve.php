<?php
require 'Trang-nguoi-dung/model/pdo.php';

echo "=== TÌM VÉ CHƯA THANH TOÁN GIÁ 400,000 ===\n\n";

// Tìm vé chưa thanh toán (trang_thai = 0) và giá 400,000
$sql = "SELECT id, price, trang_thai, ngay_dat FROM ve WHERE price = 400000 AND trang_thai = 0 ORDER BY id DESC LIMIT 10";
$result = pdo_query($sql);

if ($result && count($result) > 0) {
    foreach ($result as $ve) {
        echo "✓ Vé ID: " . $ve['id'] . "\n";
        echo "  Giá: " . $ve['price'] . " VND\n";
        echo "  Trạng thái: " . $ve['trang_thai'] . " (0 = Chưa thanh toán)\n";
        echo "  Ngày đặt: " . $ve['ngay_dat'] . "\n";
        echo "  → Mã vé: VE" . $ve['id'] . "\n\n";
    }
} else {
    echo "❌ Không tìm thấy vé chưa thanh toán giá 400,000\n\n";
    
    echo "=== CÓ VÉ CHƯA THANH TOÁN NÀO KHÔNG? ===\n";
    $sql2 = "SELECT id, price, trang_thai FROM ve WHERE trang_thai = 0 ORDER BY id DESC LIMIT 5";
    $result2 = pdo_query($sql2);
    
    if ($result2 && count($result2) > 0) {
        foreach ($result2 as $ve) {
            echo "Vé ID: " . $ve['id'] . " | Giá: " . $ve['price'] . " | Trạng thái: " . $ve['trang_thai'] . "\n";
        }
    } else {
        echo "Tất cả vé đều đã thanh toán!\n";
    }
}
?>
