<?php include "view/search.php"?>

<!-- Main content -->
<section class="container">
    <div class="overflow-wrapper">
        <div class="col-sm-12">
            <?php
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                
                if ($id <= 0) {
                    echo '<div class="alert alert-danger">Tin tức không hợp lệ!</div>';
                } else {
                    $sql = "SELECT id, tieu_de, tom_tat, noi_dung, hinh_anh, ngay_dang FROM tintuc WHERE id = ?";
                    $tin = pdo_query_one($sql, $id);
                    
                    if (!$tin) {
                        echo '<div class="alert alert-danger">Không tìm thấy tin tức!</div>';
                    } else {
                        extract($tin);
                        $ngay_format = date('d/m/Y H:i', strtotime($ngay_dang));
                        if (!empty($hinh_anh)) {
                            $image_path = '/webphim/Trang-admin/' . $hinh_anh;
                        } else {
                            $image_path = 'images/no-image.jpg';
                        }
                        
                        echo '
                        <article class="post post--news">
                            <div class="post__content">
                                <h1 class="post__title">' . htmlspecialchars($tieu_de) . '</h1>
                                <p class="post__date">📅 ' . $ngay_format . '</p>';
                                
                        if (!empty($image_path)) {
                            echo '<div class="post__image-wrapper" style="margin: 20px 0;">
                                <img alt="' . htmlspecialchars($tieu_de) . '" src="' . htmlspecialchars($image_path) . '" style="max-width: 100%; height: auto; border-radius: 8px;">
                            </div>';
                        }
                        
                        if (!empty($tom_tat)) {
                            echo '<div class="post__summary" style="background: #f5f5f5; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; font-style: italic;">
                                ' . htmlspecialchars($tom_tat) . '
                            </div>';
                        }
                        
                        echo '<div class="post__text" style="line-height: 1.8; font-size: 16px; color: #333; margin: 30px 0;">
                                ' . nl2br(htmlspecialchars($noi_dung)) . '
                            </div>
                            
                            <div class="wave-devider" style="margin: 30px 0;"></div>
                            
                            <div style="text-align: center; padding: 20px 0;">
                                <a href="index.php?act=tintuc" class="btn read-more post--btn">← Quay Lại Danh Sách Tin Tức</a>
                            </div>
                        </article>';
                    }
                }
            ?>
        </div>
    </div>
</section>

<div class="clearfix"></div>

<style>
    .post__title {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 20px 0;
        color: #222;
    }
    
    .post__date {
        color: #888;
        font-size: 14px;
        margin: 10px 0 20px 0;
    }
    
    .post__content {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
    }
    
    .post__image-wrapper {
        text-align: center;
    }
    
    .post__image-wrapper img {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .post__summary {
        font-size: 15px;
        color: #555;
    }
    
    .post__text {
        word-break: break-word;
    }
    
    .wave-devider {
        border-top: 2px solid #ddd;
    }
</style>
