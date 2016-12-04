<?php

namespace Konstrui\Cli\Command;

use Konstrui\Exception\CommandNotFoundException;

interface CommandFactoryInterface
{
    /**
     * @param $name
     *
     * @return CommandInterface
     *
     * @throws CommandNotFoundException
     */
    public function createCommandByName($name);
}
