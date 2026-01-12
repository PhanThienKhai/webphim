<?php include __DIR__ . '/../home/sideheader.php'; ?>

<style>
    /* Gradient Header */
    .cum-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .cum-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .cum-header-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .cum-header-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Statistics */
    .cum-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 10px 0;
    }

    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }

    /* Grid Layout */
    .cum-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .cum-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
    }

    .cum-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
        border-color: #667eea;
    }

    .cum-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .cum-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .cum-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .cum-info {
        margin-bottom: 12px;
    }

    .cum-info-label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
    }

    .cum-info-value {
        color: #1f2937;
        font-size: 14px;
        margin-top: 4px;
        word-break: break-all;
    }

    .cum-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
    }

    .cum-action-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .cum-action-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .cum-action-edit:hover {
        background: #bfdbfe;
    }

    .cum-action-delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    .cum-action-delete:hover {
        background: #fecaca;
    }

    /* Modal */
    #cumModal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    #cumModal.active {
        display: flex !important;
    }

    .cum-modal-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 500px;
        padding: 0;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .cum-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cum-modal-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .cum-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
    }

    .cum-modal-close:hover {
        opacity: 0.8;
    }

    .cum-modal-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .cum-modal-footer {
        padding: 15px 25px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-submit, .btn-cancel {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }

    .message {
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 4px;
        display: none;
    }

    .message.success {
        background: #d1fae5;
        color: #065f46;
        display: block;
    }

    .message.error {
        background: #fee2e2;
        color: #b91c1c;
        display: block;
    }
</style>

<div class="content-body">
    <div class="cum-header">
        <div>
            <h3>🏢 Quản lý Cụm Rạp</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Quản lý tất cả quản lý cụm trong hệ thống</p>
        </div>
        <button class="cum-header-btn" onclick="openAddModal()">➕ Thêm mới</button>
    </div>

    <!-- Statistics -->
    <div class="cum-stats">
        <div class="stat-box">
            <div class="stat-label">Tổng Quản Lý Cụm</div>
            <div class="stat-number"><?= count($ds_qllc ?? []) ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Đang Hoạt Động</div>
            <div class="stat-number"><?= count(array_filter($ds_qllc ?? [], fn($u) => true)) ?></div>
        </div>
    </div>

    <div id="messageContainer" class="message"></div>

    <!-- Grid Layout -->
    <div class="cum-grid">
        <?php foreach (($ds_qllc ?? []) as $u): ?>
            <div class="cum-card" data-id="<?= (int)$u['id'] ?>">
                <div class="cum-card-header">
                    <h4 class="cum-card-title"><?= htmlspecialchars($u['name']) ?></h4>
                    <span class="cum-badge">#<?= (int)$u['id'] ?></span>
                </div>

                <div class="cum-info">
                    <div class="cum-info-label">👤 Tài Khoản</div>
                    <div class="cum-info-value"><?= htmlspecialchars($u['user']) ?></div>
                </div>

                <div class="cum-info">
                    <div class="cum-info-label">📧 Email</div>
                    <div class="cum-info-value"><?= htmlspecialchars($u['email']) ?></div>
                </div>

                <div class="cum-info">
                    <div class="cum-info-label">📅 Ngày tạo</div>
                    <div class="cum-info-value"><?= htmlspecialchars($u['ngay_tao'] ?? 'N/A') ?></div>
                </div>

                <div class="cum-actions">
                    <button class="cum-action-btn cum-action-edit" onclick="editCum(<?= (int)$u['id'] ?>)">✏️ Sửa</button>
                    <button class="cum-action-btn cum-action-delete" onclick="deleteCum(<?= (int)$u['id'] ?>, '<?= htmlspecialchars($u['name']) ?>')">🗑️ Xóa</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div id="cumModal">
    <div class="cum-modal-content">
        <div class="cum-modal-header">
            <h2 id="modalTitle">Thêm Quản Lý Cụm Mới</h2>
            <button class="cum-modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="cum-modal-body">
            <form id="cumForm" method="POST" action="index.php?act=suatk">
                <input type="hidden" id="cumId" name="cumId">
                <input type="hidden" name="cumIdAction" id="cumIdAction">

                <div class="form-group">
                    <label for="cumName">Tên Quản Lý Cụm <span style="color: red;">*</span></label>
                    <input type="text" id="cumName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="cumUser">Tài Khoản <span style="color: red;">*</span></label>
                    <input type="text" id="cumUser" name="user" required>
                </div>

                <div class="form-group">
                    <label for="cumPass">Mật Khẩu <span style="color: red;">*</span></label>
                    <input type="password" id="cumPass" name="pass" required>
                </div>

                <div class="form-group">
                    <label for="cumEmail">Email <span style="color: red;">*</span></label>
                    <input type="email" id="cumEmail" name="email" required>
                </div>

                <div class="form-group">
                    <label for="cumPhone">Điện Thoại</label>
                    <input type="text" id="cumPhone" name="phone">
                </div>

                <div class="form-group">
                    <label for="cumAddress">Địa Chỉ</label>
                    <input type="text" id="cumAddress" name="dia_chi">
                </div>
            </form>
        </div>
        <div class="cum-modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Hủy</button>
            <button class="btn-submit" onclick="submitForm()">Lưu</button>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Thêm Quản Lý Cụm Mới';
        document.getElementById('cumForm').reset();
        document.getElementById('cumId').value = '';
        document.getElementById('cumPass').required = true;
        document.getElementById('cumModal').classList.add('active');
    }

    function editCum(id) {
        const card = document.querySelector(`.cum-card[data-id="${id}"]`);
        if (!card) return;

        document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Quản Lý Cụm';
        document.getElementById('cumId').value = id;
        document.getElementById('cumIdAction').value = id;
        document.getElementById('cumName').value = card.querySelector('.cum-card-title').textContent;
        document.getElementById('cumUser').value = card.querySelectorAll('.cum-info-value')[0].textContent;
        document.getElementById('cumEmail').value = card.querySelectorAll('.cum-info-value')[1].textContent;
        document.getElementById('cumPass').required = false;
        document.getElementById('cumPhone').value = '';
        document.getElementById('cumAddress').value = '';
        
        document.getElementById('cumModal').classList.add('active');
    }

    function deleteCum(id, name) {
        if (confirm(`Bạn chắc chắn muốn xóa "${name}"?`)) {
            window.location.href = `index.php?act=xoatk&idxoa=${id}`;
        }
    }

    function closeModal() {
        document.getElementById('cumModal').classList.remove('active');
    }

    function submitForm() {
        const form = document.getElementById('cumForm');
        const cumId = document.getElementById('cumId').value;

        if (cumId) {
            form.action = `index.php?act=suatk&idsua=${cumId}`;
        } else {
            form.action = 'index.php?act=quanly_cum';
        }

        form.submit();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('cumModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>

