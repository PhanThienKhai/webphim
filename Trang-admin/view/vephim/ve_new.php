<style>
    /* ===== HEADER & SEARCH SECTION ===== */
    .ticket-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .ticket-header h3 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .ticket-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* ===== SEARCH & FILTER SECTION ===== */
    .search-filter-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #667eea;
    }

    .search-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .search-input-group input:focus {
        outline: none;
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-input-group input::placeholder {
        color: #a0aec0;
    }

    .search-input-group i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #cbd5e0;
        pointer-events: none;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-search,
    .btn-refresh {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-refresh {
        background: #f3f4f6;
        color: #667eea;
        border: 2px solid #e5e7eb;
    }

    .btn-refresh:hover {
        background: #e5e7eb;
        border-color: #667eea;
    }

    .btn-update {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    /* ===== TABLE SECTION ===== */
    .ticket-table-wrapper {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 18px 16px;
        border-bottom: 2px solid #e5e7eb;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .table tbody tr:hover {
        background: #f9fafb;
        transform: scale(1.01);
        box-shadow: inset 0 0 12px rgba(102, 126, 234, 0.08);
    }

    .table tbody td {
        padding: 16px;
        font-size: 14px;
        color: #374151;
        vertical-align: middle;
    }

    .ticket-id {
        font-weight: 600;
        color: #667eea;
    }

    .ticket-name {
        font-weight: 500;
        color: #1f2937;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ticket-price {
        font-weight: 600;
        color: #ef4444;
    }

    /* ===== STATUS BADGES ===== */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
        border-left: 3px solid #f59e0b;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 3px solid #10b981;
    }

    .badge-used {
        background: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #ef4444;
    }

    .badge-cancelled {
        background: #fecaca;
        color: #7f1d1d;
        border-left: 3px solid #dc2626;
    }

    .badge-expired {
        background: #fed7aa;
        color: #7c2d12;
        border-left: 3px solid #f97316;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .btn-edit {
        background: #dbeafe;
        color: #0284c7;
    }

    .btn-edit:hover {
        background: #0284c7;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }

    .btn-view {
        background: #f3e8ff;
        color: #9333ea;
    }

    .btn-view:hover {
        background: #9333ea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 16px;
        margin: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .ticket-header {
            padding: 20px;
        }

        .search-row {
            grid-template-columns: 1fr;
        }

        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-search,
        .btn-refresh {
            width: 100%;
        }

        .table {
            font-size: 12px;
        }

        .table tbody td {
            padding: 12px;
        }

        .ticket-name {
            max-width: 120px;
        }

        .action-buttons {
            gap: 4px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }
</style>

<?php
include "./view/home/sideheader.php";
?>

<!-- Content Body Start -->
<div class="content-body">
    <div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
        
        <!-- Header Section -->
        <div class="ticket-header">
            <h3>🎫 Quản Lý Vé Xem Phim</h3>
            <p><?php echo isset($loadvephim) && is_array($loadvephim) ? count($loadvephim) : 0 ?> vé trong hệ thống</p>
        </div>

        <!-- Search & Filter Section -->
        <div class="search-filter-box">
            <form action="index.php?act=ve" method="post" style="margin-bottom: 20px;">
                <div class="search-row">
                    <div class="search-input-group">
                        <input type="text" name="ten" placeholder="🔍 Tìm kiếm tên khách hàng..." value="<?php echo htmlspecialchars($_POST['ten'] ?? '') ?>">
                    </div>
                    <div class="search-input-group">
                        <input type="text" name="tieude" placeholder="🎬 Tìm kiếm tên phim..." value="<?php echo htmlspecialchars($_POST['tieude'] ?? '') ?>">
                    </div>
                    <div class="search-input-group">
                        <input type="text" name="id_ve" placeholder="🏷️ Tìm kiếm ID vé..." value="<?php echo htmlspecialchars($_POST['id_ve'] ?? '') ?>">
                    </div>
                    <div class="btn-group">
                        <button type="submit" name="tk" class="btn-search">🔎 Tìm Kiếm</button>
                        <a href="index.php?act=ve" class="btn-refresh">↻ Làm Mới</a>
                    </div>
                </div>
            </form>

            <!-- Update Button -->
            <form action="index.php?act=capnhat_tt_ve" method="post" style="margin: 0;">
                <button type="submit" name="capnhat" class="btn-update">⚡ Cập nhật Vé Hết Hạn</button>
            </form>
        </div>

        <!-- Table Section -->
        <div class="ticket-table-wrapper">
            <?php if (isset($loadvephim) && is_array($loadvephim) && !empty($loadvephim)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Vé</th>
                            <th>Phim</th>
                            <th>Giá Vé</th>
                            <th>Ngày Đặt</th>
                            <th>Khách Hàng</th>
                            <th>Ngày Chiếu</th>
                            <th>Khung Giờ</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($loadvephim as $ve): ?>
                            <?php extract($ve) ?>
                            <tr>
                                <td class="ticket-id">#<?= htmlspecialchars($ve['id']) ?></td>
                                <td class="ticket-name" title="<?= htmlspecialchars($ve['tieu_de']) ?>">
                                    <?= htmlspecialchars($ve['tieu_de']) ?>
                                </td>
                                <td class="ticket-price">
                                    <?= number_format($ve['price'] ?? 0) ?> đ
                                </td>
                                <td><?= htmlspecialchars($ve['ngay_dat'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ve['name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ve['ngay_chieu'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ve['thoi_gian_chieu'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $status_text = '';
                                    $status_class = '';
                                    switch ($ve['trang_thai'] ?? '') {
                                        case '0':
                                            $status_text = '⏳ Chờ Thanh Toán';
                                            $status_class = 'badge-pending';
                                            break;
                                        case '1':
                                            $status_text = '✓ Đã Thanh Toán';
                                            $status_class = 'badge-success';
                                            break;
                                        case '2':
                                            $status_text = '✓ Đã Sử Dụng';
                                            $status_class = 'badge-used';
                                            break;
                                        case '3':
                                            $status_text = '✗ Đã Hủy';
                                            $status_class = 'badge-cancelled';
                                            break;
                                        case '4':
                                            $status_text = '⏰ Hết Hạn';
                                            $status_class = 'badge-expired';
                                            break;
                                        default:
                                            $status_text = '? Không Xác Định';
                                            $status_class = 'badge-pending';
                                    }
                                    ?>
                                    <span class="badge <?= $status_class ?>">
                                        <?= $status_text ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?act=suavephim&idsua=<?= $ve['id'] ?>" 
                                           class="btn-action btn-edit" 
                                           title="Sửa vé">
                                            ✎
                                        </a>
                                        <?php if ($trang_thai != 0): ?>
                                            <a href="index.php?act=ctve&id=<?= $ve['id'] ?>" 
                                               class="btn-action btn-view" 
                                               title="Xem chi tiết">
                                                👁
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🎫</div>
                    <p class="empty-state-text">Không tìm thấy vé nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><!-- Content Body End -->
