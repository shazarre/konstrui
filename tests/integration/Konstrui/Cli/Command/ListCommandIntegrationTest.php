<?php

namespace Konstrui\Cli\Command;

use Konstrui\Definition\Definition;
use Konstrui\Task\CallableTask;
use Konstrui\Task\CleanTask;
use Konstrui\Task\TaskAlias;

class ListCommandIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $this->expectOutputString(<<<EOS
Alias

	Task class: Konstrui\Task\CleanTask
	Description: Test description
	To run this task use following command:

		konstrui alias

Another-alias

	Task class: Konstrui\Task\CallableTask
	Description: Another test description
	To run this task use following command:

		konstrui another-alias


EOS
        );
        $definition = new Definition();
        $definition->addTask(
            new TaskAlias('alias'),
            new CleanTask([])
        );
        $definition->setDescription(new TaskAlias('alias'), 'Test description');
        $definition->addTask(
            new TaskAlias('another-alias'),
            new CallableTask(function () {
            })
        );
        $definition->setDescription(
            new TaskAlias('another-alias'),
            'Another test description'
        );
        $command = new ListCommand(
            $definition,
            'konstrui'
        );
        $command->run();
    }
}
