<?php

namespace Konstrui\Logger;

use Monolog\Logger as MonologLogger;

class MonologLoggerAdapterUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPrefix()
    {
        $logMessage = '';
        $monolog = $this->getMockBuilder(MonologLogger::class)
            ->disableOriginalConstructor()
            ->setMethods(['log'])
            ->getMock();
        $monolog->expects($this->once())
            ->method('log')
            ->willReturnCallback(function ($level, $message) use (&$logMessage) {
                $logMessage = $message;
            });
        $logger = new MonologLoggerAdapter($monolog);
        $logger->setPrefix('prefix ');
        $logger->log('test');
        $this->assertEquals('prefix test', $logMessage);
    }

    /**
     * @param int $logLevel
     * @param int $monologLogLevel
     * @dataProvider dataResolvesMonologLogLevel
     */
    public function testResolvesMonologLogLevel($logLevel, $expectedLogLevel)
    {
        $resolvedLogLevel = 0;
        $monolog = $this->getMockBuilder(MonologLogger::class)
            ->disableOriginalConstructor()
            ->setMethods(['log'])
            ->getMock();
        $monolog->expects($this->once())
            ->method('log')
            ->willReturnCallback(function ($level) use (&$resolvedLogLevel) {
                $resolvedLogLevel = $level;
            });
        $logger = new MonologLoggerAdapter($monolog);
        $logger->log('test', $logLevel);
        $this->assertEquals($resolvedLogLevel, $expectedLogLevel);
    }

    /**
     * @return array
     */
    public function dataResolvesMonologLogLevel()
    {
        return [
            'debug' => [
                LoggerInterface::LEVEL_DEBUG,
                MonologLogger::DEBUG,
            ],
            'info' => [
                LoggerInterface::LEVEL_INFO,
                MonologLogger::INFO,
            ],
            'warning' => [
                LoggerInterface::LEVEL_WARNING,
                MonologLogger::WARNING,
            ],
            'error' => [
                LoggerInterface::LEVEL_ERROR,
                MonologLogger::ERROR,
            ],
            'default' => [
                -1,
                MonologLogger::INFO,
            ],
        ];
    }
}
