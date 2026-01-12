<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        .config-header {
            background: linear-gradient(135deg, #a8abbaff 0%, #b59ccdff 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .config-header h3 {
            margin: 0;
            font-size: 28px;
        }
        .config-info {
            font-size: 13px;
            opacity: 0.9;
        }
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input,
        .form-group textarea {
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .logo-preview-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .logo-preview-current {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo-preview-current img {
            max-width: 150px;
            max-height: 100px;
            object-fit: contain;
        }
        .logo-preview-current small {
            color: #6b7280;
            margin-top: 10px;
        }
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        .status-current {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .status-current strong {
            color: #065f46;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
    </style>

    <div class="config-header">
        <div>
            <h3>Cấu hình Website</h3>
            <div class="config-info">Quản lý thông tin website, logo, liên hệ và mạng xã hội</div>
        </div>
        <?php if (!empty($cfg['ngay_cap_nhat'])): ?>
            <div style="text-align: right; font-size: 13px;">
                Cập nhật lần cuối: <?= date('d/m/Y H:i', strtotime($cfg['ngay_cap_nhat'] ?? 'now')) ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($cfg)): ?>
        <div class="status-current">
            <strong>Thông tin hiện tại:</strong> Tên: <code><?= htmlspecialchars($cfg['ten_website'] ?? 'N/A') ?></code> | Email: <code><?= htmlspecialchars($cfg['email'] ?? 'N/A') ?></code> | SĐT: <code><?= htmlspecialchars($cfg['so_dien_thoai'] ?? 'N/A') ?></code>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="index.php?act=cauhinh">
        
        <!-- Phần Thông tin cơ bản -->
        <div class="form-section">
            <div class="section-title">Thông Tin Cơ Bản</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="ten_website">Tên Website <span style="color: #ef4444;">*</span></label>
                    <input class="form-control" type="text" id="ten_website" name="ten_website" 
                           value="<?= htmlspecialchars($cfg['ten_website'] ?? '') ?>" required 
                           placeholder="Nhập tên website">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($cfg['email'] ?? '') ?>" 
                           placeholder="Nhập email liên hệ">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="so_dien_thoai">Số Điện Thoại</label>
                    <input class="form-control" type="text" id="so_dien_thoai" name="so_dien_thoai" 
                           value="<?= htmlspecialchars($cfg['so_dien_thoai'] ?? '') ?>" 
                           placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label for="dia_chi">Địa Chỉ</label>
                    <input class="form-control" type="text" id="dia_chi" name="dia_chi" 
                           value="<?= htmlspecialchars($cfg['dia_chi'] ?? '') ?>" 
                           placeholder="Nhập địa chỉ">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="mo_ta">Mô Tả Website</label>
                    <textarea class="form-control" id="mo_ta" name="mo_ta" rows="4" 
                              placeholder="Nhập mô tả website (sẽ hiển thị ở Meta description)"><?= htmlspecialchars($cfg['mo_ta'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Phần Logo -->
        <div class="form-section">
            <div class="section-title">Logo & Hình Ảnh</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="logo">Chọn Logo Mới</label>
                    <input class="form-control" type="file" id="logo" name="logo" accept="image/*" 
                           onchange="previewLogo(event)">
                    <small style="color: #6b7280; margin-top: 8px;">Định dạng: JPG, PNG, GIF, WebP. Kích thước tối ưu: 200x100px</small>
                </div>
            </div>
            <?php if (!empty($cfg['logo'])): ?>
                <div class="form-row">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Logo Hiện Tại</label>
                        <div class="logo-preview-current">
                            <img src="../../Trang-nguoi-dung/<?= htmlspecialchars($cfg['logo']) ?>" 
                                 alt="Logo" onerror="this.style.display='none'">
                            <small>📁 <?= htmlspecialchars($cfg['logo']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phần Video Banner -->
        <div class="form-section">
            <div class="section-title">Video Banner (Trang Chủ)</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="video_banner">Chọn Video Banner Mới</label>
                    <input class="form-control" type="file" id="video_banner" name="video_banner" 
                           accept="video/mp4,video/webm,video/ogg,application/x-mpegURL"
                           onchange="previewVideo(event)">
                    <small style="color: #6b7280; margin-top: 8px;">
                        Định dạng hỗ trợ: MP4, WebM, OGG, M3U8 (HLS). 
                        Kích thước tối ưu: 1920x1080 (Full HD) hoặc 1280x720 (HD). 
                        Size: &lt; 50MB
                    </small>
                </div>
            </div>
            <?php if (!empty($cfg['video_banner'])): ?>
                <div class="form-row">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Video Banner Hiện Tại</label>
                        <div class="logo-preview-current" style="min-height: 300px;">
                            <video controls style="max-width: 100%; max-height: 280px; border-radius: 8px;">
                                <source src="../../Trang-nguoi-dung/<?= htmlspecialchars($cfg['video_banner']) ?>" type="video/mp4">
                                Trình duyệt của bạn không hỗ trợ video tag.
                            </video>
                            <small style="margin-top: 15px;"><?= htmlspecialchars($cfg['video_banner']) ?></small>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-row">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <div class="logo-preview-current" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                            <small style="color: #92400e;">Chưa cấu hình video banner. Đang dùng video mặc định: video/OFFICIAL TRAILER.mp4</small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phần Mạng Xã Hội -->
        <div class="form-section">
            <div class="section-title">Mạng Xã Hội</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="facebook">Facebook URL</label>
                    <input class="form-control" type="text" id="facebook" name="facebook" 
                           value="<?= htmlspecialchars($cfg['facebook'] ?? '') ?>" 
                           placeholder="https://facebook.com/your-page">
                </div>
                <div class="form-group">
                    <label for="instagram">Instagram URL</label>
                    <input class="form-control" type="text" id="instagram" name="instagram" 
                           value="<?= htmlspecialchars($cfg['instagram'] ?? '') ?>" 
                           placeholder="https://instagram.com/your-profile">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="youtube">▶YouTube URL</label>
                    <input class="form-control" type="text" id="youtube" name="youtube" 
                           value="<?= htmlspecialchars($cfg['youtube'] ?? '') ?>" 
                           placeholder="https://youtube.com/your-channel">
                </div>
            </div>
        </div>

        <!-- Nút Lưu -->
        <div class="form-section" style="text-align: center; background: #f9fafb; border: 2px dashed #e5e7eb;">
            <button class="btn-save" type="submit" name="luu" value="1">
                Lưu cấu hình
            </button>
            <div style="margin-top: 15px; font-size: 13px; color: #6b7280;">
                Thay đổi sẽ tự động cập nhật trên trang người dùng (header, footer, title)
            </div>
        </div>
    </form>

    <!-- Preview Link -->
    <div style="margin-top: 30px; padding: 20px; background: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 8px;">
        <strong style="color: #1e40af;">Xem trước:</strong>
        <div style="margin-top: 10px;">
            <a href="/Trang-nguoi-dung/test_config.php" target="_blank" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                Test page - Xem tất cả dữ liệu cấu hình
            </a>
        </div>
        <div style="margin-top: 8px; font-size: 13px; color: #1e40af;">
            Trang người dùng sẽ tự động cập nhật sau khi bạn nhấn "Lưu cấu hình"
        </div>
    </div>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Có thể thêm preview ở đây nếu muốn
            console.log('Logo selected:', file.name);
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php include __DIR__ . '/../home/footer.php'; ?>

