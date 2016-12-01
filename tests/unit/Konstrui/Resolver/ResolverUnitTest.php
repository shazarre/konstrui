<?php

namespace Konstrui\Resolver;

use Konstrui\Definition\Definition;
use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

class ResolverUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetExistingTask()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $anotherTask = $this->getMockForAbstractClass(TaskInterface::class);

        $definition = new Definition();
        $definition->addTask(new TaskAlias('task'), $task);
        $definition->addTask(new TaskAlias('another-task'), $anotherTask);

        $resolver = new Resolver($definition);
        $this->assertSame($task, $resolver->getTask(new TaskAlias('task')));
        $this->assertSame($anotherTask, $resolver->getTask(new TaskAlias('another-task')));
    }

    public function testGetNonExistingTask()
    {
        $resolver = new Resolver(new Definition());
        $this->assertNull($resolver->getTask(new TaskAlias('alias')));
    }

    public function testHasTask()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);

        $definition = new Definition();
        $definition->addTask(new TaskAlias('task'), $task);

        $resolver = new Resolver($definition);
        $this->assertTrue($resolver->hasTask(new TaskAlias('task')));
        $this->assertfalse($resolver->hasTask(new TaskAlias('another-task')));
    }

    public function testGetExistingDependencies()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $anotherTask = $this->getMockForAbstractClass(TaskInterface::class);

        $definition = new Definition();
        $definition->addTask(new TaskAlias('task'), $task);
        $definition->addTask(new TaskAlias('another-task'), $anotherTask);
        $definition->addDependency(new TaskAlias('task'), new TaskAlias('another-task'));

        $resolver = new Resolver($definition);
        $this->assertEquals(
            [
                new TaskAlias('another-task'),
            ],
            $resolver->getDependencies(new TaskAlias('task'))
        );
    }

    public function testGetNonExistingDependencies()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);

        $definition = new Definition();
        $definition->addTask(new TaskAlias('task'), $task);

        $resolver = new Resolver($definition);
        $this->assertEmpty($resolver->getDependencies(new TaskAlias('task')));
    }

    public function testGetDependenciesForNonExistingTask()
    {
        $resolver = new Resolver(new Definition());
        $this->assertEmpty($resolver->getDependencies(new TaskAlias('task')));
    }
}
