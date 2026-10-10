<?php
require_once __DIR__ . '/koneksi.php';
session_start();

function sudah_login(): bool {
    return !empty($_SESSION['admin']);
}

function wajib_login(): void {
    if (!sudah_login()) {
        header('Location: login.php');
        exit;
    }
}

function cek_password(string $pw): bool {
    if (ADMIN_PASS_HASH !== '') {
        return password_verify($pw, ADMIN_PASS_HASH);
    }
    return hash_equals(ADMIN_PASS_AWAL, $pw);
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_valid(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}
