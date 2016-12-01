<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;

class CallableTask implements TaskInterface
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new TaskExecutionException('Given callback is not callable');
        }

        $this->callback = $callback;
    }

    public function perform()
    {
        $callback = $this->callback;

        if ($callback() === false) {
            throw new TaskExecutionException('Given callback evaluated to false');
        }
    }
}
