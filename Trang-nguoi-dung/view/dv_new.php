<?php include "view/search.php";
extract($phim);
?>

<style>
/* Cinema Selection Styles */
.cinema-selection {
    margin: 30px 0;
}

.cinema-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.cinema-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: white;
    position: relative;
}

.cinema-card:hover {
    border-color: #ffd564;
    box-shadow: 0 6px 20px rgba(255, 213, 100, 0.4);
    transform: translateY(-5px);
}

.cinema-card.active {
    border-color: #ffd564;
    background: linear-gradient(135deg, #fffbf0 0%, #fff9e6 100%);
    box-shadow: 0 4px 15px rgba(255, 213, 100, 0.3);
}

.cinema-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.cinema-card h3 {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin: 12px 0 8px 0;
}

.cinema-address {
    font-size: 13px;
    color: #666;
    margin: 8px 0;
    line-height: 1.5;
}

.cinema-badge {
    display: inline-block;
    background: #ffd564;
    padding: 6px 14px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: bold;
    margin-top: 10px;
    color: #333;
}

/* Date Selection Styles */
.date-selection {
    margin: 30px 0;
}

.date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.date-card {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: white;
}

.date-card:hover {
    border-color: #ffd564;
    box-shadow: 0 4px 12px rgba(255, 213, 100, 0.3);
    transform: translateY(-3px);
}

.date-card.active {
    border-color: #ffd564;
    background: linear-gradient(135deg, #ffd564 0%, #ffb347 100%);
    color: white;
}

.date-day {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
}

.date-number {
    font-size: 20px;
    font-weight: bold;
    margin: 8px 0;
}

.date-today-badge {
    display: inline-block;
    background: #ff6b6b;
    color: white;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 10px;
    margin-top: 5px;
}

.date-card.active .date-today-badge {
    background: #fff;
    color: #ff6b6b;
}

.date-showtimes {
    font-size: 12px;
    margin-top: 8px;
    opacity: 0.9;
}

/* Time Selection Styles */
.time-selection {
    margin: 30px 0;
}

.time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.time-card {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: white;
}

.time-card:hover:not(.disabled) {
    border-color: #ffd564;
    box-shadow: 0 4px 12px rgba(255, 213, 100, 0.3);
    transform: translateY(-3px);
}

.time-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f5f5f5;
}

.time-hour {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.time-room {
    font-size: 13px;
    color: #666;
    margin: 6px 0;
}

.time-seats {
    font-size: 12px;
    margin-top: 8px;
    padding: 4px 8px;
    background: #e8f5e9;
    border-radius: 6px;
    display: inline-block;
}

.time-card.disabled .time-seats {
    background: #ffebee;
    color: #c62828;
}

/* Breadcrumb style */
.booking-breadcrumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 30px;
    border-radius: 10px;
    margin: 20px 0;
    color: white;
}

.booking-breadcrumb-item {
    display: inline-block;
    margin-right: 15px;
    font-size: 14px;
}

.booking-breadcrumb-item.active {
    font-weight: bold;
    text-decoration: underline;
}

.booking-breadcrumb-arrow {
    margin: 0 10px;
    opacity: 0.7;
}

/* Info alert */
.info-alert {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 15px 20px;
    margin: 20px 0;
    border-radius: 6px;
}

.info-alert-icon {
    display: inline-block;
    margin-right: 10px;
    font-size: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .cinema-grid,
    .date-grid,
    .time-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="wrapper">
    <section class="container">
        <div class="order-container">
            <div class="order">
                <img class="order__images" alt='' src="images/tickets.png">
                <p class="order__title">Đặt vé xem phim <br><span class="order__descript">Tận Hưởng Thời Gian Xem Phim Vui Vẻ</span></p>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="booking-breadcrumb">
            <span class="booking-breadcrumb-item <?= $selected_rap == 0 ? 'active' : '' ?>">
                1. Chọn Rạp
            </span>
            <?php if ($selected_rap > 0): ?>
                <span class="booking-breadcrumb-arrow">→</span>
                <span class="booking-breadcrumb-item <?= empty($selected_date) ? 'active' : '' ?>">
                    2. Chọn Ngày
                </span>
            <?php endif; ?>
            <?php if (!empty($selected_date)): ?>
                <span class="booking-breadcrumb-arrow">→</span>
                <span class="booking-breadcrumb-item active">
                    3. Chọn Giờ
                </span>
            <?php endif; ?>
        </div>

        <h2 class="page-heading heading--outcontainer">PHIM BẠN CHỌN</h2>
        <div class="choose-indector choose-indector--film">
            <strong>Phim: </strong><span class="choosen-area"><?= htmlspecialchars($tieu_de) ?></span>
            <?php if (isset($rap_info) && $rap_info): ?>
                <strong style="margin-left: 30px;">Rạp: </strong>
                <span class="choosen-area"><?= htmlspecialchars($rap_info['ten_rap']) ?></span>
            <?php endif; ?>
            <?php if (!empty($selected_date)): ?>
                <strong style="margin-left: 30px;">Ngày: </strong>
                <span class="choosen-area"><?= date('d/m/Y', strtotime($selected_date)) ?></span>
            <?php endif; ?>
        </div>

        <!-- BƯỚC 1: CHỌN RẠP -->
        <?php if ($selected_rap == 0): ?>
            <div class="cinema-selection">
                <h2 class="page-heading">CHỌN RẠP CHIẾU</h2>
                
                <?php if (!empty($raps_showing)): ?>
                    <div class="cinema-grid">
                        <?php foreach ($raps_showing as $rap): ?>
                            <div class="cinema-card" 
                                 onclick="window.location.href='index.php?act=datve&id=<?= $id_phim ?>&id_rap=<?= $rap['id'] ?>'">
                                <div class="cinema-icon">🎬</div>
                                <h3><?= htmlspecialchars($rap['ten_rap']) ?></h3>
                                <p class="cinema-address">📍 <?= htmlspecialchars($rap['dia_chi']) ?></p>
                                <span class="cinema-badge"><?= $rap['so_suat'] ?> suất chiếu</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="info-alert">
                        <span class="info-alert-icon">ℹ️</span>
                        Phim này hiện chưa có lịch chiếu tại các rạp.
                    </div>
                <?php endif; ?>
            </div>

        <!-- BƯỚC 2: CHỌN NGÀY -->
        <?php elseif ($selected_rap > 0 && empty($selected_date)): ?>
            <div class="date-selection">
                <h2 class="page-heading">
                    CHỌN NGÀY CHIẾU TẠI <?= htmlspecialchars($rap_info['ten_rap']) ?>
                </h2>
                
                <?php if (!empty($dates)): ?>
                    <div class="date-grid">
                        <?php foreach ($dates as $date): 
                            $is_today = ($date['ngay_chieu'] == date('Y-m-d'));
                            $day_name = get_day_name($date['ngay_chieu']);
                        ?>
                            <div class="date-card" 
                                 onclick="window.location.href='index.php?act=datve&id=<?= $id_phim ?>&id_rap=<?= $selected_rap ?>&ngay_chieu=<?= $date['ngay_chieu'] ?>'">
                                <div class="date-day"><?= $day_name ?></div>
                                <div class="date-number"><?= date('d/m', strtotime($date['ngay_chieu'])) ?></div>
                                <?php if ($is_today): ?>
                                    <span class="date-today-badge">Hôm nay</span>
                                <?php endif; ?>
                                <div class="date-showtimes"><?= $date['so_suat'] ?> suất</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="info-alert">
                        <span class="info-alert-icon">ℹ️</span>
                        Không có lịch chiếu trong 14 ngày tới tại rạp này.
                    </div>
                <?php endif; ?>
            </div>

        <!-- BƯỚC 3: CHỌN GIỜ -->
        <?php else: ?>
            <div class="time-selection">
                <h2 class="page-heading">
                    CHỌN KHUNG GIỜ CHIẾU - <?= date('d/m/Y', strtotime($selected_date)) ?>
                </h2>
                
                <?php if (!empty($times)): ?>
                    <div class="time-grid">
                        <?php foreach ($times as $time): 
                            $is_available = $time['ghe_trong'] > 0;
                        ?>
                            <div class="time-card <?= !$is_available ? 'disabled' : '' ?>"
                                 <?php if ($is_available): ?>
                                 onclick="window.location.href='index.php?act=datve2&id=<?= $id_phim ?>&id_lc=<?= $time['id_lich_chieu'] ?>&id_g=<?= $time['id'] ?>'"
                                 <?php endif; ?>>
                                <div class="time-hour"><?= date('H:i', strtotime($time['thoi_gian_chieu'])) ?></div>
                                <div class="time-room">
                                    🎭 <?= htmlspecialchars($time['ten_phong']) ?>
                                    <?php if ($time['loai_phong'] != '2D'): ?>
                                        (<?= $time['loai_phong'] ?>)
                                    <?php endif; ?>
                                </div>
                                <div class="time-seats">
                                    <?php if ($is_available): ?>
                                        ✓ Còn <?= $time['ghe_trong'] ?> ghế
                                    <?php else: ?>
                                        ✗ Hết chỗ
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="info-alert">
                        <span class="info-alert-icon">ℹ️</span>
                        Không có suất chiếu nào trong ngày này.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </section>

    <div class="clearfix"></div>
</div>
