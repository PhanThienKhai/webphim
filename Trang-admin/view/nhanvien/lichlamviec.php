<?php include __DIR__ . '/../home/sideheader.php'; ?>

<style>
    /* ===== HEADER SECTION ===== */
    .calendar-header {
        background: linear-gradient(135deg, #77787cff 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .calendar-header h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .calendar-header p {
        margin: 8px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    /* ===== NAVIGATION SECTION ===== */
    .calendar-nav {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .nav-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-month {
        background: linear-gradient(135deg, #77787cff 0%, #764ba2 100%);
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
    }

    .btn-month:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
    }

    .month-display {
        font-weight: 700;
        font-size: 18px;
        color: #1f2937;
        min-width: 140px;
        text-align: center;
    }

    .filter-form {
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }

    .filter-form label {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-form input {
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .filter-form input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-view {
        background: linear-gradient(135deg, #77787cff 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
    }

    /* ===== LEGEND SECTION ===== */
    .legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin: 20px 0;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .legend .item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .dot {
        width: 14px;
        height: 14px;
        border-radius: 6px;
        border: 2px solid rgba(0, 0, 0, 0.1);
    }

    .dot.morning {
        background: #dbeafe;
    }

    .dot.afternoon {
        background: #fde68a;
    }

    .dot.evening {
        background: #c7d2fe;
    }

    .dot.office {
        background: #a7f3d0;
    }

    /* ===== CALENDAR GRID ===== */
    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        margin-bottom: 15px;
    }

    .weekday-header {
        font-weight: 700;
        text-align: center;
        padding: 12px 8px;
        color: #374151;
        letter-spacing: 0.5px;
        font-size: 15px;
        background: #f3f4f6;
        border-radius: 8px;
        text-transform: uppercase;
    }

    .cal {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
    }

    .cal .cell {
        border-radius: 12px;
        background: linear-gradient(180deg, #ffffff, #fafafa);
        min-height: 160px;
        padding: 7px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .cal .cell:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
        border-color: #667eea;
    }

    .cal .cell.weekend {
        background: linear-gradient(180deg, #fafafa, #f3f4f6);
    }

    .cal .cell.has {
        border-color: #c7d2fe;
        background: linear-gradient(180deg, #ffffff, #f5f3ff);
    }

    .cal .cell.today {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.15) inset;
        background: linear-gradient(180deg, #fafbff, #f0f4ff);
    }

    .cal .date {
        font-weight: 700;
        margin-bottom: 10px;
        color: #111827;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 16px;
    }

    .badge {
        font-size: 11px;
        color: #667eea;
        background: #eef2ff;
        border: 1px solid #e0e7ff;
        padding: 3px 8px;
        border-radius: 999px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .shifts-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
    }

    .shift {
        font-size: 12px;
        padding: 8px 10px;
        border-radius: 8px;
        line-height: 1.3;
        border: 1.5px solid transparent;
        transition: all 0.2s ease;
    }

    .shift:hover {
        transform: translateX(2px);
    }

    .shift .time {
        font-weight: 700;
        display: block;
        margin-bottom: 2px;
    }

    .shift.morning {
        background: #eff6ff;
        border-color: #dbeafe;
        color: #1d4ed8;
    }

    .shift.afternoon {
        background: #fffbeb;
        border-color: #fde68a;
        color: #b45309;
    }

    .shift.evening {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
    }

    .shift.office {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
    }

    .shift.default {
        background: #f3f4f6;
        border-color: #e5e7eb;
        color: #374151;
    }

    .shift-detail {
        font-size: 11px;
        opacity: 0.85;
        margin-top: 2px;
    }

    .muted {
        color: #6b7280;
        font-size: 11px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-day {
        color: #9ca3af;
        font-size: 13px;
        font-style: italic;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .cal {
            gap: 10px;
        }

        .cal .cell {
            min-height: 140px;
            padding: 12px;
        }

        .shift {
            font-size: 11px;
            padding: 6px 8px;
        }
    }

    @media (max-width: 768px) {
        .calendar-header {
            padding: 20px;
        }

        .calendar-header h3 {
            font-size: 22px;
        }

        .calendar-nav {
            flex-direction: column;
            align-items: stretch;
        }

        .nav-buttons {
            justify-content: center;
        }

        .filter-form {
            flex-direction: column;
        }

        .filter-form label {
            display: block;
            margin-bottom: 4px;
        }

        .filter-form input,
        .btn-view {
            width: 100%;
        }

        .calendar-weekdays {
            gap: 8px;
        }

        .cal {
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .cal .cell {
            min-height: 120px;
            padding: 10px;
        }

        .weekday-header {
            font-size: 12px;
            padding: 8px 4px;
        }

        .cal .date {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .shift {
            font-size: 10px;
            padding: 5px 6px;
        }

        .legend {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 480px) {
        .cal {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .cal .cell {
            min-height: 100px;
            padding: 8px;
        }

        .weekday-header {
            font-size: 11px;
            padding: 6px 2px;
        }

        .cal .date {
            font-size: 12px;
            margin-bottom: 6px;
        }

        .shift {
            font-size: 9px;
            padding: 4px 5px;
        }

        .legend {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }
</style>

<div class="content-body">
    <div style="margin: 0 auto; padding: 20px;">

        <!-- Header -->
        <div class="calendar-header">
            <h3>Lịch Làm Việc Của Tôi</h3>
            <p>Xem chi tiết ca làm việc và bộ phận giao cho bạn</p>
        </div>

        <!-- Navigation -->
        <?php 
            $ymSel = $_GET['ym'] ?? date('Y-m'); 
            $first = $ymSel.'-01'; 
            $prevYm = date('Y-m', strtotime($first.' -1 month')); 
            $nextYm = date('Y-m', strtotime($first.' +1 month')); 
        ?>
        <div class="calendar-nav">
            <div class="nav-buttons">
                <a class="btn-month" href="index.php?act=nv_lichlamviec&ym=<?= htmlspecialchars($prevYm) ?>">
                    « Tháng Trước
                </a>
                <div class="month-display">
                    <?= date('F Y', strtotime($first)) ?>
                </div>
                <a class="btn-month" href="index.php?act=nv_lichlamviec&ym=<?= htmlspecialchars($nextYm) ?>">
                    Tháng Sau »
                </a>
            </div>

            <form method="get" action="index.php" class="filter-form">
                <input type="hidden" name="act" value="nv_lichlamviec" />
                <div>
                    <label for="ym">Chọn Tháng</label>
                    <input type="month" id="ym" name="ym" value="<?= htmlspecialchars($ymSel) ?>" />
                </div>
                <button class="btn-view" type="submit">Xem</button>
            </form>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="item">
                <span class="dot morning"></span>
                <span>Sáng</span>
            </div>
            <div class="item">
                <span class="dot afternoon"></span>
                <span>Chiều</span>
            </div>
            <div class="item">
                <span class="dot evening"></span>
                <span>Tối/Đêm</span>
            </div>
            <div class="item">
                <span class="dot office"></span>
                <span>Hành Chính</span>
            </div>
        </div>

        <!-- Calendar -->
        <?php
            $tsFirst = strtotime($first);
            $daysInMonth = (int)date('t', $tsFirst);
            $startDow = (int)date('N', $tsFirst); // 1=Mon..7=Sun
            $dowName = [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'Chủ Nhật'];
            $byDay = [];
            foreach (($llv ?? []) as $r){ $d = (int)date('j', strtotime($r['ngay'])); $byDay[$d][] = $r; }
            $today = date('Y-m-d');
        ?>

        <div class="calendar-weekdays">
            <?php for ($i=1; $i<=7; $i++): ?>
                <div class="weekday-header"><?= $dowName[$i] ?></div>
            <?php endfor; ?>
        </div>

        <div class="cal">
            <?php
                // Blank cells at start
                for ($blank=1; $blank<$startDow; $blank++) {
                    echo '<div class="cell muted empty-day">-</div>';
                }
                
                // Days of month
                for ($d=1; $d<=$daysInMonth; $d++){
                    $dateStr = sprintf('%s-%02d', $ymSel, $d);
                    $dow = (int)date('N', strtotime($dateStr));
                    $classes = ['cell'];
                    if ($dow>=6) $classes[] = 'weekend';
                    if (!empty($byDay[$d])) $classes[] = 'has';
                    if ($dateStr === $today) $classes[] = 'today';
                    
                    echo '<div class="'.implode(' ', $classes).'">';
                    
                    // Date header
                    $badge = !empty($byDay[$d]) ? '<span class="badge">'.count($byDay[$d]).' ca</span>' : '';
                    echo '<div class="date">'.$d.$badge.'</div>';
                    
                    // Shifts
                    if (!empty($byDay[$d])){
                        echo '<div class="shifts-container">';
                        foreach ($byDay[$d] as $s){
                            $time = htmlspecialchars($s['gio_bat_dau']).' → '.htmlspecialchars($s['gio_ket_thuc']);
                            $caRaw = trim((string)($s['ca_lam'] ?? ''));
                            $ca = htmlspecialchars($caRaw);
                            $rap = htmlspecialchars($s['ten_rap'] ?? '');
                            $ghi = htmlspecialchars($s['ghi_chu'] ?? '');
                            
                            // Determine shift type
                            $caL = mb_strtolower($caRaw, 'UTF-8');
                            $type = 'default';
                            if ($caL !== ''){
                                if (strpos($caL, 'sáng') !== false || strpos($caL, 'sang') !== false) $type = 'morning';
                                elseif (strpos($caL, 'chiều') !== false || strpos($caL, 'chieu') !== false) $type = 'afternoon';
                                elseif (strpos($caL, 'tối') !== false || strpos($caL, 'toi') !== false || strpos($caL, 'đêm') !== false || strpos($caL, 'dem') !== false) $type = 'evening';
                                elseif (strpos($caL, 'hành') !== false || strpos($caL, 'hanh') !== false) $type = 'office';
                            }
                            
                            echo '<div class="shift '.$type.'">';
                            echo '<div class="time">'.$time.'</div>';
                            if ($ca!=='') echo '<div>'.$ca.'</div>';
                            if ($rap!=='') echo '<div class="muted shift-detail">📍 '.$rap.'</div>';
                            if ($ghi!=='') echo '<div class="muted shift-detail">📝 '.$ghi.'</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="shifts-container" style="color: #9ca3af; font-size: 12px; font-style: italic;">Ngày nghỉ</div>';
                    }
                    
                    echo '</div>';
                }
            ?>
        </div>

    </div>
</div>
