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
        'phong'        => [ROLE_QUAN_LY_RAP],
        'themphong'   => [ROLE_QUAN_LY_RAP],
        'QLsuatchieu'  => [ROLE_QUAN_LY_RAP],
        'sualichchieu' => [ROLE_QUAN_LY_RAP],
        'themlichchieu'=> [ROLE_QUAN_LY_RAP],
        'updatelichchieu'=>[ROLE_QUAN_LY_RAP],
        'kehoach'       => [ROLE_QUAN_LY_RAP], // Hiển thị form kế hoạch chiếu
        'kehoach_chieu' => [ROLE_QUAN_LY_RAP], // Kế hoạch chiếu phim
        'luu_kehoach'   => [ROLE_QUAN_LY_RAP], // Lưu kế hoạch chiếu
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
        'xinnghi'        => [ROLE_NHAN_VIEN],
        'scanve'         => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP],
        'nv_datve'       => [ROLE_NHAN_VIEN],
        'nv_baocao'      => [ROLE_NHAN_VIEN, ROLE_QUAN_LY_RAP],
        'nv_xeplich'     => [ROLE_NHAN_VIEN],

        // Quản lý rạp: phân công lịch, duyệt nghỉ, thiết bị
        'ql_lichlamviec' => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'ql_duyetnghi'   => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'thietbiphong'   => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'sodoghe'        => [ROLE_QUAN_LY_RAP, ROLE_ADMIN_HE_THONG],
        'chamcong'       => [ROLE_QUAN_LY_RAP],
        'bangluong'      => [ROLE_QUAN_LY_RAP],
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
        echo '<h1>403 - Forbidden</h1><p>Bạn không có quyền truy cập vào chức năng này.</p>';
        exit;
    }
}

?>
