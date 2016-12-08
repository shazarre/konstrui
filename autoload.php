<?php
/**
 * Inits autoload. In case composer autoload is not found, will be able
 * to load Konstrui\ classes on its own.
 *
 * TODO should not try to load classes outside from Konstrui\ namespace.
 */
$autoloadLookupPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
];

foreach ($autoloadLookupPaths as $path) {
    if (file_exists($path)) {
        require $path;

        return;
    }
}

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $path = __DIR__ . '/src/' . str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            $className
        ) . '.php';

    require $path;
});
