<?php

return [
    'tasks' => [
        'composer' => [
            'task' => new \Konstrui\Task\ExecutableTask('composer install'),
        ],
        'tests' => [
            'task' => new \Konstrui\Task\ExecutableTask(
                './vendor/bin/phpunit --configuration=phpunit.xml'
            ),
            'dependencies' => [
                'composer',
            ],
        ],
        'cleanup' => [
            'task' => new \Konstrui\Task\CleanTask(
                [
                    __DIR__ . '/code-coverage-report',
                    __DIR__ . '/cache',
                    __DIR__ . '/clover.xml',
                ]
            ),
        ],
    ],
];
