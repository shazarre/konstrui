<?php

namespace Konstruu\Task;

class TaskAliasUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testCastingToString()
    {
        $alias = new TaskAlias('alias');
        $this->assertEquals('alias', (string) $alias);
    }

    public function testGetAlias()
    {
        $alias = new TaskAlias('alias');
        $this->assertEquals('alias', $alias->getAlias());
    }

    /**
     * @dataProvider dataThrowsExceptionWhenWrongAliasProvided
     * @expectedException \Konstruu\Exception\InvalidAliasException
     */
    public function testThrowsExceptionWhenWrongAliasProvided($alias)
    {
        new TaskAlias($alias);
    }

    /**
     * @return array
     */
    public function dataThrowsExceptionWhenWrongAliasProvided()
    {
        return [
            [
                '',
            ],
            [
                0,
            ],
            [
                -100,
            ],
            [
                100,
            ],
            [
                null,
            ],
            [
                new \stdClass(),
            ],
            [
                [],
            ],
            [
                [
                    'key' => 'value',
                ],
            ],
            [
                true,
            ],
            [
                false,
            ],
        ];
    }
}
