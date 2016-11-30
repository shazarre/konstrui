<?php

namespace Konstruu\Definition\Provider;

use Konstruu\Definition\Definition;
use Konstruu\Task\TaskAlias;
use Konstruu\Task\TaskInterface;

class PhpProviderUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEmptyDefinition()
    {
        $provider = new PhpProvider([]);
        $this->assertEquals(new Definition(), $provider->provideDefinition());
    }

    /**
     * @param array $schema
     * @dataProvider dataThrowsExceptionOnInvalidDefinition
     * @expectedException \Konstruu\Exception\InvalidSchemaException
     */
    public function testThrowsExceptionOnInvalidDefinition($schema)
    {
        $provider = new PhpProvider($schema);
        $provider->provideDefinition();
    }

    public function dataThrowsExceptionOnInvalidDefinition()
    {
        return [
            [
                [
                    'tasks' => [
                        'alias' => [
                            'invalid',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array    $schema
     * @param callable $expectedDefinitionCallback
     * @dataProvider dataProvideDefinition
     */
    public function testProvideDefinition($schema, $expectedDefinitionCallback)
    {
        $provider = new PhpProvider($schema);
        $this->assertEquals($expectedDefinitionCallback(), $provider->provideDefinition());
    }

    public function dataProvideDefinition()
    {
        return [
            [
                [
                    'tasks' => [
                        'alias' => [
                            'task' => $this->getMockForAbstractClass(TaskInterface::class),
                        ],
                    ],
                ],
                function () {
                    $definition = new Definition();
                    $definition->addTask(new TaskAlias('alias'), $this->getMockForAbstractClass(TaskInterface::class));

                    return $definition;
                },
            ],
            [
                [
                    'tasks' => [
                        'alias' => [
                            'task' => $this->getMockForAbstractClass(TaskInterface::class),
                        ],
                        'another-alias' => [
                            'task' => $this->getMockForAbstractClass(TaskInterface::class),
                            'dependencies' => [
                                'alias',
                            ],
                        ],
                    ],
                ],
                function () {
                    $definition = new Definition();
                    $definition->addTask(new TaskAlias('alias'), $this->getMockForAbstractClass(TaskInterface::class));
                    $definition->addTask(new TaskAlias('another-alias'), $this->getMockForAbstractClass(TaskInterface::class));
                    $definition->addDependency(new TaskAlias('another-alias'), new TaskAlias('alias'));

                    return $definition;
                },
            ],
        ];
    }
}
