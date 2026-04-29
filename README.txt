============================================================
  GAMEVAULT - Tugas Praktikum Pemrograman Web II
  Session & Cookie di PHP - Pertemuan 8
============================================================

Nama  : [Raffi Cassidy Chandra]
NPM   : [25781097]
Kelas : [MI-2D]

============================================================
  CARA MENJALANKAN
============================================================

1. Copy folder 'shopping_cart' ke:
   - XAMPP Windows : C:\xampp\htdocs\shopping_cart\
   - XAMPP Linux   : /opt/lampp/htdocs/shopping_cart/
   - WAMP Windows  : C:\wamp64\www\shopping_cart\

2. Pastikan Apache sudah running di XAMPP/WAMP

3. Buka browser, akses:
   http://localhost/shopping_cart/

4. Aplikasi siap! 🎮

============================================================
  FITUR YANG DIIMPLEMENTASIKAN
============================================================

FITUR WAJIB:
[x] Daftar produk (10 game dengan nama, harga, stok, genre, rating)
[x] Tombol "Tambah ke Keranjang" dengan quantity selector
[x] Halaman keranjang dengan tabel dinamis (nama, harga, qty, subtotal)
[x] Tombol update quantity (+ / - dan input manual)
[x] Tombol hapus item per baris (dengan konfirmasi)
[x] Tombol kosongkan keranjang (dengan konfirmasi)
[x] Data keranjang disimpan di SESSION
[x] Preferensi tema dark/light disimpan di COOKIE
[x] Toggle tema di semua halaman (header)

FITUR BONUS:
[x] CSRF Token pada semua form POST (add, update, remove, clear, set_theme)
[x] Session timeout: keranjang otomatis dikosongkan setelah 2 menit tidak aktif
[x] Last visited product: cookie menyimpan game terakhir dilihat (30 hari)

============================================================
  KEAMANAN YANG DITERAPKAN
============================================================

Session:
- session.use_only_cookies = 1
- session.cookie_httponly = 1
- session.cookie_samesite = Strict
- Custom session name: GAMEVAULT_SESSION

CSRF:
- Token 32 byte dari random_bytes()
- Disimpan di $_SESSION (bukan cookie)
- Validasi dengan hash_equals() (timing-safe)
- Regenerate setiap request

Input Validation:
- (int) casting pada semua input numerik
- htmlspecialchars() pada semua output
- Validasi quantity (min 1, max = stok)
- Cek product_id ada di database

============================================================
  STRUKTUR FILE
============================================================

shopping_cart/
├── config.php      - Session config, CSRF, timeout, helper functions
├── products.php    - Data 10 produk game
├── process.php     - Backend: add, update, remove, clear, set_theme
├── index.php       - Halaman daftar produk
├── cart.php        - Halaman keranjang belanja
├── css/
│   └── style.css   - Gaming aesthetic dark/light mode
└── README.txt      - File ini

============================================================
  CATATAN TEKNIS
============================================================

- Timeout testing: ubah $timeout_duration di config.php (default: 120 detik)
- Tidak menggunakan database — data produk di array PHP (products.php)
- Cookie gv_theme    : preferensi tema (1 tahun)
- Cookie gv_last_visited : game terakhir dilihat (30 hari)
- PHP minimum: 7.4+ (random_bytes, hash_equals)

============================================================
  KESULITAN YANG DIHADAPI
============================================================

- ...
- ...

============================================================
