<?php include __DIR__ . '/home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading"><h3>Quản Lý Tin Tức</h3></div>
        </div>
    </div>

        <!-- Alert Messages -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?= htmlspecialchars($_GET['note'] ?? 'Thành công!') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Thêm Tin Tức Mới</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?act=quan_ly_tin_tuc" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label for="tieu_de" class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tieu_de" name="tieu_de" required>
                            </div>

                            <div class="mb-3">
                                <label for="tom_tat" class="form-label">Tóm Tắt</label>
                                <textarea class="form-control" id="tom_tat" name="tom_tat" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="noi_dung" class="form-label">Nội Dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="noi_dung" name="noi_dung" rows="5" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="hinh_anh" class="form-label">Hình Ảnh</label>
                                <input type="file" class="form-control" id="hinh_anh" name="hinh_anh" accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted">Tối đa 5MB (JPG, PNG, GIF, WebP)</small>
                                <div id="preview" class="mt-2"></div>
                            </div>

                            <button type="submit" class="btn btn-primary">✚ Thêm Tin Tức</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- News List -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Danh Sách Tin Tức</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="25%">Tiêu Đề</th>
                                        <th width="40%">Tóm Tắt</th>
                                        <th width="15%">Ngày Đăng</th>
                                        <th width="15%">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT id, tieu_de, tom_tat, noi_dung, hinh_anh, ngay_dang FROM tintuc ORDER BY ngay_dang DESC LIMIT 20";
                                        $news_list = pdo_query($sql);
                                        
                                        if (!empty($news_list)) {
                                            foreach ($news_list as $tin) {
                                                extract($tin);
                                                $ngay_format = date('d/m/Y H:i', strtotime($ngay_dang));
                                                $tom_tat_display = !empty($tom_tat) ? $tom_tat : (strlen($noi_dung) > 100 ? substr($noi_dung, 0, 100) . '...' : $noi_dung);
                                                echo '
                                                <tr>
                                                    <td>' . $id . '</td>
                                                    <td><strong>' . htmlspecialchars($tieu_de) . '</strong></td>
                                                    <td>' . htmlspecialchars($tom_tat_display) . '</td>
                                                    <td>' . $ngay_format . '</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                            onclick="loadNewsData(' . $id . ', ' . json_encode($tieu_de) . ', ' . json_encode($tom_tat) . ', ' . json_encode($noi_dung) . ', ' . json_encode($hinh_anh) . ')">
                                                            ✏️ Sửa
                                                        </button>
                                                        <form method="POST" action="index.php?act=quan_ly_tin_tuc" style="display:inline;">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="' . $id . '">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn chắc chắn muốn xóa tin tức này?\')">
                                                                🗑️ Xóa
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5" class="text-center text-muted">Chưa có tin tức nào</td></tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Chỉnh Sửa Tin Tức</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?act=quan_ly_tin_tuc" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="news_id">
                    
                    <div class="mb-3">
                        <label for="edit_tieu_de" class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_tieu_de" name="tieu_de" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_tom_tat" class="form-label">Tóm Tắt</label>
                        <textarea class="form-control" id="edit_tom_tat" name="tom_tat" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_noi_dung" class="form-label">Nội Dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_noi_dung" name="noi_dung" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_hinh_anh" class="form-label">Hình Ảnh Mới</label>
                        <input type="file" class="form-control" id="edit_hinh_anh" name="hinh_anh" accept="image/*" onchange="previewImageEdit(event)">
                        <small class="text-muted">Để trống nếu không muốn đổi ảnh</small>
                        <div id="edit_preview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">💾 Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.table-light th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 200px; border-radius: 5px;">';
        };
        reader.readAsDataURL(file);
    }
}

function previewImageEdit(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('edit_preview');
    preview.innerHTML = '';
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 200px; border-radius: 5px;">';
        };
        reader.readAsDataURL(file);
    }
}

function loadNewsData(id, tieu_de, tom_tat, noi_dung, hinh_anh) {
    document.getElementById('news_id').value = id;
    document.getElementById('edit_tieu_de').value = tieu_de;
    document.getElementById('edit_tom_tat').value = tom_tat;
    document.getElementById('edit_noi_dung').value = noi_dung;
    document.getElementById('edit_preview').innerHTML = '';
    document.getElementById('edit_hinh_anh').value = '';
}
</script>
</div>
<?php include __DIR__ . '/home/footer.php'; ?>
