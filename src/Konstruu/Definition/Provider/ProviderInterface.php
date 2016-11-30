<?php

namespace Konstruu\Definition\Provider;

use Konstruu\Definition\DefinitionInterface;
use Konstruu\Exception\InvalidSchemaException;

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
