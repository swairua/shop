<?php
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false;
}

$_GET['url'] = trim($path, '/');
require __DIR__ . '/index.php';
