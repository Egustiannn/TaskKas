<div class="auth-wrap">
  <section class="auth-box glass">
    <h1>Masuk</h1><div class="uline"></div>
    <?php include ROOT . '/app/views/layout/flash.php'; ?>
    <?php if (!empty($error)): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= url('auth/login') ?>">
      <?= csrf_field() ?>
      <label>Email<input type="email" name="email" placeholder="nama@email.com" value="<?= old('email') ?>" required autofocus></label>
      <label>Password
        <span class="pw"><input type="password" name="password" placeholder="Masukkan password..." required>
          <button type="button" data-toggle-pass aria-label="Tampilkan password">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></button></span>
      </label>
      <button class="btn block" type="submit">Masuk</button>
    </form>
    <p class="switch">Belum punya akun? <a href="<?= url('auth/register') ?>">Daftar sekarang</a></p>
    <?php include ROOT . '/app/views/layout/socials.php'; ?>
  </section>
</div>
