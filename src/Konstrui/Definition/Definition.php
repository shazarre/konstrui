<?php

namespace Konstrui\Definition;

use Konstrui\Exception\CircularDependencyException;
use Konstrui\Exception\InvalidDependencyException;
use Konstrui\Exception\TaskNotFoundException;
use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

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
     * @var array[string]
     */
    protected $descriptions = [];

    /**
     * {@inheritdoc}
     */
    public function addTask(TaskAlias $alias, TaskInterface $task)
    {
        $this->tasks[$alias->getAlias()] = $task;
        $this->dependencies[$alias->getAlias()] = [];
        $this->descriptions[$alias->getAlias()] = '';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getTask(TaskAlias $alias)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        return $this->tasks[$aliasAsString];
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(TaskAlias $alias)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        return array_values($this->dependencies[$alias->getAlias()]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return array_keys($this->tasks);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(TaskAlias $alias, $description)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        $this->descriptions[$aliasAsString] = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(TaskAlias $alias)
    {
        $aliasAsString = $alias->getAlias();
        if (!array_key_exists($aliasAsString, $this->tasks)) {
            throw new TaskNotFoundException($aliasAsString);
        }

        return $this->descriptions[$aliasAsString];
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
