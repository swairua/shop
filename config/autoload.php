<?php
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../models/',
        __DIR__ . '/../helpers/',
        __DIR__ . '/../services/',
        __DIR__ . '/../'
    ];
    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/../helpers/functions.php';
