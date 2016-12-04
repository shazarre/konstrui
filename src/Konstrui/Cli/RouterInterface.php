<?php

namespace Konstrui\Cli;

interface RouterInterface
{
    /**
     * Routes a CLI command to a dedicated Command or a Task.
     *
     * @param string $command
     *
     * @return mixed
     */
    public function route($command);
}
