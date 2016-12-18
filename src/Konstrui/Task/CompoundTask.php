<?php

namespace Konstrui\Task;

use Konstrui\Exception\TaskCreationException;

class CompoundTask implements CompoundTaskInterface
{
    /** @var array */
    protected $taskAliases;

    /**
     * @param array $tasks
     */
    public function __construct(array $taskAliases)
    {
        if (empty($taskAliases)) {
            throw new TaskCreationException('No tasks specified.');
        }

        $this->taskAliases = $taskAliases;
    }

    /**
     * @return array
     */
    public function getTaskAliases()
    {
        return $this->taskAliases;
    }

    /**
     * {@inheritdoc}
     */
    public function perform()
    {
    }
}
