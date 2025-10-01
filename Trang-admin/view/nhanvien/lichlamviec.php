<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Lịch làm việc của tôi</h3></div></div></div>

    <?php $ymSel = $_GET['ym'] ?? date('Y-m'); $first = $ymSel.'-01'; $prevYm = date('Y-m', strtotime($first.' -1 month')); $nextYm = date('Y-m', strtotime($first.' +1 month')); ?>
    <div class="row align-items-center mb-10">
        <div class="col-12 col-md-6">
            <a class="button button-sm" href="index.php?act=nv_lichlamviec&ym=<?= htmlspecialchars($prevYm) ?>">« Tháng trước</a>
            <span style="font-weight:600;margin:0 10px;"><?= htmlspecialchars(date('m / Y', strtotime($first))) ?></span>
            <a class="button button-sm" href="index.php?act=nv_lichlamviec&ym=<?= htmlspecialchars($nextYm) ?>">Tháng sau »</a>
        </div>
    </div>
    <form method="get" action="index.php" class="mb-15">
        <input type="hidden" name="act" value="nv_lichlamviec" />
        <div class="row align-items-end">
            <div class="col-12 col-md-3 mb-10"><label>Tháng</label><input class="form-control" type="month" name="ym" value="<?= htmlspecialchars($ymSel) ?>" /></div>
            <div class="col-12 col-md-2 mb-10"><button class="button" type="submit">Xem</button></div>
        </div>
    </form>

    <?php
        $tsFirst = strtotime($first);
        $daysInMonth = (int)date('t', $tsFirst);
        $startDow = (int)date('N', $tsFirst); // 1=Mon..7=Sun
        $dowName = [1=>'T2',2=>'T3',3=>'T4',4=>'T5',5=>'T6',6=>'T7',7=>'CN'];
        $byDay = [];
        foreach (($llv ?? []) as $r){ $d = (int)date('j', strtotime($r['ngay'])); $byDay[$d][] = $r; }
        $today = date('Y-m-d');
    ?>

    <style>
        .cal { display:grid; grid-template-columns:repeat(7, minmax(150px,1fr)); gap:8px; }
        .cal .cell { border:1px solid #e5e7eb; border-radius:10px; background:linear-gradient(180deg,#ffffff, #fafafa); min-height:140px; padding:10px; box-shadow:0 1px 2px rgba(0,0,0,0.04); transition:box-shadow .2s, transform .05s; }
        .cal .cell:hover { box-shadow:0 4px 10px rgba(0,0,0,0.08); transform:translateY(-1px); }
        .cal .cell.weekend { background:linear-gradient(180deg,#ffffff,#f8fafc); }
        .cal .cell.has { border-color:#c7d2fe; }
        .cal .cell.today { border-color:#60a5fa; box-shadow:0 0 0 2px rgba(96,165,250,.25) inset; }
        .cal .head { font-weight:700; text-align:center; padding:6px 0; color:#374151; letter-spacing:.2px; }
        .cal .date { font-weight:700; margin-bottom:6px; color:#111827; display:flex; align-items:center; justify-content:space-between; }
        .badge { font-size:11px; color:#374151; background:#eef2ff; border:1px solid #e0e7ff; padding:2px 6px; border-radius:999px; }
        .shift { font-size:12px; padding:6px 8px; border-radius:8px; margin-bottom:6px; line-height:1.2; border:1px solid transparent; }
        .shift .time { font-weight:700; }
        .shift.morning { background:#eff6ff; border-color:#dbeafe; color:#1d4ed8; }
        .shift.afternoon { background:#fffbeb; border-color:#fde68a; color:#b45309; }
        .shift.evening { background:#eef2ff; border-color:#c7d2fe; color:#4338ca; }
        .shift.office { background:#ecfdf5; border-color:#a7f3d0; color:#047857; }
        .shift.default { background:#f3f4f6; border-color:#e5e7eb; color:#374151; }
        .muted { color:#6b7280; }
        .legend { display:flex; gap:10px; flex-wrap:wrap; margin:10px 0 14px; }
        .legend .item { display:flex; align-items:center; gap:6px; }
        .dot { width:12px; height:12px; border-radius:999px; border:1px solid rgba(0,0,0,.08); }
        .dot.morning { background:#dbeafe; }
        .dot.afternoon { background:#fde68a; }
        .dot.evening { background:#c7d2fe; }
        .dot.office { background:#a7f3d0; }
    </style>

    <div class="row mb-10">
        <?php for ($i=1;$i<=7;$i++): ?>
            <div class="head"><?= $dowName[$i] ?></div>
        <?php endfor; ?>
    </div>
    <div class="legend">
        <div class="item"><span class="dot morning"></span> Sáng</div>
        <div class="item"><span class="dot afternoon"></span> Chiều</div>
        <div class="item"><span class="dot evening"></span> Tối</div>
        <div class="item"><span class="dot office"></span> Hành chính</div>
    </div>

    <div class="cal">
        <?php
            for ($blank=1; $blank<$startDow; $blank++) echo '<div class="cell muted"></div>';
            for ($d=1; $d<=$daysInMonth; $d++){
                $dateStr = sprintf('%s-%02d', $ymSel, $d);
                $dow = (int)date('N', strtotime($dateStr));
                $classes = ['cell'];
                if ($dow>=6) $classes[] = 'weekend';
                if (!empty($byDay[$d])) $classes[] = 'has';
                if ($dateStr === $today) $classes[] = 'today';
                echo '<div class="'.implode(' ', $classes).'">';
                $badge = !empty($byDay[$d]) ? '<span class="badge">'.count($byDay[$d]).' ca</span>' : '';
                echo '<div class="date">'.$d.$badge.'</div>';
                if (!empty($byDay[$d])){
                    foreach ($byDay[$d] as $s){
                        $time = htmlspecialchars($s['gio_bat_dau']).' → '.htmlspecialchars($s['gio_ket_thuc']);
                        $caRaw = trim((string)($s['ca_lam'] ?? ''));
                        $ca = htmlspecialchars($caRaw);
                        $rap = htmlspecialchars($s['ten_rap'] ?? '');
                        $ghi = htmlspecialchars($s['ghi_chu'] ?? '');
                        $caL = mb_strtolower($caRaw, 'UTF-8');
                        $type = 'default';
                        if ($caL !== ''){
                            if (strpos($caL, 'sáng') !== false || strpos($caL, 'sang') !== false) $type = 'morning';
                            elseif (strpos($caL, 'chiều') !== false || strpos($caL, 'chieu') !== false) $type = 'afternoon';
                            elseif (strpos($caL, 'tối') !== false || strpos($caL, 'toi') !== false || strpos($caL, 'đêm') !== false || strpos($caL, 'dem') !== false) $type = 'evening';
                            elseif (strpos($caL, 'hành') !== false || strpos($caL, 'hanh') !== false) $type = 'office';
                        }
                        echo '<div class="shift '.$type.'"><div class="time">'.$time.'</div>';
                        if ($ca!=='') echo '<div>'.$ca.'</div>';
                        if ($rap!=='') echo '<div class="muted">'.$rap.'</div>';
                        if ($ghi!=='') echo '<div class="muted">'.$ghi.'</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        ?>
    </div>
</div>
