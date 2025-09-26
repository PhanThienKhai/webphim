<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto">
            <div class="page-heading"><h3>Phân công lịch làm việc</h3></div>
            <?php if (!empty($rap['ten_rap'])): ?><div style="color:#6b7280;">Rạp: <strong><?= htmlspecialchars($rap['ten_rap']) ?></strong></div><?php endif; ?>
        </div>
    </div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <!-- Bộ lọc ở đầu trang -->
    <form method="get" action="index.php" class="mb-20">
        <input type="hidden" name="act" value="ql_lichlamviec" />
        <div class="row align-items-end">
            <div class="col-12 col-md-3 mb-15">
                <label>Nhân viên (lọc)</label>
                <select class="form-control" name="nv" onchange="this.form.submit()">
                    <option value="0">— Tất cả —</option>
                    <?php foreach (($ds_nv ?? []) as $nv): ?>
                        <option value="<?= (int)$nv['id'] ?>" <?= ((int)($_GET['nv'] ?? 0)===(int)$nv['id'])?'selected':'' ?>><?= htmlspecialchars($nv['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3 mb-15"><label>Kỳ</label>
                <select class="form-control" name="period" onchange="this.form.submit()">
                    <option value="week" <?= ($period==='week')?'selected':'' ?>>Tuần</option>
                    <option value="month" <?= ($period==='month')?'selected':'' ?>>Tháng</option>
                    <option value="year" <?= ($period==='year')?'selected':'' ?>>Năm</option>
                </select>
            </div>
            <div class="col-12 col-md-3 mb-15"><label>Tìm tên nhân viên</label><input class="form-control" type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Nhập tên..." /></div>
            <div class="col-6 col-md-3 mb-15" id="f_week"><label>Tuần (chọn ngày thuộc tuần)</label><input type="date" class="form-control" name="wdate" value="<?= htmlspecialchars($_GET['wdate'] ?? date('Y-m-d')) ?>"></div>
            <div class="col-6 col-md-3 mb-15" id="f_month"><label>Tháng</label><input type="month" class="form-control" name="ym" value="<?= htmlspecialchars($_GET['ym'] ?? date('Y-m')) ?>"></div>
            <div class="col-6 col-md-3 mb-15" id="f_year"><label>Năm</label><input type="number" class="form-control" name="y" value="<?= htmlspecialchars($_GET['y'] ?? date('Y')) ?>"></div>
            <div class="col-12 col-md-2 mb-15"><button class="button" type="submit">Lọc</button></div>
        </div>
    </form>

    <script>
    (function(){
        var p = '<?= $period ?>';
        function show(){
            var fw=document.getElementById('f_week');
            var fm=document.getElementById('f_month');
            var fy=document.getElementById('f_year');
            if(!fw||!fm||!fy) return;
            fw.style.display = (p==='week')?'' : 'none';
            fm.style.display = (p==='month')?'' : 'none';
            fy.style.display = (p==='year')?'' : 'none';
        }
        show();
    })();
    </script>

    <!-- Tạo ca đơn lẻ -->
    <form method="post" action="index.php?act=ql_lichlamviec" class="mb-30" style="border-top:1px solid #eee;padding-top:10px;">
        <div class="row">
            <div class="col-12 mb-10"><strong>Tạo ca đơn lẻ</strong></div>
            <div class="col-12 col-md-3 mb-15"><label>Nhân viên</label>
                <select class="form-control" name="id_nv_single" required>
                    <?php foreach (($ds_nv ?? []) as $nv): ?>
                        <option value="<?= (int)$nv['id'] ?>" <?= ((int)($_GET['nv'] ?? 0)===(int)$nv['id'])?'selected':'' ?>><?= htmlspecialchars($nv['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2 mb-15"><label>Ngày</label><input class="form-control" type="date" name="ngay_single" required></div>
            <div class="col-6 col-md-2 mb-15"><label>Giờ bắt đầu</label><input class="form-control" type="time" name="gio_bd_single" required></div>
            <div class="col-6 col-md-2 mb-15"><label>Giờ kết thúc</label><input class="form-control" type="time" name="gio_kt_single" required></div>
            <div class="col-6 col-md-2 mb-15"><label>Loại ca</label>
                <select class="form-control" name="loai_ca_single" onchange="applyPresetToSingle(this.value)">
                    <option value="">— Chọn —</option>
                    <option value="Sáng" data-bd="08:00" data-kt="12:00">Sáng (08:00–12:00)</option>
                    <option value="Chiều" data-bd="13:00" data-kt="17:00">Chiều (13:00–17:00)</option>
                    <option value="Tối" data-bd="17:00" data-kt="22:00">Tối (17:00–22:00)</option>
                    <option value="Hành chính" data-bd="09:00" data-kt="18:00">Hành chính (09:00–18:00)</option>
                </select>
            </div>
            <div class="col-12 col-md-6 mb-15"><label>Ghi chú</label><input class="form-control" type="text" name="ghi_single"></div>
            <div class="col-12"><button class="button button-primary" type="submit" name="create_single" value="1">Tạo ca</button></div>
        </div>
    </form>

    <!-- Tạo nhiều ca cùng lúc -->
    <form method="post" action="index.php?act=ql_lichlamviec" class="mb-30" style="border-top:1px solid #eee;padding-top:10px;">
        <div class="row">
            <div class="col-12 mb-10"><strong>Tạo nhiều ca (Bulk)</strong></div>
            <div class="col-12 mb-10"><label>Chọn nhân viên</label>
                <div class="row">
                    <?php foreach (($ds_nv ?? []) as $nv): ?>
                        <div class="col-6 col-md-3"><label><input type="checkbox" name="ids_nv_bulk[]" value="<?= (int)$nv['id'] ?>" <?= ((int)($_GET['nv'] ?? 0)===(int)$nv['id'])?'checked':'' ?>> <?= htmlspecialchars($nv['name']) ?></label></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-15"><label>Từ ngày</label><input class="form-control" type="date" name="bulk_from" required></div>
            <div class="col-6 col-md-3 mb-15"><label>Đến ngày</label><input class="form-control" type="date" name="bulk_to" required></div>
            <div class="col-12 mb-10"><label>Template ca</label>
                <div id="tplList"></div>
                <button class="button button-sm" onclick="addTpl();return false;">+ Thêm template</button>
            </div>
            <div class="col-12"><button class="button button-primary" type="submit" name="bulk_templates" value="1">Tạo lịch theo template</button></div>
        </div>
    </form>

    <style>
        .chip{border:1px solid #e5e7eb;border-radius:999px;padding:4px 8px;background:#f9fafb;cursor:pointer;display:inline-block;margin-right:6px;margin-bottom:6px}
        .chip:hover{background:#eef2ff}
        .modal{position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;z-index:1000}
        .modal .box{background:#fff;border-radius:10px;padding:16px;min-width:320px;max-width:520px;width:90%}
    </style>

    <script>
    function applyPresetToSingle(val){
        var sel = document.querySelector('select[name="loai_ca_single"]');
        var opt = sel.options[sel.selectedIndex];
        var bd = opt.getAttribute('data-bd'); var kt = opt.getAttribute('data-kt');
        if (bd && kt){ document.querySelector('input[name="gio_bd_single"]').value = bd; document.querySelector('input[name="gio_kt_single"]').value = kt; }
    }
    var tplIdx = 0;
    function addTpl(){
        var wrap = document.getElementById('tplList');
        var idx = tplIdx++;
        var block = document.createElement('div');
        block.className = 'card-sm';
        block.style.cssText = 'border:1px solid #e5e7eb;border-radius:8px;padding:10px;margin-bottom:10px;';
        block.innerHTML = '\
            <div class="row g-2" style="margin-bottom:6px;">\
                <div class="col-6 col-md-2"><input type="time" class="form-control" name="tpl['+idx+'][bd]" required></div>\
                <div class="col-6 col-md-2"><input type="time" class="form-control" name="tpl['+idx+'][kt]" required></div>\
                <div class="col-12 col-md-4"><input type="text" class="form-control" name="tpl['+idx+'][name]" placeholder="Tên ca (Sáng/Chiều/Tối)"></div>\
                <div class="col-12 col-md-3"><button class="button button-sm button-danger" onclick="this.closest(\\'.card-sm\\').remove();return false;">Xóa</button></div>\
            </div>\
            <div>Áp dụng: '+[1,2,3,4,5,6,7].map(function(d){ var label={1:"T2",2:"T3",3:"T4",4:"T5",5:"T6",6:"T7",7:"CN"}[d]; return '<label class="chip"><input type="checkbox" name="tpl['+idx+'][days][]" value="'+d+'"> '+label+'</label>'; }).join(' ')+'\
            <span class="chip" onclick="tplSetDays('+idx+', [1,2,3,4,5])">T2–T6</span>\
            <span class="chip" onclick="tplSetDays('+idx+', [6,7])">T7–CN</span>\
            <span class="chip" onclick="tplSetDays('+idx+', [1,2,3,4,5,6,7])">Cả tuần</span>\
            <span class="chip" onclick="tplSetDays('+idx+', [])">Bỏ chọn</span></div>';
        wrap.appendChild(block);
    }
    function tplSetDays(idx, arr){
        document.querySelectorAll('input[name="tpl['+idx+'][days][]"]').forEach(function(cb){ cb.checked = arr.indexOf(parseInt(cb.value)) !== -1; });
    }
    // Modal sửa
    function openEditModal(data){
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_ngay').value = data.ngay;
        document.getElementById('edit_bd').value = data.bd;
        document.getElementById('edit_kt').value = data.kt;
        document.getElementById('edit_ca').value = data.ca || '';
        document.getElementById('edit_ghi').value = data.ghi || '';
        document.getElementById('editModal').style.display='flex';
    }
    function closeEditModal(){ document.getElementById('editModal').style.display='none'; }
    </script>

    <div id="editModal" class="modal">
        <div class="box">
            <h5>Sửa/Đổi ca</h5>
            <form method="post" action="index.php?act=ql_lichlamviec">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="row">
                    <div class="col-12 col-md-4 mb-10"><label>Ngày</label><input type="date" class="form-control" name="edit_ngay" id="edit_ngay" required></div>
                    <div class="col-6 col-md-3 mb-10"><label>Bắt đầu</label><input type="time" class="form-control" name="edit_bd" id="edit_bd" required></div>
                    <div class="col-6 col-md-3 mb-10"><label>Kết thúc</label><input type="time" class="form-control" name="edit_kt" id="edit_kt" required></div>
                    <div class="col-12 col-md-4 mb-10"><label>Ca</label><input type="text" class="form-control" name="edit_ca" id="edit_ca"></div>
                    <div class="col-12 mb-10"><label>Ghi chú</label><input type="text" class="form-control" name="edit_ghi" id="edit_ghi"></div>
                </div>
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button type="button" class="button" onclick="closeEditModal()">Đóng</button>
                    <button type="submit" class="button button-primary" name="edit_submit" value="1">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ngày</th><th>Nhân viên</th><th>Giờ</th><th>Ca</th><th>Ghi chú</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($llv ?? []) as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['ngay']) ?></td>
                        <td><?= htmlspecialchars($v['ten_nv']) ?></td>
                        <td><?= htmlspecialchars($v['gio_bat_dau']) ?> → <?= htmlspecialchars($v['gio_ket_thuc']) ?></td>
                        <td><?= htmlspecialchars($v['ca_lam']) ?></td>
                        <td><?= htmlspecialchars($v['ghi_chu']) ?></td>
                        <td>
                            <a class="button button-sm" href="#" onclick="openEditModal({id: <?= (int)$v['id'] ?>, ngay: '<?= htmlspecialchars($v['ngay'], ENT_QUOTES) ?>', bd: '<?= htmlspecialchars($v['gio_bat_dau'], ENT_QUOTES) ?>', kt: '<?= htmlspecialchars($v['gio_ket_thuc'], ENT_QUOTES) ?>', ca: '<?= htmlspecialchars($v['ca_lam'] ?? '', ENT_QUOTES) ?>', ghi: '<?= htmlspecialchars($v['ghi_chu'] ?? '', ENT_QUOTES) ?>'}); return false;">Sửa</a>
                            <a class="button button-sm button-danger" href="index.php?act=ql_lichlamviec&xoa=<?= (int)$v['id'] ?>" onclick="return confirm('Xóa lịch này?')">Hủy</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
