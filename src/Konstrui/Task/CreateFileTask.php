<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggingTrait;

class CreateFileTask implements TaskInterface, LoggableInterface
{
    const SKIP_EXISTING_PATH = true;

    use LoggingTrait;

    /** @var string */
    protected $path;

    /** @var bool */
    protected $skipOnExistingPath;

    /**
     * @param string $path
     * @param bool   $skipOnExistingPath
     */
    public function __construct(
        $path,
        $skipOnExistingPath = false
    ) {
        $this->path = (string) $path;
        $this->skipOnExistingPath = (bool) $skipOnExistingPath;
    }

    /**
     * @throws TaskExecutionException
     */
    public function perform()
    {
        if (file_exists($this->path)) {
            if (!$this->skipOnExistingPath) {
                throw new TaskExecutionException(
                    sprintf(
                        'Provided path %s already exists.',
                        $this->path
                    )
                );
            }

            return;
        }

        if (!$this->createFile($this->path)) {
            throw new TaskExecutionException(
                sprintf(
                    'Could not create file ath path %s.',
                    $this->path
                )
            );
        }
    }

    /**
     * @return bool
     */
    protected function createFile()
    {
        return touch($this->path);
    }
}
