<?php

return [
    'tasks' => [
        'composer' => [
            'task' => new \Konstrui\Task\ExecutableTask('composer install'),
            'description' => 'Installs all composer dependencies.',
        ],
        'tests' => [
            'task' => new \Konstrui\Task\ExecutableTask(
                './vendor/bin/phpunit --configuration=phpunit.xml'
            ),
            'dependencies' => [
                'composer',
            ],
            'description' => 'Runs unit and integration tests.',
        ],
        'cleanup' => [
            'task' => new \Konstrui\Task\CleanTask(
                [
                    __DIR__ . '/code-coverage-report',
                    __DIR__ . '/cache',
                    __DIR__ . '/clover.xml',
                ]
            ),
            'description' => 'Performs a cleanup after running tests.',
        ],
    ],
];
