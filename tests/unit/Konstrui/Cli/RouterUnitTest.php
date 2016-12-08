<?php

namespace Konstrui\Cli;

use Konstrui\Cli\Command\CommandFactoryInterface;
use Konstrui\Cli\Command\CommandInterface;
use Konstrui\Runner\RunnerInterface;
use Konstrui\Task\TaskAlias;

class RouterUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataRoutesCommand
     */
    public function testRoutesListCommand($commandName, $cliArgument = null)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);

        $router = new Router(
            $this->getMockForAbstractClass(RunnerInterface::class),
            $factory
        );

        $factory->expects($this->once())
            ->method('createCommandByName')
            ->with($commandName)
            ->willReturn($command);

        $command->expects($this->once())
            ->method('run');

        $cliArguments = [
            './bin/konstrui',
        ];

        if ($cliArgument !== null) {
            $cliArguments[] = $cliArgument;
        }

        $router->route($cliArguments);
    }

    /**
     * @return array
     */
    public function dataRoutesCommand()
    {
        return [
            [
                'list',
                '--list',
            ],
            [
                'help',
                '--help',
            ],
            [
                'help',
            ],
            [
                'version',
                '--version',
            ],
        ];
    }

    public function testRoutesTask()
    {
        $runner = $this->getMockForAbstractClass(RunnerInterface::class);

        $router = new Router(
            $runner,
            $this->getMockForAbstractClass(CommandFactoryInterface::class)
        );

        $runner->expects($this->once())
            ->method('run')
            ->with(new TaskAlias('task'));

        $router->route(
            [
                './bin/konstrui',
                'task',
            ]
        );
    }
}
