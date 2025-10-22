<?php
// Định nghĩa vai trò (giữ nguyên số cũ và mở rộng)
// 0: Khách hàng (thành viên)
// 1: Nhân viên rạp
// 2: Admin hệ thống (quản trị toàn bộ hệ thống)
// 3: Quản lí rạp (manager 1 rạp cụ thể)
// 4: Quản lí cụm rạp (quản lí nhiều rạp)

define('ROLE_KHACH_HANG', 0);
define('ROLE_NHAN_VIEN', 1);
define('ROLE_ADMIN_HE_THONG', 2);
define('ROLE_QUAN_LY_RAP', 3);
define('ROLE_QUAN_LY_CUM', 4);

function role_label($vai_tro)
{
    switch ((int)$vai_tro) {
        case ROLE_ADMIN_HE_THONG: return 'Admin hệ thống';
        case ROLE_QUAN_LY_CUM: return 'Quản lí cụm rạp';
        case ROLE_QUAN_LY_RAP: return 'Quản lí rạp';
        case ROLE_NHAN_VIEN: return 'Nhân viên rạp';
        case ROLE_KHACH_HANG: return 'Khách hàng';
        default: return 'Khác';
    }
}

// Bản đồ quyền theo action (act)
function permission_map()
{
    return [
        // Trang chủ admin (dashboard)
        'home' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM, ROLE_QUAN_LY_RAP, ROLE_NHAN_VIEN],

        // Loại phim + Phim: chỉ Admin hệ thống và Quản lí cụm
        'QLloaiphim' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'themloai'   => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'sualoai'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'xoaloai'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'updateloai' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],

        'QLphim'     => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'themphim'   => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'suaphim'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'updatephim' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'xoaphim'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],

        // Rạp chiếu: Admin hệ thống và Quản lí cụm được quản lí danh mục rạp
        'QLrap'  => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'themrp' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'suarp'  => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'xoarp'  => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],

        // Phòng, suất chiếu: chỉ Quản lí rạp (nhân viên thao tác vé)
        'xoaphong'    => [ROLE_QUAN_LY_RAP],
        'suaphong'    => [ROLE_QUAN_LY_RAP],
        'updatephong' => [ROLE_QUAN_LY_RAP], // Quyền cập nhật thông tin phòng
        'phong'        => [ROLE_QUAN_LY_RAP],
        'themphong'   => [ROLE_QUAN_LY_RAP],
        'QLsuatchieu'  => [ROLE_QUAN_LY_RAP],
        'sualichchieu' => [ROLE_QUAN_LY_RAP],
        'themlichchieu'=> [ROLE_QUAN_LY_RAP],
        'updatelichchieu'=>[ROLE_QUAN_LY_RAP],
        'kehoach'       => [ROLE_QUAN_LY_RAP], // Hiển thị form kế hoạch chiếu
        'kehoach_chieu' => [ROLE_QUAN_LY_RAP], // Kế hoạch chiếu phim
        'luu_kehoach'   => [ROLE_QUAN_LY_RAP], // Lưu kế hoạch chiếu
        'xem_kehoach'   => [ROLE_QUAN_LY_RAP], // Xem chi tiết kế hoạch đã gửi
        'sua_kehoach'   => [ROLE_QUAN_LY_RAP], // Chỉnh sửa kế hoạch chờ duyệt
        'export_kehoach' => [ROLE_QUAN_LY_RAP], // Export Word kế hoạch chi tiết
        'thu_hoi_kehoach' => [ROLE_QUAN_LY_RAP], // Thu hồi/xóa kế hoạch đã gửi
        'export_word_kehoach' => [ROLE_QUAN_LY_RAP], // Export Word kế hoạch chiếu
        'thoigian'     => [ROLE_QUAN_LY_RAP],
        'themthoigian' => [ROLE_QUAN_LY_RAP],
        'suathoigian'  => [ROLE_QUAN_LY_RAP],
        'xoathoigian'  => [ROLE_QUAN_LY_RAP],

        // Vé: Nhân viên rạp, Quản lí rạp, Admin hệ thống
        've'            => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'suavephim'     => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'updatevephim'  => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'ctve'          => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'capnhat_tt_ve' => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'doi_hoan_ve'   => [ROLE_QUAN_LY_RAP],
        'duyet_doihoan' => [ROLE_QUAN_LY_RAP],
        'tuchoi_doihoan'=> [ROLE_QUAN_LY_RAP],

        // Nhân viên: lịch làm, xin nghỉ, scan vé
        'nv_lichlamviec' => [ROLE_NHAN_VIEN],
        'nv_chamcong'    => [ROLE_NHAN_VIEN], // Self-service attendance check-in/out
        'xinnghi'        => [ROLE_NHAN_VIEN],
        'scanve'         => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP],
        'nv_datve'       => [ROLE_NHAN_VIEN],
        'nv_baocao'      => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP],
        'nv_xeplich'     => [ROLE_NHAN_VIEN],

        // Quản lý rạp: phân công lịch, duyệt nghỉ, thiết bị
        'ql_lichlamviec' => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        // 'ql_lichlamviec_calendar_enhanced' => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG], // Enhanced calendar với phân công nhân viên
        'ql_lichlamviec_calendar' => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG], // Enhanced calendar với phân công nhân viên
        'ql_duyetnghi'   => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'thietbiphong'   => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'sodoghe'        => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'chamcong'       => [ROLE_QUAN_LY_RAP],
        'bangluong'      => [ROLE_QUAN_LY_RAP],
        'QLfeed'         => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        // Combo / Khuyến mãi
        'QLcombo'        => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'combo_them'     => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'combo_sua'      => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'combo_xoa'      => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'combo_toggle'   => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        // Khuyến mãi (mã giảm giá)
        'QLkm'           => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'km_them'        => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'km_sua'         => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'km_xoa'         => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'km_toggle'      => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],

        // Quản trị tài khoản: Admin hệ thống và Quản lý cụm đều có thể tạo tài khoản cho mọi role
        'QTkh'       => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'QTvien'     => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_RAP, ROLE_QUAN_LY_CUM],
        'themuser'   => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_RAP, ROLE_QUAN_LY_CUM],
        'suatk'      => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_RAP, ROLE_QUAN_LY_CUM],
        'xoatk'      => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_RAP, ROLE_QUAN_LY_CUM],
        'updateuser' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_RAP, ROLE_QUAN_LY_CUM],
        'QLquanlyrap' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],

        // Cấu hình website
        'cauhinh'    => [ROLE_ADMIN_HE_THONG],

        // Doanh thu: Admin hệ thống và Quản lí cụm rạp
        'DTdh'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'DTthang' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'DTtuan'  => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'DTngay'  => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'DTphim_rap' => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],

        // Quản lý cụm rạp: duyệt kế hoạch chiếu
        'duyet_lichchieu' => [ROLE_QUAN_LY_CUM, ROLE_ADMIN_HE_THONG],
        'chi_tiet_kehoach' => [ROLE_QUAN_LY_CUM, ROLE_ADMIN_HE_THONG],
        'ajax_chi_tiet_kehoach' => [ROLE_QUAN_LY_CUM, ROLE_ADMIN_HE_THONG],
        'ajax_chi_tiet_kehoach_new' => [ROLE_QUAN_LY_CUM, ROLE_ADMIN_HE_THONG],
        'duyet_kehoach'   => [ROLE_QUAN_LY_CUM, ROLE_ADMIN_HE_THONG],
        'lich_rap'        => [ROLE_QUAN_LY_CUM, ROLE_QUAN_LY_RAP],
        'phanphim'        => [ROLE_QUAN_LY_CUM],
        'hieusuat_rap'    => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'thuhoi_phim'     => [ROLE_QUAN_LY_CUM],
        'cum_admin'       => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'cum_admin_xoa'   => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM],
        'QLquanlycum'     => [ROLE_ADMIN_HE_THONG],
        'gui_kehoach'     => [ROLE_QUAN_LY_RAP],
        'TKrap'           => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM, ROLE_QUAN_LY_RAP],
        'DTphim_rap'      => [ROLE_ADMIN_HE_THONG, ROLE_QUAN_LY_CUM, ROLE_QUAN_LY_RAP],
    ];
}

