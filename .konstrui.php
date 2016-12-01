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
    ],
];
