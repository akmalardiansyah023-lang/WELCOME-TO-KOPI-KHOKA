<?php
// GET php/api/menu.php -> {"kategori":[{slug,nama}], "menu":[{nama,deskripsi,harga,foto,kategori}]}
require_once __DIR__ . '/../koneksi.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $pdo = db();
    $kategori = $pdo->query('SELECT slug, nama FROM kategori ORDER BY urutan, id')->fetchAll();
    $menu = $pdo->query('SELECT m.nama, m.deskripsi, m.harga, m.foto, k.slug AS kategori
                         FROM menu m JOIN kategori k ON k.id = m.kategori_id
                         WHERE m.tersedia = 1
                         ORDER BY k.urutan, m.urutan, m.id')->fetchAll();
    foreach ($menu as &$m) {
        $m['harga'] = (int)$m['harga'];
    }
    unset($m);
    echo json_encode(['kategori' => $kategori, 'menu' => $menu], JSON_UNESCAPED_UNICODE);
} catch (Throwable $ex) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mengambil data menu']);
}
