<?php

namespace Konstrui\Definition;

use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

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

    /**
     * Sets a description for given task.
     *
     * @param TaskAlias $alias
     * @param string    $description
     */
    public function setDescription(TaskAlias $alias, $description);

    /**
     * Gets a description for given task.
     *
     * @param TaskAlias $alias
     *
     * @return string
     */
    public function getDescription(TaskAlias $alias);
}
