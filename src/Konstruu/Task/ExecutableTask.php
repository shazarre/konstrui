<?php

namespace Konstruu\Task;

use Konstruu\Exception\TaskExecutionException;
use Konstruu\Logger\LoggingTrait;
use Konstruu\Logger\LoggableInterface;

class ExecutableTask implements TaskInterface, LoggableInterface
{
    use LoggingTrait;

    /**
     * @var string
     */
    protected $command;

    /**
     * ExecutableTask constructor.
     *
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = (string) $command;
    }

    public function perform()
    {
        $exitCode = $this->executeCommand();
        if ($exitCode) {
            throw new TaskExecutionException(sprintf('Process exited with code %d', $exitCode));
        }
    }

    /**
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
                $this->log($line);
            }
        }

        return proc_close($process);
    }
}
