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

/**
 * Export chi tiết kế hoạch chiếu ra file Word (version mới)
 */
function export_kehoach_chi_tiet_word($chi_tiet_ke_hoach) {
    if (empty($chi_tiet_ke_hoach)) {
        return false;
    }
    
    $ke_hoach_info = $chi_tiet_ke_hoach[0];
    
    // Tạo nội dung HTML
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Kế Hoạch Chiếu Phim - ' . htmlspecialchars($ke_hoach_info['ma_ke_hoach']) . '</title>
        <style>
            body { font-family: "Times New Roman", serif; font-size: 14px; line-height: 1.5; }
            .header { text-align: center; margin-bottom: 30px; }
            .header h1 { color: #d4af37; font-size: 24px; font-weight: bold; margin: 0; }
            .header h2 { color: #333; font-size: 18px; margin: 10px 0; }
            .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
            .info-table td { padding: 8px 12px; border: 1px solid #ddd; }
            .info-table .label { background: #f8f9fc; font-weight: bold; width: 150px; }
            .schedule-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
            .schedule-table th, .schedule-table td { padding: 8px; border: 1px solid #333; text-align: center; }
            .schedule-table th { background: #4a90e2; color: white; font-weight: bold; }
            .footer { margin-top: 40px; text-align: right; }
            .signature { margin-top: 50px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>🎬 CINEPASS CINEMA CHAIN</h1>
            <h2>KẾ HOẠCH CHIẾU PHIM</h2>
        </div>
        
        <table class="info-table">
            <tr><td class="label">Mã kế hoạch:</td><td><strong>' . htmlspecialchars($ke_hoach_info['ma_ke_hoach']) . '</strong></td></tr>
            <tr><td class="label">Tên phim:</td><td><strong>' . htmlspecialchars($ke_hoach_info['ten_phim']) . '</strong></td></tr>
            <tr><td class="label">Thời lượng:</td><td>' . $ke_hoach_info['thoi_luong_phim'] . ' phút</td></tr>
            <tr><td class="label">Rạp chiếu:</td><td>' . htmlspecialchars($ke_hoach_info['ten_rap']) . '</td></tr>
            <tr><td class="label">Trạng thái:</td><td>' . $ke_hoach_info['trang_thai_duyet'] . '</td></tr>
            <tr><td class="label">Ngày tạo:</td><td>' . date('d/m/Y H:i:s', strtotime($ke_hoach_info['ngay_tao'])) . '</td></tr>
        </table>
        
        <h3>CHI TIẾT LỊCH CHIẾU</h3>
        <table class="schedule-table">
            <thead>
                <tr><th>STT</th><th>Ngày chiếu</th><th>Khung giờ</th><th>Phòng</th></tr>
            </thead>
            <tbody>';
    
    $stt = 1;
    foreach ($chi_tiet_ke_hoach as $item) {
        $html .= '<tr>';
        $html .= '<td>' . $stt++ . '</td>';
        $html .= '<td>' . date('d/m/Y', strtotime($item['ngay_chieu'])) . '</td>';
        
        if ($item['khung_gio']) {
            $khung_gio_list = explode(',', $item['khung_gio']);
            $gio_html = [];
            $phong_html = [];
            
            foreach ($khung_gio_list as $kg) {
                $parts = explode('|', $kg);
                if (count($parts) == 2) {
                    $gio_html[] = date('H:i', strtotime($parts[0]));
                    if (!in_array($parts[1], $phong_html)) {
                        $phong_html[] = $parts[1];
                    }
                }
            }
            
            $html .= '<td>' . implode(', ', $gio_html) . '</td>';
            $html .= '<td>' . implode(', ', $phong_html) . '</td>';
        } else {
            $html .= '<td>-</td><td>-</td>';
        }
        
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>
        
        <div class="footer">
            <div class="signature">
                <p><strong>Quản lý rạp</strong></p>
                <p style="margin-top: 60px;">___________________</p>
            </div>
        </div>
    </body>
    </html>';

    // Set headers cho file Word
    $filename = 'KeHoach_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $ke_hoach_info['ma_ke_hoach']) . '_' . date('Ymd_His') . '.doc';
    header('Content-Type: application/msword');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    
    echo $html;
    exit;
}
?>