<?php

namespace Konstrui\Exception;

class TaskNotFoundException extends ExceptionAbstract
{
    public function __construct($taskName)
    {
        parent::__construct(sprintf('Task %s was not found in scope of this build.', $taskName));
    }
}
