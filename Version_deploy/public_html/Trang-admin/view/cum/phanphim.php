<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
<style>
/* ========== Multi-apply UI polish ========== */
.panel {
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 8px;
    padding: 14px;
    margin-bottom: 16px;
}
.panel h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
}
.toolbar { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin: 8px 0; }
.toolbar .button { padding:6px 10px; font-size:12px; }
.search-input { width:100%; max-width:340px; }
.selector-list { max-height: 320px; overflow:auto; border:1px solid #eee; border-radius:6px; padding:8px; }
.selector-item { display:flex; align-items:center; justify-content:space-between; padding:6px 8px; border-bottom:1px dashed #f0f0f0; }
.selector-item:last-child { border-bottom:none; }
.selector-item .name { font-weight:500; }
.badge { display:inline-block; padding:2px 8px; border-radius:999px; font-size:12px; }
.badge.hoatdong { background:#e6f7ed; color:#19733f; border:1px solid #c7ecd9; }
.badge.khoa { background:#fdecec; color:#a12828; border:1px solid #f7d2d2; }
.chips { display:flex; gap:6px; flex-wrap:wrap; margin-top:8px; }
.chip { background:#f2f3f5; border:1px solid #e5e7eb; border-radius:999px; padding:4px 10px; font-size:12px; display:flex; align-items:center; gap:6px; }
.chip .x { cursor:pointer; opacity:0.7; }
.muted { color:#6b7280; font-size:12px; }
.split-cols { display:grid; grid-template-columns: 1fr; gap:16px; }
/* Chỉ dùng 2 cột khi >=1200px để tránh bị che khi sidebar mở ở 992-1199px */
@media (min-width: 1200px){ .split-cols { grid-template-columns: 1fr 1fr; } }
.sticky-actions { position:sticky; bottom:0; background:#fff; padding-top:10px; border-top:1px solid #eee; margin-top:10px; display:flex; justify-content:space-between; align-items:center; }
.counter { font-size:12px; color:#374151; }
.table thead th { position: sticky; top: 0; background:#fafafa; }
/* Không chèn bất cứ phần tử DOM nào giữa .side-header và .content-body để giữ layout */
</style>
    
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Phân phối phim cho rạp</h3></div></div>
    </div>

    <form method="get" action="index.php">
        <input type="hidden" name="act" value="phanphim" />
        <div class="row align-items-end">
            <div class="col-12 col-md-6 mb-15">
                <label>Chọn rạp</label>
                <select class="form-control" name="id_rap" onchange="this.form.submit()">
                    <option value="">-- Chọn rạp --</option>
                    <?php foreach (($ds_rap ?? []) as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= isset($id_rap) && $id_rap == $r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['ten_rap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <div class="panel">
        <h4>Phân phối phim cho nhiều rạp</h4>
        <?php if (!empty($error_multi)): ?><div class="alert alert-danger"><?= htmlspecialchars($error_multi) ?></div><?php endif; ?>
        <?php if (!empty($success_multi)): ?><div class="alert alert-success"><?= htmlspecialchars($success_multi) ?></div><?php endif; ?>
        <form id="multiForm" method="post" action="index.php?act=phanphim">
            <input type="hidden" name="apply_multi" value="1" />
            <div class="split-cols">
                <div>
                    <div class="muted">Chọn rạp áp dụng</div>
                    <div class="toolbar">
                        <input id="rapSearch" class="form-control search-input" type="text" placeholder="Tìm rạp theo tên..." />
                        <button type="button" class="button" onclick="rapSelectAll(true)">Chọn tất cả (đang hiện)</button>
                        <button type="button" class="button" onclick="rapSelectAll(false)">Bỏ chọn (đang hiện)</button>
                        <button type="button" class="button" onclick="rapFilter('all')">Hiện tất cả</button>
                        <button type="button" class="button" onclick="rapFilter('active')">Chỉ rạp hoạt động</button>
                        <button type="button" class="button" onclick="rapFilter('locked')">Chỉ rạp khóa</button>
                    </div>
                    <div id="rapList" class="selector-list">
                        <?php foreach (($ds_rap ?? []) as $r): $rid=(int)$r['id']; $st=(int)$r['trang_thai']===1; ?>
                            <div class="selector-item rap-item" data-name="<?= htmlspecialchars(strtolower($r['ten_rap'])) ?>" data-status="<?= $st?'active':'locked' ?>">
                                <label style="display:flex; gap:10px; align-items:center; margin:0;">
                                    <input type="checkbox" name="ids_rap[]" value="<?= $rid ?>" onchange="syncRapChips()" />
                                    <span class="name"><?= htmlspecialchars($r['ten_rap']) ?></span>
                                </label>
                                <span class="badge <?= $st?'hoatdong':'khoa' ?>"><?= $st?'Hoạt động':'Khóa' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="rapSelected" class="chips"></div>
                </div>
                <div>
                    <div class="muted">Chọn phim áp dụng</div>
                    <div class="toolbar">
                        <input id="phimSearch" class="form-control search-input" type="text" placeholder="Tìm phim theo tên..." />
                        <button type="button" class="button" onclick="phimSelectAll(true)">Chọn tất cả (đang hiện)</button>
                        <button type="button" class="button" onclick="phimSelectAll(false)">Bỏ chọn (đang hiện)</button>
                    </div>
                    <div class="table-responsive" style="max-height:320px; overflow:auto;">
                        <table class="table table-bordered" id="phimTable">
                            <thead><tr><th style="width:40px">#</th><th>Phim</th></tr></thead>
                            <tbody>
                                <?php foreach (($ds_phim_all ?? []) as $p): ?>
                                    <tr data-name="<?= htmlspecialchars(strtolower($p['tieu_de'])) ?>">
                                        <td><input type="checkbox" name="ids_phim[]" value="<?= (int)$p['id'] ?>"></td>
                                        <td><?= htmlspecialchars($p['tieu_de']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-10"><label><input type="checkbox" name="replace" value="1"> Thay thế danh sách (xóa cũ, ghi mới)</label></div>
                </div>
            </div>
            <div class="sticky-actions">
                <div class="counter"><span id="rapCount">0</span> rạp được chọn • <span id="phimCount">0</span> phim được chọn</div>
                <div>
                    <button class="button button-primary" type="submit" onclick="return confirmApply()">Áp dụng</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    // === Rạp: filter + select ===
    const rapSearch = document.getElementById('rapSearch');
    const rapList = document.getElementById('rapList');
    const rapSelected = document.getElementById('rapSelected');
    const rapCount = document.getElementById('rapCount');
    const phimCount = document.getElementById('phimCount');

    function normalize(v){ return (v||'').toString().trim().toLowerCase(); }
    function refreshCounts(){
        const rc = rapList.querySelectorAll('input[name="ids_rap[]"]:checked').length;
        const pc = document.querySelectorAll('#phimTable input[name="ids_phim[]"]:checked').length;
        rapCount.textContent = rc; phimCount.textContent = pc;
    }
    function syncRapChips(){
        rapSelected.innerHTML = '';
        rapList.querySelectorAll('.rap-item').forEach(item => {
            const cb = item.querySelector('input[type="checkbox"]');
            if (cb.checked){
                const chip = document.createElement('div');
                chip.className = 'chip';
                const name = item.querySelector('.name').textContent;
                chip.innerHTML = `<span>${name}</span><span class="x" title="Bỏ" data-id="${cb.value}">×</span>`;
                rapSelected.appendChild(chip);
            }
        });
        refreshCounts();
    }
    rapSelected?.addEventListener('click', (e)=>{
        const id = e.target?.getAttribute?.('data-id');
        if (id){
            const cb = rapList.querySelector(`input[name="ids_rap[]"][value="${id}"]`);
            if (cb){ cb.checked = false; syncRapChips(); }
        }
    });
    rapSearch?.addEventListener('input', ()=>{
        const q = normalize(rapSearch.value);
        rapList.querySelectorAll('.rap-item').forEach(item => {
            const name = item.getAttribute('data-name') || '';
            item.style.display = name.includes(q) ? '' : 'none';
        });
    });
    function rapSelectAll(flag){
        rapList.querySelectorAll('.rap-item').forEach(item =>{
            if (item.style.display === 'none') return; // only visible
            const cb = item.querySelector('input[type="checkbox"]');
            cb.checked = !!flag;
        });
        syncRapChips();
    }
    function rapFilter(mode){
        rapSearch.value = '';
        rapList.querySelectorAll('.rap-item').forEach(item =>{
            const st = item.getAttribute('data-status');
            item.style.display = (mode==='all') ? '' : (mode===st ? '' : 'none');
        });
    }

    // === Phim: filter + select ===
    const phimSearch = document.getElementById('phimSearch');
    const phimTable = document.getElementById('phimTable');
    phimSearch?.addEventListener('input', ()=>{
        const q = normalize(phimSearch.value);
        phimTable.querySelectorAll('tbody tr').forEach(tr =>{
            const name = tr.getAttribute('data-name') || '';
            tr.style.display = name.includes(q) ? '' : 'none';
        });
    });
    function phimSelectAll(flag){
        phimTable.querySelectorAll('tbody tr').forEach(tr =>{
            if (tr.style.display === 'none') return;
            const cb = tr.querySelector('input[type="checkbox"]');
            cb.checked = !!flag;
        });
        refreshCounts();
    }
    phimTable?.addEventListener('change', (e)=>{
        if (e.target?.name === 'ids_phim[]') refreshCounts();
    });

    // Update counts initial
    document.addEventListener('change', (e)=>{
        if (e.target?.name === 'ids_rap[]') syncRapChips();
        if (e.target?.name === 'ids_phim[]') refreshCounts();
    });
    document.addEventListener('DOMContentLoaded', ()=>{ syncRapChips(); refreshCounts(); });

    // Confirm
    function confirmApply(){
        const rc = rapList.querySelectorAll('input[name="ids_rap[]"]:checked').length;
        const pc = document.querySelectorAll('#phimTable input[name="ids_phim[]"]:checked').length;
        if (rc===0 || pc===0){
            alert('Vui lòng chọn ít nhất 1 rạp và 1 phim.');
            return false;
        }
        return confirm(`Áp dụng cho ${rc} rạp và ${pc} phim?`);
    }
    </script>

    <?php if (!empty($id_rap)): ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="post" action="index.php?act=phanphim&id_rap=<?= (int)$id_rap ?>">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr><th>#</th><th>Phim</th><th>Đang phân phối</th></tr></thead>
                <tbody>
                    <?php foreach (($ds_phim_phan ?? []) as $p): ?>
                        <tr>
                            <td><input type="checkbox" name="ids_phim[]" value="<?= (int)$p['id'] ?>" <?= ($p['duoc_phan'] ? 'checked' : '') ?>></td>
                            <td><?= htmlspecialchars($p['tieu_de']) ?></td>
                            <td><?= $p['duoc_phan'] ? 'Có' : 'Không' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button class="button button-primary" type="submit" name="luu" value="1">Lưu phân phối</button>
    </form>
    <?php endif; ?>
</div>
