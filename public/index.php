<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// --- TAMBAHKAN KODE INI DI SINI ---
if (isset($_SERVER['VERCEL_URL'])) {
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions',
    ];
    foreach ($storageDirs as $dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
// ---------------------------------

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
