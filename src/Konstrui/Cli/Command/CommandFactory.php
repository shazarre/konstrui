<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Exception\CommandNotFoundException;
use Konstrui\Version;

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
     * @var Version
     */
    protected $version;

    /**
     * CommandFactory constructor.
     *
     * @param DefinitionInterface $definition
     * @param string              $cliCommand
     * @param Version             $version
     */
    public function __construct(
        DefinitionInterface $definition,
        $cliCommand,
        Version $version
    ) {
        $this->definition = $definition;
        $this->cliCommand = $cliCommand;
        $this->version = $version;
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
            case 'version':
                return new VersionCommand(
                    $this->version
                );
        }

        throw new CommandNotFoundException($name);
    }
}
