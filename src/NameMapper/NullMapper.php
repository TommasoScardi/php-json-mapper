<?php

declare(strict_types=1);

namespace Brick\JsonMapper\NameMapper;

use Brick\JsonMapper\NameMapper\INameMapper;
use ReflectionParameter;

final class NullMapper implements INameMapper
{
    public function mapName(ReflectionParameter $constructorArgument): string
    {
        return $constructorArgument->getName();
    }
}
