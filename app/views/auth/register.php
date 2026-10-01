<div class="auth-wrap">
  <section class="auth-box glass">
    <h1>Daftar</h1><div class="uline"></div>
    <?php foreach ($errors as $er): ?><div class="alert err"><?= e($er) ?></div><?php endforeach; ?>
    <?php if (!$classes): ?><div class="alert err">Belum ada data kelas. Import <b>database/taskkas.sql</b> terlebih dahulu.</div><?php endif; ?>
    <form method="post" action="<?= url('auth/register') ?>">
      <?= csrf_field() ?>
      <label>Nama lengkap<input name="name" placeholder="Nama lengkap" value="<?= old('name') ?>" required autofocus></label>
      <label>Kelas
        <select name="class_id" required>
          <option value="">Pilih kelas</option>
          <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= (($_POST['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Email<input type="email" name="email" placeholder="nama@email.com" value="<?= old('email') ?>" required></label>
      <div class="grid2">
        <label>Password
          <span class="pw"><input type="password" name="password" minlength="8" placeholder="Min. 8 karakter" required>
            <button type="button" data-toggle-pass aria-label="Tampilkan password">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></button></span>
        </label>
        <label>Ulangi password<input type="password" name="password2" minlength="8" placeholder="Ulangi password" required></label>
      </div>
      <button class="btn block" type="submit">Buat akun</button>
    </form>
    <p class="switch">Sudah punya akun? <a href="<?= url('auth/login') ?>">Masuk</a></p>
    <?php include ROOT . '/app/views/layout/socials.php'; ?>
  </section>
</div>
