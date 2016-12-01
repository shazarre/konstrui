<?php

namespace Konstrui\Runner;

use Konstrui\Definition\Definition;
use Konstrui\Exception\TaskExecutionException;
use Konstrui\Logger\LoggableInterface;
use Konstrui\Logger\LoggerInterface;
use Konstrui\Resolver\Resolver;
use Konstrui\Task\ExecutableTask;
use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

/**
 * Class RunnerIntegrationTest.
 */
class RunnerIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testRunsDependencies()
    {
        $definition = new Definition();
        $definition->addTask(
            new TaskAlias('a'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('b'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('c'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('d'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('e'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );

        $definition->addDependency(new TaskAlias('a'), new TaskAlias('b'));
        $definition->addDependency(new TaskAlias('b'), new TaskAlias('c'));
        $definition->addDependency(new TaskAlias('b'), new TaskAlias('d'));
        $definition->addDependency(new TaskAlias('a'), new TaskAlias('e'));
        $runner = new Runner(
            new Resolver($definition),
            $this->getMockForAbstractClass(LoggerInterface::class)
        );
        $this->assertTrue($runner->run(new TaskAlias('a')));
    }

    public function testSetsLoggerWhenLoggable()
    {
        /*
         * This ideally should be:
         *
         * $task = $this->getMock(
         *   [
         *     TaskInterface::class,
         *     LoggableInterface::class,
         *   ]
         * );
         *
         * but with PHP 5.5.38 and PHPUnit 4.8.29 it throws an
         * incompatible declaration exception.
         *
         * Mocking a concrete class is just a workaround for this problem
         * which does not appear in PHP 7.0.12 and PHPUnit 5.6.7
         */
        /** @var ExecutableTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(ExecutableTask::class)
            ->disableOriginalConstructor()
            ->getMock();
        $task->expects($this->once())
            ->method('setLogger');
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $runner = new Runner(
            new Resolver($definition),
            $this->getMockForAbstractClass(LoggerInterface::class)
        );
        $this->assertTrue($runner->run(new TaskAlias('alias')));
    }

    public function testStopsExecutionOnDependencyTaskExecutionException()
    {
        $definition = new Definition();
        $taskThrowingAnException = $this->getMockForAbstractClass(TaskInterface::class);
        $taskThrowingAnException->expects($this->once())
            ->method('perform')
            ->willReturnCallback(function () {
                throw new TaskExecutionException();
            });
        $taskNotToBePerformed = $this->getMockForAbstractClass(TaskInterface::class);
        $taskNotToBePerformed->expects($this->never())->method('perform');
        $definition->addTask(
            new TaskAlias('a'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('b'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('c'),
            $taskNotToBePerformed
        );
        $definition->addTask(
            new TaskAlias('d'),
            $taskThrowingAnException
        );

        $definition->addDependency(new TaskAlias('a'), new TaskAlias('b'));
        $definition->addDependency(new TaskAlias('b'), new TaskAlias('c'));
        $definition->addDependency(new TaskAlias('c'), new TaskAlias('d'));
        $runner = new Runner(
            new Resolver($definition),
            $this->getMockForAbstractClass(LoggerInterface::class)
        );
        $this->assertFalse($runner->run(new TaskAlias('a')));
    }

    public function testStopsExecutionOnTaskExecutionException()
    {
        $definition = new Definition();
        $taskThrowingAnException = $this->getMockForAbstractClass(TaskInterface::class);
        $taskThrowingAnException->expects($this->once())
            ->method('perform')
            ->willReturnCallback(function () {
                throw new TaskExecutionException();
            });
        $definition->addTask(new TaskAlias('a'), $taskThrowingAnException);
        $runner = new Runner(
            new Resolver($definition),
            $this->getMockForAbstractClass(LoggerInterface::class)
        );
        $this->assertFalse($runner->run(new TaskAlias('a')));
    }
}
