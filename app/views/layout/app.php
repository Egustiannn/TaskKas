<?php $u = $_SESSION['user']; ?>
<!DOCTYPE html>
<html lang="id"><head><?php include __DIR__ . '/head.php'; ?></head>
<body>
<div class="fallback-orbs"><i></i><i></i></div>
<canvas id="scene" data-variant="dash" aria-hidden="true"></canvas>
<header class="nav glass">
  <a class="brand" href="<?= url('dashboard') ?>">Task<b>Kas</b></a>
  <nav class="links">
    <a href="<?= url('dashboard') ?>">Dashboard</a>
    <?php if ($u['role'] === 'admin'): ?><a href="#kas">Transaksi</a><a href="#iuran">Iuran</a>
    <?php else: ?><a href="#tugas">Tugas</a><a href="#kas">Kas Kelas</a><?php endif; ?>
  </nav>
  <div class="right">
    <span><?= e($u['name']) ?></span><span class="role"><?= e($u['role']) ?></span>
    <a class="btn ghost sm" href="<?= url('auth/logout') ?>">Keluar</a>
  </div>
</header>
<main class="main">
  <?php include __DIR__ . '/flash.php'; ?>
  <?= $content ?>
</main>
<div class="foot">© <?= date('Y') ?> TaskKas</div>
<?php include __DIR__ . '/scripts.php'; ?>
</body></html>
