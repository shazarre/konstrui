<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskCreationException;
use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggerInterface;
use Konstrui\Logger\LoggingTrait;
use Konstrui\Logger\LoggableInterface;

/**
 * Creates a Phar file.
 */
class PharTask implements TaskInterface, LoggableInterface
{
    use LoggingTrait;

    /** @var string */
    protected $path;

    /** @var string */
    protected $basePath;

    /** @var array */
    protected $paths = [];

    /** @var null|string */
    protected $stub = null;

    /** @var \Phar */
    protected $phar;

    /**
     * @param $path
     * @param array       $paths
     * @param null        $basePath
     * @param string|null $stub
     *
     * @throws TaskCreationException
     */
    public function __construct(
        $path,
        array $paths,
        $basePath = null,
        $stub = null
    ) {
        if (empty($paths)) {
            throw new TaskCreationException(
                'paths parameter cannot be empty'
            );
        }

        $this->path = (string) $path;
        $this->paths = $paths;
        $this->basePath = $basePath;

        if (!empty($this->basePath)) {
            $this->basePath = realpath($this->basePath);
            if (!file_exists($this->basePath)) {
                throw new TaskCreationException('Provided basePath does not exist.');
            }
        }

        $this->stub = (string) $stub;
    }

    /**
     * {@inheritdoc}
     */
    public function perform()
    {
        if (ini_get('phar.readonly')) {
            throw new TaskExecutionException(
                'PHP setting "phar.readonly" is enabled. '
                . 'In order to use this task, please disable it.'
            );
        }

        try {
            $this->phar = new \Phar($this->path);
            $this->phar->startBuffering();

            foreach ($this->paths as $path) {
                $path = realpath($path);
                if (!file_exists($path)) {
                    throw new TaskExecutionException(
                        sprintf(
                            'Specified path %s does not exist.',
                            $path
                        )
                    );
                }

                $this->addPath($path);
            }

            if (!empty($this->stub)) {
                $this->phar->setStub($this->stub);
            }

            $this->phar->stopBuffering();
        } catch (\PharException $exception) {
            throw new TaskExecutionException($exception);
        }
    }

    /**
     * Adds single path to a Phar.
     *
     * @param string $path
     */
    protected function addPath($path)
    {
        if (is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \RecursiveDirectoryIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                /* @var $file \SplFileInfo */
                $this->addPath($file->getRealPath());
            }

            return;
        }

        $this->log(sprintf('Adding path %s', $path), LoggerInterface::LEVEL_DEBUG);

        if (!empty($this->basePath) && strpos($path, $this->basePath) === 0) {
            $this->phar->addFile(
                $path,
                substr(
                    $path,
                    strlen($this->basePath) + 1
                )
            );

            return;
        }

        $this->phar->addFile($path);
    }
}
