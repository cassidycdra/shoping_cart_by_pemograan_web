<?php


// Konfigurasi session aman
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');

// Start session dengan nama kustom
session_name('GAMEVAULT_SESSION');
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validateCSRF($token) {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}


$timeout_duration = 120;

if (isset($_SESSION['last_activity'])) {
    $elapsed = time() - $_SESSION['last_activity'];
    if ($elapsed > $timeout_duration) {
        $_SESSION['cart'] = [];
        $_SESSION['last_activity'] = time();
        $_SESSION['timeout_message'] = true;
        // Jangan destroy session, hanya kosongkan cart
    }
}
$_SESSION['last_activity'] = time();

// Hitung sisa waktu
$remaining_time = $timeout_duration - (isset($_SESSION['last_activity']) ? 0 : $timeout_duration);
$remaining_time = max(0, $remaining_time);


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function getCartCount() {
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

function getTheme() {
    return $_COOKIE['gv_theme'] ?? 'dark';
}

function setFlashMessage($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
