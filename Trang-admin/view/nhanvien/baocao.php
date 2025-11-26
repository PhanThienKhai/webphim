<?php include __DIR__ . '/../home/sideheader.php'; ?>

<style>
    /* ===== HEADER SECTION ===== */
    .report-header {
        background: linear-gradient(135deg, #8389a2ff 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .report-header h3 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .report-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* ===== FILTER SECTION ===== */
    .filter-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #667eea;
    }

    .filter-card h5 {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input {
        padding: 11px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-filter {
        background: linear-gradient(135deg, #77787cff 0%, #764ba2 100%);
        color: white;
        padding: 11px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-filter:active {
        transform: translateY(0);
    }

    .btn-reset {
        background: #e5e7eb;
        color: #374151;
        padding: 11px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-reset:hover {
        background: #d1d5db;
    }

    /* ===== STATS CARDS ===== */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 5px solid;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-card.tickets {
        border-left-color: #667eea;
    }

    .stat-card.revenue {
        border-left-color: #10b981;
    }

    .stat-card.hours {
        border-left-color: #f59e0b;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .stat-unit {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 6px;
    }

    /* ===== TABLE SECTION ===== */
    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .table-card h5 {
        margin: 0;
        padding: 20px;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
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
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
        padding: 16px;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .date-cell {
        font-weight: 500;
        color: #667eea;
    }

    .ticket-count {
        font-weight: 600;
        color: #1f2937;
        background: #f0f4ff;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .revenue-cell {
        font-weight: 600;
        color: #10b981;
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
        .report-header {
            padding: 20px;
        }

        .report-header h3 {
            font-size: 22px;
        }

        .filter-card {
            padding: 20px;
        }

        .filter-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .filter-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn-filter,
        .btn-reset {
            width: 100%;
        }

        .stats-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-value {
            font-size: 24px;
        }

        .table {
            font-size: 12px;
        }

        .table tbody td {
            padding: 12px;
        }

        .table thead th {
            padding: 12px;
        }
    }
</style>

<div class="content-body">
    <div style="margin: 0 auto; padding: 20px;">

        <!-- Header -->
        <div class="report-header">
            <h3>📊 Báo Cáo Cá Nhân</h3>
            <p>Theo dõi hiệu suất bán vé và giờ công của bạn</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <h5>🔍 Bộ Lọc Báo Cáo</h5>
            
            <form method="get" action="index.php">
                <input type="hidden" name="act" value="nv_baocao" />
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label>📅 Từ ngày</label>
                        <input type="date" name="from" value="<?= htmlspecialchars($_GET['from'] ?? date('Y-m-d', strtotime('-30 days'))) ?>" />
                    </div>
                    
                    <div class="filter-group">
                        <label>📅 Đến ngày</label>
                        <input type="date" name="to" value="<?= htmlspecialchars($_GET['to'] ?? date('Y-m-d')) ?>" />
                    </div>
                    
                    <div class="filter-group">
                        <label>📆 Tháng Tính Công</label>
                        <input type="month" name="ym" value="<?= htmlspecialchars($_GET['ym'] ?? date('Y-m')) ?>" />
                    </div>

                    <div class="filter-buttons">
                        <button class="btn-filter" type="submit">
                            🔍 Lọc Dữ Liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card tickets">
                <div class="stat-label">
                    <span>🎫</span>
                    Tổng Vé Bán
                </div>
                <div class="stat-value"><?= (int)($sum['so_ve'] ?? 0) ?></div>
                <div class="stat-unit">vé trong kỳ</div>
            </div>

            <div class="stat-card revenue">
                <div class="stat-label">
                    <span>💰</span>
                    Doanh Thu
                </div>
                <div class="stat-value" style="font-size: 20px;">
                    <?= number_format((int)($sum['doanh_thu'] ?? 0)) ?> <span style="font-size: 14px;">đ</span>
                </div>
                <div class="stat-unit">tổng cộng</div>
            </div>

            <div class="stat-card hours">
                <div class="stat-label">
                    <span>⏱️</span>
                    Giờ Công
                </div>
                <div class="stat-value"><?= number_format((float)($sum_hours ?? 0), 1) ?></div>
                <div class="stat-unit">tháng <?= htmlspecialchars($_GET['ym'] ?? date('Y-m')) ?></div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-card">
            <h5>📋 Chi Tiết Theo Ngày</h5>
            
            <?php if (!empty($by_date) && is_array($by_date)): ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>📅 Ngày</th>
                                <th>🎫 Số Vé</th>
                                <th>💵 Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($by_date as $r): ?>
                                <tr>
                                    <td>
                                        <div class="date-cell">
                                            <?= htmlspecialchars($r['ngay']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ticket-count">
                                            <?= (int)$r['so_ve'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="revenue-cell">
                                            <?= number_format((int)$r['doanh_thu']) ?> đ
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
                    <p class="empty-state-text">Không có dữ liệu báo cáo trong kỳ này</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

