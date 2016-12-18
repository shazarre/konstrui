<?php

namespace Konstrui\Task;

class CreateFileTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        if (file_exists('/tmp/create-file-test')) {
            unlink('/tmp/create-file-test');
        }
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnExistingPath()
    {
        touch('/tmp/create-file-test');
        $task = new CreateFileTask('/tmp/create-file-test');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnFileCreationFailure()
    {
        /** @var CreateFileTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(CreateFileTask::class)
            ->setMethods(['createFile'])
            ->setConstructorArgs(
                [
                    '/tmp/create-file-test',
                ]
            )
            ->getMock();
        $task->expects($this->once())
            ->method('createFile')
            ->willReturn(false);
        $task->perform();
    }

    public function testDoesNotThrowExceptionOnExistingPathWhenShouldIgnore()
    {
        touch('/tmp/create-file-test');
        $task = new CreateFileTask(
            '/tmp/create-file-test',
            CreateFileTask::SKIP_EXISTING_PATH
        );
        $task->perform();
    }

    public function testPerform()
    {
        $task = new CreateFileTask('/tmp/create-file-test');
        $this->assertNull($task->perform());
        $this->assertFileExists('/tmp/create-file-test');
    }
}
