<?php
class AuthController extends Controller {
    private function startSession(array $u) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int)$u['id'], 'name' => $u['name'], 'role' => $u['role'], 'class_id' => (int)$u['class_id']];
    }

    public function login() {
        if ($this->user()) $this->redirect('dashboard');
        $error = null;
        if ($this->isPost()) {
            $this->verifyCsrf();
            $u = User::findByEmail(trim($_POST['email'] ?? ''));
            if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
                $this->startSession($u);
                $this->redirect('dashboard');
            }
            $error = 'Email atau password salah.';
        }
        $this->view('auth/login', ['title' => 'Masuk', 'scene' => 'auth', 'error' => $error], 'site');
    }

    public function register() {
        if ($this->user()) $this->redirect('dashboard');
        $errors = [];
        if ($this->isPost()) {
            $this->verifyCsrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['password'] ?? '';
            $class = (int)($_POST['class_id'] ?? 0);
            if ($name === '') $errors[] = 'Nama wajib diisi.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
            if (strlen($pass) < 8) $errors[] = 'Password minimal 8 karakter.';
            if ($pass !== ($_POST['password2'] ?? '')) $errors[] = 'Konfirmasi password tidak sama.';
            if (!$class) $errors[] = 'Pilih kelas.';
            if (!$errors && User::findByEmail($email)) $errors[] = 'Email sudah terdaftar.';
            if (!$errors) {
                User::create(['class_id' => $class, 'name' => $name, 'email' => $email, 'password' => $pass]);
                $this->startSession(User::findByEmail($email));   // langsung masuk ke dashboard
                flash('success', 'Akun berhasil dibuat. Selamat datang di TaskKas!');
                $this->redirect('dashboard');
            }
        }
        $this->view('auth/register', ['title' => 'Daftar', 'scene' => 'auth', 'errors' => $errors, 'classes' => User::classes()], 'site');
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . url('auth/login'));
        exit;
    }
}
