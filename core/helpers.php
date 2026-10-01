<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function url($p = '') { return BASE_URL . '/' . ltrim($p, '/'); }
function rupiah($n) { return 'Rp' . number_format((float)$n, 0, ',', '.'); }
function tgl($d) {
    if (!$d) return '-';
    $b = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($d);
    return date('j', $t) . ' ' . $b[(int)date('n', $t)] . ' ' . date('Y', $t);
}
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function flash($key, $val = null) {
    if ($val !== null) { $_SESSION['flash'][$key] = $val; return null; }
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
}
function old($k, $default = '') { return e($_POST[$k] ?? $default); }
function is_admin() { return ($_SESSION['user']['role'] ?? '') === 'admin'; }
