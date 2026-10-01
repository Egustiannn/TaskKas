<div class="head">
  <div><div class="muted">Panel bendahara</div><h1>Dashboard Admin</h1></div>
  <button class="btn" data-dialog="cashDialog" data-action="<?= url('cash/create') ?>" data-title="Tambah Transaksi">+ Tambah transaksi</button>
</div>

<div class="stats">
  <div class="stat glass"><div class="ico">💰</div><div><small>Saldo kas</small><strong><?= rupiah($balance) ?></strong></div></div>
  <div class="stat glass"><div class="ico">⬇️</div><div><small>Pemasukan bulan ini</small><strong class="in"><?= rupiah($income) ?></strong></div></div>
  <div class="stat glass"><div class="ico">⬆️</div><div><small>Pengeluaran bulan ini</small><strong class="out"><?= rupiah($expense) ?></strong></div></div>
  <div class="stat glass"><div class="ico">👥</div><div><small>Jumlah siswa</small><strong><?= count($members) ?></strong></div></div>
</div>

<div class="cols">
  <section class="panel glass" id="kas">
    <div class="panel-h">
      <h2>Riwayat transaksi</h2>
      <form class="monthf" method="get" action="<?= url('dashboard') ?>#kas">
        <input type="month" name="month" value="<?= e($month) ?>"><button class="btn ghost sm">Filter</button>
        <?php if ($month): ?><a class="btn ghost sm" href="<?= url('dashboard') ?>">Reset</a><?php endif; ?>
      </form>
    </div>
    <?php if (!$rows): ?><div class="empty">Belum ada transaksi.</div><?php else: ?>
    <div class="tscroll"><table>
      <thead><tr><th>Tanggal</th><th>Keterangan</th><th class="r">Nominal</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= tgl($r['trx_date']) ?></td>
          <td><?= e($r['description']) ?><div class="sub"><span class="pill"><?= e($r['category']) ?></span><?= $r['member_name'] ? ' · ' . e($r['member_name']) : '' ?></div></td>
          <td class="r <?= $r['type'] === 'pemasukan' ? 'in' : 'out' ?>"><?= $r['type'] === 'pemasukan' ? '+' : '−' ?><?= rupiah($r['amount']) ?></td>
          <td><div class="acts">
            <button class="btn ghost sm" data-dialog="cashDialog" data-title="Edit Transaksi" data-action="<?= url('cash/update/' . $r['id']) ?>"
              data-values="<?= e(json_encode(['type' => $r['type'], 'category' => $r['category'], 'member_id' => $r['member_id'], 'amount' => (int)$r['amount'], 'trx_date' => $r['trx_date'], 'description' => $r['description']])) ?>">Edit</button>
            <form method="post" action="<?= url('cash/delete/' . $r['id']) ?>" onsubmit="return confirm('Hapus transaksi ini?')"><?= csrf_field() ?>
              <button class="btn danger sm">Hapus</button></form>
          </div></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </section>

  <section class="panel glass" id="iuran">
    <div class="panel-h"><h2>Rekap iuran</h2><span class="muted" style="font-size:.8rem">Total per siswa</span></div>
    <?php if (!$dues): ?><div class="empty">Belum ada siswa.</div><?php endif; ?>
    <?php foreach ($dues as $d): ?>
      <div class="row"><div class="grow ttl"><?= e($d['name']) ?></div>
        <strong class="<?= $d['total'] > 0 ? 'in' : 'muted' ?>"><?= rupiah($d['total']) ?></strong></div>
    <?php endforeach; ?>
  </section>
</div>

<dialog id="cashDialog"><div class="dlg glass">
  <h2 data-title>Transaksi</h2>
  <form method="post" action="" data-cashform>
    <?= csrf_field() ?>
    <div class="grid2">
      <label>Jenis<select name="type"><option value="pemasukan">Pemasukan</option><option value="pengeluaran">Pengeluaran</option></select></label>
      <label>Kategori<select name="category"><option value="iuran">Iuran</option><option value="donasi">Donasi</option><option value="operasional">Operasional</option><option value="lainnya">Lainnya</option></select></label>
    </div>
    <label data-member>Pembayar iuran
      <select name="member_id"><option value="">Pilih siswa</option>
        <?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?>
      </select></label>
    <div class="grid2">
      <label>Nominal (Rp)<input type="number" name="amount" min="1" step="1" required></label>
      <label>Tanggal<input type="date" name="trx_date" value="<?= date('Y-m-d') ?>" required></label>
    </div>
    <label>Keterangan<input name="description" placeholder="Iuran bulanan - Budi" required></label>
    <div class="acts"><button class="btn" type="submit">Simpan</button><button class="btn ghost" type="button" data-close>Batal</button></div>
  </form>
</div></dialog>
