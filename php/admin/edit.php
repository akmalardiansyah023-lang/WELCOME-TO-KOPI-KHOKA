<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../foto.php';
wajib_login();

$pdo = db();
$id  = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$st = $pdo->prepare('SELECT * FROM menu WHERE id = ?');
$st->execute([$id]);
$m = $st->fetch();
if (!$m) {
    $_SESSION['flash'] = ['pesan' => 'Menu tidak ditemukan.', 'jenis' => 'gagal'];
    header('Location: index.php');
    exit;
}

$kategori = $pdo->query('SELECT id, nama FROM kategori ORDER BY urutan')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {          // UPDATE penuh
    if (!csrf_valid()) {
        http_response_code(400);
        exit('Token tidak valid.');
    }
    $nama = trim($_POST['nama'] ?? '');
    $kat  = (int)($_POST['kategori_id'] ?? 0);
    $ada  = $pdo->prepare('SELECT COUNT(*) FROM kategori WHERE id = ?');
    $ada->execute([$kat]);

    if ($nama === '') {
        $error = 'Nama menu wajib diisi.';
    } elseif (!$ada->fetchColumn()) {
        $error = 'Kategori tidak valid.';
    } else {
        $foto = pilih_foto($_POST['foto'] ?? '', $m['foto']);
        $pdo->prepare('UPDATE menu SET kategori_id=?, nama=?, deskripsi=?, harga=?, foto=?, tersedia=? WHERE id=?')
            ->execute([
                $kat,
                mb_substr($nama, 0, 100),
                mb_substr(trim($_POST['deskripsi'] ?? ''), 0, 255),
                max(0, (int)preg_replace('/\D/', '', $_POST['harga'] ?? '0')),
                $foto,
                empty($_POST['tersedia']) ? 0 : 1,
                $id,
            ]);
        $_SESSION['flash'] = ['pesan' => 'Menu "' . $nama . '" diperbarui.', 'jenis' => 'ok'];
        header('Location: index.php');
        exit;
    }
    // tampilkan ulang isian yang barusan diketik
    $m['nama']        = $nama;
    $m['kategori_id'] = $kat;
    $m['deskripsi']   = trim($_POST['deskripsi'] ?? '');
    $m['harga']       = (int)preg_replace('/\D/', '', $_POST['harga'] ?? '0');
    $m['tersedia']    = empty($_POST['tersedia']) ? 0 : 1;
}
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Menu | Kopi Khoka</title>
  <link rel="stylesheet" href="../../style.css">
  <style>
    .adm{max-width:620px;margin:0 auto;padding:24px 16px}
    .adm input[type=text],.adm select,.adm textarea{padding:8px;width:100%;box-sizing:border-box}
    .adm .btn{padding:8px 16px;cursor:pointer;display:inline-block;text-decoration:none}
    .adm .gagal{background:#fde8e8;color:#b42318;padding:8px 12px;border-radius:6px}
    .adm .thumb{width:120px;height:120px;object-fit:cover;border-radius:8px;display:block;margin:6px 0}
    .adm .aksi{display:flex;gap:10px;margin-top:16px}
  </style>
</head>
<body>
<div class="adm">
  <p><a href="index.php">&larr; Kembali ke admin</a></p>
  <h1 class="judul-halaman">Edit Menu</h1>
  <?php if ($error): ?><p class="gagal"><?= e($error) ?></p><?php endif; ?>

  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">

    <div class="field"><label>Nama</label>
      <input type="text" name="nama" value="<?= e($m['nama']) ?>" required maxlength="100"></div>

    <div class="field"><label>Kategori</label>
      <select name="kategori_id" required>
        <?php foreach ($kategori as $k): ?>
          <option value="<?= (int)$k['id'] ?>" <?= (int)$k['id'] === (int)$m['kategori_id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
        <?php endforeach; ?>
      </select></div>

    <div class="field"><label>Harga (Rp)</label>
      <input type="text" name="harga" value="<?= (int)$m['harga'] ?>" inputmode="numeric"></div>

    <div class="field"><label>Deskripsi</label>
      <textarea name="deskripsi" rows="3" maxlength="255"><?= e($m['deskripsi']) ?></textarea></div>

    <div class="field"><label>Foto (pilih dari daftar)</label>
      <?php pilihan_foto_html((string)$m['foto']); ?>
    </div>

    <div class="field"><label><input type="checkbox" name="tersedia" value="1" <?= $m['tersedia'] ? 'checked' : '' ?>> Tampilkan di menu (tersedia)</label></div>

    <div class="aksi">
      <button type="submit" class="btn btn-gelap">Simpan perubahan</button>
      <a class="btn btn-garis-gelap" href="index.php">Batal</a>
    </div>
  </form>
</div>
<?php skrip_pratinjau_foto(); ?>
</body>
</html>
