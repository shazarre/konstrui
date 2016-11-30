<?php

namespace Konstruu\Task;

class CallableTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testCallableIsCalled()
    {
        $called = false;
        $task = new CallableTask(function () use (&$called) {
            $called = true;
        });
        $task->perform();

        $this->assertTrue($called);
    }

    /**
     * @expectedException \Konstruu\Exception\TaskExecutionException
     */
    public function testThrowsExceptionWhenCallbackReturnsFalse()
    {
        $task = new CallableTask(function () use (&$called) {
            return false;
        });
        $task->perform();
    }

    /**
     * @dataProvider dataThrowsExceptionWhenNotCallable
     * @expectedException \Konstruu\Exception\TaskExecutionException
     */
    public function testThrowsExceptionWhenNotCallable($callable)
    {
        $task = new CallableTask($callable);
        $task->perform();
    }

    /**
     * @return array
     */
    public function dataThrowsExceptionWhenNotCallable()
    {
        return [
            [
                true,
            ],
            [
                false,
            ],
            [
                '__non_existing_function',
            ],
            [
                ['NonExistingClass', 'method'],
            ],
            [
                0,
            ],
            [
                null,
            ],
            [
                '',
            ],
        ];
    }
}
