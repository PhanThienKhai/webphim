<?php

// Ensure the seat-map helpers are available (they live under Trang-admin/model)
// path: ../Trang-admin/model/phong_ghe.php relative to this file
@include_once __DIR__ . '/../Trang-admin/model/phong_ghe.php';

$id_kgc = $_SESSION['tong']['id_gio'] ?? 0;
$id_lc = $_SESSION['tong']['id_lichchieu'] ?? 0;
$id_phim = $_SESSION['tong']['id_phim'] ?? 0;

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
        // map tier to a price bucket used elsewhere (10/20/30) — keeps existing CSS logic
        $price = 10;
        if ($s['tier'] === 'middle') $price = 20;
        if ($s['tier'] === 'expensive') $price = 30;
        $ghes[$r][] = [$n, $price, 'code' => $s['code'], 'active' => (int)$s['active']];
    }
    // Ensure rows are ordered by label
    ksort($ghes);
} else {
    // legacy static layout (keeps previous behaviour)
    $ghes = [
        'A' => [[1, 10], [2, 10], [3, 10], [4, 10], [5, 10], [6, 10], [7, 10],[8,10],[9,10]],
        'B' => [[1, 10], [2, 10], [3, 10], [4, 10], [5, 10], [6, 10], [7, 10],[8,10],[9,10]],
        'C' => [[1, 10], [2, 10], [3, 10], [4, 10], [5, 10], [6, 10], [7, 10],[8,10],[9,10]],
        'D' => [[1, 10], [2, 20], [3, 20], [4, 20], [5, 20], [6, 20], [7, 20],[8,20],[9,10]],
        'E' => [[1, 10], [2, 20], [3, 20], [4, 20], [5, 20], [6, 20], [7, 20],[8,20],[9,10]],
        'F' => [[1, 10], [2, 20], [3, 20], [4, 20], [5, 20], [6, 20], [7, 20],[8,20],[9,10]],
        'G' => [[1, 10], [2, 30], [3, 30], [4, 30], [5, 30], [6, 30], [7, 30],[8,30],[9,10]],
        'H' => [[1, 10], [2, 30], [3, 30], [4, 30], [5, 30], [6, 30], [7, 30],[8,30],[9,10]],
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
                    <li class="sits-price sits-price--cheap">100.000 VNĐ</li>
                    <li class="sits-price sits-price--middle">200.000 VNĐ</li>
                    <li class="sits-price sits-price--expensive">300.000 VNĐ</li>

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
                        <aside class="sits__line">
                            <span class="sits__indecator">A</span>
                            <span class="sits__indecator">B</span>
                            <span class="sits__indecator">C</span>
                            <span class="sits__indecator">D</span>
                            <span class="sits__indecator">E</span>
                            <span class="sits__indecator">F</span>
                            <span class="sits__indecator">G</span>
                            <span class="sits__indecator">H</span>

                        </aside> <aside class="sits__right">
                            <span class="sits__indecator">A</span>
                            <span class="sits__indecator">B</span>
                            <span class="sits__indecator">C</span>
                            <span class="sits__indecator">D</span>
                            <span class="sits__indecator">E</span>
                            <span class="sits__indecator">F</span>
                            <span class="sits__indecator">G</span>
                            <span class="sits__indecator">H</span>

                        </aside>

                        <?php foreach ($ghes as $key => $value) : ?>
                            <div class="sits__row">
                                <?php foreach ($value as $o) : ?>
                                    <?php
                                    // support two shapes for $o:
                                    // legacy: [col, price]
                                    // new: [col, price, 'code'=>..., 'active'=>...]
                                    $col = is_array($o) ? ($o[0] ?? null) : null;
                                    $price = is_array($o) ? ($o[1] ?? 10) : 10;
                                    $code = is_array($o) && isset($o['code']) ? $o['code'] : ($key . $col);
                                    $active = is_array($o) && isset($o['active']) ? (int)$o['active'] : 1;
                                    $place = $code;
                                    $class = '';

                                    if (in_array($place, $khoa_ghe_all)) {
                                        $class = 'sits__place sits-price--cheap';
                                    } else {
                                        if ($price == 10) {
                                            $class = 'sits__place sits-price--cheap';
                                        } elseif ($price == 20) {
                                            $class = 'sits__place sits-price--middle';
                                        } elseif ($price == 30) {
                                            $class = 'sits__place sits-price--expensive';
                                        }
                                    }
                                    if (!$active) $class .= ' sits-state--not';
                                    ?>

                                    <span class="<?= $class ?>" data-place='<?= htmlspecialchars($place) ?>' data-price='<?= $price ?>'><?= htmlspecialchars($place) ?></span>

                                <?php endforeach ?>
                            </div>
                        <?php endforeach ?><div class="sits">



                            <footer class="sits__number">
                                <span class="sits__indecator">1</span>
                                <span class="sits__indecator">2</span>
                                <span class="sits__indecator">3</span>
                                <span class="sits__indecator">4</span>
                                <span class="sits__indecator">5</span>
                                <span class="sits__indecator">6</span>
                                <span class="sits__indecator">7</span>
                                <span class="sits__indecator">8</span>
                                <span class="sits__indecator">9</span>

                            </footer>

                        </div>
                    </div></div>
            </div>

        </div>


</div>




