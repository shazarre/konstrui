<?php

namespace Konstrui\Task;

use Konstrui\Exception\InvalidAliasException;

class TaskAlias
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * TaskAlias constructor.
     *
     * @param $alias
     *
     * @throws InvalidAliasException
     */
    public function __construct($alias)
    {
        if (!is_string($alias) || !strlen($alias)) {
            throw new InvalidAliasException();
        }

        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAlias();
    }
}
