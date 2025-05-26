<?php
// Load danh sách tài khoản
function loadall_taikhoan() {
    $sql = "SELECT * FROM taikhoan ORDER BY id ASC";
    return pdo_query($sql);
}

// Kiểm tra tài khoản
function check_tk($user, $pass) {
    $sql = "SELECT * FROM taikhoan WHERE user = '$user' AND pass = '$pass'";
    return pdo_query_one($sql);
}

// Đăng xuất
function dang_xuat() {
    unset($_SESSION['user']);
}

// Thêm tài khoản mới
function insert_taikhoan($email, $user, $pass, $name, $sdt, $dc) {
    $sql = "INSERT INTO taikhoan (email, user, pass, dia_chi, phone, name) 
            VALUES ('$email', '$user', '$pass', '$dc', '$sdt', '$name')";
    pdo_execute($sql);
}

// Sửa tài khoản
function sua_tk($id, $user, $email, $sdt, $dc) {
    $sql = "UPDATE taikhoan 
            SET user = '$user', email = '$email', phone = '$sdt', dia_chi = '$dc' 
            WHERE id = $id";
    pdo_execute($sql);
}

// Lấy mật khẩu cũ
function mkcu($id) {
    $sql = "SELECT pass FROM taikhoan WHERE id = $id";
    $result = pdo_query_one($sql);
    return $result['pass'];
}

// Đổi mật khẩu
function doi_tk($id, $passmoi) {
    $sql = "UPDATE taikhoan SET pass = '$passmoi' WHERE id = $id";
    pdo_execute($sql);
}

// Lấy thông tin 1 tài khoản
function loadone_taikhoan($id) {
    $sql = "SELECT * FROM taikhoan WHERE id = $id";
    return pdo_query_one($sql);
}

// Kiểm tra email tồn tại
function check_email($email) {
    $sql = "SELECT email FROM taikhoan WHERE email = '$email'";
    return pdo_query_one($sql);
}

// Gửi mail khôi phục mật khẩu
function sendMail($email) {
    $sql = "SELECT * FROM taikhoan WHERE email = '$email'";
    $taikhoan = pdo_query_one($sql);

    if ($taikhoan != false) {
        sendMailPass($email, $taikhoan['name'], $taikhoan['pass']);
        return "✅ Mật khẩu đã được gửi về email của bạn.";
    } else {
        return "❌ Email không tồn tại trong hệ thống.";
    }
}

// Hàm gửi mail chi tiết
function sendMailPass($email, $name, $pass) {
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phanthienkhai2901@gmail.com';
        $mail->Password   = 'pewo qrqg egsf ijxv'; // Lưu ý: Không nên hardcode mật khẩu thật
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Người gửi & người nhận
        $mail->setFrom('phanthienkhai2901@gmail.com', 'Galaxy Studio');
        $mail->addAddress($email, $name);

        // Gửi HTML email
        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode('🛡️ Khôi phục mật khẩu tài khoản Galaxy Studio') . '?=';

        // $safe_name = htmlspecialchars($name);
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; border-radius: 10px; padding: 20px; background-color: #f9f9f9;">
                <h2 style="color: #333;">Xin chào<span style="color: #007bff;">' . $safe_name . '</span>,</h2>
                <p>Bạn hoặc ai đó đã yêu cầu khôi phục mật khẩu tài khoản tại <strong>Galaxy Studio</strong>.</p>
                <p><strong>Mật khẩu của bạn là:</strong></p>
                <div style="background-color: #e9ecef; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px; font-weight: bold;">' . $pass . '</div>
                <p style="margin-top: 20px;">Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này hoặc liên hệ bộ phận hỗ trợ.</p>
                <hr>
                <p style="font-size: 12px; color: #777;">Email này được gửi tự động từ hệ thống Galaxy Studio. Vui lòng không trả lời lại email.</p>
            </div>
        ';

        $mail->send();
    } catch (Exception $e) {
        echo "Gửi email thất bại. Lỗi: {$mail->ErrorInfo}";
    }
}
?>
