<?php
session_start();

// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

include "model/pdo.php";

// Luôn reload session user từ database để lấy dữ liệu mới nhất (điểm vừa được cộng)
if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
    $user_updated = pdo_query_one("SELECT * FROM taikhoan WHERE id = ?", $_SESSION['user']['id']);
    if ($user_updated) {
        $_SESSION['user'] = $user_updated;
    }
}


include "model/loai_phim.php";
include "model/phim.php";
include "model/taikhoan.php";
include "model/lichchieu.php";
include "model/ve.php";
include "model/hoadon.php";
include "model/rap.php";
include "model/combo.php";
date_default_timezone_set("Asia/Ho_Chi_Minh");
$loadloai = loadall_loaiphim();
$loadphim = loadall_phim();
$loadphimhot = loadall_phim_hot();
$loadphimhome = loadall_phim_home();
// Load active rạp (used by header to render only rạp with upcoming showtimes)
$activeRaps = load_active_raps();
// Load all rạp (for menu full list)
$allRaps = loadall_rap();
include "view/header.php";
?>
<!-- Chèn script ngoài phần PHP -->
<script>
  (function () {
    const script = document.createElement('script');
    script.async = true;
    script.src = `http://localhost:3000/bot.js?webhookUrl=https://aidemo.workai.vn/webhook/bc6301f8-91ae-4357-be1f-4a99696fcd65/chat&title=Galaxy 
    Studio&subtitle=&messageBot=Chúng tôi hỗ trợ được gì cho anh chị.&welcomeBot=Chatbot hỗ trợ thông tin phim Galaxy studio.`;
    document.body.appendChild(script);
  })();
</script>

