<?php

require_once 'config.php';

$theme = getTheme();
$flash = getFlashMessage();
$cart  = $_SESSION['cart'] ?? [];

// Hitung total
$total_items = getCartCount();
$total_price = getCartTotal();

// Sisa waktu session
$remaining = 120;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — GameVault 🛒</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>">
</head>
<body class="<?php echo htmlspecialchars($theme); ?>">


<header class="header">
    <div class="header-inner">
        <a href="index.php" class="logo">🎮 GAME<span>VAULT</span></a>

        <div class="header-right">
            <!-- Session Timer -->
            <div class="session-timer" id="sessionTimer">
                ⏱️ <span id="timerDisplay"><?php echo $remaining; ?>s</span>
            </div>

            <!-- Cart Button (aktif) -->
            <a href="cart.php" class="cart-btn">
                🛒 Keranjang
                <span class="cart-badge"><?php echo $total_items; ?></span>
            </a>

            <!-- Theme Toggle -->
            <form method="POST" action="process.php?action=set_theme" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="theme" value="<?php echo $theme === 'dark' ? 'light' : 'dark'; ?>">
                <input type="hidden" name="redirect" value="cart.php">
                <button type="submit" class="theme-toggle" title="Toggle Theme">
                    <?php echo $theme === 'dark' ? '☀️' : '🌙'; ?>
                </button>
            </form>
        </div>
    </div>
</header>

<?php if ($flash): ?>
<div class="flash">
    <div class="flash-msg flash-<?php echo htmlspecialchars($flash['type']); ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
</div>
<?php endif; ?>


<main class="container">

    <div class="page-hero">
        <h1>🛒 KERANJANG BELANJA</h1>
        <p>
            <?php if ($total_items > 0): ?>
                <?php echo $total_items; ?> item dalam keranjang &mdash; siap checkout! 🚀
            <?php else: ?>
                Keranjangmu masih kosong nih...
            <?php endif; ?>
        </p>
    </div>

    <!-- Back link -->
    <p style="margin-bottom: 1.5rem;">
        <a href="index.php" style="color: var(--text-muted); font-size: 0.9rem;">
            ← Lanjut belanja
        </a>
    </p>

    <?php if (empty($cart)): ?>



    <div class="empty-cart">
        <span class="icon">🛒</span>
        <h2>KERANJANG KOSONG</h2>
        <p>Yuk tambahkan game favoritmu ke keranjang!</p>
        <a href="index.php" class="btn-back">🎮 Lihat Game</a>
    </div>

    <?php else: ?>


    <div class="cart-summary">

        <!-- TABLE -->
        <div class="cart-table-wrap">
            <div class="section-title">🎮 ITEM DALAM KERANJANG</div>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>GAME</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                        <th>SUBTOTAL</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                    <tr>
                        <!-- Nama Produk -->
                        <td>
                            <div style="display:flex; align-items:center; gap:0.8rem;">
                                <span style="font-size:1.8rem; line-height:1;"><?php echo htmlspecialchars($item['emoji']); ?></span>
                                <div>
                                    <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="cart-item-genre"><?php echo htmlspecialchars($item['genre']); ?></div>
                                </div>
                            </div>
                        </td>

                        <!-- Harga Satuan -->
                        <td class="price-cell"><?php echo formatRupiah($item['price']); ?></td>

                        <!-- Update Quantity Form -->
                        <td>
                            <form method="POST" action="process.php?action=update"
                                  id="form-update-<?php echo $id; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">

                                <div class="qty-controls">
                                    <button type="button"
                                            class="qty-btn"
                                            onclick="changeQty(<?php echo $id; ?>, -1, <?php echo $item['stock']; ?>)">−</button>

                                    <input type="number"
                                           name="quantity"
                                           id="qty-<?php echo $id; ?>"
                                           class="cart-qty-input"
                                           value="<?php echo $item['quantity']; ?>"
                                           min="0"
                                           max="<?php echo $item['stock']; ?>">

                                    <button type="button"
                                            class="qty-btn"
                                            onclick="changeQty(<?php echo $id; ?>, 1, <?php echo $item['stock']; ?>)">+</button>

                                    <button type="submit" class="btn-update">OK</button>
                                </div>
                            </form>
                        </td>

                        <!-- Subtotal -->
                        <td class="subtotal-cell">
                            <?php echo formatRupiah($item['price'] * $item['quantity']); ?>
                        </td>

                        <!-- Hapus -->
                        <td>
                            <form method="POST" action="process.php?action=remove"
                                  onsubmit="return confirm('Hapus <?php echo htmlspecialchars(addslashes($item['name'])); ?> dari keranjang?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" class="btn-remove" title="Hapus item">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SIDEBAR -->
        <div class="cart-sidebar">
            <div class="cart-card">
                <h3>RINGKASAN PESANAN</h3>

                <div class="cart-total-row">
                    <span>Total item:</span>
                    <strong><?php echo $total_items; ?> item</strong>
                </div>

                <?php foreach ($cart as $item): ?>
                <div class="cart-total-row" style="font-size:0.82rem; color:var(--text-muted);">
                    <span><?php echo htmlspecialchars($item['emoji']); ?> <?php echo htmlspecialchars($item['name']); ?> ×<?php echo $item['quantity']; ?></span>
                    <span><?php echo formatRupiah($item['price'] * $item['quantity']); ?></span>
                </div>
                <?php endforeach; ?>

                <div class="cart-total-row grand">
                    <span>TOTAL:</span>
                    <span><?php echo formatRupiah($total_price); ?></span>
                </div>

                <!-- Checkout (simulasi) -->
                <a href="#" class="btn-checkout"
                   onclick="alert('Fitur checkout segera hadir! 🚀'); return false;">
                    💳 CHECKOUT
                </a>

                <!-- Kosongkan Keranjang -->
                <form method="POST" action="process.php?action=clear"
                      onsubmit="return confirm('Yakin mau kosongkan semua keranjang? 🗑️')">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn-clear">🗑️ Kosongkan Keranjang</button>
                </form>
            </div>

            <!-- Info Session -->
            <div style="margin-top:1rem; padding:1rem; background:var(--card); border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.82rem; color:var(--text-muted);">
                <div>⏱️ Session berakhir: <span id="timerDisplay2"><?php echo $remaining; ?>s</span></div>
                <div style="margin-top:0.3rem;">🔒 Transaksi aman &amp; terenkripsi</div>
            </div>
        </div>

    </div>
    <?php endif; ?>

</main>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2024 <span>GameVault</span> — Dibuat dengan ❤️ untuk para gamer Indonesia</p>
</footer>

<!-- SCRIPTS -->

<script>
// Quantity +/- control
function changeQty(id, delta, maxStock) {
    const input = document.getElementById('qty-' + id);
    let val = parseInt(input.value) + delta;
    if (val < 0) val = 0;
    if (val > maxStock) val = maxStock;
    input.value = val;
}

// Session Timer
(function() {
    let remaining = <?php echo $remaining; ?>;
    const el1 = document.getElementById('timerDisplay');
    const el2 = document.getElementById('timerDisplay2');
    const wrap = document.getElementById('sessionTimer');

    function tick() {
        if (remaining <= 0) {
            location.reload();
            return;
        }
        remaining--;
        if (el1) el1.textContent = remaining + 's';
        if (el2) el2.textContent = remaining + 's';
        if (remaining <= 30 && wrap) wrap.classList.add('warning');
    }

    setInterval(tick, 1000);
})();
</script>

</body>
</html>
