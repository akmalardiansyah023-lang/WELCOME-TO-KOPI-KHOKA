<?php
// Pengaturan database. Sesuaikan dengan server/XAMPP kamu.
define('DB_HOST', 'localhost');
define('DB_NAME', 'kopi_khoka');
define('DB_USER', 'root');
define('DB_PASS', '');

// Login admin. GANTI password sebelum website di-online-kan!
// Untuk password ter-hash: php -r "echo password_hash('PASSWORD_BARU', PASSWORD_DEFAULT);"
// lalu tempel hasilnya di ADMIN_PASS_HASH (jika terisi, ADMIN_PASS_AWAL diabaikan).
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', '');
define('ADMIN_PASS_AWAL', 'khoka123');
