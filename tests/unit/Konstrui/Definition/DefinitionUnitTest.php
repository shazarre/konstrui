<?php

namespace Konstrui\Definition;

use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

class DefinitionUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testAddTask()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $this->assertSame($task, $definition->getTask(new TaskAlias('alias')));
    }

    public function testAddDependencies()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addTask(new TaskAlias('dependency'), $task);
        $definition->addDependency(new TaskAlias('alias'), new TaskAlias('dependency'));
        $this->assertEquals([new TaskAlias('dependency')], $definition->getDependencies(new TaskAlias('alias')));
    }

    /**
     * @expectedException \Konstrui\Exception\InvalidDependencyException
     */
    public function testThrowsExceptionWhenAddingSelfAsDependency()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addDependency(new TaskAlias('alias'), new TaskAlias('alias'));
    }

    /**
     * @expectedException \Konstrui\Exception\CircularDependencyException
     */
    public function testThrowsExceptionOnCircularDirectDependency()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $dependency = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addTask(new TaskAlias('dependency'), $dependency);
        $definition->addDependency(new TaskAlias('alias'), new TaskAlias('dependency'));
        $definition->addDependency(new TaskAlias('dependency'), new TaskAlias('alias'));
    }

    /**
     * @expectedException \Konstrui\Exception\CircularDependencyException
     */
    public function testThrowsExceptionOnCircularNonDirectDependency()
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
        $definition->addDependency(new TaskAlias('a'), new TaskAlias('b'));
        $definition->addDependency(new TaskAlias('a'), new TaskAlias('c'));
        $definition->addDependency(new TaskAlias('c'), new TaskAlias('a'));
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionWhenAddingDependenciesForNonExistingTask()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addDependency(new TaskAlias('another-alias'), new TaskAlias('dependency'));
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionWhenAddingDescriptionForNonExistingTask()
    {
        $definition = new Definition();
        $definition->setDescription(new TaskAlias('alias'), 'Test description');
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionWhenGettingDescriptionForNonExistingTask()
    {
        $definition = new Definition();
        $definition->getDescription(new TaskAlias('alias'));
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionWhenAddingNonExistingDependencies()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addDependency(new TaskAlias('alias'), new TaskAlias('dependency'));
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionOnGetNonExistingTask()
    {
        $definition = new Definition();
        $definition->getTask(new TaskAlias('alias'));
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionOnGetDependenciesForNonExistingTask()
    {
        $definition = new Definition();
        $definition->getDependencies(new TaskAlias('alias'));
    }

    public function testOverwriteTask()
    {
        $task = $this->getMockForAbstractClass(TaskInterface::class);
        $overwritingTask = $this->getMockForAbstractClass(TaskInterface::class);

        $definition = new Definition();
        $definition->addTask(new TaskAlias('alias'), $task);
        $definition->addTask(new TaskAlias('dependency'), $task);
        $definition->addDependency(new TaskAlias('alias'), new TaskAlias('dependency'));

        $definition->addTask(new TaskAlias('alias'), $overwritingTask);

        $this->assertSame($overwritingTask, $definition->getTask(new TaskAlias('alias')));
        $this->assertEmpty($definition->getDependencies(new TaskAlias('alias')));
    }

    public function testGetAliases()
    {
        $definition = new Definition();
        $definition->addTask(
            new TaskAlias('alias'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $definition->addTask(
            new TaskAlias('another-alias'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $this->assertEquals(
            [
                new TaskAlias('alias'),
                new TaskAlias('another-alias'),
            ],
            $definition->getAliases()
        );
    }

    public function testSetAndGetDescription()
    {
        $definition = new Definition();
        $alias = new TaskAlias('alias');
        $definition->addTask($alias, $this->getMockForAbstractClass(TaskInterface::class));
        $definition->setDescription($alias, 'Test description');
        $this->assertEquals('Test description', $definition->getDescription($alias));
    }
}
