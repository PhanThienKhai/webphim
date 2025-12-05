<?php include __DIR__ . '/../home/sideheader.php'; ?>

<link rel="stylesheet" href="../Trang-nguoi-dung/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="../Trang-nguoi-dung/css/style3860.css">

<style>
.content-body * {
    font-size: 14px;
}
.booking-container {
    max-width: 1400px;
    margin: 0 auto;
}
.page-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 30px;
}
.page-title-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
}
.page-title h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #1f2937;
}

/* Step Cards */
.step-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 24px;
    margin-bottom: 20px;
    transition: all 0.3s;
}
.step-card.disabled {
    opacity: 0.5;
    pointer-events: none;
}
.step-card.completed {
    border: 2px solid #10b981;
}
.step-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}
.step-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.step-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}
.step-card.completed .step-number {
    background: #10b981;
}
.step-title h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}
.step-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.badge-waiting {
    background: #fef3c7;
    color: #92400e;
}
.badge-active {
    background: #dbeafe;
    color: #1e40af;
}
.badge-completed {
    background: #d1fae5;
    color: #065f46;
}

/* Form Styles */
.form-group-modern {
    margin-bottom: 20px;
}
.form-label-modern {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}
.form-label-modern .required {
    color: #ef4444;
}
.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}
.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
.form-control-modern:disabled {
    background: #f9fafb;
    color: #9ca3af;
}

/* Seat Map */
.seat-selection-wrapper {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}
.seat-map-container {
    flex: 1;
    min-width: 500px;
}

/* === COPY CSS TỪ sua.php === */
:root{ 
    --seat-cheap:#fff0c7; 
    --seat-middle:#ffc8cb; 
    --seat-exp:#cdb4bd; 
    --seat-off:#dbdee1;
}

/* Khung bao ngoài - co giãn theo nội dung */
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

.sits-anchor {
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 35px;
    text-align: center;
    background: #374151;
    color: #fff;
    border-radius: 8px;
    padding: 12px;
}

/* Container căn giữa */
.sits-container {
    text-align: center;
    width: 100%;
}

/* Container chứa sơ đồ ghế */
.sits {
    position: relative;
    display: inline-block;
    margin: 0 auto;
    width: fit-content;
}

.sits .sits__row {
    white-space: nowrap;
    text-align: center;
    margin: 2px 0;
}

/* Ghế - kích thước cố định + hover effect */
.sits .sits__row .sits__place {
    width: 30px;
    height: 30px;
    margin: 3px;
    display: inline-block;
    border-radius: 6px;
    font-size: 10px;
    line-height: 30px;
    text-align: center;
    vertical-align: top;
    transition: transform .06s ease;
    cursor: pointer;
}

.sits .sits__row .sits__place:hover:not(.sits-state--not) {
    transform: scale(1.1);
}

.sits__place.sits-price--cheap {
    background: var(--seat-cheap);
    color: #92400e;
}

.sits__place.sits-price--middle {
    background: var(--seat-middle);
    color: #9f1239;
}

.sits__place.sits-price--expensive {
    background: var(--seat-exp);
    color: #4c1d95;
}

.sits__place.sits-state--not {
    background: var(--seat-off);
    color: #6b7280;
    cursor: not-allowed;
    opacity: 0.5;
}

.sits__place.sits-state--your {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    transform: scale(1.1);
}

/* Chữ cái bên trái và phải - đồng bộ với ghế */
.sits__line {
    position: absolute;
    left: -68px;
    top: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    width: 30px;
}

.sits__line--right {
    right: -68px;
    left: auto;
}

.sits__line .sits__indecator {
    height: 30px;
    line-height: 20px;
    margin: 4px 0;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    color: #4b5563;
    background: #f9fafb;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
}

/* Số cột phía dưới - đồng bộ với ghế */
.sits__number {
    margin-top: 10px;
    text-align: center;
    white-space: nowrap;
}

.sits__number .sits__indecator {
    width: 30px;
    height: 29px;
    line-height: 19px;
    margin: 0 3px;
    display: inline-block;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    color: #4b5563;
    background: #f9fafb;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
}

/* === END COPY === */

/* Luồng đi (ghế không active) */
.sits--walkway {
    opacity: 0.35;
    filter: grayscale(1);
    pointer-events: none;
    cursor: default !important;
}

.seat-legend {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}
.legend-box {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    position: relative;
}
.legend-box.selected {
    background: #7d7f8aff !important;
}
.legend-box.selected::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 16px;
    font-weight: bold;
}

