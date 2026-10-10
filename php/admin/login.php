<?php
require_once __DIR__ . '/../auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    if (hash_equals(ADMIN_USER, $user) && cek_password($pass)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin | Kopi Khoka</title>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <main>
    <section class="kontak">
      <div class="wrap" style="max-width:420px">
        <h1 class="judul-halaman">Login Admin</h1>
        <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
        <form method="post" class="form">
          <div class="field"><label for="user">Username</label>
            <input type="text" id="user" name="user" autocomplete="username" required></div>
          <div class="field"><label for="pass">Password</label>
            <input type="password" id="pass" name="pass" autocomplete="current-password" required></div>
          <button type="submit" class="btn btn-gelap">Masuk</button>
        </form>
      </div>
    </section>
  </main>
</body>
</html>
