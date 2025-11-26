 <?php include __DIR__ . '/../home/sideheader.php'; ?>

<style>
    .equipment-header {
        background: linear-gradient(135deg, #a7b1deff 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .equipment-header h3 {
        margin: 0;
        font-size: 26px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .equipment-header .emoji {
        font-size: 28px;
    }
    
    .equipment-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .stat-box {
        background: rgba(255, 255, 255, 0.15);
        padding: 12px 15px;
        border-radius: 6px;
        font-size: 13px;
        backdrop-filter: blur(10px);
    }
    
    .stat-box strong {
        display: block;
        font-size: 18px;
        margin-top: 5px;
    }
    
    .room-selector {
        margin-bottom: 25px;
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    
    .room-selector select {
        flex: 1;
        max-width: 300px;
    }
    
    .room-selector label {
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }
    
    .add-equipment-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    
    .add-equipment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
    }
    
    .equipment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .equipment-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease;
    }
    
    .equipment-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
        transform: translateY(-4px);
    }
    
    .equipment-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .equipment-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        flex: 1;
        word-break: break-word;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        text-align: center;
    }
    
    .status-tot {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-can_bao_tri {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-hong {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .equipment-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 15px 0;
        font-size: 14px;
    }
    
    .info-item {
        background: #f8f9fb;
        padding: 10px;
        border-radius: 4px;
    }
    
    .info-label {
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 3px;
    }
    
    .info-value {
        color: #1f2937;
        font-weight: 500;
        font-size: 15px;
    }
    
    .equipment-note {
        background: #fffacd;
        border-left: 3px solid #fbbf24;
        padding: 10px;
        border-radius: 4px;
        font-size: 13px;
        color: #664d03;
        margin: 10px 0;
    }
    
    .equipment-actions {
        display: flex;
        gap: 8px;
        margin-top: 15px;
    }
    
    .btn-edit, .btn-delete {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-edit {
        background: #e0e7ff;
        color: #4c51bf;
    }
    
    .btn-edit:hover {
        background: #c7d2fe;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-delete:hover {
        background: #fca5a5;
    }
    
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #9ca3af;
    }
    
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .empty-state-text {
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    /* Modal Styles - Custom for Equipment Modal */
    #equipmentModal {
        display: none !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        padding: 20px;
        box-sizing: border-box;
    }
    
    #equipmentModal.active {
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    
    #equipmentModal .modal {
        background: white;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        padding: 0;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        max-height: 90vh;
        overflow-y: auto;
        flex-shrink: 0;
        display: flex !important;
        flex-direction: column;
        left: 600px;
        top: 178px;
    }
    
    #equipmentModal .modal-header {
        background: linear-gradient(135deg, #a5afd8ff 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    
    #equipmentModal .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    #equipmentModal .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #equipmentModal .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }
    
    #equipmentModal .modal-body {
        padding: 20px;
        flex: 1;
        overflow-y: auto;
    }
    
    #equipmentModal .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-shrink: 0;
    }
    
    #equipmentModal .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    #equipmentModal .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    #equipmentModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    
    #equipmentModal .btn-secondary {
        background: #e5e7eb;
        color: #333;
    }
    
    #equipmentModal .btn-secondary:hover {
        background: #d1d5db;
    }
    
    .form-group {
        margin-bottom: 18px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #1f2937;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #aab6e9ff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .form-row.full {
        grid-template-columns: 1fr;
    }
    
    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-cancel {
        background: #e5e7eb;
        color: #374151;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
    }
    
    .btn-cancel:hover {
        background: #d1d5db;
    }
    
    .success-message {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 15px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        animation: slideDown 0.3s ease;
        z-index: 100000 !important;
        max-width: 300px;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .filter-controls {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
    }
    
    .filter-btn {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    
    .filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .filter-btn:hover {
        border-color: #667eea;
    }
</style>

<div class="content-body">
    <!-- Header -->
    <div class="equipment-header">
        <h3><span class="emoji"></span>Quản lý thiết bị phòng chiếu</h3>
    </div>

    <!-- Room Selector -->
    <form method="get" action="index.php" id="roomForm">
        <input type="hidden" name="act" value="thietbiphong" />
        <div class="room-selector">
            <div style="flex: 1; max-width: 300px;">
                <label for="id_phong">Chọn phòng chiếu</label>
                <select class="form-control" id="id_phong" name="id_phong" onchange="updateModalPhong(); document.getElementById('roomForm').submit()">
                    <option value="">-- Chọn phòng --</option>
                    <?php foreach (($ds_phong ?? []) as $p): ?>
                        <option value="<?= (int)$p['id'] ?>" <?= isset($id_phong) && $id_phong == $p['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (!empty($id_phong)): ?>
                <button type="button" class="add-equipment-btn" onclick="openAddEquipmentModal()">
                    Thêm thiết bị
                </button>
            <?php endif; ?>
        </div>
    </form>

    <?php if (!empty($id_phong)): ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Stats Section -->
        <?php
            $total_items = 0;
            $status_counts = ['tot' => 0, 'can_bao_tri' => 0, 'hong' => 0];
            foreach (($ds_tb ?? []) as $tb) {
                $total_items += $tb['so_luong'];
                $status_counts[$tb['tinh_trang']]++;
            }
        ?>
        <div class="equipment-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); margin-top: 20px;">
            <div class="equipment-stats">
                <div class="stat-box">
                    <span>Tổng thiết bị</span>
                    <strong><?= count($ds_tb ?? []) ?></strong>
                </div>
                <div class="stat-box">
                    <span>Tổng số lượng</span>
                    <strong><?= $total_items ?></strong>
                </div>
                <div class="stat-box">
                    <span>Tốt</span>
                    <strong><?= $status_counts['tot'] ?></strong>
                </div>
                <div class="stat-box">
                    <span>Cần bảo trì</span>
                    <strong><?= $status_counts['can_bao_tri'] ?></strong>
                </div>
                <div class="stat-box">
                    <span>Hỏng</span>
                    <strong><?= $status_counts['hong'] ?></strong>
                </div>
            </div>
        </div>

        <!-- Equipment Display -->
        <?php if (empty($ds_tb)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🚫</div>
                <div class="empty-state-text">Chưa có thiết bị nào</div>
                <small>Hãy thêm thiết bị cho phòng này</small>
            </div>
        <?php else: ?>
            <!-- Filter Controls -->
            <div class="filter-controls">
                <button type="button" class="filter-btn active" onclick="filterEquipment('all')">Tất cả</button>
                <button type="button" class="filter-btn" onclick="filterEquipment('tot')">Tốt</button>
                <button type="button" class="filter-btn" onclick="filterEquipment('can_bao_tri')">Cần bảo trì</button>
                <button type="button" class="filter-btn" onclick="filterEquipment('hong')">Hỏng</button>
            </div>

            <!-- Equipment Grid -->
            <div class="equipment-grid" id="equipmentGrid">
                <?php foreach (($ds_tb ?? []) as $tb): ?>
                    <div class="equipment-card" data-status="<?= htmlspecialchars($tb['tinh_trang']) ?>">
                        <div class="equipment-card-header">
                            <div class="equipment-name"><?= htmlspecialchars($tb['ten_thiet_bi']) ?></div>
                            <span class="status-badge status-<?= htmlspecialchars($tb['tinh_trang']) ?>">
                                <?php
                                    $status_text = [
                                        'tot' => 'Tốt',
                                        'can_bao_tri' => 'Bảo trì',
                                        'hong' => 'Hỏng'
                                    ];
                                    echo $status_text[$tb['tinh_trang']] ?? $tb['tinh_trang'];
                                ?>
                            </span>
                        </div>
                        
                        <div class="equipment-info">
                            <div class="info-item">
                                <div class="info-label">Số lượng</div>
                                <div class="info-value"><?= (int)$tb['so_luong'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">ID</div>
                                <div class="info-value">#<?= (int)$tb['id'] ?></div>
                            </div>
                        </div>
                        
                        <?php if (!empty($tb['ghi_chu'])): ?>
                            <div class="equipment-note">
                                <?= htmlspecialchars($tb['ghi_chu']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="equipment-actions">
                            <button class="btn-edit" onclick="editEquipment(<?= (int)$tb['id'] ?>, '<?= htmlspecialchars($tb['ten_thiet_bi'], ENT_QUOTES) ?>', <?= (int)$tb['so_luong'] ?>, '<?= htmlspecialchars($tb['tinh_trang'], ENT_QUOTES) ?>', '<?= htmlspecialchars($tb['ghi_chu'] ?? '', ENT_QUOTES) ?>')">
                                Sửa
                            </button>
                            <button class="btn-delete" onclick="deleteEquipment(<?= (int)$id_phong ?>, <?= (int)$tb['id'] ?>, '<?= htmlspecialchars($tb['ten_thiet_bi'], ENT_QUOTES) ?>')">
                                Xóa
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    // ===== MODAL FUNCTIONS =====
    
    function openAddEquipmentModal() {
        const idPhong = document.getElementById('id_phong').value;
        console.log('🔍 openAddEquipmentModal called, id_phong:', idPhong);
        
        if (!idPhong) {
            alert('Vui lòng chọn phòng trước');
            return;
        }
        
        const modal = document.getElementById('equipmentModal');
        console.log('🔍 Modal element:', modal);
        console.log('🔍 Modal current display:', window.getComputedStyle(modal).display);
        
        document.getElementById('modalIdPhong').value = idPhong;
        document.getElementById('modalTitle').textContent = '➕ Thêm thiết bị';
        document.getElementById('modalEquipmentId').value = '0';
        document.getElementById('equipmentForm').reset();
        document.getElementById('modalSoLuong').value = '1';
        document.getElementById('modalTinhTrang').value = 'tot';
        document.getElementById('submitBtn').textContent = 'Thêm thiết bị';
        
        modal.classList.add('active');
        console.log('After adding active class:', modal.className);
        console.log('Modal computed display after active:', window.getComputedStyle(modal).display);
        
        document.body.style.overflow = 'hidden';
        console.log('Modal should be visible now');
    }
    
    function closeEquipmentModal() {
        document.getElementById('equipmentModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    function editEquipment(id, ten, soLuong, tinhTrang, ghiChu) {
        const idPhong = document.getElementById('id_phong').value;
        document.getElementById('modalIdPhong').value = idPhong;
        document.getElementById('modalTitle').textContent = '✏️ Chỉnh sửa thiết bị';
        document.getElementById('modalEquipmentId').value = id;
        document.getElementById('modalTen').value = ten;
        document.getElementById('modalSoLuong').value = soLuong;
        document.getElementById('modalTinhTrang').value = tinhTrang;
        document.getElementById('modalGhiChu').value = ghiChu;
        document.getElementById('submitBtn').textContent = 'Cập nhật thiết bị';
        document.getElementById('equipmentModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // ===== FORM SUBMISSION =====
    
    function submitEquipmentForm(event) {
        event.preventDefault();
        
        const idPhong = parseInt(document.getElementById('modalIdPhong').value);
        const equipmentId = parseInt(document.getElementById('modalEquipmentId').value);
        const ten = document.getElementById('modalTen').value.trim();
        const soLuong = parseInt(document.getElementById('modalSoLuong').value) || 1;
        const tinhTrang = document.getElementById('modalTinhTrang').value;
        const ghiChu = document.getElementById('modalGhiChu').value.trim();
        
        if (!ten) {
            alert('Vui lòng nhập tên thiết bị');
            return;
        }
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang lưu...';
        
        const formData = new FormData();
        formData.append('act', equipmentId ? 'ajax_sua_thiet_bi' : 'ajax_them_thiet_bi');
        formData.append('id_phong', idPhong);
        formData.append('ten', ten);
        formData.append('so_luong', soLuong);
        formData.append('tinh_trang', tinhTrang);
        formData.append('ghi_chu', ghiChu);
        
        if (equipmentId) {
            formData.append('id', equipmentId);
        }
        
        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEquipmentModal();
                showSuccessMessage(data.message || 'Đã lưu thành công');
                
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể lưu'));
                submitBtn.disabled = false;
                submitBtn.textContent = document.getElementById('modalEquipmentId').value ? 'Cập nhật thiết bị' : 'Thêm thiết bị';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi kết nối');
            submitBtn.disabled = false;
            submitBtn.textContent = document.getElementById('modalEquipmentId').value ? 'Cập nhật thiết bị' : 'Thêm thiết bị';
        });
    }
    
    // ===== DELETE FUNCTION =====
    
    function deleteEquipment(idPhong, equipmentId, equipmentName) {
        if (!confirm(`Bạn có chắc chắn muốn xóa "${equipmentName}" không?`)) {
            return;
        }
        
        const formData = new FormData();
        formData.append('act', 'ajax_xoa_thiet_bi');
        formData.append('id_phong', idPhong);
        formData.append('id', equipmentId);
        
        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Đã xóa thành công');
                
                // Update stats
                setTimeout(() => location.reload(), 300);
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể xóa'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi kết nối');
        });
    }
    
    // ===== UTILITY FUNCTIONS =====
    
    function updateModalPhong() {
        const idPhong = document.getElementById('id_phong').value;
        document.getElementById('modalIdPhong').value = idPhong;
    }
    
    function showSuccessMessage(message) {
        const msg = document.createElement('div');
        msg.className = 'success-message';
        msg.textContent = ' ' + message;
        document.body.appendChild(msg);
        
        setTimeout(() => {
            msg.style.animation = 'slideDown 0.3s ease reverse';
            setTimeout(() => msg.remove(), 300);
        }, 3000);
    }
    
    function filterEquipment(status) {
        const cards = document.querySelectorAll('.equipment-card');
        
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Filter cards
        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Close modal on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('equipmentModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEquipmentModal();
                }
            });
        }
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEquipmentModal();
        }
    });
