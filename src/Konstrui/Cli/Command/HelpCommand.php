<?php

namespace Konstrui\Cli\Command;

class HelpCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    public function run()
    {
        echo <<<EOS
Usage: 
    
    $this->command <task|command> [arguments]

Where <task> is an alias of task provided in .konstrui.php file. For list of all
available tasks please run:
 
    $this->command --list
    
Available commands are:

    --help - displays this help
    --list - displays list of all available tasks

EOS;
    }
}
