<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        .tool-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin:8px 0}
        .chip{border:1px solid #e5e7eb;border-radius:999px;padding:6px 10px;background:#f9fafb;cursor:pointer;transition:all 0.2s}
        .chip:hover{background:#eef2ff;border-color:#6366f1}
        .chip-primary{background:#6366f1;color:#fff;border-color:#6366f1}
        .chip-primary:hover{background:#4f46e5}
        .summary{display:flex;gap:12px;flex-wrap:wrap;margin:10px 0}
        .card-sm{border:1px solid #e5e7eb;border-radius:8px;padding:10px 14px;background:#fff}
        .card-warning{border-left:4px solid #f59e0b;background:#fffbeb}
        .card-success{border-left:4px solid #10b981;background:#f0fdf4}
        .card-danger{border-left:4px solid #ef4444;background:#fef2f2}
        .card-info{border-left:4px solid #3b82f6;background:#eff6ff}
        .table thead th{position:sticky;top:0;background:#fafafa;z-index:10}
        .table tbody tr.highlight-warning{background:#fef3c7}
        .table tbody tr.highlight-danger{background:#fee2e2}
        .attendance-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin:16px 0}
        .stat-card{border:1px solid #e5e7eb;border-radius:8px;padding:14px;background:#fff;text-align:center}
        .stat-number{font-size:28px;font-weight:700;margin:8px 0}
        .stat-label{font-size:13px;color:#6b7280}
    </style>
    <div class="page-heading"><h3>Chấm công</h3></div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="get" action="index.php" class="mb-10">
        <input type="hidden" name="act" value="chamcong" />
        <div class="row align-items-end">
            <div class="col-12 col-md-3 mb-10"><label>Tháng</label><input class="form-control" type="month" name="ym" value="<?= htmlspecialchars($ym) ?>" /></div>
            <div class="col-12 col-md-4 mb-10"><label>Nhân viên (lọc)</label>
                <select class="form-control" name="nv" onchange="this.form.submit()">
                    <option value="0">— Tất cả —</option>
                    <?php foreach (($ds_nv ?? []) as $nvRow): ?>
                        <option value="<?= (int)$nvRow['id'] ?>" <?= ((int)($nv ?? 0) === (int)$nvRow['id']) ? 'selected' : '' ?>><?= htmlspecialchars($nvRow['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 mb-10"><button class="button" type="submit">Lọc</button></div>
        </div>
    </form>

    <?php if (!empty($nv) && isset($sum_hours)): ?>
        <div class="attendance-grid">
            <div class="stat-card card-info">
                <div class="stat-label">Tổng giờ làm việc</div>
                <div class="stat-number" style="color:#3b82f6"><?= number_format((float)$sum_hours,1) ?> h</div>
            </div>
            <?php if (isset($attendance_summary)): ?>
                <div class="stat-card card-success">
                    <div class="stat-label">Đúng giờ</div>
                    <div class="stat-number" style="color:#10b981"><?= (int)$attendance_summary['ontime_count'] ?></div>
                </div>
                <div class="stat-card card-warning">
                    <div class="stat-label">Đi muộn</div>
                    <div class="stat-number" style="color:#f59e0b"><?= (int)$attendance_summary['late_count'] ?></div>
                </div>
                <div class="stat-card card-warning">
                    <div class="stat-label">Về sớm</div>
                    <div class="stat-number" style="color:#f59e0b"><?= (int)$attendance_summary['early_count'] ?></div>
                </div>
                <div class="stat-card card-danger">
                    <div class="stat-label">Vắng mặt</div>
                    <div class="stat-number" style="color:#ef4444"><?= (int)$attendance_summary['absent_count'] ?></div>
                </div>
                <div class="stat-card" style="border-left:4px solid #5a5566ff;background:#faf5ff">
                    <div class="stat-label">Tỷ lệ attendance</div>
                    <div class="stat-number" style="color:#8b5cf6"><?= number_format($attendance_summary['attendance_rate'],1) ?>%</div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($attendance_detail)): ?>
            <div style="margin:20px 0">
                <h5 style="margin-bottom:12px">Chi tiết so sánh lịch phân công vs thực tế:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size:13px">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Lịch vào</th>
                                <th>Thực tế vào</th>
                                <th>Lịch ra</th>
                                <th>Thực tế ra</th>
                                <th>Đi muộn</th>
                                <th>Về sớm</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_detail as $ad): ?>
                                <tr class="<?= $ad['status'] === 'absent' ? 'highlight-danger' : ($ad['status'] === 'warning' ? 'highlight-warning' : '') ?>">
                                    <td><?= htmlspecialchars($ad['ngay']) ?></td>
                                    <td><?= htmlspecialchars($ad['scheduled_in'] ?? '-') ?></td>
                                    <td><?= $ad['actual_in'] ? htmlspecialchars($ad['actual_in']) : '<span style="color:#ef4444">Chưa chấm</span>' ?></td>
                                    <td><?= htmlspecialchars($ad['scheduled_out'] ?? '-') ?></td>
                                    <td><?= $ad['actual_out'] ? htmlspecialchars($ad['actual_out']) : '<span style="color:#ef4444">Chưa chấm</span>' ?></td>
                                    <td><?= $ad['late_minutes'] > 0 ? '<span style="color:#f59e0b">+' . (int)$ad['late_minutes'] . ' phút</span>' : '-' ?></td>
                                    <td><?= $ad['early_minutes'] > 0 ? '<span style="color:#f59e0b">-' . (int)$ad['early_minutes'] . ' phút</span>' : '-' ?></td>
                                    <td>
                                        <?php if ($ad['status'] === 'absent'): ?>
                                            <span style="color:#ef4444;font-weight:600">❌ Vắng</span>
                                        <?php elseif ($ad['status'] === 'warning'): ?>
                                            <span style="color:#f59e0b;font-weight:600">Chú ý</span>
                                        <?php else: ?>
                                            <span style="color:#10b981;font-weight:600">✓ Đúng</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    <?php elseif (!empty($sum_by_emp)): ?>
        <div class="summary">
            <?php foreach ($sum_by_emp as $s): ?>
                <div class="card-sm"><strong><?= htmlspecialchars($s['ten_nv']) ?>:</strong> <?= number_format((float)$s['so_gio'],2) ?> h</div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?act=chamcong" class="mb-20">
        <div class="row">
            <div class="col-12 mb-10">
                <div style="display:flex;gap:10px;align-items:center;padding:12px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px">
                    <span style="font-size:24px"></span>
                    <div style="flex:1">
                        <strong>Check-in nhanh</strong>
                        <p style="margin:4px 0 0;font-size:13px;color:#6b7280">Chấm công ngay lúc này (tự động điền giờ vào + 8h làm việc)</p>
                    </div>
                    <select name="id_nv" class="form-control" style="width:200px" required>
                        <option value="">-- Chọn NV --</option>
                        <?php foreach (($ds_nv ?? []) as $nvRow): ?>
                            <option value="<?= (int)$nvRow['id'] ?>"><?= htmlspecialchars($nvRow['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="button button-primary" type="submit" name="checkin_now" value="1">
                        Check-in ngay
                    </button>
                </div>
            </div>
        </div>
    </form>

    <hr style="margin:24px 0;border:none;border-top:2px dashed #e5e7eb" />

    <!-- Attendance Form Section -->
    <div style="background: linear-gradient(135deg, #aeb8e8ff 0%, #bba3d2ff 100%); padding: 30px; border-radius: 12px; margin-bottom: 30px; color: white;">
        <h5 style="margin: 0 0 20px; font-size: 18px; font-weight: 700;">Thêm chấm công thủ công</h5>
        
        <form method="post" action="index.php?act=chamcong" style="margin-bottom: 0;">
            <div class="row">
                <div class="col-12 col-md-4 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">Nhân viên *</label>
                    <select class="form-control" name="id_nv" id="nv_select" required style="background: white; color: #333;">
                        <option value="">-- Chọn nhân viên --</option>
                        <?php foreach (($ds_nv ?? []) as $nvRow): ?>
                            <option value="<?= (int)$nvRow['id'] ?>" <?= ((int)($nv ?? 0) === (int)$nvRow['id']) ? 'selected' : '' ?>><?= htmlspecialchars($nvRow['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-3 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">Ngày *</label>
                    <input id="cc_ngay" class="form-control" type="date" name="ngay" required style="background: white; color: #333;" />
                </div>
                <div class="col-6 col-md-2.5 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">Giờ vào *</label>
                    <input id="cc_gio_vao" class="form-control" type="time" name="gio_vao" required style="background: white; color: #333;" />
                </div>
                <div class="col-6 col-md-2.5 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">Giờ ra *</label>
                    <input id="cc_gio_ra" class="form-control" type="time" name="gio_ra" required style="background: white; color: #333;" />
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">Ghi chú (Tùy chọn)</label>
                    <input class="form-control" type="text" name="ghi_chu" placeholder="VD: Không tính OT, hỗ trợ sự kiện..." style="background: white; color: #333;" />
                </div>
                <div class="col-12 col-md-6 mb-15">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; opacity: 0.95;">&nbsp;</label>
                    <button class="button" type="submit" name="them" value="1" style="background: white; color: #667eea; font-weight: 700; width: 100%; border: none; padding: 12px;">
                        Thêm chấm công
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div style="margin-bottom: 25px;">
        <div style="margin-bottom: 12px; font-weight: 600; font-size: 14px; color: #333;">⚡ Tùy chỉnh nhanh:</div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <span class="chip" onclick="preset('08:00','12:00')" style="cursor: pointer;">🌅 08:00–12:00</span>
            <span class="chip" onclick="preset('13:00','17:00')" style="cursor: pointer;">🌤️ 13:00–17:00</span>
            <span class="chip" onclick="preset('08:00','17:00')" style="cursor: pointer;">☀️ 08:00–17:00</span>
            <span class="chip" onclick="preset('18:00','22:00')" style="cursor: pointer;">🌙 18:00–22:00</span>
            <span class="chip chip-primary" onclick="setToday()" style="cursor: pointer;">📅 Hôm nay</span>
            <span class="chip" onclick="copyLastRow()" style="cursor: pointer;">Nhân bản cuối</span>
            <span class="chip" onclick="exportCSV()" style="cursor: pointer;">Xuất CSV</span>
        </div>
    </div>

    <h5 style="margin:20px 0 12px">Danh sách chấm công trong tháng:</h5>
    <div class="table-responsive">
        <table class="table table-bordered" style="font-size: 13px;">
            <thead style="background: #f3f4f6; font-weight: 700;">
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 16%;">Nhân viên</th>
                    <th style="width: 12%;">Ngày</th>
                    <th style="width: 10%; text-align: center;">Vào</th>
                    <th style="width: 10%; text-align: center;">Ra</th>
                    <th style="width: 8%; text-align: center;">Số giờ</th>
                    <th style="width: 16%; text-align: center;">📍 GPS</th>
                    <th style="width: 20%; text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $index = 1;
                foreach (($ds_cc ?? []) as $r): 
                    $gio_vao = strtotime($r['gio_vao']);
                    $gio_ra = strtotime($r['gio_ra']);
                    $hours = ($gio_ra - $gio_vao) / 3600;
                    $rowClass = '';
                    $badge = '';
                    
                    if ($hours > 12) {
                        $rowClass = 'highlight-danger';
                        $badge = '<span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Quá dài</span>';
                    } elseif ($hours < 1) {
                        $rowClass = 'highlight-warning';
                        $badge = '<span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Quá ngắn</span>';
                    } else {
                        // $badge = '<span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;"></span>';
                    }
                ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= $index++ ?></td>
                        <td><strong><?= htmlspecialchars($r['ten_nv']) ?></strong></td>
                        <td><?= htmlspecialchars($r['ngay']) ?></td>
                        <td style="text-align: center; font-family: monospace;"><?= htmlspecialchars($r['gio_vao']) ?></td>
                        <td style="text-align: center; font-family: monospace;"><?= htmlspecialchars($r['gio_ra']) ?></td>
                        <td style="text-align: center;">
                            <strong><?= number_format($hours, 1) ?></strong>h
                            <br><?= $badge ?>
                        </td>
                        <td style="text-align: center; font-size: 12px;">
                            <?php if (!empty($r['latitude']) && !empty($r['longitude'])): ?>
                                <span style="color: #0066cc; cursor: pointer; text-decoration: underline; display: block; margin-bottom: 4px;" 
                                      title="Độ chính xác: ±<?= round($r['location_accuracy'] ?? 0) ?>m"
                                      onclick="openMapLocation(<?= $r['latitude'] ?>, <?= $r['longitude'] ?>)">
                                    📍 <?= number_format($r['latitude'], 4) ?>
                                </span>
                                <span style="color: #0066cc; cursor: pointer; text-decoration: underline;"
                                      title="Độ chính xác: ±<?= round($r['location_accuracy'] ?? 0) ?>m"
                                      onclick="openMapLocation(<?= $r['latitude'] ?>, <?= $r['longitude'] ?>)">
                                    <?= number_format($r['longitude'], 4) ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #ddd;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <button class="button button-sm" type="button" onclick="prefill('<?= htmlspecialchars($r['ngay']) ?>','<?= htmlspecialchars($r['gio_vao']) ?>','<?= htmlspecialchars($r['gio_ra']) ?>'); document.querySelector('html').scrollTop = 0;" style="font-size: 11px; padding: 6px 10px;">✏️ Dùng</button>
                            <a class="button button-sm button-danger" href="index.php?act=chamcong&xoa=<?= (int)$r['id'] ?>&ym=<?= urlencode($ym) ?>&nv=<?= (int)$nv ?>" onclick="return confirm('Xóa bản ghi này?')" style="font-size: 11px; padding: 6px 10px;">🗑️ Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($ds_cc)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #9ca3af; padding: 30px;">
                            <div style="font-size: 28px; margin-bottom: 8px;">📭</div>
                            Không có dữ liệu chấm công trong tháng này
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function openMapLocation(lat, lng) {
    const url = `https://maps.google.com/?q=${lat},${lng}&z=17`;
    window.open(url, '_blank');
}

function preset(v,a){ document.getElementById('cc_gio_vao').value=v; document.getElementById('cc_gio_ra').value=a; }
function setToday(){ const d=new Date(); const v=d.toISOString().slice(0,10); document.getElementById('cc_ngay').value=v; }
function copyLastRow(){
  const rows=[...document.querySelectorAll('table tbody tr')]; if(rows.length===0) return; const last=rows[0];
  const tds=last.querySelectorAll('td'); if(tds.length<5) return;
  document.getElementById('cc_ngay').value = tds[0].innerText.trim();
  document.getElementById('cc_gio_vao').value = tds[2].innerText.trim();
  document.getElementById('cc_gio_ra').value = tds[3].innerText.trim();
}
function prefill(ngay, vao, ra){ document.getElementById('cc_ngay').value=ngay; document.getElementById('cc_gio_vao').value=vao; document.getElementById('cc_gio_ra').value=ra; }
function exportCSV(){
  const rows=[...document.querySelectorAll('table tr')].map(tr=>[...tr.querySelectorAll('th,td')].map(td=>('"'+td.innerText.replace(/"/g,'""')+'"')));
  const csv=rows.map(r=>r.join(',')).join('\n');
  const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'}); const url=URL.createObjectURL(blob);
  const a=document.createElement('a'); a.href=url; a.download='chamcong.csv'; a.click(); URL.revokeObjectURL(url);
}
</script>
