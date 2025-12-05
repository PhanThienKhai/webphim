<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Sơ đồ ghế phòng chiếu</h3></div></div></div>

    <form method="get" action="index.php" class="mb-10">
        <input type="hidden" name="act" value="sodoghe" />
        <div class="row align-items-end">
            <div class="col-12 col-md-4 mb-15">
                <label>Chọn phòng</label>
                <select class="form-control" name="id_phong" onchange="this.form.submit()">
                    <option value="">-- Chọn --</option>
                    <?php foreach (($ds_phong ?? []) as $p): ?>
                        <option value="<?= (int)$p['id'] ?>" <?= isset($id_phong) && $id_phong == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if (!empty($id_phong)): ?>
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <link rel="stylesheet" href="../Trang-nguoi-dung/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="../Trang-nguoi-dung/css/style3860.css">
        <style>
            .seat-off{opacity:.3;filter:grayscale(1);}
            .tools{display:flex;gap:10px;align-items:center;margin:10px 0}
            .tools .badge{display:inline-block;padding:4px 8px;border-radius:6px;background:#f3f4f6}
        </style>

        <form method="post" action="index.php?act=sodoghe&id_phong=<?= (int)$id_phong ?>">
            <div class="row">
                <div class="col-12 col-lg-9">
                    <div class="choose-sits">
                        <div class="choose-sits__info choose-sits__info--first">
                            <ul>
                                <li class="sits-price marker--none"><strong>Giá</strong></li>
                                <li class="sits-price sits-price--cheap">Thường</li>
                                <li class="sits-price sits-price--middle">Trung</li>
                                <li class="sits-price sits-price--expensive">VIP</li>
                            </ul>
                        </div>
                        <div class="choose-sits__info">
                            <ul>
                                <li class="sits-state sits-state--your">Đang chọn</li>
                                <li class="sits-state sits-state--not">Đã khóa (không dùng)</li>
                            </ul>
                        </div>
                        <div class="col-sm-12 col-lg-10 col-lg-offset-1">
                            <div class="sits-area hidden-xs">
                                <div class="sits-anchor">Màn hình</div>
                                <div class="sits" id="grid">
                                    <?php
                                    $rows = [];
                                    $byRow = [];
                                    if (!empty($map)){
                                        foreach ($map as $m){ $byRow[$m['row_label']][]=$m; }
                                        ksort($byRow);
                                        foreach ($byRow as $r=>$list){
                                            usort($list, function($a,$b){ return $a['seat_number']<=>$b['seat_number']; });
                                            echo '<div class="sits__row">';
                                            foreach ($list as $s){
                                                $cls = 'sits__place sits-price--'.htmlspecialchars($s['tier']);
                                                if (!(int)$s['active']) $cls .= ' seat-off';
                                                $attrs = 'data-row="'.htmlspecialchars($s['row_label']).'" data-col="'.(int)$s['seat_number'].'" data-tier="'.htmlspecialchars($s['tier']).'" data-active="'.(int)$s['active'].'"';
                                                echo '<span class="'.$cls.'" '.$attrs.'>'.htmlspecialchars($s['code']).'</span>';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card" style="padding:12px;">
                        <div class="tools">
                            <span class="badge">Click: Bật/tắt ghế</span>
                        </div>
                        <div class="tools">
                            <span class="badge">Shift + Click: Đổi hạng ghế</span>
                        </div>
                        <div class="row mb-10">
                            <div class="col-6">
                                <label>Hàng (A..)</label>
                                <input class="form-control" type="number" name="rows" value="12" min="1" />
                            </div>
                            <div class="col-6">
                                <label>Cột</label>
                                <input class="form-control" type="number" name="cols" value="18" min="1" />
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-12"><button class="button" name="taomacdinh" value="1">Tạo sơ đồ mặc định</button></div>
                        </div>
                        <input type="hidden" name="map_json" id="map_json" />
                        <div class="row"><div class="col-12"><button class="button button-primary" name="luu" value="1" onclick="return collectAndSubmit()">Lưu sơ đồ</button></div></div>
                    </div>
                </div>
            </div>
        </form>

        <script>
        // Interactions for editor
        (function(){
            const grid = document.getElementById('grid');
            if(!grid) return;
            grid.querySelectorAll('.sits__place').forEach(el=>{
                el.addEventListener('click', (ev)=>{
                    const active = el.getAttribute('data-active') === '1';
                    if (ev.shiftKey) {
                        // cycle tier
                        const tiers = ['cheap','middle','expensive'];
                        let t = el.getAttribute('data-tier') || 'cheap';
                        let idx = tiers.indexOf(t);
                        idx = (idx+1) % tiers.length;
                        t = tiers[idx];
                        el.setAttribute('data-tier', t);
                        el.classList.remove('sits-price--cheap','sits-price--middle','sits-price--expensive');
                        el.classList.add('sits-price--'+t);
                    } else {
                        // toggle active
                        el.setAttribute('data-active', active ? '0' : '1');
                        el.classList.toggle('seat-off');
                    }
                });
            });
        })();

        function collectAndSubmit(){
            const list = Array.from(document.querySelectorAll('#grid .sits__place')).map(el=>({
                row_label: el.getAttribute('data-row'),
                seat_number: parseInt(el.getAttribute('data-col')||'0'),
                code: el.textContent.trim(),
                tier: el.getAttribute('data-tier') || 'cheap',
                active: el.getAttribute('data-active') === '1' ? 1 : 0
            }));
            document.getElementById('map_json').value = JSON.stringify(list);
            return true;
        }
        </script>
    <?php endif; ?>
</div>

