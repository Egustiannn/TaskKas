<?php
session_start();
define('ROOT', dirname(__DIR__));
$config = require ROOT . '/config/config.php';
define('BASE_URL', $config['base_url']);
define('APP_NAME', $config['app_name']);

require ROOT . '/core/helpers.php';
require ROOT . '/core/Database.php';
require ROOT . '/core/Controller.php';
require ROOT . '/core/App.php';

Database::configure($config['db']);
spl_autoload_register(function ($class) {
    foreach (['app/models', 'app/controllers'] as $dir) {
        $f = ROOT . "/$dir/$class.php";
        if (is_file($f)) { require $f; return; }
    }
});

try {
    (new App())->run();
} catch (PDOException $ex) {
    while (ob_get_level()) ob_end_clean();
    http_response_code(500);
    echo '<!DOCTYPE html><meta charset="utf-8"><title>Database error</title>'
       . '<body style="background:#050506;color:#f4f4f5;font-family:system-ui;max-width:640px;margin:12vh auto;padding:0 1.2rem">'
       . '<h1>Database belum siap</h1><p style="color:#8c8c95">Pastikan: (1) MySQL di XAMPP sudah Start, '
       . '(2) <b>database/taskkas.sql</b> sudah diimport, (3) kredensial di <b>config/config.php</b> benar.</p>'
       . '<pre style="background:#151517;padding:1rem;border-radius:12px;white-space:pre-wrap;color:#fb7185">'
       . htmlspecialchars($ex->getMessage()) . '</pre></body>';
}
