<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Version;

class CommandFactoryUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $expectedClassName
     * @param string $name
     * @dataProvider dataCreateCommandByName
     */
    public function testCreateCommandByName($expectedClassName, $name)
    {
        $factory = new CommandFactory(
            $this->getMockForAbstractClass(DefinitionInterface::class),
            'konstrui',
            new Version()
        );

        $this->assertInstanceOf(
            $expectedClassName,
            $factory->createCommandByName($name)
        );
    }

    /**
     * @return array
     */
    public function dataCreateCommandByName()
    {
        return [
            [
                HelpCommand::class,
                'help',
            ],
            [
                ListCommand::class,
                'list',
            ],
            [
                VersionCommand::class,
                'version',
            ],
        ];
    }

    /**
     * @expectedException \Konstrui\Exception\CommandNotFoundException
     */
    public function testThrowsExceptionOnUnknownCommand()
    {
        $factory = new CommandFactory(
            $this->getMockForAbstractClass(DefinitionInterface::class),
            'konstrui',
            new Version()
        );
        $factory->createCommandByName('unknown');
    }
}
