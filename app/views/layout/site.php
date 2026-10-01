<!DOCTYPE html>
<html lang="id"><head><?php include __DIR__ . '/head.php'; ?></head>
<body>
<div class="fallback-orbs"><i></i><i></i></div>
<canvas id="scene" data-variant="<?= e($scene ?? 'hero') ?>" aria-hidden="true"></canvas>
<header class="nav glass">
  <a class="brand" href="<?= url('/') ?>">Task<b>Kas</b></a>
  <nav class="links"><a href="<?= url('/') ?>#fitur">Fitur</a><a href="<?= url('/') ?>#peran">Peran</a><a href="<?= url('/') ?>#cara">Cara kerja</a></nav>
  <div class="right">
    <?php if (!empty($_SESSION['user'])): ?>
      <a class="btn sm" href="<?= url('dashboard') ?>">Buka Dashboard</a>
    <?php else: ?>
      <a href="<?= url('auth/login') ?>" class="muted">Masuk</a>
      <a class="btn sm" href="<?= url('auth/register') ?>">Daftar</a>
    <?php endif; ?>
  </div>
</header>
<?= $content ?>
<footer class="foot">© <?= date('Y') ?> TaskKas — tugas beres, kas transparan.</footer>
<?php include __DIR__ . '/scripts.php'; ?>
</body></html>
