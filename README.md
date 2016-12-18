# Konstrui

[![Build Status](https://travis-ci.org/shazarre/konstrui.svg?branch=master)](https://travis-ci.org/shazarre/konstrui)
[![codecov](https://codecov.io/gh/shazarre/konstrui/branch/master/graph/badge.svg)](https://codecov.io/gh/shazarre/konstrui)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/872e64c3adf04cfd97c3c44e1e3bcf5a)](https://www.codacy.com/app/leszek-stachowski/konstrui?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=shazarre/konstrui&amp;utm_campaign=Badge_Grade)

## FAQ

### What does "konstrui" mean?

It means "to build" in Esperanto. Now you can see that connection between the 
name and purpose of the project is pretty straightforward.

### A build system in PHP?

Yes, it's meant to be used with PHP projects. Because it's written in PHP, 
it's supposed to be super easy for PHP developers to dive in. 

What's important is the fact that having a project and build system in same
language lets you integrate both seamlessly. In this case, because of Konstrui
it is possible to extend built-in tasks or create new ones from scratch inside
your project namespace and use them without any issues inside build file.
Minimum you have to do is to implement `TaskInterface` and you're ready to go.

Also, keep in mind that sometimes docs can be outdated or not clear enough. In
case of Konstrui, if you don't know how something works, just look at the source
code. You'll be able to autocomplete task names, parameters, see their logic
and it's all without any hassle in your own IDE.

Of course, feel free to use it outside of PHP world, but it's mostly suited 
there.

## Installation

### Composer

    composer require "konstrui/konstrui" "dev-master"

## Usage

    ./vendor/bin/konstrui <task|command> [arguments]

### Commands

Commands in Konstrui are prefixed with `--` and their list
is predefined. Available commands are:

| Command        | Description           |
| ------------- | ------------- |
| `--help` | Will print out help information. |
| `--list` | Will print out list of all available tasks and related information (together with exact cli command to run which you can just copy paste into your terminal) |
| `--version` | Will print out version information. |

### Tasks

#### Build file

To use tasks, your project needs to include a `.konstrui.php`. Below you can find an example
contents of such file.

```php
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
```

#### Task types

##### CallableTask

This task is meant to run a specified callback (specifically speaking: anything 
that is `callable`). If the callback returns `false` a `TaskExecutionException` 
will be thrown and build process will stop at this point.


```php
<?php

// will just output "I was called!" string at runtime
new \Konstrui\Task\CallableTask(function () {
    echo "I was called!";
});

// a TaskExecutionException will be thrown at runtime
new \Konstrui\Task\CallableTask(function () {
    return false;
});
```

##### CleanTask

Will try to remove specified paths. Those which does not exist will be silently
ommited, but if for any existing one removal will file (ie. for permission 
reasons) a `TaskExecutionException` will be thrown at the build will
stop.

```php
<?php

// will remove specified folder and file
$cleanupTask = new \Konstrui\Task\CleanTask(
    [
        '/tmp/artifacts-folder/',
        '/tmp/artifacts-file.txt',
    ]
);
```

##### ExecutableTask

Will run specified command. If running the command will result in exit code
greater than 0, it will throw `TaskExecutionException` and the build will stop
(unless you specify `\Konstrui\Task\ExecutableTask::IGNORE_EXIT_CODE`).

```php
<?php

// Will throw exception when process returns exit code > 0
$executableTask = new \Konstrui\Task\ExecutableTask('ps aux');

// Will not throw exception when process returns exit code > 0
$executableTask = new \Konstrui\Task\ExecutableTask(
    'ps aux',
    \Konstrui\Task\ExecutableTask::IGNORE_EXIT_CODE
);
```

##### PhpUnitTask

Performs PHPUnit tests. Supports:
- custom configuration path
- custom phpunit executable path.

It will try to resolve phpunit path automatically if not provided. Precedence
will have phpunit located inside vendor/bin/phpunit (over phpunit) because
it is likely to use custom library version if provided as a composer
dependency.

```php
<?php

$task = new \Konstrui\Task\PhpUnitTask();

```

##### ComposerTask

Runs composer.

Currently supports 2 composer commands:
- install (`\Konstrui\Task\ComposerTask::MODE_INSTALL`)
- update (`\Konstrui\Task\ComposerTask::MODE_UPDATE`)

Specifying any other mode will throw a `TaskCreationException`.

Supports also:
- including/excluding dev dependencies
- custom composer path
 
```php
<?php

$task = new \Konstrui\Task\ComposerTask(
    \Konstrui\Task\ComposerTask::MODE_INSTALL, // MODE_UPDATE is available too
    true // it means it will install dev dependencies
);

```

#### PharTask

Creates a Phar file.

Keep in mind that php setting `phar.readonly` needs to be disabled (by default it's enabled).

```php
<?php
$task = new \Konstrui\Task\PharTask(
    'application.phar', // path where archive should be created, mandatory
     // array of paths which should be included into archive, mandatory
    [
        __DIR__ . '/src/',
        __DIR__ . '/vendor/',
    ],
    /*
     * Base path which will be removed from local phar path, so
     * for example: "/tmp/phar/test.php" with base path "/tmp/phar"
     * would become "test.php" inside the archive.
     * 
     * This param is not mandatory and can be ommited.  
     * 
     * @see http://php.net/manual/en/phar.addfile.php
     */
    __DIR__,
    /*
     * Stub for the phar file, not mandatory
     * 
     * @see http://php.net/manual/en/phar.setstub.php
     */
    <<<STUB
#!/usr/bin/env php
<?php

Phar::mapPhar('application.phar');

require 'phar://application.phar/index.php';

__HALT_COMPILER();
STUB
);
```

#### ReadFileTask

Reads contents of specified file.

Will throw an exception if the given path:
- does not exist
- is a directory
- is not readable

Produces output (file contents) which will be passed an input to next task.

```php
<?php

$task = new \Konstrui\Task\ReadFileTask(__DIR__ . '/file-to-read.txt');
```

#### WriteFileTask

Write contents to specified file.

Will throw an exception if the given path:
- does not exist
- is a directory
- is not writable

Will also throw an exception in case when neither input nor via constructor
is specified.

Keep in mind that input has precedence over constructor contents param. If there will
be both specified, input will be written and the param ignored.

```php
<?php

// Directly declare contents
$task = new \Konstrui\Task\WriteFileTask(
    __DIR__ . '/file-to-write.txt',
    "File contents\n"
);

// Task will use input
$task = new \Konstrui\Task\WriteFileTask(__DIR__ . '/file-to-write.txt');
```

#### CreateFileTask

Creates a file.

Will throw an exception if path already exists (unless you specify `\Konstrui\Task\CreateFileTask::SKIP_EXISTING_PATH`).

Will also throw an exception in case file could not be created.

```php
<?php

// Will throw exception when path already exists
$task = new \Konstrui\Task\CreateFileTask(__DIR__ . '/file-to-create.txt');

// Will not throw exception when path already exists
$task = new \Konstrui\Task\CreateFileTask(
    __DIR__ . '/file-to-create.txt',
    \Konstrui\Task\CreateFileTask::SKIP_EXISTING_PATH
);
```

#### CompoundTask

Allows grouping of tasks. Does not perform any action on its own.

```php
<?php

$task = new \Konstrui\Task\CompoundTask(
    [
        'task-alias',
        'another-task-alias',
    ]
);
```
