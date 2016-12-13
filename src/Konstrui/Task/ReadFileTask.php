<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggingTrait;

/**
 * Reads contents of specified file.
 *
 * Will throw an exception if the given path:
 * - does not exist
 * - is a directory
 * - is not readable
 */
class ReadFileTask implements TaskInterface, LoggableInterface, IO\HasOutputInterface
{
    use LoggingTrait, IO\HasOutputTrait;

    /** @var string */
    protected $path = [];

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @throws TaskExecutionException
     */
    public function perform()
    {
        if (!file_exists($this->path)) {
            throw new TaskExecutionException(
                sprintf(
                    'Provided path %s does not exists.',
                    $this->path
                )
            );
        }

        if (is_dir($this->path)) {
            throw new TaskExecutionException(
                sprintf(
                    'Provided path %s is a directory.',
                    $this->path
                )
            );
        }

        if (!is_readable($this->path)) {
            throw new TaskExecutionException(
                sprintf(
                    'Provided path %s is not readable.',
                    $this->path
                )
            );
        }

        $this->setOutput(file_get_contents($this->path));
    }
}
