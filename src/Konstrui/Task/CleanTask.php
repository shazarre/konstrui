<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggingTrait;

class CleanTask implements TaskInterface, LoggableInterface
{
    use LoggingTrait;

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
    }

    /**
     * @throws TaskExecutionException
     */
    public function perform()
    {
        foreach ($this->paths as $path) {
            if (file_exists($path)) {
                if (is_dir($path)) {
                    $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator(
                            $path,
                            \RecursiveDirectoryIterator::SKIP_DOTS
                        ),
                        \RecursiveIteratorIterator::CHILD_FIRST
                    );

                    foreach ($iterator as $file) {
                        /* @var $file \SplFileInfo */
                        if (!$this->removePath($file->getRealPath())) {
                            $this->handleError($file->getRealPath());
                        }
                    }
                }

                if (!$this->removePath($path)) {
                    $this->handleError($path);
                }
            }
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function removePath($path)
    {
        $this->log(sprintf('Removing path %s', $path));
        if (is_dir($path)) {
            return rmdir($path);
        }

        return unlink($path);
    }

    /**
     * @param string $path
     *
     * @throws TaskExecutionException
     */
    protected function handleError($path)
    {
        throw new TaskExecutionException(
            sprintf(
                'Could not remove path %s',
                $path
            )
        );
    }
}
