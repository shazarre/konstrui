<?php

namespace Konstrui\Cli;

use Konstrui\Cli\Command\CommandFactoryInterface;
use Konstrui\Runner\RunnerInterface;
use Konstrui\Task\TaskAlias;

class Router implements RouterInterface
{
    /**
     * @var RunnerInterface
     */
    protected $runner;

    /**
     * @var CommandFactoryInterface
     */
    protected $factory;

    /**
     * @param RunnerInterface         $runner
     * @param CommandFactoryInterface $factory
     */
    public function __construct(
        RunnerInterface $runner,
        CommandFactoryInterface $factory
    ) {
        $this->runner = $runner;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function route($arguments)
    {
        array_shift($arguments);

        if (!empty($arguments)) {
            switch ($arguments[0]) {
                case '--help':
                    return $this->runCommand('help');
                case '--list':
                    return $this->runCommand('list');
                default:
                    return $this->runTask($arguments[0]);
            }
        }

        return $this->runCommand('help');
    }

    /**
     * @param string $commandName
     */
    protected function runCommand($commandName)
    {
        return $this->factory->createCommandByName($commandName)->run();
    }

    /**
     * @param string $alias
     *
     * @return mixed
     */
    protected function runTask($alias)
    {
        return $this->runner->run(new TaskAlias($alias));
    }
}
