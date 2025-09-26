<?php
ob_start(); // Bắt đầu output buffering
session_start();
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
    include "./helpers/export_word.php";
    $loadphim = loadall_phim();
    $loadloai = loadall_loaiphim();
    $loadtk = loadall_taikhoan();
    // API JSON cho đặt vé (trả JSON thuần trước khi include header)
    if (isset($_GET['act'])) {
        $act_api = $_GET['act'];
        if (in_array($act_api, ['api_dates','api_times','api_reserved','api_combos','api_seatmap'], true)) {
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
                if (!$id_phong) { echo json_encode([]); exit; }
                $list = pg_list($id_phong);
                echo json_encode($list); exit;
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
                try {
                    $rows = pdo_query("SELECT id, ten_combo as name, gia as price, hinh_anh as image, mo_ta as description FROM combo_do_an WHERE trang_thai = 1 ORDER BY id");
                    echo json_encode($rows); exit;
                } catch (Exception $e) { echo json_encode([]); exit; }
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
                // Bước 2: chọn ghế + combo (từ form tự submit khi đã đủ phim/ngày/giờ/email)
                if (isset($_POST['chon_lich'])) {
                    $id_phim = (int)($_POST['id_phim'] ?? 0);
                    $id_lc = (int)($_POST['id_lc'] ?? 0);
                    $id_tg = (int)($_POST['id_tg'] ?? 0);
                    $email_kh = trim($_POST['email_kh'] ?? '');
                    if (!$id_phim || !$id_lc || !$id_tg || $email_kh==='') {
                        $error = "Vui lòng chọn phim/ngày/giờ và nhập email khách hàng";
                        include "./view/nhanvien/datve.php"; break;
                    }
                    $ghe_da_dat = ve_reserved_seats($id_tg, $id_lc);
                    $seat_map = pg_list_for_time($id_tg);
                    include "./view/nhanvien/datve_ghe.php"; break;
                }
                // Xác nhận đặt vé (một trang SPA)
                if (isset($_POST['datve_confirm'])) {
                    $id_phim = (int)($_POST['id_phim'] ?? 0);
                    $id_lc = (int)($_POST['id_lc'] ?? 0);
                    $id_tg = (int)($_POST['id_tg'] ?? 0);
                    $email_kh = trim($_POST['email_kh'] ?? '');
                    $ghe_csv = trim($_POST['ghe_csv'] ?? '');
                    $combo_text = trim($_POST['combo_text'] ?? '');
                    $price = (int)($_POST['price'] ?? 0);
                    if (!$id_phim || !$id_lc || !$id_tg || $ghe_csv==='' || $price<=0 || $email_kh==='') {
                        $error = "Thiếu thông tin hoặc chưa chọn ghế";
                    } else {
                        // Validate seats against room seat map and already-reserved list
                        $valid = true; $msgInvalid = '';
                        $seats = array_filter(array_map('trim', explode(',', $ghe_csv)));
                        $map = pg_list_for_time($id_tg);
                        if (!empty($map)){
                            $activeCodes = [];
                            foreach ($map as $m){ if ((int)$m['active']===1) $activeCodes[$m['code']] = true; }
                            foreach ($seats as $s){ if (!isset($activeCodes[$s])) { $valid=false; $msgInvalid='Ghế không hợp lệ trong phòng: '.$s; break; } }
                        }
                        // also check reserved
                        if ($valid){
                            $reserved = ve_reserved_seats($id_tg, $id_lc);
                            foreach ($seats as $s){ if (in_array($s, $reserved, true)) { $valid=false; $msgInvalid='Ghế đã có người đặt: '.$s; break; } }
                        }
                        $kh = get_tk_by_email($email_kh);
                        if (!$kh) {
                            $error = "Không tìm thấy khách hàng theo email";
                        } elseif(!$valid) {
                            $error = $msgInvalid;
                        } else {
                            ve_create_admin($id_phim, $id_rap, $id_tg, $id_lc, (int)$kh['id'], $ghe_csv, $price, (int)$_SESSION['user1']['id'], $combo_text);
                            $success = "Đặt vé thành công";
                        }
                    }
                }
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
            case "api_combos":
                header('Content-Type: application/json; charset=utf-8');
                // Nếu có bảng combo_do_an thì lấy, không thì dùng danh sách mặc định
                try {
                    $rows = pdo_query("SELECT id, ten_combo as name, gia as price FROM combo_do_an WHERE trang_thai = 1 ORDER BY id");
                    echo json_encode($rows); exit;
                } catch(Exception $e){
                    echo json_encode([]); exit;
                }

            // Quản lý rạp: lịch làm, duyệt nghỉ, thiết bị
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
                if (isset($_GET['xoa'])) { cc_delete((int)$_GET['xoa']); }
                if (isset($_POST['them'])) {
                    $id_nv = (int)($_POST['id_nv'] ?? 0);
                    $ngay = $_POST['ngay'] ?? '';
                    $gio_vao = $_POST['gio_vao'] ?? '';
                    $gio_ra = $_POST['gio_ra'] ?? '';
                    $ghi_chu = $_POST['ghi_chu'] ?? null;
                    if ($id_nv && $ngay && $gio_vao && $gio_ra) { cc_insert($id_nv, $id_rap, $ngay, $gio_vao, $gio_ra, $ghi_chu); $success = "Đã chấm công"; }
                    else { $error = "Thiếu thông tin"; }
                }
                $ds_nv = pdo_query("SELECT id, name FROM taikhoan WHERE vai_tro = 1 AND id_rap = ? ORDER BY name", $id_rap);
                $ds_cc = cc_list_by_rap_month($id_rap, $ym, $nv ?: null);
                // Summary: nếu chọn 1 nhân viên thì tổng giờ của NV đó, nếu không thì tổng theo từng NV trong tháng (rate=1 => lương = giờ)
                if ($nv) { $sum_hours = cc_sum_hours($nv, $id_rap, $ym); }
                else { $sum_by_emp = luong_tinh_thang($id_rap, $ym, 1); }
                include "./view/quanly/chamcong.php";
                break;
            case "bangluong":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $ym = $_GET['ym'] ?? date('Y-m');
                $rate = isset($_GET['rate']) ? (int)$_GET['rate'] : 30000;
                $ds_luong = luong_tinh_thang($id_rap, $ym, $rate);
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
                if (isset($_GET['duyet'])) { lc_duyet((int)$_GET['duyet'], 'Đã duyệt'); $msg = "Đã duyệt lịch"; }
                if (isset($_GET['tuchoi'])) { lc_duyet((int)$_GET['tuchoi'], 'Từ chối'); $msg = "Đã từ chối"; }
                $filter = $_GET['filter'] ?? 'cho_duyet';
                $ds_lich = lc_list_by_trang_thai($filter);
                include "./view/cum/duyet_lich.php";
                break;
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
                $ds_combo = combo_all();
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
                    if ($ten==='') { $error = "Tên không được trống"; }
                    else { combo_insert($ten, $gia, $hinh, $mo_ta, $trang_thai); $success = "Đã thêm"; }
                }
                include "./view/combo/them.php";
                break;
            case "combo_sua":
                $id = (int)($_GET['id'] ?? 0);
                $row = combo_one($id);
                if (isset($_POST['capnhat'])) {
                    $ten = trim($_POST['ten'] ?? '');
                    $gia = (int)($_POST['gia'] ?? 0);
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $hinh = null;
                    if (!empty($_FILES['hinh']['name'])) { $hinh = $_FILES['hinh']['name']; @move_uploaded_file($_FILES['hinh']['tmp_name'], "assets/images/".basename($hinh)); }
                    combo_update($id, $ten, $gia, $hinh, $mo_ta, $trang_thai);
                    $success = "Đã cập nhật"; $row = combo_one($id);
                }
                include "./view/combo/sua.php";
                break;
            case "combo_xoa":
                if (isset($_GET['id'])) { combo_delete((int)$_GET['id']); }
                $ds_combo = combo_all();
                include "./view/combo/QLcombo.php";
                break;
            case "combo_toggle":
                if (isset($_GET['id'])) { combo_toggle((int)$_GET['id']); }
                $ds_combo = combo_all();
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
                    $loai_giam = $_POST['loai_giam'] ?? 'phan_tram';
                    $phan_tram_giam = (float)($_POST['phan_tram_giam'] ?? 0);
                    $gia_tri_giam = (int)($_POST['gia_tri_giam'] ?? 0);
                    $bat_dau = $_POST['ngay_bat_dau'] ?? null;
                    $ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $dieu_kien = trim($_POST['dieu_kien_ap_dung'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    if ($ten==='') { $error = 'Tên khuyến mãi không được trống'; }
                    else { km_insert($ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau ?: null, $ket_thuc ?: null, $trang_thai, $dieu_kien, $mo_ta); $success='Đã thêm'; }
                }
                include "./view/khuyenmai/them.php";
                break;
            case "km_sua":
                $id = (int)($_GET['id'] ?? 0);
                $row = km_one($id);
                if (isset($_POST['capnhat'])) {
                    $ten = trim($_POST['ten_khuyen_mai'] ?? '');
                    $loai_giam = $_POST['loai_giam'] ?? 'phan_tram';
                    $phan_tram_giam = (float)($_POST['phan_tram_giam'] ?? 0);
                    $gia_tri_giam = (int)($_POST['gia_tri_giam'] ?? 0);
                    $bat_dau = $_POST['ngay_bat_dau'] ?? null;
                    $ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
                    $trang_thai = (int)($_POST['trang_thai'] ?? 1);
                    $dieu_kien = trim($_POST['dieu_kien_ap_dung'] ?? '');
                    $mo_ta = trim($_POST['mo_ta'] ?? '');
                    km_update($id, $ten, $loai_giam, $phan_tram_giam, $gia_tri_giam, $bat_dau ?: null, $ket_thuc ?: null, $trang_thai, $dieu_kien, $mo_ta);
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
                $loadkgc = loadall_khunggiochieu();
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
                $loadkgc = loadall_khunggiochieu();
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

            // 🎬 KẾ HOẠCH CHIẾU PHIM - Tính năng tích hợp tạo lịch và khung giờ
            case "kehoach":
                include "./view/kehoachphim/kehoach.php";
                break;
                
            case "luu_kehoach":
                if (isset($_POST['ma_phim'])) {
                    $ma_phim = (int)$_POST['ma_phim'];
                    $ghi_chu = trim($_POST['ghi_chu'] ?? '');
                    $ma_rap = (int)$_SESSION['user1']['id_rap'];
                    
                    $success_total = 0;
                    $gio_bat_dau = $_POST['gio_bat_dau'] ?? [];
                    $ma_phong = $_POST['ma_phong'] ?? [];
                    
                    // Debug data
                    error_log("DEBUG - ma_phim: $ma_phim, ma_rap: $ma_rap");
                    error_log("DEBUG - gio_bat_dau: " . print_r($gio_bat_dau, true));
                    error_log("DEBUG - ma_phong: " . print_r($ma_phong, true));
                    
                    // Validation
                    if (empty($ma_phim) || empty($ma_rap)) {
                        header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Thiếu thông tin phim hoặc rạp"));
                        exit;
                    }
                    
                    if (empty($gio_bat_dau) || empty($ma_phong)) {
                        header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Thiếu thông tin khung giờ hoặc phòng"));
                        exit;
                    }
                    
                    // Lấy ngày từ form mới
                    $tu_ngay = $_POST['tu_ngay'];
                    $den_ngay = $_POST['den_ngay'];
                    
                    if (empty($tu_ngay) || empty($den_ngay)) {
                        header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Thiếu thông tin ngày chiếu"));
                        exit;
                    }
                    
                    error_log("DEBUG - tu_ngay: $tu_ngay, den_ngay: $den_ngay");
                    
                    // Tạo lịch từ ngày bắt đầu đến ngày kết thúc
                    $current_date = new DateTime($tu_ngay);
                    $end_date = new DateTime($den_ngay);
                    $total_days = 0;
                    
                    while ($current_date <= $end_date) {
                        $ngay_chieu = $current_date->format('Y-m-d');
                        error_log("DEBUG - Tạo lịch cho ngày: $ngay_chieu");
                        
                        $id_lich = them_lichchieu_kehoach($ma_phim, $ma_rap, $ngay_chieu, $ghi_chu);
                        error_log("DEBUG - ID lịch chiếu tạo: $id_lich");
                        
                        if ($id_lich) {
                            for ($i = 0; $i < count($gio_bat_dau); $i++) {
                                if (!empty($gio_bat_dau[$i]) && !empty($ma_phong[$i])) {
                                    error_log("DEBUG - Thêm khung giờ: " . $gio_bat_dau[$i] . " phòng: " . $ma_phong[$i]);
                                    $result = them_khunggiochieu($id_lich, $ma_phong[$i], $gio_bat_dau[$i]);
                                    if ($result) {
                                        $success_total++;
                                        error_log("DEBUG - Thành công thêm khung giờ");
                                    } else {
                                        error_log("DEBUG - Lỗi thêm khung giờ");
                                    }
                                }
                            }
                            $total_days++;
                        } else {
                            error_log("DEBUG - Lỗi tạo lịch chiếu cho ngày: $ngay_chieu");
                        }
                        
                        $current_date->add(new DateInterval('P1D'));
                    }
                    
                    error_log("DEBUG - Tổng days: $total_days, success: $success_total");
                    
                    // Redirect với thông báo
                    if ($total_days > 0 && $success_total > 0) {
                        header("Location: index.php?act=kehoach&msg=success");
                        exit;
                    } else {
                        $error_msg = "Không thể tạo kế hoạch chiếu. Days: $total_days, Success: $success_total";
                        header("Location: index.php?act=kehoach&msg=error&error=" . urlencode($error_msg));
                        exit;
                    }
                } else {
                    header("Location: index.php?act=kehoach&msg=error&error=" . urlencode("Dữ liệu form không hợp lệ"));
                    exit;
                }
                break;
                
            case "export_word_kehoach":
                if (isset($_POST['kehoach_id'])) {
                    $kehoach_id = (int)$_POST['kehoach_id'];
                    
                    // Load thông tin kế hoạch chiếu
                    $sql = "SELECT lc.*, p.tieu_de, p.thoi_luong_phim, r.name as ten_rap, pc.name as ten_phong
                            FROM lichchieu lc
                            INNER JOIN phim p ON p.id = lc.id_phim
                            INNER JOIN rap r ON r.id = lc.id_rap  
                            INNER JOIN phongchieu pc ON pc.id = (
                                SELECT id_phong FROM khung_gio_chieu WHERE id_lich_chieu = lc.id LIMIT 1
                            )
                            WHERE lc.id = ? AND lc.id_rap = ?";
                    
                    $ma_rap = (int)$_SESSION['user1']['id_rap'];
                    $kehoach = pdo_query_one($sql, $kehoach_id, $ma_rap);
                    
                    if ($kehoach) {
                        // Load khung giờ
                        $sql_khung = "SELECT thoi_gian_chieu FROM khung_gio_chieu WHERE id_lich_chieu = ? ORDER BY thoi_gian_chieu";
                        $khung_gio = pdo_query($sql_khung, $kehoach_id);
                        
                        // Tạo file Word
                        export_kehoach_word($kehoach, $khung_gio);
                    } else {
                        echo "<script>alert('Không tìm thấy kế hoạch chiếu!'); history.back();</script>";
                    }
                } else {
                    echo "<script>alert('Thiếu thông tin kế hoạch chiếu!'); history.back();</script>";
                }
                break;
                
            case "preview_kehoach":
                // AJAX endpoint để preview kế hoạch
                if (isset($_POST['ma_phim'])) {
                    $ma_phim = (int)$_POST['ma_phim'];
                    $ma_phong = (int)$_POST['ma_phong'];
                    $ngay_chieu = $_POST['ngay_chieu'];
                    $gia_ve = (int)$_POST['gia_ve'];
                    $gio_bat_dau = $_POST['gio_bat_dau'] ?? [];
                    
                    // Lấy thông tin chi tiết
                    $phim_info = pdo_query_one("SELECT ten_phim, thoi_luong FROM phim WHERE ma_phim = ?", $ma_phim);
                    $phong_info = pdo_query_one("SELECT ten_phong, so_ghe_ngoi FROM phong WHERE ma_phong = ?", $ma_phong);
                    
                    echo '<div class="preview-summary">';
                    echo '<h6><i class="zmdi zmdi-movie"></i> ' . $phim_info['ten_phim'] . '</h6>';
                    echo '<p><strong>Phòng:</strong> ' . $phong_info['ten_phong'] . ' (' . $phong_info['so_ghe_ngoi'] . ' ghế)</p>';
                    echo '<p><strong>Ngày chiếu:</strong> ' . date('d/m/Y', strtotime($ngay_chieu)) . '</p>';
                    echo '<p><strong>Giá vé:</strong> ' . number_format($gia_ve) . ' VNĐ</p>';
                    echo '<p><strong>Khung giờ:</strong></p>';
                    echo '<ul class="list-unstyled">';
                    foreach ($gio_bat_dau as $index => $gio) {
                        if (!empty($gio)) {
                            $gio_kt = $_POST['gio_ket_thuc'][$index] ?? '';
                            echo '<li>• ' . $gio . ' - ' . $gio_kt . '</li>';
                        }
                    }
                    echo '</ul>';
                    echo '</div>';
                }
                exit; // Đây là AJAX response
                break;
            
             //////////QL Phòng
            case "phong":
                $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                include "./view/phong/phong.php";
                break;
            case "xoaphong":
                if (isset($_GET['idxoa'])) {
                    xoa_phon($_GET['idxoa']);
                    $loadphongg = load_phong();
                    include "./view/phong/phong.php";
                }
                break;
            case "suaphong":
                if (isset($_GET['ids'])) {
                    $id_phong_edit = (int)$_GET['ids'];
                    $loadphong1 = loadone_phong($id_phong_edit);
                    // Inline seat-map actions
                    if (isset($_POST['tao_map'])) {
                        $rows = max(1, (int)($_POST['rows'] ?? 12));
                        $cols = max(1, (int)($_POST['cols'] ?? 18));
                        pg_generate_default($id_phong_edit, $rows, $cols);
                        $suatc = "Đã tạo sơ đồ mặc định";
                    }
                    if (isset($_POST['luu_map']) && isset($_POST['map_json'])) {
                        $list = json_decode($_POST['map_json'], true);
                        if (is_array($list)) { pg_replace_map($id_phong_edit, $list); $suatc = "Đã lưu sơ đồ ghế"; }
                        else { $error = "Dữ liệu sơ đồ không hợp lệ"; }
                    }
                    $map = pg_list($id_phong_edit);
                }
                include "./view/phong/sua.php";
                break;

                case "updatephong":

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
                    $id_rap = (int)($_SESSION['user1']['id_rap'] ?? 0);
                    $loadphong = $id_rap ? load_phong_by_rap($id_rap) : load_phong();
                   if (isset($_POST['len'])) {
                       //  $id = $_POST['id'];
                       $name = $_POST['name'];
                       $so_ghe = isset($_POST['so_ghe']) ? (int)$_POST['so_ghe'] : 0;
                       $dien_tich = isset($_POST['dien_tich']) ? (float)$_POST['dien_tich'] : 0;
                       if($name =='' || !$id_rap) {
                         $error = "vui lòng không để trống";
                         $loadphong1 = load_phong($id);
                         include "./view/phong/them.php";
                         break;
                         }else{
                       them_phong($name, $id_rap, $so_ghe, $dien_tich);
                       $suatc = "Thêm thành công";

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
                $loadkgc = loadall_khunggiochieu();
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
                        $loadkgc = loadall_khunggiochieu();
                        include "./view/suatchieu/thoigian/them.php";
                        break;
                    }
                    // Chặn trùng khung giờ cùng phòng trong ngày của lịch chiếu
                    if (kgc_conflict_exists($id_lc, $id_phong, $thoi_gian_chieu)) {
                        $error = "Khung giờ này đã tồn tại cho phòng trong ngày đã chọn";
                        $loadkgc = loadall_khunggiochieu();
                        include "./view/suatchieu/thoigian/them.php";
                        break;
                    }
                    them_kgc($id_lc, $id_phong, $thoi_gian_chieu);
                    $suatc = "Thêm thành công";
                }
                $loadkgc = loadall_khunggiochieu();
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
                $loadkgc = loadall_khunggiochieu();
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
                             $loadkgc = loadall_khunggiochieu();
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
