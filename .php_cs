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
                __DIR__ . '/bin/konstruu',
                __DIR__ . '/.konstruu.php',
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
