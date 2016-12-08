<?php

namespace Konstrui\Cli\Command;

use Konstrui\Version;

class VersionCommandIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $this->expectOutputString("Konstrui version 0.2.0\n");
        $command = new VersionCommand(new Version());
        $command->run();
    }
}
