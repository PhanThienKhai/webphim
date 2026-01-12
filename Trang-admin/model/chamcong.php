<?php

// Include Haar Cascade Face Detector
require_once dirname(__FILE__) . '/../PHP-FaceDetector-master/FaceDetector.php';

function cc_ensure_schema(){
    pdo_execute("CREATE TABLE IF NOT EXISTS cham_cong (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_nv INT NOT NULL,
        id_rap INT NOT NULL,
        ngay DATE NOT NULL,
        gio_vao TIME NOT NULL,
        gio_ra TIME DEFAULT NULL,
        ghi_chu VARCHAR(255) DEFAULT NULL,
        ghi_chu_ra VARCHAR(255) DEFAULT NULL,
        location_checkin VARCHAR(100) DEFAULT NULL,
        location_checkout VARCHAR(100) DEFAULT NULL,
        auth_method_in VARCHAR(50) DEFAULT 'manual',
        auth_method_out VARCHAR(50) DEFAULT 'manual',
        break_duration INT DEFAULT 60,
        ngay_tao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function cc_validate_time($gio_vao, $gio_ra){
    // Validate: gio_ra must be after gio_vao
    $vao = strtotime($gio_vao);
    $ra = strtotime($gio_ra);
    
    if ($ra <= $vao) {
        return ['valid' => false, 'error' => 'Giờ ra phải sau giờ vào'];
    }
    
    $hours = ($ra - $vao) / 3600;
    
    if ($hours > 16) {
        return ['valid' => false, 'error' => 'Ca làm việc không được quá 16 giờ'];
    }
    
    if ($hours < 0.5) {
        return ['valid' => false, 'error' => 'Ca làm việc phải ít nhất 30 phút'];
    }
    
    return ['valid' => true, 'hours' => $hours];
}

function cc_check_duplicate($id_nv, $ngay, $gio_vao, $gio_ra){
    cc_ensure_schema();
    // Check if overlapping time exists for same employee on same day
    $existing = pdo_query("SELECT id FROM cham_cong 
                          WHERE id_nv = ? AND ngay = ? 
                          AND ((gio_vao <= ? AND gio_ra > ?) OR (gio_vao < ? AND gio_ra >= ?) OR (gio_vao >= ? AND gio_ra <= ?))",
                          $id_nv, $ngay, $gio_vao, $gio_vao, $gio_ra, $gio_ra, $gio_vao, $gio_ra);
    return count($existing) > 0;
}

function cc_insert($id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu=null){
    cc_ensure_schema();
    
    // Validate time
    $validation = cc_validate_time($gio_vao, $gio_ra);
    if (!$validation['valid']) {
        throw new Exception($validation['error']);
    }
    
    // Check duplicate
    if (cc_check_duplicate($id_nv, $ngay, $gio_vao, $gio_ra)) {
        throw new Exception('Đã tồn tại bản ghi chấm công trùng thời gian');
    }
    
    pdo_execute("INSERT INTO cham_cong(id_nv,id_rap,ngay,gio_vao,gio_ra,ghi_chu) VALUES(?,?,?,?,?,?)",
        $id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu);
}

function cc_delete($id){ cc_ensure_schema(); pdo_execute("DELETE FROM cham_cong WHERE id=?", $id); }

function cc_list_by_rap_month($id_rap, $ym, $id_nv = null){
    cc_ensure_schema();
    if ($id_nv) {
        return pdo_query("SELECT cc.*, tk.name AS ten_nv FROM cham_cong cc JOIN taikhoan tk ON tk.id = cc.id_nv WHERE cc.id_rap = ? AND cc.id_nv = ? AND DATE_FORMAT(cc.ngay,'%Y-%m') = ? ORDER BY cc.ngay DESC, cc.gio_vao",
            $id_rap, $id_nv, $ym);
    }
    return pdo_query("SELECT cc.*, tk.name AS ten_nv FROM cham_cong cc JOIN taikhoan tk ON tk.id = cc.id_nv WHERE cc.id_rap = ? AND DATE_FORMAT(cc.ngay,'%Y-%m') = ? ORDER BY cc.ngay DESC, cc.gio_vao",
        $id_rap, $ym);
}

function cc_sum_hours($id_nv, $id_rap, $ym){
    cc_ensure_schema();
    $rows = pdo_query("SELECT TIMESTAMPDIFF(MINUTE, cc.ngay + INTERVAL TIME_TO_SEC(cc.gio_vao) SECOND, cc.ngay + INTERVAL TIME_TO_SEC(cc.gio_ra) SECOND) AS minutes
                       FROM cham_cong cc WHERE cc.id_nv=? AND cc.id_rap=? AND DATE_FORMAT(cc.ngay,'%Y-%m')=?",
                       $id_nv, $id_rap, $ym);
    $min = 0; foreach ($rows as $r) { $min += max(0, (int)($r['minutes'] ?? 0)); }
    return $min/60.0;
}

function luong_tinh_thang($id_rap, $ym, $rate_per_hour = 30000){
    // Tính lương CHI TIẾT với breakdown từng ngày + phụ cấp/khấu trừ
    $ds_nv = pdo_query("SELECT id, name, phu_cap_co_dinh FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
    $out = [];
    
    foreach ($ds_nv as $nv){
        $id_nv = (int)$nv['id'];
        
        // Lấy chi tiết từng ngày làm việc
        $chi_tiet_ngay = pdo_query(
            "SELECT ngay, gio_vao, gio_ra,
                    TIMESTAMPDIFF(MINUTE, 
                        ngay + INTERVAL TIME_TO_SEC(gio_vao) SECOND, 
                        ngay + INTERVAL TIME_TO_SEC(gio_ra) SECOND
                    ) / 60.0 AS so_gio
             FROM cham_cong 
             WHERE id_nv = ? AND id_rap = ? AND DATE_FORMAT(ngay,'%Y-%m') = ?
             ORDER BY ngay ASC",
            $id_nv, $id_rap, $ym
        );
        
        // Tính tổng giờ và tiền cho từng ngày
        $tong_gio = 0;
        $late_count = 0;
        foreach ($chi_tiet_ngay as &$day) {
            $day['so_gio'] = max(0, (float)$day['so_gio']);
            $day['tien'] = round($day['so_gio'] * $rate_per_hour);
            $tong_gio += $day['so_gio'];
            
            // Đếm số lần đi muộn (sau 8:30)
            $checkin_time = date('H:i', strtotime($day['gio_vao']));
            if ($checkin_time > '08:30') {
                $late_count++;
            }
        }
        
        // Lương cơ bản
        $luong_co_ban = round($tong_gio * $rate_per_hour);
        
        // Phụ cấp cố định (lấy từ bảng taikhoan)
        $phu_cap = (float)($nv['phu_cap_co_dinh'] ?? 0);
        
        // Khấu trừ: đi muộn × 50,000 VND
        $khau_tru = $late_count * 50000;
        
        // Tổng thực lãnh
        $tong_thuc_lanh = $luong_co_ban + $phu_cap - $khau_tru;
        
        $out[] = [
            'id_nv' => $id_nv,
            'ten_nv' => $nv['name'],
            'so_gio' => $tong_gio,
            'luong_co_ban' => $luong_co_ban,
            'phu_cap' => $phu_cap,
            'khau_tru' => $khau_tru,
            'late_count' => $late_count,
            'tong_thuc_lanh' => $tong_thuc_lanh,
            'chi_tiet_ngay' => $chi_tiet_ngay
        ];
    }
    
    return $out;
}

// ============== QUẢN LÝ BẢNG LƯƠNG (LƯU VÀ TRẠNG THÁI) ==============

// Lưu bảng lương vào database
function bl_save($id_rap, $thang, $ds_luong, $nguoi_tao) {
    // Xóa bảng lương cũ nếu có (để tính lại)
    pdo_execute("DELETE FROM bang_luong WHERE id_rap = ? AND thang = ?", $id_rap, $thang);
    
    foreach ($ds_luong as $nv) {
        $id = pdo_execute(
            "INSERT INTO bang_luong (id_nv, id_rap, thang, so_gio, luong_theo_gio, phu_cap, khau_tru, thuong, tong_luong, trang_thai) 
             VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, 'nhap')",
            $nv['id_nv'], $id_rap, $thang, $nv['so_gio'], $nv['luong_co_ban'], 
            $nv['phu_cap'], $nv['khau_tru'], $nv['tong_thuc_lanh']
        );
        
        // Lưu chi tiết phụ cấp
        if ($nv['phu_cap'] > 0) {
            pdo_execute(
                "INSERT INTO bang_luong_chi_tiet (id_bang_luong, loai, ten_khoan, so_tien) VALUES (?, 'phu_cap', 'Phụ cấp cố định', ?)",
                $id, $nv['phu_cap']
            );
        }
        
        // Lưu chi tiết khấu trừ
        if ($nv['khau_tru'] > 0) {
            pdo_execute(
                "INSERT INTO bang_luong_chi_tiet (id_bang_luong, loai, ten_khoan, so_tien, ghi_chu) VALUES (?, 'khau_tru', 'Phạt đi muộn', ?, ?)",
                $id, $nv['khau_tru'], "Đi muộn {$nv['late_count']} lần × 50,000 ₫"
            );
        }
    }
    
    return true;
}

// Lấy bảng lương đã lưu
function bl_get_saved($id_rap, $thang) {
    return pdo_query(
        "SELECT bl.*, tk.name AS ten_nv 
         FROM bang_luong bl 
         JOIN taikhoan tk ON tk.id = bl.id_nv 
         WHERE bl.id_rap = ? AND bl.thang = ?
         ORDER BY tk.name",
        $id_rap, $thang
    );
}

// Kiểm tra xem tháng này đã lưu bảng lương chưa
function bl_is_saved($id_rap, $thang) {
    $result = pdo_query_one("SELECT COUNT(*) as count FROM bang_luong WHERE id_rap = ? AND thang = ?", $id_rap, $thang);
    return $result && (int)$result['count'] > 0;
}

// Cập nhật trạng thái bảng lương
function bl_update_status($id_rap, $thang, $new_status, $nguoi_duyet = null) {
    if ($new_status === 'da_duyet' || $new_status === 'da_thanh_toan') {
        pdo_execute(
            "UPDATE bang_luong SET trang_thai = ?, nguoi_duyet = ?, ngay_duyet = NOW() WHERE id_rap = ? AND thang = ?",
            $new_status, $nguoi_duyet, $id_rap, $thang
        );
    } else {
        pdo_execute(
            "UPDATE bang_luong SET trang_thai = ? WHERE id_rap = ? AND thang = ?",
            $new_status, $id_rap, $thang
        );
    }
}

// Lấy thống kê trạng thái bảng lương
function bl_get_status_summary($id_rap) {
    return pdo_query(
        "SELECT thang, trang_thai, COUNT(*) as so_nv, SUM(tong_luong) as tong_tien
         FROM bang_luong 
         WHERE id_rap = ?
         GROUP BY thang, trang_thai
         ORDER BY thang DESC",
        $id_rap
    );
}

// Lấy lịch làm việc đã phân công cho nhân viên trong tháng
function cc_get_scheduled_work($id_nv, $id_rap, $ym){
    $sql = "SELECT * FROM lich_lam_viec
            WHERE id_nhan_vien = ? 
            AND id_rap = ?
            AND DATE_FORMAT(ngay, '%Y-%m') = ?
            ORDER BY ngay, gio_bat_dau";
    return pdo_query($sql, $id_nv, $id_rap, $ym);
}

// So sánh chấm công thực tế với lịch đã phân công
function cc_compare_with_schedule($id_nv, $id_rap, $ym){
    $scheduled = cc_get_scheduled_work($id_nv, $id_rap, $ym);
    $actual = pdo_query("SELECT * FROM cham_cong WHERE id_nv = ? AND id_rap = ? AND DATE_FORMAT(ngay, '%Y-%m') = ?", $id_nv, $id_rap, $ym);
    
    $result = [];
    foreach ($scheduled as $sch) {
        $ngay = $sch['ngay'];
        $gio_vao_scheduled = $sch['gio_bat_dau'];
        $gio_ra_scheduled = $sch['gio_ket_thuc'];
        
        // Find actual attendance for this day
        $found = null;
        foreach ($actual as $act) {
            if ($act['ngay'] === $ngay) {
                $found = $act;
                break;
            }
        }
        
        if ($found) {
            $late_minutes = 0;
            $early_minutes = 0;
            
            if ($gio_vao_scheduled && $found['gio_vao'] > $gio_vao_scheduled) {
                $late_minutes = (strtotime($found['gio_vao']) - strtotime($gio_vao_scheduled)) / 60;
            }
            
            if ($gio_ra_scheduled && $found['gio_ra'] < $gio_ra_scheduled) {
                $early_minutes = (strtotime($gio_ra_scheduled) - strtotime($found['gio_ra'])) / 60;
            }
            
            $result[] = [
                'ngay' => $ngay,
                'scheduled_in' => $gio_vao_scheduled,
                'scheduled_out' => $gio_ra_scheduled,
                'actual_in' => $found['gio_vao'],
                'actual_out' => $found['gio_ra'],
                'late_minutes' => $late_minutes,
                'early_minutes' => $early_minutes,
                'status' => ($late_minutes > 5 || $early_minutes > 5) ? 'warning' : 'ok'
            ];
        } else {
            $result[] = [
                'ngay' => $ngay,
                'scheduled_in' => $gio_vao_scheduled,
                'scheduled_out' => $gio_ra_scheduled,
                'actual_in' => null,
                'actual_out' => null,
                'late_minutes' => 0,
                'early_minutes' => 0,
                'status' => 'absent'
            ];
        }
    }
    
    return $result;
}

// Tính tổng số lần đi muộn/về sớm/vắng mặt
function cc_attendance_summary($id_nv, $id_rap, $ym){
    $comparison = cc_compare_with_schedule($id_nv, $id_rap, $ym);
    
    $total_scheduled = count($comparison);
    $late_count = 0;
    $early_count = 0;
    $absent_count = 0;
    $ontime_count = 0;
    
    foreach ($comparison as $c) {
        if ($c['status'] === 'absent') {
            $absent_count++;
        } elseif ($c['status'] === 'warning') {
            if ($c['late_minutes'] > 5) $late_count++;
            if ($c['early_minutes'] > 5) $early_count++;
        } else {
            $ontime_count++;
        }
    }
    
    return [
        'total_scheduled' => $total_scheduled,
        'late_count' => $late_count,
        'early_count' => $early_count,
        'absent_count' => $absent_count,
        'ontime_count' => $ontime_count,
        'attendance_rate' => $total_scheduled > 0 ? round(($total_scheduled - $absent_count) / $total_scheduled * 100, 1) : 0
    ];
}

// ============== SELF-SERVICE CHECK-IN FUNCTIONS ==============

// Đăng ký khuôn mặt cho nhân viên
function cc_register_face($id_nv, $fingerprint_json) {
    if (!$fingerprint_json) {
        throw new Exception('Vui lòng cung cấp fingerprint khuôn mặt');
    }
    
    pdo_execute("UPDATE taikhoan SET face_template = ?, face_registered_at = NOW() WHERE id = ?",
                $fingerprint_json, $id_nv);
    
    return ['success' => true, 'message' => 'Đăng ký khuôn mặt thành công!'];
}

// Lấy face template của nhân viên
function cc_get_face_template($id_nv) {
    $user = pdo_query_one("SELECT face_template FROM taikhoan WHERE id = ?", $id_nv);
    return $user ? $user['face_template'] : null;
}

// Phát hiện khuôn mặt trong ảnh base64 sử dụng Haar Cascade
function cc_detect_face_from_base64($photoBase64) {
    try {
        // Loại bỏ data URI prefix
        if (strpos($photoBase64, 'data:') === 0) {
            $photoBase64 = substr($photoBase64, strpos($photoBase64, ',') + 1);
        }
        
        // Decode base64 thành binary
        $imageBinary = base64_decode($photoBase64, true);
        if (!$imageBinary) {
            return ['detected' => false, 'error' => 'Không thể decode ảnh'];
        }
        
        // Lưu tạm thời vào temp file
        $tempFile = sys_get_temp_dir() . '/face_' . md5(uniqid()) . '.jpg';
        file_put_contents($tempFile, $imageBinary);
        
        // Sử dụng FaceDetector để phát hiện khuôn mặt
        try {
            $detector = new FaceDetector();
            $detector->scan($tempFile);
            $faces = $detector->getFaces();
            
            // Xóa file tạm
            @unlink($tempFile);
            
            if (empty($faces)) {
                return ['detected' => false, 'error' => 'Không phát hiện khuôn mặt. Vui lòng chụp rõ mặt.'];
            }
            
            // Trả về thông tin khuôn mặt tìm được
            return [
                'detected' => true, 
                'face_count' => count($faces),
                'faces' => $faces,
                'message' => 'Phát hiện ' . count($faces) . ' khuôn mặt'
            ];
        } catch (Exception $e) {
            @unlink($tempFile);
            return ['detected' => false, 'error' => 'Lỗi Haar Cascade: ' . $e->getMessage()];
        }
    } catch (Exception $e) {
        return ['detected' => false, 'error' => 'Lỗi xử lý ảnh: ' . $e->getMessage()];
    }
}

// So sánh fingerprint với template đã đăng ký (ULTRA STRICT MODE + HAAR CASCADE)
function cc_verify_face_strict($id_nv, $current_fingerprint_json, $photoBase64 = null) {
    // SECURITY: Validate fingerprint data
    if (empty($current_fingerprint_json)) {
        throw new Exception('❌ Dữ liệu khuôn mặt trống');
    }
    
    $current = json_decode($current_fingerprint_json, true);
    
    if (!is_array($current) || count($current) === 0) {
        throw new Exception('❌ Dữ liệu khuôn mặt không hợp lệ. Vui lòng chụp lại.');
    }
    
    // Check for all zeros (invalid image) - TOO STRICT, SKIP THIS CHECK
    // $sum = array_sum($current);
    // if ($sum === 0) {
    //     throw new Exception('❌ Ảnh không hợp lệ (tất cả pixel đều tối). Vui lòng chụp lại.');
    // }
    
    // Check for all same values (solid color image) - RELAXED
    $unique_values = count(array_unique($current));
    if ($unique_values < 2) {
        // Only reject if completely uniform (1 value) - allow up to 2 unique values
        throw new Exception('❌ Ảnh không hợp lệ (hoàn toàn đơn sắc). Vui lòng chụp lại.');
    }
    
    // BƯỚC 1: Kiểm tra Haar Cascade nếu có ảnh (OPTIONAL - chỉ để detect multiple faces)
    $haar_info = ['checked' => false, 'status' => 'skipped'];
    
    if ($photoBase64) {
        try {
            $faceDetection = cc_detect_face_from_base64($photoBase64);
            if ($faceDetection['detected']) {
                // Phát hiện được khuôn mặt - kiểm tra có nhiều hơn 1 không
                $haar_info = ['checked' => true, 'status' => 'success', 'count' => $faceDetection['face_count']];
                
                if ($faceDetection['face_count'] > 1) {
                    throw new Exception('🚫 Phát hiện nhiều hơn 1 khuôn mặt. Vui lòng chấm công một mình.');
                }
            } else {
                // Haar Cascade không detect được - không throw, chỉ log
                $haar_info = ['checked' => true, 'status' => 'not_detected', 'error' => $faceDetection['error']];
                // Continue with fingerprint verification
            }
        } catch (Exception $e) {
            // Haar Cascade lỗi - không throw, chỉ log
            $haar_info = ['checked' => true, 'status' => 'error', 'error' => $e->getMessage()];
            // Continue with fingerprint verification
        }
    }
    
    // BƯỚC 2: So sánh fingerprint (THIS IS THE MAIN VERIFICATION)
    $template_json = cc_get_face_template($id_nv);
    
    if (!$template_json) {
        throw new Exception('Nhân viên chưa đăng ký khuôn mặt. Vui lòng liên hệ quản lý.');
    }
    
    $template = json_decode($template_json, true);
    
    if (!is_array($template) || count($template) === 0) {
        throw new Exception('Dữ liệu khuôn mặt không hợp lệ');
    }
    
    if (count($template) !== count($current)) {
        throw new Exception('Dữ liệu khuôn mặt không khớp (kích thước khác nhau)');
    }
    
    // Calculate similarity percentage (ULTRA STRICT: threshold = 5)
    $matchCount = 0;
    $threshold = 5; // Pixel tolerance per grid cell
    $maxDiff = 0;
    $totalDiff = 0;
    
    for ($i = 0; $i < count($template); $i++) {
        if (isset($template[$i]) && isset($current[$i])) {
            $diff = abs($template[$i] - $current[$i]);
            $totalDiff += $diff;
            $maxDiff = max($maxDiff, $diff);
            
            if ($diff <= $threshold) {
                $matchCount++;
            }
        }
    }
    
    $similarity = (count($template) > 0) ? round(($matchCount / count($template)) * 100) : 0;
    $avgDiff = round($totalDiff / count($template), 2);
    
    // THRESHOLD: 40% similarity required (relaxed for better usability - still catches different people)
    if ($similarity < 40) {
        throw new Exception("❌ Khuôn mặt không khớp! (Độ tương đồng: {$similarity}%, MAX diff: {$maxDiff}, AVG diff: {$avgDiff}). Vui lòng kiểm tra lại.");
    }
    
    // Build success message
    $haarMsg = '';
    if ($photoBase64 && isset($haar_info)) {
        if ($haar_info['status'] === 'success') {
            $haarMsg = ' [Haar OK]';
        } elseif ($haar_info['status'] === 'not_detected') {
            $haarMsg = ' [Haar: Mặt mờ]';
        }
    }
    
    return ['success' => true, 'similarity' => $similarity, 'haar_info' => $haar_info, 'message' => "✓ Khuôn mặt xác minh thành công ({$similarity}%){$haarMsg}"];
}

// Kiểm tra trạng thái check-in hôm nay của nhân viên
function cc_check_today_status($id_nv, $id_rap){
    cc_ensure_schema();
    $today = date('Y-m-d');
    $record = pdo_query_one("SELECT * FROM cham_cong WHERE id_nv = ? AND id_rap = ? AND ngay = ? ORDER BY gio_vao DESC LIMIT 1", 
                            $id_nv, $id_rap, $today);
    
    if (!$record) {
        return ['status' => 'not_checked_in', 'record' => null];
    }
    
    // Kiểm tra xem đã checkout thực sự chưa
    // Logic: Nếu gio_ra > thời điểm hiện tại => chưa checkout (vẫn là giờ mặc định)
    //        Nếu gio_ra <= thời điểm hiện tại => đã checkout thực sự
    $now = time();
    $gio_ra_timestamp = strtotime($today . ' ' . $record['gio_ra']);
    
    if ($gio_ra_timestamp > $now) {
        // Giờ ra vẫn ở tương lai => chưa checkout thực sự
        return ['status' => 'checked_in', 'record' => $record, 'checkin_time' => $record['gio_vao']];
    } else {
        // Giờ ra đã qua => đã checkout rồi
        return ['status' => 'checked_out', 'record' => $record, 'checkin_time' => $record['gio_vao'], 'checkout_time' => $record['gio_ra']];
    }
}

// Quick check-in (tự động tạo record với giờ ra = giờ vào + 8h)
function cc_quick_checkin($id_nv, $id_rap){
    cc_ensure_schema();
    $today = date('Y-m-d');
    $now_time = date('H:i:s');
    $checkout_time = date('H:i:s', strtotime('+8 hours')); // Mặc định 8h
    
    // Get GPS data from POST
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $location_accuracy = $_POST['location_accuracy'] ?? null;
    
    // Get face fingerprint from POST (as JSON array)
    $fingerprint_vao = $_POST['fingerprint_vao'] ?? null;
    
    // VERIFY FACE: So sánh với face template đã đăng ký
    if ($fingerprint_vao) {
        $verification = cc_verify_face_strict($id_nv, $fingerprint_vao);
        if (!$verification['success']) {
            throw new Exception($verification['message']);
        }
    }
    
    // Kiểm tra đã check-in chưa
    $status = cc_check_today_status($id_nv, $id_rap);
    if ($status['status'] !== 'not_checked_in') {
        throw new Exception('Bạn đã check-in hôm nay rồi');
    }
    
    // Insert record with GPS data and fingerprint
    pdo_execute("INSERT INTO cham_cong(id_nv, id_rap, ngay, gio_vao, gio_ra, ghi_chu, latitude, longitude, location_accuracy, fingerprint_vao) VALUES(?,?,?,?,?,?,?,?,?,?)",
                $id_nv, $id_rap, $today, $now_time, $checkout_time, 'Self check-in', $latitude, $longitude, $location_accuracy, $fingerprint_vao);
    
    return ['success' => true, 'time' => $now_time, 'message' => 'Check-in thành công lúc ' . date('H:i')];
}

// Quick check-out (cập nhật giờ ra)
function cc_quick_checkout($id_nv, $id_rap){
    cc_ensure_schema();
    $today = date('Y-m-d');
    $now_time = date('H:i:s');
    
    // Get GPS data from POST
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $location_accuracy = $_POST['location_accuracy'] ?? null;
    
    // Get face fingerprint from POST (as JSON array)
    $fingerprint_ra = $_POST['fingerprint_ra'] ?? null;
    
    // VERIFY FACE: So sánh với face template đã đăng ký
    if ($fingerprint_ra) {
        $verification = cc_verify_face_strict($id_nv, $fingerprint_ra);
        if (!$verification['success']) {
            throw new Exception($verification['message']);
        }
    }
    
    // Kiểm tra trạng thái
    $status = cc_check_today_status($id_nv, $id_rap);
    
    if ($status['status'] === 'not_checked_in') {
        throw new Exception('Bạn chưa check-in hôm nay');
    }
    
    if ($status['status'] === 'checked_out') {
        throw new Exception('Bạn đã check-out rồi');
    }
    
    // Validate: giờ ra phải sau giờ vào ít nhất 1h
    $gio_vao = $status['checkin_time'];
    $diff_hours = (strtotime($now_time) - strtotime($gio_vao)) / 3600;
    
    if ($diff_hours < 1) {
        throw new Exception('Giờ check-out phải sau giờ check-in ít nhất 1 tiếng');
    }
    
    // Update giờ ra with GPS data and fingerprint
    pdo_execute("UPDATE cham_cong SET gio_ra = ?, ghi_chu = 'Self check-out', latitude = ?, longitude = ?, location_accuracy = ?, fingerprint_ra = ? WHERE id = ?",
                $now_time, $latitude, $longitude, $location_accuracy, $fingerprint_ra, $status['record']['id']);
    
    $total_hours = round($diff_hours, 1);
    return ['success' => true, 'time' => $now_time, 'total_hours' => $total_hours, 'message' => 'Check-out thành công. Tổng: ' . $total_hours . ' giờ'];
}

// Lấy lịch sử check-in của nhân viên trong tháng
function cc_my_history($id_nv, $id_rap, $ym){
    cc_ensure_schema();
    return pdo_query("SELECT * FROM cham_cong WHERE id_nv = ? AND id_rap = ? AND DATE_FORMAT(ngay,'%Y-%m') = ? ORDER BY ngay DESC",
                     $id_nv, $id_rap, $ym);
}
