<?php include "view/search.php";
extract($phim);

?>

<style>
/* Small local styles to make the rạp selector match site spacing */
.rap-select-wrap{margin:8px 0 18px;}
.rap-select{padding:8px 10px;border:1px solid #d8d8d8;border-radius:4px;background:#fff;min-width:320px}
.date-pill{display:inline-block;background:#ffd564;padding:6px 12px;border-radius:6px;margin:6px 8px 6px 0}

/* Enhanced select box */
.rap-select-box{display:flex;align-items:center;gap:12px;margin:8px 0}
.rap-select-icon{width:18px;height:18px;border-radius:3px;background:#ffd564;flex:0 0 18px;box-shadow:0 1px 0 rgba(0,0,0,0.06);}
.rap-select-inner{position:relative;display:inline-block}
.rap-select{appearance:none;-webkit-appearance:none;-moz-appearance:none;padding:10px 36px 10px 12px;border-radius:6px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(16,24,40,0.04);min-width:360px;background:#fff}
.rap-select-arrow{position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#666;font-size:14px}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}

</style>
</style>

<div class="wrapper">

    <!-- Main content -->

    <section class="container">
        <div class="order-container">
            <div class="order">
                <img class="order__images" alt='' src="images/tickets.png">
                <p class="order__title">Book a ticket <br><span class="order__descript">Tận Hưởng Thời Gian Xem Phim Vui Vẻ</span></p>

            </div>
        </div>
        <div class="order-step-area">
            <div class="order-step first--step">1  Chọn Lịch Chiếu &amp; Thời Gian</div>
        </div>
    <h2 class="page-heading heading--outcontainer">PHIM BẠN CHỌN</h2>
    </section>

    <div class="choose-film">
        <div class="swiper-container">
            <div class="swiper-wrapper">

            </div>
        </div>
    </div>

    <section class="container">
        <div class="col-sm-12">
            <div class="choose-indector choose-indector--film">
                <strong>Phim Đã Chọn </strong><span class="choosen-area"><?= $tieu_de?></span>
            </div>
            <div class="choose-container choose-container--short">

            </div>
            <!-- Chọn rạp chiếu -->
            <h2 class="page-heading">CHỌN RẠP CHIẾU</h2>
            <div class="choose-container choose-container--short">
                <div class="rap-select-box">
                    <div class="rap-select-icon"></div>
                    <label for="select_rap" class="sr-only">Chọn rạp:</label>
                    <div class="rap-select-inner">
                        <select id="select_rap" class="rap-select" onchange="onRapChange(this)">
                    <option value="0">-- Chọn rạp --</option>
                    <?php
                        // Build dropdown list: active raps first, then other raps (no duplicates)
                        $raps_by_id = array();
                        $active_ids = array();
                        if (isset($activeRaps) && is_array($activeRaps)) {
                            foreach ($activeRaps as $r) {
                                $raps_by_id[(int)$r['id']] = $r;
                                $active_ids[(int)$r['id']] = true;
                            }
                        }
                        if (isset($allRaps) && is_array($allRaps)) {
                            foreach ($allRaps as $r) {
                                $raps_by_id[(int)$r['id']] = $r;
                            }
                        }
                        $raps = array_values($raps_by_id);
                        foreach ($raps as $r) {
                            $sel = (isset($selected_rap) && $selected_rap == $r['id']) ? 'selected' : '';
                            $label = isset($r['ten_rap']) ? $r['ten_rap'] : (isset($r['name']) ? $r['name'] : ('Rạp ' . $r['id']));
                            // append note for rạp without upcoming showtimes
                            if (!isset($active_ids[(int)$r['id']])) {
                                $label .= ' (không có lịch chiếu sắp tới)';
                            }
                            echo '<option value="' . (int)$r['id'] . '" ' . $sel . '>' . htmlspecialchars($label) . '</option>';
                        }
                    ?>
                        </select>
                        <div class="rap-select-arrow">▾</div>
                    </div>
                </div>
                <script>
                    function onRapChange(sel){
                        const id_rap = sel.value;
                        const url = new URL(window.location.href);
                        if(id_rap && id_rap !== '0') url.searchParams.set('id_rap', id_rap); else url.searchParams.delete('id_rap');
                        // reload the page to scope show dates
                        window.location.href = url.toString();
                    }
                </script>
            </div>
            <!-- ///////////////////////////////////////// -->
            <h2 class="page-heading">CHỌN LỊCH CHIẾU</h2>
    
            <div class="choose-container choose-container--short" class="col-sm-12">
                <?php
                if (empty($lc)) {
                    echo 'Không có lịch chiếu.';
                } else {
                    foreach ($lc as $l) {
                        extract($l);

                        if ($l['ngay_chieu'] > $realtime) {
                            $rap_query = isset($selected_rap) && $selected_rap ? '&id_rap=' . (int)$selected_rap : '';
                            ?>
                            <a href="index.php?act=datve&id=<?= $phim['id'] ?>&id_lc=<?= $l['id'] ?><?= $rap_query ?>">
                                <span class="date-pill"><?= $l['ngay_chieu'] ?></span>
                            </a>
                            <?php
                        }

                    }
                ?></div>
                <h2 class="page-heading" style="margin-top: 100px;">CHỌN THỜI GIAN CHIẾU</h2>
                <div class="time-select time-select--wide">
                    <div class="time-select__group group--first">
                        <div class="col-sm-3">
                            <p class="time-select__place">
                                <?php
                                $selected_name = 'Chưa chọn rạp';
                                if (isset($selected_rap) && $selected_rap) {
                                    foreach ($raps as $rr) {
                                        if ((int)$rr['id'] === (int)$selected_rap) {
                                            $selected_name = isset($rr['ten_rap']) ? $rr['ten_rap'] : (isset($rr['name']) ? $rr['name'] : 'Rạp ' . $rr['id']);
                                            break;
                                        }
                                    }
                                }
                                echo htmlspecialchars($selected_name);
                                ?>
                            </p>
                        </div>
                        <?php foreach ($khunggio as $g) { ?>
                        <ul class="col-sm-1 items-wrap">
                            <a href="index.php?act=datve2&id=<?= $phim['id'] ?>&id_lc=<?= $g['id_lich_chieu'] ?>&id_g=<?= $g['id'] ?><?= isset($selected_rap) && $selected_rap ? '&id_rap=' . (int)$selected_rap : '' ?>">
                                <li class="time-select__item" data-time='<?= $g['thoi_gian_chieu'] ?>'><?= $g['thoi_gian_chieu'] ?></li>
                                </a>

                        </ul>
                        <?php } } ?>
                    </div>

        </div>

    </section>

    <div class="clearfix"></div>
    <div class="clearfix"></div>
</div>

<!-- open/close -->

