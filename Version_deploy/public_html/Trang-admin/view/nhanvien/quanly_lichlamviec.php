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

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .page-header h3 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 13px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-control {
        padding: 10px 12px;
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

    .btn-search {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        height: fit-content;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #dcfce7;
        border-left: 4px solid var(--success);
        color: #15803d;
    }

    .alert-danger {
        background-color: #fee2e2;
        border-left: 4px solid var(--danger);
        color: #991b1b;
    }

    .table-wrapper {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .table thead th {
        padding: 14px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: none;
    }

    .table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: var(--light);
    }

    .table tbody td {
        padding: 12px 14px;
        font-size: 13px;
        vertical-align: middle;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-blue {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    .badge-amber {
        background-color: #fef3c7;
        color: #b45309;
    }

    .badge-purple {
        background-color: #e9d5ff;
        color: #6b21a8;
    }

    .badge-green {
        background-color: #dcfce7;
        color: #15803d;
    }

    .badge-gray {
        background-color: #f3f4f6;
        color: #374151;
    }

    .shift-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .shift-time {
        font-weight: 600;
        color: #333;
    }

    .shift-ca {
        font-size: 12px;
        color: #666;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-edit {
        padding: 6px 12px;
        background: var(--info);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-edit:hover {
        background: #0b7dda;
        transform: translateY(-1px);
    }

    .btn-delete {
        padding: 6px 12px;
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #e63946;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-state p {
        font-size: 16px;
        margin: 0;
    }

    @media (max-width: 768px) {
        .filter-form {
            grid-template-columns: 1fr;
        }

        .page-header h3 {
            font-size: 20px;
        }

        .table {
            font-size: 12px;
        }

        .table thead th,
        .table tbody td {
            padding: 10px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-edit,
        .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php include __DIR__ . '/../home/sideheader.php'; ?>

<!-- Content Body Start -->
<div class="content-body">
    
    <!-- Header -->
    <div class="page-header">
        <h3>📅 Quản Lý Lịch Làm Việc Nhân Viên</h3>
        <p>Xem, chỉnh sửa và xóa lịch làm việc của nhân viên</p>
    </div>

    <!-- Alerts -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="get" action="index.php" class="filter-form">
            <input type="hidden" name="act" value="ql_lichlamviec_calendar">
            
            <div class="form-group">
                <label for="nhanvien">Chọn Nhân Viên</label>
                <select class="form-control" id="nhanvien" name="nv_id">
                    <option value="">-- Tất cả nhân viên --</option>
                    <?php if (!empty($dsnhanvien)): ?>
                        <?php foreach ($dsnhanvien as $nv): ?>
                            <option value="<?= $nv['id'] ?>" <?= (!empty($_GET['nv_id']) && $_GET['nv_id'] == $nv['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nv['name']) ?> - <?= htmlspecialchars($nv['ma_nv']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="thang">Chọn Tháng</label>
                <input type="month" class="form-control" id="thang" name="month" value="<?= !empty($_GET['month']) ? htmlspecialchars($_GET['month']) : date('Y-m') ?>">
            </div>

            <div class="form-group">
                <label for="ca">Loại Ca</label>
                <select class="form-control" id="ca" name="ca">
                    <option value="">-- Tất cả ca --</option>
                    <option value="sáng" <?= (!empty($_GET['ca']) && $_GET['ca'] == 'sáng') ? 'selected' : '' ?>>Sáng</option>
                    <option value="chiều" <?= (!empty($_GET['ca']) && $_GET['ca'] == 'chiều') ? 'selected' : '' ?>>Chiều</option>
                    <option value="tối" <?= (!empty($_GET['ca']) && $_GET['ca'] == 'tối') ? 'selected' : '' ?>>Tối/Đêm</option>
                </select>
            </div>

            <button type="submit" class="btn-search">🔍 Tìm Kiếm</button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nhân Viên</th>
                        <th>Ngày</th>
                        <th>Giờ Làm</th>
                        <th>Ca Làm</th>
                        <th>Rạp / Bộ Phận</th>
                        <th>Ghi Chú</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lichlamviec) && is_array($lichlamviec)): ?>
                        <?php foreach ($lichlamviec as $lich): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($lich['name'] ?? 'N/A') ?></strong>
                                    <br>
                                    <small style="color: #666;">ID: <?= htmlspecialchars($lich['nhanvien_id'] ?? 'N/A') ?></small>
                                </td>
                                <td><?= !empty($lich['ngay']) ? date('d/m/Y', strtotime($lich['ngay'])) : 'N/A' ?></td>
                                <td>
                                    <span class="shift-time"><?= htmlspecialchars($lich['gio_bat_dau'] ?? '00:00') ?> → <?= htmlspecialchars($lich['gio_ket_thuc'] ?? '00:00') ?></span>
                                </td>
                                <td>
                                    <?php
                                    $ca = $lich['ca_lam'] ?? '';
                                    $caLower = mb_strtolower($ca, 'UTF-8');
                                    $badgeClass = 'badge-gray';
                                    if (strpos($caLower, 'sáng') !== false || strpos($caLower, 'sang') !== false) $badgeClass = 'badge-blue';
                                    elseif (strpos($caLower, 'chiều') !== false || strpos($caLower, 'chieu') !== false) $badgeClass = 'badge-amber';
                                    elseif (strpos($caLower, 'tối') !== false || strpos($caLower, 'toi') !== false || strpos($caLower, 'đêm') !== false) $badgeClass = 'badge-purple';
                                    elseif (strpos($caLower, 'hành') !== false || strpos($caLower, 'hanh') !== false) $badgeClass = 'badge-green';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($ca) ?: 'Không xác định' ?></span>
                                </td>
                                <td><?= htmlspecialchars($lich['ten_rap'] ?? $lich['bo_phan'] ?? 'N/A') ?></td>
                                <td>
                                    <small style="color: #666;">
                                        <?php if (!empty($lich['ghi_chu'])): ?>
                                            📝 <?= htmlspecialchars(substr($lich['ghi_chu'], 0, 30)) ?>...
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?act=sua_lichlamviec&id=<?= $lich['id'] ?>" class="btn-edit">
                                            ✏️ Sửa
                                        </a>
                                        <a href="index.php?act=xoa_lichlamviec&id=<?= $lich['id'] ?>" class="btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                            🗑️ Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <p>📭 Không có dữ liệu lịch làm việc</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div><!-- Content Body End -->
