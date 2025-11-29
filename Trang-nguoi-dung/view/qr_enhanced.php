<?php
/**
 * QR Code Generator - Enhanced Version
 * Tạo mã QR lớn hơn, dễ quét hơn
 */

// Lấy dữ liệu từ URL
$data = isset($_GET['data']) ? $_GET['data'] : 'No data';
$size = isset($_GET['size']) ? (int)$_GET['size'] : 150; // Kích thước QR code (pixel)
$level = isset($_GET['level']) ? $_GET['level'] : QR_ECLEVEL_L; // Error correction level

// Tải thư viện QR code
require_once __DIR__ . '/../model/phpqrcode/qrlib.php';

// Thêm headers
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Tạo QR code với kích thước lớn hơn
// Module size = pixel/module ratio
$moduleSize = max(1, (int)($size / 20)); // Tính module size từ kích thước mong muốn

// Tạo QR code
QRcode::png($data, false, $level, $moduleSize, 2);
