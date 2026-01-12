<?php
/**
 * Helper để set session id_hd từ payment confirmation
 * POST /sepay/set_session.php
 */
session_start();

error_log("=== SET_SESSION.php CALLED ===");
error_log("PHPSESSID: " . ($_COOKIE['PHPSESSID'] ?? 'NOT SET'));

$json = file_get_contents('php://input');
error_log("Raw JSON received: " . $json);

$data = json_decode($json, true);

error_log("SET_SESSION: Received data: " . json_encode($data));

if ($data && isset($data['id_hd'])) {
    $_SESSION['id_hd'] = (int)$data['id_hd'];
    $_SESSION['id_ve'] = (int)($data['ve_id'] ?? 0);
    
    error_log("SET_SESSION: ✅ Set id_hd=" . $_SESSION['id_hd'] . ", id_ve=" . $_SESSION['id_ve']);
    error_log("SESSION after set: " . json_encode($_SESSION, JSON_UNESCAPED_UNICODE));
    
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    error_log("SET_SESSION: ❌ Missing id_hd in request");
    echo json_encode(['success' => false, 'error' => 'Missing id_hd']);
}
?>
