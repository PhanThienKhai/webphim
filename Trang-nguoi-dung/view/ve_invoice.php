<?php
// Simple printable invoice page. Opens in a new tab and user can Print -> Save as PDF from browser.
// Expects ?id=<hoa_don_id> (or ticket id). We'll try to load invoice data using existing model functions.

require_once __DIR__ . "/../model/pdo.php";
require_once __DIR__ . "/../model/ve.php";
require_once __DIR__ . "/../model/hoadon.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$load = null;
// Try to load invoice by hoa_don id first
if ($id) {
    // load_ve_tt expects hoa_don id in ve model; alternatively loadone_vephim works for ticket id.
    $load = load_ve_tt($id); // will return invoice + ticket summary when id is hoa_don
    if (!$load) {
        // fallback: try to load ticket by id
        $load = loadone_vephim($id);
    }
}

if (!$load) {
    echo "<p>Không tìm thấy hóa đơn/vé với id đã cho.</p>";
    exit;
}

// Normalize fields used in ve_tt.php
$id_ve = $load['id'] ?? $id;
$tieu_de = $load['tieu_de'] ?? ($load['tieu_de'] ?? '');
$ngay_chieu = $load['ngay_chieu'] ?? ($load['ngay_chieu'] ?? '');
$thoi_gian_chieu = $load['thoi_gian_chieu'] ?? ($load['thoi_gian_chieu'] ?? '');
$tenphong = $load['tenphong'] ?? ($load['tenphong'] ?? '');
$ghe = $load['ghe'] ?? ($load['ghe'] ?? '');
$combo = $load['combo'] ?? ($load['combo'] ?? '');
$thanh_tien = $load['thanh_tien'] ?? ($load['price'] ?? ($load['thanh_tien'] ?? 0));
$name = $load['name'] ?? '';
$ngaytt = $load['ngay_tt'] ?? ($load['ngay_dat'] ?? date('Y-m-d H:i:s'));

?><!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hóa đơn - Vé xem phim #<?= htmlspecialchars($id_ve) ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#222; padding:20px; }
        .invoice { max-width:800px; margin:0 auto; border:1px solid #e5e5e5; padding:20px; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .brand { font-size:20px; font-weight:700; }
        .meta { text-align:right; }
        table { width:100%; border-collapse:collapse; margin-top:16px }
        th, td { padding:8px; border:1px solid #ddd; }
        .total { text-align:right; font-size:18px; font-weight:700; }
        @media print {
            .no-print { display:none }
            body { padding:0 }
            .invoice { border:none; box-shadow:none }
        }
        .btn { display:inline-block; padding:10px 14px; background:#007bff; color:#fff; text-decoration:none; border-radius:6px }
    </style>
</head>
<body>
<div class="invoice">
    <div class="header">
        <div>
            <div class="brand">Galaxy Studio</div>
            <div>Hóa đơn vé xem phim</div>
        </div>
        <div class="meta">
            <div>Hóa đơn / Vé: <strong>#<?= htmlspecialchars($id_ve) ?></strong></div>
            <div>Ngày: <?= htmlspecialchars($ngaytt) ?></div>
            <div>Khách hàng: <?= htmlspecialchars($name) ?></div>
        </div>
    </div>

    <h3 style="margin-top:18px">Chi tiết suất chiếu</h3>
    <table>
        <tr><th>Phim</th><td><?= htmlspecialchars($tieu_de) ?></td></tr>
        <tr><th>Rạp / Phòng</th><td><?= htmlspecialchars('Galaxy Studio Gò Vấp') ?> / <?= htmlspecialchars($tenphong) ?></td></tr>
        <tr><th>Ngày / Giờ</th><td><?= htmlspecialchars($ngay_chieu) ?> - <?= htmlspecialchars($thoi_gian_chieu) ?></td></tr>
        <tr><th>Ghế</th><td><?= htmlspecialchars($ghe) ?></td></tr>
        <tr><th>Combo</th><td><?= htmlspecialchars($combo) ?></td></tr>
    </table>

    <h3 style="margin-top:18px">Thanh toán</h3>
    <table>
        <tr><th>Mô tả</th><th>Thành tiền</th></tr>
        <tr><td>Vé xem phim</td><td style="text-align:right"><?= number_format($thanh_tien) ?> vnđ</td></tr>
        <tr><td class="total">TỔNG CỘNG</td><td class="total"><?= number_format($thanh_tien) ?> vnđ</td></tr>
    </table>

    <p style="margin-top:18px">Lưu ý: Đây là hóa đơn điện tử. Vui lòng trình mã vé cho quầy khi tới rạp.</p>

    <div style="margin-top:18px; display:flex; gap:12px; align-items:center">
        <a href="#" onclick="window.print(); return false;" class="btn no-print">In / Lưu PDF</a>
        <a href="../index.php?act=ctve&id=<?= htmlspecialchars($id_ve) ?>" class="no-print">Xem chi tiết vé</a>
    </div>
</div>
</body>
</html>