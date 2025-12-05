<?php 
include "./view/home/sideheader.php";
?>
<!-- Content Body Start -->
<div class="content-body">

    <!-- Header Section with Gradient -->
    <div style="background: linear-gradient(135deg, #989fbcff 0%, #b4a3c5ff 100%); border-radius: 12px; padding: 30px; margin-bottom: 30px; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 style="margin: 0; font-weight: 700; font-size: 28px;">📺 Quản Lý Phòng Chiếu</h2>
                <p style="margin: 8px 0 0 0; opacity: 0.95; font-size: 14px;">Quản lý các phòng chiếu phim</p>
            </div>
            <div class="col-md-6 text-end">
                <button onclick="openAddPhongModal()" style="background: white; color: #667eea; padding: 12px 24px; border-radius: 33px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s; border: none; cursor: pointer; float: right;">
                    <i class="zmdi zmdi-plus" style="margin-right: 8px;"></i>Thêm Phòng Mới
                </button>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if(isset($suatc) && ($suatc) != "") { ?>
        <div style="background: #fee; border-left: 4px solid #dc3545; padding: 15px 20px; border-radius: 6px; margin-bottom: 20px; color: #c82333;">
            <i class="zmdi zmdi-alert-circle" style="margin-right: 8px; font-weight: bold;"></i>
            <strong><?php echo $suatc; ?></strong>
        </div>
    <?php } ?>

    <!-- Room List Card -->
    <div class="row">
        <div class="col-12">
            <div style="background: white; border-radius: 12px; padding: 0; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                
                <!-- Table Header Stats -->
                <div style="padding: 20px 25px; background: #f8f9fb; border-bottom: 1px solid #e9ecef;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h5 style="margin: 0; font-size: 14px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="zmdi zmdi-layers" style="color: #667eea; margin-right: 8px;"></i>Danh Sách Phòng
                            </h5>
                        </div>
                        <span style="background: #e7f1ff; color: #667eea; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                            Tổng: <?php echo count($loadphong); ?> phòng
                        </span>
                    </div>
                </div>

                <!-- Table -->
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <thead>
                            <tr style="background: #f8f9fb; border-bottom: 2px solid #e9ecef;">
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #495057; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="zmdi zmdi-info" style="margin-right: 8px;"></i>ID
                                </th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #495057; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="zmdi zmdi-label" style="margin-right: 8px;"></i>Tên Phòng
                                </th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #495057; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="zmdi zmdi-chairs" style="margin-right: 8px;"></i>Số Ghế
                                </th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #495057; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                                    <i class="zmdi zmdi-settings" style="margin-right: 8px;"></i>Hành Động
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            foreach ($loadphong as $pc) {
                                $count++;
                                extract($pc);
                                $linksua = "index.php?act=suaphong&ids=" . $id;
                                $linkxoa = "index.php?act=xoaphong&idxoa=" . $id;
                                $background = ($count % 2 == 0) ? "#ffffff" : "#f8f9fb";
                                
                                echo '<tr style="background: ' . $background . '; border-bottom: 1px solid #e9ecef; transition: all 0.3s ease;">
                                    <td style="padding: 15px 20px; color: #667eea; font-weight: 600;">
                                        <span style="background: #e7f1ff; padding: 4px 10px; border-radius: 6px; display: inline-block;">
                                            #' . $id . '
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; color: #2c3e50; font-weight: 500;">
                                        <i class="zmdi zmdi-tv" style="margin-right: 8px; color: #764ba2;"></i>' . htmlspecialchars($pc['name']) . '
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; color: #495057; font-weight: 500;">
                                        <span style="background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 6px; display: inline-block; font-weight: 600;">
                                            <i class="zmdi zmdi-chairs" style="margin-right: 4px;"></i>' . (isset($pc['so_ghe']) ? $pc['so_ghe'] : 0) . ' ghế
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="' . $linksua . '" title="Chỉnh sửa" style="
                                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                color: white;
                                                padding: 8px 14px;
                                                border-radius: 6px;
                                                text-decoration: none;
                                                font-size: 13px;
                                                font-weight: 600;
                                                border: none;
                                                cursor: pointer;
                                                transition: all 0.3s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 6px;
                                            " onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(102, 126, 234, 0.4)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\';">
                                                <i class="zmdi zmdi-edit"></i> Sửa
                                            </a>
                                            <a href="' . $linkxoa . '" title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa phòng này?\');" style="
                                                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                                                color: white;
                                                padding: 8px 14px;
                                                border-radius: 6px;
                                                text-decoration: none;
                                                font-size: 13px;
                                                font-weight: 600;
                                                border: none;
                                                cursor: pointer;
                                                transition: all 0.3s;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 6px;
                                            " onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(245, 87, 108, 0.4)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\';">
                                                <i class="zmdi zmdi-delete"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <?php if (count($loadphong) == 0) { ?>
                    <div style="padding: 60px 20px; text-align: center; color: #6c757d;">
                        <i class="zmdi zmdi-tv" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px; display: block;"></i>
                        <h5 style="margin: 0 0 10px 0; color: #495057;">Chưa có phòng chiếu</h5>
                        <p style="margin: 0; font-size: 13px;">
                            <button onclick="openAddPhongModal()" style="background: none; border: none; color: #667eea; text-decoration: none; font-weight: 600; cursor: pointer;">Thêm phòng chiếu đầu tiên</button>
                        </p>
                    </div>
                <?php } ?>

                <!-- Table Footer -->
                <div style="padding: 15px 25px; background: #f8f9fb; border-top: 1px solid #e9ecef; font-size: 13px; color: #6c757d;">
                    <i class="zmdi zmdi-info-outline"></i> 
                    Hiển thị <strong><?php echo count($loadphong); ?></strong> phòng chiếu
                </div>
            </div>
        </div>
    </div>

</div><!-- Content Body End -->

<!-- Modal Thêm Phòng -->
<div id="addPhongModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; overflow-y: auto;">
    <div style="background: white; margin: 120px auto 50px auto; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); animation: slideUp 0.3s ease; margin-bottom: 100px;">
        
        <!-- Modal Header -->
        <div style="background: linear-gradient(135deg, #2c50f1ff 0%, #767398ff 100%); padding: 20px 25px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; color: white; font-weight: 700; font-size: 18px;">
                <i class="zmdi zmdi-plus-circle" style="margin-right: 8px;"></i>Thêm Phòng Chiếu Mới
            </h5>
            <button onclick="closeAddPhongModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; cursor: pointer; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">×</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: 25px;">
            <form id="addPhongForm" onsubmit="submitAddPhongForm(event)">
                
                <!-- Tên Phòng -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Tên Phòng <span style="color: #dc3545;">*</span></label>
                    <input type="text" name="name" placeholder="VD: Phòng 1, Phòng VIP..." required style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: all 0.3s; box-sizing: border-box;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)';" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';">
                </div>

                <!-- Diện Tích -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Diện Tích (m²)</label>
                    <input type="number" name="dien_tich" placeholder="VD: 150" step="0.01" min="0" style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: all 0.3s; box-sizing: border-box;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)';" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';">
                </div>

                <!-- Loại Phòng -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Loại Phòng <span style="color: #dc3545;">*</span></label>
                    <select name="loai_phong" id="modalLoaiPhong" required onchange="updateModalSoGhe()" style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: all 0.3s; background-color: white; box-sizing: border-box;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)';" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';">
                        <option value="">-- Chọn loại phòng --</option>
                        <option value="small">🎬 Phòng nhỏ (8 hàng × 12 cột = 96 ghế)</option>
                        <option value="medium" selected>🎬 Phòng trung bình (12 hàng × 18 cột = 216 ghế)</option>
                        <option value="large">🎬 Phòng lớn (15 hàng × 24 cột = 360 ghế)</option>
                        <option value="vip">👑 Phòng VIP (10 hàng × 14 cột = 140 ghế)</option>
                        <option value="custom">⚙️ Tùy chỉnh (nhập số hàng/cột)</option>
                    </select>
                </div>

                <!-- Custom Rows/Cols (ẩn ban đầu) -->
                <div id="modalCustomRowsDiv" style="display: none; margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Số Hàng Ghế</label>
                    <input type="number" id="modalCustomRows" name="custom_rows" min="1" max="20" value="12" style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; box-sizing: border-box;" onchange="updateModalCustom()" oninput="updateModalCustom()">
                </div>

                <div id="modalCustomColsDiv" style="display: none; margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Số Ghế Mỗi Hàng</label>
                    <input type="number" id="modalCustomCols" name="custom_cols" min="1" max="30" value="18" style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; box-sizing: border-box;" onchange="updateModalCustom()" oninput="updateModalCustom()">
                </div>

                <!-- Số Ghế (Read-only) -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Tổng Số Ghế</label>
                    <input type="number" id="modalSoGhe" name="so_ghe" readonly style="width: 100%; padding: 10px 14px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; background: #f8f9fb; font-weight: 600; color: #667eea; cursor: not-allowed; box-sizing: border-box;" value="216">
                </div>

                <!-- Mô Tả -->
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                    <strong style="color: #2c3e50; display: block; margin-bottom: 10px;">📋 Mô Tả Bố Trí:</strong>
                    <ul id="modalMoTaBoTri" style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 13px;">
                        <li>Hàng A-F: Ghế thường (màu vàng nhạt)</li>
                        <li>Hàng G-I: Ghế trung (màu hồng nhạt)</li>
                        <li>Hàng J-L: Ghế VIP ở giữa (màu tím nhạt)</li>
                        <li>Tự động tạo 2 lối đi ở cột 5 và cột 14</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeAddPhongModal()" style="padding: 10px 20px; border: 2px solid #e9ecef; background: white; color: #495057; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#f8f9fb';" onmouseout="this.style.background='white';">Hủy</button>
                    <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102,126,234,0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="zmdi zmdi-check" style="margin-right: 6px;"></i>Thêm Phòng
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- JavaScript cho Modal -->
<script>
const modalTemplates = {
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

// Submit form qua AJAX
function submitAddPhongForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('addPhongForm');
    const name = document.querySelector('#addPhongForm input[name="name"]').value.trim();
    const dien_tich = document.querySelector('#addPhongForm input[name="dien_tich"]').value || 0;
    const so_ghe = document.getElementById('modalSoGhe').value;
    const loai_phong = document.getElementById('modalLoaiPhong').value;
    const custom_rows = document.querySelector('#addPhongForm input[name="custom_rows"]')?.value || null;
    const custom_cols = document.querySelector('#addPhongForm input[name="custom_cols"]')?.value || null;
    
    // Validate
    if (!name) {
        alert('Vui lòng nhập tên phòng');
        return;
    }
    
    if (!loai_phong) {
        alert('Vui lòng chọn loại phòng');
        return;
    }
    
    // Khóa button
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Đang xử lý...';
    
    // Gửi AJAX
    fetch('index.php?act=ajax_them_phong', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            dien_tich: parseFloat(dien_tich),
            so_ghe: parseInt(so_ghe),
            loai_phong: loai_phong,
            custom_rows: custom_rows ? parseInt(custom_rows) : null,
            custom_cols: custom_cols ? parseInt(custom_cols) : null,
            len: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Thêm hàng mới vào bảng
            addNewPhongToTable(data.phong);
            
            // Hiển thị thông báo thành công
            showSuccessMessage(data.message);
            
            // Đóng modal
            closeAddPhongModal();
            
            // Reset form
            form.reset();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm phòng'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối: ' + error.message);
    })
    .finally(() => {
        // Mở lại button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="zmdi zmdi-check" style="margin-right: 6px;"></i>Thêm Phòng';
    });
}

