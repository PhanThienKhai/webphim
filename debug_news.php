<?php
// Simple debug file
include "Trang-admin/model/pdo.php";

echo "=== NEWS DATABASE CHECK ===\n\n";

// Check tintuc table
$sql = "SELECT id, tieu_de, hinh_anh FROM tintuc";
$news = pdo_query($sql);

if (!empty($news)) {
    echo "Found " . count($news) . " news records:\n\n";
    foreach ($news as $n) {
        echo "ID: " . $n['id'] . "\n";
        echo "Title: " . $n['tieu_de'] . "\n";
        echo "Image: " . $n['hinh_anh'] . "\n";
        echo "---\n\n";
    }
} else {
    echo "No news found in database\n";
}

// Check if files exist
echo "\n=== FILE CHECK ===\n\n";
$test_path = "Trang-admin/assets/news/1764929528_6932aff8ce675.webp";
echo "Checking: " . $test_path . "\n";
echo "Exists: " . (file_exists($test_path) ? "YES" : "NO") . "\n";
echo "Full path: " . realpath($test_path) . "\n";

// Test from Trang-nguoi-dung perspective
echo "\n=== FROM TRANG-NGUOI-DUNG/VIEW PERSPECTIVE ===\n\n";
$relative_path = "../../Trang-admin/assets/news/1764929528_6932aff8ce675.webp";
$check_path = dirname(__FILE__) . "/Trang-nguoi-dung/view/" . $relative_path;
echo "Relative from view: " . $relative_path . "\n";
echo "Check path: " . $check_path . "\n";
echo "Exists: " . (file_exists($check_path) ? "YES" : "NO") . "\n";
?>
