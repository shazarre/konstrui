<?php

return [
    'tasks' => [
        'composer' => [
            'task' => new \Konstrui\Task\ComposerTask(),
            'description' => 'Installs non-development composer dependencies.',
        ],
        'composer-dev' => [
            'task' => new \Konstrui\Task\ComposerTask(
                \Konstrui\Task\ComposerTask::MODE_INSTALL,
                true
            ),
            'description' => 'Installs all composer dependencies (including dev).',
        ],
        'tests' => [
            'task' => new \Konstrui\Task\PhpUnitTask(),
            'dependencies' => [
                'composer-dev',
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
