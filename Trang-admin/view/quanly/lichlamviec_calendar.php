<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto">
            <div class="page-heading"><h3>Phân công lịch làm việc - Calendar View</h3></div>
            <?php if (!empty($rap['ten_rap'])): ?><div style="color:#6b7280; font-size: 14px;">Rạp: <strong><?= htmlspecialchars($rap['ten_rap']) ?></strong></div><?php endif; ?>
            <!-- <div class="mt-10">
                <a href="index.php?act=ql_lichlamviec" class="btn btn-outline-secondary btn-sm">← Xem dạng bảng</a>
            </div> -->
        </div>
    </div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>


    <div class="calendar-management-container">
        <!-- Employee Selection Panel -->
        <div class="employee-selection-panel">
            <h4>Chọn nhân viên để phân công</h4>
            <div class="employee-grid">
                <?php foreach (($ds_nv ?? []) as $nv): ?>
                    <div class="employee-card" data-employee-id="<?= (int)$nv['id'] ?>">
                        <input type="checkbox" class="employee-checkbox" value="<?= (int)$nv['id'] ?>">
                        <div class="employee-content">
                            <div class="employee-avatar" style="background-color: <?= $employee_colors[(int)$nv['id']] ?? '#dde3edff' ?>">
                                <?= strtoupper(substr($nv['name'], 0, 2)) ?>
                            </div>
                            <div class="employee-info">
                                <div class="employee-name"><?= htmlspecialchars($nv['name']) ?></div>
                                <div class="employee-role">Nhân viên</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="employee-actions">
                <button type="button" class="btn-selection" id="selectAllBtn">Chọn tất cả</button>
                <button type="button" class="btn-selection" id="clearAllBtn">Bỏ chọn</button>
            </div>
        </div>

        <!-- Shift Templates -->
        <div class="shift-templates-panel">
            <h4>Khung giờ làm việc</h4>
            <div class="templates-grid">
                <button type="button" class="template-card" data-shift="Sáng" data-start="08:00" data-end="12:00">
                    <div class="template-icon">🌅</div>
                    <div class="template-info">
                        <div class="template-name">Ca Sáng</div>
                        <div class="template-time">8:00 - 12:00</div>
                    </div>
                </button>
                
                <button type="button" class="template-card" data-shift="Chiều" data-start="13:00" data-end="17:00">
                    <div class="template-icon">☀️</div>
                    <div class="template-info">
                        <div class="template-name">Ca Chiều</div>
                        <div class="template-time">13:00 - 17:00</div>
                    </div>
                </button>
                
                <button type="button" class="template-card" data-shift="Tối" data-start="17:00" data-end="22:00">
                    <div class="template-icon">🌙</div>
                    <div class="template-info">
                        <div class="template-name">Ca Tối</div>
                        <div class="template-time">17:00 - 22:00</div>
                    </div>
                </button>
                
                <button type="button" class="template-card" data-shift="Hành chính" data-start="09:00" data-end="18:00">
                    <div class="template-icon">🏢</div>
                    <div class="template-info">
                        <div class="template-name">Hành chính</div>
                        <div class="template-time">9:00 - 18:00</div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <?php 
    $ymSel = $_GET['ym'] ?? date('Y-m'); 
    $first = $ymSel.'-01'; 
    $prevYm = date('Y-m', strtotime($first.' -1 month')); 
    $nextYm = date('Y-m', strtotime($first.' +1 month')); 
    ?>
    
    <div class="calendar-section">
        <div class="calendar-header">
            <div class="calendar-nav">
                <a class="nav-btn prev" href="index.php?act=ql_lichlamviec_calendar&ym=<?= htmlspecialchars($prevYm) ?>">« Tháng trước</a>
                <h3 class="current-month"><?= htmlspecialchars(date('F Y', strtotime($first))) ?></h3>
                <a class="nav-btn next" href="index.php?act=ql_lichlamviec_calendar&ym=<?= htmlspecialchars($nextYm) ?>">Tháng sau »</a>
            </div>
            
            <form method="get" action="index.php" class="month-selector">
                <input type="hidden" name="act" value="ql_lichlamviec_calendar" />
                <input class="month-input" type="month" name="ym" value="<?= htmlspecialchars($ymSel) ?>" />
                <button class="month-btn" type="submit">📅 Chuyển</button>
            </form>
        </div>

        <?php
        $tsFirst = strtotime($first);
        $daysInMonth = (int)date('t', $tsFirst);
        $startDow = (int)date('N', $tsFirst); // 1=Mon..7=Sun
        $dowName = [1=>'T2',2=>'T3',3=>'T4',4=>'T5',5=>'T6',6=>'T7',7=>'CN'];
        
        // Group shifts by day
        $byDay = [];
        foreach (($llv ?? []) as $r){ 
            $d = (int)date('j', strtotime($r['ngay'])); 
            $byDay[$d][] = $r; 
        }
        $today = date('Y-m-d');
        ?>

        <div class="calendar-legend">
            <div class="legend-item"><span class="dot morning"></span> Ca Sáng</div>
            <div class="legend-item"><span class="dot afternoon"></span> Ca Chiều</div>
            <div class="legend-item"><span class="dot evening"></span> Ca Tối</div>
            <div class="legend-item"><span class="dot office"></span> Hành chính</div>
        </div>

        <div class="calendar-grid">
            <!-- Day headers -->
            <?php for ($i=1;$i<=7;$i++): ?>
                <div class="day-header"><?= $dowName[$i] ?></div>
            <?php endfor; ?>
            
            <!-- Empty cells for start of month -->
            <?php for ($blank=1; $blank<$startDow; $blank++): ?>
                <div class="day-cell empty"></div>
            <?php endfor; ?>
            
            <!-- Days of month -->
            <?php for ($d=1; $d<=$daysInMonth; $d++): 
                $dateStr = sprintf('%s-%02d', $ymSel, $d);
                $dow = (int)date('N', strtotime($dateStr));
                $classes = ['day-cell'];
                if ($dow>=6) $classes[] = 'weekend';
                if (!empty($byDay[$d])) $classes[] = 'has-shifts';
                if ($dateStr === $today) $classes[] = 'today';
                $classes[] = 'clickable';
            ?>
                <div class="<?= implode(' ', $classes) ?>" data-date="<?= $dateStr ?>" onclick="openAssignModal('<?= $dateStr ?>')">
                    <div class="day-number">
                        <?= $d ?>
                        <?php if (!empty($byDay[$d])): ?>
                            <span class="shift-count"><?= count($byDay[$d]) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="day-shifts">
                        <?php if (!empty($byDay[$d])): ?>
                            <?php foreach ($byDay[$d] as $s): 
                                $time = htmlspecialchars($s['gio_bat_dau']).' - '.htmlspecialchars($s['gio_ket_thuc']);
                                $caRaw = trim((string)($s['ca_lam'] ?? ''));
                                $employee = htmlspecialchars($s['ten_nv'] ?? '');
                                $caL = mb_strtolower($caRaw, 'UTF-8');
                                $type = 'default';
                                if ($caL !== ''){
                                    if (strpos($caL, 'sáng') !== false || strpos($caL, 'sang') !== false) $type = 'morning';
                                    elseif (strpos($caL, 'chiều') !== false || strpos($caL, 'chieu') !== false) $type = 'afternoon';
                                    elseif (strpos($caL, 'tối') !== false || strpos($caL, 'toi') !== false || strpos($caL, 'đêm') !== false || strpos($caL, 'dem') !== false) $type = 'evening';
                                    elseif (strpos($caL, 'hành') !== false || strpos($caL, 'hanh') !== false) $type = 'office';
                                }
                            ?>
                                <div class="shift-item <?= $type ?>" title="<?= $employee ?> - <?= $time ?>">
                                    <div class="shift-employee"><?= $employee ?></div>
                                    <div class="shift-time"><?= $time ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div id="assignModal" class="modal-overlay" style="display: none;">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3>📝 Phân công ca làm việc hàng loạt</h3>
            <button type="button" class="modal-close" onclick="closeAssignModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <form id="assignForm">
                <input type="hidden" id="assignDate" name="ngay">
                <input type="hidden" id="assignEmployees" name="employees">
                
                <!-- Date Range Selection -->
                <div class="form-section">
                    <h4 class="section-title">📅 Chọn khoảng thời gian</h4>
                    <div class="date-range-options">
                        <label class="radio-option">
                            <input type="radio" name="dateMode" value="single" checked>
                            <span>Ngày đơn</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="dateMode" value="range">
                            <span>Khoảng thời gian</span>
                        </label>
                    </div>
                    
                    <div id="singleDateSection" class="date-section">
                        <div class="form-group">
                            <label class="form-label">Chọn ngày</label>
                            <input type="date" class="form-control" id="displayDate" readonly>
                        </div>
                    </div>
                    
                    <div id="rangeDateSection" class="date-section" style="display: none;">
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Từ ngày</label>
                                <input type="date" class="form-control" id="startDate">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Đến ngày</label>
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Áp dụng cho các ngày trong tuần</label>
                            <div class="weekday-selector">
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="1" checked>
                                    <span>T2</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="2" checked>
                                    <span>T3</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="3" checked>
                                    <span>T4</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="4" checked>
                                    <span>T5</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="5" checked>
                                    <span>T6</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="6">
                                    <span>T7</span>
                                </label>
                                <label class="weekday-checkbox">
                                    <input type="checkbox" value="0">
                                    <span>CN</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employee Selection -->
                <div class="form-section">
                    <h4 class="section-title">👥 Nhân viên được chọn</h4>
                    <div id="selectedEmployees" class="selected-employees"></div>
                </div>
                
                <!-- Shift Templates Selection -->
                <div class="form-section">
                    <h4 class="section-title">⏰ Chọn ca làm việc (có thể chọn nhiều ca)</h4>
                    <div class="shift-selection-grid">
                        <label class="shift-checkbox-card">
                            <input type="checkbox" class="shift-template-check" data-shift="Sáng" data-start="08:00" data-end="12:00">
                            <div class="shift-card-content">
                                <div class="shift-icon">🌅</div>
                                <div class="shift-name">Ca Sáng</div>
                                <div class="shift-time">8:00 - 12:00</div>
                            </div>
                        </label>
                        
                        <label class="shift-checkbox-card">
                            <input type="checkbox" class="shift-template-check" data-shift="Chiều" data-start="13:00" data-end="17:00">
                            <div class="shift-card-content">
                                <div class="shift-icon">☀️</div>
                                <div class="shift-name">Ca Chiều</div>
                                <div class="shift-time">13:00 - 17:00</div>
                            </div>
                        </label>
                        
                        <label class="shift-checkbox-card">
                            <input type="checkbox" class="shift-template-check" data-shift="Tối" data-start="17:00" data-end="22:00">
                            <div class="shift-card-content">
                                <div class="shift-icon">🌙</div>
                                <div class="shift-name">Ca Tối</div>
                                <div class="shift-time">17:00 - 22:00</div>
                            </div>
                        </label>
                        
                        <label class="shift-checkbox-card">
                            <input type="checkbox" class="shift-template-check" data-shift="Hành chính" data-start="09:00" data-end="18:00">
                            <div class="shift-card-content">
                                <div class="shift-icon">🏢</div>
                                <div class="shift-name">Hành chính</div>
                                <div class="shift-time">9:00 - 18:00</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="custom-shift-toggle">
                        <button type="button" class="btn-link" id="toggleCustomShift">
                            ➕ Thêm ca tùy chỉnh
                        </button>
                    </div>
                    
                    <div id="customShiftSection" style="display: none;">
                        <div class="custom-shift-form">
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Tên ca</label>
                                    <input type="text" class="form-control" id="customShiftName" placeholder="VD: Ca khuya">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Giờ bắt đầu</label>
                                    <input type="time" class="form-control" id="customStartTime">
                                </div>
                                <div class="form-col">
                                    <label class="form-label">Giờ kết thúc</label>
                                    <input type="time" class="form-control" id="customEndTime">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-success" id="addCustomShift">
                                ✅ Thêm ca này
                            </button>
                        </div>
                    </div>
                    
                    <div id="customShiftsList" class="custom-shifts-list"></div>
                </div>
                
                <!-- Note -->
                <div class="form-section">
                    <h4 class="section-title">📝 Ghi chú chung</h4>
                    <textarea class="form-control" id="shiftNote" name="ghi_chu" rows="2" placeholder="Ghi chú chung cho tất cả các ca (tùy chọn)..."></textarea>
                </div>
                
                <!-- Summary -->
                <div class="assignment-summary" id="assignmentSummary">
                    <div class="summary-content">
                        <strong>📊 Tổng quan:</strong>
                        <span id="summaryText">Chọn nhân viên, ngày và ca làm việc để xem tổng quan</span>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeAssignModal()">❌ Hủy</button>
            <button type="button" class="btn btn-primary" id="saveAssignment">💾 Lưu phân công hàng loạt</button>
        </div>
    </div>
