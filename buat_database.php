<?php
/**
 * buat_database.php
 * Membuat database + tabel + data menu Kopi Khoka memakai kode PHP.
 *
 * Cara pakai:
 *   1. Nyalakan Apache dan MySQL (XAMPP / Laragon).
 *   2. Taruh file ini di folder kopi.khoka (htdocs/kopi.khoka/).
 *   3. Buka http://localhost/kopi.khoka/buat_database.php di browser.
 *   4. Setelah berhasil, HAPUS file ini dari server.
 *
 * File ini aman dijalankan berulang kali: tabel tidak dibuat dua kali
 * dan data menu hanya diisi kalau tabel menu masih kosong.
 */

// ====== Pengaturan koneksi MySQL (sesuaikan) ======
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$namaDb = 'kopi_khoka';
// ==================================================

header('Content-Type: text/html; charset=utf-8');

$log = [];

// Data menu awal: [kategori, nama, deskripsi, harga, foto]
$kategori = [
    'kopi'     => 'Kopi',
    'nonkopi'  => 'Non-Kopi',
    'makanan'  => 'Makanan',
    'camilan'  => 'Camilan',
];

$menuAwal = [
    ['kopi', 'Khoka Aren Soft', 'Racikan khas Khoka dengan gula aren, rasa kopinya lebih ringan.', 15000, 'images/menu/khoka_aren_soft.png'],
    ['kopi', 'Khoka Aren Strong', 'Racikan khas Khoka dengan gula aren, rasa kopinya lebih pekat.', 17000, 'images/menu/khoka_aren_strong.png'],
    ['kopi', 'Kopi Butterscotch', 'Kopi dengan rasa butterscotch yang manis dan wangi.', 18000, 'images/menu/kopi_butterscotch.png'],
    ['kopi', 'Kopi Pandan', 'Kopi dengan rasa pandan yang harum.', 18000, 'images/menu/kopi_pandan.png'],
    ['kopi', 'Kopi Banana', 'Kopi dengan rasa pisang yang manis.', 18000, 'images/menu/kopi_banana.png'],
    ['kopi', 'Kopi Caramel', 'Kopi dengan rasa karamel yang manis.', 18000, 'images/menu/kopi_caramel.png'],
    ['kopi', 'Kopi Strawberry', 'Kopi dengan rasa stroberi, manis dan segar.', 18000, 'images/menu/kopi_strawberry.png'],
    ['kopi', 'Americano', 'Espresso yang dicampur air, rasa kopinya bersih dan tegas.', 15000, 'images/menu/americano.png'],
    ['kopi', 'Latte', 'Espresso dengan susu yang lembut.', 15000, 'images/menu/latte.png'],
    ['kopi', 'Cappuccino', 'Espresso, susu, dan busa susu yang tebal.', 15000, 'images/menu/cappuccino.png'],
    ['kopi', 'Kopi Susu Hot', 'Kopi susu yang disajikan hangat.', 15000, 'images/menu/kopi_susu_hot.png'],
    ['nonkopi', 'Matcha', 'Minuman matcha yang lembut.', 18000, 'images/menu/matcha_ice.png'],
    ['nonkopi', 'Red Velvet', 'Minuman red velvet yang manis dan lembut.', 18000, 'images/menu/red_velvet.png'],
    ['nonkopi', 'Teh Tarik', 'Teh susu manis yang ditarik hingga berbusa.', 18000, 'images/menu/teh_tarik.png'],
    ['nonkopi', 'Green Tea', 'Teh hijau yang segar.', 15000, 'images/menu/green_tea.png'],
    ['nonkopi', 'Thai Tea', 'Teh Thailand yang manis dan creamy.', 15000, 'images/menu/thai_tea.png'],
    ['nonkopi', 'Taro', 'Minuman taro yang manis dan creamy.', 15000, 'images/menu/taro_ice.png'],
    ['nonkopi', 'Cokelat', 'Minuman cokelat yang manis dan lembut.', 15000, 'images/menu/coklat_ice.png'],
    ['nonkopi', 'Milo', 'Minuman Milo, manis dan gurih.', 15000, 'images/menu/milo_ice.png'],
    ['nonkopi', 'Lemon Tea', 'Teh dengan perasan lemon yang segar.', 15000, 'images/menu/lemon_tea.png'],
    ['nonkopi', 'Leci Tea', 'Teh dengan rasa leci yang manis dan segar.', 15000, 'images/menu/leci_tea.png'],
    ['nonkopi', 'Melon Spark', 'Minuman bersoda rasa melon, segar dan bergelembung.', 15000, 'images/menu/melon_spark.png'],
    ['nonkopi', 'Mango Fizz', 'Minuman bersoda rasa mangga, segar dan manis.', 15000, 'images/menu/manggo_fizz.png'],
    ['nonkopi', 'Strawberry Fizz', 'Minuman bersoda rasa stroberi, segar dan manis.', 15000, 'images/menu/strawberry_fizz.png'],
    ['nonkopi', 'Leci Fizz', 'Minuman bersoda rasa leci, segar dan manis.', 15000, 'images/menu/leci_fizz.png'],
    ['nonkopi', 'Lemon Tea Fizz', 'Lemon tea yang dipadu soda, segar dan sedikit asam.', 15000, 'images/menu/lemon_tea_fizz.png'],
    ['makanan', 'Mie Goreng Telur', 'Mie goreng dengan telur ceplok dan irisan cabai.', 12000, 'images/menu/mie_goreng_telur.png'],
    ['makanan', 'Mie Soto Telur', 'Mie berkuah soto dengan telur.', 12000, 'images/menu/mie_soto_telur.png'],
    ['camilan', 'Khoka Sweet Toast', 'Roti bakar manis khas Khoka.', 15000, 'images/menu/khoka_sweet_toast.png'],
    ['camilan', 'Smoky Bills Pizza', 'Pizza dengan cita rasa smoky, disajikan hangat.', 20000, 'images/menu/smoky_bills_pizza.png'],
    ['camilan', 'Chicken Mushroom Pizza', 'Pizza dengan topping ayam dan jamur.', 20000, 'images/menu/chicken_mushroom_pizza.png'],
    ['camilan', 'French Fries', 'Kentang goreng yang renyah.', 12000, 'images/menu/french_fries.png'],
    ['camilan', 'Fried Meatballs', 'Bakso goreng, gurih dan hangat.', 15000, 'images/menu/fried_meatballs.png'],
    ['camilan', 'Tahu Walik', 'Tahu goreng dengan isian di dalamnya, gurih dan renyah.', 15000, 'images/menu/tahu_walik.png'],
    ['camilan', 'Banana Fritter', 'Pisang goreng yang renyah, disajikan hangat.', 15000, 'images/menu/banana_fritter.png'],
    ['camilan', 'Cassava Chips', 'Keripik singkong yang renyah.', 15000, 'images/menu/cassava_chips.png'],
];

