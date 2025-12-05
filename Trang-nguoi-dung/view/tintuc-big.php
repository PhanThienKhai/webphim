<?php include "view/search.php"?>

<!-- Main content -->
<section class="container">
    <div class="overflow-wrapper">
        <div class="col-sm-12">

            <h2 class="page-heading">Tin Tức</h2>

            <?php
                // Lấy tin tức từ database, sắp xếp theo ngày mới nhất
                $sql = "SELECT id, tieu_de, tom_tat, noi_dung, hinh_anh, ngay_dang FROM tintuc ORDER BY ngay_dang DESC";
                $news_list = pdo_query($sql);
                
                if (!empty($news_list)) {
                    foreach ($news_list as $tin) {
                        extract($tin);
                        
                        // Format ngày tháng
                        $ngay_format = date('d/m/Y H:i', strtotime($ngay_dang));
                        
                        // Đường dẫn hình ảnh - absolute path từ webroot
                        // Database lưu: assets/news/file.jpg (tương đối từ Trang-admin)
                        // Convert to: /webphim/Trang-admin/assets/news/file.jpg
                        if (!empty($hinh_anh)) {
                            $image_path = '/webphim/Trang-admin/' . $hinh_anh;
                        } else {
                            $image_path = 'images/no-image.jpg';
                        }
                        
                        // Tóm tắt nếu không có thì lấy nội_dung
                        $summary = !empty($tom_tat) ? $tom_tat : $noi_dung;
                        
                        // Link chi tiết
                        $link = "index.php?act=tintuc_chi_tiet&id=" . $id;
                        
                        echo '
                        <!-- News post article-->
                        <article class="post post--news">
                            <a href="' . $link . '" class="post__image-link">
                                <img alt="' . htmlspecialchars($tieu_de) . '" src="' . htmlspecialchars($image_path) . '">
                            </a>

                            <h1><a href="' . $link . '" class="post__title-link">' . htmlspecialchars($tieu_de) . '</a></h1>
                            <p class="post__date">' . $ngay_format . '</p>

                            <div class="wave-devider"></div>

                            <p class="post__text">' . htmlspecialchars(substr($summary, 0, 300)) . (strlen($summary) > 300 ? '...' : '') . '</p>

                            <a href="' . $link . '" class="btn read-more post--btn">ĐỌC THÊM</a>

                            <div class="devider-huge"></div>
                        </article>
                        <!-- end news post article-->';
                    }
                } else {
                    echo '<p style="text-align: center; padding: 40px;">Chưa có tin tức nào</p>';
                }
            ?>

            <div class="pagination">
                <a href='#' class="pagination__prev">prev</a>
                <a href='#' class="pagination__next">next</a>
            </div>

        </div>
    </div>
</section>

<div class="clearfix"></div>