function allowed_act($act, $vai_tro)
{
    $map = permission_map();
    // Deny-by-default: nếu action không được khai báo trong map thì từ chối
    if (!isset($map[$act])) return false;
    return in_array((int)$vai_tro, $map[$act], true);
}

// Helper: enforce permission for current session user; call at controller entry points
function enforce_act_or_403($act)
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $user = $_SESSION['user1'] ?? $_SESSION['user'] ?? null;
    $vai_tro = $user['vai_tro'] ?? null;
    if (!allowed_act($act, $vai_tro)) {
        // send 403 and optionally render a friendly page
        header('HTTP/1.1 403 Forbidden');
        
        // Tạo thông báo lỗi đẹp hơn
        $role_name = role_label($vai_tro);
        $user_name = $user['ho_ten'] ?? $user['name'] ?? 'Unknown';
        
        echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f8fafc; margin: 0; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 40px; text-align: center; }
        .icon { font-size: 64px; margin-bottom: 20px; }
        h1 { color: #dc2626; margin: 0 0 10px; font-size: 28px; }
        .subtitle { color: #6b7280; margin-bottom: 30px; font-size: 16px; }
        .info { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info strong { color: #92400e; }
        .back-btn { display: inline-block; background: #3b82f6; color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; margin-top: 20px; }
        .back-btn:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>Không có quyền truy cập</h1>
        <p class="subtitle">Bạn không được phép thực hiện chức năng này</p>
        
        <div class="info">
            <strong>Thông tin tài khoản:</strong><br>
            Tên: ' . htmlspecialchars($user_name) . '<br>
            Vai trò: ' . htmlspecialchars($role_name) . '<br>
            Chức năng yêu cầu: ' . htmlspecialchars($act) . '
        </div>
        
        <p style="color: #6b7280;">Vui lòng liên hệ quản trị viên để được cấp quyền phù hợp.</p>
        
        <a href="#" class="back-btn" id="backButton">← Quay lại</a>
        <a href="index.php" class="back-btn">🏠 Trang chủ</a>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var backBtn = document.getElementById("backButton");
        if (backBtn) {
            backBtn.addEventListener("click", function(e) {
                e.preventDefault();
                history.back();
            });
        }
    });
    </script>
</body>
</html>';
        exit;
    }
}

?>
