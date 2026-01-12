<?php
session_start();
require "../model/pdo.php";
require "../model/binhluan.php";
$id_phim = $_REQUEST['id_phim'];
date_default_timezone_set("Asia/Ho_Chi_Minh");

if (isset($_POST['guibinhluan']) && ($_POST['guibinhluan'])) {
    $noidung = $_POST['noi_dung'];
    $id_user = $_SESSION['user']['id'];
    $id_phim = $_POST['id_phim'];
    $timebl = date('h:i:a d-m-Y');
    binh_luan_insert($noidung, $id_user, $id_phim, $timebl);
    header("location:" . $_SERVER['HTTP_REFERER']);
}

// Handle AJAX reply submission
if (isset($_POST['act']) && $_POST['act'] === 'submit_reply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    
    $id_binhluan = (int)($_POST['id_binhluan'] ?? 0);
    $noidung = trim($_POST['noidung'] ?? '');
    $id_user = (int)($_SESSION['user']['id'] ?? 0);
    
    if (!$id_binhluan || !$noidung) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    try {
        $sql = "INSERT INTO tra_loi_binhluan (id_binhluan, id_user, noidung, ngay_tao) 
                VALUES ('$id_binhluan', '$id_user', '$noidung', NOW())";
        pdo_execute($sql);
        echo json_encode(['success' => true, 'message' => 'Trả lời thành công']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

$dem_bl = dem_bl($id_phim);
$listbl = binh_luan_select_all($id_phim);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bình luận</title>
    <style>
        .comment-section {
            max-width: 800px;
            margin: 30px auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .comment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .comment-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .comment-count {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        /* Comment Form */
        .comment-form {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .comment-form-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .comment-form__text {
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            transition: all 0.3s ease;
        }

        .comment-form__text:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn--danger {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn--danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .login-prompt {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            padding: 15px 20px;
            border-radius: 6px;
            color: #92400e;
            font-size: 14px;
        }

        .login-prompt a {
            color: #dc2626;
            font-weight: 600;
            text-decoration: none;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        /* Comments List */
        .comment-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .comment {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .comment:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .comment-header-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }

        .comment__images {
            flex-shrink: 0;
        }

        .comment__images img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .comment-info {
            flex: 1;
        }

        .comment__author {
            font-weight: 600;
            color: #1f2937;
            text-decoration: none;
            font-size: 14px;
        }

        .comment__author:hover {
            color: #667eea;
        }

        .comment__date {
            font-size: 12px;
            color: #9ca3af;
            margin: 3px 0;
        }

        .comment__message {
            color: #374151;
            font-size: 14px;
            line-height: 1.6;
            margin: 10px 0;
            background: #f9fafb;
            padding: 10px 12px;
            border-radius: 4px;
            border-left: 3px solid #667eea;
        }

        /* Replies */
        .replies-section {
            margin-top: 12px;
            margin-left: 52px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .reply-form {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .reply-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }

        .reply-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        .btn-reply {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-reply:hover {
            background: #764ba2;
        }

        .replies-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .reply-item {
            background: #f0f9ff;
            padding: 10px 12px;
            border-radius: 4px;
            border-left: 3px solid #0c4a6e;
            font-size: 13px;
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

        .toggle-reply-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            padding: 0;
            text-decoration: none;
        }

        .toggle-reply-btn:hover {
            text-decoration: underline;
        }

        .empty-comments {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .empty-comments-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            z-index: 9999;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
<div class="comment-section">
    <!-- Header -->
    <div class="comment-header">
        <h2>💬 Bình luận</h2>
        <?php foreach ($dem_bl as $bl): 
            extract($bl);
        ?>
            <div class="comment-count">📊 Số bình luận: <strong><?= $so_binh_luan ?></strong></div>
        <?php endforeach; ?>
    </div>

    <!-- Comment Form -->
    <div class="comment-form">
        <div class="comment-form-title">✍️ Để lại bình luận của bạn</div>
        <?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])): ?>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="hidden" name="id_phim" value="<?= $id_phim ?>">
                <div class="form-group">
                    <textarea name="noi_dung" class="comment-form__text" placeholder="Chia sẻ ý kiến của bạn về phim này..." required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="guibinhluan" value="1" class="btn--danger">📤 Gửi bình luận</button>
                </div>
            </form>
        <?php else: ?>
            <div class="login-prompt">
                🔒 Để bình luận hãy <a href="index.php?act=dangnhap">Đăng nhập</a> hoặc <a href="index.php?act=dangky">Đăng ký</a> tài khoản
            </div>
        <?php endif; ?>
    </div>

    <!-- Comments List -->
    <div class="comment-list">
        <?php if (!empty($listbl)): ?>
            <?php foreach ($listbl as $bl): 
                extract($bl);
                $replies = pdo_query("SELECT tra_loi_binhluan.id, tra_loi_binhluan.noidung, tra_loi_binhluan.ngay_tao, 
                                             taikhoan.name 
                                             FROM tra_loi_binhluan
                                             LEFT JOIN taikhoan ON tra_loi_binhluan.id_user = taikhoan.id
                                             WHERE tra_loi_binhluan.id_binhluan = '$id'
                                             ORDER BY tra_loi_binhluan.ngay_tao ASC");
            ?>
                <div class="comment">
                    <div class="comment-header-row">
                        <div class="comment__images">
                            <img alt="Avatar" src="../images/comment/avatar.jpg">
                        </div>
                        <div class="comment-info">
                            <a href="#" class="comment__author"><?= htmlspecialchars($name ?? 'Ẩn danh') ?></a>
                            <p class="comment__date">🕐 <?= htmlspecialchars($ngaybinhluan) ?></p>
                        </div>
                    </div>
                    <p class="comment__message"><?= nl2br(htmlspecialchars($noidung)) ?></p>

                    <!-- Replies Section -->
                    <?php if (!empty($replies) || (isset($_SESSION['user']) && !empty($_SESSION['user']))): ?>
                        <div class="replies-section">
                            <!-- Reply Form (only for logged-in users) -->
                            <?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])): ?>
                                <form class="reply-form" onsubmit="submitReply(event, <?= $id ?>)">
                                    <textarea class="reply-input" placeholder="Trả lời bình luận này..." rows="1" required></textarea>
                                    <button type="submit" class="btn-reply">Trả lời</button>
                                </form>
                            <?php endif; ?>

                            <!-- Replies List -->
                            <?php if (!empty($replies)): ?>
                                <div class="replies-list">
                                    <?php foreach ($replies as $reply): ?>
                                        <div class="reply-item">
                                            <div class="reply-author">✓ Admin: <?= htmlspecialchars($reply['name'] ?? 'Hệ thống') ?></div>
                                            <div class="reply-time">🕐 <?= date('d/m/Y H:i', strtotime($reply['ngay_tao'])) ?></div>
                                            <div class="reply-text"><?= nl2br(htmlspecialchars($reply['noidung'])) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-comments">
                <div class="empty-comments-icon">📭</div>
                <p>Chưa có bình luận nào. Hãy đăng nhập để trở thành người đầu tiên bình luận!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function submitReply(event, commentId) {
    event.preventDefault();
    
    const form = event.target;
    const textarea = form.querySelector('.reply-input');
    const replyText = textarea.value.trim();
    
    if (!replyText) {
        alert('Vui lòng nhập nội dung trả lời');
        return;
    }
    
    const formData = new FormData();
    formData.append('act', 'submit_reply');
    formData.append('id_binhluan', commentId);
    formData.append('noidung', replyText);
    
    const submitBtn = form.querySelector('.btn-reply');
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Gửi...';
    
    fetch(window.location.pathname, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            textarea.value = '';
            showMessage('✅ Trả lời thành công!');
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể gửi trả lời'));
            submitBtn.disabled = false;
            submitBtn.textContent = 'Trả lời';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Trả lời';
    });
}

function showMessage(message) {
    const msg = document.createElement('div');
    msg.className = 'success-message';
    msg.textContent = message;
    document.body.appendChild(msg);
    
    setTimeout(() => {
        msg.remove();
    }, 3000);
}
</script>

</body>
</html>
