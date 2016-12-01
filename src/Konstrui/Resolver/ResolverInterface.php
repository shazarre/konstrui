<?php

namespace Konstrui\Resolver;

use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

/**
 * Interface ResolverInterface.
 */
interface ResolverInterface
{
    /**
     * @param TaskAlias $alias
     *
     * @return TaskInterface
     */
    public function getTask(TaskAlias $alias);

    /**
     * @param TaskAlias $alias
     *
     * @return array
     */
    public function getDependencies(TaskAlias $alias);

    /**
     * @param TaskAlias $alias
     *
     * @return bool
     */
    public function hasTask(TaskAlias $alias);
}
