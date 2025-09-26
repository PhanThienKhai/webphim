<?php
// $step: 1 - nhập email, 2 - nhập OTP, 3 - nhập mật khẩu mới
if (!isset($step)) $step = 1;
?>
<style>
.forgot-container {
    max-width: 400px;
    margin: 60px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 32px 28px 24px 28px;
}
.forgot-container h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #007bff;
    font-weight: 700;
}
.forgot-container label {
    font-weight: 500;
    margin-bottom: 6px;
    display: block;
}
.forgot-container input[type="email"],
.forgot-container input[type="text"],
.forgot-container input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 15px;
    transition: border 0.2s;
}
.forgot-container input:focus {
    border: 1.5px solid #007bff;
    outline: none;
}
.forgot-container button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    margin-top: 4px;
    transition: background 0.2s;
    box-shadow: 0 2px 8px rgba(0,123,255,0.08);
}
.forgot-container button:hover {
    background: #0056b3;
}
.forgot-container .msg {
    text-align: center;
    margin-bottom: 14px;
    font-size: 15px;
    border-radius: 4px;
    padding: 8px 0;
}
.forgot-container .msg.error {
    color: #b30000;
    background: #ffeaea;
}
.forgot-container .msg.success {
    color: #006400;
    background: #eaffea;
}
@media (max-width: 500px) {
    .forgot-container { padding: 18px 6vw; }
}
</style>
<div class="forgot-container">
    <h2>Quên mật khẩu</h2>
    <?php if (isset($error) && $error) { ?>
        <div class="msg error"> <?= $error ?> </div>
    <?php } ?>
    <?php if (isset($success) && $success) { ?>
        <div class="msg success"> <?= $success ?> </div>
    <?php } ?>
    <?php if ($step == 1) { ?>
        <form method="post">
            <label for="email">Nhập email đã đăng ký:</label>
            <input type="email" name="email" required autocomplete="email">
            <button type="submit" name="send_otp">Gửi mã OTP</button>
        </form>
    <?php } elseif ($step == 2) { ?>
        <form method="post">
            <label for="otp">Nhập mã OTP đã gửi về email:</label>
            <input type="text" name="otp" maxlength="6" required autocomplete="one-time-code">
            <button type="submit" name="verify_otp">Xác nhận OTP</button>
        </form>
    <?php } elseif ($step == 3) { ?>
        <form method="post">
            <label for="pass1">Nhập mật khẩu mới:</label>
            <input type="password" name="pass1" required autocomplete="new-password">
            <label for="pass2">Nhập lại mật khẩu mới:</label>
            <input type="password" name="pass2" required autocomplete="new-password">
            <button type="submit" name="reset_pass">Đổi mật khẩu</button>
        </form>
    <?php } ?>
</div>

