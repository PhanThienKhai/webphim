
<div class="col-sm-12">
    <h2 class="page-heading">Tin mới Nhất</h2>

    <div class="news-container">
        <?php
            // Lấy tin tức từ database, sắp xếp theo ngày mới nhất
            $sql = "SELECT id, tieu_de, tom_tat, noi_dung, hinh_anh, ngay_dang FROM tintuc ORDER BY ngay_dang DESC LIMIT 12";
            $news_list = pdo_query($sql);
            
            if (!empty($news_list)) {
                foreach ($news_list as $tin) {
                    extract($tin);
                    
                    // Format ngày tháng
                    $ngay_format = date('d/m/Y H:i', strtotime($ngay_dang));
                    
                    // Đường dẫn hình ảnh
                    $image_path = !empty($hinh_anh) ? $hinh_anh : 'imgavt/no-image.jpg';
                    
                    // Tóm tắt nếu không có thì lấy 100 ký tự từ nội dung
                    $summary = !empty($tom_tat) ? $tom_tat : (strlen($noi_dung) > 100 ? substr($noi_dung, 0, 100) . '...' : $noi_dung);
                    
                    // Link chi tiết
                    $link = "index.php?act=tintuc_chi_tiet&id=" . $id;
                    
                    echo '
                    <div class="col-sm-4 similar-wrap col--remove">
                        <div class="post post--preview post--preview--wide">
                            <div class="post__image">
                                <img alt="' . htmlspecialchars($tieu_de) . '" src="' . htmlspecialchars($image_path) . '">
                                <div class="social social--position social--hide">
                                    <span class="social__name">Share:</span>
                                    <a href="#" class="social__variant social--first fa fa-facebook"></a>
                                    <a href="#" class="social__variant social--second fa fa-twitter"></a>
                                    <a href="#" class="social__variant social--third fa fa-vk"></a>
                                </div>
                            </div>
                            <p class="post__date">' . $ngay_format . '</p>
                            <a href="' . $link . '" class="post__title">' . htmlspecialchars($tieu_de) . '</a>
                            <p class="post__summary" style="font-size: 13px; color: #666; margin: 10px 0;">' . htmlspecialchars($summary) . '</p>
                            <a href="' . $link . '" class="btn read-more post--btn">ĐỌC THÊM</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align: center; padding: 20px;">Chưa có tin tức nào</p>';
            }
        ?>
    </div>
</div>
