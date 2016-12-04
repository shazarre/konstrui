<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Task\TaskAlias;

class ListCommand implements CommandInterface
{
    /**
     * @var DefinitionInterface
     */
    protected $definition;

    /**
     * @var string
     */
    protected $cliCommand;

    public function __construct(
        DefinitionInterface $definition,
        $cliCommand
    ) {
        $this->definition = $definition;
        $this->cliCommand = $cliCommand;
    }

    public function run()
    {
        $aliases = $this->definition->getAliases();
        sort($aliases);

        foreach ($aliases as $alias) {
            $task = $this->definition->getTask(new TaskAlias($alias));

            $description = $this->definition->getDescription(new TaskAlias($alias));
            printf(
                "%s\n\n\tTask class: %s\n\tDescription: %s\n"
                 . "\tTo run this task use following command:\n\n"
                 . "\t\t%s %s\n\n",
                ucfirst($alias),
                get_class($task),
                $description ?: '<not provided>',
                $this->cliCommand,
                $alias
            );
        }
    }
}
