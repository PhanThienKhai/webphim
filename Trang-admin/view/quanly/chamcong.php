<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <style>
        .tool-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin:8px 0}
        .chip{border:1px solid #e5e7eb;border-radius:999px;padding:6px 10px;background:#f9fafb;cursor:pointer}
        .chip:hover{background:#eef2ff}
        .summary{display:flex;gap:12px;flex-wrap:wrap;margin:10px 0}
        .card-sm{border:1px solid #e5e7eb;border-radius:8px;padding:10px 14px;background:#fff}
        .table thead th{position:sticky;top:0;background:#fafafa}
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
        <div class="summary">
            <div class="card-sm"><strong>Tổng giờ trong tháng:</strong> <?= number_format((float)$sum_hours,2) ?> h</div>
        </div>
    <?php elseif (!empty($sum_by_emp)): ?>
        <div class="summary">
            <?php foreach ($sum_by_emp as $s): ?>
                <div class="card-sm"><strong><?= htmlspecialchars($s['ten_nv']) ?>:</strong> <?= number_format((float)$s['so_gio'],2) ?> h</div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?act=chamcong" class="mb-20">
        <div class="row">
            <div class="col-12 col-md-3 mb-10"><label>Nhân viên</label>
                <select class="form-control" name="id_nv" required>
                    <?php foreach (($ds_nv ?? []) as $nvRow): ?>
                        <option value="<?= (int)$nvRow['id'] ?>" <?= ((int)($nv ?? 0) === (int)$nvRow['id']) ? 'selected' : '' ?>><?= htmlspecialchars($nvRow['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 mb-10"><label>Ngày</label><input id="cc_ngay" class="form-control" type="date" name="ngay" required /></div>
            <div class="col-6 col-md-2 mb-10"><label>Giờ vào</label><input id="cc_gio_vao" class="form-control" type="time" name="gio_vao" required /></div>
            <div class="col-6 col-md-2 mb-10"><label>Giờ ra</label><input id="cc_gio_ra" class="form-control" type="time" name="gio_ra" required /></div>
            <div class="col-12 col-md-3 mb-10"><label>Ghi chú</label><input class="form-control" type="text" name="ghi_chu" /></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="them" value="1">Thêm</button></div>
            <div class="col-12 tool-row">
                <span class="chip" onclick="preset('08:00','12:00')">Ca sáng 08:00–12:00</span>
                <span class="chip" onclick="preset('13:00','17:00')">Ca chiều 13:00–17:00</span>
                <span class="chip" onclick="preset('08:00','17:00')">Full 08:00–17:00</span>
                <span class="chip" onclick="setToday()">Hôm nay</span>
                <span class="chip" onclick="copyLastRow()">Nhân bản dòng cuối</span>
                <span class="chip" onclick="exportCSV()">Xuất CSV</span>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Ngày</th><th>Nhân viên</th><th>Giờ vào</th><th>Giờ ra</th><th>Ghi chú</th><th></th></tr></thead>
            <tbody>
                <?php foreach (($ds_cc ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ngay']) ?></td>
                        <td><?= htmlspecialchars($r['ten_nv']) ?></td>
                        <td><?= htmlspecialchars($r['gio_vao']) ?></td>
                        <td><?= htmlspecialchars($r['gio_ra']) ?></td>
                        <td><?= htmlspecialchars($r['ghi_chu'] ?? '') ?></td>
                        <td>
                            <a class="button button-sm" href="#" onclick="prefill('<?= htmlspecialchars($r['ngay']) ?>','<?= htmlspecialchars($r['gio_vao']) ?>','<?= htmlspecialchars($r['gio_ra']) ?>');return false;">Dùng</a>
                            <a class="button button-sm button-danger" href="index.php?act=chamcong&xoa=<?= (int)$r['id'] ?>" onclick="return confirm('Xóa bản ghi này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
