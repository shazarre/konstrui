<?php

namespace Konstrui\Task;

class ReadFileTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        if (file_exists('/tmp/read-file-test')) {
            if (is_dir('/tmp/read-file-test')) {
                rmdir('/tmp/read-file-test');

                return;
            }

            unlink('/tmp/read-file-test');
        }
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNonExistingPath()
    {
        $task = new ReadFileTask('/tmp/non-existing-path');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnDirectoryPath()
    {
        mkdir('/tmp/read-file-test');
        $task = new ReadFileTask('/tmp/read-file-test');
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNonReadablePath()
    {
        touch('/tmp/read-file-test');
        chmod('/tmp/read-file-test', 244);
        $task = new ReadFileTask('/tmp/read-file-test');
        $task->perform();
    }

    public function testPerform()
    {
        file_put_contents('/tmp/read-file-test', 'Test content');
        $task = new ReadFileTask('/tmp/read-file-test');
        $this->assertNull($task->perform());
        $this->assertEquals('Test content', $task->getOutput());
    }
}
