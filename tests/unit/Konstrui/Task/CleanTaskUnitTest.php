<?php

namespace Konstrui\Task;

class CleanTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPaths()
    {
        $task = new CleanTask();
        $this->assertNull($task->perform());
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowExceptionWhenCannotRemoveAPath()
    {
        /** @var CleanTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $testFilePath = __DIR__ . '/test-path';
        $task = $this->getMockBuilder(CleanTask::class)
            ->setConstructorArgs(
                [
                    [
                        $testFilePath,
                    ],
                ]
            )
            ->setMethods(
                [
                    'removePath',
                ]
            )
            ->getMock();
        $task->expects($this->once())
            ->method('removePath')
            ->willReturnCallback(function () use ($testFilePath) {
                unlink($testFilePath);

                return false;
            });
        touch($testFilePath);
        $task->perform();
    }
}
