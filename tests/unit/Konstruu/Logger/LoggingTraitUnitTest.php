<?php

namespace Konstruu\Logger;

class LoggingTraitUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetLogger()
    {
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        /** @var LoggingTrait|\PHPUnit_Framework_MockObject_MockObject $logging */
        $logging = $this->getMockForTrait(LoggingTrait::class);
        $this->assertNull($logging->setLogger($logger));
    }

    public function testLogWhenLoggerIsSet()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->setMethods(['log'])
            ->getMockForAbstractClass();
        $logger->expects($this->once())->method('log');
        /** @var LoggingTrait|\PHPUnit_Framework_MockObject_MockObject $logging */
        $logging = $this->getMockForTrait(LoggingTrait::class);
        $logging->setLogger($logger);
        $this->assertNull($logging->log('test'));
    }
}
