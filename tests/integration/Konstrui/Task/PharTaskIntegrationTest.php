<?php

namespace Konstrui\Task;

class PharTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /** @var mixed */
    protected $initialPharReadOnly;

    protected function setUp()
    {
        parent::setUp();

        $this->initialPharReadOnly = ini_get('phar.readonly');
        mkdir('/tmp/phar-task-test');
        touch('/tmp/phar-task-test/test.php');
        ini_set('phar.readonly', false);
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (file_exists('/tmp/phar-task-test')) {
            if (file_exists('/tmp/phar-task-test/test.phar')) {
                unlink('/tmp/phar-task-test/test.phar');
            }
            unlink('/tmp/phar-task-test/test.php');
            rmdir('/tmp/phar-task-test');
        }
        ini_set('phar.readonly', $this->initialPharReadOnly);
    }

    public function testPerform()
    {
        $task = new PharTask(
            '/tmp/phar-task-test/test.phar',
            [
                '/tmp/phar-task-test',
            ]
        );
        $task->perform();
        $this->assertFileExists('/tmp/phar-task-test/test.phar');
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionWhenPharReadOnly()
    {
        ini_set('phar.readonly', 'On');
        $task = new PharTask(
            '/tmp/phar-task-test/test.phar',
            [
                '/tmp/phar-task-test',
            ]
        );
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskCreationException
     */
    public function testThrowsExceptionOnEmptyPaths()
    {
        $task = new PharTask('/tmp/phar-task-test/test.phar', []);
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testThrowsExceptionOnNonExistingPath()
    {
        $task = new PharTask(
            '/tmp/test.phar',
            [
                '/non-existing-path',
            ]
        );
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskCreationException
     */
    public function testThrowsExceptionOnNonExistingBasePath()
    {
        $task = new PharTask(
            '/tmp/phar-task-test/test.phar',
            [
                '/tmp/phar-task-test',
            ],
            '/non-existing-base-path/'
        );
        $task->perform();
    }

    /**
     * @expectedException \Konstrui\Exception\TaskExecutionException
     */
    public function testCatchesPharException()
    {
        $task = new PharTask(
            '/tmp/test.phar',
            [
                '/tmp/phar-task-test/test.php',
            ],
            '/tmp/phar-task-test',
            '<?php // invalid stub;'
        );
        $task->perform();
    }

    public function testSetStub()
    {
        $task = new PharTask(
            '/tmp/test.phar',
            [
                '/tmp/phar-task-test/test.php',
            ],
            '/tmp/phar-task-test',
            <<<'STUB'
<?php

Phar::mapPhar('test.phar');

__HALT_COMPILER();
STUB
        );
        $task->perform();
    }
}
