<?php

namespace Konstrui\Task;

class WriteFileTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        if (file_exists('/tmp/write-file-test')) {
            if (is_dir('/tmp/write-file-test')) {
                rmdir('/tmp/write-file-test');

                return;
            }

            unlink('/tmp/write-file-test');
        }
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNonExistingPath()
    {
        $task = new WriteFileTask('/tmp/non-existing-path');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnDirectoryPath()
    {
        mkdir('/tmp/write-file-test');
        $task = new WriteFileTask('/tmp/write-file-test');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNonWritablePath()
    {
        touch('/tmp/write-file-test');
        chmod('/tmp/write-file-test', 544);
        $task = new WriteFileTask('/tmp/write-file-test');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNoContentsToWrite()
    {
        touch('/tmp/write-file-test');
        $task = new WriteFileTask('/tmp/write-file-test');
        $task->perform();
    }

    public function testPerform()
    {
        touch('/tmp/write-file-test');
        $task = new WriteFileTask(
            '/tmp/write-file-test',
            "Test content\n"
        );
        $this->assertNull($task->perform());
        $this->assertEquals(
            "Test content\n",
            file_get_contents('/tmp/write-file-test')
        );
    }

    public function testPerformWithInput()
    {
        touch('/tmp/write-file-test');
        $task = new WriteFileTask('/tmp/write-file-test');
        $task->setInput("Test input content\n");
        $this->assertNull($task->perform());
        $this->assertEquals(
            "Test input content\n",
            file_get_contents('/tmp/write-file-test')
        );
    }
}
