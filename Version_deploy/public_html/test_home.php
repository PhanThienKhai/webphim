<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

echo "=== TESTING HOME PAGE FLOW ===<br>";

try {
    echo "Step 1: Loading all models...<br>";
    include "Trang-nguoi-dung/model/pdo.php";
    include "Trang-nguoi-dung/model/loai_phim.php";
    include "Trang-nguoi-dung/model/phim.php";
    include "Trang-nguoi-dung/model/taikhoan.php";
    include "Trang-nguoi-dung/model/lichchieu.php";
    include "Trang-nguoi-dung/model/ve.php";
    include "Trang-nguoi-dung/model/hoadon.php";
    include "Trang-nguoi-dung/model/rap.php";
    include "Trang-nguoi-dung/model/combo.php";
    echo "✅ All models loaded<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 2: Loading data...<br>";
    $loadloai = loadall_loaiphim();
    $loadphim = loadall_phim();
    $loadphimhot = loadall_phim_hot();
    $loadphimhome = loadall_phim_home();
    $activeRaps = load_active_raps();
    $allRaps = loadall_rap();
    echo "✅ All data loaded<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 3: Loading header...<br>";
    include "Trang-nguoi-dung/view/header.php";
    echo "✅ Header loaded<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 4: Loading home view...<br>";
    include "Trang-nguoi-dung/view/home.php";
    echo "✅ Home loaded<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Step 5: Loading footer...<br>";
    include "Trang-nguoi-dung/view/footer.php";
    echo "✅ Footer loaded<br>";
} catch (Exception $e) {
    echo "❌ Error loading footer: " . $e->getMessage() . "<br>";
    echo "Footer failed but rest of page loaded!";
}

echo "<br><strong>✅ PAGE TEST COMPLETE!</strong>";
?>
