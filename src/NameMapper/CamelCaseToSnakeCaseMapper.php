<?php

declare(strict_types=1);

namespace Brick\JsonMapper\NameMapper;

use Brick\JsonMapper\NameMapper\INameMapper;
use ReflectionParameter;

final class CamelCaseToSnakeCaseMapper implements INameMapper
{
    public function mapName(ReflectionParameter $constructorArgument): string
    {
        return preg_replace_callback(
            '/[A-Z]/',
            fn (array $matches) => '_' . strtolower($matches[0]),
            $constructorArgument->getName(),
        );
    }
}