</script>

<!-- Add/Edit Equipment Modal -->
<div id="equipmentModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h4 id="modalTitle">Thêm thiết bị</h4>
            <button class="modal-close" onclick="closeEquipmentModal()">✕</button>
        </div>
        <div class="modal-body">
            <form id="equipmentForm" onsubmit="submitEquipmentForm(event)">
                <input type="hidden" id="modalIdPhong" value="">
                <input type="hidden" id="modalEquipmentId" value="0">
                
                <div class="form-group">
                    <label for="modalTen">Tên thiết bị <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="modalTen" class="form-control" placeholder="Ví dụ: Máy chiếu, Bệ ghế, Loa..." required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="modalSoLuong">Số lượng</label>
                        <input type="number" id="modalSoLuong" class="form-control" value="1" min="1" max="9999">
                    </div>
                    <div class="form-group">
                        <label for="modalTinhTrang">Tình trạng <span style="color: #ef4444;">*</span></label>
                        <select id="modalTinhTrang" class="form-control" required>
                            <option value="tot">Tốt</option>
                            <option value="can_bao_tri">Cần bảo trì</option>
                            <option value="hong">Hỏng</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="modalGhiChu">Ghi chú</label>
                    <textarea id="modalGhiChu" class="form-control" placeholder="Ghi chú thêm về thiết bị..." rows="3" style="resize: vertical;"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeEquipmentModal()">Hủy</button>
            <button type="button" class="btn-primary" id="submitBtn" onclick="submitEquipmentForm(event)">Lưu thiết bị</button>
        </div>
    </div>
</div>