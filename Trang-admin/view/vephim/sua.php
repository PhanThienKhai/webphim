<style>
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #4CAF50;
        --danger: #ff6b6b;
        --info: #2196F3;
        --light: #f8f9ff;
        --border: #e0e0e0;
    }

    .form-section {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 15px;
    }

    .form-section h4 {
        color: #333;
        font-weight: 700;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--light);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.3s ease;
        background-color: white;
        color: #333;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control:disabled {
        background-color: #f5f5f5;
        color: #999;
        cursor: not-allowed;
    }

    .status-badge {
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-top: 5px;
    }

    .badge-danger {
        background-color: #ff6b6b;
        color: white;
    }

    .badge-success {
        background-color: #4CAF50;
        color: white;
    }

    .badge-info {
        background-color: #2196F3;
        color: white;
    }

    .badge-secondary {
        background-color: #95a5a6;
        color: white;
    }

    .page-heading h3 {
        color: #333;
        font-weight: 700;
        margin: 0;
        font-size: 24px;
    }

    .page-heading h3 span {
        color: #999;
        font-weight: 400;
        font-size: 14px;
        margin-left: 10px;
    }

    .button-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    .button {
        padding: 11px 24px !important;
        border-radius: 6px !important;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none !important;
        font-size: 13px;
    }

    .button-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%) !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .button-info {
        background: linear-gradient(135deg, var(--info) 0%, #1976d2 100%) !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
    }

    .button-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    }

    .info-box {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid var(--primary);
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .info-box p {
        margin: 0;
        color: #666;
        font-size: 12px;
    }

    .field-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        padding: 8px 0;
    }

    .field-item {
        display: flex;
        flex-direction: column;
    }

    .field-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 4px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .field-value {
        color: #333;
        font-size: 13px;
        padding: 4px 0;
    }

    @media (max-width: 768px) {
        .form-section {
            padding: 20px;
        }

        .field-group {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .button-group {
            flex-direction: column-reverse;
        }

        .button {
            width: 100%;
        }

        .page-heading h3 {
            font-size: 18px;
        }

        .page-heading h3 span {
            display: none;
        }
    }
</style>

<?php
include "./view/home/sideheader.php";
if (is_array($loadve)) {
    extract($loadve);
}
?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Trị Vé Xem Phim <span>/ Sửa Vé</span></h3>
            </div>
        </div>
    </div><!-- Page Headings End -->

    <!-- Info Box -->
    <div class="info-box">
        <p><strong>Lưu ý:</strong> Các trường bị vô hiệu hóa là thông tin gốc từ đơn đặt vé, chỉ có thể thay đổi trạng thái vé.</p>
    </div>

    <!-- Form Start -->
    <form action="index.php?act=updatevephim" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?=$id?>">

        <!-- Thông tin vé Section -->
        <div class="form-section">
            <h4>Thông Tin Vé</h4>
            
            <div class="field-group">
                <div class="field-item">
                    <label class="field-label">Mã Vé</label>
                    <div class="field-value"><strong>#<?=$id?></strong></div>
                </div>
                <div class="field-item">
                    <label class="field-label">Phim</label>
                    <input class="form-control" type="text" value="<?=$tieu_de?>" disabled>
                </div>
                <div class="field-item">
                    <label class="field-label">Giá Vé</label>
                    <input class="form-control" type="text" value="<?=number_format($price)?> ₫" disabled>
                </div>
            </div>

            <div class="field-group">
                <div class="field-item">
                    <label class="field-label">Ngày Chiếu</label>
                    <input class="form-control" type="date" value="<?=$ngay_chieu?>" disabled>
                </div>
                <div class="field-item">
                    <label class="field-label">Giờ Chiếu</label>
                    <input class="form-control" type="time" value="<?=$thoi_gian_chieu?>" disabled>
                </div>
                <div class="field-item">
                    <label class="field-label">Phòng Chiếu</label>
                    <input class="form-control" type="text" value="<?=$tenphong?>" disabled>
                </div>
            </div>

            <div class="field-group">
                <div class="field-item">
                    <label class="field-label">Ghế Ngồi</label>
                    <input class="form-control" type="text" value="<?=$ghe?>" disabled>
                </div>
                <div class="field-item">
                    <label class="field-label">Combo</label>
                    <input class="form-control" type="text" value="<?=$combo?>" disabled>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng Section -->
        <div class="form-section">
            <h4>Thông Tin Khách Hàng</h4>
            
            <div class="field-group">
                <div class="field-item">
                    <label class="field-label">Tên Khách Hàng</label>
                    <input class="form-control" type="text" value="<?=$name?>" disabled>
                </div>
                <div class="field-item">
                    <label class="field-label">Ngày Đặt Vé</label>
                    <input class="form-control" type="date" value="<?=$ngay_dat?>" disabled>
                </div>
            </div>
        </div>

        <!-- Trạng thái Section -->
        <div class="form-section">
            <h4>Trạng Thái Vé</h4>
            
            <div class="field-item">
                <label class="field-label" for="trang_thai">Chọn Trạng Thái</label>
                <select class="form-control" id="trang_thai" name="trang_thai">
                    <option value="0" <?= ($trang_thai == 0) ? 'selected' : '' ?>>⏳ Đang chờ</option>
                    <option value="1" <?= ($trang_thai == 1) ? 'selected' : '' ?>>✓ Xác nhận đã thanh toán</option>
                    <option value="2" <?= ($trang_thai == 2) ? 'selected' : '' ?>>✓ Đã dùng</option>
                    <option value="3" <?= ($trang_thai == 3) ? 'selected' : '' ?>>✕ Hủy</option>
                </select>

                <div style="margin-top: 10px;">
                    <?php
                    $statusText = [
                        0 => ['text' => 'Đang chờ', 'class' => 'badge-danger'],
                        1 => ['text' => '✓ Xác nhận đã thanh toán', 'class' => 'badge-success'],
                        2 => ['text' => '✓ Đã dùng', 'class' => 'badge-info'],
                        3 => ['text' => '✕ Hủy', 'class' => 'badge-secondary']
                    ];
                    $current = $statusText[$trang_thai] ?? $statusText[0];
                    ?>
                    <span class="status-badge <?=$current['class']?>"><?=$current['text']?></span>
                </div>
            </div>
        </div>

        <!-- Button Group -->
        <div class="form-section">
            <div class="button-group">
                <a href="index.php?act=ve" class="button button-info">Danh sách vé</a>
                <button class="button button-primary" type="submit" name="capnhat">Cập nhật</button>
            </div>
        </div>

    </form><!-- Form End -->

</div><!-- Content Body End -->
