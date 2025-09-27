<div style="max-width:400px;margin:40px auto;padding:20px;border:1px solid #ccc;border-radius:8px;">
    <h2 style="text-align:center;">Thông tin vé</h2>
    <p><b>Mã vé:</b> <?= $loadone_ve['id'] ?></p>
    <p><b>Phim:</b> <?= $loadone_ve['tieu_de'] ?></p>
    <p><b>Ghế:</b> <?= $loadone_ve['ghe'] ?></p>
    <p><b>Ngày chiếu:</b> <?= $loadone_ve['ngay_chieu'] ?></p>
    <p><b>Giờ chiếu:</b> <?= $loadone_ve['thoi_gian_chieu'] ?></p>
    <p><b>Phòng:</b> <?= $loadone_ve['tenphong'] ?></p>
    <p><b>Người đặt:</b> <?= $loadone_ve['name'] ?></p>
    <p><b>Trạng thái:</b> 
        <?php
        switch ($loadone_ve['trang_thai']) {
            case 1: echo 'Đã thanh toán'; break;
            case 2: echo 'Đã dùng'; break;
            case 3: echo 'Đã hủy'; break;
            case 4: echo 'Hết hạn'; break;
            default: echo 'Không xác định';
        }
        ?>
    </p>
    <?php if ($loadone_ve['trang_thai'] == 1) { ?>
        <form method="post">
            <input type="hidden" name="id_ve" value="<?= $loadone_ve['id'] ?>">
            <button type="submit" name="xacnhan" style="width:100%;padding:10px;background:#28a745;color:#fff;border:none;border-radius:4px;font-size:16px;">Xác nhận vào rạp</button>
        </form>
    <?php } elseif ($loadone_ve['trang_thai'] == 2) { ?>
        <div style="color:green;text-align:center;font-weight:bold;">Vé đã được xác nhận vào rạp!</div>
    <?php } ?>
</div> 