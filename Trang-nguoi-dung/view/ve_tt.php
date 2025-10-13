
<?php
include "view/search.php";
    extract($load_ve_tt);
    
    // Lấy thông tin rạp, nếu không có thì dùng default
    $ten_rap_hienthi = !empty($ten_rap) ? $ten_rap : 'Galaxy Studio Gò Vấp';
    $dia_chi_hienthi = !empty($dia_chi_rap) ? $dia_chi_rap : 'Địa chỉ chưa cập nhật';
    ?>
    <section class="container">
        <div class="order-container">
            <div class="order">
                <img class="order__images" alt='' src="images/tickets.png">
                <p class="order__title">Cảm ơn <br><span class="order__descript">bạn đã mua vé thành công</span></p>
            </div>

            <div class="ticket">
                <div class="ticket-position">
                    <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text">Galaxy Studio</div> </div>
                    <div class="ticket__inner">
                        <div class="ticket-secondary">
                            <span class="ticket__item">Mã vé: <strong class="ticket__number"><?= $id ?></strong></span>
                            <span class="ticket__item ticket__date"><?= $ngay_chieu ?></span>
                            <span class="ticket__item ticket__time"><?= $thoi_gian_chieu ?></span>
                            <span class="ticket__item">🏢 Rạp: <span class="ticket__cinema"><?= $ten_rap_hienthi ?></span></span>
                            <span class="ticket__item">📍 Địa chỉ: <span class="ticket__cinema"><?= $dia_chi_hienthi ?></span></span>
                            <span class="ticket__item">🚪 Phòng: <strong class="ticket__number"><?= $tenphong ?></strong></span>
                            <span class="ticket__item ticket__price">💰 Giá: <strong class="ticket__cost"><?= number_format($thanh_tien) ?> vnđ</strong></span>
                        </div>

                        <div class="ticket-primery">
                            <span class="ticket__item ticket__item--primery ticket__film" style="display= flex">Phim: <br><strong class="ticket__movie"><?= $tieu_de ?></strong></span>
                            <span class="ticket__item ticket__time">🪑 Ghế: <?= $ghe ?></span>
                            <span class="ticket__item ticket__time">🍿 Combo: <?= $combo ?></span>
                        </div>
                    </div>
                    <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">Galaxy Studio</div></div>
                </div>
            </div>
            <div style="text-align:center;margin-top:16px">
                <a class="btn btn-md btn--primary" href="view/ve_invoice.php?id=<?= $id ?>" target="_blank" rel="noopener">Tải / In hóa đơn (PDF)</a>
            </div>
        </div>
    </section>
