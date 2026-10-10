<?php
// Helper upload foto menu -> disimpan di images/menu/
// Mengembalikan path relatif (mis. "images/menu/latte-a1b2c3.png"),
// null jika tidak ada file yang diunggah, atau false jika gagal (isi $err).
function simpan_foto(array $file, string &$err = '')
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $err = 'Upload foto gagal (kode ' . (int)$file['error'] . ').';
        return false;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        $err = 'Ukuran foto maksimal 2 MB.';
        return false;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ekstensi = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($ekstensi[$mime])) {
        $err = 'Format foto harus JPG, PNG, atau WEBP.';
        return false;
    }

    $dasar = pathinfo($file['name'], PATHINFO_FILENAME);
    $dasar = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $dasar));
    $dasar = trim(substr($dasar, 0, 40), '_') ?: 'menu';
    $nama  = $dasar . '-' . bin2hex(random_bytes(3)) . '.' . $ekstensi[$mime];

    $folder = __DIR__ . '/../images/menu/';
    if (!is_dir($folder) && !mkdir($folder, 0775, true)) {
        $err = 'Folder images/menu tidak bisa dibuat.';
        return false;
    }
    if (!move_uploaded_file($file['tmp_name'], $folder . $nama)) {
        $err = 'Foto tidak bisa disimpan ke folder images/menu.';
        return false;
    }
    return 'images/menu/' . $nama;
}

// Rapikan path foto yang diketik manual (cegah ".." dan URL luar)
function bersihkan_path_foto(string $p): string
{
    $p = trim($p);
    if ($p === '' || strpos($p, '..') !== false || preg_match('#^[a-z][a-z0-9+.-]*:#i', $p) || $p[0] === '/' || $p[0] === '\\') {
        return '';
    }
    return mb_substr($p, 0, 200);
}
