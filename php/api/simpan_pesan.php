<?php
// POST nama, pesan -> disimpan ke tabel pesan_kontak
require_once __DIR__ . '/../koneksi.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Gunakan POST']);
    exit;
}

$nama  = trim($_POST['nama']  ?? '');
$pesan = trim($_POST['pesan'] ?? '');

if (mb_strlen($nama) < 2 || mb_strlen($pesan) < 5) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Nama minimal 2 huruf, pesan minimal 5 huruf']);
    exit;
}

try {
    $st = db()->prepare('INSERT INTO pesan_kontak (nama, pesan) VALUES (?, ?)');
    $st->execute([mb_substr($nama, 0, 100), mb_substr($pesan, 0, 2000)]);
    echo json_encode(['ok' => true]);
} catch (Throwable $ex) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Gagal menyimpan pesan']);
}
