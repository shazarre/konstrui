<?php

namespace Konstrui\Task;

class PhpUnitTaskIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testChecksForVendorPhpUnit()
    {
        /** @var PhpUnitTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(PhpUnitTask::class)
            ->setMethods(
                [
                    'executeCommand',
                ]
            )
            ->getMock();
        $task->expects($this->once())
            ->method('executeCommand')
            ->willReturn(0);
        $this->assertNull($task->perform());
    }
}
