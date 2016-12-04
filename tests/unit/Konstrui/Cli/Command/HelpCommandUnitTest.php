<?php

namespace Konstrui\Cli\Command;

class HelpCommandUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $this->expectOutputString(<<<'EOS'
Usage: 
    
    konstrui <task|command> [arguments]

Where <task> is an alias of task provided in .konstrui.php file. For list of all
available tasks please run:
 
    konstrui --list
    
Available commands are:

    --help - displays this help
    --list - displays list of all available tasks

EOS
        );
        $command = new HelpCommand('konstrui');
        $command->run();
    }
}
