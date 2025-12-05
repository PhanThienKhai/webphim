<?php
require 'Trang-nguoi-dung/model/pdo.php';

$sql = "SELECT id, ma_ve, price, trang_thai FROM ve ORDER BY id DESC LIMIT 5;";
$result = pdo_query($sql);

echo "=== VÉ GẦN NHẤT ===\n";
foreach ($result as $ve) {
    echo "ID: " . $ve['id'] . " | Mã vé: " . $ve['ma_ve'] . " | Giá: " . $ve['price'] . " | Trạng thái: " . $ve['trang_thai'] . "\n";
}
?>
