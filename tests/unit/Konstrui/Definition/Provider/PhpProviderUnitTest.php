<?php

namespace Konstrui\Definition\Provider;

use Konstrui\Definition\Definition;
use Konstrui\Task\CompoundTaskInterface;
use Konstrui\Task\TaskAlias;
use Konstrui\Task\TaskInterface;

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
     * @expectedException \Konstrui\Exception\InvalidSchemaException
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
     * @param callable $definitionCallback
     * @dataProvider dataProvideDefinition
     */
    public function testProvideDefinition($schema, $definitionCallback)
    {
        $provider = new PhpProvider($schema);
        $this->assertEquals($definitionCallback(), $provider->provideDefinition());
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
                            'description' => 'Test description',
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
                    $definition->setDescription(new TaskAlias('alias'), 'Test description');

                    return $definition;
                },
            ],
        ];
    }

    public function testProvideDefinitionWithCompoundTask()
    {
        /** @var CompoundTaskInterface|PHPUnit_Framework_MockObject_MockObject */
        $compoundTask = $this->getMockForAbstractClass(
            CompoundTaskInterface::class
        );
        $compoundTask->expects($this->once())
            ->method('getTaskAliases')
            ->willReturn(
                [
                    'compound-task-alias',
                    'another-compound-task-alias',
                ]
            );
        $expectedDefinition = new Definition();
        $expectedDefinition->addTask(
            new TaskAlias('compound-task-alias'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $expectedDefinition->addTask(
            new TaskAlias('another-compound-task-alias'),
            $this->getMockForAbstractClass(TaskInterface::class)
        );
        $expectedDefinition->addTask(new TaskAlias('compound'), $compoundTask);
        $expectedDefinition->addDependency(
            new TaskAlias('compound'),
            new TaskAlias('compound-task-alias')
        );
        $expectedDefinition->addDependency(
            new TaskAlias('compound'),
            new TaskAlias('another-compound-task-alias')
        );
        $provider = new PhpProvider(
            [
                'tasks' => [
                    'compound-task-alias' => [
                        'task' => $this->getMockForAbstractClass(
                           TaskInterface::class
                        ),
                    ],
                    'another-compound-task-alias' => [
                        'task' => $this->getMockForAbstractClass(
                            TaskInterface::class
                        ),
                    ],
                    'compound' => [
                        'task' => $compoundTask,
                    ],
                ],
            ]
        );
        $this->assertEquals($expectedDefinition, $provider->provideDefinition());
    }

    /**
     * @expectedException \Konstrui\Exception\TaskNotFoundException
     */
    public function testThrowsExceptionOnNonExisitingCompoundTask()
    {
        /** @var CompoundTaskInterface|PHPUnit_Framework_MockObject_MockObject */
        $compoundTask = $this->getMockForAbstractClass(
            CompoundTaskInterface::class
        );
        $compoundTask->expects($this->once())
            ->method('getTaskAliases')
            ->willReturn(
                [
                    'compound-task-alias',
                    'another-compound-task-alias',
                ]
            );
        $provider = new PhpProvider(
            [
                'tasks' => [
                    'compound-task-alias' => [
                        'task' => $this->getMockForAbstractClass(
                           TaskInterface::class
                        ),
                    ],
                    'compound' => [
                        'task' => $compoundTask,
                    ],
                ],
            ]
        );
        $provider->provideDefinition();
    }
}
