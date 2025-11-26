<?php 
include "./view/home/sideheader.php";
$bghi = 2;
$sotrang = ceil($tong / $bghi);
?>

<style>
    .feedback-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .feedback-header h3 {
        margin: 0;
        font-size: 26px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .feedback-stats {
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

    .feedback-grid {
        display: grid;
        gap: 15px;
    }

    .feedback-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .feedback-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .feedback-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .feedback-user {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        flex: 1;
    }

    .feedback-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
        flex-shrink: 0;
    }

    .feedback-user-info h4 {
        margin: 0;
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
    }

    .feedback-movie {
        font-size: 12px;
        color: #6b7280;
        margin: 2px 0;
    }

    .feedback-date {
        font-size: 11px;
        color: #9ca3af;
    }

    .feedback-content {
        background: #f9fafb;
        padding: 8px 10px;
        border-radius: 4px;
        margin: 6px 0;
        border-left: 2px solid #667eea;
        font-size: 13px;
        color: #374151;
        line-height: 1.5;
        margin-left: 40px;
    }

    .feedback-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
    }

    .btn-reply {
        background: #dbeafe;
        color: #0c4a6e;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-reply:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #fca5a5;
    }

    /* Reply Section */
    .reply-section {
        display: none;
        margin-top: 8px;
        margin-left: 40px;
        padding: 8px 10px;
        background: #f0f9ff;
        border-radius: 4px;
        border-left: 2px solid #0c4a6e;
        animation: slideIn 0.3s ease forwards;
    }

    .reply-section.active {
        display: block !important;
    }

    .reply-form {
        display: flex;
        gap: 8px;
    }

    .reply-input {
        flex: 1;
        padding: 6px 8px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        font-size: 12px;
        resize: none;
        max-height: 60px;
    }

    .reply-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }

    .btn-send {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-send:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .reply-list {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #e0e7ff;
    }

    .reply-item {
        background: white;
        padding: 8px 10px;
        border-radius: 4px;
        margin-bottom: 6px;
        font-size: 12px;
        border-left: 2px solid #10b981;
    }

    .reply-author {
        font-weight: 600;
        color: #059669;
        margin-bottom: 2px;
    }

    .reply-time {
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .reply-text {
        color: #374151;
        line-height: 1.4;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        gap: 5px;
    }

    .pagination-container .page-link {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        color: #667eea;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .pagination-container .page-link:hover {
        background: #667eea;
        color: white;
    }

    .pagination-container .page-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
        }
        to {
            opacity: 1;
            max-height: 500px;
            overflow: visible;
        }
    }
</style>

<!-- Content Body Start -->
<div class="content-body">
    <!-- Feedback Header -->
    <div class="feedback-header">
        <h3>💬 Quản Lý Bình Luận & Feedback</h3>
        <div class="feedback-stats">
            <div class="stat-box">
                <span>Tổng bình luận</span>
                <strong><?= $tong ?></strong>
            </div>
            <div class="stat-box">
                <span>Hiển thị</span>
                <strong><?= count($listbl) ?></strong>
            </div>
            <div class="stat-box">
                <span>Trang</span>
                <strong><?= $sotrang ?></strong>
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="feedback-grid">
        <?php if (!empty($listbl)): ?>
            <?php foreach ($listbl as $bl): 
                extract($bl);
                $xoabl = "index.php?act=xoabl&id=$id";
                $userInitial = strtoupper(substr($name ?? 'U', 0, 1));
                $replies = load_replies_binhluan($id);
            ?>
                <div class="feedback-card">
                    <div class="feedback-header-row">
                        <div class="feedback-user">
                            <div class="feedback-avatar"><?= $userInitial ?></div>
                            <div>
                                <h4><?= htmlspecialchars($name ?? 'Ẩn danh') ?></h4>
                                <div class="feedback-movie">🎬 <?= htmlspecialchars($tieu_de ?? 'N/A') ?></div>
                                <div class="feedback-date">📅 <?= date('d/m/Y H:i', strtotime($ngaybinhluan ?? 'now')) ?></div>
                            </div>
                        </div>
                        <div class="feedback-actions">
                            <button class="btn-reply" onclick="toggleReplyForm(this)">💬 Trả lời</button>
                            <a href="<?= $xoabl ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?')">
                                <button class="btn-delete">🗑️ Xóa</button>
                            </a>
                        </div>
                    </div>
                    <div class="feedback-content">
                        <?= nl2br(htmlspecialchars($noidung ?? '')) ?>
                    </div>

                    <!-- Reply Section -->
                    <div class="reply-section">
                        <form class="reply-form" onsubmit="submitReply(event, <?= $id ?>)">
                            <textarea class="reply-input" placeholder="Nhập trả lời..." rows="2" required></textarea>
                            <button type="submit" class="btn-send">Gửi</button>
                        </form>
                        <div class="reply-list" id="reply-list-<?= $id ?>">
                            <?php if (!empty($replies)): ?>
                                <?php foreach ($replies as $reply): ?>
                                    <div class="reply-item">
                                        <div class="reply-author"><?= htmlspecialchars($reply['name'] ?? 'Ẩn danh') ?></div>
                                        <div class="reply-time">🕐 <?= date('d/m/Y H:i', strtotime($reply['ngay_tao'])) ?></div>
                                        <div class="reply-text"><?= nl2br(htmlspecialchars($reply['noidung'])) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="font-size: 12px; color: #9ca3af; text-align: center; padding: 8px;">Chưa có trả lời</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Chưa có bình luận nào</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($sotrang > 1): ?>
        <div class="pagination-container">
            <?php for($i = 1; $i <= $sotrang; $i++): ?>
                <a href="index.php?act=QLfeed&sotrang=<?= $i ?>" class="page-link <?= ($_GET['sotrang'] ?? 1) == $i ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
<!-- Content Body End -->

<script>
function toggleReplyForm(button) {
    const card = button.closest('.feedback-card');
    const replySection = card.querySelector('.reply-section');
    
    console.log('Toggle clicked', replySection.classList.contains('active'));
    
    if (!replySection.classList.contains('active')) {
        replySection.classList.add('active');
        button.textContent = '✕ Đóng';
        button.style.background = '#93c5fd';
    } else {
        replySection.classList.remove('active');
        button.textContent = '💬 Trả lời';
        button.style.background = '#dbeafe';
    }
}

function submitReply(event, feedbackId) {
    event.preventDefault();
    
    const form = event.target;
    const textarea = form.querySelector('.reply-input');
    const replyText = textarea.value.trim();
    
    if (!replyText) {
        alert('Vui lòng nhập nội dung trả lời');
        return;
    }
    
    if (replyText.length > 1000) {
        alert('Nội dung quá dài (tối đa 1000 ký tự)');
        return;
    }
    
    const formData = new FormData();
    formData.append('act', 'ajax_them_tra_loi');
    formData.append('id_binhluan', feedbackId);
    formData.append('noidung', replyText);
    
    // Disable button while submitting
    const submitBtn = form.querySelector('.btn-send');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Đang gửi...';
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 15px 20px; border-radius: 6px; font-weight: 500; z-index: 9999;';
            successMsg.textContent = '✅ Trả lời đã được gửi thành công!';
            document.body.appendChild(successMsg);
            
            // Reload after 1 second
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể gửi trả lời'));
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}
</script>
                
       