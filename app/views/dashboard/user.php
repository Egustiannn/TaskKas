<div class="head">
  <div><div class="muted">Selamat datang kembali</div><h1><?= e($_SESSION['user']['name']) ?></h1></div>
  <button class="btn" data-dialog="taskDialog" data-action="<?= url('task/create') ?>" data-title="Tambah Tugas">+ Tambah tugas</button>
</div>

<div class="stats">
  <div class="stat glass"><div class="ico">📚</div><div><small>Tugas aktif</small><strong><?= (int)$activeTasks ?></strong></div></div>
  <div class="stat glass"><div class="ico">⏰</div><div><small>Deadline terdekat</small><strong><?= $nearest ? e($nearest['subject']) . ', ' . tgl($nearest['deadline']) : 'Tidak ada' ?></strong></div></div>
  <div class="stat glass"><div class="ico">💰</div><div><small>Saldo kelas</small><strong><?= rupiah($balance) ?></strong></div></div>
  <div class="stat glass"><div class="ico">💸</div><div><small>Pengeluaran bulan ini</small><strong><?= rupiah($expense) ?></strong></div></div>
</div>

<div class="cols">
  <section class="panel glass" id="tugas">
    <div class="panel-h"><h2>Tugas saya</h2></div>
    <div class="seg">
      <?php foreach (['semua' => 'Semua', 'belum' => 'Belum selesai', 'selesai' => 'Selesai'] as $k => $v): ?>
        <a class="<?= $filter === $k ? 'on' : '' ?>" href="<?= url('dashboard') ?>?status=<?= $k ?>#tugas"><?= $v ?></a>
      <?php endforeach; ?>
    </div>
    <?php if (!$tasks): ?><div class="empty">Belum ada tugas. 🎉</div><?php endif; ?>
    <?php foreach ($tasks as $t): $late = $t['status'] === 'belum' && $t['deadline'] < date('Y-m-d'); ?>
      <div class="row <?= $t['status'] === 'selesai' ? 'done' : '' ?>">
        <form method="post" action="<?= url('task/toggle/' . $t['id']) ?>"><?= csrf_field() ?>
          <button class="chk" title="Ubah status">✓</button></form>
        <div class="grow">
          <div class="ttl"><?= e($t['title']) ?></div>
          <div class="sub"><?= e($t['subject']) ?> · <span class="<?= $late ? 'late' : '' ?>"><?= tgl($t['deadline']) ?><?= $late ? ' (lewat)' : '' ?></span>
            <?php if ($t['description']): ?> · <?= e($t['description']) ?><?php endif; ?></div>
        </div>
        <span class="pill p-<?= e($t['priority']) ?>"><?= e($t['priority']) ?></span>
        <div class="acts">
          <button class="btn ghost sm" data-dialog="taskDialog" data-title="Edit Tugas" data-action="<?= url('task/update/' . $t['id']) ?>"
            data-values="<?= e(json_encode(['subject' => $t['subject'], 'title' => $t['title'], 'description' => $t['description'], 'deadline' => $t['deadline'], 'priority' => $t['priority'], 'status' => $t['status']])) ?>">Edit</button>
          <form method="post" action="<?= url('task/delete/' . $t['id']) ?>" onsubmit="return confirm('Hapus tugas ini?')"><?= csrf_field() ?>
            <button class="btn danger sm">Hapus</button></form>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <section class="panel glass" id="kas">
    <div class="panel-h"><h2>Kas kelas</h2><span class="muted" style="font-size:.8rem">Transaksi terbaru</span></div>
    <?php if (!$recent): ?><div class="empty">Belum ada transaksi.</div><?php endif; ?>
    <?php foreach ($recent as $r): ?>
      <div class="row">
        <div class="grow"><div class="ttl"><?= e($r['description']) ?></div><div class="sub"><?= tgl($r['trx_date']) ?> · <?= e($r['category']) ?></div></div>
        <strong class="<?= $r['type'] === 'pemasukan' ? 'in' : 'out' ?>"><?= $r['type'] === 'pemasukan' ? '+' : '−' ?><?= rupiah($r['amount']) ?></strong>
      </div>
    <?php endforeach; ?>
  </section>
</div>

<dialog id="taskDialog"><div class="dlg glass">
  <h2 data-title>Tugas</h2>
  <form method="post" action="">
    <?= csrf_field() ?>
    <label>Mata pelajaran<input name="subject" placeholder="Matematika" required></label>
    <label>Judul tugas<input name="title" placeholder="Latihan soal bab 3" required></label>
    <label>Catatan<textarea name="description" rows="2" placeholder="Opsional"></textarea></label>
    <div class="grid2">
      <label>Deadline<input type="date" name="deadline" required></label>
      <label>Prioritas<select name="priority"><option value="rendah">Rendah</option><option value="sedang" selected>Sedang</option><option value="tinggi">Tinggi</option></select></label>
    </div>
    <label>Status<select name="status"><option value="belum">Belum selesai</option><option value="selesai">Selesai</option></select></label>
    <div class="acts"><button class="btn" type="submit">Simpan</button><button class="btn ghost" type="button" data-close>Batal</button></div>
  </form>
</div></dialog>
