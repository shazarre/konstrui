<?php

namespace Konstruu\Task;

class ExecutableTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfulExecution()
    {
        $task = new ExecutableTask('ls');
        $this->assertNull($task->perform());
    }
}
