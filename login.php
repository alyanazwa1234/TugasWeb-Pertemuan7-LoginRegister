<?php
session_start();
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$dataFile = __DIR__ . '/data/users.json'; $error = '';
if (isset($_COOKIE['remember_email']) && !isset($_POST['email'])) $_POST['email'] = $_COOKIE['remember_email'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? ''; $remember = isset($_POST['remember']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Email tidak valid.';
    elseif ($password === '') $error = 'Password wajib diisi.';
    elseif (!file_exists($dataFile)) $error = 'Belum ada akun. Silakan register terlebih dahulu.';
    else {
        $users = json_decode(file_get_contents($dataFile), true) ?? []; $foundUser = null;
        foreach ($users as $user) if (strtolower($user['email']) === strtolower($email)) { $foundUser = $user; break; }
        if ($foundUser && password_verify($password, $foundUser['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id'=>$foundUser['id'],'name'=>$foundUser['name'],'email'=>$foundUser['email']];
            if ($remember) setcookie('remember_email', $email, time()+30*24*60*60, '/');
            else setcookie('remember_email', '', time()-3600, '/');
            header('Location: dashboard.php'); exit;
        } else $error = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Login - Tugas Rutin 7</title><link rel="stylesheet" href="style.css"></head>
<body><div class="auth-wrapper"><div class="auth-card"><div class="brand">Tugas Rutin 7</div><h1>Login</h1><p class="subtitle">Masuk ke sistem Login/Register sederhana.</p>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="POST" novalidate><label for="email">Email</label><input type="email" id="email" name="email" placeholder="nama@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
<label for="password">Password</label><input type="password" id="password" name="password" placeholder="Masukkan password" required>
<label class="remember"><input type="checkbox" name="remember" <?= isset($_POST['remember'])?'checked':'' ?>><span>Remember Me</span></label>
<button type="submit" class="btn">Login</button></form><p class="switch">Belum punya akun? <a href="register.php">Register di sini</a></p></div></div></body></html>
