<?php

namespace Konstruu\Definition;

use Konstruu\Task\TaskAlias;
use Konstruu\Task\TaskInterface;

interface DefinitionInterface
{
    /**
     * @param TaskAlias     $alias
     * @param TaskInterface $task
     *
     * @return mixed
     */
    public function addTask(TaskAlias $alias, TaskInterface $task);

    /**
     * @param TaskAlias $alias
     * @param TaskAlias $dependency
     *
     * @return mixed
     */
    public function addDependency(TaskAlias $alias, TaskAlias $dependency);

    /**
     * @param TaskAlias $alias
     *
     * @return TaskInterface
     */
    public function getTask(TaskAlias $alias);

    /**
     * @param TaskAlias $alias
     *
     * @return mixed
     */
    public function getDependencies(TaskAlias $alias);

    /**
     * @return array
     */
    public function getAliases();
}
