<?php include __DIR__ . '/../home/sideheader.php'; ?>
<!-- Bring over customer-facing seat styles to match UI -->
<link rel="stylesheet" href="../Trang-nguoi-dung/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="../Trang-nguoi-dung/css/style3860.css">

<div class="content-body">
    <div class="row mb-20"><div class="col-12"><div class="page-heading"><h3>Chọn ghế & combo</h3></div></div></div>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <div class="card" style="padding:16px;">
        <form method="post" action="index.php?act=nv_datve">
            <input type="hidden" name="id_phim" value="<?= (int)$id_phim ?>"/>
            <input type="hidden" name="id_lc" value="<?= (int)$id_lc ?>"/>
            <input type="hidden" name="id_tg" value="<?= (int)$id_tg ?>"/>
            <input type="hidden" name="email_kh" value="<?= htmlspecialchars($email_kh) ?>"/>

            <div class="row">
                <div class="col-12 col-lg-8 mb-20">
                    <div class="order-step-area">
                        <div class="order-step first--step order-step--disable ">1. Chọn thời gian</div>
                        <div class="order-step second--step">2. Chọn chỗ ngồi</div>
                    </div>
                    <div class="choose-sits">
                        <div class="choose-sits__info choose-sits__info--first">
                            <ul>
                                <li class="sits-price marker--none"><strong>Giá</strong></li>
                                <li class="sits-price sits-price--cheap">100.000 VND</li>
                                <li class="sits-price sits-price--middle">200.000 VND</li>
                                <li class="sits-price sits-price--expensive">300.000 VND</li>
                            </ul>
                        </div>
                        <div class="choose-sits__info">
                            <ul>
                                <li class="sits-state sits-state--not">Đã đặt</li>
                                <li class="sits-state sits-state--your">Đang chọn</li>
                            </ul>
                        </div>
                        <div class="col-sm-12 col-lg-10 col-lg-offset-1">
                            <div class="sits-area hidden-xs">
                                <style>
                                  .sits-area{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:22px 18px 14px;box-shadow:0 8px 24px rgba(0,0,0,.06)}
                                  .sits__line--right{right:0;left:auto}
                                  .sits .sits__row{white-space:nowrap}
                                  /* đồng bộ 30 + 10 margin = 40px một cột */
                                  .sits .sits__row .sits__place{width:30px;height:30px;margin:5px;display:inline-block;border-radius:6px;transition:transform .06s ease}
                                  .sits .sits__row .sits__place:hover{transform:scale(1.06)}
                                  .sits .sits__row .sits__place:before{border-radius:6px}
                                  .sits .sits__indecator{border:1px solid #d1d5db;border-radius:6px;color:#4b5563;background:#fff}
                                  .sits .sits__number{margin-top:18px}
                                </style>
                                <div class="sits-anchor">Màn hình</div>
                                <div class="sits" id="nv-sits">
                                    <?php
                                    $reserved = $ghe_da_dat ?? [];
                                    if (!empty($seat_map)){
                                        // Render from configured seat map
                                        $byRow = [];
                                        foreach ($seat_map as $s){ $byRow[$s['row_label']][] = $s; }
                                        ksort($byRow);
                                        echo '<aside class="sits__line">';
                                        foreach (array_keys($byRow) as $r){ echo '<span class="sits__indecator">'.$r.'</span>'; }
                                        echo '</aside>';
                                        $maxCol = 0;
                                        foreach ($byRow as $r => $list){
                                            usort($list, function($a,$b){ return $a['seat_number'] <=> $b['seat_number']; });
                                            if (!empty($list)) { $maxCol = max($maxCol, (int)end($list)['seat_number']); reset($list); }
                                            echo '<div class="sits__row">';
                                            foreach ($list as $it){
                                                if (!(int)$it['active']) continue; // ẩn ghế không dùng
                                                $code = $it['code'];
                                                $taken = in_array($code, $reserved, true);
                                                $tier = $it['tier'];
                                                $price = ($tier==='expensive'?300000:($tier==='middle'?200000:100000));
                                                $classes = 'sits__place sits-price--'.$tier.($taken?' sits-state--not':'');
                                                $attrs = 'data-place="'.$code.'" data-price="'.$price.'"';
                                                echo '<span class="'.$classes.'" '.$attrs.'>'.$code.'</span>';
                                            }
                                            echo '</div>';
                                        }
                                        // right side labels
                                        echo '<aside class="sits__line sits__line--right">';
                                        foreach (array_keys($byRow) as $r){ echo '<span class="sits__indecator">'.$r.'</span>'; }
                                        echo '</aside>';
                                        // bottom numbers
                                        if ($maxCol > 0) {
                                            echo '<footer class="sits__number" id="nv-sits-number">';
                                            for($i=1;$i<=$maxCol;$i++){ echo '<span class="sits__indecator">'.$i.'</span>'; }
                                            echo '</footer>';
                                        }
                                    } else {
                                        // Fallback default matrix like user site
                                        $rows = range('A','L');
                                        $cols = range(1,18);
                                        echo '<aside class="sits__line">';
                                        foreach ($rows as $r){
                                            $rowCls = ($r==='J') ? ' class="additional-margin"' : '';
                                            echo '<span class="sits__indecator"'.$rowCls.'>'.$r.'</span>';
                                        }
                                        echo '</aside>';
                                        foreach ($rows as $r){
                                            $rowClass = ($r==='J') ? 'sits__row additional-margin' : 'sits__row';
                                            echo '<div class="'.$rowClass.'">';
                                            foreach ($cols as $c){
                                                $code = $r.$c;
                                                $taken = in_array($code, $reserved, true);
                                                $cls = 'sits-price--cheap'; $price=100000;
                                                if (in_array($r, ['F','G','H','I'], true)) { $cls='sits-price--middle'; $price=200000; }
                                                if (in_array($r, ['J','K','L'], true) && $c>=5 && $c<=12) { $cls='sits-price--expensive'; $price=300000; }
                                                $classes = 'sits__place '.$cls.($taken?' sits-state--not':'');
                                                $attrs = 'data-place="'.$code.'" data-price="'.$price.'"';
                                                echo '<span class="'.$classes.'" '.$attrs.'>'.$code.'</span>';
                                            }
                                            echo '</div>';
                                        }
                                        // number footer
                                        echo '<aside class="sits__line sits__line--right">';
                                        foreach ($rows as $r){ echo '<span class="sits__indecator">'.$r.'</span>'; }
                                        echo '</aside>';
                                        echo '<footer class="sits__number">';
                                        foreach ($cols as $c) { echo '<span class="sits__indecator">'. $c .'</span>'; }
                                        echo '</footer>';
                                    }
                                    ?>
                                </div>
                                <?php if (empty($seat_map)) { echo '<footer class="sits__number" id="nv-sits-number">'; foreach (range(1,18) as $c){ echo '<span class="sits__indecator">'.$c.'</span>'; } echo '</footer>'; } ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="ghe_csv" id="ghe_csv" value=""/>
                </div>

                <div class="col-12 col-lg-4 mb-20">
                    <style>
                        .prodoan{display:flex;flex-direction:column;gap:12px}
                        .prodo{display:flex;gap:10px;border:1px solid #e5e7eb;border-radius:8px;padding:10px;align-items:center}
                        .prodo img{width:64px;height:64px;object-fit:cover;border-radius:6px}
                        .prodo h3{margin:0 0 4px;font-size:16px}
                        .prodo p{margin:0;color:#6b7280;font-size:12px}
                        .check_do_an{background-color:#dc3545;color:#fff;padding:6px 10px;border-radius:6px;cursor:pointer;white-space:nowrap}
                        .check_do_an.btn--success{background-color:#16a34a}
                    </style>
                    <div class="card" style="padding:12px;">
                        <h5 style="margin:0 0 10px">Chọn combo</h5>
                        <div class="prodoan" id="combo-list">
                            <div style="color:#6b7280">Đang tải combo...</div>
                        </div>
                        <input type="hidden" name="combo_text" id="combo_text" value=""/>
                    </div>

                    <div class="card" style="padding:12px;margin-top:12px;">
                        <h5 style="margin:0 0 10px">Tổng tiền</h5>
                        <div>Ghế: <span id="sumSeat">0</span> đ</div>
                        <div>Combo: <span id="sumCombo">0</span> đ</div>
                        <hr/>
                        <div><b>Tổng cộng: <span id="sumTotal">0</span> đ</b></div>
                        <input type="hidden" name="price" id="price" value="0"/>
                    </div>
                </div>
            </div>

            <div class="actions" style="display:flex;gap:10px;justify-content:flex-end">
                <button class="button" type="submit" name="back_step" value="1">Quay lại</button>
                <button class="button button-primary" type="submit" name="datve_confirm" value="1">Xác nhận đặt vé</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
  // State holders
  const active = new Set();
  const sumSeat = document.getElementById('sumSeat');
  const sumCombo = document.getElementById('sumCombo');
  const sumTotal = document.getElementById('sumTotal');
  const priceInput = document.getElementById('price');
  const gheInput = document.getElementById('ghe_csv');
  const comboTextInput = document.getElementById('combo_text');

  // Wire seat clicks similar to user site
  function bindSeatEvents(){
    document.querySelectorAll('.sits__place').forEach(n=>{
      n.addEventListener('click', ()=>{
        if(n.classList.contains('sits-state--not')) return;
        const seat = n.getAttribute('data-place');
        if(active.has(seat)) { active.delete(seat); n.classList.remove('sits-state--your'); }
        else { active.add(seat); n.classList.add('sits-state--your'); }
        render();
      });
    });
  }

  // Load combos and render like doan.php
  async function loadCombos(){
    const wrap = document.getElementById('combo-list');
    try {
      const res = await fetch('index.php?act=api_combos');
      const list = await res.json();
      wrap.innerHTML = '';
      const items = Array.isArray(list) && list.length ? list : [
        { name:'Combo Coca', price:59000, image:'combo1.png', description:'1 Bắp (69oz) + 1 Nước có gaz (22oz)' },
        { name:'Combo Halo', price:259000, image:'combo2.png', description:'10 Hộp bắp + FREE nước vị bất kỳ' },
        { name:'Combo Wish C1', price:125000, image:'combo4.png', description:'01 ly nước ngọt size M + 01 Hộp bắp' },
        { name:'Combo Wish B3', price:185000, image:'combo5.png', description:'02 ly nước ngọt size L + 02 Hộp bắp' }
      ];
      items.forEach(cb=>{
        const node = document.createElement('div');
        node.className = 'prodo';
        node.innerHTML = `
          <img src="../Trang-nguoi-dung/imgavt/${cb.image || 'combo1.png'}" alt="combo"/>
          <div style="flex:1">
            <h3>${cb.name || 'Combo'}</h3>
            <p>${cb.description || ''}</p>
            <div style="margin-top:4px;color:#111827;font-weight:600">${(cb.price||0).toLocaleString('vi-VN')}đ</div>
          </div>
          <span class="check_do_an btn btn-md btn--danger" data-name="${cb.name}" data-price="${cb.price||0}">CHỌN NGAY</span>
        `;
        wrap.appendChild(node);
      });

      // Toggle selection like user site and recompute
      wrap.querySelectorAll('.check_do_an').forEach(btn=>{
        btn.addEventListener('click', ()=>{
          if(btn.classList.contains('btn--danger')){
            btn.classList.remove('btn--danger');
            btn.classList.add('btn--success');
            btn.textContent = 'BỎ CHỌN';
          } else {
            btn.classList.remove('btn--success');
            btn.classList.add('btn--danger');
            btn.textContent = 'CHỌN NGAY';
          }
          render();
        });
      });
    } catch (e) {
      wrap.innerHTML = '<small>Không tải được danh sách combo</small>';
    }
  }

  function render(){
    // Seats sum
    let seatSum=0; active.forEach(seat=>{
      const el = document.querySelector('.sits__place[data-place="'+seat+'"]');
      if(el) seatSum += parseInt(el.getAttribute('data-price')||'0');
    });
    // Combos sum
    const selectedCombos = Array.from(document.querySelectorAll('.check_do_an.btn--success'));
    const comboSum = selectedCombos.reduce((acc,el)=>acc+parseInt(el.getAttribute('data-price')||'0'),0);

    sumSeat.textContent = seatSum.toLocaleString('vi-VN');
    sumCombo.textContent = comboSum.toLocaleString('vi-VN');
    const total = seatSum + comboSum;
    sumTotal.textContent = total.toLocaleString('vi-VN');
    priceInput.value = total;
    gheInput.value = Array.from(active).join(',');
    comboTextInput.value = selectedCombos.map(el=>el.getAttribute('data-name')).join(' , ');
  }

  bindSeatEvents();
  loadCombos();
  // Balance width so numbers don’t wrap and labels hug grid
  (function(){
    const grid = document.getElementById('nv-sits');
    const nums = document.querySelectorAll('#nv-sits-number .sits__indecator');
    if(grid){
      let unit = 40; const sample = grid.querySelector('.sits__place');
      if(sample){ const cs=getComputedStyle(sample); unit = sample.offsetWidth + parseFloat(cs.marginLeft)+parseFloat(cs.marginRight); }
      const cols = nums.length || (grid.querySelector('.sits__row')?.children.length || 0);
      if(cols){ grid.style.width=Math.round(cols*unit)+'px'; grid.style.margin='0 auto'; }
    }
  })();
})();
</script>