</div>

<style>
/* Alert Styles - Nền đen chữ trắng */
.alert {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    background: #2d3748 !important;
    color: #ffffff !important;
    border: 1px solid #4a5568 !important;
    font-size: 14px;
    font-weight: 500;
}

.alert-danger, .alert-success, .alert-warning, .alert-info {
    background: #2d3748 !important;
    color: #ffffff !important;
    border: 1px solid #4a5568 !important;
}

/* Enhanced Calendar Styles based on employee view */
.calendar-management-container {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.employee-selection-panel, .shift-templates-panel {
    flex: 1;
    min-width: 300px;
    background: linear-gradient(135deg, #9dabe8ff 0%, #a48dbaff 100%);
    border-radius: 15px;
    padding: 25px;
    color: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.employee-selection-panel h4, .shift-templates-panel h4 {
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
}

.employee-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.employee-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(10px);
    user-select: none;
    position: relative;
}

.employee-card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.employee-card.selected {
    background: rgba(255, 255, 255, 0.25);
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Visual checkmark for selected cards */
.employee-card.selected::after {
    content: '✓';
    position: absolute;
    top: 8px;
    right: 8px;
    background: #10b981;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    pointer-events: none;
}

.employee-checkbox {
    display: none;
}

.employee-content {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    margin: 0;
    pointer-events: none; /* Prevent child elements from blocking clicks */
}

.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 14px;
    pointer-events: none; /* Prevent blocking clicks */
}

.employee-info {
    flex: 1;
    pointer-events: none; /* Prevent blocking clicks */
}

.employee-name {
    font-weight: 600;
    font-size: 14px;
    pointer-events: none; /* Prevent blocking clicks */
}

.employee-role {
    font-size: 12px;
    opacity: 0.8;
    pointer-events: none; /* Prevent blocking clicks */
}

.employee-actions {
    /* display: flex; */
    gap: 10px;
    justify-content: center;
}

.btn-selection {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 12px;
}

.btn-selection:hover {
    background: rgba(255, 255, 255, 0.3);
}

.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.template-card {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    color: white;
}

.template-card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.template-card.selected {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
}

.template-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.template-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.template-time {
    font-size: 11px;
    opacity: 0.8;
}

/* Calendar Section */
.calendar-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.calendar-nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.3s;
}

.nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.current-month {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.month-selector {
    display: flex;
    gap: 10px;
    align-items: center;
}

.month-input {
    padding: 8px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
}

.month-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
}

.month-btn:hover {
    transform: translateY(-1px);
}

.calendar-legend {
    display: flex;
    gap: 27px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    /* justify-content: center; */
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,.1);
}

