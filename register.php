<?php
session_start();
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$dataFile = __DIR__ . '/data/users.json';
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    if ($name === '') $errors[] = 'Nama wajib diisi.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $confirmPassword) $errors[] = 'Konfirmasi password tidak sama.';
    if (!$errors) {
        if (!file_exists($dataFile)) file_put_contents($dataFile, json_encode([]));
        $users = json_decode(file_get_contents($dataFile), true) ?? [];
        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) { $errors[] = 'Email sudah terdaftar.'; break; }
        }
        if (!$errors) {
            $users[] = ['id'=>uniqid('USR'),'name'=>$name,'email'=>$email,'password'=>password_hash($password, PASSWORD_DEFAULT),'created_at'=>date('Y-m-d H:i:s')];
            file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
            $success = 'Registrasi berhasil. Silakan login.';
        }
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Register - Tugas Rutin 7</title><link rel="stylesheet" href="style.css"></head>
<body><div class="auth-wrapper"><div class="auth-card"><div class="brand">Tugas Rutin 7</div><h1>Daftar Akun</h1><p class="subtitle">Buat akun baru untuk masuk ke sistem.</p>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="POST" novalidate><label for="name">Nama</label><input type="text" id="name" name="name" placeholder="Masukkan nama" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
<label for="email">Email</label><input type="email" id="email" name="email" placeholder="nama@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
<label for="password">Password</label><input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
<label for="confirm_password">Konfirmasi Password</label><input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
<button type="submit" class="btn">Register</button></form><p class="switch">Sudah punya akun? <a href="login.php">Login di sini</a></p></div></div></body></html>