<?php
if(isset($_GET['act']) && $_GET['act']!=""){
    $act = $_GET['act'];
    switch ($act) {
        case "ctphim":  //Chi tiết phim
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $phim = loadone_phim($_GET['id']);
            }
            unset($_SESSION['mv']);
            include "view/ctphim.php";
            break;
        case "dsphim1":
            $dsp = loadall_phim();
            include "view/dsphim1.php";
            break;
        case "dsphim":  //Danh sách phim
            if (isset($_POST['kys']) && $_POST['kys'] != "") {

                $kys = $_POST['kys'];
            } else {
                $kys = "";
            }
            if (isset($_GET['id_loai']) && $_GET['id_loai'] > 0) {
                $id_loai = $_GET['id_loai'];
                $tenloai = load_ten_loai($id_loai);
            } else {
                $id_loai = 0;
            }
            $dsp = loadall_phim1($kys, $id_loai);
            $nameth = phim_select_all();
            include "view/dsphim.php";
            break;

        case "phimsapchieu": //Phim sắp chiếu (Chưa xong)
            $psc = load_phimsc();
            include "view/phimsc.php";
            break;
        case "phimdangchieu":  // Chưa xong
            $pdc = load_phimdc();
            include "view/phimdc.php";
            break;
        case "lienhe":
            include "view/lienhe.php";
            break;
        case "tintuc":
            include "view/tintuc-big.php";
            break;
        case "tintuc_chi_tiet":
            include "view/tintuc_chi_tiet.php";
            break;
        
        case "khuyenmai": // Trang khuyến mãi
            // Lấy danh sách tất cả khuyến mãi đang hoạt động
            $sql = "SELECT km.*, r.ten_rap 
                    FROM khuyen_mai km
                    LEFT JOIN rap_chieu r ON km.id_rap = r.id
                    WHERE km.trang_thai = 1 
                    AND km.ngay_bat_dau <= NOW() 
                    AND km.ngay_ket_thuc >= NOW() 
                    ORDER BY km.ngay_bat_dau DESC";
            $ds_khuyenmai = pdo_query($sql);
            include "view/khuyenmai.php";
            break;
        
        case "rapchieu"://rạp chiếu
            include "view/rapchieu.php";
            break;
        
        case "phim_theo_rap": // Xem phim đang chiếu tại rạp cụ thể
            $id_rap = isset($_GET['id_rap']) ? (int)$_GET['id_rap'] : 0;
            
            if ($id_rap <= 0) {
                // Nếu không có id_rap, redirect về trang chủ
                header("Location: index.php");
                exit;
            }
            
            // Lấy thông tin rạp
            $rap_info = loadone_rap($id_rap);
            
            // Lấy danh sách phim đang chiếu tại rạp này
            $ds_phim = [];
            if ($rap_info) {
                $ds_phim = load_phim_dang_chieu_theo_rap($id_rap);
            }
            
            include "view/phim_theo_rap.php";
            break;
        
        case "dangnhap"://Đăng nhập
            if (isset($_POST['login'])) {
                 $user = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
                $pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');
                $check_tk = check_tk($user, $pass);
                
                if ($user == '' || $pass == '') {
                      $error = "Vui lòng không để trống";
                      include "view/login/dangnhap.php";
                      break;
                } else {
                     if (is_array($check_tk) && $check_tk['vai_tro'] == 0) {
                        $_SESSION['user'] = $check_tk;
                        // Redirect để load lại header với trạng thái mới
                        echo '<script>window.location.href="index.php";</script>';
                        exit;
                    } else {
                         $thongbao = "Đăng nhập không thành công. Vui lòng kiểm tra tài khoản của bạn.";
                     }
                }
             }
                
        include "view/login/dangnhap.php";
                break;
  
            case "dangky": //đăng ký tk
                $min_password_length = 6;

                if (isset($_POST['dangky']) && $_POST['dangky'] != "") {
                    $name = $_POST['name'];
                    $sdt = $_POST['phone'];
                    $dc = $_POST['dia_chi'];
                    $user = $_POST['user'];
                    $pass = $_POST['pass'];
                    $email = $_POST['email'];

                    if (
                        !empty($name) && !empty($sdt) &&
                        !empty($dc) && !empty($user) &&
                        !empty($pass) && !empty($email)
                    ) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $thongbao = "Vui lòng nhập một địa chỉ email hợp lệ.";
                        } else if (strlen($pass) < $min_password_length) {
                            $thongbao = "Mật khẩu phải chứa ít nhất $min_password_length ký tự.";
                        } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $user)) {
                            $thongbao = "Tên người dùng không hợp lệ. Tên người dùng không được chứa khoảng trắng và dấu.";
                        } else {
                            // Kiểm tra email đã tồn tại
                            $email_check = check_email($email);
                            if ($email_check && $email_check['email'] == $email) {
                                $thongbao = "Email đã tồn tại!";
                            } else {
                                // Thêm tài khoản mới
                                insert_taikhoan($email, $user, $pass, $name, $sdt, $dc);
                                $thongbao = "Đăng ký thành công xin mời đăng nhập!";
                            }
                        }
                    } else {
                        $thongbao = "Vui lòng điền đầy đủ thông tin.";
                    }
                }
                include "view/login/dangky.php";
                break;

                case "quenmk": //Quên mật khẩu với OTP
                    $step = 1;
                    if (isset($_POST['send_otp'])) {
                        $email = $_POST['email'];
                        if ($email == '') {
                            $error = "Vui lòng không để trống email";
                        } else {
                            $user = check_email($email);
                            if ($user && $user['email'] == $email) {
                                $otp = rand(100000, 999999);
                                $_SESSION['otp'] = $otp;
                                $_SESSION['otp_email'] = $email;
                                $_SESSION['otp_time'] = time();
                                if (sendMailOTP($email, $otp)) {
                                    $success = "Đã gửi mã OTP về email. Vui lòng kiểm tra hộp thư.";
                                    $step = 2;
                                } else {
                                    $error = "Gửi email thất bại. Vui lòng thử lại.";
                                }
                            } else {
                                $error = "Email không tồn tại trong hệ thống.";
                            }
                        }
                    } elseif (isset($_POST['verify_otp'])) {
                        $step = 2;
                        $otp = $_POST['otp'];
                        if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_email'])) {
                            $error = "Phiên OTP đã hết hạn. Vui lòng thử lại.";
                            $step = 1;
                        } elseif ($otp == $_SESSION['otp'] && (time() - $_SESSION['otp_time'] <= 300)) {
                            $success = "Xác thực OTP thành công. Vui lòng nhập mật khẩu mới.";
                            $step = 3;
                        } else {
                            $error = "Mã OTP không đúng hoặc đã hết hạn.";
                        }
                    } elseif (isset($_POST['reset_pass'])) {
                        $step = 3;
                        if (!isset($_SESSION['otp_email'])) {
                            $error = "Phiên đã hết hạn. Vui lòng thử lại.";
                            $step = 1;
                        } else {
                            $pass1 = $_POST['pass1'];
                            $pass2 = $_POST['pass2'];
                            if ($pass1 == '' || $pass2 == '') {
                                $error = "Vui lòng nhập đầy đủ mật khẩu.";
                            } elseif ($pass1 !== $pass2) {
                                $error = "Mật khẩu nhập lại không khớp.";
                            } elseif (strlen($pass1) < 6) {
                                $error = "Mật khẩu phải có ít nhất 6 ký tự.";
                            } else {
                                // Cập nhật mật khẩu mới
                                update_pass_by_email($_SESSION['otp_email'], $pass1);
                                $success = "Đổi mật khẩu thành công! Bạn có thể đăng nhập với mật khẩu mới.";
                                unset($_SESSION['otp']);
                                unset($_SESSION['otp_email']);
                                unset($_SESSION['otp_time']);
                                $step = 1;
                            }
                        }
                    }
                    include "view/login/quenmk.php";
                    break;
                    
                case "suatk":
                    if (isset($_GET['idsua'])) {
                         $loadtk = loadone_taikhoan($_GET['idsua']);
                    }
                    include "view/login/sua.php";
                    break;
                case "updatetk":
                        if (isset($_GET['idsua'])) 
                        {
                            $loadtk = loadone_taikhoan($_GET['idsua']);
                        }
                        if (isset($_POST['capnhat']) && $_POST['capnhat'] != "") 
                        {
                            if (!empty($_POST['phone']) &&!empty($_POST['dia_chi']) && !empty($_POST['user']) && !empty($_POST['email'])) 
                            {
                                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
                                {                                 
                                    if (preg_match('/^[a-zA-Z0-9_]+$/', $_POST['user'])) {
                                    $id = $_POST['id'];
                                    $user = $_POST['user'];
                                    $email = $_POST['email'];
                                    $sdt = $_POST['phone'];
                                    $dc = $_POST['dia_chi'];
                                    sua_tk($id, $user, $email, $sdt, $dc);
                                    $thongbao= "Sửa thành công ";
                                    } else {
                                        $thongbao= "Tên người dùng không hợp lệ. Tên người dùng không được chứa khoảng trắng và dấu.";
                                    }
                                }
                            } else {
                                $thongbao= "Vui lòng điền đầy đủ thông tin.";
                            }
                        }
                        $loadtk = loadone_taikhoan($id);
                        include "view/login/sua.php";
                        // } else {
                        //     include "view/login/sua.php";
                        // }
                        break;

        case "datve": //Đặt vé - Flow: Chọn rạp → Chọn ngày → Chọn giờ
            $realtime = date('Y-m-d H:i:s');
            
            // Lấy thông tin phim
            $id_phim = isset($_GET['id']) && $_GET['id'] > 0 ? (int)$_GET['id'] : 0;
            if ($id_phim == 0) {
                echo '<script>window.location.href="index.php";</script>';
                exit;
            }
            
            $phim = loadone_phim($id_phim);
            if (!$phim) {
                echo '<script>window.location.href="index.php";</script>';
                exit;
            }
            
            // Lấy các tham số
            $id_rap = isset($_GET['id_rap']) && $_GET['id_rap'] ? (int)$_GET['id_rap'] : 0;
            $ngay_chieu = isset($_GET['ngay_chieu']) ? $_GET['ngay_chieu'] : '';
            $id_lc = isset($_GET['id_lc']) ? (int)$_GET['id_lc'] : 0;
            
            // Reset session khi bắt đầu đặt vé mới
            unset($_SESSION['mv']);
            unset($_SESSION['tong']);
            
            // Lấy danh sách rạp đang chiếu phim này
            $raps_showing = get_raps_showing_phim($id_phim);
            
            // Nếu không có rạp nào chiếu phim này
            if (empty($raps_showing)) {
                $_SESSION['thongbao_datve'] = "⚠️ Phim này hiện chưa có lịch chiếu tại các rạp. Vui lòng quay lại sau!";
                echo '<script>window.location.href="index.php?act=ctphim&id=' . $id_phim . '";</script>';
                exit;
            }
            
            // FLOW: Nếu chưa chọn rạp → hiển thị danh sách rạp
            if ($id_rap == 0) {
                $selected_rap = 0;
                $rap_info = null;
                $dates = [];
                $times = [];
                include "view/dv.php";
                break;
            }
            
            // Đã chọn rạp → lấy thông tin rạp
            $rap_info = loadone_rap($id_rap);
            if (!$rap_info) {
                echo '<script>window.location.href="index.php?act=datve&id=' . $id_phim . '";</script>';
                exit;
            }
            
            // Kiểm tra rạp có chiếu phim này không
            if (!check_rap_showing_phim($id_rap, $id_phim)) {
                $thongbao = "Rạp này không chiếu phim này.";
                $selected_rap = 0;
                $dates = [];
                $times = [];
                include "view/dv.php";
                break;
            }
            
            // Lấy danh sách ngày chiếu
            $dates = get_dates_for_phim_at_rap($id_phim, $id_rap);
            
            // FLOW: Nếu chưa chọn ngày → hiển thị danh sách ngày
            if (empty($ngay_chieu)) {
                $selected_rap = $id_rap;
                $selected_date = '';
                $times = [];
                include "view/dv.php";
                break;
            }
            
            // Đã chọn ngày → lấy danh sách giờ chiếu
            $times = get_showtimes_for_date($id_phim, $id_rap, $ngay_chieu);
            
            // NOTE: Các hàm cũ đã bị loại bỏ, giờ dùng get_showtimes_for_date()
            // if ($id_lc) {
            //     $khunggio = khunggiochieu_select_by_idxc($id_lc);
            // } else {
            //     $khunggio = [];
            // }
            // $lc = lichchieu_select_by_id_phim_and_rap($id_phim, $id_rap);
            
            $selected_rap = $id_rap;
            $selected_date = $ngay_chieu;
            
            include "view/dv.php";
            break;

        case "datve2": //Đặt vé chọn ghế

            // Always update session if GET parameters are provided (new time slot selected)
            if (isset($_GET['id']) && isset($_GET['id_lc']) && isset($_GET['id_g'])) {
                $id_phim = $_GET['id'];
                $id_lichchieu = $_GET['id_lc'];
                $id_gio = $_GET['id_g'];
                $_SESSION['mv'] = [$id_phim, $id_lichchieu, $id_gio];
                $list_lc = load_lc_p($id_phim, $id_lichchieu, $id_gio);
            } elseif (isset($_SESSION['mv'])) {
                // Use existing session data if no new parameters
                $list_lc = load_lc_p($_SESSION['mv'][0], $_SESSION['mv'][1], $_SESSION['mv'][2]);
            } else {
                // Fallback - redirect back to movie selection
                header('Location: index.php?act=datve');
                exit;
            }
            $_SESSION['tong'] = $list_lc;

            // XỬ LÝ THÔNG TIN KHÁCH VÃNG LAI
            if (isset($_POST['guest_name']) && isset($_POST['guest_phone']) && isset($_POST['guest_email'])) {
                $guest_name = trim($_POST['guest_name']);
                $guest_phone = trim($_POST['guest_phone']);
                $guest_email = trim($_POST['guest_email']);
                
                // Validate
                if (empty($guest_name) || empty($guest_phone) || empty($guest_email)) {
                    $thongbao['guest_error'] = '❌ Vui lòng điền đầy đủ thông tin!';
                    include 'view/login/guest_info.php';
                    break;
                }
                
                // Validate phone (10 số bắt đầu bằng 0)
                if (!preg_match('/^0[0-9]{9}$/', $guest_phone)) {
                    $thongbao['guest_error'] = '❌ Số điện thoại không hợp lệ (phải có 10 số)!';
                    include 'view/login/guest_info.php';
                    break;
                }
                
                // Validate email
                if (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
                    $thongbao['guest_error'] = '❌ Email không hợp lệ!';
                    include 'view/login/guest_info.php';
                    break;
                }
                
                // Tạo tài khoản khách vãng lai
                $guest_account = create_guest_account($guest_name, $guest_phone, $guest_email);
                
                if ($guest_account) {
                    // Lưu vào session như user bình thường
                    $_SESSION['user'] = $guest_account;
                    $_SESSION['is_guest'] = true; // Đánh dấu là khách vãng lai
                    
                    // Redirect về datve2 để hiển thị trang chọn ghế (dùng JS vì header đã gửi)
                    echo '<script>window.location.href="index.php?act=datve2";</script>';
                    exit;
                } else {
                    $thongbao['guest_error'] = '❌ Có lỗi xảy ra. Vui lòng thử lại!';
                    include 'view/login/guest_info.php';
                    break;
                }
            }

            // KIỂM TRA LOGIN/GUEST
            if (isset($_SESSION['user']) && ($_SESSION['user'])) {
                // Đã login hoặc đã nhập thông tin guest → Hiển thị trang chọn ghế
                include "view/dv2.php";
            } else {
                // Chưa login → Hiển thị form nhập thông tin khách vãng lai
                include 'view/login/guest_info.php';
            }
            break;
            
        case "dv3": //Chọn combo đồ ăn
            // Process seat selection from POST
            if (isset($_POST['tiep_tuc']) && $_POST['tiep_tuc']) {
                // Get seat data from POST
                $ten_ghe_array = isset($_POST['ten_ghe']) ? $_POST['ten_ghe'] : array();
                $gia_ghe = $_POST['giaghe'] ?? 0;
                
                // Validate seat selection
                if (empty($ten_ghe_array)) {
                    $thongbaoghe = "Phải chọn ghế";
                    include "view/dv2.php";
                    break;
                }
                
                // Store seat price and selection for combo page
                $_SESSION['tong']['gia_ghe'] = $gia_ghe;
                $_SESSION['tong']['ten_ghe'] = array('ghe' => $ten_ghe_array);
            }
            
            // Ensure we have seat data before showing combo page
            if (!isset($_SESSION['tong']['gia_ghe'])) {
                // No seat data - redirect back to seat selection
                header('Location: index.php?act=datve2');
                exit;
            }
            
            // Prepare data for view
            $ten_ghe = $_SESSION['tong']['ten_ghe'] ?? array('ghe' => array());
            $ten_doan = $_SESSION['tong']['ten_doan'] ?? array('doan' => array());
            
            // Lấy combo theo rạp
            $id_rap = isset($_SESSION['tong']['id_rap']) ? $_SESSION['tong']['id_rap'] : 0;
            if ($id_rap > 0) {
                $combos = get_combos_for_rap($id_rap);
            } else {
                $combos = []; // Nếu không có rạp, không hiển thị combo
            }
            
            include 'view/doan.php';
            break;
            
            include 'view/doan.php';
            break;
        case "dv4": //Vô trang xác nhận thanh toán
            if (isset($_POST['tiep_tuc']) && ($_POST['tiep_tuc'])) 
            {
                // Get selected seats from session (already selected in dv2)  
                $ten_ghe_data = $_SESSION['tong']['ten_ghe'] ?? array();
                $ten_ghe = isset($ten_ghe_data['ghe']) ? $ten_ghe_data['ghe'] : array();
                
                // Get selected combos from form
                $ten_doan = isset($_POST['ten_do_an']) ? $_POST['ten_do_an'] : array();
                
                // Get total price (seat + combo) from form
                $gia_tong = $_POST['giaghe'] ?? 0;
                
                // Store combo selection and final price in session
                $_SESSION['tong']['ten_doan'] = array('doan' => $ten_doan);
                $_SESSION['tong']['gia_ghe'] = $gia_tong; // Update with final total (seat + combo) - CHƯA GIẢM GIÁ
                
                // Convert seat array to string for storage
                $ghe = is_array($ten_ghe) && !empty($ten_ghe) ? implode(',', $ten_ghe) : '';
                $combo = (is_array($ten_doan) && !empty($ten_doan)) ? implode(', ', $ten_doan) : null;
                
                // Lưu thông tin vào session để dùng sau khi thanh toán
                $_SESSION['tong']['ghe_string'] = $ghe;
                $_SESSION['tong']['combo_string'] = $combo;
                
                // Tính giá cuối cùng (sau giảm giá nếu có)
                $gia_luu_db = $gia_tong; // Mặc định dùng giá gốc
                if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
                    $gia_luu_db = $_SESSION['tong']['gia_sau_giam']; // Dùng giá đã giảm
                }
                $_SESSION['tong_tien'] = $gia_luu_db; // Lưu để dùng khi thanh toán
                
                // ⚠️ KHÔNG TẠO VÉ Ở ĐÂY! Chỉ lưu thông tin vào session
                // Vé sẽ được tạo ở case 'xacnhan' sau khi thanh toán thành công
            }
            
            // Prepare data for payment view
            $ten_ghe = $_SESSION['tong']['ten_ghe'] ?? array('ghe' => array());
            $ten_doan = $_SESSION['tong']['ten_doan'] ?? array('doan' => array());
            
            include 'view/thanhtoan.php';
            break;

        case "dangxuat"://Đăng xuất
            dang_xuat();
            // Redirect để load lại header với trạng thái mới
            echo '<script>window.location.href="index.php";</script>';
            exit;
            break;

        case "lich_su_diem": // Lịch sử tích điểm
            if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] != 0) {
                // Chỉ thành viên mới có quyền xem lịch sử điểm
                echo '<script>alert("Bạn cần đăng nhập với tài khoản thành viên để xem lịch sử điểm!"); window.location.href="index.php?act=dangnhap";</script>';
                exit;
            }
            include "view/lich_su_diem.php";
            break;

        case "doimk": //Đổi mật khẩu
            if (isset($_POST['capnhat']) && $_POST['capnhat'] != "") {
                $id = $_POST['id'];
                $pass = $_POST['pass'];
                $passmoi = $_POST['passmoi'];
                $passmoi1 = $_POST['passmoi1'];
                $old_pass = mkcu($id);
                if($pass==''||$passmoi==''||$passmoi1==''){
                    $error = "vui lòng không để trống";
                }
                if($pass != $old_pass){
                    $error = "Mật khẩu cũ không đúng";
                }

                // Kiểm tra mật khẩu mới có trùng mật khẩu cũ không
                if($passmoi != $passmoi1){
                    $error = "Mật khẩu mới không trùng nhau"; 
                }

                if(!isset($error)){
                    doi_tk($id,$passmoi); 
                    $error = "đổi mật khẩu thành công";
                    // $_SESSION['user'] = check_tk($user, $pass);
                    include "view/login/doimk.php";  
                }
                else{
                    include "view/login/doimk.php"; 
                }
            } else {
                include "view/login/doimk.php";
            }
            break;
        
        case  "thanhtoan" : //Trang thanh toán
            include "view/thanhtoan.php";
            break;

        case "theloai": //Trang thể loại phim
            if (isset($_POST['kys']) && $_POST['kys'] != "") {

                $kys = $_POST['kys'];
            } else {
                $kys = "";
            }
            if (isset($_GET['id_loai']) && $_GET['id_loai'] > 0) {
                $id_loai = $_GET['id_loai'];
                $ten_loai = load_ten_loai($id_loai);
            } else {
                $id_loai = 0;
            }
            $dsp = loadall_phim1($kys, $id_loai);


            include "view/theloaiphim.php";
            break;

        case "ve" : //Trang vẽ đã mua
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $load_ve = load_ve($_GET['id']);
            }
            include "view/ve.php";
            break;

        case 'xacnhan': //Thanh toán thành công
            // ============ TẠO VÉ VÀ HÓA ĐƠN SAU KHI THANH TOÁN ============
            // Chỉ tạo nếu chưa có hoặc đã thanh toán mới
            $da_tao_ve_key = 'da_tao_ve_' . session_id();
            
            if (!isset($_SESSION['id_hd']) || !isset($_SESSION[$da_tao_ve_key])) {
                // Lấy thông tin từ session
                $id_tk = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 0;
                
                if ($id_tk == 0) {
                    echo '<script>alert("Lỗi: Bạn cần đăng nhập để thanh toán!"); window.location.href="index.php";</script>';
                    exit;
                }
                
                // Lấy thông tin đặt vé
                $ten_ghe_data = $_SESSION['tong']['ten_ghe'] ?? array();
                $ten_ghe = isset($ten_ghe_data['ghe']) ? $ten_ghe_data['ghe'] : array();
                $ghe = isset($_SESSION['tong']['ghe_string']) ? $_SESSION['tong']['ghe_string'] : implode(',', $ten_ghe);
                
                $combo = isset($_SESSION['tong']['combo_string']) ? $_SESSION['tong']['combo_string'] : '';
                $ngay_tt = date('Y-m-d H:i:s');
                $id_kgc = $_SESSION['tong']['id_gio'] ?? 0;
                $id_lc = $_SESSION['tong']['id_lichchieu'] ?? 0;
                $id_phim = $_SESSION['tong']['id_phim'] ?? 0;
                $id_rap = $_SESSION['tong']['id_rap'] ?? 0;
                
                // Lấy giá cuối cùng (ưu tiên lấy từ gia_sau_giam nếu có)
                $gia_luu_db = 0;
                if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
                    $gia_luu_db = (int)$_SESSION['tong']['gia_sau_giam'];
                } elseif (isset($_SESSION['tong_tien']) && $_SESSION['tong_tien'] > 0) {
                    $gia_luu_db = (int)$_SESSION['tong_tien'];
                } elseif (isset($_SESSION['tong']['gia_ghe']) && $_SESSION['tong']['gia_ghe'] > 0) {
                    $gia_luu_db = (int)$_SESSION['tong']['gia_ghe'];
                }
                
                error_log("=== DEBUG GIÁ VÉ ===");
                error_log("SESSION tong_tien: " . ($_SESSION['tong_tien'] ?? "NOT SET"));
                error_log("SESSION tong[gia_sau_giam]: " . ($_SESSION['tong']['gia_sau_giam'] ?? "NOT SET"));
                error_log("SESSION tong[gia_ghe]: " . ($_SESSION['tong']['gia_ghe'] ?? "NOT SET"));
                error_log("Gia_luu_db (dùng để tạo vé): " . $gia_luu_db);
                error_log("===============");
                
                if (empty($ghe) || $id_kgc == 0 || $id_lc == 0 || $id_phim == 0) {
                    echo '<script>alert("Lỗi: Thông tin đặt vé không đầy đủ!"); window.location.href="index.php";</script>';
                    exit;
                }
                
                // Tạo hóa đơn trước
                require_once 'model/hoadon.php';
                $id_hd = them_hoa_don($ngay_tt, $gia_luu_db);
                
                if ($id_hd) {
                    $_SESSION['id_hd'] = $id_hd;
                    
                    // Tạo vé
                    require_once 'model/ve.php';
                    $id_ve = them_ve($gia_luu_db, $ngay_tt, $ghe, $id_tk, $id_kgc, $id_hd, $id_lc, $id_phim, $combo, $id_rap);
                    
                    if ($id_ve) {
                        $_SESSION['id_ve'] = $id_ve;
                        $_SESSION[$da_tao_ve_key] = true; // Đánh dấu đã tạo vé
                        
                        error_log("✅ Đã tạo vé #$id_ve và hóa đơn #$id_hd sau thanh toán");
                    } else {
                        echo '<script>alert("Lỗi khi tạo vé!"); window.location.href="index.php";</script>';
                        exit;
                    }
                } else {
                    echo '<script>alert("Lỗi khi tạo hóa đơn!"); window.location.href="index.php";</script>';
                    exit;
                }
            }
            
            // Kiểm tra xem có thông tin hóa đơn không
            if (isset($_SESSION['id_hd']) && $_SESSION['id_hd'] > 0) {
                // Cập nhật trạng thái thanh toán (chuyển từ pending sang đã thanh toán)
                trangthai_hd($_SESSION['id_hd']);
                trangthai_ve($_SESSION['id_hd']);
                
                $load_ve_tt =  load_ve_tt($_SESSION['id_hd']);
                
                // ============ CỘNG ĐIỂM CHO KHÁCH HÀNG ============
                if (isset($_SESSION['user']) && $_SESSION['user']) {
                    $id_tk = (int)$_SESSION['user']['id'];
                    $vai_tro = (int)$_SESSION['user']['vai_tro'];
                    
                    // Debug log
                    error_log("=== CỘNG ĐIỂM DEBUG ===");
                    error_log("ID tài khoản: " . $id_tk);
                    error_log("Vai trò: " . $vai_tro);
                    error_log("Load vé TT: " . ($load_ve_tt ? "Có" : "Không"));
                    
                    // Chỉ cộng điểm cho khách hàng thành viên (vai_tro = 0), không cộng cho guest (-1)
                    // VÀ chỉ cộng nếu chưa cộng điểm cho đơn hàng này (kiểm tra session)
                    $da_cong_diem_key = 'da_cong_diem_' . $_SESSION['id_hd'];
                    
                    if ($vai_tro == 0 && $load_ve_tt && !isset($_SESSION[$da_cong_diem_key])) {
                        require_once 'model/diem.php';
                        
                        $id_ve = isset($load_ve_tt['id']) ? (int)$load_ve_tt['id'] : 0;
                        $tong_tien = isset($load_ve_tt['thanh_tien']) ? (int)$load_ve_tt['thanh_tien'] : 0;
                        
                        error_log("ID vé: " . $id_ve);
                        error_log("Tổng tiền: " . $tong_tien);
                        
                        if ($tong_tien <= 0) {
                            error_log("KHÔNG CỘNG ĐIỂM vì tổng tiền = 0");
                        } else {
                            // Tính điểm từ vé: Mỗi 1000 VND = 1 điểm (giống Momo)
                            $diem_ve = intval($tong_tien / 1000);
                            
                            error_log("Điểm từ vé: " . $diem_ve . " (tỷ lệ: mỗi 1000 VND = 1 điểm)");
                            
                            // Không cộng combo nữa, chỉ cộng từ giá vé
                            $tong_diem = $diem_ve;
                            
                            error_log("Tổng điểm cần cộng: " . $tong_diem);
                            
                            // Cộng điểm
                            if ($tong_diem > 0) {
                                $diem_da_cong = cong_diem(
                                    $id_tk, 
                                    $tong_diem, 
                                    "Tích điểm từ đơn hàng #" . $_SESSION['id_hd'], 
                                    $id_ve, 
                                    $_SESSION['id_hd'],
                                    false  // Không nhân hệ số hạng (chỉ muốn 1000 VND = 1 điểm)
                                );
                                
                                error_log("Điểm đã cộng (sau khi nhân hệ số): " . $diem_da_cong);
                                
                                if ($diem_da_cong) {
                                    // Lưu vào session để hiển thị thông báo
                                    $_SESSION['diem_cong_moi'] = $diem_da_cong;
                                    
                                    // Kiểm tra có nâng hạng không
                                    $hang_moi = kiem_tra_nang_hang($id_tk);
                                    if ($hang_moi) {
                                        $_SESSION['hang_moi'] = $hang_moi['ten_hang'];
                                        error_log("Nâng hạng lên: " . $hang_moi['ten_hang']);
                                    }
                                    
                                    // ============ TRỪ ĐIỂM ĐÃ ĐỔI (NẾU CÓ) ============
                                    if (isset($_SESSION['tong']['diem_doi']) && $_SESSION['tong']['diem_doi'] > 0) {
                                        $diem_tru = (int)$_SESSION['tong']['diem_doi'];
                                        $giam_gia_diem = (int)($_SESSION['tong']['giam_gia_diem'] ?? 0);
                                        
                                        $diem_da_tru = tru_diem(
                                            $id_tk,
                                            $diem_tru,
                                            "Đổi điểm giảm giá đơn hàng #" . $_SESSION['id_hd'] . " (-" . number_format($giam_gia_diem) . " VND)",
                                            $id_ve,
                                            $_SESSION['id_hd']
                                        );
                                        
                                        if ($diem_da_tru) {
                                            $_SESSION['diem_da_doi'] = $diem_tru;
                                            error_log("Đã trừ " . $diem_tru . " điểm đổi voucher");
                                        }
                                        
                                        // Xóa thông tin đổi điểm khỏi session
                                        unset($_SESSION['tong']['diem_doi']);
                                        unset($_SESSION['tong']['giam_gia_diem']);
                                    }
                                    
                                    // Reload thông tin user để cập nhật điểm
                                    $user_updated = pdo_query_one("SELECT * FROM taikhoan WHERE id = ?", $id_tk);
                                    $_SESSION['user'] = $user_updated;
                                    
                                    // Đánh dấu đã cộng điểm cho đơn hàng này
                                    $_SESSION[$da_cong_diem_key] = true;
                                    
                                    error_log("Điểm tích lũy sau khi cập nhật: " . $user_updated['diem_tich_luy']);
                                }
                            }
                        }
                    } else {
                        if ($vai_tro != 0) {
                            error_log("KHÔNG CỘNG ĐIỂM vì vai_tro=" . $vai_tro . " (không phải thành viên)");
                        } elseif (!$load_ve_tt) {
                            error_log("KHÔNG CỘNG ĐIỂM vì không có thông tin vé");
                        } elseif (isset($_SESSION[$da_cong_diem_key])) {
                            error_log("KHÔNG CỘNG ĐIỂM vì đã cộng rồi cho đơn hàng #" . $_SESSION['id_hd']);
                        }
                    }
                }
                
                gui_mail_ve($load_ve_tt);
                require_once "view/ve_tt.php";
            } else {
                // Không có id_hd trong session
                echo '<script>alert("Không tìm thấy thông tin đơn hàng!"); window.location.href="index.php";</script>';
            }
            break;

        case "ctve": //xem chi tiết vé đã mua
            if (isset($_GET['id']) && ($_GET['id'] > 0)){
                $loadone_ve =  loadone_vephim($_GET['id']);
            }
            include "view/chitiet_ve.php";
            break;

        // case "huy_ve":  //Chưa xong
        //     if(isset($_POST['capnhat'])){
        //         $id = $_POST['id'];
        //         huy_vephim($id);
        //     }
        //     // Sử dụng $_POST['id'] thay vì $_GET['id']
        //     $loadone_ve =  loadone_vephim($_POST['id']);
        //     include "view/chitiet_ve.php";
        //     break;
        case "huy_ve":
            if (isset($_POST['capnhat'])) {
                $id = $_POST['id'];
                
                // Kiểm tra xem có được phép hủy không
                $check = can_cancel_or_exchange_ticket($id);
                if ($check['can_cancel']) {
                    huy_vephim($id);
                    $thongbao = "✅ Bạn đã hủy vé thành công!";
                } else {
                    $thongbao = "❌ " . $check['message'];
                }
            }
            
            if (isset($_POST['id'])) {
                $loadone_ve = loadone_vephim($_POST['id']);
                // Lấy thông tin kiểm tra
                $ticket_check = can_cancel_or_exchange_ticket($_POST['id']);
            }
            include "view/chitiet_ve.php";
            break;

        case "quetve":
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                // Xác nhận vé nếu có POST
                if (isset($_POST['xacnhan']) && isset($_POST['id_ve'])) {
                    $id_ve = $_POST['id_ve'];
                    $sql = "UPDATE ve SET trang_thai = 2 WHERE id = $id_ve";
                    pdo_execute($sql);
                }
                $loadone_ve = loadone_vephim($_GET['id']);
                include "view/form_quetve.php";
            }
            break;

        case "zalopay_callback":
            // Xử lý callback từ ZaloPay (IPN - Instant Payment Notification)
            // Đây là server-to-server notification khi thanh toán thành công
            
            // Lấy callback data từ POST request
            $result = [];
            
            try {
                // Đọc dữ liệu từ request body
                $postdata = file_get_contents('php://input');
                $postdatajson = json_decode($postdata, true);
                
                // Log để debug
                error_log("ZaloPay Callback received: " . print_r($postdatajson, true));
                
                // Load ZaloPay config
                require_once 'view/momo/xuly_zalopay.php';
                
                // Verify MAC signature để đảm bảo request từ ZaloPay
                $reqMac = $postdatajson["mac"];
                
                $mac = hash_hmac("sha256", 
                    $postdatajson["data"], 
                    defined('ZALOPAY_KEY2') ? ZALOPAY_KEY2 : ''
                );
                
                if (strcmp($mac, $reqMac) != 0) {
                    // MAC không hợp lệ - request giả mạo
                    $result["return_code"] = -1;
                    $result["return_message"] = "mac not equal";
                    error_log("ZaloPay callback MAC verification failed");
                } else {
                    // MAC hợp lệ - xử lý đơn hàng
                    $dataJson = json_decode($postdatajson["data"], true);
                    
                    // Lấy thông tin đơn hàng
                    $app_trans_id = $dataJson["app_trans_id"];
                    $zp_trans_id = $dataJson["zp_trans_id"];
                    $amount = $dataJson["amount"];
                    
                    error_log("ZaloPay payment successful - TransID: $app_trans_id, ZP_TransID: $zp_trans_id, Amount: $amount");
                    
                    // Cập nhật database nếu cần
                    // (Ví dụ: cập nhật trạng thái đơn hàng, lưu zp_trans_id)
                    
                    $result["return_code"] = 1;
                    $result["return_message"] = "success";
                }
            } catch (Exception $e) {
                $result["return_code"] = 0; 
                $result["return_message"] = $e->getMessage();
                error_log("ZaloPay callback error: " . $e->getMessage());
            }
            
            // Trả về JSON response cho ZaloPay
            header('Content-Type: application/json');
            echo json_encode($result);
            exit; // Dừng execution sau khi trả response

    }
}else{
    unset($_SESSION['id_hd']);
    unset($_SESSION['id_ve']);
    unset($_SESSION['mv']);
    unset($_SESSION['tong']);
    include "view/home.php";
}
include "view/footer.php";

?>

