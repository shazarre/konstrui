<?php

return [
    'tasks' => [
        'composer' => [
            'task' => new \Konstruu\Task\ExecutableTask('composer install'),
        ],
        'tests' => [
            'task' => new \Konstruu\Task\ExecutableTask(
                './vendor/bin/phpunit --configuration=phpunit.xml'
            ),
            'dependencies' => [
                'composer',
            ],
        ],
    ],
];
