<!-- Trang hiển thị phim đang chiếu tại rạp cụ thể -->
<?php include "view/search.php"; ?>

<style>
/* Container & Layout */
.cinema-movies-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px 60px;
}

/* Cinema Header - Clean Modern Design */
.cinema-header-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    padding: 30px;
    margin-bottom: 40px;
    border-left: 5px solid #667eea;
}

.cinema-header-top {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
}

.cinema-icon-box {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.cinema-title-section {
    flex: 1;
}

.cinema-name {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 10px 0;
    line-height: 1.3;
}

.cinema-stats-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

.cinema-stats-badge i {
    margin-right: 5px;
}

.cinema-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.cinema-detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #555;
    font-size: 0.95rem;
}

.cinema-detail-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 1.1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.cinema-detail-text strong {
    display: block;
    color: #2c3e50;
    font-size: 0.85rem;
    margin-bottom: 2px;
    font-weight: 600;
}

/* Section Title */
.section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.section-header-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.section-header-text h2 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

.section-header-text p {
    margin: 5px 0 0 0;
    color: #6c757d;
    font-size: 0.9rem;
}

/* Movies Grid */
.movies-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
}

/* Movie Card */
.movie-item-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.movie-item-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.25);
}

.movie-poster-box {
    position: relative;
    padding-top: 140%;
    overflow: hidden;
    background: #f5f5f5;
}

.movie-poster-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.movie-item-card:hover .movie-poster-img {
    transform: scale(1.06);
}

.movie-badges-row {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    z-index: 2;
}

.badge-age-rating {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
    box-shadow: 0 2px 10px rgba(231, 76, 60, 0.4);
}

.badge-movie-duration {
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.movie-content-box {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.movie-name-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 12px 0;
    min-height: 48px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.movie-name-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s;
}

.movie-name-title a:hover {
    color: #667eea;
}

.movie-meta-list {
    flex: 1;
    margin-bottom: 12px;
}

.movie-meta-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    font-size: 0.9rem;
    color: #555;
}

.movie-meta-row i {
    color: #667eea;
    width: 16px;
    text-align: center;
}

.movie-meta-row strong {
    color: #28a745;
    font-weight: 600;
}

.showtimes-box {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
}

