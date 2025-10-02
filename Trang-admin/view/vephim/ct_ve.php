<?php
include "./view/home/sideheader.php";
?>

<style>
    .ticket-page {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
    }
    
    .ticket-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .ticket-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .modern-ticket {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 3px;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        margin-bottom: 24px;
    }
    
    .ticket-content {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
    }
    
    .ticket-cinema-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 24px 32px;
        text-align: center;
    }
    
    .ticket-cinema-banner h3 {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
        letter-spacing: 2px;
    }
    
    .ticket-cinema-banner p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }
    
    .ticket-body {
        padding: 32px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    
    .ticket-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .ticket-section h4 {
        font-size: 18px;
        font-weight: 700;
        color: #374151;
        margin: 0 0 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .ticket-info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .ticket-info-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .ticket-info-value {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .ticket-info-value.highlight {
        font-size: 20px;
        color: #667eea;
    }
    
    .ticket-movie-title {
        font-size: 22px !important;
        font-weight: 700 !important;
        color: #667eea !important;
        line-height: 1.3;
    }
    
    .ticket-footer {
        background: #f9fafb;
        padding: 24px 32px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        border-top: 2px dashed #d1d5db;
        position: relative;
    }
    
    /* Tạo hiệu ứng punch hole ở 2 bên */
    .ticket-footer::after,
    .ticket-footer::before {
        content: '';
        position: absolute;
        top: -12px;
        width: 24px;
        height: 24px;
        background: #f9fafb;
        border-radius: 50%;
        border: 2px dashed #d1d5db;
    }
    
    .ticket-footer::before {
        left: -12px;
    }
    
    .ticket-footer::after {
        right: -12px;
    }
    
    .ticket-footer-item {
        text-align: center;
    }
    
    .ticket-qr-code {
        background: #fff;
        padding: 16px;
        border-radius: 12px;
        text-align: center;
        border: 2px solid #e5e7eb;
        transition: all 0.3s;
    }
    
    .ticket-qr-code:hover {
        transform: scale(1.05);
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }
    
    .ticket-qr-code .qr-label {
        font-size: 11px;
        color: #6b7280;
        margin-top: 12px;
        font-weight: 600;
    }
    
    .ticket-status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        background: #10b981;
        color: #fff;
    }
    
    .ticket-status-badge.pending {
        background: #f59e0b;
    }
    
    .ticket-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    
    .btn-ticket-action {
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    
    .btn-print::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-print:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
    }
    
    .btn-print:active {
        transform: translateY(0);
    }
    
    .btn-back {
        background: #fff;
        color: #667eea;
        border: 2px solid #667eea;
    }
    
    .btn-back:hover {
        background: #667eea;
        color: #fff;
    }
    
    @media print {
        body { 
            margin: 0; 
            padding: 0;
            background: #fff;
        }
        .content-body {
            padding: 0;
            margin: 0;
        }
        .ticket-page {
            max-width: 100%;
            margin: 0;
            padding: 20px;
        }
        .ticket-header {
            display: none;
        }
        .ticket-actions { 
            display: none; 
        }
        .modern-ticket { 
            box-shadow: none;
            page-break-inside: avoid;
            margin: 0 auto;
            max-width: 210mm; /* A4 width */
        }
        
        /* Thêm header cho in vé */
        .print-header {
            display: block !important;
            text-align: center;
            padding: 20px 0 30px;
            border-bottom: 3px solid #667eea;
            margin-bottom: 30px;
        }
        
        .print-logo {
            font-size: 36px;
            font-weight: 900;
            color: #667eea;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }
        
        .print-subtitle {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        /* Tạo đường perforated */
        .ticket-footer::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-image: 
                repeating-linear-gradient(
                    90deg,
                    #d1d5db 0,
                    #d1d5db 10px,
                    transparent 10px,
                    transparent 20px
                );
        }
        
        /* Tối ưu màu sắc cho in */
        .ticket-cinema-banner {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .ticket-status-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Đảm bảo QR code in rõ */
        .ticket-qr-code {
            border: 2px solid #000;
        }
    }
    
    /* Header chỉ hiển thị khi in */
    .print-header {
        display: none;
    }
</style>

<div class="content-body">
    <div class="ticket-page">
        <div class="ticket-header">
            <h2>🎫 Chi Tiết Vé</h2>
        </div>
        
        <?php
        if (!empty($loadone_ve)) {
            extract($loadone_ve);
            $status_class = ($trang_thai == 1) ? '' : 'pending';
            $status_text = ($trang_thai == 1) ? 'Đã check-in' : 'Chưa sử dụng';
        ?>
        
        <div class="modern-ticket" id="ticket-print">
            <div class="ticket-content">
                <!-- Header -->
                <div class="ticket-cinema-banner">
                    <h3>CINEPASS</h3>
                    <p><?= htmlspecialchars($tenrap ?? 'Cinepass') ?></p>
                </div>
                
                <!-- Body -->
                <div class="ticket-body">
                    <!-- Left Column -->
                    <div class="ticket-section">
                        <h4>📽️ Thông tin phim</h4>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">Phim</span>
                            <span class="ticket-info-value ticket-movie-title"><?= htmlspecialchars($tieu_de ?? '') ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">📅 Ngày chiếu</span>
                            <span class="ticket-info-value"><?= date('d/m/Y', strtotime($ngay_chieu ?? '')) ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">🕐 Giờ chiếu</span>
                            <span class="ticket-info-value"><?= date('H:i', strtotime($thoi_gian_chieu ?? '')) ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">🚪 Phòng</span>
                            <span class="ticket-info-value"><?= htmlspecialchars($tenphong ?? '') ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">💺 Ghế</span>
                            <span class="ticket-info-value highlight"><?= htmlspecialchars($ghe ?? '') ?></span>
                        </div>
                        
                        <?php if (!empty($combo)): ?>
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">🍿 Combo</span>
                            <span class="ticket-info-value"><?= htmlspecialchars($combo) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="ticket-section">
                        <h4>ℹ️ Thông tin đặt vé</h4>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">🎟️ Mã vé</span>
                            <span class="ticket-info-value">#<?= htmlspecialchars($id ?? '') ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">👤 Khách hàng</span>
                            <span class="ticket-info-value"><?= htmlspecialchars($name ?? '') ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">📆 Ngày đặt</span>
                            <span class="ticket-info-value"><?= date('d/m/Y H:i', strtotime($ngay_dat ?? '')) ?></span>
                        </div>
                        
                        <?php if ($trang_thai == 1 && !empty($check_in_luc)): ?>
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">✅ Check-in lúc</span>
                            <span class="ticket-info-value"><?= date('d/m/Y H:i', strtotime($check_in_luc)) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">📊 Trạng thái</span>
                            <span class="ticket-status-badge <?= $status_class ?>"><?= $status_text ?></span>
                        </div>
                        
                        <div class="ticket-info-item">
                            <span class="ticket-info-label">💰 Tổng tiền</span>
                            <span class="ticket-info-value highlight"><?= number_format($price ?? 0) ?> đ</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="ticket-footer">
                    <div class="ticket-footer-item">
                        <div class="ticket-qr-code">
                            <div style="width: 100px; height: 100px; background: linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 75%, #f3f4f6 75%, #f3f4f6), linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 75%, #f3f4f6 75%, #f3f4f6); background-size: 10px 10px; background-position: 0 0, 5px 5px; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 2px solid #e5e7eb; position: relative;">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="3" width="8" height="8" rx="1" fill="#667eea"/>
                                    <rect x="3" y="13" width="8" height="8" rx="1" fill="#667eea"/>
                                    <rect x="13" y="3" width="8" height="8" rx="1" fill="#667eea"/>
                                    <rect x="5" y="5" width="4" height="4" fill="white"/>
                                    <rect x="5" y="15" width="4" height="4" fill="white"/>
                                    <rect x="15" y="5" width="4" height="4" fill="white"/>
                                    <rect x="13" y="13" width="3" height="3" fill="#667eea"/>
                                    <rect x="17" y="13" width="4" height="3" fill="#667eea"/>
                                    <rect x="13" y="17" width="8" height="4" fill="#667eea"/>
                                </svg>
                            </div>
                            <div class="qr-label">📱 Quét để check-in</div>
                        </div>
                    </div>
                    <div class="ticket-footer-item" style="display: flex; flex-direction: column; justify-content: center;">
                        <div class="ticket-info-label">🔖 Mã vé</div>
                        <div class="ticket-info-value" style="font-family: 'Courier New', monospace; font-weight: 700;"><?= htmlspecialchars($ma_ve ?? '#'.$id) ?></div>
                    </div>
                    <div class="ticket-footer-item" style="display: flex; flex-direction: column; justify-content: center;">
                        <div class="ticket-info-label">📋 ID Hóa đơn</div>
                        <div class="ticket-info-value"><?= htmlspecialchars($id_hd ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php } else { ?>
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 18px; color: #6b7280;">Không tìm thấy thông tin vé</p>
            </div>
        <?php } ?>
        
        <div class="ticket-actions">
            <a href="index.php?act=ve" class="btn-ticket-action btn-back">← Quay lại</a>
            <button onclick="printTicket()" class="btn-ticket-action btn-print">🖨️ In vé</button>
        </div>
    </div>
</div>

<script>
function printTicket() {
    const ticketContent = document.getElementById('ticket-print').innerHTML;
    const printWindow = window.open('', '_blank', 'width=900,height=800');
    
    printWindow.document.write(`
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>In Vé - <?= htmlspecialchars($tieu_de ?? 'Vé xem phim') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif; 
            background: #f9fafb;
            padding: 30px;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
        }
        
        .print-header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 3px solid #667eea;
            margin-bottom: 30px;
        }
        
        .print-logo {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 4px;
            margin-bottom: 8px;
        }
        
        .print-subtitle {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
        }
        
        .print-date {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 10px;
        }
        
        ${document.querySelector('style').innerHTML}
        
        .modern-ticket {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .ticket-footer {
            position: relative;
        }
        
        .ticket-footer::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background-image: 
                repeating-linear-gradient(
                    90deg,
                    #d1d5db 0,
                    #d1d5db 8px,
                    transparent 8px,
                    transparent 16px
                );
        }
        
        .print-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            color: #9ca3af;
            font-size: 12px;
        }
        
        @media print {
            body { 
                background: #fff; 
                padding: 0; 
            }
            .print-container {
                padding: 20px;
                position: relative;
            }
            .print-container::before {
                content: 'CINEPASS';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 80px;
                font-weight: 900;
                color: rgba(102, 126, 234, 0.03);
                letter-spacing: 10px;
                z-index: 0;
                pointer-events: none;
            }
            .print-header, .modern-ticket, .print-footer {
                position: relative;
                z-index: 1;
            }
            .print-logo {
                color: #667eea;
                background: none;
                -webkit-text-fill-color: #667eea;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-header">
            <div class="print-logo">CINEPASS</div>
            <div class="print-subtitle">🎬 Vé Xem Phim 🎬</div>
            <div class="print-date">In ngày: ${new Date().toLocaleString('vi-VN')}</div>
        </div>
        
        <div class="modern-ticket">
            ${ticketContent}
        </div>
        
        <div class="print-footer">
            <p><strong>Lưu ý:</strong> Vui lòng mang theo vé này hoặc mã QR khi đến rạp</p>
            <p style="margin-top: 8px;">Cảm ơn quý khách đã sử dụng dịch vụ CINEPASS</p>
            <p style="margin-top: 12px; font-size: 11px;">🌐 www.cinepass.vn | ☎ 1900-xxxx | 📧 support@cinepass.vn</p>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
    `);
    
    printWindow.document.close();
}
</script>

<?php include "./view/home/footer.php"; ?>
