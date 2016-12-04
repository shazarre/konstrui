<?php

namespace Konstrui\Definition\Provider;

use Konstrui\Definition\Definition;
use Konstrui\Exception\InvalidSchemaException;
use Konstrui\Task\TaskAlias;

/**
 * A PHP definition provider. Requires a '.konstrui.php' file returning an array
 * in following format:.
 *
 * <?php
 *
 * return [
 *     'tasks' => [
 *         'composer' => [ // task alias
 *             'task' => new \Konstrui\Task\ExecutableTask('composer install'),
 *         ],
 *         'tests' => [ // task alias
 *             'task' => new \Konstrui\Task\ExecutableTask(
 *                 './vendor/bin/phpunit --configuration=tests/phpunit.xml'
 *             ),
 *             // optional array of dependencies (referenced by aliases)
 *             'dependencies' => [
 *                 'composer',
 *             ],
 *         ],
 *     ],
 * ];
 */
class PhpProvider implements ProviderInterface
{
    const PHP_BUILD_FILE = '.konstrui.php';

    /**
     * @var array
     */
    protected $schema = [];

    /**
     * PhpProvider constructor.
     *
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }

    /**
     * {@inheritdoc}
     */
    public function provideDefinition()
    {
        $definition = new Definition();

        if (!empty($this->schema['tasks'])) {
            foreach ($this->schema['tasks'] as $alias => $schema) {
                if (!array_key_exists('task', $schema)) {
                    throw new InvalidSchemaException();
                }

                $definition->addTask(new TaskAlias($alias), $schema['task']);

                if (array_key_exists('dependencies', $schema)) {
                    foreach ($schema['dependencies'] as $dependency) {
                        $definition->addDependency(new TaskAlias($alias), new TaskAlias($dependency));
                    }
                }

                if (array_key_exists('description', $schema)) {
                    $definition->setDescription(new TaskAlias($alias), $schema['description']);
                }
            }
        }

        return $definition;
    }
}
