<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        .salary-card {
            background: linear-gradient(135deg, #8793c9ff 0%, #8c799eff 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .salary-stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .stat-box {
            flex: 1;
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }
        .employee-detail {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .employee-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 20px;
        }
        .employee-name {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }
        .employee-total {
            font-size: 24px;
            font-weight: 700;
            color: #b5cac3ff;
        }
        .breakdown-table {
            width: 100%;
            font-size: 13px;
        }
        .breakdown-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .breakdown-table .date-col {
            color: #6b7280;
            font-weight: 500;
        }
        .breakdown-table .hours-col {
            text-align: center;
            color: #3b82f6;
            font-weight: 600;
        }
        .breakdown-table .money-col {
            text-align: right;
            color: #059669;
            font-weight: 600;
        }
        .summary-row {
            background: #f9fafb;
            font-weight: 700;
        }
        .summary-row td {
            padding: 12px 10px !important;
            border-top: 2px solid #e5e7eb;
        }
        .allowance-section, .deduction-section {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
        }
        .allowance-section {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
        }
        .deduction-section {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
        }
        .section-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .final-total {
            background: linear-gradient(135deg, #93d1bcff 0%, #82a097ff 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            margin-top: 15px;
        }
        .btn-export {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-export:hover {
            background: #667eea;
            color: white;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-nhap { background: #e5e7eb; color: #374151; }
        .status-cho_duyet { background: #fef3c7; color: #92400e; }
        .status-da_duyet { background: #d1fae5; color: #065f46; }
        .status-da_thanh_toan { background: #dbeafe; color: #1e40af; }
        .action-buttons {
            margin: 20px 0;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn-action {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-save { background: #10b981; color: white; }
        .btn-save:hover { background: #059669; }
        .btn-approve { background: #3b82f6; color: white; }
        .btn-approve:hover { background: #2563eb; }
        .btn-paid { background: #8b5cf6; color: white; }
        .btn-paid:hover { background: #7c3aed; }
    </style>

    <div class="page-heading"><h3>Bảng lương chi tiết</h3></div>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" style="max-width:900px;margin:0 auto 20px">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="max-width:900px;margin:0 auto 20px">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="bangluong" />
        <div class="row align-items-end">
            <div class="col-12 col-md-3 mb-10">
                <label>Tháng</label>
                <input class="form-control" type="month" name="ym" value="<?= htmlspecialchars($ym) ?>" />
            </div>
            <div class="col-12 col-md-3 mb-10">
                <label>Đơn giá / giờ (VND)</label>
                <input class="form-control" type="number" name="rate" value="<?= (int)$rate ?>" />
            </div>
            <div class="col-12 col-md-2 mb-10">
                <button class="button" type="submit">Tính lương</button>
            </div>
            <div class="col-12 col-md-2 mb-10">
                <button class="btn-export" type="button" onclick="window.print()">In/Xuất PDF</button>
            </div>
        </div>
    </form>

    <?php if (!empty($ds_luong)): ?>
        <?php
        $tong_tat_ca = 0;
        $tong_gio_tat_ca = 0;
        foreach ($ds_luong as $r) {
            $tong_tat_ca += $r['tong_thuc_lanh'];
            $tong_gio_tat_ca += $r['so_gio'];
        }
        ?>
        
        <div class="salary-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                <h4 style="margin:0">Tổng quan tháng <?= date('m/Y', strtotime($ym . '-01')) ?></h4>
                <?php if ($is_saved): ?>
                    <?php
                    $status_labels = [
                        'nhap' => 'Nháp',
                        'cho_duyet' => 'Chờ duyệt',
                        'da_duyet' => 'Đã duyệt',
                        'da_thanh_toan' => 'Đã thanh toán'
                    ];
                    $current_status = $saved_status ?? 'nhap';
                    ?>
                    <span class="status-badge status-<?= $current_status ?>">
                        <?= $status_labels[$current_status] ?>
                    </span>
                <?php else: ?>
                    <span class="status-badge status-nhap">Chưa lưu</span>
                <?php endif; ?>
            </div>
            <div class="salary-stats">
                <div class="stat-box">
                    <div class="stat-label">Số nhân viên</div>
                    <div class="stat-value"><?= count($ds_luong) ?></div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Tổng giờ công</div>
                    <div class="stat-value"><?= number_format($tong_gio_tat_ca, 1) ?>h</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Tổng quỹ lương</div>
                    <div class="stat-value"><?= number_format($tong_tat_ca) ?> ₫</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">TB/người</div>
                    <div class="stat-value"><?= number_format($tong_tat_ca / count($ds_luong)) ?> ₫</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons no-print">
            <div style="flex:1">
                <strong>Hành động:</strong>
            </div>
            
            <?php if (!$is_saved): ?>
                <!-- Chưa lưu: Hiện nút Lưu -->
                <form method="post" action="index.php?act=bangluong&ym=<?= $ym ?>&rate=<?= $rate ?>" style="display:inline">
                    <button type="submit" name="action" value="save" class="btn-action btn-save"
                            onclick="return confirm('Lưu bảng lương tháng <?= date('m/Y', strtotime($ym.'-01')) ?>?')">
                        Lưu bảng lương
                    </button>
                </form>
            <?php else: ?>
                <!-- Đã lưu: Hiện nút theo trạng thái -->
                <?php if ($current_status === 'nhap'): ?>
                    <form method="post" action="index.php?act=bangluong&ym=<?= $ym ?>&rate=<?= $rate ?>" style="display:inline">
                        <button type="submit" name="action" value="send_approval" class="btn-action btn-approve"
                                onclick="return confirm('Gửi bảng lương lên cấp trên duyệt?')">
                            Gửi duyệt
                        </button>
                    </form>
                    <form method="post" action="index.php?act=bangluong&ym=<?= $ym ?>&rate=<?= $rate ?>" style="display:inline">
                        <button type="submit" name="action" value="save" class="btn-action btn-save">
                            Tính lại & Lưu
                        </button>
                    </form>
                <?php elseif ($current_status === 'cho_duyet'): ?>
                    <span style="color:#92400e">Đang chờ cấp trên duyệt...</span>
                <?php elseif ($current_status === 'da_duyet'): ?>
                    <form method="post" action="index.php?act=bangluong&ym=<?= $ym ?>&rate=<?= $rate ?>" style="display:inline">
                        <button type="submit" name="action" value="mark_paid" class="btn-action btn-paid"
                                onclick="return confirm('Xác nhận đã thanh toán lương cho tất cả nhân viên?')">
                            Đánh dấu đã thanh toán
                        </button>
                    </form>
                <?php elseif ($current_status === 'da_thanh_toan'): ?>
                    <span style="color:#1e40af">Đã hoàn tất thanh toán</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php foreach ($ds_luong as $r): ?>
            <div class="employee-detail">
                <div class="employee-header">
                    <div>
                        <div class="employee-name">👤 <?= htmlspecialchars($r['ten_nv']) ?></div>
                        <div style="font-size:13px;color:#6b7280;margin-top:5px">
                            ID: #<?= $r['id_nv'] ?> | Đơn giá: <?= number_format($rate) ?> ₫/giờ
                        </div>
                    </div>
                    <div class="employee-total">
                        <?= number_format($r['tong_thuc_lanh']) ?> ₫
                    </div>
                </div>

                <!-- Chi tiết từng ngày làm việc -->
                <table class="breakdown-table">
                    <thead style="background:#f9fafb">
                        <tr>
                            <th style="padding:10px">Ngày</th>
                            <th style="padding:10px;text-align:center">Giờ vào</th>
                            <th style="padding:10px;text-align:center">Giờ ra</th>
                            <th style="padding:10px;text-align:center">Số giờ</th>
                            <th style="padding:10px;text-align:right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($r['chi_tiet_ngay'] as $day): ?>
                            <tr>
                                <td class="date-col"><?= date('d/m/Y (l)', strtotime($day['ngay'])) ?></td>
                                <td style="text-align:center"><?= date('H:i', strtotime($day['gio_vao'])) ?></td>
                                <td style="text-align:center"><?= date('H:i', strtotime($day['gio_ra'])) ?></td>
                                <td class="hours-col"><?= number_format($day['so_gio'], 2) ?>h</td>
                                <td class="money-col"><?= number_format($day['tien']) ?> ₫</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="summary-row">
                            <td colspan="3" style="text-align:right">💼 Tổng lương theo giờ:</td>
                            <td class="hours-col"><?= number_format($r['so_gio'], 2) ?>h</td>
                            <td class="money-col"><?= number_format($r['luong_co_ban']) ?> ₫</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Phụ cấp -->
                <?php if ($r['phu_cap'] > 0): ?>
                    <div class="allowance-section">
                        <div class="section-title">Phụ cấp</div>
                        <table style="width:100%;font-size:13px">
                            <tr>
                                <td>Phụ cấp cố định (ăn trưa, xăng xe...)</td>
                                <td style="text-align:right;font-weight:600;color:#10b981">
                                    + <?= number_format($r['phu_cap']) ?> ₫
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Khấu trừ -->
                <?php if ($r['khau_tru'] > 0): ?>
                    <div class="deduction-section">
                        <div class="section-title">Khấu trừ</div>
                        <table style="width:100%;font-size:13px">
                            <tr>
                                <td>Đi muộn (<?= $r['late_count'] ?> lần × 50,000 ₫)</td>
                                <td style="text-align:right;font-weight:600;color:#ef4444">
                                    - <?= number_format($r['khau_tru']) ?> ₫
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Tổng thực lãnh -->
                <div class="final-total">
                    <div style="font-size:14px;opacity:0.9;margin-bottom:8px">Tổng thực lãnh</div>
                    <div style="font-size:32px;font-weight:700">
                        <?= number_format($r['tong_thuc_lanh']) ?> ₫
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
    <?php else: ?>
        <div style="text-align:center;padding:60px;color:#6b7280">
            <div style="font-size:48px;margin-bottom:15px">📭</div>
            <div style="font-size:18px">Chưa có dữ liệu chấm công trong tháng này</div>
        </div>
    <?php endif; ?>
    
    <!-- Payment Receipt Modal -->
    <style>
        .receipt-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .receipt-modal.active {
            display: flex;
        }
        .receipt-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .receipt-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .receipt-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }
        .receipt-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }
        .receipt-row.total {
            background: #f9fafb;
            padding: 16px 0;
            font-weight: 700;
            font-size: 16px;
            color: #10b981;
            border: 2px solid #d1fae5;
        }
        .receipt-row label {
            color: #6b7280;
        }
        .receipt-row value {
            font-weight: 600;
            color: #1f2937;
        }
        .receipt-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        .btn-receipt {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-receipt-print {
            background: #3b82f6;
            color: white;
        }
        .btn-receipt-print:hover {
            background: #2563eb;
        }
        .btn-receipt-close {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-receipt-close:hover {
            background: #d1d5db;
        }
    </style>
    
    <!-- Payment Confirmation -->
    <?php if (!empty($payment_details) && !empty($success)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                modal.classList.add('active');
            }
        });
        
        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                modal.classList.remove('active');
            }
        }
        
        function printPaymentReceipt() {
            window.print();
        }
    </script>
    
    <div id="paymentModal" class="receipt-modal">
        <div class="receipt-content">
            <div class="receipt-header">
                <div class="receipt-title">✅ Thanh toán thành công</div>
                <div class="receipt-subtitle">Lịch sử: <?= date('d/m/Y H:i', strtotime($ym . '-01')) ?></div>
            </div>
            
            <div style="margin: 20px 0;">
                <div class="receipt-row">
                    <label>Số nhân viên được thanh toán:</label>
                    <value><?= count($payment_details) ?></value>
                </div>
                <div class="receipt-row">
                    <label>Tháng:</label>
                    <value><?= date('m/Y', strtotime($ym . '-01')) ?></value>
                </div>
                <div class="receipt-row">
                    <label>Ngày thanh toán:</label>
                    <value><?= date('d/m/Y H:i:s') ?></value>
                </div>
                <div class="receipt-row">
                    <label>Phương thức:</label>
                    <value>Mock Transfer (Giả lập)</value>
                </div>
            </div>
            
            <div style="margin: 20px 0;">
                <h4 style="margin: 0 0 10px 0; color: #374151;">Chi tiết nhân viên:</h4>
                <?php foreach ($payment_details as $detail): ?>
                    <?php 
                    $nv = pdo_query_one("SELECT ten_nv FROM nhan_vien WHERE id = ?", $detail['id_nv']);
                    ?>
                    <div class="receipt-row">
                        <div>
                            <label><?= htmlspecialchars($nv['ten_nv'] ?? 'NV #' . $detail['id_nv']) ?></label>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">
                                Receipt: <?= $detail['receipt_id'] ?>
                            </div>
                        </div>
                        <value><?= number_format($detail['amount']) ?> ₫</value>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="padding-top: 15px; border-top: 2px solid #e5e7eb;">
                <div class="receipt-row total">
                    <label>Tổng thanh toán:</label>
                    <value><?= number_format(array_sum(array_column($payment_details, 'amount'))) ?> ₫</value>
                </div>
            </div>
            
            <div class="receipt-buttons">
                <button class="btn-receipt btn-receipt-print" onclick="printPaymentReceipt()">🖨️ In receipt</button>
                <button class="btn-receipt btn-receipt-close" onclick="closePaymentModal()">✕ Đóng</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style media="print">
    .side-header, .page-heading, form, .btn-export, .action-buttons, .no-print { display: none !important; }
    .employee-detail { page-break-inside: avoid; }
    .receipt-buttons { display: none !important; }
</style>

<?php include __DIR__ . '/../home/footer.php'; ?>
