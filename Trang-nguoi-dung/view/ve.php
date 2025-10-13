<!-- Main content -->
<?php include "view/search.php"; ?>
<form action="index.php?act=huy_ve" method="post">
<section class="container">
    <div class="order-container">
        <?php
        // Kiểm tra xem biến $load_ve có tồn tại hay không
            // Nếu tồn tại, hiển thị danh sách vé đã mua
            echo "<h2>DANH SÁCH VÉ ĐÃ MUA</h2>";
            foreach ($load_ve as $ve) {
                extract($ve);
                switch ($trang_thai) {
                    case 1:
                        $thong_bao = 'Đã thanh toán';
                        $huy_ve_style = '';
                        break;
                    case 2:
                        $thong_bao = 'Đã dùng';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    case 3:
                        $thong_bao = 'Đã hủy';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    case 4:
                        $thong_bao = 'Hết hạn';
                        $huy_ve_style = 'style="display:none;"';
                        break;
                    default:
                        $thong_bao = 'Trạng thái không xác định';
                        $huy_ve_style = '';
                }
                $linkct = "index.php?act=ctve&id=".$id;
                
                // Lấy thông tin rạp, nếu không có thì dùng default
                $ten_rap_hienthi = !empty($ten_rap) ? $ten_rap : 'Galaxy Studio Gò Vấp';
                $dia_chi_hienthi = !empty($dia_chi_rap) ? $dia_chi_rap : 'Địa chỉ chưa cập nhật';
                
                echo '<div class="ticket">
                        <div class="ticket-position">
                            <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text">Galaxy Studio</div> </div>
                            <div class="ticket__inner">
                            
                                <div class="ticket-secondary">
                                    <span class="ticket__item">Mã vé <strong class="ticket__number">' . $id . '</strong></span>
                                    <span class="ticket__item ticket__date">' . $ngay_chieu . '</span>
                                    <span class="ticket__item ticket__time">' . $thoi_gian_chieu . '</span>
                                    <span class="ticket__item">🏢 Rạp : <span class="ticket__cinema">' . $ten_rap_hienthi . '</span></span>
                                    <span class="ticket__item">📍 Địa chỉ : <span class="ticket__cinema">' . $dia_chi_hienthi . '</span></span>
                                    <span class="ticket__item">🚪 Phòng : <strong class="ticket__number">' . $tenphong . '</strong></span>
                                    <span class="ticket__item">👤 Người đặt: <span class="ticket__cinema">' . $name . '</span></span>
                                    <span class="ticket__item">🕐 Thời gian đặt: <span class="ticket__hall">' . $ngay_dat . '</span></span>
                                    <span class="ticket__item ticket__price" style="margin-top: 5px">💰 Giá: <strong class="ticket__cost">' . number_format($price) . ' vnđ</strong></span>
                                </div>
                                <div class="ticket-primery">
<span class="ticket__item ticket__item--primery ticket__film" style="display:flex;"> <strong class="ticket__movie" >PHIM : ' . $tieu_de . '</strong></span>                                    <span class="ticket__item ticket__item--primery">🪑 Ghế: <span class="ticket__place">' . $ghe . '</span></span>
                                    <span class="ticket__item ticket__item--primery">🍿 Combo: <span class="ticket__place">' . $combo . '</span></span>
                                </div>
                            </div>
                            <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">Galaxy Studio</div></div>
                        </div>
                        <div>
                        <a href="'.$linkct.'"><input type="button" value="Chi tiết vé" ></a>
                        <span>Trạng thái : '.$thong_bao.'</span>
                        </div>
                    </div>';
            }
            echo '<h1>(*) Khi hủy vé bạn liên hệ với đội ngũ cskh của Galaxy Studio để được hoàn tiền</h1>';

        ?>
    </div>
</section>
</form>

