<?php
// Helper pilihan foto menu: membaca file gambar yang sudah ada di images/menu/

// Daftar path foto, mis. ["images/menu/latte.png", ...]
function daftar_foto(): array
{
    $folder = __DIR__ . '/../images/menu/';
    $hasil = [];
    if (is_dir($folder)) {
        foreach (scandir($folder) as $f) {
            if (preg_match('/\.(jpe?g|png|webp)$/i', $f)) {
                $hasil[] = 'images/menu/' . $f;
            }
        }
    }
    sort($hasil, SORT_NATURAL | SORT_FLAG_CASE);
    return $hasil;
}

// Nama tampilan: "images/menu/khoka_aren_soft.png" -> "Khoka Aren Soft"
function label_foto(string $path): string
{
    $nama = pathinfo($path, PATHINFO_FILENAME);
    return ucwords(str_replace(['_', '-'], ' ', $nama));
}

// Terima nilai dari dropdown hanya jika ada di daftar (atau sama dengan foto lama)
function pilih_foto($nilai, string $fotoLama = ''): string
{
    $nilai = trim((string)$nilai);
    if ($nilai === '') {
        return '';
    }
    if ($nilai === $fotoLama || in_array($nilai, daftar_foto(), true)) {
        return $nilai;
    }
    return '';
}

// Cetak <select> pilihan foto + kotak pratinjau
function pilihan_foto_html(string $terpilih = '', string $prefixUrl = '../../'): void
{
    $daftar = daftar_foto();
    if ($terpilih !== '' && !in_array($terpilih, $daftar, true)) {
        array_unshift($daftar, $terpilih);   // foto lama yang file-nya tidak ada di folder
    }
    ?>
    <select name="foto" class="pilih-foto" data-prefix="<?= htmlspecialchars($prefixUrl, ENT_QUOTES, 'UTF-8') ?>">
      <option value="">(Tanpa foto)</option>
      <?php foreach ($daftar as $p): ?>
        <option value="<?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?>" <?= $p === $terpilih ? 'selected' : '' ?>>
          <?= htmlspecialchars(label_foto($p), ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    </select>
    <img class="preview-foto" alt="Pratinjau foto" style="display:none;width:120px;height:120px;object-fit:cover;border-radius:8px;margin-top:8px">
    <?php
}

// Skrip pratinjau (cukup dicetak sekali per halaman)
function skrip_pratinjau_foto(): void
{
    ?>
    <script>
      document.querySelectorAll('.pilih-foto').forEach(function (sel) {
        var img = sel.parentNode.querySelector('.preview-foto');
        function tampil() {
          if (sel.value) { img.src = sel.dataset.prefix + sel.value; img.style.display = 'block'; }
          else { img.removeAttribute('src'); img.style.display = 'none'; }
        }
        sel.addEventListener('change', tampil);
        img.addEventListener('error', function () { img.style.display = 'none'; });
        tampil();
      });
    </script>
    <?php
}
