<?php include "./view/home/sideheader.php"; ?>

<style>
    .ticket { width: 100%; max-width: 1000px; position: relative; margin: 40px auto; padding: 20px; }
    .ticket-position { display: inline-block; overflow: hidden; }
    .ticket__indecator { position: relative; border: 3px solid #dbdee1; width: 50px; float: left; height: 363px; font-family: 'PT Mono'; font-size: 12px; color: #dbdee1; }
    .indecator-text { position: absolute; width: 100px; }
    .indecator-text:before { content: ''; background-image: url(../images/icons/stars-light.svg); background-repeat: no-repeat; width: 80px; height: 16px; position: absolute; top: 0px; left: -95px; }
    .indecator-text:after { content: ''; background-image: url(../images/icons/stars-light.svg); background-repeat: no-repeat; width: 80px; height: 16px; position: absolute; top: 0px; right: -95px; }
    .indecator--pre { margin-right: -3px; }
    .indecator--post { margin-left: -3px; }
    .pre--text { -webkit-transform: rotate(90deg) translateZ(0); -moz-transform: rotate(90deg) translateZ(0); -o-transform: rotate(90deg) translateZ(0); -ms-transform: rotate(90deg) translateZ(0); transform: rotate(90deg) translateZ(0); margin-top: 137px; left: -26px; }
    .post--text { -webkit-transform: rotate(-90deg) translateZ(0); -moz-transform: rotate(-90deg) translateZ(0); -o-transform: rotate(-90deg) translateZ(0); -ms-transform: rotate(-90deg) translateZ(0); transform: rotate(-90deg) translateZ(0); margin-top: 137px; left: -29px; }
    .ticket__inner { display: flex; gap: 0; }
    .ticket-secondary { float: left; text-align: left; padding: 47px 80px 30px 60px; overflow: hidden; border-left: 3px double #dbdee1; margin-top: 37px; }
    .ticket-primery { overflow: hidden; border-left: 3px double #dbdee1; margin-top: 37px; padding: 27px 35px 22px 30px; position: relative; min-height: 120px; }
    .ticket__item { display: block; margin-bottom: 10px; font-family: 'PT Mono'; font-size: 14px; text-transform: uppercase; text-align: left; line-height: 1.5; }
    .ticket__item--primery { font-size: 20px; margin-top: 15px; }
    .ticket__number { text-transform: uppercase; font-size: 20px; }
    .ticket__date, .ticket__time { display: inline-block; margin-right: 30px; margin-top: 30px; font-weight: 600; color: #333; font-size: 14px; }
    .ticket__time { color: #666; }
    .ticket__cinema { color: #666; }
    .ticket__cost { color: #fe505a; font-size: 16px; }
    .ticket__movie { text-transform: none; }
    .ticket__place { color: #fe505a; }
    .ticket__film { border-bottom: 1px solid #dbdee1; padding-bottom: 44px; margin-bottom: 28px; }
    .ticket-control { text-align: center; margin-top: 30px; padding-bottom: 30px; }
    .status-badge { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600; margin: 10px 0; }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-used { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #fecaca; color: #7f1d1d; }
    .status-expired { background: #fed7aa; color: #7c2d12; }
    .btn-ticket-action { padding: 10px 20px; margin: 5px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .btn-print { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
    .btn-print:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
    .btn-back { background: #fff; color: #667eea; border: 2px solid #667eea; }
    .btn-back:hover { background: #667eea; color: #fff; }
    .btn-danger-action { background: #ef4444; color: #fff; }
    .btn-danger-action:hover { background: #dc2626; }
    @media print { .btn-ticket-action { display: none; } .ticket-control { display: none; } }
</style>

<div class="content-body">
    <div style="max-width: 1200px; margin: 30px auto; padding: 20px;">
        <?php
        if (!empty($loadone_ve)) {
            extract($loadone_ve);
            $status_class = ($trang_thai == 1) ? 'status-paid' : (($trang_thai == 2) ? 'status-used' : (($trang_thai == 3) ? 'status-cancelled' : 'status-expired'));
            $status_text = ($trang_thai == 1) ? 'Đã thanh toán' : (($trang_thai == 2) ? 'Đã dùng' : (($trang_thai == 3) ? 'Đã hủy' : 'Hết hạn'));
            $ten_rap_hienthi = !empty($tenrap) ? $tenrap : 'Galaxy Studio Gò Vấp';
            $qr_data = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/Trang-nguoi-dung/index.php?act=quetve&id=" . $id;
        ?>
        
        <div class="ticket" id="ticket-print">
            <div class="ticket-position">
                <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text"><?= htmlspecialchars($ten_rap_hienthi) ?></div></div>
                <div class="ticket__inner">
                    <div class="ticket-secondary">
                        <span class="ticket__item">Mã vé <strong class="ticket__number"><?= htmlspecialchars($id) ?></strong></span>
                        <span class="ticket__item ticket__date"><?= htmlspecialchars($ngay_chieu) ?></span>
                        <span class="ticket__item ticket__time"><?= htmlspecialchars($thoi_gian_chieu) ?></span>
                        <span class="ticket__item">Rạp : <span class="ticket__cinema"><?= htmlspecialchars($ten_rap_hienthi) ?></span></span>
                        <span class="ticket__item">Phòng : <strong class="ticket__number"><?= htmlspecialchars($tenphong) ?></strong></span>
                        <span class="ticket__item">Người đặt: <span class="ticket__cinema"><?= htmlspecialchars($name) ?></span></span>
                        <span class="ticket__item">Thời gian đặt: <span class="ticket__hall"><?= htmlspecialchars($ngay_dat ?? 'N/A') ?></span></span>
                        <span class="ticket__item ticket__price" style="margin-top: 5px">Giá: <strong class="ticket__cost"><?= number_format($price ?? 0) ?> vnđ</strong></span>
                    </div>

                    <div class="ticket-primery" style="position: relative; display: grid; grid-template-columns: 1fr 80px; gap: 20px; align-items: start;">
                        <div>
                            <span class="ticket__item ticket__item--primery ticket__film"><strong class="ticket__movie">PHIM: <?= htmlspecialchars($tieu_de) ?></strong></span>
                            <span class="ticket__item ticket__item--primery">Ghế: <span class="ticket__place"><?= htmlspecialchars($ghe) ?></span></span>
                            <span class="ticket__item ticket__item--primery">Combo: <span class="ticket__place"><?= htmlspecialchars($combo) ?></span></span>
                        </div>
                        <div style="justify-self: end; background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; padding: 6px; display: flex; align-items: center; justify-content: center; width: 80px; height: 80px;">
                            <img src="/Trang-nguoi-dung/view/qr.php?data=<?= urlencode($qr_data) ?>" alt="QR Code" style="width: 75px; height: 75px; object-fit: contain;" />
                        </div>
                    </div>
                </div>
                <div class="ticket__indecator indecator--post"><div class="indecator-text post--text"><?= htmlspecialchars($ten_rap_hienthi) ?></div></div>
            </div>
        </div>
        
        <div class="ticket-control">
            <div class="status-badge <?= $status_class ?>">
                ⚡ <?= $status_text ?>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="index.php?act=ve" class="btn-ticket-action btn-back">← Quay lại</a>
                <button onclick="printTicket()" class="btn-ticket-action btn-print" style="font-size: 16px; padding: 12px 24px;">🖨️ In vé</button>
                <?php if ($trang_thai == 1): ?>
                    <button type="button" onclick="cancelTicket(<?= $id ?>)" class="btn-ticket-action btn-danger-action" style="font-size: 16px; padding: 12px 24px;">❌ Hủy vé</button>
                <?php endif; ?>
            </div>
        </div>
        
        <?php } else { ?>
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 18px; color: #6b7280;">Không tìm thấy thông tin vé</p>
            </div>
        <?php } ?>
    </div>
</div>

<script>
function printTicket() {
    const ticketElement = document.getElementById('ticket-print');
    if (!ticketElement) {
        alert('Không tìm thấy vé để in');
        return;
    }
    
    const printWindow = window.open('', '', 'height=600,width=1100');
    const ticketHTML = ticketElement.innerHTML;
    const styles = `
        .ticket { width: 100%; max-width: 1000px; position: relative; margin: 40px auto; padding: 20px; }
        .ticket-position { display: inline-block; overflow: hidden; }
        .ticket__indecator { position: relative; border: 3px solid #dbdee1; width: 50px; float: left; height: 363px; font-family: 'PT Mono'; font-size: 12px; color: #dbdee1; }
        .indecator-text { position: absolute; width: 100px; }
        .indecator-text:before { content: ''; background-image: url(../images/icons/stars-light.svg); background-repeat: no-repeat; width: 80px; height: 16px; position: absolute; top: 0px; left: -95px; }
        .indecator-text:after { content: ''; background-image: url(../images/icons/stars-light.svg); background-repeat: no-repeat; width: 80px; height: 16px; position: absolute; top: 0px; right: -95px; }
        .indecator--pre { margin-right: -3px; }
        .indecator--post { margin-left: -3px; }
        .pre--text { -webkit-transform: rotate(90deg) translateZ(0); -moz-transform: rotate(90deg) translateZ(0); -o-transform: rotate(90deg) translateZ(0); -ms-transform: rotate(90deg) translateZ(0); transform: rotate(90deg) translateZ(0); margin-top: 137px; left: -26px; }
        .post--text { -webkit-transform: rotate(-90deg) translateZ(0); -moz-transform: rotate(-90deg) translateZ(0); -o-transform: rotate(-90deg) translateZ(0); -ms-transform: rotate(-90deg) translateZ(0); transform: rotate(-90deg) translateZ(0); margin-top: 137px; left: -29px; }
        .ticket__inner { display: flex; gap: 0; }
        .ticket-secondary { float: left; text-align: left; padding: 47px 80px 30px 60px; overflow: hidden; border-left: 3px double #dbdee1; margin-top: 37px; }
        .ticket-primery { overflow: hidden; border-left: 3px double #dbdee1; margin-top: 37px; padding: 27px 35px 22px 30px; position: relative; display: grid; grid-template-columns: 1fr 80px; gap: 20px; align-items: start; }
        .ticket__item { display: block; margin-bottom: 10px; font-family: 'PT Mono'; font-size: 14px; text-transform: uppercase; text-align: left; line-height: 1.5; }
        .ticket__item--primery { font-size: 20px; margin-top: 15px; }
        .ticket__number { text-transform: uppercase; font-size: 20px; }
        .ticket__date, .ticket__time { display: inline-block; margin-right: 30px; margin-top: 30px; font-weight: 600; color: #333; font-size: 14px; }
        .ticket__time { color: #666; }
        .ticket__cinema { color: #666; }
        .ticket__cost { color: #fe505a; font-size: 16px; }
        .ticket__movie { text-transform: none; }
        .ticket__place { color: #fe505a; }
        .ticket__film { border-bottom: 1px solid #dbdee1; padding-bottom: 44px; margin-bottom: 28px; }
    `;
    
    const fullHTML = `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>In Vé</title>
        <style>
            ${styles}
            body { margin: 0; padding: 20px; background: #f5f5f5; font-family: Arial, sans-serif; }
            .print-container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; }
        </style>
    </head>
    <body>
        <div class="print-container">
            ${ticketHTML}
        </div>
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                    window.close();
                }, 500);
            });
        <\/script>
    </body>
    </html>
    `;
    
    printWindow.document.write(fullHTML);
    printWindow.document.close();
}

function cancelTicket(ticketId) {
    if (!confirm('Bạn chắc chắn muốn hủy vé này? Tiền sẽ được hoàn lại.')) {
        return;
    }
    
    // Gửi AJAX request tới backend
    fetch('index.php?act=cancelTicket', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'ticket_id=' + ticketId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Hủy vé thành công! Tiền sẽ được hoàn lại trong 2-3 ngày.');
            location.reload();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể hủy vé'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi hủy vé. Vui lòng thử lại.');
    });
}
</script>
