<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$user = $_SESSION['user'];
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Dashboard - Tugas Rutin 7</title><link rel="stylesheet" href="style.css"></head>
<body><div class="dashboard-wrapper"><div class="dashboard-card"><div class="topbar"><div><div class="brand">Tugas Rutin 7</div><h1>Dashboard</h1></div><a href="logout.php" class="logout-btn">Logout</a></div>
<div class="welcome"><div class="avatar"><?= htmlspecialchars(strtoupper(substr($user['name'],0,1))) ?></div><div><h2>Selamat datang, <?= htmlspecialchars($user['name']) ?>! 👋</h2><p>Anda berhasil login ke sistem.</p></div></div>
<div class="profile-grid"><div class="info-box"><span>Nama</span><strong><?= htmlspecialchars($user['name']) ?></strong></div><div class="info-box"><span>Email</span><strong><?= htmlspecialchars($user['email']) ?></strong></div><div class="info-box"><span>Status</span><strong>Aktif</strong></div></div>
<div class="note"><strong>Informasi:</strong> Data akun disimpan pada file JSON dan password tersimpan dalam bentuk hash.</div></div></div></body></html>
