<?php
require_once __DIR__ . '/../model/phpqrcode/qrlib.php';

$data = isset($_GET['data']) ? $_GET['data'] : 'No data';
QRcode::png($data, false, QR_ECLEVEL_L, 5);
?>