.showtimes-header {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.showtimes-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.showtime-tag-item {
    background: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.85rem;
    border: 1px solid #dee2e6;
    white-space: nowrap;
    font-weight: 600;
    color: #495057;
}

.showtime-more-text {
    color: #667eea;
    font-size: 0.85rem;
    font-weight: 600;
}

.movie-action-btns {
    display: flex;
    gap: 8px;
}

.btn-movie-action {
    flex: 1;
    padding: 11px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
}

.btn-book-ticket {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-book-ticket:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-more-info {
    flex: 0 0 auto;
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 11px 14px;
}

.btn-more-info:hover {
    background: #667eea;
    color: white;
    text-decoration: none;
}

/* Empty & Error States */
.state-message-box {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
}

.state-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.state-icon.empty {
    color: #6c757d;
}

.state-icon.error {
    color: #e74c3c;
}

.state-message-box h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    font-weight: 700;
}

.state-message-box p {
    color: #6c757d;
    font-size: 1.05rem;
    margin-bottom: 25px;
    line-height: 1.6;
}

.btn-home-link {
    display: inline-block;
    padding: 12px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-home-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 992px) {
    .cinema-header-top {
        flex-direction: column;
        text-align: center;
    }
    
    .cinema-name {
        font-size: 1.7rem;
    }
    
    .cinema-details-grid {
        grid-template-columns: 1fr;
    }
    
    .movies-grid-container {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 576px) {
    .cinema-header-modern {
        padding: 20px;
    }
    
    .cinema-name {
        font-size: 1.4rem;
    }
    
    .cinema-icon-box {
        width: 60px;
        height: 60px;
        font-size: 1.7rem;
    }
    
    .section-header-text h2 {
        font-size: 1.4rem;
    }
    
    .movies-grid-container {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="cinema-movies-page">
    <?php if ($rap_info): ?>
        <!-- Cinema Header -->
        <div class="cinema-header-modern">
            <div class="cinema-header-top">
                <div class="cinema-icon-box">
                    <i class="fa fa-film"></i>
                </div>
                
                <div class="cinema-title-section">
                    <h1 class="cinema-name"><?= htmlspecialchars($rap_info['ten_rap']) ?></h1>
                    <div class="cinema-stats-badge">
                        <i class="fa fa-ticket"></i>
                        <?= count($ds_phim) ?> phim đang chiếu
                    </div>
                </div>
            </div>
            
            <div class="cinema-details-grid">
                <div class="cinema-detail-item">
                    <div class="cinema-detail-icon">
                        <i class="fa fa-map-marker"></i>
                    </div>
                    <div class="cinema-detail-text">
                        <strong>Địa chỉ</strong>
                        <?= htmlspecialchars($rap_info['dia_chi'] ?? 'Đang cập nhật') ?>
                    </div>
                </div>
                
                <div class="cinema-detail-item">
                    <div class="cinema-detail-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="cinema-detail-text">
                        <strong>Hotline</strong>
                        <?= htmlspecialchars($rap_info['so_dien_thoai'] ?? 'Đang cập nhật') ?>
                    </div>
                </div>
                
                <?php if (!empty($rap_info['email'])): ?>
                <div class="cinema-detail-item">
                    <div class="cinema-detail-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="cinema-detail-text">
                        <strong>Email</strong>
                        <?= htmlspecialchars($rap_info['email']) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($ds_phim)): ?>
            <!-- Empty State -->
            <div class="state-message-box">
                <div class="state-icon empty">
                    <i class="fa fa-film"></i>
                </div>
                <h3>Chưa có lịch chiếu</h3>
                <p>Hiện tại rạp này chưa có phim nào đang chiếu.<br>Vui lòng quay lại sau hoặc chọn rạp khác.</p>
                <a href="index.php" class="btn-home-link">
                    <i class="fa fa-home"></i> Về trang chủ
                </a>
            </div>
        <?php else: ?>
            <!-- Movies Section -->
            <div class="section-header">
                <div class="section-header-icon">
                    <i class="fa fa-ticket"></i>
                </div>
                <div class="section-header-text">
                    <h2>Phim đang chiếu</h2>
                    <p>Chọn phim và đặt vé ngay</p>
                </div>
            </div>
            
            <div class="movies-grid-container">
                <?php foreach ($ds_phim as $phim): 
                    extract($phim);
                    $link_chitiet = "index.php?act=ctphim&id=" . $id;
                    $link_datve = "index.php?act=datve&id=" . $id . "&id_rap=" . $rap_info['id'];
                    
                    // Lấy giờ chiếu preview
                    $gio_chieu_preview = get_gio_chieu_preview($id, $rap_info['id'], 4);
                ?>
                <div class="movie-item-card">
                    <div class="movie-poster-box">
                        <a href="<?= $link_chitiet ?>">
                            <img src="imgavt/<?= $img ?>" 
                                 alt="<?= htmlspecialchars($tieu_de) ?>" 
                                 class="movie-poster-img">
                        </a>
                        
                        <div class="movie-badges-row">
                            <span class="badge-age-rating"><?= $gia_han_tuoi ?>+</span>
                            <span class="badge-movie-duration">
                                <i class="fa fa-clock-o"></i>
                                <?= $thoi_luong_phim ?>'
                            </span>
                        </div>
                    </div>
                    
                    <div class="movie-content-box">
                        <h3 class="movie-name-title">
                            <a href="<?= $link_chitiet ?>"><?= htmlspecialchars($tieu_de) ?></a>
                        </h3>
                        
                        <div class="movie-meta-list">
                            <div class="movie-meta-row">
                                <i class="fa fa-tag"></i>
                                <span><?= htmlspecialchars($ten_loai) ?></span>
                            </div>
                            <div class="movie-meta-row">
                                <i class="fa fa-calendar"></i>
                                <span>Từ <?= date('d/m/Y', strtotime($ngay_chieu_dau)) ?></span>
                            </div>
                            <div class="movie-meta-row">
                                <i class="fa fa-film"></i>
                                <strong><?= $tong_suat_chieu ?> suất chiếu</strong>
                            </div>
                        </div>
                        
                        <?php if (!empty($gio_chieu_preview)): ?>
                        <div class="showtimes-box">
                            <div class="showtimes-header">
                                <i class="fa fa-clock-o"></i>
                                Giờ chiếu gần nhất:
                            </div>
                            <div class="showtimes-tags">
                                <?php 
                                $count = 0;
                                foreach ($gio_chieu_preview as $gio): 
                                    if ($count >= 3) break;
                                    $count++;
                                ?>
                                    <span class="showtime-tag-item">
                                        <?= date('H:i', strtotime($gio['thoi_gian_chieu'])) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($gio_chieu_preview) > 3): ?>
                                    <span class="showtime-more-text">+<?= count($gio_chieu_preview) - 3 ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="movie-action-btns">
                            <a href="<?= $link_datve ?>" class="btn-movie-action btn-book-ticket">
                                <i class="fa fa-ticket"></i>
                                Đặt vé
                            </a>
                            <a href="<?= $link_chitiet ?>" class="btn-movie-action btn-more-info">
                                <i class="fa fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Error State -->
        <div class="state-message-box">
            <div class="state-icon error">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <h3>Rạp không tồn tại</h3>
            <p>Rạp chiếu không tồn tại hoặc đã ngừng hoạt động.<br>Vui lòng chọn rạp khác từ menu.</p>
            <a href="index.php" class="btn-home-link">
                <i class="fa fa-home"></i> Về trang chủ
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="clearfix"></div>
