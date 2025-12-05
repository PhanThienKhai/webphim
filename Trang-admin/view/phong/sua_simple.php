<?php
include "./view/home/sideheader.php";
if (is_array($loadphong1)) {
    extract($loadphong1);
}
?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Phòng <span>/ Sửa phòng</span></h3>
            </div>
        </div>
    </div><!-- Page Headings End -->

    <?php if(isset($suatc) && $suatc != ""): ?>
        <div class="alert alert-success" style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:12px; border-radius:8px; margin-bottom:20px;">
            <?= $suatc ?>
        </div>
    <?php endif; ?>

    <!-- Thông tin phòng -->
    <form action="index.php?act=updatephong" method="POST">
        <div class="add-edit-product-wrap col-12">
            <div class="add-edit-product-form">
                <h4 class="title">Thông tin phòng chiếu</h4>

                <div class="row">
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="col-lg-4 col-12 mb-30">
                        <label class="form-label">Tên phòng</label>
                        <input class="form-control" type="text" name="name" value="<?=$name?>" required>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <label class="form-label">Số ghế</label>
                        <input class="form-control" type="number" name="so_ghe" value="<?=$so_ghe ?? 0?>" readonly style="background:#f3f4f6;">
                        <small style="color:#6b7280;">Số ghế tự động tính từ sơ đồ</small>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <label class="form-label">Diện tích (m²)</label>
                        <input class="form-control" type="number" step="0.01" name="dien_tich" value="<?=$dien_tich ?? 0?>">
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex flex-wrap justify-content-end col mbn-10">
                        <button class="button button-outline button-primary mb-10 ml-10 mr-0" type="submit" name="capnhat">💾 Cập nhật thông tin</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Quản lý sơ đồ ghế -->
    <div class="row mt-30">
        <div class="col-12">
            <div class="add-edit-product-wrap">
                <div class="add-edit-product-form">
                    <h4 class="title">🎭 Sơ đồ ghế của phòng</h4>
                    
                    <?php
                    $map = pg_list($id);
                    $has_map = !empty($map);
                    ?>

                    <?php if (!$has_map): ?>
                        <!-- Chưa có sơ đồ - Hiển thị form tạo nhanh -->
                        <div style="background:#fef3c7; border:1px solid #f59e0b; padding:20px; border-radius:8px; margin-bottom:20px;">
                            <p style="color:#92400e; margin:0;">
                                ⚠️ <strong>Phòng này chưa có sơ đồ ghế.</strong> Vui lòng chọn một mẫu bên dưới để tạo tự động.
                            </p>
                        </div>

                        <form method="post" action="index.php?act=suaphong&ids=<?= $id ?>">
                            <div class="row">
                                <div class="col-lg-4 col-12 mb-20">
                                    <label class="form-label">Chọn loại phòng</label>
                                    <select class="form-control" name="template_type" id="templateType" required onchange="updateTemplateInfo()">
                                        <option value="">-- Chọn mẫu --</option>
                                        <option value="small">🎬 Phòng nhỏ (8×12 = 96 ghế)</option>
                                        <option value="medium" selected>🎬 Phòng trung bình (12×18 = 216 ghế)</option>
                                        <option value="large">🎬 Phòng lớn (15×24 = 360 ghế)</option>
                                        <option value="vip">👑 Phòng VIP (10×14 = 140 ghế)</option>
                                        <option value="custom">⚙️ Tùy chỉnh</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-12 mb-20" id="customRowsDiv" style="display:none;">
                                    <label class="form-label">Số hàng</label>
                                    <input class="form-control" type="number" name="custom_rows" id="customRows" min="1" max="20" value="12">
                                </div>

                                <div class="col-lg-3 col-12 mb-20" id="customColsDiv" style="display:none;">
                                    <label class="form-label">Số cột</label>
                                    <input class="form-control" type="number" name="custom_cols" id="customCols" min="1" max="30" value="18">
                                </div>

                                <div class="col-lg-12 col-12 mb-20">
                                    <div id="templateInfo" style="background:#f9fafb; border:1px solid #e5e7eb; padding:15px; border-radius:8px;">
                                        <strong>📋 Mô tả bố trí:</strong>
                                        <ul style="margin:10px 0 0 0; padding-left:20px; color:#4b5563;">
                                            <li>Hàng A-F: Ghế thường</li>
                                            <li>Hàng G-I: Ghế trung</li>
                                            <li>Hàng J-L: Ghế VIP ở giữa</li>
                                            <li>Tự động tạo lối đi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <button class="button button-primary" type="submit" name="tao_map_template" value="1">
                                ✨ Tạo sơ đồ tự động
                            </button>
                        </form>

                        <script>
                        const templates = {
                            small: { desc: ['Hàng A-D: Ghế thường', 'Hàng E-F: Ghế trung', 'Hàng G-H: Ghế VIP ở giữa', 'Lối đi tự động'] },
                            medium: { desc: ['Hàng A-F: Ghế thường', 'Hàng G-I: Ghế trung', 'Hàng J-L: Ghế VIP ở giữa', 'Lối đi tự động'] },
                            large: { desc: ['Hàng A-H: Ghế thường', 'Hàng I-L: Ghế trung', 'Hàng M-O: Ghế VIP ở giữa', 'Lối đi tự động'] },
                            vip: { desc: ['Hàng A-C: Ghế trung', 'Hàng D-J: Ghế VIP toàn bộ', 'Ghế rộng, thoải mái', 'Lối đi tự động'] }
                        };
                        
                        function updateTemplateInfo() {
                            const type = document.getElementById('templateType').value;
                            const info = document.getElementById('templateInfo');
                            const customRowsDiv = document.getElementById('customRowsDiv');
                            const customColsDiv = document.getElementById('customColsDiv');
                            
                            if (type === 'custom') {
                                customRowsDiv.style.display = 'block';
                                customColsDiv.style.display = 'block';
                                info.innerHTML = '<strong>📋 Mô tả:</strong><ul style="margin:10px 0 0 0;padding-left:20px;"><li>Tự động phân bổ ghế theo tỷ lệ hợp lý</li></ul>';
                            } else if (type && templates[type]) {
                                customRowsDiv.style.display = 'none';
                                customColsDiv.style.display = 'none';
                                info.innerHTML = '<strong>📋 Mô tả bố trí:</strong><ul style="margin:10px 0 0 0;padding-left:20px;color:#4b5563;">' + 
                                    templates[type].desc.map(d => '<li>' + d + '</li>').join('') + '</ul>';
                            }
                        }
                        </script>

                    <?php else: ?>
                        <!-- Đã có sơ đồ - Hiển thị preview -->
                        <div style="background:#dbeafe; border:1px solid #3b82f6; padding:15px; border-radius:8px; margin-bottom:20px;">
                            <p style="color:#1e40af; margin:0;">
                                ✅ <strong>Sơ đồ ghế đã được thiết lập</strong> - 
                                <?php
                                $total = count($map);
                                $active = count(array_filter($map, function($s){ return (int)$s['active'] === 1; }));
                                echo "$active ghế hoạt động / $total ghế tổng";
                                ?>
                            </p>
                        </div>

                        <link rel="stylesheet" href="../Trang-nguoi-dung/css/style3860.css">
                        <style>
                            :root{ --seat-cheap:#fff0c7; --seat-middle:#ffc8cb; --seat-exp:#cdb4bd; --seat-off:#dbdee1 }
                            .seat-off{opacity:.35;filter:grayscale(1);}
                            
                            /* Khung bao ngoài - co giãn theo nội dung */
                            .sits-area {
                                    display: inline-block;
                                    margin: 0 auto;
                                    background: #fff;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 27px;
                                    padding: 29px 152px 50px;
                                    box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
                                    position: relative;
                                    min-width: 253px;
                                    max-width: 100%;
                                }
                            .sits-anchor{color:#6b7280;font-weight:600;margin-bottom:35px;text-align:center;}
                            
                            /* Container chính căn giữa */
                            .sits-container{text-align:center;width:100%;}
                            
                            /* Container chứa sơ đồ ghế */
                            .sits{
                                position:relative;
                                display:inline-block;
                                margin:0 auto;
                                /* Tính toán width chính xác để khung vừa khít */
                                width:fit-content;
                            }
                            .sits .sits__row{white-space:nowrap;text-align:center;margin:2px 0;}
                            
                            /* Ghế - kích thước cố định */
                            .sits .sits__row .sits__place{
                                width:30px;height:30px;margin:3px;
                                display:inline-block;border-radius:6px;
                                font-size:10px;line-height:30px;text-align:center;
                                vertical-align:top;
                            }
                            .sits .sits__row .sits__place:before{border-radius:6px}
                            
                            /* Chữ cái bên trái và phải - đồng bộ với ghế */
                            .sits__line{
                                position:absolute;left:-68px;top:0; /* Giảm khoảng cách */
                                display:flex;flex-direction:column;
                                justify-content:flex-start;
                                width:30px;
                            }
                            .sits__line--right{right:-68px;left:auto;} /* Giảm khoảng cách */
                            .sits__line .sits__indecator{
                                height:30px;line-height:20px;margin:4px 0;
                                border:1px solid #d1d5db;border-radius:6px;
                                color:#4b5563;background:#f9fafb;
                                text-align:center;font-size:11px;font-weight:600;
                            }
                            
                            /* Số cột phía dưới - đồng bộ với ghế */
                            .sits__number{
                                margin-top:10px;text-align:center;
                                white-space:nowrap;
                            }
                            .sits__number .sits__indecator{
                                width:30px;height:29px;line-height:19px;margin:0 3px;
                                display:inline-block;
                                border:1px solid #d1d5db;border-radius:6px;
                                color:#4b5563;background:#f9fafb;
                                text-align:center;font-size:11px;font-weight:600;
                            }
                            
                            .choose-sits__info{margin-bottom:15px;}
                            .choose-sits__info ul{list-style:none;padding:0;display:flex;gap:15px;flex-wrap:wrap;margin:0;}
                            .choose-sits__info ul li{display:flex;align-items:center;gap:8px;font-size:13px;}
                        </style>

                        <div class="choose-sits">
                            <div class="choose-sits__info choose-sits__info--first">
                                <ul>
                                    <li class="sits-price marker--none"><strong>Giá:</strong></li>
                                    <li class="sits-price sits-price--cheap">Thường</li>
                                    <li class="sits-price sits-price--middle">Trung</li>
                                    <li class="sits-price sits-price--expensive">VIP</li>
                                </ul>
                            </div>
                            <div class="choose-sits__info">
                                <ul>
                                    <li class="sits-state sits-state--not">Ghế đã khóa (không dùng)</li>
                                </ul>
                            </div>
                            
                            <div class="sits-container">
                                <div class="sits-area">
                                    <div class="sits-anchor">🎬 Màn hình</div>
                                    <div class="sits">
                                    <?php
                                    // Nhóm ghế theo hàng và sắp xếp
                                    $byRow = [];
                                    foreach ($map as $m){ $byRow[$m['row_label']][]=$m; }
                                    ksort($byRow); // Sắp xếp theo alphabet A, B, C...
                                    
                                    // Tìm số cột tối đa
                                    $maxCol = 0;
                                    foreach ($byRow as $list) {
                                        foreach ($list as $s) {
                                            $maxCol = max($maxCol, (int)$s['seat_number']);
                                        }
                                    }
                                    
                                    // Chữ cái bên trái
                                    echo '<aside class="sits__line">';
                                    foreach (array_keys($byRow) as $r){ 
                                        echo '<span class="sits__indecator">'.$r.'</span>'; 
                                    }
                                    echo '</aside>';
                                    
                                    // Render từng hàng ghế
                                    foreach ($byRow as $r => $list){
                                        // Sắp xếp ghế theo số cột
                                        usort($list, function($a,$b){ return $a['seat_number'] <=> $b['seat_number']; });
                                        
                                        echo '<div class="sits__row">';
                                        
                                        // Tạo mảng ghế đầy đủ từ 1 đến maxCol
                                        $fullRow = [];
                                        for ($col = 1; $col <= $maxCol; $col++) {
                                            $found = false;
                                            foreach ($list as $s) {
                                                if ((int)$s['seat_number'] === $col) {
                                                    $fullRow[$col] = $s;
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                            if (!$found) {
                                                // Ghế không tồn tại (lối đi)
                                                $fullRow[$col] = null;
                                            }
                                        }
                                        
                                        // Render ghế hoặc khoảng trống
                                        for ($col = 1; $col <= $maxCol; $col++) {
                                            if ($fullRow[$col]) {
                                                $s = $fullRow[$col];
                                                $cls = 'sits__place sits-price--'.htmlspecialchars($s['tier']);
                                                if (!(int)$s['active']) $cls .= ' seat-off';
                                                echo '<span class="'.$cls.'">'.htmlspecialchars($s['code']).'</span>';
                                            } else {
                                                // Khoảng trống (lối đi)
                                                echo '<span style="width:30px;height:30px;margin:3px;display:inline-block;"></span>';
                                            }
                                        }
                                        
                                        echo '</div>';
                                    }
                                    
                                    // Chữ cái bên phải
                                    echo '<aside class="sits__line sits__line--right">';
                                    foreach (array_keys($byRow) as $r){ 
                                        echo '<span class="sits__indecator">'.$r.'</span>'; 
                                    }
                                    echo '</aside>';
                                    
                                    // Số cột phía dưới
                                    if ($maxCol > 0) {
                                        echo '<footer class="sits__number">';
                                        for($i = 1; $i <= $maxCol; $i++){ 
                                            echo '<span class="sits__indecator">'.$i.'</span>'; 
                                        }
                                        echo '</footer>';
                                    }
                                    ?>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="row mt-20">
                            <div class="col-12">
                                <a href="index.php?act=suaphong&ids=<?= $id ?>&edit_advanced=1" class="button button-outline">
                                    ⚙️ Chỉnh sửa chi tiết sơ đồ
                                </a>
                                <form method="post" action="index.php?act=suaphong&ids=<?= $id ?>" style="display:inline-block;margin-left:10px;" 
                                      onsubmit="return confirm('⚠️ Bạn có chắc muốn xóa toàn bộ sơ đồ ghế hiện tại?')">
                                    <button type="submit" name="xoa_map" value="1" class="button button-outline button-danger">
                                        🗑️ Xóa và tạo lại
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($error) && $error != ""): ?>
        <p style="color: red; text-align: center;"><?= $error ?></p>
    <?php endif; ?>
</div><!-- Content Body End -->
