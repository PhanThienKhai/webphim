<?php
// Dynamic list of rạp. Index.php should provide $allRaps (from model/rap.php)
$placeholderImg = 'login-ui2/login-ui2/assets/galaxygovap.jpg';
?>

<?php include "view/search.php"; ?>

<style>
.cinema-page-header {
    background: linear-gradient(135deg, #a5abc6ff 0%, #764ba2 100%);
    color: white;
    padding: 50px 0;
    margin-bottom: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.cinema-page-title {
    text-align: center;
    font-size: 2.8rem;
    font-weight: bold;
    margin: 0 0 15px 0;
}

.cinema-page-subtitle {
    text-align: center;
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 700px;
    margin: 0 auto;
}

.cinema-grid {
    max-width: 1200px;
    margin: 0 auto 60px;
    padding: 0 15px;
}

.cinema-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 30px;
}

.cinema-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.cinema-card-inner {
    display: flex;
    gap: 0;
}

.cinema-image {
    width: 350px;
    height: 280px;
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
}

.cinema-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.cinema-card:hover .cinema-image img {
    transform: scale(1.1);
}

.cinema-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(102, 126, 234, 0.95);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.cinema-content {
    flex: 1;
    padding: 25px 30px;
    display: flex;
    flex-direction: column;
}

.cinema-name {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cinema-name i {
    color: #667eea;
}

.cinema-info {
    flex: 1;
    margin-bottom: 20px;
}

.cinema-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
    color: #555;
    font-size: 13px;
}

.cinema-info-item i {
    width: 24px;
    color: #667eea;
    margin-right: 10px;
    margin-top: 3px;
}

.cinema-map {
    width: 100%;
    height: 180px;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
    border: 2px solid #e9ecef;
}

.cinema-map iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

.cinema-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-cinema {
    flex: 1;
    min-width: 140px;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
}

.btn-primary-cinema {
    background: linear-gradient(135deg, #a7aeceff 0%, #764ba2 100%);
    color: white;
    font-size: 13px;
}

.btn-primary-cinema:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-success-cinema {
    background: linear-gradient(135deg, #9db6b4ff 0%, #6d927bff 100%);
    color: white;
    font-size: 13px;
}

.btn-success-cinema:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
}

.btn-info-cinema {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-info-cinema:hover {
    background: #667eea;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 15px;
    max-width: 600px;
    margin: 0 auto;
}

.empty-state i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #555;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
}

@media (max-width: 992px) {
    .cinema-card-inner {
        flex-direction: column;
    }
    
    .cinema-image {
        width: 100%;
        height: 250px;
    }
    
    .cinema-page-title {
        font-size: 2rem;
    }
    
    .cinema-name {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .cinema-actions {
        flex-direction: column;
    }
    
    .btn-cinema {
        width: 100%;
    }
}
</style>

<!-- Header Section -->
<div class="cinema-page-header">
    <div class="container">
        <h1 class="cinema-page-title">
            <i class="fa fa-film"></i> Hệ Thống Rạp Chiếu
        </h1>
        <p class="cinema-page-subtitle">
            Khám phá các rạp chiếu phim chất lượng cao với công nghệ hiện đại và dịch vụ tốt nhất
        </p>
    </div>
</div>

<!-- Cinema Grid -->
<div class="cinema-grid">
    <?php if (!empty($allRaps) && is_array($allRaps)) {
        foreach ($allRaps as $r) {
            $img = !empty($r['logo']) ? $r['logo'] : $placeholderImg;
            $address = $r['dia_chi'] ?? 'Đang cập nhật';
            $phone = $r['so_dien_thoai'] ?? 'Đang cập nhật';
            $email = $r['email'] ?? '';
            $mapSrc = 'https://www.google.com/maps?q=' . urlencode($address) . '&output=embed';
            $dirLink = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($address);
            $phimLink = 'index.php?act=phim_theo_rap&id_rap=' . $r['id'];
    ?>
    <div class="cinema-card">
        <div class="cinema-card-inner">
            <div class="cinema-image">
                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($r['ten_rap']) ?>">
                <div class="cinema-badge">
                    <i class="fa fa-star"></i> Rạp chính
                </div>
            </div>
            
            <div class="cinema-content">
                <h2 class="cinema-name">
                    <i class="fa fa-building"></i>
                    <?= htmlspecialchars($r['ten_rap']) ?>
                </h2>
                
                <div class="cinema-info">
                    <div class="cinema-info-item">
                        <i class="fa fa-map-marker"></i>
                        <span><?= htmlspecialchars($address) ?></span>
                    </div>
                    <div class="cinema-info-item">
                        <i class="fa fa-phone"></i>
                        <span><?= htmlspecialchars($phone) ?></span>
                    </div>
                    <?php if ($email): ?>
                    <div class="cinema-info-item">
                        <i class="fa fa-envelope"></i>
                        <span><?= htmlspecialchars($email) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="cinema-map">
                    <iframe src="<?= $mapSrc ?>" 
                            allowfullscreen="" 
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                
                <div class="cinema-actions">
                    <a href="<?= $phimLink ?>" class="btn-cinema btn-primary-cinema">
                        <i class="fa fa-ticket"></i>
                        Xem phim đang chiếu
                    </a>
                    <a href="<?= $dirLink ?>" target="_blank" class="btn-cinema btn-success-cinema">
                        <i class="fa fa-location-arrow"></i>
                        Chỉ đường
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php }
    } else { ?>
        <div class="empty-state">
            <i class="fa fa-film"></i>
            <h3>Chưa có rạp chiếu</h3>
            <p>Hiện tại chưa có rạp nào trong hệ thống. Vui lòng quay lại sau!</p>
        </div>
    <?php } ?>
</div>

<div class="clearfix"></div>
