<?php

namespace Konstrui\Task;

class ExecutableTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionWhenExitCodeNotZero()
    {
        /** @var ExecutableTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(ExecutableTask::class)
            ->setMethods(
                [
                    'executeCommand',
                ]
            )
            ->setConstructorArgs(
                [
                    'ps aux',
                ]
            )
            ->getMock();

        $task->expects($this->once())
            ->method('executeCommand')
            ->willReturn(1);

        $task->perform();
    }

    public function testDoesNotThrowExceptionWhenExitCodeNotZeroButShouldIgnore()
    {
        /** @var ExecutableTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(ExecutableTask::class)
            ->setMethods(
                [
                    'executeCommand',
                ]
            )
            ->setConstructorArgs(
                [
                    'ps aux',
                    ExecutableTask::IGNORE_EXIT_CODE,
                ]
            )
            ->getMock();

        $task->expects($this->once())
            ->method('executeCommand')
            ->willReturn(1);

        $task->perform();
    }
}
