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
    <div class="page-heading"><h3>⏰ Chấm công</h3></div>
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
                <div class="stat-card" style="border-left:4px solid #8b5cf6;background:#faf5ff">
                    <div class="stat-label">Tỷ lệ attendance</div>
                    <div class="stat-number" style="color:#8b5cf6"><?= number_format($attendance_summary['attendance_rate'],1) ?>%</div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($attendance_detail)): ?>
            <div style="margin:20px 0">
                <h5 style="margin-bottom:12px">📊 Chi tiết so sánh lịch phân công vs thực tế:</h5>
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
                                            <span style="color:#f59e0b;font-weight:600">⚠️ Chú ý</span>
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
                    <span style="font-size:24px">⚡</span>
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
                        🕐 Check-in ngay
                    </button>
                </div>
            </div>
        </div>
    </form>

    <hr style="margin:24px 0;border:none;border-top:2px dashed #e5e7eb" />

    <form method="post" action="index.php?act=chamcong" class="mb-20">
        <h5 style="margin-bottom:12px">📝 Thêm chấm công thủ công:</h5>
        <div class="row">
            <div class="col-12 col-md-3 mb-10"><label>Nhân viên</label>
                <select class="form-control" name="id_nv" id="nv_select" required>
                    <?php foreach (($ds_nv ?? []) as $nvRow): ?>
                        <option value="<?= (int)$nvRow['id'] ?>" <?= ((int)($nv ?? 0) === (int)$nvRow['id']) ? 'selected' : '' ?>><?= htmlspecialchars($nvRow['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 mb-10"><label>Ngày</label><input id="cc_ngay" class="form-control" type="date" name="ngay" required /></div>
            <div class="col-6 col-md-2 mb-10"><label>Giờ vào</label><input id="cc_gio_vao" class="form-control" type="time" name="gio_vao" required /></div>
            <div class="col-6 col-md-2 mb-10"><label>Giờ ra</label><input id="cc_gio_ra" class="form-control" type="time" name="gio_ra" required /></div>
            <div class="col-12 col-md-3 mb-10"><label>Ghi chú</label><input class="form-control" type="text" name="ghi_chu" placeholder="Tùy chọn" /></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="them" value="1">➕ Thêm chấm công</button></div>
            <div class="col-12 tool-row">
                <span class="chip" onclick="preset('08:00','12:00')">🌅 Ca sáng 08:00–12:00</span>
                <span class="chip" onclick="preset('13:00','17:00')">🌤️ Ca chiều 13:00–17:00</span>
                <span class="chip" onclick="preset('08:00','17:00')">☀️ Full 08:00–17:00</span>
                <span class="chip" onclick="preset('18:00','22:00')">🌙 Ca tối 18:00–22:00</span>
                <span class="chip chip-primary" onclick="setToday()">📅 Hôm nay</span>
                <span class="chip" onclick="copyLastRow()">📋 Nhân bản dòng cuối</span>
                <span class="chip" onclick="exportCSV()">💾 Xuất CSV</span>
            </div>
        </div>
    </form>

    <h5 style="margin:20px 0 12px">📋 Danh sách chấm công trong tháng:</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Ngày</th><th>Nhân viên</th><th>Giờ vào</th><th>Giờ ra</th><th>Số giờ</th><th>Ghi chú</th><th>Thao tác</th></tr></thead>
            <tbody>
                <?php foreach (($ds_cc ?? []) as $r): 
                    $gio_vao = strtotime($r['gio_vao']);
                    $gio_ra = strtotime($r['gio_ra']);
                    $hours = ($gio_ra - $gio_vao) / 3600;
                    $rowClass = '';
                    $warning = '';
                    
                    if ($hours > 12) {
                        $rowClass = 'highlight-danger';
                        $warning = '⚠️ Ca quá dài';
                    } elseif ($hours < 1) {
                        $rowClass = 'highlight-warning';
                        $warning = '⚠️ Ca quá ngắn';
                    }
                ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= htmlspecialchars($r['ngay']) ?></td>
                        <td><?= htmlspecialchars($r['ten_nv']) ?></td>
                        <td><?= htmlspecialchars($r['gio_vao']) ?></td>
                        <td><?= htmlspecialchars($r['gio_ra']) ?></td>
                        <td><strong><?= number_format($hours, 1) ?> h</strong> <?= $warning ?></td>
                        <td><?= htmlspecialchars($r['ghi_chu'] ?? '') ?></td>
                        <td style="white-space:nowrap">
                            <a class="button button-sm" href="#" onclick="prefill('<?= htmlspecialchars($r['ngay']) ?>','<?= htmlspecialchars($r['gio_vao']) ?>','<?= htmlspecialchars($r['gio_ra']) ?>');return false;">Dùng</a>
                            <a class="button button-sm button-danger" href="index.php?act=chamcong&xoa=<?= (int)$r['id'] ?>&ym=<?= urlencode($ym) ?>&nv=<?= (int)$nv ?>" onclick="return confirm('Xóa bản ghi này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($ds_cc)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#6b7280;padding:20px">Không có dữ liệu chấm công trong tháng này</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
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
