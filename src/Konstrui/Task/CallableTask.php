<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskExecutionException;

class CallableTask implements TaskInterface, IO\AcceptsInputInterface, IO\HasOutputInterface
{
    use IO\AcceptsInputTrait, IO\HasOutputTrait;

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
        $callbackResult = $callback($this->getInput());

        if ($callbackResult === false) {
            throw new TaskExecutionException('Given callback evaluated to false');
        }

        $this->setOutput($callbackResult);
    }
}
