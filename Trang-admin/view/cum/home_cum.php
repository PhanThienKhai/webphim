<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading"><h3>Trang Chủ - Quản lý tất cả rạp chiếu phim</h3></div>
        </div>
        <div class="col-12 col-lg-auto mb-20"><h1 id="real-time-clock"></h1></div>
    </div>

    <style>
        .filter-container {
            background: linear-gradient(135deg, #b5bfeaff 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 40px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
            margin-bottom: 25px;
            margin-top: -29px;
        }

        .filter-title {
            color: Black;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-container .form-control,
        .filter-container .button {
            border: none;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-container .form-control {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
        }

        .filter-container .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
            background: white;
        }

        .filter-container .form-control::placeholder {
            color: #999;
        }

        .filter-container label {
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            font-size: 13px;
        }

        .filter-button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .filter-button-group .button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .filter-button-group .button:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
            transform: translateY(-2px);
        }

        .filter-search-btn {
            background: white;
            color: #667eea;
            font-weight: 600;
            padding: 12px 30px !important;
            cursor: pointer;
        }

        .filter-search-btn:hover {
            background: #f0f4ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .filter-container {
                padding: 20px;
            }

            .filter-button-group {
                flex-direction: column;
            }

            .filter-button-group .button {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <form method="get" action="index.php" class="filter-container">
        <input type="hidden" name="act" value="home" />
        <input type="hidden" name="scope" value="cum" />
        
        <div class="filter-title">
            Bộ Lọc Dữ Liệu
        </div>

        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-10">
                <label>Chọn Rạp</label>
                <select class="form-control" name="id_rap" id="filterRap">
                    <option value="">— Tất cả rạp —</option>
                    <?php foreach (rap_all() as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= (!empty($id_rap) && (int)$id_rap===(int)$r['id'])?'selected':'' ?>><?= htmlspecialchars($r['ten_rap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3 mb-10">
                <label>Từ ngày</label>
                <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from ?? '') ?>" />
            </div>
            <div class="col-6 col-md-3 mb-10">
                <label>Đến ngày</label>
                <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to ?? '') ?>" />
            </div>
            <div class="col-12 col-md-2 mb-10">
                <button class="button filter-search-btn" type="submit">Lọc</button>
            </div>
        </div>

        <div class="filter-button-group">
            <a class="button" href="index.php?act=home&scope=cum&period=today<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Hôm nay</a>
            <a class="button" href="index.php?act=home&scope=cum&period=week<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Tuần này</a>
            <a class="button" href="index.php?act=home&scope=cum&period=month<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Tháng này</a>
        </div>
    </form>

    <!-- Key Metrics Cards -->
    <div class="row mb-20">
        <div class="col-lg-3 col-md-6 col-12 mb-15">
            <div class="top-report" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="head"><h4>Tổng doanh thu</h4></div>
                <div class="content"><h2><?= number_format((int)($sum_revenue ?? 0)) ?> VNĐ</h2></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-15">
            <div class="top-report" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="head"><h4>Tổng vé bán</h4></div>
                <div class="content"><h2><?= (int)($sum_tickets ?? 0) ?> VÉ</h2></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-15">
            <div class="top-report" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="head"><h4>Phim chiếu</h4></div>
                <div class="content"><h2><?= (int)($movies_showing ?? 0) ?> Phim</h2></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-15">
            <div class="top-report" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <div class="head"><h4>Combo TOP</h4></div>
                <div class="content"><h2 style="font-size: 16px;"><?= isset($best_combo_r['combo']) ? htmlspecialchars($best_combo_r['combo']) : '—' ?></h2></div>
            </div>
        </div>
    </div>

    <!-- Dashboard Charts (Chart.js) -->
    <?php include __DIR__ . '/dashboard_charts.php'; ?>

</div>

<script>
    function updateClock(){
        var d=new Date();
        function p(n){return (n<10?'0':'')+n}
        var clockElement = document.getElementById('real-time-clock');
        if(clockElement) {
            clockElement.innerText=p(d.getHours())+":"+p(d.getMinutes())+":"+p(d.getSeconds());
        }
        setTimeout(updateClock,1000);
    }
    document.addEventListener('DOMContentLoaded', function() {
        updateClock();
        
        // Tự động submit form khi chọn rạp
        const filterRap = document.getElementById('filterRap');
        if(filterRap) {
            filterRap.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
