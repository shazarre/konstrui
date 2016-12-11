<?php

namespace Konstrui\Task;

class ComposerTaskUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataPerform
     */
    public function testPerform(
        $expectedCommand,
        $mode,
        $requireDev,
        $composerPath = ComposerTask::DEFAULT_PATH
    ) {
        $taskCommand = '';
        /** @var ComposerTask|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this->getMockBuilder(ComposerTask::class)
            ->setConstructorArgs(
                [
                    $mode,
                    $requireDev,
                    $composerPath,
                ]
            )
            ->setMethods(
                [
                    'executeCommand',
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
        $task->perform();
        $this->assertEquals($expectedCommand, $taskCommand);
    }

    /**
     * @return array
     */
    public function dataPerform()
    {
        return [
            'Install, requires dev, custom path' => [
                '/test/composer install --dev',
                ComposerTask::MODE_INSTALL,
                true,
                '/test/composer',
            ],
            'Update, requires dev, custom path' => [
                '/test/composer update --dev',
                ComposerTask::MODE_UPDATE,
                true,
                '/test/composer',
            ],
            'Install, does not require dev, custom path' => [
                '/test/composer install --no-dev',
                ComposerTask::MODE_INSTALL,
                false,
                '/test/composer',
            ],
            'Update, does not require dev, custom path' => [
                '/test/composer update --no-dev',
                ComposerTask::MODE_UPDATE,
                false,
                '/test/composer',
            ],
            'Install, requires dev, no custom path' => [
                'composer install --dev',
                ComposerTask::MODE_INSTALL,
                true,
            ],
            'Update, requires dev, no custom path' => [
                'composer update --dev',
                ComposerTask::MODE_UPDATE,
                true,
            ],
            'Install, does not require dev, no custom path' => [
                'composer install --no-dev',
                ComposerTask::MODE_INSTALL,
                false,
            ],
            'Update, does not require dev, no custom path' => [
                'composer update --no-dev',
                ComposerTask::MODE_UPDATE,
                false,
            ],
        ];
    }

    /**
     * @expectedException \Konstrui\Exception\TaskCreationException
     */
    public function testThrowsExceptionWhenInvalidModeProvided()
    {
        new ComposerTask('invalid');
    }
}
