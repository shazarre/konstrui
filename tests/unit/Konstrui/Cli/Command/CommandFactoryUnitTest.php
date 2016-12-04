<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\DefinitionInterface;

class CommandFactoryUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $expectedCommandClassName
     * @param string $name
     * @dataProvider dataCreateCommandByName
     */
    public function testCreateCommandByName($expectedCommandClassName, $name)
    {
        $factory = new CommandFactory(
            $this->getMockForAbstractClass(DefinitionInterface::class),
            'konstrui'
        );

        $this->assertInstanceOf(
            $expectedCommandClassName,
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
        ];
    }

    /**
     * @expectedException \Konstrui\Exception\CommandNotFoundException
     */
    public function testThrowsExceptionOnUnknownCommand()
    {
        $factory = new CommandFactory(
            $this->getMockForAbstractClass(DefinitionInterface::class),
            'konstrui'
        );
        $factory->createCommandByName('unknown');
    }
}
