<?php

declare(strict_types=1);

namespace Brick\JsonMapper\NameMapper;

use Brick\JsonMapper\NameMapper\INameMapper;
use ReflectionParameter;

final class SnakeCaseToCamelCaseMapper implements INameMapper
{
    public function mapName(ReflectionParameter $constructorArgument): string
    {
        return preg_replace_callback(
            '/_([a-z])/',
            fn (array $matches) => strtoupper($matches[1]),
            $constructorArgument->getName(),
        );
    }
}
