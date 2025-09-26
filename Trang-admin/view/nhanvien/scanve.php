<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Kiểm tra vé điện tử</h3></div></div></div>

    <?php if (!empty($msg)): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if (!empty($err)): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=scanve">
        <div class="row align-items-end">
            <div class="col-12 col-md-6 mb-15">
                <label>Mã vé</label>
                <input class="form-control" type="text" name="ma_ve" placeholder="Nhập hoặc quét mã vé" required />
            </div>
            <div class="col-12 col-md-3 mb-15">
                <button class="button button-primary" type="submit" name="kiemtra" value="1">Kiểm tra / Check-in</button>
            </div>
        </div>
    </form>

    <?php if (!empty($ve_chi_tiet)): ?>
        <div class="row mt-20">
            <div class="col-12 col-lg-8">
                <div class="card" style="padding:16px;">
                    <h5>Thông tin vé</h5>
                    <p><b>Phim:</b> <?= htmlspecialchars($ve_chi_tiet['tieu_de']) ?></p>
                    <p><b>Ngày chiếu:</b> <?= htmlspecialchars($ve_chi_tiet['ngay_chieu']) ?>, <b>Giờ:</b> <?= htmlspecialchars($ve_chi_tiet['thoi_gian_chieu']) ?></p>
                    <p><b>Phòng:</b> <?= htmlspecialchars($ve_chi_tiet['tenphong']) ?>, <b>Ghế:</b> <?= htmlspecialchars($ve_chi_tiet['ghe']) ?></p>
                    <p><b>Đặt lúc:</b> <?= htmlspecialchars($ve_chi_tiet['ngay_dat']) ?></p>
                    <p><b>Check-in lúc:</b> <?= htmlspecialchars($ve_chi_tiet['check_in_luc'] ?? 'Chưa') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

