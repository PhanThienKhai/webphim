<style>
    /* ===== HEADER SECTION ===== */
    .leave-header {
        background: linear-gradient(135deg, #8389a2ff 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .leave-header h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .leave-header p {
        margin: 8px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* ===== FORM SECTION ===== */
    .leave-form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #667eea;
    }

    .leave-form-card h5 {
        margin: 0 0 25px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f9fafb;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #77787cff 0%, #764ba2 100%);
        color: white;
        padding: 14px 32px;
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

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* ===== ALERTS ===== */
    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
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

    .alert-info {
        background: #dbeafe;
        color: #0c4a6e;
        border-left: 4px solid #0284c7;
    }

    /* ===== HISTORY TABLE ===== */
    .history-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .history-card h5 {
        margin: 0;
        padding: 20px;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
    }

    .table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border: none;
        border-bottom: 2px solid #e5e7eb;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .table tbody td {
        padding: 16px;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .date-range {
        font-weight: 500;
        color: #667eea;
    }

    .reason-text {
        color: #6b7280;
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border-left: 3px solid #f59e0b;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
        border-left: 3px solid #10b981;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #ef4444;
    }

    .cinema-name {
        font-weight: 500;
        color: #1f2937;
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
        .leave-header {
            padding: 20px;
        }

        .leave-header h3 {
            font-size: 22px;
        }

        .leave-form-card {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .table {
            font-size: 12px;
        }

        .table tbody td {
            padding: 12px;
        }

        .date-range {
            display: block;
            margin-top: 4px;
        }
    }
</style>

<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div style=" margin: 0 auto; padding: 5px;">
        
        <!-- Header -->
        <div class="leave-header">
            <h3>📋 Xin Nghỉ Phép</h3>
            <p>Gửi yêu cầu nghỉ phép đến quản lý rạp</p>
        </div>

        <!-- Alerts -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <span>❌</span>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span>✓</span>
                <div><?= htmlspecialchars($success) ?></div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="leave-form-card">
            <h5>Tạo Đơn Xin Nghỉ Phép</h5>
            
            <form method="post" action="index.php?act=xinnghi">
                <div class="form-row">
                    <div class="form-group">
                        <label>📅 Từ ngày</label>
                        <input type="date" name="tu_ngay" required />
                    </div>
                    <div class="form-group">
                        <label>📅 Đến ngày</label>
                        <input type="date" name="den_ngay" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>💬 Lý do nghỉ phép</label>
                    <textarea name="ly_do" rows="3" placeholder="Ví dụ: Bệnh, Công việc riêng, Sự kiện gia đình..." required></textarea>
                </div>

                <button class="btn-submit" type="submit" name="gui" value="1">
                    📤 Gửi Yêu Cầu
                </button>
            </form>
        </div>

        <!-- History Card -->
        <div class="history-card">
            <h5>📊 Lịch Sử Xin Nghỉ</h5>
            
            <?php if (!empty($dnp_cua_toi) && is_array($dnp_cua_toi)): ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>⏱️ Thời Gian</th>
                                <th>💭 Lý Do</th>
                                <th>📌 Trạng Thái</th>
                                <th>🏢 Rạp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dnp_cua_toi as $d): ?>
                                <tr>
                                    <td>
                                        <div class="date-range">
                                            📅 <?= htmlspecialchars($d['tu_ngay']) ?> → <?= htmlspecialchars($d['den_ngay']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="reason-text">
                                            <?= htmlspecialchars($d['ly_do']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $d['trang_thai'] ?? '';
                                        $status_class = 'status-pending';
                                        $status_text = '⏳ Chờ Duyệt';
                                        
                                        if ($status === 'Đã duyệt') {
                                            $status_class = 'status-approved';
                                            $status_text = '✓ Đã Duyệt';
                                        } elseif ($status === 'Từ chối') {
                                            $status_class = 'status-rejected';
                                            $status_text = '✗ Từ Chối';
                                        }
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <?= $status_text ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="cinema-name">
                                            <?= htmlspecialchars($d['ten_rap']) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p class="empty-state-text">Bạn chưa có yêu cầu nghỉ phép nào</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

