<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Set timezone to Vietnam
date_default_timezone_set('Asia/Ho_Chi_Minh');

echo "=== TESTING INDEX.PHP FLOW ===<br>";

try {
    echo "Step 1: Loading includes...<br>";
    include "Trang-nguoi-dung/model/pdo.php";
    include "Trang-nguoi-dung/model/loai_phim.php";
    include "Trang-nguoi-dung/model/phim.php";
    include "Trang-nguoi-dung/model/taikhoan.php";
    include "Trang-nguoi-dung/model/lichchieu.php";
    include "Trang-nguoi-dung/model/ve.php";
    include "Trang-nguoi-dung/model/hoadon.php";
    include "Trang-nguoi-dung/model/rap.php";
    include "Trang-nguoi-dung/model/combo.php";
    echo "✅ All includes loaded<br>";
} catch (Exception $e) {
    echo "❌ Error during includes: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 2: Calling data loading functions...<br>";
    $loadloai = loadall_loaiphim();
    $loadphim = loadall_phim();
    $loadphimhot = loadall_phim_hot();
    $loadphimhome = loadall_phim_home();
    $activeRaps = load_active_raps();
    $allRaps = loadall_rap();
    echo "✅ All data loaded<br>";
} catch (Exception $e) {
    echo "❌ Error during data loading: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 3: Loading header.php...<br>";
    include "Trang-nguoi-dung/view/header.php";
    echo "✅ header.php loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading header.php: " . $e->getMessage() . "<br>";
    exit;
}

echo "<br><strong>✅ FLOW TEST PASSED! Page should load now.</strong>";
?>
