<?php

require __DIR__ . '/autoload.php';

if (file_exists(getcwd() . '/' . \Konstrui\Definition\Provider\PhpProvider::PHP_BUILD_FILE)) {
    $phpProvider = new \Konstrui\Definition\Provider\PhpProvider(
        require \Konstrui\Definition\Provider\PhpProvider::PHP_BUILD_FILE
    );
    $definition = $phpProvider->provideDefinition();
    $resolver = new \Konstrui\Resolver\Resolver($definition);
    $router = new \Konstrui\Cli\Router(
        new \Konstrui\Runner\Runner($resolver, new \Konstrui\Logger\StandardLogger()),
        new \Konstrui\Cli\Command\CommandFactory(
            $definition,
            $argv[0],
            new \Konstrui\Version()
        )
    );
    $router->route($argv);
} else {
    echo 'No build file found.' . PHP_EOL;

    exit(1);
}
