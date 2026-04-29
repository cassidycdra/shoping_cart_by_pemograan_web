<?php

require_once 'config.php';
require_once 'products.php';

$action = $_GET['action'] ?? '';

// Validasi CSRF untuk semua POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validateCSRF($token)) {
        setFlashMessage('error', '⚠️ Request tidak valid! CSRF token salah.');
        header('Location: index.php');
        exit;
    }
    // Regenerate token setelah validasi
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

switch ($action) {

    case 'add':
        $product_id = (int)($_POST['product_id'] ?? 0);
        $quantity   = (int)($_POST['quantity'] ?? 1);
        $product    = getProduct($product_id);

        if (!$product) {
            setFlashMessage('error', '❌ Produk tidak ditemukan!');
            header('Location: index.php');
            exit;
        }

        if ($product['stock'] <= 0) {
            setFlashMessage('error', '❌ Stok ' . htmlspecialchars($product['name']) . ' habis!');
            header('Location: index.php');
            exit;
        }

        if ($quantity < 1) $quantity = 1;
        if ($quantity > $product['stock']) $quantity = $product['stock'];

        // Jika sudah ada di cart, tambah quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $new_qty = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
            if ($new_qty > $product['stock']) {
                $new_qty = $product['stock'];
            }
            $_SESSION['cart'][$product_id]['quantity'] = $new_qty;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'emoji'    => $product['emoji'],
                'genre'    => $product['genre'],
                'stock'    => $product['stock'],
                'quantity' => $quantity,
            ];
        }

        // Set last visited cookie
        setcookie('gv_last_visited', $product['name'], time() + (30 * 24 * 3600), '/');

        setFlashMessage('success', '✅ ' . htmlspecialchars($product['name']) . ' ditambahkan ke keranjang!');
        header('Location: index.php');
        exit;

 
    case 'update':
        $product_id = (int)($_POST['product_id'] ?? 0);
        $quantity   = (int)($_POST['quantity'] ?? 0);

        if (!isset($_SESSION['cart'][$product_id])) {
            setFlashMessage('error', '❌ Item tidak ditemukan di keranjang!');
            header('Location: cart.php');
            exit;
        }

        if ($quantity <= 0) {
            // Hapus item jika quantity 0
            $name = $_SESSION['cart'][$product_id]['name'];
            unset($_SESSION['cart'][$product_id]);
            setFlashMessage('info', '🗑️ ' . htmlspecialchars($name) . ' dihapus dari keranjang.');
        } else {
            $stock = $_SESSION['cart'][$product_id]['stock'];
            if ($quantity > $stock) $quantity = $stock;
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            setFlashMessage('success', '✅ Quantity berhasil diupdate!');
        }

        header('Location: cart.php');
        exit;


    case 'remove':
        $product_id = (int)($_POST['product_id'] ?? 0);

        if (isset($_SESSION['cart'][$product_id])) {
            $name = $_SESSION['cart'][$product_id]['name'];
            unset($_SESSION['cart'][$product_id]);
            setFlashMessage('info', '🗑️ ' . htmlspecialchars($name) . ' dihapus dari keranjang.');
        }

        header('Location: cart.php');
        exit;


    case 'clear':
        $_SESSION['cart'] = [];
        setFlashMessage('info', '🗑️ Keranjang berhasil dikosongkan.');
        header('Location: cart.php');
        exit;

    case 'set_theme':
        $theme = ($_POST['theme'] ?? 'dark') === 'light' ? 'light' : 'dark';
        setcookie('gv_theme', $theme, time() + (365 * 24 * 3600), '/');
        header('Location: ' . ($_POST['redirect'] ?? 'index.php'));
        exit;

    default:
        header('Location: index.php');
        exit;
}
?>
