<?php

namespace Konstruu\Runner;

use Konstruu\Task\TaskAlias;

/**
 * Interface RunnerInterface.
 */
interface RunnerInterface
{
    /**
     * @param TaskAlias $alias
     *
     * @return mixed
     */
    public function run(TaskAlias $alias);
}
