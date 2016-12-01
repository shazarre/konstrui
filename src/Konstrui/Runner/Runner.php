<?php

namespace Konstrui\Runner;

use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggerInterface;
use Konstrui\Resolver\ResolverInterface;
use Konstrui\Exception\TaskExecutionException;
use Konstrui\Task\TaskAlias;

class Runner implements RunnerInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    protected $completed = [];

    /**
     * @var array
     */
    protected $executionStack = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Runner constructor.
     *
     * @param ResolverInterface $resolver
     * @param LoggerInterface   $logger
     */
    public function __construct(ResolverInterface $resolver, LoggerInterface $logger)
    {
        $this->resolver = $resolver;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function run(TaskAlias $alias)
    {
        if (!$this->resolver->hasTask($alias)) {
            $this->logger->log(
                sprintf('Task %s not found.', $alias->getAlias()),
                LoggerInterface::LEVEL_ERROR
            );

            return false;
        }

        $this->addToExecutionStack($alias);

        foreach ($this->resolver->getDependencies($alias) as $dependency) {
            if (!in_array($dependency, $this->completed)) {
                if (!$this->run($dependency)) {
                    return false;
                }
            }
        }

        $this->setLoggerPrefix();

        $this->logger->log(sprintf('Starting task %s', $alias));
        $task = $this->resolver->getTask($alias);

        if ($task instanceof LoggableInterface) {
            $task->setLogger($this->logger);
        }

        try {
            $task->perform();
            $this->logger->log('Task finished successfully');
        } catch (TaskExecutionException $e) {
            $this->logger->log(
                sprintf('Task failed with exception: %s', $e->getMessage()),
                LoggerInterface::LEVEL_ERROR
            );

            return false;
        } finally {
            $this->finishTask($alias);
        }

        return true;
    }

    /**
     * @param $alias
     */
    protected function finishTask($alias)
    {
        $this->markAsCompleted($alias);
        $this->removeFromExecutionStack();
    }

    /**
     * @param $alias
     */
    protected function markAsCompleted($alias)
    {
        $this->completed[] = $alias;
    }

    protected function setLoggerPrefix()
    {
        if (!empty($this->executionStack)) {
            $this->logger->setPrefix(implode(' > ', $this->executionStack) . ': ');
        }
    }

    /**
     * @return mixed
     */
    protected function removeFromExecutionStack()
    {
        return array_pop($this->executionStack);
    }

    /**
     * @param TaskAlias $alias
     *
     * @return int
     */
    protected function addToExecutionStack(TaskAlias $alias)
    {
        return array_push($this->executionStack, $alias->getAlias());
    }
}