/* Combo Selection */
.combo-sidebar {
    width: 391px;
}
.combo-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}
.combo-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s;
    cursor: pointer;
}
.combo-item:hover {
    border-color: #667eea;
}
.combo-item.selected {
    border-color: #667eea;
    background: #f0f4ff;
}
.combo-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}
.combo-info h5 {
    margin: 0 0 4px;
    font-size: 14px;
    font-weight: 600;
}
.combo-info p {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
}
.combo-price {
    font-weight: 700;
    color: #667eea;
}
.combo-quantity {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
}
.qty-btn {
    width: 28px;
    height: 28px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    font-weight: 700;
}
.qty-btn:hover {
    background: #f3f4f6;
}
.qty-value {
    width: 40px;
    text-align: center;
    font-weight: 600;
}

/* Summary Box */
.summary-box {
    background: #fff;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}
.summary-row.total {
    border-top: 2px solid #e5e7eb;
    padding-top: 12px;
    font-weight: 700;
    font-size: 16px;
    color: #667eea;
}

/* Promo code section */
#promo-code-input:focus {
    outline: none;
    border-color: #10b981;
}

#btn-apply-promo:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

#btn-apply-promo:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Buttons */
.btn-modern {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.btn-primary-modern:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}
.btn-primary-modern:disabled {
    background: #d1d5db;
    cursor: not-allowed;
}
.btn-outline-modern {
    background: #fff;
    border: 2px solid #667eea;
    color: #667eea;
}
.btn-outline-modern:hover {
    background: #667eea;
    color: #fff;
}

/* Payment Confirmation */
.payment-summary {
    background: linear-gradient(135deg, #8e94abff 0%, #a292b2ff 100%);
    color: #fff;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
}
.payment-summary h4 {
    margin: 0 0 16px;
    font-size: 20px;
}
.payment-detail {
    background: rgba(255,255,255,0.1);
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 12px;
}
.payment-detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}
.payment-total {
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    padding-top: 16px;
    border-top: 2px solid rgba(255,255,255,0.3);
}
</style>

