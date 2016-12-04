<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Exception\CommandNotFoundException;

class CommandFactory implements CommandFactoryInterface
{
    /**
     * @var DefinitionInterface
     */
    protected $definition;

    /**
     * @var string
     */
    protected $cliCommand;

    /**
     * CommandFactory constructor.
     *
     * @param DefinitionInterface $definition
     * @param string              $cliCommand
     */
    public function __construct(
        DefinitionInterface $definition,
        $cliCommand
    ) {
        $this->definition = $definition;
        $this->cliCommand = $cliCommand;
    }

    /**
     * {@inheritdoc}
     */
    public function createCommandByName($name)
    {
        switch ($name) {
            case 'list':
                return new ListCommand(
                    $this->definition,
                    $this->cliCommand
                );
            case 'help':
                return new HelpCommand(
                    $this->cliCommand
                );
        }

        throw new CommandNotFoundException($name);
    }
}
