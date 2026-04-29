<?php

require_once 'config.php';
require_once 'products.php';

// Baca tema dari cookie
$theme = getTheme();

// Set cookie last_visited jika ada ?id parameter
if (isset($_GET['id'])) {
    $visited_id = (int)$_GET['id'];
    $visited_product = getProduct($visited_id);
    if ($visited_product) {
        setcookie('gv_last_visited', $visited_product['name'], time() + (30 * 24 * 3600), '/');
    }
}

// Ambil flash message
$flash = getFlashMessage();

// Hitung sisa waktu session
$timeout_duration = 120;
$elapsed = isset($_SESSION['last_activity']) ? (time() - $_SESSION['last_activity']) : 0;
// last_activity sudah di-update di config.php, jadi remaining = timeout_duration
$remaining = $timeout_duration;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVault 🎮 — Toko Game Terbaik</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎮</text></svg>">
</head>
<body class="<?php echo htmlspecialchars($theme); ?>">


<!-- HEADER -->

<header class="header">
    <div class="header-inner">
        <a href="index.php" class="logo">🎮 GAME<span>VAULT</span></a>

        <div class="header-right">
            <!-- Session Timer -->
            <div class="session-timer" id="sessionTimer">
                ⏱️ <span id="timerDisplay"><?php echo $remaining; ?>s</span>
            </div>

            <!-- Cart Button -->
            <a href="cart.php" class="cart-btn">
                🛒 Keranjang
                <span class="cart-badge"><?php echo getCartCount(); ?></span>
            </a>

            <!-- Theme Toggle -->
            <form method="POST" action="process.php?action=set_theme" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="theme" value="<?php echo $theme === 'dark' ? 'light' : 'dark'; ?>">
                <input type="hidden" name="redirect" value="index.php">
                <button type="submit" class="theme-toggle" title="Toggle Theme">
                    <?php echo $theme === 'dark' ? '☀️' : '🌙'; ?>
                </button>
            </form>
        </div>
    </div>
</header>


<!-- FLASH MESSAGE -->

<?php if ($flash): ?>
<div class="flash">
    <div class="flash-msg flash-<?php echo htmlspecialchars($flash['type']); ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['timeout_message'])): ?>
<div class="flash">
    <div class="flash-msg flash-error">
        ⏰ Sesi tidak aktif terlalu lama — keranjang otomatis dikosongkan!
    </div>
</div>
<?php unset($_SESSION['timeout_message']); ?>
<?php endif; ?>


<!-- MAIN CONTENT -->

<main class="container">

    <!-- Hero -->
    <div class="page-hero">
        <h1>GAME STORE TERBAIK</h1>
        <p>Koleksi game premium pilihan — harga terbaik, transaksi aman ⚡</p>
    </div>

    <!-- Last Visited -->
    <?php if (!empty($_COOKIE['gv_last_visited'])): ?>
    <div class="last-visited">
        📌 Terakhir kamu lihat: <strong><?php echo htmlspecialchars($_COOKIE['gv_last_visited']); ?></strong>
    </div>
    <?php endif; ?>

    <!-- Products -->
    <div class="section-title">🕹️ SEMUA GAME</div>

    <div class="products-grid">
        <?php foreach (getAllProducts() as $product): ?>
        <?php
            $is_out = $product['stock'] <= 0;
            $is_low = $product['stock'] > 0 && $product['stock'] <= 5;
            $badge_class = 'badge-' . str_replace(' ', '', $product['badge']);
        ?>
        <div class="product-card <?php echo $is_out ? 'out-of-stock' : ''; ?>">

            <!-- Badge -->
            <span class="product-badge <?php echo htmlspecialchars($badge_class); ?>">
                <?php echo htmlspecialchars($product['badge']); ?>
            </span>

            <!-- Emoji Cover -->
            <a href="?id=<?php echo $product['id']; ?>">
                <span class="product-emoji"><?php echo $product['emoji']; ?></span>
            </a>

            <!-- Genre -->
            <span class="product-genre"><?php echo htmlspecialchars($product['genre']); ?></span>

            <!-- Name -->
            <h3 class="product-name">
                <a href="?id=<?php echo $product['id']; ?>" style="color: inherit;">
                    <?php echo htmlspecialchars($product['name']); ?>
                </a>
            </h3>

            <!-- Description -->
            <p class="product-desc"><?php echo htmlspecialchars($product['description']); ?></p>

            <!-- Meta: Price & Rating -->
            <div class="product-meta">
                <span class="product-price"><?php echo formatRupiah($product['price']); ?></span>
                <span class="product-rating">⭐ <?php echo $product['rating']; ?></span>
            </div>

            <!-- Stock Status -->
            <p class="product-stock">
                <?php if ($is_out): ?>
                    <span class="stock-empty">❌ Stok Habis</span>
                <?php elseif ($is_low): ?>
                    <span class="stock-low">⚠️ Sisa <?php echo $product['stock']; ?> unit</span>
                <?php else: ?>
                    <span class="stock-ok">✅ Stok Tersedia (<?php echo $product['stock']; ?>)</span>
                <?php endif; ?>
            </p>

            <!-- Add to Cart Form -->
            <?php if (!$is_out): ?>
            <form method="POST" action="process.php?action=add" class="add-form">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number"
                       name="quantity"
                       class="qty-input"
                       value="1"
                       min="1"
                       max="<?php echo $product['stock']; ?>">
                <button type="submit" class="btn-add">+ Keranjang</button>
            </form>
            <?php else: ?>
            <button class="btn-add" disabled>Stok Habis</button>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

</main>


<footer class="footer">
    <p>© 2024 <span>GameVault</span> — Dibuat dengan ❤️ untuk para gamer Indonesia</p>
</footer>


<script>
(function() {
    let remaining = <?php echo $remaining; ?>;
    const timerEl = document.getElementById('timerDisplay');
    const timerWrap = document.getElementById('sessionTimer');

    function updateTimer() {
        if (remaining <= 0) {
            timerEl.textContent = '0s';
            // Reload untuk trigger timeout di server
            location.reload();
            return;
        }
        remaining--;
        timerEl.textContent = remaining + 's';

        if (remaining <= 30) {
            timerWrap.classList.add('warning');
        }
    }

    setInterval(updateTimer, 1000);
})();
</script>

</body>
</html>
