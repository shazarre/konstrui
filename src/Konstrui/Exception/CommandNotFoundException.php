<?php

namespace Konstrui\Exception;

class CommandNotFoundException extends ExceptionAbstract
{
    public function __construct($commandName)
    {
        parent::__construct(sprintf('Command %s not found.', $commandName));
    }
}
