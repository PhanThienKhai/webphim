<?php
// Printable ticket page for PDF export. Opens in new tab and triggers print.
require_once __DIR__ . "/../model/pdo.php";
require_once __DIR__ . "/../model/ve.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo '<p>Vé không hợp lệ.</p>';
    exit;
}
$loadone_ve = loadone_vephim($id);
if (!$loadone_ve) {
    echo '<p>Không tìm thấy vé.</p>';
    exit;
}

// Build QR URL same as chitiet_ve.php
$qr_data = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['REQUEST_URI']) . "/index.php?act=quetve&id=" . $loadone_ve['id'];

?><!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vé - #<?= htmlspecialchars($loadone_ve['id']) ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#222; padding:20px; background:#f6f6f6 }
        .ticket-wrap { max-width:720px; margin:0 auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 4px 18px rgba(0,0,0,0.06) }
        .ticket-head { display:flex; justify-content:space-between; align-items:center }
        .brand { font-weight:700; font-size:20px }
        .ticket-body { margin-top:16px; display:flex; gap:18px }
        .ticket-left { flex:1 }
        .ticket-right { width:220px; text-align:center }
        .qr { background:#fff; padding:8px; border-radius:6px }
        table { width:100%; border-collapse:collapse }
        td { padding:6px 8px }
        .label { color:#666; font-size:13px }
        .value { font-weight:700 }
        .note { margin-top:12px; color:#555 }
        @media print { .no-print{ display:none } }
        .btn { display:inline-block; padding:10px 14px; background:#007bff; color:#fff; text-decoration:none; border-radius:6px }
    </style>
</head>
<body>
<div class="ticket-wrap">
    <div class="ticket-head">
        <div class="brand">Galaxy Studio</div>
        <div class="meta">Vé: <strong>#<?= htmlspecialchars($loadone_ve['id']) ?></strong></div>
    </div>

    <div class="ticket-body">
        <div class="ticket-left">
            <table>
                <tr><td class="label">Phim</td><td class="value"><?= htmlspecialchars($loadone_ve['tieu_de']) ?></td></tr>
                <tr><td class="label">Rạp / Phòng</td><td class="value">Galaxy Studio Gò Vấp / <?= htmlspecialchars($loadone_ve['tenphong']) ?></td></tr>
                <tr><td class="label">Ngày chiếu</td><td class="value"><?= htmlspecialchars($loadone_ve['ngay_chieu']) ?></td></tr>
                <tr><td class="label">Giờ chiếu</td><td class="value"><?= htmlspecialchars($loadone_ve['thoi_gian_chieu']) ?></td></tr>
                <tr><td class="label">Ghế</td><td class="value"><?= htmlspecialchars($loadone_ve['ghe']) ?></td></tr>
                <tr><td class="label">Giá</td><td class="value"><?= number_format($loadone_ve['price']) ?> VNĐ</td></tr>
                <tr><td class="label">Combo</td><td class="value"><?= htmlspecialchars($loadone_ve['combo']) ?></td></tr>
                <tr><td class="label">Người đặt</td><td class="value"><?= htmlspecialchars($loadone_ve['name']) ?></td></tr>
            </table>
            <p class="note">Mã vé: <strong><?= htmlspecialchars($loadone_ve['id']) ?></strong></p>
            <p class="note">Vui lòng xuất trình mã QR này cho nhân viên tại cổng kiểm soát.</p>
        </div>
        <div class="ticket-right">
            <div class="qr">
                <img src="qr.php?data=<?= urlencode($qr_data) ?>" alt="QR">
            </div>
            <div style="margin-top:12px">Ngày đặt: <?= htmlspecialchars($loadone_ve['ngay_dat']) ?></div>
        </div>
    </div>

    <div style="margin-top:18px; text-align:center">
        <a href="#" onclick="window.print(); return false;" class="btn no-print">In / Lưu PDF</a>
        <a href="index.php?act=ctve&id=<?= htmlspecialchars($loadone_ve['id']) ?>" class="no-print" style="margin-left:8px">Quay lại chi tiết vé</a>
    </div>
</div>

<script>
// Auto-open print dialog shortly after load to make it easy for users
window.addEventListener('load', function(){
    setTimeout(function(){
        // Only auto-open print if window was opened from another page (target=_blank)
        try {
            if (window.opener || window.location.search.indexOf('autoprint=1') !== -1) {
                window.print();
            }
        } catch(e){}
    }, 400);
});
</script>
</body>
</html>