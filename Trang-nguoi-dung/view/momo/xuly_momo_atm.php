<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

/**
 * Thực hiện POST JSON tới URL
 */
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// ====== CẤU HÌNH MO Mo Gateway ======
$endpoint    = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = 'MOMOBKUN20180529';
$accessKey   = 'klm05TvNBzhg7h7j';
$secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

// ====== THÔNG TIN ĐƠN HÀNG ======
$movieTitle     = isset($_SESSION['tong']['tieu_de']) ? $_SESSION['tong']['tieu_de'] : 'Vé phim';
$myMomoAccount  = '0384104942';            // Số tài khoản MoMo của bạn
$myMomoName     = 'Phan Thiên Khải';      // Tên chủ TK MoMo
$orderInfo      = "Thanh toán {$movieTitle} - TK MoMo: {$myMomoAccount} ({$myMomoName})";

// Lấy số tiền thanh toán - ưu tiên giá sau giảm nếu có mã khuyến mãi
if (isset($_SESSION['tong']['gia_sau_giam']) && $_SESSION['tong']['gia_sau_giam'] > 0) {
    // Đã áp dụng mã khuyến mãi - dùng giá sau giảm
    $amount = (int)$_SESSION['tong']['gia_sau_giam'];
} else {
    // Chưa áp mã - dùng giá gốc
    $amount = isset($_SESSION['tong']['gia_ghe']) ? (int)$_SESSION['tong']['gia_ghe'] : 0;
}

// Validate số tiền
if ($amount < 10000) {
    die("Lỗi: Số tiền thanh toán phải tối thiểu 10,000 VND. Số tiền hiện tại: " . number_format($amount) . " VND");
}

if ($amount > 50000000) {
    die("Lỗi: Số tiền thanh toán vượt quá giới hạn 50,000,000 VND. Số tiền hiện tại: " . number_format($amount) . " VND");
}

// Tạo các ID đơn hàng & request
$orderId   = time() . "";
$requestId = time() . "";

// URL redirect & IPN (thay bằng domain thật khi deploy)
$baseUrl     = 'http://localhost/duan1/Trang%20người%20dùng/index.php';
$redirectUrl = "{$baseUrl}?act=ve&id=1";
$ipnUrl      = "{$baseUrl}?act=ve&id=1";

$requestType = "payWithATM";
$extraData   = ""; // có thể thêm dữ liệu tuỳ chọn

// ====== Tạo signature ======
$rawHash = "accessKey={$accessKey}"
         . "&amount={$amount}"
         . "&extraData={$extraData}"
         . "&ipnUrl={$ipnUrl}"
         . "&orderId={$orderId}"
         . "&orderInfo={$orderInfo}"
         . "&partnerCode={$partnerCode}"
         . "&redirectUrl={$redirectUrl}"
         . "&requestId={$requestId}"
         . "&requestType={$requestType}";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// ====== Chuẩn bị payload ======
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Galaxy Studio",
    'storeId'     => "MomoStore123",
    'requestId'   => $requestId,
    'amount'      => $amount,
    'orderId'     => $orderId,
    'orderInfo'   => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl'      => $ipnUrl,
    'lang'        => 'vi',
    'extraData'   => $extraData,
    'requestType' => $requestType,
    'signature'   => $signature
);

// ====== Gửi request và nhận payUrl ======
$result     = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);

// ====== Hiển thị tài khoản MoMo và redirect ======
if (isset($jsonResult['payUrl'])) {
    // Hiển thị thông tin tài khoản trước khi chuyển hướng
    echo "<div style=\"font-family: Arial, sans-serif; max-width:400px;margin:30px auto;padding:20px;border:1px solid #ccc;border-radius:8px;text-align:center;\">";
    echo "<h2>Thanh toán qua MoMo</h2>";
    echo "<p><strong>Số tài khoản MoMo:</strong> {$myMomoAccount}</p>";
    echo "<p><strong>Chủ tài khoản:</strong> {$myMomoName}</p>";
    echo "<p>Bạn sẽ được chuyển đến MoMo để hoàn tất thanh toán.</p>";
    echo "<p>Nếu không tự điều hướng, <a href=\"{$jsonResult['payUrl']}\">bấm vào đây</a>.</p>";
    echo "</div>";

    // Tự động redirect sau 5 giây
    header("Refresh: 5; URL=" . $jsonResult['payUrl']);
    exit;
} else {
    // Hiển thị lỗi nếu có
    echo "<p>Đã có lỗi xảy ra khi tạo đơn thanh toán MoMo.</p>";
    if (isset($jsonResult['message'])) {
        echo "<p>Lỗi từ MoMo: {$jsonResult['message']}</p>";
    }
    exit;
}
