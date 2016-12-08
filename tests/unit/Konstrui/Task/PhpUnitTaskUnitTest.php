<?php

namespace Konstrui\Task;

class PhpUnitTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataPerform
     */
    public function testPerform(
        $expectedCommand,
        $configurationPath = null,
        $phpUnitPath = null,
        $vendorPhpUnit = false
    ) {
        $taskCommand = '';
        /** @var PhpUnitTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(PhpUnitTask::class)
            ->setConstructorArgs(
                [
                    $configurationPath,
                    $phpUnitPath,
                ]
            )
            ->setMethods(
                [
                    'executeCommand',
                    'isVendorPhpUnitPresent',
                ]
            )
            ->getMock();
        $task->expects($this->once())
            ->method('executeCommand')
            ->willReturnCallback(function () use ($task, &$taskCommand) {
                $reflectionClass = new \ReflectionClass($task);
                $commandProperty = $reflectionClass->getProperty('command');
                $commandProperty->setAccessible(true);
                $taskCommand = $commandProperty->getValue($task);
            });
        $task->expects($this->any())
            ->method('isVendorPhpUnitPresent')
            ->willReturn((bool) $vendorPhpUnit);
        $task->perform();
        $this->assertEquals($expectedCommand, $taskCommand);
    }

    /**
     * @return array
     */
    public function dataPerform()
    {
        return [
            'Custom configuration and custom path' => [
                '/test/phpunit --configuration=/test/phpunit.xml',
                '/test/phpunit.xml',
                '/test/phpunit',
            ],
            'Custom configuration and no path provided, should use global one' => [
                'phpunit --configuration=/test/phpunit.xml',
                '/test/phpunit.xml',
            ],
            'Custom configuration and no path provided, should use vendor one' => [
                'vendor/bin/phpunit --configuration=/test/phpunit.xml',
                '/test/phpunit.xml',
                null,
                true,
            ],
            'No custom configuration, but path provided, vendor present' => [
                '/test/phpunit',
                null,
                '/test/phpunit',
                true,
            ],
            'No custom configuration, but path provided, vendor not present' => [
                '/test/phpunit',
                null,
                '/test/phpunit',
            ],
            'No configuration and no path provided, should use vendor one' => [
                'vendor/bin/phpunit',
                null,
                null,
                true,
            ],
        ];
    }
}
