<?php include "view/search.php"; ?>

<style>
    .container {
        width: 80%;
        margin: 0 auto;
    }

    h1 {
        text-align: center;
    }

    .prodoan {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .prodo {
        width: 23%;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .prodo:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transform: translateY(-5px);
    }
    
    .prodo img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 5px;
    }
    
    .combo-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 11px;
        margin-top: 5px;
    }

    .check_do_an {
        background-color: #dc3545;
        color: white;
        padding: 8px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .check_do_an:hover {
        background-color: #c82333;
    }
    
    .btn--success {
        background-color: #28a745 !important;
    }
    
    .btn--success:hover {
        background-color: #218838 !important;
    }
    
    .no-combo-message {
        text-align: center;
        padding: 40px;
        color: #999;
        font-size: 18px;
    }
</style>

<!-- Info Bar hiển thị thông tin đặt vé -->
<div class="booking-info-bar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; margin: 20px auto; max-width: 1200px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-around; align-items: center; color: white;">
        <div style="margin: 10px; text-align: center;">
            <i class="fa fa-film" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Phim:</strong><br>
                <?= isset($_SESSION['tong']['tieu_de']) ? $_SESSION['tong']['tieu_de'] : 'N/A' ?>
            </div>
        </div>
        <div style="margin: 10px; text-align: center;">
            <i class="fa fa-building" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Rạp:</strong><br>
                <?= isset($_SESSION['tong']['ten_rap']) ? $_SESSION['tong']['ten_rap'] : 'N/A' ?>
            </div>
        </div>
        <div style="margin: 10px; text-align: center;">
            <i class="fa fa-calendar" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Ngày chiếu:</strong><br>
                <?= isset($_SESSION['tong']['ngay_chieu']) ? $_SESSION['tong']['ngay_chieu'] : 'N/A' ?>
            </div>
        </div>
        <div style="margin: 10px; text-align: center;">
            <i class="fa fa-clock" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Giờ chiếu:</strong><br>
                <?= isset($_SESSION['tong']['thoi_gian_chieu']) ? $_SESSION['tong']['thoi_gian_chieu'] : 'N/A' ?>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="place-form-area">
    <section class="container">
        <h1>Combo Đồ ăn</h1>
        
        <?php if (isset($combos) && count($combos) > 0): ?>
        <div class="prodoan">
            <?php foreach ($combos as $combo): ?>
            <div class="prodo">
                <img src="<?= !empty($combo['hinh_anh']) ? $combo['hinh_anh'] : 'imgavt/combo1.png' ?>" alt="<?= htmlspecialchars($combo['ten_combo']) ?>">
                <h3><?= htmlspecialchars($combo['ten_combo']) ?></h3>
                <p><?= htmlspecialchars($combo['mo_ta']) ?></p>
                
                <?php if ($combo['id_rap'] !== null): ?>
                    <span class="combo-badge">🏢 Combo riêng của rạp</span>
                <?php else: ?>
                    <span class="combo-badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">🌟 Combo toàn hệ thống</span>
                <?php endif; ?>
                
                <p style="font-size: 20px; color: #dc3545; font-weight: bold; margin-top: 10px;">
                    Giá: <?= number_format($combo['gia_combo'], 0, ',', '.') ?>đ
                </p>
                
                <div class="combo-doan-right">
                    <span class="check_do_an btn btn-md btn--danger" 
                          check-price='<?= $combo['gia_combo'] ?>' 
                          check-place='<?= htmlspecialchars($combo['ten_combo']) ?>'>
                        CHỌN NGAY
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="no-combo-message">
                <i class="fa fa-info-circle" style="font-size: 50px; color: #ddd;"></i>
                <p>Hiện tại không có combo đồ ăn nào khả dụng cho rạp này.</p>
            </div>
        <?php endif; ?>

    </section>
</div>

<form action="index.php?act=dv4" method="post">
    <div class="col-lg-offset-1">
        <div class="tong">
            <h2 class="phim" style="color: #667eea;">Thông tin đặt vé</h2>
            
            <div style="display: flex; margin-bottom: 10px;">
                <span>🪑 Ghế đã chọn:</span>
                <div class="checked-place">
                    <?php
                    if (isset($ten_ghe['ghe'])) {
                        $ghes = $ten_ghe['ghe'];
                        echo '<span class="choosen-place">' . implode(', ', $ghes) . '</span>';

                        // Tạo các hidden input cho mỗi ghế
                        foreach ($ghes as $ghe) {
                            echo '<input type="hidden" name="ten_ghe[]" value="' . htmlspecialchars($ghe) . '">';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <div style="display: flex; margin-bottom: 10px;">
                <span>🍿 Combo đã chọn:</span>
                <div class="check-doan">
                    <?php
                    if (isset($ten_doan['doan'])) {
                        foreach ($ten_doan['doan'] as $doan) {
                            echo '<span class="check-doan">' . htmlspecialchars($doan) . '</span>';
                            echo '<input type="hidden" name="ten_do_an[]" value="' . htmlspecialchars($doan) . '">';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="tongtien">
                <div class="checked-result">
                    <span>Tổng cộng:</span>
                    <input name="giaghe" style="width: 80px; font-size: 20px; border: none;" type="text" id="gia_ghe"
                           value="<?php 
                           if (isset($_SESSION['tong'][0])) {
                               $displayValue = ($_SESSION['tong'][0] == 0) ? $_SESSION['tong'][2] : $_SESSION['tong'][0];
                           } else {
                               $displayValue = 0;
                           }
                           echo $displayValue; 
                           ?>"> VND
                </div>
            </div>
        </div>
    </div>

    <div class="booking-pagination">
        <a href="index.php?act=datve2&id=<?php echo $_SESSION['tong']['id_phim'] ?>">
            <span class="quaylai">QUAY LẠI</span>
        </a>
        <a href="#">
            <input type="submit" name="tiep_tuc" class="booking-pagination__button" value="TIẾP TỤC">
        </a>
    </div>
</form>

<div class="clearfix"></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Xử lý sự kiện click cho nút chọn combo
        $('.check_do_an').on('click touchstart', function () {
            if ($(this).hasClass('btn--danger')) {
                $(this).removeClass('btn--danger').addClass('btn--success').text('BỎ CHỌN');
            } else {
                $(this).removeClass('btn--success').addClass('btn--danger').text('CHỌN NGAY');
            }
        });
    });
</script>
