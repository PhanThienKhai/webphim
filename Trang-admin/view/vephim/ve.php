<style>
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #4CAF50;
        --danger: #ff6b6b;
        --info: #2196F3;
        --light: #f8f9ff;
        --border: #e0e0e0;
    }

    .search-section {
        background: linear-gradient(135deg, #a0a6c0 0%, #523371 100%);        
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        width: 100%;
    }

    .search-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        align-items: center;
    }

    .search-form input {
        padding: 11px 14px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .search-form input[type="text"] {
        background-color: white;
        color: #333;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .search-form input[type="text"]:focus {
        outline: none;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        transform: translateY(-1px);
    }

    .search-form input[type="text"]::placeholder {
        color: #aaa;
    }

    .search-form .tkim {
        background-color: white;
        color: var(--primary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-form .tkim:hover {
        background-color: #f5f5f5;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
    }

    .controls-section {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .cap {
        padding: 11px 22px;
        background: linear-gradient(135deg, var(--success) 0%, #45a049 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(76, 175, 80, 0.3);
        font-size: 13px;
    }

    .cap:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
    }

    .cap:active {
        transform: translateY(0);
    }

    .table-wrapper {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
    }

    .table thead {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .table thead th {
        font-weight: 600;
        border: none;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
        font-size: 13px;
    }

    .table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: var(--light);
    }

    .table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        font-size: 13px;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 18px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-danger {
        background-color: #ff6b6b;
        color: white;
    }

    .badge-success {
        background-color: #4CAF50;
        color: white;
    }

    .badge-info {
        background-color: #2196F3;
        color: white;
    }

    .badge-secondary {
        background-color: #95a5a6;
        color: white;
    }

    .action h4 {
        margin: 0;
    }

    .table-action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .button {
        padding: 6px 10px !important;
        background-color: var(--info) !important;
        border: none !important;
        border-radius: 4px !important;
        color: white !important;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
    }

    .button:hover {
        background-color: #0b7dda !important;
        transform: scale(1.05);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .page-heading h3 {
        color: #333;
        font-weight: 700;
        margin: 0;
        font-size: 24px;
    }

    .page-heading h3 span {
        color: #999;
        font-weight: 400;
        font-size: 14px;
        margin-left: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #999;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    @media (max-width: 992px) {
        .search-form {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .search-section {
            padding: 15px;
            margin-bottom: 15px;
        }

        .page-heading h3 {
            font-size: 18px;
        }

        .page-heading h3 span {
            display: none;
        }

        .table-responsive {
            font-size: 12px;
        }

        .table thead th,
        .table tbody td {
            padding: 8px 6px;
        }

        .controls-section {
            margin-bottom: 15px;
        }

        .cap {
            flex: 1;
            min-width: 140px;
        }
    }
</style>

<?php
include "./view/home/sideheader.php";
?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">

        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Vé Xem Phim <span>/ Danh Sách Vé</span></h3>
            </div>
        </div><!-- Page Heading End -->

        <!-- Search Section -->
        <div class="search-section">
            <form action="index.php?act=ve" method="post" class="search-form">
                <input type="text" name="ten" placeholder="Tìm kiếm người dùng..." class="search-input">
                <input type="text" name="tieude" placeholder="Tìm kiếm phim..." class="search-input">
                <input type="text" name="id_ve" placeholder="#️Tìm kiếm ID vé..." class="search-input">
                <input type="submit" name="tk" value="Tìm Kiếm" class="tkim">
            </form>
        </div>
    </div><!-- Page Headings End -->

    <!-- Controls Section -->
    <div class="controls-section">
        <form action="index.php?act=capnhat_tt_ve" method="post" style="display: inline;">
            <button type="submit" name="capnhat" class="cap">
                <i class="zmdi zmdi-refresh"></i> Cập nhật vé hết hạn
            </button>
        </form>
    </div>

    <div class="row mbn-30">

    <!-- Table Section -->
    <div class="col-12 mb-30">
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table table-vertical-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phim</th>
                            <th>Giá Vé</th>
                            <th>Ngày Đặt</th>
                            <th>Tên Khách Hàng</th>
                            <th>Ngày Chiếu</th>
                            <th>Khung Giờ</th>
                            <th>Trạng Thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($loadvephim) && is_array($loadvephim)): ?>
                            <?php foreach($loadvephim as $ve): ?>
                                <?php extract($ve); ?>
                                <tr>
                                    <td><strong>#<?=$ve['id']?></strong></td>
                                    <td><?=$ve['tieu_de']?></td>
                                    <td><strong><?=number_format($price)?> ₫</strong></td>
                                    <td><?=date('d/m/Y', strtotime($ve['ngay_dat']))?></td>
                                    <td><?=$ve['name']?></td>
                                    <td><?=date('d/m/Y', strtotime($ve['ngay_chieu']))?></td>
                                    <td><?=$ve['thoi_gian_chieu']?></td>
                                    <td>
                                        <?php
                                        switch ($ve['trang_thai']) {
                                            case '0':
                                                echo '<span class="badge badge-danger">Đang chờ</span>';
                                                break;
                                            case '1':
                                                echo '<span class="badge badge-success">✓ Xác nhận</span>';
                                                break;
                                            case '2':
                                                echo '<span class="badge badge-info">✓ Đã dùng</span>';
                                                break;
                                            case '3':
                                                echo '<span class="badge badge-secondary">✕ Hủy</span>';
                                                break;
                                            case '4':
                                                echo '<span class="badge badge-secondary">⏱ Hết hạn</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-secondary">❓ Không xác định</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="action">
                                        <div class="table-action-buttons">
                                            <a class="button button-box button-xs button-info" href="index.php?act=suavephim&idsua=<?=$ve['id']?>" title="Chỉnh sửa">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                            <?php if ($ve['trang_thai'] != 0): ?>
                                                <a class="button button-box button-xs button-info" href="index.php?act=ctve&id=<?=$ve['id']?>" title="Chi tiết">
                                                    <i class="zmdi zmdi-info"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <p>Không có dữ liệu vé nào</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

</div><!-- Content Body End -->


