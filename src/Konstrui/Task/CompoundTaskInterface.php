<?php

namespace Konstrui\Task;

interface CompoundTaskInterface extends TaskInterface
{
    /**
     * @return array
     */
    public function getTaskAliases();
}
