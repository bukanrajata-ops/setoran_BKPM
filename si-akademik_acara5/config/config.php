<?php
// config/config.php
// Autoloader sederhana: memetakan namespace App\ ke folder app/

spl_autoload_register(function ($class) {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});


// supaya tetap benar walau URL sudah bertingkat (misal /mahasiswa/23001).
// Sesuaikan dengan nama folder project kamu di htdocs.
define('BASE_URL', '/si-akademik_acara5/public');
