<?php
/**
 * Export kế hoạch chiếu ra file Word
 */
function export_kehoach_word($kehoach, $khung_gio) {
    // Tạo nội dung HTML cho file Word
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Xác Nhận Lịch Chiếu</title>
        <style>
            body { font-family: "Times New Roman", serif; font-size: 14px; line-height: 1.5; }
            .header { text-align: center; margin-bottom: 30px; }
            .header h1 { color: #d4af37; font-size: 24px; font-weight: bold; }
            .header h2 { color: #333; font-size: 18px; margin: 5px 0; }
            .content { margin: 20px 0; }
            .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .info-table td { padding: 8px; border: 1px solid #ddd; }
            .info-table .label { background: #f5f5f5; font-weight: bold; width: 150px; }
            .schedule-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .schedule-table th, .schedule-table td { padding: 10px; border: 1px solid #333; text-align: center; }
            .schedule-table th { background: #4a90e2; color: white; font-weight: bold; }
            .footer { margin-top: 40px; }
            .signature { float: right; text-align: center; margin-top: 30px; }
            .signature p { margin: 5px 0; }
            .logo { width: 80px; height: 80px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>🎬 CINEPASS CINEMA CHAIN</h1>
            <h2>XÁC NHẬN KẾ HOẠCH CHIẾU PHIM</h2>
            <p><strong>Rạp:</strong> ' . htmlspecialchars($kehoach['ten_rap']) . '</p>
        </div>
        
        <div class="content">
            <table class="info-table">
                <tr>
                    <td class="label">Tên Phim:</td>
                    <td><strong>' . htmlspecialchars($kehoach['tieu_de']) . '</strong></td>
                </tr>
                <tr>
                    <td class="label">Thời Lượng:</td>
                    <td>' . $kehoach['thoi_luong_phim'] . ' phút</td>
                </tr>
                <tr>
                    <td class="label">Ngày Chiếu:</td>
                    <td><strong>' . date('d/m/Y (l)', strtotime($kehoach['ngay_chieu'])) . '</strong></td>
                </tr>
                <tr>
                    <td class="label">Phòng Chiếu:</td>
                    <td>' . htmlspecialchars($kehoach['ten_phong']) . '</td>
                </tr>
                <tr>
                    <td class="label">Trạng Thái:</td>
                    <td>' . ($kehoach['trang_thai_duyet'] == 1 ? '<strong style="color: green;">✅ Đã Duyệt</strong>' : '<strong style="color: orange;">⏳ Chờ Duyệt</strong>') . '</td>
                </tr>
            </table>
            
            <h3>📋 KHUNG GIỜ CHIẾU</h3>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Giờ Chiếu</th>
                        <th>Giờ Kết Thúc (Dự Kiến)</th>
                        <th>Ghi Chú</th>
                    </tr>
                </thead>
                <tbody>';
    
    $stt = 1;
    foreach ($khung_gio as $kg) {
        $gio_bat_dau = date('H:i', strtotime($kg['thoi_gian_chieu']));
        
        // Tính giờ kết thúc dự kiến (thời lượng phim + 15 phút dọn dẹp)
        $timestamp = strtotime($kg['thoi_gian_chieu']) + ($kehoach['thoi_luong_phim'] * 60) + (15 * 60);
        $gio_ket_thuc = date('H:i', $timestamp);
        
        $html .= '
                    <tr>
                        <td>' . $stt . '</td>
                        <td><strong>' . $gio_bat_dau . '</strong></td>
                        <td>' . $gio_ket_thuc . '</td>
                        <td>Bao gồm 15 phút dọn dẹp</td>
                    </tr>';
        $stt++;
    }
    
    $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p><strong>Ghi Chú Từ Quản Lý Rạp:</strong></p>
                <p style="font-style: italic; background: #f9f9f9; padding: 15px; border-left: 4px solid #4a90e2;">
                    ' . ($kehoach['ghi_chu'] ? htmlspecialchars($kehoach['ghi_chu']) : 'Không có ghi chú đặc biệt.') . '
                </p>
                
                <div class="signature">
                    <p><strong>Ngày tạo:</strong> ' . date('d/m/Y H:i') . '</p>
                    <p>___________________________</p>
                    <p><strong>Quản Lý Rạp</strong></p>
                    <br><br>
                    <p>___________________________</p>
                    <p><strong>Quản Lý Cụm (Duyệt)</strong></p>
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    // Xuất file Word
    $filename = 'KeHoachChieu_' . date('Ymd_His', strtotime($kehoach['ngay_chieu'])) . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $kehoach['tieu_de']) . '.doc';
    
    header('Content-Type: application/msword');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    
    echo $html;
    exit;
}
?>