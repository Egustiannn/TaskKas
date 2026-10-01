<?php
/** Aksi kas hanya untuk admin/bendahara, dari dashboard admin. */
class CashController extends Controller {
    private function input(): array {
        $type = ($_POST['type'] ?? '') === 'pengeluaran' ? 'pengeluaran' : 'pemasukan';
        $cat = in_array($_POST['category'] ?? '', ['iuran', 'donasi', 'operasional', 'lainnya'], true) ? $_POST['category'] : 'lainnya';
        $member = (int)($_POST['member_id'] ?? 0);
        return [
            'type' => $type,
            'category' => $cat,
            'member_id' => ($cat === 'iuran' && $member) ? $member : null,
            'amount' => (float)preg_replace('/[^\d.]/', '', $_POST['amount'] ?? '0'),
            'description' => trim($_POST['description'] ?? ''),
            'trx_date' => $_POST['trx_date'] ?? '',
        ];
    }
    private function valid(array $d): bool {
        if ($d['amount'] <= 0 || $d['description'] === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $d['trx_date'])) {
            flash('error', 'Nominal, tanggal, dan keterangan wajib diisi dengan benar.');
            return false;
        }
        return true;
    }
    private function guard() { $this->requireAdmin(); if (!$this->isPost()) $this->redirect('dashboard'); $this->verifyCsrf(); }

    public function create() {
        $this->guard();
        $u = $this->user();
        $d = $this->input();
        if ($this->valid($d)) { Cash::create($u['class_id'], $u['id'], $d); flash('success', 'Transaksi dicatat.'); }
        $this->redirect('dashboard');
    }
    public function update($id = 0) {
        $this->guard();
        $d = $this->input();
        if ($this->valid($d)) { Cash::update((int)$id, $this->user()['class_id'], $d); flash('success', 'Transaksi diperbarui.'); }
        $this->redirect('dashboard');
    }
    public function delete($id = 0) {
        $this->guard();
        Cash::delete((int)$id, $this->user()['class_id']);
        flash('success', 'Transaksi dihapus.');
        $this->redirect('dashboard');
    }
}
