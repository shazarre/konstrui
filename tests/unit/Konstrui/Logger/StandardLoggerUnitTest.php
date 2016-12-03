<?php

namespace Konstrui\Logger;

class StandardLoggerUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPrefix()
    {
        $this->expectOutputString("INFO: prefix > test log message\n");
        $logger = new StandardLogger();
        $logger->setPrefix('prefix > ');
        $logger->log('test log message');
    }

    /**
     * @param int $logLevel
     * @param int $monologLogLevel
     * @dataProvider dataResolvesMonologLogLevel
     */
    public function testMapsLogLevelToReadableName($logLevel, $expectedPrefix)
    {
        $this->expectOutputString(
            sprintf(
                "%s: test log message\n",
                $expectedPrefix
            )
        );
        $logger = new StandardLogger();
        $logger->log('test log message', $logLevel);
    }

    /**
     * @return array
     */
    public function dataResolvesMonologLogLevel()
    {
        return [
            'debug' => [
                LoggerInterface::LEVEL_DEBUG,
                'DEBUG',
            ],
            'info' => [
                LoggerInterface::LEVEL_INFO,
                'INFO',
            ],
            'warning' => [
                LoggerInterface::LEVEL_WARNING,
                'WARNING',
            ],
            'error' => [
                LoggerInterface::LEVEL_ERROR,
                'ERROR',
            ],
            'default' => [
                -1,
                'INFO',
            ],
        ];
    }
}
