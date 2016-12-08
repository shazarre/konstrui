<?php

$finder = Symfony\CS\Finder::create()
    ->in(
        [
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ]
    )
    ->append(
        Symfony\CS\Finder::create()->append(
            [
                __DIR__ . '/autoload.php',
                __DIR__ . '/bin/konstrui',
                __DIR__ . '/.konstrui.php',
                __FILE__,
            ]
        )
    )
    ;

return Symfony\CS\Config::create()
    ->fixers(
        [
            '-concat_without_spaces',
            'concat_with_spaces',
        ]
    )
    ->finder($finder)
    ;
