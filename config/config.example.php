<?php
// Salin file ini menjadi config.php lalu sesuaikan.
return [
    'app_name' => 'TaskKas',
    // XAMPP: /taskkas/public  |  jika document root sudah ke folder public: ''
    'base_url' => '/taskkas/public',
    'db' => [
        'host' => 'localhost',
        'name' => 'taskkas',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
];
