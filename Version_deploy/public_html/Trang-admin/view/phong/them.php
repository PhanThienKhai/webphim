<?php
include "./view/home/sideheader.php";
?>
<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">

        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Loại Phim <span>/ Thêm Phim</span></h3>
            </div>
        </div><!-- Page Heading End -->

        <!-- Page Button Group Start -->

    </div><!-- Page Headings End -->

    <!-- Add or Edit Product Start -->
    <?php if(isset($suatc)&&($suatc)!= ""){
        echo'<p  style="color: red; text-align: center;">' .$suatc. '</p>';
    }?>
    <form action="index.php?act=themphong" method="post"  enctype="multipart/form-data">
        <div class="add-edit-product-wrap col-12">
            <div class="add-edit-product-form">

                    <h4 class="title">Thêm phòng chiếu</h4>

    
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-30">
                            <label class="form-label">Tên phòng <span style="color:red">*</span></label>
                            <input class="form-control" type="text" placeholder="VD: Phòng 1, Phòng VIP..." name="name" required>
                        </div>
                        <div class="col-lg-6 col-12 mb-30">
                            <label class="form-label">Diện tích (m²)</label>
                            <input class="form-control" type="number" step="0.01" placeholder="VD: 150" name="dien_tich" min="0">
                        </div>
                    </div>

                    <h4 class="title mt-30">Thiết lập sơ đồ ghế tự động</h4>
                    <p style="color:#6b7280; margin-bottom:15px;">Chọn mẫu phòng, hệ thống sẽ tự động tạo sơ đồ ghế phù hợp</p>
                    
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-30">
                            <label class="form-label">Chọn loại phòng <span style="color:red">*</span></label>
                            <select class="form-control" name="loai_phong" id="loaiPhong" required onchange="updateSoGhe()">
                                <option value="">-- Chọn loại phòng --</option>
                                <option value="small">🎬 Phòng nhỏ (8 hàng × 12 cột = 96 ghế)</option>
                                <option value="medium" selected>🎬 Phòng trung bình (12 hàng × 18 cột = 216 ghế)</option>
                                <option value="large">🎬 Phòng lớn (15 hàng × 24 cột = 360 ghế)</option>
                                <option value="vip">👑 Phòng VIP (10 hàng × 14 cột = 140 ghế)</option>
                                <option value="custom">⚙️ Tùy chỉnh (nhập số hàng/cột)</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-4 col-12 mb-30" id="customRowsDiv" style="display:none;">
                            <label class="form-label">Số hàng ghế</label>
                            <input class="form-control" type="number" name="custom_rows" id="customRows" min="1" max="20" value="12">
                        </div>
                        
                        <div class="col-lg-4 col-12 mb-30" id="customColsDiv" style="display:none;">
                            <label class="form-label">Số ghế mỗi hàng</label>
                            <input class="form-control" type="number" name="custom_cols" id="customCols" min="1" max="30" value="18">
                        </div>
                        
                        <div class="col-lg-4 col-12 mb-30">
                            <label class="form-label">Tổng số ghế</label>
                            <input class="form-control" type="number" name="so_ghe" id="soGhe" readonly style="background:#f3f4f6; font-weight:600; color:#111;" value="216">
                        </div>
                    </div>

                    <div class="row mb-20">
                        <div class="col-12">
                            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:15px;">
                                <strong style="color:#111;">📋 Mô tả bố trí:</strong>
                                <ul id="moTaBoTri" style="margin:10px 0 0 0; padding-left:20px; color:#4b5563;">
                                    <li>Hàng A-F: Ghế thường (màu vàng nhạt)</li>
                                    <li>Hàng G-I: Ghế trung (màu hồng nhạt)</li>
                                    <li>Hàng J-L: Ghế VIP ở giữa (màu tím nhạt)</li>
                                    <li>Tự động tạo 2 lối đi ở cột 5 và cột 14</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <script>
                    const templates = {
                        small: { rows: 8, cols: 12, desc: [
                            'Hàng A-D: Ghế thường',
                            'Hàng E-F: Ghế trung',
                            'Hàng G-H: Ghế VIP ở giữa',
                            'Lối đi ở cột 4 và cột 9'
                        ]},
                        medium: { rows: 12, cols: 18, desc: [
                            'Hàng A-F: Ghế thường',
                            'Hàng G-I: Ghế trung',
                            'Hàng J-L: Ghế VIP ở giữa',
                            'Lối đi ở cột 5 và cột 14'
                        ]},
                        large: { rows: 15, cols: 24, desc: [
                            'Hàng A-H: Ghế thường',
                            'Hàng I-L: Ghế trung',
                            'Hàng M-O: Ghế VIP ở giữa',
                            'Lối đi ở cột 7 và cột 18'
                        ]},
                        vip: { rows: 10, cols: 14, desc: [
                            'Hàng A-C: Ghế trung',
                            'Hàng D-J: Ghế VIP toàn bộ',
                            'Ghế rộng rãi, thoải mái',
                            'Lối đi ở cột 4 và cột 11'
                        ]}
                    };
                    
                    function updateSoGhe() {
                        const loai = document.getElementById('loaiPhong').value;
                        const customRowsDiv = document.getElementById('customRowsDiv');
                        const customColsDiv = document.getElementById('customColsDiv');
                        const soGhe = document.getElementById('soGhe');
                        const moTa = document.getElementById('moTaBoTri');
                        
                        if (loai === 'custom') {
                            customRowsDiv.style.display = 'block';
                            customColsDiv.style.display = 'block';
                            updateCustom();
                        } else if (loai && templates[loai]) {
                            customRowsDiv.style.display = 'none';
                            customColsDiv.style.display = 'none';
                            const t = templates[loai];
                            soGhe.value = t.rows * t.cols;
                            moTa.innerHTML = t.desc.map(d => '<li>' + d + '</li>').join('');
                        } else {
                            customRowsDiv.style.display = 'none';
                            customColsDiv.style.display = 'none';
                            soGhe.value = 0;
                            moTa.innerHTML = '<li>Vui lòng chọn loại phòng</li>';
                        }
                    }
                    
                    function updateCustom() {
                        const rows = parseInt(document.getElementById('customRows').value) || 0;
                        const cols = parseInt(document.getElementById('customCols').value) || 0;
                        document.getElementById('soGhe').value = rows * cols;
                        document.getElementById('moTaBoTri').innerHTML = '<li>Tự động phân bổ ghế theo tỷ lệ: 50% Thường, 30% Trung, 20% VIP</li>';
                    }
                    
                    document.getElementById('customRows')?.addEventListener('input', updateCustom);
                    document.getElementById('customCols')?.addEventListener('input', updateCustom);
                    </script>

                    </div>

                    <h4 class="title">Thao tác</h4>

                    <div class="product-upload-gallery row flex-wrap">


                        <!-- Button Group Start -->
                        <div class="row">
                            <div class="d-flex flex-wrap justify-content-end col mbn-10">
                                <button class="button button-outline button-primary mb-10 ml-10 mr-0" type="submit" name="len">Thêm</button>
                            </div>
                        </div><!-- Button Group End -->

            </div>

        </div><!-- Add or Edit Product End -->

    </form>
    <?php if(isset($error)&&$error !=""){
                echo '<p   style="color: red; text-align: center;"
                > '.$error.' </p>';
            } ?>
</div><!-- Content Body End -->
