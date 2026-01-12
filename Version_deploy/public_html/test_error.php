<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "✅ PHP hoạt động bình thường<br>";

// Kiểm tra database connection
echo "Kiểm tra kết nối database...<br>";

try {
    include "Trang-nguoi-dung/model/pdo.php";
    echo "✅ pdo.php load thành công<br>";
} catch (Exception $e) {
    echo "❌ Lỗi từ pdo.php: " . $e->getMessage() . "<br>";
}

echo "Done!";
?>
