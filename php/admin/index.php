<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../foto.php';
wajib_login();

$pdo = db();

function flash(string $pesan, string $jenis = 'ok'): void {
    $_SESSION['flash'] = ['pesan' => $pesan, 'jenis' => $jenis];
}
function ke_index(): void {
    header('Location: index.php');
    exit;
}
function ambil_harga(): int {
    return max(0, (int)preg_replace('/\D/', '', $_POST['harga'] ?? '0'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        http_response_code(400);
        exit('Token tidak valid.');
    }
    $aksi = $_POST['aksi'] ?? '';
    $id   = (int)($_POST['id'] ?? 0);

    if ($aksi === 'tambah') {                       // CREATE
        $nama = trim($_POST['nama'] ?? '');
        $kat  = (int)($_POST['kategori_id'] ?? 0);
        if ($nama === '' || $kat <= 0) {
            flash('Nama dan kategori wajib diisi.', 'gagal');
            ke_index();
        }
        $foto = pilih_foto($_POST['foto'] ?? '');
        $pdo->prepare('INSERT INTO menu (kategori_id, nama, deskripsi, harga, foto, urutan)
                       VALUES (?, ?, ?, ?, ?, 999)')
            ->execute([
                $kat,
                mb_substr($nama, 0, 100),
                mb_substr(trim($_POST['deskripsi'] ?? ''), 0, 255),
                ambil_harga(),
                $foto,
            ]);
        flash('Menu "' . $nama . '" ditambahkan.');
    } elseif ($aksi === 'harga' && $id) {           // UPDATE cepat (harga)
        $pdo->prepare('UPDATE menu SET harga = ? WHERE id = ?')->execute([ambil_harga(), $id]);
        flash('Harga diperbarui.');
    } elseif ($aksi === 'tersedia' && $id) {        // UPDATE cepat (status)
        $pdo->prepare('UPDATE menu SET tersedia = 1 - tersedia WHERE id = ?')->execute([$id]);
        flash('Status menu diubah.');
    } elseif ($aksi === 'hapus' && $id) {           // DELETE
        $pdo->prepare('DELETE FROM menu WHERE id = ?')->execute([$id]);
        flash('Menu dihapus.');
    } elseif ($aksi === 'dibaca' && $id) {
        $pdo->prepare('UPDATE pesan_kontak SET dibaca = 1 WHERE id = ?')->execute([$id]);
    } elseif ($aksi === 'hapus_pesan' && $id) {
        $pdo->prepare('DELETE FROM pesan_kontak WHERE id = ?')->execute([$id]);
        flash('Pesan dihapus.');
    }
    ke_index();   // cegah kirim ulang saat halaman di-refresh
}

$notif = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$kategori = $pdo->query('SELECT id, nama FROM kategori ORDER BY urutan')->fetchAll();
$menu = $pdo->query('SELECT m.*, k.nama AS kategori FROM menu m
                     JOIN kategori k ON k.id = m.kategori_id
                     ORDER BY k.urutan, m.urutan, m.id')->fetchAll();
$pesan = $pdo->query('SELECT * FROM pesan_kontak ORDER BY id DESC LIMIT 100')->fetchAll();
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Kopi Khoka</title>
  <link rel="stylesheet" href="../../style.css">
  <style>
    .adm{max-width:1000px;margin:0 auto;padding:24px 16px}
    .adm h2{margin:32px 0 12px}
    .adm table{width:100%;border-collapse:collapse;font-size:.95rem}
    .adm th,.adm td{padding:8px;border-bottom:1px solid #ddd;text-align:left;vertical-align:middle}
    .adm .scroll{overflow-x:auto}
    .adm input,.adm select,.adm textarea{padding:6px;max-width:100%}
    .adm form.inline{display:inline}
    .adm .btn{padding:6px 12px;font-size:.85rem;cursor:pointer;display:inline-block;text-decoration:none}
    .adm .off{opacity:.5}
    .adm .baru{font-weight:700}
    .adm .notif{background:#e6f4ea;padding:8px 12px;border-radius:6px}
    .adm .notif.gagal{background:#fde8e8;color:#b42318}
    .adm .thumb{width:44px;height:44px;object-fit:cover;border-radius:6px;background:#eee;display:block}
    .adm .cari{margin:0 0 10px;width:100%;max-width:320px}
  </style>
</head>
<body>
<div class="adm">
  <p><a href="../../index.html">&larr; Ke website</a> · <a href="keluar.php">Keluar</a></p>
  <h1 class="judul-halaman">Admin Kopi Khoka</h1>
  <?php if ($notif): ?><p class="notif <?= $notif['jenis'] === 'gagal' ? 'gagal' : '' ?>"><?= e($notif['pesan']) ?></p><?php endif; ?>

  <h2>Pesan masuk (<?= count($pesan) ?>)</h2>
  <div class="scroll"><table>
    <tr><th>Waktu</th><th>Nama</th><th>Pesan</th><th></th></tr>
    <?php foreach ($pesan as $p): ?>
    <tr class="<?= $p['dibaca'] ? '' : 'baru' ?>">
      <td><?= e($p['dibuat']) ?></td>
      <td><?= e($p['nama']) ?></td>
      <td><?= nl2br(e($p['pesan'])) ?></td>
      <td>
        <?php if (!$p['dibaca']): ?>
        <form method="post" class="inline"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="aksi" value="dibaca"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <button class="btn btn-garis-gelap">Tandai dibaca</button></form>
        <?php endif; ?>
        <form method="post" class="inline" onsubmit="return confirm('Hapus pesan ini?')">
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="aksi" value="hapus_pesan"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <button class="btn btn-garis-gelap">Hapus</button></form>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$pesan): ?><tr><td colspan="4">Belum ada pesan.</td></tr><?php endif; ?>
  </table></div>

  <h2>Daftar menu (<?= count($menu) ?>)</h2>
  <input type="search" id="cari" class="cari" placeholder="Cari nama menu...">
  <div class="scroll"><table id="tabel-menu">
    <tr><th>Foto</th><th>Kategori</th><th>Nama</th><th>Harga (Rp)</th><th>Aksi</th></tr>
    <?php foreach ($menu as $m): ?>
    <tr class="<?= $m['tersedia'] ? '' : 'off' ?>">
      <td><?php if ($m['foto']): ?><img class="thumb" src="../../<?= e($m['foto']) ?>" alt=""><?php else: ?><span class="thumb"></span><?php endif; ?></td>
      <td><?= e($m['kategori']) ?></td>
      <td class="nama"><?= e($m['nama']) ?></td>
      <td>
        <form method="post" class="inline"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="aksi" value="harga"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <input type="text" name="harga" value="<?= (int)$m['harga'] ?>" size="7" inputmode="numeric">
          <button class="btn btn-garis-gelap">Simpan</button></form>
      </td>
      <td>
        <a class="btn btn-garis-gelap" href="edit.php?id=<?= (int)$m['id'] ?>">Edit</a>
        <form method="post" class="inline"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="aksi" value="tersedia"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <button class="btn btn-garis-gelap"><?= $m['tersedia'] ? 'Sembunyikan' : 'Tampilkan' ?></button></form>
        <form method="post" class="inline" onsubmit="return confirm('Hapus menu ini?')">
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <button class="btn btn-garis-gelap">Hapus</button></form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>

  <h2>Tambah menu</h2>
  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <input type="hidden" name="aksi" value="tambah">
    <div class="field"><label>Nama</label><input type="text" name="nama" required maxlength="100"></div>
    <div class="field"><label>Kategori</label>
      <select name="kategori_id" required>
        <?php foreach ($kategori as $k): ?><option value="<?= (int)$k['id'] ?>"><?= e($k['nama']) ?></option><?php endforeach; ?>
      </select></div>
    <div class="field"><label>Harga (Rp)</label><input type="text" name="harga" inputmode="numeric" placeholder="15000"></div>
    <div class="field"><label>Deskripsi</label><textarea name="deskripsi" rows="2" maxlength="255"></textarea></div>
    <div class="field"><label>Foto (pilih dari daftar)</label>
      <?php pilihan_foto_html(); ?>
    </div>
    <button type="submit" class="btn btn-gelap">Tambah</button>
  </form>
</div>
<script>
  // Pencarian cepat di tabel menu
  document.getElementById('cari').addEventListener('input', function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#tabel-menu tr').forEach(function (tr, i) {
      if (i === 0) return;
      var n = tr.querySelector('.nama');
      tr.style.display = !q || (n && n.textContent.toLowerCase().indexOf(q) > -1) ? '' : 'none';
    });
  });
</script>
<?php skrip_pratinjau_foto(); ?>
</body>
</html>
