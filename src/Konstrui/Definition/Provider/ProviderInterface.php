<?php

namespace Konstrui\Definition\Provider;

use Konstrui\Definition\DefinitionInterface;
use Konstrui\Exception\InvalidSchemaException;

/**
 * Definition provider.
 *
 * Needs to return a Definition object that will be further used by Resolver
 * and Runner to run given build target.
 */
interface ProviderInterface
{
    /**
     * @throws InvalidSchemaException
     *
     * @return DefinitionInterface
     */
    public function provideDefinition();
}
