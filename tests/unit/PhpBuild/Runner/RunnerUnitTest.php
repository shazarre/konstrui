<?php

namespace Konstruu\Runner;

use Konstruu\Logger\LoggerInterface;
use Konstruu\Resolver\ResolverInterface;
use Konstruu\Task\TaskAlias;

class RunnerUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testRunEmpty()
    {
        $resolver = $this->getMockForAbstractClass(ResolverInterface::class);
        $resolver->expects($this->once())->method('hasTask')->willReturn(false);
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $runner = new Runner($resolver, $logger);
        $this->assertFalse($runner->run(new TaskAlias('alias')));
    }
}
