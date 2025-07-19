<?php

use Illuminate\Http\Request;

$_SERVER['SCRIPT_NAME'] = '/oficinas/public/index.php';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
$_SERVER['PHP_SELF'] = '/oficinas/public/index.php';

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
