<?php
// Test script để kiểm tra hàm lc_list_grouped_for_approval
include_once 'model/pdo.php';
include_once 'model/lichchieu.php';

echo "<h1>Test Hàm lc_list_grouped_for_approval</h1>";

// Test cho trạng thái 'cho_duyet'
echo "<h3>Lịch chờ duyệt:</h3>";
$cho_duyet = lc_list_grouped_for_approval('cho_duyet');
echo "<pre>";
print_r($cho_duyet);
echo "</pre>";

// Test cho trạng thái 'da_duyet'
echo "<h3>Lịch đã duyệt:</h3>";
$da_duyet = lc_list_grouped_for_approval('da_duyet');
echo "<pre>";
print_r($da_duyet);
echo "</pre>";
?>