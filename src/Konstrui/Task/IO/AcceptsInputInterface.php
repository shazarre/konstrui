<?php

namespace Konstrui\Task\IO;

/**
 * Interface for tasks accepting input.
 */
interface AcceptsInputInterface
{
    /**
     * Sets input for the task.
     *
     * @param mixed $input
     */
    public function setInput($input);
}