// Thêm hàng phòng mới vào bảng
function addNewPhongToTable(phong) {
    const tbody = document.querySelector('table tbody');
    
    // Kiểm tra nếu bảng trống
    const emptyState = document.querySelector('.padding[style*="60px"]');
    if (emptyState) {
        emptyState.remove();
    }
    
    // Tính background color xen kẽ
    const lastRow = tbody.lastElementChild;
    const rowCount = tbody.querySelectorAll('tr').length;
    const background = (rowCount % 2 == 0) ? '#ffffff' : '#f8f9fb';
    
    // Tạo hàng mới
    const tr = document.createElement('tr');
    tr.style.cssText = `background: ${background}; border-bottom: 1px solid #e9ecef; transition: all 0.3s ease; animation: slideIn 0.3s ease;`;
    
    tr.innerHTML = `
        <td style="padding: 15px 20px; color: #667eea; font-weight: 600;">
            <span style="background: #e7f1ff; padding: 4px 10px; border-radius: 6px; display: inline-block;">
                #${phong.id}
            </span>
        </td>
        <td style="padding: 15px 20px; color: #2c3e50; font-weight: 500;">
            <i class="zmdi zmdi-tv" style="margin-right: 8px; color: #764ba2;"></i>${escapeHtml(phong.name)}
        </td>
        <td style="padding: 15px 20px; text-align: center; color: #495057; font-weight: 500;">
            <span style="background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 6px; display: inline-block; font-weight: 600;">
                <i class="zmdi zmdi-chairs" style="margin-right: 4px;"></i>${phong.so_ghe} ghế
            </span>
        </td>
        <td style="padding: 15px 20px; text-align: center;">
            <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="index.php?act=suaphong&ids=${phong.id}" title="Chỉnh sửa" style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 8px 14px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-size: 13px;
                    font-weight: 600;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="zmdi zmdi-edit"></i> Sửa
                </a>
                <a href="index.php?act=xoaphong&idxoa=${phong.id}" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa phòng này?');" style="
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                    padding: 8px 14px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-size: 13px;
                    font-weight: 600;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(245, 87, 108, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="zmdi zmdi-delete"></i> Xóa
                </a>
            </div>
        </td>
    `;
    
    tbody.appendChild(tr);
    
    // Cập nhật số lượng phòng
    const totalBadge = document.querySelector('span[style*="background: #e7f1ff"]');
    if (totalBadge) {
        const currentCount = parseInt(totalBadge.textContent.match(/\d+/)[0]);
        totalBadge.textContent = 'Tổng: ' + (currentCount + 1) + ' phòng';
    }
    
    // Cập nhật footer
    const footer = document.querySelector('div[style*="border-top: 1px solid #e9ecef"]');
    if (footer) {
        const countMatch = footer.textContent.match(/Hiển thị.*?(\d+)/);
        if (countMatch) {
            footer.innerHTML = '<i class="zmdi zmdi-info-outline"></i> Hiển thị <strong>' + (parseInt(countMatch[1]) + 1) + '</strong> phòng chiếu';
        }
    }
}

