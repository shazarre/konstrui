<?php

namespace Konstrui\Task\IO;

/**
 * Interface for tasks with output.
 */
interface HasOutputInterface
{
    /**
     * Returns output of the task.
     *
     * @return mixed
     */
    public function getOutput();
}
