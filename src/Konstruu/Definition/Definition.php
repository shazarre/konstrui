<?php

namespace Konstruu\Definition;

use Konstruu\Exception\CircularDependencyException;
use Konstruu\Exception\InvalidDependencyException;
use Konstruu\Exception\TaskNotFoundException;
use Konstruu\Task\TaskAlias;
use Konstruu\Task\TaskInterface;

class Definition implements DefinitionInterface
{
    /**
     * @var array
     */
    protected $tasks = [];

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @param TaskAlias     $alias
     * @param TaskInterface $task
     */
    public function addTask(TaskAlias $alias, TaskInterface $task)
    {
        $this->tasks[$alias->getAlias()] = $task;
        $this->dependencies[$alias->getAlias()] = [];
    }

    public function addDependency(TaskAlias $alias, TaskAlias $dependency)
    {
        $aliasAsString = $alias->getAlias();
        $dependencyAsString = $dependency->getAlias();

        if ($aliasAsString == $dependencyAsString) {
            throw new InvalidDependencyException();
        }

        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        if (!array_key_exists($dependencyAsString, $this->tasks)) {
            throw new TaskNotFoundException($dependencyAsString);
        }

        if (in_array($alias, $this->flattenAllDependencies($dependency))) {
            throw new CircularDependencyException();
        }

        $this->dependencies[$aliasAsString][$dependencyAsString] = $dependency;
    }

    public function getTask(TaskAlias $alias)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        return $this->tasks[$aliasAsString];
    }

    public function getDependencies(TaskAlias $alias)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        return array_values($this->dependencies[$alias->getAlias()]);
    }

    public function getAliases()
    {
        return array_keys($this->tasks);
    }

    protected function flattenAllDependencies(TaskAlias $alias)
    {
        $result = $this->getDependencies($alias);

        foreach ($result as $childrenAlias) {
            $result += $this->getDependencies($childrenAlias);
        }

        return $result;
    }
}
