<?php

namespace Konstrui\Task;

class CompoundTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Konstrui\Exception\TaskCreationException
     */
    public function testThrowsExceptionWhenNoTasksProvided()
    {
        new CompoundTask([]);
    }

    public function testGetTaskAliases()
    {
        $expectedTaskAliases = [
            'task-alias',
            'another-task-alias',
        ];
        $task = new CompoundTask($expectedTaskAliases);
        $this->assertEquals($expectedTaskAliases, $task->getTaskAliases());
    }

    public function testPerform()
    {
        $task = new CompoundTask(
            [
                'task-alias',
                'another-task-alias',
            ]
        );
        $this->assertNull($task->perform());
    }
}
