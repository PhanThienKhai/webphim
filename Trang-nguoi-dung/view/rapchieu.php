<?php
// Dynamic list of rạp. Index.php should provide $allRaps (from model/rap.php)
// Optional ?id_rap= to highlight/show a specific rạp at top
$selectedId = isset($_GET['id_rap']) ? (int)$_GET['id_rap'] : null;
$placeholderImg = 'login-ui2/login-ui2/assets/galaxygovap.jpg';

// Find selected rap if any
$selectedRap = null;
if ($selectedId && !empty($allRaps)) {
    foreach ($allRaps as $r) {
        if ((int)$r['id'] === $selectedId) { $selectedRap = $r; break; }
    }
}
?>

<?php if ($selectedRap) : ?>
    <div style="max-width: 900px; margin: 24px auto;">
        <h2 style="color: #d35400;">🎬 <?= htmlspecialchars($selectedRap['ten_rap']) ?></h2>
        <img src="<?= (!empty($selectedRap['logo']) ? htmlspecialchars($selectedRap['logo']) : $placeholderImg) ?>" alt="<?= htmlspecialchars($selectedRap['ten_rap']) ?>" style="width:100%;border-radius:10px;margin-bottom:20px;">
        <p style="font-family: Arial, sans-serif;line-height:1.6;color:#333;"><?= nl2br(htmlspecialchars($selectedRap['mo_ta'] ?? '')) ?></p>
    </div>
<?php else: ?>
    <div style="max-width: 900px; margin: 24px auto;">
        <h2 style="color: #d35400;">🎬 Rạp chiếu</h2>
        <p style="font-family: Arial, sans-serif;line-height:1.6;color:#333;">Danh sách tất cả rạp chiếu. Bạn có thể thêm ảnh (`logo`) và mô tả (`mo_ta`) trong bảng `rap_chieu` để hiển thị đẹp hơn.</p>
    </div>
<?php endif; ?>

<style>
.rap-list {max-width:1100px;margin:24px auto;display:flex;flex-direction:column;gap:24px;padding-bottom:48px}
.rap-item {background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);padding:18px;display:flex;gap:18px;align-items:flex-start}
.rap-img {width:320px;flex-shrink:0;border-radius:8px;overflow:hidden}
.rap-img img{width:100%;height:200px;object-fit:cover}
.rap-body{flex:1}
.rap-title {font-size:20px;font-weight:700;color:#007bff;margin-bottom:6px}
.rap-address {font-size:14px;color:#333;margin-bottom:12px}
.rap-map {width:100%;height:200px;border-radius:8px;overflow:hidden;margin-bottom:12px}
.rap-btn {display:inline-block;padding:8px 18px;background:#28a745;color:#fff;border:none;border-radius:5px;font-size:15px;text-decoration:none;transition:background 0.2s}
.rap-btn:hover {background:#1e7e34}
@media (max-width:900px){.rap-item{flex-direction:column}.rap-img img{height:220px}}
</style>

<div class="rap-list">
    <?php if (!empty($allRaps) && is_array($allRaps)) {
        foreach ($allRaps as $r) {
            $img = !empty($r['logo']) ? $r['logo'] : $placeholderImg;
            $address = $r['dia_chi'] ?? '';
            $mapSrc = 'https://www.google.com/maps?q=' . urlencode($address) . '&output=embed';
            $dirLink = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($address);
            $detailLink = 'index.php?act=rapchieu&id_rap=' . $r['id'];
    ?>
    <div class="rap-item">
        <div class="rap-img"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($r['ten_rap']) ?>"></div>
        <div class="rap-body">
            <div class="rap-title"><?= htmlspecialchars($r['ten_rap']) ?></div>
            <div class="rap-address"><?= htmlspecialchars($address) ?></div>
            <div class="rap-map"><iframe src="<?= $mapSrc ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
            <a class="rap-btn" href="<?= $dirLink ?>" target="_blank">Chỉ đường</a>
            <a class="rap-btn" href="<?= $detailLink ?>" style="background:#007bff;margin-left:8px">Xem chi tiết</a>
        </div>
    </div>
    <?php }
    } else { ?>
        <div style="padding:24px;background:#fff;border-radius:8px;max-width:900px;margin:auto;text-align:center">Hiện chưa có rạp nào trong hệ thống.</div>
    <?php } ?>
</div>
