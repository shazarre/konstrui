<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggingTrait;

/**
 * Writes content to specified file.
 *
 * Will throw an exception if the given path:
 * - does not exist
 * - is a directory
 * - is not readable
 */
class WriteFileTask implements TaskInterface, LoggableInterface, IO\AcceptsInputInterface
{
    use LoggingTrait, IO\AcceptsInputTrait;

    /** @var string */
    protected $path = [];

    /** @var string|null */
    protected $contents;

    /**
     * @param string $path
     * @param string $contents
     */
    public function __construct($path, $contents = null)
    {
        $this->path = $path;
        $this->contents = $contents;
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

        if (!is_writable($this->path)) {
            throw new TaskExecutionException(
                sprintf(
                    'Provided path %s is not writable.',
                    $this->path
                )
            );
        }

        if ($this->hasInput()) {
            file_put_contents($this->path, $this->getInput());

            return;
        }

        if (!empty($this->contents)) {
            file_put_contents($this->path, $this->contents);

            return;
        }

        throw new TaskExecutionException(
            'Could not determine any content to write.'
        );
    }
}
