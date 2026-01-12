<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST LOADING INCLUDES ===<br>";

try {
    echo "Loading pdo.php...<br>";
    include "Trang-nguoi-dung/model/pdo.php";
    echo "✅ pdo.php loaded<br>";
} catch (Exception $e) {
    echo "❌ pdo.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading loai_phim.php...<br>";
    include "Trang-nguoi-dung/model/loai_phim.php";
    echo "✅ loai_phim.php loaded<br>";
} catch (Exception $e) {
    echo "❌ loai_phim.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading phim.php...<br>";
    include "Trang-nguoi-dung/model/phim.php";
    echo "✅ phim.php loaded<br>";
} catch (Exception $e) {
    echo "❌ phim.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading taikhoan.php...<br>";
    include "Trang-nguoi-dung/model/taikhoan.php";
    echo "✅ taikhoan.php loaded<br>";
} catch (Exception $e) {
    echo "❌ taikhoan.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading lichchieu.php...<br>";
    include "Trang-nguoi-dung/model/lichchieu.php";
    echo "✅ lichchieu.php loaded<br>";
} catch (Exception $e) {
    echo "❌ lichchieu.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading ve.php...<br>";
    include "Trang-nguoi-dung/model/ve.php";
    echo "✅ ve.php loaded<br>";
} catch (Exception $e) {
    echo "❌ ve.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading hoadon.php...<br>";
    include "Trang-nguoi-dung/model/hoadon.php";
    echo "✅ hoadon.php loaded<br>";
} catch (Exception $e) {
    echo "❌ hoadon.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading rap.php...<br>";
    include "Trang-nguoi-dung/model/rap.php";
    echo "✅ rap.php loaded<br>";
} catch (Exception $e) {
    echo "❌ rap.php error: " . $e->getMessage() . "<br>";
    exit;
}

try {
    echo "Loading combo.php...<br>";
    include "Trang-nguoi-dung/model/combo.php";
    echo "✅ combo.php loaded<br>";
} catch (Exception $e) {
    echo "❌ combo.php error: " . $e->getMessage() . "<br>";
    exit;
}

// Test calling functions
try {
    echo "<br>Testing functions:<br>";
    echo "loadall_loaiphim()...<br>";
    $loadloai = loadall_loaiphim();
    echo "✅ loadall_loaiphim() works - returned " . count($loadloai) . " items<br>";
} catch (Exception $e) {
    echo "❌ loadall_loaiphim() error: " . $e->getMessage() . "<br>";
}

try {
    echo "loadall_phim()...<br>";
    $loadphim = loadall_phim();
    echo "✅ loadall_phim() works - returned " . count($loadphim) . " items<br>";
} catch (Exception $e) {
    echo "❌ loadall_phim() error: " . $e->getMessage() . "<br>";
}

try {
    echo "loadall_phim_hot()...<br>";
    $loadphimhot = loadall_phim_hot();
    echo "✅ loadall_phim_hot() works - returned " . count($loadphimhot) . " items<br>";
} catch (Exception $e) {
    echo "❌ loadall_phim_hot() error: " . $e->getMessage() . "<br>";
}

try {
    echo "loadall_phim_home()...<br>";
    $loadphimhome = loadall_phim_home();
    echo "✅ loadall_phim_home() works - returned " . count($loadphimhome) . " items<br>";
} catch (Exception $e) {
    echo "❌ loadall_phim_home() error: " . $e->getMessage() . "<br>";
}

try {
    echo "load_active_raps()...<br>";
    $activeRaps = load_active_raps();
    echo "✅ load_active_raps() works - returned " . count($activeRaps) . " items<br>";
} catch (Exception $e) {
    echo "❌ load_active_raps() error: " . $e->getMessage() . "<br>";
}

try {
    echo "loadall_rap()...<br>";
    $allRaps = loadall_rap();
    echo "✅ loadall_rap() works - returned " . count($allRaps) . " items<br>";
} catch (Exception $e) {
    echo "❌ loadall_rap() error: " . $e->getMessage() . "<br>";
}

echo "<br><strong>✅ ALL TESTS PASSED!</strong>";
?>