<div class="content-body">
    <div class="booking-container">
        <div class="page-title">
            <div class="page-title-icon">🎫</div>
            <h3>Đặt vé cho khách hàng</h3>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" style="border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- STEP 1: Chọn suất chiếu -->
        <div class="step-card" id="step1">
            <div class="step-header">
                <div class="step-title">
                    <div class="step-number">1</div>
                    <h4>Chọn suất chiếu & thông tin khách hàng</h4>
                </div>
                <span class="step-badge badge-active" id="step1-badge">Đang thực hiện</span>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group-modern">
                        <label class="form-label-modern">🎬 Phim <span class="required">*</span></label>
                        <select class="form-control-modern" id="id_phim">
                            <option value="">-- Chọn phim --</option>
                            <?php foreach (($ds_phim ?? []) as $p): ?>
                                <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['tieu_de']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="form-group-modern">
                        <label class="form-label-modern">📅 Ngày chiếu <span class="required">*</span></label>
                        <select class="form-control-modern" id="id_lc" disabled>
                            <option value="">-- Chọn phim trước --</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="form-group-modern">
                        <label class="form-label-modern">⏰ Khung giờ <span class="required">*</span></label>
                        <select class="form-control-modern" id="id_tg" disabled>
                            <option value="">-- Chọn ngày trước --</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="form-group-modern">
                        <label class="form-label-modern">📧 Email khách <span class="required">*</span></label>
                        <input class="form-control-modern" type="email" id="email_kh" placeholder="vd: khach@email.com" />
                    </div>
                </div>
            </div>
            
            <div style="text-align: right;">
                <button class="btn-modern btn-primary-modern" id="btn-next-step2" disabled>
                    Tiếp tục chọn ghế →
                </button>
            </div>
        </div>

        <!-- STEP 2: Chọn ghế & combo -->
        <div class="step-card disabled" id="step2">
            <div class="step-header">
                <div class="step-title">
                    <div class="step-number">2</div>
                    <h4>Chọn ghế ngồi & combo</h4>
                </div>
                <span class="step-badge badge-waiting" id="step2-badge">Chờ hoàn thành bước 1</span>
            </div>
            
            <div class="seat-selection-wrapper">
                <div class="seat-map-container">
                    <div class="seat-legend">
                        <div class="legend-item">
                            <div class="legend-box" style="background: var(--seat-cheap);"></div>
                            <span>100.000 đ</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box" style="background: var(--seat-middle);"></div>
                            <span>200.000 đ</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box" style="background: var(--seat-exp);"></div>
                            <span>300.000 đ</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box selected"></div>
                            <span>Đang chọn</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box" style="background: var(--seat-off);"></div>
                            <span>Đã đặt</span>
                        </div>
                    </div>
                    
                    <div class="sits-area" id="seat-map-area">
                        <div class="sits-anchor">Màn hình</div>
                        <div class="sits" id="sits-container">
                            <p style="text-align: center; color: #6b7280;">Vui lòng chọn suất chiếu ở bước 1</p>
                        </div>
                    </div>
                </div>
                
                <div class="combo-sidebar">
                    <h5 style="margin: 0 0 12px; font-weight: 600;">🍿 Chọn combo (tùy chọn)</h5>
                    <div class="combo-list" id="combo-list">
                        <p style="color: #6b7280; font-size: 13px;">Đang tải combo...</p>
                    </div>
                    
                    <!-- Mã khuyến mãi -->
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px dashed #e5e7eb;">
                        <h5 style="margin: 0 0 12px; font-weight: 600;">🎟️ Mã khuyến mãi</h5>
                        <div style="display: flex; gap: 8px;">
                            <select 
                                id="promo-code-select" 
                                style="flex: 1; padding: 10px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #fff; cursor: pointer; width: 10px"
                            >
                                <option value="">Chọn mã khuyến mãi</option>
                            </select>
                            <button 
                                type="button" 
                                id="btn-apply-promo"
                                style="padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;"
                            >
                                Áp dụng
                            </button>
                        </div>
                        <div id="promo-message" style="margin-top: 8px; font-size: 13px; min-height: 20px;"></div>
                        <div id="promo-info" style="display: none; margin-top: 12px; padding: 12px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; font-size: 13px;"></div>
                    </div>
                    
                    <div class="summary-box">
                        <h5 style="margin: 0 0 12px; font-weight: 600;">Tổng tiền</h5>
                        <div class="summary-row">
                            <span>Ghế:</span>
                            <span id="sum-seat">0 đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Combo:</span>
                            <span id="sum-combo">0 đ</span>
                        </div>
                        <div class="summary-row" id="discount-row" style="display: none; color: #10b981;">
                            <span>Giảm giá:</span>
                            <span id="sum-discount">0 đ</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="sum-total">0 đ</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button class="btn-modern btn-outline-modern" id="btn-back-step1">
                    ← Quay lại
                </button>
                <button class="btn-modern btn-primary-modern" id="btn-next-step3" disabled>
                    Tiếp tục thanh toán →
                </button>
            </div>
        </div>

        <!-- STEP 3: Xác nhận thanh toán -->
        <div class="step-card disabled" id="step3">
            <div class="step-header">
                <div class="step-title">
                    <div class="step-number">3</div>
                    <h4>Xác nhận & thanh toán</h4>
                </div>
                <span class="step-badge badge-waiting" id="step3-badge">Chờ hoàn thành bước 2</span>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="payment-summary">
                        <h4>📋 Thông tin đặt vé</h4>
                        <div class="payment-detail">
                            <div class="payment-detail-row">
                                <span>Phim:</span>
                                <strong id="confirm-phim">-</strong>
                            </div>
                            <div class="payment-detail-row">
                                <span>Ngày chiếu:</span>
                                <strong id="confirm-ngay">-</strong>
                            </div>
                            <div class="payment-detail-row">
                                <span>Giờ chiếu:</span>
                                <strong id="confirm-gio">-</strong>
                            </div>
                            <div class="payment-detail-row">
                                <span>Ghế:</span>
                                <strong id="confirm-ghe">-</strong>
                            </div>
                            <div class="payment-detail-row">
                                <span>Email khách:</span>
                                <strong id="confirm-email">-</strong>
                            </div>
                            <div class="payment-detail-row" id="confirm-combo-row" style="display: none;">
                                <span>Combo:</span>
                                <strong id="confirm-combo">-</strong>
                            </div>
                            <div class="payment-detail-row" id="confirm-promo-row" style="display: none; color: #10b981;">
                                <span>🎟️ Mã KM:</span>
                                <strong id="confirm-promo">-</strong>
                            </div>
                        </div>
                        <div class="payment-total" id="confirm-total">0 đ</div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div style="background: #fff; border: 2px solid #e5e7eb; border-radius: 12px; padding: 24px;">
                        <h5 style="margin: 0 0 16px; font-weight: 600;">💳 Phương thức thanh toán</h5>
                        <div class="form-group-modern">
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #667eea; border-radius: 8px; cursor: pointer; background: #f0f4ff;">
                                <input type="radio" name="payment_method" value="cash" checked style="width: 20px; height: 20px;">
                                <span style="font-weight: 600;">💵 Tiền mặt tại quầy</span>
                            </label>
                        </div>
                        <p style="color: #6b7280; font-size: 13px; margin: 16px 0;">
                            ℹ️ Khách hàng sẽ thanh toán trực tiếp tại quầy khi nhận vé
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button class="btn-modern btn-outline-modern" id="btn-back-step2">
                    ← Quay lại
                </button>
                <button class="btn-modern btn-primary-modern" id="btn-confirm-booking" style="padding: 12px 48px;">
                    ✓ Xác nhận đặt vé
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    // State management
    const state = {
        step: 1,
        phim: null,
        lc: null,
        tg: null,
        email: '',
        seats: new Set(),
        combos: {},
        seatPrices: {},
        comboData: [],
        reservedSeats: [],
        promoCode: '',
        promoDiscount: 0,
        promoValid: false
    };

    // DOM elements
    const phimSelect = document.getElementById('id_phim');
    const lcSelect = document.getElementById('id_lc');
    const tgSelect = document.getElementById('id_tg');
    const emailInput = document.getElementById('email_kh');
    
    const step1Card = document.getElementById('step1');
    const step2Card = document.getElementById('step2');
    const step3Card = document.getElementById('step3');
    
    const btnNextStep2 = document.getElementById('btn-next-step2');
    const btnNextStep3 = document.getElementById('btn-next-step3');
    const btnBackStep1 = document.getElementById('btn-back-step1');
    const btnBackStep2 = document.getElementById('btn-back-step2');
    const btnConfirm = document.getElementById('btn-confirm-booking');
    
    // Promo code elements
    const promoSelect = document.getElementById('promo-code-select');
    const btnApplyPromo = document.getElementById('btn-apply-promo');
    const promoMessage = document.getElementById('promo-message');
    const promoInfo = document.getElementById('promo-info');
    const discountRow = document.getElementById('discount-row');
    const sumDiscount = document.getElementById('sum-discount');

    // Utility functions
    function clearSelect(sel, placeholder) {
        sel.innerHTML = '';
        const op = document.createElement('option');
        op.value = '';
        op.textContent = placeholder;
        sel.appendChild(op);
    }

    function formatCurrency(num) {
        return new Intl.NumberFormat('vi-VN').format(num) + ' đ';
    }

    function updateStepUI(step) {
        // Update cards
        [step1Card, step2Card, step3Card].forEach((card, idx) => {
            if (idx + 1 < step) {
                card.classList.remove('disabled');
                card.classList.add('completed');
            } else if (idx + 1 === step) {
                card.classList.remove('disabled', 'completed');
            } else {
                card.classList.add('disabled');
                card.classList.remove('completed');
            }
        });

        // Update badges
        const badges = {
            1: document.getElementById('step1-badge'),
            2: document.getElementById('step2-badge'),
            3: document.getElementById('step3-badge')
        };

        Object.keys(badges).forEach(key => {
            const badge = badges[key];
            badge.className = 'step-badge';
            if (parseInt(key) < step) {
                badge.className += ' badge-completed';
                badge.textContent = 'Hoàn thành';
            } else if (parseInt(key) === step) {
                badge.className += ' badge-active';
                badge.textContent = 'Đang thực hiện';
            } else {
                badge.className += ' badge-waiting';
                badge.textContent = 'Chờ hoàn thành bước ' + (parseInt(key) - 1);
            }
        });

        // Scroll to active step
        const activeCard = [step1Card, step2Card, step3Card][step - 1];
        activeCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Step 1: Load phim data
    phimSelect.addEventListener('change', async () => {
        clearSelect(lcSelect, '-- Chọn lịch theo phim --');
        clearSelect(tgSelect, '-- Chọn khung giờ --');
        lcSelect.disabled = true;
        tgSelect.disabled = true;
        state.phim = null;
        state.lc = null;
        state.tg = null;

        if (!phimSelect.value) {
            checkStep1Complete();
            return;
        }

        try {
            const res = await fetch('index.php?act=api_dates&id_phim=' + encodeURIComponent(phimSelect.value));
            const data = await res.json();

            if (data.length === 0) {
                clearSelect(lcSelect, '-- Không có lịch chiếu --');
            } else {
                clearSelect(lcSelect, '-- Chọn ngày chiếu --');
                data.forEach(row => {
                    const op = document.createElement('option');
                    op.value = row.id;
                    op.textContent = row.ngay_chieu;
                    op.dataset.ngay = row.ngay_chieu;
                    lcSelect.appendChild(op);
                });
                lcSelect.disabled = false;
            }
            
            state.phim = {
                id: phimSelect.value,
                ten: phimSelect.options[phimSelect.selectedIndex].text
            };
        } catch (e) {
            clearSelect(lcSelect, '-- Lỗi tải dữ liệu --');
        }
        checkStep1Complete();
    });

    lcSelect.addEventListener('change', async () => {
        clearSelect(tgSelect, '-- Chọn khung giờ --');
        tgSelect.disabled = true;
        state.lc = null;
        state.tg = null;

        if (!lcSelect.value) {
            checkStep1Complete();
            return;
        }

        try {
            const res = await fetch('index.php?act=api_times&id_lc=' + encodeURIComponent(lcSelect.value));
            const data = await res.json();

            if (data.length === 0) {
                clearSelect(tgSelect, '-- Không có khung giờ --');
            } else {
                clearSelect(tgSelect, '-- Chọn giờ chiếu --');
                data.forEach(row => {
                    const op = document.createElement('option');
                    op.value = row.id;
                    op.textContent = row.thoi_gian_chieu;
                    op.dataset.gio = row.thoi_gian_chieu;
                    tgSelect.appendChild(op);
                });
                tgSelect.disabled = false;
            }
            
            state.lc = {
                id: lcSelect.value,
                ngay: lcSelect.options[lcSelect.selectedIndex].dataset.ngay
            };
        } catch (e) {
            clearSelect(tgSelect, '-- Lỗi tải dữ liệu --');
        }
        checkStep1Complete();
    });

    tgSelect.addEventListener('change', () => {
        if (tgSelect.value) {
            state.tg = {
                id: tgSelect.value,
                gio: tgSelect.options[tgSelect.selectedIndex].dataset.gio
            };
        } else {
            state.tg = null;
        }
        checkStep1Complete();
    });

    emailInput.addEventListener('input', () => {
        state.email = emailInput.value.trim();
        checkStep1Complete();
    });

    function checkStep1Complete() {
        const isComplete = state.phim && state.lc && state.tg && state.email !== '';
        btnNextStep2.disabled = !isComplete;
    }

    // Move to step 2
    btnNextStep2.addEventListener('click', async () => {
        state.step = 2;
        updateStepUI(2);
        await loadSeatMap();
        await loadCombos();
        await loadPromos();
    });

    // Load seat map
    async function loadSeatMap() {
        const container = document.getElementById('sits-container');
        container.innerHTML = '<p style="text-align: center; color: #6b7280;">Đang tải sơ đồ ghế...</p>';

        console.log('🎫 Loading seat map for:', {
            id_lc: state.lc.id,
            id_tg: state.tg.id,
            lc_date: state.lc.ngay_chieu,
            tg_time: state.tg.thoi_gian_chieu
        });

        try {
            // Load reserved seats
            const apiUrl = `index.php?act=api_reserved&id_lc=${state.lc.id}&id_tg=${state.tg.id}`;
            console.log('🔗 API URL:', apiUrl);
            
            const resReserved = await fetch(apiUrl);
            state.reservedSeats = await resReserved.json();
            console.log('🔒 Reserved seats:', state.reservedSeats);

            // Load seat map từ phòng chiếu
            const resSeatMap = await fetch(`index.php?act=api_seatmap&id_tg=${state.tg.id}`);
            const data = await resSeatMap.json();
            
            const seatMap = data.seats || [];
            const prices = data.prices || { cheap: 60000, middle: 80000, expensive: 100000 };

            if (!seatMap || seatMap.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #ef4444;">Phòng chiếu chưa có sơ đồ ghế</p>';
                return;
            }

            // Update price legend
            document.querySelector('.legend-item:nth-child(1) span').textContent = formatCurrency(prices.cheap);
            document.querySelector('.legend-item:nth-child(2) span').textContent = formatCurrency(prices.middle);
            document.querySelector('.legend-item:nth-child(3) span').textContent = formatCurrency(prices.expensive);

            // Group seats by row
            const byRow = {};
            seatMap.forEach(seat => {
                if (!byRow[seat.row_label]) byRow[seat.row_label] = [];
                byRow[seat.row_label].push(seat);
            });

            // Sort rows
            const rows = Object.keys(byRow).sort();
            
            // Build HTML
            let html = '<aside class="sits__line">';
            rows.forEach(r => {
                html += `<span class="sits__indecator">${r}</span>`;
            });
            html += '</aside>';

            let maxCol = 0;
            rows.forEach(r => {
                byRow[r].sort((a, b) => a.seat_number - b.seat_number);
                const lastSeat = byRow[r][byRow[r].length - 1];
                if (lastSeat) maxCol = Math.max(maxCol, lastSeat.seat_number);

                html += '<div class="sits__row">';
                byRow[r].forEach(seat => {
                    const code = seat.code;
                    const isReserved = state.reservedSeats.includes(code);
                    const tier = seat.tier || 'cheap';
                    const price = prices[tier] || 60000;

                    // Nếu ghế không active (luồng đi) - hiển thị ô trống
                    if (!seat.active) {
                        html += `<span class="sits__place sits--walkway"></span>`;
                        return;
                    }

                    state.seatPrices[code] = price;

                    const classes = `sits__place sits-price--${tier}${isReserved ? ' sits-state--not' : ''}`;
                    html += `<span class="${classes}" data-seat="${code}" data-price="${price}">${code}</span>`;
                });
                html += '</div>';
            });

            html += '<aside class="sits__line sits__line--right">';
            rows.forEach(r => {
                html += `<span class="sits__indecator">${r}</span>`;
            });
            html += '</aside>';

            html += '<footer class="sits__number">';
            for (let c = 1; c <= maxCol; c++) {
                html += `<span class="sits__indecator">${c}</span>`;
            }
            html += '</footer>';

            container.innerHTML = html;

            // Bind seat clicks
            container.querySelectorAll('.sits__place').forEach(seat => {
                if (!seat.classList.contains('sits-state--not')) {
                    seat.addEventListener('click', () => toggleSeat(seat));
                }
            });
        } catch (e) {
            console.error('Error loading seat map:', e);
            container.innerHTML = '<p style="text-align: center; color: #ef4444;">Lỗi tải sơ đồ ghế</p>';
        }
    }

    function toggleSeat(seatEl) {
        const code = seatEl.dataset.seat;
        if (state.seats.has(code)) {
            state.seats.delete(code);
            seatEl.classList.remove('sits-state--your');
        } else {
            state.seats.add(code);
            seatEl.classList.add('sits-state--your');
        }
        updateSummary();
    }

    // Load combos
    async function loadCombos() {
        const container = document.getElementById('combo-list');
        console.log('Loading combos...');
        try {
            const res = await fetch('index.php?act=api_combos');
            console.log('Combo response status:', res.status);
            state.comboData = await res.json();
            console.log('Combo data:', state.comboData);

            if (!state.comboData || state.comboData.length === 0) {
                container.innerHTML = '<p style="color: #6b7280; font-size: 13px;">Không có combo cho rạp này</p>';
                return;
            }

            let html = '';
            state.comboData.forEach(combo => {
                state.combos[combo.id] = 0;
                html += `
                    <div class="combo-item" id="combo-${combo.id}">
                        <div class="combo-info" style="flex: 1;">
                            <h5>${combo.name}</h5>
                            <p class="combo-price">${formatCurrency(combo.price)}</p>
                            <div class="combo-quantity">
                                <button type="button" class="qty-btn" onclick="changeComboQty(${combo.id}, -1)">−</button>
                                <span class="qty-value" id="qty-${combo.id}">0</span>
                                <button type="button" class="qty-btn" onclick="changeComboQty(${combo.id}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        } catch (e) {
            console.error('Error loading combos:', e);
            container.innerHTML = '<p style="color: #ef4444; font-size: 13px;">Lỗi tải combo: ' + e.message + '</p>';
        }
    }

    // Load available promo codes
    async function loadPromos() {
        try {
            const res = await fetch('index.php?act=api_promos');
            const data = await res.json();
            
            // Clear dropdown
            promoSelect.innerHTML = '<option value="">-- Chọn mã khuyến mãi --</option>';
            
            if (data.promos && data.promos.length > 0) {
                data.promos.forEach(promo => {
                    const option = document.createElement('option');
                    option.value = promo.code;
                    option.textContent = `${promo.code} - ${promo.name} (${promo.discount_text})`;
                    option.title = promo.desc || promo.name;
                    promoSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = '-- Không có mã khuyến mãi --';
                promoSelect.appendChild(option);
                promoSelect.disabled = true;
            }
        } catch (e) {
            console.error('Error loading promos:', e);
            promoSelect.innerHTML = '<option value="">-- Lỗi tải mã khuyến mãi --</option>';
            promoSelect.disabled = true;
        }
    }

    window.changeComboQty = function(comboId, delta) {
        const newQty = Math.max(0, state.combos[comboId] + delta);
        state.combos[comboId] = newQty;
        document.getElementById(`qty-${comboId}`).textContent = newQty;
        
        const comboItem = document.getElementById(`combo-${comboId}`);
        if (newQty > 0) {
            comboItem.classList.add('selected');
        } else {
            comboItem.classList.remove('selected');
        }
        
        updateSummary();
    };

    function updateSummary() {
        let seatTotal = 0;
        state.seats.forEach(code => {
            seatTotal += state.seatPrices[code] || 0;
        });

        let comboTotal = 0;
        state.comboData.forEach(combo => {
            comboTotal += combo.price * state.combos[combo.id];
        });

        const subtotal = seatTotal + comboTotal;
        const discount = state.promoValid ? state.promoDiscount : 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('sum-seat').textContent = formatCurrency(seatTotal);
        document.getElementById('sum-combo').textContent = formatCurrency(comboTotal);
        
        // Hiển thị dòng giảm giá nếu có
        if (state.promoValid && discount > 0) {
            discountRow.style.display = 'flex';
            sumDiscount.textContent = '- ' + formatCurrency(discount);
        } else {
            discountRow.style.display = 'none';
        }
        
        document.getElementById('sum-total').textContent = formatCurrency(total);

        btnNextStep3.disabled = state.seats.size === 0;
    }
    
    // Apply promo code
    btnApplyPromo.addEventListener('click', async () => {
        const code = promoSelect.value.trim();
        if (code === '' || code === '-- Chọn mã khuyến mãi --') {
            promoMessage.innerHTML = '<span style="color: #ef4444;">⚠️ Vui lòng chọn mã khuyến mãi</span>';
            return;
        }
        
        const originalTotal = calculateTotal();
        if (originalTotal === 0) {
            promoMessage.innerHTML = '<span style="color: #ef4444;">⚠️ Vui lòng chọn ghế trước</span>';
            return;
        }
        
        btnApplyPromo.disabled = true;
        btnApplyPromo.textContent = 'Đang kiểm tra...';
        promoMessage.innerHTML = '<span style="color: #6b7280;">Đang kiểm tra mã...</span>';
        
        try {
            const res = await fetch(`index.php?act=api_check_promo&code=${encodeURIComponent(code)}&price=${originalTotal}`);
            const data = await res.json();
            
            if (data.valid) {
                state.promoCode = code;
                state.promoDiscount = data.discount || 0;
                state.promoValid = true;
                
                promoMessage.innerHTML = `<span style="color: #10b981;">✓ ${data.message}</span>`;
                promoInfo.style.display = 'block';
                promoInfo.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>${data.promo_name}</strong>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">${data.promo_desc || ''}</div>
                        </div>
                        <button type="button" onclick="removePromo()" style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">Xóa</button>
                    </div>
                `;
                promoSelect.disabled = true;
                btnApplyPromo.disabled = true;
                
                updateSummary();
            } else {
                state.promoValid = false;
                state.promoDiscount = 0;
                promoMessage.innerHTML = `<span style="color: #ef4444;">✗ ${data.message}</span>`;
                promoInfo.style.display = 'none';
                updateSummary();
            }
        } catch (e) {
            console.error('Promo check error:', e);
            promoMessage.innerHTML = '<span style="color: #ef4444;">✗ Lỗi kiểm tra mã</span>';
        } finally {
            btnApplyPromo.disabled = false;
            btnApplyPromo.textContent = 'Áp dụng';
        }
    });
    
    // Remove promo code (global function)
    window.removePromo = function() {
        state.promoCode = '';
        state.promoDiscount = 0;
        state.promoValid = false;
        promoSelect.value = '';
        promoSelect.disabled = false;
        btnApplyPromo.disabled = false;
        promoMessage.innerHTML = '';
        promoInfo.style.display = 'none';
        updateSummary();
    };

    // Move to step 3
    btnNextStep3.addEventListener('click', () => {
        state.step = 3;
        updateStepUI(3);
        updateConfirmation();
    });

    function updateConfirmation() {
        document.getElementById('confirm-phim').textContent = state.phim.ten;
        document.getElementById('confirm-ngay').textContent = state.lc.ngay;
        document.getElementById('confirm-gio').textContent = state.tg.gio;
        document.getElementById('confirm-ghe').textContent = Array.from(state.seats).join(', ');
        document.getElementById('confirm-email').textContent = state.email;

        // Combo info
        const combos = [];
        state.comboData.forEach(combo => {
            if (state.combos[combo.id] > 0) {
                combos.push(`${combo.name} x${state.combos[combo.id]}`);
            }
        });
        
        if (combos.length > 0) {
            document.getElementById('confirm-combo-row').style.display = 'flex';
            document.getElementById('confirm-combo').textContent = combos.join(', ');
        } else {
            document.getElementById('confirm-combo-row').style.display = 'none';
        }
        
        // Promo info
        if (state.promoValid && state.promoCode) {
            document.getElementById('confirm-promo-row').style.display = 'flex';
            document.getElementById('confirm-promo').textContent = state.promoCode + ' (-' + formatCurrency(state.promoDiscount) + ')';
        } else {
            document.getElementById('confirm-promo-row').style.display = 'none';
        }

        // Calculate total with discount
        let total = 0;
        state.seats.forEach(code => {
            total += state.seatPrices[code] || 0;
        });
        state.comboData.forEach(combo => {
            total += combo.price * state.combos[combo.id];
        });
        
        if (state.promoValid) {
            total = Math.max(0, total - state.promoDiscount);
        }
        
        document.getElementById('confirm-total').textContent = formatCurrency(total);
    }

    function calculateTotal() {
        let total = 0;
        state.seats.forEach(code => {
            total += state.seatPrices[code] || 0;
        });
        state.comboData.forEach(combo => {
            total += combo.price * state.combos[combo.id];
        });
        return total;
    }

    // Back buttons
    btnBackStep1.addEventListener('click', () => {
        state.step = 1;
        updateStepUI(1);
    });

    btnBackStep2.addEventListener('click', () => {
        state.step = 2;
        updateStepUI(2);
    });

    // Confirm booking
    btnConfirm.addEventListener('click', async () => {
        btnConfirm.disabled = true;
        btnConfirm.textContent = 'Đang xử lý...';

        const combos = [];
        state.comboData.forEach(combo => {
            if (state.combos[combo.id] > 0) {
                combos.push(`${combo.name} x${state.combos[combo.id]}`);
            }
        });

        const formData = new FormData();
        formData.append('id_phim', state.phim.id);
        formData.append('id_lc', state.lc.id);
        formData.append('id_tg', state.tg.id);
        formData.append('email_kh', state.email);
        formData.append('ghe_csv', Array.from(state.seats).join(','));
        formData.append('combo_text', combos.join('; '));
        formData.append('price', calculateTotal());
        formData.append('promo_code', state.promoValid ? state.promoCode : ''); // Thêm mã khuyến mãi
        formData.append('datve_confirm', '1');

        try {
            const res = await fetch('index.php?act=nv_datve', {
                method: 'POST',
                body: formData
            });
            
            // Lấy response text trước để debug
            const text = await res.text();
            console.log('Response text:', text);
            
            // Parse JSON
            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                console.error('Response was:', text);
                alert('❌ Server trả về dữ liệu không đúng định dạng. Vui lòng kiểm tra console.');
                btnConfirm.disabled = false;
                btnConfirm.textContent = '✓ Xác nhận đặt vé';
                return;
            }
            
            if (data.success && data.ve_id) {
                // Hiển thị modal với 2 tùy chọn
                showSuccessModal(data.ve_id);
            } else {
                // Hiển thị lỗi cụ thể từ server
                alert('❌ ' + (data.error || 'Có lỗi xảy ra. Vui lòng thử lại.'));
                btnConfirm.disabled = false;
                btnConfirm.textContent = '✓ Xác nhận đặt vé';
            }
        } catch (e) {
            console.error('Fetch error:', e);
            alert('❌ Lỗi kết nối. Vui lòng thử lại.');
            btnConfirm.disabled = false;
            btnConfirm.textContent = '✓ Xác nhận đặt vé';
        }
    });

    // Show success modal
    function showSuccessModal(veId) {
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        `;
        
        modal.innerHTML = `
            <div style="
                background: #fff;
                border-radius: 16px;
                padding: 40px;
                max-width: 450px;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            ">
                <div style="font-size: 64px; margin-bottom: 20px;">✅</div>
                <h3 style="margin: 0 0 12px; font-size: 24px; color: #1f2937;">Đặt vé thành công!</h3>
                <p style="margin: 0 0 32px; color: #6b7280; font-size: 15px;">
                    Vé đã được tạo với mã #${veId}
                </p>
                
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button onclick="window.location.href='index.php?act=ctve&id=${veId}'" 
                            style="
                                padding: 14px 28px;
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                color: #fff;
                                border: none;
                                border-radius: 10px;
                                font-weight: 600;
                                font-size: 15px;
                                cursor: pointer;
                                transition: transform 0.2s;
                            "
                            onmouseover="this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                        🎫 Xem vé
                    </button>
                    
                    <button onclick="printTicket(${veId})" 
                            style="
                                padding: 14px 28px;
                                background: #fff;
                                color: #667eea;
                                border: 2px solid #667eea;
                                border-radius: 10px;
                                font-weight: 600;
                                font-size: 15px;
                                cursor: pointer;
                                transition: all 0.2s;
                            "
                            onmouseover="this.style.background='#f3f4f6'"
                            onmouseout="this.style.background='#fff'">
                        🖨️ In vé
                    </button>
                </div>
                
                <button onclick="window.location.href='index.php?act=nv_datve'" 
                        style="
                            margin-top: 20px;
                            padding: 10px 24px;
                            background: transparent;
                            color: #6b7280;
                            border: none;
                            font-size: 14px;
                            cursor: pointer;
                            text-decoration: underline;
                        ">
                    Đặt vé mới
                </button>
            </div>
        `;
        
        document.body.appendChild(modal);
    }
    
    // Print ticket function
    window.printTicket = async function(veId) {
        try {
            const res = await fetch(`index.php?act=ctve&id=${veId}`);
            const html = await res.text();
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
                window.location.href = 'index.php?act=nv_datve';
            }, 500);
        } catch (e) {
            alert('Lỗi khi tải vé. Vui lòng thử lại.');
        }
    };

    // Initialize
    checkStep1Complete();
})();
</script>
</div>
