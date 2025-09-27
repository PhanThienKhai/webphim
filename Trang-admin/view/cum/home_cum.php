<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading"><h3>Trang Chủ (Cụm rạp)</h3></div>
        </div>
        <div class="col-12 col-lg-auto mb-20"><h1 id="real-time-clock"></h1></div>
    </div>

    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="home" />
        <input type="hidden" name="scope" value="cum" />
        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-10">
                <label>Rạp</label>
                <select class="form-control" name="id_rap">
                    <option value="">— Tất cả rạp —</option>
                    <?php foreach (rap_all() as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= (!empty($id_rap) && (int)$id_rap===(int)$r['id'])?'selected':'' ?>><?= htmlspecialchars($r['ten_rap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3 mb-10"><label>Từ ngày</label><input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from ?? '') ?>" /></div>
            <div class="col-6 col-md-3 mb-10"><label>Đến ngày</label><input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to ?? '') ?>" /></div>
            <div class="col-12 col-md-2 mb-10"><button class="button button-primary" type="submit">Lọc</button></div>
        </div>
        <div class="row mb-10">
            <div class="col-auto"><a class="button" href="index.php?act=home&scope=cum&period=today<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Hôm nay</a></div>
            <div class="col-auto"><a class="button" href="index.php?act=home&scope=cum&period=week<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Tuần này</a></div>
            <div class="col-auto"><a class="button" href="index.php?act=home&scope=cum&period=month<?= $id_rap?('&id_rap='.(int)$id_rap):'' ?>">Tháng này</a></div>
        </div>
    </form>

    <div class="row">
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng doanh thu</h4></div><div class="content"><h2><?= number_format((int)($sum_revenue ?? 0)) ?> VNĐ</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng vé đã bán</h4></div><div class="content"><h2><?= (int)($sum_tickets ?? 0) ?> VÉ</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng doanh thu hôm nay</h4></div><div class="content"><h2><?= number_format((int)($rev_today ?? 0)) ?> VNĐ</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng doanh thu tuần này</h4></div><div class="content"><h2><?= number_format((int)($rev_week ?? 0)) ?> VNĐ</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng doanh thu tháng này</h4></div><div class="content"><h2><?= number_format((int)($rev_month ?? 0)) ?> VNĐ</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Tổng phim đang chiếu</h4></div><div class="content"><h2><?= (int)($movies_showing ?? 0) ?> Phim</h2></div></div>
        </div>
        <div class="col-xlg-6 col-md-6 col-12 mb-30">
            <div class="top-report"><div class="head"><h4>Combo được đặt nhiều nhất</h4></div><div class="content"><h2><?= isset($best_combo_r['combo']) ? ('Đã có '.(int)$best_combo_r['so_luong_dat'].' '.$best_combo_r['combo'].' được đặt') : '—' ?></h2></div></div>
        </div>
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head"><h3 class="title">Doanh thu theo ngày</h3></div>
                <div class="box-body"><div id="chart_rev_date" style="height:400px"></div></div>
            </div>
        </div>
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head"><h3 class="title">Doanh thu theo rạp</h3></div>
                <div class="box-body"><div id="chart_rev_rap" style="height:400px"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock(){var d=new Date();function p(n){return (n<10?'0':'')+n}document.getElementById('real-time-clock').innerText=p(d.getHours())+":"+p(d.getMinutes())+":"+p(d.getSeconds());setTimeout(updateClock,1000);}updateClock();

    // Charts using Google Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCumCharts);
    function drawCumCharts(){
        var revDate = [['Ngày','Doanh thu']];
        var rowsDate = <?= json_encode($rev_by_date_rows ?? []) ?>;
        rowsDate.forEach(function(r){ revDate.push([r[0], Number(r[1])]); });
        var dataDate = google.visualization.arrayToDataTable(revDate);
        var optDate = { legend:{position:'none'}, areaOpacity:0.05, colors:['#3b82f6'], chartArea:{left:60,top:20,width:'85%',height:'75%'}, vAxis:{format:'short'} };
        var chartDate = new google.visualization.AreaChart(document.getElementById('chart_rev_date'));
        chartDate.draw(dataDate, optDate);

        var revRap = [['Rạp','Doanh thu']];
        var rowsRap = <?= json_encode($rev_by_rap_rows ?? []) ?>;
        rowsRap.forEach(function(r){ revRap.push([r[0], Number(r[1])]); });
        var dataRap = google.visualization.arrayToDataTable(revRap);
        var optRap = { legend:{position:'none'}, colors:['#10b981'], chartArea:{left:140,top:20,width:'70%',height:'75%'}, vAxis:{format:'short'} };
        var chartRap = new google.visualization.BarChart(document.getElementById('chart_rev_rap'));
        chartRap.draw(dataRap, optRap);
    }
</script>
