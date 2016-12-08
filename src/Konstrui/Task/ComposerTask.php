<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskCreateException;

/**
 * Runs composer.
 *
 * Currently supports 2 composer commands:
 * - install
 * - update
 *
 * Specifying any other mode will throw a TaskCreateException.
 *
 * Supports also:
 * - including/excluding dev dependencies
 * - custom composer path
 */
class ComposerTask extends ExecutableTask
{
    /**
     * @var string
     */
    const MODE_INSTALL = 'install';

    /**
     * @var string
     */
    const MODE_UPDATE = 'update';

    /**
     * @var string
     */
    const DEFAULT_PATH = 'composer';

    /**
     * @param string $mode
     * @param bool   $requireDev
     * @param string $composerPath
     *
     * @throws TaskCreateException
     */
    public function __construct(
        $mode = self::MODE_INSTALL,
        $requireDev = false,
        $composerPath = self::DEFAULT_PATH
    ) {
        if (!in_array($mode, [self::MODE_INSTALL, self::MODE_UPDATE])) {
            throw new TaskCreateException('Invalid mode provided');
        }

        parent::__construct(
            sprintf(
                '%s %s %s',
                $composerPath,
                $mode,
                $requireDev ? '--dev' : '--no-dev'
            )
        );
    }
}
