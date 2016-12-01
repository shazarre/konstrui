<?php

namespace Konstrui\Resolver;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Task\TaskAlias;

class Resolver implements ResolverInterface
{
    /**
     * @var DefinitionInterface
     */
    protected $definition;

    /**
     * Resolver constructor.
     *
     * @param DefinitionInterface $definition
     */
    public function __construct(DefinitionInterface $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @param TaskAlias $alias
     *
     * @return null|\Konstrui\Task\TaskInterface
     */
    public function getTask(TaskAlias $alias)
    {
        if (!$this->hasTask($alias)) {
            return null;
        }

        return $this->definition->getTask($alias);
    }

    /**
     * @param TaskAlias $alias
     *
     * @return array|mixed
     */
    public function getDependencies(TaskAlias $alias)
    {
        if (!$this->hasTask($alias)) {
            return [];
        }

        return $this->definition->getDependencies($alias);
    }

    /**
     * @param TaskAlias $alias
     *
     * @return bool
     */
    public function hasTask(TaskAlias $alias)
    {
        $aliases = $this->definition->getAliases();

        return in_array($alias->getAlias(), $aliases);
    }
}
