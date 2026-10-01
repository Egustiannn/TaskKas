<!DOCTYPE html>
<html lang="id"><head><?php include __DIR__ . '/head.php'; ?></head>
<body>
<div class="fallback-orbs"><i></i><i></i></div>
<canvas id="scene" class="front" data-variant="auth" aria-hidden="true"></canvas>
<?= $content ?>
<div class="anim-controls">
  <button class="round" id="animToggle" type="button" aria-label="Putar / jeda animasi">
    <svg class="i-pause" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>
    <svg class="i-play" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
  </button>
</div>
<?php include __DIR__ . '/scripts.php'; ?>
</body></html>
