<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Xếp lịch làm (nhiều ngày)</h3></div></div></div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=nv_xeplich" class="mb-20">
        <div class="row mb-10">
            <div class="col-12 col-md-4"><label>Chế độ</label>
                <div>
                    <label style="margin-right:15px"><input type="radio" name="mode" value="day" checked onchange="switchMode('day')"> Theo ngày</label>
                    <label><input type="radio" name="mode" value="week" onchange="switchMode('week')"> Theo tuần</label>
                </div>
            </div>
        </div>

        <div id="mode_day">
            <div class="tool-row"><button class="button button-sm" onclick="addDaySection();return false;">+ Thêm ngày</button></div>
            <div id="daySections"></div>
        </div>

        <div id="mode_week" style="display:none">
            <div class="row">
                <div class="col-6 col-md-3 mb-10"><label>Từ ngày</label><input type="date" class="form-control" name="week_from" /></div>
                <div class="col-6 col-md-3 mb-10"><label>Đến ngày</label><input type="date" class="form-control" name="week_to" /></div>
            </div>
            <?php $thu = [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'Chủ nhật']; ?>
            <div class="row">
                <?php foreach($thu as $k=>$v): ?>
                <div class="col-12 col-md-6 col-lg-4 mb-15">
                    <div class="card-sm" style="border:1px solid #e5e7eb;border-radius:8px;padding:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <strong><?= $v ?></strong>
                            <button class="button button-xs" onclick="addWeekShift(<?= $k ?>);return false;">+ Thêm ca</button>
                        </div>
                        <div id="week<?= $k ?>_list" data-count="0"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-10"><button class="button button-primary" type="submit" name="nv_builder_submit" value="1">Tạo lịch</button></div>
    </form>

    <script>
    function switchMode(m){ document.getElementById('mode_day').style.display=(m==='day')?'':'none'; document.getElementById('mode_week').style.display=(m==='week')?'':'none'; }
    var dayIdx = 0;
    function addDaySection(){
        var wrap = document.getElementById('daySections');
        var idx = dayIdx++;
        var card = document.createElement('div');
        card.className = 'card-sm';
        card.style.cssText = 'border:1px solid #e5e7eb;border-radius:8px;padding:10px;margin-bottom:10px;';
        card.innerHTML = '\
            <div style="display:flex;gap:10px;align-items:center;justify-content:space-between;margin-bottom:8px;">\
                <div style="flex:1 1 auto"><label>Ngày</label> <input type="date" class="form-control" name="days['+idx+'][date]" required></div>\
                <div><button class="button button-xs button-danger" onclick="this.closest(\\'.card-sm\\').remove();return false;">Xóa ngày</button></div>\
            </div>\
            <div id="shifts'+idx+'" data-count="0"></div>\
            <button class="button button-sm" onclick="addShiftRow('+idx+');return false;">+ Thêm ca</button>';
        wrap.appendChild(card);
        addShiftRow(idx);
    }
    function addShiftRow(idx, bd, kt, ca, ghi){
        var list = document.getElementById('shifts'+idx);
        var c = parseInt(list.getAttribute('data-count')||'0');
        var row = document.createElement('div');
        row.className = 'row g-2 shift-row';
        row.style.marginBottom = '8px';
        row.innerHTML = '\
            <div class="col-6 col-md-3"><input type="time" class="form-control" name="days['+idx+'][shifts]['+c+'][bd]" required></div>\
            <div class="col-6 col-md-3"><input type="time" class="form-control" name="days['+idx+'][shifts]['+c+'][kt]" required></div>\
            <div class="col-6 col-md-3"><input type="text" class="form-control" name="days['+idx+'][shifts]['+c+'][ca]" placeholder="Ca"></div>\
            <div class="col-6 col-md-3"><input type="text" class="form-control" name="days['+idx+'][shifts]['+c+'][ghi]" placeholder="Ghi chú"></div>\
            <div class="col-12"><button class="button button-xs button-danger" onclick="this.closest(\\'.shift-row\\').remove();return false;">X</button></div>';
        list.appendChild(row);
        list.setAttribute('data-count', String(c+1));
        var inputs = row.querySelectorAll('input');
        if (bd) inputs[0].value = bd; if (kt) inputs[1].value = kt; if (ca) inputs[2].value = ca; if (ghi) inputs[3].value = ghi;
    }
    function addWeekShift(d, bd, kt, ca, ghi){
        var list = document.getElementById('week'+d+'_list');
        var c = parseInt(list.getAttribute('data-count')||'0');
        var row = document.createElement('div');
        row.className = 'row g-2 shift-row';
        row.style.marginBottom = '8px';
        row.innerHTML = '\
            <div class="col-6 col-md-4"><input type="time" class="form-control" name="week['+d+']['+c+'][bd]" required></div>\
            <div class="col-6 col-md-4"><input type="time" class="form-control" name="week['+d+']['+c+'][kt]" required></div>\
            <div class="col-6 col-md-3"><input type="text" class="form-control" name="week['+d+']['+c+'][ca]" placeholder="Ca"></div>\
            <div class="col-6 col-md-1"><button class="button button-sm button-danger" onclick="this.closest(\\'.shift-row\\').remove();return false;">X</button></div>';
        list.appendChild(row);
        list.setAttribute('data-count', String(c+1));
        var inputs = row.querySelectorAll('input');
        if (bd) inputs[0].value = bd; if (kt) inputs[1].value = kt; if (ca) inputs[2].value = ca; if (ghi && inputs[3]) inputs[3].value = ghi;
    }
    // Một ngày mẫu để bắt đầu
    addDaySection();
    </script>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Ngày</th><th>Giờ</th><th>Ca</th><th>Ghi chú</th></tr></thead>
            <tbody>
                <?php foreach (($llv ?? []) as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['ngay']) ?></td>
                        <td><?= htmlspecialchars($v['gio_bat_dau']) ?> → <?= htmlspecialchars($v['gio_ket_thuc']) ?></td>
                        <td><?= htmlspecialchars($v['ca_lam']) ?></td>
                        <td><?= htmlspecialchars($v['ghi_chu']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

