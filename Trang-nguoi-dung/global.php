<?php

// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Ensure the seat-map helpers are available (they live under Trang-admin/model)
// path: ../Trang-admin/model/phong_ghe.php relative to this file
@include_once __DIR__ . '/../Trang-admin/model/phong_ghe.php';

$id_kgc = $_SESSION['tong']['id_gio'] ?? 0;
$id_lc = $_SESSION['tong']['id_lichchieu'] ?? 0;
$id_phim = $_SESSION['tong']['id_phim'] ?? 0;

// Debug session data for seat locking
if (isset($_GET['debug'])) {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;'>";
    echo "<h4>Debug Session Data:</h4>";
    echo "<p>Current Time Slot ID: $id_kgc</p>";
    echo "<p>Schedule ID: $id_lc</p>";
    echo "<p>Movie ID: $id_phim</p>";
    echo "<p>Session mv: " . print_r($_SESSION['mv'] ?? 'Not set', true) . "</p>";
    echo "</div>";
}

// Get room pricing info
$room_pricing = [];
if ($id_kgc) {
    $kg = pdo_query_one("SELECT id_phong FROM khung_gio_chieu WHERE id = ?", $id_kgc);
    if ($kg) {
        $room_info = pdo_query_one("SELECT gia_thuong, gia_trung, gia_vip FROM phongchieu WHERE id = ?", $kg['id_phong']);
        if ($room_info) {
            $room_pricing = [
                'cheap' => (int)$room_info['gia_thuong'],
                'middle' => (int)$room_info['gia_trung'], 
                'expensive' => (int)$room_info['gia_vip']
            ];
        }
    }
}

// Fallback prices if no room pricing found
if (empty($room_pricing)) {
    $room_pricing = ['cheap' => 60000, 'middle' => 80000, 'expensive' => 100000];
}

// Try to load an explicit seat-map for the room tied to the selected time.
// If none exists, fall back to the old static $ghes array.
$seat_map = [];
if ($id_kgc) {
    $seat_map = pg_list_for_time($id_kgc);
}

// Build $ghes-like structure from $seat_map when available
if (!empty($seat_map)) {
    $ghes = [];
    foreach ($seat_map as $s) {
        $r = $s['row_label'];
        $n = (int)$s['seat_number'];
        // Use actual price from room pricing
        $price = $room_pricing[$s['tier']] ?? $room_pricing['cheap'];
        $ghes[$r][] = [$n, $price, 'code' => $s['code'], 'active' => (int)$s['active']];
    }
    // Ensure rows are ordered by label
    ksort($ghes);
} else {
    // legacy static layout (keeps previous behaviour)
    $ghes = [
        'A' => [[1, $room_pricing['cheap']], [2, $room_pricing['cheap']], [3, $room_pricing['cheap']], [4, $room_pricing['cheap']], [5, $room_pricing['cheap']], [6, $room_pricing['cheap']], [7, $room_pricing['cheap']],[8,$room_pricing['cheap']],[9,$room_pricing['cheap']]],
        'B' => [[1, $room_pricing['cheap']], [2, $room_pricing['cheap']], [3, $room_pricing['cheap']], [4, $room_pricing['cheap']], [5, $room_pricing['cheap']], [6, $room_pricing['cheap']], [7, $room_pricing['cheap']],[8,$room_pricing['cheap']],[9,$room_pricing['cheap']]],
        'C' => [[1, $room_pricing['cheap']], [2, $room_pricing['cheap']], [3, $room_pricing['cheap']], [4, $room_pricing['cheap']], [5, $room_pricing['cheap']], [6, $room_pricing['cheap']], [7, $room_pricing['cheap']],[8,$room_pricing['cheap']],[9,$room_pricing['cheap']]],
        'D' => [[1, $room_pricing['cheap']], [2, $room_pricing['middle']], [3, $room_pricing['middle']], [4, $room_pricing['middle']], [5, $room_pricing['middle']], [6, $room_pricing['middle']], [7, $room_pricing['middle']],[8,$room_pricing['middle']],[9,$room_pricing['cheap']]],
        'E' => [[1, $room_pricing['cheap']], [2, $room_pricing['middle']], [3, $room_pricing['middle']], [4, $room_pricing['middle']], [5, $room_pricing['middle']], [6, $room_pricing['middle']], [7, $room_pricing['middle']],[8,$room_pricing['middle']],[9,$room_pricing['cheap']]],
        'F' => [[1, $room_pricing['cheap']], [2, $room_pricing['middle']], [3, $room_pricing['middle']], [4, $room_pricing['middle']], [5, $room_pricing['middle']], [6, $room_pricing['middle']], [7, $room_pricing['middle']],[8,$room_pricing['middle']],[9,$room_pricing['cheap']]],
        'G' => [[1, $room_pricing['cheap']], [2, $room_pricing['expensive']], [3, $room_pricing['expensive']], [4, $room_pricing['expensive']], [5, $room_pricing['expensive']], [6, $room_pricing['expensive']], [7, $room_pricing['expensive']],[8,$room_pricing['expensive']],[9,$room_pricing['cheap']]],
        'H' => [[1, $room_pricing['cheap']], [2, $room_pricing['expensive']], [3, $room_pricing['expensive']], [4, $room_pricing['expensive']], [5, $room_pricing['expensive']], [6, $room_pricing['expensive']], [7, $room_pricing['expensive']],[8,$room_pricing['expensive']],[9,$room_pricing['cheap']]],
    ];
}

