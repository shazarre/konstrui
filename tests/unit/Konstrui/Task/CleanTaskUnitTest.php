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
    public function testThrowExceptionWhenCannotRemoveFile()
    {
        /** @var CleanTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $testFilePath = __DIR__ . '/test-file';
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

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowExceptionWhenCannotRemoveDirectory()
    {
        $testDirPath = __DIR__ . '/test-directory/';
        /** @var CleanTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(CleanTask::class)
            ->setConstructorArgs(
                [
                    [
                        $testDirPath,
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
            ->willReturnCallback(function () use ($testDirPath) {
                unlink($testDirPath . 'test-file');
                rmdir($testDirPath);

                return false;
            });

        mkdir($testDirPath);
        touch($testDirPath . 'test-file');
        $task->perform();
    }
}
