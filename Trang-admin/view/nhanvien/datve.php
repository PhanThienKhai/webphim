<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Đặt vé cho khách hàng</h3></div></div></div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="post" action="index.php?act=nv_datve" id="nv-booking-form">
        <div class="row">
            <div class="col-12 col-md-4 mb-15">
                <label>Phim</label>
                <select class="form-control" name="id_phim" id="id_phim" required>
                    <option value="">-- Chọn phim --</option>
                    <?php foreach (($ds_phim ?? []) as $p): ?>
                        <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['tieu_de']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 mb-15">
                <label>Ngày chiếu (lịch)</label>
                <select class="form-control" name="id_lc" id="id_lc" required>
                    <option value="">-- Chọn lịch theo phim --</option>
                </select>
            </div>
            <div class="col-12 col-md-4 mb-15">
                <label>Khung giờ</label>
                <select class="form-control" name="id_tg" id="id_tg" required>
                    <option value="">-- Chọn khung giờ --</option>
                </select>
            </div>
            <div class="col-12 col-md-6 mb-15"><label>Email khách hàng</label><input class="form-control" type="email" name="email_kh" id="email_kh" required /></div>
            <input type="hidden" name="chon_lich" id="chon_lich" value="1" />
            <div class="col-12"><button class="button button-primary" type="submit">Tiếp tục chọn ghế</button></div>
        </div>
    </form>
    <script>
    (function(){
        const phim = document.getElementById('id_phim');
        const lc = document.getElementById('id_lc');
        const tg = document.getElementById('id_tg');
        const email = document.getElementById('email_kh');
        const form = document.getElementById('nv-booking-form');

        function clearSelect(sel, placeholder){ sel.innerHTML=''; const op=document.createElement('option'); op.value=''; op.textContent=placeholder; sel.appendChild(op); }

        phim.addEventListener('change', async ()=>{
            clearSelect(lc, '-- Chọn lịch theo phim --'); clearSelect(tg, '-- Chọn khung giờ --');
            if(!phim.value) return;
            const res = await fetch('index.php?act=api_dates&id_phim='+encodeURIComponent(phim.value));
            const data = await res.json();
            data.forEach(row=>{ const op=document.createElement('option'); op.value=row.id; op.textContent=row.ngay_chieu; lc.appendChild(op); });
        });

        lc.addEventListener('change', async ()=>{
            clearSelect(tg, '-- Chọn khung giờ --');
            if(!lc.value) return;
            const res = await fetch('index.php?act=api_times&id_lc='+encodeURIComponent(lc.value));
            const data = await res.json();
            data.forEach(row=>{ const op=document.createElement('option'); op.value=row.id; op.textContent=row.thoi_gian_chieu; tg.appendChild(op); });
        });

        tg.addEventListener('change', async ()=>{
            if(phim.value && lc.value && tg.value && email.value){
                form.submit();
            }
        });
    })();
    </script>
</div>
