<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggingTrait;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggerInterface;

class ExecutableTask implements TaskInterface, LoggableInterface
{
    const IGNORE_EXIT_CODE = true;

    use LoggingTrait;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var bool
     */
    protected $ignoreExitCode;

    /**
     * ExecutableTask constructor.
     *
     * @param string $command
     */
    public function __construct($command, $ignoreExitCode = false)
    {
        $this->command = (string) $command;
        $this->ignoreExitCode = $ignoreExitCode;
    }

    public function perform()
    {
        $exitCode = $this->executeCommand();
        if ($exitCode) {
            $message = sprintf('Process exited with code %d', $exitCode);
            if ($this->ignoreExitCode === self::IGNORE_EXIT_CODE) {
                $this->log($message, LoggerInterface::LEVEL_WARNING);

                return;
            }

            throw new TaskExecutionException($message);
        }
    }

    /**
     * TODO refactor to symfony/process.
     *
     * @return int
     */
    protected function executeCommand()
    {
        $this->log(sprintf('Running command: %s', $this->command));

        $process = proc_open($this->command, [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes);

        if (is_resource($process)) {
            while (($line = fgets($pipes[1])) || ($line = fgets($pipes[2]))) {
                $this->log($line, LoggerInterface::LEVEL_DEBUG);
            }
        }

        return proc_close($process);
    }
}
