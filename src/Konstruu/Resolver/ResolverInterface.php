<?php

namespace Konstruu\Resolver;

use Konstruu\Task\TaskAlias;
use Konstruu\Task\TaskInterface;

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
