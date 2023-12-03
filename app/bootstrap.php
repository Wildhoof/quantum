<?php

declare(strict_types=1);

// Set up the autoloader
spl_autoload_register(function(string $class)
{
    $classpath = str_replace('\\', '/', $class);
    $path =  __DIR__ . '/../src/'. $classpath . '.php';

    if (!file_exists($path)) {
        $message = sprintf('Class %s not found', $class);
        throw new RuntimeException($message);
    }

    require $path;
});