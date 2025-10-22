<?php
session_start();

// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Handle Enhanced Calendar POST requests FIRST (before any includes or output)
if (isset($_GET['act']) && $_GET['act'] === 'ql_lichlamviec_calendar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear any output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Content-Type: application/json; charset=utf-8');
    
    // Debug log - write to file to see if this code runs
    file_put_contents('debug_post.log', "POST handler started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    
    // Check session first
    if (!isset($_SESSION['user1'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
    
    // Include required files for this API
    include "./model/pdo.php";
    include "./model/lichlamviec.php";
    include "./helpers/quyen.php";
    
    // Check permissions
    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
    if (!allowed_act('ql_lichlamviec_calendar', $currentRole)) {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit;
    }
    
    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
    if (!$id_rap) {
        echo json_encode(['success' => false, 'message' => 'No cinema ID']);
        exit;
    }
    
    // Debug log
    error_log('Enhanced Calendar POST request received');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Debug input
    error_log('Input data: ' . print_r($input, true));
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }
    
    if ($input['action'] === 'create_assignments') {
        $assignments = $input['assignments'] ?? [];
        $success_count = 0;
        $error_count = 0;
        $errors = [];
        
        // Debug log
        file_put_contents('debug_post.log', "Assignments received: " . print_r($assignments, true) . "\n", FILE_APPEND);
        
        if (empty($assignments)) {
            echo json_encode(['success' => false, 'message' => 'Không có ca làm việc nào để tạo']);
            exit;
        }
        
        foreach ($assignments as $assignment) {
            $id_nv = (int)($assignment['nhanvien_id'] ?? 0);
            $ngay = trim($assignment['ngay'] ?? '');
            $gio_bat_dau = trim($assignment['gio_bat_dau'] ?? '');
            $gio_ket_thuc = trim($assignment['gio_ket_thuc'] ?? '');
            $ca_lam = trim($assignment['ca_lam'] ?? '');
            $ghi_chu = trim($assignment['ghi_chu'] ?? '');
            
            file_put_contents('debug_post.log', "Processing assignment: ID=$id_nv, Date=$ngay, Start=$gio_bat_dau, End=$gio_ket_thuc\n", FILE_APPEND);
            
            if ($id_nv && $ngay && $gio_bat_dau && $gio_ket_thuc) {
                // Kiểm tra nhân viên có thuộc rạp này không
                $nv_check = pdo_query_one("SELECT id FROM taikhoan WHERE id = ? AND id_rap = ?", $id_nv, $id_rap);
                if (!$nv_check) {
                    $errors[] = "Nhân viên ID $id_nv không thuộc rạp này";
                    $error_count++;
                    file_put_contents('debug_post.log', "Employee check failed for ID=$id_nv\n", FILE_APPEND);
                    continue;
                }
                
                // Kiểm tra trùng lặp
                if (!llv_exists($id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc)) {
                    llv_insert($id_nv, $id_rap, $ngay, $gio_bat_dau, $gio_ket_thuc, 
                              $ca_lam ? $ca_lam : null, $ghi_chu ? $ghi_chu : null);
                    $success_count++;
                    file_put_contents('debug_post.log', "Successfully inserted assignment for ID=$id_nv\n", FILE_APPEND);
                } else {
                    $errors[] = "Ca làm việc đã tồn tại cho nhân viên ID $id_nv vào $ngay";
                    $error_count++;
                    file_put_contents('debug_post.log', "Duplicate assignment for ID=$id_nv\n", FILE_APPEND);
                }
            } else {
                $errors[] = "Thiếu thông tin bắt buộc cho một ca làm việc (ID=$id_nv, ngay=$ngay, start=$gio_bat_dau, end=$gio_ket_thuc)";
                $error_count++;
                file_put_contents('debug_post.log', "Missing required data for assignment\n", FILE_APPEND);
            }
        }
        
        // Determine overall success - chỉ success nếu KHÔNG có lỗi
        $overall_success = ($success_count > 0 && $error_count === 0);
        
        $response = [
            'success' => $overall_success,
            'success_count' => $success_count,
            'error_count' => $error_count,
            'message' => $overall_success 
                ? "✅ Tạo thành công $success_count ca làm việc" 
                : ($success_count > 0 
                    ? "⚠️ Tạo được $success_count ca, nhưng có $error_count ca bị lỗi" 
                    : "❌ Không thể tạo ca làm việc nào"),
            'errors' => $errors,
            'partial_success' => ($success_count > 0 && $error_count > 0) // Thêm flag này
        ];
        
        file_put_contents('debug_post.log', "Final response: " . print_r($response, true) . "\n", FILE_APPEND);
        
        echo json_encode($response);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action: ' . ($input['action'] ?? 'none')]);
        exit;
    }
}

// Handle suaphong POST requests BEFORE any output
if (isset($_GET['act']) && $_GET['act'] === 'suaphong' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['ids'])) {
    // Clear any output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Check session first
    if (!isset($_SESSION['user1'])) {
        header("Location: login.php");
        exit;
    }
    
    // Include required files
    include "./model/pdo.php";
    include "./model/phong_ghe.php";
    include "./model/phong.php";
    include "./helpers/quyen.php";
    
    // Check permissions
    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
    if (!allowed_act('suaphong', $currentRole)) {
        header("HTTP/1.0 403 Forbidden");
        exit;
    }
    
    $id_phong_edit = (int)$_GET['ids'];
    
    // Xử lý tạo sơ đồ theo template (form đơn giản)
    if (isset($_POST['tao_map_template'])) {
        $template = $_POST['template_type'] ?? 'medium';
        $custom_rows = isset($_POST['custom_rows']) ? (int)$_POST['custom_rows'] : null;
        $custom_cols = isset($_POST['custom_cols']) ? (int)$_POST['custom_cols'] : null;
        pg_generate_by_template($id_phong_edit, $template, $custom_rows, $custom_cols);
        header("Location: index.php?act=suaphong&ids=$id_phong_edit&success=created");
        exit;
    }
    
    // Xử lý xóa sơ đồ
    if (isset($_POST['xoa_map'])) {
        pdo_execute("DELETE FROM phong_ghe WHERE id_phong = ?", $id_phong_edit);
        pdo_execute("UPDATE phongchieu SET so_ghe = 0 WHERE id = ?", $id_phong_edit);
        header("Location: index.php?act=suaphong&ids=$id_phong_edit&success=deleted");
        exit;
    }
    
    // Xử lý form tạo sơ đồ mặc định (preset layout)
    if (isset($_POST['tao_map'])) {
        $rows = max(1, (int)($_POST['rows'] ?? 12));
        $cols = max(1, (int)($_POST['cols'] ?? 18));
        pg_generate_default($id_phong_edit, $rows, $cols);
        header("Location: index.php?act=suaphong&ids=$id_phong_edit&success=default");
        exit;
    }
    
    if (isset($_POST['luu_map']) && isset($_POST['map_json'])) {
        $list = json_decode($_POST['map_json'], true);
        if (is_array($list)) { 
            pg_replace_map($id_phong_edit, $list); 
            header("Location: index.php?act=suaphong&ids=$id_phong_edit&success=saved");
            exit;
        } else { 
            header("Location: index.php?act=suaphong&ids=$id_phong_edit&error=invalid_data");
            exit;
        }
    }
    
    // Xử lý cập nhật đầy đủ (thông tin phòng + sơ đồ ghế)
    if (isset($_POST['cap_nhat_full'])) {
        // Cập nhật thông tin phòng
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $so_ghe = isset($_POST['so_ghe']) ? (int)$_POST['so_ghe'] : 0;
        $dien_tich = isset($_POST['dien_tich']) ? (float)$_POST['dien_tich'] : 0;
        
        // Lấy giá vé từ form
        $gia_thuong = isset($_POST['gia_thuong']) ? (float)$_POST['gia_thuong'] : 50000;
        $gia_trung = isset($_POST['gia_trung']) ? (float)$_POST['gia_trung'] : 70000;
        $gia_vip = isset($_POST['gia_vip']) ? (float)$_POST['gia_vip'] : 100000;
        
        if ($id && $name) {
            update_phong($id, $name, $so_ghe, $dien_tich, $gia_thuong, $gia_trung, $gia_vip);
        }
        
        // Cập nhật sơ đồ ghế (nếu có)
        if (isset($_POST['map_json']) && $_POST['map_json']) {
            $list = json_decode($_POST['map_json'], true);
            if (is_array($list)) { 
                pg_replace_map($id_phong_edit, $list); 
            }
        }
        
        header("Location: index.php?act=suaphong&ids=$id_phong_edit&success=updated");
        exit;
    }
}

if(isset($_SESSION['user1'])) {
    include "./model/pdo.php";
    include "./model/loai_phim.php";
    include "./model/phim.php";
    include "./model/taikhoan.php";
    include "./model/lichchieu.php";
    include "./model/phong.php";
    include "./model/thongke.php";
    include "./model/ve.php";
    include "./model/khunggio.php";
    include "./model/phong_ghe.php";
    include "./model/binhluan.php";
    include "./model/rap.php";
    include "./model/lichlamviec.php";
    include "./model/nghiphep.php";
    include "./model/thietbi.php";
    include "./model/website.php";
    include "./model/phim_rap.php";
    include "./model/combo.php";
    include "./model/khuyenmai.php";
    include "./model/doihoan.php";
    include "./model/chamcong.php";
    include "./helpers/quyen.php";
    $loadphim = loadall_phim();
    $loadloai = loadall_loaiphim();
    $loadtk = loadall_taikhoan();
    // API JSON cho đặt vé (trả JSON thuần trước khi include header)
    if (isset($_GET['act'])) {
        $act_api = $_GET['act'];
        if (in_array($act_api, ['api_dates','api_times','api_reserved','api_combos','api_seatmap','api_check_promo','api_promos'], true)) {
            header('Content-Type: application/json; charset=utf-8');
            if ($act_api === 'api_dates') {
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $id_phim = (int)($_GET['id_phim'] ?? 0);
                if (!$id_rap || !$id_phim) { echo json_encode([]); exit; }
                $rows = pdo_query("SELECT id, ngay_chieu FROM lichchieu WHERE id_rap = ? AND id_phim = ? ORDER BY ngay_chieu", $id_rap, $id_phim);
                echo json_encode($rows); exit;
            }
            if ($act_api === 'api_seatmap') {
                $id_phong = (int)($_GET['id_phong'] ?? 0);
                $id_tg = (int)($_GET['id_tg'] ?? 0);
                if (!$id_phong && $id_tg) {
                    $kg = pdo_query_one("SELECT id_phong FROM khung_gio_chieu WHERE id = ?", $id_tg);
                    $id_phong = (int)($kg['id_phong'] ?? 0);
                }
                if (!$id_phong) { echo json_encode(['seats' => [], 'prices' => []]); exit; }
                
                // Lấy giá vé của phòng
                $phong = pdo_query_one("SELECT gia_thuong, gia_trung, gia_vip FROM phongchieu WHERE id = ?", $id_phong);
                $prices = [
                    'cheap' => (int)($phong['gia_thuong'] ?? 60000),
                    'middle' => (int)($phong['gia_trung'] ?? 80000),
                    'expensive' => (int)($phong['gia_vip'] ?? 100000)
                ];
                
                $seats = pg_list($id_phong);
                echo json_encode(['seats' => $seats, 'prices' => $prices]); 
                exit;
            }
            if ($act_api === 'api_times') {
                $id_lc = (int)($_GET['id_lc'] ?? 0);
                if (!$id_lc) { echo json_encode([]); exit; }
                $rows = pdo_query("SELECT id, thoi_gian_chieu FROM khung_gio_chieu WHERE id_lich_chieu = ? ORDER BY thoi_gian_chieu", $id_lc);
                echo json_encode($rows); exit;
            }
            if ($act_api === 'api_reserved') {
                $id_lc = (int)($_GET['id_lc'] ?? 0);
                $id_tg = (int)($_GET['id_tg'] ?? 0);
                if (!$id_lc || !$id_tg) { echo json_encode([]); exit; }
                $list = ve_reserved_seats($id_tg, $id_lc);
                echo json_encode($list); exit;
            }
            if ($act_api === 'api_combos') {
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { echo json_encode([]); exit; }
                try {
                    $rows = combo_by_rap($id_rap);
                    echo json_encode($rows); exit;
                } catch (Exception $e) { echo json_encode([]); exit; }
            }
            if ($act_api === 'api_check_promo') {
                $code = trim($_GET['code'] ?? '');
                $price = (int)($_GET['price'] ?? 0);
                
                if ($code === '' || $price <= 0) {
                    echo json_encode(['valid' => false, 'message' => 'Thông tin không hợp lệ']);
                    exit;
                }
                
                $km = km_find_by_code($code);
                if (!$km) {
                    echo json_encode(['valid' => false, 'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn']);
                    exit;
                }
                
                $discount = km_calculate_discount($km, $price);
                $final_price = max(0, $price - $discount);
                
                echo json_encode([
                    'valid' => true,
                    'message' => 'Áp dụng thành công!',
                    'discount' => $discount,
                    'final_price' => $final_price,
                    'promo_name' => $km['ten_khuyen_mai'],
                    'promo_desc' => $km['mo_ta'] ?? ''
                ]);
                exit;
            }
            if ($act_api === 'api_promos') {
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { echo json_encode(['promos' => []]); exit; }
                
                $promos = km_active_list_by_rap($id_rap);
                $result = [];
                foreach ($promos as $p) {
                    $discount_text = '';
                    if ($p['loai_giam'] === 'phan_tram') {
                        $discount_text = 'Giảm ' . number_format($p['phan_tram_giam']) . '%';
                    } else {
                        $discount_text = 'Giảm ' . number_format($p['gia_tri_giam']) . 'đ';
                    }
                    
                    $result[] = [
                        'code' => $p['ma_khuyen_mai'],
                        'name' => $p['ten_khuyen_mai'],
                        'desc' => $p['mo_ta'] ?? '',
                        'discount_text' => $discount_text,
                        'expires' => date('d/m/Y', strtotime($p['ngay_ket_thuc']))
                    ];
                }
                echo json_encode(['promos' => $result]);
                exit;
            }
            exit;
        }
    }
    // Xử lý đăng xuất TRƯỚC khi có bất kỳ output nào để tránh lỗi headers already sent
    if (isset($_GET['act']) && $_GET['act'] === 'dangxuat') {
        unset($_SESSION['user1']);
        header('Location: login.php');
        exit;
    }
    
    // Xử lý đặt vé AJAX trước khi include header
    if (isset($_POST['datve_confirm']) && isset($_GET['act']) && $_GET['act'] === 'nv_datve') {
        header('Content-Type: application/json; charset=utf-8');
        
        $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
        if (!$id_rap) {
            echo json_encode(['success' => false, 'error' => 'Không xác định được rạp']);
            exit;
        }
        
        $id_phim = (int)($_POST['id_phim'] ?? 0);
        $id_lc = (int)($_POST['id_lc'] ?? 0);
        $id_tg = (int)($_POST['id_tg'] ?? 0);
        $email_kh = trim($_POST['email_kh'] ?? '');
        $ghe_csv = trim($_POST['ghe_csv'] ?? '');
        $combo_text = trim($_POST['combo_text'] ?? '');
        $price = (int)($_POST['price'] ?? 0);
        
        if (!$id_phim || !$id_lc || !$id_tg || $ghe_csv==='' || $price<=0 || $email_kh==='') {
            echo json_encode(['success' => false, 'error' => 'Thiếu thông tin hoặc chưa chọn ghế']);
            exit;
        }
        
        // Validate seats
        $valid = true; 
        $msgInvalid = '';
        $seats = array_filter(array_map('trim', explode(',', $ghe_csv)));
        $map = pg_list_for_time($id_tg);
        
        if (!empty($map)){
            $activeCodes = [];
            foreach ($map as $m){ 
                if ((int)$m['active']===1) $activeCodes[$m['code']] = true; 
            }
            foreach ($seats as $s){ 
                if (!isset($activeCodes[$s])) { 
                    $valid=false; 
                    $msgInvalid='Ghế không hợp lệ trong phòng: '.$s; 
                    break; 
                } 
            }
        }
        
        // Check reserved
        if ($valid){
            $reserved = ve_reserved_seats($id_tg, $id_lc);
            foreach ($seats as $s){ 
                if (in_array($s, $reserved, true)) { 
                    $valid=false; 
                    $msgInvalid='Ghế đã có người đặt: '.$s; 
                    break; 
                } 
            }
        }
        
        $kh = get_tk_by_email($email_kh);
        if (!$kh) {
            echo json_encode(['success' => false, 'error' => 'Không tìm thấy khách hàng theo email']);
            exit;
        } elseif(!$valid) {
            echo json_encode(['success' => false, 'error' => $msgInvalid]);
            exit;
        } else {
            // Xử lý mã khuyến mãi (nếu có)
            $promo_code = trim($_POST['promo_code'] ?? '');
            $discount_amount = 0;
            $final_price = $price;
            
            if ($promo_code !== '') {
                $km = km_find_by_code($promo_code);
                if ($km) {
                    $discount_amount = km_calculate_discount($km, $price);
                    $final_price = max(0, $price - $discount_amount);
                    // Thêm thông tin khuyến mãi vào combo_text
                    if ($combo_text !== '') $combo_text .= '; ';
                    $combo_text .= 'KM: ' . $km['ten_khuyen_mai'] . ' (-' . number_format($discount_amount) . 'đ)';
                }
            }
            
            $ve_id = ve_create_admin($id_phim, $id_rap, $id_tg, $id_lc, (int)$kh['id'], $ghe_csv, $final_price, (int)$_SESSION['user1']['id'], $combo_text);
            echo json_encode([
                'success' => true, 
                've_id' => $ve_id,
                'discount' => $discount_amount,
                'final_price' => $final_price
            ]);
            exit;
        }
    }
    
    // Xử lý các POST actions trước khi include header (để tránh lỗi headers already sent)
    if (isset($_GET['act']) && $_POST && $_GET['act'] === 'luu_kehoach') {
        $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
        if (!allowed_act('luu_kehoach', $currentRole)) {
            header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Bạn không có quyền thực hiện thao tác này!"));
            exit;
        }
        
        // Kiểm tra quyền
        if (!isset($_SESSION['user1']) || $_SESSION['user1']['vai_tro'] != ROLE_QUAN_LY_RAP) {
            header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Bạn không có quyền thực hiện thao tác này!"));
            exit;
        }
        
        $ma_rap = $_SESSION['user1']['id_rap'] ?? null;
        if (!$ma_rap) {
            header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Không xác định được rạp của bạn!"));
            exit;
        }
        
        try {
            // Lấy dữ liệu từ form
            $ma_phim = (int)($_POST['ma_phim'] ?? 0);
            $tu_ngay = $_POST['tu_ngay'] ?? '';
            $den_ngay = $_POST['den_ngay'] ?? '';
            $ghi_chu = $_POST['ghi_chu'] ?? '';
            $gio_bat_dau = $_POST['gio_bat_dau'] ?? [];
            $ma_phong = $_POST['ma_phong'] ?? [];
            
            // Validate dữ liệu
            if ($ma_phim <= 0 || empty($tu_ngay) || empty($den_ngay) || empty($gio_bat_dau) || empty($ma_phong)) {
                throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
            }
            
            if (count($gio_bat_dau) != count($ma_phong)) {
                throw new Exception("Số lượng khung giờ và phòng chiếu không khớp!");
            }
            
            // Tạo mã kế hoạch duy nhất cho toàn bộ kế hoạch
            $ma_ke_hoach = 'KH_' . $ma_rap . '_' . date('YmdHis') . '_' . rand(100, 999);
            $nguoi_tao = $_SESSION['user1']['id'] ?? null;
            
            // Tạo lịch chiếu cho từng ngày
            $start_date = new DateTime($tu_ngay);
            $end_date = new DateTime($den_ngay);
            $ke_hoach_ids = [];
            
            while ($start_date <= $end_date) {
                $ngay_chieu = $start_date->format('Y-m-d');
                
                // Tạo lịch chiếu với hàm cũ
                $id_lich_chieu = them_lichchieu_return_id($ma_phim, $ngay_chieu, $ma_rap);
                
                if ($id_lich_chieu) {
                    $ke_hoach_ids[] = $id_lich_chieu;
                    
                    // Cập nhật mã kế hoạch và thông tin người tạo
                    pdo_execute("UPDATE lichchieu SET ma_ke_hoach = ?, ghi_chu = ?, nguoi_tao = ? WHERE id = ?", 
                               $ma_ke_hoach, $ghi_chu, $nguoi_tao, $id_lich_chieu);
                    
                    // Tạo các khung giờ chiếu
                    for ($i = 0; $i < count($gio_bat_dau); $i++) {
                        if (!empty($gio_bat_dau[$i]) && !empty($ma_phong[$i])) {
                            $id_phong = (int)$ma_phong[$i];
                            
                            // Kiểm tra phòng có thuộc rạp hiện tại không
                            $phong_check = pdo_query_one("SELECT id FROM phongchieu WHERE id = ? AND id_rap = ?", 
                                                        $id_phong, $ma_rap);
                            
                            if ($phong_check) {
                                // Chỉ tạo khung giờ nếu phòng thuộc rạp hiện tại
                                them_kgc($id_lich_chieu, $id_phong, $gio_bat_dau[$i]);
                            }
                            // Nếu phòng không thuộc rạp, bỏ qua (có thể log warning)
                        }
                    }
                }
                
                $start_date->add(new DateInterval('P1D'));
            }
            
            // Redirect thành công với mã kế hoạch
            $so_luong = count($ke_hoach_ids);
            header("Location: index.php?act=kehoach&msg=success&ke_hoach=" . urlencode($ma_ke_hoach));
            exit;
            
        } catch (Exception $e) {
            header("Location: index.php?act=kehoach&msg=error&error=" . urlencode($e->getMessage()));
            exit;
        }
    }
    
    include "./view/home/header.php";

    if (isset($_GET['act']) && ($_GET['act'] != "")) {
        $act = $_GET['act'];
        $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
        if (!allowed_act($act, $currentRole)) {
            include "./view/home/403.php";
        } else {
        switch ($act) {
            case "cauhinh":
                $cfg = website_get();
                if (isset($_POST['luu'])) {
                    $ten = trim($_POST['ten_website'] ?? '');
                    $diachi = trim($_POST['dia_chi'] ?? '');
                    $sdt = trim($_POST['so_dien_thoai'] ?? '');
                    $email = trim($_POST['email'] ?? '');
                    $mota = trim($_POST['mo_ta'] ?? '');
                    $fb = trim($_POST['facebook'] ?? '');
                    $ig = trim($_POST['instagram'] ?? '');
                    $yt = trim($_POST['youtube'] ?? '');
                    $logo = null;
                    if (!empty($_FILES['logo']['name'])) {
                        $logo = $_FILES['logo']['name'];
                        $target_dir = "../Trang-nguoi-dung/imgavt/";
                        $target_file = $target_dir . basename($_FILES['logo']['name']);
                        @move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
                    }
                    website_update($ten, $diachi, $sdt, $email, $mota, $fb, $ig, $yt, $logo);
                    $success = "Đã lưu cấu hình";
                    $cfg = website_get();
                }
                include "./view/website/cau_hinh.php";
                break;
            // Nhân viên: lịch làm, xin nghỉ, quét vé
            case "nv_lichlamviec":
                $ym = $_GET['ym'] ?? date('Y-m');
                $llv = llv_list_by_user_month((int)$_SESSION['user1']['id'], $ym);
                include "./view/nhanvien/lichlamviec.php";
                break;
            case "nv_baocao":
                $id_nv = (int)($_SESSION['user1']['id'] ?? 0);
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $today = date('Y-m-d');
                $from = $_GET['from'] ?? date('Y-m-d', strtotime('-30 days'));
                $to   = $_GET['to']   ?? $today;
                $sum = $by_date = $sum_hours = null;
                if ($id_nv && $id_rap) {
                    $sum = ve_sum_by_staff($id_nv, $id_rap, $from, $to);
                    $by_date = ve_stats_by_staff_date_range($id_nv, $id_rap, $from, $to);
                    $ym = $_GET['ym'] ?? date('Y-m');
                    $sum_hours = cc_sum_hours($id_nv, $id_rap, $ym);
                }
                include "./view/nhanvien/baocao.php";
                break;
            case "nv_xeplich":
                $id_nv = (int)($_SESSION['user1']['id'] ?? 0);
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (isset($_POST['nv_builder_submit'])) {
                    $mode = $_POST['mode'] ?? 'day';
                    $added=0; $skip=0;
                    if ($mode === 'day') {
                        $days = isset($_POST['days']) && is_array($_POST['days']) ? $_POST['days'] : [];
                        foreach ($days as $d) {
                            $ngay = trim($d['date'] ?? '');
                            if ($ngay === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$ngay)) continue;
                            $shifts = isset($d['shifts']) && is_array($d['shifts']) ? $d['shifts'] : [];
                            foreach ($shifts as $s) {
                                $bd = trim($s['bd'] ?? ''); $kt = trim($s['kt'] ?? ''); $ca = trim($s['ca'] ?? ''); $ghi = trim($s['ghi'] ?? '');
                                if ($bd!=='' && $kt!==''){
                                    if (!llv_exists($id_nv, $id_rap, $ngay, $bd, $kt)) { llv_insert($id_nv, $id_rap, $ngay, $bd, $kt, ($ca!==''?$ca:null), ($ghi!==''?$ghi:null)); $added++; } else { $skip++; }
                                }
                            }
                        }
                        $success = "Đã tạo $added lịch, bỏ qua $skip (trùng)";
                    } else { // week
                        $fromD = trim($_POST['week_from'] ?? '');
                        $toD = trim($_POST['week_to'] ?? '');
                        if ($fromD && $toD){ $tsF=strtotime($fromD); $tsT=strtotime($toD); if($tsF!==false && $tsT!==false && $tsF>$tsT){ $tmp=$fromD; $fromD=$toD; $toD=$tmp; }}
                        $week = isset($_POST['week']) && is_array($_POST['week']) ? $_POST['week'] : [];
                        $map = [];
                        for($d=1;$d<=7;$d++){
                            if (!empty($week[$d])){
                                foreach ($week[$d] as $s){ $bd=trim($s['bd']??''); $kt=trim($s['kt']??''); $ca=trim($s['ca']??''); $ghi=trim($s['ghi']??''); if($bd!=='' && $kt!==''){ $map[$d][]=['bd'=>$bd,'kt'=>$kt,'ca'=>$ca,'ghi'=>$ghi]; } }
                            }
                        }
                        if ($fromD && $toD && !empty($map)){
                            $start=strtotime($fromD); $end=strtotime($toD);
                            for($t=$start; $t<=$end; $t+=86400){ $dow=(int)date('N',$t); if(!empty($map[$dow])){ $ngay=date('Y-m-d',$t); foreach($map[$dow] as $p){ if(!llv_exists($id_nv, $id_rap, $ngay, $p['bd'], $p['kt'])){ llv_insert($id_nv,$id_rap,$ngay,$p['bd'],$p['kt'],($p['ca']!==''?$p['ca']:null),($p['ghi']!==''?$p['ghi']:null)); $added++; } else { $skip++; } } } }
                            $success = "Đã tạo $added lịch, bỏ qua $skip (trùng)";
                        } else { $error = "Thiếu dữ liệu tuần"; }
                    }
                }
                $llv = llv_list_by_user($id_nv);
                include "./view/nhanvien/xeplich.php";
                break;
            case "xinnghi":
                if (isset($_POST['gui'])) {
                    $id_nv = (int)$_SESSION['user1']['id'];
                    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $tu = $_POST['tu_ngay'] ?? '';
                    $den = $_POST['den_ngay'] ?? '';
                    $ly_do = trim($_POST['ly_do'] ?? '');
                    if (!$id_rap || $tu==='' || $den==='' || $ly_do==='') {
                        $error = "Vui lòng điền đầy đủ và đảm bảo tài khoản thuộc một rạp";
                    } else {
                        np_insert($id_nv, $id_rap, $tu, $den, $ly_do);
                        $success = "Đã gửi yêu cầu nghỉ phép";
                    }
                }
                $dnp_cua_toi = np_list_by_user($_SESSION['user1']['id']);
                include "./view/nhanvien/xinnghi.php";
                break;
            case "scanve":
                if (isset($_POST['kiemtra'])) {
                    $ma = trim($_POST['ma_ve'] ?? '');
                    if ($ma === '') {
                        $err = "Vui lòng nhập mã vé";
                    } else {
                        $ve_chi_tiet = ve_find_by_code($ma);
                        if ($ve_chi_tiet) {
                            if (empty($ve_chi_tiet['check_in_luc'])) {
                                ve_checkin($ve_chi_tiet['id'], $_SESSION['user1']['id']);
                                $msg = "Check-in thành công";
                                $ve_chi_tiet = ve_find_by_code($ma);
                            } else {
                                $msg = "Vé đã được check-in lúc " . $ve_chi_tiet['check_in_luc'];
                            }
                        } else {
                            $err = "Không tìm thấy vé với mã này";
                        }
                    }
                }
                include "./view/nhanvien/scanve.php";
                break;
            case "nv_datve":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { include "./view/home/403.php"; break; }
                // Chỉ hiển thị phim có lịch chiếu tại rạp nhân viên đang thuộc
                $ds_phim = load_phim_by_rap_has_schedule($id_rap);
                // POST datve_confirm đã xử lý ở trên (trước khi include header) để trả JSON thuần
                include "./view/nhanvien/datve.php";
                break;
            case "api_dates":
                header('Content-Type: application/json; charset=utf-8');
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $id_phim = (int)($_GET['id_phim'] ?? 0);
                if (!$id_rap || !$id_phim) { echo json_encode([]); exit; }
                $rows = pdo_query("SELECT id, ngay_chieu FROM lichchieu WHERE id_rap = ? AND id_phim = ? ORDER BY ngay_chieu", $id_rap, $id_phim);
                echo json_encode($rows); exit;
            case "api_times":
                header('Content-Type: application/json; charset=utf-8');
                $id_lc = (int)($_GET['id_lc'] ?? 0);
                if (!$id_lc) { echo json_encode([]); exit; }
                $rows = pdo_query("SELECT id, thoi_gian_chieu FROM khung_gio_chieu WHERE id_lich_chieu = ? ORDER BY thoi_gian_chieu", $id_lc);
                echo json_encode($rows); exit;
            case "api_reserved":
                header('Content-Type: application/json; charset=utf-8');
                $id_lc = (int)($_GET['id_lc'] ?? 0);
                $id_tg = (int)($_GET['id_tg'] ?? 0);
                if (!$id_lc || !$id_tg) { echo json_encode([]); exit; }
                $list = ve_reserved_seats($id_tg, $id_lc);
                echo json_encode($list); exit;
            
            // api_combos đã xử lý ở dòng 300-311 (filter theo id_rap)
            // case "api_combos": - REMOVED duplicate

            // Enhanced calendar với tính năng phân công nhân viên
            case "ql_lichlamviec_calendar":
                // Kiểm tra quyền truy cập (chỉ cho GET request - POST đã xử lý ở đầu file)
                $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                if (!allowed_act('ql_lichlamviec_calendar', $currentRole)) {
                    include "./view/home/403.php"; 
                    break;
                }
                
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { 
                    include "./view/home/403.php"; 
                    break; 
                }
                
                // Load dữ liệu để hiển thị (chỉ GET request)
                $ym = $_GET['ym'] ?? date('Y-m');
                
                // Lấy danh sách lịch làm việc của tháng
                $first = $ym.'-01';
                $last = date('Y-m-t', strtotime($first));
                $llv = llv_list_by_rap($id_rap, $first, $last);
                
                // Tạo màu cho mỗi nhân viên
                $ds_nv = pdo_query("SELECT id, name FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
                $employee_colors = [];
                $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'];
                foreach ($ds_nv as $index => $nv) {
                    $employee_colors[$nv['id']] = $colors[$index % count($colors)];
                }
                
                $rap = rap_one($id_rap);
                include "./view/quanly/lichlamviec_calendar.php";
                break;
                
            case "ql_lichlamviec":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { include "./view/home/403.php"; break; }
                if (isset($_GET['xoa'])) { llv_delete((int)$_GET['xoa']); }
                // Filters
                $period = $_GET['period'] ?? 'month';
                $qname = trim($_GET['q'] ?? '');
                $nvf = isset($_GET['nv']) ? (int)$_GET['nv'] : 0;
                $fromF = $toF = null;
                if ($period === 'week') {
                    $wdate = $_GET['wdate'] ?? date('Y-m-d');
                    $ts = strtotime($wdate);
                    $fromF = date('Y-m-d', strtotime('monday this week', $ts));
                    $toF = date('Y-m-d', strtotime('sunday this week', $ts));
                } elseif ($period === 'year') {
                    $y = (int)($_GET['y'] ?? date('Y'));
                    $fromF = $y.'-01-01';
                    $toF = $y.'-12-31';
                } else { // month (default)
                    $ymF = $_GET['ym'] ?? date('Y-m');
                    $fromF = $ymF.'-01';
                    $toF = date('Y-m-t', strtotime($fromF));
                }
                // Tạo lịch theo builder (theo ngày hoặc theo tuần)
                if (isset($_POST['builder_submit'])) {
                    $id_nv = (int)($_POST['id_nv_build'] ?? 0);
                    $mode = $_POST['mode'] ?? 'day';
                    $added = 0; $skip = 0;
                    if ($id_nv) {
                        if ($mode === 'day') {
                            $days = isset($_POST['days']) && is_array($_POST['days']) ? $_POST['days'] : [];
                            foreach ($days as $d) {
                                $ngay = trim($d['date'] ?? '');
                                if ($ngay === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngay)) continue;
                                $shifts = isset($d['shifts']) && is_array($d['shifts']) ? $d['shifts'] : [];
                                foreach ($shifts as $s) {
                                    $bd = trim($s['bd'] ?? '');
                                    $kt = trim($s['kt'] ?? '');
                                    $ca = trim($s['ca'] ?? '');
                                    $ghi = trim($s['ghi'] ?? '');
                                    if ($bd !== '' && $kt !== '') {
                                        if (!llv_exists($id_nv, $id_rap, $ngay, $bd, $kt)) { llv_insert($id_nv, $id_rap, $ngay, $bd, $kt, ($ca!==''?$ca:null), ($ghi!==''?$ghi:null)); $added++; } else { $skip++; }
                                    }
                                }
                            }
                            $success = "Đã tạo $added lịch, bỏ qua $skip (trùng)";
                        } elseif ($mode === 'week') {
                            $from = trim($_POST['week_from'] ?? '');
                            $to = trim($_POST['week_to'] ?? '');
                            if ($from && $to) { $tsF = strtotime($from); $tsT = strtotime($to); if ($tsF!==false && $tsT!==false && $tsF>$tsT){ $tmp=$from; $from=$to; $to=$tmp; } }
                            $week = isset($_POST['week']) && is_array($_POST['week']) ? $_POST['week'] : [];
                            if ($from && $to && !empty($week)) {
                                $map = [];
                                for ($d=1;$d<=7;$d++){
                                    if (!empty($week[$d]) && is_array($week[$d])) {
                                        foreach ($week[$d] as $s) {
                                            $bd = trim($s['bd'] ?? ''); $kt = trim($s['kt'] ?? ''); $ca = trim($s['ca'] ?? ''); $ghi = trim($s['ghi'] ?? '');
                                            if ($bd!=='' && $kt!=='') { $map[$d][] = ['bd'=>$bd,'kt'=>$kt,'ca'=>$ca,'ghi'=>$ghi]; }
                                        }
                                    }
                                }
                                if (!empty($map)) {
                                    $start = strtotime($from); $end = strtotime($to);
                                    for ($t=$start; $t <= $end; $t+=86400) {
                                        $dow = (int)date('N',$t);
                                        if (!empty($map[$dow])) {
                                            $ngay = date('Y-m-d',$t);
                                            foreach ($map[$dow] as $p) {
                                                if (!llv_exists($id_nv, $id_rap, $ngay, $p['bd'], $p['kt'])) { llv_insert($id_nv, $id_rap, $ngay, $p['bd'], $p['kt'], ($p['ca']!==''?$p['ca']:null), ($p['ghi']!==''?$p['ghi']:null)); $added++; } else { $skip++; }
                                            }
                                        }
                                    }
                                    $success = "Đã tạo $added lịch, bỏ qua $skip (trùng)";
                                } else { $error = "Chưa thêm ca cho ngày trong tuần"; }
                            } else { $error = "Thiếu khoảng ngày áp dụng"; }
                        }
                    } else { $error = "Chưa chọn nhân viên"; }
                }
                // Tạo ca đơn lẻ
                if (isset($_POST['create_single'])) {
                    $id_nv = (int)($_POST['id_nv_single'] ?? 0);
                    $ngay = trim($_POST['ngay_single'] ?? '');
                    $bd = trim($_POST['gio_bd_single'] ?? '');
                    $kt = trim($_POST['gio_kt_single'] ?? '');
                    $ca = trim($_POST['loai_ca_single'] ?? '');
                    $ghi = trim($_POST['ghi_single'] ?? '');
                    $ok = ($id_nv>0 && preg_match('/^\d{4}-\d{2}-\d{2}$/',$ngay) && preg_match('/^\d{2}:\d{2}$/',$bd) && preg_match('/^\d{2}:\d{2}$/',$kt));
                    if ($ok) {
                        $bdm = (int)substr($bd,0,2)*60 + (int)substr($bd,3,2);
                        $ktm = (int)substr($kt,0,2)*60 + (int)substr($kt,3,2);
                        if ($bdm >= $ktm) { $error = "Giờ bắt đầu phải nhỏ hơn giờ kết thúc"; }
                        else {
                            if (!llv_exists($id_nv, $id_rap, $ngay, $bd, $kt)) { llv_insert($id_nv, $id_rap, $ngay, $bd, $kt, ($ca!==''?$ca:null), ($ghi!==''?$ghi:null)); $success = "Đã tạo lịch"; }
                            else { $error = "Lịch trùng với ca đã có"; }
                        }
                    } else { $error = "Thiếu thông tin hoặc sai định dạng"; }
                }
                // Tạo nhiều ca theo template
                if (isset($_POST['bulk_templates'])) {
                    $ids_nv = isset($_POST['ids_nv_bulk']) ? array_map('intval', (array)$_POST['ids_nv_bulk']) : [];
                    $from = trim($_POST['bulk_from'] ?? '');
                    $to = trim($_POST['bulk_to'] ?? '');
                    $tpl = isset($_POST['tpl']) && is_array($_POST['tpl']) ? $_POST['tpl'] : [];
                    if ($from && $to) { $tsF = strtotime($from); $tsT = strtotime($to); if ($tsF!==false && $tsT!==false && $tsF>$tsT){ $tmp=$from; $from=$to; $to=$tmp; } }
                    $templates = [];
                    foreach ($tpl as $t){
                        $bd = trim($t['bd'] ?? ''); $kt = trim($t['kt'] ?? ''); $name = trim($t['name'] ?? '');
                        $days = isset($t['days']) && is_array($t['days']) ? array_map('intval', $t['days']) : [];
                        if ($bd!=='' && $kt!=='' && !empty($days)){
                           $bdm = (int)substr($bd,0,2)*60 + (int)substr($bd,3,2);
                           $ktm = (int)substr($kt,0,2)*60 + (int)substr($kt,3,2);
                           if ($bdm < $ktm) $templates[] = ['bd'=>$bd,'kt'=>$kt,'name'=>$name,'days'=>$days];
                        }
                    }
                    if (!empty($ids_nv) && $from && $to && !empty($templates)) {
                        $start = strtotime($from); $end = strtotime($to);
                        $added=0; $skip=0;
                        foreach ($ids_nv as $id_nv){
                            for ($t=$start; $t <= $end; $t+=86400) {
                                $dow = (int)date('N',$t);
                                $dstr = date('Y-m-d',$t);
                                foreach ($templates as $tp){
                                    if (in_array($dow, $tp['days'], true)){
                                        if (!llv_exists($id_nv, $id_rap, $dstr, $tp['bd'], $tp['kt'])) { llv_insert($id_nv, $id_rap, $dstr, $tp['bd'], $tp['kt'], ($tp['name']!==''?$tp['name']:null), null); $added++; } else { $skip++; }
                                    }
                                }
                            }
                        }
                        $success = "Đã tạo $added lịch, bỏ qua $skip (trùng)";
                    } else { $error = "Thiếu thông tin, chưa chọn nhân viên, hoặc template không hợp lệ"; }
                }
                // Cập nhật lịch (sửa/đổi ca)
                if (isset($_POST['edit_submit'])) {
                    $id = (int)($_POST['edit_id'] ?? 0);
                    $ngay = trim($_POST['edit_ngay'] ?? '');
                    $bd = trim($_POST['edit_bd'] ?? '');
                    $kt = trim($_POST['edit_kt'] ?? '');
                    $ca = trim($_POST['edit_ca'] ?? '');
                    $ghi = trim($_POST['edit_ghi'] ?? '');
                    if ($id>0 && preg_match('/^\d{4}-\d{2}-\d{2}$/',$ngay) && preg_match('/^\d{2}:\d{2}$/',$bd) && preg_match('/^\d{2}:\d{2}$/',$kt)){
                        $bdm = (int)substr($bd,0,2)*60 + (int)substr($bd,3,2);
                        $ktm = (int)substr($kt,0,2)*60 + (int)substr($kt,3,2);
                        if ($bdm < $ktm){ llv_update($id, $ngay, $bd, $kt, ($ca!==''?$ca:null), ($ghi!==''?$ghi:null)); $success = "Đã cập nhật"; }
                        else { $error = "Giờ bắt đầu phải nhỏ hơn giờ kết thúc"; }
                    } else { $error = "Thiếu thông tin chỉnh sửa"; }
                }
                // Dữ liệu render
                $ds_nv = pdo_query("SELECT id, name FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
                $llv = llv_list_by_rap($id_rap, $fromF, $toF, $nvf ?: null);
                if ($qname !== '') { $llv = array_values(array_filter($llv, function($r) use ($qname){ return stripos($r['ten_nv'] ?? '', $qname) !== false; })); }
                $rap = rap_one($id_rap);
                include "./view/quanly/lichlamviec.php";
                break;
            case "ql_duyetnghi":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { include "./view/home/403.php"; break; }
                if (isset($_GET['duyet'])) { np_update_trang_thai((int)$_GET['duyet'], 'Đã duyệt'); $msg = "Đã duyệt"; }
                if (isset($_GET['tuchoi'])) { np_update_trang_thai((int)$_GET['tuchoi'], 'Từ chối'); $msg = "Đã từ chối"; }
                $dnp = np_list_by_rap($id_rap);
                include "./view/quanly/duyetnghi.php";
                break;
            case "chamcong":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $ym = $_GET['ym'] ?? date('Y-m');
                $nv = isset($_GET['nv']) ? (int)$_GET['nv'] : 0;
                
                if (isset($_GET['xoa'])) { 
                    cc_delete((int)$_GET['xoa']); 
                    $success = "Đã xóa bản ghi chấm công";
                }
                
                // Quick check-in
                if (isset($_POST['checkin_now'])) {
                    $id_nv = (int)($_POST['id_nv'] ?? 0);
                    if ($id_nv) {
                        $ngay = date('Y-m-d');
                        $gio_vao = date('H:i:s');
                        $gio_ra = date('H:i:s', strtotime('+8 hours')); // Default 8h shift
                        try {
                            cc_insert($id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, 'Check-in nhanh');
                            $success = "✓ Đã check-in lúc " . date('H:i');
                        } catch (Exception $e) {
                            $error = "✗ Lỗi check-in: " . $e->getMessage();
                        }
                    }
                }
                
                if (isset($_POST['them'])) {
                    $id_nv = (int)($_POST['id_nv'] ?? 0);
                    $ngay = $_POST['ngay'] ?? '';
                    $gio_vao = $_POST['gio_vao'] ?? '';
                    $gio_ra = $_POST['gio_ra'] ?? '';
                    $ghi_chu = $_POST['ghi_chu'] ?? null;
                    
                    if ($id_nv && $ngay && $gio_vao && $gio_ra) {
                        try {
                            cc_insert($id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu);
                            $success = "✓ Đã chấm công thành công";
                        } catch (Exception $e) {
                            $error = "✗ " . $e->getMessage();
                        }
                    } else { 
                        $error = "✗ Thiếu thông tin bắt buộc"; 
                    }
                }
                
                $ds_nv = pdo_query("SELECT id, name FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
                $ds_cc = cc_list_by_rap_month($id_rap, $ym, $nv ?: null);
                
                // Summary
                if ($nv) { 
                    $sum_hours = cc_sum_hours($nv, $id_rap, $ym);
                    $attendance_summary = cc_attendance_summary($nv, $id_rap, $ym);
                    $attendance_detail = cc_compare_with_schedule($nv, $id_rap, $ym);
                } else { 
                    $sum_by_emp = luong_tinh_thang($id_rap, $ym, 1); 
                }
                
                include "./view/quanly/chamcong.php";
                break;
            case "nv_chamcong":
                // Self-service attendance for employees
                $id_nv = (int)($_SESSION['user1']['id'] ?? 0);
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $ym = $_GET['ym'] ?? date('Y-m');
                
                // Handle check-in/check-out actions
                if (isset($_POST['action'])) {
                    try {
                        if ($_POST['action'] === 'checkin') {
                            cc_quick_checkin($id_nv, $id_rap);
                            $success = "✓ Check-in thành công lúc " . date('H:i');
                        } elseif ($_POST['action'] === 'checkout') {
                            cc_quick_checkout($id_nv, $id_rap);
                            $success = "✓ Check-out thành công lúc " . date('H:i');
                        }
                    } catch (Exception $e) {
                        $error = "✗ " . $e->getMessage();
                    }
                }
                
                // Get today's status
                $today_status = cc_check_today_status($id_nv, $id_rap);
                
                // Debug: Log status to check
                error_log("DEBUG nv_chamcong: id_nv=$id_nv, id_rap=$id_rap, status=" . json_encode($today_status));
                
                // Get history for current month
                $history = cc_my_history($id_nv, $id_rap, $ym);
                
                // Calculate total hours and simple stats
                $total_hours = 0;
                $late_count = 0;
                foreach ($history as $h) {
                    $diff = strtotime($h['gio_ra']) - strtotime($h['gio_vao']);
                    $total_hours += $diff / 3600;
                    
                    // Simple late check: if check-in after 8:30 AM
                    $checkin_time = date('H:i', strtotime($h['gio_vao']));
                    if ($checkin_time > '08:30') {
                        $late_count++;
                    }
                }
                
                // Create simple summary (without schedule comparison)
                $summary = [
                    'late_count' => $late_count,
                    'total_days' => count($history),
                    'total_hours' => $total_hours
                ];
                
                include "./view/nhanvien/chamcong_self.php";
                break;
            case "bangluong":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $id_user = (int)($_SESSION['user1']['id'] ?? 0);
                $ym = $_GET['ym'] ?? date('Y-m');
                $rate = isset($_GET['rate']) ? (int)$_GET['rate'] : 30000;
                
                // Xử lý các action
                if (isset($_POST['action'])) {
                    $action = $_POST['action'];
                    
                    // Tính lương trước khi thực hiện action
                    $ds_luong = luong_tinh_thang($id_rap, $ym, $rate);
                    
                    try {
                        switch ($action) {
                            case 'save':
                                bl_save($id_rap, $ym, $ds_luong, $id_user);
                                $success = "✓ Đã lưu bảng lương tháng " . date('m/Y', strtotime($ym.'-01'));
                                break;
                            
                            case 'send_approval':
                                bl_update_status($id_rap, $ym, 'cho_duyet', null);
                                $success = "✓ Đã gửi bảng lương lên cấp trên duyệt";
                                break;
                            
                            case 'approve':
                                bl_update_status($id_rap, $ym, 'da_duyet', $id_user);
                                $success = "✓ Đã duyệt bảng lương";
                                break;
                            
                            case 'mark_paid':
                                bl_update_status($id_rap, $ym, 'da_thanh_toan', $id_user);
                                $success = "✓ Đã đánh dấu đã thanh toán";
                                break;
                        }
                    } catch (Exception $e) {
                        $error = "✗ Lỗi: " . $e->getMessage();
                    }
                }
                
                // Tính lương
                $ds_luong = luong_tinh_thang($id_rap, $ym, $rate);
                
                // Kiểm tra đã lưu chưa
                $is_saved = bl_is_saved($id_rap, $ym);
                $saved_status = 'nhap';
                if ($is_saved) {
                    $saved = pdo_query_one("SELECT trang_thai FROM bang_luong WHERE id_rap = ? AND thang = ? LIMIT 1", $id_rap, $ym);
                    $saved_status = $saved['trang_thai'] ?? 'nhap';
                }
                
                include "./view/quanly/bangluong.php";
                break;
            case "thietbiphong":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (!$id_rap) { include "./view/home/403.php"; break; }
                $ds_phong = load_phong_by_rap($id_rap);
                $id_phong = isset($_GET['id_phong']) ? (int)$_GET['id_phong'] : 0;
                if ($id_phong) {
                    if (isset($_GET['xoa'])) { tb_delete((int)$_GET['xoa']); $success = "Đã xóa"; }
                    if (isset($_POST['them'])) {
                        $ten = trim($_POST['ten'] ?? '');
                        $so_luong = (int)($_POST['so_luong'] ?? 1);
                        $tt = $_POST['tinh_trang'] ?? 'tot';
                        $gc = $_POST['ghi_chu'] ?? null;
                        if ($ten !== '') { tb_insert($id_phong, $ten, $so_luong, $tt, $gc); $success = "Đã thêm"; } else { $error = "Thiếu tên"; }
                    }
                    $ds_tb = tb_list_by_phong($id_phong);
                }
                include "./view/quanly/thietbiphong.php";
                break;
            // Quản lý rạp chiếu (cụm)
            case "QLrap":
                $ds_rap = rap_all();
                include "./view/rap/QLrap.php";
                break;
            case "themrp":
                if (isset($_POST['luu'])) {
                    $ten_rap = trim($_POST['ten_rap'] ?? '');
                    $dia_chi = trim($_POST['dia_chi'] ?? '');
                    $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
                    $email = trim($_POST['email'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    $trang_thai = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;
                    $logo = null;
                    if (!empty($_FILES['logo']['name'])) {
                        $logo = $_FILES['logo']['name'];
                        $target_dir = "../Trang-nguoi-dung/imgavt/";
                        $target_file = $target_dir . basename($_FILES['logo']['name']);
                        @move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
                    }
                    if ($ten_rap === '' || $dia_chi === '' || $so_dien_thoai === '' || $email === '') {
                        $error = "Vui lòng điền đầy đủ thông tin";
                    } else {
                        rap_insert($ten_rap, $dia_chi, $so_dien_thoai, $email, $mo_ta, $logo, $trang_thai);
                        $success = "Thêm rạp thành công";
                    }
                }
                include "./view/rap/them.php";
                break;
            case "suarp":
                $id = (int)($_GET['id'] ?? 0);
                $rp = rap_one($id);
                if (isset($_POST['capnhat'])) {
                    $ten_rap = trim($_POST['ten_rap'] ?? '');
                    $dia_chi = trim($_POST['dia_chi'] ?? '');
                    $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
                    $email = trim($_POST['email'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    $trang_thai = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;
                    $logo = $rp['logo'] ?? null;
                    if (!empty($_FILES['logo']['name'])) {
                        $logo = $_FILES['logo']['name'];
                        $target_dir = "../Trang-nguoi-dung/imgavt/";
                        $target_file = $target_dir . basename($_FILES['logo']['name']);
                        @move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
                    }
                    if ($ten_rap === '' || $dia_chi === '' || $so_dien_thoai === '' || $email === '') {
                        $error = "Vui lòng điền đầy đủ thông tin";
                    } else {
                        rap_update($id, $ten_rap, $dia_chi, $so_dien_thoai, $email, $mo_ta, $logo, $trang_thai);
                        $success = "Cập nhật thành công";
                        $rp = rap_one($id);
                    }
                }
                include "./view/rap/sua.php";
                break;
            // (removed) sodoghe: quản lý sơ đồ ghế tách riêng đã được tích hợp vào 'suaphong'
            case "xoarp":
                $id = (int)($_GET['id'] ?? 0);
                if ($id > 0) { rap_delete($id); }
                $ds_rap = rap_all();
                include "./view/rap/QLrap.php";
                break;
            case "duyet_lichchieu":
                // Xử lý duyệt lịch chiếu
                if (isset($_GET['duyet'])) { 
                    lc_duyet((int)$_GET['duyet'], 'Đã duyệt'); 
                    $msg = "Đã duyệt lịch chiếu"; 
                }
                if (isset($_GET['tuchoi'])) { 
                    lc_duyet((int)$_GET['tuchoi'], 'Từ chối'); 
                    $msg = "Đã từ chối lịch chiếu"; 
                }
                
                // Xử lý duyệt toàn bộ kế hoạch theo mã kế hoạch
                if (isset($_GET['duyet_kehoach'])) {
                    $ma_ke_hoach = $_GET['ma_ke_hoach'] ?? '';
                    $action = $_GET['action'] ?? ''; // 'duyet' hoặc 'tu_choi'
                    
                    if ($ma_ke_hoach && in_array($action, ['duyet', 'tu_choi'])) {
                        $trang_thai = ($action === 'duyet') ? 'Đã duyệt' : 'Từ chối';
                        
                        // Duyệt tất cả lịch chiếu trong kế hoạch này
                        $sql = "UPDATE lichchieu SET trang_thai_duyet = ? WHERE ma_ke_hoach = ?";
                        pdo_execute($sql, $trang_thai, $ma_ke_hoach);
                        
                        $msg = ($action === 'duyet') ? "Đã duyệt toàn bộ kế hoạch chiếu" : "Đã từ chối toàn bộ kế hoạch chiếu";
                    }
                }
                
                $filter = $_GET['filter'] ?? 'cho_duyet';
                
                // Lấy danh sách lịch chiếu nhóm theo mã kế hoạch
                $ds_lich = lc_list_grouped_for_approval($filter);
                
                include "./view/cum/duyet_lich.php";
                break;
            case "chi_tiet_kehoach":
                $ma_ke_hoach = $_GET['ma'] ?? '';
                $id_cum = $_SESSION['user1']['id_cum'] ?? null;
                if ($ma_ke_hoach) {
                    $chi_tiet = ke_hoach_chi_tiet($ma_ke_hoach, $id_cum);
                    include "./view/cum/chi_tiet_kehoach.php";
                } else {
                    $msg = "Không tìm thấy mã kế hoạch";
                    include "./view/cum/duyet_lich.php";
                }
                break;
            case "ajax_chi_tiet_kehoach":
                header('Content-Type: application/json; charset=utf-8');
                $ma_ke_hoach = $_GET['ma'] ?? '';
                $id_cum = $_SESSION['user1']['id_cum'] ?? null;
                if ($ma_ke_hoach) {
                    $chi_tiet = ke_hoach_chi_tiet($ma_ke_hoach, $id_cum);
                    echo json_encode([
                        'success' => true,
                        'data' => $chi_tiet
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Không tìm thấy mã kế hoạch'
                    ]);
                }
                exit;
            case "ajax_chi_tiet_kehoach_new":
                $ma_ke_hoach = $_GET['ma'] ?? '';
                
                if ($ma_ke_hoach) {
                    // Lấy chi tiết các lịch chiếu theo mã kế hoạch
                    $sql = "SELECT 
                                lc.id,
                                lc.ngay_chieu,
                                lc.trang_thai_duyet,
                                p.tieu_de as ten_phim,
                                p.thoi_luong_phim,
                                p.img,
                                lp.name as ten_loai,
                                r.ten_rap,
                                GROUP_CONCAT(
                                    CONCAT(kgc.thoi_gian_chieu, ' (', ph.name, ')') 
                                    ORDER BY kgc.thoi_gian_chieu 
                                    SEPARATOR ', '
                                ) as khung_gio
                            FROM lichchieu lc
                            LEFT JOIN phim p ON lc.id_phim = p.id
                            LEFT JOIN loaiphim lp ON p.id_loai = lp.id
                            LEFT JOIN rap_chieu r ON lc.id_rap = r.id
                            LEFT JOIN khung_gio_chieu kgc ON lc.id = kgc.id_lich_chieu
                            LEFT JOIN phongchieu ph ON kgc.id_phong = ph.id
                            WHERE lc.ma_ke_hoach = ?
                            GROUP BY lc.id
                            ORDER BY lc.ngay_chieu";
                    
                    $chi_tiet = pdo_query($sql, $ma_ke_hoach);
                    include "./view/cum/chi_tiet_kehoach_modal.php";
                } else {
                    echo '<div class="alert alert-danger">Thông tin không hợp lệ!</div>';
                }
                exit;
            case "lich_rap":
                // If manager of a cinema: restrict to own cinema
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 3) {
                    $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $ds_rap = $myRap ? [rap_one($myRap)] : rap_all();
                } else {
                    $ds_rap = rap_all();
                }
                $id_rap = isset($_GET['id_rap']) ? (int)$_GET['id_rap'] : 0;
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 3 && !$id_rap) {
                    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                }
                $view = $_GET['view'] ?? 'day';
                $date = $_GET['date'] ?? date('Y-m-d');
                $from_date = $date;
                $to_date = $date;
                if ($view === 'week') {
                    $ts = strtotime($date);
                    $monday = date('Y-m-d', strtotime('monday this week', $ts));
                    $sunday = date('Y-m-d', strtotime('sunday this week', $ts));
                    $from_date = $monday;
                    $to_date = $sunday;
                }
                if ($id_rap) {
                    $ds_lich = lc_list_by_rap_and_date_range($id_rap, $from_date, $to_date);
                }
                include "./view/cum/lich_rap.php";
                break;
            case "phanphim":
                $ds_rap = rap_all();
                $id_rap = isset($_GET['id_rap']) ? (int)$_GET['id_rap'] : 0;
                // Áp dụng cho nhiều rạp cùng lúc (bổ sung)
                if (isset($_POST['apply_multi']) && $_POST['apply_multi'] == '1') {
                    $rap_ids = isset($_POST['ids_rap']) ? array_map('intval', (array)$_POST['ids_rap']) : [];
                    $ids_phim = isset($_POST['ids_phim']) ? array_map('intval', (array)$_POST['ids_phim']) : [];
                    $replace = !empty($_POST['replace']);
                    if (!empty($rap_ids) && !empty($ids_phim)) {
                        pr_assign_to_raps($rap_ids, $ids_phim, $replace);
                        $success_multi = $replace ? "Đã thay thế danh sách phim cho các rạp đã chọn" : "Đã phân phối (bổ sung) cho các rạp đã chọn";
                    } else {
                        $error_multi = "Vui lòng chọn ít nhất 1 rạp và 1 phim";
                    }
                }
                // Chế độ 1 rạp như cũ
                if ($id_rap) {
                    if (isset($_POST['luu'])) {
                        $ids = isset($_POST['ids_phim']) ? array_map('intval', $_POST['ids_phim']) : [];
                        pr_clear_and_assign($id_rap, $ids);
                        $success = "Đã lưu phân phối";
                    }
                    $ds_phim_phan = pr_list_by_rap($id_rap);
                }
                // Luôn cần danh sách phim để dùng cho phân phối nhiều rạp
                $ds_phim_all = loadall_phim();
                include "./view/cum/phanphim.php";
                break;
            case "QLloaiphim": //QL loại phim
                include "./view/loaiphim/QLloaiphim.php";
                break;
                case "themloai":
                    if (isset($_POST['gui'])) {

                      if(!isset($_POST['name']) || empty($_POST['name'])) {
                        $error = "Tên thể loại không được để trống";

                      }

                      if(!isset($error)){
                        $name = $_POST['name'];
                        them_loaiphim($name); 
                        $suatc = "THÊM THÀNH CÔNG";

                      }
                  
                    }
                    include "./view/loaiphim/them.php"; 
                    break;
                case "sualoai":
                    if (isset($_GET['idsua'])) {
                        $loadone_loai = loadone_loaiphim($_GET['idsua']);
                    }
                    include "./view/loaiphim/sua.php";
                    break;
                case "xoaloai":
                    if (isset($_GET['idxoa'])) {
                        xoa_loaiphim($_GET['idxoa']);
                        $loadloai = loadall_loaiphim();
                        include "./view/loaiphim/QLloaiphim.php";
                    } else {
                        include "./view/loaiphim/QLloaiphim.php";
                    }
                    break;
                case "updateloai":
                    if (isset($_POST['capnhat'])) {
                            $id = $_POST['id'];
                            $name = $_POST['name'];
                    if($name == ''){
                    $error ="vui lòng điền đầy đủ thông tin";
                    $loadone_loai = loadone_loaiphim($id);
                    include "./view/loaiphim/sua.php";
                    break;
                    }else{
                            update_loaiphim($id, $name);
                            $suatc = "SỬA THÀNH CÔNG";

                    }
                    }
                    $loadloai = loadall_loaiphim();
                    
                        include "./view/loaiphim/QLloaiphim.php";
                    break;
             /////////////////////////Thêm xóa sửa phim       
            case "QLphim":
                if(isset($_POST['tk1'])&&($_POST['tk1'])){
                    $searchName1 = $_POST['ten'];
                    $searchLoai = $_POST['loai'];
                }else{
                    $searchName1 ="";
                    $searchLoai="";
                }
                $loadphim = loadall_phim($searchName1,$searchLoai);
                include "./view/phim/QLphim.php";
                break;
                case "themphim":
                if (isset($_POST['gui'])) {
                    $tieu_de = $_POST['tieu_de'];
                    $daodien = $_POST['daodien'];
                    $dienvien = $_POST['dienvien'];
                    $quoc_gia = $_POST['quoc_gia'];
                    $gia_han_tuoi = $_POST['gia_han_tuoi'];
                    $thoiluong = $_POST['thoiluong'];
                    $date = $_POST['date'];
                    $link = $_POST['link'];
                    $id_loai = $_POST['id_loai'];
                    $mo_ta = $_POST['mo_ta'];
                    $img = $_FILES['anh']['name'];
                    $target_dir = "../Trang-nguoi-dung/imgavt/";
                    $target_file = $target_dir . basename($_FILES['anh']['name']);
                    if (move_uploaded_file($_FILES['anh']['tmp_name'], $target_file)) {
                        echo "Bạn đã upload ảnh thành công";
                    } else {
                        echo "Upload ảnh không thành công";
                    }
                    if( $tieu_de =='' || $daodien =='' || $dienvien==''|| $quoc_gia==''|| $gia_han_tuoi==''|| $img==''|| $mo_ta==''|| $thoiluong==''|| $date==''|| $id_loai==''){
                        $error =  "Vui  lòng không để chống";

                            include "./view/phim/them.php";
                        break;
                        }else{
                    them_phim($tieu_de, $daodien, $dienvien, $img, $mo_ta, $thoiluong, $quoc_gia, $gia_han_tuoi, $date, $id_loai,$link);
                        $suatc = "Thêm thành công";
                    }
                    }
                    $loadphim = loadall_phim();
                    include "./view/phim/them.php";
                    break;
                case "xoaphim":
                    if (isset($_GET['idxoa'])) {
                        xoa_phim($_GET['idxoa']);
                        $loadphim = loadall_phim();
                        include "./view/phim/QLphim.php";
                    }
                    break;
                case "thuhoi_phim":
                    if (isset($_GET['id'])) {
                        $id_phim = (int)$_GET['id'];
                        if ($id_phim > 0) {
                            pr_remove_film_all($id_phim);
                            $suatc = "Đã thu hồi phim khỏi tất cả rạp (phân phối)";
                        }
                    }
                    $loadphim = loadall_phim();
                    include "./view/phim/QLphim.php";
                    break;
                case "suaphim":
                    if (isset($_GET['idsua'])) {
                        $loadone_phim = loadone_phim($_GET['idsua']);
                    }
                    include "./view/phim/sua.php";
                    break;
                case "updatephim":
                    if (isset($_POST['capnhat'])) {
                        $id = $_POST['id'];
                        $tieu_de = $_POST['tieu_de'];
                        $daodien = $_POST['daodien'];
                        $dienvien = $_POST['dienvien'];
                        $quoc_gia = $_POST['quoc_gia'];
                        $gia_han_tuoi = $_POST['gia_han_tuoi'];
                        $thoi_luong = $_POST['thoiluong'];
                        $date = $_POST['date'];
                        $id_loai = $_POST['id_loai'];
                        $mo_ta = $_POST['mo_ta'];

                        // Xử lý ảnh
                        $img = $_FILES['anh']['name'];
                        $target_dir = "../Trang-nguoi-dung/imgavt/";
                        $target_file = $target_dir . basename($_FILES['anh']['name']);

                        if (move_uploaded_file($_FILES['anh']['tmp_name'], $target_file)) {
                            echo "Bạn đã upload ảnh thành công";
                        } else {
                            echo "Upload ảnh không thành công";
                        }

                        // Kiểm tra dữ liệu
                        if ($tieu_de == '' || $daodien == '' || $dienvien == '' || $quoc_gia == '' || $gia_han_tuoi == ''  || $mo_ta == '' || $thoi_luong == '' || $date == '' || $id_loai == '') {
                            $error = "Vui lòng không để trống";

                                $loadone_phim = loadone_phim($id);

                            include "./view/phim/sua.php";
                            break;
                        } else {
                            sua_phim($id, $tieu_de, $img, $mo_ta, $thoi_luong, $date, $id_loai);
                            $suatc = "Cập nhật thành công";
                        }
                    }
                    $loadphim = loadall_phim();
                    include "./view/phim/QLphim.php";
                    break;
            ////////////////////////////////////////////////////////
            case "sualichchieu": //Thêm xóa sửa lịch chiếu
                if (isset($_GET['idsua'])) {
                    $loadone_lc = loadone_lichchieu($_GET['idsua']);
                }
                include "./view/suatchieu/sua.php";
                break;
            case "themlichchieu":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                // Chỉ cho phép chọn phim đã được phân phối cho rạp này
                $loadphim = $id_rap ? load_phim_by_phanphoi($id_rap) : loadall_phim();
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                if (isset($_POST['them'])) {
                    $id_phim = $_POST['id_phim'];
                    $ngay_chieu = $_POST['nc'];
                    if($id_phim ==''||$ngay_chieu ==''|| !$id_rap) {
                        $error = "vui lòng không để trống";
                        $loadone_lc = loadone_lichchieu($id);
                        include "./view/suatchieu/them.php";
                        break;
                    }else{
                    them_lichchieu($id_phim,$ngay_chieu,$id_rap);
                        $suatc = "Thêm thành công";
                }
                        }
                $loadlich = $id_rap ? loadall_lichchieu_by_rap($id_rap) : loadall_lichchieu();
                include "./view/suatchieu/them.php";
                break;
            
            case "updatelichchieu":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                // Chỉ hiển thị phim đã phân phối cho rạp
                $loadphim = $id_rap ? load_phim_by_phanphoi($id_rap) : loadall_phim();
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                if(isset($_POST['capnhat'])) {              
         
                        $id = $_POST['id'];
                        $id_phim = $_POST['id_phim'];   
                        $ngay_chieu = $_POST['nc'];
                        if($id ==''||$id_phim ==''||$ngay_chieu =='' || !$id_rap) {
                          $error = "vui lòng không để trống";
                           $loadone_lc = loadone_lichchieu($id);
                          include "./view/suatchieu/sua.php";
                          break;
                         }else{
                        sua_lichchieu($id, $id_phim,  $ngay_chieu, $id_rap);
                        $suatc = "SỬA THÀNH CÔNG";

                    }
                  
                  }
                  $loadlich = $id_rap ? loadall_lichchieu_by_rap($id_rap) : loadall_lichchieu();
                  include "./view/suatchieu/QLsuatchieu.php";
                  break;
            case "QLcarou":
                include "./view/phim/sua.php";
                break;
          
            case "khachhang":
                include "./view/user/khachhang.php";
                break;
            ////////////////Doanh thu theo đơn hàng, tháng, tuần, ngày, theo từng phim    
            case "DTdh":
                $dt = load_thongke_doanhthu();

                include "./view/danhthu/DTdh.php";
                break;
            case "DTthang":
                $dtt =  load_doanhthu_thang1();
                $dtt1 =  load_doanhthu_thang();
                include "./view/danhthu/DTthang.php";
                break;
            case "DTtuan":
                $dtt =  load_doanhthu_tuan1();
                $dtt1 =  load_doanhthu_tuan();
                include "./view/danhthu/DTtuan.php";
                break;
            case "DTngay":
                $dtt =  load_doanhthu_ngay1();
                $dtt1 =  load_doanhthu_ngay();
                include "./view/danhthu/DTngay.php";
                break;
            case "TKrap":
                $period = $_GET['period'] ?? '';
                $from = $_GET['from'] ?? '';
                $to = $_GET['to'] ?? '';
                if ($period === 'week') {
                    $from = date('Y-m-d', strtotime('monday this week'));
                    $to = date('Y-m-d', strtotime('sunday this week'));
                } elseif ($period === 'month') {
                    $from = date('Y-m-01');
                    $to = date('Y-m-t');
                } elseif ($period === 'quarter') {
                    $month = (int)date('n');
                    $q = (int)ceil($month/3);
                    $firstMonth = ($q-1)*3 + 1;
                    $from = date('Y-') . str_pad($firstMonth,2,'0',STR_PAD_LEFT) . '-01';
                    $to = date('Y-m-t', strtotime($from . ' +2 months'));
                }
                if (!empty($from) || !empty($to)) {
                    $tk_rap = doanhthu_theo_rap_khoang($from ?: null, $to ?: null);
                } else { $tk_rap = doanhthu_theo_rap(); }
                // Nếu là quản lý rạp: chỉ hiển thị rạp của mình
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 3) {
                    $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $tk_rap = array_values(array_filter($tk_rap, function($r) use($myRap){ return (int)($r['id'] ?? 0) === $myRap; }));
                }
                include "./view/cum/thongke_rap.php";
                break;
            case "DTphim":
                include "./view/danhthu/DTphim.php";
                break;
            case "DTphim_rap":
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 3) {
                    $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $ds_rap = $myRap ? [rap_one($myRap)] : rap_all();
                } else { $ds_rap = rap_all(); }
                $id_rap = isset($_GET['id_rap']) ? (int)$_GET['id_rap'] : 0;
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 3) { $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0); }
                $period = $_GET['period'] ?? '';
                $from = $_GET['from'] ?? '';
                $to = $_GET['to'] ?? '';
                if ($period === 'week') { $from = date('Y-m-d', strtotime('monday this week')); $to = date('Y-m-d', strtotime('sunday this week')); }
                elseif ($period === 'month') { $from = date('Y-m-01'); $to = date('Y-m-t'); }
                elseif ($period === 'quarter') { $month = (int)date('n'); $q = (int)ceil($month/3); $firstMonth = ($q-1)*3+1; $from = date('Y-').str_pad($firstMonth,2,'0',STR_PAD_LEFT).'-01'; $to = date('Y-m-t', strtotime($from.' +2 months')); }
                if ($id_rap > 0) {
                    $dt_phim_rap = doanhthu_phim_theo_rap($id_rap, $from ?: null, $to ?: null);
                } else {
                    $dt_phim_rap = doanhthu_phim_toan_he_thong($from ?: null, $to ?: null);
                }
                include "./view/cum/dtphim_rap.php";
                break;
            case "hieusuat_rap":
                $period = $_GET['period'] ?? '';
                $from = $_GET['from'] ?? '';
                $to = $_GET['to'] ?? '';
                if ($period === 'week') { $from = date('Y-m-d', strtotime('monday this week')); $to = date('Y-m-d', strtotime('sunday this week')); }
                elseif ($period === 'month') { $from = date('Y-m-01'); $to = date('Y-m-t'); }
                elseif ($period === 'quarter') { $month = (int)date('n'); $q = (int)ceil($month/3); $firstMonth = ($q-1)*3+1; $from = date('Y-').str_pad($firstMonth,2,'0',STR_PAD_LEFT).'-01'; $to = date('Y-m-t', strtotime($from.' +2 months')); }
                $hs_rap = hieusuat_rap_khoang($from ?: null, $to ?: null);
                include "./view/cum/hieusuat_rap.php";
                break;
            /////////////////////////////////////
            case "timeline":
                include "./view/voucher/timeline.php";
                break;
            case "chitiethoadon":
                include "./view/vephim/chitiethoadon.php";
                break;

            case "QLfeed":
                    $listbl =  loadall_bl();
                    $tong = count($listbl);
                    $loadtk = loadall_taikhoan();
                    $loadloai = loadall_loaiphim();
                    include "./view/feedblack/QLfeed.php";
                         break;
            // Combo / Khuyến mãi
            case "QLcombo":
                // Kiểm tra role: Quản lý rạp chỉ xem combo của rạp mình
                $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                
                if ($currentRole === 3 && $id_rap_user > 0) {
                    // Quản lý rạp: chỉ xem combo của rạp mình
                    $ds_combo = combo_all_by_rap($id_rap_user);
                } else {
                    // Admin: xem tất cả combo
                    $ds_combo = combo_all();
                }
                
                include "./view/combo/QLcombo.php";
                break;
            case "combo_them":
                if (isset($_POST['luu'])) {
                    $ten = trim($_POST['ten'] ?? '');
                    $gia = (int)($_POST['gia'] ?? 0);
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $hinh = null;
                    if (!empty($_FILES['hinh']['name'])) {
                        $hinh = $_FILES['hinh']['name'];
                        @move_uploaded_file($_FILES['hinh']['tmp_name'], "assets/images/".basename($hinh));
                    }
                    
                    // Tự động gán id_rap nếu là quản lý rạp
                    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                    $id_rap_insert = null;
                    if ($currentRole === 3) {
                        $id_rap_insert = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    }
                    
                    if ($ten==='') { 
                        $error = "Tên không được trống"; 
                    } else { 
                        combo_insert($ten, $gia, $hinh, $mo_ta, $trang_thai, $id_rap_insert); 
                        $success = "Đã thêm"; 
                    }
                }
                include "./view/combo/them.php";
                break;
            case "combo_sua":
                $id = (int)($_GET['id'] ?? 0);
                $row = combo_one($id);
                
                // Kiểm tra quyền: Quản lý rạp chỉ sửa combo của rạp mình
                $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                
                if ($currentRole === 3) {
                    // Quản lý rạp: chỉ sửa combo của rạp mình hoặc combo chung (id_rap IS NULL)
                    $combo_id_rap = (int)($row['id_rap'] ?? 0);
                    if ($combo_id_rap > 0 && $combo_id_rap !== $id_rap_user) {
                        // Combo thuộc rạp khác
                        include "./view/home/403.php";
                        break;
                    }
                }
                
                if (isset($_POST['capnhat'])) {
                    $ten = trim($_POST['ten'] ?? '');
                    $gia = (int)($_POST['gia'] ?? 0);
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $hinh = null;
                    if (!empty($_FILES['hinh']['name'])) { 
                        $hinh = $_FILES['hinh']['name']; 
                        @move_uploaded_file($_FILES['hinh']['tmp_name'], "assets/images/".basename($hinh)); 
                    }
                    
                    // Tự động gán id_rap nếu là quản lý rạp
                    $id_rap_update = null;
                    if ($currentRole === 3) {
                        $id_rap_update = $id_rap_user;
                    }
                    
                    combo_update($id, $ten, $gia, $hinh, $mo_ta, $trang_thai, $id_rap_update);
                    $success = "Đã cập nhật"; 
                    $row = combo_one($id);
                }
                include "./view/combo/sua.php";
                break;
            case "combo_xoa":
                if (isset($_GET['id'])) { 
                    $combo_id = (int)$_GET['id'];
                    $combo_row = combo_one($combo_id);
                    
                    // Kiểm tra quyền: Quản lý rạp chỉ xóa combo của rạp mình
                    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                    $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    
                    $can_delete = true;
                    if ($currentRole === 3 && $combo_row) {
                        $combo_id_rap = (int)($combo_row['id_rap'] ?? 0);
                        if ($combo_id_rap > 0 && $combo_id_rap !== $id_rap_user) {
                            $can_delete = false;
                        }
                    }
                    
                    if ($can_delete) {
                        combo_delete($combo_id);
                    }
                }
                
                // Reload danh sách theo role
                $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if ($currentRole === 3 && $id_rap_user > 0) {
                    $ds_combo = combo_all_by_rap($id_rap_user);
                } else {
                    $ds_combo = combo_all();
                }
                
                include "./view/combo/QLcombo.php";
                break;
            case "combo_toggle":
                if (isset($_GET['id'])) { 
                    $combo_id = (int)$_GET['id'];
                    $combo_row = combo_one($combo_id);
                    
                    // Kiểm tra quyền: Quản lý rạp chỉ toggle combo của rạp mình
                    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                    $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    
                    $can_toggle = true;
                    if ($currentRole === 3 && $combo_row) {
                        $combo_id_rap = (int)($combo_row['id_rap'] ?? 0);
                        if ($combo_id_rap > 0 && $combo_id_rap !== $id_rap_user) {
                            $can_toggle = false;
                        }
                    }
                    
                    if ($can_toggle) {
                        combo_toggle($combo_id);
                    }
                }
                
                // Reload danh sách theo role
                $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                $id_rap_user = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if ($currentRole === 3 && $id_rap_user > 0) {
                    $ds_combo = combo_all_by_rap($id_rap_user);
                } else {
                    $ds_combo = combo_all();
                }
                
                include "./view/combo/QLcombo.php";
                break;
            // Khuyến mãi (mã giảm giá)
            case "QLkm":
                // DB hiện tại không gán mã theo rạp; hiển thị toàn bộ
                $ds_km = km_all();
                include "./view/khuyenmai/QLkm.php";
                break;
            case "km_them":
                if (isset($_POST['luu'])) {
                    $ten = trim($_POST['ten_khuyen_mai'] ?? '');
                    $ma_code = trim($_POST['ma_khuyen_mai'] ?? '');
                    $loai_giam = $_POST['loai_giam'] ?? 'phan_tram';
                    $phan_tram_giam = (float)($_POST['phan_tram_giam'] ?? 0);
                    $gia_tri_giam = (int)($_POST['gia_tri_giam'] ?? 0);
                    $bat_dau = $_POST['ngay_bat_dau'] ?? null;
                    $ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $dieu_kien = trim($_POST['dieu_kien_ap_dung'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    
                    // Tự động gán id_rap cho quản lý rạp
                    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                    $id_rap_km = null;
                    if ($currentRole === 3) {
                        $id_rap_km = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    }
                    
                    if ($ten==='') { 
                        $error = 'Tên khuyến mãi không được trống'; 
                    } elseif ($ma_code==='') { 
                        $error = 'Mã khuyến mãi không được trống'; 
                    } else { 
                        km_insert($ten, $ma_code, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau ?: null, $ket_thuc ?: null, $trang_thai, $dieu_kien, $mo_ta, $id_rap_km); 
                        $success='Đã thêm'; 
                    }
                }
                include "./view/khuyenmai/them.php";
                break;
            case "km_sua":
                $id = (int)($_GET['id'] ?? 0);
                $row = km_one($id);
                if (isset($_POST['capnhat'])) {
                    $ten = trim($_POST['ten_khuyen_mai'] ?? '');
                    $ma_code = trim($_POST['ma_khuyen_mai'] ?? '');
                    $loai_giam = $_POST['loai_giam'] ?? 'phan_tram';
                    $phan_tram_giam = (float)($_POST['phan_tram_giam'] ?? 0);
                    $gia_tri_giam = (int)($_POST['gia_tri_giam'] ?? 0);
                    $bat_dau = $_POST['ngay_bat_dau'] ?? null;
                    $ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $dieu_kien = trim($_POST['dieu_kien_ap_dung'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    
                    // Tự động gán id_rap cho quản lý rạp
                    $currentRole = (int)($_SESSION['user1']['vai_tro'] ?? -1);
                    $id_rap_km = null;
                    if ($currentRole === 3) {
                        $id_rap_km = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    }
                    
                    km_update($id, $ten, $ma_code, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau ?: null, $ket_thuc ?: null, $trang_thai, $dieu_kien, $mo_ta, $id_rap_km);
                    $success = 'Đã cập nhật'; $row = km_one($id);
                }
                include "./view/khuyenmai/sua.php";
                break;
            case "km_xoa":
                if (isset($_GET['id'])) { km_delete((int)$_GET['id']); }
                $ds_km = km_all();
                include "./view/khuyenmai/QLkm.php";
                break;
            case "km_toggle":
                if (isset($_GET['id'])) { km_toggle((int)$_GET['id']); }
                $ds_km = km_all();
                include "./view/khuyenmai/QLkm.php";
                break;
            case "xoabl":
                         if(isset($_GET['id'])){
                            $id = $_GET['id'];
                            delete_binhluan($id);
                        }
                         $listbl =  loadall_bl();
                         include "./view/feedblack/QLfeed.php";
                          break;
            case "thoigian":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadkgc = loadall_khunggiochieu($id_rap);
                include "./view/suatchieu/thoigian/thoigian.php";
                break;
            case "themthoigian":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                $loadlc = $id_rap ? pdo_query("SELECT lc.id, lc.ngay_chieu, p.tieu_de FROM lichchieu lc JOIN phim p ON p.id = lc.id_phim WHERE lc.id_rap = ? ORDER BY lc.ngay_chieu DESC", $id_rap) : loadall_lichchieu();
                if (isset($_POST['them'])) {
                    $id_lc = (int)($_POST['id_lc'] ?? 0);
                    $id_phong = (int)($_POST['id_phong'] ?? 0);
                    $tgc = trim($_POST['tgc'] ?? '');
                    if (!$id_lc || !$id_phong || $tgc==='') { $error = "Vui lòng chọn lịch chiếu, phòng và giờ"; }
                    else if (kgc_conflict_exists($id_lc, $id_phong, $tgc)) { $error = "Trùng phòng/giờ trong ngày này"; }
                    else { them_kgc($id_lc, $id_phong, $tgc); $suatc = "Đã thêm khung giờ"; }
                }
                include "./view/suatchieu/thoigian/them.php";
                break;
            case "suathoigian":
                $id = (int)($_GET['ids'] ?? 0);
                $row = loadone_khung_gio_chieu($id);
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                $loadlc = $id_rap ? pdo_query("SELECT lc.id, lc.ngay_chieu, p.tieu_de FROM lichchieu lc JOIN phim p ON p.id = lc.id_phim WHERE lc.id_rap = ? ORDER BY lc.ngay_chieu DESC", $id_rap) : loadall_lichchieu();
                if (isset($_POST['capnhat'])) {
                    $id_lc = (int)($_POST['id_lc'] ?? 0);
                    $id_phong = (int)($_POST['id_phong'] ?? 0);
                    $tgc = trim($_POST['tgc'] ?? '');
                    if (!$id_lc || !$id_phong || $tgc==='') { $error = "Vui lòng chọn lịch chiếu, phòng và giờ"; }
                    else if (kgc_conflict_exists($id_lc, $id_phong, $tgc, $id)) { $error = "Trùng phòng/giờ trong ngày này"; }
                    else { sua_kgc($id, $id_lc, $id_phong, $tgc); $suatc = "Đã cập nhật"; $row = loadone_khung_gio_chieu($id); }
                }
                include "./view/suatchieu/thoigian/sua.php";
                break;
            case "xoathoigian":
                if (isset($_GET['idxoa'])) { xoa_kgc((int)$_GET['idxoa']); }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadkgc = loadall_khunggiochieu($id_rap);
                include "./view/suatchieu/thoigian/thoigian.php";
                break;
            case "QLsuatchieu":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadlich = $id_rap ? loadall_lichchieu_by_rap($id_rap) : loadall_lichchieu();
                include "./view/suatchieu/QLsuatchieu.php";
                break;
            case "gui_kehoach":
                $id = (int)($_GET['id'] ?? 0);
                if ($id > 0) {
                    $lc = loadone_lichchieu($id);
                    $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    if ($lc && (int)$lc['id_rap'] === $myRap) {
                        lc_duyet($id, 'Chờ duyệt');
                        $suatc = "Đã gửi kế hoạch chờ duyệt";
                    } else { $error = "Lịch không thuộc rạp của bạn"; }
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadlich = $id_rap ? loadall_lichchieu_by_rap($id_rap) : loadall_lichchieu();
                include "./view/suatchieu/QLsuatchieu.php";
                break;
                
            // Kế hoạch chiếu phim
            case "kehoach":
                include "./view/kehoachphim/kehoach.php";
                break;
            
            case "xem_kehoach":
                if (isset($_GET['ma'])) {
                    $ma_ke_hoach = $_GET['ma'];
                    $id_rap = $_SESSION['user1']['id_rap'] ?? null;
                    $chi_tiet_ke_hoach = ke_hoach_chi_tiet($ma_ke_hoach, $id_rap);
                    include "./view/kehoachphim/xem_chi_tiet.php";
                } else {
                    header("Location: index.php?act=kehoach&msg=error&error=Không tìm thấy kế hoạch");
                    exit;
                }
                break;
            
            case "export_kehoach":
                if (isset($_GET['ma'])) {
                    $ma_ke_hoach = $_GET['ma'];
                    $id_rap = $_SESSION['user1']['id_rap'] ?? null;
                    $chi_tiet_ke_hoach = ke_hoach_chi_tiet($ma_ke_hoach, $id_rap);
                    if (!empty($chi_tiet_ke_hoach)) {
                        include "./helpers/export_word.php";
                        export_kehoach_chi_tiet_word($chi_tiet_ke_hoach);
                    } else {
                        header("Location: index.php?act=kehoach&msg=error&error=Không tìm thấy kế hoạch");
                        exit;
                    }
                } else {
                    header("Location: index.php?act=kehoach&msg=error&error=Thiếu mã kế hoạch");
                    exit;
                }
                break;
            
            case "thu_hoi_kehoach":
                if (isset($_GET['ma'])) {
                    $ma_ke_hoach = $_GET['ma'];
                    $id_rap = $_SESSION['user1']['id_rap'] ?? null;
                    
                    // Kiểm tra xem kế hoạch có thuộc rạp này không
                    $chi_tiet = ke_hoach_chi_tiet($ma_ke_hoach, $id_rap);
                    if (!empty($chi_tiet)) {
                        // Lấy tất cả id_lich_chieu thuộc kế hoạch này
                        $sql = "SELECT id FROM lichchieu WHERE ma_ke_hoach = ?";
                        $lich_chieu_ids = pdo_query($sql, $ma_ke_hoach);
                        
                        // Xóa các khung giờ chiếu liên quan
                        foreach ($lich_chieu_ids as $lc) {
                            pdo_execute("DELETE FROM khung_gio_chieu WHERE id_lich_chieu = ?", $lc['id']);
                        }
                        
                        // Xóa các lịch chiếu
                        pdo_execute("DELETE FROM lichchieu WHERE ma_ke_hoach = ?", $ma_ke_hoach);
                        
                        // Dùng JavaScript redirect để tránh lỗi "headers already sent"
                        echo "<script>window.location.href='index.php?act=kehoach&msg=success&success=" . urlencode("Đã thu hồi kế hoạch thành công!") . "';</script>";
                        exit;
                    } else {
                        echo "<script>window.location.href='index.php?act=kehoach&msg=error&error=" . urlencode("Không tìm thấy kế hoạch hoặc không có quyền thu hồi!") . "';</script>";
                        exit;
                    }
                } else {
                    echo "<script>window.location.href='index.php?act=kehoach&msg=error&error=" . urlencode("Thiếu mã kế hoạch") . "';</script>";
                    exit;
                }
                break;
            
            case "sua_kehoach":
                if (isset($_GET['ma'])) {
                    $ma_ke_hoach = $_GET['ma'];
                    $id_rap = $_SESSION['user1']['id_rap'] ?? null;
                    $chi_tiet_ke_hoach = ke_hoach_chi_tiet($ma_ke_hoach, $id_rap);
                    
                    // Chỉ cho phép sửa kế hoạch đang "Chờ duyệt"
                    if (!empty($chi_tiet_ke_hoach) && $chi_tiet_ke_hoach['trang_thai_duyet'] == 'Chờ duyệt') {
                        include "./view/kehoachphim/sua_kehoach.php";
                    } else {
                        header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Không thể sửa kế hoạch này!"));
                        exit;
                    }
                } else {
                    header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Thiếu mã kế hoạch"));
                    exit;
                }
                break;
            
             //////////QL Phòng
            case "phong":
                enforce_act_or_403('phong'); // Kiểm tra quyền
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                include "./view/phong/phong.php";
                break;
            case "xoaphong":
                enforce_act_or_403('xoaphong'); // Kiểm tra quyền
                if (isset($_GET['idxoa'])) {
                    xoa_phon($_GET['idxoa']);
                    $loadphongg = load_phong();
                    include "./view/phong/phong.php";
                }
                break;
            case "suaphong":
                enforce_act_or_403('suaphong'); // Kiểm tra quyền
                if (isset($_GET['ids'])) {
                    $id_phong_edit = (int)$_GET['ids'];
                    $loadphong1 = loadone_phong($id_phong_edit);
                    
                    // Hiển thị thông báo từ URL parameter
                    if (isset($_GET['success'])) {
                        switch ($_GET['success']) {
                            case 'created': $suatc = "Đã tạo sơ đồ ghế thành công!"; break;
                            case 'deleted': $suatc = "Đã xóa sơ đồ ghế"; break;
                            case 'default': $suatc = "Đã tạo sơ đồ mặc định"; break;
                            case 'saved': $suatc = "Đã lưu sơ đồ ghế"; break;
                            case 'updated': $suatc = "Đã cập nhật thông tin phòng và sơ đồ ghế"; break;
                        }
                    }
                    if (isset($_GET['error'])) {
                        switch ($_GET['error']) {
                            case 'invalid_data': $error = "Dữ liệu sơ đồ không hợp lệ"; break;
                        }
                    }
                    $map = pg_list($id_phong_edit);
                }
                
                // Luôn dùng giao diện advanced (đầy đủ tính năng)
                include "./view/phong/sua.php";
                break;

                case "updatephong":
                    enforce_act_or_403('updatephong'); // Kiểm tra quyền

                    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                    if (isset($_POST['capnhat'])) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $so_ghe = isset($_POST['so_ghe']) ? (int)$_POST['so_ghe'] : null;
                        $dien_tich = isset($_POST['dien_tich']) ? (float)$_POST['dien_tich'] : null;
                        if($id==''||$name =='') {
                          $error = "vui lòng không để trống";
                          $loadphong1 = load_phong($id);
                          include "./view/phong/sua.php";
                          break;
                         }else{
                        update_phong($id, $name, $so_ghe, $dien_tich);
                        $suatc = "sửa thành công";

                    }
                  }
                    $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                    include "./view/phong/phong.php";
                    break;
                case "themphong":
                    enforce_act_or_403('themphong'); // Kiểm tra quyền
                    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                   if (isset($_POST['len'])) {
                       $name = trim($_POST['name'] ?? '');
                       $so_ghe = isset($_POST['so_ghe']) ? (int)$_POST['so_ghe'] : 0;
                       $dien_tich = isset($_POST['dien_tich']) ? (float)$_POST['dien_tich'] : 0;
                       $loai_phong = $_POST['loai_phong'] ?? 'medium';
                       $custom_rows = isset($_POST['custom_rows']) ? (int)$_POST['custom_rows'] : null;
                       $custom_cols = isset($_POST['custom_cols']) ? (int)$_POST['custom_cols'] : null;
                       
                       if($name =='' || !$id_rap) {
                         $error = "Vui lòng nhập tên phòng";
                         include "./view/phong/them.php";
                         break;
                       } else {
                           // Thêm phòng vào database
                           them_phong($name, $id_rap, $so_ghe, $dien_tich);
                           
                           // Lấy ID phòng vừa tạo
                           $id_phong_moi = pdo_query_one("SELECT id FROM phongchieu WHERE name = ? AND id_rap = ? ORDER BY id DESC LIMIT 1", $name, $id_rap);
                           
                           if ($id_phong_moi && isset($id_phong_moi['id'])) {
                               // Tự động tạo sơ đồ ghế theo template
                               include_once "./model/phong_ghe.php";
                               pg_generate_by_template($id_phong_moi['id'], $loai_phong, $custom_rows, $custom_cols);
                               $suatc = "✅ Thêm phòng và tạo sơ đồ ghế thành công!";
                           } else {
                               $suatc = "Thêm phòng thành công";
                           }
                       }
                   }
                    $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                   include "./view/phong/them.php";
                   break;
            ////////////////////////////////////////
            case "updatethoigian":
                $loadlc = loadall_lichchieu();
                $loadphong = load_phong();
                if (isset($_POST['capnhat'])) {
                    $id = $_POST['id'];
                    $id_lc = $_POST['id_lc'];
                    $id_phong = $_POST['id_phong'];
                    $thoi_gian_chieu = $_POST['tgc'];
                    sua_kgc($id, $id_lc, $id_phong, $thoi_gian_chieu);
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadkgc = loadall_khunggiochieu($id_rap);
                include "./view/suatchieu/thoigian/thoigian.php";
                break;
            case "themthoigian":
                $loadlc = loadall_lichchieu();
                $loadphong = load_phong();
                if (isset($_POST['them'])) {
                    $id_lc = isset($_POST['id_lc']) ? (int)$_POST['id_lc'] : 0;
                    $id_phong = isset($_POST['id_phong']) ? (int)$_POST['id_phong'] : 0;
                    $thoi_gian_chieu = trim($_POST['tgc'] ?? '');
                    if($id_lc<=0 || $id_phong<=0 || $thoi_gian_chieu==='') {
                        $error = "Vui lòng chọn lịch chiếu, phòng và giờ";
                        $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                        $loadkgc = loadall_khunggiochieu($id_rap);
                        include "./view/suatchieu/thoigian/them.php";
                        break;
                    }
                    // Chặn trùng khung giờ cùng phòng trong ngày của lịch chiếu
                    if (kgc_conflict_exists($id_lc, $id_phong, $thoi_gian_chieu)) {
                        $error = "Khung giờ này đã tồn tại cho phòng trong ngày đã chọn";
                        $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                        $loadkgc = loadall_khunggiochieu($id_rap);
                        include "./view/suatchieu/thoigian/them.php";
                        break;
                    }
                    them_kgc($id_lc, $id_phong, $thoi_gian_chieu);
                    $suatc = "Thêm thành công";
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadkgc = loadall_khunggiochieu($id_rap);
                include "./view/suatchieu/thoigian/them.php";
                break;
            case "suathoigian":
                $loadlich = loadall_lichchieu();
                $loadphong = load_phong();
                if (isset($_GET['ids'])) {
                    $load1kgc = loadone_khung_gio_chieu($_GET['ids']);
                }
                include "./view/suatchieu/thoigian/sua.php";
                break;
            case "xoathoigian":
                if (isset($_GET['idxoa']) && ($_GET['idxoa'] > 0)){
                    xoa_kgc($_GET['idxoa']);
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadkgc = loadall_khunggiochieu($id_rap);
                include "./view/suatchieu/thoigian/thoigian.php";
                break;
            case "xoalichchieu":
                if (isset($_GET['idxoa']) && ($_GET['idxoa'] > 0)){
                    xoa_lc($_GET['idxoa']);
                }
                $loadlich = loadall_lichchieu();
                include "./view/suatchieu/QLsuatchieu.php";
                break;
            case "QTkh":
                $loadall_kh1 = loadall_taikhoan();
                include "./view/user/khachhang.php";
                break;
            case "QTvien":
                if (in_array($_SESSION['user1']['vai_tro'], [3]) && !empty($_SESSION['user1']['id_rap'])) {
                    $loadalltk = loadall_taikhoan_nv_by_rap((int)$_SESSION['user1']['id_rap']);
                } else {
                    $loadalltk = loadall_taikhoan_nv();
                }
                include "./view/user/QTvien.php";
                break;
            case "QLquanlyrap":
                $ds_qllr = loadall_quanly_rap();
                include "./view/user/quanly_rap.php";
                break;
            case "QLquanlycum":
                $ds_qllc = loadall_taikhoan_by_role(4);
                include "./view/user/quanly_cum.php";
                break;
                case "suatk":
                    if (isset($_GET['idsua'])) {
                        $id_edit = (int)$_GET['idsua'];
                        $loadtk = loadone_taikhoan($id_edit);
                        // Nếu là Quản lý rạp: chỉ được sửa nhân viên cùng rạp
                        if ((int)$_SESSION['user1']['vai_tro'] === 3) {
                            $guard = tk_get_role_rap($id_edit);
                            $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                            if (!$guard || (int)$guard['vai_tro'] !== 1 || (int)$guard['id_rap'] !== $myRap) {
                                include "./view/home/403.php"; break;
                            }
                        }
                    }
                    include "./view/user/sua.php";
                    break;
                case "themuser":
                    if(isset($_POST['them'])){
                        $name = trim($_POST['name'] ?? '');
                        $user = trim($_POST['user'] ?? '');
                        $pass = trim($_POST['pass'] ?? '');
                        $email = trim($_POST['email'] ?? '');
                        $phone = trim($_POST['phone'] ?? '');
                        $dia_chi = trim($_POST['dia_chi'] ?? '');
                        $id_rap_post = isset($_POST['id_rap']) && $_POST['id_rap'] !== '' ? (int)$_POST['id_rap'] : null;
                        $vai_tro_post = isset($_POST['vai_tro']) && $_POST['vai_tro'] !== '' ? (int)$_POST['vai_tro'] : null;

                        if ($name=='' || $user=='' || $pass=='' || $email=='' || $vai_tro_post===null) {
                            $error = "Vui lòng điền đủ tên, tài khoản, mật khẩu, email";
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $error = "Email không đúng định dạng";
                        } else {
                            $role = $vai_tro_post;
                            // Nếu người thêm là Quản lý rạp: chỉ được tạo Nhân viên rạp tại rạp của họ
                            if ((int)$_SESSION['user1']['vai_tro'] === 3) {
                                $role = 1; // ép về nhân viên
                                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                            } else {
                                $id_rap = $id_rap_post;
                            }
                            // Yêu cầu id_rap với các role thuộc 1 rạp
                            if (in_array($role, [1,3]) && !$id_rap) {
                                $error = "Vui lòng chọn rạp cho nhân viên/quản lý rạp";
                            } else {
                                // Các role khác: id_rap có thể để null
                                insert_user_role($email,$user,$pass,$name,$phone,$dia_chi,$role,$id_rap);
                                $suatc = "Thêm tài khoản thành công";
                            }
                        }
                    }
                    include "./view/user/them.php";
                    break;
                    
                      case "updateuser":
                        if(isset($_POST['capnhat'])){
                           $id =$_POST['id'];
                           $name =$_POST['name'];
                           $user =$_POST['user'];
                           $pass =$_POST['pass'];
                           $email =$_POST['email'];
                           $phone =$_POST['phone'];
                           $dia_chi =$_POST['dia_chi'];
                           if($id==''||$name ==''||$email==''|| $pass==''|| $user==''||$phone==''||$dia_chi=='') {
                             $error = "vui lòng không để trống";
                             // Không cần load khung giờ chiếu trong user management
                             include "./view/user/sua.php";
                             break;
                           }else {
                           // Nếu là Quản lý rạp: chỉ được cập nhật nhân viên thuộc rạp của họ
                           if ((int)$_SESSION['user1']['vai_tro'] === 3) {
                               $guard = tk_get_role_rap((int)$id);
                               $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                               if (!$guard || (int)$guard['vai_tro'] !== 1 || (int)$guard['id_rap'] !== $myRap) {
                                   include "./view/home/403.php"; break;
                               }
                           }
                           sua_tk($id, $name, $user, $pass, $email, $phone, $dia_chi);  
                           $suatc = "Sửa thành công";

                        }
                       }
     
                        $loadalltk = loadall_taikhoan_nv();
                       include "./view/user/QTvien.php";
                       break;        
            case "xoatk":
                if(isset($_GET['idxoa'])){
                    $id = (int)$_GET['idxoa'];
                    // Nếu là Quản lý rạp: chỉ xóa nhân viên cùng rạp
                    if ((int)$_SESSION['user1']['vai_tro'] === 3) {
                        $guard = tk_get_role_rap($id);
                        $myRap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                        if ($guard && (int)$guard['vai_tro'] === 1 && (int)$guard['id_rap'] === $myRap) {
                            xoa_tk($id);
                        }
                    } else {
                        xoa_tk($id);
                    }
                }
                if (in_array($_SESSION['user1']['vai_tro'], [3]) && !empty($_SESSION['user1']['id_rap'])) {
                    $loadalltk = loadall_taikhoan_nv_by_rap((int)$_SESSION['user1']['id_rap']);
                } else {
                    $loadalltk = loadall_taikhoan_nv();
                }
                include "./view/user/QTvien.php";
                break;
            case "dangxuat":
                // Dự phòng: nếu đến được đây (sau khi đã output), dùng JS để chuyển trang
                unset($_SESSION['user1']);
                echo "<script>window.location.href='login.php';</script>";
                exit;
            case "home":
                // Nếu là Quản lý cụm: dashboard cụm với filter rạp + ngày
                if ((int)($_SESSION['user1']['vai_tro'] ?? -1) === 4 && (isset($_GET['scope']) ? $_GET['scope']==='cum' : true)) {
                    $id_rap = isset($_GET['id_rap']) && $_GET['id_rap']!=='' ? (int)$_GET['id_rap'] : null;
                    $period = $_GET['period'] ?? '';
                    $from = $_GET['from'] ?? '';
                    $to = $_GET['to'] ?? '';
                    if ($period === 'today') { $from = date('Y-m-d'); $to = date('Y-m-d'); }
                    elseif ($period === 'week') { $from = date('Y-m-d', strtotime('monday this week')); $to = date('Y-m-d', strtotime('sunday this week')); }
                    elseif ($period === 'month') { $from = date('Y-m-01'); $to = date('Y-m-t'); }
                    // Default range if not provided: current month
                    if (!$from) { $from = date('Y-m-01'); }
                    if (!$to) { $to = date('Y-m-t'); }
                    $sum_revenue = tk_sum_revenue($id_rap, $from ?: null, $to ?: null);
                    $sum_tickets = tk_count_tickets($id_rap, $from ?: null, $to ?: null);
                    $movies_showing = tk_count_movies_showing($id_rap, $from ?: null, $to ?: null);
                    $best_combo_r = best_combo_khoang($id_rap, $from ?: null, $to ?: null);
                    // Các mốc nhanh (luôn theo rạp filter nếu có)
                    $rev_today = tk_sum_revenue($id_rap, date('Y-m-d'), date('Y-m-d'));
                    $rev_week  = tk_sum_revenue($id_rap, date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week')));
                    $rev_month = tk_sum_revenue($id_rap, date('Y-m-01'), date('Y-m-t'));
                    // Chart series
                    $rows = tk_revenue_by_date($from, $to, $id_rap);
                    $rev_by_date_rows = [];
                    foreach ($rows as $r) { $rev_by_date_rows[] = [$r['ngay'], (int)$r['revenue']]; }
                    $rowsR = tk_revenue_by_rap($from, $to);
                    $rev_by_rap_rows = [];
                    foreach ($rowsR as $r) { $rev_by_rap_rows[] = [$r['ten_rap'], (int)$r['revenue']]; }
                    include "./view/cum/home_cum.php";
                } else {
                    // Mặc định: dashboard Admin hệ thống
                    $best_combo = best_combo();
                    $tong_tuan = tong_week();
                    $tong_thang = tong_thang();
                    $tong_day = tong_day();
                    $tpdc = tong_phimdc();
                    $tpsc = tong_phimsc();
                    $tong = tong();
                    include "./view/home.php";
                }
                break;
            ////////////Quản lí vé
            case "ve":
                if(isset($_POST['tk'])&&($_POST['tk'])){
                    $searchName = $_POST['ten'];
                    $searchTieuDe = $_POST['tieude'];
                    $searchid = $_POST['id_ve'];
                }else{
                    $searchName ="";
                    $searchTieuDe="";
                    $searchid = "";
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if ($id_rap && in_array($_SESSION['user1']['vai_tro'], [1,3])) {
                    $loadvephim = loadall_vephim1_by_rap($searchName, $searchTieuDe, $searchid, $id_rap);
                } else {
                    $loadvephim = loadall_vephim1($searchName, $searchTieuDe, $searchid);
                }
                include "./view/vephim/ve.php";
                break;
            case "suavephim":
                if(isset($_GET['idsua'])){
                    $loadve=loadone_vephim($_GET['idsua']);
                }
                include "./view/vephim/sua.php";
                break;
            case "updatevephim":
                if(isset($_POST['capnhat'])){
                    $id =$_POST['id'];
                    $trang_thai =$_POST['trang_thai'];
                    update_vephim($id,$trang_thai);
                }    if(isset($_POST['tk'])&&($_POST['tk'])){
                $searchName = $_POST['ten'];
                $searchTieuDe = $_POST['tieude'];
                $searchid = $_POST['id_ve'];
                }else{
                    $searchName ="";
                    $searchTieuDe="";
                    $searchid = "";
                }
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if ($id_rap && in_array($_SESSION['user1']['vai_tro'], [1,3])) {
                    $loadvephim = loadall_vephim1_by_rap($searchName, $searchTieuDe, $searchid, $id_rap);
                } else {
                    $loadvephim = loadall_vephim1($searchName, $searchTieuDe, $searchid);
                }
                include "view/vephim/ve.php";
                break;
            case "ctve":
                if (isset($_GET['id']) && ($_GET['id'] > 0)){
                    $loadone_ve =  loadone_vephim($_GET['id']);
                }
                include "view/vephim/ct_ve.php";
                break;
            case "capnhat_tt_ve":
                if(isset($_POST['tk'])&&($_POST['tk'])){
                $searchName = $_POST['ten'];
                $searchTieuDe = $_POST['tieude'];
                  }else{
                        $searchName ="";
                          $searchTieuDe="";
                   }

                      include "./view/vephim/ve.php";
                if(isset($_POST['capnhat'])){
                    capnhat_tt_ve();
                }
                $loadvephim =loadall_vephim1($searchName, $searchTieuDe);
                gui_mail_ve($load_ve_tt);
                include "./view/user/QTvien.php";
                break;
            case "doi_hoan_ve":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                if (isset($_POST['tao'])) {
                    $id_ve = (int)($_POST['id_ve'] ?? 0);
                    $loai = $_POST['loai'] ?? 'doi';
                    $ly_do = trim($_POST['ly_do'] ?? '');
                    $tt_moi = isset($_POST['trang_thai_moi']) && $_POST['trang_thai_moi']!=='' ? (int)$_POST['trang_thai_moi'] : null;
                    if ($id_ve>0 && $id_rap>0) { dh_tao($id_ve, $id_rap, $loai, $ly_do, $tt_moi); $success = "Đã tạo yêu cầu"; }
                    else { $error = "Thiếu ID vé hoặc rạp"; }
                }
                $ds_yc = dh_list_by_rap($id_rap);
                include "./view/vephim/doihoan.php";
                break;
            case "duyet_doihoan":
                $id = (int)($_GET['id'] ?? 0);
                $row = dh_one($id);
                if ($row) { dh_update_trang_thai($id, 'da_duyet'); if (!empty($row['trang_thai_moi'])) { update_vephim((int)$row['id_ve'], (int)$row['trang_thai_moi']); } $success = "Đã duyệt"; }
                $ds_yc = dh_list_by_rap((int)($_SESSION['user1']['id_rap'] ?? 0)); include "./view/vephim/doihoan.php"; break;
            case "tuchoi_doihoan":
                $id = (int)($_GET['id'] ?? 0);
                $row = dh_one($id);
                if ($row) { dh_update_trang_thai($id, 'tu_choi'); $success = "Đã từ chối"; }
                $ds_yc = dh_list_by_rap((int)($_SESSION['user1']['id_rap'] ?? 0)); include "./view/vephim/doihoan.php"; break;

            // Quản lý CỤM + Admin hệ thống: tài khoản admin hệ thống
            case "cum_admin":
                if (isset($_POST['them'])) {
                    $name = trim($_POST['name'] ?? '');
                    $user = trim($_POST['user'] ?? '');
                    $pass = trim($_POST['pass'] ?? '');
                    $email = trim($_POST['email'] ?? '');
                    $phone = trim($_POST['phone'] ?? '');
                    $dia_chi = trim($_POST['dia_chi'] ?? '');
                    if ($name && $user && $pass && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        insert_user_role($email,$user,$pass,$name,$phone,$dia_chi,2,null);
                        $success = "Đã thêm Admin hệ thống";
                    } else {
                        $error = "Thiếu thông tin hoặc email không hợp lệ";
                    }
                }
                $ds_admin = loadall_taikhoan_by_role(2);
                include "./view/user/cum_admin.php";
                break;
            case "cum_admin_xoa":
                if (isset($_GET['id'])) { xoa_tk((int)$_GET['id']); }
                $ds_admin = loadall_taikhoan_by_role(2);
                include "./view/user/cum_admin.php";
                break;
        }
        }
    } else {
        $best_combo = best_combo();
        $tong_tuan = tong_week();
        $tong_thang = tong_thang();
        $tong_day = tong_day();
        $tpdc = tong_phimdc();
        $tpsc = tong_phimsc();
        $tong = tong();
        include "./view/home.php";
    }

    include "./view/home/footer.php";
}else{
    header('location: login.php');
}
