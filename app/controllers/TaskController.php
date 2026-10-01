<?php
/** Semua aksi tugas berupa POST dari dashboard user, lalu kembali ke dashboard. */
class TaskController extends Controller {
    private function input(): array {
        $pr = $_POST['priority'] ?? 'sedang';
        return [
            'subject' => trim($_POST['subject'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? '') ?: null,
            'deadline' => $_POST['deadline'] ?? '',
            'priority' => in_array($pr, ['rendah', 'sedang', 'tinggi'], true) ? $pr : 'sedang',
            'status' => ($_POST['status'] ?? 'belum') === 'selesai' ? 'selesai' : 'belum',
        ];
    }
    private function valid(array $d): bool {
        if ($d['subject'] === '' || $d['title'] === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $d['deadline'])) {
            flash('error', 'Mapel, judul, dan deadline wajib diisi dengan benar.');
            return false;
        }
        return true;
    }
    private function guard() { $this->requireLogin(); if (!$this->isPost()) $this->redirect('dashboard'); $this->verifyCsrf(); }

    public function create() {
        $this->guard();
        $d = $this->input();
        if ($this->valid($d)) { Task::create($this->user()['id'], $d); flash('success', 'Tugas ditambahkan.'); }
        $this->redirect('dashboard');
    }
    public function update($id = 0) {
        $this->guard();
        $d = $this->input();
        if ($this->valid($d)) { Task::update((int)$id, $this->user()['id'], $d); flash('success', 'Tugas diperbarui.'); }
        $this->redirect('dashboard');
    }
    public function toggle($id = 0) {
        $this->guard();
        Task::toggle((int)$id, $this->user()['id']);
        $this->redirect('dashboard');
    }
    public function delete($id = 0) {
        $this->guard();
        Task::delete((int)$id, $this->user()['id']);
        flash('success', 'Tugas dihapus.');
        $this->redirect('dashboard');
    }
}
