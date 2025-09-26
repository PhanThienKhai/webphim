<!-- Main content -->
<?php
// Giả sử $loadone_ve chứa thông tin vé
$qr_data = "ID_VE=" . $loadone_ve['id'] . "&USER=" . $loadone_ve['id_tk'];
?>

<?php include "view/search.php"; ?>
<form action="index.php?act=huy_ve" method="post">
    <section class="container">
        <div class="order-container">
            <?php if (isset($loadone_ve)) {
                echo "<h2>CHI TIẾT VÉ</h2>";
                    extract($loadone_ve);
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
                    echo '
                       <div class="ticket">
                        <div class="ticket-position">
                            <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text">Galaxy Studio</div> </div>
                            <div class="ticket__inner">
                                <div class="ticket-secondary">
                                    <span class="ticket__item">Mã vé <strong class="ticket__number">' . $id . '</strong></span>
                                    <span class="ticket__item ticket__date">' . $ngay_chieu . '</span>
                                    <span class="ticket__item ticket__time">' . $thoi_gian_chieu . '</span>
                                    <span class="ticket__item">Rạp : <span class="ticket__cinema">Galaxy Studio Gò Vấp</span></span>
                                    <span class="ticket__item">Phòng : <strong class="ticket__number">' . $tenphong . '</strong></span>
                                    <span class="ticket__item">Người đặt: <span class="ticket__cinema">' . $name . '</span></span>
                                    <span class="ticket__item">Thời gian đặt: <span class="ticket__hall">' . $ngay_dat . '</span></span>
                                    <span class="ticket__item ticket__price" style="margin-top: 5px">Giá: <strong class="ticket__cost">' . number_format($price) . ' vnđ</strong></span>
                                </div>

                                <div class="ticket-primery">
<span class="ticket__item ticket__item--primery ticket__film" style="display:flex;> <strong class="ticket__movie" >PHIM : ' . $tieu_de . '</strong></span>                                    <span class="ticket__item ticket__item--primery">Ghế: <span class="ticket__place">' . $ghe . '</span></span>
                                    <span class="ticket__item ticket__item--primery">Combo: <span class="ticket__place">' . $combo . '</span></span>
                                </div>
                            </div>
                            <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">Galaxy Gò Vấp</div></div>
                        </div>
                        <div>
                        <input type="hidden" name="id" value="'.$id.'">

                        <span>Trạng thái : '.$thong_bao.'</span>
                        <button type="submit" name="capnhat" class="btn btn-danger" '.$huy_ve_style.' style="margin-top:10px;">Hủy vé</button>

                        </div>
                    </div>
                     ';
            ?>
        </div>
    </section>
    <?php
// Giả sử $loadone_ve chứa thông tin vé
$qr_data = "http://192.168.100.9/webphim/Trang-nguoi-dung/index.php?act=quetve&id=" . $loadone_ve['id'];
?>
<!-- Div hiển thị trên web -->
<div id="qr-ticket-content" style="background:#fff; padding:20px; border-radius:8px; max-width:400px; margin:0 auto;">
    <h4 style="margin-bottom:15px; text-align:center;">Mã QR vé của bạn:</h4>
    <img src="view/qr.php?data=<?= urlencode($qr_data) ?>" alt="QR Code Vé Xem Phim" style="display:block; margin:0 auto;" />
    <h5 style="text-align:center; margin: 20px 0 0 0; line-height:1.6;">
        Vui lòng lưu lại mã này<br>
        Trước khi vào rạp hãy đưa mã QR này cho nhân viên tại cổng kiểm soát<br>
        Galaxy Studio xin cảm ơn!
    </h5>
</div>
<!-- Div ẩn chỉ dùng để xuất ảnh -->
<div id="qr-ticket-download" style="display:none; background:#fff; padding:20px; border-radius:8px; max-width:400px; margin:0 auto;">
    <h2 style="text-align:center;">Vé xem phim</h2>
    <img src="view/qr.php?data=<?= urlencode($qr_data) ?>" alt="QR Code Vé Xem Phim" style="display:block; margin:0 auto; width:180px; height:180px;" />
    <hr>
    <div style="font-size:25px;">
        <p><b>Phim:</b> <?= $tieu_de ?></p>
        <p><b>Ghế:</b> <?= $ghe ?></p>
        <p><b>Ngày chiếu:</b> <?= $ngay_chieu ?></p>
        <p><b>Giờ chiếu:</b> <?= $thoi_gian_chieu ?></p>
        <p><b>Phòng:</b> <?= $tenphong ?></p>
        <p><b>Giá vé:</b> <?= number_format($price) ?> VNĐ</p>
        <p><b>Combo:</b> <?= $combo ?></p>
        <p><b>Trạng thái:</b>
            <?php
            switch ($trang_thai) {
                case 1: echo 'Đã thanh toán'; break;
                case 2: echo 'Đã dùng'; break;
                case 3: echo 'Đã hủy'; break;
                case 4: echo 'Hết hạn'; break;
                default: echo 'Không xác định';
            }
            ?>
        </p>
    </div>
    <div style="text-align:center; margin-top:10px; font-size:13px; color:#888;">Vui lòng đưa mã này cho nhân viên tại cổng kiểm soát<br>Galaxy Studio xin cảm ơn!</div>
</div>
<div style="text-align:center; margin-top:20px;">
    <button id="save-qr-btn" type="button" style="padding:10px 20px; font-size:16px; background:#007bff; color:#fff; border:none; border-radius:4px;">Lưu mã QR (ảnh)</button>
    <div id="save-qr-error" style="color:red; margin-top:10px; display:none;"></div>
</div>
<div style="text-align:center; margin-top:12px;">
    <a class="btn btn--primary" href="view/chitiet_ve_pdf.php?id=<?= $loadone_ve['id'] ?>" target="_blank" rel="noopener">Tải / In vé (PDF)</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.getElementById('save-qr-btn').onclick = function(e) {
    e.preventDefault();
    const ticket = document.getElementById('qr-ticket-download');
    const errorDiv = document.getElementById('save-qr-error');
    errorDiv.style.display = 'none';
    ticket.style.display = 'block';
    html2canvas(ticket).then(canvas => {
        try {
            const link = document.createElement('a');
            link.download = 've_xem_phim.png';
            link.href = canvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (e) {
            errorDiv.textContent = 'Không thể lưu ảnh. Vui lòng thử lại trên trình duyệt khác hoặc kiểm tra cài đặt tải file.';
            errorDiv.style.display = 'block';
        }
        ticket.style.display = 'none';
    }).catch(function(err) {
        errorDiv.textContent = 'Lỗi khi tạo ảnh: ' + err;
        errorDiv.style.display = 'block';
        ticket.style.display = 'none';
    });
};
</script>
</form>

<?php
} else {
    // Nếu không tồn tại, in ra thông báo "Bạn chưa thanh toán"
    ?>
    <section class="container">
        <p>Bạn chưa thanh toán vé.</p>
    </section>
    <?php
}
?>