<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="page-heading"><h3>Thêm mã khuyến mãi</h3></div>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="post" action="index.php?act=km_them">
        <div class="row">
            <div class="col-12 col-md-6 mb-15">
                <label>Mã code khuyến mãi <span style="color:#ef4444">*</span></label>
                <input class="form-control" type="text" name="ma_khuyen_mai" required 
                       placeholder="VD: SINHNHAT20, HSSV15, COMBO50K" 
                       style="font-family:monospace;font-size:14px;text-transform:uppercase"
                       pattern="[A-Z0-9]{3,20}" 
                       title="Chỉ chữ IN HOA và số, từ 3-20 ký tự">
                <small style="color:#6b7280;font-size:12px">📝 Mã duy nhất để nhân viên nhập khi đặt vé</small>
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Tên khuyến mãi <span style="color:#ef4444">*</span></label>
                <input class="form-control" type="text" name="ten_khuyen_mai" required
                       placeholder="VD: Khuyến mãi sinh nhật">
            </div>
            <div class="col-6 col-md-2 mb-15">
                <label>Loại giảm</label>
                <select class="form-control" name="loai_giam" id="loai_giam">
                    <option value="phan_tram">Phần trăm %</option>
                    <option value="tien_mat">Tiền mặt VND</option>
                </select>
            </div>
            <div class="col-6 col-md-2 mb-15" id="field_phan_tram">
                <label>% Giảm</label>
                <input class="form-control" type="number" name="phan_tram_giam" step="0.01" min="0" max="100" placeholder="VD: 10">
            </div>
            <div class="col-6 col-md-2 mb-15" id="field_tien_mat" style="display:none">
                <label>Số tiền giảm (VND)</label>
                <input class="form-control" type="number" name="gia_tri_giam" min="0" placeholder="VD: 50000">
            </div>
            <div class="col-6 col-md-2 mb-15">
                <label>Trạng thái</label>
                <select class="form-control" name="trang_thai">
                    <option value="1">✓ Hoạt động</option>
                    <option value="0">✗ Tắt</option>
                </select>
            </div>
            <div class="col-6 col-md-3 mb-15">
                <label>Từ ngày</label>
                <input class="form-control" type="date" name="ngay_bat_dau" required>
            </div>
            <div class="col-6 col-md-3 mb-15">
                <label>Đến ngày</label>
                <input class="form-control" type="date" name="ngay_ket_thuc" required>
            </div>
            <div class="col-12 col-md-6 mb-15">
                <label>Điều kiện áp dụng</label>
                <input class="form-control" type="text" name="dieu_kien_ap_dung" 
                       placeholder="VD: Áp dụng cho vé từ 100.000đ">
            </div>
            <div class="col-12 mb-15">
                <label>Mô tả chi tiết</label>
                <textarea class="form-control" name="mo_ta" rows="3" 
                          placeholder="Mô tả về khuyến mãi này..."></textarea>
            </div>
            <div class="col-12">
                <button class="button button-primary" type="submit" name="luu" value="1">💾 Lưu khuyến mãi</button> 
                <a class="button" href="index.php?act=QLkm">← Hủy</a>
            </div>
        </div>
    </form>
    
    <script>
    // Toggle hiển thị field theo loại giảm giá
    document.getElementById('loai_giam').addEventListener('change', function() {
        const loai = this.value;
        const fieldPhanTram = document.getElementById('field_phan_tram');
        const fieldTienMat = document.getElementById('field_tien_mat');
        
        if (loai === 'phan_tram') {
            fieldPhanTram.style.display = 'block';
            fieldTienMat.style.display = 'none';
            fieldPhanTram.querySelector('input').required = true;
            fieldTienMat.querySelector('input').required = false;
        } else {
            fieldPhanTram.style.display = 'none';
            fieldTienMat.style.display = 'block';
            fieldPhanTram.querySelector('input').required = false;
            fieldTienMat.querySelector('input').required = true;
        }
    });
    
    // Auto uppercase mã khuyến mãi
    const maKMInput = document.querySelector('input[name="ma_khuyen_mai"]');
    if (maKMInput) {
        maKMInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    }
    </script>
</div>
