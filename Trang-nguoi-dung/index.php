<?php
session_start();

// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

include "model/pdo.php";
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
    script.src = `http://localhost:3000/bot.js?webhookUrl=https://aidemo.membee.app/webhook/54764430-2511-4013-8d8e-ad94171f4680/chat&title=Galaxy 
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
        case "rapchieu"://rạp chiếu
            include "view/rapchieu.php";
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

            if (isset($_SESSION['user']) && ($_SESSION['user'])) {

                include "view/dv2.php";
            } else {
                $error = "";
                $thongbao1= "";
                $thongbao['dangnhap'] = 'đăng nhập đi để đặt vé!';
                include 'view/login/dangnhap.php';
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
                $_SESSION['tong']['gia_ghe'] = $gia_tong; // Update with final total (seat + combo)
                
                // Convert seat array to string for database
                $ghe = is_array($ten_ghe) && !empty($ten_ghe) ? implode(',', $ten_ghe) : '';
                $ngay_tt = date('Y-m-d H:i:s');
                $id_user = $_SESSION['user']['id'];
                $id_kgc = $_SESSION['tong']['id_gio'];
                $combo = (is_array($ten_doan) && !empty($ten_doan)) ? implode(', ', $ten_doan) : null;
                $id_lc = $_SESSION['tong']['id_lichchieu'];
                $id_phim = $_SESSION['tong']['id_phim'];
                $id_rap = isset($_SESSION['tong']['id_rap']) ? $_SESSION['tong']['id_rap'] : null; // Lấy id_rap từ session
                
                $id_hd = them_hoa_don($ngay_tt, $gia_tong);
                if ($id_hd) {
                    $_SESSION['id_hd'] = $id_hd;
                    $id_ve = them_ve($gia_tong, $ngay_tt, $ghe, $id_user, $id_kgc, $id_hd, $id_lc, $id_phim, $combo, $id_rap); // Thêm id_rap

                    if ($id_ve) {
                        $_SESSION['id_ve'] = $id_ve;
                    } else {
                        echo "Đã xảy ra lỗi khi đặt vé. Vui lòng thử lại.";
                    }
                } else {
                    echo " xảy ra lỗi khi tạo hóa đơn. Vui lòng thử lại.";
                }

            }
            
            // Prepare data for payment view
            $ten_ghe = $_SESSION['tong']['ten_ghe'] ?? array('ghe' => array());
            $ten_doan = $_SESSION['tong']['ten_doan'] ?? array('doan' => array());
            
            include 'view/thanhtoan.php';
            break;

        case "dangxuat"://Đăng xuất
            dang_xuat();
            include "view/login/dangnhap.php";
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

        case 'xacnhan': //Chưa xong
            if (isset($_GET['message']) && ($_GET['message'] == 'Successful.')) {
                trangthai_hd($_SESSION['id_hd']);
                trangthai_ve($_SESSION['id_hd']);
                $load_ve_tt =  load_ve_tt($_SESSION['id_hd']);
                gui_mail_ve($load_ve_tt);
                require_once "view/ve_tt.php";
                break;
            }

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
                // Gọi hàm cập nhật trạng thái vé sang 'Đã hủy' (trang_thai = 3)
                huy_vephim($id);
                $thongbao = "Bạn đã hủy vé thành công.";
            }
            $loadone_ve = loadone_vephim($_POST['id']);
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

