<?php
// Printable ticket page for PDF export. Opens in new tab and triggers print.
// Unified print view for both invoice and ticket display with QR code.
require_once __DIR__ . "/../model/pdo.php";
require_once __DIR__ . "/../model/ve.php";
require_once __DIR__ . "/../model/hoadon.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$loadone_ve = null;

if ($id) {
    // Try to load by hoa_don id first
    $loadone_ve = load_ve_tt($id);
    if (!$loadone_ve) {
        // Fallback: try to load by ticket id
        $loadone_ve = loadone_vephim($id);
    }
}

if (!$loadone_ve) {
    echo '<p>Không tìm thấy vé/hóa đơn.</p>';
    exit;
}

// Build QR URL - standardized across all views
// Detect protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$qr_data = $protocol . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/Trang-nguoi-dung/index.php?act=quetve&id=" . $loadone_ve['id'];

// Lấy thông tin rạp
$ten_rap_hienthi = !empty($loadone_ve['ten_rap']) ? $loadone_ve['ten_rap'] : 'Galaxy Studio Gò Vấp';
$dia_chi_hienthi = !empty($loadone_ve['dia_chi_rap']) ? $loadone_ve['dia_chi_rap'] : 'Địa chỉ chưa cập nhật';

?><!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vé - #<?= htmlspecialchars($loadone_ve['id']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .order-container { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        
        .order { text-align: center; margin-bottom: 30px; }
        .order__images { width: 60px; height: 60px; margin-bottom: 15px; opacity: 0.8; }
        .order__title { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .order__descript { font-size: 16px; color: #999; display: block; font-weight: 400; }
        
        .ticket { margin: 30px 0; }
        .ticket-position { display: flex; flex-direction: column; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: #fff; }
        
        .ticket__indecator { height: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; }
        .indecator-text { font-size: 10px; color: #fff; font-weight: 700; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); }
        .indecator--post { display: flex; justify-content: flex-end; }
        .indecator--post .indecator-text { left: auto; right: 12px; }
        
        .ticket__inner { display: flex; gap: 0; }
        .ticket-secondary { flex: 1; padding: 20px 24px; border-right: 2px solid #e5e7eb; }
        .ticket-primery { flex: 1; padding: 20px 24px; position: relative; }
        
        .ticket__item { display: block; margin-bottom: 12px; font-size: 13px; line-height: 1.5; }
        .ticket__item:last-child { margin-bottom: 0; }
        
        .ticket__number { color: #667eea; font-weight: 700; font-size: 16px; }
        .ticket__date { font-weight: 600; color: #333; font-size: 14px; }
        .ticket__time { color: #666; }
        .ticket__cinema { color: #666; }
        .ticket__cost { color: #e74c3c; font-size: 16px; }
        .ticket__movie { color: #667eea; font-weight: 700; font-size: 18px; }
        .ticket__film { font-size: 14px !important; margin-bottom: 16px !important; }
        .ticket__item--primery { font-weight: 600; }
        
        .qr-box { position: absolute; top: 68px; right: 41px; width: 67px; height: 68px; background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .qr-box img { width: 80px; height: 80px; object-fit: contain; }
        
        .note { margin-top: 20px; text-align: center; font-size: 12px; color: #999; font-style: italic; }
        
        .button-group { margin-top: 24px; text-align: center; }
        .btn { display: inline-block; padding: 12px 20px; margin: 0 8px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; }
        .btn-print { background: #667eea; color: #fff; }
        .btn-print:hover { background: #5568d3; }
        .btn-back { background: #e5e7eb; color: #333; }
        .btn-back:hover { background: #d1d5db; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .order-container { box-shadow: none; padding: 20px; }
            .button-group { display: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="order-container">
        <div class="order">
            <img class="order__images" alt="Ticket" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%23667eea' width='100' height='100' rx='10'/%3E%3Ctext x='50' y='50' font-size='40' fill='white' text-anchor='middle' dy='.3em'%3E🎬%3C/text%3E%3C/svg%3E">
            <p class="order__title">Vé xem phim<br><span class="order__descript">In để sử dụng tại rạp</span></p>
        </div>

        <div class="ticket">
            <div class="ticket-position">
                <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text"><?= htmlspecialchars($ten_rap_hienthi) ?></div></div>
                <div class="ticket__inner">
                    <div class="ticket-secondary">
                        <span class="ticket__item">Mã vé: <strong class="ticket__number"><?= htmlspecialchars($loadone_ve['id']) ?></strong></span>
                        <span class="ticket__item ticket__date"><?= htmlspecialchars($loadone_ve['ngay_chieu']) ?></span>
                        <span class="ticket__item ticket__time"><?= htmlspecialchars($loadone_ve['thoi_gian_chieu']) ?></span>
                        <span class="ticket__item">🏢 Rạp: <span class="ticket__cinema"><?= htmlspecialchars($ten_rap_hienthi) ?></span></span>
                        <span class="ticket__item">📍 Địa chỉ: <span class="ticket__cinema"><?= htmlspecialchars($dia_chi_hienthi) ?></span></span>
                        <span class="ticket__item">🚪 Phòng: <strong class="ticket__number"><?= htmlspecialchars($loadone_ve['tenphong']) ?></strong></span>
                        <span class="ticket__item ticket__price">💰 Giá: <strong class="ticket__cost"><?= number_format($loadone_ve['price'] ?? $loadone_ve['thanh_tien'] ?? 0) ?> vnđ</strong></span>
                    </div>

                    <div class="ticket-primery">
                        <div class="qr-box">
                            <img src="qr.php?data=<?= urlencode($qr_data) ?>" alt="QR Code" />
                        </div>
                        <span class="ticket__item ticket__item--primery ticket__film">Phim: <br><strong class="ticket__movie"><?= htmlspecialchars($loadone_ve['tieu_de']) ?></strong></span>
                        <span class="ticket__item ticket__time">🪑 Ghế: <?= htmlspecialchars($loadone_ve['ghe']) ?></span>
                        <span class="ticket__item ticket__time">🍿 Combo: <?= htmlspecialchars($loadone_ve['combo']) ?></span>
                    </div>
                </div>
                <div class="ticket__indecator indecator--post"><div class="indecator-text post--text"><?= htmlspecialchars($ten_rap_hienthi) ?></div></div>
            </div>
        </div>

        <p class="note">Vui lòng xuất trình mã QR hoặc mã vé này cho nhân viên tại cổng kiểm soát</p>

        <div class="button-group no-print">
            <button class="btn btn-print" onclick="window.print(); return false;">🖨️ In / Lưu PDF</button>
            <a href="index.php?act=ctve&id=<?= htmlspecialchars($loadone_ve['id']) ?>" class="btn btn-back">← Quay lại</a>
        </div>
    </div>
</div>

<script>
// Auto-open print dialog when opened from another page
window.addEventListener('load', function(){
    setTimeout(function(){
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