.dot.morning { background: #dbeafe; }
.dot.afternoon { background: #fde68a; }
.dot.evening { background: #c7d2fe; }
.dot.office { background: #a7f3d0; }

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.day-header {
    background: #374151;
    color: white;
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.day-cell {
    background: white;
    min-height: 120px;
    padding: 10px;
    transition: all 0.2s;
    position: relative;
}

.day-cell.clickable {
    cursor: pointer;
}

.day-cell.clickable:hover {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    transform: scale(1.02);
    z-index: 10;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.day-cell.weekend {
    background: #f8fafc;
}

.day-cell.today {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 2px solid #f59e0b;
}

.day-cell.has-shifts {
    border-left: 4px solid #10b981;
}

.day-cell.empty {
    background: #f9fafb;
    cursor: default;
}

.day-number {
    font-weight: 700;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #111827;
}

.shift-count {
    background: #3b82f6;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
}

.day-shifts {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.shift-item {
    font-size: 10px;
    padding: 4px 6px;
    border-radius: 6px;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.shift-item:hover {
    transform: scale(1.05);
}

.shift-item.morning { background: #eff6ff; border-color: #dbeafe; color: #1d4ed8; }
.shift-item.afternoon { background: #fffbeb; border-color: #fde68a; color: #b45309; }
.shift-item.evening { background: #eef2ff; border-color: #c7d2fe; color: #4338ca; }
.shift-item.office { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
.shift-item.default { background: #f3f4f6; border-color: #e5e7eb; color: #374151; }

.shift-employee {
    font-weight: 600;
    margin-bottom: 2px;
}

.shift-time {
    opacity: 0.8;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(3px);
}

.modal-container {
    background: white;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    max-height: 95vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transform: scale(1);
    transition: transform 0.3s ease;
    margin: 10px;
    display: flex;
    flex-direction: column;
}

.modal-large {
    max-width: 900px;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-body {
    padding: 20px;
    max-height: calc(95vh - 160px);
    overflow-y: auto;
    flex: 1;
}

.modal-footer {
    padding: 12px 20px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    flex-shrink: 0;
}

.form-group {
    margin-bottom: 20px;
    margin-top: 22px;

}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-col {
    flex: 1;
}

.selected-employees {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 40px;
    padding: 8px;
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
}

.selected-employee-tag {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-management-container {
        flex-direction: column;
    }
    
    .employee-grid {
        grid-template-columns: 1fr;
    }
    
    .templates-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .calendar-header {
        flex-direction: column;
        text-align: center;
    }
    
    .calendar-nav {
        order: 2;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    /* Modal responsive */
    .modal-container {
        width: 95%;
        max-width: none;
        margin: 10px;
        max-height: 95vh;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 15px 20px;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
    }
}

/* New styles for bulk assignment features */
.form-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-size: 15px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-range-options {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.radio-option {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    user-select: none;
}

.radio-option:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.radio-option input[type="radio"] {
    margin: 0;
    cursor: pointer;
}

.radio-option input[type="radio"]:checked + span {
    font-weight: 600;
    color: #667eea;
}

.radio-option:has(input:checked) {
    background: #ede9fe;
    border-color: #667eea;
}

.weekday-selector {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.weekday-checkbox {
    flex: 1;
    min-width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    user-select: none;
}

.weekday-checkbox:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.weekday-checkbox input[type="checkbox"] {
    display: none;
}

.weekday-checkbox span {
    font-weight: 600;
    color: #6b7280;
}

.weekday-checkbox:has(input:checked) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.weekday-checkbox:has(input:checked) span {
    color: white;
}

.shift-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.shift-checkbox-card {
    position: relative;
    cursor: pointer;
    user-select: none;
}

.shift-checkbox-card input[type="checkbox"] {
    display: none;
}

.shift-card-content {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s;
}

.shift-checkbox-card:hover .shift-card-content {
    background: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-2px);
}

.shift-checkbox-card:has(input:checked) .shift-card-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.shift-checkbox-card:has(input:checked) .shift-card-content::after {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 5px;
    background: #10b981;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.shift-icon {
    font-size: 32px;
    margin-bottom: 8px;
}

.shift-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.shift-time {
    font-size: 12px;
    color: #6b7280;
}

.shift-checkbox-card:has(input:checked) .shift-time {
    color: rgba(255, 255, 255, 0.9);
}

.custom-shift-toggle {
    text-align: center;
    margin-bottom: 15px;
}

.btn-link {
    background: none;
    border: none;
    color: #667eea;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.3s;
}

.btn-link:hover {
    background: #ede9fe;
}

.custom-shift-form {
    background: #f9fafb;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
}

.custom-shifts-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.custom-shift-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #ede9fe;
    border: 2px solid #667eea;
    border-radius: 8px;
}

.custom-shift-info {
    flex: 1;
}

.custom-shift-remove {
    background: #ef4444;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.custom-shift-remove:hover {
    background: #dc2626;
}

.assignment-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
}

.summary-content {
    font-size: 14px;
    line-height: 1.6;
}

#summaryText {
    display: block;
    margin-top: 8px;
    font-weight: 400;
}
</style>

<script src="assets/js/calendar-schedule.js"></script>
<script>
// Custom notification function to replace alert() and avoid persistent toast
function showNotification(message, type = 'success', duration = 3000) {
    // Remove any existing notifications first
    document.querySelectorAll('.custom-notification').forEach(el => el.remove());
    
    const notif = document.createElement('div');
    notif.className = 'custom-notification';
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #2d3748;
        color: #ffffff;
        padding: 15px 25px;
        border-radius: 10px;
        border: 1px solid #4a5568;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        max-width: 400px;
        word-wrap: break-word;
        font-size: 15px;
        font-weight: 500;
        animation: slideInRight 0.3s ease forwards;
    `;
    notif.innerHTML = `${message}`;
    document.body.appendChild(notif);
    
    // Auto remove
    setTimeout(() => {
        notif.style.animation = 'slideOutRight 0.3s ease forwards';
        setTimeout(() => notif.remove(), 300);
    }, duration);
}

// Add CSS animations
if (!document.getElementById('notif-animations')) {
    const style = document.createElement('style');
    style.id = 'notif-animations';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Simple employee selection system
const selectedEmployees = new Set();

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('📅 Initializing Calendar Employee Selection...');
    
    const employeeCards = document.querySelectorAll('.employee-card');
    console.log(`Found ${employeeCards.length} employee cards`);
    
    // Add click handlers to each card
    employeeCards.forEach((card, index) => {
        const empId = card.dataset.employeeId;
        const empName = card.querySelector('.employee-name').textContent;
        const checkbox = card.querySelector('.employee-checkbox');
        
        console.log(`Setting up card ${index + 1}: ${empName} (ID: ${empId})`);
        
        // Main card click handler - Using mousedown for better response
        card.addEventListener('mousedown', function(e) {
            // Prevent text selection
            e.preventDefault();
            
            console.log(`🖱️ Card clicked: ${empName} (ID: ${empId})`);
            
            // Toggle selection
            if (selectedEmployees.has(empId)) {
                // Deselect
                selectedEmployees.delete(empId);
                checkbox.checked = false;
                card.classList.remove('selected');
                console.log(`❌ Deselected: ${empName}`);
            } else {
                // Select
                selectedEmployees.add(empId);
                checkbox.checked = true;
                card.classList.add('selected');
                console.log(`✅ Selected: ${empName}`);
            }
            
            updateSelectedDisplay();
        });
        
        // Also handle regular click as fallback
        card.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    
    console.log('✅ Employee selection handlers attached');
    
    // Attach button event listeners
    console.log('🔍 Looking for buttons...');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    console.log('selectAllBtn:', selectAllBtn);
    console.log('clearAllBtn:', clearAllBtn);
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🔘 Select All button clicked via event listener');
            selectAllEmployees();
        });
        console.log('✅ Select All button listener attached');
    } else {
        console.error('❌ Select All button not found!');
    }
    
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🔘 Clear All button clicked via event listener');
            clearSelection();
        });
        console.log('✅ Clear All button listener attached');
    } else {
        console.error('❌ Clear All button not found!');
    }
    
    // Try to initialize CalendarAssignmentManager if available
    if (typeof CalendarAssignmentManager !== 'undefined') {
        try {
            window.calendarManager = new CalendarAssignmentManager();
            console.log('✅ Calendar Manager initialized');
        } catch (error) {
            console.warn('⚠️ Calendar Manager init failed:', error);
        }
    }
});

// Helper function to update selected display in modal
function updateSelectedDisplay() {
    const selectedDiv = document.getElementById('selectedEmployees');
    if (!selectedDiv) return;
    
    if (selectedEmployees.size === 0) {
        selectedDiv.innerHTML = '<p style="color: #666; margin: 10px 0;">Chưa chọn nhân viên nào</p>';
        return;
    }
    
    const employees = Array.from(selectedEmployees).map(id => {
        const card = document.querySelector(`[data-employee-id="${id}"]`);
        const name = card ? card.querySelector('.employee-name').textContent : 'Unknown';
        return { id: id, name: name };
    });
    
    selectedDiv.innerHTML = employees.map(emp => 
        `<span class="selected-employee-tag">${emp.name}</span>`
    ).join('');
    
    // Update hidden field
    const hiddenField = document.getElementById('assignEmployees');
    if (hiddenField) {
        hiddenField.value = employees.map(e => e.id).join(',');
    }
}

// Global helper functions
function selectAllEmployees() {
    console.log('🔵 Select all employees clicked');
    try {
        const cards = document.querySelectorAll('.employee-card');
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        
        console.log(`Found ${cards.length} cards and ${checkboxes.length} checkboxes`);
        
        // Check if all are selected
        const allSelected = Array.from(checkboxes).every(cb => cb.checked);
        console.log(`All selected: ${allSelected}`);
        
        if (allSelected) {
            // Deselect all
            console.log('Deselecting all...');
            selectedEmployees.clear();
            cards.forEach(card => {
                card.classList.remove('selected');
                const checkbox = card.querySelector('.employee-checkbox');
                if (checkbox) checkbox.checked = false;
            });
            console.log('❌ All employees deselected');
        } else {
            // Select all
            console.log('Selecting all...');
            cards.forEach((card, index) => {
                const empId = card.dataset.employeeId;
                console.log(`  Adding employee ${index + 1}: ID=${empId}`);
                selectedEmployees.add(empId);
                card.classList.add('selected');
                const checkbox = card.querySelector('.employee-checkbox');
                if (checkbox) checkbox.checked = true;
            });
            console.log(`✅ All ${cards.length} employees selected, Set size: ${selectedEmployees.size}`);
        }
        
        console.log('Calling updateSelectedDisplay...');
        updateSelectedDisplay();
        console.log('✅ Update complete');
    } catch (error) {
        console.error('❌ Error in selectAllEmployees:', error);
    }
}

function clearSelection() {
    console.log('Clear selection clicked');
    selectedEmployees.clear();
    
    const cards = document.querySelectorAll('.employee-card');
    cards.forEach(card => {
        card.classList.remove('selected');
        card.querySelector('.employee-checkbox').checked = false;
    });
    
    updateSelectedDisplay();
}

function openAssignModal(date) {
    console.log('Opening modal for date:', date);
    const modal = document.getElementById('assignModal');
    if (modal) {
        // Clear any page-level alerts/notifications
        const alerts = document.querySelectorAll('.alert-danger, .alert-success, .alert-warning');
        alerts.forEach(alert => alert.remove());
        
        // Set date values
        document.getElementById('assignDate').value = date;
        document.getElementById('displayDate').value = date;
        
        // Reset date mode to single
        const singleModeRadio = document.querySelector('input[name="dateMode"][value="single"]');
        if (singleModeRadio) singleModeRadio.checked = true;
        
        const singleSection = document.getElementById('singleDateSection');
        const rangeSection = document.getElementById('rangeDateSection');
        if (singleSection) singleSection.style.display = 'block';
        if (rangeSection) rangeSection.style.display = 'none';
        
        // Set start and end dates for range mode
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        if (startDateInput) startDateInput.value = date;
        if (endDateInput) endDateInput.value = date;
        
        // Reset shifts
        document.querySelectorAll('.shift-template-check').forEach(ch => ch.checked = false);
        customShifts = [];
        renderCustomShifts();
        
        // Reset custom shift form
        const customSection = document.getElementById('customShiftSection');
        const toggleBtn = document.getElementById('toggleCustomShift');
        if (customSection) customSection.style.display = 'none';
        if (toggleBtn) toggleBtn.textContent = '➕ Thêm ca tùy chỉnh';
        
        // Show modal with animation
        modal.style.display = 'flex';
        modal.style.opacity = '0';
        
        // Force reflow
        modal.offsetHeight;
        
        // Animate in
        modal.style.transition = 'opacity 0.3s ease';
        modal.style.opacity = '1';
        
        // Update selected employees display
        updateSelectedDisplay();
        
        // Update summary
        if (typeof updateSummary === 'function') {
            updateSummary();
        }
        
        // Focus on body to prevent scroll issues
        document.body.style.overflow = 'hidden';
        
        // Add click outside to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAssignModal();
            }
        });
    }
}

function closeAssignModal() {
    console.log('Closing modal');
    const modal = document.getElementById('assignModal');
    if (modal) {
        // Animate out
        modal.style.opacity = '0';
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
}

// Save assignment function
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveAssignment');
    if (saveBtn) {
        console.log('✅ Save button event listener attached');
        saveBtn.addEventListener('click', function(e) {
            console.log('🔵 Save button clicked at', new Date().toISOString());
            
            // Prevent multiple clicks
            if (this.disabled) {
                console.log('⚠️ Button already disabled, ignoring click');
                return;
            }
            
            if (selectedEmployees.size === 0) {
                showNotification('⚠️ Vui lòng chọn ít nhất một nhân viên!', 'error');
                return;
            }
            
            // Disable button immediately
            this.disabled = true;
            const originalText = this.textContent;
            this.textContent = 'Đang xử lý...';
            console.log('🔒 Button disabled, starting submission');
            
            const formData = {
                ngay: document.getElementById('assignDate').value,
                employees: Array.from(selectedEmployees),
                gio_bat_dau: document.getElementById('startTime').value,
                gio_ket_thuc: document.getElementById('endTime').value,
                ca_lam: document.getElementById('shiftType').value,
                ghi_chu: document.getElementById('shiftNote').value
            };
            
            console.log('Saving assignment:', formData);
            
            // Always use submitAssignment (don't call calendarManager to avoid double submission)
            submitAssignment(formData).finally(() => {
                // Re-enable button after completion
                this.disabled = false;
                this.textContent = originalText;
                console.log('🔓 Button re-enabled');
            });
        });
    } else {
        console.error('❌ Save button not found!');
    }
});

// Fallback save function
let isSubmitting = false; // Prevent double submission

async function submitAssignment(data) {
    if (isSubmitting) {
        console.log('⚠️ Already submitting, ignoring duplicate request');
        return;
    }
    
    isSubmitting = true;
    try {
        // Convert to backend expected format
        const assignments = data.employees.map(empId => ({
            nhanvien_id: empId,
            ngay: data.ngay,
            gio_bat_dau: data.gio_bat_dau,
            gio_ket_thuc: data.gio_ket_thuc,
            ca_lam: data.ca_lam,
            ghi_chu: data.ghi_chu
        }));
        
        console.log('Sending assignments:', assignments);
        
        const response = await fetch('index.php?act=ql_lichlamviec_calendar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'create_assignments',
                assignments: assignments
            })
        });
        
        // Kiểm tra response status
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Kiểm tra content type
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error('Non-JSON response:', text.substring(0, 500));
            throw new Error('Server trả về dữ liệu không hợp lệ (không phải JSON). Vui lòng kiểm tra console.');
        }
        
        const result = await response.json();
        console.log('Server response:', result);
        console.log('Success flag:', result.success, 'Type:', typeof result.success);
        console.log('Partial success flag:', result.partial_success);
        
        if (result.success) {
            // Hoàn toàn thành công
            showNotification(`✅ Phân công thành công! Tạo được ${result.success_count} ca làm việc`, 'success', 2000);
            setTimeout(() => {
                closeAssignModal();
                location.reload();
            }, 2000);
        } else if (result.partial_success) {
            // Một phần thành công
            let msg = `⚠️ Tạo được ${result.success_count} ca làm việc, nhưng có ${result.error_count} ca bị lỗi.\n\n`;
            msg += 'Bạn có muốn tải lại trang để xem kết quả không?\n\n';
            if (result.errors && result.errors.length > 0) {
                msg += 'Chi tiết lỗi:\n' + result.errors.slice(0, 5).join('\n');
                if (result.errors.length > 5) {
                    msg += `\n... và ${result.errors.length - 5} lỗi khác`;
                }
            }
            
            if (confirm(msg)) {
                closeAssignModal();
                location.reload();
            }
        } else {
            // Hoàn toàn thất bại
            let errorMsg = result.message || 'Không thể phân công';
            if (result.errors && result.errors.length > 0) {
                errorMsg += '\n\nChi tiết: ' + result.errors.slice(0, 3).join(', ');
                if (result.errors.length > 3) {
                    errorMsg += `... (+${result.errors.length - 3} lỗi khác)`;
                }
            }
            showNotification('❌ ' + errorMsg, 'error', 5000);
        }
    } catch (error) {
        console.error('Save error:', error);
        showNotification('❌ Lỗi khi lưu: ' + error.message, 'error', 5000);
    } finally {
        isSubmitting = false; // Reset flag after completion
    }
}

// =====================================================
// BULK ASSIGNMENT FEATURES - NEW CODE
// =====================================================

// Custom shifts array
let customShifts = [];

// Date mode toggle
document.addEventListener('DOMContentLoaded', function() {
    const dateModeRadios = document.querySelectorAll('input[name="dateMode"]');
    const singleSection = document.getElementById('singleDateSection');
    const rangeSection = document.getElementById('rangeDateSection');
    
    dateModeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'single') {
                singleSection.style.display = 'block';
                rangeSection.style.display = 'none';
            } else {
                singleSection.style.display = 'none';
                rangeSection.style.display = 'block';
            }
            updateSummary();
        });
    });
    
    // Toggle custom shift form
    const toggleBtn = document.getElementById('toggleCustomShift');
    const customSection = document.getElementById('customShiftSection');
    
    if (toggleBtn && customSection) {
        toggleBtn.addEventListener('click', function() {
            if (customSection.style.display === 'none') {
                customSection.style.display = 'block';
                this.textContent = '➖ Ẩn ca tùy chỉnh';
            } else {
                customSection.style.display = 'none';
                this.textContent = '➕ Thêm ca tùy chỉnh';
            }
        });
    }
    
    // Add custom shift
    const addCustomBtn = document.getElementById('addCustomShift');
    if (addCustomBtn) {
        addCustomBtn.addEventListener('click', function() {
            const name = document.getElementById('customShiftName').value.trim();
            const start = document.getElementById('customStartTime').value;
            const end = document.getElementById('customEndTime').value;
            
            if (!name || !start || !end) {
                showNotification('⚠️ Vui lòng điền đầy đủ thông tin ca làm việc!', 'error');
                return;
            }
            
            if (start >= end) {
                showNotification('⚠️ Giờ kết thúc phải sau giờ bắt đầu!', 'error');
                return;
            }
            
            // Add to custom shifts array
            customShifts.push({
                name: name,
                start: start,
                end: end,
                id: Date.now()
            });
            
            // Clear form
            document.getElementById('customShiftName').value = '';
            document.getElementById('customStartTime').value = '';
            document.getElementById('customEndTime').value = '';
            
            // Update display
            renderCustomShifts();
            updateSummary();
            
            showNotification('✅ Đã thêm ca tùy chỉnh!', 'success', 2000);
        });
    }
    
    // Listen to all shift checkboxes
    const shiftChecks = document.querySelectorAll('.shift-template-check');
    shiftChecks.forEach(check => {
        check.addEventListener('change', updateSummary);
    });
    
    // Listen to date changes
    document.getElementById('displayDate')?.addEventListener('change', updateSummary);
    document.getElementById('startDate')?.addEventListener('change', updateSummary);
    document.getElementById('endDate')?.addEventListener('change', updateSummary);
    
    // Listen to weekday changes
    const weekdayChecks = document.querySelectorAll('.weekday-checkbox input');
    weekdayChecks.forEach(check => {
        check.addEventListener('change', updateSummary);
    });
});

function renderCustomShifts() {
    const container = document.getElementById('customShiftsList');
    if (!container) return;
    
    if (customShifts.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    container.innerHTML = customShifts.map(shift => `
        <div class="custom-shift-item" data-id="${shift.id}">
            <div class="custom-shift-info">
                <strong>${shift.name}</strong>: ${shift.start} - ${shift.end}
            </div>
            <button type="button" class="custom-shift-remove" onclick="removeCustomShift(${shift.id})">
                🗑️ Xóa
            </button>
        </div>
    `).join('');
}

function removeCustomShift(id) {
    customShifts = customShifts.filter(s => s.id !== id);
    renderCustomShifts();
    updateSummary();
    showNotification('✅ Đã xóa ca tùy chỉnh!', 'success', 2000);
}

function updateSummary() {
    const summaryEl = document.getElementById('summaryText');
    if (!summaryEl) return;
    
    const employeeCount = selectedEmployees.size;
    const dateMode = document.querySelector('input[name="dateMode"]:checked')?.value || 'single';
    
    // Count dates
    let dateCount = 0;
    let dateInfo = '';
    
    if (dateMode === 'single') {
        dateCount = 1;
        const date = document.getElementById('displayDate')?.value || '';
        dateInfo = date ? new Date(date).toLocaleDateString('vi-VN') : 'chưa chọn';
    } else {
        const startDate = document.getElementById('startDate')?.value;
        const endDate = document.getElementById('endDate')?.value;
        
        if (startDate && endDate) {
            const selectedWeekdays = Array.from(document.querySelectorAll('.weekday-checkbox input:checked'))
                .map(ch => parseInt(ch.value));
            
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            // Count matching days
            for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                const dow = d.getDay(); // 0=Sun, 1=Mon, etc
                if (selectedWeekdays.includes(dow)) {
                    dateCount++;
                }
            }
            
            dateInfo = `${start.toLocaleDateString('vi-VN')} - ${end.toLocaleDateString('vi-VN')}`;
        } else {
            dateInfo = 'chưa chọn khoảng thời gian';
        }
    }
    
    // Count shifts
    const templateShifts = document.querySelectorAll('.shift-template-check:checked').length;
    const shiftCount = templateShifts + customShifts.length;
    
    // Calculate total assignments
    const totalAssignments = employeeCount * dateCount * shiftCount;
    
    // Build summary
    let summary = [];
    
    if (employeeCount > 0) {
        summary.push(`<strong>${employeeCount}</strong> nhân viên`);
    } else {
        summary.push('<span style="color: #fbbf24;">Chưa chọn nhân viên</span>');
    }
    
    if (dateCount > 0) {
        summary.push(`<strong>${dateCount}</strong> ngày (${dateInfo})`);
    } else {
        summary.push('<span style="color: #fbbf24;">Chưa chọn ngày</span>');
    }
    
    if (shiftCount > 0) {
        summary.push(`<strong>${shiftCount}</strong> ca`);
    } else {
        summary.push('<span style="color: #fbbf24;">Chưa chọn ca</span>');
    }
    
    let finalText = summary.join(' × ');
    
    if (totalAssignments > 0) {
        finalText += ` = <strong style="font-size: 18px; color: #fbbf24;">${totalAssignments}</strong> lượt phân công`;
    }
    
    summaryEl.innerHTML = finalText;
}

// Override the save button click handler
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit to ensure the original handler is attached
    setTimeout(() => {
        const saveBtn = document.getElementById('saveAssignment');
        if (saveBtn) {
            // Remove all previous event listeners by cloning
            const newSaveBtn = saveBtn.cloneNode(true);
            saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);
            
            // Add new handler
            newSaveBtn.addEventListener('click', handleBulkAssignment);
        }
    }, 100);
});

async function handleBulkAssignment() {
    const btn = document.getElementById('saveAssignment');
    
    if (btn.disabled) return;
    
    // Validate
    if (selectedEmployees.size === 0) {
        showNotification('⚠️ Vui lòng chọn ít nhất một nhân viên!', 'error');
        return;
    }
    
    // Get selected shifts
    const selectedShifts = [];
    
    // Template shifts
    document.querySelectorAll('.shift-template-check:checked').forEach(check => {
        selectedShifts.push({
            name: check.dataset.shift,
            start: check.dataset.start,
            end: check.dataset.end
        });
    });
    
    // Custom shifts
    customShifts.forEach(shift => {
        selectedShifts.push({
            name: shift.name,
            start: shift.start,
            end: shift.end
        });
    });
    
    if (selectedShifts.length === 0) {
        showNotification('⚠️ Vui lòng chọn ít nhất một ca làm việc!', 'error');
        return;
    }
    
    // Get dates
    const dates = [];
    const dateMode = document.querySelector('input[name="dateMode"]:checked')?.value || 'single';
    
    if (dateMode === 'single') {
        const date = document.getElementById('displayDate')?.value;
        if (!date) {
            showNotification('⚠️ Vui lòng chọn ngày!', 'error');
            return;
        }
        dates.push(date);
    } else {
        const startDate = document.getElementById('startDate')?.value;
        const endDate = document.getElementById('endDate')?.value;
        
        if (!startDate || !endDate) {
            showNotification('⚠️ Vui lòng chọn khoảng thời gian!', 'error');
            return;
        }
        
        const selectedWeekdays = Array.from(document.querySelectorAll('.weekday-checkbox input:checked'))
            .map(ch => parseInt(ch.value));
        
        if (selectedWeekdays.length === 0) {
            showNotification('⚠️ Vui lòng chọn ít nhất một ngày trong tuần!', 'error');
            return;
        }
        
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dow = d.getDay();
            if (selectedWeekdays.includes(dow)) {
                dates.push(d.toISOString().split('T')[0]);
            }
        }
    }
    
    if (dates.length === 0) {
        showNotification('⚠️ Không có ngày nào phù hợp!', 'error');
        return;
    }
    
    // Build assignments
    const assignments = [];
    const ghi_chu = document.getElementById('shiftNote')?.value || '';
    
    selectedEmployees.forEach(empId => {
        dates.forEach(date => {
            selectedShifts.forEach(shift => {
                assignments.push({
                    nhanvien_id: empId,
                    ngay: date,
                    gio_bat_dau: shift.start,
                    gio_ket_thuc: shift.end,
                    ca_lam: shift.name,
                    ghi_chu: ghi_chu
                });
            });
        });
    });
    
    console.log('Bulk assignments:', assignments);
    
    // Confirm
    const confirmMsg = `Bạn sắp tạo ${assignments.length} lượt phân công:\n` +
                      `- ${selectedEmployees.size} nhân viên\n` +
                      `- ${dates.length} ngày\n` +
                      `- ${selectedShifts.length} ca/ngày\n\n` +
                      `Tiếp tục?`;
    
    if (!confirm(confirmMsg)) return;
    
    // Disable button
    btn.disabled = true;
    const originalText = btn.textContent;
    btn.textContent = 'Đang xử lý...';
    
    try {
        const response = await fetch('index.php?act=ql_lichlamviec_calendar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'create_assignments',
                assignments: assignments
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error('Non-JSON response:', text.substring(0, 500));
            throw new Error('Server trả về dữ liệu không hợp lệ');
        }
        
        const result = await response.json();
        console.log('Server response:', result);
        
        if (result.success) {
            showNotification(`✅ Phân công thành công ${result.success_count} ca làm việc!`, 'success', 2000);
            setTimeout(() => {
                closeAssignModal();
                location.reload();
            }, 2000);
        } else if (result.partial_success) {
            let msg = `⚠️ Tạo được ${result.success_count} ca, có ${result.error_count} ca bị lỗi.\n\nTải lại trang?`;
            if (confirm(msg)) {
                closeAssignModal();
                location.reload();
            }
        } else {
            let errorMsg = result.message || 'Không thể phân công';
            if (result.errors && result.errors.length > 0) {
                errorMsg += '\n\nChi tiết: ' + result.errors.slice(0, 3).join(', ');
            }
            showNotification('❌ ' + errorMsg, 'error', 5000);
        }
    } catch (error) {
        console.error('Save error:', error);
        showNotification('❌ Lỗi: ' + error.message, 'error', 5000);
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
</script>
