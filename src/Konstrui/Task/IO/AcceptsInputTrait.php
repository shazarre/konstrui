<?php

namespace Konstrui\Task\IO;

/**
 * Trait implementing AcceptsInputInterface.
 */
trait AcceptsInputTrait
{
    /** @var mixed */
    protected $input;

    /**
     * Sets input for the task.
     *
     * @param mixed $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * Internal getter for input.
     *
     * @return mixed
     */
    protected function getInput()
    {
        return $this->input;
    }

    /**
     * Checks if task has any input.
     */
    protected function hasInput()
    {
        return !empty($this->input);
    }
}
