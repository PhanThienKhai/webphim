<?php include "view/search.php"; ?>
<?php if(isset($thongbaoghe)&&($thongbaoghe)!= ""){
    echo'<p  style="color: red; text-align: center;">' .$thongbaoghe. '</p>';
}
?>
<?php include 'global.php';
?>

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
            <i class="fa fa-map-marker" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Địa chỉ:</strong><br>
                <?= isset($_SESSION['tong']['dia_chi_rap']) ? $_SESSION['tong']['dia_chi_rap'] : 'N/A' ?>
            </div>
        </div>
        <div style="margin: 10px; text-align: center;">
            <i class="fa fa-door-open" style="font-size: 24px; color: #ffd564;"></i>
            <div style="margin-top: 5px;">
                <strong>Phòng:</strong><br>
                <?= isset($_SESSION['tong']['ten_phong']) ? $_SESSION['tong']['ten_phong'] : 'N/A' ?>
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

<div class="col-lg-offset-1">
    <div class="tong">
        <form action="index.php?act=dv3" method="post">
            <h2 class="phim" style="color: #667eea;">Chọn ghế ngồi</h2>

            <div style="display: flex">
                <span>🪑Ghế đã chọn :</span>
                <div class="checked-place">
                    <?php
                    if (isset($_SESSION['tong']['ghe'])) {
                        foreach ($_SESSION['tong']['ghe'] as $ghe) {
                            echo  '<span class="choosen-place">' . implode(', ', $ghe) . '</span>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="tongtien">
                <div class="checked-result">
                    <span>Tổng cộng :</span>
                    <input name="giaghe" style="width: 80px; font-size: 20px; border: none;" type="text" id="gia_ghe"
                           value="<?php echo isset($_SESSION['tong'][0]) ? $_SESSION['tong'][0] : 0; ?>"> VND
                </div>
            </div>
    </div>
</div>

<div class="booking-pagination">
    <a href="index.php?act=datve&id=<?php echo $_SESSION['tong']['id_phim']; ?>&id_rap=<?php echo isset($_SESSION['tong']['id_rap']) ? $_SESSION['tong']['id_rap'] : ''; ?>&ngay_chieu=<?php echo isset($_SESSION['tong']['ngay_chieu']) ? $_SESSION['tong']['ngay_chieu'] : ''; ?>">
        <span class="quaylai">QUAY LẠI</span>
    </a>
    <a href="#" id="tiep_tuc_link">
        <input type="submit" name="tiep_tuc" class="booking-pagination__button" value="TIẾP TỤC">
    </a>
</div>

<div class="clearfix"></div>
</form></section></div></div>