try {
    // 1. Koneksi ke server MySQL (belum memilih database)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $log[] = 'Terhubung ke server MySQL.';

    // 2. Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$namaDb`
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$namaDb`");
    $log[] = "Database '$namaDb' siap.";

    // 3. Buat tabel
    $pdo->exec("CREATE TABLE IF NOT EXISTS kategori (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(30) NOT NULL UNIQUE,
        nama VARCHAR(60) NOT NULL,
        urutan INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS menu (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        kategori_id INT UNSIGNED NOT NULL,
        nama VARCHAR(100) NOT NULL,
        deskripsi VARCHAR(255) NOT NULL DEFAULT '',
        harga INT UNSIGNED NOT NULL DEFAULT 0,
        foto VARCHAR(200) NOT NULL DEFAULT '',
        tersedia TINYINT(1) NOT NULL DEFAULT 1,
        urutan INT NOT NULL DEFAULT 0,
        dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pesan_kontak (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        pesan TEXT NOT NULL,
        dibaca TINYINT(1) NOT NULL DEFAULT 0,
        dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    $log[] = 'Tabel kategori, menu, dan pesan_kontak siap.';

    // 4. Isi data awal
    $pdo->beginTransaction();

    $urut = 1;
    $stKat = $pdo->prepare('INSERT IGNORE INTO kategori (slug, nama, urutan) VALUES (?, ?, ?)');
    foreach ($kategori as $slug => $nama) {
        $stKat->execute([$slug, $nama, $urut++]);
    }

    $jumlahMenu = (int)$pdo->query('SELECT COUNT(*) FROM menu')->fetchColumn();
    if ($jumlahMenu === 0) {
        $idKat = [];
        foreach ($pdo->query('SELECT id, slug FROM kategori') as $r) {
            $idKat[$r['slug']] = (int)$r['id'];
        }
        $stMenu = $pdo->prepare('INSERT INTO menu (kategori_id, nama, deskripsi, harga, foto, urutan)
                                 VALUES (?, ?, ?, ?, ?, ?)');
        $no = 1;
        foreach ($menuAwal as $m) {
            $stMenu->execute([$idKat[$m[0]], $m[1], $m[2], $m[3], $m[4], $no++]);
        }
        $log[] = count($menuAwal) . ' menu dimasukkan ke database.';
    } else {
        $log[] = "Tabel menu sudah berisi $jumlahMenu data, tidak diisi ulang.";
    }

    $pdo->commit();
    $berhasil = true;
} catch (Throwable $ex) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $berhasil = false;
    $log[] = 'GAGAL: ' . $ex->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buat Database | Kopi Khoka</title>
  <style>
    body{font-family:system-ui,sans-serif;max-width:640px;margin:40px auto;padding:0 16px;line-height:1.6}
    .ok{color:#1a7f37}.gagal{color:#b42318}
    li{margin:4px 0}
  </style>
</head>
<body>
  <h1>Buat Database Kopi Khoka</h1>
  <p class="<?= $berhasil ? 'ok' : 'gagal' ?>">
    <strong><?= $berhasil ? 'Berhasil.' : 'Ada masalah.' ?></strong>
  </p>
  <ul>
    <?php foreach ($log as $baris): ?>
      <li><?= htmlspecialchars($baris, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
  </ul>
  <?php if ($berhasil): ?>
    <p>Cek hasilnya di phpMyAdmin. Setelah itu <strong>hapus file buat_database.php</strong> dari server.</p>
  <?php else: ?>
    <p>Periksa apakah MySQL sudah menyala dan user/password di bagian atas file sudah benar.</p>
  <?php endif; ?>
</body>
</html>
