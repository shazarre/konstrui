<?php

namespace Konstrui\Logger;

use Colors\Color;

class StandardLoggerUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPrefix()
    {
        $prefix = new Color('prefix > ');
        $prefix = $prefix->fg('green');
        $logMessage = new Color('test log message');
        $logMessage = $logMessage->fg('green');
        $this->expectOutputString(
            sprintf(
                "INFO: %s%s\n",
                $prefix,
                $logMessage
            )
        );
        $logger = new StandardLogger();
        $logger->setPrefix('prefix > ');
        $logger->log('test log message');
    }

    /**
     * @param int $logLevel
     * @param int $monologLogLevel
     * @dataProvider dataResolvesMonologLogLevel
     */
    public function testMapsLogLevelToReadableName(
        $logLevel,
        $expectedPrefix,
        callable $colorizeCallback
    ) {
        $this->expectOutputString(
            sprintf(
                "%s: %s\n",
                $expectedPrefix,
                $colorizeCallback("test log message")
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
                function ($text) {
                    return $text;
                },
            ],
            'info' => [
                LoggerInterface::LEVEL_INFO,
                'INFO',
                function ($text) {
                    $color = new Color($text);

                    return $color->fg('green');
                },
            ],
            'warning' => [
                LoggerInterface::LEVEL_WARNING,
                'WARNING',
                function ($text) {
                    $color = new Color($text);

                    return $color->fg('yellow');
                },
            ],
            'error' => [
                LoggerInterface::LEVEL_ERROR,
                'ERROR',
                function ($text) {
                    $color = new Color($text);

                    return $color->bg('red')->fg('white');
                },
            ],
            'default' => [
                -1,
                'INFO',
                function ($text) {
                    $color = new Color($text);

                    return $color->fg('green');
                },
            ],
        ];
    }
}
