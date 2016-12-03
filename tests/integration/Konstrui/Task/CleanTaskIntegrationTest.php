<?php

namespace Konstrui\Task;

class CleanTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testDeletePaths()
    {
        $tmpDirPath = __DIR__ . '/tmp/';
        mkdir($tmpDirPath);
        touch($tmpDirPath . 'test-file');
        mkdir($tmpDirPath . 'test-subdirectory');
        touch($tmpDirPath . 'test-subdirectory/another-file');
        $tmpFilePath = __DIR__ . 'test-file';

        $cleanupTask = new CleanTask(
            [
                $tmpDirPath,
                $tmpFilePath,
            ]
        );
        $cleanupTask->perform();

        $this->assertFileNotExists($tmpDirPath);
        $this->assertFileNotExists($tmpFilePath);
    }

    public function testSkipsEmptyPaths()
    {
        $cleanupTask = new CleanTask(
            [
                __DIR__ . '/non-existing-file',
            ]
        );
        $this->assertNull($cleanupTask->perform());
    }
}
