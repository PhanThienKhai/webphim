<?php
include "./view/home/sideheader.php";
if (is_array($loadphong1)) {
    extract($loadphong1);
}
?>

<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10" style="margin-top: -46px;">

        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3> Phòng <span>/ Sửa phòng</span></h3>
            </div>
        </div><!-- Page Heading End -->

        <!-- Page Button Group Start -->

    </div><!-- Page Headings End -->

    <!-- Simple Alert Notification Style -->
    <style>
        /* Đảm bảo layout tương thích với theme admin */
        body, html, .wrapper, .content-body {
            background: transparent !important;
        }
        
        /* Đảm bảo form không tạo nền trắng */
        .add-edit-product-wrap, .add-edit-product-form {
            background: transparent !important;
        }
        
        /* Ẩn đường kẻ dọc từ theme admin */
        .title::before,
        .title::after,
        h4.title::before,
        h4.title::after {
            display: none !important;
        }
        
        /* Ẩn đường kẻ dọc từ choose-sits__info--first */
        .choose-sits__info--first {
            border-right: none !important;
        }
        
        /* Simple slide notification */
        .slide-notification {
            position: fixed;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            font-size: 15px;
            z-index: 99999;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            min-width: 300px;
            text-align: center;
        }
        .slide-notification.show {
            top: 80px;
        }
        .slide-notification.success {
            background: #333;
            border-left: 4px solid #ddefdeff;
        }
        .slide-notification.error {
            background: #333;
            border-left: 4px solid #f44336;
        }
    </style>
    
    <script>
        function showSlideAlert(message, type = 'success', duration = 3500) {
            // Remove existing notification
            const existing = document.querySelector('.slide-notification');
            if (existing) existing.remove();
            
            // Create new notification
            const alert = document.createElement('div');
            alert.className = `slide-notification ${type}`;
            alert.textContent = message;
            
            document.body.appendChild(alert);
            
            // Show with animation
            setTimeout(() => alert.classList.add('show'), 100);
            
            // Auto hide
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, duration);
        }
        
        // Show notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for notification parameters and show alerts
            const urlParams = new URLSearchParams(window.location.search);
            let hasNotification = false;
            
            <?php if(isset($suatc) && $suatc != ""): ?>
                showSlideAlert('<?= addslashes($suatc) ?>', 'success', 4000);
                hasNotification = true;
            <?php endif; ?>
            
            <?php if(isset($error) && $error != ""): ?>
                showSlideAlert('<?= addslashes($error) ?>', 'error', 5000);
                hasNotification = true;
            <?php endif; ?>
            
            // Clean URL after showing notification
            if (hasNotification && (urlParams.has('success') || urlParams.has('error'))) {
                urlParams.delete('success');
                urlParams.delete('error');
                const newUrl = window.location.pathname + '?' + urlParams.toString();
                window.history.replaceState({}, document.title, newUrl);
            }
        });
    </script>

    <!-- Add or Edit Product Start -->
    <form action="index.php?act=suaphong&ids=<?= (int)($id ?? 0) ?>" method="POST">
        <input type="hidden" name="id" value="<?= (int)($id ?? 0) ?>">
        <div class="add-edit-product-wrap col-12">

            <div class="add-edit-product-form">

                <h4 class="title">Sửa phòng chiếu</h4>

                <div class="row">
                    <input  type="hidden" name="id" value="<?= $id ?>">

                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Tên phòng</span><br>
                        <input class="form-control" type="text"  name="name" value="<?=$name?>"></div>
                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Số ghế</span><br>
                        <input class="form-control" type="number"  name="so_ghe" value="<?=$so_ghe ?? 0?>"></div>
                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Diện tích (m²)</span><br>
                        <input class="form-control" type="number" step="0.01" name="dien_tich" value="<?=$dien_tich ?? 0?>"></div>
               
                </div>

                <!-- Pricing Section -->
                <div class="row">
                    <div class="col-12 mb-10">
                        <h4 class="title">💰 Giá vé theo loại ghế</h4>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Ghế Thường (VNĐ)</span><br>
                        <input class="form-control" type="number" step="1000" name="gia_thuong" value="<?= number_format($gia_thuong ?? 50000, 0, '', '') ?>" placeholder="50000">
                        <small class="text-muted">Giá hiển thị: <?= number_format($gia_thuong ?? 50000, 0, '.', ',') ?>đ</small>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Ghế Trung (VNĐ)</span><br>
                        <input class="form-control" type="number" step="1000" name="gia_trung" value="<?= number_format($gia_trung ?? 70000, 0, '', '') ?>" placeholder="70000">
                        <small class="text-muted">Giá hiển thị: <?= number_format($gia_trung ?? 70000, 0, '.', ',') ?>đ</small>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <span class="title">Ghế VIP (VNĐ)</span><br>
                        <input class="form-control" type="number" step="1000" name="gia_vip" value="<?= number_format($gia_vip ?? 100000, 0, '', '') ?>" placeholder="100000">
                        <small class="text-muted">Giá hiển thị: <?= number_format($gia_vip ?? 100000, 0, '.', ',') ?>đ</small>
                    </div>
                </div> 

                <div class="product-upload-gallery row flex-wrap">


                    <!-- Button Group Start -->
                    <div class="row">
                        <div class="d-flex flex-wrap justify-content-end col mbn-10">
                            <!-- Nút sẽ được đặt ở cuối form -->
                        </div>
                    </div><!-- Button Group End -->

                </div>

            </div><!-- Add or Edit Product End -->

    <!-- Seat map editor integrated -->
    <div class="row mt-20">
        <div class="col-12">
            <h4 class="title" style="margin-left: 20px;">Sơ đồ ghế của phòng</h4>
        </div>
        <div class="col-12">
            <link rel="stylesheet" href="../Trang-nguoi-dung/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
            <link rel="stylesheet" href="../Trang-nguoi-dung/css/style3860.css">
            <style>
                :root{ --seat-cheap:#fff0c7; --seat-middle:#ffc8cb; --seat-exp:#cdb4bd; --seat-off:#dbdee1 }
                .seat-off{opacity:.35;filter:grayscale(1);}
                
                /* Đảm bảo font nhất quán với hệ thống */
                .choose-sits, .editor-tools, .sits-anchor, .sits__indecator, .badge, .chip {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
                    margin-top: -24px;
                }
                .tools{display:flex;gap:10px;align-items:center;margin:12px 0;flex-wrap:wrap}
                .tools .badge{display:inline-block;padding:9px 10px;border-radius:15px;background:#f3f4f6; font-size: 12px}
                .palette{display:flex;gap:8px;align-items:center}
                .chip{display:inline-flex;align-items:center;gap:6px;border:1px solid #e5e7eb;border-radius:999px;padding:6px 10px;cursor:pointer;background:#fff}
                .chip.is-active{outline:2px solid #111827}
                .dot{width:14px;height:14px;border-radius:50%}
                .dot.cheap{background:var(--seat-cheap)}
                .dot.middle{background:var(--seat-middle)}
                .dot.exp{background:var(--seat-exp)}
                .dot.off{background:var(--seat-off)}
                /* Khung bao ngoài - co giãn theo nội dung - COPY FROM sua_simple.php */
                .sits-area {
                                    display: inline-block;
                                    margin: 0 auto;
                                    background: #fff;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 27px;
                                    padding: 29px 152px 50px;
                                    box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
                                    position: relative;
                                    min-width: 253px;
                                    max-width: 100%;
                                }
                .sits-anchor{color:#6b7280;font-weight:600;margin-bottom:35px;text-align:center;}
                
                /* Container chính căn giữa */
                .sits-container{text-align:center;width:100%;}
                
                /* Container chứa sơ đồ ghế */
                .sits{
                    position:relative;
                    display:inline-block;
                    margin:0 auto;
                    width:fit-content;
                }
                .sits .sits__row{white-space:nowrap;text-align:center;margin:2px 0;}
                
                /* Ghế - kích thước cố định + hover effect cho editor */
                .sits .sits__row .sits__place{
                    width:30px;height:30px;margin:3px;
                    display:inline-block;border-radius:6px;
                    font-size:10px;line-height:30px;text-align:center;
                    vertical-align:top;
                    transition:transform .06s ease;
                    cursor:pointer; /* Cho editor */
                }
                .sits .sits__row .sits__place:hover{transform:scale(1.06)}
                .sits .sits__row .sits__place:before{border-radius:6px}
                
                /* Chữ cái bên trái và phải - đồng bộ với ghế - từ simple */
                .sits__line{
                    position:absolute;left:-68px;top:0;
                    display:flex;flex-direction:column;
                    justify-content:flex-start;
                    width:30px;
                }
                .sits__line--right{right:-68px;left:auto;}
                .sits__line .sits__indecator{
                    height:30px;line-height:20px;margin:4px 0;
                    border:1px solid #d1d5db;border-radius:6px;
                    color:#4b5563;background:#f9fafb;
                    text-align:center;font-size:11px;font-weight:600;
                }
                
                /* Số cột phía dưới - đồng bộ với ghế */
                .sits__number{
                    margin-top:10px;text-align:center;
                    white-space:nowrap;
                }
                .sits__number .sits__indecator{
                    width:30px;height:29px;line-height:19px;margin:0 3px;
                    display:inline-block;
                    border:1px solid #d1d5db;border-radius:6px;
                    color:#4b5563;background:#f9fafb;
                    text-align:center;font-size:11px;font-weight:600;
                }
                
                /* Thông tin ghế cho editor */
                .choose-sits .choose-sits__info {
                    display: inline-block;
                    font-size: 13px;
                    padding: 10px 20px 0 40px;
                    margin-bottom: 22px;
                }
                .choose-sits__info ul{list-style:none;padding:0;display:flex;gap:15px;flex-wrap:wrap;margin:0;}
                .choose-sits__info ul li{display:flex;align-items:center;gap:8px;font-size:16px;}
                .editor-controls{display:grid;gap:10px}
                .editor-controls .row{margin:0}
                .editor-tools{position:sticky;top:16px}
                
                /* Layout căn giữa cho card và sơ đồ ghế */
                .seat-editor-row.row.justify-content-center {
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 20px 0;
                }
                .seat-editor-row .card {
                    display: inline-block;
                    vertical-align: top;
                    margin-top: 0;
                }
            </style>
                <div class="row justify-content-center seat-editor-row">
                    <div class="col-12 col-lg-7">
                        <div class="choose-sits">
                            <div class="choose-sits__info choose-sits__info--first">
                                <ul>
                                    <li class="sits-price marker--none"><strong>Loại ghế & Giá vé</strong></li>
                                    <li class="sits-price sits-price--cheap">Thường - <?= number_format($gia_thuong ?? 50000, 0, '.', ',') ?>đ</li>
                                    <li class="sits-price sits-price--middle">Trung - <?= number_format($gia_trung ?? 70000, 0, '.', ',') ?>đ</li>
                                    <li class="sits-price sits-price--expensive">VIP - <?= number_format($gia_vip ?? 100000, 0, '.', ',') ?>đ</li>
                                    <li class="sits-state sits-state--not">Ghế đã khóa (không dùng)</li>
                                </ul>
                            </div>
                            <div class="choose-sits__info">
                                <ul>
                                    <li style="color:#059669;"><strong>💡 Tip:</strong> Click ghế để bật/tắt, giữ chuột để tô nhiều ghế</li>
                                </ul>
                            </div>
                            
                            <div class="sits-container">
                                <div class="sits-area">
                                    <div class="sits-anchor">🎬 Màn hình</div>
                                        <div class="sits" id="grid">
                                        <?php if (!empty($map)){
                                            $byRow = [];
                                            foreach ($map as $m){ $byRow[$m['row_label']][]=$m; }
                                            ksort($byRow);
                                            echo '<aside class="sits__line">';
                                            foreach (array_keys($byRow) as $r){ echo '<span class="sits__indecator">'.$r.'</span>'; }
                                            echo '</aside>';
                                            $maxCol=0;
                                            foreach ($byRow as $r=>$list){
                                                usort($list, function($a,$b){ return $a['seat_number']<=>$b['seat_number']; });
                                                if (!empty($list)) { $maxCol = max($maxCol, (int)end($list)['seat_number']); reset($list); }
                                                echo '<div class="sits__row">';
                                                foreach ($list as $s){
                                                    $cls = 'sits__place sits-price--'.htmlspecialchars($s['tier']);
                                                    if (!(int)$s['active']) $cls .= ' seat-off';
                                                    $attrs = 'data-row="'.htmlspecialchars($s['row_label']).'" data-col="'.(int)$s['seat_number'].'" data-tier="'.htmlspecialchars($s['tier']).'" data-active="'.(int)$s['active'].'"';
                                                    echo '<span class="'.$cls.'" '.$attrs.'>'.htmlspecialchars($s['code']).'</span>';
                                                }
                                                echo '</div>';
                                            }
                                            echo '<aside class="sits__line sits__line--right">';
                                            foreach (array_keys($byRow) as $r){ echo '<span class="sits__indecator">'.$r.'</span>'; }
                                            echo '</aside>';
                                            if ($maxCol>0){
                                                echo '<footer class="sits__number">';
                                                for($i=1;$i<=$maxCol;$i++){ echo '<span class="sits__indecator">'.$i.'</span>'; }
                                                echo '</footer>';
                                            }
                                        } else { echo '<div style="color:#6b7280">Chưa có sơ đồ. Hãy tạo mặc định bên phải.</div>'; }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card editor-tools" style="padding:27px;margin-left:-94px; margin-top: 58px; margin-right: 34px;">
                            <div class="tools">
                                <div class="palette" id="palette">
                                    <div class="chip is-active" data-tool="cheap"><span class="dot cheap"></span><span>Thường</span></div>
                                    <div class="chip" data-tool="middle"><span class="dot middle"></span><span>Trung</span></div>
                                    <div class="chip" data-tool="expensive"><span class="dot exp"></span><span>VIP</span></div>
                                    <div class="chip" data-tool="off"><span class="dot off"></span><span>Tắt ghế</span></div>
                                </div>
                                <span class="badge">Giữ chuột để tô nhiều ghế</span>
                            </div>
                            <div class="row mb-10">
                                <div class="col-6"><label>Hàng</label><input class="form-control" type="number" name="rows" value="12" min="1"></div>
                                <div class="col-6"><label>Cột</label><input class="form-control" type="number" name="cols" value="18" min="1"></div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-6"><button class="button button-outline" type="button" id="addRow">+ Thêm hàng</button></div>
                                <div class="col-6"><button class="button button-outline" type="button" id="addCol">+ Thêm cột</button></div>
                                <div class="col-6" style="margin-top:8px"><button class="button button-outline button-danger" type="button" id="delRow">- Bớt hàng</button></div>
                                <div class="col-6" style="margin-top:8px"><button class="button button-outline button-danger" type="button" id="delCol">- Bớt cột</button></div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-12">
                                    <label>Bố cục mẫu</label>
                                    <select class="form-control" id="preset">
                                        <option value="premium" selected>Premium (2 lối đi + VIP phía sau)</option>
                                        <option value="center">1 lối đi giữa</option>
                                        <option value="twoaisles">2 lối đi hai bên</option>
                                        <option value="noaisle">Không lối đi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-12"><button class="button button-outline" type="button" onclick="applyPreset()">Áp dụng mẫu (không lưu)</button></div>
                            </div>
                            <div class="row mb-10"><div class="col-12"><button class="button" name="tao_map" value="1">Tạo sơ đồ mặc định</button></div></div>
                            <input type="hidden" name="map_json" id="map_json" />
                            <div class="row"><div class="col-12"><button class="button button-primary" name="cap_nhat_full" value="1" onclick="return collectAndSubmit()">Cập nhật</button></div></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function bindGridEvents(){
        const grid = document.getElementById('grid');
        if(!grid) return;
        grid.querySelectorAll('.sits__place').forEach(el=>{
            el.addEventListener('click', (ev)=>{
                const active = el.getAttribute('data-active') === '1';
                if (ev.shiftKey) {
                    const tiers=['cheap','middle','expensive'];
                    let t = el.getAttribute('data-tier')||'cheap';
                    let idx = (tiers.indexOf(t)+1)%tiers.length;
                    t = tiers[idx];
                    el.setAttribute('data-tier', t);
                    el.classList.remove('sits-price--cheap','sits-price--middle','sits-price--expensive');
                    el.classList.add('sits-price--'+t);
                } else {
                    el.setAttribute('data-active', active ? '0':'1');
                    el.classList.toggle('seat-off');
                }
            });
        });
    }
    let currentTool = 'cheap';
    bindGridEvents();

    // palette handlers
    document.getElementById('palette').addEventListener('click', (e)=>{
        const chip = e.target.closest('.chip');
        if(!chip) return;
        document.querySelectorAll('#palette .chip').forEach(c=>c.classList.remove('is-active'));
        chip.classList.add('is-active');
        currentTool = chip.getAttribute('data-tool');
    });

    function drawGrid(list){
        const grid = document.getElementById('grid');
        if(!grid) return;
        // group by row
        const byRow = {};
        let maxCol=0; let rowOrder=[];
        list.forEach(s=>{
            if(!byRow[s.row_label]){ byRow[s.row_label]=[]; rowOrder.push(s.row_label); }
            byRow[s.row_label].push(s);
            if (s.seat_number>maxCol) maxCol=s.seat_number;
        });
        rowOrder.sort();
        // build HTML
        let html = '';
        html += '<aside class="sits__line">'+ rowOrder.map(r=>'<span class="sits__indecator">'+r+'</span>').join('') +'</aside>';
        rowOrder.forEach(r=>{
            const items = byRow[r].sort((a,b)=>a.seat_number-b.seat_number);
            html += '<div class="sits__row">';
            items.forEach(s=>{
                const active = s.active ? 1 : 0;
                const cls = 'sits__place sits-price--'+(s.tier||'cheap') + (active? '':' seat-off');
                html += '<span class="'+cls+'" data-row="'+s.row_label+'" data-col="'+s.seat_number+'" data-tier="'+(s.tier||'cheap')+'" data-active="'+active+'">'+s.code+'</span>';
            });
            html += '</div>';
        });
        html += '<aside class="sits__line sits__line--right">'+ rowOrder.map(r=>'<span class="sits__indecator">'+r+'</span>').join('') +'</aside>';
        if (maxCol>0){ html += '<footer class="sits__number">'+ Array.from({length:maxCol}, (_,i)=>'<span class="sits__indecator">'+(i+1)+'</span>').join('') +'</footer>'; }
        grid.innerHTML = html;
        // Set fixed width so labels and numbers align and center nicely
        const unit = (function(){
            const el = grid.querySelector('.sits__place');
            if(!el) return 36; // 30px width + 3px margin left + 3px margin right
            const cs = getComputedStyle(el);
            return el.offsetWidth + parseFloat(cs.marginLeft) + parseFloat(cs.marginRight);
        })();
        // Không cần set width cho grid vì đã dùng CSS positioning
        // grid.style.width = Math.round(maxCol * unit) + 'px';
        // grid.style.margin = '0 auto';
        bindGridEvents();
    }

    function genPresetList(rows, cols, preset){
        const letters = Array.from({length: rows}, (_,i)=>String.fromCharCode('A'.charCodeAt(0)+i));
        const list = [];
        const mid = Math.floor((cols+1)/2);
        const aisles = new Set();
        if (preset==='center') aisles.add(mid);
        if (preset==='twoaisles') { aisles.add(5); aisles.add(cols-4); }
        if (preset==='premium') { aisles.add(5); aisles.add(cols-4); }
        letters.forEach((r,ri)=>{
            for(let c=1;c<=cols;c++){
                let active = !aisles.has(c);
                let tier = 'cheap';
                // tier logic
                if (preset==='premium'){
                    if (ri >= rows-3 && c>=6 && c<=cols-5) tier='expensive';
                    else if (ri >= Math.floor(rows/2)-1) tier='middle';
                } else {
                    if (ri >= rows-4 && c>=6 && c<=cols-5) tier='expensive';
                    else if (ri >= Math.floor(rows/2)-2) tier='middle';
                }
                list.push({ row_label:r, seat_number:c, code:r+c, tier, active: active?1:0 });
            }
        });
        return list;
    }

    function applyPreset(){
        const rows = parseInt(document.querySelector('input[name="rows"]').value||'12');
        const cols = parseInt(document.querySelector('input[name="cols"]').value||'18');
        const preset = document.getElementById('preset').value;
        const list = genPresetList(rows, cols, preset);
        drawGrid(list);
    }

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

    // Painting (drag) support + tool actions
    (function(){
        let isDown=false;
        document.addEventListener('mousedown', (e)=>{ if (e.button===0) isDown=true; });
        document.addEventListener('mouseup', ()=>{ isDown=false; });
        document.getElementById('grid').addEventListener('mouseover', (e)=>{
            if(!isDown) return;
            const el = e.target.closest('.sits__place');
            if(!el) return;
            applyTool(el);
        });
        document.getElementById('grid').addEventListener('click', (e)=>{
            const el=e.target.closest('.sits__place'); if(!el) return; applyTool(el);
        });
        function applyTool(el){
            if(currentTool==='off'){
                el.setAttribute('data-active','0'); el.classList.add('seat-off');
            } else {
                el.setAttribute('data-active','1'); el.classList.remove('seat-off');
                el.setAttribute('data-tier', currentTool);
                el.classList.remove('sits-price--cheap','sits-price--middle','sits-price--expensive');
                el.classList.add('sits-price--'+(currentTool==='expensive'?'expensive':currentTool));
            }
        }
        // Initial adjustment - không cần set width vì đã dùng CSS positioning
        // Grid sẽ tự căn giữa và labels sẽ đồng bộ với ghế thông qua CSS
    })();

    // Row/Col controls
    (function(){
        const gridEl = document.getElementById('grid');
        function getRows(){ return Array.from(gridEl.querySelectorAll('.sits__row')); }
        function getColsCount(){ const r=getRows()[0]; return r? r.querySelectorAll('.sits__place').length : 0; }
        function refreshLabels(){
            const rowsEls = getRows();
            const rowLetters = rowsEls.map(r=> r.querySelector('.sits__place')?.getAttribute('data-row')).filter(Boolean);
            const maxCol = getColsCount();
            gridEl.querySelectorAll('.sits__line, .sits__number').forEach(n=>n.remove());
            const left = document.createElement('aside'); left.className='sits__line';
            left.innerHTML = rowLetters.map(r=>'<span class="sits__indecator">'+r+'</span>').join('');
            gridEl.insertBefore(left, rowsEls[0]||null);
            const right = document.createElement('aside'); right.className='sits__line sits__line--right';
            right.innerHTML = rowLetters.map(r=>'<span class="sits__indecator">'+r+'</span>').join('');
            gridEl.appendChild(right);
            const foot = document.createElement('footer'); foot.className='sits__number';
            foot.innerHTML = Array.from({length:maxCol}, (_,i)=>'<span class="sits__indecator">'+(i+1)+'</span>').join('');
            gridEl.appendChild(foot);
        }
        function nextLetter(ch){ return String.fromCharCode(ch.charCodeAt(0)+1); }
        function lastLetter(){ const rows=getRows(); if(!rows.length) return 'A'; return rows[rows.length-1].querySelector('.sits__place').getAttribute('data-row'); }
        function rebuildRow(rLetter, cols){
            const div=document.createElement('div'); div.className='sits__row';
            for(let c=1;c<=cols;c++){
                const span=document.createElement('span'); span.className='sits__place sits-price--cheap';
                span.setAttribute('data-row', rLetter); span.setAttribute('data-col', c);
                span.setAttribute('data-tier','cheap'); span.setAttribute('data-active','1');
                span.textContent = rLetter + c;
                div.appendChild(span);
            }
            return div;
        }
        document.getElementById('addRow').addEventListener('click', ()=>{
            const cols=getColsCount(); const l=lastLetter(); const nl=nextLetter(l);
            gridEl.appendChild(rebuildRow(nl, cols)); bindGridEvents(); refreshLabels();
        });
        document.getElementById('delRow').addEventListener('click', ()=>{
            const rows=getRows(); if(!rows.length) return; rows[rows.length-1].remove(); refreshLabels();
        });
        document.getElementById('addCol').addEventListener('click', ()=>{
            const rows=getRows(); const oldCols=getColsCount(); const newCol=oldCols+1;
            rows.forEach(r=>{
                const letter = r.querySelector('.sits__place').getAttribute('data-row');
                const span=document.createElement('span'); span.className='sits__place sits-price--cheap';
                span.setAttribute('data-row', letter); span.setAttribute('data-col', newCol);
                span.setAttribute('data-tier','cheap'); span.setAttribute('data-active','1');
                span.textContent = letter + newCol; r.appendChild(span);
            });
            bindGridEvents(); refreshLabels();
        });
        document.getElementById('delCol').addEventListener('click', ()=>{
            const rows=getRows(); const cols=getColsCount(); if(cols<=1) return;
            rows.forEach(r=>{ const last=r.querySelector('.sits__place:last-child'); if(last) last.remove(); });
            refreshLabels();
        });
    })();

    // Real-time price display update
    function updatePriceDisplay(inputEl) {
        const value = parseInt(inputEl.value) || 0;
        const displayEl = inputEl.parentElement.querySelector('.text-muted');
        if (displayEl) {
            displayEl.textContent = `Giá hiển thị: ${value.toLocaleString('vi-VN')}đ`;
        }
        
        // Update legend prices
        updateLegendPrices();
    }
    
    function updateLegendPrices() {
        const giaT = parseInt(document.querySelector('input[name="gia_thuong"]').value) || 50000;
        const giaTr = parseInt(document.querySelector('input[name="gia_trung"]').value) || 70000;
        const giaV = parseInt(document.querySelector('input[name="gia_vip"]').value) || 100000;
        
        const legend = document.querySelector('.choose-sits__info--first ul');
        if (legend) {
            legend.innerHTML = `
                <li class="sits-price marker--none"><strong>Loại ghế & Giá vé</strong></li>
                <li class="sits-price sits-price--cheap">Thường - ${giaT.toLocaleString('vi-VN')}đ</li>
                <li class="sits-price sits-price--middle">Trung - ${giaTr.toLocaleString('vi-VN')}đ</li>
                <li class="sits-price sits-price--expensive">VIP - ${giaV.toLocaleString('vi-VN')}đ</li>
                <li class="sits-state sits-state--not">Ghế đã khóa (không dùng)</li>
            `;
        }
    }
    
    // Bind price input events
    document.addEventListener('DOMContentLoaded', function() {
        const priceInputs = document.querySelectorAll('input[name="gia_thuong"], input[name="gia_trung"], input[name="gia_vip"]');
        priceInputs.forEach(input => {
            input.addEventListener('input', function() {
                updatePriceDisplay(this);
            });
        });
    });
    </script>
</div><!-- Content Body End -->