// Hiển thị thông báo thành công
function showSuccessMessage(message) {
    const contentBody = document.querySelector('.content-body');
    const alertDiv = document.createElement('div');
    alertDiv.style.cssText = `
        background: #d1fae5;
        border-left: 4px solid #059669;
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
        color: #065f46;
        animation: slideDown 0.3s ease;
        position: relative;
    `;
    alertDiv.innerHTML = `
        <i class="zmdi zmdi-check-circle" style="margin-right: 8px; font-weight: bold;"></i>
        <strong>${message}</strong>
        <button onclick="this.parentElement.remove()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #065f46; font-size: 20px; cursor: pointer;">×</button>
    `;
    
    contentBody.insertBefore(alertDiv, contentBody.firstChild);
    
    // Tự động ẩn sau 4 giây
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.animation = 'slideUp 0.3s ease forwards';
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 4000);
}

// Escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function openAddPhongModal() {
    document.getElementById('addPhongModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddPhongModal() {
    document.getElementById('addPhongModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('addPhongForm').reset();
    document.getElementById('modalLoaiPhong').value = 'medium';
    updateModalSoGhe();
}

function updateModalSoGhe() {
    const loai = document.getElementById('modalLoaiPhong').value;
    const customRowsDiv = document.getElementById('modalCustomRowsDiv');
    const customColsDiv = document.getElementById('modalCustomColsDiv');
    const soGhe = document.getElementById('modalSoGhe');
    const moTa = document.getElementById('modalMoTaBoTri');
    
    if (loai === 'custom') {
        customRowsDiv.style.display = 'block';
        customColsDiv.style.display = 'block';
        updateModalCustom();
    } else if (loai && modalTemplates[loai]) {
        customRowsDiv.style.display = 'none';
        customColsDiv.style.display = 'none';
        const t = modalTemplates[loai];
        soGhe.value = t.rows * t.cols;
        moTa.innerHTML = t.desc.map(d => '<li>' + d + '</li>').join('');
    } else {
        customRowsDiv.style.display = 'none';
        customColsDiv.style.display = 'none';
        soGhe.value = 0;
        moTa.innerHTML = '<li>Vui lòng chọn loại phòng</li>';
    }
}

function updateModalCustom() {
    const rows = parseInt(document.getElementById('modalCustomRows').value) || 0;
    const cols = parseInt(document.getElementById('modalCustomCols').value) || 0;
    document.getElementById('modalSoGhe').value = rows * cols;
    document.getElementById('modalMoTaBoTri').innerHTML = '<li>Tự động phân bổ ghế theo tỷ lệ: 50% Thường, 30% Trung, 20% VIP</li>';
}

// Đóng modal khi click bên ngoài
document.addEventListener('click', function(event) {
    const modal = document.getElementById('addPhongModal');
    if (event.target === modal) {
        closeAddPhongModal();
    }
});

// Khởi tạo
updateModalSoGhe();
</script>

<style>
@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: translateX(-20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
