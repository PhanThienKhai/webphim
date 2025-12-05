
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
            
            <?php if (isset($_SESSION['diem_cong_moi']) && $_SESSION['diem_cong_moi'] > 0): ?>
            <!-- Thông báo tích điểm -->
            <div style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; padding: 20px; border-radius: 15px; text-align: center; margin: 20px 0; box-shadow: 0 4px 15px rgba(255,215,0,0.3);">
                <h3 style="margin: 0 0 10px 0; font-size: 1.5rem;">
                    🎉 Chúc mừng! Bạn nhận được <strong><?= number_format($_SESSION['diem_cong_moi']) ?> điểm</strong>
                </h3>
                <p style="margin: 0; font-size: 1rem; opacity: 0.9;">
                    <?php if (isset($_SESSION['hang_moi'])): ?>
                        🏆 Bạn đã được nâng hạng lên <strong><?= $_SESSION['hang_moi'] ?></strong>!<br>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['diem_da_doi']) && $_SESSION['diem_da_doi'] > 0): ?>
                        ⭐ Đã sử dụng <?= number_format($_SESSION['diem_da_doi']) ?> điểm để giảm giá<br>
                    <?php endif; ?>
                    Tổng điểm hiện tại: <strong><?= number_format($_SESSION['user']['diem_tich_luy'] ?? 0) ?> điểm</strong>
                </p>
            </div>
            <?php 
                unset($_SESSION['diem_cong_moi']);
                unset($_SESSION['hang_moi']);
                unset($_SESSION['diem_da_doi']);
            endif; ?>

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

                        <div class="ticket-primery" style="position: relative;">
                            <div style="position: absolute; top: 68px; right: 41px; width: 67px; height: 68px; background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                <img src="view/qr.php?data='<?php echo urlencode("http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/webphim/Trang-nguoi-dung/index.php?act=quetve&id=" . $id); ?>.'" style="width: 80px; height: 80px; object-fit: contain;" />
                            </div>
                            <span class="ticket__item ticket__item--primery ticket__film" style="display= flex">Phim: <br><strong class="ticket__movie"><?= $tieu_de ?></strong></span>
                            <span class="ticket__item ticket__time">🪑 Ghế: <?= $ghe ?></span>
                            <span class="ticket__item ticket__time">🍿 Combo: <?= $combo ?></span>
                        </div>
                    </div>
                    <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">Galaxy Studio</div></div>
                </div>
            </div>
            <div style="text-align:center;margin-top:16px">
                <a class="btn btn-md btn--primary" href="view/ve_invoice.php?id=<?= $id ?>" target="_blank" rel="noopener" style="color: #000;">Tải / In hóa đơn (PDF)</a>
            </div>
        </div>
    </section>
