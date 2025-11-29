<?php include __DIR__ . '/../home/sideheader.php'; ?>

<style>
    .content-body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .search-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .search-form {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input {
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
    }

    .search-result {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: none;
    }

    .search-result.show {
        display: block;
    }

    .result-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }

    .result-box {
        border: 2px solid #e5e7eb;
        padding: 20px;
        border-radius: 10px;
    }

    .result-box h3 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .result-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .result-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .result-label {
        font-weight: 600;
        color: #666;
        font-size: 13px;
    }

    .result-value {
        color: #333;
        font-size: 13px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 25px;
    }

    .btn-action {
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 16px;
        text-transform: uppercase;
    }

    .btn-hoan {
        background: #f59e0b;
        color: white;
    }

    .btn-hoan:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .btn-doi {
        background: #3b82f6;
        color: white;
    }

    .btn-doi:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-error {
        background: #fee2e2;
        border-color: #ef4444;
        color: #7f1d1d;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .modal-header h2 {
        margin: 0;
        color: #333;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
    }

    .close-btn:hover {
        color: #333;
    }

    .form-row {
        margin-bottom: 20px;
    }

    .form-row label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-row input,
    .form-row textarea,
    .form-row select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-row input:focus,
    .form-row textarea:focus,
    .form-row select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row textarea {
        resize: vertical;
        min-height: 80px;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-submit {
        flex: 1;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-submit:hover {
        opacity: 0.9;
    }

    .btn-cancel {
        flex: 1;
        padding: 12px;
        background: #e5e7eb;
        color: #333;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }

    @media (max-width: 768px) {
        .search-form {
            grid-template-columns: 1fr;
        }

        .result-header {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-body">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>📋 Quản Lý Đổi/Hoàn Vé</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Tìm vé để hoàn tiền hoặc đổi vé</p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <form class="search-form" id="searchForm">
            <div class="form-group">
                <label>🔍 Nhập ID Vé</label>
                <input type="number" id="veId" placeholder="VD: 382" required>
            </div>
            <button type="submit" class="btn btn-search">Tìm Kiếm</button>
        </form>
    </div>

    <!-- Search Result -->
    <div class="search-result" id="searchResult">
        <div id="resultContent"></div>
    </div>

    <!-- Alert Message -->
    <div id="alertMessage"></div>
</div>

<!-- Modal Hoàn Vé -->
<div class="modal" id="modalHoan">
    <div class="modal-content">
        <div class="modal-header">
            <h2>💰 Hoàn Vé</h2>
            <button class="close-btn" onclick="closeModal('modalHoan')">&times;</button>
        </div>
        <form id="formHoan" onsubmit="submitHoan(event)">
            <div class="form-row">
                <label>ID Vé</label>
                <input type="text" id="hoanVeId" readonly>
            </div>
            <div class="form-row">
                <label>Lý Do Hoàn</label>
                <textarea id="hoanLyDo" placeholder="Nhập lý do hoàn vé..." required></textarea>
            </div>
            <div class="form-row">
                <label>Số Tiền Hoàn (VND)</label>
                <input type="number" id="hoanSoTien" placeholder="VD: 100000" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-submit">✓ Xác Nhận Hoàn</button>
                <button type="button" class="btn-cancel" onclick="closeModal('modalHoan')">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Đổi Vé -->
<div class="modal" id="modalDoi">
    <div class="modal-content">
        <div class="modal-header">
            <h2>🔄 Đổi Vé</h2>
            <button class="close-btn" onclick="closeModal('modalDoi')">&times;</button>
        </div>
        <form id="formDoi" onsubmit="submitDoi(event)">
            <div class="form-row">
                <label>ID Vé Cũ</label>
                <input type="text" id="doiVeCuId" readonly>
            </div>
            <div class="form-row">
                <label>Lý Do Đổi</label>
                <textarea id="doiLyDo" placeholder="Nhập lý do đổi vé..." required></textarea>
            </div>
            <div class="form-row">
                <label>Chọn Vé Mới (ID)</label>
                <input type="number" id="doiVeMoiId" placeholder="Nhập ID vé mới" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-submit">✓ Xác Nhận Đổi</button>
                <button type="button" class="btn-cancel" onclick="closeModal('modalDoi')">Hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentVe = null;

    document.getElementById('searchForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const veId = document.getElementById('veId').value;
        
        try {
            const response = await fetch(`index.php?act=ajax_get_ve&id=${veId}`);
            const data = await response.json();
            
            if (data.success) {
                currentVe = data.ve;
                displayVeInfo(data.ve);
                document.getElementById('searchResult').classList.add('show');
                document.getElementById('alertMessage').innerHTML = '';
            } else {
                showAlert(data.message || 'Không tìm thấy vé', 'error');
            }
        } catch (error) {
            showAlert('Lỗi tìm kiếm: ' + error.message, 'error');
        }
    });

    function displayVeInfo(ve) {
        const html = `
            <div class="result-header">
                <div class="result-box">
                    <h3>🎬 Thông Tin Vé</h3>
                    <div class="result-item">
                        <span class="result-label">ID Vé</span>
                        <span class="result-value">#${ve.id}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Phim</span>
                        <span class="result-value">${ve.ten_phim || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Ghế</span>
                        <span class="result-value">${ve.ghe || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Giá Vé</span>
                        <span class="result-value">${ve.price || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Ngày Đặt</span>
                        <span class="result-value">${formatDate(ve.ngay_dat)}</span>
                    </div>
                </div>
                <div class="result-box">
                    <h3>👤 Thông Tin Khách</h3>
                    <div class="result-item">
                        <span class="result-label">Tên</span>
                        <span class="result-value">${ve.ten_khach || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Email</span>
                        <span class="result-value">${ve.email || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Điện Thoại</span>
                        <span class="result-value">${ve.phone || 'N/A'}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Trạng Thái</span>
                        <span class="result-value">${ve.trang_thai === 0 ? '✓ Chưa xử dụng' : '✓ Đã sử dụng'}</span>
                    </div>
                </div>
            </div>
            <div class="action-buttons">
                <button type="button" class="btn-action btn-hoan" onclick="openHoanModal()">💰 Hoàn Vé</button>
                <button type="button" class="btn-action btn-doi" onclick="openDoiModal()">🔄 Đổi Vé</button>
            </div>
        `;
        document.getElementById('resultContent').innerHTML = html;
    }

    function formatDate(date) {
        if (!date) return 'N/A';
        return new Date(date).toLocaleDateString('vi-VN', { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function openHoanModal() {
        if (!currentVe) return;
        document.getElementById('hoanVeId').value = currentVe.id;
        document.getElementById('hoanSoTien').value = currentVe.price;
        document.getElementById('modalHoan').classList.add('show');
    }

    function openDoiModal() {
        if (!currentVe) return;
        document.getElementById('doiVeCuId').value = currentVe.id;
        document.getElementById('modalDoi').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    async function submitHoan(event) {
        event.preventDefault();
        const form = new FormData();
        form.append('act', 'tao_don_hoan');
        form.append('id_ve', document.getElementById('hoanVeId').value);
        form.append('ly_do', document.getElementById('hoanLyDo').value);
        form.append('so_tien', document.getElementById('hoanSoTien').value);

        try {
            const response = await fetch('index.php', { method: 'POST', body: form });
            const text = await response.text();
            
            if (text.includes('success')) {
                showAlert('✓ Tạo yêu cầu hoàn vé thành công!', 'success');
                closeModal('modalHoan');
                document.getElementById('formHoan').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Lỗi: ' + text, 'error');
            }
        } catch (error) {
            showAlert('Lỗi: ' + error.message, 'error');
        }
    }

    async function submitDoi(event) {
        event.preventDefault();
        const form = new FormData();
        form.append('act', 'tao_don_doi');
        form.append('id_ve', document.getElementById('doiVeCuId').value);
        form.append('ly_do', document.getElementById('doiLyDo').value);
        form.append('id_ve_moi', document.getElementById('doiVeMoiId').value);

        try {
            const response = await fetch('index.php', { method: 'POST', body: form });
            const text = await response.text();
            
            if (text.includes('success')) {
                showAlert('✓ Tạo yêu cầu đổi vé thành công!', 'success');
                closeModal('modalDoi');
                document.getElementById('formDoi').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Lỗi: ' + text, 'error');
            }
        } catch (error) {
            showAlert('Lỗi: ' + error.message, 'error');
        }
    }

    function showAlert(message, type) {
        const alertDiv = document.getElementById('alertMessage');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        alertDiv.style.display = 'block';
        
        if (type === 'success') {
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 3000);
        }
    }

    // Đóng modal khi click ngoài
    window.onclick = function(event) {
        const modalHoan = document.getElementById('modalHoan');
        const modalDoi = document.getElementById('modalDoi');
        
        if (event.target === modalHoan) {
            closeModal('modalHoan');
        }
        if (event.target === modalDoi) {
            closeModal('modalDoi');
        }
    }
</script>

