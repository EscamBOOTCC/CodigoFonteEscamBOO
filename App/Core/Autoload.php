<?php

spl_autoload_register(
    function ($class) {
        $prefix  = 'App\\';
        $baseDir = __DIR__ . '/../';

        if (strpos($class, $prefix) === 0) {
            $path = substr($class, strlen($prefix));
            $classFile = $baseDir . str_replace('\\', '/', $path) . '.php';

            if (file_exists($classFile)) {
                require_once $classFile;
            }
        }
    }
);
