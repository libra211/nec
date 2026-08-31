<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$request = Request::capture();

// Serve the app under the /nec base path (local dev via http://localhost/nec).
$base = '/nec';
$path = $request->getPathInfo();
if ($path === $base || str_starts_with($path, $base.'/')) {
    $trimmed = substr($path, strlen($base)) ?: '/';
    $server = $request->server->all();
    $server['REQUEST_URI'] = $trimmed.($request->getQueryString() ? '?'.$request->getQueryString() : '');
    $server['PATH_INFO'] = $trimmed;
    $server['ORIGINAL_REQUEST_URI'] = $request->server->get('REQUEST_URI');
    $request->initialize(
        $request->query->all(),
        $request->request->all(),
        $request->attributes->all(),
        $request->cookies->all(),
        $request->files->all(),
        $server
    );
}

$app->handleRequest($request);
