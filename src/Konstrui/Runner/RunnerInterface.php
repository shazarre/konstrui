<?php

namespace Konstrui\Runner;

use Konstrui\Task\TaskAlias;

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