$khoa_ghe = khoa_ghe($id_kgc, $id_lc, $id_phim);
if (isset($khoa_ghe) && $khoa_ghe != array()) {
    $khoa_ghe_ = [];
    foreach ($khoa_ghe as $sub_array) {
        $khoa_ghe_ = array_merge($khoa_ghe_, $sub_array);
    }
    $khoa_ghe__ = implode(',', $khoa_ghe_);
    $khoa_ghe_all = explode(',', $khoa_ghe__);
} else {
    $khoa_ghe_all = array();
}
?>

<!-- Main content -->
<div class="place-form-area">
    <section class="container">
        <div class="order-container">
        </div>
        <div class="order-step-area">
            <div class="order-step first--step order-step--disable ">1.   Lịch Chiếu &amp; Thời Gian</div>
            <div class="order-step second--step">2. Chọn ghế </div>
        </div>

        <div class="choose-sits">
            <div class="choose-sits__info choose-sits__info--first">
                <ul>
                    <li class="sits-price marker--none"><strong>Giá </strong></li>
                    <li class="sits-price sits-price--cheap"><?= number_format($room_pricing['cheap']) ?> VNĐ</li>
                    <li class="sits-price sits-price--middle"><?= number_format($room_pricing['middle']) ?> VNĐ</li>
                    <li class="sits-price sits-price--expensive"><?= number_format($room_pricing['expensive']) ?> VNĐ</li>

                </ul>
            </div>

            <div class="choose-sits__info">
                <ul>
                    <li class="sits-state sits-state--not"> Đã được chọn</li>
                    <li class="sits-state sits-state--your">Lựa chọn của bạn </li>
                </ul>
            </div>
            <div class="ghe12">
                <div class=" col-lg-10 col-lg-offset-1">

                    <div class="sits-anchor">Màn Hình</div>

                    <div class="sits">
                        <?php
                        // Get all row labels from $ghes
                        $row_labels = array_keys($ghes);
                        ?>
                        <aside class="sits__line">
                            <?php foreach ($row_labels as $row_label): ?>
                            <span class="sits__indecator"><?= htmlspecialchars($row_label) ?></span>
                            <?php endforeach; ?>
                        </aside> 
                        <aside class="sits__right">
                            <?php foreach ($row_labels as $row_label): ?>
                            <span class="sits__indecator"><?= htmlspecialchars($row_label) ?></span>
                            <?php endforeach; ?>
                        </aside>

                        <?php foreach ($ghes as $key => $value) : ?>
                            <div class="sits__row">
                                <?php foreach ($value as $o) : ?>
                                    <?php
                                    // support two shapes for $o:
                                    // legacy: [col, price]
                                    // new: [col, price, 'code'=>..., 'active'=>...]
                                    $col = is_array($o) ? ($o[0] ?? null) : null;
                                    $price = is_array($o) ? ($o[1] ?? $room_pricing['cheap']) : $room_pricing['cheap'];
                                    $code = is_array($o) && isset($o['code']) ? $o['code'] : ($key . $col);
                                    $active = is_array($o) && isset($o['active']) ? (int)$o['active'] : 1;
                                    $place = $code;
                                    $class = '';

                                    // First determine base class by price
                                    if ($price == $room_pricing['cheap']) {
                                        $class = 'sits__place sits-price--cheap';
                                    } elseif ($price == $room_pricing['middle']) {
                                        $class = 'sits__place sits-price--middle';
                                    } elseif ($price == $room_pricing['expensive']) {
                                        $class = 'sits__place sits-price--expensive';
                                    } else {
                                        $class = 'sits__place sits-price--cheap'; // fallback
                                    }
                                    
                                    // Then apply state classes
                                    if (!$active) {
                                        $class .= ' sits-state--not'; // Ghế không hoạt động (admin tắt)
                                    } elseif (in_array($place, $khoa_ghe_all)) {
                                        $class .= ' sits-state--not'; // Ghế đã được đặt trong khung giờ này
                                    }
                                    ?>

                                    <span class="<?= $class ?>" data-place='<?= htmlspecialchars($place) ?>' data-price='<?= $price ?>'><?= htmlspecialchars($place) ?></span>

                                <?php endforeach ?>
                            </div>
                        <?php endforeach ?><div class="sits">



                            <?php
                            // Get max column number from all rows
                            $max_col = 0;
                            foreach ($ghes as $row) {
                                foreach ($row as $seat) {
                                    $col = is_array($seat) ? ($seat[0] ?? 0) : 0;
                                    if ($col > $max_col) $max_col = $col;
                                }
                            }
                            ?>
                            <footer class="sits__number">
                                <?php for ($i = 1; $i <= $max_col; $i++): ?>
                                <span class="sits__indecator"><?= $i ?></span>
                                <?php endfor; ?>
                            </footer>

                        </div>
                    </div></div>
            </div>

        </div>


</div>




