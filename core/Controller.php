<?php
class Controller {
    /** Layout: site (landing), auth (login/daftar), app (dashboard) */
    protected function view(string $path, array $data = [], string $layout = 'app') {
        extract($data);
        ob_start();
        require ROOT . "/app/views/$path.php";
        $content = ob_get_clean();
        require ROOT . "/app/views/layout/$layout.php";
    }
    protected function redirect(string $path) { header('Location: ' . url($path)); exit; }
    protected function user() { return $_SESSION['user'] ?? null; }
    protected function requireLogin() { if (!$this->user()) $this->redirect('auth/login'); }
    protected function requireAdmin() {
        $this->requireLogin();
        if (!is_admin()) { http_response_code(403); exit('Akses ditolak: khusus admin/bendahara.'); }
    }
    protected function verifyCsrf() {
        if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? null)) {
            http_response_code(419); exit('Token tidak valid. Muat ulang halaman.');
        }
    }
    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
}
