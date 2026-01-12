<?php
session_start();
header('Content-type: text/html; charset=utf-8');
require('../../config/domain_config.php');

// MoMo Credentials
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

// Lấy dữ liệu từ MoMo
$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';
$orderInfo = isset($_POST['orderInfo']) ? $_POST['orderInfo'] : '';
$signature = isset($_POST['signature']) ? $_POST['signature'] : '';
$resultCode = isset($_POST['resultCode']) ? $_POST['resultCode'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Log callback
$logFile = __DIR__ . '/momo_callback.log';
$logMessage = date('Y-m-d H:i:s') . " - OrderID: $orderId, ResultCode: $resultCode, Message: $message\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);

// Xác minh signature
$rawHash = "amount=" . $amount . "&extraData=&ipnUrl=&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=MOMOBKUN20180529&requestId=&requestType=payWithATM";
$expectedSignature = hash_hmac("sha256", $rawHash, $secretKey);

// Kiểm tra thanh toán thành công
if ($resultCode == "0" && $signature == $expectedSignature) {
    // Thanh toán thành công
    // Cập nhật trạng thái vé trong database
    require_once __DIR__ . '/../../model/pdo.php';
    
    $orderId_int = intval($orderId);
    
    try {
        // Cập nhật trạng thái vé thanh toán
        $sql = "UPDATE ve SET trang_thai = 1 WHERE id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderId_int]);
        
        // Ghi log thành công
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Payment SUCCESSFUL for Order: $orderId_int\n", FILE_APPEND);
        
        // Redirect về trang vé - Use production domain config
        header('Location: ' . BASE_URL . '/index.php?act=ve');
        exit();
    } catch (Exception $e) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Database Error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
} else {
    // Thanh toán thất bại
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Payment FAILED or Invalid Signature\n", FILE_APPEND);
    
    // Redirect về trang vé - Use production domain config
    header('Location: ' . BASE_URL . '/index.php?act=ve&error=payment_failed');
    exit();
}
?>
